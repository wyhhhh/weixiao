<?php
namespace AppKeeper\Controller;
use AppKeeper\Common\AppKeeperController;

class IndexController extends AppKeeperController {

    function _initialize(){
		parent::_initialize();
		
	}

    //管理员首页
    public function index(){
    	// $ret = $this->verifyUser("Users",1);
    	// p("123<br/>".$ret);
    	// return;

    	$res_data['status'] = 1;
        $res_data['data'] = "";
    	$res_data['msg'] = "";
    	$this->_return($res_data);
    }


}