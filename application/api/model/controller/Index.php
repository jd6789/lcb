<?php
namespace app\api\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
        $out_trade_no='2041523266798646168';
        $total_amount='10';
        $subject='我的茶馆001';
        $body='我的茶馆002';
        $setReturnUrl='http://love1314.ink/api/alpay/buyReturnUrl';
        $notify_url='http://love1314.ink/api/alpay/buyNotiflUrl';
        $model=new Alpay();
        $model->testAlpay($out_trade_no,$total_amount,$subject,$body,$setReturnUrl,$notify_url);
    }
    public function test(){

        $url = "http://love1314.ink/api/alpay/texts?user_id=32";
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        echo $output;
    }
}
