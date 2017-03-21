<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class FeedbackController extends AppUserController {

    function _initialize(){
		parent::_initialize();
		
	}

	//意见反馈接口
	public function feedback(){
		$userid = $this->_data['userid'];//当前用户的ID
		$content = $this->_data['content'];//反馈的内容
		$data['content'] = $content;
		$data['userid'] = $userid;
		$data['addtime'] = time();
		$map = array();
		$map['userid'] = $userid;
		if ($userid) {
			$result = M("Feedback")->where($map)->add($data);
			if ($result) {
				$res_data['status'] = 1;
				$res_data['msg'] = "提交成功";
			}else{
				$res_data['status'] = 0;
				$res_data['msg'] = "提交失败";
				$this->_return($res_data);
			}
		}else{
			$res_data['status'] = 0;
			$res_data['msg'] = "没有userid，用户是否登录";
		}
		$this->_return($res_data);
	}
}