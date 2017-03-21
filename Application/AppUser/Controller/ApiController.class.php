<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class ApiController extends AppUserController {

    function _initialize(){
        parent::_initialize();
    }

    public function test(){
        $pt = I("post.");
        if(empty($pt)){
            $pt = "没有数据！！！";
        }else{
            $pt = "0.0 === 有数据！！！";
        }
        p($pt);
        return $pt;
    }

    public function get_token()
    {
        // Content-Type:application/x-www-form-urlencoded
        // App-Key:8brlm7uf8bcz3
        // Nonce:555555
        // Timestamp:1484814056
        // Signature:c9f36ec65176fa6d92f6b4d5527456ac8111c252

        //$data = array("name" => "Hagrid", "age" => "36");
        // $param = array();
        // $param['userId'] = "123456";
        // $param['name'] = "aaa";
        // $param['portraitUri'] = "headportrait";
        // if(isset($param)){
        //     $data_string = json_encode($param);
        // }
        srand((double)microtime()*1000000);
        $appKey = '8brlm7uf8bcz3'; // 开发者 App Key
        $appSecret = 'iH0kzREkuOt'; // 开发者平台分配的 App Secret。
        $nonce = rand(); // 获取随机数。
        $timestamp = time(); // 获取时间戳。
        $signature = sha1($appSecret.$nonce.$timestamp);

        $method = 'POST';
        // $data_string = "userId=10000&name=Ironman&portraitUri=http%3A%2F%2Fabc.com%2Fmyportrait.jpg";
        //echo $data_string;
        // $strJson = '{"userId":"1000012","name":"JINJIN","portraitUri":"http%3A%2F%2Fabc.com%2Fmyportrait.jpg"}';
        $url='http://api.cn.ronghub.com/user/getToken.json'; 
        $header[] = 'App-Key:'.$appKey;
        $header[] = 'Nonce:'.$nonce;
        $header[] = 'Timestamp:'.$timestamp;
        $header[] = 'Signature:'.$signature;

        $param = array();
        $param['userId'] = "123456";
        $param['name'] = "aaa";
        $param['portraitUri'] = "headportrait";

        $token = send_request($url, $param, $header,$method = 'POST',$refererUrl = '', $timeout = 30, $proxy = false);
        p(json_decode($token,true));
    }


    /**
     * 发起 server 请求
     * @param $action
     * @param $params
     * @param $httpHeader
     * @return mixed
     */
    public function curl($action, $params) {
        $action = self::SERVERAPIURL.$action.'.'.$this->format;
        $httpHeader = $this->createHttpHeader();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $action);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build_query($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); //处理http证书问题
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        if (false === $ret) {
            $ret =  curl_errno($ch);
        }
        curl_close($ch);
        return $ret;
    }



    // public function get_token(){
    //     // 重置随机数种子。
    //     srand((double)microtime()*1000000);

    //     $appKey = '8brlm7uf8bcz3'; // 开发者 App Key
    //     $appSecret = 'iH0kzREkuOt'; // 开发者平台分配的 App Secret。
    //     $nonce = rand(); // 获取随机数。
    //     $timestamp = time(); // 获取时间戳。

    //     $signature = sha1($appSecret.$nonce.$timestamp);
    //     p($signature."<br/>");

    //     $param = array();
    //     $param['userId'] = "123456";
    //     $param['name'] = "aaa";
    //     $param['portraitUri'] = "headportrait";
    //     // $url='http://localhost:1018/AppUser/Api/test';
    //     $url='http://api.cn.ronghub.com/user/getToken.json';
    //     // header("content-type:text/html; charset=UTF-8"); 
    //     $header[] = "content-type:text/html; charset=UTF-8"; 
    //     $header['App-Key'] = $appKey;
    //     $header['Nonce'] = "nonce";
    //     $header['Timestamp'] = "timestamp";
    //     $header['Signature'] = "signature";
    //     $ch = curl_init();//初始化curl
    //     curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
    //     curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    //     curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $header); //
    //     $data = curl_exec($ch);//运行curl
    //     p($data);
    //     curl_close($ch);
    // }

    public function _search(){
        //status 0_接口开发完成 页面待接, 1_完成
        //operation_item 操作端:1_用户端，2_DD服务人员端，3_社区维修人员端，4_管理员端
        $map = array();
        $operation_item = I('get.operation_item');
        $status = I('get.status');
        $reset = I('get.reset');
        $name = I('get.name');
        $apicloud = I('get.apicloud');
        if ($operation_item!="") {
            $map['operation_item'] = array('eq',"$operation_item");
        }
        if ($reset!="") {
            if($reset<2){
                //最近有无更新 0_无, 1_有更新, 2_今日更新, 3_今日新增 , 4_本周更新, 5_本周新增, 6_本月更新, 7_本月新增
                $map['reset'] = array('eq',"$reset");
            }else if($reset==2){
                //获取到 今日更新 的数据
                $this_day = date("Y-m-d"." 00:00:00");
                $this_time = strtotime($this_day);
                $map['edittime'] = array('gt',$this_time);
            }else if($reset==3){
                //获取到 今日新增 的数据
                $this_day = date("Y-m-d"." 00:00:00");
                $this_time = strtotime($this_day);
                $map['addtime'] = array('gt',$this_time);
            }else if($reset==4){
                //获取到 本周更新 的数据
                $this_week = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
                $this_week_time = strtotime($this_week);
                $map['edittime'] = array('gt',$this_week_time);
            }else if($reset==5){
                //获取到 本周新增 的数据
                $this_week = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
                $this_week_time = strtotime($this_week);
                $map['addtime'] = array('gt',$this_week_time);
            }else if($reset==6){
                //获取到 本月更新 的数据
                $this_month = date("Y-m"."-01 00:00:00");
                $this_month_time = strtotime($this_month);
                $map['edittime'] = array('gt',$this_month_time);
            }else if($reset==7){
                //获取到 本月新增 的数据
                $this_month = date("Y-m"."-01 00:00:00");
                $this_month_time = strtotime($this_month);
                $map['addtime'] = array('gt',$this_month_time);
            }
        }
        if ($status!="") {
            $map['status'] = array('eq',"$status");
        }
        if (!empty($name)) {
            $map['name'] = array('like',"%$name%");
        }
        if (!empty($apicloud)) {
            $map['apicloud'] = array('like',"%$apicloud%");
        }
        $this->assign('search',array(
            'operation_item' => $operation_item,
            'status' => $status,
            'reset' => $reset,
            'apicloud' => $apicloud,
            'name' => $name
        ));
        $map['isshow'] = array('eq',"1");//是否显示：0_不显示, 1_显示 (默认)
        return $map;
    }
    
    public function index()
    {   
        $map = $this->_search();

        $list = M('Api')->where($map)->order("sort asc")->select();
        // p(M('Api')->getlastsql());
        $this->assign('list',$list);
        $this->display();
	} 
    
    public function add(){
        if(IS_POST){
            $data = I("post.");
            if(empty($data['sort'])){
                $map = array();
                $last_sort = M("Api")->field("sort")->order("sort desc")->find();
                $data['sort'] = ($last_sort['sort']+1);
            }
            $data['status'] = 0;//0_接口开发完成 页面待接, 1_完成
            $data['addtime'] = time();
            $api_return = M('Api')->add($data);
            if($api_return){
                $this->ajaxReturn(1, '操作成功！');
            }else{
                $this->ajaxReturn(0, '操作失败！');
            }
        }

        $this->display();
    }

    public function edit(){
        if(IS_POST){
            $data = I("post.");
            $data['reset'] = 1;//是否重置：0_否, 1_接口被重置(修改)
            $data['edittime'] = time();
            $api_return = M('Api')->save($data);
            if($api_return){
                $this->ajaxReturn(1, '操作成功！');
            }else{
                $this->ajaxReturn(0, '操作失败！');
            }
        }else{
            $id =I('get.'.$pk);
            // $id = I("post.id");
            if(!empty($id)){
                $api = M('Api')->where($id)->find();
                $this->assign("info",$api);
            }else{
                // $this->show("参数错误！");
                $this->error('参数错误！');
                die();
            }
        }

        $this->display();
    }

    public function signature(){
        if(IS_POST){
            $data = I("post.");
            $data['reset'] = 0;//(已完成，去掉重置) 是否重置：0_否, 1_接口被重置(修改)
            $data['status'] = 1;//0_接口开发完成 页面待接, 1_对接完成
            $api_return = M('Api')->save($data);
            if($api_return){
                $this->ajaxReturn(1, '操作成功！');
            }else{
                $this->ajaxReturn(0, '操作失败！');
            }
        }else{
            $id =I('get.'.$pk);
            if(!empty($id)){
                $api = M('Api')->field("id,apicloud")->where($id)->find();
                $this->assign("info",$api);
            }else{
                // $this->show("参数错误！");
                $this->error('参数错误！');
                die();
            }
        }

        $this->display();
    }

    public function delete() {
        $pk = M('Api')->getPk();
        $ids = trim(I('get.'.$pk), ',');
        if ($ids) { 
            //加上id条件
            $map['id'] = array('in',$ids);
            $api_delete = M('Api')->where($map)->delete();
            if ($api_delete) {
                IS_AJAX && $this->ajaxReturn(1, '操作成功！');
                $this->success('操作成功！');
            } else {
                IS_AJAX && $this->ajaxReturn(0, '操作失败！');
                $this->error('操作失败！');
            }
        }
        else {
            IS_AJAX && $this->ajaxReturn(0, '非法操作！');
            $this->error('非法操作！');
        }
    }


    /*--------------查询数据系列--------------*/

    public function getdata()
    {
        $object = I('get.object');
        $object==""?$object=1:$object;
        if($object==1){
            //用户
            //status:状态：0_不启用,1_启用
            $list = M('Users')->field("id,phone,password,pet_name,sex,head_portrait,status as qy,sequestration as fh")->select();
            // p(M('Users')->getlastsql());
            // return;
        }else if($object==2){
            //DD维修员
            //isshow:是否启用: 0_不启用, 1_启用（默认）
            //status:审核状态: 0_待审核（默认）, 1_初审通过, 2_复审通过
            //sequestration:账号状态: 0_封号, 1_正常（默认）
            $list = M('RepairUser')->field("id,phone,password,pet_name,sex,head_portrait,isshow qy,status as sh,sequestration as fh")->select();
        }else if($object==3){
            //社区维修人员
            //isshow:是否启用: 0_不启用, 1_启用（默认）
            //status:审核状态: 0_待审核（默认）, 1_初审通过, 2_复审通过
            //sequestration:账号状态: 0_封号, 1_正常（默认）
            $list = M('CommunityRepair')->field("id,phone,password,pet_name,sex,head_portrait,isshow qy,status as sh,sequestration as fh")->select();
        }else if($object==4){
            //管理员
            //status:状态：0_不启用,1_启用
            $list = M('Keeper')->field("id,phone,password,pet_name,sex,head_portrait,status as qy")->select();
        }

        $this->assign('list',$list);
        $this->assign('search',array(
            "object" => $object
        ));
        $this->display();
    }

}