<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/system/recharge_info.html";i:1524828036;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
</head>
<body>
<div class="cont_search clearfix">
    <dl class="fl" style="visibility: hidden">
        <dt>分类：</dt>
        <dd><select class="ng-untouched ng-pristine ng-valid">
            <option value="0">请选择</option>
            <!---->
            <option value="1802235318000043">
                <!---->【商品】
                <!---->小商品
            </option>
            <option value="1802235318000042">
                <!---->【商品】
                <!---->饮料/酒水
            </option>
            <option value="1802235317900041">
                <!---->【商品】
                <!---->香烟
            </option>
            <option value="1802235317800039">
                <!---->【商品】
                <!---->茶水/咖啡
            </option>
            <option value="1802235317900040">
                <!---->【商品】
                <!---->简餐
            </option>
        </select>

        </dd>
        <dt>商品名称：</dt>
        <dd>
            <stock-productlist-control>
                <div class="search_input">
                    <input maxlength="50" type="text" class="ng-untouched ng-pristine ng-valid">
                    <!---->
                </div>
            </stock-productlist-control>
        </dd>
    </dl>
    <a class="btngreen search fl" href="javascript:void(0)" style="visibility: hidden">查询</a>
    <a class="export fl" href="<?php echo URL('tmvip/System/add_recharge'); ?>">添加理茶宝规则</a>
</div>
<div class="bench white-scroll" >
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>充值金额</td>
                <td>充值等级</td>
                <td>充值返还总积分</td>
                <td>每日固定返还率(%)</td>
                <td>一级返利(%)</td>
                <td>二级返利(%)</td>
                <td>一级绩效(%)</td>
                <td>二级绩效(%)</td>
                <td>简介</td>
                <td>名称</td>
                <td>购买返送的注册积分</td>
                <td>注册激活消耗的积分</td>
                <td>礼包积分</td>
                <td width="10%">操作</td>
            </tr>

            <!---->
            <?php if(is_array($info) || $info instanceof \think\Collection || $info instanceof \think\Paginator): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tr>
                <td align="center" ><span><?php echo $vo['rec_money']; ?></span></td>
                <td align="center" ><span><?php echo $vo['rec_lev']; ?></span></td>
                <td align="center" ><span><?php echo $vo['total_inte']; ?></span></td>
                <td align="center" ><span><?php echo $vo['init_rates']; ?></span>%</td>
                <td align="center" ><span><?php echo $vo['fir_rec']; ?></span>%</td>
                <td align="center" ><span><?php echo $vo['sec_rec']; ?></span>%</td>
                <td align="center" ><span><?php echo $vo['fir_merits']; ?></span>%</td>
                <td align="center" ><span><?php echo $vo['sec_merits']; ?></span>%</td>
                <td align="center" ><span><?php echo $vo['subject']; ?></span></td>
                <td align="center" ><span><?php echo $vo['body']; ?></span></td>
                <td align="center" ><span><?php echo $vo['reg_rec']; ?></span></td>
                <td align="center" ><span><?php echo $vo['sec_reg_rec']; ?></span></td>
                <td align="center" ><span><?php echo $vo['gift']; ?></span></td>
                <td align="center">
                    <a href="<?php echo URL('tmvip/System/recharge_edit',['recharge_id'=>$vo['id']]); ?>">编辑</a>
                    <a href="<?php echo URL('tmvip/System/recharge_del','recharge_id='.$vo['id']); ?>" >删除</a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
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