<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:89:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/share/redpack_info.html";i:1539943358;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>发卡记录</title>
    <link rel="stylesheet" type="text/css" href="/partner/css/swiper-3.3.1.min.css" />
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        body{
            background-color: #f0f0f28c;
        }
        .content{
            height: 4rem;
            background-color: #fff;
            border-bottom:1px solid #f2f2f2;
            padding: 0 0.8rem;
        }
        label{
            float: left;
            height: 3rem;
            line-height: 3rem;
        }
        input{
            height: 3rem;
            border: none;
            text-align: right;
            outline: none;
            padding-right: 0.5rem;
        }
        .content >span{
            float: right;
        }
        .both{
            clear: both;
        }
        .content ul li{
            list-style: none;
            width:20%;
            float: left;
            height: 2.6rem;
            line-height: 2.6rem;
            text-align: center;
            margin:0.2rem 0.5rem;
            background-color: #ffc27d;
            box-sizing: border-box;
            color: #fff;
            border-radius: 0.5rem;
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
        .fl{
            float: left;
        }
        #mcover{ position: fixed; top:0;  left:0; width:100%; height:100%;background:rgba(0, 0, 0, 0.7);  display:none;z-index:20000;}
        #mcover img {position: fixed;right: 18px;top:5px;width: 260px;height: 180px;z-index:20001;}
    </style>
</head>
<body>
<div class="content" style="text-align: center;font-size: 1rem;background-color:transparent;margin-top:0.5rem;line-height:2rem;height:2rem;font-weight: 600;text-align: center;">
    <a href="javascript:history.go(-1)" style="float: left;color: #000;text-decoration: none;font-weight: 400;">关闭</a>发卡记录</div>
<div style="height: 9rem;line-height: 3rem;text-align: center;padding-top: 1rem;box-sizing: border-box">
    <h2 id="money">0.00</h2>
    <div>发出电子消费卡<b style="color:#ffd29d;" id="count">0</b>个</div>
</div>
<div id="con">
    <div id="cone"></div>
</div>
<div id="mcover" onClick="document.getElementById('mcover').style.display='';" style=""><img src="/partner/images/tishi.png"></div>
</body>
<script src="/partner/js/jquery.min.js"></script>
<script src="/partner/js/layer/layer.js"></script>
<script src="/partner/js/jweixin-1.4.0.js"></script>
<script src="/partner/js/swiper.jquery.min.js"></script>
<script>

    $(function () {
        var page=1
        $("#con").on("click",".send",function () {
             var sign=$(this).attr("data-id")
            if(sign){
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
                        link: 'http://vip.guochamall.com/newapp/Share/checkUser?aa='+sign, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
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
                        link: 'http://vip.guochamall.com/newapp/Share/checkUser?aa='+sign, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
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

        })
        // dropload
        $('#con').dropload({
            scrollArea : window,
            loadDownFn : function(me){
                // 拼接HTML
                var result = '';
                $.ajax({
                    url:"<?php echo url('newapp/share/red_pack_info'); ?>",
                    data:{page:page},
                    type: "post",
                    success: function(data){
                        console.log(data);
                        if(data.status==1){
                            $("#money").text(data.data.info_sum)
                            $("#count").text(data.data.info_num)
                            var str=''
                            if(data.data.info !=null){
                                for(var i=0;i<data.data.info.length;i++){
                                    str+='<div class="content" style="line-height:2rem;">\n' +
                                        '        <div class="fl" style="width: 70%;font-size: 10px;color:#ccc;"><div style="font-size: 14px;color:#000;">'+(data.data.info[i].get_user_name==null?"-":data.data.info[i].get_user_name)+'</div>'+getLocalTime(data.data.info[i].red_pack_time)+'</div><span style="min-width: 25%;text-align: center;font-size: 10px;"><div style="font-size: 14px;">'+data.data.info[i].red_pack+'积分</div><div style="color:#ccc;">'+(data.data.info[i].pack_statue==0?"已领取":"未领取 <span style='color: #bd361a' class='send' data-id='"+data.data.info[i].red_sign+"'>继续发送</span>")+'</div></span>\n' +
                                        '        <div class="both"></div>\n' +
                                        '    </div>'
                                }
                                // 为了测试，延迟1秒加载
                                setTimeout(function(){
                                    // 插入数据到页面，放到最后面
                                    $("#cone").append(str)
                                    // 每次数据插入，必须重置
                                    page++;
                                    me.resetload();
                                },1000);
                            }

                        }else{
                            // 锁定
                            me.lock();
                            // 无数据
                            me.noData();
                            me.resetload();
                            if(page==1){
                                $(".dropload-noData").text("暂无送卡记录")
                            }

                        }

                    },
                    error: function(xhr, type){
                       // alert('Ajax error!');
                        // 即使加载出错，也得重置
                        me.resetload();
                    }
                });
            }
        });

        // function getdata(page) {
        //     $.ajax({
        //         url:"<?php echo url('newapp/share/red_pack_info'); ?>",
        //         data:{page:page},
        //         type: "post",
        //         success: function (data) {
        //             console.log(data);
        //             if(data.status==1){
        //                 $("#money").text(data.data.info_sum)
        //                 $("#count").text(data.data.info_num)
        //                 var str=''
        //                 if(data.data.info !=null){
        //                     for(var i=0;i<data.data.info.length;i++){
        //                         str+='<div class="content" style="line-height:2rem;">\n' +
        //                             '        <div class="fl" style="width: 70%;font-size: 10px;color:#ccc;"><div style="font-size: 14px;color:#000;">'+(data.data.info[i].get_user_name==null?"-":data.data.info[i].get_user_name)+'</div>'+getLocalTime(data.data.info[i].red_pack_time)+'</div><span style="min-width: 25%;text-align: center;font-size: 10px;"><div style="font-size: 14px;">'+data.data.info[i].red_pack+'积分</div><div style="color:#ccc;">'+(data.data.info[i].pack_statue==0?"已领取":"未领取 <span style='color: #bd361a' class='send' data-id='"+data.data.info[i].red_sign+"'>继续发送</span>")+'</div></span>\n' +
        //                             '        <div class="both"></div>\n' +
        //                             '    </div>'
        //                     }
        //                 }
        //                 $("#con").append(str)
        //             }
        //         }
        //     })
        // }
        function getLocalTime(nS) {
            var myYear = new Date(parseInt(nS) * 1000).getFullYear()
            var myMonth = new Date(parseInt(nS) * 1000).getMonth() + 1
            var myDay = new Date(parseInt(nS) * 1000).getDate()
            var hour = new Date(parseInt(nS) * 1000).getHours();
            var minute = new Date(parseInt(nS) * 1000).getMinutes();
            var second = new Date(parseInt(nS) * 1000).getSeconds();
            if (myMonth < 10) {
                myMonth = '0' + myMonth
            }
            if (myDay < 10) {
                myDay = '0' + myDay
            }
            if (second < 10) {
                second = '0' + second
            }
            if (minute < 10) {
                minute = '0' + minute
            }
            var showDate = myYear + "-" + myMonth + '-' + myDay + ' ' + hour + ':' + minute + ':' + second
            return showDate
        }
    })

</script>
</html>