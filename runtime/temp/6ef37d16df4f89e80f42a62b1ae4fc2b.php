<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/order/order.html";i:1546422862;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>所有寄售商品</title>
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
        td .txt {
            border: 1px solid #f7de9c;
            background: #fff9d6;
            padding: 5px 15px;
            font-size: 14px;
            color: #d98d41;
            line-height: 24px;
            position: absolute;
            z-index: 1;
            box-shadow: 0 0 5px rgba(179,89,0,.2);
            font-weight: 400;
            text-align: left;
        }
        .globaltable table td{
            padding: 0;
        }
        .globaltable>table>tbody>tr>td>table>tbody>tr>td:first-child{
            border-left: none;
        }
        .globaltable>table>tbody>tr>td>table>tbody>tr:first-child>td{
            border-top: none;
        }
        .globaltable>table>tbody>tr>td>table>tbody>tr:last-child>td{
            border-bottom:  none;
        }
        .th{
            width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .globaltable table tbody:first-child tr:first-child td {
            background: #fff;
            color: #666;
        }
        .globaltable table tbody:first-child tr:hover td {
            background: #fff;
            color: #666;
        }
    </style>
</head>
<body>
<div class="cont_search clearfix">
    <form action=""  method="post">
        <!-- 关键字 -->
        用户名<input type="text" name="username" size="15" />
        <input type="submit" value="查询" class="btngreen search ">
        <a href="<?php echo URL('tmvip/order/order_excel'); ?>" style="float: right;margin-top: 10px;">导出Excel</a>
    </form>
</div>
<div class="bench white-scroll">
    <div class="globaltable r5px" style="overflow-x: auto;">
        <table border="0" cellpadding="0" cellspacing="0" style="width: 1660px">
            <tbody>
            <tr>
                <td width="150">订单编号</td>
                <td width="150">用户名</td>
                <td width="160">银行卡号</td>
                <td width="150">商品名称</td>
                <td width="150">商品金额</td>
                <td width="150">商品数量</td>
                <td width="150">商品总价</td>
                <td width="150">支付方式</td>
                <td width="150">寄售时间</td>
                <td width="150">是否寄售</td>
                <td width="150">操作</td>
            </tr>

            <!---->
            <?php if(is_array($data['info']) || $data['info'] instanceof \think\Collection || $data['info'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>
            <tr>
                <td><?php echo $v['order_id']; ?></td>
                <td><?php echo $data[$v['order_id']]['username']; ?></td>
                <td style="position: relative;" class="xianshi"><?php echo $data[$v['order_id']]['bank_card']; ?>
                    <div class="txt r3px hide tst2s" style="top: -25px;left: 25px;">
                        <p>姓名：<?php echo $data[$v['order_id']]['real_name']; ?> 　银行名称：<?php echo $data[$v['order_id']]['bank_name']; ?></p>
                    </div>
                </td>

                <td colspan="6">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:1200px;">
                        <?php foreach($data[$v['order_id']]['goods'] as $vo): ?>
                        <tr>
                            <td  class="th" width="150"><div style="max-width: 150px;overflow:hidden;text-overflow: ellipsis;white-space: nowrap"><?php echo $vo['goods_name']; ?></div></td>
                            <td style="width: 150px;overflow:hidden;text-overflow: ellipsis;white-space: nowrap"><?php echo $vo['goods_price']; ?></td>
                            <td style="width: 150px;overflow:hidden;text-overflow: ellipsis;white-space: nowrap"><span style="color:red;"><?php echo $vo['good_number']; ?></span> &nbsp;件</td>
                            <td style="width: 150px;overflow:hidden;text-overflow: ellipsis;white-space: nowrap"><?php echo $vo['goods_price'] * $vo['good_number']; ?></td>

                            <td style="width: 150px;overflow:hidden;text-overflow: ellipsis;white-space: nowrap"><?php if($vo['pay_way'] == 1): ?>
                                茶点
                                <?php else: ?>
                                茶券
                                <?php endif; ?></td>
                            <td style="width: 150px;overflow:hidden;text-overflow: ellipsis;white-space: nowrap"><?php echo $vo['times']; ?></td>
                            <!--<td style="width: 150px;overflow:hidden;text-overflow: ellipsis;white-space: nowrap">-->
                                <!--<?php if($vo['is_third'] == 1): ?>  <span style="color:red;"> 已寄售</span>-->
                                <!--<?php else: ?>-->
                                <!--未寄售-->
                                <!--<?php endif; ?>-->
                            <!--</td>-->
                            <!--<td style="width: 150px;overflow:hidden;text-overflow: ellipsis;white-space: nowrap">-->
                                <!--<?php if($vo['is_third'] == 1): ?>-->
                                <!--<?php if($vo['over_status'] == 1): ?>-->
                                <!--<span style="color:red;"> 已完成统计</span>-->
                                <!--<?php else: ?>-->
                                <!--<button class="tj" data-id="<?php echo $vo['cart_id']; ?>">完成统计</button>-->
                                <!--<?php endif; ?>-->
                                <!--<?php endif; ?>-->
                            <!--</td>-->
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </td>
                <td style="width: 150px;overflow:hidden;text-overflow: ellipsis;white-space: nowrap">
                    <?php if($data[$v['order_id']]['goods'][0]['is_third'] == 1): ?>  <span style="color:red;"> 已寄售</span>
                    <?php else: ?>
                    未寄售
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($data[$v['order_id']]['goods'][0]['is_third'] == 1): if($data[$v['order_id']]['goods'][0]['over_status'] == 1): ?>
                    <span style="color:red;"> 已完成统计</span>
                    <?php else: ?>
                    <button class="tj" data-id="<?php echo $v['order_id']; ?>">完成统计</button>
                    <?php endif; endif; ?>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <!---->
            </tbody>
        </table><p><?php echo $data['info']->render(); ?></p>
    </div>
    <div class="page clearfix">
        <!--<div class="linklist">-->
        <!--<a class="prev" href="javascript:void(0)">&nbsp;</a>-->
        <!--<a href="javascript:void(0)" class="current">1</a>-->
        <!--<a class="next" href="javascript:void(0)">&nbsp;</a>-->
        <!--</div>-->
    </div>
</div>
</body>
</html>
<script src="/admin/js/jquery.min.js"></script>
<script>
    $(".tj").click(function(){
        var id=$(this).attr("data-id")
        $.ajax({
            url:"<?php echo URL('tmvip/order/overorder'); ?>",
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
    $(".xianshi").mouseover(function () {
        $(this).find(".txt").show()
    }).mouseout(function () {
        $(this).find(".txt").hide()
    })
</script>