<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/login/login.html";i:1535962791;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
    <link href="/newtea/css/layer.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/red.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/reset.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/common.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/iconfont.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/login.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="content"><!--content-->

    <div class="back"><a  href="<?php echo URL('newapp/user/index'); ?>"><i class="iconfont icon-fanhui"></i></a></div>
    <div class="logo"><img src="/newtea/images/logo.png" width="120px"></div>

    <div class="box"><!--box-->

        <div class="box-con"><!--box-con-->
            <div class="box-login tab"><!--box-login-->
                <p class="user"><i class="iconfont icon-yonghu"></i>用户名</p>
                <p><input id="username" type="text" placeholder="请输入用户名"></p>
                <p class="pwd"><i class="iconfont icon-mima"></i>密码</p>
                <p><input id="password" type="password" placeholder="请输入密码"></p>
                <div class="forg">

                </div>
                <div class="btn">
                    <input id="login-btn" type="button" value="登录">
                </div>

            </div><!--box-login-->

        </div><!--box-con-->

    </div><!--box-->

</div><!--content-->



<script type="text/javascript" src="/newtea/js/jquery-1.9.0.min.js"></script>
<script src="/newtea/js/layer.js"></script>
<script src="/newtea/js/icheck.min.js"></script>
<script>
    $(function(){
        //自定义checkbox
        $('input').iCheck({
            checkboxClass: 'icheckbox_minimal-red',
            radioClass: 'iradio_minimal-red',
            increaseArea: '20%' // optional
        });

        $("#check").on('ifClicked',function(){
            if($(this).is(':checked')){
                $(".reg-btn").addClass("noreg");
                $(".reg-btn").attr("disabled",true)

            }else{
                $(".reg-btn").removeClass("noreg");
                $(".reg-btn").attr("disabled",false)
            }
        })
        //登录
        $("#login-btn").click(function(){
            var username = $(".box-login #username").val();
            var password = $("#password").val();
            if(!username){
                layer.msg("请填写用户名");
                return false;
            }
            if(!password){
                layer.msg("请填写密码");
                return false;
            }
            $.ajax({
                type:"post",
                url:"<?php echo url('login/login_ne'); ?>",
                dataType:'json',
                data:{
                    'username':username,
                    'password':password,
                },
                success:function(msg){
                    var msg=JSON.parse(msg)
                    if(msg.status == 2){
                        layer.msg("用户名不存在");
                        return false;
                    }
                    if(msg.status == 9){
                        layer.msg(msg.data);
                        return false;
                    }
                    if(msg.status == 0){
                        layer.msg("密码错误");
                        return false;
                    }
                    if(msg.status == 3){
                        layer.msg("该账户被冻结");
                        return false;
                    }
                    if(msg.status == 1){
                        layer.msg("登录成功");
                        setTimeout(function(){
                            //location.href="<?php echo url('user/myinfo'); ?>";
                            location.href="<?php echo url('user/index'); ?>";
                        },1000);
                    }
                },
                error:function(){
                    //alert("error")
                }
            });
        })











    })
</script>
</body>
</html>