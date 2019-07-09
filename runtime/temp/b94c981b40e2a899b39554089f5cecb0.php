<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/user/user_detail.html";i:1545105231;}*/ ?>
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
        手机号<input type="text" name="tel" size="15" />
        用户名<input type="text" name="user" size="15" />
        <input type="submit" value="查询" class="btngreen search ">
        <a href="<?php echo URL('tmvip/User/user_rech_info'); ?>" style="float: right;">导出excel</a>
    </form>
</div>
<div class="white-scroll">
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>编号</td>
                <td>用户名</td>
                <td>手机号</td>
                <td>充值总额</td>
                <td>一级推荐人数</td>
                <td>二级推荐人数</td>
                <td>注册时间</td>
                <td>等级</td>
                <td>是否激活</td>
                <td>是否购买</td>
                <td>用户推荐人</td>
                <td>操作</td>
            </tr>

            <!---->
            <?php if(is_array($data['info']) || $data['info'] instanceof \think\Collection || $data['info'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr>
                <td align="center"><span onclick=""><?php echo $v['id']; ?> </span></td>
                <td align="center" class="first-cell"><span><?php echo $data[$v['user_id']]['user_name']; ?></span></td>
                <td align="center"><span onclick=""><?php echo $data[$v['user_id']]['mobile_phone']; ?></span></td>
                <td align="center"><span onclick=""><?php echo $v['money']; ?> </span></td>
                <td align="center"><span><?php echo $data[$v['user_id']]['one']; ?></span></td>
                <td align="center"><span><?php echo $data[$v['user_id']]['two']; ?></span></td>
                <td align="center"><span><?php echo date('Y-m-d h:i:s',$data[$v['user_id']]['reg_time']); ?></span></td>
                <!--<td align="center"><span><?php if($data[$v['user_id']]['user_rank'] == 9): ?>股东<?php elseif($data[$v['user_id']]['user_rank'] == 10): ?>会员<?php endif; ?></span></td>-->
                <td align="center"><span><?php echo $data[$v['user_id']]['user_rank']; ?></span></td>
                <td align="center"><span><?php if($data[$v['user_id']]['user_recharge'] == 1): ?>已激活<?php else: ?><font style="color:red">未激活</font><?php endif; ?></span></td>
                <td><?php if($v['money'] == ''): ?>否
                    <?php else: ?>
                    是
                    <?php endif; ?>
                </td>
                <td align="center" class="first-cell"><span><?php echo $data[$v['user_id']]['parent_name']; ?></span></td>
                <td align="center"><a href="<?php echo URL('tmvip/User/reset_login_pwd',['user_id'=>$v['user_id']]); ?>">重置登录密码</a>&nbsp;&nbsp;
                                   <a href="<?php echo URL('tmvip/User/reset_pay_pwd',['user_id'=>$v['user_id']]); ?>">重置支付密码</a>&nbsp;&nbsp;
                                   <a href="<?php echo URL('tmvip/User/edit_user_info',['user_id'=>$v['user_id']]); ?>">用户信息修改</a>
                                   <a href="<?php echo URL('tmvip/User/one_user_info',['user_id'=>$v['user_id']]); ?>">用户信息查看</a>
                                   <a href="<?php echo URL('tmvip/User/user_team',['user_id'=>$v['user_id']]); ?>">查看团队</a>
                                   <a href="<?php echo URL('tmvip/User/user_del',['user_id'=>$v['user_id']]); ?>">删除</a>
                    <?php if($v['wait'] == '0'): ?>
                    <a href="<?php echo URL('tmvip/User/reset_login_pwd',['user_id'=>$v['user_id']]); ?>" >开启</a>
                    <?php else: ?>
                    <a href="<?php echo URL('tmvip/User/user_wait',['user_id'=>$v['user_id']]); ?>" class="wait">冻结</a>
                    <?php endif; ?>
                </td>

            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <!---->
            </tbody>
        </table>
        <p><?php echo $data['info']->render(); ?></p>
    </div>
</div>
</body>
</html>