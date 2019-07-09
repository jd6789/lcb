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
use think\View;
use think\Db;
class Wxpay extends Controller{
    //获取微信的配置文件
    public function config(){
        $data=Db::table('tea_wxconfig')->find();
        return $data;
    }
   //PC端的微信支付方式
    public function wxpay($body,$out_trade_no,$total_fee,$notify_url){
        $this->config();
        $data['appid']='wx97c3159a64046d86';
        $data['mch_id']='1459448102';
        $data['trade_type']='NATIVE';
        $data['spbill_create_ip']=$_SERVER["REMOTE_ADDR"];
//        $data['body']='我的茶馆我的店';
        $data['body']=$body;
//        $data['out_trade_no']=time().'168';
        $data['out_trade_no']=$out_trade_no;
//        $data['total_fee']=1;
        $data['total_fee']=$total_fee;
//        $data['notify_url']="http://shop.guochamall.com/test.php";
        $data['notify_url']=$notify_url;
        $data['nonce_str']=uniqid();
        $key="iTeas641guocha77777shanghai021Ac";
        $data['sign']=$this->sign($data,$key);
        $data=$this->chqngeXml($data);
        $url="https://api.mch.weixin.qq.com/pay/unifiedorder";
        $data=$this->acurl($data,$url);
        $arr=(array)simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);
        ob_clean();
        Header("Content-type: image/png");
        $url=$arr['code_url'];
        return $url;
    }
    //数组转换成XML数据格式
    private function chqngeXml($data){
        $xml='';$xml.="<xml>";
        foreach ($data as $key => $value) {
            $xml .= "<{$key}>{$value}</{$key}>";
        }
        $xml.= "</xml>";
        return $xml;
    }
    //通过curl调取微信支付的支付方式
    private function acurl($data,$url){
        $ch=curl_init();
        // dump($data);die;
        $url="https://api.mch.weixin.qq.com/pay/unifiedorder";
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
    private function sign($data,$key){
        $str='';
        ksort($data);
        foreach ($data as $key => $value) {
            $str .= "{$key}={$value}"."&";
        }
        $str .= "key={$key}";
        $str=md5($str);
        $str=strtoupper($str);
        return $str;
    }

    //PC端 的微信回调
    //购买理茶宝的微信支付回调
    //  http://web.licha.com/api/Webwxpay/buyManage.asp
    public function buyManage()
    {
        $data = file_get_contents('php://input');
                $data="<xml><appid><![CDATA[wx97c3159a64046d86]]></appid>
<bank_type><![CDATA[CFT]]></bank_type>
<cash_fee><![CDATA[10]]></cash_fee>
<fee_type><![CDATA[CNY]]></fee_type>
<is_subscribe><![CDATA[Y]]></is_subscribe>
<mch_id><![CDATA[1459448102]]></mch_id>
<nonce_str><![CDATA[5adc8522631d0]]></nonce_str>
<openid><![CDATA[o-u5zwMZpEZTwou61nmv7VPZuCvA]]></openid>
<out_trade_no><![CDATA[6401524195643843168]]></out_trade_no>
<result_code><![CDATA[SUCCESS]]></result_code>
<return_code><![CDATA[SUCCESS]]></return_code>
<sign><![CDATA[ABB26848A691B0B7AB3EEE7BB8033585]]></sign>
<time_end><![CDATA[20180422205051]]></time_end>
<total_fee>1</total_fee>
<trade_type><![CDATA[NATIVE]]></trade_type>
<transaction_id><![CDATA[4200000066201804224196965222]]></transaction_id>
</xml>";
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
                        $data=array(
                            'trade_no'=>$arr['transaction_id'],
                            'pay_status'=>1,
                            'trade_beizhu'=>"交易成功"
                        );
                        Db::table('tea_recharge_cart')->where("recharge_num",$arr['out_trade_no'])->setField($data);
                        $userids=intval($manageInfo['user_id']);
                        $rechargeController=new RechargeCart();
                        $rechargeController->buyUpdate($userids,$arr['out_trade_no'],$arr['transaction_id']);
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
//        //echo file_put_contents("./$num.txt",$data);
//        $pa = '%<out_trade_no><!\[CDATA\[(.*)\]\]></out_trade_no>%';//正则表达式
//        preg_match_all($pa,$data,$matches1);
//        $pas = '%<return_code><!\[CDATA\[(.*)\]\]></return_code>%';//正则表达式
//        preg_match_all($pas,$data,$matches2);
//        $pax = '%<transaction_id><!\[CDATA\[(.*)\]\]></transaction_id>%';//正则表达式
//        preg_match_all($pax,$data,$matches3);
//        //$out_trade_no= $matches1[1][0];
//        $out_trade_no = '2661524214412144168';
//        //$list['return_code'] = $matches2[1][0];
//         $list['return_code'] = 'SUCCESS';
//        //$list['transaction_id'] = $matches3[1][0];
//        $list['transaction_id'] = '4200000072201802067984056132';
//        $manageInfo=Db::table('tea_recharge_cart')->where("recharge_num",$out_trade_no)->find();
//        //$manageInfo=D('RechargeCart')->where('id=2')->find();
//        //dump($manageInfo);die;
//        if($manageInfo['pay_status']==1){
//            //echo 111;
//            return false;
//        }else{
//            $data=array(
//                'trade_no'=>$list['transaction_id'],
//                'pay_status'=>1
//            );
//            Db::table('tea_recharge_cart')->where("recharge_num='$out_trade_no'")->setField($data);
//            $userids=intval($manageInfo['user_id']);
//            $rechargeController=new RechargeCart();
//            $rechargeController->buyUpdate($userids,$out_trade_no,$list['transaction_id']);
//        }
    }
    //升级理茶宝的微信回调
    //  web.jieyu.com/Api/ReturnUrl/againBuyManage
    public function againBuyManage(){
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
                        $data=array(
                            'trade_no'=>$arr['transaction_id'],
                            'pay_status'=>1,
                            'trade_beizhu'=>"交易成功"
                        );
                        Db::table('tea_recharge_cart')->where("recharge_num",$arr['out_trade_no'])->setField($data);
                        $userids=intval($manageInfo['user_id']);
                        $rechargeController=new RechargeCart();
                        $rechargeController->asign($userids,$arr['out_trade_no'],$arr['transaction_id']);
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
//        $data = file_get_contents('php://input');
//        $pa = '%<out_trade_no><!\[CDATA\[(.*)\]\]></out_trade_no>%';//正则表达式
//        preg_match_all($pa,$data,$matches1);
//        $pas = '%<return_code><!\[CDATA\[(.*)\]\]></return_code>%';//正则表达式
//        preg_match_all($pas,$data,$matches2);
//        $pax = '%<transaction_id><!\[CDATA\[(.*)\]\]></transaction_id>%';//正则表达式
//        preg_match_all($pax,$data,$matches3);
//        //$out_trade_no= $matches1[1][0];
//        $out_trade_no = '8131524214647971168';
//        //$list['return_code'] = $matches2[1][0];
//        $list['return_code'] = 'SUCCESS';
//        //$list['transaction_id'] = $matches3[1][0];
//        $list['transaction_id'] = '2018011521001004630215613284';
//        $manageInfo=Db::table('tea_recharge_cart')->where("recharge_num",$out_trade_no)->find();
//        if($list['return_code']=='SUCCESS'){
//            if($manageInfo['pay_status']==1 && $manageInfo['trade_no']==$list['transaction_id'] ){
//                echo 111;
//                return false;
//            }else{
//
//                $data=array(
//                    'trade_no'=>$list['transaction_id'],
//                    'pay_status'=>1
//                );
//                Db::table('tea_recharge_cart')->where("recharge_num='$out_trade_no'")->setField($data);
//                $userids=intval($manageInfo['user_id']);
//                $rechargeController=new RechargeCart();
//                $rechargeController->asign($userids,$out_trade_no,$list['transaction_id']);
//            }
//        }else{
//            echo "no";
//            return false;
//        }
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
                        $data=[
                            'trade_no'=>$arr['transaction_id'],
                            'trade_beizhu'=>"交易成功",
                            'pay_status'=>1,
                            'pay_way'=>2,
                        ];
                        Db::table('tea_money')->where("money_num",$arr['out_trade_no'])->setField($data);
                        //更新钱包的金额
                        $user_id=intval($manageInfo['user_ids']);
                        $last_money=Db::table('tea_user')->where('user_id',$user_id)->find();
                        Db::table('tea_user')->where('user_id',$user_id)->setInc('wallet',floatval($manageInfo['montys']));
                        $logData=array(
                            'user_id'=>$user_id,
                            'surplus_inte'=>floatval($manageInfo['montys']),
                            'introduce'=>"我的钱包充值".floatval($manageInfo['montys']),
                            'wallet'=>1,
                            'sum_inte'=>floatval($last_money['wallet']),
                            'addtime'=>time(),
                            'year'=>date('Y'),
                            'month'=>date('m'),
                            'day'=>date('d'),
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
}