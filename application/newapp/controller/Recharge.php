<?php
/**
 * Created by PhpStorm.
 * user: jacky-fu
 * Date: 2018/4/9
 * Time: 11:36
 */
namespace app\newapp\controller;
use app\newapp\model\RechargeCart;
use app\tmvip\controller\Api;
use think\Db;
use app\api\controller\Webalpay;
use think\View;
class Recharge extends Com {

    //显示所有的理茶宝信息
    public function rechargeIndex(){
        $data=Db::table('tea_recharge')->select();
        return json($data);
    }
    //判断用户是否购买权限
    public function allowBuy($user_id,$recharge_money){
        //首先判断用户是否有过购买记录，然后判断是否允许购买以及再次购买
        $res = Db::table('tea_user_recharge')
            ->where([
                'is_return' =>1,
                'is_ceo'=>0,
                'user_id'   =>$user_id
            ])
            ->find();
        if($res){
            //如果存在就是再次购买
            $this->allowAddBuy($user_id,$recharge_money);
        }else{
            return true;
        }
    }

    //判断用户是否能再次购买
    public function allowAddBuy($user_id,$recharge_money){
        //根据用户的购买记录查询最后一次的购买钱数
        //只允许购买大于等于最后一次的购买的钱数
        $actioninfo =  Db::table('tea_user_recharge')
            ->where([
                'is_return' =>0,
                'is_ceo'=>0,
                'user_id'   =>$user_id
            ])
            ->order('rec_addtime desc')->limit('1')
            ->find();
        //如果不存在则提示用户前往购买理茶宝
        if(!$actioninfo){
            return 0;
            //$this->error("当前的积分未返还完",U('Index/customer_info'));
        }
        if(intval($actioninfo['rec_money']) > intval($recharge_money)){
            return 1;
            //$this->error("当前购买的产品金钱数小于上次购买的钱数",U('index/lichabao'));
        }else{
            return true;
        }
    }

    //用户购买理茶宝产品(第一次及后续积分返还完再次购买)
    public function buyManage(){
        $this->checkLogin();
        $user_id=session('lcb_user_id');
        $recharge_id = intval(input('post.recharge_id'));
        $moeny = Db::table('tea_recharge')->where('id',$recharge_id)->find();
        $recharge_money=floatval($moeny['rec_money']);
        // //用户没有完善个人信息，调去公共查询用户的方法
//       $user_con=new User();
//       $user_info=$user_con->is_real();
//        if($user_info==0) return 3;
        //判断用户的上级是否购买
        $recharge_user_info=$this->buyRecharge($user_id);
        if($recharge_user_info===false) return 8;   //上级未购买，无法购买
        //首先判断是否允许客户购买
        $res =$this->allowBuy($user_id,$recharge_money);
        if(!$res) return 0;
        // 判断是否有未支付理茶宝订单 ***************
        $nopayorder=Db::table('tea_recharge_cart')
            ->where([
                'pay_status' =>0,
                'is_ceo'=>0,
                'user_id'   =>$user_id
            ])->find();
        if ($nopayorder)  return  4;
        //只有用户支付成功才允许数据入库
        $model = new RechargeCart();
        $res =$model->buyManage($recharge_id,$user_id,$recharge_money);
        if(!$res) return 5; //下单失败
        //下单成功，跳转到支付宝支付，带上Id
        return json(array('status'=>1,'id'=>$res));
    }
    //用户追加购买
    public function addBuyManage(){
        $this->checkLogin();
        $user_id = session('lcb_user_id');
        //获取用户追加购买时参数
        $recharge_id = intval(input('post.reacharge_id'));
        $moeny = Db::table('tea_recharge')
            ->where('id',$recharge_id)
            ->find();
        $moeny=floatval($moeny['rec_money']);
        //在个人中心实现追加购买
        //①首先判断用户购买的金额是否大于最后一次购买的金额
        $actioninfo = Db::table('tea_user_recharge')
            ->where('user_id',$user_id)
            ->where('is_return',0)
            ->where('is_ceo',0)
            ->order('rec_addtime desc')
            ->limit('1')->find();
        if(intval($actioninfo['rec_money']) > $moeny){
            return 0;
            //$this->error("当前追加购买的产品金钱数小于当前购买的钱数",U('index/customer_info'));
        }
        // 判断是否有未支付理茶宝订单 ***************
        $nopayorder=Db::table('tea_recharge_cart')
            ->where([
                'pay_status' =>0,
                'is_ceo'=>0,
                'user_id'   =>$user_id
            ])
            ->find();
        if ($nopayorder) {
            return  4;
            // 您有尚未支付的理茶宝产品，请支付或者取消
        }
        //②将购买的金额以及ID添加到积分购物车里面去
        $model = new RechargeCart;
        $res =$model->buyManage($recharge_id,$user_id,$moeny);
        if(!$res){
            return 2;
            //$this->error('下单购买失败');
        }
        //下单成功，跳转到支付宝支付，带上Id
        //下单成功，跳转到支付宝支付，带上Id
        $data['status']  = 1;
        $data['id'] = $res;
        return json($data);
    }

    //用户升级购买
    public function addAgainBuyManage(){
        $this->checkLogin();
        //$IntegralModel=Db::table('tea_integral');
        $rechargeModel=new RechargeCart();
        $user_id = session('lcb_user_id');
        //$jifen=$IntegralModel->where('user_id',$user_id)->order('id desc')->find();
        //点击 升级按钮先判断能否点击购买
        $this->allowAddBuyManage();
        //获取用户升级购买时参数
        $recharge_id = intval(input('post.recharge_id'));
        $moeny = Db::table('tea_recharge')
            ->where('id',$recharge_id)
            ->find();
        $rec_money=floatval($moeny['rec_money']);
        $teares=0;
        //在个人中心实现追加购买
        //①首先判断用户购买的金额是否大于最后一次购买的金额
        $actioninfo = Db::table('tea_user_recharge')
            ->where("user_id=$user_id AND is_return=1 AND is_ceo=0")
            ->order('addtime desc')->limit('1')
            ->find();
        $last_lev=intval($actioninfo['rec_lev']);
        if(floatval($actioninfo['rec_money']) >= $rec_money){
            //ajax发送数据
            return 1;
        }
        // 判断是否有未支付理茶宝订单 ***************
        $nopayorder=Db::table('tea_recharge_cart')
            ->where([
                'pay_status' =>0,
                'is_ceo'=>0,
                'user_id'   =>$user_id
            ])
            ->find();
        if ($nopayorder) {
            return 4;
            // 您有尚未支付的理茶宝产品，请支付或者取消
        }
        //判断用户购买的产品是否有激活
        if(intval($actioninfo['is_active'])==0){
            return 7;
            //购买的产品没有激活
        }

        $moeny = $rec_money - floatval($actioninfo['rec_money']); //需支付的金额数值为当前选择的产品的钱减掉已经购买的钱
        //②将购买的金额以及ID添加到积分购物车里面去
        //获取传递过来的茶券以及茶点

        //①如果茶籽等于差价，那么直接修改用户的
        //积分数等于用户选择升级的产品的差价
        //直接操作数据库减去相对于的积分数即可
        //$IntegralModel->where('id='.$jifen['id'])->setDec('reg_inte',floatval($teares));
        $res =$rechargeModel->addBuyManage($recharge_id,$user_id,$moeny,$teares,$last_lev);
        if(!$res){
            return 5;
        }
        $data['status']  = 1;$data['id'] = $res;
        //下单成功，跳转到支付宝支付，带上Id
        return json($data);
    }

    //判断用户是否能够多次点击升级理茶宝产品
    public function allowAddBuyManage(){
        $this->checkLogin();
        //首先在理茶宝订单表里面查询该用户ID是否存在未支付的订单
        $user_id=session('lcb_user_id');
        $model=Db::table('tea_recharge_cart');
        $info = $model->where("user_id='$user_id' AND pay_status=0 AND is_ceo=0")->select();
        if($info){
//          您有未完成的理茶宝升级订单，如果需要更换别的产品，请前往个人中心取消订单后重新下单
            return 3;
        }else{
            return true;
        }
    }

    //第一次下单或者再次购买成功之后跳转到支付页面会后显示商品的详情
    public function confirm(){
        $id=intval(input('get.id'));
        $user_id=session('lcb_user_id');
        $data=Db::table('tea_recharge_cart')
            ->where('id',$id)
            ->find();
        $user_wallet=Db::table('tea_user')->where('user_id',$user_id)->find();
        $data['wallet']=floatval($user_wallet['wallet']);
        $recharge_data=Db::table('tea_recharge')->where('id',$data['recharge_id'])->find();
        $view=new View();
        $view->assign('data',$data);
        $view->assign('recharge',$recharge_data);
        return $view->fetch();
    }

    //升级理茶宝之后购买成功跳转到支付页面会后显示商品的详情
    public function confirmupdate(){
        $id=intval(input('get.id'));
        $user_id=session('lcb_user_id');
        $data=Db::table('tea_recharge_cart')
            ->where('id',$id)
            ->find();
        if(!$data){
            $this->error('操作非法','user/index');
        }
        $user_wallet=Db::table('tea_user')->where('user_id',$user_id)->find();
        $data['wallet']=floatval($user_wallet['wallet']);
        $recharge_data=Db::table('tea_recharge')->where('id',$data['recharge_id'])->find();
        $view=new View();
        $view->assign('data',$data);
        $view->assign('recharge',$recharge_data);
        return $view->fetch();
    }

    //传递recharge_cart表中的id，通过ID查询所有信息并返回页面，在微信支付时使用这个方法
    public function findOneById(){
        $id=intval(input('post.id'));
        $user_id=session('lcb_user_id');
        $money_money=floatval(input('post.money'));
        $res=$this->payMoney($id,$money_money);
        if($res['status']==0){
//            $this->error('服务器正在抢修中','appmobile/user/index');
            return 0;
        }elseif($res['status']==1){
//            $this->success('支付成功','appmobile/user/index');
            return 3;
        }else {
            if ($money_money != 0) $this->payMoney($id, $money_money);
            $data = Db::table('tea_recharge_cart')
                ->alias('a')
                ->where('a.id', $id)
                ->where("a.user_id", $user_id)
                ->join('tea_recharge r', 'r.id=a.recharge_id')
                ->find();
            if ($data) {
                return json($data);
            } else {
                return 0;
            }
        }

    }

    //支付时选择钱包金额
    public function payMoney($recharge_id,$money){
        $rechargeInfo=Db::table('tea_recharge_cart')->where('id',$recharge_id)->find();
        //判断金额是否一致
        $user_id=intval($rechargeInfo['user_id']);
        if(intval($rechargeInfo['pay_status'])==1){
            $data['status']=0;
            $data['msg']='no';
            return $data;
        }
        if(floatval($rechargeInfo['recharge_money']) <= 0.00){
            $data['status']=2;
            $data['msg']='ok';
            return $data;
        }
        if(floatval($rechargeInfo['recharge_money'])!=floatval($money)){
            $data=array(
                'recharge_money'=>floatval($rechargeInfo['recharge_money'])-$money,
                'again_money'=>$money
            );
            $res= Db::table('tea_recharge_cart')->where('id',$recharge_id)->setField($data);
            if($res){
                //操作成功
                //更新钱包的金额

                Db::table('tea_user')->where('user_id',$user_id)->setDec('wallet',floatval($money));
                $logData=array(
                    'user_id'=>$user_id,
                    'surplus_inte'=>floatval($money),
                    'introduce'=>"购买理茶宝消耗".floatval($money),
                    'wallet'=>2,
                    'addtime'=>time(),
                    'year'=>date('Y'),
                    'month'=>date('m'),
                    'day'=>date('d'),
                    'log_out_trade_no'=>date('Y').date('m').date('d').uniqid().'168'
                );
                Db::table('tea_integral_log')->insert($logData);
                $data['status']=2;
                $data['msg']='ok';
                return $data;
            }else{
                //操作失败
                $data['status']=0;
                $data['msg']='no';
                return $data;
            }
        }else{
            //金额一致，不需要跳转，直接提示支付成功
            //首先修改订单的状态
            $data=[
                'pay_status'=>1,
                'trade_no'=>date('Y').date('m').date('d').uniqid().'168',
                'trade_beizhu'=>'交易成功',
                'recharge_money'=>0,
                'again_money'=>$money
            ];
            Db::table('tea_recharge_cart')->where('id',$recharge_id)->setField($data);
            Db::table('tea_user')->where('user_id',$user_id)->setDec('wallet',floatval($money));
            $logData=array(
                'user_id'=>$user_id,
                'surplus_inte'=>floatval($money),
                'introduce'=>"购买理茶宝消耗".floatval($money),
                'wallet'=>2,
                'addtime'=>time(),
                'year'=>date('Y'),
                'month'=>date('m'),
                'day'=>date('d'),
                'log_out_trade_no'=>date('Y').date('m').date('d').uniqid().'168'
            );
            Db::table('tea_integral_log')->insert($logData);
            $controller =new RechargeCart();
            //判断是否是升级还是购买
            if(intval($rechargeInfo['is_againbuy'])==0){
                //购买
                $ares=$controller->buyUpdate($user_id,$rechargeInfo['recharge_num'],date('Y').date('m').date('d').uniqid().'168');
                $data['status']=1;
                $data['msg']='ok';
                return $data;
            }else{
                //升级
               $controller->asignManage($user_id,$rechargeInfo['recharge_num'],date('Y').date('m').date('d').uniqid().'168');
                $data['status']=1;
                $data['msg']='ok';
                return $data;
            }



        }

    }

    //第一次购买以及后续追加购买调用支付宝支付
    //移动端
    public function webalpay(){
        //判断用户是否伪造ID进行请求付款，以避免网站被黑
        // $this->checkLogin();
        $user_id=session('lcb_user_id');
        $reachrge_cart_id=intval(input('get.id'));
        $money_money=floatval(input('get.money'));
        if($money_money==0.00){
            //没有选择钱包的钱
            $data=Db::table('tea_recharge_cart')
                ->where('id',$reachrge_cart_id)
                ->where('user_id',$user_id)
                ->find();
            if(!$data) $this->error('数据非法，请住手','user/index');
            $recharge_data=Db::table('tea_recharge')
                ->where('id',$data['recharge_id'])
                ->find();
            $alpayController=new Webalpay();
            $alpayController->alpay($data['recharge_num'],$data['recharge_money'],$recharge_data['body'],$recharge_data['body'],$setReturnUrl='http://love1314.ink/api/Webalpay/buyReturnUrl',$notify_url='http://love1314.ink/api/Webalpay/buyNotiflUrl');
        }else{
            //有选择钱包的钱
            $res=$this->payMoney($reachrge_cart_id,$money_money);
            if($res['status']==0){
                $this->error('服务器正在抢修中','appmobile/user/index');
            }elseif($res['status']==1){
                $this->success('支付成功','appmobile/user/index');
            }else {
                $data = Db::table('tea_recharge_cart')
                    ->where('id', $reachrge_cart_id)
                    ->where('user_id', $user_id)
                    ->find();
                if (!$data) $this->error('数据非法，请住手', 'user/index');
                $recharge_data = Db::table('tea_recharge')
                    ->where('id', $data['recharge_id'])
                    ->find();
                $alpayController = new Webalpay();
                $alpayController->alpay($data['recharge_num'], $data['recharge_money'], $recharge_data['body'], $recharge_data['body'], $setReturnUrl = 'http://'.$_SERVER['HTTP_HOST'].'/api/Webalpay/buyReturnUrl', $notify_url = 'http://'.$_SERVER['HTTP_HOST'].'/api/Webalpay/buyNotiflUrl');
            }
        }

    }

    //用户升级完调用微信支付
    public function webwxpay(){
        //判断用户是否伪造ID进行请求付款，以避免网站被黑
        // $this->checkLogin();
        $user_id=session('lcb_user_id');
        $reachrge_cart_id=intval(input('get.id'));
        $money_money=floatval(input('get.money'));
        if($money_money==0.00){
            //没有选择钱包的钱
            $data=Db::table('tea_recharge_cart')
                ->where('id',$reachrge_cart_id)
                ->where('user_id',$user_id)
                ->find();
            if(!$data) $this->error('数据非法，请住手','user/index');
            $recharge_data=Db::table('tea_recharge')
                ->where('id',$data['recharge_id'])
                ->find();
            //header("Location:other.php");
            $url="/wapWxpay/example/managerJsapi.php?body=".$recharge_data['body']."&&money=".$data['recharge_money']."&&attach=ordermanager&&trade=".$data['recharge_num'];
            echo "<script>window.location.href=$url;</script> ";
            //header("Location:/wapWxpay/example/managerJsapi.php?body=".$recharge_data['body']."&&money=".$data['recharge_money']."&&attach=ordermanager&&trade=".$data['recharge_num']);
        }else {
            //有选择钱包的钱
            //$this->payMoney($reachrge_cart_id, $money_money);
            $data = Db::table('tea_recharge_cart')
                ->where('id', $reachrge_cart_id)
                ->where('user_id', $user_id)
                ->find();
            if (!$data) $this->error('数据非法，请住手', 'user/index');
            $recharge_data = Db::table('tea_recharge')
                ->where('id', $data['recharge_id'])
                ->find();
            $url="/wapWxpay/example/managerJsapi.php?body=".$recharge_data['body']."&&money=".$data['recharge_money']."&&attach=ordermanager&&trade=".$data['recharge_num'];
            echo "<script>window.location.href="+"$url"+";</script> ";
            // header("Location:/wapWxpay/example/managerJsapi.php?body=".$recharge_data['body']."&&money=".$data['recharge_money']."&&attach=ordermanager&&trade=".$data['recharge_num']);
        }
    }

    //升级理茶宝支付
    //支付宝支付
    public function updatealpay(){
        //判断用户是否伪造ID进行请求付款，以避免网站被黑
        // $this->checkLogin();
        $user_id=session('lcb_user_id');
        $reachrge_cart_id=intval(input('get.id'));
        $money_money=floatval(input('get.money'));
        if($money_money==0.00){
            //没有选择钱包的钱
            $data=Db::table('tea_recharge_cart')
                ->where('id',$reachrge_cart_id)
                ->where('user_id',$user_id)
                ->find();
            if(!$data) $this->error('数据非法，请住手','user/index');
            $recharge_data=Db::table('tea_recharge')
                ->where('id',$data['recharge_id'])
                ->find();
            $alpayController=new Webalpay();
            $alpayController->alpay($data['recharge_num'],$data['recharge_money'],$recharge_data['body'],$recharge_data['body'],$setReturnUrl='http://'.$_SERVER['HTTP_HOST'].'/api/Webalpay/updateReturnUrl',$notify_url='http://'.$_SERVER['HTTP_HOST'].'/api/Webalpay/updateNotiflUrl');
        }else{
            //有选择钱包的钱
            $res=$this->payMoney($reachrge_cart_id,$money_money);
            if($res['status']==0){
                $this->error('服务器正在抢修中','appmobile/user/index');
            }elseif($res['status']==1){
                $this->success('支付成功','appmobile/user/index');
            }else{
                $data=Db::table('tea_recharge_cart')
                    ->where('id',$reachrge_cart_id)
                    ->where('user_id',$user_id)
                    ->find();
                if(!$data) $this->error('数据非法，请住手','user/index');
                $recharge_data=Db::table('tea_recharge')
                    ->where('id',$data['recharge_id'])
                    ->find();
                $alpayController=new Webalpay();
                $alpayController->alpay($data['recharge_num'],$data['recharge_money'],$recharge_data['body'],$recharge_data['body'],$setReturnUrl='http://love1314.ink/api/Webalpay/updateReturnUrl',$notify_url='http://love1314.ink/api/Webalpay/updateNotiflUrl');
            }

        }
    }
    //取消相关的订单
    public function del(){

        $this->checkLogin();
        //理茶宝订单表ID
        $id=intval(input('post.id'));
        $user_id = session('lcb_user_id');
        //取消订单时将订单所有的茶籽返还到积分表中去
        //在模型中调用这个方法
        $model=new RechargeCart();
        $res = $model->del($user_id,$id);
        if($res){
            return 1;
        }else{
            return 0;
        }
    }

    //显示个人中心的我的理茶宝订单
    public function myRechargeIndex(){
        $this->checkLogin();
        $user_id=session('lcb_user_id');
        $data=Db::table('tea_recharge_cart')
            ->alias('c')
            ->field('c.*,r.rec_money')
            ->where('c.user_id',$user_id)
            ->where('c.is_ceo',0)
            ->join('tea_recharge r','r.id=c.recharge_id')
            ->select();
        return json($data);
    }

    //显示升级理茶宝的页面所有理茶宝信息
    public function updataRechargeIndex(){
        $user_id=session('lcb_user_id');
        $userRecharge=Db::table('tea_user_recharge')
            ->where('user_id',$user_id)
            ->where('is_ceo',0)
            ->order('id desc')
            ->limit(1)
            ->find();
        $data=Db::table('tea_recharge')
            ->where('rec_money','>',floatval($userRecharge['rec_money']))
            ->select();
        return json($data);
    }

    //判断用户是否能够升级
    public function allowproduct(){
        $user_id=session('lcb_user_id');
        $actioninfo = Db::table('tea_user_recharge')
            ->where("user_id=$user_id AND is_return=1 AND is_active= 1 AND is_ceo=0")
            ->order('rec_addtime desc')
            ->limit('1')->find();
        //dump($actioninfo);
        //判断用户是否能够升级
        if(!$actioninfo){
            return  1;
        }
    }

    //跳转到升级页面
    public function richardtea(){
//        $id=intval(input('get.id'));
//        $data=Db::table('tea_recharge_cart')
//            ->where('id',$id)
//            ->find();
//        $recharge_data=Db::table('tea_recharge')->where('id',$data['recharge_id'])->find();
//        $view=new View();
//        $view->assign('data',$data);
//        $view->assign('recharge',$recharge_data);
        return $this->fetch();
    }
    //在我的理茶宝页面，点击购买激活的时候查看，用户信息完整验证
    public function checkToActive(){
        $user_id=session('lcb_user_id');
//        $userApi=new User();
//        $user_info=$userApi->is_real();
//        if($user_info==0)  return json(array('status'=>0,'msg'=>'信息不完整'));    //尚未填写推荐人，前往填写
        $actioninfo = Db::table('tea_user_recharge')
            ->where("user_id=$user_id AND is_return=1")
            ->order('addtime desc')
            ->limit('1')
            ->find();
        if($actioninfo){
            //已经购买了
            $data['status']=2;
            return json($data);
        }
        // 判断是否有未支付理茶宝订单 ***************
        $nopayorder=Db::table('tea_recharge_cart')
            ->where([
                'pay_status' =>0,
                'user_id'   =>$user_id
            ])
            ->find();
        if ($nopayorder) {
            $data['status']=3;
            return json($data);
            // $this->error('您有尚未支付的理茶宝产品，请支付或者取消',U('index/customer_info'));
        }
            $data['status']=1;
            return json($data);
    }

    //在理茶宝页面点击购买后进入理茶宝的详情信息
    public function confirmproduct()
    {
        $recharge_id=intval(input('get.recharge_id'));
        $recahrge_data=Db::table('tea_recharge')
            ->where('id',$recharge_id)
            ->find();
        $recahrge_data['b']=floatval($recahrge_data['total_inte'])-floatval($recahrge_data['gift']);
        return view('confirmproduct',['data'=>$recahrge_data]);
    }

    //购买时判断当前用户的上级是否有购买理茶宝
    private function buyRecharge($user_id){
        $user_info=Db::connect(config('db_config2'))->field('parent_id')->name("users")->where("user_id",$user_id)->find();
        $parent_id=intval($user_info['parent_id']);
        if($parent_id==0) return true;
        $recharge_info=Db::table('tea_user_recharge')
            ->where("user_id",$parent_id)
            ->where('is_active',1)
            ->find();
        if($recharge_info){
            return true;
        }else{
            return false;
        }
    }

    
}