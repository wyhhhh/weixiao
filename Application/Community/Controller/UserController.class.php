<?php
namespace Community\Controller;
use Community\Common\ManageController;
use Community\Common\ExcelReader;
use Community\Common\OLERead;
use Think\Controller;
class UserController extends IndexController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->_name='manage_ip';
	}
    public function index(){ 
        $this->display("index");
    }
	public function _before_getList(){
        $map = array();
        $fields = "";
        //搜索设置
        $arrJoins[] = " as mip left join ".$this->_qz."manages_community as mc on mc.id = mip.adminid ";
        $arrJoins[] = "left join ".$this->_qz."manage_role_community as mrc on mrc.id = mc.roleid ";
        $fields = "mrc.name,mip.id,mip.ip,mip.username,mip.number,if(mip.status=1,'启用','禁用') as status";
        $data['arrJoins'] = $arrJoins;
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