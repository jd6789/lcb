<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"/www/wwwroot/vip.guochamall.com/public/../application/newapp/view/share/test.html";i:1539941497;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>消费卡领取信息</title>
    <style>
        a {text-decoration: none;-webkit-tap-highlight-color: rgba(0,0,0,0);}
        .btn-submit {font-size: 1.6rem;color: #fff;border: 0;text-align: center;padding: 1.2rem 0;width: 100%;background: #EC5151;}
        /*红包*/
        .user-coupon{margin-bottom:4.6rem; margin-top:5rem;}
        .user-coupon-add .btn-submit{width:100%; display: block;}
        .coupon-add-text .text-all input{padding:0.5rem}
        .coupon-add-text .text-all input::-webkit-input-placeholder{color:#ccc}
        .coupon-add-text .text-all .is-null{color:#ffb7b7}
        .user-coupon .big-remark-all .dis-box .padding-all{padding-right:1.3rem;}
        .user-coupont-box{border-radius: 4px;}
        .user-coupont-box .coupont-right{border-left: 1px dashed #ccc;position:relative; margin: .4rem;}
        .user-coupont-box .coupont-left{padding: 1.1rem 1rem;text-align: center;}
        .user-coupont-box .coupont-left span{font-size:2.5rem;font-weight: 700;padding-top: 2rem;display: block;color:#ec5151;}
        .coupont-cont .coupont-cont-title{color:#ec5151;font-size:1.3rem;}
        .user-coupont-box .coupont-right a span{background:#ec5151;font-size:1rem;border-radius: 5rem;padding:.4rem 1.2rem;color:#fff;margin-top:2.2rem;text-align: center;display: block;}
        .coupont-yuan{width:0.6rem;height:0.6rem;border-radius: 100%;background: #f3f3f3;position:absolute;left:0;left: -.35rem; }
        .cou-top{top: -.3rem;}
        .cou-bottom{bottom:-.3rem;}
        .coupont-middle h4 {margin: 0.5rem 0;}
        .user-coupont-box .coupont-right a span.is_left {background:#aaa;}

        /*悬浮btn*/
        .filter-btn{position: fixed; width:100%; left:0; right:0; bottom:0; box-sizing: border-box; z-index: 5; background:#fafafc;     box-shadow:1px 0px 5px rgba(100,100,100,0.2);}
        .filter-btn .filter-btn-a{padding:0 1.3rem;  display: block; text-align: center; position: relative;}
        .filter-btn .filter-btn-a i{font-size:2.2rem; color:#777; display:block; padding-top: .2rem;}
        .filter-btn .filter-btn-flow sup{position:absolute; top:.1rem; right:.9rem; height:1.4rem; min-width:1.4rem; line-height: 1.4rem;  padding:0 .2rem; box-sizing:border-box; font-size:1.2rem; color:#fff; border-radius: .7rem;}
        .filter-btn .filter-btn-a i.icon-gouwuche em{position:absolute; top: 0; right:0; display: block; min-width: 1.3rem; height:1.3rem; line-height:1.3rem; padding:.1rem .3rem; font-size:1.1rem; border-radius: .8rem; box-sizing: border-box; color:#fff;}
        .filter-btn .filter-btn-a em{display:block; font-size:1.1rem; margin-top:-.1rem; color:#666;}
        .filter-btn a.box-flex:last-of-type{margin-right:0; margin-left:0;}

    </style>
</head>

<body bgcolor="#f3f3f3">

<img src="/cash_user_card/<?php echo $cashcard; ?>" width="100%">

<div class="user-coupon-add  filter-btn">
    <a href="javascript:void(0)" type="button" class="btn-submit" style="font-size: 2.8rem; padding: 2rem;">长按图片保存到手机</a>
</div>

</body>
</html>