<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/user/recommender.html";i:1529916245;}*/ ?>
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
    <title>我的推荐</title>
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

        .content {
            font-size: 0.28rem;
            margin-top: 1.1rem;
            background-color: #fff;
            padding-top: 0.3rem;
        }

        .content > .btn {
            height: 0.9rem;
            line-height: 0.9rem;
            font-size: 0.28rem;
        }

        .content > .btn > a {
            display: inline-block;
            width: 45%;
            height: 0.8rem;
            line-height: 0.8rem;
            text-align: center;
            background-color: #435aaa;
            color: #fff;
            margin-left: 2%;
            font-size: 0.28rem;
            border-radius: 0.15rem;
        }
        .content span{
            color: red;
        }
        table{
            width: 100%;
            border-collapse:collapse;
            color: #2f2f2f;
        }
        table thead tr{
            background-color: #eee;
            height: 0.8rem;
        }
        table tbody tr{
            background-color: #fff;
            border-bottom:1px solid #d9d9d9;
            height: 0.8rem;
        }
        table td{
            text-align: center;
        }
        table a{
            padding: 0.06rem 0.2rem;
            border-radius: 0.25rem;
            background-color: #ff9b12;
            color: #fff;
        }
    </style>
    <style>
        <!-- 分页样式 -->
         .turn_page ul,.turn_page1 ul{
            display: inline-block;
            margin-bottom: 0;
            margin-left: 0;
            -webkit-border-radius: 0.04rem;
            -moz-border-radius: 0.04rem;
            border-radius: 0.04rem;
            -webkit-box-shadow: 0 0.016rem 0.032rem rgba(0, 0, 0, 0.05);
            -moz-box-shadow: 0 0.016rem 0.032rem rgba(0, 0, 0, 0.05);
            box-shadow: 0 0.016rem 0.032rem rgba(0, 0, 0, 0.05);
        }

        .turn_page ul li, .turn_page1 ul li {
            display: inline;
        }

        .turn_page ul li a, .turn_page1 ul li a {
            float: left;
            padding: 0.066rem 0.2rem;
            line-height: 0.33rem;
            text-decoration: none;
            background-color: #fff;
            /*border-left-width: 0;*/
            margin-left: 0.032rem;
            color:  #ff9b12;
            border: 0.016rem solid  #ff9b12;
        }

        .turn_page ul li a.active, .turn_page1 ul li a.active {
            color: #fff;
            background:  #ff9b12;
        }

        .turn_page ul li a:hover, .turn_page1 ul li a:hover {
            color: #fff;
            background:  #ff9b12;
        }

        .turn_page span, .turn_page1 span {
            float: left;
            margin-left: 0.15rem;
        }

        .turn_page span i, .turn_page1 span i {
            font-style: normal;
        }
    </style>
</head>
<body>
<div class="head">
    <a href="javascript:history.go(-1);" class="back">
        <img src="/partner/images/fh_03.png" alt="">
    </a>
    <p style="color: #000;font-size: 0.35rem;">我的推荐</p>
</div>
<div class="content">
    <div class="btn">
        <a href="javascript:;" id="reg" class="fl">推荐注册</a>
        <!--<a href="javascript:;" id="reg" class="fl">代人注册</a>-->
        <!--<a id="btn" href="javascript:;" class="fr" style="margin-right: 2%;">二级市场</a>-->
        <a id="btn" href="javascript:;" class="fr">二级市场</a>
        <!--<a id="btn" href="javascript:;" class="fr" style="margin-right:28%;">二级市场</a>-->
        <!--<div class="clear"></div>-->
    </div>
    <div style="margin-bottom: 0.2rem;padding:0.2rem 0.3rem;" class="count">一级市场：<span id="one"><?php echo $data['one_num']; ?></span>人&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;二级市场：<span
            id="two"><?php echo $data['two_num']; ?></span>人<br>团队市场：<span id="team"><?php echo $data['one_num'] + $data['two_num'] +1; ?></span>人&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;团队总业绩：<span id="grade"><?php echo $data['sum_one_rec_monry']; ?></span>
    </div>
</div>
<div id="tab1">
    <table id="table">
        <thead>
        <tr>
            <td>一级</td>
            <td>等级</td>
            <td>业绩</td>
            <!--<td>操作</td>-->
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <div class="turn_page" style="text-align: center;margin-top: 10px;">

    </div>
</div>

<div id="tab2" style="display: none;">
    <table id="table1">
        <thead>
        <tr>
            <td>二级</td>
            <td>等级</td>
            <td>业绩</td>
            <!--<td>操作</td>-->
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <div class="turn_page1" style="text-align: center;margin-top: 10px;"></div>
</div>
<script>
    $(function () {
        $("#btn").click(function () {
            var text = $(this).text();
            if (text == "二级市场") {
                $("#tab1").css("display", "none");
                $("#tab2").css("display", "block");
                $("#btn").text("一级市场");
            } else {
                $("#tab2").css("display", "none");
                $("#tab1").css("display", "block");
                $("#btn").text("二级市场");
            }
        })
       $("#reg").click(function () {
           location.href="<?php echo url('recom/otherreg'); ?>"
       })
    })
</script>
<script>
    var c_page = 1;
    getData(c_page);
    var step = 10;
    function getData(curPage) {
        $.ajax({
            type: "POST",
            url: "<?php echo url('user/recommenders'); ?>",
            dataType: "json",
            success: function (data) {
                if (data.status==0 || data=="" ||data==0){
            var str='<tr><td colspan="3">暂无数据</td></tr>'
            $("#table tbody").html(str);
            $(".turn_page").html("");

        }else{
            var flag=true
            $("#table tbody").html("");
            $(".turn_page").html("");
            var data_length=data.length;
            var str = ''
            var x_page = curPage;
            curPage = (curPage - 1) * step;

            var count = curPage + step;
            if (data_length > x_page * step) {

            } else {
                count = data_length;
            }
            for (var i = 0; i < data.length; i++) {
                str += "<tr>" +
                    "<td>" + data[i].user_name + "</td>" +
                    "<td>" + data[i].status.lev + "</td>"+
                    "<td>" + data[i].sum + "</td></tr>"
            }

            $("#table tbody").html(str);        //重绘table

            var pageNum = Math.ceil(data_length / step);    //获取得到的数据页数


            str = "";

            /*若页数大于1则添加上一页、下一页链接*/
            if (Math.ceil(data_length / step) > 1) {
                str = "<ul style='display: inline-block;'><li><a href='javascript:void(0);onclick=preEvent();' id='pre' data-num='1'>上一页</a></li>"
            } else {
                str = "<ul style='display: inline-block;'>";
            }
            /*循环输出每一页的链接*/
            for (var i = 0; i < Math.ceil(data_length / step); i++) {
                str += "<li><a href='javascript:void(0);onclick=getData(" + (parseInt(i) + 1) + ");' data-type='num'>" + (parseInt(i) + 1) + "</a></li>";
            }
            if (str.indexOf("上一页") > -1) {
                str += "<li><a href='javascript:void(0);onclick=nextEvent();' id='next' data-num='1'>下一页</a></li>"
                    + "<span>共<i id='pageNum'>" + pageNum + "</i>页</span></ul>";

            } else {
                str += "<span>共<i id='pageNum'>" + pageNum + "</i>页</span></ul>";

            }
            $(".turn_page").html(str);
            addClass(x_page)
            $("#pre").attr("data-num", x_page);
            $("#next").attr("data-num", x_page);
            //把当前页码存到上一页、下一页的data-num属性中，这样可以在点击上一页或者下一页时知道应该跳到哪页
                    }



            }

        });
    }

    /**
     * 上一页点击事件
     */
    function preEvent() {
        var c_page = $("#pre").attr("data-num");
        if (c_page <= 1) {
            $(this).attr('disabled', "true");
        } else {
            c_page = parseInt(c_page) - 1;
            getData(c_page);
        }
    }
    /**
     * 下一页点击事件
     */
    function nextEvent() {
        var c_page = $("#next").attr("data-num");
        var pageNum = $("#pageNum").text();
        if (c_page >= pageNum) {
            $(this).attr('disabled', "true");
        } else {
            c_page = parseInt(c_page) + 1;
            getData(c_page);
        }
    }

    function addClass(num) {
        $(".turn_page ul li").eq(num).find("a").addClass("active");
    }
    function getPropertyCount(o){
        var n, count = 0;
        for(n in o){
            if(o.hasOwnProperty(n)){
                count++;
            }
        }
        return count;
    }
</script>
<script>
    var c_page1 = 1;
    getData1(c_page1);
    var step = 10;
    function getData1(curPage1) {
        $.ajax({
            type: "POST",
            url: "<?php echo url('user/vcm'); ?>",
            dataType: "json",
            success: function (data) {
                if (data.status==0 || data=="" ||data==0) {
                    var str = '<tr><td colspan="3">暂无数据</td></tr>'
                    $("#table1 tbody").html(str);
                    $(".turn_page1").html("");

                } else {
                    $("#table1 tbody").html("");
                    $(".turn_page1").html("");
                    var str = ""
                    var x_page = curPage1;
                    curPage1 = (curPage1 - 1) * step;
                    var count = curPage1 + step;
                    if (data.length > x_page * step) {

                    } else {
                        count = data.length;
                    }
                    for (var i = curPage1; i < count; i++) {
                        str += "<tr><td>" + data[i].user_name + "</td><td>" + data[i].status.lev + "</td><td>" + data[i].sum + "</td></tr>";
                    }

                    $("#table1 tbody").html(str);        //重绘table

                    var pageNum = Math.ceil(data.length / step);    //获取得到的数据页数


                    str = "";

                    /*若页数大于1则添加上一页、下一页链接*/
                    if (Math.ceil(data.length / step) > 1) {
                        str = "<ul style='display: inline-block;'><li><a href='javascript:void(0);onclick=preEvent1();' id='pre1' data-num='1'>上一页</a></li>"
                    } else {
                        str = "<ul style='display: inline-block;'>";
                    }
                    /*循环输出每一页的链接*/
                    for (var i = 0; i < Math.ceil(data.length / step); i++) {
                        str += "<li><a href='javascript:void(0);onclick=getData1(" + (parseInt(i) + 1) + ");' data-type='num'>" + (parseInt(i) + 1) + "</a></li>";
                    }
                    if (str.indexOf("上一页") > -1) {
                        str += "<li><a href='javascript:void(0);onclick=nextEvent1();' id='next1' data-num='1'>下一页</a></li>"
                            + "<span>共<i id='pageNum1'>" + pageNum + "</i>页</span></ul>";

                    } else {
                        str += "<span>共<i id='pageNum1'>" + pageNum + "</i>页</span></ul>";

                    }
                    $(".turn_page1").html(str);
                    addClass1(x_page)
                    $("#pre1").attr("data-num", x_page);
                    $("#next1").attr("data-num", x_page);
                    //把当前页码存到上一页、下一页的data-num属性中，这样可以在点击上一页或者下一页时知道应该跳到哪页
                }
            }
        });
    }

    /**
     * 上一页点击事件
     */
    function preEvent1() {
        var c_page1 = $("#pre1").attr("data-num");
        if (c_page1 <= 1) {
            $(this).attr('disabled', "true");
        } else {
            c_page1 = parseInt(c_page1) - 1;
            getData1(c_page1);
        }
    }
    /**
     * 下一页点击事件
     */
    function nextEvent1() {
        var c_page1 = $("#next1").attr("data-num");
        var pageNum = $("#pageNum1").text();
        if (c_page1 >= pageNum) {
            $(this).attr('disabled', "true");
        } else {
            c_page1 = parseInt(c_page1) + 1;
            getData1(c_page1);
        }
    }

    function addClass1(num) {
        $(".turn_page1 ul li").eq(num).find("a").addClass("active");
    }
</script>
</body>
</html>