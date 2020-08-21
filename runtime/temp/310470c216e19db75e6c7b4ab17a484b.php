<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"D:\project2\lcb\public/../application/partner\view\shareholder\login.html";i:1528702932;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="description" content="国茶商城"/>
    <meta name="keywords" content="国茶商城"/>
    <title>股东登录</title>
    <link rel="stylesheet" href="/partner/css/commen.css">
    <script type="text/javascript" src="/partner/js/jquery.min.js"></script>
    <script src="/partner/js/layer/layer.js"></script>
    <script type="text/javascript" src="/partner/js/auto-size.js"></script>
    <script src="/partner/js/url.js"></script>
    <style>
        html, body {
            background-color: #000;
            font-size: 0.28rem;
        }

        img {
            max-width: 100%;
            display: inline-block;
        }
        .content{
            height: 7rem;
            margin-top: 1.5rem;
            background: url("/partner/images/kuang1_03.png") no-repeat center;
            background-size: 90% 3.8rem;
            position: relative;
        }
        .content .logo{
            position: absolute;
            top: 0.6rem;
            left: 40.5%;
            width: 1.2rem;
            height: 1.2rem;
            border-radius: 50%;
            background-color: #fff;
            text-align: center;
            line-height: 1.2rem;
        }
        .content>img{
            width: 1rem;
            position: absolute;
            top: -2%;
            left: 55%;
        }
        .login_print{
            margin-top: 0.45rem;
            position: relative;
        }
        .login_print img{
            width: 0.45rem;
            margin-right:0.2rem;
            vertical-align: bottom;
        }
        .login_print input{
            display: inline-block;
            width: 78%;
            height: 0.8rem;
            font-size: 0.28rem;
            border: none;
            border-bottom:1px solid #ccc;
        }
        .login_print i.username{
            position: absolute;
            width: 0.6rem;
            height: 0.6rem;
            background: url("/partner/images/icon/qux_03.png") no-repeat center;
            background-size: 0.2rem 0.2rem;
            right: 0.4rem;
            top: 0.2rem;
            display: none;
        }
        .login_print i.password{
            position: absolute;
            width: 0.6rem;
            height: 0.6rem;
            background: url("/partner/images/icon/yj_03.png") no-repeat center;
            background-size: 0.4rem 0.25rem;
            right: 0.4rem;
            top: 0.2rem;
            display: none;
        }
        .content>a{
            position: absolute;
            width: 70%;
            height: 1rem;
            background-color: #fdde72;
            border-radius: 0.5rem;
            text-align: center;
            line-height: 1rem;
            bottom:1rem;
            left: 1rem;
            color: #fff;
            font-size: 0.35rem;
            font-weight: 200;
        }
        .tip a{
            color: #fff;
        }
        .fl{
            float: left;
            padding-left: 0.2rem;
        }
        .fr{
            float: right;
            text-align: right;
            padding-right: 0.2rem;
        }
        .footer{
            position: absolute;
            width: 100%;
            bottom: 0.5rem;
            text-align: center;
        }
        .footer img{
            height: 1.5rem;

        }
    </style>
</head>
<body>
<div class="head">
    <a href="javascript:history.go(-1);" class="back">
        <img src="/partner/images/icon/fanh_03.png" alt="">
    </a>
    <!--<a href="<?php echo url('shareholder/register'); ?>" class="home">-->
    <!--&lt;!&ndash;<a href="javascript:;" class="home">&ndash;&gt;-->
        <!--注册-->
    <!--</a>-->
</div>
<div class="content">
    <div class="logo">
        股东
    </div>
    <img src="/partner/images/icon/yez_03.png" alt="" >
    <div style="padding-top: 2rem;width: 80%;margin:0 auto;">
        <div class="login_print">
            <img src="/partner/images/icon/yhm_03.png" alt=""><input type="text" id="username" placeholder="请输入用户名"><i class="username"></i>
        </div>
        <div class="login_print">
            <img src="/partner/images/icon/mima_03.png" alt=""><input type="password" id="password" placeholder="请输入密码"><i class="password p_show" data-id="1"></i>
        </div>
    </div>
    <a href="javascript:;" id="login">登录</a>
    <div style="color: #fff;margin-top: 1.8rem;" class="tip">
        <div class="fl" style="width: 40%;">
            <!--<a href="<?php echo url('shareholder/reppwd'); ?>">忘记密码?</a>-->
        </div>
        <div class="fr" style="width: 40%;">
            <!--<a href="<?php echo url('shareholder/mb_login'); ?>" class="tel">手机号登录</a>-->
        </div>
        <div class="clear"></div>
    </div>
</div>
<div class="footer">
    <img src="/partner/images/icon/cha_03.png" alt="">
</div>


<script>
    $("#username").focus(function () {
        $(".username").show()
    }).blur(function () {
        $(".username").hide()
    })
    $("#password").focus(function () {
        $(".password").show()
    })
    $(".username").click(function () {
        $("#username").val("")
        $("#username").focus()
    })
    $(".password").mousedown(function () {
        $("#password").prop("type","text")

    }).mouseup(function () {
        $("#password").prop("type","password")
    })
    $("#login").click(function () {
        var username=$("#username").val()
        var password=$("#password").val()
        if(!username){
            layer.msg("用户名不能为空！")
            return false
        }
        if(!password){
            layer.msg("密码不能为空！")
            return false
        }
        $.ajax({
            url:"<?php echo url('partner/shareholder/loginwork'); ?>",
            data:{
                username:username,
                password:password
            },
            type:"post",
            success:function (data) {
                if(data.status==0){
                    layer.msg("密码错误！")
                    return false
                }
                if(data.status==1){
                    location.href="<?php echo url('index/index'); ?>"
                }
                if(data.status==2){
                    layer.msg(data.data)
                    return false
                }
                if(data.status==3){
                    layer.msg(data.data)
                    return false
                }
                if(data.status==4){
                    layer.msg(data.data)
                    return false
                }
                if(data.status==9){
                    layer.msg(data.data)
                    return false
                }
            }
        })
    })
</script>
</body>
</html>