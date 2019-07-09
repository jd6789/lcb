<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/3/31
 * Time: 9:48
 */
namespace app\tmvip\controller;
use app\tmvip\model\HomeUser;
use think\Db;
use think\Controller;
use think\Request;

class System extends Common {
    //商城系统设置
    public function system(){

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
    public function index(){
        $menu = $this->user['menus'];
        //dump($menu);die;
        //获取当前url的权限id
        $request = Request::instance();
        //$request->module(). '/' . $request->controller() . '/' . $request->action() ;
        $url_id = $this->url_id($request->module(),$request->controller(),$request->action());
        $id = intval($url_id['id']);
        return view("index",['menu'=>$menu,'url'=>$id]);
    }

    //理茶宝积分规则设置
    public function add_recharge(){
        $homeuser_obj = new HomeUser();
        if(request()->isGet()){
            return view("add_recharge");
        }else{
            $data = input('post.');
            $data['init_rates'] = $data['init_rates']/100;
            $data['fir_rec'] = $data['fir_rec']/100;
            $data['sec_rec'] = $data['sec_rec']/100;
            $data['fir_merits'] = $data['fir_merits']/100;
            $data['sec_merits'] = $data['sec_merits']/100;
            $insert_data = $homeuser_obj->add_recharge($data);
            if($insert_data){
                $this->success("理茶宝规则添加成功",'tmvip/System/recharge_info');
            }else{
                $this->error("理茶宝规则添加失败");
            }
        }

    }

    //理茶宝规则修改
    public function recharge_edit($recharge_id){
        $homeuser_obj = new HomeUser();
        //$recharge_id = input("get.recharge_id");
        $info = $homeuser_obj->getRechargeById(intval($recharge_id));
        $info['init_rates'] = $info['init_rates']*100;
        $info['fir_rec'] = $info['fir_rec']*100;
        $info['sec_rec'] = $info['sec_rec']*100;
        $info['fir_merits'] = $info['fir_merits']*100;
        $info['sec_merits'] = $info['sec_merits']*100;
        return view("recharge_edit",['data'=>$info]);
    }

    //理茶宝规则修改后保存
    public function save_recharge_edit(){
        $homeuser_obj = new HomeUser();
        $recharge_id = input("post.id");

        $data = input('post.');
        //dump($data);die;
        $data['init_rates'] = $data['init_rates']/100;
        $data['fir_rec'] = $data['fir_rec']/100;
        $data['sec_rec'] = $data['sec_rec']/100;
        $data['fir_merits'] = $data['fir_merits']/100;
        $data['sec_merits'] = $data['sec_merits']/100;
        $update_data = $homeuser_obj->update_recharge($recharge_id,$data);
        if($update_data){
            $this->success("理茶宝规则编辑成功",'tmvip/System/recharge_info');
        }else{
            $this->error("理茶宝规则编辑失败");
        }
    }

    //理茶宝充值规则展示
    public function recharge_info(){
        $homeuser_obj = new HomeUser();
        $info = $homeuser_obj->recharge_info();
        foreach($info as $k => $v){
            $info[$k]['init_rates'] = $v['init_rates']*100;
            $info[$k]['fir_rec'] = $v['fir_rec']*100;
            $info[$k]['sec_rec'] = $v['sec_rec']*100;
            $info[$k]['fir_merits'] = $v['fir_merits']*100;
            $info[$k]['sec_merits'] = $v['sec_merits']*100;
        }
        return view("recharge_info",['info'=>$info]);
    }

    //理茶宝充值规则删除
    public function recharge_del($recharge_id){
        $homeuser_obj = new HomeUser();
        //$recharge_id = input("post.recharge_id");
        //dump($recharge_id);die;
        $del_data = $homeuser_obj->del_recharge(intval($recharge_id));
        if($del_data){
            $this->success("理茶宝规则删除成功");
        }else{
            $this->error("理茶宝规则删除失败");
        }
    }

    //茶点茶券转换规则展示
    public function teapoint(){
        $homeuser_obj = new HomeUser();
        $data = $homeuser_obj->getRate();
        $data['rank_start'] = $data['rank_start'] / 10000;

        $data['hight_rate'] = $data['hight_rate']*100;
        $data['tea_inte_rate'] = $data['tea_inte_rate']*100;
        $data['tea_score_rate'] = $data['tea_score_rate']*100;
        $data['slow_tea_inte_rate'] = $data['slow_tea_inte_rate']*100;
        $data['slow_tea_score_rate'] = $data['slow_tea_score_rate']*100;
        $data['dev_rate'] = $data['dev_rate']*100;
        return view("teapoint",['data'=>$data]);
    }

    //茶点茶券转换规则修改
    public function teapoint_edit(){
        $homeuser_obj = new HomeUser();
        $data = $homeuser_obj->getRate();
        $data['rank_start'] = $data['rank_start'] / 10000;
        $data['hight_rate'] = $data['hight_rate']*100;
        $data['tea_inte_rate'] = $data['tea_inte_rate']*100;
        $data['tea_score_rate'] = $data['tea_score_rate']*100;
        $data['slow_tea_inte_rate'] = $data['slow_tea_inte_rate']*100;
        $data['slow_tea_score_rate'] = $data['slow_tea_score_rate']*100;
        $data['dev_rate'] = $data['dev_rate']*100;
        return view("teapoint_edit",['data'=>$data]);
    }

    //茶点茶券转换规则修改后保存
    public function save_teapoint_edit(){
        $homeuser_obj = new HomeUser();
        $id = intval(input("post.id"));
        $data = input("post.");
        //dump($data);die;
        $data['rank_start'] = $data['rank_start'] * 10000;

        $data['hight_rate'] = floatval($data['hight_rate'])/100;
        $data['tea_inte_rate'] = floatval($data['tea_inte_rate'])/100;
        $data['tea_score_rate']=floatval($data['tea_score_rate'])/100;
        $data['slow_tea_score_rate']=floatval($data['slow_tea_score_rate'])/100;
        $data['slow_tea_inte_rate']=floatval($data['slow_tea_inte_rate'])/100;
        $data['dev_rate']=floatval($data['dev_rate'])/100;
        $sum= $data['tea_inte_rate']+$data['tea_score_rate'];
        if($sum !=1){
            $this->error("输入的百分比和有误，请重新输入");
        }
        $nume= $data['slow_tea_score_rate']+$data['slow_tea_inte_rate'];
        if($nume !=1){
            $this->error("输入的百分比和有误，请重新输入");
        }
        $res = $homeuser_obj->save_teapoint_edit($id,$data);
        if($res ==false){
            $this->error("修改失败");
        }else{
            $this->success('修改成功','tmvip/System/teapoint');
        }
    }

//    //显示所有用户的购买记录到后台便于操作
//    public function recharge_cart(){
//        $homeuser_obj = new HomeUser();
//        $user=input('post.username');
//        if(!$user){
//            $user = "";
//        }
//        $data = $homeuser_obj->recharge_cart($user);
//        //dump($data);die;
//        return view("recharge_cart",['data'=>$data]);
//    }
//
//    //显示所有用户购买理茶宝的充值记录
//    public function user_recharge(){
//        $homeuser_obj = new HomeUser();
//        $user=input('post.username');
//        if(!$user){
//            $user = "";
//        }
//        $data = $homeuser_obj->user_recharge($user);
//        //dump($data);die;
//        return view("user_recharge",['data'=>$data]);
//    }

    //显示所有用户的购买记录到后台便于操作
    public function recharge_cart(){
        $username = input('post.username');

        if (!$username) {
            $username = "";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        $where = " a.id > 0  AND a.is_ceo=0  ";
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
           if(!empty($user_ids)){
               if(strpos($user_ids,',')===false){
                   $where .= " and user_id = $user_ids ";
               }else{
                   $where .= " and user_id in ($user_ids) ";
               }
           }else{
               $where .= " and user_id ='' ";
           }

        }
        $data =  Db::table('tea_recharge_cart')
            ->alias('a')
            //->where("u.username like '%$user%'")
            ->field('a.id,a.rec_addtime,a.recharge_num,a.pay_status,b.rec_money,a.recharge_money,b.body,a.is_againbuy,a.user_id')
            ->join('tea_recharge b',' a.recharge_id=b.id')
            //->join('tea_user u ',' a.user_id = u.id')
            ->where($where)
            ->order('rec_addtime desc')->paginate('14');
            //->select();
        foreach($data->items() as $k=> $v){
            $user_id = intval($v['user_id']);
            $user = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
            $list[$user_id]['username'] = $user['user_name'];
            $list[$user_id]['tel'] = $user['mobile_phone'];
        }
        $list['info'] = $data;
        //dump($list);die;
        return view("recharge_cart",['data'=>$list]);
    }


    //显示所有用户购买理茶宝的充值记录
    public function user_recharge(){
        $username = input('post.username');
        if (!$username) {
            $username = "";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');

        //获取用户信息
        $where = " id > 0 AND is_ceo=0  ";
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);

            if(!empty($user_ids)){
                if(strpos($user_ids,',')===false){
                    $where .= " and user_id = $user_ids ";
                }else{
                    $where .= " and user_id in ($user_ids) ";
                }
            }else{
                $where .= " and user_id >0 ";
            }
        }
        $data = Db::table('tea_user_recharge')
            ->alias('a')
            //->where("b.username like '%$user%'")
            ->field('a.id,a.rec_money,a.addtime,a.rec_addtime,a.is_return,a.recharger_num,a.total_inte,a.user_id,a.is_gifts')
            //->join('tea_user b ',' a.user_id=b.id')
            ->where($where)
            ->order('addtime desc')->paginate('14');
            //->select();
        foreach($data->items() as $k=> $v){
            $user_id = intval($v['user_id']);
            $user = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
            $list[$user_id]['username'] = $user['user_name'];
            //dump($user);
        }
        $list['info'] = $data;
        //dump($list);die;
        return view("user_recharge",['data'=>$list]);
    }

    //获取资源集的id
    public function getId($info){
        if(empty($info)){
            $str = "";
        }else{
            $str  = '';
            foreach($info as $k => $v){
                $str .= $v['user_id'].",";
            }
            $str = substr($str,0,strlen($str)-1);
        }
        return $str;
    }

    //钱包线下支付
    public function wallet_online_pay(){
        $username = input('post.username');
        if (!$username) {
            $username = "";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        $where = " money_id > 0 ";
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(!empty($user_ids)){
                if(strpos($user_ids,',')===false){
                    $where .= " and user_id = $user_ids ";
                }else{
                    $where .= " and user_id in ($user_ids) ";
                }
            }else{
                $where .= " and user_id >0 ";
            }
        }
        $data =  Db::table('tea_money')
            ->where($where)
            ->order('money_addtime desc')->paginate('14');
        //->select();
        foreach($data->items() as $k=> $v){
            $user_id = intval($v['user_ids']);
            $user = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
            $list[$user_id]['username'] = $user['user_name'];
            $list[$user_id]['tel'] = $user['mobile_phone'];
        }
        $list['info'] = $data;
        //dump($list);die;
        return view("wallet_online_pay",['data'=>$list]);
    }


    //会员段位等级
    public function add_rank(){
        $homeuser_obj = new HomeUser();
        if(request()->isGet()){
            $data = $homeuser_obj->show_rank();
            return view("add_rank",['data'=>$data]);
        }else{
            $homeuser_obj = new HomeUser();
            $data = input("post.");
            $data['rank_rate'] = $data['rank_rate'] / 100;
            $data['rank_peo'] = intval($data['rank_peo']) ;
            $data['son_id'] = intval($data['son_id']) ;
            $data['first'] = intval($data['first']) ;
            $data['rank_addtime'] = time();
            $insert_data = $homeuser_obj->add_rank($data);
            if($insert_data){
                $this->success('添加成功');
            }else{
                $this->error("添加失败");
            }
        }
    }

    //会员段位修改
    public function edit_rank(){
        $homeuser_obj = new HomeUser();
        $rank_id = intval(input("get.rank_id"));
        $data = $homeuser_obj->edit_rank($rank_id);
        $data1 = $homeuser_obj->show_rank();
        $data['rank_rate'] = intval($data['rank_rate']) * 100;
        $data['rank_peo'] = intval($data['rank_peo']) ;
        $data['son_id'] = intval($data['son_id']) ;
        $data['first'] = intval($data['first']) ;
        return view("edit_rank",['data'=>$data,'data1'=>$data1]);
    }

    //会员段位等级
    public function save_edit_rank(){
        $homeuser_obj = new HomeUser();

        $data = input("post.");
        $rank_id = intval($data['rank_id']);
        $data['rank_rate'] = $data['rank_rate'] / 100;
        $data['rank_peo'] = intval($data['rank_peo']) ;
        $data['son_id'] = intval($data['son_id']) ;
        $data['first'] = intval($data['first']) ;
        $data['rank_addtime'] = time();
        $update_data = $homeuser_obj->save_edit_rank($rank_id,$data);
        if($update_data){
            $this->success('修改成功');
        }else{
            $this->error("修改失败");
        }
    }

    //会员段位删除
    public function del_rank(){
        $homeuser_obj = new HomeUser();
        $rank_id = intval(input("get.rank_id"));
        $del_data = $homeuser_obj->del_rank($rank_id);
        if($del_data){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }

    //会员段位展示
    public function show_rank(){
        $homeuser_obj = new HomeUser();
        $data = $homeuser_obj->show_rank();
        //$data['rank_start'] = intval($data['rank_start']) / 10000;
        $data['rank_peo'] = intval($data['rank_peo']) ;
        return view("show_rank");
    }


    //页面的信息设置
    public function banner(){

    }
    //支付宝的接口设置
    public function alpay(){

    }
    //微信接口设置
    public function wxpau(){

    }
    //短信接口设置
    public function duanxin(){
        //添加短信接口
        $data=request();
    }
    //添加短信模板
    public function exitduanxin(){

    }
}