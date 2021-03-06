<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:91:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/recharge/userintegral.html";i:1534593270;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
    <link href="/admin/css/customPage.css" rel="stylesheet">
    <style>
        /*分页*/
        .pagination {
            text-align: right;
            margin-top: 15px;
        }
        .pagination li {display: inline-block;margin-right: -1px;padding: 5px;border: 1px solid #e2e2e2;min-width: 20px;text-align: center;}
        .pagination li.active {background: #009688;color: #fff;border: 1px solid #009688;}
        .pagination li a {display: block;text-align: center;}

    </style>
    <style>
        .cont_search input.search{
            height: 28px;
            line-height: 28px;
            width: 100px;
            font-weight: 200;
            font-size: 14px;
        }
        .cont_search table{
            width: auto;
        }
        .cont_search table td{
            padding-right: 10px;
        }
        html,body{
            width:100%;
            height:100%;
        }
        .box{
            position:fixed;
            left:0;
            top:0;
            width:100%;
            height:100%;
            display:none
        }
        .handfh{
            position:fixed;
            left:0;
            top:0;
            width:100%;
            height:100%;
            display:none
        }
        .box-con{
            width:100%;
            height:100%;
            background:rgba(0,0,0,.5);
            position:absolute;
        }
        .box-text{
            width:300px;
            height:300px;
            position:absolute;
            background:#fff;
            z-index:100;
            top:50%;
            left:50%;
            margin-left:-150px;
            margin-top: -150px;
        }
        .box-title{
            height:40px;
            line-height:40px;
            border-bottom:solid 1px #ccc;
            background:#f1f1f1;
        }
        .sd{
            margin-left:15px;
            color:#666;
            font-size:16px
        }
        .box1{
            margin:15px;
            font-size:16px;
        }
        .box1 p{
            margin:25px 0
        }

        .close{
            float:right;
            font-size:18px;
            margin-right:15px;
            cursor:pointer;


        }
        .closes{
            float:right;
            font-size:18px;
            margin-right:15px;
            cursor:pointer;


        }
        input[type=text]{
            width:180px;
            height:28px;
            margin-left:10px
        }
        .qd-btn{
            border:none;
            background:cornflowerblue;
            width:80px;
            height:40px;
            border-radius:20px;
            font-size:15px;
            margin-top:15px;
            color:#fff;
        }
        .blank{
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.3);
            z-index: 998;
            display: none;
        }
        .zhezhao{
            display: none;
            position: absolute;
            width: 50%;
            left: 50%;
            top: 35%;
            transform: translate(-50%,-50%);
            background-color: #fff;
            padding:30px 20px;
            z-index: 999;
        }
        .blank.in,.zhezhao.in{
            display: block;
        }
        .blanks{
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.3);
            z-index: 998;
            display: none;
        }
        .zhezhaos{
            display: none;
            position: absolute;
            width: 50%;
            left: 50%;
            top: 35%;
            transform: translate(-50%,-50%);
            background-color: #fff;
            padding:30px 20px;
            z-index: 999;
        }
        .blanks.in,.zhezhaos.in{
            display: block;
        }
    </style>
</head>
<body>
<div class="cont_search clearfix">
    <form action=""  method="post">
        <!-- 关键字 -->
        用户名<input type="text" name="username" size="15" id="username"/>
        <input  value="查询" class="btngreen search " id="search">
    </form>
</div>
<div class="bench white-scroll">
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0" id="gudong_table">
            <thead>
            <tr>
                <td>用户名称</td>
                <td>门店名字</td>
                <td>需返还的总积分</td>
                <td>已返的积分</td>
                <td>剩余积分</td>
                <td>剩余茶劵</td>
                <td>剩余茶点</td>
                <td>上次返还时间</td>
                <td>操作</td>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div class="page clearfix">
        <div class="text">共<b id="count_page2">1</b>页<b id="count_num2">1</b>条记录</div>
        <div class="page3" style="display: inline;"></div>
    </div>
</div>



<!--手动释放积分-->
<div class="box">
        <div class="box-con">
            <div class="box-text">
                <div class="box-title"><span class="sd">手动释放积分</span><span class="close">X</span></div>
                <div class="box1">
                    <p>确认给<span class="sduser" style="color:#f00"></span>手动释放积分吗？</p>
                    <p>茶点:<input class="tea_ponit_inte" type="text" value="" ></p>
                    <p>茶券:<input class="tea_inte" type="text" value="" ></p>
                    <div style="text-align:center"><input class="qd-btn" type="button" value="确定"></div>
                </div>
            </div>
        </div>

</div>


<!--展示用户的历史记录-->
<div class="blank"></div>
<div class="zhezhao">
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0" id="table">
            <thead>
            <tr>
                <td>订单号</td>
                <td>积分记录</td>
                <td>上次返还时间</td>
                <td>说明</td>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table><p></p>
    </div>
    <div class="page clearfix">
        <div class="text">共<b id="count_page">1</b>页<b id="count_num">1</b>条记录</div>
        <div class="page1" style="display: inline;"></div>
    </div>
</div>

<!--展示用户的分红记录-->
<div class="blanks"></div>
<div class="zhezhaos">
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0" id="tables">
            <thead>
            <tr>
                <td>门店名字</td>
                <td>分红金额</td>
                <td>分红时间</td>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div class="page clearfix">
        <div class="text">共<b id="count_page1">1</b>页<b id="count_num1">1</b>条记录</div>
        <div class="page2" style="display: inline;"></div>
    </div>
</div>


</body>
</html>
<script src="/admin/js/jquery.min.js"></script>
<script src="/admin/js/customPage.js"></script>

<script>
    var page=1
    //分页
    $(".page1").CustomPage({
        pageSize: 5,
        current: 1,
        callback: function (selected) {
            var id = $(".hostory").val();
            hostory_rocode(selected,id)
        }
    });
    $(".page2").CustomPage({
        pageSize: 5,
        current: 1,
        callback: function (selected) {
            var id = $(".fenhong").val();
            fenhong_recode(selected,id)
        }
    });
    $(".page3").CustomPage({
        pageSize: 15,
        current: 1,
        callback: function (selected) {
            var username=$("#username").val()
            get_gudong(selected,username)
        }
    });
    $("#search").click(function () {
        var username=$("#username").val()
        get_gudong(page,username)
    })
    var username=$("#username").val()
    get_gudong(page,username)
    //全部列表
    function get_gudong(page,username) {
        $.ajax({
            url:"<?php echo url('tmvip/recharge/userintegral'); ?>",
            data:{page:page,username:username},
            type:"post",
            success:function (data) {
                console.log(data);
                var str=''
                if(data==0){
                    str+="<tr>\n" +
                        "                <td colspan='11'>暂无数据</td>\n" +
                        "            </tr>"
                    var count=1;
                    $("#count_page").text(0)
                    $("#count_num").text(0)
                    $(".page3").CustomPage({
                        pageSize: 5,
                        count: count,
                        current: page
                    });
                    $("#gudong_table tbody").html(str)
                }

                for(var i=0;i<data.data.length;i++){
                    str+=' <tr>\n' +
                        '                <td class="username">'+data.data[i].username+'</td>\n' +
                        '                <td class="username">'+data.data[i].stores_name+'</td>\n' +
                        '                <td>'+data.data[i].total_sum+'</td>\n' +
                        '                <td>'+data.data[i].back_inte+'</td>\n' +
                        '                <td>'+data.data[i].surplus_inte+'</td>\n' +
                        '                <td>'+data.data[i].tea_inte+'</td>\n' +
                        '                <td>'+data.data[i].tea_ponit_inte+'</td>\n' +
                        '                <td>'+data.data[i].last_time+'</td>\n' +
                        '                <td>\n' +
                        '                    <button value="'+data.data[i].id+'" class="all">历史返还记录</button>&nbsp;\n' +
                        '                    <button value="'+data.data[i].id+'" class="fenhong">分红记录</button>\n' +
                        '                </td>\n' +
                        '            </tr>'
                }

                var count=data.total;
                $("#count_page2").text(Math.ceil(count/15))
                $("#count_num2").text(count)
                $(".page3").CustomPage({
                    pageSize: 15,
                    count: count,
                    current: page
                });
                $("#gudong_table tbody").html(str)
            }
        })

    }
    var int_id=''
    $(".dels").click(function(){
        $(".sduser").text($(this).parent().parent().find(".username").text());
        int_id=$(this).parent().parent().find(".hid").val();
        $(".box").fadeIn(500)
    });
    $(".close").click(function(){
        $(".box").fadeOut(500)
    })

//        手动分红
    $(" .handfenhong").click(function(){
        $(".sdusers").text($(this).parent().parent().find(".username").text());
        int_id=$(this).parent().parent().find(".hid").val();
        $(".handfh").fadeIn(500)
    });
    $(".closes").click(function(){
        $(".handfh").fadeOut(500)
    })


    $(".qd-btn").click(function () {
        var int_id = $(".hid").val();
        var tea_ponit_inte = $(".tea_ponit_inte").val();
        var tea_inte = $(".tea_inte").val();
        $.ajax({

            type: "post",
            url: "<?php echo url('tmvip/recharge/handinte'); ?>",
            data: {
                int_id: int_id,
                tea_ponit_inte: tea_ponit_inte,
                tea_inte: tea_inte
            },
            success: function (data) {
                if (data.status == 0) {
                    alert('服务器错误，操作失败');
                } else {
                    alert("操作成功");
                    location.reload();
                }
            }
        })
    })
    //历史返还记录
    $("#gudong_table ").on("click",".all",function () {
        $(".all").removeClass("hostory")
        var id=$(this).val()
        $(this).addClass("hostory")
        hostory_rocode(page,id)
    })


    function hostory_rocode(page,id){
        $.ajax({
            url:"<?php echo url('tmvip/recharge/oneRechargeLog'); ?>",
            data:{page:page,id:id},
            type:"post",
            success:function (data) {
                console.log(data);
                var table=''
                if(data==0){
                    table+="<tr>\n" +
                        "                <td colspan='4'>暂无数据</td>\n" +
                        "            </tr>"
                    var count=1;
                    $("#count_page").text(0)
                    $("#count_num").text(0)
                    $(".page1").CustomPage({
                        pageSize: 5,
                        count: count,
                        current: page
                    });
                    $("#table tbody").html(table)
                    $(".zhezhao").addClass("in")
                    $(".blank").addClass("in")
                }else {
                    for(var i=0;i<data.data.length;i++){
                        table+="<tr>\n" +
                            "                <td class=\"username\">"+data.data[i].log_out_trade_no+"</td>\n" +
                            "                <td>"+data.data[i].surplus_inte+"</td>\n" +
                            "                <td>"+data.data[i].bb+"</td>\n" +
                            "                <td>"+data.data[i].introduce+"</td>\n" +
                            "            </tr>"
                    }
                    var count=data.total;
                    $("#count_page").text(Math.ceil(count/5))
                    $("#count_num").text(count)
                    $(".page1").CustomPage({
                        pageSize: 5,
                        count: count,
                        current: page
                    });
                    $("#table tbody").html(table)
                    $(".zhezhao").addClass("in")
                    $(".blank").addClass("in")
                }

            }
        })
    }
    $(".blank").click(function () {
        $(".zhezhao").removeClass("in")
        $(".blank").removeClass("in")
    })

    $("#gudong_table ").on("click",".fenhong",function () {
        $(".fenhong").removeClass("fenhong")
        $(this).addClass("fenhong")
        var id=$(this).val()
        fenhong_recode(page,id)

    })
    function fenhong_recode(page,id){
        $.ajax({
            url:"<?php echo url('tmvip/recharge/fenghonglog'); ?>",
            data:{page:page,id:id},
            type:"post",
            success:function (data) {
                console.log(data);
                var table=''
                if(data==0){
                    table+="<tr>\n" +
                        "                <td colspan='3'>暂无数据</td>\n" +
                        "            </tr>"
                    var count=1;
                    $("#count_page1").text(0)
                    $("#count_num1").text(0)
                    $(".page2").CustomPage({
                        pageSize: 5,
                        count: count,
                        current: page
                    });
                    $("#tables tbody").html(table)
                    $(".zhezhaos").addClass("in")
                    $(".blanks").addClass("in")
                }else {
                    for(var i=0;i<data.data.length;i++){
                        table+="<tr>\n" +
                            "                <td>"+data.data[i].storename.stores_name+"</td>\n" +
                            "                <td class=\"username\">"+data.data[i].bonus_money+"</td>\n" +
                            "                <td>"+data.data[i].addtime+"</td>\n" +
                            "            </tr>"
                    }
                    var count=data.total;
                    $("#count_page1").text(Math.ceil(count/5))
                    $("#count_num1").text(count)
                    $(".page2").CustomPage({
                        pageSize: 5,
                        count: count,
                        current: page
                    });
                    $("#tables tbody").html(table)
                    $(".zhezhaos").addClass("in")
                    $(".blanks").addClass("in")
                }

            }
        })
    }
    $(".blanks").click(function () {
        $(".zhezhaos").removeClass("in")
        $(".blanks").removeClass("in")
    })
</script>

