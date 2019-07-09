<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/user/myinfo.html";i:1543573613;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
    <link href="/newtea/css/reset.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/common.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/iconfont.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/newtea/css/swiper-4.2.0.min.css">
    <link href="/newtea/css/myinfo.css" rel="stylesheet" type="text/css">
	<title>我的</title>
	<style>
		.def img{
			width:45px;
			height:45px;
			border-radius:45px;
		}
		.footer p:first-child{
      		margin:5px 0;
      	}
	</style>
</head>
<body>
	<div class="content"><!--content-->
		
		<div class="title"><!--title-->
			<a href="<?php echo url('newapp/alluser/paycode'); ?>" class="fkm"><img src="/newtea/images/fkm.png" width="25"></a>
			<span class="titlelcb">我的</span>
			<!--<i class="iconfont icon-xiaoxi"></i>-->
		</div><!--title-->

		<div class="my">
			<div class="myinfo">
				<a href="<?php echo url('user/personcenter'); ?>"><i class="iconfont icon-jiantouyou you"></i></a>
				<div class="def"><img src="<?php echo $data['user_picture']; ?>" width="45"></div>
				<div class="name"><?php echo $data['user_name']; ?></div>
				<div class="money"><span class="sm">￥</span><?php echo $data['wallet']; ?></div>
				<div class="wallet">钱包余额</div>
			</div>
			<div class="btn"><a href="<?php echo url('money/recharge'); ?>"><input class="zr-btn" type="button" value="转入"></a></div>
		</div>

		<div class="lcb">
			<div class="cha">
				<span><img src="/newtea/images/cha.png" width="22"></span>
				<span class="tea">理茶宝</span>
				<span class="buyjf" ><a href="javascript:;"  id="buy" class="generations">购买</a></span>
			</div>
			<div class="lcb-con">
				<div class="lcb-con1">
					<div>
						<p>茶点</p>
						<p><?php echo $data['tea_ponit_inte']; ?></p>
					</div>
					<div>
						<p>上次收益（元）</p>
						<p class="scsy"><?php echo $data['sfjf']; ?></p>
					</div>
				</div>
				<div class="lcb-con2">
					<div>
						<p>茶券</p>
						<p><?php echo $data['tea_inte']; ?></p>
					</div>					
					<div>
						<p>剩余返还积分</p>
						<p><?php echo $data['surplus_inte']; ?></p>
					</div>
				</div>
			</div>
		</div>

		<div class="easy">
			<ul>
				<a href="<?php echo URL('newapp/Share/redpack'); ?>" id="hongb">
					<li>
						<i class="iconfont icon-wodeqianbao1"></i>
						<span class="wd">送消费卡</span>
						<i class="iconfont icon-jiantouyou smyou"></i>
					</li>
				</a>
				<a href="<?php echo url('newapp/money/mywallet'); ?>">
					<li>					
						<i class="iconfont icon-wodeqianbao1"></i>
						<span class="wd">我的钱包</span>
						<i class="iconfont icon-jiantouyou smyou"></i>					
					</li>
				</a>
				<a href="http://www.tmvip.cn/mobile/user/login/login2?username=<?php echo \think\Session::get('v_user_shop.username'); ?>&&password=<?php echo \think\Session::get('v_user_shop.password'); ?>">
					<li>
						<i class="iconfont icon-wodeqianbao1"></i>
						<span class="wd">前往商城</span>
						<i class="iconfont icon-jiantouyou smyou"></i>
					</li>
				</a>
				<a href="<?php echo url('newapp/user/order'); ?>">
					<li>
						<i class="iconfont icon-wodeqianbao1"></i>
						<span class="wd">商城订单</span>
						<i class="iconfont icon-jiantouyou smyou"></i>
					</li>
				</a>
				<a href="<?php echo url('newapp/alluser/paycode'); ?>">
					<li>					
						<i class="iconfont icon-fukuanma"></i>
						<span class="wd">付款码</span>
						<i class="iconfont icon-jiantouyou smyou"></i>					
					</li>
				</a>
				<a href="<?php echo url('newapp/recom/otherreg'); ?>">
					<li>
						<img class="yq" src="/newtea/images/yq.png" width="25">
						<span class="wd">推荐注册</span>
						<i class="iconfont icon-jiantouyou smyou"></i>
					</li>
				</a>
				<a href="<?php echo url('newapp/user/myrichardtea'); ?>">
					<li>					
						<img class="yq" src="/newtea/images/cha.png" width="22">
						<span class="wd">我的理茶宝</span>
						<i class="iconfont icon-jiantouyou smyou"></i>					
					</li>
				</a>

				<!--<a href="javascript:;">-->
					<!--<li>					-->
						<!--<i class="iconfont icon-shenghuojiaofei"></i>-->
						<!--<span class="wd">生活缴费</span>				-->
					<!--</li>-->
				<!--</a>-->
				<!--<a href="javascript:;">-->
					<!--<li>					-->
						<!--<i class="iconfont icon-shoujichongzhi"></i>-->
						<!--<span class="wd">手机充值</span>					-->
					<!--</li>-->
				<!--</a>-->
				<!--<a href="javascript:;">-->
					<!--<li>					-->
						<!--<i class="iconfont icon-weibiaoti2"></i>-->
						<!--<span class="wd">电信固话</span>					-->
					<!--</li>-->
				<!--</a>-->
			</ul>
		</div>
		
	</div><!--content-->
		<footer class="footer">
      <nav class="nav">
        <ul>
          <li><a  href="<?php echo url('newapp/user/index'); ?>">
            <p><img src="/newtea/images/sy1.png" width="21"></p>
            <p>首页</p>
          </a></li>
          <li><a href="<?php echo url('newapp/user/richardtea'); ?>">
            <p><img src="/newtea/images/lcb1.png" width="19"></p>
            <p>理茶宝</p>
          </a></li>
          <li><a href="<?php echo url('newapp/user/record'); ?>">
            <p><img src="/newtea/images/jl1.png" width="21"></p>
            <p>记录</p>
          </a></li>
          <li><a class="active"  href="<?php echo url('newapp/user/myinfo'); ?>">
            <p><img src="/newtea/images/wd.png" width="20"></p>
            <p>我的</p>
          </a></li>
        </ul>
      </nav>
  </footer>

	<script src="/newtea/js/jquery-1.8.3.min.js"></script>
	<script src="/newtea/js/swiper-4.2.0.min.js"></script>
	<script src="/mobile/js/layer.js"></script>

	<script>
        var is_weixin = (function(){return navigator.userAgent.toLowerCase().indexOf('micromessenger') !== -1})();
        if(is_weixin){

        }else{
            $("#hongb").hide()
        }
		$(".fkm").click(function(){
		$(".box").fadeIn(100)
		})

		var scsy = $(".scsy").text();
		var b = scsy.replace(/[&\|\\\*^%$#@\-]/g,"");
        $(".scsy").text(b);

		$(".box-bg").click(function(){
			$(".box").fadeOut(200)
		})
        $("#buy").click(function () {
            $.ajax({
                url: "<?php echo url('recharge/checkToActive'); ?>",
                type: 'post',
                success: function (msg) {
                    if (msg.status == 1) {
                        alert('前往购买激活');
                        setTimeout(function () {
                            location.href = "<?php echo url('user/richardtea'); ?>"
                        }, 2000)

                    }
                    if (msg.status == 0) {
                        alert('信息不完整');
                        setTimeout(function () {
                            location.href = "<?php echo url('user/realname'); ?>"
                        }, 2000)

                    }
                    if (msg.status == 2) {

                        alert('您已购买理茶宝');
                    }
                    if (msg.status == 4) {
                        alert('您有尚未支付的理茶宝产品，请支付或者取消');
                    }
                }
            })
        })
  	</script>
</body>
</html>