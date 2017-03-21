<?php
namespace AppCommunityRepair\Controller;
use AppCommunityRepair\Common\AppCommunityRepairController;

class InfoController extends AppCommunityRepairController {

    function _initialize(){
		parent::_initialize();
		
	}
	// //通知列表接口
	// public function infoList(){
	// 	$map = array();
	// 	$map['type'] = 2;
	// 	//消息类型:1_物业管理通知DD维修,2_物业通知社区服务人员, 3_系统推送消息到用户/住户, 4_网站反馈结果通知消息
	// 	$count = M("Information")->count($mod_pk);
	// 	$pagesize = 6;
	// 	$pager = new \Think\Page($count,$pagesize);
	// 	$list = M("Information")->field("id,title,from_UNIXTIME(addtime,'%y-%m-%d %H:%i') as addtime")->order("id asc")->limit($pager->firstRow.','.$pager->listRows)->where($map)->select();
	// 	$res_data['data'] = $list;
	// 	$res_data['status'] = 1;
	// 	$res_data['msg'] = "列表加载成功";
	// 	$this->_return($res_data);
	// }
	// //通知详情接口
	// public function infoDetail(){
	// 	$id = $this->_data['id'];//当前通知的ID
	// 	$map = array();
	// 	$map['id'] = $id;
	// 	$result = M("Information")->field("title,content,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime")->where($map)->find();
	// 	$res_data['data'] = $result;
	// 	$res_data['status'] = 1;
	// 	$res_data['msg'] = "详情加载成功";
	// 	$this->_return($res_data);
	// }

}