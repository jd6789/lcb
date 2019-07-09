<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"/www/wwwroot/vip.guochamall.com/public/../application/index/view/index/mobiles.html";i:1528691386;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="description" content="淘米科技"/>
    <meta name="keywords" content="淘米科技"/>
    <title>选择会员</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        html,body{
            height: 100%;
        }
        .content{
            width: 100%;
            height: 100%;
            background: url("/index/images/bg.jpg") no-repeat;
            -webkit-background-size: cover;
            background-size: cover;
            position: relative;
            padding-top: 24%;
            box-sizing: border-box;
        }
        .content img{
            display: block;
            width: 30%;
            margin: 0 auto;

        }
        .content>div{
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%,-20%);
            width: 100%;
        }
        .content>div>div{
            width: 46%;
            margin: 0 auto;
            margin-bottom: 15px;
            border: 1px solid #646464;
            padding: 15px 20px;
            border-radius: 5px;
            color: #fff;
            text-align: center;
        }
        .content>div>div.curr{
            background-color: #25afba;
            border: 1px solid #25afba;
        }
        a{
            text-decoration: none;
            color: #fff
        }
    </style>
</head>
<body>
<div class="content">
    <img src="/index/images/guocha.png" alt="">
    <div>
        <!--<div class="curr"><a href="<?php echo url('partner/index/index'); ?>">我是股东</a></div>-->
        <div class="curr"><a href="<?php echo url('partner/Shareholder/login'); ?>">我是股东</a></div>
        <div><a href="<?php echo url('newapp/user/index'); ?>">我是理茶宝会员</a></div>
    </div>

</div>
</body>
</html>