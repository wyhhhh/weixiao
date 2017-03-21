<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class ManagesController extends ManageController {

	function _initialize()
	{
		parent::_initialize(); 
	}

	public function _before_getList()
    {
        $map = array();
        $fields = "";
        $arrJoins = array();
        
        //搜索设置
        $username = I('get.username');
        if ($username != "") {
            $map['m.username'] = array('like',"%$username%");
        }
        $arrJoins[] = "as m left join ".$this->_qz."manage_role as mr on mr.id = m.roleid";

        $fields = "m.id,m.username,mr.name,if(m.status = 1,'启用','不启用') as status";
        $data['map'] = $map;
        $data['arrJoins'] = $arrJoins;
        $data['fields'] = $fields;
        return $data;
    }

    //加载添加页面之前
    public function _before_add(){
        //查询角色
		$rolelist = M('ManageRole')->field("id,name")->order('sort asc,id desc')->select();
		$this->assign('rolelist',$rolelist);        
    }

    //入库之前
    public function _before_insert($data){
    	//验证用户名是否重复
		$map = array();
		$map['username'] = $data['username'];
		// $map['id'] = array('neq',$data['id']);
		$info = M('Manages')->where($map)->find();
		if (!empty($info)) {
			$this->ajaxReturn(0,'用户名已存在！');
			return;
		}

    	$data['password'] = encrypt_str($data['password']);
		$data['status'] = 1;

        return $data;
    }

    //修改之前
    public function _before_edit(){
        //查询角色
		$rolelist = M('ManageRole')->field("id,name")->order('sort asc,id desc')->select();
		$this->assign('rolelist',$rolelist);        
    }

    function _before_update($data){
		 if(!empty($data['password'])){
			 $data['password']=encrypt_str($data['password']);
		 }
		 else
		 {
		 	unset($data['password']);
		 }

		 //验证用户名是否重复
		 $map = array();
		 $map['username'] = $data['username'];
		 $map['id'] = array('neq',$data['id']);
		 $info = M('Manages')->where($map)->find();
		 if (!empty($info)) {
			$this->ajaxReturn(0,'用户名已存在！');
		 }
		 return $data;
	 }

	//修改字段之前
    public function _before_field_edit()
    {
        $data = array();
        //范围多个用逗号隔开，如: 0,1 （不需要则为空）
        $data['range'] = "0,1";
        //条件 (除传过来的id外的附加条件，不需要则为空)
        $map = array();
        $data['map'] = $map;
        //字段名 (必须设置)
        $data['fieldname'] = "status";

        return $data;
    }

}