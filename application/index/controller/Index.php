<?php
namespace app\index\controller;
use think\cache\driver\Redis;
use think\Controller;
use think\Request;
use think\Db;
class Index extends Controller
{
    public function index()
    {

        if (!Request::instance()->isMobile()){
            //$this->redirect('newapp/user/index');
            return $this->fetch();
        }else{
            $this->redirect('index/mobiles');
        }
    }

    public function errors(){
       return $this->fetch();
    }
//
    public function mobiles(){
        return $this->fetch();
    }
    public function test(){
        $redis = new Redis();
       $data['user']=Db::table('tea_user')->select();
       $data['log']=Db::table('tea_integral_log')->select();
        $redis->set('user',$data);
        $rs=$redis->get('user');

        dump($rs);

    }
    public function cc(){
        $data=Db::table('tea_alpay_config')->find();
        dump($data);

    }
    public function aa($url="https://qr.alipay.com/bax01737ezp633eekqot008f",$level=6,$size=60){
            Vendor('phpqrcode.phpqrcode');
            $errorCorrectionLevel =intval($level) ;//容错级别
            $matrixPointSize = intval($size);//生成图片大小
            //生成二维码图片
            $object = new \QRcode();
            $data= $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
            dump($data);

    }
    public function bb(){
//        Db::table('tea_integral')->where('id',221)->setInc('tea_ponit_inte',2000);
//        Db::table('tea_integral')->where('id',221)->setInc('back_inte',2000);
//        Db::table('tea_integral')->where('id',221)->setDec('surplus_inte',2000);
//        $inte_data=array(
//            'last_time'=>time(),
//            'year'=>date("Y"),
//            'month'=>date("m"),
//            'day'=>date("d")
//        );
//        Db::table('tea_integral')->where('id',221)->update($inte_data);
        //添加
        $ceo_data=array(
            'integral_id'=>intval(222),
            'thistime'=>date('Y-m-d'),
            'next_time'=>date('Y-m-d',strtotime('+1 month')),
            'back_inte'=>2000,
            'tims'=>1+1,

        );
        Db::table("tea_ceo_integral_log")->insert($ceo_data);
    }

    //股东每月定时定时返还积分
    public function timeGiveInte(){
        $time = date('Y-m-d');
        //查出所有的股东积分表   连表查
        //查出所有股东积分返还表    查出积分表内的剩余积分
        $subQuery=Db::query('SELECT b.*,max(b.int_id) as ids FROM tea_ceo_integral_log as b WHERE b.integral_id > 0 GROUP BY b.integral_id DESC ');

        foreach ($subQuery as $k=>$v){
            $out=Db::table('tea_ceo_integral_log')
                ->where('int_id',$v['ids'])
                ->find();
            if(intval($out['tims'])+1 <=30)
            $integral_log[$k]=Db::table("tea_ceo_integral_log")
                ->alias('a')
                ->where('a.tims','<=',30)
                ->where('int_id','in',$v['ids'])
                ->field('a.*,b.user_id,b.id')
                ->join('tea_integral b','a.integral_id=b.id')
                ->find();
        }
        if(empty($integral_log)){
            return 1;
        }
        foreach ($integral_log as $k=>$v ){
           if(intval($v['tims'])+1 <=30)
            // 如果最后释放时间戳小于当前日前时间戳
            if (strtotime($v['next_time']) >= strtotime($time)){
                //修改用户表积分
                $users = Db::table('tea_user')->where('user_id',intval($v['user_id']))->find();
                if($users){
                    Db::table('tea_user')->where('user_id',intval($v['user_id']))->setInc('tea_ponit_inte',2000);
                }
                //形成记录
                $tea_ponit_inte ="+".'2000';
                $surplus_inte_ch = "-".'2000';
                $log_out_trade_no=date('Y').date('m').date('d').uniqid().'168';
                $introduce = "股东每月返还";
                $this->MakeLog($v['user_id'],0,$surplus_inte_ch,0,$tea_ponit_inte,0,$introduce,

                    4,0,0,1,0,0,0,0,0,

                    0,0,0,0,0,1,0,0,$log_out_trade_no);

                //更新积分表
                Db::table('tea_integral')->where('id',intval($v['id']))->setInc('tea_ponit_inte',2000);
                Db::table('tea_integral')->where('id',intval($v['id']))->setInc('back_inte',2000);
                Db::table('tea_integral')->where('id',intval($v['id']))->setDec('surplus_inte',2000);
                $inte_data=array(
                    'last_time'=>time(),
                    'year'=>date("Y"),
                    'month'=>date("m"),
                    'day'=>date("d")
                );
                Db::table('tea_integral')->where('id',intval($v['id']))->update($inte_data);
                //添加
                $ceo_data=array(
                    'integral_id'=>intval($v['integral_id']),
                    'thistime'=>date('Y-m-d'),
                    'next_time'=>date('Y-m-d',strtotime('+1 month')),
                    'back_inte'=>2000,
                    'tims'=>intval($v['tims'])+1,

                );
                Db::table("tea_ceo_integral_log")->insert($ceo_data);

            }
        }
    }
    //积分日志记录

    private function MakeLog($user_id,$user_lev,$surplus_inte,$tea_inte,$tea_ponit_inte,$reg_inte,$introduce,$menu

        ,$sum_inte,$have_inte,$use_type,$recom,$recom_one,$recom_two,$grade,$grade_one,$grade_two

        ,$recharge_money,$shopping,$exchange,$online,$fix,$other_id,$other_lev,$log_out_trade_no){

        //dump($recom_one);die;

        $time = time();

        //用户·id

        $log['user_id'] = $user_id;

        //自己等级(推荐时使用)

        $log['user_lev'] = $user_lev;

        //释放额度

        $log['surplus_inte'] = $surplus_inte;

        //茶券

        $log['tea_inte'] = $tea_inte;

        //茶点

        $log['tea_ponit_inte'] = $tea_ponit_inte;

        //茶籽

        $log['reg_inte'] = $reg_inte;

        //记录说明

        $log['introduce'] = $introduce;

        //积分记录分类

        $log['menu'] = $menu;

        //返还总积分

        $log['sum_inte'] = $sum_inte;

        //剩余积分

        $log['have_inte'] = $have_inte;

        //积分类型

        $log['use_type'] = $use_type;

        //推荐

        $log['recom'] = $recom;

        //一级推荐

        $log['recom_one'] = $recom_one;

        //二级推荐

        $log['recom_two'] = $recom_two;

        //绩效

        $log['grade'] = $grade;

        //一级绩效

        $log['grade_one'] = $grade_one;

        //二级绩效

        $log['grade_two'] = $grade_two;

        //充值金额

        $log['recharg_money'] = $recharge_money;

        //购物

        $log['shopping'] = $shopping;

        //兑换

        $log['exchange'] = $exchange;

        //线下

        $log['online'] = $online;

        //固定

        $log['fix'] = $fix;

        //来源id

        $log['other_id'] = $other_id;

        //用户级别

        $log['other_lev'] = $other_lev;

        //记录产生时间

        $log['addtime'] = $time;

        //记录产生时间

        $log['year'] = date("Y");

        //记录产生时间

        $log['month'] = date("m");

        //记录产生时间

        $log['day'] = date("d");

        $log['log_out_trade_no'] = $log_out_trade_no;

        //入库

        //M('IntegralLog')->add($log);

        Db::table("tea_integral_log")->insert($log);

    }


    public function phpExcelList($field, $list, $title='文件'){
        vendor('PHPExcel.PHPExcel');
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel); //设置保存版本格式
        foreach ($list as $key => $value) {
            foreach ($field as $k => $v) {
                if ($key == 0) {
                    $objPHPExcel->getActiveSheet()->setCellValue($k . '1', $v[1]);
                }
                $i = $key + 2; //表格是从2开始的
                $objPHPExcel->getActiveSheet()->setCellValue($k . $i, $value[$v[0]]);
            }

        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename='.$title.'.xls');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }

    public function aaaa() {
       $data=Db::table('tea_integral_log')->select();

        $field = array(
            'A' => array('id', 'ID'),
            'B' => array('user_id', '用户ID'),
            'C' => array('user_lev', '用户等级'),
            'D' => array('surplus_inte', '释放积分'),
            'E' => array('tea_inte', '茶券'),
            'F' => array('tea_ponit_inte', '茶点'),
            'G' => array('introduce', '说明'),
            'H' => array('log_out_trade_no', '订单号'),
            'I' => array('year', '年'),
            'J' => array('month', '月'),
            'K' => array('day', '日')
        );
        $this->phpExcelList($field, $data, '充值列表_' . date('Y-m-d'));
    }
    public function bbbb() {
       $data=Db::table('tea_recharge_cart')->select();

        $field = array(
            'A' => array('recharge_money', '充值金额'),
            'B' => array('user_id', '用户ID'),
            'C' => array('id', '订单编号'),
            'D' => array('is_againbuy', '购买OR升级(0:购买,1:升级)'),
            'E' => array('pay_status', '是否支付(0:未支付,1:已支付)'),
            'F' => array('is_active', '是否激活(0:未激活,1:已激活)'),
            'G' => array('again_money', '支付时选择的钱包金额'),
            'H' => array('trade_beizhu', '交易备注'),
            'I' => array('order_addtime', '购买日期'),
            'J' => array('is_ceo', '会员类型(1:股东,0:理茶宝)'),
            'K' => array('trade_no', '交易号')
        );
        $this->phpExcelList($field, $data, '购买记录_' . date('Y-m-d'));
    }
    
    public function ccc(){
        $obj = new \app\tmvip\controller\Order();
        $list = $obj->ccc();
        return $this->fetch('ccc',['data'=>$list]);
    }




}
