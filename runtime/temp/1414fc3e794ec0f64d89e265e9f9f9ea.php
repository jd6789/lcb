<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/system/user_recharge.html";i:1524619658;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
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
    <form action=""  method="post">
        <!-- 关键字 -->
        用户名<input type="text" name="username" size="15" />
        <input type="submit" value="查询" class="btngreen search ">
    </form>
</div>
<div class="bench white-scroll">
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>用户名</td>
                <td>充值金额</td>
                <td>购买时间</td>
                <td>应返积分</td>
                <td>是否激活</td>
                <td>激活时间</td>
            </tr>

            <!---->
            <?php if(is_array($data['info']) || $data['info'] instanceof \think\Collection || $data['info'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $data[$v['user_id']]['username']; ?></td>
                <td><?php echo $v['rec_money']; ?></td>
                <td><?php echo date('Y-m-d',$v['addtime']); ?></td>
                <td><?php echo $v['total_inte']; ?></td>
                <td><?php if($v['is_return'] == 1): ?>已激活<?php else: ?>未激活<?php endif; ?></td>
                <td><?php if($v['rec_addtime'] == ''): else: ?><?php echo date('Y-m-d',$v['rec_addtime']); endif; ?></td>
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