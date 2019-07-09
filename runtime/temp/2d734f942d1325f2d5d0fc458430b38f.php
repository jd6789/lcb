<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/alluser/paycode.html";i:1529916228;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
    <link href="/newtea/css/reset.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/common.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/iconfont.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/paycode.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/layer.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/mobile/js/jquery-1.9.0.min.js"></script>
    <script type="text/javascript" src="/mobile/js/jquery.qrcode.min.js"></script>
    <title>付款码</title>
    <style>
        html,body{
            height:100%;
        }
        .codes{
            position:absolute;
            width:70%;
            top:50%;
            left:50%;
            transform:translate(-50%,-50%)
        }
        #code{
            width:100% !important;
            height:100% !important;
            margin:0 auto;

         }
      #code canvas{
            width:100%;
          height:100%;
      }
        .shixiao{
            height:100%;
            width: 100%;
            background:#ccc;
            position:absolute;
            top:-2px;
            box-sizing: border-box;
            padding-top:45%;
            display:none;
        }
    </style>

</head>
<body>
      <div class="content"><!--content-->

          <div class="top"><!--top-->
             <a class="back" href="javascript:history.go(-1);"><i class="iconfont icon-fanhui"></i></a>
             <span class="login">付款码</span>
          </div><!--top-->

          <div class="ewmbg">
            <img src="/newtea/images/ewmbg.png">
              <div class="codes">
                  <div id="code" style="height:100%;width: 100%;"> </div>
                  <div class="shixiao" style="">二维码已失效</div>
              </div>

              <input type="hidden" value='<?php echo $data; ?>' id="datas">
            <p class="sys">请扫描上方二维码进行支付</p>
          </div>
          

      </div><!--content-->
      <script src="/newtea/js/layer.js"></script>
      
</body>
<script>


    $(function(){
        var urldata = $("#datas").val();
        $('#code').qrcode(urldata);


        setTimeout(function(){
            $(".shixiao").css({"display":"block"})
        },120000)
    })

</script>
</html>