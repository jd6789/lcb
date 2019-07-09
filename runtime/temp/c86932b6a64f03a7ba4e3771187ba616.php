<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/admin/rule_show.html";i:1524123364;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
</head>
<body>
<div class="cont_search clearfix">
    <a class="btngreen search fl" href="javascript:void(0)" style="visibility: hidden">查询</a>
    <a class="export fl" href="<?php echo URL('tmvip/Admin/add_rule'); ?>">添加权限</a>
</div>
<div class="bench white-scroll">
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>权限名称</td>
                <td>模型名称</td>
                <td>控制器名称</td>
                <td>方法名称</td>
                <td>是否导航显示</td>
                <td>操作</td>
            </tr>

            <!---->
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tr>
                <td  class="first-cell" STYLE="text-align: left">
                    <span><a href="javascript:void(0)">|<?php echo str_repeat('--',$vo['lev']); ?><?php echo $vo['rule_name']; ?></a></span>
                </td>
                <td width="15%"><?php echo $vo['module_name']; ?></td>
                <td width="15%"><?php echo $vo['controller_name']; ?></td>
                <td width="15%"><?php echo $vo['action_name']; ?></td>
                <td width="15%"><img src="/admin/images/<?php if($vo['is_show'] == '1'): ?>yes.gif<?php else: ?>no.gif<?php endif; ?>"  /></td>

                <td width="30%" align="center">
                    <a href="<?php echo URL('tmvip/Admin/edit_rule',['id'=>$vo['id']]); ?>">编辑</a> |
                    <a href="<?php echo URL('tmvip/Admin/del_rule',['id'=>$vo['id']]); ?>" title="移除" onclick="">移除</a>
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