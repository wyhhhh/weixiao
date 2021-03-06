<?php
namespace Manage\Common;
use Common\Common\SystemController;

class ManageController extends SystemController
{

	function _initialize()
	{ 
		parent::_initialize();

		$session_userid = session('manageid');
        $temp = $this->_name.":".$this->_method;

        //不需要验证权限操作
        $arr = array();
        $arr[0] = 'Index:index';//登录页面
        $arr[1] = 'PushTo:index';//接口页面
        //登录验证
        if (empty($session_userid) && !in_array($temp,$arr)) {
            if (IS_AJAX) { 
                $this->ajaxReturn(0,"登录超时，请重新登录！");
            }
            $this->error("请登录！",'/');
            die();
        }
        if (in_array($temp,$arr)) {
        	return;
        }
        //不需要验证权限操作
        $arr = array();
        $arr[0] = 'Index:index';//登录页面
        $arr[1] = 'PushTo:index';//接口页面
		//获取当前登录 用户权限 
        $map = array(); 
        $map['m.id'] = $session_userid;
        $info = M('Manages')->field('m.id,m.status,mr.name as rolename,m.username,mr.permissions')->join(" as m inner join ".$this->_qz."manage_role as mr on mr.id = m.roleid ")->where($map)->find();
        if (empty($info))
        {
            if (IS_AJAX)
            {
               $this->ajaxReturn(0,"你的账号被删除！");
            }
            $this->error('你的账号被删除！',"/");   
        }
        if ($info['status'] != 1)
        {
            if (IS_AJAX)
            {
               $this->ajaxReturn(0,"你的账号被禁用！");
            }
            $this->error('你的账号被禁用！',"/");
        }
        $this->assign('manage',$info);
        $this->_manage = $info;

        $arr[] = 'Index:main';//登录页面
        if (!in_array($temp,$arr)) {
	        //不需要验证权限操作
	        $arr = array();
	        $arr[] = 'Manages:test';//测试
	        $this->_check_qx($info,$arr,"1");
        }
	}

}