<?php
/**
 * Created by PhpStorm.
 * User: jacky-fu
 * Date: 2018/4/26
 * Time: 15:41
 */
namespace app\api\controller;
use app\newapp\model\RechargeCart;
use think\Controller;
use think\Db;
header('Access-Control-Allow-Origin:*');
class Api extends Controller{
    private $public_key='c5bd3fa331e9a8626c2b95d6e363d417';   //定义一个自定义的密钥，以后这个是可以修改的变量
    //所有平台调用的支付接口
    public function apiPay(){
        /*定义支付接口参数
        parm &key       密钥  string   c5bd3fa331e9a8626c2b95d6e363d417
        parm &money     金额  float
        parm &ras2      版本号  2.0
        parm &charset   字符集  utf8
        parm &sign_type 字符集  md5
        parm &sign      签名 string
        parm &user_id      用户名  int
        parm &goods      商品名称   string
        parm &user_id      消费的店名称  string
        parm &shopping      购物  string
        parm &online      门店消费  string
        parm &tea_type      消费类型
        */
        //$data= file_get_contents('php://input');
        $data=input('post.');
        $data= json_encode($data);
        Db::table('tea_test')->insert(['test'=>$data,'trade_status'=>'测试接口']);
        $arr=json_decode($data,true);
        //判断接口是否为非法请求
        if(empty($arr['money']) || empty($arr['user_id']) ||empty($arr['sign'])  ||  empty($arr['trade_no'])){
            $data=array(
                'status'=>0,
                'msg'=>'非法请求，请住手'
            );
            return json($data);
        }
        //判断订单是否已经支付
        $orderInfo=Db::table('tea_integral_log')->where('trade_no',$arr['trade_no'])->count();
        if($orderInfo > 0) {
            $data=array(
                'status'=>0,
                'msg'=>'该订单已经支付'
            );
            return json($data);
        }

        $user_id=intval($arr['user_id']);
        $signData=array(
            "mykey"=>$this->public_key,
            'myras2' =>'2.0',
            'mycharset' =>'utf8',
            'money'=>$arr['money'],
            'mysign_type' =>'md5',
            'trade_no'  =>$arr['trade_no'],
            'user_id'=>$user_id
        );

        $mysign=$this->sign($signData);
        //获取传递过来的签名，判断签名是否正确
        $sign=$arr['sign'];
        if($sign!=$mysign){
            $data=array(
                'status'=>0,
                'msg'=>'签名错误'
            );
            return json($data);
        }
        //判断用户是否存在
        $user_res = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->find();
        if(!$user_res){
            $data=array(
                'status'=>0,
                'msg'=>'用户不存在'
            );
            return json($data);
        }
        //判断用户是否在我们数据库存在
        $user_info = Db::name("user")->where("user_id",$user_id)->find();
        if(!$user_info){
            $data=array(
                'status'=>0,
                'msg'=>'非国茶理茶宝账号无法购买'
            );
            return json($data);
        }
        //获取传递过来的需要支付的金额
        $teaSorts=$arr['money'];   //传递过来的消费金额
        $trade_no=$arr['trade_no'];  //传递过来的订单号
        $goodsName=$arr['goods_name'];         //传递过来的消费商品名称
        $store=$arr['store_name'];        //线下门店消费
        $shopping=$arr['shopping'];        //购物
        $online=$arr['online'];        //线下门店消费
        //显示商品的消费详情
        $str=empty($goodsName) ? $store."门店消费" : $goodsName;
        $users=Db::table('tea_user')->field('tea_ponit_inte,tea_inte')->where('user_id',$user_id)->find();
        $userMoneys=floatval($users['tea_ponit_inte'])+floatval($users['tea_inte']);
        //用户的茶点茶券不足以支付
        if($teaSorts >$userMoneys ){
            $data=array(
                'status'=>0,
                'msg'=>'用户的茶点茶券不足'
            );
            return json($data);
        }
        //在入口判断是品台还是收银支付，平台需要判断是茶点还是茶券支付
        if(intval($arr['shopping'])==1) {
            //平台支付,需要判断为什么支付
            if (intval($arr['tea_type']) == 1) {
                //茶点支付
                //判断茶点金额是否足够支付
                if (floatval($users['tea_ponit_inte']) < $teaSorts) {
                    $data = array(
                        'status' => 0,
                        'msg' => '用户的茶点不足'
                    );
                    return json($data);
                } else {
                    //使用茶点进行消费
                    $orderInfo = Db::table('tea_integral_log')->where('trade_no', $arr['trade_no'])->count();
                    if ($orderInfo > 0) {

                    } else {
                    Db::table('tea_user')->where('user_id', $user_id)->setDec('tea_ponit_inte', $teaSorts);
                    //Db::table('tea_integral')->where('user_id',$user_id)->setDec('tea_ponit_inte',$teaSorts);



                        $logData = array(
                            'user_id' => $user_id,
                            'introduce' => $str,
                            'surplus_inte' => '-' . $teaSorts,
                            'tea_ponit_inte' => '-' . $teaSorts,
                            'tea_inte' => '+0',
                            'use_type' => 2,
                            'shopping' => $shopping,
                            'online' => $online,
                            'addtime' => time(),
                            'year' => date('Y'),
                            'month' => date('m'),
                            'day' => date('d'),
                            'trade_no' => $trade_no,
                            'log_out_trade_no' => date('Y') . date('m') . date('d') . uniqid() . '168'
                        );
                        Db::table('tea_integral_log')->insert($logData);
                    }
                }
            } else {
                //茶券支付
                if (floatval($users['tea_inte']) < $teaSorts) {
                    $data = array(
                        'status' => 0,
                        'msg' => '用户的茶券不足'
                    );
                    return json($data);
                } else {
                    //使用茶点进行消费
                    $orderInfo = Db::table('tea_integral_log')->where('trade_no', $arr['trade_no'])->count();
                    if ($orderInfo > 0) {

                    } else {
                    Db::table('tea_user')->where('user_id', $user_id)->setDec('tea_inte', $teaSorts);
                    //Db::table('tea_integral')->where('user_id',$user_id)->setDec('tea_inte',$teaSorts);



                        $logData = array(
                            'user_id' => $user_id,
                            'introduce' => $str,
                            'surplus_inte' => '-' . $teaSorts,
                            'tea_inte' => '-' . $teaSorts,
                            'tea_ponit_inte' => '+0',
                            'use_type' => 2,
                            'shopping' => $shopping,
                            'online' => $online,
                            'addtime' => time(),
                            'year' => date('Y'),
                            'month' => date('m'),
                            'day' => date('d'),
                            'trade_no' => $trade_no,
                            'log_out_trade_no' => date('Y') . date('m') . date('d') . uniqid() . '168'
                        );
                        Db::table('tea_integral_log')->insert($logData);
                    }
                }
            }

            //支付成功之后的操作，需要加入到数据库，通过传递过来的订单号去平台数据库查找订单的信息
            $order_data = Db::connect(config('db_config2'))->name("order_info")->where("order_sn='$trade_no'")->field('order_id')->find();
            //先入库到我们自己数据库
            $o_id = intval($order_data['order_id']);
            $num_orer = Db::table('tea_order')->where("order_id = $o_id")->count();
            if ($num_orer > 0) {

            } else {

            $order_datas = array(
                'user_id' => $user_id,
                'total_price' => $teaSorts,
                'actual_price' => $teaSorts,
                'order_num' => $trade_no,
                'trade_no' => $logData['log_out_trade_no'],
                'pay_way' => intval($arr['tea_type']),
                'is_send' => 0,
                'order_id' => intval($order_data['order_id']),
            );
            //添加订单
            Db::table('tea_order')->insert($order_datas);
            //查询出订单的商品的信息
            $order_goods_data = Db::connect(config('db_config2'))
                ->name("order_goods")
                ->field('goods_number,goods_price,goods_id')
                ->where('order_id', intval($order_data['order_id']))
                ->select();
            foreach ($order_goods_data as $v) {
                $goods_datas = Db::connect(config('db_config2'))->name("goods")->where('goods_id', intval($v['goods_id']))->find();
                $order_goods_datas = array(
                    'order_id' => intval($order_data['order_id']),
                    'goods_id' => intval($v['goods_id']),
                    'good_number' => intval($v['goods_number']),
                    'over_status' => 0,
                    'is_third' => 0,
                    'goods_name' => $goods_datas['goods_name'],
                    'goods_img' => $goods_datas['goods_img'],
                    'goods_thumb' => $goods_datas['goods_thumb'],
                    'goods_detail' => $goods_datas['goods_desc'],
                    'goods_price' => floatval($v['goods_price']),
                    'pay_way' => intval($arr['tea_type']),

                );
                //将订单的商品信息入库
                Db::table('tea_order_cart')->insert($order_goods_datas);
            }
        }
            $data=array(
                'status'=>1,
                'trade_no'=>$trade_no,
                'msg'=>'支付成功',
                'money'=>$teaSorts,
                'out_trade_no'=>$logData['log_out_trade_no']
            );
            return json($data);
        }else{

            //收银 的支付
            /*有两种情况需要加以判断
            ①茶点大于消费金额
            ②茶点不够需要扣除相应的茶券
        */
            $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
           if(floatval($users['tea_ponit_inte']) >=$teaSorts){
                    Db::table('tea_user')->where('user_id',$user_id)->setDec('tea_ponit_inte',$teaSorts);
                   // Db::table('tea_integral')->where('user_id',$user_id)->setDec('tea_ponit_inte',$teaSorts);
                    $logData=array(
                        'user_id'=>$user_id,
                        'introduce'=>$str,
                        'surplus_inte'=>'-'.$teaSorts,
                        'tea_ponit_inte'=>'-'.$teaSorts,
                        'tea_inte'=>'+0',
                        'use_type'=>2,
                        'shopping'    =>$shopping,
                        'online'    =>$online,
                        'addtime'   =>time(),
                        'year'=>date('Y'),
                        'month'=>date('m'),
                        'day'=>date('d'),
                        'log_out_trade_no'=>$log_out_trade_no,
                        'trade_no'=>$trade_no
                    );
                    Db::table('tea_integral_log')->insert($logData);
                }else{
                   Db::table('tea_user')->where('user_id',$user_id)->setField('tea_ponit_inte',0);
                   Db::table('tea_user')->where('user_id',$user_id)->setDec('tea_inte',floatval($teaSorts-floatval($users['tea_ponit_inte'])));
                   //Db::table('tea_integral')->where('user_id',$user_id)->setDec('tea_ponit_inte',$teaSorts);
                   $logData=array(
                       'user_id'=>$user_id,
                       'introduce'=>$str,
                       'surplus_inte'=>'-'.$teaSorts,
                       'tea_ponit_inte'=>'-'.floatval($users['tea_ponit_inte']),
                       'tea_inte'=>'-'.floatval($teaSorts-floatval($users['tea_ponit_inte'])),
                       'use_type'=>2,
                       'shopping'    =>$shopping,
                       'online'    =>$online,
                       'addtime'   =>time(),
                       'year'=>date('Y'),
                       'month'=>date('m'),
                       'day'=>date('d'),
                       'log_out_trade_no'=>$log_out_trade_no,
                       'trade_no'=>$trade_no
                   );
                   Db::table('tea_integral_log')->insert($logData);

                }
            $data=array(
                'status'=>1,
                'trade_no'=>$trade_no,
                'msg'=>'支付成功',
                'money'=>$teaSorts,
                'out_trade_no'=>$log_out_trade_no
            );
            return json($data);

        }
    }

    //制造签名
    private function sign($data){
        $str='';
        ksort($data);
        foreach ($data as $key => $value) {
            $str .= "{$key}={$value}"."&";
        }
        $str=md5($str);
        $str=strtoupper($str);
        return $str;
    }

    //给出来的线下支付的功能理茶宝
    public function nmanage(){
        $recharge_id=intval(input('post.id'));
        $con=new RechargeCart();
        $recharge_res=$con->updateRec($recharge_id);
        if($recharge_res){
            $data['status']=1;
            $data['msg']='操作成功';
            return json($data);
        }else{
            $data['status']=0;
            $data['msg']='操作失败';
            return json($data);
        }
    }
    //给出来的线下取消的接口理茶宝
    public function delmanage(){
        $recharge_num=intval(input('post.recharge_num'));
        $recharge=Db::table('tea_recharge_cart')->field('user_id,id')->where('recharge_num',$recharge_num)->find();
        $con=new RechargeCart();
        $recharge_res=$con->del(intval($recharge['user_id']),intval($recharge['id']));
        if($recharge_res){
            $data['status']=1;
            $data['msg']='操作成功';
            return json($data);
        }else{
            $data['status']=0;
            $data['msg']='操作失败';
            return json($data);
        }
    }
    //给出来的线下支付的功能钱包
    public function money(){
        $money_id=intval(input('post.id'));
        $money_data=Db::table('tea_money')->where('money_id',$money_id)->find();
        if(!$money_data){
            //订单错误
            return json(array('status'=>0));
        }
        if(intval($money_data['pay_status']) != 0){
            //订单已经支付
            return json(array('status'=>0));
        }
        $data=array(
            'pay_way'=>3,
            'pay_status'=>1,
            'trade_no'=>date('Y').date('m').date('d').uniqid().'168',
            'trade_beizhu'=>'线下支付成功'
        );
        $logData=array(
            'user_id'=>intval($money_data['user_ids']),
            'surplus_inte'=>'+'.floatval($money_data['montys']),
            'introduce'=>"我的钱包充值".floatval($money_data['montys']),
            'wallet'=>1,
            'sum_inte'=>floatval($money_data['montys']),
            'addtime'=>time(),
            'year'=>date('Y'),
            'month'=>date('m'),
            'day'=>date('d'),
            'log_out_trade_no'=>date('Y').date('m').date('d').uniqid().'168'
        );

        //启动事务
        Db::startTrans();
        try{
            Db::table('tea_integral_log')->insert($logData);   //写入记录表
            //支付成功后更新数据库
            Db::table('tea_user')->where('user_id',intval($money_data['user_ids']))->setInc('wallet',floatval($money_data['montys']));
            Db::table('tea_money')->where("money_id", $money_id)->setField($data);   //支付成功，修改钱包表

            // 提交事务
            Db::commit();
            return json(array('status'=>1));
        }catch (\Exception $e){
            //事务回滚
            Db::rollback();
            return json(array('status'=>0));
        }

    }
    //给出来的线下取消的功能钱包
    public function delmoney(){
        $money_id=intval(input('post.id'));
        $money_data=Db::table('tea_money')->where('money_id',$money_id)->find();
        if(!$money_data){
            //订单错误
            return json(array('status'=>0));
        }
        $res=Db::table('tea_money')->where('money_id',$money_id)->delete();
        if($res){
            return json(array('status'=>1));
        }else{
            return json(array('status'=>0));
        }
    }
    //给出来的线下寄售系统
    public function thirdSale(){
        $cart_id=intval(input('post.order_num'));
        //要获得用户ID，订单ID，以及商品ID
        $user_id=!empty(input('post.user_id')) ? intval(input('post.user_id')):session('user_id');
        //判断该订单是否已经付款过
        $payStatus=Db::table('tea_order_cart')->where('cart_id',$cart_id)->field('pay_way,order_id,goods_id,times')->find();
        if($payStatus){
            if(intval($payStatus['pay_way']) ==2){
                if((time()-strtotime($payStatus['times'])) >=99999986400) return 9;
                //更改成挂卖第三方状态
                Db::table('tea_order_cart')->where('cart_id',$cart_id)->setField('is_third',1);
                //更改平台数据
                Db::connect(config('db_config2'))->name("order_info")->where('order_id',intval($payStatus['order_id']))->update(['order_status'=>5,'shipping_status'=>2]);
                //已经支付过则可以挂卖到第三方
                $goods= Db::connect(config('db_config2'))->name("goods")->where('goods_id',intval($payStatus['goods_id']))->find();
                $data=array(
                    'goods_name'=>$goods['goods_name'],
                    'goods_sn'=>$goods['goods_sn'],
                    'cate_id'=>$goods['cat_id'],
                    'price'=>floatval($goods['shop_price']),
                    'goods_connect'=>$goods['goods_desc'],
                    'addtime'=>time(),
                    'jy_goods_id'=>$goods['goods_id'],
                    'goods_from'=>'',
                    'goods_img'=>$goods['goods_id'],
                    'poem'=>'',
                    'goods_lev' =>0,
                    'saletime'=>time(),
                );
                //$gc_goods = M('Home/Goods')->db(1,'DBConf1')->table('guocha_goods')->add($data);
                $gc_goods=Db::connect(config('db_config3'))->name("goods")->insertGetId($data);
                //$user_id = M('Home/Goods')->field('id')->db(1,'DBConf1')->table('guocha_user')->where("username = '$username'")->find();
                $data2=array('goods_id'=>$gc_goods,'user_id'=>$user_id,'state'=>2);
                Db::connect(config('db_config3'))->name("state")->insert($data2);
                return 1;
            }else{
                return 2;
            }
        }else{
            return 0;
        }
    }
    //给出来的付款码ajax查询系统
    public  function payCode(){
        $key=input('post.key');
        //$key=file_get_contents('php://input') ;
        //return $key;
        //判断传递过来的是不是违法的 长度为32位
        //if(empty($key)  || strlen($key) !=32){
        //    return 0;
        //}
        $cc1 = array('sms_num'=>$key,'sms_type'=>2);
        Db::table('tea_sms')->insert($cc1);
         $code_res=Db::table('tea_session')->where('key_s',$key)->find();
        //$code_res = Db::query("select * from tea_session where key_s = '$key'")[0];
        //dump($code_res);
        //①首先判断存不存在
        if($code_res){
            $cc = array('sms_num'=>$key,'sms_type'=>1);
            Db::table('tea_sms')->insert($cc);
            //存在
            //②判断是不是失效
            if(time() > intval($code_res['overtime'])){
                //二维码已经失效，过了有效期
                return 0;
            }else{
                return json(array('user_id'=>$code_res['user_id'],'key_s'=>$key,'is_ceo'=>$code_res['is_ceo']));
            }
        }else{
            //不存在
            return 0;
        }
    }
    //代人注册的公共文件
    public function otherreg(){
        $username = input('post.username');
        $password1 = input('post.password1');
        $password2 = input('post.password2');
        $name = input('post.name');
        $user_id=session('user_id');
        $user_rank=intval(input('post.user_rank'));
        if($password1 != $password2) return json(array('status'=>3));
        $tm_salt = rand(0000,9999);
        $password = md5(md5($password1).$tm_salt);   //密码
        $data = array(
            'user_name' => $username,
            'nick_name' => $name,
            'password' => $password,
            'parent_id' => $user_id,
            'tm_salt' => $tm_salt,      //盐
            'reg_time' => time(),   //注册时间
            'email' => $username.'@qq.com',   //注册时间
            'user_rank'=>9         //会员等级是理茶宝会员
        );
        //插入到数据库，并且返回他的user_id
        $res = Db::connect(config('db_config2'))->name("users")->insertGetId($data);
        $data2 = array(
            'user_id'=>$res,
            'wait'=>1,
        );
        $res2 = Db::table('tea_user')->insert($data2);
        if($res2){
            return json(array('msg'=>"注册成功",'status'=>1));
        }else{
            return json(array('msg'=>"内部维护中",'status'=>2));
        }
    }
    //判断用户名是否存在
    public function checkUser(){
        $username = input('post.username');
        $user_info = Db::connect(config('db_config2'))->name("users")->where("user_name",$username)->find();
        if(!empty($user_info)){
            return json_encode(array('data'=>"用户名已经被使用",'status'=>0));
        }
    }
    //新版的支付接口API
    /*定义支付接口参数
     parm &key       密钥  string   c5bd3fa331e9a8626c2b95d6e363d417
     parm &money     金额  float
     parm &ras2      版本号  2.0
     parm &charset   字符集  utf8
     parm &sign_type 字符集  md5
     parm &sign      签名 string
     parm &user_id      用户名  int
     parm &goods      商品名称   string
     parm &user_id      消费的店名称  string
     parm &shopping      购物  string
     parm &online      门店消费  string
     parm &tea_type      消费类型
     */
    public function newApi(){
        $data=input('post.');
        $data= json_encode($data);
        Db::table('tea_test')->insert(['test'=>$data,'trade_status'=>'测试接口']);
        $arr=json_decode($data,true);
        //判断接口是否为非法请求
        if(empty($arr['money']) || empty($arr['user_id']) ||empty($arr['sign'])  ||  empty($arr['trade_no'])){
            $data=array(
                'status'=>0,
                'msg'=>'非法请求，请住手'
            );
            return json($data);
        }
        //判断订单是否已经支付
        $orderInfo=Db::table('tea_integral_log')->where('trade_no',$arr['trade_no'])->find();
        if($orderInfo) {
            $data=array(
                'status'=>0,
                'msg'=>'该订单已经支付'
            );
            return json($data);
        }
        $user_id=intval($arr['user_id']);
        $signData=array(
            "mykey"=>$this->public_key,
            'myras2' =>'2.0',
            'mycharset' =>'utf8',
            'money'=>$arr['money'],
            'mysign_type' =>'md5',
            'trade_no'  =>$arr['trade_no'],
            'user_id'=>$user_id
        );
        $mysign=$this->sign($signData);
        //获取传递过来的签名，判断签名是否正确
        $sign=$arr['sign'];
        if($sign!=$mysign){
            $data=array(
                'status'=>0,
                'msg'=>'签名错误'
            );
            return json($data);
        }
        //判断用户是否存在
        $user_res = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->find();
        if(!$user_res){
            $data=array(
                'status'=>0,
                'msg'=>'用户不存在'
            );
            return json($data);
        }
        //获取传递过来的需要支付的金额
        $teaSorts=$arr['money'];   //传递过来的消费金额
        $trade_no=$arr['trade_no'];  //传递过来的订单号
        $goodsName=$arr['goods_name'];         //传递过来的消费商品名称
        $store=$arr['store_name'];        //线下门店消费
        $shopping=$arr['shopping'];        //购物
        $online=$arr['online'];        //线下门店消费
        //显示商品的消费详情
        $str=empty($goodsName) ? $store."门店消费" : $goodsName;
        $users=Db::table('tea_user')->field('tea_ponit_inte,tea_inte')->where('user_id',$user_id)->find();
        $userMoneys=floatval($users['tea_ponit_inte'])+floatval($users['tea_inte']);
        //用户的茶点茶券不足以支付
        if($teaSorts >$userMoneys ){
            $data=array(
                'status'=>0,
                'msg'=>'用户的茶点茶券不足'
            );
            return json($data);
        }
        //在入口判断是茶点支付还是茶券支付
        if(intval($arr['shopping'])==1){
            //平台支付,需要判断为什么支付
            if(intval($arr['tea_type'])==1){
                //茶点支付
                //判断茶点金额是否足够支付
                if(floatval($users['tea_ponit_inte']) < $teaSorts){
                    $data=array(
                        'status'=>0,
                        'msg'=>'用户的茶点不足'
                    );
                    return json($data);
                }else{
                    //使用茶点进行消费
                    Db::table('tea_user')->where('user_id',$user_id)->setDec('tea_ponit_inte',$teaSorts);   //扣除会员的的信息
                    //需要判断积分表的积分是否足够支付，如果不够那就需要去处理多个积分表
                    $integral_data= Db::table('tea_integral')->field('id')->where('user_id',$user_id)->where('tea_ponit_inte','>',$teaSorts)->find();
                    //如果有存在单个记录满足就直接处理
                    if($integral_data){
                        Db::table('tea_integral')->where('user_id',$user_id)->where('id',$integral_data['id'])->setDec('tea_ponit_inte',$teaSorts);//处理相对应的表的记录
                    }else{
                        $integral_datas=Db::table('tea_integral')->field('id,tea_ponit_inte')
                            ->where('user_id',$user_id)
                            ->where('is_return',1)->order('id desc')->select();//查找用户下对应的所有的积分表
                        $count_moneys=0;
                        foreach ($integral_datas as $v){

                        }
                    }

                    $logData=array(
                        'user_id'=>$user_id,
                        'introduce'=>$str,
                        'surplus_inte'=>'-'.$teaSorts,
                        'tea_ponit_inte'=>'-'.$teaSorts,
                        'use_type'=>2,
                        'shopping'    =>$shopping,
                        'online'    =>$online,
                        'addtime'   =>time(),
                        'year'=>date('Y'),
                        'month'=>date('m'),
                        'day'=>date('d'),
                        'trade_no'=>$trade_no,
                        'log_out_trade_no'=>date('Y').date('m').date('d').uniqid().'168'
                    );
                    Db::table('tea_integral_log')->insert($logData);
                }
            }else{
                //茶券支付
                if(floatval($users['tea_inte']) < $teaSorts){
                    $data=array(
                        'status'=>0,
                        'msg'=>'用户的茶券不足'
                    );
                    return json($data);
                }else{
                    //使用茶点进行消费
                    Db::table('tea_user')->where('user_id',$user_id)->setDec('tea_inte',$teaSorts);
                    Db::table('tea_integral')->where('user_id',$user_id)->setDec('tea_inte',$teaSorts);
                    $logData=array(
                        'user_id'=>$user_id,
                        'introduce'=>$str,
                        'surplus_inte'=>'-'.$teaSorts,
                        'tea_inte'=>'-'.$teaSorts,
                        'use_type'=>2,
                        'shopping'    =>$shopping,
                        'online'    =>$online,
                        'addtime'   =>time(),
                        'year'=>date('Y'),
                        'month'=>date('m'),
                        'day'=>date('d'),
                        'trade_no'=>$trade_no,
                        'log_out_trade_no'=>date('Y').date('m').date('d').uniqid().'168'
                    );
                    Db::table('tea_integral_log')->insert($logData);
                }
            }

            //支付成功之后的操作，需要加入到数据库，通过传递过来的订单号去平台数据库查找订单的信息
            $order_data= Db::connect(config('db_config2'))->name("order_info")->where("order_sn='$trade_no'")->field('order_id')->find();
            //先入库到我们自己数据库
            $order_datas=array(
                'user_id'=>$user_id,
                'total_price'=>$teaSorts,
                'actual_price'=>$teaSorts,
                'order_num'=>$trade_no,
                'trade_no'=>$logData['log_out_trade_no'],
                'pay_way'=>intval($arr['tea_type']),
                'is_send'=>0,
                'order_id'=>intval($order_data['order_id']),
            );
            //添加订单
            Db::table('tea_order')->insert($order_datas);
            //查询出订单的商品的信息
            $order_goods_data=Db::connect(config('db_config2'))->name("order_goods")->field('goods_number,goods_price,goods_id')->where('order_id',intval($order_data['order_id']))->select();
            foreach ($order_goods_data as $v){
                $goods_datas=Db::connect(config('db_config2'))->name("goods")->where('goods_id',intval($v['goods_id']))->find();
                $order_goods_datas=array(
                    'order_id'=>intval($order_data['order_id']),
                    'goods_id'=>intval($v['goods_id']),
                    'good_number'=>intval($v['goods_number']),
                    'over_status'=>0,
                    'is_third'=>0,
                    'goods_name'=>$goods_datas['goods_name'],
                    'goods_img'=>$goods_datas['goods_img'],
                    'goods_thumb'=>$goods_datas['goods_thumb'],
                    'goods_detail'=>$goods_datas['goods_desc'],
                    'goods_price'=>floatval($v['goods_price']),
                    'pay_way'=>intval($arr['tea_type']),

                );
                //将订单的商品信息入库
                Db::table('tea_order_cart')->insert($order_goods_datas);
            }
            $data=array(
                'status'=>1,
                'trade_no'=>$trade_no,
                'msg'=>'支付成功',
                'money'=>$teaSorts,
                'out_trade_no'=>$logData['log_out_trade_no']
            );
            return json($data);
        }else{
            //收银系统的支付

        }
    }

    public function testa(){
    $user_id=1;
    $teaSorts=30;
    $integral_datas=Db::table('tea_integral')->field('id,tea_ponit_inte')->where('user_id',$user_id)->where('is_return',1)->order('id desc')->select();//查找用户下对应的所有的积分表
    $count_moneys=0;
    foreach ($integral_datas as $v){
        $count_moneys += ($teaSorts-$v['tea_ponit_inte']);
        dump($count_moneys);
    }
}

    public function index(){
        return $this->fetch();
    }
    //发送短信
    public function sendCode(){
        $code=input('post.code');
        //获取传输过来的手机号码
        $tel = input('post.tel');
        include_once('../Api/top/TopClient.php');
        $c = new \TopClient;
        $c->appkey = '23662994';
        $c->secretKey = '12c4693b91926a394e8ca913e132be01';
        vendor('top.request.AlibabaAliqinFcSmsNumSendRequest');
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("我的茶馆");
        $req->setSmsParam("{\"code\":\"$code\",\"product\":\"测试\"}");
        $req->setRecNum("$tel");//电话号码
        $req->setSmsTemplateCode("SMS_62170183");
        $resp = $c->execute($req);
        if($resp->result->msg){
            return json(array('s'=>1,'msg'=>'短信发送成功'));
        }else{
            return json(array('s'=>0,'msg'=>'短信发送失败'));
        }
    }

    /*

    //股东的定时任务返回的时间
    public function everyMonthReturnTeapoint(){
        $query=Db::query('select * FROM tea_integral b join (select * from tea_ceo_integral_log a join (select max(int_id) as ids from tea_ceo_integral_log GROUP BY integral_id) as t on t.ids = a.int_id) as c ON b.id=c.integral_id');
        foreach ($query as $v){
            //开始按周期性返还，首先判断是不是已经到了返还的次数,只有返还次数在就返还积分
                if(intval($v['tims'])<intval($v['months'])){
                    // 如果最后释放时间戳小于当前日前时间戳
                    if (strtotime($v['next_time']) <= strtotime(date('Y-m-d'))){
                        $teas=round(floatval($v['total_sum'])/intval($v['months']),2);        //每次返还的总积分比
                        $tea__inte_return=round($teas*floatval($v['tea_point_num']),2);       //每次返还的茶券
                        $tea_ponit_inte_return=$teas-$tea__inte_return;                                     //每次返还的茶点
                        //判断剩余的返还积分是不是大于
                        if($teas>floatval($v['surplus_inte'])){
                            $tea__inte_return=round(floatval($v['surplus_inte'])*floatval($v['tea_point_num']),2);       //每次返还的茶券
                            $tea_ponit_inte_return=floatval($v['surplus_inte'])-$tea__inte_return;                                     //每次返还的茶点
                            //修改用户表积分
                            $users = Db::table('tea_user')->where('user_id',intval($v['user_id']))->find();
                            if($users){
                                //避免虚假数据写入到数据库
                                Db::table('tea_user')->where('user_id',intval($v['user_id']))->setInc('tea_ponit_inte',$tea_ponit_inte_return);
                                Db::table('tea_user')->where('user_id',intval($v['user_id']))->setInc('tea_inte',$tea__inte_return);
                            }
                            //形成记录
                            $tea_ponit_inte ="+".$tea_ponit_inte_return;
                            $tea_inte ="+".$tea__inte_return;
                            $surplus_inte_ch = "-".floatval($v['surplus_inte']);
                            $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                            $introduce = "股东每月定时返还";
                            $this->MakeLog($v['user_id'],0,$surplus_inte_ch,$tea_inte,$tea_ponit_inte,0,$introduce,4,0,0,1,0,0,0,0,0,0,0,0,0,0,1,0,0,$log_out_trade_no);
                            //更新积分表
                            Db::table('tea_integral')->where('id',intval($v['id']))->setInc('tea_ponit_inte',$tea_ponit_inte_return);
                            Db::table('tea_integral')->where('id',intval($v['id']))->setInc('tea_inte',$tea__inte_return);
                            Db::table('tea_integral')->where('id',intval($v['id']))->setInc('back_inte',floatval($v['surplus_inte']));
                            Db::table('tea_integral')->where('id',intval($v['id']))->setField('surplus_inte',0);
                            $inte_data=array(
                                'last_time'=>date('Y-m-d'),
                                'year'=>date("Y"),
                                'month'=>date("m"),
                                'day'=>date("d")
                            );
                            Db::table('tea_integral')->where('id',intval($v['id']))->update($inte_data);
                            //添加
                            $ceo_data=array(
                                'integral_id'=>intval($v['integral_id']),
                                'thistime'=>date('Y-m-d'),
                                'next_time'=>date('Y-m-d',strtotime('+1 month')),
                                'back_inte'=>floatval($v['surplus_inte']),
                                'months'=>intval($v['months']),
                                'tea_point_num'=>$v['tea_point_num'],
                                'tea_int_point_num'=>$v['tea_int_point_num'],
                                'tims'=>intval($v['tims'])+1,

                            );
                            Db::table("tea_ceo_integral_log")->insert($ceo_data);
                        }else{
                            //修改用户表积分
                            $users = Db::table('tea_user')->where('user_id',intval($v['user_id']))->find();
                            if($users){
                                //避免虚假数据写入到数据库
                                Db::table('tea_user')->where('user_id',intval($v['user_id']))->setInc('tea_ponit_inte',$tea_ponit_inte_return);
                                Db::table('tea_user')->where('user_id',intval($v['user_id']))->setInc('tea_inte',$tea__inte_return);
                            }
                            //形成记录
                            $tea_ponit_inte ="+".$tea_ponit_inte_return;
                            $tea_inte ="+".$tea__inte_return;
                            $surplus_inte_ch = "-".$teas;
                            $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                            $introduce = "股东每月定时返还";
                            $this->MakeLog($v['user_id'],0,$surplus_inte_ch,$tea_inte,$tea_ponit_inte,0,$introduce,4,0,0,1,0,0,0,0,0,0,0,0,0,0,1,0,0,$log_out_trade_no);

                            //更新积分表
                            Db::table('tea_integral')->where('id',intval($v['id']))->setInc('tea_ponit_inte',$tea_ponit_inte_return);
                            Db::table('tea_integral')->where('id',intval($v['id']))->setInc('tea_inte',$tea__inte_return);
                            Db::table('tea_integral')->where('id',intval($v['id']))->setInc('back_inte',$teas);
                            Db::table('tea_integral')->where('id',intval($v['id']))->setDec('surplus_inte',$teas);
                            $inte_data=array(
                                'last_time'=>date('Y-m-d'),
                                'year'=>date("Y"),
                                'month'=>date("m"),
                                'day'=>date("d")
                            );
                            Db::table('tea_integral')->where('id',intval($v['id']))->update($inte_data);
                            //添加
                            $ceo_data=array(
                                'integral_id'=>intval($v['integral_id']),
                                'thistime'=>date('Y-m-d'),
                                'next_time'=>date('Y-m-d',strtotime('+1 month')),
                                'back_inte'=>$teas,
                                'months'=>intval($v['months']),
                                'tea_point_num'=>$v['tea_point_num'],
                                'tea_int_point_num'=>$v['tea_int_point_num'],
                                'tims'=>intval($v['tims'])+1,

                            );
                            Db::table("tea_ceo_integral_log")->insert($ceo_data);
                        }

                        unset($tea_ponit_inte_return);   //销毁内存
                        unset($tea__inte_return);        //销毁内存
                        unset($teas);                    //销毁内存
                    }
                }
        }
    }


    */


    //积分日志记录
    private function MakeLog($user_id,$user_lev,$surplus_inte,$tea_inte,$tea_ponit_inte,$reg_inte,$introduce,$menu,$sum_inte,$have_inte,$use_type,$recom,$recom_one,$recom_two,$grade,$grade_one,$grade_two,$recharge_money,$shopping,$exchange,$online,$fix,$other_id,$other_lev,$log_out_trade_no){

        //dump($recom_one);die;

        $time = time();

        //用户·id

        $log['user_id'] = $user_id;

        //自己等级(推荐时使用)

        $log['user_lev'] = $user_lev;

        //释放额度

        $log['surplus_inte'] = $surplus_inte;

        //茶券

        $log['tea_inte'] = $tea_inte;

        //茶点

        $log['tea_ponit_inte'] = $tea_ponit_inte;

        //茶籽

        $log['reg_inte'] = $reg_inte;

        //记录说明

        $log['introduce'] = $introduce;

        //积分记录分类

        $log['menu'] = $menu;

        //返还总积分

        $log['sum_inte'] = $sum_inte;

        //剩余积分

        $log['have_inte'] = $have_inte;

        //积分类型

        $log['use_type'] = $use_type;

        //推荐

        $log['recom'] = $recom;

        //一级推荐

        $log['recom_one'] = $recom_one;

        //二级推荐

        $log['recom_two'] = $recom_two;

        //绩效

        $log['grade'] = $grade;

        //一级绩效

        $log['grade_one'] = $grade_one;

        //二级绩效

        $log['grade_two'] = $grade_two;

        //充值金额

        $log['recharg_money'] = $recharge_money;

        //购物

        $log['shopping'] = $shopping;

        //兑换

        $log['exchange'] = $exchange;

        //线下

        $log['online'] = $online;

        //固定

        $log['fix'] = $fix;

        //来源id

        $log['other_id'] = $other_id;

        //用户级别

        $log['other_lev'] = $other_lev;

        //记录产生时间
        $log['addtime'] = $time;
        //记录产生时间
        $log['year'] = date("Y");
        //记录产生时间
        $log['month'] = date("m");
        //记录产生时间
        $log['day'] = date("d");
        $log['log_out_trade_no'] = $log_out_trade_no;
        //入库
        Db::table("tea_integral_log")->insert($log);
    }



    //暂时关掉

    /*
    //理茶宝用户每日返还积分
    public function everyInte(){
        $rate_info = $this->getRate();      //分配比例

        $time = date('Y-m-d');
        //获取未返完的积分信息  //只返还理茶宝的会员，不返还股东的积分
        //2018-7-10增加查询条件每日返还率为0的账号就是员工号
        $data = Db::table("tea_integral")->where('is_return ', 1)->where('is_ceo',0)->where('erevy_back_rate','>',0)->select();  //修改只返还理茶宝会员的积分
        foreach($data as $k=>$v){
            // 如果最后释放时间戳小于当前日前时间戳
            if (strtotime($v['last_time']) < strtotime($time)){
                $every_rate = $v['erevy_back_rate']+$v['grow_rate'];    //总返还率 = 每日固定释放 + 增加释放
                //是否需要返还
                if($every_rate > 0){
                    //如果总返还率大于封顶值
                    if($every_rate > $rate_info['hight_rate']){
                        //当天要返还的积分
                        $inte = $v['total_sum'] * $rate_info['hight_rate'];     //返还积分 = 需返还的总积分 x 封顶值
                    }else{
                        $inte = $v['total_sum'] * $every_rate;  //返还积分 = 需返还的总积分 x 总返还率
                    }
                    //如果剩余积分小于等于需返还的积分
                    if($v['surplus_inte']<=$inte){

                        if ($v['user_id'] <= 2645) {
                            $v['tea_inte'] = $v['tea_inte'] + $v['surplus_inte'] * $rate_info['slow_tea_inte_rate'];    //茶券 = 茶券 + 剩余积分 x 静态茶券返还比例
                            $v['tea_ponit_inte'] = $v['tea_ponit_inte'] + $v['surplus_inte'] * $rate_info['slow_tea_score_rate'];   //茶点 = 茶点 + 剩余积分 x 静态茶点返还比例
                        } else {
                            $v['tea_ponit_inte'] = $v['tea_ponit_inte'] + $v['surplus_inte'];
                        }

                        //$v['surplus_inte'] = 0; //剩余积分=0
                        $v['back_inte'] = $v['total_sum'];
                        //改为已返完
                        $v['is_return'] = 0;
                        $v['last_time'] = $time;
                        Db::table('tea_user_recharge')->where('user_id',$v['user_id'])->order('id desc')->limit(1)->setField('is_return',' 0');
                        //记录
                        if ($v['user_id'] <= 2645) {
                            $tea_inte = ($v['surplus_inte'] * $rate_info['slow_tea_inte_rate']);
                            $tea_ponit_inte = $v['surplus_inte'] * $rate_info['slow_tea_score_rate'];
                        } else {
                            $tea_inte = 0;
                            $tea_ponit_inte = $v['surplus_inte'];
                        }

                        //修改记录
                        $tea_inte_ch = "+".$tea_inte;
                        $tea_ponit_inte_ch = "+".$tea_ponit_inte;
                        $surplus_inte_ch = "-".$v['surplus_inte'];
                        $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                        $introduce = "每日释放";
                        $this->MakeLog($v['user_id'],intval($v['lev']),$surplus_inte_ch,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                            4,$v['total_sum'],0,1,0,0,0,0,0,
                            0,0,0,0,0,1,0,0,$log_out_trade_no);
                        $v['surplus_inte'] = 0;
                        //修改用户表积分
                        $users = Db::table('tea_user')->where('user_id',intval($v['user_id']))->find();
                        if($users){
                            $tea_inte = doubleval($users['tea_inte']) + $tea_inte;
                            $tea_ponit_inte = doubleval($users['tea_ponit_inte']) + $tea_ponit_inte;
                            Db::table('tea_user')->where('user_id',intval($v['user_id']))->update(['tea_inte'=>$tea_inte,'tea_ponit_inte'=>$tea_ponit_inte]);
                        }

                    } else {
                        $v['surplus_inte'] = $v['surplus_inte']-$inte;
                        if ($v['user_id'] <= 2645) {
                            $v['tea_inte'] = $v['tea_inte'] + $inte * $rate_info['slow_tea_inte_rate'];
                            $v['tea_ponit_inte'] = $v['tea_ponit_inte'] + $inte * $rate_info['slow_tea_score_rate'];
                        } else {
                            $v['tea_ponit_inte'] = $v['tea_ponit_inte'] + $inte;
                        }
                        $v['back_inte'] = $v['back_inte']+$inte;
                        $v['last_time'] = $time;

                        //记录
                        if ($v['user_id'] <= 2645) {
                            $tea_inte = $inte * $rate_info['slow_tea_inte_rate'];
                            $tea_ponit_inte = $inte * $rate_info['slow_tea_score_rate'];
                        } else {
                            $tea_inte = 0;
                            $tea_ponit_inte = $inte;
                        }

                        //修改记录
                        $tea_inte_ch = "+".$tea_inte;
                        $tea_ponit_inte_ch = "+".$tea_ponit_inte;
                        $inte_ch = "-".$inte;
                        $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                        $introduce = "每日释放";
                        //$this->MakeLog($v['user_id'],$inte,$tea_inte,$tea_ponit_inte,0,$introduce,4,0,0,0,0,$v['total_sum'],$v['surplus_inte'],0,0,1,0,0,0);
                        $this->MakeLog($v['user_id'],intval($v['lev']),$inte_ch,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                            4,$v['total_sum'],$v['surplus_inte'],1,0,0,0,0,0,
                            0,0,0,0,0,1,0,0,$log_out_trade_no);


                        //修改用户表积分
                        $users = Db::table('tea_user')->where('user_id',intval($v['user_id']))->find();
                        if($users){
                            $tea_inte = doubleval($users['tea_inte']) + $tea_inte;
                            $tea_ponit_inte = doubleval($users['tea_ponit_inte']) + $tea_ponit_inte;
                            Db::table('tea_user')->where('user_id',intval($v['user_id']))->update(['tea_inte'=>$tea_inte,'tea_ponit_inte'=>$tea_ponit_inte]);
                        }

                    }
                    $this->updInte($v['id'],$v);

                }else{
                    $rule['last_time'] = $time;
                    $rule['erevy_back_rate'] = 0;
                    $rule['grow_rate'] = 0;
                    $rule['addtime'] = time();
                    $rule['year'] = date("Y");
                    $rule['month'] = date("m");
                    $rule['day'] = date("d");
                    Db::table('tea_integral')->where('id',$v['id'])->update($rule);
                }
            }
        }
    }

    */

    //获得利率信息问题
    private function getRate(){
        return Db::table('tea_rate')->order("id desc")->limit(1)->find();
    }

    //更新用户积分表
    private function updInte($id,$res){
        $res['addtime'] = time();
        $res['year'] = date("Y");
        $res['month'] = date("m");
        $res['day'] = date("d");
        return  Db::table('tea_integral')
            ->where('id',$id)
            ->update($res);
    }

    //------------------------------------------------------------------------------------------------------------------
    // 10/31 修改
    //理茶宝用户每日返还积分
    public function everyInte(){
        $rate_info = $this->getRate();      //分配比例

        $time = date('Y-m-d');
        //获取未返完的积分信息  //只返还理茶宝的会员，不返还股东的积分
        //2018-7-10增加查询条件每日返还率为0的账号就是员工号
        $data = Db::table("tea_integral")->where('is_return ', 1)->where('is_ceo',0)->where('erevy_back_rate','>',0)->select();  //修改只返还理茶宝会员的积分
        foreach($data as $k=>$v){
            // 如果最后释放时间戳小于当前日前时间戳
            if (strtotime($v['last_time']) < strtotime($time)){
                //新增
                if(intval($v['id']) > 510  ){
                    $every_rate = $v['erevy_back_rate'];
                }else{
                    $every_rate = $v['erevy_back_rate']+$v['grow_rate'];    //总返还率 = 每日固定释放 + 增加释放
                }
               // $every_rate = $v['erevy_back_rate']+$v['grow_rate'];    //总返还率 = 每日固定释放 + 增加释放
                //是否需要返还
                if($every_rate > 0){
                    //如果总返还率大于封顶值
                    if($every_rate > $rate_info['hight_rate']){
                        //当天要返还的积分
                        $inte = $v['total_sum'] * $rate_info['hight_rate'];     //返还积分 = 需返还的总积分 x 封顶值
                    }else{
                        $inte = $v['total_sum'] * $every_rate;  //返还积分 = 需返还的总积分 x 总返还率
                    }
                    //如果剩余积分小于等于需返还的积分
                    if($v['surplus_inte']<=$inte){
                        $inte_sign = $v['tea_inte'];
                        if ($v['user_id'] <= 2645) {
                            $v['tea_inte'] = $v['tea_inte'] + $v['surplus_inte'] * $rate_info['slow_tea_inte_rate'];    //茶券 = 茶券 + 剩余积分 x 静态茶券返还比例
                            $v['tea_ponit_inte'] = $v['tea_ponit_inte'] + $v['surplus_inte'] * $rate_info['slow_tea_score_rate'];   //茶点 = 茶点 + 剩余积分 x 静态茶点返还比例
                        } else {
                            $v['tea_ponit_inte'] = $v['tea_ponit_inte'] + $v['surplus_inte'];
                        }

                        if(intval($v['id']) > 510 ){
                            $v['tea_inte'] = $inte_sign + $v['surplus_inte'];
                        }



                        //$v['surplus_inte'] = 0; //剩余积分=0
                        $v['back_inte'] = $v['total_sum'];
                        //改为已返完
                        $v['is_return'] = 0;
                        $v['last_time'] = $time;
                        Db::table('tea_user_recharge')->where('user_id',$v['user_id'])->order('id desc')->limit(1)->setField('is_return',' 0');
                        //记录
                        if ($v['user_id'] <= 2645) {
                            $tea_inte = ($v['surplus_inte'] * $rate_info['slow_tea_inte_rate']);
                            $tea_ponit_inte = $v['surplus_inte'] * $rate_info['slow_tea_score_rate'];
                        } else {
                            $tea_inte = 0;
                            $tea_ponit_inte = $v['surplus_inte'];
                        }

                        if(intval($v['id']) > 510 ){
                            $tea_inte =  $v['surplus_inte'];
                            $tea_ponit_inte = 0;
                        }

                        //修改记录
                        $tea_inte_ch = "+".$tea_inte;
                        $tea_ponit_inte_ch = "+".$tea_ponit_inte;
                        $surplus_inte_ch = "-".$v['surplus_inte'];
                        $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                        $introduce = "每日释放";
                        $this->MakeLog($v['user_id'],intval($v['lev']),$surplus_inte_ch,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                            4,$v['total_sum'],0,1,0,0,0,0,0,
                            0,0,0,0,0,1,0,0,$log_out_trade_no);
                        $v['surplus_inte'] = 0;
                        //修改用户表积分
                        $users = Db::table('tea_user')->where('user_id',intval($v['user_id']))->find();
                        if($users){
                            $tea_inte = doubleval($users['tea_inte']) + $tea_inte;
                            $tea_ponit_inte = doubleval($users['tea_ponit_inte']) + $tea_ponit_inte;
                            Db::table('tea_user')->where('user_id',intval($v['user_id']))->update(['tea_inte'=>$tea_inte,'tea_ponit_inte'=>$tea_ponit_inte]);
                        }

                    } else {
                        $v['surplus_inte'] = $v['surplus_inte']-$inte;
                        $sign_inte =  $v['tea_inte'];
                        if ($v['user_id'] <= 2645) {
                            $v['tea_inte'] = $v['tea_inte'] + $inte * $rate_info['slow_tea_inte_rate'];
                            $v['tea_ponit_inte'] = $v['tea_ponit_inte'] + $inte * $rate_info['slow_tea_score_rate'];
                        } else {
                            $v['tea_ponit_inte'] = $v['tea_ponit_inte'] + $inte;
                        }

                        if(intval($v['id']) > 510 ){
                            $v['tea_inte'] = $sign_inte +$inte;
                        }

                        $v['back_inte'] = $v['back_inte']+$inte;
                        $v['last_time'] = $time;

                        //记录
                        if ($v['user_id'] <= 2645) {
                            $tea_inte = $inte * $rate_info['slow_tea_inte_rate'];
                            $tea_ponit_inte = $inte * $rate_info['slow_tea_score_rate'];
                        } else {
                            $tea_inte = 0;
                            $tea_ponit_inte = $inte;
                        }

                        if(intval($v['id']) > 510 ){
                            $tea_inte = $inte;
                            $tea_ponit_inte = 0;
                        }

                        //修改记录
                        $tea_inte_ch = "+".$tea_inte;
                        $tea_ponit_inte_ch = "+".$tea_ponit_inte;
                        $inte_ch = "-".$inte;
                        $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                        $introduce = "每日释放";
                        //$this->MakeLog($v['user_id'],$inte,$tea_inte,$tea_ponit_inte,0,$introduce,4,0,0,0,0,$v['total_sum'],$v['surplus_inte'],0,0,1,0,0,0);
                        $this->MakeLog($v['user_id'],intval($v['lev']),$inte_ch,$tea_inte_ch,$tea_ponit_inte_ch,0,$introduce,
                            4,$v['total_sum'],$v['surplus_inte'],1,0,0,0,0,0,
                            0,0,0,0,0,1,0,0,$log_out_trade_no);


                        //修改用户表积分
                        $users = Db::table('tea_user')->where('user_id',intval($v['user_id']))->find();
                        if($users){
                            $tea_inte = doubleval($users['tea_inte']) + $tea_inte;
                            $tea_ponit_inte = doubleval($users['tea_ponit_inte']) + $tea_ponit_inte;
                            Db::table('tea_user')->where('user_id',intval($v['user_id']))->update(['tea_inte'=>$tea_inte,'tea_ponit_inte'=>$tea_ponit_inte]);
                        }

                    }
                    $this->updInte($v['id'],$v);

                }else{
                    $rule['last_time'] = $time;
                    $rule['erevy_back_rate'] = 0;
                    $rule['grow_rate'] = 0;
                    $rule['addtime'] = time();
                    $rule['year'] = date("Y");
                    $rule['month'] = date("m");
                    $rule['day'] = date("d");
                    Db::table('tea_integral')->where('id',$v['id'])->update($rule);
                }
            }
        }
    }

    //股东的定时任务返回的时间
    public function everyMonthReturnTeapoint(){
        //$query=Db::query('select * FROM tea_integral b join (select * from tea_ceo_integral_log a join (select max(int_id) as ids from tea_ceo_integral_log GROUP BY integral_id) as t on t.ids = a.int_id) as c ON b.id=c.integral_id');
        $query=Db::query('select * FROM tea_integral b join (select * from tea_ceo_integral_log a join (select max(int_id) as ids from tea_ceo_integral_log GROUP BY integral_id) as t on t.ids = a.int_id) as c ON b.id=c.integral_id where b.type in (2,3)');
        foreach ($query as $v){
            //开始按周期性返还，首先判断是不是已经到了返还的次数,只有返还次数在就返还积分
            if(intval($v['tims'])<intval($v['months'])){
                // 如果最后释放时间戳小于当前日前时间戳
                if (strtotime($v['next_time']) <= strtotime(date('Y-m-d'))){

                    if(intval($v['id']) > 510 ){
                        $teas = (int)(floatval($v['total_sum'])/intval($v['months']));
                        $tea__inte_return=intval($teas);       //每次返还的茶券
                        $tea_ponit_inte_return=0;
                    }else{
                        $teas=round(floatval($v['total_sum'])/intval($v['months']),2);        //每次返还的总积分比
                        $tea__inte_return=round($teas*floatval($v['tea_point_num']),2);       //每次返还的茶券
                        $tea_ponit_inte_return=$teas-$tea__inte_return;                                     //每次返还的茶点
                    }
                    //判断剩余的返还积分是不是大于
                    if($teas>floatval($v['surplus_inte'])){
                        if(intval($v['id']) > 510){
                            $tea__inte_return = floatval($v['surplus_inte']);
                        }else{
                            $tea__inte_return=round(floatval($v['surplus_inte'])*floatval($v['tea_point_num']),2);       //每次返还的茶券
                        }
                        //$tea__inte_return=round(floatval($v['surplus_inte'])*floatval($v['tea_point_num']),2);       //每次返还的茶券
                        $tea_ponit_inte_return=floatval($v['surplus_inte'])-$tea__inte_return;                                     //每次返还的茶点
                        //修改用户表积分
                        $users = Db::table('tea_user')->where('user_id',intval($v['user_id']))->find();
                        if($users){
                            //避免虚假数据写入到数据库
                            Db::table('tea_user')->where('user_id',intval($v['user_id']))->setInc('tea_ponit_inte',$tea_ponit_inte_return);
                            Db::table('tea_user')->where('user_id',intval($v['user_id']))->setInc('tea_inte',$tea__inte_return);
                        }
                        //形成记录
                        $tea_ponit_inte ="+".$tea_ponit_inte_return;
                        $tea_inte ="+".$tea__inte_return;
                        $surplus_inte_ch = "-".floatval($v['surplus_inte']);
                        $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                        $introduce = "股东每月定时返还";
                        $this->MakeLog($v['user_id'],0,$surplus_inte_ch,$tea_inte,$tea_ponit_inte,0,$introduce,4,0,0,1,0,0,0,0,0,0,0,0,0,0,1,0,0,$log_out_trade_no);
                        //更新积分表
                        Db::table('tea_integral')->where('id',intval($v['id']))->setInc('tea_ponit_inte',$tea_ponit_inte_return);
                        Db::table('tea_integral')->where('id',intval($v['id']))->setInc('tea_inte',$tea__inte_return);
                        Db::table('tea_integral')->where('id',intval($v['id']))->setInc('back_inte',floatval($v['surplus_inte']));
                        Db::table('tea_integral')->where('id',intval($v['id']))->setField('surplus_inte',0);
                        $inte_data=array(
                            'last_time'=>date('Y-m-d'),
                            'year'=>date("Y"),
                            'month'=>date("m"),
                            'day'=>date("d")
                        );
                        Db::table('tea_integral')->where('id',intval($v['id']))->update($inte_data);
                        //添加
                        $ceo_data=array(
                            'integral_id'=>intval($v['integral_id']),
                            'thistime'=>date('Y-m-d'),
                            'next_time'=>date('Y-m-d',strtotime('+1 month')),
                            'back_inte'=>floatval($v['surplus_inte']),
                            'months'=>intval($v['months']),
                            'tea_point_num'=>$v['tea_point_num'],
                            'tea_int_point_num'=>$v['tea_int_point_num'],
                            'tims'=>intval($v['tims'])+1,

                        );
                        Db::table("tea_ceo_integral_log")->insert($ceo_data);
                    }else{
                        //修改用户表积分
                        $users = Db::table('tea_user')->where('user_id',intval($v['user_id']))->find();
                        if($users){
                            //避免虚假数据写入到数据库
                            Db::table('tea_user')->where('user_id',intval($v['user_id']))->setInc('tea_ponit_inte',$tea_ponit_inte_return);
                            Db::table('tea_user')->where('user_id',intval($v['user_id']))->setInc('tea_inte',$tea__inte_return);
                        }
                        //形成记录
                        $tea_ponit_inte ="+".$tea_ponit_inte_return;
                        $tea_inte ="+".$tea__inte_return;
                        $surplus_inte_ch = "-".$teas;
                        $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                        $introduce = "股东每月定时返还";
                        $this->MakeLog($v['user_id'],0,$surplus_inte_ch,$tea_inte,$tea_ponit_inte,0,$introduce,4,0,0,1,0,0,0,0,0,0,0,0,0,0,1,0,0,$log_out_trade_no);

                        //更新积分表
                        Db::table('tea_integral')->where('id',intval($v['id']))->setInc('tea_ponit_inte',$tea_ponit_inte_return);
                        Db::table('tea_integral')->where('id',intval($v['id']))->setInc('tea_inte',$tea__inte_return);
                        Db::table('tea_integral')->where('id',intval($v['id']))->setInc('back_inte',$teas);
                        Db::table('tea_integral')->where('id',intval($v['id']))->setDec('surplus_inte',$teas);
                        $inte_data=array(
                            'last_time'=>date('Y-m-d'),
                            'year'=>date("Y"),
                            'month'=>date("m"),
                            'day'=>date("d")
                        );
                        Db::table('tea_integral')->where('id',intval($v['id']))->update($inte_data);
                        //添加
                        $ceo_data=array(
                            'integral_id'=>intval($v['integral_id']),
                            'thistime'=>date('Y-m-d'),
                            'next_time'=>date('Y-m-d',strtotime('+1 month')),
                            'back_inte'=>$teas,
                            'months'=>intval($v['months']),
                            'tea_point_num'=>$v['tea_point_num'],
                            'tea_int_point_num'=>$v['tea_int_point_num'],
                            'tims'=>intval($v['tims'])+1,

                        );
                        Db::table("tea_ceo_integral_log")->insert($ceo_data);
                    }

                    unset($tea_ponit_inte_return);   //销毁内存
                    unset($tea__inte_return);        //销毁内存
                    unset($teas);                    //销毁内存
                }
            }
        }
    }

}