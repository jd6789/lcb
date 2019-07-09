<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/report/lichabao.html";i:1524828036;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
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
    <div>
        <form action="" method="post">
            用户名搜索：<input type="text" name="username" value="">
            <input type="submit" value="查询" class="btngreen search ">
        </form>
    </div>
    <div>
        <!--<table>-->
            <!--<tr>-->
                <!--<td>-->
                    <!--<?php if($data['money'] == 0): ?>现金支付的金额￥<span style="color:red">0</span>&nbsp;&nbsp;-->
                        <!--<?php else: ?>-->
                        <!--现金支付的金额￥<span style="color:red"><?php echo $data['money']; ?></span>&nbsp;&nbsp;-->
                    <!--<?php endif; ?>-->
                <!--</td>-->
                <!--<td>-->
                    <!--<?php if($data['again_money'] == 0): ?>-->
                        <!--升级理茶宝消耗的茶籽<span style="color:red"><?php echo $data['again_money']; ?></span>&nbsp;&nbsp;-->
                    <!--<?php endif; ?>-->
                <!--</td>-->
            <!--</tr>-->
        <!--</table>-->
    </div>
</div>
<div class="bench white-scroll" >
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>用户名</td>
                <td>商品名称</td>
                <td>理茶宝等级</td>
                <td>实付金额</td>
                <td>消费时间</td>
            </tr>

            <?php if(is_array($data['info']) || $data['info'] instanceof \think\Collection || $data['info'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>
                <tr align="center">
                    <td><?php echo $data[$v['user_id']]['username']; ?></td>
                    <td><?php echo $v['body']; ?></td>
                    <td><?php echo $data[$v['user_id']]['rec_money']; ?></td>
                    <td><?php if($v['again_money'] == 0): ?>￥<?php echo $data[$v['user_id']]['recharge_money']; else: ?>￥<?php echo $data[$v['user_id']]['recharge_money']; ?>+<?php echo $data[$v['user_id']]['again_money']; ?>茶籽<?php endif; ?></td>
                    <td><?php echo date('Y-m-d H:i:s',$v['rec_addtime']); ?></td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <!---->
            </tbody>
        </table><p><?php echo $data['info']->render(); ?></p>
    </div>
    <div class="page clearfix">
        <div class="text">共<b>1</b>页<b>4</b>条记录</div>
        <div class="linklist">
            <a class="prev" href="javascript:void(0)">&nbsp;</a>
            <a href="javascript:void(0)" class="current">1</a>
            <a class="next" href="javascript:void(0)">&nbsp;</a>
        </div>
    </div>
</div>
</body>
</html>