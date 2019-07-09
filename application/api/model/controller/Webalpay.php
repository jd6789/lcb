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
class Webalpay extends Controller{
    public function index(){
        return "Webalpay";
    }
    //配置支付宝的config文件
    private function config(){
        $data=Db::table('tea_alpay_config')->find();
        return $data;
    }
    //移动端支付宝支付   下单购买以及再次购买，不包括升级购买
       public function alpay($out_trade_no='',$total_amount='0.01',$subject='我的茶馆',$body='我的茶馆',$setReturnUrl,$notify_url){
        $config=$this->config();
          // $config['gatewayUrl']='https://openapi.alipaydev.com/gateway.do';
        require_once("./Pay/webalpay/wappay/service/AlipayTradeService.php");
        require_once("./Pay/webalpay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php");
        if (!empty($out_trade_no)&& trim($out_trade_no)!=""){
            //超时时间
            $timeout_express="15d";
            $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setTimeExpress($timeout_express);
            $payResponse = new \AlipayTradeService($config);
            $result=$payResponse->wapPay($payRequestBuilder,$setReturnUrl,$notify_url);
            return ;
        }
    }

    /*购买理茶宝的支付宝支付成功后回调地址
          START
   ***/
    //同步回调地址(购买理茶宝的回调)
    public function buyReturnUrl()
    {
        //用户购买理茶宝支付宝支付后的回调地址
        $out_trade_no = htmlspecialchars($_GET['out_trade_no']);//商户订单号
        $trade_no = htmlspecialchars($_GET['trade_no']);//支付宝交易号
        $order_info = Db::table('tea_recharge_cart')->where('recharge_num', $out_trade_no)->find();
        if (intval($order_info['pay_status'] == 1)) {
            $this->success('支付成功，即将跳转到个人中心', 'newapp/user/index');

        } else {
            $this->error('支付宝内部处理中，稍后支付详情信息会在个人中心显示，即将跳转到个人中心', 'newapp/user/index');
        }
    }
    public function buyNotiflUrl(){
        $trade_status=$_POST['trade_status'];      //支付宝返回回来的支付状态
        $out_trade_no=$_POST['out_trade_no'];   //原支付请求的商户订单号
        $order_info=Db::table('tea_recharge_cart')->where('recharge_num',"$out_trade_no")->find();
        $data = file_get_contents('php://input');
        $datas=[
            'test'=>$data,
            'trade_status'=>$trade_status
        ];
        Db::table('tea_test')->insert($datas);
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
                        if(intval($cartInfo['is_gift'])==1){
                            $data = array(
                                'user_id' => $user_id,
                                'rec_money' => floatval($rechargeInfo['rec_money']),
                                'subject' => $rechargeInfo['subject'],
                                'body' => $rechargeInfo['body'],
                                'rec_lev' => $rechargeInfo['rec_lev'],
                                'total_inte' => floatval($rechargeInfo['total_inte'])-floatval($rechargeInfo['gift']),
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
                                'pay_status' =>1,
                                'is_gifts' =>1,
                                'gift_inte'=>$rechargeInfo['gift'],
                                'year'=>date('Y'),
                                'month'=>date('m'),
                                'day'=>date('d'),
                            );
                        }else{
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
                                'pay_status' =>1,
                                'gift_inte'=>0,
                                'is_gifts' =>0,
                                'year'=>date('Y'),
                                'month'=>date('m'),
                                'day'=>date('d'),
                            );
                        }

                        Db::table('tea_user_recharge')->insert($data);
                        $url = "http://".$_SERVER['HTTP_HOST']."/tmvip/Integral/grow_rate?user_id=$user_id";
                        $ch=curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_exec($ch);
                        curl_close($ch);
                        $url = "http://".$_SERVER['HTTP_HOST']."/tmvip/Integral/InteReturn?user_id=$user_id";
                        $ch=curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_exec($ch);
                        curl_close($ch);
                    }

                }
            }elseif(intval($order_info['pay_status']==1)){
                //执行完返回支付宝成功信息
                echo  success;
            }

        }
    }
    /*购买理茶宝的支付宝支付成功后回调地址
        END
  ***/

    /*购买理茶宝的支付宝支付成功后回调地址
        START
 ***/
    //同步回调地址(升级理茶宝的回调)
    public function updateReturnUrl()
    {
        //用户购买理茶宝支付宝支付后的回调地址
        $out_trade_no = htmlspecialchars($_GET['out_trade_no']);//商户订单号
        $trade_no = htmlspecialchars($_GET['trade_no']);//支付宝交易号
        $order_info = Db::table('tea_recharge_cart')->where('recharge_num', $out_trade_no)->find();
        if (intval($order_info['pay_status'] == 1)) {
            $this->success('支付成功，即将跳转到个人中心', 'newapp/user/index');

        } else {
            $this->error('支付宝内部处理中，稍后支付详情信息会在个人中心显示，即将跳转到个人中心', 'newapp/user/index');
        }
    }
    public function updateNotiflUrl(){
        $trade_status=$_POST['trade_status'];      //支付宝返回回来的支付状态
        $trade_status='TRADE_SUCCESS';      //支付宝返回回来的支付状态
        $out_trade_no=$_POST['out_trade_no'];   //原支付请求的商户订单号
        $out_trade_no='5601524828208923164';   //原支付请求的商户订单号
        $order_info=Db::table('tea_recharge_cart')->where('recharge_num',"$out_trade_no")->find();
        $data = file_get_contents('php://input');
        $datas=[
            'test'=>$data,
            'trade_status'=>$trade_status
        ];
        //Db::table('tea_test')->insert($datas);
        if($order_info){
            if(intval($order_info['pay_status']==0)){
                if($trade_status == 'TRADE_SUCCESS'){
                    //支付成功，开始处理业务逻辑
                    $total_amount=$_POST['total_amount'];   //支付成功后的实际支付的金额
                    $total_amount='10000';   //支付成功后的实际支付的金额
                    $trade_no=$_POST['trade_no'];           //支付宝内部的交易号
                    $trade_no='2018042721001004350200332519';           //支付宝内部的交易号
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
                        //首先通过订单ID以及用户ID查询出该条信息，然后将信息获取后替换到用户的购买记录表
                        $cartInfo = Db::table('tea_recharge_cart')
                            ->where("user_id = $user_id AND recharge_num = '$out_trade_no'")
                            ->find();
                        //查询出用户充值的记录表。根据id将升级的数据更新到表中
                        $userRecharge=Db::table('tea_user_recharge')
                            //->field('id,gift_inte,is_gifts')
                            ->where('user_id='.$user_id)
                            ->order('addtime desc')
                            ->limit(1)
                            ->find();
                        $jifenInfo=Db::table('tea_integral')
                            ->where("user_id=$user_id AND is_return=1")
                            ->order('id desc')
                            ->limit(1)->find();
                        //然后通过积分购物车表查询的充值表ID查询出对应的记录
                        $rechargeInfo = Db::table('tea_recharge')->where('id='.$cartInfo['recharge_id'])->find();
                       //判断用户是否选择礼包
                        if(intval($cartInfo['is_gift'])==0){
                            $data = array(
                                'rec_money' => $rechargeInfo['rec_money'],
                                'trade_no'  =>$trade_no,
                                'rec_lev' => $rechargeInfo['rec_lev'],
                                'total_inte' => floatval($rechargeInfo['total_inte'])-floatval($userRecharge['gift_inte']),  //用应返积分减去上次购买的赠送的积分，不管上次是购买还是升级
                                'init_rates' => $rechargeInfo['init_rates'],
                                'fir_rec' => $rechargeInfo['fir_rec'],
                                'sec_rec' => $rechargeInfo['sec_rec'],
                                'recharger_num'  =>$out_trade_no,
                                'gift_inte'=>$userRecharge['gift_inte'],
                                'is_gifts'=>$userRecharge['is_gifts'],
                                'year'=>date('Y'),
                                'month'=>date('m'),
                                'day'=>date('d'),

                            );
                            Db::table('tea_user_recharge')->where('id=',$userRecharge['id'])->setField($data);
                            /*更新积分表的字段是
                                total_sum   需返还的积分等于这次应返积分减掉上次选择礼包的积分
                                surplus_inte     剩余返还的积分等于现在剩余的应返积分再减掉上次选择礼包的积分
                            */
                            $datae=array(
                                'total_sum'=>floatval($rechargeInfo['total_inte'])-floatval($userRecharge['gift_inte']),
                                'surplus_inte'=>$rechargeInfo['total_inte']-floatval($jifenInfo['back_inte'])-floatval($userRecharge['gift_inte']),
                            );
                            Db::table('tea_integral')->where('id=',$jifenInfo['id'])->setField($datae);
                        }else{
                            //选择了礼包
                            $data = array(
                                'rec_money' => $rechargeInfo['rec_money'],
                                'trade_no'  =>$trade_no,
                                'rec_lev' => $rechargeInfo['rec_lev'],
                                'total_inte' => floatval($rechargeInfo['total_inte'])-floatval($rechargeInfo['gift']),  //用应返积分减去上次购买的赠送的积分再减去选择礼包赠送的积分，不管上次是购买还是升级
                                'init_rates' => $rechargeInfo['init_rates'],
                                'fir_rec' => $rechargeInfo['fir_rec'],
                                'sec_rec' => $rechargeInfo['sec_rec'],
                                'recharger_num'  =>$out_trade_no,
                                'gift_inte'=>floatval($rechargeInfo['gift']),  //总礼包积分数等于上次选择的礼包积分数加上这次选择礼包的积分数
                                'is_gifts'=>1,
                                'year'=>date('Y'),
                                'month'=>date('m'),
                                'day'=>date('d'),

                            );
                            Db::table('tea_user_recharge')->where('id',$userRecharge['id'])->setField($data);
                            /*更新积分表的字段是
            total_sum   需返还的积分等于这次应返总积分减掉这次选择礼包的积分数再减掉上次选择礼包的积分
            surplus_inte     剩余返还的积分等于现在剩余的应返积分减掉这里选择礼包的积分数再减掉上次选择礼包的积分
            tea_ponit_inte   上次的总茶点数加上这次礼包应该返还的积分数
            */
                            $datae=array(
                                'total_sum'=>floatval($rechargeInfo['total_inte'])-floatval($rechargeInfo['gift']),
                                'surplus_inte'=>$rechargeInfo['total_inte']-floatval($jifenInfo['back_inte'])-floatval($rechargeInfo['gift']),
                                'tea_ponit_inte'=>floatval($jifenInfo['tea_ponit_inte']) + (floatval($rechargeInfo['gift'])-floatval($userRecharge['gift_inte']))
                            );
                            Db::table('tea_integral')->where('id',$jifenInfo['id'])->setField($datae);
                            //更新积分表还要更新用户表
                            $user=Db::table('tea_user')->field('tea_ponit_inte,tea_inte')->where('user_id',$user_id)->find();
                            $user_data=array(
                                'tea_ponit_inte'=>floatval($user['tea_ponit_inte'])+(floatval($rechargeInfo['gift'])-floatval($userRecharge['gift_inte']))
                            );
                            Db::table('tea_user')->where('user_id',$user_id)->setField($user_data);
                        }

                        $url = "http://".$_SERVER['HTTP_HOST']."/tmvip/Integral/grow_rate?user_id=$user_id";
                        $ch=curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_exec($ch);
                        curl_close($ch);
                        $url = "http://".$_SERVER['HTTP_HOST']."/tmvip/Integral/InteReturn?user_id=$user_id";
                        $ch=curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_exec($ch);
                        curl_close($ch);
                    }

                }
            }elseif(intval($order_info['pay_status']==1)){
                //执行完返回支付宝成功信息
                echo  success;
            }

        }
    }
    /*购买理茶宝的支付宝支付成功后回调地址
        END
  ***/

    /*钱包下单的支付成功回调地址
       START
        ***/
    public function moneyReturnUrl(){

        //用户购买理茶宝支付宝支付后的回调地址
        $out_trade_no = htmlspecialchars($_GET['out_trade_no']);//商户订单号
        //$trade_no = htmlspecialchars($_GET['trade_no']);//支付宝交易号
        $order_info = Db::table('tea_money')->where('money_num', $out_trade_no)->find();
        if (intval($order_info['pay_status'] == 1)) {
            $this->success('支付成功，即将跳转到个人中心', 'newapp/user/index');

        } else {
            $this->error('支付宝内部处理中，稍后支付详情信息会在个人中心显示，即将跳转到个人中心', 'newapp/user/index');
        }
    }
    public function moneyNotiflUrl(){
        $trade_status=$_POST['trade_status'];      //支付宝返回回来的支付状态
        $out_trade_no=$_POST['out_trade_no'];   //原支付请求的商户订单号
        $total_amount=$_POST['total_amount'];   //支付成功后的实际支付的金额
        $order_info=Db::table('tea_money')->where('money_num',"$out_trade_no")->find();
        if($order_info){
            if(intval($order_info['pay_status'])==0) {
                if ($trade_status == 'TRADE_SUCCESS') {
                    $trade_no = $_POST['trade_no'];           //支付宝内部的交易号
                        //订单存在，判断金额是否一致，防止金额伪造
                        if (floatval($order_info['montys']) == floatval($total_amount)) {
                            $data = [
                                'trade_no' => $trade_no,
                                'trade_beizhu' => "交易成功",
                                'pay_status' => 1,
                                'pay_way' => 2,
                            ];
                            Db::table('tea_money')->where("money_num", $out_trade_no)->setField($data);
                            $last_money=Db::table('tea_user')->where('user_id',intval($order_info['user_ids']))->find();
                            //支付成功后更新数据库
                            Db::table('tea_user')->where('user_id',intval($order_info['user_ids']))->setInc('wallet',floatval($order_info['montys']));
                            $logData=array(
                                'user_id'=>intval($order_info['user_ids']),
                                'surplus_inte'=>'+'.floatval($order_info['montys']),
                                'introduce'=>"我的钱包充值".floatval($order_info['montys']),
                                'wallet'=>1,
                                'sum_inte'=>floatval($last_money['wallet']),
                                'addtime'=>time(),
                                'year'=>date('Y'),
                                'month'=>date('m'),
                                'day'=>date('d'),
                                'log_out_trade_no'=>date('Y').date('m').date('d').uniqid().'168'
                            );
                            Db::table('tea_integral_log')->insert($logData);
                        } else {
                            $data = [
                                'trade_no' => $trade_no,
                                'trade_beizhu' => "订单金额跟订单金额不一致，实际支付金额为" . $total_amount,
                                'pay_status' => 2
                            ];
                            Db::table('tea_money')->where("money_num", $out_trade_no)->setField($data);
                        }
                    }
            } else {
                //执行完返回支付宝成功信息
                echo success;
            }
        }
    }
    /*钱包下单的支付成功回调地址
       END
        ***/

    /*购买理茶宝的支付宝支付成功后回调地址
          START
   ***/
    //同步回调地址(购买理茶宝的回调)
    public function shareholderReturnUrl()
    {
        //用户购买理茶宝支付宝支付后的回调地址
        $out_trade_no = htmlspecialchars($_GET['out_trade_no']);//商户订单号
        $trade_no = htmlspecialchars($_GET['trade_no']);//支付宝交易号
        $order_info = Db::table('tea_recharge_cart')->where('recharge_num', $out_trade_no)->find();
        if (intval($order_info['pay_status'] == 1)) {
            $this->success('支付成功，即将跳转到个人中心', 'partner/index/index');

        } else {
            $this->error('支付宝内部处理中，稍后支付详情信息会在个人中心显示，即将跳转到个人中心', 'partner/index/index');
        }
    }
    public function shareholderNotiflUrl(){
        $trade_status=$_POST['trade_status'];      //支付宝返回回来的支付状态
        $out_trade_no=$_POST['out_trade_no'];   //原支付请求的商户订单号
        $order_info=Db::table('tea_recharge_cart')->where('recharge_num',"$out_trade_no")->find();
        $data = file_get_contents('php://input');
        $datas=[
            'test'=>$data,
            'trade_status'=>$trade_status
        ];
        Db::table('tea_test')->insert($datas);
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
                        //首先通过订单ID以及用户ID查询出该条信息，然后将信息获取后替换到用户的购买记录表
                        $cartInfo = Db::table('tea_recharge_cart')
                            ->where("user_id = $user_id AND recharge_num = '$out_trade_no'")
                            ->find();
                        //然后通过积分购物车表查询的充值表ID查询出对应的记录
                        $rechargeInfo = Db::table('tea_new_recharge')->where('recharge_id',$cartInfo['recharge_id'])->find();
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
                                'cap_rates' => 0,
                                'reg_rec' => 0,
                                'sec_reg_rec' =>0,
                                'is_first' =>0,
                                'addtime'   =>time(),
                                'tea_inte_rate' => 0,
                                'tea_score_rate' => 0,
                                'recharger_num'  =>$out_trade_no,
                                'trade_no'  =>$trade_no,
                                'pay_status' =>1,
                                'gift_inte'=>0,
                                'is_gifts' =>0,
                                'year'=>date('Y'),
                                'month'=>date('m'),
                                'day'=>date('d'),
                                'is_ceo'=>1,
                            );
                        Db::table('tea_user_recharge')->insert($data);
                        $url = "http://".$_SERVER['HTTP_HOST']."/tmvip/Integral/grow_rate?user_id=$user_id";
                        $ch=curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_exec($ch);
                        curl_close($ch);
                        $url = "http://".$_SERVER['HTTP_HOST']."/tmvip/Integral/InteReturn?user_id=$user_id";
                        $ch=curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_exec($ch);
                        curl_close($ch);
                    }

                }
            }elseif(intval($order_info['pay_status']==1)){
                //执行完返回支付宝成功信息
                echo  success;
            }

        }
    }
    /*购买理茶宝的支付宝支付成功后回调地址
        END
  ***/











}