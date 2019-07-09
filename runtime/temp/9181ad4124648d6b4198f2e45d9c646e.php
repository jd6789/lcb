<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/user/order.html";i:1536384373;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="description" content="国茶商城"/>
    <meta name="keywords" content="国茶商城"/>
    <title>待发货</title>
    <link rel="stylesheet" href="/partner/css/style.css">
    <style>
        body, button, dd, dl, dt, fieldset, form, h1, h2, h3, h4, h5, h6, input, legend, li, ol, p, select, table, td, textarea, th, ul {
            margin: 0;
            padding: 0;
            font-family: '微软雅黑';
            overflow-x: hidden;
            font-size: 0.8rem;
        }
        *{
            margin: 0;
            padding: 0;
        }
        a {
            text-decoration: none;
            color: #333;
        }
        a:focus,a:hover{
            text-decoration: none;
            color: #333;
        }
        button{
            border: none;
            outline: none;
        }
        select:focus{
            outline:none !important;
        }
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px #262333 inset !important;//关于解决输入框背景颜色
        -webkit-text-fill-color: rgba(255,255,255,1)!important;//关于接输入框文字颜色
        -webkit-tap-highlight-color:rgba(0,0,0,0);
        }
        input{
            border-radius: 0;
        }
        input[type=button], input[type=submit], input[type=file], button { cursor: pointer; -webkit-appearance: none; }
        ul {
            list-style: none;
        }
        html, body {
            height: 100%;
        }
        .head {
            width: 100%;
            height: 2.5rem;
            background: #639a67;
            position: fixed;
            z-index: 999;
            top: 0;
        }
        .head > .back {
            display: block;
            width: 1.5rem;
            height: 2.5rem;
            line-height: 2.5rem;
            float: left;
            font-size: 0.8rem;
            color: #fff;
            margin-left: 0.5rem;
        }
        .head > .back > img {
            vertical-align: middle;
            width: 0.5rem;
            margin-top: 0.15rem;
        }
        .head > p {
            float: left;
            margin-left: 1.12rem;
            font-size: 0.28rem;
            color: #fff;
            line-height: 0.9rem;
        }
        .head .home {
            width: 0.65rem;
            height: 0.9rem;
            line-height: 0.7rem;
            float: right;
            color: #fff;
            font-size: 0.45rem;
        }
        .head .home > img {
            display: block;
            width: 0.5rem;
            margin-top: 0.15rem;
        }

        #blank {
            position: fixed;
            z-index: 19891014;
            height: 100%;
            width: 100%;
            display: none;
            top: 0.9rem;
            left: 0;
            background-color: #f5f5f5;
        }
        #blank >div{
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50% ,-50%);
            text-align: center;
            font-size: 0.28rem;
        }
        #blank >div>img,#blank >div>span,#blank >div>button{
            display: inline-block;
            text-align: center;
        }
        #blank >div>img{
            width: 1.8rem;
        }
        #blank >div>span{
            font-size: 0.28rem;
            color: #ccc;
        }
        #blank >div>a{
            margin-top: 0.18rem;
            padding:0.18rem 0.3rem;
            border: none;
            background-color: #e90327;
            color: #f5f5f5;
            display: block;
        }
        .order dl ul li {
            margin-top: 6%;
            float: left;
            width: 66%;
            margin-left: 4%;
        }
    </style>
</head>
<body>

<div class="head" style="display: none;">
    <a href="javascript:history.go(-1);" class="back">
        ＜返回
    </a>
</div>
<div class="contaniner" style="font-size: 0.22rem" id="goods">

    <section class="order" id="order">
        <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "暂时没有数据" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
        <dl>
            <dt>
                <div style="color: #999;margin-bottom: 2px;">订单号：<?php echo $v['order_num']; ?></div>
                <time><?php echo $v['times']; ?></time>
                <span><?php if($v['over_status'] == 0): if($v['is_third'] == 0): if($v['pay_way'] == 2): ?><a href="javascript:;" style="color:red" class="jishou" data-id="<?php echo $v['cart_id']; ?>">寄售</a><?php endif; else: ?>寄售中<?php endif; else: ?>已售出<?php endif; ?></span>
            </dt>
            <ul>
                <a href="javascript:;">
                    <figure><img src="https://tmvip.oss-cn-hangzhou.aliyuncs.com/<?php echo $v['goods_thumb']; ?>" /></figure>
                    <!--<figure><img src="https://tmvip.oss-cn-hangzhou.aliyuncs.com/images/201806/thumb_img/909_thumb_G_1527896134322.jpg" /></figure>-->
                    <li>
                        <p><?php echo $v['goods_name']; ?></p>
                        <b><?php echo $v['goods_price']; ?></b>
                        <strong>×<?php echo $v['good_number']; ?></strong>
                    </li>
                </a>
            </ul>
            <dd>
                <h3>商品总额</h3>
                <i>￥<?php echo $v['good_number']*$v['goods_price']; ?></i>
            </dd>
        </dl>
        <?php endforeach; endif; else: echo "暂时没有数据" ;endif; ?>
    </section>
</div>
<script type="text/javascript" src="/partner/js/jquery.min.js"></script>
<script src="/partner/js/layer/layer.js"></script>
<script>
    $(function () {

        $("#order").on("click","a.jishou",function () {
            var order_num=$(this).attr("data-id")
            $.ajax({
                type: "post",
                url:"<?php echo url('api/api/thirdSale'); ?>",
                data: {order_num: order_num},
                success: function (msg) {
                    if (msg == 0) {
                        layer.msg('寄售失败')
                    } else if (msg == 1) {
                        layer.msg('寄售成功');
                        setTimeout(function () {
                            location.reload();
                        }, 3000)

                    } else if (msg == 9) {
                        layer.msg('超过24小时的商品不允许寄售');
                    }else if (msg == 2) {
                        layer.msg('非茶券购买商品无法寄售');
                    }
                }
            })

        })

    })

</script>
</body>
</html>