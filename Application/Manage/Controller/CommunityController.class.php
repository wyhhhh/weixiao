<?php
namespace Manage\Controller;
use Manage\Common\ManageController;
use Think\Controller;
class CommunityController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
	}

	public function _before_getList(){
        $map = array();
        $fields = "";
        //搜索设置
        $name = I('get.name');
        if ($name != "") {
            $map['name'] = array('like',"%$name%");
        } 
        $fields = "id,name,town,address,addtime";
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