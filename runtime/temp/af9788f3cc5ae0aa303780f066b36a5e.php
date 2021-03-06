<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"/www/wwwroot/vip.guochamall.com/public/../application/partner/view/index/recode.html";i:1528718402;}*/ ?>
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
    <title>记录</title>
    <link href="/partner/css/jquery.pagination.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/partner/css/shijian.css">
    <link rel="stylesheet" href="/partner/css/jquery-ui.min.css">
    <link rel="stylesheet" href="/partner/css/font-awesome.min.css">
    <link rel="stylesheet" href="/partner/css/commen.css">
    <script type="text/javascript" src="/partner/js/jquery.min.js"></script>
    <script src="/partner/js/layer/layer.js"></script>
    <script type="text/javascript" src="/partner/js/auto-size.js"></script>
    <style>
        html, body {
            background-color: #edf0f5;
            font-size: 0.28rem;
            height: 100%;
        }

        .head {
            background-color: #fff;
        }

        .content {
            margin-top: 0.916rem;
            position: relative;
            z-index: 999;
        }

        .content ul {
            background-color: #fff;
        }

        .content ul li {
            float: left;
        }

        .content > ul.select li {
            width: 50%;
            height: 0.7rem;
            line-height: 0.7rem;
            position: relative;
            text-align: center;
        }

        .content > ul.select li > a > i {
            font-size: 0.4rem;
        }

        .content > ul.select li > a > i:before {
            content: "\f107";
        }

        .content > ul.select li.curr i:before {
            content: "\f106";
        }
        .content > ul.select li.curr a{
            color: #ff9b12;
        }
        .content > ul.option {
            width: 80%;
            margin: 0 auto;
            display: none;
            background-color: #fff;
            color: #000;
        }

        .content > ul.option li {
            width: 33.3%;
            height: 1.2rem;
            line-height: 1.2rem;
            position: relative;
            text-align: center;
        }
        .content > ul.option1 li {
            width: 25%;
            height: 1.2rem;
            position: relative;
            text-align: center;
        }

        .content > ul.option li.curr span {
            background-color: #000;
            padding: 0.1rem 0.05rem;
            border-radius: 0.05rem;
            color: #fff;
        }

        .mask {
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 2;
            position: absolute;
        }

        .mask.in {
            display: block;
        }

        .time {
            height: 0.8rem;
            line-height: 0.8rem;
            padding-left: 0.3em;
        }

        .time img {
            width: 0.35rem;
            vertical-align: middle;
            margin-right: 0.2rem;
        }
        table{
            width: 100%;
            border-collapse:collapse;
            background-color: #fff;
            color: #3a3a3a;
        }
        table tbody tr{
            border-bottom:1px solid #d9d9d9;
            height: 1rem;
        }
        table td{
            padding-left: 0.2rem;
            font-size:0.26rem;
        }
        table div.tea_name{
            font-size: 0.3rem;
        }
        .tea_time{
            font-size: 0.24rem;
            color:#ccc;
        }
        .time input{
            background-color: transparent;
            font-size: 0.28rem;
        }
        #input1{
            background: url("/partner/images/rl_03.png") no-repeat;
            background-size: 0.35rem 0.35rem;
            padding-left: 0.36rem;
        }
        table tr td:nth-child(3) div{
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            width: 1.8rem;
        }
        table th{
            font-weight: normal;
        }
        .ui-pagination-container .ui-pagination-page-item.active {
            background: #fdde72;
            border-color: #fdde72;
            color: #fff;
            cursor: default;
        }
    </style>
</head>
<body>
<div class="mask"></div>
<div class="head">
    <a href="javascript:history.go(-1);" class="back">
        <img src="/partner/images/fh_03.png" alt="">
    </a>
    <p style="color: #000;font-size: 0.35rem;">记录</p>
</div>
<div class="content">
    <ul class="select">
        <li class="jylx"><a href="javascript:;">交易类型<i class="fa"></i></a>

        </li>
        <li class="jyjl"><a href="javascript:;">交易记录 <i class="fa"></i></a>
        </li>
        <div class="clear"></div>
    </ul>
    <ul class="option option1">
        <li class="curr"><span data-id="0">全部</span></li>
        <li><span data-id="1">茶点</span></li>
        <li><span data-id="2">茶劵</span></li>
        <!--<li><span data-id="3">可用资金</span></li>-->
        <div class="clear"></div>
    </ul>
    <ul class="option option2">
        <li class="curr"><span data-id="0">全部</span></li>
        <li><span data-id="1">收益</span></li>
        <li><span data-id="2">支出</span></li>
        <div class="clear"></div>
    </ul>
</div>
<!--<div class="time">-->
     <!--<input type="text" id="input1" value=""/>-->
<!--</div>-->
<table id="table">

</table>
<div class="box" style="padding-bottom: 2.1rem;">

    <div id="pagination3" class="page fr"></div>

</div>
<div id="footer">
    <div class="item items">
        <a href="<?php echo url('index/index'); ?>"><span class="footer_home"></span>首页</a>
        <a href="<?php echo url('index/willopen'); ?>"><span class="footer_xuancha"></span>门店收益</a>
        <a href="javascript:;" class="check"><span class="footer_shopcar"></span>记录</a>
        <a href="<?php echo url('index/custom_info'); ?>"><span class="footer_wode"></span>我的</a>
    </div>
</div>
<script src="/partner/js/jquery-ui.min.js"></script>
<script src="/partner/js/datepicker-zh-CN.js"></script>
<script src="/partner/js/jquery.pagination.min.js" type="text/javascript"></script>
<script src="/partner/js/jquer_shijian.js" type="text/javascript"></script>
<script>
    $(function () {
        var now=getNowFormatDate()
        $("#input1").val(now)
        var page=1
        var time=''       //获取日期与时间
        var count_page='';
        var count=''
        var type1=$(".option1 li.curr span").attr("data-id")
        var type2=$(".option2 li.curr span").attr("data-id")
        getdata(page,time,type1,type2)
        $("#input1").shijian({

            y: +50,//当前年份+50

            startYear: 1989,

            Hour: false,//是否显示小时

            Minute: false,//是否显分钟

            Seconds: false,//是否显秒，

            okfun: function () {

                time = $("#input1").val();
                var type1=$(".option1 li.curr span").attr("data-id")
                var type2=$(".option2 li.curr span").attr("data-id")
                getdata(page, time,type1, type2)

            }

        })
        $("#dateCrmlistExpireTime").datepicker();
        $(".content>ul.select>li.jylx").click(function () {
            $(".content>ul.select>li.jyjl").removeClass("curr")
            $(this).toggleClass("curr")
            $(".mask").toggleClass("in")
            $("ul.option1").fadeToggle()
            $("ul.option2").hide()
        })
        $(".content>ul.select>li.jyjl").click(function () {
            $(".content>ul.select>li.jylx").removeClass("curr")
            $(this).toggleClass("curr")
            $(".mask").toggleClass("in")
            $("ul.option1").hide()
            $("ul.option2").fadeToggle()
        })
        $(".content>ul.option>li").click(function () {
            $(this).addClass("curr").siblings().removeClass("curr")
        })
        $('#pagination3').pagination({

            showData: 8,

            count: 3,

            pageCount:count_page,

            homePageText: "首页",

            endPageText: "尾页",

            prevPageText: "上一页",

            nextPageText: "下一页",

            mode: 'fixed',

            coping:false,

            callback: function (currentPage) {

                page = currentPage
                var type1_1=$(".option1 li.curr span").attr("data-id")
                var type2_2=$(".option2 li.curr span").attr("data-id")
                getdata(page, time, type1_1, type2_2);



            }

        })
        $("ul.option li").click(function () {
            page=1;
            var type1_1=$(".option1 li.curr span").attr("data-id")
            var type2_2=$(".option2 li.curr span").attr("data-id")
            getdata(page, time, type1_1, type2_2);
            $("ul.option").hide()
            $(".mask").toggleClass("in")
            $(".content>ul.select>li.jylx").removeClass("curr")
        })

    function getdata(page, time, type1, type2) {
        $.ajax({

            type: "POST",

            url:"<?php echo url('shareholder/recordType'); ?>",

            data: {

                page: page,

                time: time,

                type: type1,

                usetype: type2

            },

            dataType: "json",

            success: function (data) {
                count = data.count
                count_page = Math.ceil(count / 8)
                $("#table").html('')

                var str = ""

                str += "<tr bgcolor='#fdde72' height='38px' style='color:#fff;'>"

                    + " <th>茶劵</th>"

                    + " <th>茶点</th>"

                    + "<th style='width: 1.8rem;'>说明</th>"

                    + "<th style='width: 1.8rem;'>时间</th></tr>";



                for (var i = 0; i < data.list.length; i++) {

                    str += '<tr>\n' +

                        '            <td class="green">' + ((data.list[i].tea_inte == null) ? 0 : data.list[i].tea_inte) + '</td>\n' +

                        '            <td class="green">' + ((data.list[i].tea_ponit_inte == null) ? 0 : data.list[i].tea_ponit_inte) + '</td>\n' +

                        '            <td><div>' + data.list[i].introduce + '</div></td>\n' +

                        '            <td>' + getLocalTime(data.list[i].addtime) + '</td>\n' +

                        '        </tr>'

                }

                $("#table").html(str);

                $("#pagination3").pagination("setPage", page, count_page);

                var green = $("td.green").text();



                if(green.charAt(0) == "+"){

                    $("td.green").css({"color":"green"})

                }else if(green.charAt(0) == "-"){

                    $("td.green").css({"color":"red"})

                }



            }

        });

    }
    function getNowFormatDate() {
        var date = new Date();
        var seperator1 = "-";
        var seperator2 = ":";
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
        return currentdate;
    }
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

</body>
</html>