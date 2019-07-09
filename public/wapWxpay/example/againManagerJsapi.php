<?php
ini_set('date.timezone','Asia/Shanghai');
header("content-type:text/html;charset=utf-8");
//error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once "./WxPay.JsApiPay.php";
require_once "../logs/log.php";

//初始化日志
//$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
//$log = Log::Init($logHandler, 15);
//①、获取用户openid
$tools = new JsApiPay();
//$openId ='o-u5zwMZpEZTwou61nmv7VPZuCvA';
$openId = $tools->GetOpenid();
var_dump($openId);die;
$body=urldecode($_GET['body']);
$body="我的茶馆我的店";
$money=intval(floatval($_GET['money'])*100);
$money="1";
$attach=$_GET['attach'];
$attach='order';
$out_trade_num=$_GET['trade'];
$out_trade_num=time();
//$url=$_GET('url');
//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($body);
$input->SetAttach($attach);
$input->SetOut_trade_no($out_trade_num);
$input->SetTotal_fee($money);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
//$input->SetGoods_tag("test");
$input->SetNotify_url("http://shop.guochamall.com/api/Webwxpay/againBuyManage");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
//var_dump($input);die;
$order = WxPayApi::unifiedOrder($input);

$jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>微信支付</title>
    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                <?php echo $jsApiParameters; ?>,
                function(res){
                    WeixinJSBridge.log(res.err_msg);
                    alert("支付成功");
                    setTimeout(function () {
                        location.href="http://shop.guochamall.com/guocha/custom_info.html";
                    },1500)
                }
            );
        }

        function callpay()
        {
            //alert(1110)
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
    </script>
    <script type="text/javascript">
        //获取共享地址


        window.onload = function(){
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', editAddress, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', editAddress);
                    document.attachEvent('onWeixinJSBridgeReady', editAddress);
                }
            }else{
                //editAddress();
            }
        };

    </script>
    <style>
        *{
            padding:0;
            margin:0;
        }
        ul,ol,li{
            list-style:none;
        }
        html{
            font-size:625%;
        }
        body{
            font-size:.14rem;
            font-family:"微软雅黑";
        }
        input{
            outline:none;
        }
        .content{
            max-width:7.5rem;
            margin:0 auto;
        }
        .title{
            height:.45rem;
            text-align:center;
            line-height:.45rem;
            font-size:.18rem;
            color:#333;
            box-sizing:border-box;
            border-bottom:solid 1px #edf0f5;
            border-bottom:solid 1px #edf0f5;
        }
        .zfinfo{
            height:1rem;
            padding-left:20px;
            box-sizing:border-box;
            background:#fff url("bg001.png") no-repeat 90% center;
            background-size:150px;
        }
        .zfmc{
            font-size:.14rem;
            color:#333;
            padding-top:25px;
            padding-bottom:13px;
        }
        .pay-mon{
            font-size:.15rem;
            color:#333;
            padding:15px 0 0 20px;
        }
        .pay-num{
            color:#ff6000;
            margin:50px 0;
            font-size:.32rem;
            font-weight:bold;
            text-align:center;
        }
        .pay-num span{
            font-size:.20rem;
        }
        .pay-btn{
            width:60%;
            margin:0 auto;
        }
        .btn{
            height:.45rem;
            background:#ff6000;
            border:none;
            border-radius:5px;
            font-size:.16rem;
            color:#fff;
            width:100%;
        }
    </style>
</head>
<body>
<!--<br/>
<font color="#9ACD32"><b>该笔订单支付名称为：<span style="color:#f00;font-size:16px" id="body"></span></b></font><br/><br/>
<font color="#9ACD32"><b>该笔订单支付金额为：<span style="color:#f00;font-size:16px" id="money"></span></b></font><br/><br/>
<div align="center">
    <button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >立即支付</button>
</div>-->
<div class="content">

    <div class="title">微信支付</div>
    <div class="zfinfo">
        <p class="zfmc">该笔订单支付名称为：<span style="color:#f00;font-size:16px" id="body"></span></p>
        <p class="zfje">理茶宝充值：<span style="color:#f00;font-size:16px" id="money"></span></p>
    </div>
    <hr size="10" color="#edf0f5">
    <div class="pay">
        <div class="pay-mon">支付金额</div>
        <div class="pay-num"><span>￥</span>20000.00</div>
    </div>

    <div class="pay-btn">
        <input type="button" class="btn" onclick="callpay()" value="立即支付">
    </div>

</div>
<script>
    var body = getUrlParam("body");
    var money = getUrlParam("money");
    document.getElementById("body").innerText = body;
    document.getElementById("money").innerText = money;

    function getUrlParam(key) {
        // 获取参数
        var url = window.location.search;
        // 正则筛选地址栏
        var reg = new RegExp("(^|&)" + key + "=([^&]*)(&|$)");
        // 匹配目标参数
        var result = url.substr(1).match(reg);
        //返回参数值
        return result ? decodeURIComponent(result[2]) : null;
    }
</script>
</body>
</html>