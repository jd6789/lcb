<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/report/vip_again.html";i:1524643496;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
    <link rel="stylesheet" href="/admin/css/jquery-ui.min.css">


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
            用户名搜索：<input type="text" name="username" value="" style="border:1px solid #ccc ">
            起始时间：<input type="text" class="calendar ng-untouched ng-pristine ng-valid "   name="howtime1" id="saleReportBeginTime"/>
            结束时间：<input type="text" class="calendar ng-untouched ng-pristine ng-valid "   name="howtime2" id="saleReportEndTime"/>
            <input type="submit" value="查询" style="padding:0 10px;line-height:20px;" class="btngreen search ">
        </form>
    </div>
    <div><table><tr><td><span>合计：</span><span style="color:red"><?php echo $dop['sum']; ?></span>&nbsp;&nbsp;<span>现金:</span><span style="color:red"><?php echo $dop['recharge_money']; ?></span>&nbsp;&nbsp;<span>茶籽:</span><span style="color:red"><?php echo $dop['again_money']; ?></span></td></tr></table></div>
</div>
<div class="bench white-scroll" >
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>会员账号</td>
                <td>升级前</td>
                <td>升级后</td>
                <td>金额</td>
                <td>现金</td>
                <td>茶籽</td>
                <td>时间</td>
            </tr>

            <!---->
            <?php if(is_array($dop['info']) || $dop['info'] instanceof \think\Collection || $dop['info'] instanceof \think\Paginator): $i = 0; $__LIST__ = $dop['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $v['name']; ?></td>
                <td><?php echo $v['last_lev']; ?>星</td>
                <td><?php echo $v['new_lev']; ?>星</td>
                <td><?php echo $v['sum']; ?></td>
                <td><?php echo $v['recharge_money']; ?></td>
                <td><?php echo $v['again_money']; ?></td>
                <td><?php echo date('Y-m-d H:i:s',$v['rec_addtime']); ?></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <!---->
            </tbody>
        </table>
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
<script src="/admin/js/jquery.min.js"></script>
<script src="/admin/js/jquery-ui.min.js"></script>
<script src="/admin/js/datepicker-zh-CN.js"></script>
<script>
    $(function () {
        $("#saleReportBeginTime").datepicker();
        $("#saleReportEndTime").datepicker();
    })

</script>