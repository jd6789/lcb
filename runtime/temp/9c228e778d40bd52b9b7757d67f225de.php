<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/system/recharge_edit.html";i:1523864990;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
    <style>
        .viewsmall.in {
            min-height: 70%;
            width: 60%;
            top: 2%;
        }
    </style>
</head>
<body>
<div class="viewsmall form_cont r10px in" style="transform: translateX(-50%);">
    <div class="form_cap clearfix">
        <span class="fl">修改理茶宝规则</span>
        <!--<a class="close fr" href="javascript:void(0);"></a>-->
    </div>
    <div class="form_detail">
        <!---->
        <form method="post" action="<?php echo URL('tmvip/System/save_recharge_edit'); ?>">
        <div class="form_table" style="width: 325px;margin: 0 auto;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td>充值金额：</td>
                    <td><input autofocus="true" placeholder="" type="text"
                               class="ng-untouched ng-pristine ng-invalid" name="rec_money" value="<?php echo $data['rec_money']; ?>">
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"></td>
                </tr>
                <tr>
                    <td>充值等级：</td>
                    <td><input  placeholder="" type="text"
                               class="ng-untouched ng-pristine ng-invalid" name="rec_lev" value="<?php echo $data['rec_lev']; ?>">
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"></td>
                </tr>
                <tr>
                    <td>每日固定返还率：</td>
                    <td><input  placeholder="" type="text"
                                class="ng-untouched ng-pristine ng-invalid" name="init_rates" value="<?php echo $data['init_rates']; ?>">%
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"></td>
                </tr>
                <tr>
                    <td>一级返利：</td>
                    <td><input  placeholder="" type="text"
                                class="ng-untouched ng-pristine ng-invalid" name="fir_rec" value="<?php echo $data['fir_rec']; ?>">%
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"></td>
                </tr>
                <tr>
                    <td>二级返利：</td>
                    <td><input  placeholder="" type="text"
                                class="ng-untouched ng-pristine ng-invalid" name="sec_rec" value="<?php echo $data['sec_rec']; ?>">%
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"></td>
                </tr><tr>
                    <td>一级绩效：</td>
                    <td><input  placeholder="" type="text"
                                class="ng-untouched ng-pristine ng-invalid" name="fir_merits" value="<?php echo $data['fir_merits']; ?>">%
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"></td>
                </tr>
                <tr>
                    <td>二级绩效：</td>
                    <td><input  placeholder="" type="text"
                                class="ng-untouched ng-pristine ng-invalid" name="sec_merits" value="<?php echo $data['sec_merits']; ?>">%
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"></td>
                </tr>
                <tr>
                    <td>简介：</td>
                    <td><input  placeholder="" type="text"
                                class="ng-untouched ng-pristine ng-invalid" name="subject" value="<?php echo $data['subject']; ?>">
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"></td>
                </tr>
                <tr>
                    <td>名称：</td>
                    <td><input  placeholder="" type="text"
                                class="ng-untouched ng-pristine ng-invalid" name="body" value="<?php echo $data['body']; ?>">
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"></td>
                </tr>
                <tr>
                    <td>购买返送的注册积分：</td>
                    <td><input  placeholder="" type="text"
                                class="ng-untouched ng-pristine ng-invalid" name="reg_rec" value="<?php echo $data['reg_rec']; ?>">
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"></td>
                </tr>
                <tr>
                    <td>注册激活消耗的积分：</td>
                    <td><input  placeholder="" type="text"
                                class="ng-untouched ng-pristine ng-invalid" name="sec_reg_rec" value="<?php echo $data['sec_reg_rec']; ?>">
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"></td>
                </tr>
                <tr>
                    <td>礼包的积分数：</td>
                    <td><input  placeholder="" type="text"
                                class="ng-untouched ng-pristine ng-invalid" name="gift" value="<?php echo $data['sec_reg_rec']; ?>">
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"></td>
                </tr>
                <tr>
                    <td><input name="id" type="" value="<?php echo $data['id']; ?>"  hidden></td>
                    <td><input type="submit" value="确定" class="btngreen baocun"></td>
                </tr>
                </tbody>
            </table>
        </div>
        </form>

    </div>
</div>
</body>
</html>