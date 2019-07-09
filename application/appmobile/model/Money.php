<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/4/21
 * Time: 10:21
 */
namespace app\appmobile\model;
use think\Model;
use think\Db;
class Money extends Model{
    //充值钱包
    public function buyMoneys($user_id,$moneys){
        $a = rand(100, 999);
        $b = rand(100, 999);
        $res = "$a" . time() . "$b" . '168';
        $model=new Money(
            [
                'montys'=>$moneys,
                'user_ids'     =>$user_id,
                'money_addtime' =>time(),
                'money_num'=>$res,
            ]
        );
        $model->save();
        return $model->money_id;
    }
}