<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"/www/wwwroot/vip.guochamall.com/public/../application/partner/view/index/bonus_log.html";i:1527045302;}*/ ?>
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
    <title>分红记录</title>
    <link rel="stylesheet" href="/partner/css/commen.css">
    <script type="text/javascript" src="/partner/js/jquery.min.js"></script>
    <script src="/partner/js/layer/layer.js"></script>
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
    </style>
</head>
<body>
<div class="head">
    <a href="javascript:history.go(-1);" class="back">
        <img src="/partner/images/fh_03.png" alt="">
    </a>
    <p style="color: #000;font-size: 0.35rem;">分红记录</p>
</div>
<div class="blank"></div>
<div class="inte_bg">
    <div><span> 总共分红</span>￥<?php echo $mon; ?></div>

</div>
<h3>分红记录<span ></span></h3>
<table>


    <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
    <tr>
        <td class="fl">
            <div><?php echo $v['storename']['stores_name']; ?>分红</div>
            <div><?php echo $v['addtime']; ?></div>
        </td>
        <td class="fr">
            <div class="fu"><?php echo $v['bonus_money']; ?></div>
        </td>
    </tr>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</table>
</body>
</html>