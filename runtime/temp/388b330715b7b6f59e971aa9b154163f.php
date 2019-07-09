<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:93:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/user/lcb_quantity_chart.html";i:1525231966;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
    <link href="/admin/css/bootstrap.min.css" rel="stylesheet">
    <script src="/admin/js/jquery.min.js"></script>
    <link rel="stylesheet" href="/admin/css/jquery-ui.min.css">
    <script src="/admin/js/bootstrap.min.js"></script>
    <script src="/admin/js/distpicker.data.js"></script>
    <script src="/admin/js/distpicker.js"></script>
    <script src="/admin/js/main.js"></script>
    <script src="/admin/js/highcharts.js"></script>
    <style>
        .viewsmall.in {
            min-height: 70%;
            width: 100%;
            top: 2%;
            padding-left: 20px;
        }
        .turn_page ul, .turn_page1 ul {
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
            padding: 1px 7px;
            text-decoration: none;
            background-color: #fff;
            /*border-left-width: 0;*/
            margin-left: 15px;
            color: #639a67;
            border: 1px solid #639a67;
        }

        .turn_page ul li a.active, .turn_page1 ul li a.active {
            color: #fff;
            background: #639a67;
        }

        .turn_page ul li a:hover, .turn_page1 ul li a:hover {
            color: #fff;
            background: #639a67;
        }

        .turn_page span, .turn_page1 span {
            float: left;
            margin-left: 15px;
        }

        .turn_page span i, .turn_page1 span i {
            font-style: normal;
        }
    </style>
</head>
<body>
<div class="viewsmall form_cont r10px in" style="transform: translateX(-37%);">
    <div class="search1 search">
    起止时间： <input class="calendar ng-untouched ng-pristine ng-valid " id="saleReportBeginTime" type="text" name="time1" >&nbsp;&nbsp;至&nbsp;&nbsp;<input
        class="calendar ng-untouched ng-pristine ng-valid "
        id="saleReportEndTime" type="text" name="time2" >
        <button id="button1">查询</button>
    <!--手机号<input type="text" name="tel" size="15" />-->
    <!--用户名<input type="text" name="user" size="15" />-->
</div>
    <div id="container" style="min-width:400px;height:400px;margin: 0 auto;"></div>
    <div class="globaltable r5px">
        <table id="table1" border="1">

        </table>
    </div>
</div>
</body>
</html>
<script src="/admin/js/jquery-ui.min.js"></script>
<script src="/admin/js/datepicker-zh-CN.js"></script>
<script>
    $("#saleReportBeginTime").datepicker();
    $("#saleReportEndTime").datepicker();
    $(function () {
        $(".search2 span").click(function () {
            $(".search2 span input").removeAttr("name")
            $(this).addClass("curr").siblings().removeClass("curr")
            $(this).find("input").attr("name","type")
        })

    })
</script>
<script>
    $("#button1").click(function(){
        var time1 = $('input[name^="time1"]').val();
        var time2 = $('input[name^="time2"]').val();
        getDate2_1(1,time1,time2)
    });
</script>
<script>
    getDate2_1(1,0,0)
    function getDate2_1(curPage, time1, time2) {
        if (time1 == 0) {
            var time1_1 = ""
        } else {
            time1_1 = time1
        }
        if (time2 == 0) {
            var time2_1 = ""
        } else {
            time2_1 = time2
        }
        $.ajax({
            url: "<?php echo URL('tmvip/User/lcb_quantity_chart'); ?>",
            type: "post",
            data: {time1: time1_1, time2: time2_1},
            success: function (data) {
                var data = JSON.parse(data)
                console.log(data)
                var str = ''
                var str1 = ''
                names = "";
                grades = "";
                place = "";
                place1 = "";
                place2 = "";
                place3 = "";
                xx = "";
                yy = "";
                zz = ""
                zz1 = "";
                zz2 = "";
                zz3 = "";

                for (var i = 0; i < data.list.length; i++) {
                    for (var j = 0; j < data['list'][i].length; j++) {
                        var name = data['list'][i][j]['time'][j];//X轴名称
                        var grade = data['list'][i][j]['lev_one'][j];//释放
                        var cz = data['list'][i][j]['lev_two'][j];
                        var cz1 = data['list'][i][j]['lev_three'][j];
                        var cz2 = data['list'][i][j]['lev_four'][j];
                        var cz3 = data['list'][i][j]['lev_five'][j];

                        names += ",'" + name + "'";
                        grades += "," + grade + "";
                        place += "," + cz + "";
                        place1 += "," + cz1 + "";
                        place2 += "," + cz2 + "";
                        place3 += "," + cz3 + "";
                    }
                }
                //console.log(name);
                xx = names.substring(1);
                xx = "[" + xx + "]";
                yy = grades.substring(1);
                yy = "[" + yy + "]";
                zz = place.substring(1);
                zz = "[" + zz + "]";
                zz1 = place1.substring(1);
                zz1 = "[" + zz1 + "]";
                zz2 = place2.substring(1);
                zz2 = "[" + zz2 + "]";
                zz3 = place3.substring(1);
                zz3 = "[" + zz3 + "]";
                tab2_1_chart()
                //table1(data.list1)

            }
        })
    }

    function tab2_1_chart() {
        //定制树状图颜色
        /*  Highcharts.setOptions({
                  colors:['#8000ff']
          });  */
        //图表展示容器，与div的id保持一致
        $('#container').highcharts({
            chart: {
                //指定图表的类型，默认是折线图（line）
                type: "column",
                //定制树状图颜色

            }, title: {

                //指定图表标题
                text: '',
            },

            //动态获取数据库的名字  就是所谓的X轴
            xAxis: {
                categories: eval(xx),
                crosshair: true
            },
            credits: {
                enabled: false
            },
            //动态获取数据
            series: [
                {
                    name: '1级理茶宝',
                    animation: true,
                    data: eval(yy)

                }, {
                    name: '2级理茶宝',
                    animation: true,
                    data: eval(zz)

                },{
                    name: '3级理茶宝',
                    animation: true,
                    data: eval(zz1)

                }
                ,{
                    name: '4级理茶宝',
                    animation: true,
                    data: eval(zz2)

                }
                ,{
                    name: '5级理茶宝',
                    animation: true,
                    data: eval(zz3)

                }],
            //Y轴标题
            yAxis: {
                title: {text: "积分数"}
            },

        });
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
</script>
<script>
    var c_page = 1;
    getData(c_page);
    var step = 15;
    function getData(curPage, time1, time2) {
        if (time1 == 0) {
            var time1_1 = ""
        } else {
            time1_1 = time1
        }
        if (time2 == 0) {
            var time2_1 = ""
        } else {
            time2_1 = time2
        }
        $.ajax({
            type: "POST",
            url: "<?php echo URL('tmvip/User/lcb_quantity_chart'); ?>",
            data: {time1: time1_1, time2: time2_1},
            dataType: "json",
            success: function (data) {
                var data=JSON.parse(data)
                $("#table1").html("");
                $(".turn_page").html("");

                var data_length=getPropertyCount(data.list1);
                var str = "<tr><td>用户名</td>"+
                    "<td>充值金额</td>"+
                    "<td>充值等级</td>"+
                    "<td>产品名称</td>"+
                    "<td>时间</td>" +
                    "</tr>";
                var x_page = curPage;
                curPage = (curPage - 1) * step;

                var count = curPage + step;
                if (data_length > x_page * step) {

                } else {
                    count = data_length;
                }
                for (var i = curPage; i < count; i++) {

                    str += "<tr>"+

                        "<td>"+data.list1[i]['user_name']+"</td>"+
                        "<td>"+data.list1[i]['rec_money']+"</td>"+
                        "<td>"+data.list1[i]['rec_lev']+"</td>"+
                        "<td>"+data.list1[i]['body']+"</td>"+
                        "<td>"+getLocalTime(data.list1[i]['addtime'])+"</td>"+

                        "</tr>";

                }

                $("#table1").html(str);        //重绘table

                var pageNum = Math.ceil(data_length / step);    //获取得到的数据页数


                str = "";

                /*若页数大于1则添加上一页、下一页链接*/
                if (Math.ceil(data_length / step) > 1) {
                    str = "<ul><li><a href='javascript:void(0);onclick=preEvent(" + time1 + "," + time2 + ");' id='pre' data-num='1'>上一页</a></li>"
                } else {
                    str = "<ul>";
                }
                /*循环输出每一页的链接*/
                for (var i = 0; i < Math.ceil(data_length / step); i++) {
                    str += "<li><a href='javascript:void(0);onclick=getData(" + (parseInt(i) + 1) + "," + time1 + "," + time2 + ");' data-type='num'>" + (parseInt(i) + 1) + "</a></li>";
                }
                if (str.indexOf("上一页") > -1) {
                    str += "<li><a href='javascript:void(0);onclick=nextEvent(" + time1 + "," + time2 + ");' id='next' data-num='1'>下一页</a></li>"
                        + "<span>共<i id='pageNum'>" + pageNum + "</i>页</span></ul>";

                } else {
                    str += "<span>共<i id='pageNum'>" + pageNum + "</i>页</span></ul>";

                }
                $(".turn_page").html(str);
                addClass(x_page)
                $("#pre").attr("data-num", x_page);
                $("#next").attr("data-num", x_page);
                //把当前页码存到上一页、下一页的data-num属性中，这样可以在点击上一页或者下一页时知道应该跳到哪页
            },
            error: function (data) {

                alert("请求失败");
            }
        });
    }

    /**
     * 上一页点击事件
     */
    function preEvent(time1,time2) {
        var c_page = $("#pre").attr("data-num");
        if (c_page <= 1) {
            $(this).attr('disabled', "true");
        } else {
            c_page = parseInt(c_page) - 1;
            getData(c_page,time1,time2);
        }
    }
    /**
     * 下一页点击事件
     */
    function nextEvent(time1,time2) {
        var c_page = $("#next").attr("data-num");
        var pageNum = $("#pageNum").text();
        if (c_page >= pageNum) {
            $(this).attr('disabled', "true");
        } else {
            c_page = parseInt(c_page) + 1;
            getData(c_page,time1,time2);
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