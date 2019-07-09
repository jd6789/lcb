<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:89:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/report/integral_log.html";i:1542965978;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>积分日志记录</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
    <link rel="stylesheet" href="/admin/css/jquery-ui.min.css">
    <script src="/admin/js/jquery.min.js"></script>
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
            用户名搜索：<input type="text" name="username" value="<?php echo \think\Session::get('log_name.username'); ?>" id="user">&nbsp;&nbsp;&nbsp;&nbsp;<input type="date" name="time1" id="time1" value="<?php echo \think\Session::get('log_name.time1'); ?>">&nbsp;-&nbsp;<input type="date" name="time2" id="time2" value="<?php echo \think\Session::get('log_name.time2'); ?>">
            <input type="submit" value="查询" class="btngreen search ">
            <a href="javascript:;" style="float: right;" id="download">导出excel</a>
        </form>

    </div>
    <div>
    </div>
</div>
<div class="bench white-scroll" >
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>编号</td>
                <td>用户名</td>
                <td>茶券</td>
                <td>茶点</td>
                <td>说明</td>
                <td>时间</td>
            </tr>

            <?php if(is_array($data['info']) || $data['info'] instanceof \think\Collection || $data['info'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>
            <tr align="center">
                <td><?php echo $v['id']; ?></td>
                <td><?php echo $data[$v['user_id']]['username']; ?></td>
                <td><?php echo $v['tea_inte']; ?></td>
                <td><?php echo $v['tea_ponit_inte']; ?></td>
                <td><?php echo $v['introduce']; ?></td>
                <td><?php echo $v['times']; ?></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <!---->
            </tbody>
        </table><p><?php echo $data['info']->render(); ?></p>
    </div>
</div>
<script>
    $(function () {
        $("#download").click(function () {
            var user=$("#user").val()
            var time1=$("#time1").val()
            var time2=$("#time2").val()
            location.href="<?php echo URL('tmvip/Report/integral_log_excel'); ?>?time1="+time1+"&time2="+time2+'&username='+user
        })
    })
</script>
</body>
</html>