<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/user/user_all.html";i:1548041001;}*/ ?>
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
        html,body{
            width: 100%;
        }
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
        .dn{
            display: none;
        }
        input[type="text"]  {
            width: 230px;
        }
    </style>
</head>
<body>
<div class="viewsmall form_cont r10px " style="transform: translateX(-40%);width: 100%;">
    <div class="form_cap clearfix">
        <span class="fl">修改用户积分</span>
        <!--<a class="close fr" href="javascript:void(0);"></a>-->
    </div>
    <!---->
    <form method="post" action="}">
        <div class="form_table" style="width: 800px;margin: 0 auto;">
            <table border="0" cellpadding="0" cellspacing="0"  style="width: 800px">
                <tr >
                    <td>应返总积分：</td>
                    <td><?php echo $info['total_sum']; ?></td>
                    <td>已返总积分：</td>
                    <td><?php echo $info['back_inte']; ?></td>
                    <td>待返总积分：</td>
                    <td><?php echo $info['surplus_inte']; ?></td>
                </tr>
                <tr>
                    <td>已返总茶点：</td>
                    <td><?php echo $info['have_tea_ponit_inte']; ?></td>
                    <td>已返总茶券：</td>
                    <td><?php echo $info['have_tea_inte']; ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>消耗总茶点：</td>
                    <td><?php echo $info['use_tea_ponit_inte']; ?></td>
                    <td>消耗总茶券：</td>
                    <td><?php echo $info['use_tea_inte']; ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>现有总茶点：</td>
                    <td><?php echo $info['tea_ponit_inte']; ?></td>
                    <td>现有总茶券：</td>
                    <td><?php echo $info['tea_inte']; ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>待返总茶点：</td>
                    <td><?php echo $info['will_tae_ponit']; ?></td>
                    <td>待返总茶券：</td>
                    <td><?php echo $info['will_tae_inte']; ?></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </form>

</div>
</div>
</body>
</html>