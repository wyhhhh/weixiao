<?php
namespace Community\Controller;
use Community\Common\ManageController;
use Community\Common\ExcelReader;
use Community\Common\OLERead;
use Think\Controller;
class LookController extends IndexController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->_name='manage_ip_use';
	}
    public function index(){ 
        $this->display("index");
    }
	public function _before_getList(){
        $map = array();
        $fields = "";
        //搜索设置
        $arrJoins[] = " as wth left join ".$this->_qz."manage_ip as wt on wt.id = wth.ipid ";
        $arrJoins[] = "left join ".$this->_qz."navigations as no on no.controller = wth.database ";
        $map['no.type']=3;
        $fields = "wth.id,if(wth.type=1,'查看',if(wth.type=2,'修改',if(wth.type=3,'更新',if(wth.type=4,'删除','显示错误')))) as type,no.name,wth.addtime,wt.ip,wt.username,wth.database";
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