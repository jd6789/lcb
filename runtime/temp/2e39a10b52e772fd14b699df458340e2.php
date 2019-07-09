<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/user/personcenter.html";i:1534839619;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
    <link href="/newtea/css/reset.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/common.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/iconfont.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/personcenter.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/layer.css" rel="stylesheet" type="text/css">
    <title>个人中心</title>
    <script type="text/javascript" src="/newtea/js/jquery-1.9.0.min.js"></script>
    <style>
        .tc-btn {
            width: 1.5rem;
            height: .45rem;
            border: none;
            background: #435aaa;
            border-radius: 20px;
            font-size: .15rem;
            color: #fff;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<div style="position: absolute;width: 100%;height: 100%;background-color:#fff;top: 0;left: 0;" id="zhezhao">
    <img src="/newtea/images/loding.gif" alt="" style="width: 50px;position: absolute;left: 50%;top: 50%;transform:translate(-50%,-50%)">
</div>
      <div class="content"><!--content-->

          <div class="top"><!--top-->
             <a class="back" href="<?php echo url('user/myinfo'); ?>"><i class="iconfont icon-fanhui"></i></a>
             <span class="login">个人中心</span>
          </div><!--top-->

          <div class="center">
            <div class="center-info">
              <ul>
                <a href="<?php echo url('user/personinfo'); ?>"><li><i class="iconfont icon-yonghu"></i>个人信息<i class="iconfont icon-jiantouyou"></i></li></a>
                <a class="smrz" href="<?php echo url('realname'); ?>"><li><i class="iconfont icon-xiazai18"></i>实名认证<i class="iconfont icon-jiantouyou"></i></li></a>
                <a href="<?php echo url('changepwd'); ?>"><li><i class="iconfont icon-mima"></i>修改密码<i class="iconfont icon-jiantouyou"></i></li></a>
                <a href="<?php echo url('recommender'); ?>"><li><i class="iconfont icon-qiandai"></i>我的推荐<i class="iconfont icon-jiantouyou"></i></li></a>
                  <a  href="javascript:;" id="link" <?php if(\think\Request::instance()->session('type') == 1): ?> style="display:none" <?php else: endif; ?>> <li class="glzz"><i class="iconfont icon-qiandai"></i>关联账户<i class="iconfont icon-jiantouyou"></i></li></a>


                  <input class="glzz_val" hidden value="<?php echo \think\Request::instance()->session('type'); ?>">
            </ul>
            </div>
          </div>
          <div class="tc" style="text-align:center">
              <input class="tc-btn" type="button" id="qhdl" value="切换登录">
              <!--<input class="tc-btn" type="button" id="outlogin" style="background:#ceb63a" value="退出登录">-->
          </div>


      </div><!--content-->

      <script src="/newtea/js/layer.js"></script>

      <script>

          var glzz = $(".glzz_val").val()
          if(glzz == 1){
              $(".glzz").hide()
          }

              $("#link").click(function(){
                  $.ajax({
                      type:"post",
                      url:"<?php echo url('Wx/account_link'); ?>",
                      success:function(data){
                          var data = JSON.parse(data);
                          if(data.status == 1){
                              location.href = "<?php echo url('user/linkover'); ?>"
                          }else{
                              location.href = "<?php echo url('user/accountlink'); ?>"
                          }

                      }
                  })

              })
      $.ajax({
          type:"post",
          url:"<?php echo url('user/is_realname'); ?>",
          success:function(data){
            //console.log(data)
            if(data.status == 1){
                $(".smrz").css({"display":"none"})

            }
              $("#zhezhao").hide()
          },
          error:function(){
            alert("error")
              $("#zhezhao").hide()
          }
        })



        $('#qhdl').click(function () {
            location.href = "<?php echo url('login/login'); ?>"
        })
      </script>


<script>
    function isWeiXin() {
        var ua = window.navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == 'micromessenger') {
            return true;
        } else {
            return false;
        }
    }
    //判断是否为微信打开的
    if(isWeiXin()){
        $("#link").show()
    }else{
        $("#link").hide()
    }
</script>
      
</body>
</html>