<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class HelpmessageController extends AppUserController {

    function _initialize(){
		parent::_initialize();
		
	}
	//帮助接口
	public function help(){
		$map = array();
		$map['isshow'] = 1;//是否显示: 0_不显示, 1_显示（默认）
		$count = M("HelpMessage")->count($mod_pk);
		$pagesize = 6;
		$pager = new \Think\Page($count,$pagesize);
		$helpdata = M("HelpMessage")->field("id,name")->where($map)->order("id asc")->limit($pager->firstRow.','.$pager->listRows)->select();
		$res_data['status'] = 1;
		$res_data['data'] = $helpdata;
		$res_data['msg'] = "加载成功";
		$this->_return($res_data);
	}
	//帮助详情接口
	public function helpDetail(){
		$id = $this->_data['id'];//帮助页面的ID
		$map = array();
		$map['id'] = $id;
		$map['isshow'] = 1;//是否显示: 0_不显示, 1_显示（默认）
		$message = M("HelpMessage")->field("name,content")->where($map)->find();
		if (empty($message)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "没有数据";
			$this->_return($res_data);
		}
		$res_data['data'] = $message;
		$res_data['status'] = 1;
		$res_data['msg'] = "加载成功";
		$this->_return($res_data);
	}
}