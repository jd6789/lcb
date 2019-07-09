<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/6/2
 * Time: 10:25
 */
namespace  app\tmvip\controller;
use think\Db;
use think\Request;
class Order extends Common{
    public function index(){
        $menu = $this->user['menus'];
        //获取当前url的权限id
        $request = Request::instance();
        $url_id = $this->url_id($request->module(),$request->controller(),$request->action());
        $id = intval($url_id['id']);
        return view("index",['menu'=>$menu,'url'=>$id]);
    }
    //显示所有的商品
    public function order_new(){
        $username=input('post.username');
        if (!$username) {
            $username = "";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        $where = " o.order_id > 0  ";
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(empty($user_ids)){
                $where .= " AND o.user_id = ''";
            }else{
                if(strpos($user_ids,',')===false){
                    $where .= " AND o.user_id = $user_ids ";
                }else{
                    $where .= " AND o.user_id = $user_ids ";
                }
            }
        }
        $data=Db::table('tea_order')->alias('o')
            ->where($where)
            ->join('tea_order_cart c','c.order_id=o.order_id')
            ->order("order_addtime desc,c.is_third desc")
            ->paginate('14');
        foreach($data->items() as $k=> $v){
            $user_id = intval($v['user_id']);
            $user = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
            $list[$user_id]['username'] = $user['user_name'];
            $list[$user_id]['tel'] = $user['mobile_phone'];
            //获取银行卡信息
            $real_info = Db::connect(config('db_config2'))->name("users_real")->where('user_id',$user_id)->find();
            $list[$user_id]['real_name'] = $real_info['real_name'];
            $list[$user_id]['bank_name'] = $real_info['bank_name'];
            $list[$user_id]['bank_card'] = $real_info['bank_card'];
        }
        $list['info'] = $data;
        return view("order",['data'=>$list]);
    }

    //
    public function order(){

        $username=input('post.username');
        if (!$username) {
            $username = "";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        $where = " o.order_id > 0  ";
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(empty($user_ids)){
                $where .= " AND o.user_id = ''";
            }else{
                if(strpos($user_ids,',')===false){
                    $where .= " AND o.user_id = $user_ids ";
                }else{
                    $where .= " AND o.user_id = $user_ids ";
                }
            }
        }
        $data=Db::table('tea_order')->alias('o')
            ->where($where)
            ->order('order_addtime desc')
            ->paginate('14');
        foreach($data->items() as $k=> $v){
            $order_id = intval($v['order_id']);
            $user_id = intval($v['user_id']);
            $user = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
            $list[$order_id]['username'] = $user['user_name'];
            $list[$order_id]['tel'] = $user['mobile_phone'];
            //获取银行卡信息
            $real_info = Db::connect(config('db_config2'))->name("users_real")->where('user_id',$user_id)->find();
            $list[$order_id]['real_name'] = $real_info['real_name'];
            $list[$order_id]['bank_name'] = $real_info['bank_name'];
            $list[$order_id]['bank_card'] = $real_info['bank_card'];

            //获取具体商品

            $list[$order_id]['goods'] = Db::table('tea_order_cart')->where('order_id',$order_id)->order('is_third desc,over_status')->select();
        }
        $list['info'] = $data;
       //return $list;
        return view("order",['data'=>$list]);
    }

    public function ccc(){
        $username=input('post.username');
        if (!$username) {
            $username = "";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        $where = " o.order_id > 0  ";
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(empty($user_ids)){
                $where .= " AND o.user_id = ''";
            }else{
                if(strpos($user_ids,',')===false){
                    $where .= " AND o.user_id = $user_ids ";
                }else{
                    $where .= " AND o.user_id = $user_ids ";
                }
            }

        }
        $data=Db::table('tea_order')->alias('o')
            ->where($where)
            ->order('order_addtime desc')
            ->paginate('14');

        foreach($data->items() as $k=> $v){
            $order_id = intval($v['order_id']);
            $user_id = intval($v['user_id']);
            $user = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
            $list[$order_id]['username'] = $user['user_name'];
            $list[$order_id]['tel'] = $user['mobile_phone'];
            //获取银行卡信息
            $real_info = Db::connect(config('db_config2'))->name("users_real")->where('user_id',$user_id)->find();
            $list[$order_id]['real_name'] = $real_info['real_name'];
            $list[$order_id]['bank_name'] = $real_info['bank_name'];
            $list[$order_id]['bank_card'] = $real_info['bank_card'];

            //获取具体商品
            $order_id = intval($v['order_id']);
            $list[$order_id]['goods'] = Db::table('tea_order_cart')->where('order_id',$order_id)->select();
        }
        $list['info'] = $data;
        return $list;
    }

    
    //完成统计
    public function overorder(){
        $cart_id=intval(input('post.id'));
        $res=Db::table('tea_order_cart')->where('order_id',$cart_id)->setField('over_status',1);
        if($res){
            return 1;
        }else{
            return 0;
        }
    }
    //获取资源集的id
    private function getId($info){
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
    //显示提现的所有页面
    public function postal(){
        //通过用户名查找提现记录
        $username=input('post.username');
        if (!$username) {
            $username = "";
        }
        $api_obj = new Api();
        $user_info = $api_obj->getUserInfo("",$username,'');
        //获取用户信息
        $where = " id > 0  ";
        if($username ){
            $user_ids = $this->getId($user_info['info']['list']);
            if(empty($user_ids)){
                $where .= " AND user_id = ''";
            }else{
                if(strpos($user_ids,',')===false){
                    $where .= " AND user_id = $user_ids ";
                }else{
                    $where .= " AND user_id = $user_ids ";
                }
            }
        }
        $data=Db::table('tea_postal')
            ->where($where)
            ->order('id desc')
            ->paginate('14');
        foreach($data->items() as $k=> $v){
            $user_id                        = intval($v['user_id']);
            $user                           = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
            $user_real                      = Db::connect(config('db_config2'))->name("users_real")->field('real_name,bank_card,bank_name')->where('user_id',$user_id)->find();
            $list[$user_id]['username']     = $user['user_name'];
            $list[$user_id]['real_name']    = $user_real['real_name'];
            $list[$user_id]['card']         = $user_real['bank_card'];
            $list[$user_id]['bank_name']    = $user_real['bank_name'];
        }
        $list['info'] = $data;
        return view("postal",['data'=>$list]);
    }
    //驳回提现
    public function rejected(){
        $id=intval(input('post.id'));
        $money=Db::name('postal')->where('id',$id)->field('user_id,money_num')->find();
        // 启动事务
        Db::startTrans();
        try{
            Db::name('postal')->where('id',$id)->setField('status',2);
            Db::name('user')->where('user_id',intval($money['user_id']))->setInc('tea_inte',floatval($money['money_num']));
            //提交事务
            Db::commit();
            return 1;
        }catch(\Exception $e){
            // 回滚事务
            Db::rollback();
            return 0;
        }
    }
    //完成提现
    public function over(){
        if(request()->isAjax()){
            $id=intval(input('post.id'));
            $postal_data= Db::name('postal')->where('id',$id)->find();
            // 启动事务
            Db::startTrans();
            try{
                Db::name('postal')->where('id',$id)->setField('status',1);
                $log=[
                    'user_id'=>  $postal_data['user_id'],
                    'tea_inte'=>  '-'.$postal_data['money_num'],
                    'trade_no'=>  $postal_data['trade_no'],
                    'surplus_inte'=>   '-'.$postal_data['money_num'],
                    'tea_ponit_inte'=> '+0',
                    'introduce'=> '茶券提现'.$postal_data['money_num'],
                    'use_type'=> 2,
                    'wallet'=> 3,
                    'addtime' => time(),
                    'year' => date("Y"),
                    'month' => date("m"),
                    'day' => date("d"),
                    'postal' => 1,
                    'log_out_trade_no' => date('Y').date('m').date('d').uniqid().'168',
                ];
                Db::name('integral_log')->insert($log);
                //提交事务
                Db::commit();
                return 1;
            }catch(\Exception $e){
                // 回滚事务
                Db::rollback();
                return 0;
            }
        }
    }

    //导出excel
    public function postal_excel(){
        //提现中的记录
        $where = " status = 0  ";
        $data=Db::table('tea_postal')
            ->where($where)
            ->order('id desc')->select();
        foreach($data as $k=> $v){
            $user_id                        = intval($v['user_id']);
            $user                           = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
            $user_real                      = Db::connect(config('db_config2'))->name("users_real")->field('real_name,bank_card,bank_name')->where('user_id',$user_id)->find();
            $list[$k]['user_id'] = $v['user_id'];
            $list[$k]['username']     = $user['user_name'];
            $list[$k]['real_name']    = $user_real['real_name'];
            $list[$k]['card']         = $user_real['bank_card'];
            $list[$k]['bank_name']    = $user_real['bank_name'];
            $list[$k]['money_num'] = $v['money_num'];
            $list[$k]['create_time'] = $v['create_time'];
            $list[$k]['status'] = '申请中';
        }
        $filename = '提现申请'.date('Ymd');
        $header = array('会员编号','会员名称','真实姓名','银行卡号','开户行','提现金额','申请时间','状态');
        //$index = array('user_id','username','real_name','card','bank_name','money_num','create_time','status');
        //会员记录
        //$this->createtable($data,$filename,$header,$index);
        $this->createtable($list, $header, $filename);


    }

//生成excel
    public function createtable($data = array(), $title = array(), $filename = 'report')
    {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        // header("Content-Type: text/html;charset=GB2312");
        //导出xls 开始
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv("UTF-8", "GB2312", $v);
            }
            $title = implode("\t", $title);
            echo "$title\n";
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $temp = '="' . $cv . '"';
                    $data[$key][$ck] = iconv("UTF-8", "GB2312", $temp);
                }
                $data[$key] = implode("\t", $data[$key]);
            }
            echo implode("\n", $data);
        }
    }

    public function order_excel(){
        //获取用户信息
        $where = " o.order_id > 0 and pay_way = 2 ";
        $data=Db::table('tea_order')->alias('o')->where($where)->order('order_addtime desc')->select();
        foreach($data as $k=> $v){
            $order_id = intval($v['order_id']);
            $user_id = intval($v['user_id']);
            $user = Db::connect(config('db_config2'))->name("users")->where('user_id',$user_id)->find();
            $data[$k]['username'] = $user['user_name'];
            $data[$k]['tel'] = $user['mobile_phone'];
            //获取银行卡信息
            $real_info = Db::connect(config('db_config2'))->name("users_real")->where('user_id',$user_id)->find();
            $data[$k]['real_name'] = $real_info['real_name'];
            $data[$k]['bank_name'] = $real_info['bank_name'];
            $data[$k]['bank_card'] = $real_info['bank_card'];
            //获取具体商品
            $data[$k]['goods'] = Db::table('tea_order_cart')->where('order_id',$order_id)->order('is_third desc,over_status')->select();
        }
        $filename = '寄售管理'.date('Ymd');
        $header = array('订单编号','用户名','姓名','开户行','银行卡号','商品名称','商品金额','商品数量','商品总价','支付方式','状态','时间');
        //dump($data);
        $this->exportOrderExcel($filename, $header, $data);
    }

    function exportOrderExcel($title, $cellName, $data) {
        $htmlinfo = "<table  border='1' border-collapse='collapse' cellspacing=0 cellpadding=0>";
        $htmlinfo .= "<tr>";
        foreach($cellName as $v){
            $htmlinfo .= "<td>".$v."</td>";
        }
        $htmlinfo .= "</tr>";
        foreach ($data as $k => $v) {
            //dump($v);die;
            $htmlinfo .="<tr>";
            $htmlinfo .="<td>".$v['order_id']."</td>";
            $htmlinfo .=" <td>".$v['username']."</td>";
            $htmlinfo .=" <td>".$v['real_name']."</td>";
            $htmlinfo .=" <td>".$v['bank_name']."</td>";
            $htmlinfo .="<td style='vnd.ms-excel.numberformat:@'>".$v['bank_card']."</td>";
            $htmlinfo .="<td style='position: relative;' colspan='5'>";
            $htmlinfo .="<table style='border: none'>";
            foreach ($v['goods'] as $k1 => $v1) {
                $htmlinfo .= "<tr style='border:none;'>";
                $htmlinfo .= "<td style='border:none;border-left: 0.1px solid #000;border-bottom: 0.1px solid #000;border-top:none;'>" . $v1['goods_name'] . "</td>";
                $htmlinfo .= "<td style='border:none;border-left: 0.1px solid #000;border-bottom: 0.1px solid #000;border-top:none;'>" . $v1['goods_price']. "</td>";
                $htmlinfo .= "<td style='border:none;border-left: 0.1px solid #000;border-bottom: 0.1px solid #000;border-top:none;'>" . $v1['good_number'] . "</td>";
                $htmlinfo .= "<td style='border:none;border-left: 0.1px solid #000;border-bottom: 0.1px solid #000;border-top:none;'>" . $v1['goods_price']*$v1['good_number']."</td>";
                $htmlinfo .= "<td style='border:none;border-left: 0.1px solid #000;border-bottom: 0.1px solid #000;border-top:none;'>茶券</td>";

                $htmlinfo .= "</tr>";
            }
            $htmlinfo .= "</table>";
            if($v['goods'][0]['is_third']==1){
                $v['goods'][0]['is_third'] = '已寄售';
            }else{
                $v['goods'][0]['is_third'] = '未寄售';
            }
            $htmlinfo .= "<td style='border:none;border-left: 0.1px solid #000;border-bottom: 0.1px solid #000;border-top:none;'>" . $v['goods'][0]['is_third'] . "</td>";
            $htmlinfo .= "<td style='border:none;border-left: 0.1px solid #000;border-bottom: 0.1px solid #000;border-top:none;'>" . $v['goods'][0]['times'] . "</td>";
            $htmlinfo .= "</tr>";
        }
        $htmlinfo .= "</table>";
        $path = $_SERVER['DOCUMENT_ROOT'] . "/cc";
        $fp = @fopen($path . ".html", "wb");
        @fwrite($fp, $htmlinfo);
        unset($htmlinfo);
        @fclose($fp);
        rename($path . ".html", $path . ".xls");

        $file = fopen($path . ".xls", "r");
        //dump($file);die;
        header("Content-Type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($path . ".xls"));
        header("Content-Disposition: attachment; filename=" . $title . ".xls");
        echo fread($file, filesize($path . ".xls"));
        fclose($file);
        @unlink($path . ".xls");
        exit;
    }
}