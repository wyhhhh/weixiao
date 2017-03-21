<?php
namespace Manage\Model;
use Think\Model;

class ManageRoleModel extends Model { 
	//自动验证
	protected $_validate = array(
		array('name','require','管理组名称不能为空',1),
		array('name','','管理组名称已存在',1,'unique'),
		array('sort','number','排序必须为数字',1),
		// array('permissions','require','管理组权限必须设置',1)
    );
    
    //自动完成
    protected $_auto = array (
    	array('isdel','1'),//默认为可删除
    );
}