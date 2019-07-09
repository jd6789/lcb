<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/5/4
 * Time: 10:25
 */
namespace app\partner\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Cookie;
use app\api\model\Huiyuan;
class Login extends Controller{
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
    //显示切换登录的页面
    public function login(){
        cookie::set('admin',null);
        session::set('user', null);
        session::set('user_id', null);
        session::set('phone',null);
        return $this->fetch();
    }
    //切换登录的业务逻辑
    public function login_ne(){
        $username = trim(input('post.username'));
        $password = trim(input('post.password'));
        //判断用户名是否存在
        $userobj = new Huiyuan();
        //通过访问淘米平台数据库查看用户名是否存在
        $user = Db::connect(config('db_config2'))->name("users")->where('user_name',$username)->find();
//        if($user && intval($user['user_rank'])!=9)  return json(array("data" => "不是股东无法登陆", 'status' => 9));   //判断是不是股东
        if(!$user){
            return json(array("data"=>"用户不存在",'status'=>2));
        }else{
            //是否冻结
            $user_id= $user['user_id'];
            $usernifo = Db::table('tea_user')->where('user_id',$user_id)->find();
            if($usernifo){
                if($usernifo['wait'] == 0){
                    return json(array("data"=>"该账户被冻结",'status'=>3));
                }
            }else{
                //将这个会员插入到数据库里面去
                Db::table('tea_user')->insert(['user_id'=>$user_id,'is_ceo'=>1]);
            }

            //用户名存在  判断密码是否正确
            $password = md5(md5($password).$user['tm_salt']);
            $res = $userobj->pwd_exit($username,$password);
            if($res){
                session('user_shop','');
                $list['username'] = $username;
                $list['password'] = $password;
                session('user_shop',$list);
                return json(array("data"=>"登陆成功",'status'=>1));
            }else{
                return json(array("data"=>"密码错误",'status'=>0));
            }
        }
    }

}