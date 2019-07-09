<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/4/10
 * Time: 11:26
 */
namespace app\api\controller;
use app\appmobile\model\RechargeCart;
use think\Controller;
use think\Db;
use think\View;
class Webwxpay extends Controller{
    private $appid='wx97c3159a64046d86';        //微信商户appid
    private $mch_id='1459448102';               //微信商户id
    /**
     * 移动端微信支付方法
     * @param  $out_trade_no            需要支付的订单号
     *@param  $total_amount             需要支付的金额，订单的金额乘以100
     *@param  $body                     需要支付的订单的商品的说明
     *@param  $notify_url               支付成功后的同步回调地址
     */
    public function webWxPay($body,$out_trade_no,$total_fee,$notify_url){
        $data['appid']=$this->appid;
        $data['mch_id']=$this->mch_id;
        $data['trade_type']='NATIVE';                           //支付形式
        $data['spbill_create_ip']=$_SERVER["REMOTE_ADDR"];      //支付客户端的IP
        $data['body']=$body;                                    //需要支付的订单的商品的说明
        $data['out_trade_no']=$out_trade_no;                    //需要支付的订单号
        $data['total_fee']=$total_fee*100;                      //需要支付的金额
        $data['notify_url']=$notify_url;                        //支付成功后的同步回调地址
        $data['nonce_str']=uniqid();                            //加密延签的随机码
        $data['sign']=$this->sign($data);                       //加密后的延签
        $data=$this->chqngeXml($data);                          //将所有请求数据转换成xml数据请求
        $url="https://api.mch.weixin.qq.com/pay/unifiedorder";  //请求的API
        $data=$this->acurl($data,$url);                         //开始请求
        $arr=(array)simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);    //将返回的xml数据转换成数据
        $url=$arr['code_url'];
        return $url;
    }


    //购买理茶宝的微信支付回调
    //  http://web.licha.com/api/Webwxpay/buyManage.asp
    public function buyManage()
    {
        $data = file_get_contents('php://input');
        $arr=(array)simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);
        if($arr['result_code'] != 'SUCCESS'){
            return false;
        }else{
            //通过订单号查询订单是否存在
            $manageInfo=Db::table('tea_recharge_cart')->where("recharge_num",$arr['out_trade_no'])->find();
            if($manageInfo){
                if($manageInfo['pay_status']==1){
                    //echo 111;
                    return false;
                }elseif ($manageInfo['pay_status']==2) {
                    return false;
                }else{
                    //订单存在，判断金额是否一致，防止金额伪造
                    if(floatval($manageInfo['recharge_money'])*100 == floatval($arr['cash_fee'])){
                        //首先修改订单的状态
                        $data=[
                            'pay_status'=>1,
                            'trade_no'=>$arr['transaction_id'],
                            'trade_beizhu'=>'交易成功',
                            'pay_way'=>2
                        ];
                        Db::table('tea_recharge_cart')->where('recharge_num',$arr['out_trade_no'])->setField($data);
                        //修改的同时处理理茶宝的一些信息
                        $user_id=intval($manageInfo['user_id']);
                        //$model=new RechargeCart();
                        //$model->buyUpdate($user_id,$out_trade_no,$trade_no);
                        //首先通过订单ID以及用户ID查询出该条信息，然后将信息获取后替换到用户的购买记录表
                        $cartInfo = Db::table('tea_recharge_cart')
                            ->where("user_id",$user_id)
                            ->where('recharge_num',$arr['out_trade_no'])
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
                                'recharger_num'  =>$arr['out_trade_no'],
                                'trade_no'  =>$arr['transaction_id'],
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
                                'recharger_num'  =>$arr['out_trade_no'],
                                'trade_no'  =>$arr['transaction_id'],
                                'pay_status' =>1,
                                'gift_inte'=>0,
                                'is_gifts' =>0,
                                'year'=>date('Y'),
                                'month'=>date('m'),
                                'day'=>date('d'),
                            );
                        }
                        Db::table('tea_user_recharge')->insert($data);
                        $url = "http://love1314.ink/tmvip/Integral/grow_rate?user_id=$user_id";
                        $ch=curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_exec($ch);
                        curl_close($ch);
                        $url = "http://love1314.ink/tmvip/Integral/InteReturn?user_id=$user_id";
                        $ch=curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_exec($ch);
                        curl_close($ch);
                    }else{
                        $data=[
                            'trade_no'=>$arr['transaction_id'],
                            'trade_beizhu'=>"订单金额跟订单金额不一致，实际支付金额为".$arr['cash_fee']/100,
                            'pay_status'=>2
                        ];
                        Db::table('tea_recharge_cart')->where("recharge_num",$arr['out_trade_no'])->setField($data);
//                    金额不一致
                        return false;
                    }
                }
            }else{
                return false;
            }
        }
    }
    //升级理茶宝的微信回调
    //  web.jieyu.com/Api/ReturnUrl/againBuyManage
    public function againBuyManage(){
        $data = file_get_contents('php://input');
        $arr=(array)simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);
        if($arr['result_code'] != 'SUCCESS'){
            return false;
        }else {
            //通过订单号查询订单是否存在
            $manageInfo = Db::table('tea_recharge_cart')->where("recharge_num", $arr['out_trade_no'])->find();
            if ($manageInfo) {
                if ($manageInfo['pay_status'] == 1) {
                    return false;
                } elseif ($manageInfo['pay_status'] == 2) {
                    return false;
                } else {
                    //订单存在，判断金额是否一致，防止金额伪造
                    if (floatval($manageInfo['recharge_money']) * 100 == floatval($arr['cash_fee'])) {

                        $out_trade_no = $arr['out_trade_no'];
                        $trade_no = $arr['transaction_id'];
                        //首先修改订单的状态
                        $data = [
                            'pay_status' => 1,
                            'trade_no' => $trade_no,
                            'trade_beizhu' => '交易成功',
                            'pay_way'=>2
                        ];
                        Db::table('tea_recharge_cart')->where('recharge_num', $out_trade_no)->setField($data);
                        //修改的同时处理理茶宝的一些信息
                        $user_id = intval($manageInfo['user_id']);
                        //首先通过订单ID以及用户ID查询出该条信息，然后将信息获取后替换到用户的购买记录表
                        $cartInfo = Db::table('tea_recharge_cart')
                            ->where("user_id = $user_id AND recharge_num = '$out_trade_no'")
                            ->find();
                        //然后通过积分购物车表查询的充值表ID查询出对应的记录
                        $rechargeInfo = Db::table('tea_recharge')->where('id' ,$cartInfo['recharge_id'])->find();
                        $rate = Db::table('tea_rate')->find();
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
                        if (intval($cartInfo['is_gift']) == 1) {
                            //选择了礼包的升级
                            $data = array(
                                'rec_money' => $rechargeInfo['rec_money'],
                                'trade_no'  =>$trade_no,
                                'rec_lev' => $rechargeInfo['rec_lev'],
                                'total_inte' => floatval($rechargeInfo['total_inte'])-floatval($rechargeInfo['gift']),  //用应返积分减去上次购买的赠送的积分再减去选择礼包赠送的积分，不管上次是购买还是升级
                                'init_rates' => $rechargeInfo['init_rates'],
                                'fir_rec' => $rechargeInfo['fir_rec'],
                                'sec_rec' => $rechargeInfo['sec_rec'],
                                'recharger_num'  =>$out_trade_no,
                                'gift_inte'=>floatval($rechargeInfo['gift']),  //总礼包积分数等于这次选择礼包的积分数
                                'is_gifts'=>1,
                                'year'=>date('Y'),
                                'month'=>date('m'),
                                'day'=>date('d'),

                            );
                           Db::table('tea_user_recharge')->where('id',$userRecharge['id'])->setField($data);
                            $jifenInfo=Db::table('tea_integral')
                                ->where("user_id=$user_id AND is_return=1")
                                ->order('id desc')
                                ->limit(1)->find();
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
                        } else {
                            //升级没有选择礼包
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
                           Db::table('tea_user_recharge')->where('id',$userRecharge['id'])->setField($data);
                            /*更新积分表的字段是
           total_sum   需返还的积分等于这次应返积分减掉上次选择礼包的积分
          surplus_inte     剩余返还的积分等于现在剩余的应返积分再减掉上次选择礼包的积分
           */
                            $datae=array(
                                'total_sum'=>floatval($rechargeInfo['total_inte'])-floatval($userRecharge['gift_inte']),
                                'surplus_inte'=>$rechargeInfo['total_inte']-floatval($jifenInfo['back_inte'])-floatval($userRecharge['gift_inte']),
                            );
                            Db::table('tea_integral')->where('id',$jifenInfo['id'])->setField($datae);
                        }

                        $url = "http://love1314.ink/tmvip/Integral/grow_rate?user_id=$user_id";
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_exec($ch);
                        curl_close($ch);
                        $url = "http://love1314.ink/tmvip/Integral/InteReturn?user_id=$user_id";
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_exec($ch);
                        curl_close($ch);


                    } else {
                        $data = [
                            'trade_no' => $arr['transaction_id'],
                            'trade_beizhu' => "订单金额跟订单金额不一致，实际支付金额为" . $arr['cash_fee'] / 100,
                            'pay_status' => 2
                        ];
                        Db::table('tea_recharge_cart')->where("recharge_num", $arr['out_trade_no'])->setField($data);
//                    金额不一致
                        return false;
                    }
                }
            } else {
                return false;
            }
        }

    }
    //钱包充值成功的微信回调
    public function moneyUrl(){
        $data = file_get_contents('php://input');
//        $data="<xml><appid><![CDATA[wx97c3159a64046d86]]></appid>
//<bank_type><![CDATA[CFT]]></bank_type>
//<cash_fee><![CDATA[1]]></cash_fee>
//<fee_type><![CDATA[CNY]]></fee_type>
//<is_subscribe><![CDATA[Y]]></is_subscribe>
//<mch_id><![CDATA[1459448102]]></mch_id>
//<nonce_str><![CDATA[5adc8522631d0]]></nonce_str>
//<openid><![CDATA[o-u5zwMZpEZTwou61nmv7VPZuCvA]]></openid>
//<out_trade_no><![CDATA[3251524281794486168]]></out_trade_no>
//<result_code><![CDATA[SUCCESS]]></result_code>
//<return_code><![CDATA[SUCCESS]]></return_code>
//<sign><![CDATA[ABB26848A691B0B7AB3EEE7BB8033585]]></sign>
//<time_end><![CDATA[20180422205051]]></time_end>
//<total_fee>1</total_fee>
//<trade_type><![CDATA[NATIVE]]></trade_type>
//<transaction_id><![CDATA[4200000066201804224196965222]]></transaction_id>
//</xml>";
        $arr=(array)simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);
        //首先判断是否支付成功
        if($arr['result_code'] != 'SUCCESS'){
            return false;
        }else{
            //通过订单号查询订单是否存在
            $manageInfo=Db::table('tea_money')->where("money_num",$arr['out_trade_no'])->find();
            if($manageInfo){
                //判断订单是否已经支付过，就是防止多次回调请求数据库
                if(intval($manageInfo['pay_status']==1)){
                    return false;
                }elseif (intval($manageInfo['pay_status']==2)){
                    return false;
                } else{
                    //订单存在，判断金额是否一致，防止金额伪造
                    if(floatval($manageInfo['montys'])*100 == floatval($arr['cash_fee'])){
                        $out_trade_no = $arr['out_trade_no'];
                        $trade_no = $arr['transaction_id'];
                        $data = [
                            'trade_no' => $trade_no,
                            'trade_beizhu' => "交易成功",
                            'pay_status' => 1,
                            'pay_way' => 2,
                        ];
                        Db::table('tea_money')->where("money_num", $out_trade_no)->setField($data);
                        $last_money=Db::table('tea_user')->where('user_id',intval($manageInfo['user_ids']))->find();
                        //支付成功后更新数据库
                        Db::table('tea_user')->where('user_id',intval($manageInfo['user_ids']))->setInc('wallet',floatval($manageInfo['montys']));
                        $logData=array(
                            'user_id'=>intval($manageInfo['user_ids']),
                            'surplus_inte'=>'+'.floatval($manageInfo['montys']),
                            'introduce'=>"我的钱包充值".floatval($manageInfo['montys']),
                            'wallet'=>1,
                            'sum_inte'=>floatval($last_money['wallet']),
                            'addtime'=>time(),
                            'year'=>date('Y'),
                            'month'=>date('m'),
                            'day'=>date('d'),
                            'log_out_trade_no'=>date('Y').date('m').date('d').uniqid().'168'
                        );
                        Db::table('tea_integral_log')->insert($logData);
                    }else{
                        $data=[
                            'trade_no'=>$arr['transaction_id'],
                            'trade_beizhu'=>"订单金额跟订单金额不一致，实际支付金额为".$arr['cash_fee']/100,
                            'pay_status'=>2
                        ];
                        Db::table('tea_money')->where("money_num",$arr['out_trade_no'])->setField($data);
//                    金额不一致
                        return false;
                    }
                }

            }else{
                return false;
            }

        }


    }

    public function test(){
        // require_once 'phpqrcode.php';
        //      */
        // const APPID = 'wx97c3159a64046d86';
        // const MCHID = '1459448102';
        // const KEY = 'iTeas641guocha77777shanghai021Ac';
        // const APPSECRET = '05bb81ed77cf0dc8fbd2a56e7f3f8aec';
        $data['appid']='wx97c3159a64046d86';
        $data['mch_id']='1459448102';
        $data['trade_type']='NATIVE';
        $data['spbill_create_ip']=$_SERVER["REMOTE_ADDR"];
        $data['body']='我的茶馆我的店';
        $data['out_trade_no']=time().'168';
        $data['total_fee']=1;
        //$data['product_id']=rand(100,9999);
        $data['notify_url']="http://shop.guochamall.com/test.php";
        $data['nonce_str']=uniqid();
        $data['sign']=$this->sign($data);
        $data=$this->chqngeXml($data);
        $url="https://api.mch.weixin.qq.com/pay/unifiedorder";
        $data=$this->acurl($data,$url);
        $arr=(array)simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);
        ob_clean();
        Header("Content-type: image/png");
        //dump($arr);die;
        //$a=new \QRcode();
        //$url=urldecode($arr['code_url']);
        $url=$arr['code_url'];
        //$data=$this->index($url,'','');
        // return $a->png($url);

        $view=new View();
        $view->assign('data',$url);
        return $view->fetch();

    }
    //数组转换成XML数据格式
    public function chqngeXml($data){
        $xml='';$xml.="<xml>";
        foreach ($data as $key => $value) {
            $xml .= "<{$key}>{$value}</{$key}>";
        }
        $xml.= "</xml>";
        return $xml;
    }
    //通过curl调取微信支付的支付方式
    public function acurl($data,$url){
        $ch=curl_init();
//        $url="https://api.mch.weixin.qq.com/pay/unifiedorder";
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // 运行cURL，请求网页
        $data = curl_exec($ch);
        //dump($data);die;
        if($data){
            // 关闭URL请求
            curl_close($ch);
            return $data;
        }else{
            return curl_error($ch);
        }

    }
    //签名加密的方法
    public function sign($data){
        $str='';
        ksort($data);
        foreach ($data as $key => $value) {
            $str .= "{$key}={$value}"."&";
        }
        $str .= "key=iTeas641guocha77777shanghai021Ac";
        $str=md5($str);
        $str=strtoupper($str);
        return $str;
    }
}