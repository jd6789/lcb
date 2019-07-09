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
class Recom extends Co{
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
    //显示我的推荐页面
    public function recommen(){
        $user_id = session('user_id');
        //查询当前用户的积分信息
        $nowUserInfo=Db::table('tea_user_recharge')->where('user_id',$user_id)->sum('rec_money');
        $groom_info = "";
        $groom_info = $this->groom_info($user_id);
        //每个人推荐的一级人数
        $user_info['one_num'] = $groom_info['num'];
        //每个人推荐的一级人的id
        $user_info['one_num_id'] = $groom_info['num_id'];
        // $user_info['one_num_info'] = $groom_info['num_info'];
        $user_info['sum_one_rec_monry'] = $groom_info['sum_one_rec_monry'];
        //dump($user_info);die;
        //每个人推荐的二级人数
        if($groom_info['num_id']){
            $groom_info_second = $this->groom_info_second($groom_info['num_id']);
            //dump($groom_info_second);die;
            if($groom_info_second['counts_id']){
                //每个人推荐的二级人的id
                $user_info['two_num_id'] = substr($groom_info_second['counts_id'],0,strlen($groom_info_second['counts_id'])-1);
            }else{
                $user_info['two_num_id'] = "";
            }
            //每个人推荐的二级人数
            $user_info['two_num'] = $groom_info_second['counts'];
            // $user_info['two_num_info'] = $groom_info_second['counts_info'];
            $user_info['sum_second_rec_monry'] = $groom_info_second['sum_second_rec_monry'];
        }else{
            $arr = array();
            $user_info['two_num'] = 0;
            $user_info['two_num_id'] = "";
            //$user_info['two_num_info'] = $arr;
            $user_info['sum_second_rec_monry'] = 0;
        }
//        dump($user_info);die;
        //显示总入股数量
        $user_info['partner']=($user_info['sum_second_rec_monry'] + $user_info['sum_one_rec_monry'])/100000;
        $this->assign('data',$user_info);
        return $this->fetch();
    }
    public function index(){
        $user_id = session('user_id');
        //查询当前用户的积分信息
        $nowUserInfo=Db::table('tea_user_recharge')->where('user_id',$user_id)->sum('rec_money');
        $groom_info = "";
        $groom_info = $this->groom_info($user_id);
        dump($groom_info);
        //每个人推荐的一级人数
        $user_info['one_num'] = $groom_info['num'];
        //每个人推荐的一级人的id
        $user_info['one_num_id'] = $groom_info['num_id'];
        // $user_info['one_num_info'] = $groom_info['num_info'];
        $user_info['sum_one_rec_monry'] = $groom_info['sum_one_rec_monry']+floatval($nowUserInfo);
        //dump($user_info);die;
        //每个人推荐的二级人数
        if($groom_info['num_id']){
            $groom_info_second = $this->groom_info_second($groom_info['num_id']);
            //dump($groom_info_second);die;
            if($groom_info_second['counts_id']){
                //每个人推荐的二级人的id
                $user_info['two_num_id'] = substr($groom_info_second['counts_id'],0,strlen($groom_info_second['counts_id'])-1);
            }else{
                $user_info['two_num_id'] = "";
            }
            //每个人推荐的二级人数
            $user_info['two_num'] = $groom_info_second['counts'];
            // $user_info['two_num_info'] = $groom_info_second['counts_info'];
            $user_info['sum_second_rec_monry'] = $groom_info_second['sum_second_rec_monry'];
        }else{
            $arr = array();
            $user_info['two_num'] = 0;
            $user_info['two_num_id'] = "";
            //$user_info['two_num_info'] = $arr;
            $user_info['sum_second_rec_monry'] = 0;
        }
        dump($user_info);die;
    }
    //发送AJAX返回数据 我的一级推荐
    public function recommenders(){
        $user_id = session('user_id');
        $childId = $this->getChilderId($user_id);
        if (empty($childId )) return 0;
        foreach ($childId as $k =>$v){
            $childId[$k]['gufen']=Db::name('recharge_cart')->where('is_ceo',1)->where('pay_status',1)->where('user_id',$v['user_id'])->count();
        }
//        foreach ($childId as $k=>$v){
//            $childId[$k]['sum'] = $this->getChilderSum($v['user_id']);
//            $childId[$k]['status'] = $this->getLev($v['user_id']);
//        }

//        $data = $this->groupByInitials($childId, 'user_name');
//        foreach ($data as $k1 => $v1) {
//            foreach ($v1 as $v2) {
//                $list[] = $v2;
//            }
//        }
        if($childId){
            return json($childId);
        }else{
            return 0;
        }
    }


    //发送AJAX返回数据 我的二级推荐
    public function vcm(){
        $user_id = session('user_id');

        $info_list = Db::connect(config('db_config2'))->name('users')->field('user_id')->where("parent_id",$user_id)->where('user_rank','in','10,9')->select();
        if (empty($info_list)) return 0;
        foreach($info_list as $k=>$v){
            $a[] = Db::connect(config('db_config2'))->name('users')->field('user_id,user_name')->where("parent_id",$v['user_id'])->where('user_rank','in','10,9')->select();
        }
        foreach($a as $vo){
            if($vo != ""){
                foreach($vo as $v2){
                    $ss[] = $v2;
                }
            }
        }
        foreach ($ss as $k3 => $v3) {
//            $ss[$k3]['sum'] = $this->getChilderSum($v3['user_id']);
//            $ss[$k3]['status'] = $this->getLev($v3['user_id']);
            $ss[$k3]['gufen']=Db::name('recharge_cart')->where('is_ceo',1)->where('pay_status',1)->where('user_id',$v3['user_id'])->count();
        }

//        $data = $this->groupByInitials($ss, 'user_name');
//        foreach ($data as $k1 => $v1) {
//            foreach ($v1 as $v4) {
//                $list[] = $v4;
//            }
//        }
//        dump($ss);die;
        if($ss){
            return json($ss);
        }else{
            return 0;
        }
    }
//获取用户等级
    public function getLev($id){
        $data =Db::table('tea_user_recharge')->where('user_id='.$id)->order('id desc')->limit(1)->find();
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

    //通过用户id 找下一级用户id
    public function getChilderId($user_id){
        return Db::connect(config('db_config2'))
            ->name('users')
            ->field('user_id,user_name,nick_name,parent_id,user_rank')
            ->where('user_rank','in','10,9')
            ->where("parent_id",$user_id)
            ->select();
        //return D('User')->where('parent_id = '.$user_id)->select();

    }

    /**
     * 二维数组根据首字母分组排序
     * @param  array  $data      二维数组
     * @param  string $targetKey 首字母的键名
     * @return array             根据首字母关联的二维数组
     */
    public function groupByInitials(array $data, $targetKey = 'user_name')
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

    //通过id找到下一级用户购买总额
    public function getChilderSum($user_id=''){
        //$child_list =  D('User')->where('parent_id = '.$user_id)->Field('id')->select();
        //dump($child_list);
        //$sum = M('RechargeCart')->where('user_id in $child_list  AND pay_status = 1')->SUM('recharge_money');
        //return $sum;

        // 取得上级所有直接下级用户id
        //$lower_users_id = M('user')->where('parent_id='.$user_id)->Field('id')->select();
        $lower_users_id=Db::connect(config('db_config2'))->name('users')->where("parent_id",$user_id)->where('user_rank','in','10,9')->Field('user_id')->select();
        //定义查询上级所有直接下级用户总额条件
        $id_arr['user_id'] = array('in');
        if(empty($lower_users_id)){
            $id_arr['user_id'][] = 0;
            $lower_users_count_money=0.00;
        }else{
            foreach ($lower_users_id as $v)
            {
                $temp_arr[] = $v['user_id'];
            }
            $id_arr['user_id'][] = $temp_arr;
            $lower_users_count_money = Db::table('tea_user_recharge')->where($id_arr)->SUM('rec_money');
        }

        // 取得上级所有直接下级用户购买理茶宝总额
        return intval($lower_users_count_money);
    }


    //获取用户总金额
    public function  getMoneySum($id){
        $data = Db::table('tea_recharge_cart')->where(array('parent_id'=>$id,'pay_status'=>1))->select();
        $sum = 0;
        if($data){
            foreach($data as $k => $v){
                $sum = $v['recharge_money']+$sum;
            }
        }
        return  $sum;
    }

    //获取每个人的推荐人一级（直推）
    public function groom_info($id){

        //$groom_info = Db::table("tea_user")->field('id,user_name,parent_id')->where("parent_id",$id)->select();
        $groom_info = Db::connect(config('db_config2'))->name('users')->where("parent_id",$id)->where('user_rank','in','10,9')->select();
        $one_rec_monry = 0;
        foreach($groom_info as $k => $v){
            $groom_info[$k]['integral'] = $this->last_integral(intval($v['user_id']));
            $one_rec_monry += $groom_info[$k]['integral']['sum_rec_money'];
        }
        $list['num'] = count($groom_info);
        $list['num_id'] = $this->getId($groom_info);
        //$list['num_info'] = $groom_info;
        $list['sum_one_rec_monry'] = $one_rec_monry;
        return $list;
    }

    //获取每个人二推人数
    public function groom_info_second($groom_info_id){
        $arr = explode(",",$groom_info_id);
        $counts = 0;
        $counts_id = "";
        $counts_sum = 0;
        $count_arr = array();
        for($i = 0;$i< count($arr);$i++){
            $infos = $this->groom_info($arr[$i]);
            $counts += intval($infos['num']);
            if($infos['num_id']!=""){
                $counts_id .= $infos['num_id'] .",";
            }
            //$count_arr = array_merge($count_arr, $infos['num_info']);
            $counts_sum += intval($infos['sum_one_rec_monry']);
        }
        $list['counts'] = $counts;
        $list['counts_id'] = $counts_id;
        //$list['counts_info'] = $count_arr;
        $list['sum_second_rec_monry'] = $counts_sum;
        return $list;
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
        $sum_user_integral_data = Db::query("select sum(rec_money) as 'sum_rec_money' from tea_user_recharge where user_id = $user_id");
        $list['sum_rec_money'] = $sum_user_integral_data[0]['sum_rec_money'];
        //$data['integral'] = $list;
        return $list;
    }

    //代人注册
    public function otherreg(){
        return $this->fetch();
    }

    public function hhgg(){
        return $this->fetch();
    }

}