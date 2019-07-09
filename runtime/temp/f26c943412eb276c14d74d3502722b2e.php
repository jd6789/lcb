<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:91:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/user/user_inte_change.html";i:1547628174;}*/ ?>
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
    <form method="post" action="<?php echo URL('tmvip/User/user_inte_change'); ?>">
        <div class="form_table" style="width: 325px;margin: 0 auto;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td>输入用户名：</td>
                    <td><input  placeholder="" type="text"
                                class="ng-untouched ng-pristine ng-invalid" name="username"  id="user_name"/>
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"><input class="btngreen baocun" id="confirm" value="查询" style="width: 50px;height: 30px;font-size: 12px;margin-left: 8px;" readonly></td>
                </tr>
                <tr class="dn">
                    <td>姓名：</td>
                    <td id="username"></td>
                    <td>手机号：</td>
                    <td id="tel"></td>
                </tr>
                <tr class="dn">
                    <td>可用茶点：</td>
                    <td id="teapoint"></td>
                    <td>可用茶劵：</td>
                    <td id="teainte"></td>
                </tr>
                <tr>
                    <td>变动原因：</td>
                    <td><textarea  id="" style="width: 230px;height: 80px;vertical-align: middle;resize: none" name="introduce"></textarea>
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;"></td>
                </tr>
                <tr>
                    <td>茶点修改：</td>
                    <td ><select name="teaponit_tye" style="width: 65px">
                        <option value="1">增加</option>
                        <option value="0">减少</option>
                    </select>
                        <input placeholder="" type="text"
                               class="ng-untouched ng-pristine ng-invalid" name="tea_ponit_inte" value="0" style="width: 160px;">
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;">

                    </td>
                </tr>
                <tr>
                    <td>茶券修改：</td>
                    <td><select name="teainte_type" style="width: 65px;">
                        <option value="1">增加</option>
                        <option value="0">减少</option>
                    </select>
                        <input placeholder="" type="text"
                               class="ng-untouched ng-pristine ng-invalid" name="tea_inte" value="0"style="width: 160px;">
                    </td>
                    <td colspan="2" style="text-align:left; padding:0;">

                    </td>
                </tr>
                <tr>
                    <td><input name="user_id" type=""  hidden ></td>
                    <td><input type="submit" value="确定" class="btngreen baocun" disabled id="submit" style="background-color:#ccc;"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </form>

</div>
</div>

<script src="/admin/js/jquery.min.js"></script>
<script>
    $(function () {
        $("#user_name").blur(function () {
            var a=$("#user_name").val()
            if(a ==''){
                $("#submit").attr("disabled",true)
                $("#submit").css("background-color","#ccc")
            }else{
                $("#submit").removeAttr("disabled")
                $("#submit").css("background-color","#29a672")
            }
        })
        $("#user_name").blur()
        $("#confirm").click(function () {
            var a=$("#user_name").val()
            if(a != ""){
                $.ajax({
                    url:"<?php echo URL('tmvip/User/check_user'); ?>",
                    type:'post',
                    data:{username:a},
                    success:function (data) {

                        var data=JSON.parse(data)
                        console.log(data);
                        if(data.status==0){
                            alert("该用户不存在")
                            $("tr.dn").slideUp(500)
                        }else{
                            $("#username").text(data.data.real_name)
                            $("#tel").text(data.data.mobile_phone)
                            $("#teapoint").text(data.data.tea_ponit_inte)
                            $("#teainte").text(data.data.tea_inte)
                            $("tr.dn").slideDown(1000)
                        }
                    }
                })
            }else{
                $("tr.dn").slideUp(500)
            }
        })
    })
</script>
</body>
</html>