<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:95:"/www/wwwroot/vip.guochamall.com/public/../application/partner/view/recharge/storeindex_new.html";i:1541001084;}*/ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>选择门店</title>
</head>
<style>
    *{
        margin:0px;
        padding:0px;
    }
    .title{
        height:45px;
        line-height:45px;
        text-align:center;
        position:relative
    }
    .serch{
        text-align:center;
        background:#e9ecf1;
        padding:10px;
        position:relative;
    }
    .serch input{
        border:solid 1px #e9ecf1;
        border-radius:20px;
    }
    .back{
        position:absolute;
        left:10px;
        top:0px;
    }
    .lists{
        line-height:3;
    }
    .lists li{
        padding:0 8px;
        border-bottom:solid 1px #e9ecf1;
        font-size:18px;
        padding-left:18px;
    }
    .icon-user{
        position: absolute;
        right: 15%;
        z-index: 5;
        background-image: url(/partner/images/ss_03.png);
        background-repeat: no-repeat; /*设置图片不重复*/
        background-position: 0px 6px; /*图片显示的位置*/
        width: 30px; /*设置图片显示的宽*/
        height: 30px; /*图片显示的高*/
        background-size:24px
    }

</style>
<body>
<div class="title">
    <a href="javascript:history.go(-1);" class="back">
        <img width="20" src="/partner/images/fh_03.png" alt="">
    </a>选择门店
</div>
<div class="serch">
    <i class="icon-user"></i>
    <input placeholder="请输入搜索门店名字" id="searchs" type="text" style="width:80%;height:36px;">
</div>
<div class="lists">
    <ul id="html">

    </ul>
</div>
</body>
<script type="text/javascript" src="/partner/js/jquery.min.js"></script>
<script>
    $(function () {

        $.ajax({
            type:"post",
            url:"<?php echo url('recharge/storeData_new'); ?>",
            success:function(data){
                console.log(data)
                var html = '';
                for(var i=0;i<data.length;i++){
                    html += '<li class="btn" data-id="'+data[i].id+'">'+data[i].stores_name+'</li>'
                }
                $("#html").html(html)
            }
        })

        $("#searchs").keyup(function(){
            let val = $("#searchs").val();
            $.ajax({
                type:"post",
                url:"<?php echo url('recharge/storeData_new'); ?>",
                data:{
                    name:val
                },
                success:function(data){
                    //console.log(data);
                    var html = '';
                    for(var i=0;i<data.length;i++){
                        html += '<li class="btn" data-id="'+data[i].id+'">'+data[i].stores_name+'</li>'
                    }
                    $("#html").html(html)
                }
            })
        })

        $("#html").on("click",".btn",function () {
            var id=$(this).attr("data-id")
            $.ajax({
                url:"<?php echo url('recharge/cookie_store_new'); ?>",
                data:{id:id},
                type:"post",
                success:function (msg) {
                    if(msg==1){
                        self.location=document.referrer;
                    }else{
                        self.location=document.referrer;
                    }
                }
            })
        });
    })
</script>
</html>