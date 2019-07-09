<?php

namespace app\newapp\controller;

use think\Db;
use think\Controller;

class Share extends Controller
{
    //wx97c3159a64046d86
////05bb81ed77cf0dc8fbd2a56e7f3f8aec
    protected $appid = "wx97c3159a64046d86";
    protected $secret = "05bb81ed77cf0dc8fbd2a56e7f3f8aec";

    //发红包页面
    public function redpack()
    {
        $siagn = $this->getSignature();
        return view('redpack', ['sign' => $siagn]);
    }

    //领红包
    public function get_redpack()
    {
        $recharge_num = $_GET['aa'];
        $data = Db::connect(config('db_config4'))->name("user_recharge")->where('recharge_num', '=', $recharge_num)->find();
        $img_path = $this->create_cash_card($data['tel'], $data['recharge_dollar']);
        $card = array('cashcard' => $img_path);
        return view('test', $card);
    }

    public function del_redpack()
    {
        return view('del_redpack');
    }

    public function checkUser_11()
    {
        return view('checkUser');
    }

    public function redpack_info()
    {
        $siagn = $this->getSignature();
        return view('redpack_info', ['sign' => $siagn]);
    }

    //发红包
    public function sendRedPack()
    {
        $user_id = intval(input('post.user_id'));
        $red_pack = round(floatval(input('post.red_pack')), 2);
        $red_beizhu = input('post.red_beizhu');

        //查找用户id
        $res = Db::table("tea_user")->where("user_id", '=', $user_id)->find();
        //减少用户积分数
        $new_tea_inte = $res['tea_ponit_inte'] - $red_pack;
        //修改积分
        $res = Db::table("tea_user")->where("user_id", '=', "$user_id")->setField('tea_ponit_inte', $new_tea_inte);
        if ($res) {
            //写入记录
            $arr = array('user_id' => $user_id, 'surplus_inte' => '-' . $red_pack, 'tea_ponit_inte' => '-' . $red_pack, "introduce" => "红包分享支出",
                'menu' => 5, 'use_type' => 2, "online" => 1, 'addtime' => time(), 'year' => date("Y"), 'month' => date("m"), 'day' => date("d"));
            Db::table("tea_integral_log")->insert($arr);
            //生成标识码
            $sign = time() . $user_id . $red_pack * 100;
            //写入数据库
            $list = array("user_id" => $user_id, 'red_pack' => $red_pack, 'red_pack_time' => time(), 'pack_statue' => 1, 'red_beizhu' => $red_beizhu, 'red_sign' => $sign);
            Db::table('tea_red_pack')->insert($list);
            return json(array('status' => 1, 'msg' => '', 'data' => $sign));
        } else {
            return json(array('status' => 0, 'msg' => '红包异常'));
        }
    }

    //收红包
    public function getRedPack()
    {
        $red_sign = input('post.sign');

        //判断标志码
        $res = Db::table('tea_red_pack')->where('red_sign', '=', $red_sign)->find();
        if (intval($res['pack_statue']) == 1) {
            return json(array('status' => 1, 'msg' => ''));
        } else {
            return json(array('status' => 0, 'msg' => '红包已被领取过,'));
        }
    }

    //领取人判断
    public function getUserExit()
    {
        $mobile_phone = input('post.mobile_phone');
        $red_sign = input('post.sign');
        $user_name = input('post.user_name');
        $code = intval(input('post.code'));

        $res = Db::table('tea_red_pack')->where('red_sign', '=', $red_sign)->find();


        if ($code == intval(session('code'))) {
            session('code', "");
            if (intval($res['pack_statue']) == 1) {
                //判断手机号是否存在
                $info = Db::connect(config('db_config2'))->name("users")->where('mobile_phone', '=', $mobile_phone)->find();

                if ($info) {
                    //是否为线下会员
                    $onlind_info = Db::connect(config('db_config4'))->name("user")->where('vip_id', '=', intval($info['user_id']))->find();
                    if ($onlind_info) {
                        $get_user_id = $info['user_id'];
                        //修改积分
                        $data = array('dollar_sum' => floatval($onlind_info['dollar_sum']) + floatval($res['red_pack']), 'rech_money' => floatval($onlind_info['rech_money']) + floatval($res['red_pack']));
                        $up_res = Db::connect(config('db_config4'))->name("user")->where('vip_id', '=', $get_user_id)->update($data);
                        if ($up_res) {
                            //写入记录
                            $lists = array(
                                "vip_id" => intval($info['user_id'])
                            , "balancec" => floatval($onlind_info['dollar_sum'])
                            , "recharge_dollar" => floatval($res['red_pack'])
                            , "give_dollar" => 0
                            , "exchange_dollar" => 0
                            , "use_type" => 1
                            , "recharge_time" => time()
                            , "shop_id" => 5
                            , "work_id" => $onlind_info['work_id']
                            , "money_sum" => floatval($onlind_info['dollar_sum']) + floatval($res['red_pack'])
                            , "vip_user" => $onlind_info['vip_user']
                            , "tel" => $mobile_phone
                            , "recharge_num" => time() . intval($info['user_id']) . mt_rand(1000, 9999)
                            , "card_num" => $onlind_info['card_num']
                            , "year" => date("Y")
                            , "month" => date("m")
                            , "day" => date("d")
                            , 'detail' => '红包领取');
                            Db::connect(config('db_config4'))->name("user_recharge")->insert($lists);
                            Db::table('tea_red_pack')->where('red_sign', '=', "$red_sign")->update(['get_red_id' => $get_user_id, 'pack_statue' => 0, 'get_red_time' => time(), 'get_user_name' => $user_name]);
                            return json(array('status' => 1, 'msg' => '', 'data' => $lists['recharge_num']));
                        } else {
                            return json(array('status' => 0, 'msg' => '领取失败'));
                        }
                    } else {
                        $add_res = intval($info['user_id']);
                    }
                } else {
                    //新建会员
                    $ec_salt = mt_rand(10000, 99999);
                    $password = mt_rand(100000, 999999);
                    $password = md5(md5($password) . $ec_salt);
                    $list_info = array('user_name' => 'rp' . $mobile_phone, 'nick_name' => $user_name, 'mobile_phone' => $mobile_phone, 'password' => $password,
                        'tm_salt' => $ec_salt, 'sex' => 0, 'reg_time' => time(), 'parent_id' => intval($res['user_id']));

                    $add_res = Db::connect(config('db_config2'))->name("users")->insertGetId($list_info);

                }
                //线下会员
                //获取会员等级
                $rank = Db::connect(config('db_config4'))->name("discount_rank")->where('shop_id', '=', 5)
                    //->whereOr('is_public','=',1)
                    ->order('id desc')->limit(1)->find();
                //操作人id
                $work_info = Db::connect(config('db_config4'))->name("admin")->where('shop_id', '=', 5)->order('id desc')->limit(1)->find();
                $list1 = array('vip_id' => $add_res, "vip_user" => $user_name, "card_num" => substr(date('Y'), 2, 3) . date('m') . date('d') . time()
                , "lev" => intval($rank['id']), "first_dollar" => 0, "shop_id" => 5
                , "work_id" => intval($work_info['id']), "dollar_sum" => floatval($res['red_pack']), 'rech_money' => floatval($res['red_pack']), 'year' => date('Y')
                , 'month' => date('m'), 'day' => date('d'));
                //dump($list1);
                $Vip_data = Db::connect(config('db_config4'))->name("user")->insert($list1);

                if ($Vip_data) {
                    $lists = array(
                        "vip_id" => $add_res
                    , "balancec" => 0
                    , "recharge_dollar" => floatval($res['red_pack'])
                    , "give_dollar" => 0
                    , "exchange_dollar" => 0
                    , "use_type" => 1
                    , "recharge_time" => time()
                    , "shop_id" => 5
                    , "work_id" => intval($work_info['id'])
                    , "money_sum" => floatval($res['red_pack'])
                    , "vip_user" => $user_name
                    , "tel" => $mobile_phone
                    , "recharge_num" => time() . intval($info['user_id']) . mt_rand(1000, 9999)
                    , "card_num" => $list1['card_num']
                    , "year" => date("Y")
                    , "month" => date("m")
                    , "day" => date("d")
                    , 'detail' => '红包领取');
                    Db::connect(config('db_config4'))->name("user_recharge")->insert($lists);

                    Db::table('tea_red_pack')->where('red_sign', '=', "$red_sign")->update(['get_red_id' => $add_res, 'pack_statue' => 0, 'get_red_time' => time(), 'get_user_name' => $user_name]);
                    return json(array('status' => 1, 'msg' => '1111', 'data' => $lists['recharge_num']));
                } else {
                    return json(array('status' => 0, 'msg' => '领取失败'));
                }
            } else {
                return json(array('status' => 2, 'msg' => '红包已被领取过,'));
            }
        } else {
            return json(array('status' => 0, 'msg' => '验证码错误'));
        }
    }

    //成为会员
    public function createUserAccount()
    {
        $mobile_phone = input('post.mobile_phone');
        $red_sign = input('post.sign');
        $info = Db::connect(config('db_config2'))->name("users")->where('mobile_phone', '=', $mobile_phone)->find();
        //此手机号已存在
        if ($info) {
            $onlind_info = Db::connect(config('db_config4'))->name("user")->where('vip', '=', intval($info['user_id']))->find();
            if (!$onlind_info) {
                $add_res = intval($info['user_id']);
            }
        } else {
            //新建会员
            $ec_salt = mt_rand(10000, 99999);
            $password = mt_rand(100000, 999999);
            $password = md5(md5($password) . $ec_salt);
            $list_info = array('user_name' => "rp" . $mobile_phone, 'mobile_phone' => $mobile_phone, 'password' => $password, 'tm_salt' => $ec_salt, 'sex' => 0, 'reg_time' => time());
            $add_res = Db::connect(config('db_config2'))->name("users")->inserGetId($list_info);
            $res = Db::table('tea_red_pack')->where('red_sign', '=', $red_sign)->find();
            $list1['parent_id'] = $res['user_id'];
        }
        //线下会员
        //获取会员等级
        $rank = Db::connect(config('db_config4'))->name("discount_rank")->where('shop_id', '=', 5)->whereOr('is_public', '=', 1)->order('id desc')->limit(1)->find();
        //操作人id
        $work_info = Db::connect(config('db_config4'))->name("admin")->where('shop_id', '=', 5)->order('id desc')->limit(1)->find();
        $list1 = array('vip_id' => $add_res, "vip_user" => "rp" . $mobile_phone, "card_num" => substr(date('Y'), 2, 3) . date('m') . date('d') . time()
        , "lev" => intval($rank['id']), "first_dollar" => 0, "shop_id" => 5
        , "work_id" => intval($work_info['id']), "dollar_sum" => 0, 'rech_give_money' => 0, 'year' => date('Y')
        , 'month' => date('m'), 'day' => date('d'));
        $Vip_data = Db::connect(config('db_config4'))->name("users")->insert($list1);
    }

    //发送验证码
    public function msgCode()
    {
        $code = mt_rand(100000, 999999);
        session('code', $code);
        //获取传输过来的手机号码
        $tel = input('post.tel');
        //$tel="13419574061";
        include_once('../Api/top/TopClient.php');
        $c = new \TopClient;
        $c->appkey = '23662994';
        $c->secretKey = '12c4693b91926a394e8ca913e132be01';
        vendor('top.request.AlibabaAliqinFcSmsNumSendRequest');
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("我的茶馆");
        $req->setSmsParam("{\"code\":\"$code\",\"product\":\"测试\"}");
        $req->setRecNum("$tel");//电话号码
        $req->setSmsTemplateCode("SMS_62170183");
        $resp = $c->execute($req);
        if ($resp->result->msg) {
            return json(array('status' => 1, 'msg' => '短信发送成功'));
        } else {
            return json(array('status' => 0, 'msg' => '短信发送失败'));
        }
    }

    //红包记录
    public function red_pack_info()
    {
        $user_id = session('user_id') ? session('user_id') : session('lcb_user_id');
        //$user_id=992;
        $page = intval(input('post.page'));
        $count = 6;
        if ($page) {
            $page = ($page - 1) * $count;
        } else {
            $page = 0;
        }
        //$page = 0;
        if ($user_id) {
            $info_num = Db::table('tea_red_pack')->where("user_id", '=', $user_id)->count();
            $info_sum = Db::table('tea_red_pack')->where("user_id", '=', $user_id)->sum('red_pack');
        } else {
            $info_num = 0;
            $info_sum = 0;
        }
        $info = Db::table('tea_red_pack')->where("user_id", '=', $user_id)->order('id desc')->limit($page, $count)->select();
        if (empty($info)) {
            return json(array('status' => 0, 'msg' => '暂无记录'));
        } else {
            $list = ['info_num' => $info_num, 'info_sum' => $info_sum, 'info' => $info];
            return json(array('status' => 1, 'msg' => '', 'data' => $list));
        }

    }

    //获取用户茶点积分
    public function user_red_use()
    {
        $user_id = session('user_id') ? session('user_id') : session('lcb_user_id');
        if (!$user_id) {
            return json(array('status' => 0, 'msg' => '数据异常，请重新登录'));
        } else {
            $res = Db::table('tea_user')->field('tea_ponit_inte')->where('user_id', '=', $user_id)->find();
            $list = ['user_id' => $user_id, 'tea_inte' => $res];
            return json(array('status' => 1, 'msg' => '', 'data' => $list));
        }

    }

    /**
     * 生成签名JSSDK
     */
    public function getSignature()
    {

        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $ticket = $this->getTicket();
        $data = array('noncestr' => 'Wm3WZYTPz0wzccnW',
            'jsapi_ticket' => "$ticket",
            'timestamp' => time(),
            'url' => "$url");
        $sign = $this->make_sign($data);
        return array('sign' => $sign, 'info' => $data);
    }

    //获取token
    public function get_token()
    {
        //$appid = "wx7797056e9ca6c4f7";
        //$secret ="413cf776bd09963ff0ef89fc9f301b1c";
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appid . "&secret=" . $this->secret;
        $str = $this->getHttp($url);
        //dump( $str);
        $access_token = $str->access_token;
        return $access_token;
    }

    //获取jsapi_ticket
    public function getTicket()
    {
        $token = $this->get_token();
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$token&type=jsapi";
        $str = $this->getHttp($url);
        $access_token = $str->ticket;
        return $access_token;
    }

    public function make_sign($data)
    {
        //字典序
        ksort($data);
        //组装字符串
        $stringA = http_build_query($data);
        $stringA = urldecode($stringA);
        //添加key
        //dump($stringA);
        $sign = sha1($stringA);
        return $sign;
    }

    public function getHttp($url)
    {
        $ch = curl_init();
        //设置传输地址
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置以文件流形式输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //接收返回数据
        $data = curl_exec($ch);
        curl_close($ch);
        $jsonInfo = json_decode($data);
        return $jsonInfo;
    }

    public function test()
    {
        var_dump($_REQUEST);
        exit;
//        $card = array('cashcard'=>$this->create_cash_card());
//        return view('test',$card);
    }

    /**
     * 合成图片
     */
    public function create_cash_card($no = 'gc15256601212', $money = 0)
    {
        $img_path = '../public/cash_user_card/00001.png';
        $image = imagecreatefrompng($img_path);
        $font = '../public/cash_user_card/bmzy.ttf';
        $color = imagecolorallocate($image, 236, 204, 102);
        imagefttext($image, 9, 0, 8, 22, $color, $font, 'NO:' . $no);
        imagefttext($image, 14, 0, 8, 50, $color, $font, '面值：' . $money . ' 元');
        $pic_name = $no . time() . '.png';
        $pic = '../public/cash_user_card/' . $pic_name;
//        imagejpeg($image, dirname(ROOT_PATH), $pic, 50);
        imagepng($image, $pic);
//        imagedestroy($image);
        $path = $pic;
        return $pic_name;
    }







    //----------------领取红包修改------------------------------------------
    //获取微信信息
    public function get_wx_info()
    {
        $data = input('get.');
        session('wx_user_id', $data['user_id']);
        session('wx_user_info', $data);
        //dump($data);die;
        return view('checkUser_test');
    }

    //收红包
    public function getRedPack_new()
    {
        $red_sign = session('sign');
        if ($red_sign == '') {
            return json(array('status' => 0, 'msg' => '红包异常'));
        }
        //判断标志码
        $res = Db::table('tea_red_pack')->where('red_sign', '=', $red_sign)->find();
        if (intval($res['pack_statue']) == 1) {
            return json(array('status' => 1, 'msg' => ''));
        } else {
            return json(array('status' => 0, 'msg' => '红包已被领取过,'));
        }
    }

    //先判断
    //提示是否需要绑定
    //是否需要合并
    //修改页面
    public function checkUser()
    {
        //获取标志
        $sign = input('get.aa');
        session('sign',$sign);
//        $list = array('wx_user_id'=>3025,'open_id'=>'obmp11A7Rka5SEWLZmFGKSZT6oW0');
//        session('wx_user_id', 3025);
//        session('wx_user_info', $list);
        $sin = 0;
        if (session('wx_user_id') === '' || $sin == 0) {
            $this->redirect("http://www.tmvip.cn/mobile/oauth?type=wechat&back_url=http://vip.guochamall.com/newapp/Share/get_wx_info");
        } else {
            //dump()
            return view('checkUser_test');
        }

    }


    //获取用户余额
    public function get_card_money($user_id)
    {
        $res = Db::connect(config('db_config2'))->field('vid,card_money')->table('taom_value_card')->where("user_id = $user_id")->find();
        if (!$res) {
            return array();
        }
        return $res;
    }

    //获取储蓄卡类型
    public function get_card_cate()
    {
        $res = Db::connect(config('db_config2'))->field('id')->table('taom_value_card_type')->order('id desc')->find();
        if (!$res) {
            return array();
        }
        return $res;

    }

    //新建会员
    public function add_new_card($data)
    {
        $card_cate = $this->get_card_cate();
        if (empty($card_cate)) {
            return false;
        }
        $data['tid'] = $card_cate['id'];
        $data['value_card_sn'] = $this->get_card_sn();
        $data['value_card_password'] = '123456';
        $user_id = intval($data['user_id']);
        $data['bind_time'] = Db::connect(config('db_config2'))->table('taom_users')->field('reg_time')->where("user_id = $user_id")->find()['reg_time'];
        $data['end_time'] = $data['bind_time'] + 30 * 365 * 24 * 86400;
        //dump($data);
        $res = Db::connect(config('db_config2'))->table('taom_value_card')->insert($data);
        if ($res) {
            $this->add_card_log(intval($data['user_id']), 0, 0, $data['card_money']);
        }
        return $res;
    }

    //修改会员余额
    public function change_card_money($user_id, $money, $type)
    {
        if ($type == 1) {
            $res = Db::connect(config('db_config2'))->table('taom_value_card')->field('card_money')->where("user_id = $user_id")->setInc('card_money', $money);
        }
        if ($type == 2) {
            $res = Db::connect(config('db_config2'))->table('taom_value_card')->field('card_money')->where("user_id = $user_id")->setDec('card_money', $money);
        }
        if ($res && $type == 2 && $money != 0) {
            $this->add_card_log($user_id, 0, $money, 0);
        }
        if ($res && $type == 1 && $money != 0) {
            $this->add_card_log($user_id, 0, 0, $money);
        }
        return $res;
    }

    //添加消费记录
    public function add_card_log($user_id, $order_id, $use_val, $add_val)
    {
        $res = $this->get_card_money($user_id);
        if (!empty($res)) {
            $list['vc_id'] = (int)$res['vid'];
            $list['order_id'] = $order_id;
            $list['use_val'] = $use_val;
            $list['add_val'] = $add_val;
            $list['record_time'] = time();
            //dump($list);
            Db::connect(config('db_config2'))->table('taom_value_card_record')->insert($list);
        }

    }

    public function get_card_sn()
    {
        $res = Db::connect(config('db_config2'))->table('taom_value_card')->field('value_card_sn')->where('value_card_sn', 'like', '%gc_%')->order('vid desc')->limit(1)->find();
        if (!$res) {
            return 'gc_000000000001';
        }
        $res_arr = explode('_', $res['value_card_sn']);
        $num = intval($res_arr[1]) + 1;
        $num = str_pad($num, 12, "0", STR_PAD_LEFT);
        return $res_arr['0'] . "_" . $num;
    }

    //
    public function user_get_pack_new()
    {
        //$data = ['user_id'=>81,'unionid'=>'obmp11F08hUzUQNLVYPkXly8CERU'];
        //session('wx_user_id',3004);
        //session('wx_user_info',$data);
        //1.获取信息
        $mobile_phone = input('post.mobile_phone');
        ///$red_sign = input('post.sign');
        $user_name = input('post.user_name');
        $code = intval(input('post.code'));
        $wx_u_id = (int)session('wx_user_id');
        $red_sign = session('sign');
//        $mobile_phone = "13545236337";
//        $user_name = '1111';
//        $red_sign = '154535491399230000';
        //获取红包信息
        $res = Db::table('tea_red_pack')->where('red_sign', '=', $red_sign)->find();
        if (!$res) {
            return json(array('status' => 0, 'msg' => '领取失败!!'));
        }
//        $code = 1;
//        session('code',1);
        //2.判断验证码
        if ($code == intval(session('code'))) {
            //验证红包是否过期

            if (intval($res['pack_statue']) == 1) {
                //3。判断手机号是否被使用
                $info = Db::connect(config('db_config2'))->name("users")->where('mobile_phone', '=', $mobile_phone)->find();
                if ($info && $info['user_id'] != $wx_u_id) {

                    return json(array('status' => 3, 'msg' => '手机号已近存在', 'data' => $info));
                } else {
                    //4.判断用户手机号是否一致
                    $u_mp = Db::connect(config('db_config2'))->name("users")->field('mobile_phone,user_id')->where('user_id', '=', $wx_u_id)->find();
                    if ($u_mp && $u_mp['mobile_phone'] != $mobile_phone && $u_mp['mobile_phone'] != '') {
                        return json(array('status' => 4, 'msg' => '手机号已存在', 'data' => $u_mp));
                    } else {
                        //5.更新账户基本信息
                        $ec_salt = mt_rand(10000, 99999);
                        $password = mt_rand(100000, 999999);
                        $password = md5(md5($password) . $ec_salt);
                        $list_info = array('nick_name' => $user_name, 'mobile_phone' => $mobile_phone, 'password' => $password,
                            'tm_salt' => $ec_salt, 'sex' => 0, 'reg_time' => time(), 'parent_id' => intval($res['user_id']));

                        $add_res = Db::connect(config('db_config2'))->name("users")->where("user_id", '=', session('wx_user_id'))->update($list_info);
                        //更新用户储蓄卡
                        $usr_card = $this->get_card_money(session('wx_user_id'));

                        if (empty($usr_card)) {
                            //不存在卡，新建一张卡
                            $list['user_id'] = session('wx_user_id');
                            $list['card_money'] = floatval($res['red_pack']);
                            $this->add_new_card($list);
                        } else {
                            //更新
                            $this->change_card_money(session('wx_user_id'), floatval($res['red_pack']), 1);
                        }

                        $u_inf = Db::connect(config('db_config4'))->name("user")->where("vip_id = $wx_u_id")->find();
                        //dump($u_inf);

                        if ($u_inf) {
                            //同步余额
                            $Vip_data = $this->add_user_cash($wx_u_id, $user_name, $mobile_phone, $res['red_pack'], 1);
                        } else {
                            //新建
                            $Vip_data = $this->add_user_cash($wx_u_id, $user_name, $mobile_phone, $res['red_pack'], 0);
                        }
                        //dump($Vip_data);
                        //7.添加余额获取记录
                        if ($Vip_data) {
                            //修改红包状态
                            Db::table('tea_red_pack')->where('red_sign', '=', "$red_sign")->update(['get_red_id' => $add_res, 'pack_statue' => 0, 'get_red_time' => time(), 'get_user_name' => $user_name]);
                            return json(array('status' => 1, 'msg' => '1111', 'data' => $Vip_data));
                        } else {
                            return json(array('status' => 0, 'msg' => '领取失败'));
                        }
                    }
                }
            } else {
                return json(array('status' => 2, 'msg' => '红包已被领取过'));
            }
        } else {
            return json(array('status' => 0, 'msg' => '验证码错误!!'));
        }
    }

    public function relative_user_info()
    {
        $mobile_phone = input('post.mobile_phone');
        //$red_sign = input('post.sign');
        $user_name = input('post.user_name');
        $u_id = intval(input('post.user_id'));
        $red_sign = session('sign');
        //$wx_u_id = (int)session('wx_user_id');
//        $mobile_phone = "13545236337";
//        $user_name = '1111';
//        $red_sign = '154535491399230000';
//        $u_id=3026;
        //获取红包信息
        $res = Db::table('tea_red_pack')->where('red_sign', '=', $red_sign)->find();
        if ($u_id) {
            //关联用户--需要关联的用户
            $user['user_id'] = $u_id;
            //绑定
            $user_id = intval(session('wx_user_id'));  //微信返回的
            //$user_id = 3025;
            $relative1 = Db::connect(config('db_config2'))->name("connect_user")->where('user_id', '=', $user_id)->find();
            //dump($relative1);

                if (!$relative1) {
                return json(array('status' => 0, 'msg' => '关联出错!'));
            } else {
                //是否存在
                $connect_user_id = Db::connect(config('db_config2'))->name('connect_user')->where(array('user_id' => intval($user['user_id']), 'connect_code' => 'sns_wechat'))->count();
                if (($connect_user_id == 0) && (intval($user['user_id'] != intval($user_id)))) {
                    $relative = Db::connect(config('db_config2'))->name("connect_user")->where('id', '=', $relative1['id'])->update(['user_id' => intval($user['user_id'])]);
                    if ($relative) {
                        //更新关联后用户的余额
                        //更新用户储蓄卡
                        $usr_card = $this->get_card_money(intval($user['user_id']));
                        if (empty($usr_card)) {
                            //不存在卡，新建一张卡
                            $list['user_id'] = intval($user['user_id']);
                            $list['card_money'] = floatval($res['red_pack']);
                            $this->add_new_card($list);
                        } else {
                            //更新
                            $this->change_card_money(intval($user['user_id']), floatval($res['red_pack']), 1);
                        }
                        //判断用户是否存在线下账户
                        $u_inf = Db::connect(config('db_config4'))->name("user")->where("vip_id = $u_id")->find();
                        if ($u_inf) {
                            //同步余额
                            $Vip_data = $this->add_user_cash($u_id, $user_name, $mobile_phone, $res['red_pack'], 1);
                        } else {
                            //新建
                            $Vip_data = $this->add_user_cash($u_id, $user_name, $mobile_phone, $res['red_pack'], 0);
                        }
                        if ($Vip_data) {
                            //修改红包状态
                            Db::table('tea_red_pack')->where('red_sign', '=', "$red_sign")->update(['get_red_id' => $u_id, 'pack_statue' => 0, 'get_red_time' => time(), 'get_user_name' => $user_name]);
                            return json(array('status' => 1, 'msg' => '1111', 'data' => $Vip_data));
                        } else {
                            return json(array('status' => 0, 'msg' => '领取失败'));
                        }
                        return json(array('status' => 1, 'msg' => '关联完成'));
                    } else {
                        return json(array('status' => 0, 'msg' => '关联出错!!'));
                    }
                } else {
                    return json(array('status' => 0, 'msg' => '此用户已关联'));
                }
            }

        } else {
            //不关联
            //更新用户信息
            $ec_salt = mt_rand(10000, 99999);
            $password = mt_rand(100000, 999999);
            $password = md5(md5($password) . $ec_salt);
            $list_info = array('nick_name' => $user_name, 'mobile_phone' => $mobile_phone, 'password' => $password,
                'tm_salt' => $ec_salt, 'sex' => 0, 'reg_time' => time(), 'parent_id' => intval($res['user_id']));

            $add_res = Db::connect(config('db_config2'))->name("users")->where("user_id", '=', session('wx_user_id'))->update($list_info);

            //更新用户储蓄卡
            $usr_card = $this->get_card_money(session('wx_user_id'));
            if (empty($usr_card)) {
                //不存在卡，新建一张卡
                $list['user_id'] = session('wx_user_id');
                $list['card_money'] = floatval($res['red_pack']);
                $this->add_new_card($list);
            } else {
                //更新
                $this->change_card_money(session('wx_user_id'), floatval($res['red_pack']), 1);
            }

            //新建
            $Vip_data = $this->add_user_cash(session('wx_user_id'), $user_name, $mobile_phone, $res['red_pack'], 0);
            if ($Vip_data) {
                //修改红包状态
                Db::table('tea_red_pack')->where('red_sign', '=', "$red_sign")->update(['get_red_id' => session('wx_user_id'), 'pack_statue' => 0, 'get_red_time' => time(), 'get_user_name' => $user_name]);
                return json(array('status' => 1, 'msg' => '1111', 'data' => $Vip_data));
            } else {
                return json(array('status' => 0, 'msg' => '领取失败'));
            }

        }
    }

    public function update_user()
    {
        $mobile_phone = input('post.mobile_phone');
        //$red_sign = input('post.sign');
        $user_name = input('post.user_name');
        $wx_u_id = (int)session('wx_user_id');
        $up = (int)input('post.up');
        $red_sign = session('sign');
//        session('wx_user_id',3026);
//        $mobile_phone = "13545236337";
//        $user_name = '1111';
//        $red_sign = '154535491399230000';
//        $up = 1;
        //获取红包信息
        $res = Db::table('tea_red_pack')->where('red_sign', '=', $red_sign)->find();
        if ($up) {
            //更新
            Db::connect(config('db_config2'))->name("users")->where("user_id", '=', $wx_u_id)->update(['mobile_phone' => $mobile_phone]);
        }
        //更新用户信息
        $ec_salt = mt_rand(10000, 99999);
        $password = mt_rand(100000, 999999);
        $password = md5(md5($password) . $ec_salt);
        $list_info = array('nick_name' => $user_name, 'mobile_phone' => $mobile_phone, 'password' => $password,
            'tm_salt' => $ec_salt, 'sex' => 0, 'reg_time' => time(), 'parent_id' => intval($res['user_id']));

        $add_res = Db::connect(config('db_config2'))->name("users")->where("user_id", '=', session('wx_user_id'))->update($list_info);

        //更新用户储蓄卡
        $usr_card = $this->get_card_money(session('wx_user_id'));
        if (empty($usr_card)) {
            //不存在卡，新建一张卡
            $list['user_id'] = session('wx_user_id');
            $list['card_money'] = floatval($res['red_pack']);
            $this->add_new_card($list);
        } else {
            //更新
            $this->change_card_money(session('wx_user_id'), floatval($res['red_pack']), 1);
        }
        //判断用户是否存在线下账户
        $u_inf = Db::connect(config('db_config4'))->name("user")->where("vip_id = $wx_u_id")->find();
        if ($u_inf) {
            //同步余额
            $Vip_data = $this->add_user_cash($wx_u_id, $user_name, $mobile_phone, $res['red_pack'], 1);
        } else {
            //新建
            $Vip_data = $this->add_user_cash($wx_u_id, $user_name, $mobile_phone, $res['red_pack'], 0);
        }
        if ($Vip_data) {
            //修改红包状态
            Db::table('tea_red_pack')->where('red_sign', '=', "$red_sign")->update(['get_red_id' => session('wx_user_id'), 'pack_statue' => 0, 'get_red_time' => time(), 'get_user_name' => $user_name]);
            return json(array('status' => 1, 'msg' => 'success', 'data' => $Vip_data));
        } else {
            return json(array('status' => 0, 'msg' => '领取失败'));
        }
    }

    //新建线下会员
    public function add_user_cash($user_id, $user_name, $mobile_phone, $red_pack, $up)
    {
        //6.收银后台新建账号
        $rank = Db::connect(config('db_config4'))->name("discount_rank")->where('shop_id', '=', 5)
            //->whereOr('is_public','=',1)
            ->order('id desc')->limit(1)->find();
        $work_info = Db::connect(config('db_config4'))->name("admin")->where('shop_id', '=', 5)->order('id desc')->limit(1)->find();
        //操作人id
        //获取卡余额
        $card = $this->get_card_money($user_id);
        if (empty($card)) {
            $card['card_money'] = 0;
        }
        if ($up) {
            $Vip_data = Db::connect(config('db_config4'))->name("user")->where("vip_id = $user_id")->update(['dollar_sum' => floatval($card['card_money'])]);
        } else {
            $work_info = Db::connect(config('db_config4'))->name("admin")->where('shop_id', '=', 5)->order('id desc')->limit(1)->find();
            $list1 = array('vip_id' => session('wx_user_id'), "vip_user" => $user_name, "card_num" => substr(date('Y'), 2, 3) . date('m') . date('d') . time()
            , "lev" => intval($rank['id']), "first_dollar" => 0, "shop_id" => 5
            , "work_id" => intval($work_info['id']), "dollar_sum" => floatval($card['card_money']), 'rech_money' => floatval($red_pack), 'year' => date('Y')
            , 'month' => date('m'), 'day' => date('d'));
            $Vip_data = Db::connect(config('db_config4'))->name("user")->insert($list1);
        }

        if ($Vip_data || $Vip_data == 0) {
            $lists = array(
                "vip_id" => session('wx_user_id'), "balancec" => 0, "recharge_dollar" => floatval($red_pack)
            , "give_dollar" => 0, "exchange_dollar" => 0, "use_type" => 1, "recharge_time" => time()
            , "shop_id" => 5, "work_id" => intval($work_info['id'])
            , "money_sum" => floatval($card['card_money']), "vip_user" => $user_name
            , "tel" => $mobile_phone, "recharge_num" => time() . intval($user_id) . mt_rand(1000, 9999)
            , "card_num" => '', "year" => date("Y"), "month" => date("m"), "day" => date("d"), 'detail' => '红包领取');
            Db::connect(config('db_config4'))->name("user_recharge")->insert($lists);
            return $lists['recharge_num'];
        } else {
            return false;
        }
    }

}
