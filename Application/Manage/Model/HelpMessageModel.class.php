<?php
namespace Manage\Model;
use Think\Model;

class HelpMessageModel extends Model { 
	//自动验证
	protected $_validate = array(
		array('name','require','标题不能为空',1),
		array('name','','标题已存在',1,'unique'),
		array('sort','number','排序必须为数字',1),
		array('content','require','请填写内容',1),
		array('isshow','require','请选择显示状态',1),
    );  
    //自动完成
    protected $_auto = array (
    	array('addtime',time,3,'function'),//3表示新增数据，更新数据都进行时间的处理，
    );

}