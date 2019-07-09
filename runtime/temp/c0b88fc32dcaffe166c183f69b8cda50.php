<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/order/postal.html";i:1546411157;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>提现申请表</title>
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
        <a href="<?php echo URL('tmvip/Order/postal_excel'); ?>" style="float: right;margin-top:10px;">导出</a>
    </form>
</div>
<div class="bench white-scroll">
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>订单编号</td>
                <td>用户名</td>
                <td>真实姓名</td>
                <td>银行卡号</td>
                <td>开户行</td>
                <td>提现金额</td>
                <td>申请时间</td>
                <td>操作时间</td>
                <td>状态</td>
                <td>操作</td>
            </tr>

            <!---->
            <?php if(is_array($data['info']) || $data['info'] instanceof \think\Collection || $data['info'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>
            <tr>
                <td><?php echo $k; ?></td>
                <td><?php echo $data[$v['user_id']]['username']; ?></td>
                <td><?php echo $data[$v['user_id']]['real_name']; ?></td>
                <td><?php echo $data[$v['user_id']]['card']; ?></td>
                <td><?php echo $data[$v['user_id']]['bank_name']; ?></td>
                <td><?php echo $v['money_num']; ?></td>
                <td><?php echo $v['create_time']; ?></td>
                <td> <?php if($v['status'] == 0): ?>--.-- <?php else: ?><?php echo $v['update_time']; endif; ?></td>
                <td>
                    <?php if($v['status'] == 0): ?>
                    <span style="color:red;"> 申请中</span>
                    <?php elseif($v['status'] == 1): ?>
                    <span style="color:green;"> 提现已完成</span>
                    <?php elseif($v['status'] == 2): ?>
                    <span style="color:skyblue;"> 提现驳回</span>
                    <?php else: ?>
                    <span style="color:red;"> 提现失败</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($v['status'] == 0): ?>
                    <button class="tj" data-id="<?php echo $v['id']; ?>">驳回</button>
                    <button class="over" data-id="<?php echo $v['id']; ?>">完成提现</button>
                    <?php elseif($v['status'] == 1): ?>
                    <span style="color:green;"> 提现已完成</span>
                    <?php elseif($v['status'] == 2): ?>
                    <!--<span style="color:skyblue;"> 提现驳回</span>-->
                    <?php else: ?>
                    <span style="color:red;"> 提现失败</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <!---->
            </tbody>
        </table><p><?php echo $data['info']->render(); ?></p>
    </div>
    <div class="page clearfix">
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

    // 驳回提现
    $(".tj").click(function(){
        var id=$(this).attr("data-id");
        $.ajax({
            url:"<?php echo URL('tmvip/order/rejected'); ?>",
            data:{id:id},
            type:"post",
            success:function(msg){

                if(msg ==0){
                    alert('服务器错误，操作失败');
                }else{
                    alert("操作成功");
                    location.reload();
                }

            }
        });
    })
    // 完成提现
    $(".over").click(function(){
        var id=$(this).attr("data-id");
        $.ajax({
            url:"<?php echo URL('tmvip/order/over'); ?>",
            data:{id:id},
            type:"post",
            success:function(msg){
                if(msg ==0){
                    alert('服务器错误，操作失败');
                }else{
                    alert("操作成功");
                    location.reload();
                }

            }
        });
    })


</script>