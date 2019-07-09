<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/user/index.html";i:1524828036;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="/admin/images/mjicon.ico" type="image/x-icon">
    <script src="/admin/js/jquery.min.js"></script>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
    <style>
        iframe {
            width: 100%;
            height: 100%;
            overflow: auto;
        }
    </style>
</head>
<body>
<div class="contviewtab">
    <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
    <a href="<?php echo URL($v['module_name'].'/'.$v['controller_name'].'/'.$v['action_name']); ?>" target="bbb" <?php if($v['parent_id'] == $url): ?>class="curr" <?php else: ?>style="display: none;"<?php endif; ?>><?php echo $v['rule_name']; ?></a>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</div>
<div class="bench white-scroll">
    <iframe src="<?php echo URL('tmvip/User/user_manage'); ?>" frameborder="0" name="bbb" id="iframe"></iframe>


</div>
<script src="/admin/js/jquery.min.js"></script>
<script>
    $(function () {
        $(".user").mouseover(function () {
            $(".user>.dropdown").addClass("in")
        }).mouseleave(function () {
            $(".user>.dropdown").removeClass("in")
        })
        $(".contviewtab a.curr:eq(0)").addClass("current")
        var href=$(".contviewtab a.curr:eq(0)").attr("href")
        $("#iframe").attr("src",href)
        $(".contviewtab a").click(function () {
            $(this).addClass("current").siblings().removeClass("current")
        })

    })
</script>
</body>
</html>