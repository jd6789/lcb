<?php
/**
 * Created by PhpStorm.
 * User: jacky-fu
 * Date: 2018/4/28
 * Time: 9:27
 */
namespace app\api\controller;
use think\Db;
use think\Controller;
class Apitest extends Controller{
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
        $data['notify_url']="http://shop.guochamall.com/test.php";
        $data['nonce_str']=uniqid();
        $data['sign']=$this->sign($data);
        $data=$this->chqngeXml($data);
        $url="https://api.mch.weixin.qq.com/pay/unifiedorder";
        $data=$this->acurl($data,$url);
        //dump($data);
        //$arr=(array)simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);
        //ob_clean();
        //Header("Content-type: image/png");
        //dump($arr);die;
        //$a=new \QRcode();
        //$url=urldecode($arr['code_url']);
        //$url=$arr['code_url'];
        //$data=$this->index($url,'','');
        // return $a->png($url);

//        $view=new View();
//        $view->assign('data',$url);
//        return $view->fetch();
            $data="<xml><return_code><![CDATA[SUCCESS]]></return_code>
<return_msg><![CDATA[OK]]></return_msg>
<appid><![CDATA[wx97c3159a64046d86]]></appid>
<mch_id><![CDATA[1459448102]]></mch_id>
<nonce_str><![CDATA[5P90CkG5mdqYdTIV]]></nonce_str>
<sign><![CDATA[CA72230389175EA154D1D65530B11B04]]></sign>
<result_code><![CDATA[SUCCESS]]></result_code>
<prepay_id><![CDATA[wx280931422687150140b261971918681885]]></prepay_id>
<trade_type><![CDATA[JSAPI]]></trade_type>
</xml>";
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