<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/4/9
 * Time: 11:36
 */

namespace app\partner\controller;

use app\partner\model\RechargeCart;
use app\appmobile\controller\User;
use app\tmvip\controller\Api;
use think\Db;
use app\api\controller\Webalpay;

class Recharge extends Co
{
    private $gufen=1.5;     //购买股东对应的股份，可以修改的
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

    //显示购买页面
    public function confirm_buy($id)
    {
        $this->checkLogin();
        //通过cookie存的数据来显示已经选择的门店
        $store_id=cookie('store_id');
        if(empty($store_id)){
            $store_name['stores_name']='请选择门店';
            $store_name['id']=0;
        }else{
            $store_name=Db::connect(config('db_config2'))->name("offline_store")->where('id',$store_id)
                ->find();
        }

        $recharge_id = intval($id);
        $data = Db::table('tea_new_recharge')
            ->where('recharge_id', $recharge_id)
            ->find();
        return view('confirm_buy', ['data' => $data,'store'=>$store_name]);
    }

    //传递recharge信息
    public function findOneById()
    {
        $recharge_id = intval(input('post.id'));
        $data = Db::table('tea_new_recharge')
            ->where('recharge_id', $recharge_id)
            ->find();
        return json($data);
    }

    //用户购买门店股权，第一次购买以及多次购买
        public function buyManage1111()
    {

        $this->checkLogin();
        $user_id = session('user_id');
        $recharge_id = intval(input('post.recharge_id'));   //获取传递过来的股东Id
        $store_id=intval(input('post.store_id'));           //获取传递过来的门店ID
        if(empty($store_id)) return 9;
        $rechargeData = Db::table('tea_new_recharge')->where('recharge_id', $recharge_id)->find();
        //判断用户的上级是否购买
        $recharge_user_info=$this->buyRecharge($user_id);
        if($recharge_user_info===false) return 8;   //上级未购买，无法购买
        //判断是否在一个门店超过了两份
        if(Db::table('tea_recharge_cart')->where('user_id',$user_id)->where('store_id',$store_id)->count()>=2) return 6;
        //判断用户是否实名认证了。现在取消此方法
//        $user_con = new User();
//        $user_info = $user_con->is_real();
//        if ($user_info == 0) {
//            return 3;
//        }
//        //首先判断是否允许客户购买
//        $res = $this->allowBuy($user_id, $rechargeData['rec_money']);
//        if (!$res) {
//            return 0;
//            //  $this->error('请在理茶宝积分返还完再购买',U('index/index'));
//        }
        // 判断是否有未支付股东订单 ***************
        $nopayorder = Db::table('tea_recharge_cart')
            ->where([
                'pay_status' => 0,
                'is_ceo' => 1,
                'user_id' => $user_id
            ])
            ->find();
        if ($nopayorder) {
            return 4;       //有尚未支付的固定订单，请支付或者取消
        }
        //只有用户支付成功才允许数据入库
        $model = new RechargeCart();
        $res = $model->buyManage($recharge_id, $user_id, $rechargeData['rec_money'],$store_id);
        if (!$res) {
            return 5;   //下单购买失败
        }
        //下单成功，跳转到支付宝支付，带上Id
        $data['status'] = 1;
        $data['id'] = $res;
        cookie('store_id',null);
        return json($data);
    }

    //取消相关的订单
    public function del()
    {

        $this->checkLogin();
        //理茶宝订单表ID
        $id = intval(input('post.id'));
        $user_id = session('user_id');
        //取消订单时将订单所有的茶籽返还到积分表中去
        //在模型中调用这个方法
        $model = new RechargeCart();
        $res = $model->del($user_id, $id);
        if ($res) {
            return 1;
        } else {
            return 0;
        }
    }

    //订单详情取消股东订单
    public function delrecharge($id)
    {
        $this->checkLogin();
        //理茶宝订单表ID
        $user_id = session('user_id');
        //取消订单时将订单所有的茶籽返还到积分表中去
        //在模型中调用这个方法
        $res = Db::table('tea_recharge_cart')
            ->where('id', $id)
            ->delete();
        if ($res) {
            $this->success('取消成功', 'shareholder/inte_info');
        } else {
            $this->error('取消失败', 'shareholder/inte_info');
        }
    }

    //显示个人中心的我的理茶宝订单
    public function myRechargeIndex()
    {
        $this->checkLogin();
        $user_id = session('user_id');
        $data = Db::table('tea_recharge_cart')
            ->alias('c')
            ->field('c.*,r.rec_money')
            ->where('c.user_id', $user_id)
            ->join('tea_recharge r', 'r.id=c.recharge_id')
            ->select();
        return json($data);
    }

    //在我的理茶宝页面，点击购买激活的时候查看，用户信息完整验证
    public function checkToActive()
    {
        $user_id = session('user_id');
        $userApi = new User();
        $user_info = $userApi->is_real();
        if ($user_info == 0) {
            $data['status'] = 0;
            $data['msg'] = "信息不完整";
            //$this->error('尚未填写推荐人，前往填写',U('user/my_recommender'));
            return json($data);
        }
        $actioninfo = Db::table('tea_user_recharge')
            ->where("user_id=$user_id AND is_return=1")
            ->order('addtime desc')
            ->limit('1')
            ->find();
        if ($actioninfo) {
            //已经购买了
            $data['status'] = 2;
            return json($data);
        }
        // 判断是否有未支付理茶宝订单 ***************
        $nopayorder = Db::table('tea_recharge_cart')
            ->where([
                'pay_status' => 0,
                'user_id' => $user_id
            ])
            ->find();
        if ($nopayorder) {
            $data['status'] = 3;
            return json($data);
            //有尚未支付的理茶宝产品
        }


        $data['status'] = 1;
        return json($data);

    }

    //前往股东确认支付页面
    public function confirm($id)
    {
        $user_id = session('user_id');
        $data = Db::table('tea_recharge_cart')
            ->where('id', $id)
            ->find();
        $user_wallet = Db::table('tea_user')->where('user_id', $user_id)->find();
        $data['wallet'] = floatval($user_wallet['wallet']);
        $recharge_data = Db::table('tea_new_recharge')->where('recharge_id', $data['recharge_id'])->find();
        $this->assign('data', $data);
        $this->assign('recharge', $recharge_data);
        return $this->fetch();
    }

    //移动端
    public function webalpay()
    {
        //判断用户是否伪造ID进行请求付款，以避免网站被黑
        // $this->checkLogin();
        $user_id = session('user_id');
        $reachrge_cart_id = intval(input('get.id'));
        $data = Db::table('tea_recharge_cart')
            ->where('id', $reachrge_cart_id)
            ->where('user_id', $user_id)
            ->where('is_ceo', 1)
            ->find();
        if (!$data) $this->error('数据非法，请住手', 'index/index');
        $money_money = floatval(input('get.money'));
        if ($money_money == 0.00) {
            //没有选择钱包的钱

            $recharge_data = Db::table('tea_recharge')
                ->where('id', $data['recharge_id'])
                ->find();
            $alpayController = new Webalpay();
            $alpayController->alpay($data['recharge_num'], $data['recharge_money'], $recharge_data['body'], $recharge_data['body'], $setReturnUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/api/Webalpay/buyReturnUrl', $notify_url = 'http://' . $_SERVER['HTTP_HOST'] . '/api/Webalpay/buyNotiflUrl');
        } else {
            //有选择钱包的钱
            $res = $this->payMoney($reachrge_cart_id, $money_money);
            if ($res['status'] == 0) {
                $this->error('服务器正在抢修中', 'index/custom_info');
            } elseif ($res['status'] == 1) {
                $this->success('支付成功', 'index/custom_info');
            } else
                $recharge_data = Db::table('tea_new_recharge')
                    ->where('recharge_id', $data['recharge_id'])
                    ->find();
            $alpayController = new Webalpay();
            $alpayController->alpay($data['recharge_num'], $data['recharge_money'], $recharge_data['body'], $recharge_data['body'], $setReturnUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/api/Webalpay/shareholderReturnUrl', $notify_url = 'http://' . $_SERVER['HTTP_HOST'] . '/api/Webalpay/shareholderNotiflUrl');
        }
    }

    //激活门店股权信息
    public function active_lcb()
    {
        $recharge_cart_id=intval(input('post.id'));       //tea_recharge_cart的ID
        $recharge_info=Db::table('tea_recharge_cart')->where('id',$recharge_cart_id)->find();
        $info = Db::table("tea_user_recharge")
            ->where('is_active =0')
            ->where('user_id', intval($recharge_info['user_id']))
            ->order('id desc')
            ->limit(1)
            ->find();
//        //以前是否购买过
//        $data = Db::table("tea_integral")
//            ->where('user_id',  intval($recharge_info['user_id']))
//            ->where('is_ceo', 1)
//            ->order('id desc')
//            ->limit(1)
//            ->find();
//        if ($data) {
//            //已买过，获得上次购买记录数据叠加
//
//            $res['total_sum'] = $data['total_sum'] + $info['total_inte'];   //总积分
//            //剩余注册积分=剩余注册积分+未激活产品的赠送注册积分-需要消耗的积分
//            $res['tea_inte'] = $data['tea_inte'];       //当前茶券
//            $res['tea_ponit_inte'] = $data['tea_ponit_inte'];   //当前茶点
//
//        } else {
//            $res['total_sum'] = $info['total_inte'];    //总积分
//            $res['tea_inte'] = 0;   //当前茶券
//            $res['tea_ponit_inte'] = 0; //当前茶点
//        }
        $res['total_sum'] = $info['total_inte'];    //总积分
        $res['tea_inte'] = 0;   //当前茶券
        $res['tea_ponit_inte'] = 0; //当前茶点
        $res['user_id'] =  intval($recharge_info['user_id']); //用户id
        $res['back_inte'] = 0;  //已返还积分
        $res['surplus_inte'] = $res['total_sum'];//剩余积分
        $res['last_time'] = date('Y-m-d');  //最后释放时间
        $res['is_return'] = 1;  //是否返还结束为未结束
        $res['addtime'] = time(); //记录产生时间
        $res['year'] = date("Y"); //记录产生时间
        $res['month'] = date("m"); //记录产生时间
        $res['day'] = date("d"); //记录产生时间
        $res['is_ceo'] = 1;//生成为股东的积分记录
        $time = time();
        //开启事务
        Db::startTrans();
        try {
            $res_insert = Db::table('tea_integral')->insertGetId($res); //插入积分表并返回主键值
            //将获取的积分表ID插入到股东门店表中
            $ceo_store=array(
                'user_id'=> intval($recharge_info['user_id']),
                'store_id'=>intval($recharge_info['store_id']),
                'inte_id'=>$res_insert,
                'gufen'=>$this->gufen
            );
            Db::table("tea_ceo_store")->insert($ceo_store);
            //将获取的积分表ID插入到新建数据库去
            $ceo_data = array(
                'integral_id' => $res_insert,
                'thistime' => date('Y-m-d'),
                'next_time' => date('Y-m-d', strtotime('+1 month')),
                'back_inte' => 0,
                'tims' => 0,
            );
            Db::table("tea_ceo_integral_log")->insert($ceo_data);

            //将用户理茶宝表字段修改为激活
            Db::table("tea_user_recharge")->where('id', $info['id'])->update(['is_active' => 1]);
            Db::table("tea_user_recharge")
                ->where('id', $info['id'])
                ->update(['rec_addtime' => $time]);
            Db::table("tea_recharge_cart")
                ->where('id', $recharge_cart_id)
                ->update(['is_active' => 1]);
            //提交事务
            Db::commit();
            return true;
        } catch (\Exception $e) {

            //事务回滚
            Db::rollback();
            return false;
        }
    }

    //显示所有的门店
    public function storeindex(){
//        $store_name=input('post.name');
//        if(empty($store_name)){
//            $data=Db::connect(config('db_config2'))
//                ->name("offline_store")
//                //->where('ru_id',1)
//                //->where("stores_name like '%$store_name%'")
//                ->select();
//        }else{
//            $data=Db::connect(config('db_config2'))
//                ->name("offline_store")
//                //->where('ru_id',1)
//                ->where("stores_name like '%$store_name%'")
//                ->select();
//        }
//
//        dump($data);die;
//        $this->assign('data',$data);
        return $this->fetch();
    }

    //点击搜索按钮之后显示对应的门店信息
    public function storeData(){
        $store_name=input('post.name');
        if(empty($store_name)){
            $store_name='';
        }
        $data=Db::connect(config('db_config2'))->name("offline_store")
            //->where('ru_id',61)
            ->where("stores_name", 'like',"%$store_name%")
            ->where("stores_name", 'not like',"%我的店%")
            ->where('is_confirm', 1)
            ->select();
        return json($data);
    }

    //当点击门店之后将门店ID存到cookie中
    public function cookie_store(){
        $store_id=intval(input('post.id'));
        $store_ids=cookie('store_id');
        if(empty($store_ids)){
            $data=cookie('store_id',$store_id);
        }else{
            cookie('store_id',null);
            $data=cookie('store_id',$store_id);
        }
        if($data){
            return 1;
        }else{
            return 0;
        }
    }

    //用户追加购买        现已失效方法
    public function addBuyManage()
    {
        $this->checkLogin();
        $user_id = session('user_id');
        //获取用户追加购买时参数
        $recharge_id = intval(input('post.reacharge_id'));
        $moeny = intval(input('post.rec_money'));
        //在个人中心实现追加购买
        //①首先判断用户购买的金额是否大于最后一次购买的金额
        $actioninfo = Db::table('tea_user_recharge')
            ->where('user_id', $user_id)
            ->where('is_return', 0)
            ->order('rec_addtime desc')
            ->limit('1')->find();
        if (intval($actioninfo['rec_money']) > $moeny) {
            return 0;
            //$this->error("当前追加购买的产品金钱数小于当前购买的钱数",U('index/customer_info'));
        }
        // 判断是否有未支付理茶宝订单 ***************
        $nopayorder = Db::table('tea_recharge_cart')
            ->where([
                'pay_status' => 0,
                'user_id' => $user_id
            ])
            ->find();
        if ($nopayorder) {
            return 4;
            // $this->error('您有尚未支付的理茶宝产品，请支付或者取消',U('index/customer_info'));
        }
        //②将购买的金额以及ID添加到积分购物车里面去
        $model = new RechargeCart();
        $res = $model->buyManage($recharge_id, $user_id, $moeny);
        if (!$res) {
            return 2;
            $this->error('下单购买失败');
        }
        //下单成功，跳转到支付宝支付，带上Id
        //下单成功，跳转到支付宝支付，带上Id
        $data['status'] = 1;
        $data['id'] = $res;
        return json($data);
    }

    //购买时判断当前用户的上级是否有购买股东权益
    private function buyRecharge($user_id){
        $user_info=Db::connect(config('db_config2'))->field('parent_id')->name("users")->where("user_id",$user_id)->find();
        $parent_id=intval($user_info['parent_id']);
        if($parent_id==0) return true;
        $recharge_info=Db::table('tea_integral')->where("user_id",$parent_id)->find();
        if($recharge_info){
            return true;
        }else{
            return false;
        }
    }
    //判断用户是否购买权限     现已失效方法
    public function allowBuy($user_id, $recharge_money)
    {
        //首先判断用户是否有过购买记录，然后判断是否允许购买以及再次购买
        $res = Db::table('tea_user_recharge')
            ->where([
                'is_return' => 1,
                'is_ceo' => 1,
                'user_id' => $user_id
            ])
            ->find();
        if ($res) {
            //如果存在就是再次购买
            $this->allowAddBuy($user_id, $recharge_money);
        } else {
            return true;
        }
    }
    //判断用户是否能再次购买    现已失效方法
    public function allowAddBuy($user_id, $recharge_money)
    {
        //根据用户的购买记录查询最后一次的购买钱数
        //只允许购买大于等于最后一次的购买的钱数
        $actioninfo = Db::table('tea_user_recharge')
            ->where([
                'is_return' => 0,
                'user_id' => $user_id
            ])
            ->order('rec_addtime desc')->limit('1')
            ->find();
        //如果不存在则提示用户前往购买理茶宝
        if (!$actioninfo) {
            echo 0;
            die;// 跳转到购买页面
            //$this->error("当前的积分未返还完",U('Index/customer_info'));
        }
        if (intval($actioninfo['rec_money']) > intval($recharge_money)) {
            echo 1;
            die;
            //$this->error("当前购买的产品金钱数小于上次购买的钱数",U('index/lichabao'));
        } else {
            return true;
        }
    }

    //-----------------------------------------------------------------------------------------------------
    //增加门店的
    //显示购买页面
    public function confirm_buy_new($id)
    {
        //$this->checkLogin();
        //通过cookie存的数据来显示已经选择的门店
        $store_id=cookie('store_id_new');
        if(empty($store_id)){
            $store_name['stores_name']='请选择门店';
            $store_name['id']=0;
        }else{
            $store_name=Db::connect(config('db_config2'))->name("offline_store")->where('id',$store_id)
                ->find();
        }

        $recharge_id = intval($id);
        $data = Db::table('tea_new_recharge')
            ->where('recharge_id', $recharge_id)
            ->where('type',3)
            ->find();
        return view('confirm_buy_new', ['data' => $data,'store'=>$store_name]);
    }

    //显示所有的门店
    public function storeindex_new(){

        return $this->fetch();
    }

    //点击搜索按钮之后显示对应的门店信息
    public function storeData_new(){
        $store_name=input('post.name');
        if(empty($store_name)){
            $where = " id > 0";
        }else{
            $where = "stores_name like '%$store_name%'";
        }
        $data=Db::connect(config('db_config2'))->name("offline_store")
            //->where('ru_id',61)
            ->where($where)
            ->where("stores_name", 'not like',"%我的茶馆%")
             ->where("stores_name", 'not like',"国茶上海体验馆")
            ->where('is_confirm', 1)
            ->select();
        return json($data);
    }

    //当点击门店之后将门店ID存到cookie中
    public function cookie_store_new(){
        $store_id=intval(input('post.id'));
        $store_ids=cookie('store_id_new');
        if(empty($store_ids)){
            $data=cookie('store_id_new',$store_id);
        }else{
            cookie('store_id_new',null);
            $data=cookie('store_id_new',$store_id);
        }
        if($data){
            return 1;
        }else{
            return 0;
        }
    }

    //用户购买门店股权，第一次购买以及多次购买（门店）
    public function buyManage_new()
    {

        $this->checkLogin();
        $user_id = session('user_id');
        $recharge_id = intval(input('post.recharge_id'));   //获取传递过来的股东Id
        $store_id=intval(input('post.store_id'));           //获取传递过来的门店ID
        if(empty($store_id)) return 9;
        $rechargeData = Db::table('tea_new_recharge')->where('recharge_id', $recharge_id)->where(' type = 3 ')->find();
        //判断用户的上级是否购买
        $recharge_user_info=$this->buyRecharge($user_id);
        if($recharge_user_info===false) return 8;   //上级未购买，无法购买
        //判断门店股份是否多余
        if(Db::table('tea_recharge_cart')->where('store_id',$store_id)->where('pay_status',1)->count()>=21) return 6;
        //判断是否在一个门店超过了两份
        if(Db::table('tea_recharge_cart')->where('user_id',$user_id)->where('store_id',$store_id)->count()>=21) return 6;
        //判断用户是否实名认证了。现在取消此方法
//        $user_con = new User();
//        $user_info = $user_con->is_real();
//        if ($user_info == 0) {
//            return 3;
//        }
//        //首先判断是否允许客户购买
//        $res = $this->allowBuy($user_id, $rechargeData['rec_money']);
//        if (!$res) {
//            return 0;
//            //  $this->error('请在理茶宝积分返还完再购买',U('index/index'));
//        }
        // 判断是否有未支付股东订单 ***************
        $nopayorder = Db::table('tea_recharge_cart')
            ->where([
                'pay_status' => 0,
                'is_ceo' => 1,
                'user_id' => $user_id
            ])
            ->find();
        if ($nopayorder) {
            return 4;       //有尚未支付的固定订单，请支付或者取消
        }
        //只有用户支付成功才允许数据入库
        $model = new RechargeCart();
        $res = $model->buyManage($recharge_id, $user_id, $rechargeData['rec_money'],$store_id,3);
        if (!$res) {
            return 5;   //下单购买失败
        }
        //下单成功，跳转到支付宝支付，带上Id
        $data['status'] = 1;
        $data['id'] = $res;

        $data['contract_num'] = session('user_contract_num');
        cookie('store_id',null);
        session('user_contract_num',null);
        return json($data);
    }

    //用户购买门店股权，第一次购买以及多次购买（茶馆）
    public function buyManage()
    {

        $this->checkLogin();
        $user_id = session('user_id');
        $recharge_id = intval(input('post.recharge_id'));   //获取传递过来的股东Id
        $store_id=intval(input('post.store_id'));           //获取传递过来的门店ID
        if(empty($store_id)) return 9;
        $rechargeData = Db::table('tea_new_recharge')->where('recharge_id', $recharge_id)->where(' type = 2 ')->find();
        //判断用户的上级是否购买
        $recharge_user_info=$this->buyRecharge($user_id);
        if($recharge_user_info===false) return 8;   //上级未购买，无法购买
        //判断是否在一个门店超过了两份
        if(Db::table('tea_recharge_cart')->where('user_id',$user_id)->where('store_id',$store_id)->count()>=2) return 6;
        //判断用户是否实名认证了。现在取消此方法
//        $user_con = new User();
//        $user_info = $user_con->is_real();
//        if ($user_info == 0) {
//            return 3;
//        }
//        //首先判断是否允许客户购买
//        $res = $this->allowBuy($user_id, $rechargeData['rec_money']);
//        if (!$res) {
//            return 0;
//            //  $this->error('请在理茶宝积分返还完再购买',U('index/index'));
//        }
        // 判断是否有未支付股东订单 ***************
        $nopayorder = Db::table('tea_recharge_cart')
            ->where([
                'pay_status' => 0,
                'is_ceo' => 1,
                'user_id' => $user_id
            ])
            ->find();
        if ($nopayorder) {
            return 4;       //有尚未支付的固定订单，请支付或者取消
        }
        //只有用户支付成功才允许数据入库
        $model = new RechargeCart();
        $res = $model->buyManage($recharge_id, $user_id, $rechargeData['rec_money'],$store_id,2);
        if (!$res) {
            return 5;   //下单购买失败
        }
        //下单成功，跳转到支付宝支付，带上Id
        $data['status'] = 1;
        $data['id'] = $res;
        $data['contract_num'] = session('user_contract_num');
        cookie('store_id',null);
        session('user_contract_num',null);
        return json($data);
    }
}