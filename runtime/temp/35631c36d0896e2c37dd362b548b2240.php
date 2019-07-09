<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/admin/admin_show.html";i:1524129600;}*/ ?>
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
    <a class="export fl" href="<?php echo URL('tmvip/Admin/add_admin'); ?>">添加管理员</a>
</div>
<div class="bench white-scroll" >
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>管理员登录名</td>
                <td>管理员密码</td>
                <td>管理员名称</td>
                <td>角色名称</td>
                <td>添加时间</td>
                <td>操作</td>
            </tr>

            <!---->
            <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr>
                <td align="center" class="first-cell"><span><?php echo $v['username']; ?></span></td>
                <td align="center"><span onclick=""><?php echo $v['password']; ?></span></td>
                <td align="center"><span onclick=""><?php echo $v['name']; ?></span></td>
                <td align="center"><span onclick=""><?php echo $v['role_name']; ?></span></td>
                <td align="center"><span><?php echo date('Y-m-d h:i:s',$v['addtime']); ?></span></td>
                <td align="center"><a href="<?php echo URL('tmvip/Admin/reset_pwd',['user_id'=>$v['id']]); ?>">重置登录密码</a>&nbsp;&nbsp;&nbsp;
                                   <a href="<?php echo URL('tmvip/Admin/user_edit',['user_id'=>$v['id']]); ?>">用户信息修改</a>&nbsp;&nbsp;
                                   <a href="<?php echo URL('tmvip/Admin/user_del',['user_id'=>$v['id']]); ?>">删除</a>
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