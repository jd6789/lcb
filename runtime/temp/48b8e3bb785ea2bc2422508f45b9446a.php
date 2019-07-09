<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:91:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/share/checkUser_test.html";i:1545814935;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>领取消费卡</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        body{
            background-color: #f2f2f2;
            padding: 1rem ;
        }
        .content{
            height: 3rem;
            background-color: #fff;
            margin-top: 1.5rem;
            padding: 0 0.5rem;
            overflow-x: hidden;
        }
        label{
            float: left;
            height: 3rem;
            line-height: 3rem;
        }
        input{
            height: 3rem;
            border: none;
            text-align: left;
            outline: none;
            padding-right: 0.5rem;
        }
        .content >span{
            float: right;
            height:3rem;
            line-height:3rem;
        }
        .both{
            clear: both;
        }
        .content ul li{
            list-style: none;
            width:25%;
            float: left;
            height: 3rem;
            line-height: 3rem;
            text-align: center;
        }
        .content ul li:first-of-type{
            text-align: left;
        }
        .content ul li:last-of-type{
            text-align: right;
        }
        .content textarea{
            width: 100%;
            height: 3rem;
            resize: none;
            border: none;
            outline: none;
            font-size: 14px;
        }
        body> a{
            display: block;
            width: 90%;
            height: 3rem;
            line-height: 3rem;
            background-color: #ff938e;
            color: #fff;
            text-align: center;
            text-decoration: none;
            margin:0 auto;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }
        body>a.check{
            background-color: #ff3536;
        }
        button{
            height:1.5rem;
            border: none;
            outline: none;
            padding: 0.3rem;
            background-color: #ffe7d6;
            color: #ccc;
        }
    </style>
</head>
<body>
<div class="content" style="text-align: center;font-size: 1rem;background-color:transparent;margin-top:0.5rem;line-height:2rem;height:2rem;font-weight: 600;text-align: center;">
    <a href="javascript:history.go(-1)" style="float: left;color: #000;text-decoration: none;font-weight: 400;">取消</a>领取 国茶股份 消费卡</div>
<div class="content">
    <label for="name" style="width: 42%;">请输入姓名：</label><span style="width: 50%;"><input type="text"  id="name" ></span>
    <div class="both"></div>
</div>
<div class="content">
    <input type="tel"  id="tel" placeholder="输入手机号" style="width:50%;"><span style="width: 40%;text-align:right;"><button id="getCaptcha">获取验证码</button></span>
    <div class="both"></div>
</div>
<div class="content">
    <label for="yanz" style="width: 42%;">请输入验证码：</label><span style="width: 50%;"><input type="number"  id="yanz" ></span>
    <div class="both"></div>
</div>
<a href="javascript:;" class="check" id="confirm">确定</a>
</body>
<script src="/partner/js/jquery.min.js"></script>
<script src="/partner/js/layer/layer.js"></script>
<script>


    $(function () {
        var id = ''
        var sign = ''
        //var sign1 = getParam("aa")
        //sign = '154535491399230000';
        $.ajax({
            data: {sign: sign},
            url: "<?php echo url('newapp/share/getRedPack_new'); ?>",
            type: "post",
            success: function (data) {
                console.log(data.status)
                if (data.status == 1) {

                } else if (data.status == 0) {
                    location.href = "<?php echo url('newapp/share/del_redpack'); ?>"
                }
            }
        })


        $("#getCaptcha").click(function () {
            var tel = $("#tel").val();
            if (tel == "") {
                layer.msg("手机号不能为空");
                return false;
            } else {
                if (!checkMobile(tel)) {
                    layer.msg("手机号码格式不正确");
                    return false;
                } else {
                    $.ajax({
                        data: {tel: tel},
                        url:"<?php echo url('newapp/share/msgCode'); ?>",
                        type: "post",
                        success: function (data) {
                            if (data.status == 1) {
                                layer.msg("验证码发送成功");
                                settime();
                            } else {
                                layer.msg("验证码发送失败");
                            }
                        }
                    })
                }
            }
        })
        $("#confirm").click(function () {
            var tel = $("#tel").val()
            var yanz = $("#yanz").val()
            var name = $("#name").val()
            if(!name){
                layer.msg("请输入您的姓名")
                return false
            }
            var user_id = ''
            //alert(1);
            $.ajax({
                data: {mobile_phone: tel, sign: sign, code: yanz, user_name: name},
                url: "<?php echo url('newapp/share/user_get_pack_new'); ?>",
                type: "post",
                success: function (data) {
                    console.log(data);
                    //alert(data.status);
                    if (data.status == 3) {
                        user_id = data.data.user_id
                        layer.confirm('该手机号码已使用<br>使用账户为' + data.data.user_name+'<br>是否绑定该账户', {
                            btn: ['绑定', '取消'], //按钮
                            title: "温馨提示"
                        }, function () {
                            $.ajax({
                                data: {mobile_phone: tel, sign: sign, code: yanz, user_name: name, user_id: user_id},
                                url: "<?php echo url('newapp/share/relative_user_info'); ?>",
                                type: "post",
                                success: function (data) {
                                    alert(data.status);
                                    console.log(data);
                                    if (data.status == 1) {
                                        location.href = "<?php echo url('newapp/share/get_redpack'); ?>?aa=" + data.data
                                    } else if (data.status == 2) {
                                        location.href = "<?php echo url('newapp/share/del_redpack'); ?>"
                                    }
                                    layer.close()
                                }
                            })
                        }, function () {
                            layer.close()
                            layer.msg("改手机号账户已经存在，请更换手机号")

                        });
                    } else if (data.status == 4) {
                        layer.confirm('该手机号码与用户预留手机号不一致，预留手机号为' + data.data.mobile_phone + ',是否更新手机号？', {
                            btn: ['更新', '取消'], //按钮
                            title: "温馨提示"
                        }, function () {
                            $.ajax({
                                data: {mobile_phone: tel, sign: sign, code: yanz, user_name: name,up:1},
                                url: "<?php echo url('newapp/share/update_user'); ?>",
                                type: "post",
                                success: function (data) {
                                    console.log(data);
                                    //alert(data.status);
                                    if (data.status == 1) {
                                        location.href = "<?php echo url('newapp/share/get_redpack'); ?>?aa=" + data.data
                                    } else if (data.status == 2) {
                                        location.href = "<?php echo url('newapp/share/del_redpack'); ?>"
                                    }
                                }
                            })
                        }, function () {
                            $.ajax({
                                data: {mobile_phone: tel, sign: sign, code: yanz, user_name: name, user_id: user_id,up:0},
                                url: "<?php echo url('newapp/share/update_user'); ?>",
                                type: "post",
                                success: function (data) {
                                    console.log(data);
                                    if (data.status == 1) {
                                        location.href = "<?php echo url('newapp/share/get_redpack'); ?>?aa=" + data.data

                                    } else if (data.status == 2) {
                                        location.href = "<?php echo url('newapp/share/del_redpack'); ?>"
                                    }
                                    layer.close()
                                }
                            })

                        })
                    } else if (data.status == 0) {
                        layer.msg(data.msg)
                    }else if (data.status == 1) {
                        location.href = "<?php echo url('newapp/share/get_redpack'); ?>?aa=" + data.data
                    }else if (data.status == 2) {
                        location.href = "<?php echo url('newapp/share/del_redpack'); ?>"
                    }   else {
                        layer.msg("验证码错误!")
                    }
                }
            })
        })
    })
    function GetQueryString(name)
    {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    }
    function getParam(paramName) {
        paramValue = "", isFound = !1;
        if (this.location.search.indexOf("?") == 0 && this.location.search.indexOf("=") > 1) {
            arrSource = unescape(this.location.search).substring(1, this.location.search.length).split("&"), i = 0;
            while (i < arrSource.length && !isFound) arrSource[i].indexOf("=") > 0 && arrSource[i].split("=")[0].toLowerCase() == paramName.toLowerCase() && (paramValue = arrSource[i].split("=")[1], isFound = !0), i++
        }
        return paramValue == "" && (paramValue = null), paramValue
    }
</script>
<script>
    var countdown = 60;
    function settime() {
        if (countdown == 0) {
            $("#getCaptcha").removeAttr("disabled");
            $("#getCaptcha").text("重新发送") ;
            countdown = 60;
            return false;
        } else {
            $("#getCaptcha").attr("disabled", "true");
            $("#getCaptcha").text("重新发送(" + countdown + ")");
            countdown--;
        }
        setTimeout(function () {
                    settime()
                }
                , 1000)
    }
    //验证手机号正则
    function checkMobile(mobile) {
        var flag = true;
        if (typeof(mobile) != "string") {
            flag = false;
        } else {
            // ��� ��ϵ�绰 - �ֻ�����
            var mobileReg = /^(13|14|15|17|18)\d{9}$/;
            if (!mobileReg.test(mobile)) {
                flag = false;
            }
        }
        return flag;
    }
</script>
</html>