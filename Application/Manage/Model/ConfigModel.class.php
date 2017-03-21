<?php
namespace Manage\Model;
use Think\Model;

class ConfigModel extends Model { 
	//自动验证
	protected $_validate = array(
		array('name','require','社区名称不能为空',1),
		array('keyword','require','搜索关键字不能为空',1),
		array('description','require','网站描述不能为空',1),
		array('hie_interval','require','催促维修等待时间间隔不能为空',1),
		array('hie_interval','number','催促维修等待时间间隔必须为数字',1),
		array('mail','require','网站邮件地址不能为空',1),
		array('mail','email','请输入正确的邮箱格式',1),
		array('phone','require','网站联系电话不能为空',1),
    );
    
    //自动完成
    protected $_auto = array (
 		//
    );

}