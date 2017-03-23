<?php
namespace Community\Controller;
use Community\Common\ManageController;
use Community\Common\ExcelReader;
use Community\Common\OLERead;
use Think\Controller;
class RootController extends IndexController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->_name='manages_community';
	}
    public function index(){ 
        $this->display("index");
    }
	public function _before_getList(){
        $map = array();
        $fields = "";
        //搜索设置

        $fields = "id,username,if(status=1,'启用','禁用') as status,logintime,loginip,logincount";
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
    public function _before_field_edit($data){
        $data['fieldname']='status';
        $data['range']='232';
        return $data;
    }
    

}