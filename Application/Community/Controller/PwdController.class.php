<?php
namespace Community\Controller;
use Community\Common\ManageController;
use Community\Common\ExcelReader;
use Community\Common\OLERead;
use Think\Controller;
class PwdController extends IndexController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->_name='manage_role_community';
	}
    public function index(){ 
        $this->display("index");
    }
	public function _before_getList(){
        $map = array();
        $fields = "";
        //搜索设置

        $fields = "id,name,if(communityid=2,'微校账号',if(communityid=1,'微联盟账号','管理员账号')) as type";
        $data['map'] = $map;
        $data['fields'] = $fields;
        return $data;
    }

    public function _before_insert($data){
        $data["addtime"] = time();
        return $data;
    }

    public function test2(){

        if(IS_POST){
            session("ss","ASD");
            $this->ajaxReturn(1, ' ');
        }
        $this->display();
    }

    //删除判断方法预留
    // public function _before_delete($ids){
    //     $map = array("in",$ids);
        // M("Building")->where($map)->select();
    // }
    

}