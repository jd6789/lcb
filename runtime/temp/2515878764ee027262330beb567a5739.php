<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/system/teapoint.html";i:1528700192;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
</head>
<body>
<div class="cont_search clearfix">
    <!--<dl class="fl">-->
        <!--<dt>分类：</dt>-->
        <!--<dd><select class="ng-untouched ng-pristine ng-valid">-->
            <!--<option value="0">请选择</option>-->
            <!--&lt;!&ndash;&ndash;&gt;-->
            <!--<option value="1802235318000043">-->
                <!--&lt;!&ndash;&ndash;&gt;【商品】-->
                <!--&lt;!&ndash;&ndash;&gt;小商品-->
            <!--</option>-->
            <!--<option value="1802235318000042">-->
                <!--&lt;!&ndash;&ndash;&gt;【商品】-->
                <!--&lt;!&ndash;&ndash;&gt;饮料/酒水-->
            <!--</option>-->
            <!--<option value="1802235317900041">-->
                <!--&lt;!&ndash;&ndash;&gt;【商品】-->
                <!--&lt;!&ndash;&ndash;&gt;香烟-->
            <!--</option>-->
            <!--<option value="1802235317800039">-->
                <!--&lt;!&ndash;&ndash;&gt;【商品】-->
                <!--&lt;!&ndash;&ndash;&gt;茶水/咖啡-->
            <!--</option>-->
            <!--<option value="1802235317900040">-->
                <!--&lt;!&ndash;&ndash;&gt;【商品】-->
                <!--&lt;!&ndash;&ndash;&gt;简餐-->
            <!--</option>-->
        <!--</select>-->

        <!--</dd>-->
        <!--<dt>商品名称：</dt>-->
        <!--<dd>-->
            <!--<stock-productlist-control>-->
                <!--<div class="search_input">-->
                    <!--<input maxlength="50" type="text" class="ng-untouched ng-pristine ng-valid">-->
                    <!--&lt;!&ndash;&ndash;&gt;-->
                <!--</div>-->
            <!--</stock-productlist-control>-->
        <!--</dd>-->
    <!--</dl>-->
    <a class="btngreen search fl" href="" style="visibility: hidden">aa</a>
    <a class="export fl" href="<?php echo URL('tmvip/System/teapoint_edit'); ?>">修改返积分规则</a>
</div>
<div class="bench white-scroll" >
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>茶点动态比例(%)</td>
                <td>茶券动态比例(%)</td>
                <td>茶点静态比例(%)</td>
                <td>茶券静态比例(%)</td>
                <td>固定返利封顶值(%)</td>
                <td>转赠注册积分最低值</td>
                <td>发展奖利率 (%)</td>
            </tr>
            <!---->
            <tr>
                <td align="center" ><span><?php echo $data['tea_score_rate']; ?></span></td>
                <td align="center" ><span><?php echo $data['tea_inte_rate']; ?></span></td>
                <td align="center" ><span><?php echo $data['slow_tea_score_rate']; ?></span></td>
                <td align="center" ><span><?php echo $data['slow_tea_inte_rate']; ?></span></td>
                <td align="center" ><span><?php echo $data['hight_rate']; ?></span></td>
                <td align="center" ><span><?php echo $data['small_reg']; ?></span></td>
                <td align="center" ><span><?php echo $data['dev_rate']; ?></span></td>
            </tr>
            <!---->
            </tbody>
        </table>
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