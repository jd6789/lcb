var url="http://local.jy2.com/";
var open_id="wx7797056e9ca6c4f7"
$(function () {
    var head_height=$(".head").height();
    $("body>.head").next().css("margin-top",head_height-1)
    $(window).scroll(function(){
        var winPos = $(document).scrollTop();

        if(winPos>=head_height){
            //$("body>.head").css({"position":"fixed","top":'0',"z-index":"999","-webkit-transform":"translateZ(0)"});

        }else{
            //$("body>.head").css({"position":"static"});

        }


    });
})
function getLocalTime(nS) {
    var myYear= new Date(parseInt(nS)*1000).getFullYear()
    var myMonth= new Date(parseInt(nS)*1000).getMonth()+1
    var myDay= new Date(parseInt(nS)*1000).getDate()
    var   hour=new Date(parseInt(nS)*1000).getHours();
    var   minute=new Date(parseInt(nS)*1000).getMinutes();
    var   second=new Date(parseInt(nS)*1000).getSeconds();
    if(myMonth<10){
        myMonth='0'+myMonth
    }
    if(myDay<10){
        myDay='0'+myDay
    }
    if(second<10){
        second='0'+second
    }
    if(minute<10){
        minute='0'+minute
    }
    var showDate = myYear+"-"+myMonth+'-'+myDay+' '+hour+':'+minute+':'+second
    return showDate
}