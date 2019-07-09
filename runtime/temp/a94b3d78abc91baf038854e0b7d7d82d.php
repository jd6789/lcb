<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/user/user_edit.html";i:1540302979;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
    <link href="/admin/css/bootstrap.min.css" rel="stylesheet">
    <script src="/admin/js/jquery.min.js"></script>
    <script src="/admin/js/bootstrap.min.js"></script>
    <script src="/admin/js/distpicker.data.js"></script>
    <script src="/admin/js/distpicker.js"></script>
    <script src="/admin/js/main.js"></script>
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
        <span class="fl">修改用户信息</span>
        <!--<a class="close fr" href="javascript:void(0);"></a>-->
    </div>
    <div class="form_detail">
        <!---->
        <form method="post" action="<?php echo URL('tmvip/User/save_user_info'); ?>">
            <div class="form_table" style="width: 325px;margin: 0 auto;">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td>用户名：</td>
                        <td><input autofocus="true" placeholder="" type="text"
                                   class="ng-untouched ng-pristine ng-invalid" name="username"
                                   value="<?php echo $data['user_name']; ?>" readonly />
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>手机号：</td>
                        <td><input autofocus="true" placeholder="" type="text"
                                   class="ng-untouched ng-pristine ng-invalid" name="mobile_phone"
                                   value="<?php echo $data['mobile_phone']; ?>">
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>身份正号：</td>
                        <td><input placeholder="" type="text"
                                   class="ng-untouched ng-pristine ng-invalid" name="idcard" value="<?php echo $data['self_num']; ?>">
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>姓名：</td>
                        <td><input placeholder="" type="text"
                                   class="ng-untouched ng-pristine ng-invalid" name="real_name"
                                   value="<?php echo $data['real_name']; ?>">
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>银行名称：</td>
                        <td><input placeholder="" type="text"
                                   class="ng-untouched ng-pristine ng-invalid" name="bank_name" value="<?php echo $data['bank_name']; ?>">
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td>银行卡号：</td>
                        <td><input placeholder="" type="text"
                                   class="ng-untouched ng-pristine ng-invalid" name="bank_card" value="<?php echo $data['bank_card']; ?>">
                        </td>
                        <td colspan="2" style="text-align:left; padding:0;"></td>
                    </tr>
                    <tr>
                        <td class="">收货地址：</td>
                        <td height="30">
                            <div class="form-inline">
                                <div data-toggle="distpicker">
                                    <div class="form-group">
                                        <label class="sr-only" for="province1">Province</label>
                                        <select class="form-control" id="province1" data-province="<?php echo $data['info']['province']; ?>" name="province"></select>
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="city1">City</label>
                                        <select class="form-control" id="city1" data-city="<?php echo $data['info']['city']; ?>" name="city"></select>
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="district1">District</label>
                                        <select class="form-control" id="district1" data-district="<?php echo $data['info']['area']; ?>" name="area"></select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="district" value="<?php echo $data['info']['district']; ?>" style="height: 30px;">
                                    </div>
                                    <input type="hidden" name="address_id" value="<?php echo $data['info']['id']; ?>">
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td><input name="user_id" type="" value="<?php echo $data['user_id']; ?>" hidden></td>
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