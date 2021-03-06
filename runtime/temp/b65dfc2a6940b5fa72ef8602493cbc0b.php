<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/share/redpack.html";i:1545616583;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>送消费卡</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f2f2f2;
            padding: 0.6rem;
        }

        .content {
            min-height: 3rem;
            background-color: #fff;
            margin-top: 1rem;
            padding: 0 0.5rem;
        }

        label {
            float: left;
            height: 3rem;
            line-height: 3rem;
            width: 40%;
        }

        input {
            height: 3rem;
            border: none;
            text-align: right;
            outline: none;
            padding-right: 0.5rem;
        }

        .content > span {
            float: right;
            width: 50%;
        }

        .both {
            clear: both;
        }

        .content ul li:nth-child(odd){
            list-style: none;
            width: 45%;
            float: left;
            height: 3.5rem;
            line-height: 3.5rem;
            text-align: center;
            margin: 0.2rem 2.5%;
            background-color: #fff;
            border: 1px solid #fea748;
            box-sizing: border-box;
            color: #000;
            border-radius: 0.5rem;
        }
        .content ul li:nth-child(even){
            list-style: none;
            width: 45%;
            float: right;
            height:3.5rem;
            line-height: 3.5rem;
            text-align: center;
            margin: 0.2rem 2.5%;
            background-color: #fff;
            border: 1px solid #fea748;
            box-sizing: border-box;
            color: #000;
            border-radius: 0.5rem;
        }
        .content ul li.active{
            color: #FFF;
            background: -webkit-linear-gradient(left, #fea748 , #ff9f37); /* Safari 5.1 - 6.0 */
            background: -o-linear-gradient(right,  #fea748 , #ff9f37); /* Opera 11.1 - 12.0 */
            background: -moz-linear-gradient(right, #fea748 , #ff9f37); /* Firefox 3.6 - 15 */
            background: linear-gradient(to right, #fea748 , #ff9f37); /* 标准的语法（必须放在最后） */
        }
        .content textarea {
            width: 100%;
            height: 3rem;
            resize: none;
            border: none;
            outline: none;
            font-size: 14px;
        }
        a{
            text-decoration: none;
            color: #909090;
        }
        body > a {
            display: block;
            width: 90%;
            height: 3rem;
            line-height: 3rem;
            background-color: #fea748;
            color: #fff;
            text-align: center;
            text-decoration: none;
            margin: 0 auto;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }

        #mcover{ position: fixed; top:0;  left:0; width:100%; height:100%;background:rgba(0, 0, 0, 0.7);  display:none;z-index:20000;}
        #mcover img {position: fixed;right: 18px;top:5px;width: 260px;height: 180px;z-index:20001;}
    </style>
</head>
<body>
<div class="content"
     style="display: none;text-align: center;font-size: 1rem;background-color:transparent;margin-top:0.5rem;line-height:2rem;height:2rem;font-weight: 600;text-align: center;">
    <a href="javascript:history.go(-1)" style="float: left;color: #000;text-decoration: none;font-weight: 400;">取消</a>送消费卡
</div>
<div class="content" style="margin-top: 0.5rem;">
    <label for="money">请输入金额：</label><span><input type="number" readonly id="money" placeholder="0.00"
                                                  style="width:80%;">元</span>
    <div class="both"></div>
</div>
<div style="text-align: right;font-size: 12px;color:red;display: none;" id="hidd">输入的数值不能低于50积分</div>
<div class="content">
    <ul>
        <li class="active"><span>2000</span>元</li>
        <li><span>1000</span>元</li>
        <li><span>800</span>元</li>
        <li><span>500</span>元</li>
        <li><span>300</span>元</li>
        <div class="both"></div>
    </ul>

</div>
<div class="content">
    <textarea name="" id="msg" cols="30" rows="10" placeholder="留言"></textarea>
</div>
<div class="content" style="text-align: center;font-size: 2rem;background-color:transparent;line-height:3rem;">
    ￥ <b id="money1">2000.00</b>
</div>
<a href="javascript:;" id="confirm" class="check">分享给朋友</a>

<div style="position: absolute;bottom: 0.5rem;left: 0;text-align: center;width: 100%;" id="scoll"><a href="<?php echo url('newapp/share/redpack_info'); ?>">发送记录</a></div>
<div id="mcover" onClick="document.getElementById('mcover').style.display='';" style=""><img src="/partner/images/tishi.png"></div>
</body>
<script src="/partner/js/jquery.min.js"></script>
<script src="/partner/js/layer/layer.js"></script>
<script src="/partner/js/jweixin-1.4.0.js"></script>
<script>
    $(function () {
        var inte = 0
        var user_id = ''
        if (document.documentElement.clientWidth < document.documentElement.offsetWidth-4){
            $("#scoll").css({"position":"static","margin-top":"0.5rem"})
        }
        $.ajax({
            url: "<?php echo url('newapp/share/user_red_use'); ?>",
            type: "post",
            success: function (data) {
                console.log(data);
                if (data.status == 0) {
                    layer.msg(data.msg)
                } else {
                    inte = data.data.tea_inte.tea_ponit_inte
                    user_id = data.data.user_id
                }
            }
        })
        $("#money").keyup(function () {
            var a = parseFloat($(this).val())
            if (a >=50) {
                $("#hidd").hide()
                if (a > inte) {
                    layer.msg("您现在只有" + inte + "积分，不能大于该数额")
                    $("#money").val("")
                } else {
                    $("a").addClass("check")
                    $("#money1").text(a.toFixed(2))
                }
            } else {
                $("#hidd").show()
                $("#money1").text("0.00")
                $("a").removeClass("check")
            }
        })
        var aa=Number($("li.active span").text())
        $("#money").val(aa)
        $("#money1").text(aa.toFixed(2))
        $("li").click(function () {
            var a = parseFloat($(this).find("span").text())
            $(this).addClass("active").siblings().removeClass("active")
            $("a").addClass("check")
            $("#money").val(a)
            $("#money1").text(a.toFixed(2))
        })
        //塞钱
        $("#confirm").click(function () {
            var a = parseFloat($("#money").val())
            $.ajax({
                url: "<?php echo url('newapp/share/user_red_use'); ?>",
                type: "post",
                success: function (data) {
                    console.log(data);
                    if (data.status == 0) {
                        layer.msg(data.msg)
                    } else {
                        inte = data.data.tea_inte.tea_ponit_inte
                        user_id = data.data.user_id
                        if (a >=50) {
                            if (a > inte) {
                                layer.msg("您现在只有" + inte + "积分，不能大于该数额")
                                return false
                            }else{
                                var money=$("#money").val()
                                var beizhu=$("#msg").val()
                                $.ajax({
                                    url:"<?php echo url('newapp/share/sendRedPack'); ?>",
                                    data:{user_id:user_id,red_pack:money,red_beizhu:beizhu},
                                    type:"post",
                                    success:function (data) {
                                        console.log(data);
                                        if(data.status==1){
                                            $("#mcover").show()
                                            wx.config({
                                                debug: false,
                                                appId: "wx97c3159a64046d86",
                                                timestamp: "<?php echo $sign['info']['timestamp']; ?>",
                                                nonceStr: "<?php echo $sign['info']['noncestr']; ?>",
                                                signature: "<?php echo $sign['sign']; ?>",
                                                jsApiList: [
                                                    'onMenuShareTimeline',
                                                    'onMenuShareAppMessage',
                                                    'onMenuShareQQ',
                                                    'onMenuShareWeibo',
                                                    'onMenuShareQZone',
                                                    'chooseImage',
                                                    'uploadImage'
                                                ]
                                            });
                                            wx.ready(function () {
                                                // 分享到朋友圈
                                                wx.onMenuShareTimeline({
                                                    title: '送您一张国茶线下消费卡', // 分享标题
                                                    link: 'http://vip.guochamall.com/newapp/Share/checkUser?aa='+data.data, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                                                    imgUrl: 'http://vip.guochamall.com/partner/images/hongbaoi.png', // 分享图标
                                                    success: function () {
                                                        // 用户确认分享后执行的回调函数
                                                        var xhr = new XMLHttpRequest();
                                                        xhr.onreadystatechange=function(){
                                                            if(xhr.readyState==4&&xhr.status==200){
                                                                location.href="<?php echo url('newapp/share/redpack_info'); ?>"
//                                alert(xhr.responseText)
                                                            }
                                                        }
                                                        xhr.open('post','/Patient/Index/userShare')
                                                        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded');
                                                        xhr.send('status=1');
                                                        location.href="<?php echo url('newapp/share/redpack_info'); ?>"
                                                    },
                                                    cancel: function () {
                                                        // 用户取消分享后执行的回调函数
                                                        layer.msg('您取消了分享，继续分享请点击右上角')
                                                    }
                                                });
                                                //                    分享给朋友
                                                wx.onMenuShareAppMessage({
                                                    title: '送您一张国茶线下消费卡', // 分享标题
                                                    desc: '亲爱的，凭借此卡可用于 国茶集团 任意茶馆及门店抵扣消费哦。', // 分享描述
                                                    link: 'http://vip.guochamall.com/newapp/Share/checkUser?aa='+data.data, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                                                    imgUrl: 'http://vip.guochamall.com/partner/images/hongbaoi.png', // 分享图标
                                                    type: '', // 分享类型,music、video或link，不填默认为link
                                                    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                                                    success: function () {
                                                        // 用户确认分享后执行的回调函数
                                                        var xhr = new XMLHttpRequest();
                                                        xhr.onreadystatechange=function(){
                                                            if(xhr.readyState==4&&xhr.status==200){
                                                                location.href="<?php echo url('newapp/share/redpack_info'); ?>"
                                                            }
                                                        }
                                                        xhr.open('post','/Patient/Index/userShare')
                                                        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded');
                                                        xhr.send('status=1');
                                                        location.href="<?php echo url('newapp/share/redpack_info'); ?>"
                                                    },
                                                    cancel: function () {
                                                        // 用户取消分享后执行的回调函数
                                                        layer.msg('您取消了分享，继续分享请点击右上角')
                                                    }
                                                });


                                            });
                                        }

                                    }
                                })
                            }
                        } else {
                            layer.msg("输入的积分数额不能小于50")
                            return false
                        }

                    }
                }
            })





        })
    })

</script>


</html>