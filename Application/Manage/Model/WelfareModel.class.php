<?php
namespace Manage\Model;
use Think\Model;

class WelfareModel extends Model { 
	//自动验证
	protected $_validate = array(
		array('title','require','标题不能为空',1),
		array('sort','number','排序必须为数字',1),
		array('isshow','number','请选择是否显示',1),
    );
    
    //自动完成
    protected $_auto = array (
 
    );

}