<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/money/mywallet.html";i:1529916231;}*/ ?>
  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
    <link href="/newtea/css/reset.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/common.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/iconfont.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/mywallet.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/layer.css" rel="stylesheet" type="text/css">
    <title>我的钱包</title>
</head>
<body>
      <div class="content"><!--content-->

          <div class="top"><!--top-->
             <a class="back" href="<?php echo url('user/myinfo'); ?>"><i class="iconfont icon-fanhui"></i></a>
             <span class="login">我的钱包</span>
          </div><!--top-->

          <div class="totalassets"><!--totalassets-->
            <div class="totalassets1">
              <div class="totalassets-num">
                <p class="total-sm">总资产(元)</p>
                <p class="total-dol"><?php echo $data['wallet']; ?></p>
              </div>
              <div class="total-img">
                <div class="total-bg"><img src="/newtea/images/total.png"></div>
                <div class="money">
                  <span class="ye">可用余额(元)<?php echo $data['wallet']; ?></span>
                  <span class="zr"><a href="<?php echo url('money/recharge'); ?>"><input class="zr-btn" type="button" value="转入"></a></span>
                </div>
              </div>
            </div>            
          </div><!--totalassets-->
          <hr color="#edf0f5"  size="10"/>

          <div class="list clear">
            <ul>
              <li><a href="javascript:;" class="actives" data-num="0">全部</a></li>
              <li><a href="javascript:;" data-num="1">转入</a></li>
              <li><a href="javascript:;" data-num="2">支出</a></li>
              <li><a href="javascript:;" data-num="3">未支付订单</a></li>
            </ul>
          </div>
          <div class="list-con">
            <table id="table1" border="0" cellpadding="0" cellspacing="0">

            </table>
          </div>

      </div><!--content-->
      <script type="text/javascript" src="/newtea/js/jquery-1.9.0.min.js"></script>
      <script src="/mobile/js/layer.js"></script>
      <script>
        $(function(){
            $(".list-con table").eq(0).css({"display":"block"})
            $(".list a").click(function(){
                var index = $(this).parents().index();
                num = $(this).attr("data-num");
                getData(num)

                $(this).addClass("actives").parent("li").siblings().find("a").removeClass("actives");

            });

            var num = 0;
            getData(num)
            function getData(num){
                $.ajax({
                    type:"post",
                    url:"<?php echo url('money/moneyIndexs'); ?>",
                    data:{
                        type:num
                    },
                    success:function(data){
                        var html = "";
                        for(var i = 0;i < data.length; i++){
                            if(num == 3){
                                html += "<tr>";
                                html += "<td><p>"+ getLocalTime(data[i].addtime) +"</p></td>";
                                html += "<td><p><a style='color:darkblue' href='javascript:;' id='confirm'>继续支付</a></p><p><a id='qxdd' style='color:#f00' href='javascript:;' data-id='"+ data[i].money_id+"'>取消订单</a></p></td>";
                                html += "<td><p>"+ data[i].introduce +"</p><p>"+ data[i].log_out_trade_no +"</p></td>";
                                html += "</tr>"
                            }else{
                                html += "<tr>";
                                html += "<td><p>"+ getLocalTime(data[i].addtime) +"</p></td>";
                                html += "<td class='blue'>"+ data[i].surplus_inte +"</td>";
                                html += "<td><p>"+ data[i].introduce +"</p><p>"+ data[i].log_out_trade_no +"</p></td>";
                                html += "</tr>"
                            }

                        }
                        $("#table1").html(html);
                    }
                })

            }

            $("#table1").on("click","#qxdd",function(){
                var dataid = $("#qxdd").attr("data-id");
                //console.log(dataid)
                $.ajax({
                    type:"post",
                    url:"<?php echo url('money/delMoney'); ?>",
                    data:{
                        id:dataid
                    },
                    success:function(msg){
                        if(msg.status==1){
                            layer.msg("取消成功");
                            setTimeout(function(){
                                location.reload();
                            },1000)
                        }else{
                            layer.msg("取消失败");
                        }
                    }
                })
            })


            $("#table1").on("click","#confirm",function(){
                var dataid = $("#qxdd").attr("data-id");
                location.href = "<?php echo url('money/confirm'); ?>?id=" + dataid;

            })

            function getLocalTime(nS) {
                var myYear = new Date(parseInt(nS) * 1000).getFullYear()
                var myMonth = new Date(parseInt(nS) * 1000).getMonth() + 1
                var myDay = new Date(parseInt(nS) * 1000).getDate()
                var hour = new Date(parseInt(nS) * 1000).getHours();
                var minute = new Date(parseInt(nS) * 1000).getMinutes();
                var second = new Date(parseInt(nS) * 1000).getSeconds();
                if (myMonth < 10) {
                    myMonth = '0' + myMonth
                }
                if (myDay < 10) {
                    myDay = '0' + myDay
                }
                if (second < 10) {
                    second = '0' + second
                }
                if (minute < 10) {
                    minute = '0' + minute
                }
                var showDate = myYear + "-" + myMonth + '-' + myDay + ' ' + hour + ':' + minute + ':' + second
                return showDate
            }

        })
      </script>
</body>
</html>