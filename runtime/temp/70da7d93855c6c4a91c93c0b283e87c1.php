<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:89:"/www/wwwroot/vip.guochamall.com/public/../application/partner/view/index/posyalindex.html";i:1529402390;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
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
        .top{
            height:.5rem;
            line-height:.5rem;
            text-align:center;
        }
        .back{
            float:left;
            margin-left:10px;
        }
        .mx{
            font-size:.16rem;
            color:#333;
            margin-right:35px;
        }
        table{
            width:94%;
            margin:0 12px;
        }
        .table tr td{
            border-bottom:solid 1px #ccc;
            padding:11px 0;
        }
        .blue{
            color:#1d70eb;
        }
        .orange{
            color:#fba325;
        }
        .red{
            color:#d81e1e;
        }
        .time{
            color:#797979;
        }
        .p{
            padding-top:10px;
        }
        .page{
            text-align:center;
            margin-top:10px;
        }
    </style>
    <link href="/partner/css/jquery.pagination.css" rel="stylesheet" type="text/css">
    <title>提现明细</title>
</head>
<body>
      <div class="content"><!--content-->

          <div class="top">
              <a href="<?php echo url('postal'); ?>" class="back"><img src="/partner/images/back1.png" width="25px"></a>
              <span class="mx">提现明细</span>
          </div>
          <hr color="#f5f7fa" size="5">

          <div>
              <table border="0" cellpadding="0" cellspacing="0" class="table">
                  <tbody class="tbody">

                  </tbody>
              </table>
              <div class="box">
                  <div id="pagination4" class="page fl"></div>
              </div>
          </div>
      </div><!--content-->
      <script type="text/javascript" src="/partner/js/jquery.min.js"></script>
      <script src="/partner/js/layer/layer.js"></script>
      <script src="/partner/js/jquery.pagination.min.js" type="text/javascript"></script>
      <script>

          var page = 1;
          var count = '';
          getData(page);
          $('#pagination4').pagination({
              showData: 8,
              count: 3,
              pageCount:count,
              homePageText: "首页",
              endPageText: "尾页",
              prevPageText: "上一页",
              nextPageText: "下一页",
              mode: 'fixed',
              coping:false,
              callback: function (currentPage) {
                  page = currentPage
                  getData(page);

              }
          });
          function getData(page){
              $.ajax({
                  type:"post",
                  url:"<?php echo url('api/postal/allPostalInfo'); ?>",
                  data:{
                      page:page
                  },
                  success:function(data){
                      if(data.data==0){
                          $("#pagination4").hide();
                                return false
                      }else{
                          var html = '';
                          for(var i=0;i<data.data.length;i++){
                              html += '<tr>';
                              html += '<td align="left"><p>-<span>'+data.data[i].money_num+'</span></p><p class="p">分红提现</p></td>';
                              if(data.data[i].status == 0){
                                  html += '<td align="right"><p class="orange">申请中</p><p class="time p">'+data.data[i].create_time+'</p></td>';
                              }
                              if(data.data[i].status == 1){
                                  html += '<td align="right"><p class="blue">提现成功</p><p class="time p">'+data.data[i].create_time+'</p></td>';
                              }
                              if(data.data[i].status == 2){
                                  html += '<td align="right"><p class="red">驳回</p><p class="time p">'+data.data[i].create_time+'</p></td>';
                              }
                              html += '</tr>'
                          }
                          $(".tbody").html(html);
                          count = data.data_num
                          count_page = Math.ceil(count / 8)
                          $("#pagination4").pagination("setPage", page,count_page);
                      }

                  }
              })
          }



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

      </script>

</body>
</html>