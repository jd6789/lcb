<?php
/**
 * Created by PhpStorm.
 * User: jacky-fu
 * Date: 2018/4/26
 * Time: 15:41
 */
namespace app\api\controller;
use app\appmobile\model\RechargeCart;
use think\Controller;
use think\Db;
header('Access-Control-Allow-Origin:*');
class Api extends Controller{
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
        */
        //$data= file_get_contents('php://input');
        $data=input('post.');
        $data= json_encode($data);
        //return $data;
        //$data=input('post.');
//        $data="{\"user_id\":32,\"sign\":\"804A942FC84107EEBC77FF85368A2023\",\"money\":23.39,\"trade_no\":\"15253367662772076\",\"goods_name\":\"\u6211\u7684\u8336\u9986\u6d4b\u8bd5\u6570\u636e\",\"shopping\":\"1\",\"online\":\"0\",\"store_name\":\"\"}";
        $datas=[

            'test'=>$data,

            'trade_status'=>'API接口文件接口'

        ];
        $arr=json_decode($data,true);
        Db::table('tea_test')->insert($datas);
        //return $data;
        // return "进入服务器成功";
        // $arr=(array)simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);
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
            "mykey"=>'c5bd3fa331e9a8626c2b95d6e363d417',
            'myras2' =>'2.0',
            'mycharset' =>'utf8',
            'money'=>$arr['money'],
            'mysign_type' =>'md5',
            'trade_no'  =>$arr['trade_no'],
            'user_id'=>$user_id
        );

        $mysign=$this->sign($signData);
        $datas=[

            'test'=>$mysign,

            'trade_status'=>'API接口文件接口'

        ];
        //$arr=json_decode($data,true);
        Db::table('tea_test')->insert($datas);
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
        /*有两种情况需要加以判断
            ①茶点茶券都比用户消费金额高
            ②用户金额高于其中任何一种
        */
        //①茶点茶券都比用户消费金额高
        if(floatval($users['tea_ponit_inte']) >=$teaSorts &&  floatval($users['tea_inte']) >=$teaSorts ){
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
            $data=array(
                'status'=>1,
                'trade_no'=>$trade_no,
                'msg'=>'支付成功',
                'money'=>$teaSorts,
                'out_trade_no'=>$logData['log_out_trade_no']
            );
            return json($data);
        }elseif (floatval($users['tea_ponit_inte']) >=$teaSorts ||  floatval($users['tea_inte']) >=$teaSorts ){
            //如果用户金额高于其中任何一种
            if(floatval($users['tea_ponit_inte']) >=$teaSorts){
                Db::table('tea_user')->where('user_id',$user_id)->setDec('tea_ponit_inte',$teaSorts);
                Db::table('tea_integral')->where('user_id',$user_id)->setDec('tea_ponit_inte',$teaSorts);
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
                    'log_out_trade_no'=>date('Y').date('m').date('d').uniqid().'168',
                    'trade_no'=>$trade_no
                );
                Db::table('tea_integral_log')->insert($logData);
                $data=array(
                    'status'=>1,
                    'trade_no'=>$trade_no,
                    'msg'=>'支付成功',
                    'money'=>$teaSorts,
                    'out_trade_no'=>$logData['log_out_trade_no']
                );
                return json($data);
            }else{
                Db::table('tea_user')->where('user_id',$user_id)->setDec('tea_ponit_inte',$teaSorts);
                Db::table('tea_integral')->where('user_id',$user_id)->setDec('tea_ponit_inte',$teaSorts);
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
                    'log_out_trade_no'=>date('Y').date('m').date('d').uniqid().'168',
                    'trade_no'=>$trade_no
                );
                Db::table('tea_integral_log')->insert($logData);
                $data=array(
                    'status'=>1,
                    'trade_no'=>$trade_no,
                    'msg'=>'支付成功',
                    'money'=>$teaSorts,
                    'out_trade_no'=>$logData['log_out_trade_no']
                );
                return json($data);
            }
        }else{
            $newmoney=  floatval($users['tea_ponit_inte']) >=floatval($users['tea_inte']) ? 1 : 2;
            if($newmoney ==1){
                //茶点大于茶券   tea_ponit_inte
                //操作数据库
                $data=array(
                    'tea_inte'=>0,
                    'tea_ponit_inte' =>floatval($users['tea_ponit_inte'])- ($teaSorts-floatval($users['tea_inte']))
                );
                Db::table('tea_user')->where('user_id',$user_id)->setField($data);
                Db::table('tea_integral')->where('user_id',$user_id)->setField($data);
                $logData=array(
                    'user_id'=>$user_id,
                    'introduce'=>$str,
                    'surplus_inte'=>'-'.$teaSorts,
                    'tea_inte'=>'-'.floatval($users['tea_inte']),
                    'tea_ponit_inte'=>'-'.($teaSorts-floatval($users['tea_inte'])),
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
                $data=array(
                    'status'=>1,
                    'trade_no'=>$trade_no,
                    'msg'=>'支付成功',
                    'money'=>$teaSorts,
                    'out_trade_no'=>$logData['log_out_trade_no']
                );
                return json($data);
            }else{
                //茶券大于茶点   tea_inte
                //操作数据库
                $data=array(
                    'tea_ponit_inte'=>0,
                    'tea_inte' =>floatval($users['tea_inte'])- ($teaSorts-floatval($users['tea_ponit_inte']))
                );
                Db::table('tea_user')->where('user_id',$user_id)->setField($data);
                Db::table('tea_integral')->where('user_id',$user_id)->setField($data);
                $logData=array(
                    'user_id'=>$user_id,
                    'introduce'=>$str,
                    'surplus_inte'=>'-'.$teaSorts,
                    'tea_ponit_inte'=>'-'.floatval($users['tea_ponit_inte']),
                    'tea_inte'=>'-'.($teaSorts-floatval($users['tea_ponit_inte'])),
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
                $data=array(
                    'status'=>1,
                    'trade_no'=>$trade_no,
                    'msg'=>'支付成功',
                    'money'=>$teaSorts,
                    'out_trade_no'=>$logData['log_out_trade_no']
                );
                return json($data);
            }
        }

    }


    //制造签名
    public function sign($data){
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



    //给出来的付款码ajax查询系统
    public  function payCode(){
        $key=input('post.key');
        //$key=file_get_contents('php://input') ;
        //return $key;
        //判断传递过来的是不是违法的 长度为32位
        if(empty($key)  || strlen($key) !=32){
            return 0;
        }
        $code_res=Db::table('tea_session')->where('key',$key)->find();
        //①首先判断存不存在
        if($code_res){
            //存在
            //②判断是不是失效
            if(time() > intval($code_res['overtime'])){
                //二维码已经失效，过了有效期
                return 0;
            }else{
                return json(array('user_id'=>$code_res['user_id'],'key'=>$key));
            }
        }else{
            //不存在
            return 0;
        }
    }

    public function test(){
        $data= file_get_contents('php://input');
        $datas=[

            'test'=>$data,

            'trade_status'=>'API接口文件接口'

        ];
        //$arr=json_decode($data,true);
        Db::table('tea_test')->insert($datas);
    }
}