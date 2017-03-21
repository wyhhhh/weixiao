<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class ReportController extends AppUserController {

    function _initialize(){
		parent::_initialize();
		
	}
	//我的报修单
	public function report(){
		$userid = $this->_data['userid'];//当前用户的ID
		$pageNumber = $this->_data['pageNumber'];//当前第几页
		if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
		$map = array();
		$map['userid'] = $userid;
		$count = M("ReportRepair")->count($mod_pk);
		$pagesize = 5;
		$pager = new \Think\Page($count,$pagesize);
		$report = M("ReportRepair")->field("id,description,place,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime")->where($map)->order("addtime desc")->limit(($pageNumber-1)*5,$pager->listRows)->select();
		if (empty($report)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "没有数据";
			$this->_return($res_data);
		}
		// foreach ($report as $key => $item) {
		// 	$res_data['data'][$key] = $item;
		// 	// $date = date('Y-m-d H:i:s',$item['addtime']);
		// 	// $res_data['data'][$key]['addtime'] = $date;
		// }
		$res_data['data'] = $report;
		$res_data['status'] = 1;
		$res_data['msg'] = "加载成功";
		$this->_return($res_data);
	}
	//我的报修详情页接口
	public function reportDetail(){
		$reportid = $this->_data['reportid'];//报修单ID
		$userid = $this->_data['userid'];//当前用户的ID
		$map = array();
		$map['id'] = $userid;
		$userdata = M("Users")->field("pet_name,head_portrait")->where($map)->find();
		if (empty($userdata)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "没有数据";
			$this->_return($res_data);
		}
		$map = array();
		$map['userid'] = $userid;
		$map['id'] = $reportid;
		$reportdata = M("ReportRepair")->field("description,images,place,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime,status")->where($map)->find();
		$images = $reportdata['images'];
		$images = json_decode($images,true);
		$reportdata['images'] = $images;
		$res_data['data'] = $reportdata;
		$res_data['data']['pet_name'] = $userdata['pet_name'];
		$res_data['data']['head_portrait'] = $userdata['head_portrait'];
		//处理情况: 0_待处理（默认）, 1_已指派维修员, 2_正在处理, 3_已处理, 4_重复报修, 5_误报
		if ($reportdata['status'] == 0) {
			$res_data['data']['status'] = "待处理";
		}elseif($reportdata['status'] == 1){
			$res_data['data']['status'] = "已经指派维修员";
		}elseif($reportdata['status'] == 2){
			$res_data['data']['status'] = "正在处理";
		}elseif($reportdata['status'] == 3){
			$res_data['data']['status'] = "已处理";
		}elseif($reportdata['status'] == 4){
			$res_data['data']['status'] = "重复报修";
		}elseif($reportdata['status'] == 5){
			$res_data['data']['status'] = "误报";
		}
		$res_data['status'] = 1;
		$res_data['msg'] = "加载成功";
		$this->_return($res_data);
	}
	//我的保修单删除接口
	public function delete(){
		$id = $this->_data['id'];//当前删除的ID
		// $userid = $this->_data['userid'];//当前登录的用户ID
		if (empty($id)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "非法操作！";
			$this->_return($res_data);
		}
		$map = array();
		$map['id'] = $id;
		$result = M("ReportRepair")->where($map)->delete();
		if ($result) {
			$res_data['status'] = 1;
			$res_data['msg'] = "删除成功!";
		}else{
			$res_data['status'] = 0;
			$res_data['msg'] = "删除失败!";
		}
		$this->_return($res_data);
	}
}