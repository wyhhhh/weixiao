<?php
namespace Manage\Model;
use Think\Model;

class UsersModel extends Model { 
	//自动验证
	protected $_validate = array(
		array('real_name','require','姓名',1),
		array('phone','number','手机号必须为数字',1),
		array('phone','/^1[3|4|5|8][0-9]\d{4,8}$/','手机号码错误！','0','regex',1),
		array('id_card','/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/','身份证格式错误')
    );

    //自动完成
    protected $_auto = array (
 		array('addtime',time,3,'function'),//3表示新增数据，更新数据都进行时间的处理，
    );

}