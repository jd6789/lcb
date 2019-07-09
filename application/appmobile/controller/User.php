<?php
/**
 * Created by PhpStorm.
 * user: jieyu
 * Date: 2018/4/8
 * Time: 11:43
 */
namespace app\appmobile\controller;
use think\Controller;
use think\Cookie;
use think\Request;
use app\appmobile\model\Huiyuan;
use think\Session;
use think\Db;
use think\Upload;

class User extends Common
{
    //判断用户是否登录
    public function checkLogin(){
        if(!session('user_id')){
            $this->error('未登录','appmobile/user/login');
            return false;
        }
        return true;
    }
    public function index()
    {

        return $this->fetch();
    }
    public function accountlink()
    {
        $this->checkLogin();
        return $this->fetch();
    }


    public function confirm()
    {
        $this->checkLogin();
        return $this->fetch();
    }
    public function myrichardtea()
    {
        $this->checkLogin();
        return $this->fetch();
    }
    public function tellogin()
    {
        return $this->fetch();
    }
    public function richardtea()
    {
        $this->checkLogin();
        return $this->fetch();
    }
    public function repwd()
    {
        return $this->fetch();
    }

    public function bindphone()
    {
        return $this->fetch();
    }
    public function problem()
    {
        return $this->fetch();
    }

    public function personcenter()
    {
        if(request()->isAjax()) {
            $user_id = cookie('user')['user_id'];
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
        return $this->fetch();
    }
    public function record()

    {
        $this->checkLogin();
        return $this->fetch();
    }
    public function mywallet()
    {
        return $this->fetch();
    }

    public function paysuccess()
    {
        return $this->fetch();
    }
    public function paycode()
    {
        return $this->fetch();
    }

    public function friends()
    {
        return $this->fetch();
    }

    //通过id找到下一级用户购买总额
    public function getChilderSum($user_id){
        // 取得上级所有直接下级用户id
        $lower_users_id = Db::connect(config('db_config2'))->name('users')->where('parent_id='.$user_id)->Field('user_id')->select();
//dump($lower_users_id);die;
        //定义查询上级所有直接下级用户总额条件
        $id_arr['user_id'] = array('in');

        foreach ($lower_users_id as $v)
        {
            $temp_arr[] = $v['user_id'];
        }

        $id_arr['user_id'][] = $temp_arr;
        // 取得上级所有直接下级用户购买理茶宝总额
        $lower_users_count_money = Db::table('tea_recharge_cart')->where($id_arr)->SUM('recharge_money');
        //dump($lower_users_count_money);
        return intval($lower_users_count_money);
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

public function ceshi()
{
    $user_id = 82;
    $user_info_list = Db::connect(config('db_config2'))->name('users')->select();
    foreach($user_info_list as $key=>$value){
        if($value['parent_id'] == $user_id){
            $info_list[] = $value;
        }
    }
    //dump($info_list);die;
    foreach($user_info_list as $k=>$v){
        //$a[] = Db::connect(config('db_config2'))->name('users')->getChild($v['user_id']);
        foreach($info_list as $k1 =>$v1){
            if($v['parent_id'] == $v1['user_id']){
                $a[] = $v;
            }
        }
    }


    foreach ($a as $k3 => $v3) {

        $ss[$k3]['sum'] = Db::table('tea_user_recharge')->where('user_id',$v3['user_id'])->value('rec_money');
        $ss[$k3]['status'] = $this->getLev($v3['user_id']);
    }

    dump($ss);die;
}

    //我的二级推荐
    public function ceshi2()
    {
        $user_id = 82;
        //$user_id = cookie('user')['user_id'];
        //用户表所有的用户信息
        $user_info_list = Db::connect(config('db_config2'))->name('users')->field('user_id,user_name,parent_id')->select();
        //dump($user_info_list);die;
        //$info_list = $this->getChild($user_id);

        //下一级的用户id
        foreach($user_info_list as $key=>$value){
            if($value['parent_id'] == $user_id){
                $info_list[] = $value;
            }
        }
        //dump($info_list);die;

        //下二级用户id
        foreach($user_info_list as $k=>$v){
            //$a[] = Db::connect(config('db_config2'))->name('users')->getChild($v['user_id']);
            foreach($info_list as $k1 =>$v1){
                if($v['parent_id'] == $v1['user_id']){
                    $a[] = $v;
                }
            }
        }
        //dump($a);

        foreach ($a as $k3 => $v3) {
            //$ss[$k3]['sum'] = $this->getChilderSum($v3['id']);
            $ss[$k3]['recharge_money'] = Db::table('tea_recharge_cart')->where('user_id',$v3['user_id'])->value('recharge_money');
            $ss[$k3]['again_money'] = Db::table('tea_recharge_cart')->where('user_id',$v3['user_id'])->value('again_money');
            $ss[$k3]['status'] = $this->getLev($v3['parent_id']);
            $ss[$k3]['parent_id'] = $a[$k3]['parent_id'];
        }
//dump($ss);
        for($i=0;$i<count($ss);$i++){
            for($j=1+$i;$j<count($ss);$j++){
                if($ss[$i]['parent_id'] == $ss[$j]['parent_id']){
                    $ss[$i]['recharge_money'] = $ss[$i]['recharge_money']+$ss[$j]['recharge_money'];
                    $ss[$i]['again_money'] = $ss[$i]['again_money']+$ss[$j]['again_money'];
                    array_splice($ss[$j],0,count($ss[$j]));
                }
            }
        }
        for($r = 0; $r< count($ss) ; $r++){
            if(empty($ss[$r])){
                //$ss[$r] = $ss[$r+1];
                unset($ss[$r]);
            }
        }
        //$ss['sum'] = $ss['again_money']+$ss['recharge_money'];
        foreach($ss as $k => $v){
            $ss[$k]['sum'] = $ss[$k]['again_money']+$ss[$k]['recharge_money'];
        }


        //20:24     2018/4/26

//        foreach($info_list as $m => $n){
//            dump($n);
//            foreach($ss as $y => $h){
//                if($n['user_id'] == $h['parent_id']){
//                    $cc['sum'] = $h['sum'];
//                }else{
//                    $cc['sum'] = 0;
//                }
//            }
//        }
//        dump($ss);

        dump($ss);die;
    }

    public function vcm(){
        $user_id = cookie('user')['user_id'];
        if(request()->isAjax()){
            $info_list = Db::connect(config('db_config2'))->name('users')->getChild($user_id);
            foreach($info_list as $k=>$v){
                $a[] = Db::connect(config('db_config2'))->name('users')->getChild($v['user_id']);
            }
            foreach($a as $vo){
                if($vo != ""){
                    foreach($vo as $v2){
                        $ss[] = $v2;
                    }
                }
            }
            foreach ($ss as $k3 => $v3) {
                //$ss[$k3]['sum'] = $this->getChilderSum($v3['id']);
                $ss[$k3]['sum'] = Db::table('tea_user_recharge')->where('user_id',$v3['user_id'])->value('rec_money');
                $ss[$k3]['status'] = $this->getLev($v3['id']);
            }
            dump($ss);
        }
    }

    //二级市场
    public function my_two()
    {
        if(request()->isAjax()){
            $user_id = cookie('user')['user_id'];
            //用户表所有的用户信息
            $user_info_list = Db::connect(config('db_config2'))->name('users')->field('user_id,user_name,parent_id')->select();
            //dump($user_info_list);die;
            //$info_list = $this->getChild($user_id);

            //下一级的用户id
            foreach($user_info_list as $key=>$value){
                if($value['parent_id'] == $user_id){
                    $info_list[] = $value;
                }
            }
            //dump($info_list);

            //下二级用户id
            foreach($user_info_list as $k=>$v){
                //$a[] = Db::connect(config('db_config2'))->name('users')->getChild($v['user_id']);
                foreach($info_list as $k1 =>$v1){
                    if($v['parent_id'] == $v1['user_id']){
                        $a[] = $v;
                    }
                }
            }
            //dump($a);

            //下三级的用户id
            foreach($user_info_list as $k=>$v){
                //$a[] = Db::connect(config('db_config2'))->name('users')->getChild($v['user_id']);
                foreach($a as $k1 =>$v1){
                    if($v['parent_id'] == $v1['user_id']){
                        $cc[] = $v;
                    }
                }
            }
            //dump($cc);

            foreach ($cc as $k3 => $v3) {
                //$ss[$k3]['sum'] = $this->getChilderSum($v3['id']);
                $cc[$k3]['recharge_money'] = Db::table('tea_recharge_cart')->where('user_id',$v3['user_id'])->value('recharge_money');
                $cc[$k3]['again_money'] = Db::table('tea_recharge_cart')->where('user_id',$v3['user_id'])->value('again_money');
                $cc[$k3]['status'] = $this->getLev($v3['parent_id']);
                //$cc[$k3]['parent_id'] = $a[$k3]['parent_id'];
            }

            for($i=0;$i<count($cc);$i++){
                for($j=1+$i;$j<count($cc);$j++){
                    if($cc[$i]['parent_id'] == $cc[$j]['parent_id']){
                        $cc[$i]['recharge_money'] = $cc[$i]['recharge_money']+$cc[$j]['recharge_money'];
                        $cc[$i]['again_money'] = $cc[$i]['again_money']+$cc[$j]['again_money'];
                        array_splice($ss,$j,1);
                        $j = $i;
                    }
                }
            }
            //dump($cc);die;
            for($r = 0; $r< count($cc) ; $r++){
                if(empty($cc[$r])){
                    //$ss[$r] = $ss[$r+1];
                    unset($cc[$r]);
                }
            }
            //dump($cc);die;
            //$ss['sum'] = $ss['again_money']+$ss['recharge_money'];
            foreach($cc as $k => $v){
                $cc[$k]['sum'] = $cc[$k]['again_money']+$cc[$k]['recharge_money'];
            }
            foreach($a as $k => $v){
                //$child_user_id[$k]['sum'] = $this->getChilderSum($user_id);
                $a[$k]['status'] = $this->getLev($v['user_id']);
//start
                //取得上级所有直接下级用户id (二级的user_id)
                /*$lower_users_id[] = Db::connect(config('db_config2'))->name('users')->where('parent_id='.$v['user_id'])->Field('user_id')->select();
//end
                foreach($lower_users_id as $k1=>$v1){
                    foreach($v1 as $k2=>$v2){
                        $child_user_id[$k1]['recharge_money'] = Db::table('tea_recharge_cart')->where("user_id=".$v2['user_id'])->value('recharge_money');
                        $child_user_id[$k1]['again_money'] = Db::table('tea_recharge_cart')->where("user_id=".$v2['user_id'])->value('again_money');
                        $child_user_id[$k1]['sum'] = $child_user_id[$k1]['recharge_money']+$child_user_id[$k1]['again_money'];
                    }
                }
                if(empty($child_user_id[$k1]['sum'])){
                    $child_user_id[$k]['sum'] = 0;
                }*/
            }
            //dump($cc);die;
//        foreach($a as $k => $v){
//            //$child_user_id[$k]['sum'] = $this->getChilderSum($user_id);
//            $a[$k]['status'] = $this->getLev($v['user_id']);
//        }
            //20:24     2018/4/26
            //dump($cc);

            if($cc || $a){
                $data['a'] = $a;
                $data['cc'] = $cc;
                return json(array('data'=>$data,'status'=>1));
            }else{
                //$child_user_id['ss'] = $ss;

                return json(array('status'=>0));
            }

//            $data['cc'] = $cc;
//            $data['a'] = $a;
//            return json(array('data'=>$data));
        }
    }

    //我的推荐
    public function recommend()
    {
        $data_two = array();
        $data_one = array();
        if(request()->isAjax()){
            //当前会员的user_id
            $user_id = cookie('user')['user_id'];
            //当前会员的下一级的user_id
            $child_user_id = Db::connect(config('db_config2'))->name('users')->where("parent_id",$user_id)->field('user_id,user_name')->select();


            $user_info_list = Db::connect(config('db_config2'))->name('users')->field('user_id,user_name,parent_id')->select();
            //dump($user_info_list);die;
            //$info_list = $this->getChild($user_id);

            //下一级的用户id
            foreach($user_info_list as $key=>$value){
                if($value['parent_id'] == $user_id){
                    $info_list[] = $value;
                }
            }
            //dump($info_list);die;

            //下二级用户id
            foreach($user_info_list as $k=>$v){
                //$a[] = Db::connect(config('db_config2'))->name('users')->getChild($v['user_id']);
                foreach($info_list as $k1 =>$v1){
                    if($v['parent_id'] == $v1['user_id']){
                        $a[] = $v;
                    }
                }
            }
            //dump($a);

            foreach ($a as $k3 => $v3) {
                //$ss[$k3]['sum'] = $this->getChilderSum($v3['id']);
                $ss[$k3]['recharge_money'] = Db::table('tea_recharge_cart')->where('user_id',$v3['user_id'])->value('recharge_money');
                $ss[$k3]['again_money'] = Db::table('tea_recharge_cart')->where('user_id',$v3['user_id'])->value('again_money');
                $ss[$k3]['status'] = $this->getLev($v3['parent_id']);
                $ss[$k3]['parent_id'] = $a[$k3]['parent_id'];
            }
//dump(count($ss));
//            for($i=0;$i<count($ss);$i++){
//                for($j=1;$j<$i;$j++){
//                    if($ss[$i]['parent_id'] == $ss[$j]['parent_id']){
//                        $ss[$i]['recharge_money'] = $ss[$i]['recharge_money']+$ss[$j]['recharge_money'];
//                        $ss[$i]['again_money'] = $ss[$i]['again_money']+$ss[$j]['again_money'];
//                        unset($ss[$j]);
//                    }
//                }
//            }

            for($i=0;$i<count($ss);$i++){
                for($j=1+$i;$j<count($ss);$j++){
                    if($ss[$i]['parent_id'] == $ss[$j]['parent_id']){
                        $ss[$i]['recharge_money'] = $ss[$i]['recharge_money']+$ss[$j]['recharge_money'];
                        $ss[$i]['again_money'] = $ss[$i]['again_money']+$ss[$j]['again_money'];
                        array_splice($ss,$j,1);
                        $j = $i;
                    }
                }
            }
            for($r = 0; $r< count($ss) ; $r++){
                if(empty($ss[$r])){
                    //$ss[$r] = $ss[$r+1];
                    unset($ss[$r]);
                }
            }

            //$ss['sum'] = $ss['again_money']+$ss['recharge_money'];
            foreach($ss as $k => $v){
                $ss[$k]['sum'] = $ss[$k]['again_money']+$ss[$k]['recharge_money'];
            }




            foreach($child_user_id as $k => $v){
                //$child_user_id[$k]['sum'] = $this->getChilderSum($user_id);
                $child_user_id[$k]['status'] = $this->getLev($v['user_id']);
//start
                //取得上级所有直接下级用户id (二级的user_id)
                /*$lower_users_id[] = Db::connect(config('db_config2'))->name('users')->where('parent_id='.$v['user_id'])->Field('user_id')->select();
//end
                foreach($lower_users_id as $k1=>$v1){
                    foreach($v1 as $k2=>$v2){
                        $child_user_id[$k1]['recharge_money'] = Db::table('tea_recharge_cart')->where("user_id=".$v2['user_id'])->value('recharge_money');
                        $child_user_id[$k1]['again_money'] = Db::table('tea_recharge_cart')->where("user_id=".$v2['user_id'])->value('again_money');
                        $child_user_id[$k1]['sum'] = $child_user_id[$k1]['recharge_money']+$child_user_id[$k1]['again_money'];
                    }
                }
                if(empty($child_user_id[$k1]['sum'])){
                    $child_user_id[$k]['sum'] = 0;
                }*/
            }
            if($ss || $child_user_id){
                $data['aa'] = $child_user_id;
                $data['ss'] = $ss;
                return json(array('data'=>$data,'status'=>1));
            }else{
                //$child_user_id['ss'] = $ss;

                return json(array('status'=>0));
            }
        }else{
            //当前会员的用户id
            $user_id = cookie('user')['user_id'];

            //下一级的user_id
            $child_user_id = Db::connect(config('db_config2'))->name('users')->where("parent_id",$user_id)->field('user_id,user_name')->select();

            foreach($child_user_id as $k => $v){

//start
                //取得上级所有直接下级用户id (二级的user_id)
                $lower_users_id[] = Db::connect(config('db_config2'))->name('users')->where('parent_id='.$v['user_id'])->Field('user_id')->select();
//end
                foreach($lower_users_id as $k1=>$v1){
                    //dump($v1);echo 1;
                    foreach($v1 as $k2=>$v2){
                        //dump($v2);echo 2;

                        $child_user_id[$k1]['recharge_money'] = Db::table('tea_recharge_cart')->where("user_id=".$v2['user_id'])->sum('recharge_money');
                        $child_user_id[$k1]['again_money'] = Db::table('tea_recharge_cart')->where("user_id=".$v2['user_id'])->value('again_money');
                        $child_user_id[$k1]['sum'][] = $child_user_id[$k1]['recharge_money']+$child_user_id[$k1]['again_money'];
//                        foreach($child_user_id as $m=>$n){
//                            //dump($n);
//                            if(!empty($n['recharge_money']) || !empty($n['again_money'])){
//                                $child_user_id[$k1]['sum'] = $n['recharge_money']+$n['again_money'];
//                            }
//                        }
                    }
                }
                if(empty($child_user_id[$k1]['sum'])){
                    $child_user_id[$k]['sum'] = 0;
                }
                $child_user_id[$k]['status'] = $this->getLev($v['user_id']);
            }

//dump($lower_users_id);


            $res = $this->getNumber($user_id);
            //下一级人数
            $this->assign('one',$res['one']);
            //下二级人数
            $this->assign('two',$res['two']);
            //dump($res);

            //$total = $this->getCateTree($user_id);
            $data = $user_info_list = Db::connect(config('db_config2'))->name('users')->field('user_id,user_name,parent_id')->select();
            //所有下一级
            foreach($data as $k1=>$v1){
                if($v1['parent_id'] == $user_id){
                    $data_one[] = $v1;
                }
            }
            //所有下二级
            foreach($data as $k=>$v){
                foreach($data_one as $k1 => $v1){
                    if($v['parent_id'] == $v1['user_id']){
                        $data_two[] = $v;
                    }
                }
            }

            //合并下属的一级 二级 会员
            $total = array_merge($data_one,$data_two);

            //团队总人数
            $num_num = count($total)+1;
            $this->assign('num_num',$num_num);
            //dump($num_num);

            //团队业绩
            foreach($total as $k => $v){
                $list[] = $v['user_id'];
            }
            $list[] = $user_id;
            $list_user_id = implode(',',$list);
            $list_user_id = $list_id = substr($list_user_id, 0,strlen($list_user_id));
            //dump($list_user_id);
            $recharge_money = Db::table('tea_recharge_cart')->where("pay_status = 1 and user_id in ($list_user_id)")->SUM('recharge_money');
            $again_money = Db::table('tea_recharge_cart')->where("pay_status = 1 and user_id in ($list_user_id)")->SUM('again_money');
            //$money_total = number_format($recharge_money+$again_money,2);
            $money_total = $recharge_money+$again_money;
          // dump($money_total);die;
	
            //dump($child_user_id);die;
            $this->assign('money_total',$money_total);
            $this->assign('child',$child_user_id);
            return $this->fetch();
        }
    }

    //获取推荐人数
    public function getNumber($user_id)
    {
        $one = array();
        $two = array();
        //先获取所有的分类信息
        $data = Db::connect(config('db_config2'))->name('users')->select();
        //dump($data);
        //获得每个用户一级推荐人信息（人数）
        foreach ($data as $k => $v) {
            //dump($v);
            $c = $v['user_id'];
            //dump($c);die;
            $i = 0;
            foreach ($data as $k1 => $v1) {
                $arr[$c] = $i;
                //dump($arr);die;
                if ($v1['parent_id'] == $c) {
                    $i++;
                    $list[$c][] = $v1['user_id'];
                    //dump($list);die;
                    /*$list = array(1){
                        [61] => array {
                            [0] => 62}}*/
                    $arr[$c] = $i;
                    //dump($arr);die;     $arr=array('61'=>1)
                    //$list1[$c][] = $v1;
                }
            }
        }

        //获得每个二级推荐人人数
        //dump($list);die;
        foreach($list as $k=>$v){
            //dump($list);die;
            //dump($k);
            $sum = 0;
            foreach($v as $k1=>$v2){
                $n = (int)$v2;
                //dump($n);
                //dump($arr);
                $sum += $arr[$n];
                $list2[$k] = $sum;    //每个会员的二级推荐人数
                //dump($list2);
            }
        }
        //dump($arr);die;
        foreach($arr as $k => $v){
            //dump($arr);
            //dump($list);
            //dump($k);
            if($v == 0){
                $list[$k] = 0;
            }
        }
        $info['once'] = $arr;
        $info['second'] = $list2;
        //dump($info);die;
        //dump($user_id);
        foreach($arr as $k => $v){
            $user_id = (int)$user_id;
            //dump($user_id);
            //dump($v);
            if($k == $user_id){
                $one = $arr[$k];
            }
        }
        //dump($one);die;
        //dump($list2);die;
        foreach($list2 as $k1 => $v1){
            $user_id = (int)$user_id;
            if($k1 == $user_id){
                $two = $list2[$k1];
            }
        }

        $info['one'] = $one;
        $info['two'] = $two;

        //dump($info);die;
        return $info;
    }

    //团队人数
    //无限级分类
    //获取格式化之后的数据
    public function getCateTree($id=0)
    {
        //先获取所有的分类信息
        $data = Db::connect(config('db_config2'))->name("users")->select();
        //dump($data);die;
        //在对获取的信息进行格式化
        $list = $this->getTree($data,$id);
        //dump($list);die;
        return $list;
    }

    //格式化分类信息
    public function getTree($data,$id=0,$lev=1)
    {
        static $list = array();
        foreach ($data as $key => $value) {
            //dump($value);die;
            if($value['parent_id']==$id){
                $value['lev']=$lev;
                $list[]=$value;
                //使用递归的方式获取分类下的子分类
                $this->getTree($data,$value['user_id'],$lev+1);
            }
        }
        return $list;
    }

    //实名认证
    public function realname()
    {

//        if(request()->isAjax()){
//            $user_id = cookie('user')['user_id'];
//            $is_real = Db::connect(config('db_config2'))->name('users_real')->where("user_id",$user_id)->find();
//
//            if($is_real){
//                return json(array('status'=>1,'data'=>$is_real));
//            }else{
//                $parent_id = Db::connect(config('db_config2'))->name('users')->where("user_id",$user_id)->value('parent_id');
//                if($parent_id == 0){
//                    return json(array('parent_id'=>0));
//                }else{
//                    return json(array('parent_id'=>$parent_id));
//                }
//            }
//        }

        if(request()->isPost()){
            $user_id = cookie('user')['user_id'];
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
            $dir= '/public/uploads';
            $face = $dir . $face1;
            $back = $dir . $back1;
            $real_name = trim(input('post.real_name'));
            $sex = trim(input('post.sex'));
            $idcard = trim(input('post.idcard'));
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
            $data['user_id'] = cookie('admin')['user_id'];

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
                //将验证信息同步到平台
                $userinfo = Db::connect(config('db_config2'))->name("users_real")->where("user_id",$user_id)->find();
//                if($userinfo){
//                    Db::connect(config('db_config2'))->name("users_real")->where("user_id",$user_id)->update([
//                        'real_name'=>$real_name,
//                        'self_num'=>$idcard,
//                        'add_time'=>time(),
//                        'front_of_id_card'=>$face2,
//                        'reverse_of_id_card'=>$back2,
//                    ]);
//                }else{
//                    Db::connect(config('db_config2'))->name("users_real")->insert([
//                        'user_id'=>$user_id,
//                        'real_name'=>$real_name,
//                        'self_num'=>$idcard,
//                        'add_time'=>time(),
//                        'front_of_id_card'=>$face2,
//                        'reverse_of_id_card'=>$back2,
//                    ]);
//                }

                $this->success('验证通过',url('user/myinfo'));
            }else{
                $this->error('服务器维护中');
            }
        }else{
            return $this->fetch();
        }
    }

    public function realname111()
    {
        if(request()->isPost()){
            $face_img = $this->request->file('zhengmian');
//dump($face_img);
            $back_img = $this->request->file('fanmian');
            if($face_img){
                $info1 = $face_img->move(ROOT_PATH . 'public' . DS . 'uploads');
//dump($info1);
                //$info1 = $face_img->move('/public/uploads/');
                if($info1){
                    $face1 = $info1->getSaveName();
                    //$face1 = $info1->getPathname();
                    $face2 = "local.lcb.com/Lcb/tea_treasure/public/uploads/".$face1;
                    //$face2 = "local.lcb.com/uploads/".$face1;
                    //dump($face2);die;
                    // 成功上传后 获取上传信息
                    // 输出 jpg
                    //echo $face_img->getExtension();
                    // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                    //echo $face_img->getSaveName();
                    // 输出 42a79759f284b767dfcb2a0197904287.jpg
                    //echo $face_img->getFilename();
                }else{
                    // 上传失败获取错误信息
                    echo $face_img->getError();
                }
            }
            if($back_img){
                $info2 = $back_img->move(ROOT_PATH . 'public' . DS . 'uploads');
                //dump($info2);
                if($info2){
                    $back1 = $info2->getSaveName();
                    $back2 = "local.lcb.com/Lcb/tea_treasure/public/uploads/".$back1;
                    // 成功上传后 获取上传信息
                    // 输出 jpg
                    //echo $back_img->getExtension();
                    // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                    //echo $back_img->getSaveName();
                    // 输出 42a79759f284b767dfcb2a0197904287.jpg
                    //echo $back_img->getFilename();
                }else{
                    // 上传失败获取错误信息
                    echo $back_img->getError();
                }
            }

            $dir= '/public/uploads';
            $face = $dir . $face1;
            //$back = $dir . $back_img['savepath'] . $back_img['savename'];
            $back = $dir . $back1;
            $real_name = trim(input('post.real_name'));

            $sex = trim(input('post.sex'));
            $idcard = trim(input('post.idcard'));
            $rec_tel = trim(input('post.rec_tel'));

            //判断验证码是否正确
            $getCode = input('post.code');
            if($getCode){
                //dump($getCode);die;
                $code = session('code');
                if($getCode != $code){
                    $this->error('验证码错误');
                }
            }
            //$data['rec_tel'] = $rec_tel;
            $data['real_name'] = $real_name;
            $data['sex'] = $sex;
            $data['idcard'] = $idcard;
            $data['face_img'] = $face;
            $data['back_img'] = $back;
            $data['is_pass'] = 0;
            $data['is_first'] = 1;
            $data['addtime'] = time();
            $data['user_id'] = cookie('admin')['user_id'];
            //dump($data);die;
            $user_id = cookie('user')['user_id'];
            $user = Db::table('tea_real_name')->where("user_id",$user_id)->find();
            if(!$user){
                $cc = Db::table('tea_real_name')->insert($data);
            }else{
                $cc = Db::table('tea_real_name')->where("user_id",$user_id)->update($data);
            }

            if($cc){
                //cookie::set('success',1);
                //return json(array('status'=>1,'data'=>"上传成功"));
                //将验证信息同步到平台
                $userinfo = Db::connect(config('db_config2'))->name("users_real")->where("user_id",$user_id)->find();
                if($userinfo){
                    Db::connect(config('db_config2'))->name("users_real")->where("user_id",$user_id)->update([
                        'real_name'=>$real_name,
                        'self_num'=>$idcard,
                        'add_time'=>time(),
                        'front_of_id_card'=>$face2,
                        'reverse_of_id_card'=>$back2,
                    ]);
                }else{
                    Db::connect(config('db_config2'))->name("users_real")->insert([
                        'user_id'=>$user_id,
                        'real_name'=>$real_name,
                        'self_num'=>$idcard,
                        'add_time'=>time(),
                        'front_of_id_card'=>$face2,
                        'reverse_of_id_card'=>$back2,
                    ]);
                }

                $this->success('验证通过',url('user/myinfo'));
                //return json_encode(array('status'=>1,'data'=>"上传成功"));
            }else{
                //cookie('success',0);
                //return json(array('status'=>0,'data'=>"上传失败"));
                $this->error('服务器维护中');
            }
        }else{
            return $this->fetch();
        }
        /*$user_id = cookie('user')['user_id'];
        $info = Db::table('tea_real_name')->where("user_id=$user_id")->find();
        if(!$info){
            $is_first = 0;
        }else{
            $is_first = 1;
            if($info['is_pass'] != 1){
                $is_pass = 0;
            }else{
                $is_pass = 1;
            }
        }
        $this->assign('is_first', $is_first);
        $this->assign('is_pass', $is_pass);*/
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

    //快捷登录
    public function login_iphone()
    {
        $tel = input('post.tel');
        $mobileCode = input('post.mobileCode');
        $code = session('code');
        if($mobileCode != $code){
            return json_encode(array('msg'=>"验证码错误",'status'=>0));
        }
        session::set('msg',null);
        //验证用户是否正确
        $info = db('user')->checkTel($tel);
        if(!$info){
            return json_encode(array('msg'=>"登陆失败",'status'=>0));
        }else{
            return json_encode(array('msg'=>"登录成功",'status'=>1));
        }
    }

    //找回密码1
    public function reppwd()
    {
        if(request()->isAjax()){
            //判断验证码是否正确
            $code = session('code');
            $user_code = input('post.code');
            if($user_code != $code){
                return json_encode(array("data"=>"验证码错误",'status'=>0));
            }else{
                return (1);
            }
        }else{
            return $this->fetch();
        }

    }
    //找回密码2
    public function reppwd2()
    {
        if(request()->isAjax()){
            $username = input('post.username');
            $userinfo = db('user')->where("username",$username)->find();

            //重置密码
            $password1 = input('post.password1');
            $password2 = input('post.password2');
            //判断密码是否一致
            if($password1 != $password2){
                return json_encode(array('data'=>"亲,两次密码必须一致哦!!!",'status'=>0));
            }
            $salt = $userinfo['salt'];
            $password = md5(md5($password1).$salt);

            //判断密码修改是否成功
            $res = db('user')->where("username",$username)->setField('password',$password);
            if($res){
                return json_encode(array('data'=>"密码修改成功",'status'=>1));
            }else{
                return json_encode(array('data'=>"密码修改失败",'status'=>0));
            }
        }else{
            return $this->fetch();
        }

    }
    //找回密码3
    public function reppwd3()
    {
        if(request()->isAjax()){
           $tel = input('post.tel');
           $username = input('post.username');
           $userinfo = Db::connect(config('db_config2'))->name("users")->where("mobile_phone='$tel' and user_name='$username'")->find();
            //判断验证码是否正确
            $code = session('code');
            $user_code = input('post.code');
            if($user_code != $code){
                return json_encode(array("data"=>"验证码错误",'status'=>3));
            }
            //重置密码
           $password1 = input('post.password1');
           $password2 = input('post.password2');
           //判断密码是否一致
            if($password1 != $password2){
                return json_encode(array('data'=>"亲,两次密码必须一致哦!!!",'status'=>2));
            }
           $salt = $userinfo['tm_salt'];
           $password = md5(md5($password1).$salt);

           //判断密码修改是否成功
           $res = Db::connect(config('db_config2'))->name("users")->where("mobile_phone='$tel' and user_name='$username'")->setField('password',$password);
            //dump($res);
           if($res){
               return json_encode(array('data'=>"密码修改成功",'status'=>1));
           }else{
               return json_encode(array('data'=>"密码修改失败",'status'=>0));
           }
        }else{
            return $this->fetch();
        }

    }

    //退出登录
    public function loginout(){
        cookie::set('admin',null);
        //$this->success("您已退出登录");
        session::set('user', null);
        session::set('user_id', null);
        session::set('phone',null);
        session::set('type',0);
        return json(1);
        //$this->redirect('/index');
    }

    //短信验证码 OK
    public function test()
    {
        $code = rand(100000,999999);
        //session('code',$code);
        session::set('code',$code);
        //获取传输过来的手机号码
        //$tel = $_POST['tel'];
        $tel = input('post.tel');
        //dump($tel);die;
        session::set('boolen',$tel);
        //$tel=13886643564;
        include_once('../Api/top/TopClient.php');
        $c = new \TopClient;
        $c->appkey = '23662994';
        $c->secretKey = '12c4693b91926a394e8ca913e132be01';
        include_once('../Api/top/request/AlibabaAliqinFcSmsNumSendRequest.php');
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("我的茶馆");
        $req->setSmsParam("{\"code\":\"$code\",\"product\":\"测试\"}");
        $req->setRecNum("$tel");//电话号码
        $req->setSmsTemplateCode("SMS_62170183");
        $resp = $c->execute($req);
        if($resp->result->msg){
            //$this->ajaxReturn(array('s'=>1,'msg'=>'短信发送成功'));
            return json_encode(array('s'=>1,'msg'=>'短信发送成功'));
            //$this->success('短信发送成功');
        }else{
            //$this->ajaxReturn(array('s'=>0,'msg'=>'短信发送失败'));
            return json_encode(array('s'=>0,'msg'=>'短信发送失败'));
            //$this->success('短信发送失败');
        }
    }

    //判断用户名是否存在
    public function checkUser()
    {
        $username = input('post.username');
        //$username = 'jacky01';
        //$user_info = Db::table('tes_user')->where("username",$username)->find();
        $user_info = Db::connect(config('db_config2'))->name("users")->where("user_name",$username)->find();
        //dump($user_info);die;
        if(!empty($user_info)){
            return json_encode(array('data'=>"该用户已存在",'status'=>0));
        }
    }

    public function checkUser2()
    {
        $username = input('post.username');
        $user_info = Db::connect(config('db_config2'))->name("users")->where("user_name",$username)->find();
        if(empty($user_info)){
            return json_encode(array('data'=>"用户名不存在",'status'=>0));
        }
    }

    //判断手机号码是否存在
    public function checkTel()
    {
        $tel = input('post.rec_tel');
        //$tel = '13886643564';
        $res = Db::connect(config('db_config2'))->name("users")->where("mobile_phone",$tel)->find();
        //dump($res);
        if(empty($res)){
            return json_encode(array('data'=>"手机号不存在",'status'=>0));
        }else{
            return json_encode(array('data'=>"手机号已存在",'status'=>1));
        }
    }

    public function cmobile_phone()
    {
        $tel = input('post.tel');
        //$tel = '13886643564';
        $res = Db::connect(config('db_config2'))->name("users")->where("mobile_phone",$tel)->find();
        //dump($res);
        if(empty($res)){
            return json_encode(array('data'=>"手机号不存在",'status'=>0));
        }else{
            $user_id = $res['user_id'];
            $wait = Db::table('tea_user')->where("user_id",$user_id)->value('wait');
            if($wait == 0){
                return json_encode(array('status'=>2,'冻结'));
            }
            return json_encode(array('data'=>"手机号已存在",'status'=>1));
        }
    }

    //判断手机登录的短息验证码是否正确
    public function checkCode()
    {
        $getCode = input('post.code');
        $code = session('code');
        $tel = input('post.tel');
        //$res = Db::connect(config('db_config2'))->name("users")->where("mobile_phone",$tel)->find();
        if($getCode != $code){
            return json_encode(array('msg'=>"验证码错误",'status'=>0));
        }else{
            $data = Db::connect(config('db_config2'))->name("users")->where("mobile_phone",$tel)->find();

            $user_id = $data['user_id'];
            $wait = Db::table('tea_user')->where("user_id",$user_id)->value('wait');
            //dump($wait);
            if($wait == 0){
                return json_encode(array('status'=>2,'冻结'));
            }
            cookie::set('admin',$data);
            session::set('admin',$data);
            cookie::set('user',$data);
            session::set('user',$data);
            session::set('user_id',$data['user_id']);
            return json_encode(array('msg'=>"登录成功",'status'=>1));
        }
    }

    //判断推荐人的短息验证码是否正确
    public function check_recCode()
    {
        $getCode = input('post.code');
        $code = session('code');
        //$tel = input('post.tel');
        if($getCode != $code){
            return json_encode(array('msg'=>"验证码错误",'status'=>0));
        }else{
            /*$data = Db::connect(config('db_config2'))->name("users")->where("mobile_phone",$tel)->find();
            cookie::set('admin',$data);
            session::set('admin',$data);*/
            return json_encode(array('msg'=>"成功",'status'=>1));
        }
    }

    //理茶宝会员注册
    public function register()
    {
        if(request()->isAjax()){
            $username = input('post.username');
            $password1 = input('post.password1');
            $tel = input('post.tel');
            $re_username = input('post.re_username');
            $code_user = input('post.sendCode');
            $code = session('code');
            //判断短信验证码是否正确
            if($code_user != $code){
                return json_encode(array('msg'=>"验证码错误",'status'=>2));
            }
            //如果有推荐人
            if($re_username){
                //根据推荐人用户名查询其信息
                $re_info = Db::connect(config('db_config2'))->name("users")->where("user_name",$re_username)->find();

                if(!$re_info){
                    return json_encode(array('msg'=>"推荐人不存在",'status'=>0));   //推荐人不存在
                }else{
                    $user_id = $re_info['user_id'];
                    $data2 = Db::table('tea_user')->where("user_id",$user_id)->find();
                    if($data2['wait']==0){
                        return json_encode(array('status'=>5,'data'=>'被冻结'));
                    }else{
                        //推荐人是否购买产品
                        $info = Db::table('tea_user_recharge')->where('user_id='.$re_info['user_id'])->order('id desc')->limit(1)->find();
                        if(!$info){
                            return json_encode(array('status'=>4,'data'=>'未激活'));
                        }
                    }
                }
                //推荐人id
                $parent_id = $re_info['user_id'];
            }else{
                //没有推荐人
                $parent_id = 0;
            }
            $tm_salt = rand(0000,9999);
            $password = md5(md5($password1).$tm_salt);   //密码
            $data = array(
                'user_name' => $username,
                'password' => $password,
                'mobile_phone' => $tel,
                'parent_id' => $parent_id,
                'tm_salt' => $tm_salt,
                'reg_time' => time(),   //注册时间
            );
            $res = Db::connect(config('db_config2'))->name("users")->insert($data);
            if(!$res){
                return json_encode(array('msg'=>'内部维护中','status'=>3));
            }else{
                //同步理茶宝用户
                $user_info = Db::connect(config('db_config2'))->name("users")->where('user_name',$username)->find();
                $user_id = $user_info['user_id'];
                $data2 = array(
                    'user_id'=>$user_id,
                    'wait'=>1,
                );
                $res2 = Db::table('tea_user')->insert($data2);
                if($res2){
                    return json_encode(array('msg'=>"注册成功",'status'=>1));
                }else{
                    return json_encode(array('msg'=>"内部维护中",'status'=>3));
                }
            }
        }else{
            return $this->fetch();
        }
    }

    //用户密码  ok
    public function changepwd()
    {
        if(request()->isAjax()){
            $user_id = cookie('user')['user_id'];
            //判断用户当前密码是否正确
            $password1 = trim(input('post.password1'));
            $userinfo = Db::connect(config('db_config2'))->name("users")->where("user_id=$user_id")->find();
            $password = $userinfo['password'];
            $password1 = md5(md5($password1).$userinfo['tm_salt']);
            if($password1 != $password){
                return json(array('status'=>0,'data'=>"当前密码不正确"));
            }

            $password2 = trim(input('post.password2'));
            $password3 = trim(input('post.password3'));
            if($password2 != $password3){
                return json(array('status'=>2,'data'=>"密码不一致"));
            }
            $password = md5(md5($password2).$userinfo['tm_salt']);
            $res = Db::connect(config('db_config2'))->name("users")->where("user_id=$user_id")->setField('password',$password);
            if($res){
                return json(array('status'=>1,'data'=>"密码修改成功"));
            }else{
                return json(array('status'=>3,'data'=>"系统维护中"));
            }
        }else{
//            $user_id = cookie('user')['user_id'];
//            dump($user_id);
            return $this->fetch();
        }
    }

    //理茶宝会员登录
    public function login()
    {
        if(request()->isAjax()){
            //获取用户输入的用户名 密码
            $username = trim(input('post.username'));
            $password = trim(input('post.password'));

            //判断用户名是否存在
            $userobj = new Huiyuan();
            //print_r($userobj);
            //通过访问淘米平台数据库查看用户名是否存在
            $user = Db::connect(config('db_config2'))->name("users")->where('user_name',$username)->find();

            if(!$user){
                return json_encode(array("data"=>"用户不存在",'status'=>2));
            }else{
                //是否冻结
                $user_id= $user['user_id'];
                $usernifo = Db::table('tea_user')->where('user_id',$user_id)->find();
                if($usernifo){
                    if($usernifo['wait'] == 0){
                        return json_encode(array("data"=>"该账户被冻结",'status'=>3));
                    }
                }else{
                    //将这个会员插入到数据库里面去
                    Db::table('tea_user')->insert(['user_id'=>$user_id,'is_ceo'=>0]);
                }
                //用户名存在  判断密码是否正确
                $password = md5(md5($password).$user['tm_salt']);
                $res = $userobj->pwd_exit($username,$password);
                if($res){
                    return json_encode(array("data"=>"登陆成功",'status'=>1));
                }else{
                    return json_encode(array("data"=>"密码错误",'status'=>0));
                }
            }
        }else{
            return $this->fetch();
        }
    }

    //个人信息显示
    public function personinfo()
    {
        $user_id = cookie('user')['user_id'];
        $user = Db::connect(config('db_config2'))->name("users")->where("user_id=$user_id")->field('nick_name,mobile_phone,reg_time')->find();
        $user2 = Db::connect(config('db_config2'))->name("users_real")->where("user_id=$user_id")->field('real_name,self_num,bank_name')->find();
        $rec_addtime = Db::table('tea_user_recharge')->where("user_id",$user_id)->value('rec_addtime');
        $user['real_name'] = $user2['real_name'];
        $user['self_num'] = $user2['self_num'];
        $user['bank_name'] = $user2['bank_name'];
        $user['rec_addtime'] = $rec_addtime;
        dump($user);
        $this->assign('data',$user);
        return $this->fetch();
    }

    //推荐人验证
    public function pTelVer()
    {
        $p_user = trim(input('post.p_user'));
        if ($p_user) {
            $data = Db::connect(config('db_config2'))->name("users")->where("user_name='$p_user'")->find();
            if (!$data) {
                return json(array('status'=>0,'data'=>'不存在'));
            } else {
                $user_id = $data['user_id'];
                $data2 = Db::table('tea_user')->where("user_id",$user_id)->find();
                if($data2['wait']==0){
                    return json(array('status'=>2,'data'=>'被冻结'));
                }else{
                    //推荐人是否购买产品
                    $info = Db::table('tea_user_recharge')->where('pay_status = 1 and user_id='.$data['user_id'])->order('id desc')->limit(1)->find();
                    if(!$info){
                        return json(array('status'=>3,'data'=>'未激活'));
                    }else{
                        return json(array('status'=>1,'data'=>'ok'));
                    }
                }
            }
        }
    }

    //个人中心首页
    public function myinfo()
    {
        $this->checkLogin();
        $user_id = session('user_id');
        //dump($user_id);
        $user_name = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->value('user_name');
        $user_rank = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->value('user_rank');
        $userinfo = Db::table('tea_user')->where("user_id",$user_id)->find();
        $userinfo['user_name'] = $user_name;
        $userinfo['user_rank'] = $user_rank;
        $integral = Db::table('tea_user')->alias('a')
            ->join('tea_integral b','a.user_id=b.user_id')
            ->join('tea_integral_log c','a.user_id=c.user_id')
            ->field('b.surplus_inte,c.surplus_inte as sfjf')
            ->where("a.user_id=$user_id")->find();
        $userinfo['surplus_inte'] = $integral['surplus_inte'];
        $userinfo['sfjf'] = $integral['sfjf'];
        //dump($userinfo);die;
//        $integral = Db::table('tea_user')->alias('a')
//            ->join('tea_integral b','a.id=b.user_id')
//            ->field('a.username,a.rank_id,b.surplus_inte,b.tea_inte,b.tea_ponit_inte,c.surplus_inte as shouyi')
//            ->join("tea_integral_log c","a.id=c.user_id")
//            ->where("a.id=$user_id")
//            ->find();
        //dump($userinfo);
        $this->assign('data',$userinfo);
        return $this->fetch();
    }

    //完善个人信息
    public function perfectinfo()
    {
        if(request()->isAjax()){
            $user_id = cookie('user')['user_id'];
            //$parent_id = trim(input('post.parent_id'));  //推荐人id
            $real_name = trim(input('post.real_name'));   //真实姓名

            //支付密码
            $pay_pwd = trim(input('post.pay_pwd1'));

            $idcard = trim(input('post.idcard'));   //身份证
            $bank = trim(input('post.bank'));    //银行卡号
            $bank_name = trim(input('post.bank_name'));   //开户行

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
            //return json(array('data'=>$p_info));
            $user_info = Db::connect(config('db_config2'))->name("users")->where("user_id=$user_id")->find();
            $tel = $user_info['mobile_phone'];
            $tm_salt = $user_info['tm_salt'];
            $pay_pwd = md5(md5($pay_pwd).$tm_salt);   //支付密码
            $user_address = input('post.user_address');
            $arr = explode('-',$user_address);
            $province = $arr[0];   //省
            $city = $arr[1];    //市
            $area = $arr[2];      //县 区
            $district = trim(input('post.district'));   //详细地址
            $res1 = Db::connect(config('db_config2'))->name("users_real")
                ->insert([
                    'user_id'=>$user_id,
                    'real_name' => $real_name,
                    'self_num' => $idcard,
                    'bank_name'=>$bank_name,
                    'bank_card'=>$bank,
                ]);
            if($parent_id){
                $res2 = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->setField('parent_id',$parent_id);
            }

//            $res3 =  Db::connect(config('db_config2'))->name("users_bank")->where("user_id",$user_id)->insert([
//                'bank_name'=>$bank_name,
//                'bank_card'=>$bank,
//            ]);

//            $res1 = Db::connect(config('db_config2'))->name("users")->where("id=$user_id")
//                ->insert([
//                    'real_name' => $real_name,
//                    'idcard'=>$idcard,
//                    'bank'=>$bank,
//                    'bank_name'=>$bank_name,
//                    'parent_id'=>$parent_id]);

            $address = array(
                'user_id'=>$user_id,
                'province'=>$province,
                'city'=>$city,
                'area'=>$area,
                'district'=>$district,
                'rec_name'=>$real_name,
                'is_use'=>1,
                'tel'=>$tel,
            );

            $add_ress = array(
                'user_id'=>$user_id,
                'country'=>1,
                'province'=>$province,
                'city'=>$city,
                'district'=>$area,
                'address'=>$district,
            );

            $res3 = Db::table('tea_user')->where('user_id',$user_id)->setField('pay_pwd',$pay_pwd);
            $res4 = Db::table('tea_user_address')->insert($address);
            $res5 = Db::connect(config('db_config2'))->name("user_address")->insert($add_ress);
            if($res1 && $res4 && $res5){
                return json(array('status'=>1));
            }else{
                return json(array('status'=>0));
            }
        }else{
            $user_id = cookie('user')['user_id'];
            $parent_id = Db::connect(config('db_config2'))->name("users")->where("user_id",$user_id)->value('parent_id');
            if($parent_id == 0){
                $p_id = 0;
            }else{
                $p_id = $parent_id;
            }
            $this->assign('p_id',$p_id);
            return $this->fetch();
        }
    }

    //帮人注册
    public function otherreg()
    {
        if (request()->isAjax()) {
            $user_id = cookie('user')['user_id'];
            $username = trim(input('post.username'));
            $password = trim(input('post.password1'));
            $tel = trim(input('post.tel'));
            $pay_pwd = trim(input('post.pay_pwd1'));
            $msginfo = trim(input('post.sendCode'));
            $code = session('code');
            //判断验证码是否正确
            if ($msginfo != $code) {
                return json(array('status' => 0, 'msg' => "验证码错误"));
            }
            session::set('msg',null);
            $salt = rand(1000, 9999);
            $password = md5(md5($password) . $salt);
            $pay_pwd = md5(md5($pay_pwd) . $salt);
            $data = array(
                'user_name' => $username,
                'password' => $password,
                'mobile_phone' => $tel,
                'parent_id' => $user_id,
                'tm_salt' => $salt,
                'reg_time' => time(),
            );
            //$info = D('user')->add($data);
            $info = Db::connect(config('db_config2'))->name("users")->insert($data);
            if (!$info) {
                return json(array('status' => 2, 'msg' => "注册失败"));
            } else {
                $time = time();
                //将用户同步到lcb
                //$gc_user = M('user')->db(1,'DBConf1')->table('guocha_user')->where("username = '$username'")->find();
                $userid = Db::connect(config('db_config2'))->name("users")->where("user_name",$username)->value('user_id');
                $data2 = array(
                    'user_id'=>$userid,
                    'pay_pwd'=>$pay_pwd,
                    'wait'=>0,
                );
                $lcb_user = Db::table('tea_user')->insert($data2);

                if(!$lcb_user){
                    return json(array('status' => 2, 'msg' => "注册失败"));
                }
                return json(array('status' => 1, 'msg' => "ok"));
            }
        } else {

            return $this->fetch();
        }
    }




    //代买的用户信息判断
    public function OtherToActive(){
        $info = cookie('user');
        $user_info  = Db::table('tea_user_recharge')->where('pay_status = 1 and user_id='.$info['user_id'])->order('id desc')->limit(1)->find();
        if(!$user_info){
            return json(array('status' => 2, 'msg' =>"尚未购买产品" ));
        }
        $user_id = I('post.user_id');
        $user = D('User')->where('id='.$user_id)->find();
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

    //填写代买人信息
    public function daimai_custom(){
        if(request()->isAjax()){
            //个人信息
            $other_id = I('post.other_id');
            //通过id获取信息
            $user = D('User')->where('id='.$other_id)->find();
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
            $info = D('User')->where('id='.$user['id'])->save($c);
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

    //判断会员是否进行过实名认证
    public function is_real()
    {
        $user_id = $user_id = cookie('user')['user_id'];
        $is_real = Db::connect(config('db_config2'))->name('users_real')->where("user_id",$user_id)->find();
        if($is_real){
            return (1);
        }else{
            return (0);
        }
    }

    //判断用户是否购买权限
    public function allowBuy(){
        if(request()->isAjax()){
            $user_id = cookie('user')['user_id'];
            //首先判断用户是否有过购买记录，然后判断是否允许购买以及再次购买
            $res = Db::table('tea_user_recharge')
                ->where([
                    'is_return' =>1,
                    'user_id'   =>$user_id
                ])
                ->find();
            if($res){
                //如果存在就是再次购买
                return json(array('status'=>1,'data'=>'可以购买'));
            }else{
                return json(array('status'=>0,'data'=>'无权限'));
            }
        }
    }



    //重构我的推荐人
    public function recommender(){
        if(!request()->isAjax()){
            $user_id = session('user_id');

        }else{

        }
    }
}