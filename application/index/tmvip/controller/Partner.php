<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/5/4
 * Time: 10:25
 */
namespace  app\tmvip\controller;
use think\Db;
use think\Request;
class Partner extends Common {
    //显示上边的栏
    public function index(){
        $menu = $this->user['menus'];
        //获取当前url的权限id
        $request = Request::instance();
        $url_id = $this->url_id($request->module(),$request->controller(),$request->action());
        $id = intval($url_id['id']);
        return view("index",['menu'=>$menu,'url'=>$id]);
    }
    public function test(){
        return view();
    }
    //理茶宝的项目设置
    public function recharge(){

    }
}