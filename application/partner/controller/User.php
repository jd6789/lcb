<?php
/**
 * Created by PhpStorm.
 * User: jacky-fu
 * Date: 2018/5/4
 * Time: 18:52
 */
namespace app\partner\controller;
use think\Cookie;
use think\Request;
use app\partner\model\Gudong;
use think\Session;
use think\Db;
use think\Upload;
use app\tmvip\controller\Api;
use think\cache;
class User extends Co{
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
    //判断个人中心我的信息页面是否显示实名认证
    public function is_realname(){
        if(request()->isAjax()) {
            $user_id = session('user_id');
            $is_real = Db::connect(config('db_config2'))->name('users_real')->where("user_id", $user_id)->find();
            if ($is_real) {
                return json(array('status' => 1, 'data' => $is_real));
            } else {
                $parent_id = Db::connect(config('db_config2'))->name('users')->where("user_id", $user_id)->value('parent_id');
                if ($parent_id == 0) {
                    return json(array('parent_id' => 0));
                } else {
                    return json(array('parent_id' => $parent_id));
                }
            }
        }
    }

    //实名认证
    public function realname()
    {
        if(request()->isPost()){
            $user_id = session('user_id');
            $bank_name = trim(input('post.bank_name'));   //开户行
            $bank = trim(input('post.bank'));    //银行卡号
            //支付密码
            $pay_pwd = trim(input('post.pay_pwd1'));
            $face_img = $this->request->file('zhengmian');
            $back_img = $this->request->file('fanmian');
            if($face_img){
                $info1 = $face_img->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info1){
                    $face1 = $info1->getSaveName();

                    $face2 = "local.lcb.com/Lcb/tea_treasure/public/uploads/".$face1;
                }else{
                    // 上传失败获取错误信息
                    echo $face_img->getError();
                }
            }
            if($back_img){
                $info2 = $back_img->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info2){
                    $back1 = $info2->getSaveName();
                    $back2 = "local.lcb.com/Lcb/tea_treasure/public/uploads/".$back1;
                }else{
                    // 上传失败获取错误信息
                    echo $back_img->getError();
                }
            }
            $dir='http://'. $_SERVER['HTTP_HOST'].'/public/uploads';
            $face = $dir . $face1;
            $back = $dir . $back1;
            $real_name = trim(input('post.real_name'));
            $sex = trim(input('post.sex'));
            $idcard = trim(input('post.idcard'));
            //判断有没有
            $real_user = Db::connect(config('db_config2'))->name("users_real")->where('user_id',$user_id)->find();
            if($real_user){
                $res1 = Db::connect(config('db_config2'))->name("users_real")->where('user_id',$user_id)
                    ->update([
                        //'user_id'=>$user_id,
                        'real_name' => $real_name,
                        'self_num' => $idcard,
                        'bank_name'=>$bank_name,
                        'bank_card'=>$bank,
                        'front_of_id_card'=>$face,
                        'reverse_of_id_card'=>$back,
                    ]);
            }else{
                //插入到验证表
                $res1 = Db::connect(config('db_config2'))->name("users_real")
                    ->insert([
                        'user_id'=>$user_id,
                        'real_name' => $real_name,
                        'self_num' => $idcard,
                        'bank_name'=>$bank_name,
                        'bank_card'=>$bank,
                        'front_of_id_card'=>$face,
                        'reverse_of_id_card'=>$back,
                    ]);
            }

            //插入到收货地址表
            //$user_info = Db::connect(config('db_config2'))->name("users")->where("user_id=$user_id")->find();

//            $tel = $user_info['mobile_phone'];  //用户电话
//            $user_address = input('post.user_address');
//            $arr = explode('-',$user_address);
//            $province = $arr[0];   //省
//            $city = $arr[1];    //市
//            $area = $arr[2];      //县 区
//            $district = trim(input('post.district'));   //详细地址
//            $address = array(
//                'user_id'=>$user_id,
//                'country'=>1,
//                'province'=>$province,
//                'city'=>$city,
//                'district'=>$area,
//                'address'=>$district,
//                'tel'=>$tel,
//                'mobile'=>$tel
//            );
//            $res5 = Db::connect(config('db_config2'))->name("user_address")->insert($address);
            if($res1){
                $sex = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->setField('sex',$sex);
            }
            $data['real_name'] = $real_name;
            $data['sex'] = $sex;
            $data['idcard'] = $idcard;
            $data['face_img'] = $face;
            $data['back_img'] = $back;
            $data['is_pass'] = 0;
            $data['is_first'] = 1;
            $data['addtime'] = time();
            $data['user_id'] = $user_id;
            //更新理茶宝用户表的二级安全密码
//            $pay_pwd = md5(md5($pay_pwd).$user_info['ec_salt']);   //支付密码
//            Db::table('tea_user')->where('user_id',$user_id)->setField('pay_pwd',$pay_pwd);   //修改支付密码
            $user = Db::table('tea_real_name')->where("user_id",$user_id)->find();
            if(!$user){
                $cc = Db::table('tea_real_name')->insert($data);
            }else{
                $cc = Db::table('tea_real_name')->where("user_id",$user_id)->update($data);
            }

            $p_user = trim(input('post.p_user'));  //推荐人用户名
            if($p_user){
                $p_info = Db::connect(config('db_config2'))->name("users")->where("user_name='$p_user'")->find();
                //return json(array('data'=>$p_info));
                if(!$p_info){
                    return json(array('status'=>'4'));
                }else{
                    $parent_id = $p_info['user_id'];
                    $data2 = Db::table('tea_user')->where("user_id",$parent_id)->find();
                    if($data2['wait']==0){
                        return json(array('status'=>5,'data'=>'被冻结'));
                    }else{
                        //推荐人是否购买产品
                        $info = Db::table('tea_user_recharge')->where('pay_status = 1 and user_id='.$data2['user_id'])->order('id desc')->limit(1)->find();
                        if(!$info){
                            return json(array('status'=>6,'data'=>'未激活'));
                        }else{
                            $parent_id = $p_info['user_id'];
                        }
                    }
                }
            }

            if($cc){

                $this->success('验证通过',url('index/custom_info'));
            }else{
                $this->error('服务器维护中');
            }
        }

    }
    //图片
    public function upload()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('zhengmian');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }

    public function upFile($img, $dir)
    {
        //dump($_FILES[$img]);
        if (!isset($_FILES[$img]) || $_FILES[$img]['error'] != 0 || $_FILES[$img]['size'] > 2097152) {
            return false;
        }
        $upload = new \Think\Upload();
        $upload->rootPath = $dir;
        $info = $upload->uploadOne($_FILES[$img]);
        if (!$info) {
            $this->error = $upload->getError();
        }
        //上传后的图片地址
        $img = $dir . $info['savepath'] . $info['savename'];
        return $img;
    }
}