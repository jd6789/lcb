<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:92:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/recharge/recharge_edit.html";i:1528787693;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>修改股东规则</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
    <script src="/admin/js/jquery.min.js"></script>
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
        <span class="fl">修改股东规则</span>
        <!--<a class="close fr" href="javascript:void(0);"></a>-->
    </div>
    <div class="form_detail">
        <!---->
        <form method="post" action="<?php echo URL('tmvip/recharge/save_recharge_edit'); ?>">
            <div class="form_table" style="width: 325px;margin: 0 auto;">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td>充值金额：</td>
                        <td><input autofocus="true" placeholder="" type="text"
                                   class="ng-untouched ng-pristine ng-invalid" name="rec_money" value="<?php echo $info['rec_money']; ?>">
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>充值等级：</td>
                        <td><input  placeholder="" type="text"
                                    class="ng-untouched ng-pristine ng-invalid" name="rec_lev" value="<?php echo $info['rec_lev']; ?>">
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>应返总积分：</td>
                        <td><input  placeholder="" type="text"
                                    class="ng-untouched ng-pristine ng-invalid" name="total_inte" value="<?php echo $info['total_inte']; ?>">
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>一级推荐奖励：</td>
                        <td><input  placeholder="" type="text"
                                    class="ng-untouched ng-pristine ng-invalid" name="fir_rec" value="<?php echo $info['fir_rec']; ?>">%
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>二级推荐奖励：</td>
                        <td><input  placeholder="" type="text"
                                    class="ng-untouched ng-pristine ng-invalid" name="sec_rec" value="<?php echo $info['sec_rec']; ?>">%
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>茶点返还比例：</td>
                        <td><input  placeholder="" type="text"
                                    class="ng-untouched ng-pristine ng-invalid" name="fir_merits" value="<?php echo $info['fir_merits']; ?>">%
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>茶券返还比例：</td>
                        <td><input  placeholder="" type="text"
                                    class="ng-untouched ng-pristine ng-invalid" name="sec_merits" value="<?php echo $info['sec_merits']; ?>">%
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>简介：</td>
                        <td><input  placeholder="" type="text"
                                    class="ng-untouched ng-pristine ng-invalid" name="body" value="<?php echo $info['body']; ?>">
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>返还周期：</td>
                        <td>
                            <input type="text" value="<?php echo $info['type_time']; ?>" name="type_time">月
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>分配门店股份：</td>
                        <td>
                            <input type="text" value="<?php echo $info['gufen']; ?>" name="gufen">%
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td><input type="hidden" name="recharge_id" value="<?php echo $info['recharge_id']; ?>"></td>
                        <td><input type="submit" value="确定" class="btngreen baocun"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </form>

    </div>
</div>
<script>
    $(function(){

        $(".one").click(function(){
            //alert($(this).val())
            var val = $(this).val();
            if(val == 1){
                $(".three").show()
            }else{
                $(".three").hide()
            }
        })
    })

</script>
</body>
</html>