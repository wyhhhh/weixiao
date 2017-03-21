<?php
namespace AppRepairUser\Controller;
use AppRepairUser\Common\AppRepairUserController;

class IndexController extends AppRepairUserController {

    function _initialize(){
		parent::_initialize();
		
	}

    //服务人员端首页
    public function index(){
    	$res_data['status'] = 1;
        $res_data['data'] = "";
    	$res_data['msg'] = "";
    	$this->_return($res_data);
    }


}