<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/user/login.html";i:1551412662;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>登录</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
	<link href="/newtea/css/layer.css" rel="stylesheet" type="text/css">
	<link href="/newtea/css/red.css" rel="stylesheet" type="text/css">
	<link href="/newtea/css/reset.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/common.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/iconfont.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/login.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="content"><!--content-->

		<div class="back"><a  href="<?php echo URL('newapp/user/index'); ?>"><i class="iconfont icon-fanhui"></i></a></div>
		<div class="logo"><img src="/newtea/images/logo.png" width="120px"></div>

		<div class="box"><!--box-->
			<div class="box-title">
				<!--<a class="login this" href="javascript:;">登录</a>-->
				<!--<a class="reg" href="javascript:;">注册</a>-->
			</div>

			<div class="box-con"><!--box-con-->
				<div class="box-login tab"><!--box-login-->
					<p class="user"><i class="iconfont icon-yonghu"></i>用户名</p>
					<p><input id="username" type="text" placeholder="请输入用户名"></p>
					<p class="pwd"><i class="iconfont icon-mima"></i>密码</p>
					<p><input id="password" type="password" placeholder="请输入密码"></p>
					<div class="forg">
						<!--<a  href="<?php echo URL('newapp/user/repwd'); ?>">忘记密码?</a>-->
						<!--<a class="tel-login" href="<?php echo URL('newapp/user/tellogin'); ?>">手机快捷登录</a>-->
					</div>
					<div class="btn">
						<input id="login-btn" type="button" value="登录">
					</div>
					<!--<div class="dsf">-->
						<!--<p class="dsflogin"><img src="/newtea/images/xian1.png" ><span>第三方登录</span><img src="/newtea/images/xian2.png" ></p>-->
						<!--<p class="quick-login">-->
							<!--<a href="javascript:;"><img src="/newtea/images/wx.png" width="30px"></a>-->
							<!--<a href="javascript:;"><img src="/newtea/images/qq.png" width="20px"></a>-->
						<!--</p>-->
					<!--</div>-->
				</div><!--box-login-->

				<div class="box-reg tab"><!--box-reg-->
					<p class="user"><i class="iconfont icon-yonghu"></i>用户名</p>
					<!--<p><input class="input" id="username" type="text" placeholder="请输入用户名"></p>-->
					<p class="pwd"><i class="iconfont icon-mima"></i>密码</p>
					<p><input class="input" id="password1" type="password" placeholder="请输入密码"></p>
					<p class="pwd"><i class="iconfont icon-mima"></i>确认密码</p>
					<p><input class="input" id="password2" type="password" placeholder="请再次输入密码"></p>
					<p class="pwd"><i class="iconfont icon-shouji"></i>手机</p>
					<p class="telnum">
						<span class="qh">+86</span>
						<span class="sj"><img src="/newtea/images/sj.png" ></span>
						<input class="input" id="tel" type="text" placeholder="请输入手机号">
						<!--<div class="telqh">
							<ul>
								<li></li>
								<li></li>
								<li></li>
							</ul>
						</div>-->
					</p>
					<p class="pwd"><i class="iconfont icon-yanzhengma2"></i>验证码</p>
					<p><input class="input" id="code" type="text" placeholder="请输入短信验证码"><input id="sendCodeBth" class="yzm" type="button" value="获取验证码"></p>
					<p class="pwd"><i class="iconfont icon-iconfonttuijianren"></i>推荐人</p>
					<p><input class="input" id="re_username" type="text" placeholder="请输入推荐人账号(选填)"></p>
					<div class="agree"><input type="checkbox" name="iCheck" id="check"><span class="ty">我已阅读并同意<a class="userxy" href="javascript:;">《用户使用协议》</a></span></div>
					<div class="btn">
						<input class="reg-btn noreg" type="button" value="注册" disabled="disabled">
					</div>
				</div><!--box-reg-->
			</div><!--box-con-->

		</div><!--box-->

	</div><!--content-->



	<script type="text/javascript" src="/newtea/js/jquery-1.9.0.min.js"></script>
	<script src="/newtea/js/layer.js"></script>
    <script src="/newtea/js/icheck.min.js"></script>
	<script>
	$(function(){
		//自定义checkbox
		$('input').iCheck({
             checkboxClass: 'icheckbox_minimal-red',
             radioClass: 'iradio_minimal-red',
             increaseArea: '20%' // optional
        });

		$("#check").on('ifClicked',function(){
			if($(this).is(':checked')){
				$(".reg-btn").addClass("noreg");
				$(".reg-btn").attr("disabled",true)
				
			}else{
				$(".reg-btn").removeClass("noreg");
				$(".reg-btn").attr("disabled",false)
			}
		})

		//登陆注册切换
		$(".box-title a").click(function(){
			var index = $(this).index();
			$(this).addClass("this").siblings().removeClass("this")
			$(this).parents(".box").find(".tab").eq(index).css({"display":"block"}).siblings().css({"display":"none"})
		});

		//登录
		$("#login-btn").click(function(){
			var username = $(".box-login #username").val();
			var password = $("#password").val();
			if(!username){
				layer.msg("请填写用户名");
				return false;
			}
			if(!password){
				layer.msg("请填写密码");
				return false;
			}
			$.ajax({
            type:"post",
            url:"<?php echo url('user/login_login'); ?>",
            dataType:'json',
            data:{
                'username':username,
                'password':password,
            },
            success:function(msg){
                if(msg.status == 2){
                    layer.msg("用户名不存在");
                    return false;
                }
                if(msg.status == 0){
                    layer.msg("密码错误");
                    return false;
                }
                if(msg.status == 3){
                    layer.msg("该账户被冻结");
                    return false;
                }
                if(msg.status == 9){
                    layer.msg(msg.data);
                    return false;
                }
                if(msg.status == 1){
                    layer.msg("登录成功");
                    setTimeout(function(){
                        location.href="<?php echo url('newapp/user/index'); ?>";
                    },1000);
                }
            },
            error:function(){
            	alert("error")
            }
        });
		})
			


			//判断用户名是否存在
      $(".box-reg #username").blur(function(){
        var username = $('.box-reg #username').val();
        if(!username){
            layer.msg("用户名不能为空!");
            return false;
        }else{
            var patt1 = new RegExp(/\s+/g);
            if (patt1.test(username)) {
                layer.msg("用户名不能有空格");
                return false
                }
            }
        $.ajax({
            type:"post",
            url:"<?php echo url('appmobile/user/checkUser'); ?>",
            dataType:'json',
            data:{
                'username':username,
            },
            success:function(msg){
                var msg=JSON.parse(msg)
                if(msg.status == 0){
                    layer.msg("已被注册，请更换用户名");
                }else{
                    layer.msg("可以使用");
                }
            }
        });
      });

      //验证手机号正则
      function checkMobile(mobile) {
          var flag = true;
          if (typeof(mobile) != "string") {
              flag = false;
          } else {
              // ??? ????绰 - ???????
              var mobileReg = /^(13|14|15|17|18)\d{9}$/;
              if (!mobileReg.test(mobile)) {
                  flag = false;
              }
          }
          return flag;
      }
      //判断手机号是否存在
      $("#tel").blur(function(){
          var tel = $('#tel').val();
          if(!tel){
              layer.msg("手机号码不能为空!");
              return false;
          }else{
              if(!checkMobile(tel)){
                  layer.msg("手机号码格式不正确!");
                  return false;
              }
          }

          $.ajax({
              type:"post",
              url:"<?php echo url('appmobile/user/cmobile_phone'); ?>",
              dataType:'json',
              data:{
                  'tel':tel,
              },
              success:function(msg){
                  var msg=JSON.parse(msg)
                  if(msg.status == 1){
                      layer.msg("您是淘米会员,可直接用淘米平台账号登录!");
                      setTimeout(function(){
                          //location.href="<?php echo url('appmobile/user/login'); ?>";
                          $(".box-login").css({"display":"block"});
		                  $(".box-reg").css({"display":"none"});
                      },2500);
                  }
              }
          });
      });
    //获取短信验证码
    $('#sendCodeBth').click(function(){
        var tel = $('#tel').val();
        if(!tel){
            layer.msg("请输入手机号!");
            return false;
        }
        $.ajax({
            type:'post',
            url:"<?php echo url('appmobile/user/test'); ?>",
            data:{'tel':tel},
            dataType:'json',
            success:function(msg){
                layer.msg("验证码发送成功!");
            }
        });
    });


    $("#password1").blur(function(){
        var password1 = $('#password1').val();
        var reg = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
        if(!password1){
            layer.msg("请输入密码");
            return false
        }else{

            if(!reg.test(password1)){
                layer.msg("密码只能由6-16位数字和字母组成");
                return false
            }
        }
    })

          $("#password2").blur(function(){
              var password2 = $('#password2').val();
              var reg = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
              if(!password2){
                  layer.msg("请再次输入密码");
                  return false
              }else{

                  if(!reg.test(password2)){
                      layer.msg("密码只能由6-16位数字和字母组成");
                      return false
                  }
              }
          })

    //判断两次密码是否一致
    $("#password2").blur(function(){
        var password1 = $('#password1').val();
        var password2 = $('#password2').val();
        if(password1 != password2){
            layer.msg("两次密码必须一致!");
            return false;
        }
    })

			//注册
			$(".reg-btn").click(function(){
				var reg = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
		        var username = $('.box-reg #username').val();
		        var password1 = $('#password1').val();
		        var password2 = $('#password2').val();
		        // var pay_pwd1 = $('#pay_pwd1').val();
		        // var pay_pwd2 = $('#pay_pwd2').val();
		        var re_username = $('#re_username').val();
		        var tel = $('#tel').val();
		        var code = $('#code').val();
		        if(!username){
		            layer.msg("用户名不能为空!");
		            return false;
		        }else{
		            var patt1 = new RegExp(/\s+/g);
		            if (patt1.test(username)) {
		                layer.msg("用户名不能有空格");
		                return false
		            }
		        }
		        if(!password1){
		            layer.msg("密码不能为空!");
		            return false;
		        }else{

		            if(!reg.test(password1)){
		                layer.msg("密码只能由6-16位数字和字母组成");
		                return false
		            }
		        }
		        if(!password2){
		            layer.msg("请输入确认密码!");
		            return false;
		        }else{

		            if(!reg.test(password2)){
		                layer.msg("密码只能由6-16位数字和字母组成");
		                return false
		            }
		        }
		        if(!tel){
		            layer.msg("手机号不能为空!");
		            return false;
		        }else{
		            if(!checkMobile(tel)){
		                layer.msg("手机号码格式不正确!");
		                return false;
		            }
		        }

		        if(!code){
		            layer.msg("请输入短信验证码!");
		            return false;
		        }
		        if(password1 != password2){
		            layer.msg("两次密码必须一致!");
		            return false;
		        }
		        $.ajax({
		            type:"post",
		            url:"<?php echo url('appmobile/user/register'); ?>",
		            dataType:'json',
		            data:{
		                'username':username,
		                'password1':password1,
		                'password2':password2,
		                'tel':tel,
		                // 'pay_pwd1':pay_pwd1,
		                're_username':re_username,
		                'sendCode':code,
		            },
		            success:function(msg){
		                var msg=JSON.parse(msg)
		                if(msg.status == 1){
		                    layer.msg("注册成功");
		                    setTimeout(function(){
		                        //location.href="<?php echo url('appmobile/user/login'); ?>";
		                        $(".box-login").css({"display":"block"});
		                        $(".box-reg").css({"display":"none"});
		                    },1000);
		                }
		                if(msg.status == 0){
		                    layer.msg("推荐人不存在");
		                }
		                if(msg.status == 2){
		                    layer.msg("验证码错误");
		                }
		                if(msg.status == 3){
		                    layer.msg("服务器维护中");
		                }
		                if(msg.status == 5){
		                    layer.msg("推荐人账户被冻结");
		                }
		                if(msg.status == 4){
		                    layer.msg("推荐人未激活");
		                }
		            }
		        });
			})
	})

    var alert_msg = '尊敬的股东：\n' +
        '大家好，自2019年3月1日起，股东返款AEP实行因操作步骤原因受到阻碍，现公司决定两种模式返款，各位股东自愿行为决定模式： \n' +
        '一、更改于原方式平台返现\n' +
        ' 二、返AEP保证每月不少于每月返款额度，麻烦各位股东相互转告，并私信于我司客服袁月，统一统计上报并规范化管理，感谢各位股东一如既往支持国茶，在此期间给各位股东造成困扰实在抱歉!\n'
    alert(alert_msg);

    </script>
</body>
</html>