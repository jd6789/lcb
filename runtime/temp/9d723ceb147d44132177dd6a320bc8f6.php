<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/admin/edit_role.html";i:1524619658;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
    <style>
        html,body{
            height: 100%;
            width: 100%;
        }
        .viewsmall.in {
            min-height: 70%;
            width: 90%;
            top: 2%;
        }
        .form_cont .form_table td:nth-child(odd) {
            text-align: left;
            color: #777;
            width: 70px;
        }
    </style>
</head>
<body>
<div class="viewsmall form_cont r10px in" style="transform: translateX(-37%);">
    <div class="form_cap clearfix">
        <span class="fl">添加权限</span>
        <!--<a class="close fr" href="javascript:void(0);"></a>-->
    </div>
    <div class="form_detail">
        <!---->
        <form method="post" action="<?php echo URL('tmvip/Admin/save_edit_role'); ?>">
            <div class="form_table" style="min-width: 325px;margin: 0 auto;height: 700px;overflow: auto;">
                <div>角色名称： <input autofocus="true" placeholder="" type="text"
                                  class="ng-untouched ng-pristine ng-invalid" name="rule_name" value="<?php echo $role_name['role_name']; ?>"></div>
                <div style="font-weight: 700;margin-top: 20px;">分配权限:</div>

                <table border="1" cellpadding="0" cellspacing="0">
                    <?php if(is_array($rule) || $rule instanceof \think\Collection || $rule instanceof \think\Paginator): $i = 0; $__LIST__ = $rule;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td width="60">
                            <div style="width: 100%;font-weight: 700;border-bottom:1px solid #e6e7eb;"><input type="checkbox" name="rule[]" value="<?php echo $v['id']; ?>" <?php if(in_array(($v['id']), is_array($hasRules)?$hasRules:explode(',',$hasRules))): ?> checked="checked" <?php endif; ?> ><?php echo $v['rule_name']; ?></div>
                            <div style="width: 100%;margin-left: 30px;white-space: normal">
                                <?php if(is_array($v['info']) || $v['info'] instanceof \think\Collection || $v['info'] instanceof \think\Paginator): $i = 0; $__LIST__ = $v['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <div style="width: 100%;white-space: normal;"><input type="checkbox" name="rule[]" value="<?php echo $vo['id']; ?>" <?php if(in_array(($vo['id']), is_array($hasRules)?$hasRules:explode(',',$hasRules))): ?> checked="checked" <?php endif; ?>><?php echo $vo['rule_name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                <div style="width: 100%;margin-left:30px;white-space: normal;border-bottom:1px solid #e6e7eb;">
                                    <?php if(is_array($vo['info']) || $vo['info'] instanceof \think\Collection || $vo['info'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?>
                                    <input type="checkbox" name="rule[]" value="<?php echo $voo['id']; ?>" <?php if(in_array(($voo['id']), is_array($hasRules)?$hasRules:explode(',',$hasRules))): ?> checked="checked" <?php endif; ?>><?php echo $voo['rule_name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>

                        </td>
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr>
                        <td><input type="checkbox" class="qx" data-id="0">全选&nbsp;&nbsp;&nbsp;&nbsp;<input  type="submit" value="确定" class="btngreen baocun"> <input type="text" name="id" value="<?php echo $role_name['id']; ?>" hidden></td>
                    </tr>
                </table>
            </div>
        </form>

    </div>
</div>
<script src="/admin/js/jquery.min.js"></script>
<script>
    $(function () {
        $(".qx").click(function () {
            var flag=$(this).attr("data-id")
            if(flag=="0"){
                $("table input[type='checkbox']").prop("checked","checked")
                $(this).attr("data-id","1")
            }else{
                $("table input[type='checkbox']").prop("checked",false)
                $(this).attr("data-id","0")
            }

        })
    })
</script>
</body>
</html>