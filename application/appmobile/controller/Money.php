<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/4/20
 * Time: 17:05
 */
namespace app\appmobile\controller;
use app\api\controller\Webalpay;
use app\tmvip\controller\Api;
use think\Db;
use think\Request;
use think\View;

class Money extends Common{
   //进入我的充值页面
    public function recharge(){
        $user_id=intval(session('user_id'));
        $nopayorder=Db::table('tea_money')
            ->where([
                'pay_status' =>0,
                'user_ids'   =>$user_id
            ])
            ->find();
        if($nopayorder) {
            $this->error('您有尚未支付的钱包充值订单，请支付或者取消','money/mymoney');
        }
        return $this->fetch();
    }

    //开始充值我的钱包
    public function buyMoney(){
        $user_id=intval(session('user_id'));
        //首先判断用户是否被冻结
        $user_con=new User();
        $user_info=$user_con->is_real();
        if($user_info==0){
            return 3;
            //$this->error('尚未填写推荐人，前往填写',U('user/my_recommender'));
        }
        //然后判断用户是否有未支付的钱包订单
        $nopayorder=Db::table('tea_money')
            ->where([
                'pay_status' =>0,
                'user_ids'   =>$user_id
            ])
            ->find();
        if($nopayorder) return 4;
        $moneys=floatval(input('post.money'));
        if($moneys==0.00)  return 9;
        //允许客户充值钱包
        $mooney=new \app\appmobile\model\Money();
        $res=$mooney->buyMoneys($user_id,$moneys);
        if($res){
            $data['status']  = 1;
            $data['id'] = $res;
            return json($data);
        }else{
            return 0;
        }

    }

    //跳转到支付页面
    public function confirm(){
        $user_id=session('user_id');
        $id=intval(input('get.id'));
        //$id=2;
        $data=Db::table('tea_money')
            ->where('money_id',$id)
            ->where('user_ids',$user_id)
            ->find();
        if($data){
            $view=new View();
            $view->assign('data',$data);
            return $view->fetch();
        }else{
            $this->error('数据非法，请重新下单','user/index');
        }
    }

    //传递recharge_cart表中的id，通过ID查询所有信息并返回页面
    public function findOneById(){
        $id=intval(input('post.id'));
        $user_id=session('user_id');
        $data=Db::table('tea_money')
            ->where('money_id',$id)
            ->where('user_ids',$user_id)
            ->find();
        if($data){
            $data['body']="我的钱包充值".$data['montys'];
            return json($data);
        }else{
            return 0;
        }

    }

    //选择支付宝支付  //移动端
    public function webalpay(){
        //判断用户是否伪造ID进行请求付款，以避免网站被黑
         $this->checkLogin();
         $user_id=session('user_id');
        $id=intval(input('get.id'));
        $data=Db::table('tea_money')
            ->where('money_id',$id)
            ->where('user_ids',$user_id)
            ->find();
        if(!$data) $this->error('数据非法，请住手','user/index');
        $alpayController=new Webalpay();
        $alpayController->alpay($data['money_num'],$data['montys'],"我的钱包充值".$data['montys'],"我的钱包充值".$data['montys'],$setReturnUrl='http://love1314.ink/api/Webalpay/moneyReturnUrl',$notify_url='http://love1314.ink/api/Webalpay/moneyNotiflUrl');
    }

    //选择微信支付//移动端
    public function webwxpay(){

    }

    //显示我的钱包页面
    public function mymoney(){
      return $this->fetch();
    }
    //我的钱包页面显示所有的订单信息
    public function moneyIndex(){
        $user_id=session('user_id');
        $data=Db::table('tea_money')
            ->where('user_ids',$user_id)
            ->select();
        if(!$data){
            return 0;
        }else{
            return json($data);
        }
    }


    //我的钱包取消订单
    public function delMoney(){
        $id=intval(input('post.id'));
        $res=Db::table('tea_money')
            ->where('money_id',$id)
            ->delete();
        if($res){
            $data['status']=1;
            $data['msg']='取消成功';
            return json($data);
        }else{
            $data['status']=0;
            $data['msg']='服务器错误，取消失败';
            return json($data);
        }
    }


    //我的钱包线下支付
    public function onlineMoneyPay(){
        //获取传递过来的订单号
        $money_num=input('post.money_num');
        $money=Db::table('tea_money')
            ->where('money_num',$money_num)
            ->find();
        $user_id=intval($money['user_ids']);
        if(intval($money['pay_status'])==1  || intval($money['pay_status'])==2){
            $data['status']=0;
            $data['msg']='订单支付错误或者已经支付，如果已经支付无需处理';
            return json($data);
        }
        //订单信息正常之后开始处理线下支付
        Db::startTrans(); //开启事务
        try{
            $data=array(
                'pay_status'=>1,
                'trade_no'=>date('Y').date('m').date('d').uniqid().'168',
                'pay_way'=>3,
                'trade_beizhu'=>'线下支付成功'
            );
            Db::table('tea_money')->where('money_num',$money_num)->setField($data);
            Db::table('tea_user')->where('user_id',$user_id)->setInc('wallet',floatval($money['montys']));
            $logData=array(
                'user_id'=>$user_id,
                'surplus_inte'=>'+'.floatval($money['montys']),
                'introduce'=>"我的钱包充值".floatval($money['montys']),
                'wallet'=>1,
                'sum_inte'=>0,
                'addtime'=>time(),
                'year'=>date('Y'),
                'month'=>date('m'),
                'day'=>date('d'),
                'log_out_trade_no'=>date('Y').date('m').date('d').uniqid().'168'
            );
            Db::table('tea_integral_log')->insert($logData);
        }catch (\Exception $e){
            //回滚事务
            Db::rollback();
        }
    }
}