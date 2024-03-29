<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/money/recharge.html";i:1529916231;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
    <link href="/mobile/css/reset.css" rel="stylesheet" type="text/css">
    <link href="/mobile/css/common.css" rel="stylesheet" type="text/css">
    <link href="/mobile/css/iconfont.css" rel="stylesheet" type="text/css">
    <link href="/mobile/css/recommend.css" rel="stylesheet" type="text/css">
    <link href="/mobile/css/layer.css" rel="stylesheet" type="text/css">
    <title>钱包充值</title>
    <style>
      .czje{
          height:50px;
          line-height:50px;
          background:#fff;
          margin-top:10px;
          font-size:.16rem;
          color:#000;
          padding-left:15px;
          position:relative;
      }
      #text1{
        height:36px;
        margin-left:15px;
        border:none;
        width:calc(100% - 90px);
      }
      .close{
        position:absolute;
        right:20px;
        
        width:20px;
        height:20px;
        background:#bfbfbf;
        line-height:18px;
        text-align:center;
        font-size:.16rem;
        border-radius:20px;
        color:#fff;
        top:17px;
        display:none;
        z-index:999;
      }
      .cz_b{
        width:75%;
        margin:0 auto;
      }
      #cz_btn{
        width:100%;
        height:.45rem;
        background:#435aaa;
        color:#fff;
        font-size:.15rem;
        margin-top:60px;
        border:none;
        border-radius:30px;
      }
      #calculator{
        position:fixed;
        bottom:0px;
        left:0px;
        width:100%;
      }

    </style>
</head>
<body>
      <div class="content" ><!--content-->

          <div class="top"><!--top-->
             <a class="back" href="<?php echo url('user/myinfo'); ?>"><i class="iconfont icon-fanhui"></i></a>
             <span class="login">钱包充值</span>
          </div><!--top-->

        
          <div class="czje" id="inputContent">
            <!--充值金额<input type="text" id="text1" readonly><span class="close">x</span>-->
            充值金额<input type="number" id="text1" ><span class="close">x</span>
          </div>
          
          <div class="cz_b">
            <input type="button" id="cz_btn" value="立即充值">
          </div>

      </div><!--content-->
      <script type="text/javascript" src="/mobile/js/jquery-1.9.0.min.js"></script>
      <script src="/mobile/js/layer.js"></script>
      

      <script type="text/javascript" src="/mobile/js/index.js"></script>
      <script>
          $("#cz_btn").click(function () {
              var money=$("#text1").val()

              $.ajax({
                  url:"<?php echo url('money/buyMoney'); ?>",
                  data:{money:money},
                  type:"post",
                  success:function (msg) {
                      if (msg == 3) {
                          layer.msg("信息不完整，前往填写");
                          setTimeout(function () {
                              location.href = "<?php echo url('user/realname'); ?>"
                          }, 2000)
                      }
                      if (msg == 4) {
                          layer.msg("您有尚未支付的钱包充值订单，请支付或者取消")
                          return false

                      }
                      if (msg == 0) {
                          layer.msg("下单购买失败")
                          return false
                      }
                      if (msg == 9) {
                          layer.msg("请输入正确金额")
                          return false
                      }
                      if (msg.status == 1) {
                          layer.msg("下单成功");
                          setTimeout(function () {
                              location.href = "<?php echo url('money/confirm'); ?>?id=" + msg.id;
                          }, 2000)
                      }
                  }
              })
          })
      </script>
</body>
</html>