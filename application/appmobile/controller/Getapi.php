<?php
/**
 * Created by PhpStorm.
 * user: jieyu
 * Date: 2018/4/19
 * Time: 10:08
 */
namespace app\appmobile\controller;
use think\Controller;
use think\Cookie;
use think\Request;
use think\Session;
use think\Db;
class Getapi extends Controller
{
    //通过用户名查询信息
    public function c_un1(){
        //$url = "www.tmvip.cn/api.php?method=taom.user.list.get&app_key=1807CC58-3D08-465B-B908-DF7F088E3218&page=1&page_size=15&format=json";

        $user_name = trim(input('post.username'));
//        $user_name = 'jacky01';
        $url = "www.tmvip.cn/api.php?method=taom.user.list.get&app_key=1807CC58-3D08-465B-B908-DF7F088E3218&page=1&page_size=15&user_name=".$user_name."&format=json";
        $data = $this->getHttp($url);
        dump($data);die;
//        $password = $data['info']['list'][0]['password'];
//        $sex = $data['info']['list'][0]['sex'];
//        $salt = $data['info']['list'][0]['tm_salt'];
//        $tel = $data['info']['list'][0]['mobile_phone'];
//        $data2['username'] = $user_name;
//        $data2['password'] = $password;
//        $data2['sex'] = $sex;
//        $data2['salt'] = $salt;
//        $data2['wait'] = 1;
//        $data2['tel'] = $tel;
//        //dump($data2);die;
//        $res = Db::table('tea_user')->insert($data2);
        if(count($data['info']['list'])){
            return json(1);
        }else{
            return json(0);
        }
    }
    public function c_un(){
        if(request()->isAjax()){
            $user_name = trim(input('post.username'));
            $url = "www.tmvip.cn/api.php?method=taom.user.list.get&app_key=1807CC58-3D08-465B-B908-DF7F088E3218&page=1&page_size=15&user_name=".$user_name."&format=json";
            $data = $this->getHttp($url);
            //该账号已注册过淘米会员
            if(count($data['info']['list'])){
                return json(array('status'=>1,'data'=>"您是淘米会员,可直接输入淘米账号登录"));
            }
            //未注册淘米会员
            $post_data['user_name'] = input('post.username');
            $post_data['password'] = input('post.password1');
            $post_data['mobile_phone'] = input('post.tel');

            $curl = "www.tmvip.cn/api.php?method=taom.user.insert.post&app_key=1807CC58-3D08-465B-B908-DF7F088E3218&data=".$post_data."&format=json";
            $res = $this->postHttp($curl,$data);
        }
    }

    public function c_mp(){
        //$url = "www.tmvip.cn/api.php?method=taom.user.list.get&app_key=1807CC58-3D08-465B-B908-DF7F088E3218&page=1&page_size=15&format=json";
        //$user_name = '灰太羊不吃草';
        $tel = trim(input('post.tel'));
        $tel = 17371675160;
        $url = "www.tmvip.cn/api.php?method=taom.user.list.get&app_key=1807CC58-3D08-465B-B908-DF7F088E3218&page=1&page_size=15&mobile_phone=".$tel."&format=json";
        $data = $this->getHttp($url);
        //dump($data);die;
        $username = $data['info']['list'][0]['user_name'];
        $password = $data['info']['list'][0]['password'];
        $sex = $data['info']['list'][0]['sex'];
        $salt = $data['info']['list'][0]['tm_salt'];
        //$tel = $data['info']['list'][0]['mobile_phone'];
        $data2['username'] = $username;
        $data2['password'] = $password;
        $data2['sex'] = $sex;
        $data2['salt'] = $salt;
        $data2['wait'] = 1;
        $data2['tel'] = $tel;
        //dump($data);die;
        $res = Db::table('tea_user')->insert($data2);
        if($data){
            return json(0);
        }
    }

//    public function add_user()
//    {
//        $url = "www.tmvip.cn/api.php?method=taom.user.insert.post&app_key=1807CC58-3D08-465B-B908-DF7F088E3218&page=1&page_size=15&format=json";
//    }

    //get请求
    public function getHttp($url){
        $ch=curl_init();
        //设置传输地址
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置以文件流形式输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //接收返回数据
        $data=curl_exec($ch);
        curl_close($ch);
        $jsonInfo=json_decode($data,true);
        return $jsonInfo;
    }

    //将注册的会员信息同步到淘米
    public function insertUser()
    {
        $post_data['user_name'] = 'yinhang001';
        $post_data['password'] = '123456';
        $post_data['mobile_phone'] = '13697352974';
        $curl = "www.tmvip.cn/api.php?method=taom.user.insert.post&app_key=1807CC58-3D08-465B-B908-DF7F088E3218&data=".$post_data."&format=json";
        $data = $this->postHttp($curl,$post_data);
        dump($data);
    }

    //post请求
    public function postHttp($curl,$post_data)
    {
        //初始化
        $ch=curl_init();
        //设置传输地址
        curl_setopt($ch, CURLOPT_URL, $curl);
        //设置以文件流形式输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($ch, CURLOPT_POST, 1);
        //设置post参数
//        $post_data = array(
//            "username"=>'yinhang',
//            'password'=>'123456',
//        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        //接收返回数据(执行命令)
        $data=curl_exec($ch);
        //关闭url请求
        curl_close($ch);
        //显示获得的数据
        print_r($data);
        $jsonInfo=json_decode($data,true);
        return $jsonInfo;
    }
}