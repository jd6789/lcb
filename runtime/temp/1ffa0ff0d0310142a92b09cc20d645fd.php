<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:92:"/www/wwwroot/vip.guochamall.com/public/../application/partner/view/recharge/confirm_buy.html";i:1543217637;}*/ ?>
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
    <title>确认购买</title>
    <link rel="stylesheet" href="/partner/css/commen.css">
    <script type="text/javascript" src="/partner/js/jquery.min.js"></script>
    <script src="/partner/js/layer/layer.js"></script>
    <script type="text/javascript" src="/partner/js/auto-size.js"></script>
    <style>
        html, body {
            background-color:  #edf0f5;
            font-size: 0.28rem;
        }

        .head {
            background-color: #fff;
        }
        .blank{
            height: 0.9rem;
        }
        .content{
            margin-top: 0.2rem;
            background-color: #fff;
        }
        .price{
            height: 1.5rem;
            line-height: 1.5rem;
            font-size: 0.35rem;
            padding-left: 0.2rem;
            border-bottom:1px solid #f5f5f5;
        }
        .price input{
            height: 0.6rem;
            width: 40%;
            margin-left: 0.3rem;
            font-size: 0.35rem;

        }
        :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
            color: #ccc; opacity:1;
        }

        ::-moz-placeholder { /* Mozilla Firefox 19+ */
            color: #ccc;opacity:1;
        }

        input:-ms-input-placeholder{
            color: #ccc;opacity:1;
        }

        input::-webkit-input-placeholder{
            color: #ccc;opacity:1;
        }
        body>button{
            background: -moz-linear-gradient(left, #fe9d12 0%, #f8c51c 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fe9d12), color-stop(100%,#f8c51c));
            background: -webkit-linear-gradient(left, #fe9d12 0%,#f8c51c 100%);
            background: -o-linear-gradient(left, #fe9d12 0%,#f8c51c 100%);
            background: -ms-linear-gradient(left, #fe9d12 0%,#f8c51c 100%);
            background: linear-gradient(left right, #fe9d12 0%,#f8c51c 100%);
            color: #fff;
            width: 80%;
            height: 0.8rem;
            display: block;
            margin: 1.3rem auto;
            border-radius: 0.35rem ;
        }
        .agreement{
            height: 1rem;
            line-height: 1rem;
            font-size: 0.24rem;
            padding-left: 0.25rem;
        }
        .agreement span{
            display: inline-block;
            width: 0.3rem;
            height: 0.3rem;
            background: url("/partner/images/wxz_03.png") no-repeat;
            background-size: 0.3rem 0.3rem;
            vertical-align: middle;
            margin-right: 0.1rem;
        }
        .agreement span.curr{
            background: url("/partner/images/ty_03.png") no-repeat;
            background-size: 0.3rem 0.3rem;
        }
        .agreement a{
            color: #ff9b12;
        }
        .mendian{
            background:#fff;
            line-height:3;
            padding:0 10px;
        }
        .right{
            float:right
        }
        .right span{
            margin:0 15px
        }
        .mendian .rjt{
            font-size:22px;
            color:#ccc;
        }
        .right1{
            float:right
        }
        .right1 span{
            margin:0 15px
        }
    </style>
</head>
<body>
<div class="head">
    <a href="<?php echo url('index/index'); ?>" class="back">
        <img src="/partner/images/fh_03.png" alt="">
    </a>
    <p style="color: #000;font-size: 0.35rem;">确认购买</p>
</div>
<div class="blank"></div>
<div class="content">
    <div class="price">
        ￥<input type="number" placeholder="100000元起购" value="<?php echo $data['rec_money']; ?>" readonly id="price">
        <span class="fr" style="margin-right: 0.2rem;color: #ccc;font-size: 0.28rem;">限额说明</span>
        <input type="hidden" value="<?php echo $data['recharge_id']; ?>" id="id">
    </div>
    <!--<div class="agreement">
        <!--<span class="curr"></span>
        <!--我已同意并阅读 <a href="">《理茶宝产品协议》</a>
        <a href="">选择门店</a>
    </div>-->
    <div class="mendian">
        <ul>
            <!--<li style="list-style-type:none; border-bottom:1px solid #e9ecf1">选择门店</li>-->
            <li  style="list-style-type:none; border-bottom:1px solid #e9ecf1">
                <a href="<?php echo url('recharge/storeindex'); ?>" >入股门店<span class="right" style="font-weight:bold;font-size: 15px; "><?php echo $store['stores_name']; ?><span>></span></span></a>
            </li>
            <!--<li><a style="color:#ccc" href="javascript:;">排队预约门店<span class="right1">暂未开张门店<span>></span></span></a></li>-->
        </ul>
        <input type="hidden" name="store_id" class="store_id" value="<?php echo $store['id']; ?>">
    </div>

</div>
<button id="buy" style="font-size: 20px">确认购买</button>
<script>
    $(function () {
        $(".agreement span").click(function () {
            if($(this).hasClass("curr")){
                $("button").css("background","#ccc")
                $("button").attr("disable",true)
            }else{
                $("button").css("background","#ff9b12")
                $("button").removeAttr("disable")
            }
            $(this).toggleClass("curr")

        })
        $("#buy").click(function () {
            var id=$("#id").val();
            var store=$(".store_id").val();
            $.ajax({
                url:"<?php echo url('recharge/buyManage'); ?>",
                data:{recharge_id:id,store_id:store},
                type:"post",
                success:function (msg) {
                    if (msg == 0) {
                        layer.msg("请在股东权益积分返还完再购买")
                        return false
                    }
                    if (msg == 1) {
                        layer.msg("当前购买的产品金钱数小于上次购买的钱数")
                        return false
                    }
                    if (msg == 3) {
                        layer.msg("信息不完整，前往填写");
                        setTimeout(function () {
                            location.href = "<?php echo url('shareholder/real_name'); ?>"
                        }, 2000)

                    }
                    if (msg == 4) {
                        layer.msg("您有尚未支付的股东权益，请支付或者取消")
                        return false

                    }
                    if (msg == 5) {
                        layer.msg("下单购买失败")
                        return false
                    }
                    if (msg == 8) {
                        layer.msg("您的上级未购买，所以您也无法购买")
                        return false
                    }
                    if (msg == 9) {
                        layer.msg("请选择门店")
                        return false
                    }
                    if (msg == 6) {
                        layer.msg("一个门店不能超过两份股权")
                        return false

                    }
                    if (msg.status == 1) {
                        layer.msg("下单成功")
                        setTimeout(function () {
                            layer.confirm('此编号可以在入股记录中查看', {
                                btn: ['确定'],
                                title: "合同编号:"+msg.contract_num,
                                skin : "my-skin"
                            }, function(){
                                setTimeout(function () {
                                    location.href = "<?php echo url('recharge/confirm'); ?>?id=" + msg.id;
                                },200)
                            });
                        },1500)
                    }
                }
            })
        })
    })
</script>
</body>
</html>