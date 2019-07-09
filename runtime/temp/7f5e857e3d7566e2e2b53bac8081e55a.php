<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/user/record.html";i:1534925407;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,user-scalable=no,minimal-ui">
    <link href="/newtea/css/reset.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/common.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/iconfont.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/newtea/css/swiper-4.2.0.min.css">
    <link href="/newtea/css/shijian.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/jquery.pagination.css" rel="stylesheet" type="text/css">
    <link href="/newtea/css/record.css" rel="stylesheet" type="text/css">
	<title>记录</title>
</head>
<style>
    .footer p:first-child{
      margin:5px 0;
    }
  </style>
<body>
	<div class="content"><!--content-->
		
		<div class="title"><!--title-->
			<span class="titlelcb">理茶宝</span>
		</div><!--title-->
		<div class="slide">
			<ul class="slide-ul">
				<li class="lcb">理茶宝</li>
				<li class="qb">钱包</li>
			</ul>
		</div>



		<div class="list clear">
            <ul>
              <li><a href="javascript:;" data-num="1" class="actives">全部</a></li>
              <li><a href="javascript:;" data-num="2" >每日释放</a></li>
              <li><a href="javascript:;" data-num="3">奖励</a></li>
              <li><a href="javascript:;" data-num="4">消费</a></li>
            </ul>
        </div>
        <div class="wallet clear">
            <ul>
              <li><a href="javascript:;" data-num="1" class="actives">全部</a></li>
              <li><a href="javascript:;" data-num="2">充值</a></li>
              <li><a href="javascript:;" data-num="3">支出</a></li>
            </ul>
        </div>
          <div class="list-con"><!--list-con-->
          	<div class="list-boc">
          		<div class="list-time">
          			<input type="text" id="input1" value=""/>
          		</div>				
          		<table id="table1" border="0" cellpadding="0" cellspacing="0">
	              
	            </table>
	            <div class="box">
		            <div id="pagination1" class="page fl"></div>
		        </div>
          	</div>
          	<div class="list-boc">
          		<div class="list-time">
          			<input type="text" id="input2" value=""/>
          		</div>				
          		<table id="table2" border="0" cellpadding="0" cellspacing="0">
	              
	            </table>
	            <div class="box">
		            <div id="pagination2" class="page fl"></div>
		        </div>
          	</div>              
          </div><!--list-con-->
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
          <li><a class="active" href="<?php echo url('newapp/user/record'); ?>">
            <p><img src="/newtea/images/jl.png" width="21"></p>
            <p>记录</p>
          </a></li>
          <li><a href="<?php echo url('newapp/user/myinfo'); ?>">
            <p><img src="/newtea/images/wd1.png" width="22"></p>
            <p>我的</p>
          </a></li>
        </ul>
      </nav>
  </footer>

	<script src="/newtea/js/jquery-1.9.0.min.js"></script>
	<script src="/newtea/js/swiper-4.2.0.min.js"></script>
	<script src="/newtea/js/jquer_shijian.js" type="text/javascript"></script>
	<script src="/newtea/js/jquery.pagination.min.js" type="text/javascript"></script>
	<script>
        //分页
        $('#pagination1').pagination({
            showData: 8,
            count: 3,
            pageCount:3,
            homePageText: "首页",
            endPageText: "尾页",
            prevPageText: "上一页",
            nextPageText: "下一页",
            mode: 'fixed',
            coping:false,
            callback: function (currentPage) {
                page = currentPage
                getdata(page, num, time1);

            }
        });
        $('#pagination2').pagination({
            showData: 8,
            count: 3,
            pageCount:3,
            homePageText: "首页",
            endPageText: "尾页",
            prevPageText: "上一页",
            nextPageText: "下一页",
            mode: 'fixed',
            coping:false,
            callback: function (currentPage) {
                page1 = currentPage
                getdata1(page1, num1, time2);

            }
        });

        $(".titlelcb").click(function(){
			//下拉
			var none = $(".slide").css("display");
			if(none == "none"){
				$(this).addClass("titlelcbs")
				$(".slide").slideDown(200);
				$(".slide-ul").slideDown(300);
			}else{
				$(this).removeClass("titlelcbs")
				$(".slide").slideUp(200);
				$(".slide-ul").slideUp(300);
			}
		
		})


			$(".list li a").click(function(){
				num = $(this).attr("data-num");
				//console.log(num)
				$(this).addClass("actives").parent("li").siblings().find("a").removeClass("actives");
				getdata(page, num,time1);
			})
			$(".wallet li a").click(function(){
				num1 = $(this).attr("data-num");
				$(this).addClass("actives").parent("li").siblings().find("a").removeClass("actives");
				getdata1(page1, num1,time2);
			})

			$(".qb").click(function(){
				var none = $(".slide").css("display");
				if(none == "none"){
					$(".titlelcb").addClass("titlelcbs")
				}else{
					$(".titlelcb").removeClass("titlelcbs")
				}
				$(".list").css({"display":"none"});
				$(".wallet").css({"display":"block"});
				$(".slide").css({"display":"none"});
				getdata1(page1, num1,time2);
			})
			$(".lcb").click(function(){
				var none = $(".slide").css("display");
				if(none == "none"){
					$(".titlelcb").addClass("titlelcbs")
				}else{
					$(".titlelcb").removeClass("titlelcbs")
				}
				$(".list").css({"display":"block"});
				$(".wallet").css({"display":"none"});
				$(".slide").css({"display":"none"});
			})

			$(".list-con .list-boc").eq(0).css({"display":"block"});
			//选项卡切换
			$(".slide-ul li").click(function(){
				var index = $(this).index();
				$(this).addClass("actives").siblings().removeClass("actives");
				$(".titlelcb").text($(this).text());				
				$(".list-con .list-boc").eq(index).css({"display":"block"}).siblings().css({"display":"none"});
			})

			/*$(".list-con .list-boc").eq(0).css({"display":"block"})
            $(".list a").click(function(){
              var index = $(this).parents().index();
              //console.log(index);
              $(this).addClass("actives").parent("li").siblings().find("a").removeClass("actives");
             $(".list-con .list-boc").eq(index).css({"display":"block"}).siblings().css({"display":"none"});
            });*/

      //理茶宝数据加载
        var page = 1;
	    var num = 1;
	    var time1 ='';
	    //var time2 ='' ;
	    var count='';
    	var count_page='';
	    getdata(page, num,time1);
        function getdata(page, num, time1) {
        $.ajax({
            type: "POST",
            url: "<?php echo url('Integral/index'); ?>",
            data: {
                page: page,
                type: num,
                time1: time1,
            },
            dataType: "json",
            success: function (data) {
                var data = JSON.parse(data);
                console.log(data)
                count = data.count
                count_page = Math.ceil(count / 6)
                var html = ""
                for (var i = 0; i < data.list.length; i++) {
                    //console.log(data)
                    if(data.list[i].tea_inte != 0){
                        html += '<tr>';
                        html += '<td><p>茶券</p><p>'+getLocalTime(data.list[i].addtime)+'</p></td>';
                        html += '<td>'+data.list[i].introduce+'</td>';
                        html += '<td class="yellow">'+data.list[i].tea_inte+'</td>';
                        html += '</tr>';
                    }
                    if(data.list[i].tea_ponit_inte != 0){
                        html += '<tr>';
                        html += '<td><p>茶点</p><p>'+getLocalTime(data.list[i].addtime)+'</p></td>';
                        html += '<td>'+data.list[i].introduce+'</td>';
                        html += '<td class="yellow">'+data.list[i].tea_ponit_inte+'</td>';
                        html += '</tr>';
                    }

                }
                $("#table1").html(html);
                $("#pagination1").pagination("setPage", page, count_page);

                var green = $("td.green").text();
                //console.log(green.charAt(0));
                if(green.charAt(0) == "+"){
                    $("td.green").css({"color":"green"})
                }else if(green.charAt(0) == "-"){
                    $("td.green").css({"color":"red"})
                }

            }
        });
    }




       //钱包数据加载
        var page1 = 1;
	    var num1 = 1;
	    var time2 ='';
	    //var time2 ='' ;
	    var count1='';
    	var count_page1='';
	    getdata1(page1, num1,time2);
        function getdata1(page1, num1, time2) {
        $.ajax({
            type: "POST",
            url: "<?php echo url('appmobile/Integral/wallet_info'); ?>",
            data: {
                page: page1,
                type: num1,
                time1: time2,
            },
            dataType: "json",
            success: function (data) {
                var data = JSON.parse(data);
                //console.log(data)
                count = data.count
                count_page = Math.ceil(count / 6)
                var html = ""
                for (var i = 0; i < data.list.length; i++) {
                    //console.log(data)
                    html += '<tr>';
                    html += '<td>'+getLocalTime(data.list[i].addtime)+'</td>';
                    html += '<td>'+data.list[i].introduce+'</td>';
                    html += '<td class="yellow">'+data.list[i].have_inte+'</td>';
                    html += '</tr>';

                }
                $("#table2").html(html);
                $("#pagination2").pagination("setPage", page1, count_page1);
                var green = $("td.green").text();
                //console.log(green.charAt(0));
                if(green.charAt(0) == "+"){
                    $("td.green").css({"color":"green"})
                }else if(green.charAt(0) == "-"){
                    $("td.green").css({"color":"red"})
                }

            }
        });
    }



         //选择需要显示的时间
         var myDate = new Date();
         var getTime = myDate.getFullYear();
         var getMonth = myDate.getMonth() + 1;
         var getDay = myDate.getDate();
         var totalTime = getTime + '-' + getMonth + '-' + getDay;
        $("#input1").val(totalTime);
        $("#input2").val(totalTime);
        $("#input1").shijian({
            y: +100,//当前年份+100
            startYear: 2016,
            Hour: false,//是否显示小时
            Minute: false,//是否显分钟
            Seconds: false,//是否显秒，
            showNowTime: true,
            okfun: function () {
            	time1 = $("#input1").val();
                getdata(page, num,time1);
            }
        }) 
        $("#input2").shijian({
            y: +100,//当前年份+100
            startYear: 2016,
            Hour: false,//是否显示小时
            Minute: false,//是否显分钟
            Seconds: false,//是否显秒，
            showNowTime: true,
            okfun: function () {
            	time2 = $("#input2").val();
                getdata1(page1, num1,time2);
            }
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
  
  	</script>
</body>
</html>