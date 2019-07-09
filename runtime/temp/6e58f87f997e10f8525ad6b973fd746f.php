<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:92:"/www/wwwroot/vip.guochamall.com/public/../application/partner/view/index/account_assets.html";i:1528113540;}*/ ?>
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
    <title>账户资产</title>
    <link rel="stylesheet" href="/partner/css/commen.css">
    <script type="text/javascript" src="/partner/js/jquery.min.js"></script>
    <script src="/partner/js/layer/layer.js"></script>
    <script type="text/javascript" src="/partner/js/auto-size.js"></script>
    <style>
        html, body {
            background-color: #edf0f5;
            font-size: 0.28rem;
            height: 100%;
        }

        .head {
            background-color: #fff;
        }
        #container{
            width: 60%;
            height: 3.5rem;
            margin:0 auto;
        }
        .content{
            padding: 0.15rem 0;
            margin-top: 0.916rem;
            background-color: #fff;
        }
        .money{
            background-color: #fff;
            padding:0 0.2rem;
        }
        .money span{
            color: #ff9b12;
            margin-left: 0.15rem;
        }
        .money button{
            background-color: #ff9b12;
            color: #fff;
            padding:0.12rem 0.42rem;
            border-radius: 0.25rem;
            vertical-align: middle;
        }
        .info{
            background-color: #fff;
        }
        .info .fl{
            height: 1.5rem;
            width: 33.3%;
        }
        .info .fl div{
            text-align: center;
        }
        .info .fl div:first-of-type{
            margin-top: 0.4rem;
            font-size: 0.35rem;
            color: #ff9b12;
        }
        .info .fl div i{
            width: 0.15rem;
            height: 0.15rem;
            background-color: #ff9b12;
            display: inline-block;
            vertical-align: middle;
            margin-right: 0.15rem;
        }
        .info .fl:first-of-type div i{
            background-color: #fd5726;
        }
        .info .fl:nth-child(3) div i{
            background-color: #0000ff;
        }
        table{
            width: 100%;
            border-collapse:collapse;
            background-color: #fff;
            color: #3a3a3a;
        }
        table tbody tr{
            border-bottom:1px solid #d9d9d9;
            height: 1rem;
        }
        table td{
            padding-left: 0.2rem;
            font-size:0.3rem;

        }
        tr td:last-of-type{
            text-align: center;
        }
table img{
    width: 0.4rem;
    vertical-align: middle;
    margin-right: 0.15rem;
}

    </style>
</head>
<body>
<div class="head">
    <a href="javascript:history.go(-1);" class="back">
        <img src="/partner/images/fh_03.png" alt="">
    </a>
    <p style="color: #000;font-size: 0.35rem;">账户资产</p>
</div>
<div class="content">
    <div id="container"></div>
</div>
<!--<div class="money">-->
    <!--<div class="fl">可用余额(元)<span>6000</span></div>-->
    <!--<div class="fr">-->
        <!--<button>转入</button>-->
    <!--</div>-->
    <!--<div class="clear"></div>-->
<!--</div>-->
<div class="info">
    <div class="fl">
        <div>
            <?php echo $new; ?>%
        </div>
        <div>
            <i></i>最新收益
        </div>
    </div>
    <div class="fl">
        <div>
            <?php echo $all; ?>%
        </div>
        <div>
            <i></i>累计收益
        </div>
    </div>
    <div class="fl">
        <div>
            <?php echo $tea_inte; ?>%
        </div>
        <div>
            <i></i>积分
        </div>
    </div>
    <div class="clear"></div>
</div>
<table>
    <tr>
        <td>
            <img src="/partner/images/zxsy_03.png" alt="">最新分红
        </td>
        <td ><span id="zxfh"><?php echo $data['last_inte']; ?></span>元
        </td>
    </tr>
    <tr>
        <td>
            <img src="/partner/images/ljsy_03.png" alt="">累积分红
        </td>
        <td ><span id="ljfh"><?php echo $data['fenhong']; ?></span>元
        </td>
    </tr>
    <!--<tr>-->
        <!--<td>-->
            <!--<img src="/partner/images/wdjf_03.png" alt="">可用钱包余额-->
        <!--</td>-->
        <!--<td>-->
            <!--95555元-->
        <!--</td>-->
    <!--</tr>-->
    <tr>
        <td>
            <img src="/partner/images/wdjf_03.png" alt="">可用消费总额度
        </td>
        <td >
            <span id="kyxf"><?php echo $data['user_info']; ?></span>
        </td>
    </tr>
    <tr>
        <td>
            <img src="/partner/images/wdjf_03.png" alt="">可用茶点
        </td>
        <td >
            <span id="kyxfs"><?php echo $user['tea_ponit_inte']; ?></span>
        </td>
    </tr>
    <tr>
        <td>
            <img src="/partner/images/wdjf_03.png" alt="">可用茶券
        </td>
        <td >
            <span id="kyxfa"><?php echo $user['tea_inte']; ?></span>
        </td>
    </tr>
    <tr>
        <td>
            <a href="<?php echo url('index/bonus_log'); ?>"><img src="/partner/images/wdjf_03.png" alt="">分红记录</a>
        </td>
        <td></td>
    </tr>
</table>
<script src="/partner/js/echarts.min.js"></script>
<script type="text/javascript">
    var dom = document.getElementById("container");
    var myChart = echarts.init(dom);
    var zxfh=$("#zxfh").text()
    var ljfh=$("#ljfh").text()
    var kyxf=$("#kyxf").text()
    var all=(add(add(zxfh,ljfh),kyxf)).toFixed(2)
    var app = {};
    option = null;
    app.title = '环形图';

    option = {
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        graphic:{
            type:'text',
            left:'center',
            top:'center',
            style:{
                text:'总资产（元）\n'+all,
                textAlign:'center',
                fill:'#000',
                width:30,
                height:30
            }
        },
        series: [
            {
                name:'账户下资产',
                type:'pie',
                radius: ['50%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    normal: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        show: true,
                        textStyle: {
                            fontSize: '30',
                            fontWeight: 'bold'
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data:[
                    {value:zxfh, name:'最新分红'},
                    {value:ljfh, name:'累积分红'},
                    {value:kyxf, name:'可用消费额度'}

                ]
            }
        ]
    };
    ;
    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }
    //浮点数加减
    function add(a, b) {
        var c, d, e;
        try {
            c = a.toString().split(".")[1].length;
        } catch (f) {
            c = 0;
        }
        try {
            d = b.toString().split(".")[1].length;
        } catch (f) {
            d = 0;
        }
        return e = Math.pow(10, Math.max(c, d)), (mul(a, e) + mul(b, e)) / e;
    }

    function sub(a, b) {
        var c, d, e;
        try {
            c = a.toString().split(".")[1].length;
        } catch (f) {
            c = 0;
        }
        try {
            d = b.toString().split(".")[1].length;
        } catch (f) {
            d = 0;
        }
        return e = Math.pow(10, Math.max(c, d)), (mul(a, e) - mul(b, e)) / e;
    }

    function div(a, b) {
        var c, d, e = 0,
            f = 0;
        try {
            e = a.toString().split(".")[1].length;
        } catch (g) {
        }
        try {
            f = b.toString().split(".")[1].length;
        } catch (g) {
        }
        return c = Number(a.toString().replace(".", "")), d = Number(b.toString().replace(".", "")), mul(c / d, Math.pow(10, f - e));
    }

    function mul(a, b) {
        var c = 0,
            d = a.toString(),
            e = b.toString();
        try {
            c += d.split(".")[1].length;
        } catch (f) {
        }
        try {
            c += e.split(".")[1].length;
        } catch (f) {
        }
        return Number(d.replace(".", "")) * Number(e.replace(".", "")) / Math.pow(10, c);
    }
</script>
</body>
</html>