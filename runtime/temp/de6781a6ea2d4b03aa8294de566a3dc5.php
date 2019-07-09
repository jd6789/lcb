<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/index/index.html";i:1531294248;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理系统</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
    <style>
        iframe{
            width: 100%;
            height: 100%;
            overflow: auto;
        }
    </style>
    </head>
<body>
<div class="main">
    <!---->
    <div class="leftpanel">
        <div class="logo"><a>
            <!----><img height="60" src="/admin/images/logo.jpg" width="100">
            <!---->
        </a></div>
        <div class="leftmenu thin-scroll">
            <!---->
        <ul>
                <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                <li><a  href="<?php echo URL($v['module_name'].'/'.$v['controller_name'].'/'.$v['action_name']); ?>"
                       target="aaa"  <?php if($v['parent_id'] == '0'): ?> class="curr"<?php else: ?>style="display: none;"<?php endif; ?>><?php echo $v['rule_name']; ?></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>
        </div>

    </div>

    <div class="content" style="margin: 0px 0px 0px 100px;">
        <!---->
        <div class="contnav flex">
            <div class="txt">
                <?php echo $admin['username']; ?><!----><span> - <?php echo $admin['role_name']; ?></span>
            </div>
            <div class="txt"><a href="<?php echo url('partner/index/delDirAndFile'); ?>">清除缓存</a></div>
            <div class="marquee flex-1">
                <!----><marquee direction="left" onmouseout="this.start()" onmouseover="this.stop()" scrollamount="6">
                <!--v3.1.0版本更新内容：1、条码枪扫码收款；2、客人微信自助买单；3、商品沽清；更多详情请关注微信公众号：“茗匠门店管理系统”。
                <a class="mqclose" href="javascript:void(0);" title="关闭"></a>-->
            </marquee>
            </div>
            <div class="navinfo thin-scroll">
                <!--<div class="nav_feedback"><a href="javascript:void(0);">意见反馈</a></div>-->
                <!--<div class="message"><a>消息</a>&lt;!&ndash;&ndash;&gt;</div>-->
                <div class="user">
                    <a href="javascript:void(0)"><?php echo $admin['username']; ?></a>
                    <div class="dropdown r5px">
                        <ul>
                            <li><a href="<?php echo URL('tmvip/Admin/out_login'); ?>">退出登录</a></li>
                        </ul>
                    </div>
                </div>
                <!--<div class="shop">-->
                    <!--<a href="javascript:void(0)">【gc】</a>-->
                    <!--<div class="dropdown r5px">-->
                        <!--<ul>-->
                            <!--&lt;!&ndash;&ndash;&gt;-->
                        <!--</ul>-->
                    <!--</div>-->
                <!--</div>-->
            </div>
        </div>
        <div class="nfb_box tst2s r5px]">
            <!---->
            <i class="jiantou"></i>
            <div class="caption"><span>意见反馈</span><a class="close" href="javascript:void(0);"></a></div>
            <div class="box">
                <textarea class="r3px ng-untouched ng-pristine ng-valid" placeholder="请上帝们指正！ o(╥﹏╥)o"></textarea>
            </div>
            <a class="btngreen" href="javascript:void(0);">提交</a>
        </div>
        <div class="contview">
            <iframe src="<?php echo URL('tmvip/Report/lichabao'); ?>" frameborder="0" id="frame" name="aaa"></iframe>

        </div>
    </div>
    <!--登录失效提示-->
    <div class="feedback tst2s r5px">
        登录信息已失效，请重新登录

    </div>
</div>
<script src="/admin/js/jquery.min.js"></script>
<script>
    $(function () {
        $(".leftmenu ul li a.curr:eq(0)").addClass("current")
        var href=$(".leftmenu ul li a.curr:eq(0)").attr("href")
        $("#frame").attr("src",href)
        $(".leftmenu ul li").click(function () {
            $(".leftmenu ul li a").removeClass("current")
            $(this).find("a").addClass("current")
        })
        $(".user").mouseover(function () {
            $(".user>.dropdown").addClass("in")
        }).mouseleave(function () {
            $(".user>.dropdown").removeClass("in")
        })

    })
</script>
</body>
</html>