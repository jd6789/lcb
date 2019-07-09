<?php
/**
 * Created by PhpStorm.
 * User: jacky-fu
 * Date: 2018/4/9
 * Time: 11:36
 */

namespace app\tmvip\controller;

use think\Request;
use think\Db;

class Store extends Common
{
    //显示首页
    public function index()
    {
        $menu = $this->user['menus'];
        //获取当前url的权限id
        $request = Request::instance();
        $url_id = $this->url_id($request->module(), $request->controller(), $request->action());
        $id = intval($url_id['id']);
        return view("index", ['menu' => $menu, 'url' => $id]);
    }

    //显示所有的门店
    public function storeIndex()
    {
        $data = $userinfo = Db::connect(config('db_config2'))->name("offline_store")->select();
        return view("storeIndex", ['data' => $data]);
    }

    //门店手动分红
    public function handFenhong()
    {
        $id=intval(input('post.int_id'));
        $moneys=floatval(input('post.moneys'));
        //查找出门店所有的股东
        $all_ceo = Db::table('tea_ceo_store')->where('store_id', $id)->select();
        //return json($all_ceo);
        foreach ($all_ceo as $v){
            $this->ceoBonus($v['user_id'],$v['store_id'],$moneys*(floatval($v['gufen'])/100));
        }
        return json(1);
    }

    //给每个股东分红
    private function ceoBonus($user_id, $store_id, $money)
    {
        $data = array(
            'user_id' => $user_id,
            'store_id' => $store_id,
            'bonus_money' => $money,
            //'addtime'=>time()
        );
        return Db::table('tea_ceo_bonus')->insert($data);
    }

    //显示门店下所有的股东
    public function storedown()
    {
        $store_id = intval(input('post.id'));
        $data = Db::table('tea_ceo_store')
            ->field('user_id,gufen')
            ->where('store_id', $store_id)
            ->select();
        if(!$data){
            return 0;
        }
        foreach ($data as $k => $v) {
            $data[$k]['name'] = Db::connect(config('db_config2'))->name("users")->field('user_name')->where('user_id=' . intval($v['user_id']))->find();
            $data[$k]['all'] = Db::table('tea_ceo_bonus')->where('user_id', intval($v['user_id']))->where('store_id', $store_id)->SUM('bonus_money');
        }
        return json($data);
    }

    //显示所有股东对应的门店的股权
    public function allceoindex(){
        $data=Db::table('tea_ceo_store')
            ->order('store_id desc')
            ->select();
        foreach ( $data as $k => $v){
            $data[$k]['stores_name']=Db::connect(config('db_config2'))->name("offline_store")->field('stores_name')->where('id='.intval($v['store_id']))->find();
            $data[$k]['user_name']=Db::connect(config('db_config2'))->name("users")->field('user_name')->where('user_id=' . intval($v['user_id']))->find();
        }
        return view("allceoindex", ['data' => $data]);
    }
}