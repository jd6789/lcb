<?php
namespace app\api\controller;
use think\cache\driver\Redis;
use think\Controller;
use think\Db;
class Index extends Controller
{

    public function aa()
    {
        $query = Db::query('select * FROM tea_integral b join (select * from tea_ceo_integral_log a join (select max(int_id) as ids from tea_ceo_integral_log GROUP BY integral_id) as t on t.ids = a.int_id) as c ON b.id=c.integral_id');
    }

    public function index()
    {
//        $log=[
//            'user_id'=>  922,
//            'tea_inte'=>  '-4666',
//            'trade_no'=>  '201806205b2a3604abab4168',
//            'surplus_inte'=>  '-4666',
//            'tea_ponit_inte'=> '+0',
//            'introduce'=> '茶券提现4666',
//            'use_type'=> 2,
//            'wallet'=> 3,
//            'addtime' => strtotime('2018-07-09 10:12:20'),
//            'year' => '2018',
//            'month' => '07',
//            'day' => '09',
//            'postal' => 1,
//            'log_out_trade_no' => date('Y').date('m').date('d').uniqid().'168',
//        ];
//        Db::name('integral_log')->insert($log);exit('ok');
//        Db::name('user')->where('user_id',1071)->setDec('tea_ponit_inte',5868);
//        $logData=array(
//            'user_id'=>1071,
//            'introduce'=>'账户转移清结',
//            'surplus_inte'=>'-'.'90898',
//            'tea_ponit_inte'=>'-'.'5868',
//            'tea_inte'=>'-'.'85030',
//            'use_type'=>2,
//            'shopping'    =>0,
//            'online'    =>1,
//            'addtime'   =>time(),
//            'year'=>date('Y'),
//            'month'=>date('m'),
//            'day'=>date('d'),
//            'log_out_trade_no'=>date('Y').date('m').date('d').uniqid().'168'
//        );
//        Db::table('tea_integral_log')->insert($logData);exit('a');
//        $data=Db::name('integral')->field('last_time,id')->select();
//        foreach ($data as $k =>$v){
//            if(intval($v['last_time'])>2018){
//                $data[$k]['last_time']=date('Y-m-d H',$v['last_time']);
//            }
//        }
//        dump($data);die;
        echo '4-16    '.strtotime(date('2018-04-16'));
        echo "<br>";
        echo  '4-17     '.strtotime(date('2018-04-17'));
        echo "<br>";
        echo  '4-18     '.strtotime(date('2018-04-18'));
        echo "<br>";
        echo  '4-20     '.strtotime(date('2018-04-20'));
        echo "<br>";
        echo  '5-2    '.strtotime(date('2018-05-02'));
        echo "<br>";
        echo  '5-4    '.strtotime(date('2018-05-04'));
        echo "<br>";
        echo "<br>";echo '<hr>';
        echo '5-16    '.strtotime(date('2018-05-16'));
        echo "<br>";
        echo  '5-17     '.strtotime(date('2018-05-17'));
        echo "<br>";
        echo  '5-18     '.strtotime(date('2018-05-18'));
        echo "<br>";
        echo  '5-20     '.strtotime(date('2018-05-20'));
        echo "<br>";
        echo  '6-2    '.strtotime(date('2018-06-02'));
        echo "<br>";
        echo  '6-4    '.strtotime(date('2018-06-04'));
        echo "<br>";
        echo '<hr>';
        echo '6-16    '.strtotime(date('2018-06-16'));
        echo "<br>";
        echo  '6-17     '.strtotime(date('2018-06-17'));
        echo "<br>";
        echo  '6-18     '.strtotime(date('2018-06-18'));
        echo "<br>";
        echo  '6-20     '.strtotime(date('2018-06-20'));



    }

    public function test()
    {

        $url = "http://love1314.ink/api/alpay/texts?user_id=32";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        echo $output;
    }

    public function a()
    {
        $t1 = microtime(true);
        $a = new Redis();
        $data = $a->get('testinfo');
        //$data=Db::name('test')->select();
        //$aa=json($data);
        //$res=$a->set('testinfo',$data);
        $t2 = microtime(true);
        echo '耗时' . round($t2 - $t1, 3) . '秒';
        //var_dump($data);
        unset($data);
        unset($aa);
    }

    public function b()
    {
        $t1 = microtime(true);
        $data = Db::name('test')->select();
        //$data = Db::query('select * from tea_test where id < 50000');
        $t2 = microtime(true);
        echo '耗时' . round($t2 - $t1, 3) . '秒';
        echo memory_get_usage();
        dump($data);
    }
}
