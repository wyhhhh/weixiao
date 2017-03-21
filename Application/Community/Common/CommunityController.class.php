<?php
namespace Community\Common;
use Common\Common\SystemController;
class CommunityController extends SystemController{
    
	function _initialize()
  { 
    parent::_initialize();

    $session_userid = session('manageid');
        $temp = $this->_name.":".$this->_method;

        //不需要验证权限操作
        $arr = array();
        $arr[] = 'Index:index';//登录页面
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
        //获取当前登录 用户权限 
        $map = array(); 
        $map['m.id'] = $session_userid;
        //物业后台管理平台使用 ManagesCommunity 
        $info = M('ManagesCommunity')->field('m.id,m.status,mrc.name as rolename,m.username,mrc.permissions,mrc.communityid')->join(" as m inner join ".$this->_qz."manage_role_community as mrc on mrc.id = m.roleid ")->where($map)->find();
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
          $arr[] = 'ManagesCommunity:test';//测试
          $this->_check_qx($info,$arr,"1");
        }
  }
	
	
}