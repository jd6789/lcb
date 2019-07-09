<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/report/consumption.html";i:1531291892;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>消费统计</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
    <link rel="stylesheet" href="/admin/css/jquery-ui.min.css">
    <script src="/admin/js/jquery-ui.min.js"></script>
    <script src="/admin/js/datepicker-zh-CN.js"></script>
    <script>
        $("#saleReportBeginTime").datepicker();
        $("#saleReportEndTime").datepicker();
    </script>
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
    </style>
</head>
<body>
<div class="cont_search clearfix">
    <form action="" method="post">
        用户名搜索：<input type="text" name="username" value="">
        <select name="cate" >
            <option value="2" selected>购买商品</option>
            <option value="1">门店消费</option>
            <option value="3">股东提现</option>
        </select>
        <input type="submit" value="查询" class="btngreen search ">
    </form>
    <div>
        <table>
            <tr>
                <td>消费的茶券:<span style="color:red"><?php if($data['rec_tea_inte'] == 0): ?>0<?php else: ?><?php echo $data['rec_tea_inte']; endif; ?></span>&nbsp;&nbsp;</td>
                <td>
                    <?php if($data['rec_tea_ponit_inte'] == 0): ?>消费的茶点:<span style="color:red">0</span>
                    <?php else: ?> 消费的茶点:<span style="color:red"><?php echo $data['rec_tea_ponit_inte']; ?></span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="bench white-scroll" >
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>编号</td>
                <td>用户名</td>
                <td>消费说明</td>
                <td>消费金额</td>
                <td>消费时间</td>
            </tr>
            <?php if(is_array($data['log']) || $data['log'] instanceof \think\Collection || $data['log'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['log'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>
            <tr align="center">
                <td><?php echo $v['id']; ?></td>
                <td><?php echo $data[$v['user_id']]['username']; ?></td>
                <td><?php echo $v['introduce']; ?></td>
                <td><if condition="$v.tea_inte neq 0 ">消费茶券<?php echo $v['tea_inte']; ?><else />&nbsp;&nbsp;&nbsp;消费茶点<?php echo $v['tea_ponit_inte']; ?></if></td>
                <td><?php echo date('Y-m-d H:i:s',$v['addtime']); ?></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <!---->
            </tbody>
        </table><p><?php echo $data['log']->render(); ?></p>
    </div>
</div>
</body>
</html>