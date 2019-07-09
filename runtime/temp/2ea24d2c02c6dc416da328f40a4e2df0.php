<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:91:"/www/wwwroot/vip.guochamall.com/public/../application/partner/view/shareholder/paycode.html";i:1528940785;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="description" content="淘米科技"/>
    <meta name="keywords" content="淘米科技"/>
    <title>付款码</title>
    <link rel="stylesheet" href="/partner/css/commen.css">
    <script type="text/javascript" src="/partner/js/jquery.min.js"></script>
    <script src="/partner/js/layer/layer.js"></script>
    <script type="text/javascript" src="/partner/js/auto-size.js"></script>
    <script type="text/javascript" src="/mobile/js/jquery-1.9.0.min.js"></script>
    <script type="text/javascript" src="/mobile/js/jquery.qrcode.min.js"></script>
    <style>
        html, body {
            background-color: #edf0f5;
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        .head {
            background-color: #fff;
        }
        .erweima,.code-box{
            position: absolute;
            width: 36%;
            height: 20%;
            top: 27%;
            left: 32%;
        }
        body>span{
            color: #fff;
            letter-spacing:3px;
            font-size: 0.28rem;
        }
        body>.left{
            position: absolute;
            left: 23%;
            bottom: 10.2%;
        }
        body>.right{
            position: absolute;
            right: 21%;
            bottom: 10.2%;
        }
        .blank{
            height: 0.9rem;
        }
        .content{
            background: url("/partner/images/fkm-bg.png") no-repeat ;
            background-size: 100% 100%;
            flex:1;
        }
        #code>canvas{
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
<div class="head" style="display: none;">
    <a href="javascript:history.go(-1);" class="back">
        <img src="/partner/images/fh_03.png" alt="">
    </a>
    <p style="color: #000;font-size: 0.35rem;">付款码</p>
</div>
<!--<div class="blank"></div>-->
<div class="content">
    <div class="erweima">
        <div id="code" style="height:100%;width: 100%;"></div>

        <input type="hidden" value='<?php echo $data; ?>' id="data">
    </div>
    <div class="code-box" style="background-color:#ccc;height: 21%;width: 38%;display: none;z-index:999;text-align: center;font-size: .28rem;"><div style="text-align: center;padding-top: 43%;">二维码已失效</div></div>
</div>
<!--<span class="left">-->
    <!--开通付款码-->
<!--</span>-->
<!--<span class="right">支付更快捷</span>-->
</body>
<script>


    $(function(){
        setTimeout(function(){
            $(".code-box").fadeIn(500);
        },120000)



        var urldata = $("#data").val();
        $('#code').qrcode(urldata);
    })

</script>
</html>