<?php
namespace Manage\Model;
use Think\Model;

class BannerModel extends Model { 
	//自动验证
	protected $_validate = array(
		array('title','require','标题不能为空',1),
		array('url','require','url必填',1),
		array('url','url','请输入正确的URL地址',1),
		array('sort','number','排序必须为数字',1),
    );
    
    //自动完成
    protected $_auto = array (
 
    );

}