<?php
namespace AppCommunityRepair\Controller;
use AppCommunityRepair\Common\AppCommunityRepairController;

class IndexController extends AppCommunityRepairController {

    function _initialize(){
		parent::_initialize();
		
	}

    //社区维修人员端首页
    public function index(){
    	$res_data['status'] = 1;
        $res_data['data'] = "";
    	$res_data['msg'] = "";
    	$this->_return($res_data);
    }


}