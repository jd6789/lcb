<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/user/wallet_change.html";i:1524619658;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>交易记录</title>
    <link rel="stylesheet" href="/admin/css/jquery-ui.min.css">
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
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
        .content{
            background-color: #f6f6f6;
            padding: 0px;
            margin: 0;

        }
        .bx{
            background-color: #fff;
            padding:30px 20px;
        }
        .search{
            font-size: 14px;
            margin-bottom: 25px;
        }
        .search span{
            padding:5px 30px;
            margin-left: 15px;
            cursor: pointer;
        }
        .search span.curr{
            background-color: #f7f3eb;
            color: #8c8577;
        }
        table{
            width: 100%;
            margin-top: 20px;
        }


    </style>
</head>
<body>
<div class="content">
    <div class="bx">
    <form action="" method="post">
        <div class="search1 search">
            起止时间： <input class="calendar ng-untouched ng-pristine ng-valid " id="saleReportBeginTime" type="text" name="time1" >&nbsp;&nbsp;至&nbsp;&nbsp;<input
                class="calendar ng-untouched ng-pristine ng-valid "
                id="saleReportEndTime" type="text" name="time2" >
            手机号<input type="text" name="tel" size="15" />
            用户名<input type="text" name="user" size="15" />
        </div>
        <div class="search2 search">
            交易记录： <span class="curr"  >全部 <input type="hidden" value="1" name="type"></span> <span>充值<input type="hidden" value="2"></span> <span>支出<input type="hidden" value="3"></span> <span >提现<input type="hidden" value="4"></span> <span >退回余额<input type="hidden" value="5"></span>
        </div>
        <div class="search3 search">
            <!--交易类型： <span class="curr">转入</span> <span>收益</span> <span>支出</span> -->
            <input type="submit" value="查询" class="btngreen search ">
        </div>
        </form>
            <div class="globaltable r5px">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td>用户名</td>
                        <td>手机号</td>
                        <td>充值前总额</td>
                        <td>充值额度</td>
                        <td>说明</td>
                        <td>时间</td>
                    </tr>
                    <?php if(is_array($data['info']) || $data['info'] instanceof \think\Collection || $data['info'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td align="center" class="first-cell"><span><?php echo $data[$v['user_id']]['user_name']; ?></span></td>
                        <td align="center"><span onclick=""><?php echo $data[$v['user_id']]['mobile_phone']; ?></span></td>
                        <td align="center"><span onclick=""><?php echo $v['sum_inte']; ?> </span></td>
                        <td align="center"><span onclick=""><?php echo $v['surplus_inte']; ?> </span></td>
                        <td align="center"><span><?php echo $v['introduce']; ?></span></td>
                        <td align="center"><span><?php echo date('Y-m-d h:i:s',$v['addtime']); ?></span></td>
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                <p><?php echo $data['info']->render(); ?></p>
            </div>

    </div>
</div>
<script src="/admin/js/jquery.min.js"></script>
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
</body>
</html>