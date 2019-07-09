<?php
/**
 * Created by PhpStorm.
 * User: jacky-fu
 * Date: 2018/4/28
 * Time: 9:23
 */
namespace  app\api\controller;
use think\Controller;
use think\Db;
class Testpay extends Controller {
    public function test(){
        // require_once 'phpqrcode.php';
        //      */
        // const APPID = 'wx97c3159a64046d86';
        // const MCHID = '1459448102';
        // const KEY = 'iTeas641guocha77777shanghai021Ac';
        // const APPSECRET = '05bb81ed77cf0dc8fbd2a56e7f3f8aec';
        $data['appid']='wx97c3159a64046d86';
        $data['mch_id']='1459448102';
        $data['trade_type']='JSAPI';
        $data['spbill_create_ip']=$_SERVER["REMOTE_ADDR"];
        $data['body']='我的茶馆我的店';
        $data['out_trade_no']=time().'168';
        $data['total_fee']=1;
        $data['openid']='o-u5zwMZpEZTwou61nmv7VPZuCvA';
        //$data['product_id']=rand(100,9999);
        $data['notify_url']="http://love1314.ink/api/testpay/a";
        $data['nonce_str']=uniqid();
        $data['sign']=$this->sign($data);
        $data=$this->chqngeXml($data);
        $url="https://api.mch.weixin.qq.com/pay/unifiedorder";
        $data=$this->acurl($data,$url);
        $arr=(array)simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);
        $urls['package']='prepay_id='.$arr['prepay_id'];
        $urls['nonceStr']=uniqid();
        $urls['appId']=$arr['appid'];
        $urls['timeStamp']=time();
        $urls['signType']='MD5';
        $urls['paySign']=$this->sign($urls);
        //$view=new View();
        $this->assign('data',$urls);
        return $this->fetch();

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

    public function a(){
        $num=time();
        $data = file_get_contents('php://input');
        $arr=(array)simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);
        file_put_contents('./test/'."$num.txt",$arr);
    }
    //获取用户openid
    public function openid(){
        require_once "../vendor/wapWxpay/example/WxPay.JsApiPay.php";
        //vendor('wapWxpay.example.WxPay.JsApiPay.php');
        $tools = new \JsApiPay();
//$openId ='o-u5zwMZpEZTwou61nmv7VPZuCvA';
        $openId = $tools->GetOpenid();
        var_dump($openId);die;
    }
}