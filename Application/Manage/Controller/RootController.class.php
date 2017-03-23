<?php
namespace Manage\Controller;
use Manage\Common\ManageController;
use Think\Controller;
class RootController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->_name='manages_community';
	}

	public function _before_getList(){
        $map = array();
        $fields = "";
        //搜索设置

        $fields = "id,username,status,logintime,loginip,logincount";
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