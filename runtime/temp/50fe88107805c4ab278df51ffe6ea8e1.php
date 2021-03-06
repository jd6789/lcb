<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"/www/wwwroot/vip.guochamall.com/public/../application/partner/view/index/postal.html";i:1547776528;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
    <script type="text/javascript" src="/partner/js/jquery.min.js"></script>
    <script src="/partner/js/layer/layer.js"></script>
    <style>
        *{
            padding:0;
            margin:0;
        }
        a{
            text-decoration:none;
        }
        ul,ol,li{
            list-style:none;
        }
        html{
            font-size:625%;
        }
        body{
            font-size:.14rem;
            font-family:"微软雅黑";
            overflow-x:hidden;
            background:#f5f7fa;
        }
        html,body{
            height:100%;width:100%;
        }
        .clear:after{
            content:"";
            display:block;
            height:0px;
            clear:both;
        }

        input[type=button], input[type=submit], input[type=file], button { cursor: pointer; -webkit-appearance: none; }
        input{
            -webkit-appearance: none;
            outline:none
        }
        input::-webkit-input-placeholder {
            /* placeholder颜色  */
            color: #aab2bd;
            /* placeholder字体大小  */
            font-size: .14rem;

        }
        .content{
            max-width:7.5rem;
            margin:0 auto;
            padding-bottom:.55rem;
        }
        .bg{
            position:relative;
            width:100%;
            height:100%;
        }
        .bgimg img{
            width:100%;
            position:absolute;
            top:0;
            left:0;
        }
        .top{
            height:.5rem;
            line-height:.5rem;
            position:absolute;
            top:0px;
            left:0px;
            width:100%;
        }
        .top_con{
            text-align:center;
        }
        .drzc{
            font-size:.18rem;
            color:#fff;
            margin-left:20px
        }
        .back{
            position:absolute;
            left:10px;

        }

        .mx{
            float:right;
            margin-right:15px;
            font-size:.15rem;
            color:#fff;
        }
        .txye{
            width:100%;
            text-align:center;
            position:absolute;
            top:0px;
            margin-top:25%;
        }
        .dol{
            font-size:.24rem;
            font-weight:bold;
            color:#fff
        }
        .dolsm{
            font-siez:.14rem;
            color:#fff;
            margin-top:18px;
        }
        .bottom{
            position:absolute;
            margin-top:50%;
            width:100%;
            display:flex;
            text-align:center;
            color:#fff
        }
        .bottom div{
            flex:1
        }
        .xx{
            background:#fff;
        }
        .qb{
            font-size:.15rem;
            padding:27px 0 40px 15px
        }
        .qb img{
            margin-right:10px;
            position:relative;
            top:2px;
        }
        .confirm{
            width:95%;
            margin-left:10px;
            border-bottom:solid 1px #ccc;
            padding-bottom:30px;
        }
        .fuhao{
            font-size:.24rem;
        }
        .input{
            height:26px;
            margin-left:15px;
            border:none;
            font-size:.15rem;
        }
        .all{
            float:right;
            margin-top:3px;
            font-size:.13rem;
            color:#fba325
        }
        .sxf{
            font-size:.1rem;
            color:#b1b1b1;
            padding:5px 0 38px 15px;
        }
        .btn{
            width:95%;
            margin:0 auto;
            padding-bottom:15px;
        }

        .qd-btn{
            width:100%;
            border:none;
            background:#fba325;
            height:49px;
            color:#fff;
            font-size:.16rem;
            border-radius:50px;
        }
    </style>
    <title>提现</title>
</head>
<body>
      <div class="content"><!--content-->

          <div class="bg" style="padding-bottom: 63%">
            <div class="bgimg"><img src="/partner/images/bg.png"></div>
              <div class="top">
                  <div class="top_con">
                      <a class="back" href="<?php echo url('custom_info'); ?>"><img src="/partner/images/back2.png" width="25px"></a><span class="drzc">提现</span>
                      <a href="<?php echo url('posyalindex'); ?>" class="mx">明细</a>
                  </div>
              </div>
              <div class="txye">
                  <p class="dol"></p>
                  <p class="dolsm">可提现余额(元)</p>
              </div>
              <div class="bottom">
                  <div class="b1">
                      <p class="b1m"></p>
                      <p>可消费额度</p>
                  </div>
                  <div class="b1">
                      <p class="b3m"></p>
                      <p>提现中金额</p>
                  </div>
                  <div class="b2">
                      <p class="b2m"></p>
                      <p>已提现金额</p>
                  </div>
              </div>
          </div>
          <div class="xx">
              <div class="qb"><img src="/partner/images/qb.png" width="20px">提现金额</div>
              <div class="confirm">
                  <span class="fuhao">￥</span><span><input class="input" placeholder="请输入提现金额,50起提" type="text"></span><span class="all">全部</span>
              </div>
              <div class="sxf"></div>
              <div class="btn">
                 <input type="button" class="qd-btn" value="提现已关闭">
              </div>
          </div>



      </div><!--content-->

      <script>
          $(function(){
              $.ajax({
                  type:"post",
                  url:"<?php echo url('api/postal/getPostalBaseInfo'); ?>",
                  success:function(data){
                      $(".dol").text(data.data.tea_inte);
                      $(".b1m").text(data.data.teas);
                      $(".b2m").text(data.info)
                      $(".b3m").text(data.postal_will)
                  }
              });

              $(".all").click(function(){
                  var dol = $(".dol").text();
                  $(".input").val(dol)
              });

              $(".qd-btn").click(function(){
                  var money = Number($(".input").val());
                  var dol = Number($(".dol").text());
                  if(money>dol){
                      layer.msg("您输入的金额大于可提现金额");
                      return false
                  }
                  if(money< 50){
                      layer.msg("您输入的金额小于允许的最低金额");
                      return false
                  }
                  if(!money){
                      layer.msg("请先输入提现的金额");
                      return false
                  }
                  if(money<0){
                      layer.msg("操作非法");
                      return false
                  }
                  $.ajax({
                      type:"post",
                      url:"<?php echo url('api/postal/getPostalInfo'); ?>",
                      data:{
                          inte_num:money
                      },
                      success:function(data){
                          if(data == 0){
                              layer.msg("您输入的金额大于可提现金额");
                          }
                          if(data == 1){
                              layer.msg("提现失败");
                          }
                          if(data == 2){
                              layer.msg("提现成功");
                              setTimeout(function () {
                                  location.href = "<?php echo url('index/posyalindex'); ?>"
                              }, 2000)
                          }
                          if(data == 3){
                              layer.msg("信息不完整，前往填写");
                              setTimeout(function () {
                                  location.href = "<?php echo url('shareholder/real_name'); ?>"
                              }, 2000)
                          }
                      }
                  })
              })

          })

      </script>

</body>
</html>