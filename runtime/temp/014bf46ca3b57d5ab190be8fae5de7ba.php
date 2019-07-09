<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/user/richardtea.html";i:1541002131;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>理茶宝</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
	<link href="/newtea/css/layer.css" rel="stylesheet" type="text/css">
	<link href="/newtea/css/red.css" rel="stylesheet" type="text/css">
	<link href="/newtea/css/reset.css" rel="stylesheet" type="text/css">
  <link href="/newtea/css/common.css" rel="stylesheet" type="text/css">
  <link href="/newtea/css/iconfont.css" rel="stylesheet" type="text/css">
  <link href="/newtea/css/richardtea.css" rel="stylesheet" type="text/css">
</head>
<style>
    .footer p:first-child{
      margin:5px 0;
    }
  </style>
<body>
	<div class="content"><!--content-->

    <div class="title">理茶宝</div>
    <div class="richtea">
      <div class="cd">理茶宝产品</div>
      <div class="richtea-box"><!--richtea-box-->
        
      </div><!--richtea-box-->
    </div>

	</div><!--content-->
  <footer class="footer">
      <nav class="nav">
        <ul>
          <li><a  href="<?php echo url('newapp/user/index'); ?>">
            <p><img src="/newtea/images/sy1.png" width="21"></p>
            <p>首页</p>
          </a></li>
          <li><a class="active" href="<?php echo url('newapp/user/richardtea'); ?>">
            <p><img src="/newtea/images/lcb.png" width="18"></p>
            <p>理茶宝</p>
          </a></li>
          <li><a href="<?php echo url('newapp/user/record'); ?>">
            <p><img src="/newtea/images/jl1.png" width="21"></p>
            <p>记录</p>
          </a></li>
          <li><a href="<?php echo url('newapp/user/myinfo'); ?>">
            <p><img src="/newtea/images/wd1.png" width="22"></p>
            <p>我的</p>
          </a></li>
        </ul>
      </nav>
  </footer>


	<script type="text/javascript" src="/newtea/js/jquery-1.9.0.min.js"></script>
	<script src="/newtea/js/layer.js"></script>
  <script src="/newtea/js/icheck.min.js"></script>
	<script>
	$(function(){

    $.ajax({
            type: "post",
            url: "<?php echo url('newapp/recharge/rechargeIndex'); ?>",
            success:function(data){
                var html = '';
                //console.log(data);
                for(var i = 0; i < data.length; i++){
                    html += '<div class="richtea-con" data-id="'+data[i].id+'" style="'+(i==0?"display:none":"")+'">';
                    html += '<div class="tea">';
                    html += '<div class="one">';
                    html += '<p class="num"><span class="dol">￥</span><span class="yuan">'+ data[i].rec_money +'</span></p>';
                    html += '<p class="txt">购理茶宝</p>';
                    html += '</div>';
                    html += '<div class="two">+</div>';
                    html += '<div class="one">';
                    html += '<p class="num">'+ data[i].total_inte + '</p>';
                    html += '<p class="txt">赠积分</p>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="btn"><input class="buy-btn" type="button" value="立即购买"></div>';
                    html += '</div>';

                }
                $(".richtea-box").append(html);

            }
    });

    $(".richtea-box").on('click','.buy-btn',function(){
        var price = $(this).parent().parent().find(".yuan").text();
        var dataid = $(this).parent().parent().attr("data-id");
        location.href="<?php echo url('newapp/user/confirmproduct'); ?>"+'?recharge_id='+dataid
    })

	})
	</script>
</body>
</html>