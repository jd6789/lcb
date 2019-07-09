<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/user/user_manage.html";i:1529643231;}*/ ?>
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
    <form action="" method="post">
        用户名搜索：<input type="text" name="user" value="" style="border:1px solid #ccc ">
        手机号搜索：<input type="text" name="tel" value="" style="border:1px solid #ccc ">
        会员状态：<select name="status" id="c">
        <option value="0">全部</option>
        <option value="1">未激活</option>
        <option value="2">激活</option>
        <option value="3">冻结</option>
        <option value="4">出局</option>
    </select>
        会员等级：<select name="rec_lev" id="v">
        <option value="0">等级</option>
        <option value="1">一星</option>
        <option value="2">二星</option>
        <option value="3">三星</option>
        <option value="4">四星</option>
        <option value="5">五星</option>
    </select>
        茶籽大于等于：<input type="text" name="chazi" style="width:60px;height:20px;line-height:20px;border: 1px solid #ccc">
        茶券大于等于：<input type="text" name="chajuan" style="width:60px;height:20px;line-height:20px;border: 1px solid #ccc">
        茶点大于等于：<input type="text" name="chadian" style="width:60px;height:20px;line-height:20px;border: 1px solid #ccc">
        剩余额度大于等于：<input type="text" name="e_du" style="width:60px;height:20px;line-height:20px;border: 1px solid #ccc">
        <input type="submit" value="查询" class="btngreen search ">
    </form>
</div>
<div class="bench white-scroll" >
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>会员账号</td>
                <td>等级额度(星级)</td>
                <td>茶券</td>
                <td>茶点</td>
                <td>茶籽</td>
                <td>状态</td>
            </tr>

            <!---->
            <?php if(is_array($data['info']) || $data['info'] instanceof \think\Collection || $data['info'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $data[$v['userid']]['username']; ?></td>
                <td><?php echo $v['total_sum']; ?>/<?php echo $v['surplus_inte']; ?>(<?php if($v['rec_lev'] == ''): ?>0星<?php else: ?><?php echo $v['rec_lev']; ?>星<?php endif; ?>)</td>
                <td><?php echo $v['tea_inte']; ?></td>
                <td><?php echo $v['tea_ponit_inte']; ?></td>
                <td><?php echo $v['reg_inte']; ?></td>
                <td><?php if($v['wait'] == '0'): ?>冻结
                    <?php else: if($v['total_sum'] == ''): ?>未激活
                          <?php else: if($v['is_return'] == '0'): ?>出局
                          <?php else: ?>正常
                            <?php endif; endif; endif; ?>
                </td>
                <td>
                    <!--<a href="<?php echo URL('tmvip/User/user_team',['user_id'=>$v['user_id']]); ?>">查看团队</a>-->
                    <input  value="<?php echo $data[$v['userid']]['username']; ?>" hidden>
                </td>
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