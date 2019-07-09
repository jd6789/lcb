<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:89:"/www/wwwroot/vip.guochamall.com/public/../application/partner/view/index/custom_info.html";i:1545036736;}*/ ?>
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
    <title>我的</title>
    <link rel="stylesheet" href="/partner/css/commen.css">
    <script type="text/javascript" src="/partner/js/jquery.min.js"></script>
    <script src="/partner/js/layer/layer.js"></script>
    <script type="text/javascript" src="/partner/js/auto-size.js"></script>
    <style>
        html, body {
            background-color: #edf0f5;
            font-size: 0.28rem;
        }

        .head {
            background-color: #fff;
        }

        .custom {
            margin-top: 0.9rem;
            height: 3rem;
            background: url("/partner/images/bg_02.png") no-repeat #fff;
            background-size: 100% 3rem;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .custom img {
            display: block;
            margin: 0 auto;
            width: 1.3rem;
            border-radius: 50%;
        }

        .custom .username {
            text-align: center;
            margin-top: 0.2rem;
            color: #fff;
        }

        .custom_privilege {
            background-color: #fff;
        }

        .custom_option {
            width: 33.3%;
            height: 1.7rem;
            text-align: center;
            border-right: 1px solid #edf0f5;
            border-bottom: 1px solid #edf0f5;
            box-sizing: border-box;

        }

        .custom_option img {
            display: block;
            height: 0.7rem;
            margin: 0 auto;
            margin-bottom: 0.2rem;
        }

        .custom_privilege a {
            display: block;
            width: 100%;
            height: 100%;
            padding-bottom: 0.2rem;
            padding-top: 0.2rem;
        }
        .custom_privilege{
            padding-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="head">
    <a href="javascript:history.go(-1);" class="back">
        <img src="/partner/images/fh_03.png" alt="">
    </a>
    <p style="color: #000;font-size: 0.35rem;">个人中心</p>
</div>
<div class="custom">
    <img src="<?php echo $data['user_picture']; ?>" alt="">

    <div class="username">
        <a href="<?php echo url('shareholder/cus_info'); ?>">
        <?php echo $data['user_name']; ?>
        </a>
    </div>
</div>
<div class="custom_privilege">
    <div class="custom_option fl">
        <!--<a href="<?php echo url('shareholder/sale'); ?>">-->
        <!--<a href="<?php echo url('index/willopen'); ?>">-->
            <a href="http://www.tmvip.cn/mobile/user/login/login2?username=<?php echo \think\Session::get('user_shop.username'); ?>&&password=<?php echo \think\Session::get('user_shop.password'); ?>">
            <!--<img src="/partner/images/xssb_03.png" alt="">-->
            <img src="/partner/images/mdcx_03.png" alt="">
            前往商城
        </a>
    </div>
    <div class="custom_option fl">
        <a href="<?php echo url('shareholder/paycode'); ?>">
            <img src="/partner/images/fkm_03.png" alt="">
            付款码
        </a>
    </div>
    <div class="custom_option fl">
        <!--<a href="<?php echo url('shareholder/appointment'); ?>">-->
        <a href="<?php echo url('recom/recommen'); ?>">
            <img src="/partner/images/yuyue_03.png" alt="">
            <!--我的预约-->
            我的推荐
        </a>
    </div>
    <div class="custom_option fl">
        <a href="<?php echo url('shareholder/inte_info'); ?>">
            <img src="/partner/images/jfmx_03.png" alt="">
            入股记录
        </a>
    </div>
    <div class="custom_option fl">
        <a href="<?php echo url('index/postal'); ?>">
            <img src="/partner/images/xiaox_03.png" alt="">
            分红提现
        </a>
    </div>
    <div class="custom_option fl">
        <a href="<?php echo url('recom/otherreg'); ?>">
            <img src="/partner/images/tjm_03.png" alt="">
            推荐注册
        </a>
    </div>
    <div class="custom_option fl" id="hongb">
        <a href="<?php echo URL('newapp/Share/redpack'); ?>">
            <img src="/partner/images/tjm_03.png" alt="">
            送消费卡
        </a>
    </div>
    <div class="clear"></div>
</div>
<div id="footer">
    <div class="item items">
        <a href="<?php echo url('index/index'); ?>"><span class="footer_home"></span>首页</a>
        <a href="<?php echo url('index/willopen'); ?>"><span class="footer_xuancha"></span>门店收益</a>
        <a href="<?php echo url('index/recode'); ?>"><span class="footer_shopcar"></span>记录</a>
        <a href="javascript:;" class="check"><span class="footer_wode"></span>我的</a>
    </div>
</div>

<script>
    var is_weixin = (function(){return navigator.userAgent.toLowerCase().indexOf('micromessenger') !== -1})();
    if(is_weixin){

    }else{
        $("#hongb").hide()
    }
</script>
</body>
</html>