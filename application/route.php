<?php

use think\Route;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//];


Route::group('tmvip',function(){
    //------------------Index-------------------------------------
    //Route::rule('mindex','tmvip/Admin/main_index','GET');
    Route::any("index","tmvip/Index/index",['method' => 'get|post']);  //登录
    Route::rule('c','tmvip/Index/c','GET');
    Route::rule('cc','tmvip/Index/cc','GET');


    //------------------User--------------------------------------
    //Route::rule('users','tmvip/User/user_detail','GET');
    Route::any('uindex','tmvip/User/index',['method' => 'get']);//会员首页
    Route::any('user','tmvip/User/user_manage',['method' => 'get|post']);
    Route::rule('uuse/:user_id','tmvip/User/user_use','GET');
    Route::rule('uwait/:user_id','tmvip/User/user_wait','GET');
    Route::rule('urestl/:user_id','tmvip/User/reset_login_pwd','GET');
    Route::rule('urestp/:user_id','tmvip/User/reset_pay_pwd','GET');
    Route::rule('uedit/:user_id','tmvip/User/edit_user_info','GET');
    Route::rule('urdel','tmvip/User/user_del','GET');
    Route::rule('usedit','tmvip/User/save_user_info','POST');
    Route::rule('uoinfo/:user_id','tmvip/User/one_user_info','GET');
    Route::any('udetail','tmvip/User/user_detail',['method' => 'get|post']);
    Route::any('uincreasewal','tmvip/User/increase_wallet',['method' => 'get|post']);
    Route::any('uwithdrawal','tmvip/User/withdrawal_wallet',['method' => 'get|post']);
    Route::any('uintechange','tmvip/User/integral_change',['method' => 'get|post']);
    Route::any('uwallchange','tmvip/User/wallet_change',['method' => 'get|post']);
    Route::any('uintechart','tmvip/User/integral_chart',['method' => 'get|post']);
    Route::any('ulcbchart','tmvip/User/lcb_quantity_chart',['method' => 'get|post']);
    Route::any('uwalchart','tmvip/User/wallet_chart',['method' => 'get|post']);
    Route::rule('userteam/:user_id','tmvip/User/user_team','GET');
    Route::rule('cv','tmvip/User/cv','GET');
    Route::any('uss','tmvip/User/user_rech_info',['method' => 'get|post']);
    Route::any('uic','tmvip/User/user_inte_change',['method' => 'get|post']);
    Route::any('uica','tmvip/User/check_user',['method' => 'get|post']);
    Route::any('uall','tmvip/User/user_all',['method' => 'get|post']);



    //-------------------System-----------------------------------
    Route::rule("rindex","tmvip/System/index",'GET');
    Route::any("radd","tmvip/System/add_recharge",['method' => 'get|post']);
    Route::rule("recharge","tmvip/System/recharge_info",'GET');
    Route::rule("redit/:recharge_id","tmvip/System/recharge_edit",'get');
    Route::rule('rsedit','tmvip/System/save_recharge_edit','POST');
    Route::rule("rdel/:recharge_id","tmvip/System/recharge_del",'get');
    Route::rule("rate","tmvip/System/teapoint",'get');
    Route::rule("ratedit","tmvip/System/teapoint_edit",'get');
    Route::rule("delDirAndFile","tmvip/System/delDirAndFile",'get');
    Route::rule("ratesedit","tmvip/System/save_teapoint_edit",'post');
    Route::any("rank","tmvip/System/add_rank",['method' => 'get|post']);
    Route::any("rechcart","tmvip/System/recharge_cart",['method' => 'get|post']);
    Route::any("userrech","tmvip/System/user_recharge",['method' => 'get|post']);
    Route::any("walletonline","tmvip/System/wallet_online_pay",['method' => 'get|post']);


    //------------------Admin-----------------------------------
    //Route::any("index","tmvip/Index/index",['method' => 'get|post']);  //登录
    //Route::any("gc","tmvip/Admin/admin_login",['method' => 'get|post']);
    Route::rule('mindex','tmvip/Admin/main_index','GET');   //后台首页
    Route::rule('aindex','tmvip/Admin/index','GET');   //管理员首页
    Route::rule('ashow','tmvip/Admin/admin_show','GET');   //管理员展示
    Route::rule("out","tmvip/Admin/out_login",'GET');
    Route::any("admin","tmvip/Admin/add_admin",['method' => 'get|post']);
    Route::rule('areset/:user_id','tmvip/Admin/reset_pwd','GET');
    Route::rule('adel/:user_id','tmvip/Admin/user_del','GET');
    Route::rule('aedit/:user_id','tmvip/Admin/user_edit','GET');
    Route::rule('asuedit','tmvip/Admin/save_user_edit','POST');
    //-------------------------权限-
    Route::any("arule","tmvip/Admin/add_rule",['method' => 'get|post']);
    Route::rule("aruleshow","tmvip/Admin/rule_show",'GET');
    Route::rule('aruleedit/:id','tmvip/Admin/edit_rule','GET');
    Route::rule('asruedit','tmvip/Admin/save_edit_rule','POST');
    Route::rule('aruledel/:id','tmvip/Admin/del_rule','GET');
    //-------------------------角色
    Route::any('arole','tmvip/Admin/add_role',['method' => 'get|post']);
    Route::rule("aroleshow","tmvip/Admin/role_show",'GET');
    Route::rule('aroledit/:id','tmvip/Admin/edit_role','GET');
    Route::rule('asroedit','tmvip/Admin/save_edit_role','POST');
    Route::rule('aroledel/:id','tmvip/Admin/del_role','GET');

    //-------------------Report---------------------------------------------------
    Route::any("consumption","tmvip/Report/consumption",['method' => 'get|post']);
    Route::any("lchbao","tmvip/Report/lichabao",['method' => 'get|post']);
    Route::any("release","tmvip/Report/release_tongji",['method' => 'get|post']);
    Route::any("recommend","tmvip/Report/recommend_reward",['method' => 'get|post']);
    Route::any("performance","tmvip/Report/performance_reward",['method' => 'get|post']);
    Route::any("integral_log","tmvip/Report/integral_log",['method' => 'get|post']);
    Route::any("uout","tmvip/Report/user_out",['method' => 'get|post']);
    Route::any("uagain","tmvip/Report/vip_again",['method' => 'get|post']);
    Route::any("integral_log_excel","tmvip/Report/integral_log_excel",['method' => 'get|post']);
    //------------------api
    Route::rule('cvs','tmvip/Api/cv','GET');

    //-----------------股东
    Route::rule("rechargeindex","tmvip/Recharge/index",'GET');
    Route::rule('recharges','tmvip/Recharge/userrecharge','get|post');
    // Route::rule('rechargeindex','tmvip/Recharge/index','get|post');
    Route::rule('recharge_infos','tmvip/Recharge/recharge_info','get|post');
    Route::rule('integral_infos','tmvip/Recharge/userintegral','get|post');
    Route::rule('updateRec','tmvip/Recharge/updateRec','get|post');
    Route::rule('active_tea_treasure','tmvip/Recharge/active_tea_treasure','get|post');
    Route::rule('handinte','tmvip/Recharge/handinte','get|post');
    Route::rule('addrecharge','tmvip/Recharge/add_recharge','get|post');
    Route::rule('recharge_del','tmvip/Recharge/recharge_del','get|post');
    Route::rule('new_recharge_edit','tmvip/recharge/recharge_edit','get|post');
    Route::rule('gdedit','tmvip/recharge/save_recharge_edit','POST');
    Route::rule('oneRechargeLog','tmvip/recharge/oneRechargeLog','POST');
    Route::rule('timeGiveInte','tmvip/recharge/timeGiveInte','POST');
    Route::rule('fenghonglog','tmvip/recharge/fenghonglog','POST');


    //-----------------门店管理
    Route::rule('stroes','tmvip/store/index','get|post');
    Route::rule('storeindex','tmvip/store/storeIndex','get|post');
    Route::rule('handFenhong','tmvip/store/handFenhong','get|post');
    Route::rule('storedown','tmvip/store/storedown','get|post');
    Route::rule('allceoindex','tmvip/store/allceoindex','get|post');

    //寄售管理
    Route::rule('order','tmvip/order/index','get|post');
    Route::rule('orders','tmvip/order/order','get|post');
    Route::rule('overorder','tmvip/order/overorder','get|post');
    Route::rule('postal','tmvip/order/postal','get|post');  //提现的页面显示
    Route::rule('rejected','tmvip/order/rejected','get|post');  //驳回提现
    Route::rule('overpost','tmvip/order/over','get|post');  //完成提现

    Route::rule('orders_s','tmvip/order/order_new','get|post');
    Route::rule('postalexcel','tmvip/order/postal_excel','get|post');  //提现的页面显示
    Route::rule('orderexcel','tmvip/order/order_excel','get|post');  //提现的页面显示

    //后台首页登录
    Route::rule('admin_login','tmvip/login/login','get|post');




    //-------------------------------------------------------------------
//    Route::group("Index",function(){
//        Route::rule('indexs','tmvip/Index/index','GET');
//    });
});
//    Route::group("partner",function(){
//        //Route::rule('index','partner/Index/index','GET');  //股东首页
//        //Route::rule('login','partner/shareholder/login','GET');  //股东首页
//
//    });

