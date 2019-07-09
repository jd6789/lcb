<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/money/confirm.html";i:1529916230;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
    <link href="/newtea/css/reset.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/common.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/iconfont.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/confirm.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/layer.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/red.css" rel="stylesheet">
    <script type="text/javascript" src="/newtea/js/jquery-1.9.0.min.js"></script>
    <script src="/newtea/js/icheck.min.js"></script>
    <script src="/newtea/js/layer.js"></script>

    <title>确认支付</title>
    <style>
      .wallet-balance{
        height:1rem;
        box-sizing:border-box;
        border-top:solid 1px #edf0f5;
        background:#fff;
         padding-left:15px;
      }
      .wallet-num{
        font-size:.15rem;
        margin:.15rem 0;
      }
      .wallet-num span{
        color:#ce0b0b;

      }
      .fh{
        font-size:.20rem;
        color:#333333;
        font-weight:700;
      }
      .inp{
        height:.26rem;
        border:none;
        font-size:.16rem;
        margin-left:5px;
      }
      .all{
        float:right;
        margin-right:15px;
        margin-top:8px;
        color:#4359aa;
        font-size:.14rem;
      }
    </style>
</head>
<body>
      <div class="content"><!--content-->
          <div class="top"><!--top-->
             <a class="back" href="javascript:history.go(-1);"><i class="iconfont icon-fanhui"></i></a>
             <span class="login">确认支付</span>
             <!--<a class="rel" href="reg.html">注册</a>-->
          </div><!--top-->

          <div class="total">
            <p class="total-num"><span class="sm"></span>需支付金额:¥<?php echo $data['montys']; ?></p>
            <p class="total-jf">订单名称：我的钱包充值<?php echo $data['montys']; ?></p>
          </div>
          <div class="payment">
          <div class="pay">支付方式</div>
            <ul>
              <li class="zfbpay">
                <span><img src="/newtea/images/pay-zfb.png"></span>
                <span>支付宝</span>
                <span class="radio"><input value="0" type="radio" name="iCheck"></span>
              </li>
              <li class="wxpay">
                <span><img src="/newtea/images/pay-wx.png"></span>
                <span>微信</span>
                <span class="radio"><input id="wx" value="1"  type="radio" name="iCheck"></span>
              </li>
              <li>
                <span><img src="/newtea/images/pay-xx.png"></span>
                <span>线下支付</span>
                <span class="radio"><input id="xx"  value="3" type="radio" name="iCheck"></span>
              </li>
            </ul>
          </div>
          
          <div   class="xxinfo">
              <div class="xxinfo1">您选择了 线下支付，即将跳转到理茶宝页面，请在下方查看转账方式，并且完成转账后联系客服</div>
              <p style="text-align:center">线下充值渠道,公司账户</p>
              <div class="xxinfo2">               
                <p class="red">需要支付6%的税点</p>
                <p>账户名称：国茶实业（上海）股份有限公司</p>
                <p>开户银行：中国银行</p>
                <p>银行账号：436462236256</p>
                <p>开户网点：中国银行股份有限公司上海市零陵路支行</p>
                <p class="red">温馨提示，转账时请在信息留言栏里备注商城用户名</p>
              </div>
              <p style="text-align:center">线下充值渠道</p>
              <div class="xxinfo2">               
                <p class="red">无需支付税点</p>
                <p>账户名称：刘耿丹</p>
                <p>开户银行：中国光大银行</p>
                <p>银行账号：436462236256</p>
                <p>银行账号：6226630602009028</p>
                <p class="red">温馨提示，转账时请在信息留言栏里备注商城用户名</p>
              </div>
          </div>

          <div class="now">
            <input id="ljfk" type="button" class="now-btn" value="立即付款">
          </div>
          <div class="look">
            <input   type="button" class="look-btn" value="前往支付">
          </div>

      </div><!--content-->



    <script>
        $(document).ready(function(){
          $('input').iCheck({
             checkboxClass: 'icheckbox_minimal-red',
             radioClass: 'iradio_minimal-red',
             increaseArea: '20%' // optional
        });

          
    });
    </script>

    <script>  

      $(function(){
        var payid = getUrlParam("id")//获取当前地址信息
          if (isWeiXin()) {
                $(".wxpay").show()
                $(".zfbpay").hide()
            } else {
                $(".wxpay").hide()
                $(".zfbpay").show()
            }
              
            //alert($("input[name='iCheck']:checked").val());  
          
             
            $(".look-btn").click(function(){
                location.href="<?php echo url('user/index'); ?>";
            })

            $('.payment input').on('ifChecked', function(event){
                  var pay_val = $(this).val();
                  //console.log(pay_v)
                  if(pay_val == 3){
                    $('.now').css({'display':'none'});
                    $('.look').css({'display':'block'});
                    $('.xxinfo').css({'display':'block'});
                  }else{
                    $('.now').css({'display':'block'});
                    $('.look').css({'display':'none'});
                    $('.xxinfo').css({'display':'none'});
                  }
                 
            });


          $('#wx').on('ifClicked', function(event){
              //alert(1)
              $.ajax({
                  type:"post",
                  url:"<?php echo url('appmobile/money/findOneById'); ?>",
                  data:{id:payid},
                  success:function(data){
                      if(data==0){
                          layer.msg("服务器错误")
                      }else{
                          location.href="/wapWxpay/example/moneyJsapi.php?body="+data.body+"&&money="+data.montys+"&&attach=ordermanager&&trade="+data.money_num;
                      }
                  }
              })
          });

          $("#ljfk").click(function(){
              var pay_v = $(".payment input[type=radio]:checked").val();
              //支付宝0
              if(pay_v == 0){
                  window.location.href="<?php echo url('money/webalpay'); ?>?id="+payid;
              }
          })
          //获取当前地址信息
          function getUrlParam(name) {
                var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
                var r = window.location.search.substr(1).match(reg);
                if (r != null) return unescape(r[2]);
                return null;
              }
          })


        function isWeiXin() {
                var ua = window.navigator.userAgent.toLowerCase();
                if (ua.match(/MicroMessenger/i) == 'micromessenger') {
                    return true;
                } else {
                    return false;
                }
            }
      
    </script>




</body>
</html>