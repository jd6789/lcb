<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/4/10
 * Time: 11:26
 */
namespace app\api\controller;
use think\Controller;
use think\Db;
use app\tmvip\controller\recharge;
use app\appmobile\Model\RechargeCart;
use app\tmvip\controller\Integral;
class Alpay extends Controller{
    public function i(){
        $this->success('同步回调成功','Index/index/index');
        $str="%5D&notify_type=trade_status_sync&out_trade_no=1523426723&total_amount=0.01&trade_status=TRADE_SUCCESS&trade_no=2018041121001004350200326764&auth_app_id=2016080800194072&receipt_amount=0.01&point_amount=0.00&app_id=2016080800194072&buyer_pay_amount=0.01&sign_type=RSA2&seller_id=2088102170453656";
    }
    public function index(){

//        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
//        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
//        dump($array_data)
        $trade_no = htmlspecialchars($_GET['trade_no']);
        $out_trade_no = htmlspecialchars($_GET['out_trade_no']);
        $datas=[
            'test'=>$trade_no
        ];
        Db::table('tea_test')->insert($datas);
       $this->i();
    }
    public function setNotifyUrl(){
        $data = file_get_contents('php://input');
        $total_amount=$_POST['total_amount'];
        $trade_status=$_POST['trade_status'];
        //业务代码-----这里要再判断当前订单状态，避免同步和异步都修改订单。
        if($trade_status == 'TRADE_SUCCESS'){
            $datas=[
                'test'=>$data,
                'trade_status'=>'交易成功'
            ];
           Db::table('tea_test')->insert($datas);
            //执行完返回支付宝成功信息
            echo  success;
        }elseif ($trade_status == 'TRADE_CLOSED'){
            $datas=[
                'test'=>$data,
                'trade_status'=>'未付款交易超时关闭，或支付完成后全额退款'
            ];
            Db::table('tea_test')->insert($datas);
        }elseif ($trade_status == 'WAIT_BUYER_PAY'){
            $datas=[
                'test'=>$data,
                'trade_status'=>'交易创建，等待买家付款'
            ];
            Db::table('tea_test')->insert($datas);
        }elseif ($trade_status == '交易结束，不可退款'){
            $datas=[
                'test'=>$data,
                'trade_status'=>'未付款交易超时关闭，或支付完成后全额退款'
            ];
            Db::table('tea_test')->insert($datas);
        }



    }




    //配置支付宝的config文件
    private function config(){
        $data=Db::table('tea_alpay_config')->find();
        return $data;
    }
    //pc端支付宝支付
    public function PcAlpay($out_trade_no='9181523266801745168',$total_amount='0.01',$subject='我的茶馆',$body='我的茶馆',$setReturnUrl='http://love1314.ink/api/alpay/index',$notify_url='http://love1314.ink/api/alpay/setNotifyUrl'){
        $config=$this->config();
        $out_trade_no=time();
        require_once("./Pay/alpay/AopSdk.php");
        $aop = new \AopClient ();
        $aop->gatewayUrl = $config['gatewayUrl'];
        $aop->appId = $config['app_id'];
        $aop->rsaPrivateKey =$config['merchant_private_key'];
        $aop->alipayrsaPublicKey=$config['alipay_public_key'];
        $aop->apiVersion = '1.0';
        $aop->signType = $config['sign_type'];
        $aop->postCharset=$config['charset'];
        $aop->format='json';
        //dump($aop);die;
        $request = new \AlipayTradePagePayRequest ();
        $request->setNotifyUrl($notify_url);
        $request->setReturnUrl($setReturnUrl);
        $request->setBizContent("{'product_code':'FAST_INSTANT_TRADE_PAY','out_trade_no':$out_trade_no,'subject':'$subject','total_amount':'$total_amount','body':'$body'}");
        echo  $result=$aop->pageExecute ($request);
    }

    public function testAlpay($out_trade_no,$total_amount,$subject,$body,$setReturnUrl,$notify_url){
        $config=$this->config();
        require_once("./Pay/alpay/AopSdk.php");
        $aop = new \AopClient ();
        $aop->gatewayUrl = $config['gatewayUrl'];
        $aop->appId = $config['app_id'];
        $aop->rsaPrivateKey =$config['merchant_private_key'];
        $aop->alipayrsaPublicKey=$config['alipay_public_key'];
        $aop->apiVersion = '1.0';
        $aop->signType = $config['sign_type'];
        $aop->postCharset=$config['charset'];
        $aop->format='json';
        //dump($aop);die;
        $request = new \AlipayTradePagePayRequest ();
        $request->setNotifyUrl($notify_url);
        $request->setReturnUrl($setReturnUrl);
        $request->setBizContent("{'product_code':'FAST_INSTANT_TRADE_PAY','out_trade_no':$out_trade_no,'subject':'$subject','total_amount':'$total_amount','body':'$body'}");
        echo  $result=$aop->pageExecute ($request);
    }

    /*购买理茶宝的支付宝支付成功后回调地址
           START
    ***/
    //同步回调地址(购买理茶宝的回调)
    public function buyReturnUrl(){
        //用户购买理茶宝支付宝支付后的回调地址
        $out_trade_no = htmlspecialchars($_GET['out_trade_no']);//商户订单号
        $trade_no = htmlspecialchars($_GET['trade_no']);//支付宝交易号
        $order_info=Db::table('tea_recharge_cart')->where('recharge_num',$out_trade_no)->find();
        if(intval($order_info['pay_status']==1)){
            $this->success('支付成功，即将跳转到个人中心','index/index/index');
        }else{
            $this->error('支付宝内部处理中，稍后支付详情信息会在个人中心显示，即将跳转到个人中心','index/index/index');
        }

    }
    public function buyNotiflUrl(){
        $data = file_get_contents('php://input');
        $datas=[
            'test'=>$data,
            'trade_status'=>'交易成功'
        ];
        Db::table('tea_test')->insert($datas);
        $trade_status=$_POST['trade_status'];      //支付宝返回回来的支付状态
        $out_trade_no=$_POST['out_trade_no'];   //原支付请求的商户订单号
        $order_info=Db::table('tea_recharge_cart')->where('recharge_num',$out_trade_no)->find();
        if($order_info){
            if(intval($order_info['pay_status']==0)){
                if($trade_status == 'TRADE_SUCCESS'){
                    //支付成功，开始处理业务逻辑
                    $total_amount=$_POST['total_amount'];   //支付成功后的实际支付的金额
//                    $total_amount='1.00';   //支付成功后的实际支付的金额
                    $trade_no=$_POST['trade_no'];           //支付宝内部的交易号
//                    $trade_no='2018041121001004350200326781';           //支付宝内部的交易号
                    if(floatval($total_amount)!=floatval($order_info['recharge_money'])){
                        //首先判断实际支付的金额是否跟订单金额一致
                        $data=[
                            'trade_no'=>$trade_no,
                            'trade_beizhu'=>"订单金额跟订单金额不一致，实际支付金额为.$total_amount",
                            'pay_status'=>2
                        ];
                        Db::table('tea_recharge_cart')->where('recharge_num',$out_trade_no)->setField($data);
                    }else{
                        //首先修改订单的状态
                        $data=[
                            'pay_status'=>1,
                            'trade_no'=>$trade_no,
                            'trade_beizhu'=>'交易成功',
                        ];
                        Db::table('tea_recharge_cart')->where('recharge_num',$out_trade_no)->setField($data);
                        //修改的同时处理理茶宝的一些信息
                        $user_id=intval($order_info['user_id']);
                        //$model=new RechargeCart();
                        //$model->buyUpdate($user_id,$out_trade_no,$trade_no);
                        //首先通过订单ID以及用户ID查询出该条信息，然后将信息获取后替换到用户的购买记录表
                        $cartInfo = Db::table('tea_recharge_cart')
                            ->where("user_id = $user_id AND recharge_num = '$out_trade_no'")
                            ->find();
                        //然后通过积分购物车表查询的充值表ID查询出对应的记录
                        $rechargeInfo = Db::table('tea_recharge')->where('id='.$cartInfo['recharge_id'])->find();
                        $rate = Db::table('tea_rate')->find();
                        $data = array(
                            'user_id' => $user_id,
                            'rec_money' => $rechargeInfo['rec_money'],
                            'subject' => $rechargeInfo['subject'],
                            'body' => $rechargeInfo['body'],
                            'rec_lev' => $rechargeInfo['rec_lev'],
                            'total_inte' => $rechargeInfo['total_inte'],
                            'init_rates' => $rechargeInfo['init_rates'],
                            'fir_rec' => $rechargeInfo['fir_rec'],
                            'sec_rec' => $rechargeInfo['sec_rec'],
                            'sec_merits' => $rechargeInfo['sec_merits'],
                            'fir_merits' => $rechargeInfo['fir_merits'],
                            'cap_rates' => $rate['hight_rate'],
                            'reg_rec' => $rechargeInfo['reg_rec'],
                            'sec_reg_rec' => $rechargeInfo['sec_reg_rec'],
                            'is_first' =>0,
                            'addtime'   =>time(),
                            'tea_inte_rate' => $rate['tea_inte_rate'],
                            'tea_score_rate' => $rate['tea_score_rate'],
                            'recharger_num'  =>$out_trade_no,
                            'trade_no'  =>$trade_no,
                            'pay_status' =>1
                        );
                        Db::table('tea_user_recharge')->insert($data);
                        $this->InteReturn($user_id );
                        $this->grow_rate($user_id );
                        $this->texts($user_id );
                    }

                }
            }elseif(intval($order_info['pay_status']==1)){
                //执行完返回支付宝成功信息
                echo  success;
            }

        }
    }

    public function InteReturn($user_id)
    {
        $user_id=empty($user_id)? intval(input('get.user_id')) :$user_id;
        //a->b->c->d->e
        //1.获得用户自己的信息  e
        $mm = Db::table('tea_user')->where('id=' . $user_id)->find();
        //用户购买的产品信息  e
        $des = $this->getProduct($user_id);
        //用户买产品充值的钱数   e
        //$data = M('RechargeCart')->where('user_id=' . $user_id)->order('id desc')->limit(1)->find();
        $data = Db::table("tea_recharge_cart")->where('user_id=' . $user_id)->order('id desc')->limit(1)->find();

        // 实际支付金额=实际支付金额+使用积分数
        $data['recharge_money'] = $data['recharge_money'] + $data['again_money'];
        //2.找到推荐人d
        $info_fir = $this->getParent($user_id);
        if (!$info_fir === false) {
            // d购买的产品
            $fir_golad = $this->getProductByUserId($info_fir['id']);
            if (!$fir_golad === false) {
                //计算一级的推荐奖  d  （d获得推一）
                $fir_ref = $data['recharge_money'] * $fir_golad['fir_rec'];

                //更改一级推荐人的剩余积分
                $introduce = $mm['username'] . "用户激活（一级推荐）";
                $this->ChangeInte($info_fir, $fir_ref, $introduce,3,1,$fir_ref,0,0,0,0,$des['user_id'],$des['rec_lev']);

                //3.找到推荐人c
                $info_sec = $this->getParent($info_fir['id']);
                $data_fir = $info_sec;
                //计算一级绩效
                if (!$data_fir === false) {
                    //c购买的产品
                    $sec_golad = $this->getProductByUserId($info_sec['id']);   //c 产品
                    if (!$sec_golad === false) {
                        //c拿 d 的一级绩效
                        $fir_mer = $fir_ref * $sec_golad['fir_merits'];
                        //更改一级绩效人的剩余积分
                        $introduce = $mm['username'] . "用户激活（一级绩效）";
                        $this->ChangeInte($data_fir, $fir_mer, $introduce,3,0,0,0,1,$fir_mer,0,$des['user_id'],$des['rec_lev']);
                    }
                }

                //4.找到推荐人b
                $data_sec = $this->getParent($data_fir['id']);
                //计算二级绩效
                if (!$data_sec === false) {
                    //b购买的产品
                    $data_sec_mer = $this->getProductByUserId($data_sec['id']);
                    if (!$data_sec_mer === false) {
                        //b拿 d 的二级绩效
                        $sec_mer = $fir_ref * $data_sec_mer['sec_merits'];
                        $introduce = $mm['username'] . "用户激活(二级绩效)";
                        $this->ChangeInte($data_sec, $sec_mer, $introduce,3,0,0,0,1,0,$sec_mer,$des['user_id'],$des['rec_lev']);
                    }
                }
            }
        }

        $info_sec = $this->getParent($info_fir['id']);
        //
        if (!$info_sec === false) {
            //c购买的产品
            $sec_golad = $this->getProductByUserId($info_sec['id']);   //c 产品
            //5.c获得二级推荐奖
            if (!$sec_golad === false) {
                $sec_ref = $data['recharge_money'] * $sec_golad['sec_rec'];
                //更改二级推荐人的剩余积分
                $introduce = $mm['username'] . "用户激活（二级推荐）";
                $this->ChangeInte($info_sec, $sec_ref, $introduce,3,1,0,$sec_ref,0,0,0,$des['user_id'],$des['rec_lev']);

                //6. b获得 c 的 一绩
                if (!$data_sec_mer === false) {
                    $sec_mer_first = $sec_ref * $data_sec_mer['fir_merits'];
                    $introduce = $mm['username'] . "用户激活(一级绩效)";
                    $this->ChangeInte($data_sec, $sec_mer_first, $introduce,3,0,0,0,1,$sec_mer_first,0,$des['user_id'],$des['rec_lev']);
                }

                //7.c二级绩效人详细信息  A
                $data_third = $this->getParent($data_sec['id']);
                if (!$data_third === false) {
                    //a购买信息
                    $data_third_mer = $this->getProductByUserId($data_third['id']);
                    if (!$data_third_mer === false) {
                        //a 拿 c 二绩
                        $third_mer_first = $sec_ref * $data_third_mer['sec_merits'];
                        $introduce = $mm['username'] . "用户激活(二级绩效)";
                        $this->ChangeInte($data_third, $third_mer_first, $introduce,3,0,0,0,1,0,$third_mer_first,$des['user_id'],$des['rec_lev']);
                    }
                }
            }
        }

        //上级是否需要升级
        $this->user_parent_upgrade($user_id);
        //上级是否抽佣
        $this->user_parent_money($user_id);
    }
    //发展奖比例
    public function grow_rate($user_id){
        $user_id=empty($user_id)? intval(input('get.user_id')) :$user_id;
        // 取得系统设定的理茶宝分配配置
        $rate_info = $this->getRate();

        // 取得购买人自身产品信息
        $user_info = $this->getProduct($user_id);

        // 获取上级信息
        $parent = $this->getParent($user_id);

        // 获得上级用户的当前integral表信息
        //$parent_integral = M('integral')->where('user_id='.$parent['id'])->Field('grow_rate')->find();
        $parent_integral = Db::table('tea_integral')->where('user_id',$parent['id'])->Field('grow_rate')->find();

        // 获得上级用户的购买金额
        //$parent_rec_money = M('user_recharge')->where('user_id='.$parent['id'])->Field('rec_money')->find();
        $parent_rec_money = Db::table('tea_user_recharge')->where('user_id',$parent['id'])->Field('rec_money')->find();

        // 取得上级所有直接下级用户id
        //$lower_users_id = M('user')->where('parent_id='.$parent['id'])->Field('id')->select();
        $lower_users_id = Db::table('tea_user')->where('parent_id',$parent['id'])->Field('id')->select();

        //定义查询上级所有直接下级用户总额条件
        $id_arr['user_id'] = array('in');
        foreach ($lower_users_id as $v)
        {
            $temp_arr[] = $v['id'];
        }
        $id_arr['user_id'][] = $temp_arr;

        // 取得上级所有直接下级用户购买理茶宝总额
        //$lower_users_count_money = M('user_recharge')->where($id_arr)->SUM('rec_money');
        $lower_users_count_money = Db::table('tea_user_recharge')->where($id_arr)->SUM('rec_money');

        // 定义要增加上级用户固定释放增长值 = (下级总额 / 上级额度 * 理茶宝)
        $parent_grow_rate_new['grow_rate'] = intval($lower_users_count_money / $parent_rec_money['rec_money']) * $rate_info['dev_rate'];
        // $parent_grow_rate_new['grow_rate'] = $parent_grow_rate_new['grow_rate'] - $parent_integral['grow_rate'];
        // 最终用户固定释放增长值
        $parent_integral['grow_rate'] = $parent_grow_rate_new['grow_rate'];
        // 如果
        //M('Integral')->where('user_id='.$parent['id'])->save($parent_integral);
        Db::table('tea_integral')->where('user_id',$parent['id'])->update($parent_integral);
    }
    public function texts(){
        $data=$_POST;
        $datas=[
            'test'=>$data,
            'trade_status'=>'显示这个说明异步调用了我这个方法'
        ];
       $res = Db::table('tea_test')->insert($datas);
       return $res;
    }
    /*购买理茶宝的支付宝支付成功后回调地址
            END
   ***/

}