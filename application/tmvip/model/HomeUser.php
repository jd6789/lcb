<?php
/**
 * Created by PhpStorm.
 * user: jieyu
 * Date: 2018/3/31
 * Time: 10:10
 */

namespace app\tmvip\model;
use app\tmvip\controller\Api;
use think\Db;
use think\Model;


class HomeUser extends Model{


//    保存类实例的静态成员变量
//    private static $_instance;
//
//    //private 构造函数
//    private function __construct() {
//    }
//
//    private function __clone() {
//    }
//
//    //单例方法访问类实例
//    public static function getInstance() {
//        if (!(self::$_instance instanceof self)) {
//            self::$_instance = new self();
//        }
//        return self::$_instance;
//    }

    //获取用户积分信息
    public function users_info(){
        //获取所有人信息
        $user_info = Db::table("tea_user")->field('id,username,parent_id,rank_id')->select();

//        $id_arr = Db::table("tea_integral")->field("max(id) as id")->group("user_id")->select();
//
//        $id = "";
//        foreach($id_arr as $k => $v){
//            $id .= $v['id'].",";
//        }
//        $id = substr($id,0,strlen($id)-1);
//
//        $user_info = Db::table("tea_user")
//            ->join("tea_integral "," tea_user.id = tea_integral.user_id")
//            ->join("tea_user_recharge "," tea_user_recharge.user_id = tea_user.id")
//            ->where("tea_integral.id in ($id)")
//            ->select();

        //获取一级推荐人
        foreach($user_info as $k => $v){
            //$user_info[$k]['integral'] = $this->last_integral(intval($v['id']));

            $groom_info = "";
            $groom_info = $this->groom_info($v['id']);
            //每个人推荐的一级人数
            $user_info[$k]['one_num'] = $groom_info['num'];
            //每个人推荐的一级人的id
            //$user_info[$k]['one_num_id'] = $groom_info['num_id'];
            //$user_info[$k]['one_num_info'] = $groom_info['num_info'];
            //$user_info[$k]['sum_one_rec_monry'] = $groom_info['sum_one_rec_monry'];
            //每个人推荐的二级人数
//            if($groom_info['num_id']){
//                $groom_info_second = $this->groom_info_second($groom_info['num_id']);
//                if($groom_info_second['counts_id']){
//                    //每个人推荐的二级人的id
//                    //$user_info[$k]['two_num_id'] = substr($groom_info_second['counts_id'],0,strlen($groom_info_second['counts_id'])-1);
//                }else{
//                    //$user_info[$k]['two_num_id'] = "";
//                }
//                //每个人推荐的二级人数
//                $user_info[$k]['two_num'] = $groom_info_second['counts'];
//                //$user_info[$k]['two_num_info'] = $groom_info_second['counts_info'];
//                //$user_info[$k]['sum_second_rec_monry'] = $groom_info_second['sum_second_rec_monry'];
//            }else{
//                $arr = array();
//                $user_info[$k]['two_num'] = 0;
//                //$user_info[$k]['two_num_id'] = "";
//                //$user_info[$k]['two_num_info'] = $arr;
//                //$user_info[$k]['sum_second_rec_monry'] = 0;
//            }
        }
        return $user_info;
    }

    //获取每个人的推荐人一级（直推）
    public function groom_info($id){

        $groom_info = Db::table("tea_user")->field('id,username,parent_id')->where("parent_id",$id)->select();
        $one_rec_monry = 0;
//        foreach($groom_info as $k => $v){
//            $groom_info[$k]['integral'] = $this->last_integral(intval($v['id']));
//            $one_rec_monry += $groom_info[$k]['integral']['sum_rec_money'];
//        }
        $list['num'] = count($groom_info);
        //$list['num_id'] = $this->getId($groom_info);
        //$list['num_info'] = $groom_info;
        //$list['sum_one_rec_monry'] = $one_rec_monry;
        return $list;
    }

    //获取每个人二推人数
    public function groom_info_second($groom_info_id){
        $arr = explode(",",$groom_info_id);
        $counts = 0;
        $counts_id = "";
        $counts_sum = 0;
        //$count_arr = array();
        for($i = 0;$i< count($arr);$i++){
            $infos = $this->groom_info($arr[$i]);
            $counts += intval($infos['num']);
//            if($infos['num_id']!=""){
//                $counts_id .= $infos['num_id'] .",";
//            }
            //$count_arr = array_merge($count_arr, $infos['num_info']);
            //$counts_sum += intval($infos['sum_one_rec_monry']);
        }
        $list['counts'] = $counts;
        //$list['counts_id'] = $counts_id;
        //$list['counts_info'] = $count_arr;
        //$list['sum_second_rec_monry'] = $counts_sum;
        return $list;
    }

    //获取用户最后一次购买的记录
    public function last_integral($user_id){
        //$user_id = intval($data['id']);
        //是否激活
        $integral_data = Db::query("select max(id) as 'id' from tea_integral where user_id = $user_id");
        if($integral_data){
            //是否购买
            $user_integral_data = Db::query("select max(id) as 'id' from tea_user_recharge where user_id = $user_id and pay_status = 1 ");
            if($user_integral_data){
                //未购买
                $list = array("total_sum"=>0,"surplus_inte"=>0,"back_inte"=>0,"tea_inte"=>0,"tea_ponit_inte"=>0,"reg_inte"=>0,"lev"=>0);
            }else{
                $list = array("total_sum"=>$user_integral_data['total_inte'],"surplus_inte"=>$user_integral_data['total_inte'],
                    "back_inte"=>0,"tea_inte"=>0,"tea_ponit_inte"=>0,"reg_inte"=>$user_integral_data['reg_rec'],"lev "=>$user_integral_data['lev']);
            }
        }else{
            $list = array("total_sum"=>$integral_data['total_sum'],"surplus_inte"=>$integral_data['surplus_inte'],"back_inte"=>$integral_data['back_inte'],
                "tea_inte"=>$integral_data['tea_inte'],"tea_ponit_inte"=>$integral_data['tea_ponit_inte'],"reg_inte"=>0,"lev "=>$integral_data['lev']);
        }
        $sum_user_integral_data = Db::query("select sum(rec_money) as 'sum_rec_money' from tea_user_recharge ");
        $list['sum_rec_money'] = $sum_user_integral_data[0]['sum_rec_money'];
        //$data['integral'] = $list;
        return $list;
    }

    //会员信息
    public function user_detailssss($username,$tel){

//        $info = Db::connect(config('db_config2'))->name('users')->select();
//        foreach($info as $k => $v){
//            Db::table('tea_user')->insert(['user_id'=>intval($v['user_id'])]);
//        }
//        dump($info);die;

        $api_obj = new Api();
        //获取用户信息
        $user_info = $api_obj->getUserInfo("",$username,$tel);
        //获取用户信息
        if($username || $tel){
            $user_ids = $this->getId($user_info['info']['list']);
            $where = " tea_user.user_id in ($user_ids) ";
        }else{
            $where = " tea_user.user_id > 0 ";
        }

        $subQuery = Db::table('tea_user_recharge')
            ->field("sum(rec_money) as money,user_id")
            ->where("pay_status = 1 ")
            ->group("user_id")
            ->buildSql();

        $data = Db::table("tea_user")
            ->field("tea_user.id,tea_user.user_id,t.money")
            //->join($subQuery.' t',"tea_user.user_id in (t.user_id)","left")//->select();
            ->join($subQuery.' t',"t.user_id in (tea_user.user_id)","left")//->select();
            ->where($where)
            ->paginate(14);

        //dump($user_info['info']['list']);
        $list=array();
//        foreach($data->items() as $k=>$v){
//            //获取用户信息
//            $user_id = $v['user_id'];
//            foreach($user_info['info']['list'] as $k1 =>$v1){
//                if($v1['user_id']==$user_id){
//                    $list[$user_id]['user_name'] = $v1['user_name'];
//                    $list[$user_id]['mobile_phone'] = $v1['mobile_phone'];
//                    $list[$user_id]['reg_time'] = $v1['reg_time'];
//                    $list[$user_id]['one']  = Db::connect(config('db_config2'))->name("users")->where('parent_id',$user_id)->count('user_id');
//                    $list[$user_id]['two']  = Db::connect(config('db_config2'))->query("select count(b.user_id) as user_id from taom_users b join (select a.user_id from taom_users a where a.parent_id = $user_id) as c
//                                                        on b.parent_id in (c.user_id) ")[0]['user_id'];
//                }
//            }
//        }
        foreach($data->items() as $k=>$v){
            //获取用户信息
            $user_id = $v['user_id'];
            $user_info = Db::connect(config('db_config2'))->name("users")->field('user_name,user_id,parent_id,mobile_phone,reg_time')->where('user_id',$user_id)->find();
            $list[$user_id]['user_name'] = $user_info['user_name'];
            $list[$user_id]['mobile_phone'] = $user_info['mobile_phone'];
            $list[$user_id]['reg_time'] = $user_info['reg_time'];
            $list[$user_id]['one']  = Db::connect(config('db_config2'))->name("users")->where('parent_id',$user_id)->count('user_id');
            $list[$user_id]['two']  = Db::connect(config('db_config2'))->query("select count(b.user_id) as user_id from taom_users b join (select a.user_id from taom_users a where a.parent_id = $user_id) as c
                                                        on b.parent_id in (c.user_id) ")[0]['user_id'];

        }
        $list['info'] = $data;
        //dump($list);die;
        return $list;
    }

    //通过id获取用户信息
    public function getUserById($user_id){

        return  Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->find();
    }

    //保存用户信息
    public function save_user_info($user_id,$list){

        return Db::table("tea_user")->where("user_id",$user_id)->update($list);
    }

    //保存用户收货地址
    public function save_user_address($user_id,$list){
        if ($user_id <= 0) {
            return Db::table("tea_user_address")->insert($list);
        } else {
            return Db::table("tea_user_address")->where("user_id",$user_id)->update($list);
        }
    }

    //通过id获取用户的推荐人
    protected function groom($user_id){
        $user_info = $this->getUserById($user_id);
        //推荐人信息
        $groom_info = $this->getUserById($user_info->parent_id);
        return $groom_info;
    }

    //获取用户下一级人信息（被推荐 一推）
    public function groom_first($user_id){

        $groom_first_data = Db::table("tea_user")->where("parent_id",$user_id)->select();
        return $groom_first_data;
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


        //获取推荐人数
        public function getNumber($where)
        {

            $subQuery = Db::table('tea_user_recharge')
                ->field("sum(rec_money) as money,user_id")
                ->where("pay_status = 1 ")
                ->group("user_id")
                ->buildSql();

            $data1 = Db::table("tea_user")
                ->field("id,username,parent_id,tel,first_time,t.money")
                ->join($subQuery.' t',"tea_user.id in (t.user_id)","left")//->select();
                ->where($where)
                ->paginate(14);




            //先获取所有的分类信息
//            $data = Db::query("select id,username,parent_id,tel,first_time,t.money from tea_user  left join (select sum(rec_money) as money,user_id
//                                   from tea_user_recharge where pay_status = 1 GROUP BY user_id ) as t on  tea_user.id in (t.user_id)");

            //$data = Db::table('tea_user')->field('id,parent_id,username,tel,first_time')->select();
            // ->paginate(5, false, ['var_page'=>'p']);
//            $data = Db::query("select id,username,parent_id,t.money from tea_user join (select sum(rec_money) as money,user_id
//                                   from tea_user_recharge where pay_status = 1 and user_id = 109) as t on t.user_id = tea_user.id; ");
           //dump($data1);die;
            $data = Db::table('tea_user')->field('id,parent_id')->select();
            //获得每个用户一级推荐人信息（人数）
            foreach($data as $k=>$v){
                $c = intval($v['id']);
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
                //用户充值总额
                //$user_sum = Db::query("select sum(rec_money) as 'sum_rec_money' from tea_user_recharge where pay_status = 1 and user_id = $c ");
                //$data[$k]['user_sum'] = $user_sum[0]['sum_rec_money'];
            }
            //dump($list);die;
            //return $data;
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
            //$list = $data->items();
            //dump($data->items());die;
            $info['one'] = $arr;
            $info['two'] = $list2;
//            foreach($list as $k => $v){
//                $id = intval($v['id']);
//                $list1['one'][$id] = Db::query("select count(id) as id from tea_user a where a.parent_id = $id")[0]['id'];
//                $list1['two'][$id] = Db::query("select count(b.id) as id from tea_user b join (select a.id from tea_user a where a.parent_id = $id) as c
//                                                        on b.parent_id in (c.id) ;")[0]['id'];
//            }
            $info['info'] = $data1;

            //用户充值总额
            //dump($data);die;
            return $info;
        }

    public function getNumber1(){
        $data = Db::table('tea_user')->field('id,parent_id,username,tel,first_time')->select();
        foreach($data as $k=>$v){
            $id = intval($v['id']);
//            $one_arr = Db::query("select a.id,a.parent_id,a.username from tea_user a where a.parent_id = $id ");
//            $data[$k]['one_num'] = count($one_arr);
//            $ids = $this->getId($one_arr);
//            $two_arr = Db::query("select a.id,a.parent_id,a.username from tea_user a where a.parent_id in ($ids) ");
//            $data[$k]['two_num'] = count($two_arr);
            $data[$k]['one'] = Db::query("select count(id) as id from tea_user a where a.parent_id = $id")[0]['id'];
            $data[$k]['two'] = Db::query("select count(b.id) as id from tea_user b join (select a.id from tea_user a where a.parent_id = $id) as c 
                                                        on b.parent_id in (c.id) ;")[0]['id'];
        }
        return $data;

    }

    //开启用户
    public function user_use($id){
          //冻结释放积分
        $c = Db::table('tea_integral')->where('user_id='.$id)->order('id desc')->limit(1)->find();
        if(!$c===false){
            $visit = date('Y-m-d');
            M('Integral')->where('user_id='.$c['id'])->setField('last_time',$visit);
        }
        return Db::table('tea_user')->where('user_id', $id)->update(['wait' => 1]);
    }

    //冻结用户
    public function user_wait($id){
        //冻结释放积分
        $c = Db::table('tea_integral')->where('user_id='.$id)->order('id desc')->limit(1)->find();
        if(!$c===false){
            Db::table('tea_integral')->where('id='.$c['id'])->setField('last_time',"2066-01-15");
        }
        return Db::table('tea_user')->where('user_id', $id)->update(['wait' => 0]);
    }

    //获取用户地址信息
    public function user_address($user_id){
        $where = ['user_id'=>intval($user_id),'is_use'=>1];

        return Db::table("tea_user_address")->where($where)->order("id desc")->limit(1)->find();

    }

    //重置登录密码
    public function reset_login_pwd($user_id,$password){

        return Db::connect(config('db_config2'))->name("users")->where('user_id', $user_id)->update(['password' => $password]);
    }

    //重置支付密码
    public function reset_pay_pwd($user_id,$password){

        return Db::table('tea_user')->where('user_id', $user_id)->update(['pay_pwd' => $password]);
    }

    //通过用户名获取用户信息
    public function getInfoByName($username){

        $data =  Db::table('tea_user')->where("username like '%$username%'")->select();
        $str = "";
        foreach ($data as $k=>$v){
            $str .= $v['id'].",";
        }
        $str = substr($str,0,strlen($str)-1);
        $list['list_id'] = $str;
        $list['info'] = $data;
        return $list;
    }

    //通过条件获取积分记录
    public function getIntegralLog($where){

        //return Db::table("tea_integral_log")->where($where)->order('addtime desc')->select();
        return Db::query("select a.id,a.username,a.parent_id,a.rank_id,b.* from tea_user as a join tea_integral_log as b on a.id = b.user_id  where $where order by b.addtime desc");
    }

    //添加充值规则
    public function add_recharge($data){

        return Db::table("tea_recharge")->insert($data);
    }

    //通过id获得理茶宝规则
    public function getRechargeById($id){

        return Db::table("tea_recharge")->where("id",$id)->find();
    }

    //更新理茶宝规则
    public function update_recharge($id,$data){

        return Db::table('tea_recharge')->where('id', $id)->update($data);
    }

    //获取所有充值规则信息
    public function recharge_info(){

        return Db::table("tea_recharge")->select();
    }

    //通过id删除理茶宝规则
    public function del_recharge($id){

        return Db::table("tea_recharge")->where("id",$id)->delete();
    }

    ////获得利率信息问题
    public function getRate(){

        return Db::table('tea_rate')->order("id desc")->limit(1)->find();
    }

    //修改转换比例
    public function save_teapoint_edit($id,$data){

        return Db::table('tea_rate')->where('id', $id)->update($data);
    }

    //添加会员等级
    public function add_rank($data){

        return Db::table('tea_rank')->insert($data);

    }

    //修改会员等级
    public function save_edit_rank($rank_id,$data){

        return Db::table('tea_rank')->where('rank_id',$rank_id)->update($data);

    }

    //删除会员等级
    public function del_rank($id){
        $rank_data_res = Db::table("tea_rank")->where("rank_id",$id)->find();
        if($rank_data_res['first']==1){
            return false;
        }
        $rank_data = Db::table("tea_rank")->where("son_id",$id)->find();
        if($rank_data){
            //存在上一级不可删除
            return false;
        }else{
            return Db::table("tea_rank")->where("rank_id",$id)->delete();
        }
    }

    //展示会员等级
    public function show_rank(){

        return Db::table("tea_rank")->select();
    }

    //id获取会员等级

    //获取用户购买信息
    public function recharge_cart_data($where){
        return Db::table("tea_recharge_cart")->where($where)->select();
    }

    //显示所有用户的购买记录到后台便于操作
    public function recharge_cart($username){
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        $where = " a.id > 0 ";
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(strpos($user_ids,',')===false){
                $where .= " and user_id = $user_ids ";
            }else{
                $where .= " and user_id in ($user_ids) ";
            }
        }
        $data =  Db::table('tea_recharge_cart')
            ->alias('a')
            //->where("u.username like '%$user%'")
            ->field('a.id,a.rec_addtime,a.recharge_num,a.pay_status,b.rec_money,a.recharge_money,b.body,a.is_againbuy,a.user_id')
            ->join('tea_recharge b',' a.recharge_id=b.id')
            //->join('tea_user u ',' a.user_id = u.id')
            ->where($where)
            ->order('rec_addtime desc')
            ->select();
        foreach($data as $k=> $v){
            $user_id = intval($v['user_id']);
            $user = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->count('user_id');
            $data[$k]['username'] = $user['user_name'];
            $data[$k]['tel'] = $user['mobile_phone'];
        }
        //dump($data);die;
        return $data;
    }


    //显示所有用户购买理茶宝的充值记录
    public function user_recharge($username){
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        $where = " id > 0 ";
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(strpos($user_ids,',')===false){
                $where .= " and user_id = $user_ids ";
            }else{
                $where .= " and user_id in ($user_ids) ";
            }
        }

        $data = Db::table('tea_user_recharge')
            ->alias('a')
            //->where("b.username like '%$user%'")
            ->field('a.id,a.rec_money,a.addtime,a.rec_addtime,a.is_return,a.recharger_num,a.total_inte,a.user_id')
            //->join('tea_user b ',' a.user_id=b.id')
            ->where($where)
            ->order('addtime desc')
            ->select();
        foreach($data as $k=> $v){
            $user_id = intval($v['user_id']);
            $user = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->count('user_id');
            $data[$k]['username'] = $user['user_name'];
        }
        return $data;
    }

    //无县级
    //获取格式化之后的数据
    public function getCateTree($id=0)
    {
        //先获取所有的分类信息
        $data = Db::table('user')->select();
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


    //-----------------------------------------------------------------------------------------------------------------------
    //会员信息
    public function user_detail($username,$tel){
        $api_obj = new Api();
        //获取用户信息
        $user_info = $api_obj->getUserInfo("",$username,$tel);
        //获取用户信息
//        if($username || $tel){
//            $user_ids = $this->getId($user_info['info']['list']);
//
//            $where = " tea_user.user_id in ($user_ids) ";
//        }else{
//            $where = " tea_user.user_id > 0 ";
//        }
        $where = " tea_user.user_id >0 ";
        if($username || $tel ){
            $user_ids = $this->getId($user_info['info']['list']);

            if(!empty($user_ids)){
                if(strpos($user_ids,',')===false){
                    $where .= " and tea_user.user_id = $user_ids ";
                }else{
                    $where .= " and  tea_user.user_id in ($user_ids) ";
                }
            }else{
                //$where .= " tea_user.user_id ='' ";
            }
        }
        //dump($where);die;


        $subQuery = Db::table('tea_user_recharge')
            ->field("sum(rec_money) as money,user_id")
            ->where("pay_status = 1 ")
            ->group("user_id")
            ->buildSql();
        $data = Db::table("tea_user")
            ->field("tea_user.id,tea_user.user_id,t.money,tea_user.wait")
            //->join($subQuery.' t',"tea_user.user_id in (t.user_id)","left")//->select();
            ->join($subQuery.' t',"t.user_id in (tea_user.user_id)","left")//->select();
            ->where($where)
            ->order('tea_user.user_id desc')
            ->paginate(14);
        
        $list=array();
        foreach($data->items() as $k=>$v){

            //获取用户信息
            $user_id = $v['user_id'];
            $user_info = Db::connect(config('db_config2'))->name("users")->field('user_name,user_id,parent_id,mobile_phone,reg_time,user_rank')->where('user_id',$user_id)->find();
            $list[$user_id]['user_id'] = $user_id;
            $list[$user_id]['user_name'] = $user_info['user_name'];
            $list[$user_id]['mobile_phone'] = Db::connect(config('db_config2'))->field('bank_mobile')->name("users_real")->where('user_id',$user_id)->find()['bank_mobile'];;
            $list[$user_id]['reg_time'] = $user_info['reg_time'];
            //$list[$user_id]['user_rank'] = $user_info['user_rank'];

            $res = Db::query("select  count(id) as 'id',type  from  tea_integral where user_id = $user_id group by type ");
            $str= '';
            foreach($res as $v){
                if((int)$v['type']==1){
                    $str .="会员+";
                }
                if((int)$v['type']==2){
                    $str .="茶馆+";
                }
                if((int)$v['type']==3){
                    $str .="门店+";
                }
            }
            $list[$user_id]['user_rank'] = substr($str,0,strlen($str)-1);

            $user_recharge = Db::table("tea_recharge_cart")->where("user_id",$user_id)->where("pay_status",1)->limit(1)->find();
            if(!empty($user_recharge)){
                $user_recharge = 1;
            }else{
                $user_recharge = 0;
            }
            $list[$user_id]['user_recharge'] =  $user_recharge;
            $list[$user_id]['one']  = Db::connect(config('db_config2'))->name("users")->where('parent_id',$user_id)->where('user_rank','in','9,10')->count('user_id');
            $list[$user_id]['two']  = Db::connect(config('db_config2'))->query("select count(b.user_id) as user_id from taom_users b 
                                            join (select a.user_id from taom_users a where a.parent_id = $user_id AND a.user_rank in (9,10)) as c on b.parent_id in (c.user_id) where b.user_rank in (9,10)")[0]['user_id'];
            $list[$user_id]['parent_name'] = Db::connect(config('db_config2'))->name("users")
                ->field('user_name,user_id,parent_id,mobile_phone,reg_time')->where('user_id',intval($user_info['parent_id']))->find()['user_name'];
        }
        //$data = $this->array_sort($data);

        $list['info'] = $data;
        //dump($list);die;
        return $list;
    }

    //排序
    public function array_sort($list){
        //dump($list);
        foreach ($list as $k=>$v){
            $user_id[$k] = intval($v['user_id']);
        }
        //dump($id);die;
        array_multisort($user_id, SORT_DESC, $list);
        //dump($list);die;
        return $list;


    }




    //钱包充值
    public function increase_wallet($user_name,$mobile_phone){
        $api_obj = new Api();
        //获取用户信息
        $user_info = $api_obj->getUserInfo("",$user_name,$mobile_phone);
        //获取用户信息
//        if($user_name || $mobile_phone){
//            $user_ids = $this->getId($user_info['info']['list']);
//            $where = " tea_integral_log.user_id in ($user_ids) ";
//        }else{
//            $where = " tea_integral_log.user_id > 0 ";
//        }
        if($user_name || $mobile_phone) {
            $user_ids = $this->getId($user_info['info']['list']);
            if (!empty($user_ids)) {
                if (strpos($user_ids, ',') === false) {
                    $where = "  tea_integral_log.user_id = $user_ids ";
                } else {
                    $where = "   tea_integral_log.user_id in ($user_ids) ";
                }
            } else {
                $where = " tea_integral_log.user_id >0 ";
            }
        }

        //获取充值记录
        $wallet_data = Db::table("tea_integral_log")->field('user_id,introduce,surplus_inte,addtime')->where($where." and shopping = 1 ")->paginate(14);
        //用户信息
        $list = array();
        foreach($wallet_data->items() as $k => $v){
            $user_id = $v['user_id'];
            $user_info = Db::connect(config('db_config2'))->name("users")->field('user_name,user_id,mobile_phone')->where('user_id',$user_id)->find();
            $list[$user_id]['user_name'] = $user_info['user_name'];
            $list[$user_id]['mobile_phone'] = $user_info['mobile_phone'];
            $list[$user_id]['sum_wallet'] = Db::table('tea_user')->where('user_id',$user_id)->find()['wallet'];
        }
        $list['info'] = $wallet_data;
        return $list;
    }

    //钱包提现
    public function withdrawal_wallet($user_name,$mobile_phone){
        $api_obj = new Api();
        //获取用户信息
        $user_info = $api_obj->getUserInfo("",$user_name,$mobile_phone);
        //获取用户信息
//        if($user_name || $mobile_phone){
//            $user_ids = $this->getId($user_info['info']['list']);
//            $where = " tea_integral_log.user_id in ($user_ids) ";
//        }else{
//            $where = " tea_integral_log.user_id > 0 ";
//        }

        if($user_name || $mobile_phone) {
            $user_ids = $this->getId($user_info['info']['list']);
            if (!empty($user_ids)) {
                if (strpos($user_ids, ',') === false) {
                    $where = "  tea_integral_log.user_id = $user_ids ";
                } else {
                    $where = "   tea_integral_log.user_id in ($user_ids) ";
                }
            } else {
                $where = " tea_integral_log.user_id >0 ";
            }
        }

        //获取充值记录
        $wallet_data = Db::table("tea_integral_log")->field('user_id,introduce,surplus_inte,addtime')->where($where." and shopping = 3 ")->paginate(14);
        //用户信息
        $list = array();
        foreach($wallet_data->items() as $k => $v){
            $user_id = $v['user_id'];
            $user_info = Db::connect(config('db_config2'))->name("users")->field('user_name,user_id,mobile_phone')->where('user_id',$user_id)->find();
            $list[$user_id]['user_name'] = $user_info['user_name'];
            $list[$user_id]['mobile_phone'] = $user_info['mobile_phone'];
            $list[$user_id]['sum_wallet'] = Db::table('tea_user')->where('user_id',$user_id)->find()['wallet'];
        }
        $list['info'] = $wallet_data;
        //dump($list);die;
        return $list;
    }


}
