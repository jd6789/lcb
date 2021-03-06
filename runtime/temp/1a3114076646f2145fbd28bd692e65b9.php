<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/user/myrichardtea.html";i:1529916241;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
    <link href="/mobile/css/reset.css" rel="stylesheet" type="text/css">
    <link href="/mobile/css/common.css" rel="stylesheet" type="text/css">
    <link href="/mobile/css/iconfont.css" rel="stylesheet" type="text/css">
    <link href="/mobile/css/page.css" rel="stylesheet" type="text/css">
    <link href="/mobile/css/myrichardtea.css" rel="stylesheet" type="text/css">
    <link href="/mobile/css/layer.css" rel="stylesheet" type="text/css">
    <title>我的理茶宝</title>
    <style>
        .btn2 {
            color: #00a0e9;
            border: none;
            background-color: transparent;
            width: 0.7rem;
        }
    </style>
</head>
<body>
<div class="content"><!--content-->

    <div class="top"><!--top-->
        <a class="back" href="<?php echo url('myinfo'); ?>"><i class="iconfont icon-fanhui"></i></a>
        <span class="login">我的理茶宝</span>
    </div><!--top-->


    <div class="registration"><!--registration-->
        <div class="registration1">
            <div><input id="againbuy" type="button" class="generations" value="产品升级"></div>
            <div><input id="buy" type="button" class="generations" value="购买激活"></div>
        </div>
    </div><!--registration-->


    <div class="level"><!--level-->
        <table border="0" cellpadding="0" cellspacing="0" align="center" id="table">

        </table>
        <div class="turn_page" style="text-align: center;margin-top: 10px;"></div>
    </div><!--level-->
    <div style="margin-top: 15px;color:#ccc;text-align: center;display: none;" id="tips">
        您没有购买过理茶宝产品
    </div>

</div><!--content-->
<script type="text/javascript" src="/mobile/js/jquery-1.9.0.min.js"></script>
<script src="/mobile/js/layer.js"></script>
<script src="/mobile/js/url.js"></script>
<script>
    var c_page = 1;
    getData(c_page);
    var step = 10;

    function getData(curPage) {
        $.ajax({
            type: "POST",
            url: "<?php echo url('recharge/myRechargeIndex'); ?>",
            dataType: "json",
            success: function (data) {
                if (data == 0) {
                    $("#tips").show()
                    return false
                }
                $("#table").html("");
                $(".turn_page").html("");
                var data_length = getPropertyCount(data);
                var str = "<tr bgcolor='#e1e1e1'><th >编号</th>"
                    + "<th>交易类型</th>"
                    + "<th>理茶宝等级金额</th>"
                    + "<th>实付金额</th>"
                    + "<th>是否支付</th>"
                    + "<th style='width: 0.6rem;'>交易时间</th>"
                    + "<th>操作</th></tr>";
                var x_page = curPage;
                curPage = (curPage - 1) * step;

                var count = curPage + step;
                if (data_length > x_page * step) {

                } else {
                    count = data_length;
                }
                for (var i = curPage; i < count; i++) {
                    str += ' <tr>\n' +
                        '                            <td>' + (i + 1) + '</td>'
                    if (data[i].is_againbuy == 1) {
                        str += '<td>理茶宝升级</td>'
                    } else {
                        str += '<td>购买理茶宝产品</td>'
                    }
                    str += '<td>￥' + data[i].rec_money + '</td>'
                    if (data[i].again_money == 0) {
                        str += '<td>￥' + data[i].recharge_money + '</td>'
                    } else {
                        str += '<td>￥' + data[i].recharge_money + '+' + data[i].again_money + '余额' + '</td>'
                    }
                    if (data[i].pay_status == 1) {
                        str += '<td>已支付</td>'
                    } else {
                        if (data[i].is_againbuy == 1) {
                            //升级理茶宝继续支付
                            str += '<td><a href="<?php echo url('recharge/confirmupdate'); ?>?id=' + data[i].id + '">继续支付</a></td>'
                        } else {
                            //购买理茶宝继续支付
//                            str+='<td><a href="Index_pay.html?id='+data[i].id+'">继续支付</a></td>'
                            str += '<td><a href="<?php echo url('user/confirm'); ?>?id=' + data[i].id + '">继续支付</a></td>'
                        }

                    }
                    str += '<td>' + getLocalTime(data[i].rec_addtime) + '</td>'
                    str +=
                        '                            <td align="center">\n' +
                        '                                <input type="text" value="' + data[i].is_active + '" class="h_btnnn' + i + '" hidden>\n' +
                        '                                <input type="text" value="' + data[i].pay_status + '" hidden class="h_btn' + i + '">\n' +
                        '                                <button style="display: none;" class="btn1">激活</button>\n' +
                        '                                <input type="text" value="' + data[i].is_againbuy + '" class="h_btnn' + i + '" hidden>\n' +
                        '                                <button style="display: none;" class="btn2">取消订单</button>\n' +
                        '                                <input type="text" value="' + data[i].id + '" hidden>\n' +
                        '                            </td>\n' +
                        '                        </tr>'
                }

                $("#table").html(str);        //重绘table
                var rows = $("#table").find("tr").length;
                for (var i = 0; i < rows; i++) {
                    var a = $(".h_btn" + i + "").val();
                    var b = $(".h_btnn" + i + "").val();
                    var c = $(".h_btnnn" + i + "").val();
                    if (a == "1" && b == "0") {
                        $(".h_btn" + i + "").next().css("display", "inline-block");
                    }
                    if (a == "0") {
                        $(".h_btnn" + i + "").next().css("display", "inline-block");
                    }
                    if (c == "1") {
                        $(".h_btnnn" + i + "").next().next().text("已激活");
                        $(".h_btnnn" + i + "").next().next().css("background", "#f5f5f5");
                        $(".h_btnnn" + i + "").next().next().css("color", "#333");
                        $(".h_btnnn" + i + "").next().next().css("border", "none");
                        $(".h_btnnn" + i + "").parent().find(".btn1").attr("disabled", true)
                    }
                }
                var pageNum = Math.ceil(data_length / step);    //获取得到的数据页数


                str = "";

                /*若页数大于1则添加上一页、下一页链接*/
                if (Math.ceil(data_length / step) > 1) {
                    str = "<ul><li><a href='javascript:void(0);onclick=preEvent();' id='pre' data-num='1'>上一页</a></li>"
                } else {
                    str = "<ul>";
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
            },
            error: function (data) {

                alert("请求失败");
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

    function getPropertyCount(o) {
        var n, count = 0;
        for (n in o) {
            if (o.hasOwnProperty(n)) {
                count++;
            }
        }
        return count;
    }
</script>
<script>
    $(function () {
        $("#table").on("click", ".btn1", function () {
            $(this).addClass("click")
            var a = $(this).next().next().next().val()
            $.ajax({
                url: "<?php echo url('alluser/active_tea_treasure'); ?>",
                type: "post",
                data: {id: a},
                success: function (msg) {
                    if (msg == 1) {
                        layer.msg("激活完成");
                        setTimeout(function () {
                            location.reload()
                        }, 500)

                    }
                    if (msg == 0) {
                        layer.msg("激活失败");
                    }
                    if (msg == 2) {
                        layer.msg('推荐人尚未填写，前往填写');
                        location.href = "<?php echo url('user/realname'); ?>"
                    }
                }
            })
        })
        $("#table").on("click", ".btn2", function () {
            var id = $(this).next().val()
            $.ajax({
                url: "<?php echo url('recharge/del'); ?>",
                type: "post",
                data: {id: id},
                success: function (msg) {
                    if (msg == 1) {
                        layer.msg("取消成功");
                        setTimeout(function () {
                            location.reload();
                        }, 1000)
                    } else {
                        layer.msg("取消失败");
                    }
                }
            })
        })
        $("#buy").click(function () {

            $.ajax({
                url: "<?php echo url('recharge/checkToActive'); ?>",
                type: 'post',
                success: function (msg) {

                    if (msg.status == 1) {
                        layer.msg('前往购买激活');
                        setTimeout(function () {
                            location.href = "<?php echo url('user/richardtea'); ?>"
                        }, 2000)

                    }
                    if (msg.status == 0) {
                        layer.msg('信息不完整');
                        setTimeout(function () {
                            location.href = "<?php echo url('user/realname'); ?>"
                        }, 2000)

                    }
                    if (msg.status == 2) {
                        layer.msg('您已购买理茶宝');
                    }
                    if (msg.status == 3) {
                        layer.msg('您有尚未支付的理茶宝产品，请支付或者取消');
                    }
                }
            })
        })
        $("#againbuy").click(function () {

            $.ajax({
                url: "<?php echo url('recharge/allowproduct'); ?>",
                type: "get",
                success: function (msg) {
                    if (msg == 1) {
                        layer.msg('您未购买过理茶宝产品，请点击前往购买激活购买');
                    } else {
                        location.href = "<?php echo url('recharge/richardtea'); ?>"
                    }
                }
            })
        })

    });

</script>



</body>
</html>