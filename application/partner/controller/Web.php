<?php

/**

 * Created by PhpStorm.

 * User: jieyu

 * Date: 2018/4/28

 * Time: 23:15

 */

namespace app\partner\controller;



use think\Controller;

use think\Db;

use think\Cookie;

class Web extends Controller {
    /**
     *                             _ooOoo_
     *                            o8888888o
     *                            88" . "88
     *                            (| -_- |)
     *                            O\  =  /O
     *                         ____/`---'\____
     *                       .'  \\|     |//  `.
     *                      /  \\|||  :  |||//  \
     *                     /  _||||| -:- |||||-  \
     *                     |   | \\\  -  /// |   |
     *                     | \_|  ''\---/''  |   |
     *                     \  .-\__  `-`  ___/-. /
     *                   ___`. .'  /--.--\  `. . __
     *                ."" '<  `.___\_<|>_/___.'  >'"".
     *               | | :  `- \`.;`\ _ /`;.`/ - ` : | |
     *               \  \ `-.   \_ __\ /__ _/   .-` /  /
     *          ======`-.____`-.___\_____/___.-`____.-'======
     *                             `=---='
     *          ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
     *                     佛祖保佑        永无BUG
     *            佛曰:
     *                   写字楼里写字间，写字间里程序员；
     *                   程序人员写程序，又拿程序换酒钱。
     *                   酒醒只在网上坐，酒醉还来网下眠；
     *                   酒醉酒醒日复日，网上网下年复年。
     *                   但愿老死电脑间，不愿鞠躬老板前；
     *                   奔驰宝马贵者趣，公交自行程序员。
     *                   别人笑我忒疯癫，我笑自己命太贱；
     *                   不见满街漂亮妹，哪个归得程序员？
     */


    public function user_wx(){



        $data = input('get.');
        $user_info = Db::connect(config('db_config2'))->name("users")->where('user_id',$data['user_id'])->find();
        $user_data=Db::table('tea_user')->where('user_id',$data['user_id'])->find();
        //判断是否是股东权益的
//        if(intval($user_data['is_ceo'])==0){
//            $this->redirect('index/index/index');
//        }

        if(!$user_data){

            Db::table('tea_user')->insert(['user_id'=>$data['user_id'],'is_ceo'=>1]);

            session("user_id",$data['user_id']);

            session("user",$user_info);

            session('unionid',$data['unionid']);

            session('wx_data',$data);

            Cookie::set("user",$user_info);

            //dump(session('user_id'));

            //dump(session('unionid'));die;

            $this->redirect('index/index');

        }else{

            session("user_id",$data['user_id']);

            session("user",$user_info);

            Cookie::set("user",$user_info);

            session('unionid',$data['unionid']);

            session('wx_data',$data);

            //dump(session('user_id'));

            //dump(session('wx_data'));die;

            $this->redirect('index/index');

        }

//        session("user_id",$data['user_id']);

//        session("user",$user_info);

//        Cookie::set("user",$user_info);

        //$this->redirect('user/index');

    }

}