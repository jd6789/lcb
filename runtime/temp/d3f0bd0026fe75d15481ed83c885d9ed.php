<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/user/index.html";i:1539150200;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
    <link href="/newtea/css/reset.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/common.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/iconfont.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/newtea/css/swiper-4.2.0.min.css">
    <link href="/newtea/css/index.css" rel="stylesheet" type="text/css">
    <title>理茶宝首页</title>
    <style>
        .content {
            padding-bottom: .45rem;
        }

        .footer p:first-child {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<div class="content"><!--content-->

    <div class="title"><!--title-->
        <a href="<?php echo url('newapp/alluser/paycode'); ?>" class="fkm"><img src="/newtea/images/fkm.png" width="25"></a>
        <span class="titlelcb">理茶宝</span>
        <a class="logini" href="<?php echo url('newapp/user/login'); ?>" id="href"
           style="width: 140px;text-align: right;overflow: auto;white-space: nowrap;text-overflow: ellipsis;">
            <span class="" id="username" style="color:#fff"></span>
        </a>
        <!--<i class="iconfont icon-xiaoxi"></i>-->
    </div><!--title-->

    <div class="swiper-container"><!--swiper-container-->
        <div class="swiper-wrapper">
            <!--<div class="swiper-slide"><img src="/mobile/images/banner1.jpg"></div>-->
            <!--<div class="swiper-slide"><img src="/mobile/images/banner2.jpg"></div>-->
            <!--<div class="swiper-slide"><img src="/mobile/images/banner3.jpg"></div>-->
            <div class="swiper-slide"><img src="/mobile/images/1.jpg"></div>
            <div class="swiper-slide"><img src="/mobile/images/2.jpg"></div>
            <div class="swiper-slide"><img src="/mobile/images/3.jpg"></div>
        </div>
        <!-- 如果需要分页器 -->
        <div class="swiper-pagination"></div>
        <!--<div class="problems"><a href="<?php echo url('problem'); ?>">常见问题</a></div>-->
    </div><!--swiper-container-->

    <div class="index-bg">
        <img src="/newtea/images/index.png">
    </div>
</div>

<footer class="footer">
    <nav class="nav">
        <ul>
            <li><a class="active" href="<?php echo url('newapp/user/index'); ?>">
                <p><img src="/newtea/images/sy.png" width="21"></p>
                <p>首页</p>
            </a></li>
            <li><a href="<?php echo url('newapp/user/richardtea'); ?>">
                <p><img src="/newtea/images/lcb1.png" width="19"></p>
                <p>理茶宝</p>
            </a></li>
            <li><a href="<?php echo url('newapp/user/record'); ?>">
                <p><img src="/newtea/images/jl1.png" width="21"></p>
                <p>记录</p>
            </a></li>
            <li><a href="<?php echo url('newapp/user/myinfo'); ?>">
                <p><img src="/newtea/images/wd1.png" width="22"></p>
                <p>我的</p>
            </a></li>
        </ul>
    </nav>
</footer>

<script src="/newtea/js/jquery-1.8.3.min.js"></script>
<script src="/newtea/js/swiper-4.2.0.min.js"></script>
<script>

    $(".fkm").click(function () {
        $(".box").fadeIn(100)
    })

    $(".box-bg").click(function () {
        $(".box").fadeOut(200)
    })

    $.ajax({
        url: "<?php echo url('alluser/userLogin'); ?>",
        type: "post",
        success: function (msg) {
            if (msg.status == 1) {
                $("#username").text("欢迎您，" + msg.msg)
                $("#href").attr("href", "javascript:;")
            } else {
                $("#username").text("登录")
            }
        }
    })

    var mySwiper = new Swiper('.swiper-container', {
        autoplay: true,
        //freeMode : true,
        //touchRatio : 0.5,
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
        },
        autoplay: {
            disableOnInteraction: false,
        },
    })
</script>
</body>
</html>