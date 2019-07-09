<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:91:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/recharge/userrecharge.html";i:1543217817;}*/ ?>
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
                <td>用户名称</td>
                <td>用户电话</td>
                <td>应付金额</td>
                <td>订单金额</td>
                <td>产品名称</td>
                <td>下单时间</td>
                <td>订单号</td>
                <td>是否支付</td>
                <td>是否升级</td>
                <td>操作</td>
            </tr>

            <!---->
            <?php if(is_array($data['info']) || $data['info'] instanceof \think\Collection || $data['info'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $data[$v['user_id']]['username']; ?></td>
                <td><?php echo $data[$v['user_id']]['tel']; ?></td>
                <td><?php echo $v['recharge_money']; ?></td>
                <td><?php echo $v['rec_money']; ?></td>
                <td><?php echo $v['body']; ?></td>
                <td><?php echo date('Y-m-d H:i:s',$v['rec_addtime']); ?></td>
                <td><?php echo $v['contract_num']; ?></td>
                <td>
                    <?php if($v['pay_status'] == 1): ?>已支付
                        <?php else: ?>
                        未支付
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($v['is_againbuy'] == 1): ?>升级
                        <?php else: ?>
                        购买
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($v['pay_status'] == 1): if($v['is_active'] == 0): ?>

                         <button value="<?php echo $v['id']; ?>" class="handjihuo">手动激活</button>&nbsp;
                    <?php else: endif; else: ?>
                        <button value="<?php echo $v['recharge_num']; ?>" class="dels">超时删除</button>&nbsp;
                        <button value="<?php echo $v['id']; ?>" class="submi">已确认支付</button>
                        </select>
                    <?php endif; ?>
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
<script src="/admin/js/jquery.min.js"></script>
<script>
    $(".submi").click(function(){
        var btnn=$(this).val();
        $.ajax({
            url:"<?php echo URL('tmvip/recharge/updateRec'); ?>",
            data:{id:btnn},
            type:"post",
            success:function(msg){
                if(msg.status ==0){
                    alert('服务器错误，操作失败');
                }else{
                    alert("操作成功");
                    location.reload();
                }

            }
        });
    })
    $(".dels").click(function(){
        var btn=$(this).val();
        $.ajax({
            url:"<?php echo URL('api/api/delmanage'); ?>",
            data: {recharge_num:btn},
            type:"post",
            success:function(msg){
                if(msg.status ==1){
                    alert("删除成功");
                    location.reload();
                }else{
                    alert("删除失败");
                }
            }
        });
    })
    $(".handjihuo").click(function(){
        var btn=$(this).val();
        $.ajax({
            url:"<?php echo URL('tmvip/recharge/active_tea_treasure'); ?>",
            data: {id:btn},
            type:"post",
            success:function(msg){
                if(msg ==1){
                    alert("激活成功");
                    location.reload();
                }else{
                    alert("激活失败");
                }
            }
        });
    })
</script>