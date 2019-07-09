<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:93:"/www/wwwroot/vip.guochamall.com/public/../application/partner/view/shareholder/inte_info.html";i:1543217580;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="description" content="淘米科技"/>
    <meta name="keywords" content="淘米科技"/>
    <title>入股记录</title>
    <link rel="stylesheet" href="/partner/css/commen.css">
    <!--<script type="text/javascript" src="/partner/js/jquery.min.js"></script>-->
    <!--<script src="/partner/js/layer/layer.js"></script>-->
    <script type="text/javascript" src="/partner/js/auto-size.js"></script>
    <style>
        html, body {
            background-color: #fff;
            font-size: 0.28rem;
        }

        .head {
            background-color: #fff;
        }
        .inte_bg{
            height: 2.5rem;
            background: url("/partner/images/jfmx-bg_02.png") no-repeat;
            background-size: auto 2.5rem;
            padding-top: 1rem;
            box-sizing: border-box;
        }
        .inte_bg>div{
            color: #fff;
            text-align: center;
            float: left;
        }
        .inte_bg>div:first-of-type{
            margin-left: 2.2rem;
            margin-right: 0.25rem;
            font-size: 0.35rem;
        }
        .inte_bg>div:last-of-type{
            font-size: 0.35rem;
        }
        .inte_bg span{
            display: block;
            text-align: center;
        }
        .blank{
            height: 0.9rem;
        }
        h3{
            height: 0.7rem;
            line-height: 0.7rem;
            border-bottom:1px solid #d9d9d9;
            padding-left: 0.2rem;
            color: #343434;
            font-weight:400;
            font-size:0.3rem;
        }
        table{
            width: 100%;

            border-collapse:collapse;
        }
        table tr{
            height: 0.9rem;
            border-bottom:1px solid #d9d9d9;
        }
        td.fl{
            padding-left: 0.2rem;
        }
        td.fl div:first-of-type{
            font-size:0.28rem;
            color: #4b4b4b;
            margin-top: 0.1rem;
        }
        td.fl div:last-of-type{
            color: #ababab;
            font-size: 0.22rem;
        }
        td.fr div{
            margin-top: 0.25rem;
            margin-right: 0.3rem;
        }
        td.fr div.fu{
            color: red;
        }
        td.fr div.zheng{
            color: #ff9b12;
        }
        /*分页样式*/
        .pagination{text-align:center;margin-top:20px;margin-bottom: 20px;}
        .pagination li{margin:0px 10px; border:1px solid #e6e6e6;padding: 3px 8px;display: inline-block;}
        .pagination .active{background-color: #dd1a20;color: #fff;}
        .pagination .disabled{color:#aaa;}
       #jh{
            padding: 10px 20px;
            outline: none;
            border: none;
            color: #fff;
           background:#fdac23;
           border-radius:15px
        }
    </style>
</head>
<body>
<div class="head">
    <a href="<?php echo url('index/custom_info'); ?>" class="back">
        <img src="/partner/images/fh_03.png" alt="">
    </a>
    <p style="color: #000;font-size: 0.35rem;">入股记录</p>
</div>
<div class="blank"></div>
<div class="inte_bg">
    <div><span>可用积分</span><?php echo $user['tea_ponit_inte']+$user['tea_inte']; ?></div>
    <div><span>可用余额</span><?php echo $user['wallet']; ?></div>
</div>
<h3>消费明细  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <!--<span ><a  href="<?php echo url('money/recharge'); ?>" style="color: #cc8014">充值钱包</a></span>-->
</h3>
<table>


    <?php if(is_array($data['money']) || $data['money'] instanceof \think\Collection || $data['money'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['money'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
    <tr>
        <td class="fl">
            <div>钱包充值<?php echo $v['montys']; ?></div>
            <div><?php echo date('Y-m-d H:i:s',$v['money_addtime'] ); ?></div>
        </td>
        <td class="fr">
            <div class="fu"><?php if($v['pay_status'] == '0'): ?>
                <a href="<?php echo url('money/confirm',['id'=>$v['money_id']]); ?>" style="color: #1E9FFF">继续支付</a>
                <a href="<?php echo url('money/delMoney',['id'=>$v['money_id']]); ?>" style="color: #ac2925">取消订单</a>
                <?php else: ?>
                订单完成
                <?php endif; ?></div>
        </td>
    </tr>
    <?php endforeach; endif; else: echo "" ;endif; if(is_array($data['recharge']) || $data['recharge'] instanceof \think\Collection || $data['recharge'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['recharge'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$o): $mod = ($i % 2 );++$i;?>
    <tr>
        <td class="fl">
            <div>
                门店股份:<?php echo $o['recharge_money']; ?>
            </div>
            <div><span style="font-size: 12px"><?php echo date('Y-m-d H:i:s',$o['rec_addtime'] ); ?></span></div>
            <div class="fl">
                合同编号:<?php echo $o['contract_num']; ?>
            </div>
        </td>
        <td class="fr">
            <div class="fu">
                <?php if($o['pay_status'] == '0'): ?>
                <a href="<?php echo url('recharge/confirm',['id'=>$o['id']]); ?>" style="color: #1E9FFF">继续支付</a>
                <a href="<?php echo url('recharge/delrecharge',['id'=>$o['id']]); ?>" style="color: #ac2925">取消订单</a>
                <?php else: if($o['is_active'] == 0): ?>
                确认中
                <!--<button id="jh" data-id="<?php echo $o['id']; ?>">激活</button>-->
                <?php else: ?>
                订单完成
                <?php endif; endif; ?></div>
        </td>
    </tr>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</table>
</body>
<script>
    // $(function(){
    //     $('#jh').click(function () {
    //         var id=$(this).attr('data-id');
    //         $.ajax({
    //             url:"<?php echo URL('tmvip/recharge/active_tea_treasure'); ?>",
    //             data: {id:id},
    //             type:"post",
    //             success:function(msg){
    //                 if(msg ==1){
    //                     alert("激活成功");
    //                     location.reload();
    //                 }else{
    //                     alert("激活失败");
    //                 }
    //             }
    //         });
    //     })
    //     $('#jh').parent().css("margin-top","0.1rem")
    // })
</script>
</html>