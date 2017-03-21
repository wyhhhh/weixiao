<?php
namespace AppRepairUser\Controller;
use AppRepairUser\Common\AppRepairUserController;

class RepairController extends AppRepairUserController {

    function _initialize(){
		parent::_initialize();
		
	}
    //维修列表
    public function repairList(){
        $repairuserid = $this->_data['repairuserid'];//当前维修人员的ID
        $pageNumber = $this->_data['pageNumber'];//当前第几页
        $ispay = $this->_data['ispay'];//1_已缴费，0_未交费
        if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
        $map = array();
        if (isset($ispay)) {
            $map['paystatus'] = $ispay;      
        }
        $map['repairuserid'] = $repairuserid;
        $list = M("RepairRelation")->field("repairid,paystatus")->where($map)->select();
        if (empty($list)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $res_data['data'] = $list;
        // $this->_return($res_data);
            $count = M("Repair")->count($mod_pk);
            $pagesize = 6;
            $pager = new \Think\Page($count,$pagesize);
        foreach ($list as $key => $item) {
            if ($item['paystatus'] == 0) {
            //用户缴费状态: 0_未缴费（默认）, 1_已缴费, 2_缴费退款中, 3_已退款
                $map = array();
                $repairlist = M("Repair")->field("ur.name,u.userid,u.addtime")->join("as u left join ".$this->_qz."repair_type as ur on u.typeid = ur.id")->order("u.id asc")->limit(($pageNumber-1)*6,$pager->listRows)->where($map)->find();
                $res_data['data'][$key] = $repairlist;
                $res_data['data'][$key]['addtime'] = date('Y-m-d H:i',$repairlist['addtime']);//时间格式化
                $res_data['data'][$key]['paystatus'] = "未交费";
                $map['id'] = $repairlist['userid'];//连接用户表，查询用户名
                $username = M("Users")->field("real_name")->where($map)->find();
                $res_data['data'][$key]['real_name'] = $username['real_name'];
            }elseif ($item['paystatus'] == 1) {
            //用户缴费状态: 0_未缴费（默认）, 1_已缴费, 2_缴费退款中, 3_已退款
                $map = array();
                $repairlist = M("Repair")->field("ur.name,u.userid,u.addtime")->join("as u left join ".$this->_qz."repair_type as ur on u.typeid = ur.id")->order("u.id asc")->limit(($pageNumber-1)*6,$pager->listRows)->where($map)->find();
                $res_data['data'][$key] = $repairlist;
                $res_data['data'][$key]['addtime'] = date('Y-m-d H:i',$repairlist['addtime']);//时间格式化
                $res_data['data'][$key]['paystatus'] = "已缴费";
                $map['id'] = $repairlist['userid'];//连接用户表，查询用户名
                $username = M("Users")->field("real_name")->where($map)->find();
                $res_data['data'][$key]['real_name'] = $username['real_name'];

            }elseif ($item['paystatus'] == 2) {
            //用户缴费状态: 0_未缴费（默认）, 1_已缴费, 2_缴费退款中, 3_已退款
                $map = array();
                $repairlist = M("Repair")->field("ur.name,u.userid,u.addtime")->join("as u left join ".$this->_qz."repair_type as ur on u.typeid = ur.id")->order("u.id asc")->limit(($pageNumber-1)*6,$pager->listRows)->where($map)->find();
                $res_data['data'][$key] = $repairlist;
                $res_data['data'][$key]['addtime'] = date('Y-m-d H:i',$repairlist['addtime']);//时间格式化
                $res_data['data'][$key]['paystatus'] = "缴费退款中";
                $map['id'] = $repairlist['userid'];//连接用户表，查询用户名
                $username = M("Users")->field("real_name")->where($map)->find();
                $res_data['data'][$key]['real_name'] = $username['real_name'];
            }elseif ($item['paystatus'] == 3) {
                //用户缴费状态: 0_未缴费（默认）, 1_已缴费, 2_缴费退款中, 3_已退款
                $map = array();
                $repairlist = M("Repair")->field("ur.name,u.userid,u.addtime")->join("as u left join ".$this->_qz."repair_type as ur on u.typeid = ur.id")->order("u.id asc")->limit(($pageNumber-1)*6,$pager->listRows)->where($map)->find();
                $res_data['data'][$key] = $repairlist;
                $res_data['data'][$key]['addtime'] = date('Y-m-d H:i',$repairlist['addtime']);//时间格式化
                $res_data['data'][$key]['paystatus'] = "已退款";
                $map['id'] = $repairlist['userid'];//连接用户表，查询用户名
                $username = M("Users")->field("real_name")->where($map)->find();
                $res_data['data'][$key]['real_name'] = $username['real_name'];
            }
                $res_data['data'][$key]['repairid'] = $item['repairid'];
                $res_data['status'] = 1;
                $res_data['msg'] = "维修列表加载成功";
        }
        $this->_return($res_data); 
    }
    //维修详情页
    public function repairDetail(){
        $repairid = $this->_data['repairid'];//当前维修单的ID
        $userid = $this->_data['userid'];//当前维修的住户的ID
        $name = $this->_data['name'];//当前的维修类别
        $map = array();
        $map['u.id'] = $userid;
        $map['status'] = 1;//状态：0_不启用,1_启用
        $data = M("Users")->field("u.phone,u.house_id,u.real_name,ur.number,ur.communityid")->join("as u left join ".$this->_qz."house as ur on ur.id = u.house_id")->where($map)->find();
        if (empty($data)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $res_data['data'] = $data;
        // P($data);
        $map = array();
        $map['id'] = $data['communityid'];//当前社区ID
        $address = M("community")->field("name,address")->where($map)->find();
        $res_data['data']['address'] = $address['name'];//地址的显示
        $map = array();
        $map['id'] = $repairid;
        $repair = M("Repair")->field("name,description,images")->where($map)->find();
        $res_data['data']['name'] = $repair['name'];//维修标题
        $res_data['data']['description'] = $repair['description'];//维修描述
        $images = $repair['images'];//图片(数组形式)
        $images = json_decode($images,true);
        $res_data['data']['images'] = $images;
        $map = array();
        $map['repairid'] = $repairid;
        $repairdata = M("RepairRelation")->field("paystatus,evaluate")->where($map)->find();
        if ($repairdata['paystatus'] == 1 && $repairdata['evaluate'] <=0) {//条件缴费完成and用户差评
            $res_data['data']['appeal'] = 1;//appeal为1的时候，前端显示申诉按钮
        }else{
            $res_data['data']['appeal'] = 0;//appeal为0的时候，前端不显示申诉按钮
        }
        $res_data['status'] = 1;
        $res_data['msg'] = "详情加载成功";
        $this->_return($res_data);
    }
    //申诉接口
    public function appeal(){
        $repairid = $this->_data['repairid'];//当前维修订单的ID
        if (empty($repairid)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $reason = $this->_data['reason'];//申诉的原因
        if (empty($reason)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "请填写申诉原因";
            $this->_return($res_data);
        }
        $images = $this->_data['images'];//上传的内容图片
        $addtime = time();
        $map = array();
        $map['repairid'] = $repairid;
        $repair = M("RepairRelation")->field("id")->where($map)->find();
        $repairrelationid = $repair['id'];
        $data['repairrelationid'] = $repairrelationid;
        $data['reason'] = $reason;
        $data['images'] = $images;
        $data['addtime'] = $addtime;
        $map = array();
        $result = M("Representations")->where($map)->add($data);
        if ($result) {
            $res_data['status'] = 1;
            $res_data['msg'] = "申诉成功";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "系统出错,申诉失败";
        }
        $this->_return($res_data);
    }
}