<?php
namespace AppCommunityRepair\Controller;
use AppCommunityRepair\Common\AppCommunityRepairController;

class RepairController extends AppCommunityRepairController {

    function _initialize(){
		parent::_initialize();
		
	}
    //社区报修人员-报修列表接口
    public function repairList(){
    	$repairid = $this->_data['repairid'];//报修人员的ID
        $pageNumber = $this->_data['pageNumber'];//当前第几页
        if (empty($pageNumber)) {
            $pageNumber = 1;
        }
        if (empty($repairid)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $map = array();
        $map['id'] = $repairid;
        $community = M("CommunityRepair")->field("communityid")->where($map)->find();
        if (empty($community)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $communityid = $community['communityid'];//获取当前社区维修人员的社区ID
    	$map = array();
    	$map['rr.repairid'] = $repairid;
        $map['rr.communityid'] = $communityid;
        $count = M("ReportRepair")->count($mod_pk);
        $pagesize = 6;
        $pager = new \Think\Page($count,$pagesize);
    	$list = M("ReportRepair")->field("rr.id,rr.userid,from_UNIXTIME(rr.addtime,'%Y-%m-%d %H:%i') as addtime,rr.place,u.real_name,rr.status,rr.isread")->join("as rr left join ".$this->_qz."users as u on u.id = rr.userid")->where($map)->order("rr.addtime asc")->limit(($pageNumber-1)*6,$pager->listRows)->select();
        if (empty($list)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "没有数据";
            $this->_return($res_data);
        }
        foreach ($list as $key => $item) {
    	    $res_data['data'][$key] = $item;
            //处理情况: 0_待处理（默认）, 1_已指派维修员, 2_正在处理, 3_已处理, 4_重复报修, 5_误报
            if ($item['status'] == 1) {
                $res_data['data'][$key]['status'] = "已指派维修员";
            }elseif ($item['status'] == 2) {
                $res_data['data'][$key]['status'] = "正在处理";
            }elseif ($item['status'] == 3) {
                $res_data['data'][$key]['status'] = "已处理";
            }elseif ($item['status'] == 4) {
                $res_data['data'][$key]['status'] = "重复报修";
            }elseif ($item['status'] == 5) {
                $res_data['data'][$key]['status'] = "误报";
            }
        }
    	$res_data['status'] = 1;
    	$res_data['msg'] = "列表加载成功";
    	$this->_return($res_data);
    }
    //报修详情接口
    public function repairDetail(){
    	// $userid = $this->_data['userid'];//申请报修的住户ID
    	$id = $this->_data['id'];//当前报修订单的ID
        $isread = $this->_data['isread'];//1_已读，0_未读
        if ($isread == 1) {//已读
            $data['isread'] = $isread;
            $map = array();
            $map['id'] = $id;
            $result1 = M("ReportRepair")->where($map)->save($data);
        	$map = array();
        	$map['u.id'] = $id;
        	$result = M("ReportRepair")->field("u.id,ur.pet_name,ur.head_portrait,u.place,u.description,u.images,from_UNIXTIME(u.addtime,'%y-%m-%d %H:%i') as addtime")->join("as u left join ".$this->_qz."users as ur on ur.id = u.userid")->where($map)->find();
        	$res_data['data'] = $result;
        	$res_data['msg'] = "加载成功";    
        }else{
            $res_data['msg'] = "读取状态返回错误";
            $res_data['status'] = 0;
        }
    	$this->_return($res_data);
    }
    //报修订单-删除接口
    // public function delete(){
    //     $id = $this->_data['id'];//删除的对应的维修订单的ID
    // }
    //报修处理完成接口
    public function doWell(){
    	$id = $this->_data['id'];//当前报修订单的ID
        if (empty($id)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $map = array();
        $map['id'] = $id;
        $true = M("ReportRepair")->field("status")->where($map)->find();
        if ($true['status'] != 1 && $true['status'] == 5) {
            $res_data['status'] = 0;
            $res_data['msg'] = "你已经点击了误报,不能再点击处理完成";
            $this->_return($res_data);
        }
            if ($true['status'] == 3) {
                $res_data['status'] = 0;
                $res_data['msg'] = "你已经点击了处理完成,不能重复点击";
                $this->_return($res_data);
            }
        	$map = array();
        	$map['id'] = $id;
        	$data['status'] = 3;
        	//处理情况: 0_待处理（默认）, 1_已指派维修员, 2_正在处理, 3_已处理, 4_重复报修, 5_误报
        	$result = M("ReportRepair")->where($map)->save($data);
            if ($result) {    
                $res_data['status'] = 1;
                $res_data['msg'] = "处理成功";
            }else{
                $res_data['status'] = 0;
                $res_data['msg'] = "你已经处理过了";
            }
    	$this->_return($res_data);
    }
    //误报接口
    public function error(){
    	$id = $this->_data['id'];//当前报修订单的ID
        if (empty($id)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        //判断前端点击事件
        $map = array();
        $map['id'] = $id;
        $true = M("ReportRepair")->field("status")->where($map)->find();
        if ($true['status'] != 1 && $true['status'] == 3) {
            $res_data['status'] = 0;
            $res_data['msg'] = "你已经点击了处理完成,不能再点击误报";
            $this->_return($res_data);
            }   
            if ($true['status'] == 5) {
                $res_data['status'] = 0;
                $res_data['msg'] = "你已经点击了误报，不能重复点击";
                $this->_return($res_data);
            }
        	$map = array();
        	$map['id'] = $id;
        	$data['status'] = 5;
        	//处理情况: 0_待处理（默认）, 1_已指派维修员, 2_正在处理, 3_已处理, 4_重复报修, 5_误报
        	$result = M("ReportRepair")->where($map)->save($data);
            if ($result) {    
                $res_data['status'] = 1;
                $res_data['msg'] = "处理成功";
            }else{
                $res_data['status'] = 0;
                $res_data['msg'] = "你已经误报过了";
            }
    	$this->_return($res_data);
    }

}