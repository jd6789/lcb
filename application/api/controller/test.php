<?php
/**
 * Created by PhpStorm.
 * user: admin
 * Date: 2017/10/19
 * Time: 13:27
 */

namespace Home\Controller;
use Think\Controller;

class UserController extends Controller
{
    public function _empty(){
        $this->display('./404.html');
    }

    //用户注册
    public function regist()
    {
        // 如果提交过来数据
        if (IS_AJAX) {
            $username = trim(I('post.username'));   // 用户名
            $password = trim(I('post.password'));   // 密码
            $p_user = trim(I('post.p_user'));       // 上级用户名
            $tel = trim(I('post.tel'));             // 手机号码
            $pay_pwd = trim(I('post.pay_pwd'));     // 二级密码
            $msginfo = trim(I('post.msginfo'));     // 验证码
            // 打开Session
            session_start();
            $code = $_SESSION['msg'];
            // 如果提交过来的验证码和Session中的验证码不一致
            if ($msginfo != $code) {
                // 返回错误
                $this->ajaxReturn(array('status' => 0, 'msg' => "验证码错误"));
            }
            session('msg',null);    // 重新定义Session中的msg为空

            // 如果提交过来的推荐人不为空
            if ($p_user) {
                // 查询推荐人信息
                $res = D('user')->where("username='$p_user'")->find();
                if($res['wait']==0){
                    $this->ajaxReturn(array('status' => 0, 'msg' => "此用户已被冻结"));
                }
                $parent_id = $res['id'];    // 将推荐人Id赋值给变量
            } else {
                // 否则将变量设置为0
                $parent_id = 0;
            }

            // 判断用户名数据库中是否有记录 ************
            $user_info = D('user')->where("username='$username'")->find();
            if (!empty($user_info)) {
                $this->ajaxReturn(array('status' => 0, 'msg' => "用户名重复"));
            }

            $salt = rand(100000, 999999);   // 盐
            $password = md5(md5($password) . $salt);    // 密码
            $pay_pwd = md5(md5($pay_pwd) . $salt);      // 二级密码
            // 组合数据
            $data = array(
                'username' => $username,
                'password' => $password,
                'tel' => $tel,
                'parent_id' => $parent_id,
                'salt' => $salt,
                'pay_pwd'=>$pay_pwd,
                'first_time' => time(),
            );
            // 增加到用户表
            $info = D('user')->add($data);
            // 如果增加用户失败
            if (!$info) {
                $this->ajaxReturn(array('status' => 0, 'msg' => "注册失败"));
            } else {

                $time = time();
                //将用户同步到官网
                $gc_user = M('user')->db(1,'DBConf1')->table('guocha_user')->where("username = '$username'")->find();
                if (!$gc_user) {
                    M('user')->db(1,'DBConf1')->table('guocha_user')->add(array(
                        'tel'       =>  $tel,
                        'username'  =>  $username,
                        'parent_id' =>  $parent_id,
                        'password'  =>  $password,
                        'salt'      =>  $salt,
                        'first_time'    =>  $time,
                    ));
                }
                $this->ajaxReturn(array('status' => 1, 'msg' =>  $address));
            }
        } else {
            $this->display();
        }
    }

    //用户名验证
    public function UserVer()
    {
        $username = trim($_POST['userName']);
        $data = D('user')->where("username='$username'")->find();
        if ($data) {
            echo 0;
        } else {
            echo 1;
        }
    }

    //推荐人验证
    public function pTelVer()
    {
        $p_user = trim(I('post.p_user'));
        if ($p_user) {
            $data = D('user')->where("username='$p_user'")->find();

            if (!$data) {
                echo 0;
            } else {
                if($data['wait']==0){
                    echo 0;
                }else{
                    //推荐人是否购买产品
                    $info = M('UserRecharge')->where('pay_status = 1 and user_id='.$data['id'])->order('id desc')->limit(1)->find();
                    if(!$info){
                        echo 0;
                    }else{
                        echo 1;
                    }
                }
            }
        }
    }

    // 发送验证码
    public function send_phones()
    {
        include_once('../Api/top/TopClient.php');
        session_start();
        $codes = rand(100000, 999999);
        $_SESSION['msg'] = $codes;
        //获取传输过来的手机号码
        $tel = $_POST['tel'];
        $sms = $_POST['sms'];
        if ($tel == "1") {
            $this->ajaxReturn(array('status' => 0, 'msg' => "手机号不能为空"));
        }
        $c = new \TopClient;
        $c->appkey = '23662994';
        $c->secretKey = '12c4693b91926a394e8ca913e132be01';
        // var_dump($c);
        include_once('../Api/top/request/AlibabaAliqinFcSmsNumSendRequest.php');
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("我的茶馆");
        $req->setSmsParam("{\"code\":\"$codes\",\"product\":\"国茶商城\"}");
        $req->setRecNum("$tel");//电话号码
        $req->setSmsTemplateCode("$sms");
        $resp = $c->execute($req);
        if ($resp->result->msg) {
            echo 1;
        } else {
            echo 0;
        }
    }

    //用户名登录
    public function login()
    {
        if (IS_AJAX) {
            $username = I('post.username');
            $password = I('post.password');
            //用户信息
            $c = D("user")->where("username='$username'")->find();
            if(!$c){
                $this->ajaxReturn(array('status' => 0, 'msg' => "用户不存在"));
            }
            if($c['wait']==0){
                $this->ajaxReturn(array('status' => 2, 'msg' => "no"));
            }
            //验证用户名是否正确
            $info = D('user')->checkUser($username, $password);
            if (!$info) {
                $this->ajaxReturn(array('status' => 0, 'msg' => D('user')->getError()));
            } else {
                $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
            }
        }
        if (IS_GET) {
            $this->display();
        }
    }

    //快捷登录
    public function login_phone()
    {
        $tel = I('post.tel');
        $mobileCode = I('post.mobileCode');
        session_start();
        $code = $_SESSION['msg'];
        if ($mobileCode != $code) {
            $this->ajaxReturn(array('status' => 0, 'msg' => "验证码错误"));
        }
        session('msg',null);
        //验证用户名是否正确
        $info = D('user')->checkTel($tel);
        if (!$info) {
            $this->ajaxReturn(array('status' => 0, 'msg' => D('user')->getError()));
        } else {

            $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
        }
    }

    public function verify()
    {
        //如果gd库也开启了但是就是不能正常的生成验证码可以使用ob_clean()实现
        // ob_clean();
        $config = array('length' => 4, 'fontSize' => 18);
        $verify = new \Think\Verify($config);
        $verify->entry();
    }

    //退出登录
    public function logout()
    {
        session('user', null);
        session('user_id', null);
        session('phone',null);
        $this->redirect('/index');
    }

    //用户填写推荐人
    public function addReferee()
    {
        $this->checkLogin();
        $user_id = session('user_id');
        if (IS_AJAX) {
            $username = I('post.user');
            $info = D('user')->addRef($user_id, $username);
            if (!$info) {
                $this->ajaxReturn(array('status' => 0, 'msg' => D('user')->getError()));
            } else {
                $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
            }
        }
    }

    //用户激活
    public function active()
    {
        $this->checkLogin();
        $user_id = session('user_id');
        if (IS_AJAX) {
            //受激活用户id
            $id = I('get.id');
            $info = D('user')->ChangeActive($user_id, $id);
            if (!$info) {
                $this->ajaxReturn(array('status' => 0, 'msg' => D('user')->getError()));
            } else {
                $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
            }
        }
    }

//    public function getChild(){
//        $info = D('user')->select();
//        foreach ($info as $key => $value) {
//            if($value['parent_id']==1){
//                $list[]=$value;
//                //使用递归的方式获取分类下的子分类
//                //$this->getTree($data,$value['id'],$lev+1);
//            }
//        }
//        dump($list);
//    }

    //认证信息
    public function real_name()
    {
        if (IS_POST) {
            //dump(I('post.'));die;
            $username = trim(I('post.username'));
            $idcard = trim(I('post.idcard'));
            //$face_img = $this->cc();
            $dir = 'RealNameUploads/';
            $face_img = $this->upFile('face_img', $dir);
            $back_img = $this->upFile('back_img', $dir);
            //dump($face_img);die;
            $data['username'] = $username;
            $data['idcard'] = $idcard;
            $data['face_img'] = $face_img;
            $data['back_img'] = $back_img;
            $data['user_id'] = session('user_id');
            $data['is_pass'] = 0;
            $data['is_first'] = 1;
            $data['addtime'] = time();
            $cc = M('RealName')->add($data);
            if ($cc) {
                cookie('success', 1);
            } else {
                cookie('success', 0);
            }
        }
        $user_id = session('user_id');
        //$user_id = 3;
        $info = M('RealName')->where('user_id=' . $user_id)->find();
        {
            if (!$info) {
                //第一次
                $is_first = 0;
            } else {
                $is_first = 1;
                if ($info['is_pass'] != 1) {
                    $is_pass = 0;
                } else {
                    $is_pass = 1;
                }
            }
        }
        $this->assign('is_first', $is_first);
        $this->assign('is_pass', $is_pass);
        $this->display();
    }

    public function gg()
    {
        if (IS_AJAX) {
            $username = trim(I('post.username'));
            $idcard = trim(I('post.idcard'));
            $face = trim(I('post.face_img'));
            $back = trim(I('post.back_img'));
            //$face_img = $this->cc();
            $face_img = $this->upFile($face);
            $back_img = $this->upFile($back);
            //dump($face_img);die;
            $data['username'] = $username;
            $data['idcard'] = $idcard;
            $data['face_img'] = $face_img;
            $data['back_img'] = $back_img;
            $data['user_id'] = session('user_id');
            $data['is_pass'] = 0;
            $data['is_first'] = 1;
            $data['addtime'] = time();
            $cc = M('RealName')->add($data);
            if ($cc) {
                return 1;
            } else {
                return 0;
            }
        }
        $this->display();
    }

    //图片上传
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

    //帮人注册
    public function otherReg()
    {
        $user_id = session('user_id');
        if (IS_AJAX) {
            $username = trim(I('post.username'));
            $password = trim(I('post.password'));
            $tel = trim(I('post.tel'));
            $pay_pwd = trim(I('post.pay_pwd'));
            $msginfo = trim(I('post.msginfo'));
            session_start();
            $code = $_SESSION['msg'];
            if ($msginfo != $code) {
                $this->ajaxReturn(array('status' => 0, 'msg' => "验证码错误"));
            }
            session('msg',null);
            $salt = rand(100000, 999999);
            $password = md5(md5($password) . $salt);
            $pay_pwd = md5(md5($pay_pwd) . $salt);
            $data = array(
                'username' => $username,
                'password' => $password,
                'tel' => $tel,
                'parent_id' => $user_id,
                'salt' => $salt,
                'pay_pwd'=>$pay_pwd,
                'real_name' => $username,
                'first_time' => time(),
            );
            $info = D('user')->add($data);
            if (!$info) {
                $this->ajaxReturn(array('status' => 0, 'msg' => "注册失败"));
            } else {
                $time = time();
                //将用户同步到官网
                $gc_user = M('user')->db(1,'DBConf1')->table('guocha_user')->where("username = '$username'")->find();
                if (!$gc_user) {
                    M('user')->db(1,'DBConf1')->table('guocha_user')->add(array(
                        'tel'       =>  $tel,
                        'username'  =>  $username,
                        'parent_id' =>  $user_id,
                        'password'  =>  $password,
                        'salt'      =>  $salt,
                        'first_time'    =>  $time,
                    ));
                }
                $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
            }
        } else {
            $this->display();
        }
    }

    //个人信息保存
    public function user_info()
    {
        $user_id = session('user_id');
        $info = D('user')->where('id=' . $user_id)->find();
        if($info['parent_id']!=0){
            //我的推荐人
            $data = D('user')->where('id='.$info['parent_id'])->find();
            $p_user = $data['username'];
        }else{
            $p_user = "";
        }
        $this->assign('p_user',$p_user);

        //收货地址
        $res = M('UserAddress')->where(array('user_id'=>$user_id,'is_use'=>1))->find();
        //拼接
        $address =$res['province'].$res['city'].$res['area'].$res['district'];
        $this->assign('address',$address);

        //激活时间
        $active_time = M('UserRecharge')->where('user_id='.$user_id)->order('id')->limit(1)->find();
        if(!$active_time){
            $active_time['addtime'] = "";
        }
        $this->assign('active_time',$active_time);

        $this->assign('info', $info);

        $this->display();
    }

    //我的二级推荐
    public function vcm(){
        $user_id = session('user_id');
        if(IS_AJAX){
            $info_list = D('user')->getChild($user_id);
            foreach($info_list as $k=>$v){
                $a[] = D('user')->getChild($v['id']);
            }
            foreach($a as $vo){
                if($vo != ""){
                    foreach($vo as $v2){
                        $ss[] = $v2;
                    }
                }
            }
            foreach ($ss as $k3 => $v3) {
                $ss[$k3]['sum'] = $this->getChilderSum($v3['id']);
                $ss[$k3]['status'] = $this->getLev($v3['id']);
            }
            $data = $this->groupByInitials($ss, 'username');
            foreach ($data as $k1 => $v1) {
                foreach ($v1 as $v4) {
                    $list[] = $v4;
                }
            }
            $sse = json_encode($list);
            $this->ajaxReturn(array('msg'=>$sse));
        }
    }
    //我的一级推荐
    public function recommender()
    {
        $user_id = session('user_id');
        if (IS_AJAX) {
            //$info_list = D('user')->getChild($user_id);
            //foreach ($info_list as $k => $v) {
            //$info_list[$k]['sum'] = $this->getMoneySum($v['id']);
            //$info_list[$k]['status'] = $this->getLev($v['id']);
            //}

            $childId = $this->getChilderId($user_id);
            foreach ($childId as $k=>$v){
                $childId[$k]['sum'] = $this->getChilderSum($v['id']);
                $childId[$k]['status'] = $this->getLev($v['id']);
            }

            $data = $this->groupByInitials($childId, 'username');
            foreach ($data as $k1 => $v1) {
                foreach ($v1 as $v2) {
                    $list[] = $v2;
                }
            }
            $info = json_encode($list);
            $this->ajaxReturn(array('msg' => $info));
        } else {
            $res = $this->getNumber();
            $this->assign('one', $res['one']["$user_id"]);
            $this->assign('two', $res['two']["$user_id"]);

            $user_id = session('user_id');
            $num_sum = $this->getCateTree($user_id);
            $num_num = count($num_sum)+1;
            //人数
            $this->assign('num_num', $num_num);
            //业绩
            foreach($num_sum as $v){
                $list[] = $v['id'];
            }
            $list[] = $user_id;
            $list_id = implode(",", $list);
            //$list_ids = substr($list_id, 0,$list_id.strlen());
            // dump( $list_ids);
            $money_sum = M('RechargeCart')->where("pay_status = 1 and user_id in ($list_id)")->SUM('recharge_money');
            $inte_sum = M('RechargeCart')->where("pay_status = 1 and user_id in $list_id")->SUM('again_money');
            $sum_sum = $money_sum+$inte_sum;
            $this->assign('sum_sum', $sum_sum);
            //echo $sum_sum;
            $this->display();
        }
    }

    //通过用户id 找下一级用户id
    public function getChilderId($user_id){
        return D('user')->where('parent_id = '.$user_id)->select();

    }

    //通过id找到下一级用户购买总额
    public function getChilderSum($user_id){
        //$child_list =  D('user')->where('parent_id = '.$user_id)->Field('id')->select();
        //dump($child_list);
        //$sum = M('RechargeCart')->where('user_id in $child_list  AND pay_status = 1')->SUM('recharge_money');
        //return $sum;

        // 取得上级所有直接下级用户id
        $lower_users_id = M('user')->where('parent_id='.$user_id)->Field('id')->select();

        //定义查询上级所有直接下级用户总额条件
        $id_arr['user_id'] = array('in');

        foreach ($lower_users_id as $v)
        {
            $temp_arr[] = $v['id'];
        }
        $id_arr['user_id'][] = $temp_arr;
        // 取得上级所有直接下级用户购买理茶宝总额
        $lower_users_count_money = M('user_recharge')->where($id_arr)->SUM('rec_money');
        return intval($lower_users_count_money);
    }


    /**
     * 二维数组根据首字母分组排序
     * @param  array  $data      二维数组
     * @param  string $targetKey 首字母的键名
     * @return array             根据首字母关联的二维数组
     */
    public function groupByInitials(array $data, $targetKey = 'name')
    {
        $data = array_map(function ($item) use ($targetKey) {
            return array_merge($item, [
                'initials' => $this->getInitials($item[$targetKey]),
            ]);
        }, $data);
        $data = $this->sortInitials($data);
        return $data;
    }

    /**
     * 按字母排序
     * @param  array  $data
     * @return array
     */
    public function sortInitials(array $data)
    {
        $sortData = [];
        foreach ($data as $key => $value) {
            $sortData[$value['initials']][] = $value;
        }
        ksort($sortData);
        return $sortData;
    }

    /**
     * 获取首字母
     * @param  string $str 汉字字符串
     * @return string 首字母
     */
    public function getInitials($str)
    {
        if (empty($str)) {return '';}
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) {
            return strtoupper($str{0});
        }

        $s1  = iconv('UTF-8', 'gb2312', $str);
        $s2  = iconv('gb2312', 'UTF-8', $s1);
        $s   = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) {
            return 'A';
        }
        if ($asc >= -20283 && $asc <= -19776) {
            return 'B';
        }
        if ($asc >= -19775 && $asc <= -19219) {
            return 'C';
        }
        if ($asc >= -19218 && $asc <= -18711) {
            return 'D';
        }
        if ($asc >= -18710 && $asc <= -18527) {
            return 'E';
        }
        if ($asc >= -18526 && $asc <= -18240) {
            return 'F';
        }
        if ($asc >= -18239 && $asc <= -17923) {
            return 'G';
        }
        if ($asc >= -17922 && $asc <= -17418) {
            return 'H';
        }
        if ($asc >= -17417 && $asc <= -16475) {
            return 'J';
        }
        if ($asc >= -16474 && $asc <= -16213) {
            return 'K';
        }
        if ($asc >= -16212 && $asc <= -15641) {
            return 'L';
        }
        if ($asc >= -15640 && $asc <= -15166) {
            return 'M';
        }
        if ($asc >= -15165 && $asc <= -14923) {
            return 'N';
        }
        if ($asc >= -14922 && $asc <= -14915) {
            return 'O';
        }
        if ($asc >= -14914 && $asc <= -14631) {
            return 'P';
        }
        if ($asc >= -14630 && $asc <= -14150) {
            return 'Q';
        }
        if ($asc >= -14149 && $asc <= -14091) {
            return 'R';
        }
        if ($asc >= -14090 && $asc <= -13319) {
            return 'S';
        }
        if ($asc >= -13318 && $asc <= -12839) {
            return 'T';
        }
        if ($asc >= -12838 && $asc <= -12557) {
            return 'W';
        }
        if ($asc >= -12556 && $asc <= -11848) {
            return 'X';
        }
        if ($asc >= -11847 && $asc <= -11056) {
            return 'Y';
        }
        if ($asc >= -11055 && $asc <= -10247) {
            return 'Z';
        }
        return null;
    }




    //获取用户总金额
    public function  getMoneySum($id){
        $data = M('RechargeCart')->where(array('parent_id'=>$id,'pay_status'=>1))->select();
        $sum = 0;
        if($data){
            foreach($data as $k => $v){
                $sum = $v['recharge_money']+$sum;
            }
        }
        return  $sum;
    }

    //获取用户等级
    public function getLev($id){
        $data =M('UserRecharge')->where('user_id='.$id)->order('id desc')->limit(1)->find();
        if($data){
            $rec['lev'] = $data['rec_lev'];
            $rec['out'] = $data['is_return'];
            $rec['active'] = 1;
            $rec['no'] = intval($data['is_active']);
        }else{
            $rec['lev'] = 0;
            $rec['active'] = 0;
            $rec['out'] = 0;
            $rec['no'] = 0;
        }
        return $rec;
    }

    //代激活
    public function OtherActive(){
        if (IS_AJAX) {
            $user_id = intval(I('post.user_id'));
            //购买记录id
            //获取用户购买最后一次id
            $Cart_info = M('RechargeCart')->where('pay_status = 1 and user_id = '.$user_id)->order('id desc')->limit(1)->find();
            $id = intval($Cart_info['id']);

            $rate_info = $this->getRate();
            $info = M('UserRecharge')->where('is_active = 0 and user_id='.$user_id)->order('id desc')->limit(1)->find();

            //以前是否购买过
            $data = M('Integral')->where('user_id=' . $user_id)->order('id desc')->limit(1)->find();
            if ($data) {
                //已买过，获得上次购买记录数据叠加

                //判断是否释放结束************
                if ($data['is_return']==1) {
                    $res['grow_rate'] = $data['grow_rate'];     //增加的固定释放
                } else {
                    $res['grow_rate'] = 0;
                }
                $res['total_sum'] = $data['total_sum'] + $info['total_inte'];   //总积分
                //剩余注册积分=剩余注册积分+未激活产品的赠送注册积分-需要消耗的积分
                $res['reg_inte'] = $data['reg_inte'] + $info['reg_rec'] - $info['sec_reg_rec'];
                $res['grow_rate'] = $data['grow_rate'];     //增加的固定释放
                $res['tea_inte'] = $data['tea_inte'];       //当前茶券
                $res['tea_ponit_inte'] = $data['tea_ponit_inte'];   //当前茶点
            } else {
                $res['total_sum'] = $info['total_inte'];    //总积分
                $res['reg_inte'] = $info['reg_rec'] - $info['sec_reg_rec'];     //剩余注册积分
                $res['grow_rate'] = 0;  //增加的固定释放
                $res['tea_inte'] = 0;   //当前茶券
                $res['tea_ponit_inte'] = 0; //当前茶点
            }
            $res['erevy_back_rate'] = $info['init_rates'];  //每日固定释放值
            $res['user_id'] = $user_id; //用户id
            $res['back_inte'] = 0;  //已返还积分

            //开始返积分
            $every_rate = $res['erevy_back_rate']+$res['grow_rate'];    //每日固定释放总值
            // 当释放总值大于固定释放封顶
            if($every_rate > $rate_info['hight_rate']){
                //当天要返还的积分
                $inte = $info['total_inte'] * $rate_info['hight_rate']; //充值返还总额 * 固定释放封顶返还率
            }else{
                $inte = $info['total_inte'] * $every_rate;  //充值返还总额 x 释放总值
            }

            $res['surplus_inte'] = $res['total_sum']-$inte;     //剩余积分 = 需返还总积分 - 当天返还积分
            $res['tea_inte'] = $res['tea_inte'] + $inte * $rate_info['slow_tea_inte_rate'];     //茶券 = 当前茶券 + 当天返还总积分 x 静态积分茶券释放比例
            $res['tea_ponit_inte'] = $res['tea_ponit_inte'] + $inte * $rate_info['slow_tea_score_rate'];    // 茶点 = 当前茶点 + 当天返还总积分 x 静态积分茶点释放比例
            $res['back_inte'] = $res['back_inte']+$inte;    //已返还积分 = 已返还积分 - 当天返还总积分
            $res['last_time'] = date('Y-m-d');  //最后释放时间
            $res['is_return'] = 1;  //是否返还结束为未结束
            $res_insert = M('Integral')->add($res);

            $tea_inte =  $inte * $rate_info['slow_tea_inte_rate'];    //返还的茶券 = 返还总积分 * 静态积分茶券释放比例
            $tea_ponit_inte = $inte * $rate_info['slow_tea_score_rate'];   //返还的茶点 = 返还总积分 * 静态积分茶点释放比例
            $introduce = "激活释放";
            $log_obj = new \Home\Controller\IntegralController();
            $log_obj->MakeLog($user_id,$inte,$tea_inte,$tea_ponit_inte,0,$introduce,4,0,0,0,$info['rec_lev'],$res['total_sum'],$res['surplus_inte'],0,0,1,0,0,0);

            $data_upd = M('UserRecharge')->where('id=' . $info['id'])->setField('is_active', '1');
            $user_active = M('UserRecharge')->where('id=' . $info['id'])->find();
            $time = time();
            M('UserRecharge')->where('id=' . $info['id'])->setField('rec_addtime', $time);
            if ($res_insert && $data_upd) {

                $introduce = "激活本人账户";
                $log_obj = new \Home\Controller\IntegralController();
                $log_obj->MakeLog($user_id,$inte,0,0,$info['sec_reg_rec'],$introduce,2,$user_active['rec_money'],0,0,$info['rec_lev'],$res['total_sum'],$res['surplus_inte'],0,0,1,0,0,0);

                M('RechargeCart')->where('id='.$id)->setField('is_active', '1');
                echo 1;
            } else {
                echo  0;
            }
        }
    }



    //获取推荐人数
    public function getNumber()
    {
        //先获取所有的分类信息
        $data = M('user')->select();
        //dump($data);
        //dump($data);
        //获得每个用户一级推荐人信息（人数）
        foreach($data as $k=>$v){
            $c = $v['id'];
            $i =0;
            foreach($data as $k1=>$v1){
                $arr[$c] = $i;
                if($v1['parent_id'] == $c){
                    $i++;
                    $list[$c][] = $v1['id'];
                    $arr[$c] = $i;
                    //$list1[$c][] = $v1;
                }
            }
        }
        //获得每个用户二级推荐人信息（人数）
        foreach($list as $k=>$v){
            //dump($k);
            $sum= 0;
            foreach ($v as $k1=>$v2){
                $n = (int)$v2;
                $sum += $arr[$n];
                $list2[$k] = $sum;
            }
        }
        foreach ($arr as $k=>$v){
            if($v == 0){
                $list2[$k] = 0;
            }
        }
        $info['one'] = $arr;
        $info['two'] = $list2;
        //dump($info);
        return $info;
    }

    //密码修改
    public function reset_password()
    {

        if (IS_AJAX) {
            $pwd = I('post.pwd');
            $password = I('post.password');
            $password2 = I('post.password2');
            //验证密码是否正确
            $info = D('user')->checkPwd($pwd, $password, $password2);
            if (!$info) {
                $this->ajaxReturn(array('status' => 0, 'msg' => D('user')->getError()));
            } else {
                $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
            }
        }
        $this->display();
    }

    //赠送积分
    public function give_jifen()
    {
        $user_id = session('user_id');
        if (IS_AJAX) {
            $username = I('post.username');
            $inte = I('post.inte');
            $info = D('user')->ChangeRegInte($user_id, $username, $inte);
            if (!$info) {
                $this->ajaxReturn(array('status' => 0, 'msg' => D('user')->getError()));
            } else {
                $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
            }
        }
        $info = M('Integral')->where('id=' . $user_id)->find();
        $this->assign('info', $info);
        $this->display();
    }

    //同手机号用户
    public function getInfoByTel($tel)
    {
        $data = session('user');
        $info = D('user')->where('tel=' . $tel)->select();
        $this->assign('info', $info);
    }

    //切换用户
    public function switchUser($id)
    {
        $info = D('user')->where('id=' . $id)->find();
        if ($info) {
            session('user', null);
            session('user_id', null);
            //session('phone',null);
            session('user_id', $info['id']);
            session('user', $info);
            //切换用户登录时间
            $info['addtime'] = time();
            D('user')->where('id=' . $info['id'])->save($info);
            $this->redirect('Index/customer_info');
            //$this->display('Index/index');
        }
    }

    //二级密码修改设置
    public function pay_password()
    {
        $user_id = session('user_id');
        if (IS_AJAX) {
            $pwd = I('post.pwd');
            $pay_pwd = I('post.password');
            $password2 = I('post.password2');
            //验证密码是否正确
            $info = D('user')->checkPay($pwd, $pay_pwd, $password2);
            if (!$info) {
                $this->ajaxReturn(array('status' => 0, 'msg' => D('user')->getError()));
            } else {
                $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
            }
        }
        $this->display();
    }


    //理茶宝激活
    public function activeProduct()
    {
        $user_id = session('user_id');
        if (IS_AJAX) {
            $id = I('post.id');

            $rate_info = $this->getRate();
            $info = M('UserRecharge')->where('is_active = 0 and user_id='.$user_id)->order('id desc')->limit(1)->find();

            //以前是否购买过
            $data = M('Integral')->where('user_id=' . $user_id)->order('id desc')->limit(1)->find();
            if ($data) {
                //已买过，获得上次购买记录数据叠加

                //判断是否释放结束************
                if ($data['is_return']==1) {
                    $res['grow_rate'] = $data['grow_rate'];     //增加的固定释放
                } else {
                    $res['grow_rate'] = 0;
                }
                $res['total_sum'] = $data['total_sum'] + $info['total_inte'];   //总积分
                //剩余注册积分=剩余注册积分+未激活产品的赠送注册积分-需要消耗的积分
                $res['reg_inte'] = $data['reg_inte'] + $info['reg_rec'] - $info['sec_reg_rec'];
                $res['grow_rate'] = $data['grow_rate'];     //增加的固定释放
                $res['tea_inte'] = $data['tea_inte'];       //当前茶券
                $res['tea_ponit_inte'] = $data['tea_ponit_inte'];   //当前茶点
            } else {
                $res['total_sum'] = $info['total_inte'];    //总积分
                $res['reg_inte'] = $info['reg_rec'] - $info['sec_reg_rec'];     //剩余注册积分
                $res['grow_rate'] = 0;  //增加的固定释放
                $res['tea_inte'] = 0;   //当前茶券
                $res['tea_ponit_inte'] = 0; //当前茶点
            }
            $res['erevy_back_rate'] = $info['init_rates'];  //每日固定释放值
            $res['user_id'] = $user_id; //用户id
            $res['back_inte'] = 0;  //已返还积分

            //开始返积分
            $every_rate = $res['erevy_back_rate']+$res['grow_rate'];    //每日固定释放总值
            // 当释放总值大于固定释放封顶
            if($every_rate > $rate_info['hight_rate']){
                //当天要返还的积分
                $inte = $info['total_inte'] * $rate_info['hight_rate']; //充值返还总额 * 固定释放封顶返还率
            }else{
                $inte = $info['total_inte'] * $every_rate;  //充值返还总额 x 释放总值
            }

            $res['surplus_inte'] = $res['total_sum']-$inte;     //剩余积分 = 需返还总积分 - 当天返还积分
            $res['tea_inte'] = $res['tea_inte'] + $inte * $rate_info['slow_tea_inte_rate'];     //茶券 = 当前茶券 + 当天返还总积分 x 静态积分茶券释放比例
            $res['tea_ponit_inte'] = $res['tea_ponit_inte'] + $inte * $rate_info['slow_tea_score_rate'];    // 茶点 = 当前茶点 + 当天返还总积分 x 静态积分茶点释放比例
            $res['back_inte'] = $res['back_inte']+$inte;    //已返还积分 = 已返还积分 - 当天返还总积分
            $res['last_time'] = date('Y-m-d');  //最后释放时间
            $res['is_return'] = 1;  //是否返还结束为未结束

            $res_insert = M('Integral')->add($res);

            //生成记录
//            $time = time(); //生成时间
            //           $log['user_id'] = $user_id; //用户id
//            $log['surplus_inte'] = $inte;   //返还总积分
//            $log['tea_inte'] = $inte * $rate_info['slow_tea_inte_rate'];    //返还的茶券 = 返还总积分 * 静态积分茶券释放比例
//            $log['tea_ponit_inte'] = $inte * $rate_info['slow_tea_score_rate']; //返还的茶点 = 返还总积分 * 静态积分茶点释放比例
//            $log['reg_inte'] = "0";     //注册积分
//            $log['addtime'] = $time;    //添加时间
//            $log['introduce'] = "激活释放"; //记录描述
//            $log['menu'] = 4;
//            ['recharge_money'] = 0; //充值
//            $log['exchange'] = 0;   //兑换
//            $log['shopping'] = 0;   //购物
//            M('IntegralLog')->add($log);

            $tea_inte =  $inte * $rate_info['slow_tea_inte_rate'];    //返还的茶券 = 返还总积分 * 静态积分茶券释放比例
            $tea_ponit_inte = $inte * $rate_info['slow_tea_score_rate'];   //返还的茶点 = 返还总积分 * 静态积分茶点释放比例
            $introduce = "激活释放";
            $log_obj = new \Home\Controller\IntegralController();
            $log_obj->MakeLog($user_id,$inte,$tea_inte,$tea_ponit_inte,0,$introduce,4,0,0,0,$info['rec_lev'],$res['total_sum'],$res['surplus_inte'],0,0,1,0,0,0);


            $data_upd = M('UserRecharge')->where('id=' . $info['id'])->setField('is_active', '1');
            $user_active = M('UserRecharge')->where('id=' . $info['id'])->find();
            $time = time();
            M('UserRecharge')->where('id=' . $info['id'])->setField('rec_addtime', $time);
            if ($res_insert && $data_upd) {

                //积分消耗明细
//                $time = time();
//                $log['user_id'] = $user_id;
//                $log['surplus_inte'] = $inte;
//                $log['tea_inte'] = 0;
//                $log['tea_ponit_inte'] = 0;
//                $log['reg_inte'] =  $info['sec_reg_rec'];
//                $log['addtime'] = $time;
//                $log['introduce'] = "激活本人账户";
//                $log['menu'] = 2;
//                $log['recharge_money'] = $user_active['rec_money'];
//                $log['exchange'] = 0;
//                $log['shopping'] = 0;
//                M('IntegralLog')->add($log);

                $introduce = "激活本人账户";
                $log_obj = new \Home\Controller\IntegralController();
                $log_obj->MakeLog($user_id,$inte,0,0,$info['sec_reg_rec'],$introduce,2,$user_active['rec_money'],0,0,$info['rec_lev'],$res['total_sum'],$res['surplus_inte'],0,0,1,0,0,0);

                M('RechargeCart')->where('id='.$id)->setField('is_active', '1');
                echo 1;
            } else {
                echo  0;
            }
        }
    }

    public function getRate(){
        return M('Rate')->where('id=1')->find();
    }

    //积分转换
    public function jifen_exchange()
    {
        $user_id = session('user_id');
        if (IS_AJAX) {
            $tea = I('post.tea');
            $inte = I('post.inte');
            $pwd = intval(I('post.pwd'));
            $data = D('user')->where('id='.$user_id)->find();
            if (md5(md5($pwd) . $data['salt']) != $data['pay_pwd']) {
                echo 2;
            } else {
                $info = M('Integral')->where('user_id=' . $user_id)->order('id desc')->limit(1)->find();
                if ($tea == 1) {
                    //茶积分
                    if ($info['tea_inte'] >= $inte) {
                        $info['tea_inte'] = $info['tea_inte'] - $inte;
                        $info['reg_inte'] = $info['reg_inte'] + $inte;
                        $res = M('Integral')->where('id=' . $info['id'])->save($info);
                        if ($res) {

                            //记录
//                            $time = time();
//                            $log['user_id'] = $user_id;
//                            $log['surplus_inte'] = 0;
//                           $log['tea_inte'] =  $inte;
//                            $log['tea_ponit_inte'] = 0;
//                            $log['reg_inte'] = $inte;
//                            $log['addtime'] = $time;
//                            $log['menu'] = 6;
//                            $log['introduce'] = "兑换茶籽";
//                            $log['recharge_money'] = 0;
//                            $log['exchange'] = $inte;
//                            $log['shopping'] = 0;
//                            M('IntegralLog')->add($log);

                            $introduce = "兑换茶籽";
                            $log_obj = new \Home\Controller\IntegralController();
                            $log_obj->MakeLog($user_id,0,$inte,0,$inte,$introduce,6,0,$inte,0,0,0,0,0,0,0,0,0,0);

                            echo 1;
                        } else {
                            echo 0;
                        }
                    }
                }
                if ($tea == 0) {
                    //茶点积分
                    if ($info['tea_ponit_inte'] >= $inte) {
                        $info['tea_ponit_inte'] = $info['tea_ponit_inte'] - $inte;
                        $info['reg_inte'] = $info['reg_inte'] + $inte;
                        $res = M('Integral')->where('id=' . $info['id'])->save($info);
                        if ($res) {

                            //记录
//                            $time = time();
//                            $log['user_id'] = $user_id;
//                            $log['surplus_inte'] = 0;
//                            $log['tea_inte'] = 0;
//                            $log['tea_ponit_inte'] = $inte;
//                            $log['reg_inte'] =  $inte;
//                            $log['addtime'] = $time;
//                            $log['menu'] = 6;
//                            $log['introduce'] = "兑换茶籽";
//                            $log['recharge_money'] = 0;
//                            $log['exchange'] = $inte;
//                            $log['shopping'] = 0;
//                            M('IntegralLog')->add($log);

                            $introduce = "兑换茶籽";
                            $log_obj = new \Home\Controller\IntegralController();
                            $log_obj->MakeLog($user_id,0,0,$inte,$inte,$introduce,6,0,$inte,0,0,0,0,0,0,0,0,0,0);
                            echo 1;
                        } else {
                            echo 0;
                        }
                    }
                }
            }
        } else {
            $info = M('Integral')->where('user_id=' . $user_id)->order('id desc')->limit(1)->find();
            if (!$info) {
                $info['tea_inte'] = 0;
                $info['tea_ponit_inte'] = 0;
            }
            $this->assign('info', $info);
            $this->display();
        }

    }

//验证用户激活
    public function checkToActive(){
        $user = session('user');
        if(!$user){
            $this->ajaxReturn(array('status' =>2, 'msg' => "尚未登录"));
        }
        //$this->ajaxReturn(array('status' => 0, 'msg' =>$user['real_name'] ));
        if($user['parent_id']==0 || !$user['real_name'] || !$user['idcard'] || !$user['bank'] ||!$user['bank_name']){
            $this->ajaxReturn(array('status' => 0, 'msg' =>$user['idcard'] ));
        }
        //是否有收货地址
        $data = M('UserAddress')->where('is_use = 1 and user_id='.$user['id'])->find();
        if(!$data){
            $this->ajaxReturn(array('status' => 0, 'msg' => "收货地址未填写"));
        }else{
            $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
        }
    }

    //推荐人验证
    public function my_recommenders(){
        $user_id = session('user_id');
        if (IS_AJAX) {
            $username = trim(I('post.username'));
            if ($username) {
                // 查询推荐人信息
                $data = D('user')->where("username='$username'")->find();
                // 如果推荐人不存在则报错
                if (!$data) {
                    $this->ajaxReturn(array('status' => 0, 'msg' => "推荐人不存在"));
                }
                if($data['wait']==0){
                    $this->ajaxReturn(array('status' => 0, 'msg' => "此用户已被冻结"));
                }

                // 如果推荐人id等于当前用户id则报错
                if ($data['parent_id'] == $user_id) {
                    $this->ajaxReturn(array('status' => 0, 'msg' => "推荐人不符合"));
                }
                //判断推荐人是否购买产品
                $info = M('UserRecharge')->where('pay_status = 1 and user_id=' . $data['id'])->order('id desc')->limit(1)->find();
                if (!$info) {
                    $this->ajaxReturn(array('status' => 0, 'msg' => "推荐人不符合,尚未购买产品"));
                }
                $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
            }
        }
    }

    //个人信息完善
    public function my_recommender(){
        // 取当前登录用户id
        $user_id = session('user_id');
        if (IS_AJAX) {
            $username = trim(I('post.username'));
            if($username){
                // 查询推荐人信息
                $data = D('user')->where("username='$username'")->find();
                if($data['id']==1){
                    $parent_id = 1;
                }else{
                    // 如果推荐人不存在则报错
                    if(!$data){
                        $this->ajaxReturn(array('status' => 0, 'msg' => "推荐人不存在"));
                    }
                    // if($data['wait']==0){
                    //   $this->ajaxReturn(array('status' => 0, 'msg' => "此用户已被冻结"));
                    //  }
                    // 如果推荐人id等于当前用户id则报错
                    if($data['parent_id']==$user_id){
                        $this->ajaxReturn(array('status' => 0, 'msg' => "推荐人不符合"));
                    }
                    //判断推荐人是否购买产品
                    $info = M('UserRecharge')->where('pay_status = 1 and user_id='.$data['id'])->order('id desc')->limit(1)->find();
                    if(!$info){
                        $this->ajaxReturn(array('status' => 0, 'msg' => "推荐人不符合,尚未购买产品"));
                    }
                    //获得推荐人id
                    $parent_id = $data['id'];
                }
            }else{
                $parent_id = 1;
            }

            //个人信息
            $idcard = trim(I('post.IDcard'));       // 身份证号码
            $real_name = trim(I('post.real_name')); // 姓名
            $bank = trim(I('post.bank'));           // 银行卡信息
            $bank_name = trim(I('post.bank_name'));           // 银行卡信息
            $province = I('post.provinceId');           // 省
            $city = I('post.cityId');                 // 市
            $area = I('post.districtId');                 // 区/县
            $district = I('post.address');         // 地址
            // 更新当前用户推荐人信息
            $c['parent_id'] = $parent_id;
            $c['idcard'] = $idcard;
            $c['real_name'] = $real_name;
            $c['bank'] = $bank;
            $c['bank_name'] = $bank_name;
            $info = D('user')->where('id='.$user_id)->save($c);
            // 如果更新数据失败
            if ($info<=0) {
                $this->ajaxReturn(array('status' => 0, 'msg' => "填写失败"));
            } else {
                $user = session('user');
                //判断是否有收货地址
                $data = M('UserAddress')->where('is_use = 1 and user_id='.$user['id'])->find();
                if($data){
                    M('UserAddress')->where('is_use = 1 and user_id='.$user['id'])->setField('is_use',0);
                }
                //地址入库
                // 增加用户收货地址
                $address = array(
                    'user_id'=>$user['id'],
                    'province' => $province,
                    'city' => $city,
                    'area'=>$area,
                    'district'=>$district,
                    'tel'=>$user['tel'],
                    'rec_name'=>$real_name,
                    'is_use'=>1,
                );
                M('UserAddress')->add($address);
                $user_data = D('user')->where('id='.intval($user['id']))->find();
                session('user',null);
                session('user',$user_data);
                $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
            }
        }else{
            $user_id = session('user_id');
            $res = D('user')->where('id='.$user_id)->find();
            $this->assign('res',$res['parent_id']);
            $this->assign('sec',$res);
            //通过父id找到父用户名
            $sos = D('user')->where('id='.intval($res['parent_id']))->find();
            $this->assign('sos',$sos['username']);

            $this->display();
        }
    }


    //填写代买人信息
    public function daimai_custom(){
        if(IS_AJAX){
            //个人信息
            $other_id = I('post.other_id');
            //通过id获取信息
            $user = D('user')->where('id='.$other_id)->find();
            $idcard = trim(I('post.IDcard'));       // 身份证号码
            $real_name = trim(I('post.real_name')); // 姓名
            $bank = trim(I('post.bank'));           // 银行卡信息
            $bank_name = trim(I('post.bank_name'));           // 银行卡信息
            $province = I('post.provinceId');           // 省
            $city = I('post.cityId');                 // 市
            $area = I('post.districtId');                 // 区/县
            $district = I('post.address');         // 地址
            // 更新当前用户推荐人信息

            $c['idcard'] = $idcard;
            $c['real_name'] = $real_name;
            $c['bank'] = $bank;
            $c['bank_name'] = $bank_name;
            $info = D('user')->where('id='.$user['id'])->save($c);
            // 如果更新数据失败
            if ($info<=0) {
                $this->ajaxReturn(array('status' => 0, 'msg' => "填写失败"));
            }
            $data = M('UserAddress')->where('is_use = 1 and user_id='.$other_id)->find();
            if($data){
                M('UserAddress')->where('is_use = 1 and user_id='.$other_id)->setField('is_use',0);
            }
            //地址入库
            // 增加用户收货地址
            $address = array(
                'user_id'=>$other_id,
                'province' => $province,
                'city' => $city,
                'area'=>$area,
                'district'=>$district,
                'tel'=>$user['tel'],
                'rec_name'=>$real_name,
                'is_use'=>1,
            );
            M('UserAddress')->add($address);
            $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
        }else{
            $user_id = I('get.id');
            $this->assign('user_id',$user_id);
            $this->display();
        }
    }

    //代买的用户信息判断
    public function OtherToActive(){
        $info = session('user');
        $user_info  = M('UserRecharge')->where('pay_status = 1 and user_id='.$info['id'])->order('id desc')->limit(1)->find();
        if(!$user_info){
            $this->ajaxReturn(array('status' => 2, 'msg' =>"尚未购买产品" ));
        }
        $user_id = I('post.user_id');
        $user = D('user')->where('id='.$user_id)->find();
        if($user['parent_id']==0 || !$user['real_name'] || !$user['idcard'] || !$user['bank'] ||!$user['bank_name']){
            $this->ajaxReturn(array('status' => 0, 'msg' =>"信息不完整" ));
        }
        //是否有收货地址
        $data = M('UserAddress')->where('is_use = 1 and user_id='.$user['id'])->find();
        if(!$data){
            $this->ajaxReturn(array('status' => 0, 'msg' => "收货地址未填写"));
        }else{
            $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
        }
    }

    //推荐人2
    public function my_recommendere(){
        $user_id = session('user_id');
        if (IS_AJAX) {
            $info['parent_id'] = 1;
            $info = D('user')->where('id='.$user_id)->save($info);
            if (!$info) {
                $this->ajaxReturn(array('status' => 0, 'msg' => D('user')->getError()));
            } else {
                $this->ajaxReturn(array('status' => 1, 'msg' => "ok"));
            }
        }
    }

    public function account(){
        //显示用户消费记录
        $user_id=session('user_id');
        $data = D('Order')
            ->where("user_id=$user_id AND pay_status=1")
            ->select();
        //显示用户的充值记录
        $lichabao = D('RechargeCart')->where("user_id=$user_id AND pay_status=1")
            ->select();
        $this->assign('orderInfo',$data);
        $this->assign('manageInfo',$lichabao);
        $this->display();
    }


    //积分日志
    public function detile()
    {
        $user_id = session('user_id');
        //$info = M('IntegralLog')->where('user_id=' . $user_id)->select();
        $pagesize = 9;
        $count = M('IntegralLog')->where("menu in (3,4) and user_id=" . $user_id)->count();
        //计算出分页导航
        $page = new \Think\Page($count, $pagesize);
        //页码列表长度
        $page->rollPage = 5;
        $page->setConfig('last', '尾页');
        $page->setConfig('first', '首页');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');

        $show = $page->show();
        //获取当前页码
        $p = intval(I('get.p'));
        //dump($p);
        //获得具体的数据
        $info = M('IntegralLog')->where("menu in (3,4) and user_id=" . $user_id)->order('addtime desc')->page($p, $pagesize)->select();
        // dump($info);
        // dump($show);
        //将需要的数据及分页导航都返回
        //return array('pageStr' => $show, 'data' => $data);
        $this->assign('info',$info);
        $this->assign('show',$show);


        $data = M('IntegralLog')->where("menu in (3,4) and user_id=" . $user_id)->select();
        $res['surplus_inte'] = 0;
        $res['tea_inte']=0;
        $res['tea_ponit_inte'] =0;
        if($data){
            foreach($data as $v){
                $res['tea_inte'] = $res['tea_inte']+$v['tea_inte'];
                $res['tea_ponit_inte'] = $res['tea_ponit_inte']+$v['tea_ponit_inte'];
                $res['surplus_inte'] = $res['tea_inte']+$res['tea_ponit_inte'];
            }
        }

        $this->assign('res',$res);

        $this->display();
    }


    //我的释放
    public function relief(){

        $user_id = session('user_id');
        $pagesize = 9;
        $count = M('IntegralLog')->where(array('user_id'=>$user_id,'menu'=>4))->count();
        //计算出分页导航
        $page = new \Think\Page($count, $pagesize);
        //页码列表长度
        $page->rollPage = 5;
        $page->setConfig('last', '尾页');
        $page->setConfig('first', '首页');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');

        $show = $page->show();
        //获取当前页码
        $p = intval(I('get.p'));
        //dump($p);
        //获得具体的数据
        $info = M('IntegralLog')->where(array('user_id'=>$user_id,'menu'=>4))->order('addtime desc')->page($p, $pagesize)->select();
        // dump($info);
        // dump($show);
        //将需要的数据及分页导航都返回
        //return array('pageStr' => $show, 'data' => $data);
        $this->assign('info',$info);
        $this->assign('show',$show);

        $data = M('IntegralLog')->where(array('user_id'=>$user_id,'menu'=>4))->select();
        $res['surplus_inte'] = 0;
        $res['tea_inte']=0;
        $res['tea_ponit_inte'] =0;
        if($data){
            foreach($data as $v){
                $res['tea_inte'] = $res['tea_inte']+$v['tea_inte'];
                $res['tea_ponit_inte'] = $res['tea_ponit_inte']+$v['tea_ponit_inte'];
                $res['surplus_inte'] = $res['tea_inte']+$res['tea_ponit_inte'];
            }
        }

        $this->assign('res',$res);
        $this->display();
    }

    //我的奖励
    public function reward(){
        $user_id = session('user_id');
        $pagesize = 9;
        $count = M('IntegralLog')->where(array('user_id'=>$user_id,'menu'=>3))->count();
        //计算出分页导航
        $page = new \Think\Page($count, $pagesize);
        //页码列表长度
        $page->rollPage = 5;
        $page->setConfig('last', '尾页');
        $page->setConfig('first', '首页');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');

        $show = $page->show();
        //获取当前页码
        $p = intval(I('get.p'));
        //dump($p);
        //获得具体的数据
        $info = M('IntegralLog')->where(array('user_id'=>$user_id,'menu'=>3))->order('addtime desc')->page($p, $pagesize)->select();
        //dump($info);
        //dump($show);
        //将需要的数据及分页导航都返回
        //return array('pageStr' => $show, 'data' => $data);
        $this->assign('info',$info);
        $this->assign('show',$show);

        $data = M('IntegralLog')->where(array('user_id'=>$user_id,'menu'=>3))->select();
        $res['surplus_inte'] = 0;
        $res['tea_inte']=0;
        $res['tea_ponit_inte'] =0;
        if($data){
            foreach($data as $v){
                $res['surplus_inte'] = $res['surplus_inte']+$v['surplus_inte'];
                $res['tea_inte'] = $res['tea_inte']+$v['tea_inte'];
                $res['tea_ponit_inte'] = $res['tea_ponit_inte']+$v['tea_ponit_inte'];
            }
        }

        $this->assign('res',$res);

        $this->display();
    }

    //我的茶券，茶点记录
    public function record(){
        $user_id = session('user_id');
        $pagesize = 9;
        $count = M('IntegralLog')->where("menu in (5,6) and user_id=" . $user_id)->count();
        //计算出分页导航
        $page = new \Think\Page($count, $pagesize);
        //页码列表长度
        $page->rollPage = 5;
        $page->setConfig('last', '尾页');
        $page->setConfig('first', '首页');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $show = $page->show();
        //获取当前页码
        $p = intval(I('get.p'));
        //获得具体的数据
        $info = M('IntegralLog')->where("menu in (5,6,7) and user_id=" . $user_id)->order('addtime desc')->page($p, $pagesize)->select();
        //将需要的数据及分页导航都返回
        $this->assign('info',$info);
        $this->assign('show',$show);

        $data = M('IntegralLog')->where("menu in (5,6,7) and user_id=" . $user_id)->select();
        $res['tea_inte']=0;
        $res['tea_ponit_inte'] =0;
        $res['shopping']=0;
        $res['exchange'] = 0;
        if($data){
            foreach($data as $v){
                $res['shopping'] = $res['shopping']+$v['shopping'];
                $res['tea_inte'] = $res['tea_inte']+$v['tea_inte'];
                $res['tea_ponit_inte'] = $res['tea_ponit_inte']+$v['tea_ponit_inte'];
                $res['exchange'] = $res['exchange']+$v['exchange'];
            }
        }

        $this->assign('res',$res);

        $this->display();
    }

    //我的茶籽
    public function cha_z(){
        $user_id = session('user_id');
        $pagesize = 9;
        $count = M('IntegralLog')->where("menu in (2,6) and user_id=" . $user_id)->count();
        //计算出分页导航
        $page = new \Think\Page($count, $pagesize);
        //页码列表长度
        $page->rollPage = 5;
        $page->setConfig('last', '尾页');
        $page->setConfig('first', '首页');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');

        $show = $page->show();
        //获取当前页码
        $p = intval(I('get.p'));
        //dump($p);
        //获得具体的数据
        $info = M('IntegralLog')->where("menu in (2,6) and user_id=" . $user_id)->order('addtime desc')->page($p, $pagesize)->select();
        //dump($info);
        //dump($show);
        //将需要的数据及分页导航都返回
        //return array('pageStr' => $show, 'data' => $data);
        $this->assign('info',$info);
        $this->assign('show',$show);

        $data = M('IntegralLog')->where("menu in (2,6) and user_id=" . $user_id)->select();
        $res['tea_inte']=0;
        $res['tea_ponit_inte'] =0;
        $res['recharge_money']=0;
        $res['reg_inte'] = 0;
        if($data){
            foreach($data as $v){
                $res['recharge_money'] = $res['recharge_money']+$v['recharge_money'];
                $res['tea_inte'] = $res['tea_inte']+$v['tea_inte'];
                $res['tea_ponit_inte'] = $res['tea_ponit_inte']+$v['tea_ponit_inte'];
                $res['reg_inte'] = $res['reg_inte']+$v['reg_inte'];
            }
        }

        $this->assign('res',$res);

        $this->display();
    }


    //忘记密码
    public function forgetPwd(){
        if(IS_AJAX){
            $username = I('post.username');
            $msginfo = I('post.msginfo');
            $password = I('post.password');
            session_start();
            $code = $_SESSION['msg'];
            if ($msginfo != $code) {
                $this->ajaxReturn(array('status' => 0, 'msg' => "验证码错误"));
            }
            $info = D('user')->where("username='$username'")->find();
            $password = md5(md5($password).$info['salt']);
            $data = D('user')->where("username='$username'")->setField('password',$password);
            if($data){


                $username = $info['username'];
                $info['password'] = $password;
                //将用户同步到官网
                $gc_user = M('user')->db(1,'DBConf1')->table('guocha_user')->where("username='$username'")->setField('password',$info['password']);

                $this->ajaxReturn(array('status' => 1, 'msg' => "修改密码成功"));
            }else{
                $this->ajaxReturn(array('status' => 0, 'msg' => "修改密码失败"));
            }
        }else{
            $this->display();
        }
    }

    //购买激活
    public function toActive(){
        if(IS_AJAX){
            $user_id = session('user_id');
            $p_info = D('user')->where('id='.$user_id)->find();
            if($p_info['parent_id']==0){
                echo 0;
            }else{
                echo 1;
            }
        }
    }

    //上级是否购买
    public function p_info(){
        $user_id = session('user_id');
        if(IS_AJAX){
            $p_info = M('UserRecharge')->where('pay_status = 1 and user_id='.$user_id)->order('id desc')->limit(1)->find();
            if($p_info){
                echo 1;
            }else{
                echo 0;
            }
        }
    }



    //团队人数
    //无县级
    //获取格式化之后的数据
    public function getCateTree($id=0)
    {
        //先获取所有的分类信息
        $data = D('user')->select();
        //在对获取的信息进行格式化
        $list = $this->getTree($data,$id);
        return $list;
    }
    //格式化分类信息
    public function getTree($data,$id=0,$lev=1)
    {
        static $list = array();
        foreach ($data as $key => $value) {
            if($value['parent_id']==$id){
                $value['lev']=$lev;
                $list[]=$value;
                //使用递归的方式获取分类下的子分类
                $this->getTree($data,$value['id'],$lev+1);
            }
        }
        return $list;
    }


    //格式二维码vcard
    public function  get_vcard(){
        $user_id = intval(session("user_id"));
        $user = 1;
        //Vendor('phpqrcode.phpqrcode');
        include "../ThinkPHP/Library/Vendor/phpqrcode/phpqrcode.php";
        //include "../AiApi/phpqrcode/phpqrcode.php";
        //生成二维码图片
        $object = new QRcode();   //名片

        //$url ="http://bomt.cn/home/user/loging/?cc=123";   //扫码登录
        $url ="http://shop.guochamall.com/home/user/regist/?cc=123";   //扫码登录
        $level='M';
        $size=6;
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //$object->png($url,"vcardimg/b.png");
        $object->png($url, "/card/qrcode_".$user_id.".jpg", $errorCorrectionLevel, $matrixPointSize,2);    //vcardimg为保存目录
        // echo '< img src="../qrcode1.png">';


        $logo = './card/logo.png';//准备好的logo图片
        $QR = "/card/qrcode_".$user_id.".jpg";//已经生成的原始二维码图
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
        }
        //输出图片
        //imagepng($QR, './Video/helloweba1.png');
        //echo '< img src="/Video/helloweba1.png">';

        imagepng($QR, './card/user_'.$user_id.'.png');
        echo '<img src="http://shop.guochamall.com/card/user_' . $user_id . '.png">';
    }
}
