<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"/www/wwwroot/vip.guochamall.com/public/../application/partner/view/index/index.html";i:1543573568;}*/ ?>
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
    <title>首页</title>
    <link rel="stylesheet" href="/partner/css/commen.css">
    <script type="text/javascript" src="/partner/js/jquery.min.js"></script>
    <script src="/partner/js/layer/layer.js"></script>
    <script type="text/javascript" src="/partner/js/auto-size.js"></script>
    <style>
        html, body {
            background-color: #edf0f5;
            font-size: 0.28rem;
        }
        body{
            padding-bottom:1rem;
        }
        .head{
            background-color: #fff;
        }
        .content{
            height: 2rem;
            margin-top: 0.5rem;
        }
        .content div{
            float: left;
            width: 27%;
            text-align: center;
            margin-left: 4.5%;
            background-color: #fff;
            height: 1.5rem;
            border-radius: 15px;
            font-size: 0.24rem;
            box-shadow: -0.05rem 0.05rem 0.2rem 0.03rem #d3d4d8;
        }
        .content div img{
            width: 40%;
            display: block;
            margin: 0 auto;
            margin-top: 0.2rem;
            margin-bottom: 0.2rem;
        }
        .content>div:nth-child(2)>img{
            margin-bottom: 0.07rem;
        }
        .cont{
            height: 4.2rem;
            background-color: #fff;
            width: 90%;
            margin:0 auto;
            border-radius: 15px;
            position: relative;
            padding-top: 0.3rem;
            box-sizing: border-box;
            box-shadow: -0.05rem 0.05rem 0.2rem 0.03rem #d3d4d8;
        }
        .cont>img{
            position: absolute;
            top: -0.2rem;
            left: 0.5rem;
            width: 0.8rem;
        }
        .cont>h3{
            text-align: center;
            font-weight: 300;
        }
        .cont a{
            display: block;
            height: 0.7rem;
            line-height: 0.7rem;
            width: 70%;
            margin:0 auto;
            /*background-color: #fe9d12;*/
            background: -moz-linear-gradient(left, #fe9d12 0%, #f8c51c 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fe9d12), color-stop(100%,#f8c51c));
            background: -webkit-linear-gradient(left, #fe9d12 0%,#f8c51c 100%);
            background: -o-linear-gradient(left, #fe9d12 0%,#f8c51c 100%);
            background: -ms-linear-gradient(left, #fe9d12 0%,#f8c51c 100%);
            background: linear-gradient(left right, #fe9d12 0%,#f8c51c 100%);
            border-radius: 20px;
            text-align: center;
            color: #fff;
            margin-top:0.3rem;
        }
        .lilv{
            margin-top: 0.2rem;
            height: 2rem;
            background: url("/partner/images/lcb_03.png") no-repeat center;
            background-size: auto 2rem;
            color: #fff;
            text-align: center;
            line-height: 2.3rem;
            font-size: 0.35rem;
        }
    </style>
</head>
<body>
<div class="head">
    <a href="javascript:history.go(-1);" class="back">
        <img src="/partner/images/fh_03.png" alt="">
    </a>
    <p style="color: #000;font-size: 0.35rem;">首页</p>
</div>
<div style="margin-top: 0.9rem;">
    <img src="/partner/images/index_banner.png" alt="" width="100%">
</div>
<div class="content">

        <div>

            <a href="http://www.tmvip.cn/mobile/user/login/login2"> <img src="/partner/images/mdcx_03.png" alt="">

            <!--测试期间使用-->
                  前往商城
            </a>
        </div>

    <div>
        <a href="<?php echo url('index/postal'); ?>"><img src="/partner/images/zxl_03.png" alt="" height="40">
        <!--门店总销量-->
            分红提现
        </a>
    </div>
    <div>
        <a href="<?php echo url('index/account_assets'); ?>"><img src="/partner/images/zhzb_03.png" alt="" height="40">
        账户资产</a>
    </div>
</div>
<!--<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
<!--<div class="cont">-->

        <!--<img src="/partner/images/zs_03.png" alt="">-->
        <!--<h3>股东入股</h3>-->
        <!--<div class="lilv">-->
            <!--<?php echo $vo['rec_money']; ?>-->

        <!--</div>-->
        <!--<a href="<?php echo url('recharge/confirm_buy',['id'=>$vo['recharge_id']]); ?>" id="submit">立即入股</a>-->
    <!--<input type="hidden" value="<?php echo $vo['recharge_id']; ?>">-->
<!--</div>-->
<!--<br>-->
<!--<?php endforeach; endif; else: echo "" ;endif; ?>-->

<div class="cont">

    <img src="/partner/images/zs_03.png" alt="">
    <h3>股东入股茶馆</h3>
    <div class="lilv">
        <?php echo $data[0]['rec_money']; ?>
    </div>
    <a href="<?php echo url('recharge/confirm_buy',['id'=>$data[0]['recharge_id']]); ?>" id="submit">立即入股</a>
    <input type="hidden" value="<?php echo $data[0]['recharge_id']; ?>">
</div>
<br>


<div class="cont">

    <img src="/partner/images/zs_03.png" alt="">
    <h3>股东入股门店</h3>
    <div class="lilv">
        <?php echo $data[1]['rec_money']; ?>

    </div>
    <a href="<?php echo url('recharge/confirm_buy_new',['id'=>$data[1]['recharge_id']]); ?>" id="submit1">立即入股</a>
    <input type="hidden" value="<?php echo $data[1]['recharge_id']; ?>">
</div>
<br>


<div id="footer">
    <div class="item items">
        <a href="javascript:;" class="check"><span class="footer_home"></span>首页</a>
        <a href="<?php echo url('index/willopen'); ?>" ><span class="footer_xuancha"></span>门店收益</a>
        <a href="<?php echo url('index/recode'); ?>"><span class="footer_shopcar"></span>记录</a>
        <a href="<?php echo url('index/custom_info'); ?>"><span class="footer_wode"></span>我的</a>
    </div>
</div>

</body>
</html>