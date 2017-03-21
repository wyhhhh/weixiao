<?php
namespace AppKeeper\Common;
use Common\Common\AppController;
class AppKeeperController extends AppController{
	function _initialize(){
		parent::_initialize(); 
  //       $session_userid = session('companyuserid');
  //       //登录验证
  //       if (empty($session_userid)) {
  //           if (IS_AJAX) { 
  //               $this->ajaxReturn(0,"登录超时，请重新登录！");
  //           }
  //           $this->error("请登录！",U('Home/Users/login'));
  //           die();
  //       }
		// //获取权限
  //       $map = array(); 
  //       $map['u.id'] = $session_userid;
  //       $info = M('Users')->field('status,username,u.type,permissions')->join(" as u left join role as r on r.id = u.role_id ")->where($map)->find();

  //       if (empty($info)) {
  //           if (IS_AJAX) {
  //              $this->ajaxReturn(0,"你的账号被删除！");
  //           }
  //           $this->error('你的账号被删除！',"/");   
  //       }
  //       if ($info['status'] != 1) {
  //           if (IS_AJAX) {
  //              $this->ajaxReturn(0,"你的账号被禁用！");
  //           }
  //           $this->error('你的账号被禁用！',"/");
  //       }
  //       $this->assign('user',$info);

  //       //不需要验证权限操作
  //       $arr = array();
  //       $arr[] = 'Companys:index';//我的桌面
  //       $arr[] = 'Manages:desktop';//我的桌面
  //       $this->_check_qx($info,$arr);
        
  //       //查询导航
  //       $this->_nav($info);

  //       //查询消息提醒
  //       $this->_message($info);

	}

	
	
}