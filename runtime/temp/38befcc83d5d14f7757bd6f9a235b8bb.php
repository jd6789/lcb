<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:92:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/recharge/recharge_info.html";i:1537435037;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>修改股东规则</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
</head>
<body>
<div class="cont_search clearfix">
    <a class="btngreen search fl" href="javascript:void(0)" style="visibility: hidden">查询</a>
    <a class="export fl" href="<?php echo URL('tmvip/recharge/add_recharge'); ?>">添加理茶宝规则</a>
</div>
<div class="white-scroll">
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>充值金额</td>
                <td>充值返还总积分</td>
                <td>名称</td>
                <td width="10%">操作</td>
            </tr>

            <!---->
            <?php if(is_array($info) || $info instanceof \think\Collection || $info instanceof \think\Paginator): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tr>
                <td align="center" ><span><?php echo $vo['rec_money']; ?></span></td>
                <td align="center" ><span><?php echo $vo['total_inte']; ?></span></td>
                <td align="center" ><span><?php echo $vo['body']; ?></span></td>
                <td align="center">
                    <a href="<?php echo URL('tmvip/recharge/recharge_edit','recharge_id='.$vo['recharge_id']); ?>">编辑</a>
                    <a  href="<?php echo URL('tmvip/recharge/recharge_del','recharge_id='.$vo['recharge_id']); ?>" >删除</a>
                </td>
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