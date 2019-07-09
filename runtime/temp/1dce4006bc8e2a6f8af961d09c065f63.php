<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:92:"/www/wwwroot/vip.guochamall.com/public/../application/partner/view/shareholder/cus_info.html";i:1528719351;}*/ ?>
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
    <title>个人中心</title>
    <link rel="stylesheet" href="/partner/css/mui.min.css">
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
        .content{
            font-size: 0.28rem;
            margin-top: 1.1rem;
        }
        .footer-bd{
            margin:0.5rem auto;
        }
        a.public_m3 {
            margin: 0 auto;
            display: block;
            width: 90%;
            height: 0.8rem;
            background: #ff9b12;
            text-align: center;
            line-height: 0.8rem;
            color: #fff;
            border-radius: 0.1rem;
            margin-top: 0.3rem;
            font-size: 0.28rem;

        }
        ul li a img{
            height: 0.35rem;
            vertical-align: middle;
            margin-right: 0.32rem;
        }
        ul li:last-of-type a img{
            margin-right: 0.2rem;
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
<div class="content">
    <ul class="mui-table-view">
        <li class="mui-table-view-cell">
            <a class="mui-navigate-right" href="<?php echo url('shareholder/cus_infomation'); ?>"><img src="/partner/images/grxx_03.png" alt="">个人信息</a>
        </li>
        <li class="mui-table-view-cell" id="smrz">
            <a class="mui-navigate-right" href="<?php echo url('shareholder/real_name'); ?>"><img src="/partner/images/smrz_03.png" alt="">实名认证</a>
        </li>
        <li class="mui-table-view-cell">
            <a class="mui-navigate-right" href="<?php echo url('shareholder/edit_password'); ?>"><img src="/partner/images/yhmm_03.png" alt="">更改密码</a>
        </li>
        <!--<li class="mui-table-view-cell">-->
            <!--<a class="mui-navigate-right" href="bd_phone.html"><img src="/partner/images/bdsj_03.png" alt="">绑定手机</a>-->
        <!--</li>-->
        <!--<li class="mui-table-view-cell">-->
            <!--<a class="mui-navigate-right" href="<?php echo url('recom/recommen'); ?>"><img src="/partner/images/wdtj_03.png" alt="">我的推荐</a>-->
        <!--</li>-->
        <!--<li class="mui-table-view-cell" id="link">-->
            <!--<a class="mui-navigate-right" href="javascript:;"><img src="/partner/images/wdtj_03.png" alt="">关联账户</a>-->
        <!--</li>-->

    </ul>
    <div class="footer-bd" >
        <a href="javascript:;" class="public_m3" id="log">切换登录</a>
        <!--<a href="javascript:;" class="public_m3" id="wx" style="display: none;">绑定微信</a>-->
    </div>
</div>
<script>
    $.ajax({
        type:"post",
        url:"<?php echo url('user/is_realname'); ?>",
        success:function(data){
            if(data.status == 1){
                $("#smrz").css({"display":"none"})
            }
            $("#zhezhao").hide()
        },
    });
    function isWeiXin() {
        var ua = window.navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == 'micromessenger') {
            return true;
        } else {
            return false;
        }
    }
    //判断是否为微信打开的
    if(isWeiXin()){
        $("#link").show()
    }else{
        $("#link").hide()
    }
    $('#log').click(function () {
        location.href = "<?php echo url('login/login'); ?>"
    })

    $("#link").click(function(){

        $.ajax({
            type:"post",
            url:"<?php echo url('shareholder/account_link'); ?>",
            success:function(data){
                var data = JSON.parse(data);
                //alert(data)
                if(data.status == 1){
                    location.href = "<?php echo url('shareholder/linkover'); ?>"
                }else{
                    location.href = "<?php echo url('shareholder/accountlink'); ?>"
                }

            }
        })

    })

</script>

</body>
</html>