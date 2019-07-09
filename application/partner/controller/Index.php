<?php

namespace app\partner\controller;

use think\Db;
use think\Session;
use app\partner\model\Gudong;

class Index extends Co
{
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
    //短信验证码 OK
    public function test()
    {
        $data = "sdhfkashdjkfhasjkhdfjhsadfhjkashdkjfhjkasd";
        $data = json_encode($data);
        vendor('barcodegen.class.BCGFontFile');
        //vendor('barcodegen.class.BCGColor');
        vendor('barcodegen.class.BCGDrawing');
        require_once('../vendor/barcodegen/class/BCGcode128.barcode.php');
        $colorFront = new \BCGColor(0, 0, 0);
        $colorBack = new \BCGColor(255, 255, 255);
        $font = new \BCGFontFile("./Arial.ttf", 14);
        // $font =$_SERVER['HTTP_HOST']."/vendor/barcodegen/font/Arial.ttf";
        $code = new \BCGcode128();
        $code->setScale(2);
        $code->setFont($font);
        $code->setColor($colorFront, $colorBack);
        $code->parse("$data");

        // Drawing Part
        $a = time();
        $drawing = new \BCGDrawing("$a.png", $colorBack);
        $drawing->setBarcode($code);
        $drawing->draw();
        //dump($drawing);die;
        header('Content-Type: image/png');

        return $drawing->finish(\BCGDrawing::IMG_FORMAT_PNG);
    }
    public function aaaa(){
        //获取用户输入的用户名 密码
        $username = trim('郎彩霞');
        $password = trim('123456');

        //判断用户名是否存在
        $userobj = new Gudong();
        //print_r($userobj);
        //通过访问淘米平台数据库查看用户名是否存在
        $user = Db::connect(config('db_config2'))->name("users")->where('user_name', $username)->find();
        //dump($user);die;
        //判断是不是股东
//        if($user && intval($user['user_rank'])!=9) return json(array("data" => "不是股东无法登陆", 'status' => 9));
        if (!$user) {
            return json(array("data" => "用户不存在", 'status' => 2));
        } else {
            //是否冻结
            $user_id = $user['user_id'];
            $usernifo = Db::table('tea_user')->where('user_id', $user_id)->find();
            //判断是否为会员
            if($usernifo){
                if ($usernifo['wait'] == 0) {
                    return json(array("data" => "该账户被冻结", 'status' => 3));
                }
            }else{
                //将这个会员插入到数据库里面去
                Db::table('tea_user')->insert(['user_id'=>$user_id,'is_ceo'=>1]);
            }


//                if ($usernifo['is_ceo'] == 0) {
//                    //return json(array('status' => 4, 'data' => "不是股东"));
//                }
            //用户名存在  判断密码是否正确
            $password =empty($user['tm_salt'])?  md5($password): md5(md5($password).$user['tm_salt']);
            //dump($password);die;$str='e10adc3949ba59abbe56e057f20f883e'.'e10adc3949ba59abbe56e057f20f883e';
            $res = $userobj->pwd_exit($username, $password);
            if ($res) {
                return json(array("data" => "登陆成功", 'status' => 1));
            } else {
                return json(array("data" => "密码错误", 'status' => 0));
            }
        }
    }

    //显示首页
    public function index()
    {
        //首先查出股东的权益
        $rechargeData = Db::table('tea_new_recharge')
            ->select();
        return view('index', ['data' => $rechargeData]);
    }

    //显示我的个人中心首页
    public function custom_info()
    {
        $this->checkLogin();
        $user_id = session('user_id');
        //$user_id=session('user_id');
        //显示个人中心的用户名
        $user_info = Db::connect(config('db_config2'))->name("users")->where('user_id', $user_id)->find();
        if($user_info['user_picture'] ==''){
            $user_info['user_picture']='http://'.$_SERVER['HTTP_HOST'].'/newtea/images/mrimg.png';
        }
        //dump($user_info);die;
        return view('custom_info', ['data' => $user_info]);
    }

    //清理 runtime 下的 tmp下的文件
    function delDirAndFile()
    {
        $dirName = RUNTIME_PATH . 'temp';
        if ($handle = opendir("$dirName")) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (is_dir("$dirName/$item")) {
                        delDirAndFile("$dirName/$item");
                    } else {
                        echo unlink("$dirName/$item") ?: $this->error('清除失败');
                    }
                }
            }
            closedir($handle);
            echo rmdir($dirName) ? $this->success('清除成功') : $this->error('清除失败');
        }
    }

    //将理茶宝的连接生成二维码
    public function qrcode()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/appmobile/user/index.asp';
        Vendor('phpqrcode.phpqrcode');
        //生成二维码图片
        $object = new \QRcode();
        ob_clean();
        $level = 'M';
        $size = 4;
        $errorCorrectionLevel = intval($level);//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        $filename = './index/images/' . 'lcb' . '.png';
        $object->png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

        $QR = $filename;                //已经生成的原始二维码图片文件


        $QR = imagecreatefromstring(file_get_contents($QR));

        //输出图片
        imagepng($QR, 'qrcode.png');
        imagedestroy($QR);
    }

    //将股东的连接生成二维码
    public function gdqrcode()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/partner/index/index.asp';
        Vendor('phpqrcode.phpqrcode');
        //生成二维码图片
        $object = new \QRcode();
        ob_clean();
        $level = 'M';
        $size = 4;
        $errorCorrectionLevel = intval($level);//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        $filename = './index/images/' . 'gd' . '.png';
        $object->png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

        $QR = $filename;                //已经生成的原始二维码图片文件


        $QR = imagecreatefromstring(file_get_contents($QR));

        //输出图片
        imagepng($QR, 'qrcode.png');
        imagedestroy($QR);
    }

    //删除log下前几天的文件
    function delBeforeDayLog()
    {
        for ($i = 1; $i < 10; $i++) {
            $day = date('Ym') - $i;
            //dump( $yesterday);
            $dirName = RUNTIME_PATH . 'log/' . $day;
            //dump($dirName);die;
            if ($handle = opendir("$dirName")) {
                while (false !== ($item = readdir($handle))) {
                    if ($item != "." && $item != "..") {
                        if (is_dir("$dirName/$item")) {
                            delBeforeDayLog("$dirName/$item");
                        } else {
                            unlink("$dirName/$item") ?: $this->error('删除失败');
                        }
                    }
                }
                closedir($handle);
                echo rmdir($dirName) ? $this->error('成功') : $this->error('删除失败');
            }
        }
    }


    //显示账户资产
    public function account_assets()
    {
        $this->checkLogin();
        $user_id = session('user_id');
        //获取所有的积分
        $user_info = Db::table('tea_user')->field('tea_ponit_inte,tea_inte,wallet')->where('user_id', $user_id)->find();
        //查询出最近的一次分红
        $last_inte = Db::table('tea_ceo_bonus')->where('user_id', $user_id)->order('bonus_id desc')->limit(1)->find();
        if (empty($last_inte)) {
            $data['last_inte'] = 0.00;
        } else {
            $data['last_inte'] = floatval($last_inte['bonus_money']);
        }
        //累计分红
        $fenhong = Db::table('tea_ceo_bonus')->where('user_id', $user_id)->SUM('bonus_money');
        if (empty($fenhong)) {
            $data['fenhong'] = 0.00;
        } else {
            $data['fenhong'] = floatval($fenhong);
        }
        $data['user_info'] = floatval($user_info['tea_ponit_inte']) + floatval($user_info['tea_inte']);
        $allMoney = $data['user_info'] + $data['fenhong'] + $data['last_inte'];
        //最新收益百分比
        if($allMoney==0.00){
            $new = 0;
            $all = 0;
            $tea_inte =0;
        }else{
            $new = round($data['last_inte'] / $allMoney, 4) * 100;
            $all = round($data['fenhong'] / $allMoney, 4) * 100;
            $tea_inte = round($data['user_info'] / $allMoney, 4) * 100;
        }
        $this->assign('new', $new);
        $this->assign('all', $all);
        $this->assign('user', $user_info);
        $this->assign('tea_inte', $tea_inte);
        $this->assign('data', $data);
        return $this->fetch();
    }

    //显示股东历史分红记录
    public function bonus_log()
    {
        $this->checkLogin();
        $user_id = session('user_id');
        $data = Db::table('tea_ceo_bonus')->where('user_id', $user_id)->select();
        foreach ($data as $k => $v) {
            $data[$k]['storename'] = Db::connect(config('db_config2'))->name("offline_store")->field('stores_name')->where('id=' . intval($v['store_id']))->find();
        }
        $sum = Db::table('tea_ceo_bonus')->where('user_id', $user_id)->SUM('bonus_money');
        $this->assign('data', $data);
        $this->assign('mon', $sum);
        return $this->fetch();
    }

    //我的记录页面
    public function recode()
    {
        $this->checkLogin();
        return $this->fetch();
    }


    //显示功能暂未开放页面
    public function willopen()
    {
        return $this->fetch();
    }

    //将理茶宝的连接生成二维码
    public function aa()
    {
        //$url = 'http://' . $_SERVER['HTTP_HOST'] . '/appmobile/user/index.asp';
        $url = 'wxp://f2f02p8fUyOo_SdwG3ro8HjVxO0OBXYhJ5Op';
        Vendor('phpqrcode.phpqrcode');
        //生成二维码图片
        $object = new \QRcode();
        ob_clean();
        $level = 'H';
        $size = 10;
        $errorCorrectionLevel = intval($level);//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        $filename = './index/images/' . 'dashang_weixin'. time(). '.png';
        $object->png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

        $logo = './index/images/logo.jpg';  //准备好的logo图片
        $QR = $filename;                //已经生成的原始二维码图片文件

        if (file_exists($logo)) {
            $QR = imagecreatefromstring(file_get_contents($QR));        //目标图象连接资源。
            $logo = imagecreatefromstring(file_get_contents($logo));    //源图象连接资源。
            $QR_width = imagesx($QR);           //二维码图片宽度
            $QR_height = imagesy($QR);          //二维码图片高度
            $logo_width = imagesx($logo);       //logo图片宽度
            $logo_height = imagesy($logo);      //logo图片高度
            $logo_qr_width = $QR_width / 4;     //组合之后logo的宽度(占二维码的1/5)
            $scale = $logo_width/$logo_qr_width;    //logo的宽度缩放比(本身宽度/组合后的宽度)
            $logo_qr_height = $logo_height/$scale;  //组合之后logo的高度
            $from_width = ($QR_width - $logo_qr_width) / 2;   //组合之后logo左上角所在坐标点

            //重新组合图片并调整大小
            /*
             *  imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
             */
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
        }
        //输出图片
        imagepng($QR, './index/images/' . 'dashang_weixin'. time(). '.png');
        imagedestroy($QR);
        imagedestroy($logo);
    }
    public function bb(){
        $url = 'https://qr.alipay.com/a6x02149ew4ll2vmxd6wl9d';
        Vendor('phpqrcode.phpqrcode');
        //生成二维码图片
        $object = new \QRcode();
        ob_clean();
        $level = 'M';
        $size = 10;
        $errorCorrectionLevel = intval($level);//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        $filename = './index/images/' . 'dashang_weixin'. time(). '.png';
        $object->png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

        $QR = $filename;                //已经生成的原始二维码图片文件


        $QR = imagecreatefromstring(file_get_contents($QR));

        //输出图片
        imagepng($QR, 'qrcode.png');
        imagedestroy($QR);
    }
    //显示我的订单页面
    public function order(){
        $this->checkLogin();
        $user_id = session('user_id');
        $data=Db::table('tea_order')->alias('o')
            ->where('o.user_id',$user_id)
            ->join('tea_order_cart c','c.order_id=o.order_id')
            ->select();
        $this->assign('data', $data);
        return $this->fetch();
    }
    //显示我的提现记录页面
    public function postal(){
        $this->checkLogin();
        return $this->fetch();
    }
    //显示我的提现记录明细页面
    public function posyalindex(){
        $this->checkLogin();
        return $this->fetch();
    }
}