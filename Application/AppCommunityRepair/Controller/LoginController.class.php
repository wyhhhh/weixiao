<?php
namespace AppCommunityRepair\Controller;
use AppCommunityRepair\Common\AppCommunityRepairController;

class LoginController extends AppCommunityRepairController {

    function _initialize(){
		parent::_initialize();
	}

    //登录页面
    public function login(){
       //验证 登录
        $mod = M("CommunityRepair");
        $putPhone = $this->_data['phone'];
        $putPassword = $this->_data['password'];
        if (empty($putPhone) || empty($putPassword)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $map = array();
        $map['phone'] = $putPhone;
        $result = $mod ->field('id,password,pet_name,head_portrait,status,communityid')->where($map)->find();
        //返回数据拼接、判断
        if ($result) {
            $userId = $result['id'];
            $hashedPassword = $result['password'];
            $status = $result['status'];//状态：0_不启用,1_启用
            $res = check_str($putPassword,$hashedPassword);//去验证
            //用户头像验证和替换
            $head_portrait = $result['head_portrait'];//头像
            if(empty($head_portrait)){
                $head_portrait_config = $this->_config['default_headportrait'];
                if(!empty($head_portrait_config)){
                    $map_save['id'] = $keeperid;
                    $map_save['head_portrait'] = $head_portrait_config;
                    $mod ->where($map)->save($map_save);
                }
            }
            //密码验证
            if (!$res){
                $res_data['status'] = 0;
                $res_data['msg'] = "密码错误！";
                $this->_return($res_data);
            }
            // status 审核状态: 0_待审核（默认）, 1_初审通过, 2_复审通过
            if ($status != 2){
                $res_data['status'] = 0;
                $res_data['msg'] = "你的账号被禁止登录, 请联系管理员！";
                $this->_return($res_data);
            }
            $res_data['status'] = 1;
            $res_data['data']['userid'] = $userId;
            $res_data['data']['communityid'] = $result['communityid'];
            $res_data['msg'] = "登录成功";

            //这个是返回的是 当前请求的Host头 begin
            $ip_host = $this->init_ip();
            $res_data['data']['ip_host'] = $ip_host;
            //end

            //登录记录日志
            $map_save = array();
            $map_save['ip'] = get_client_ip();
            // $map_save['place'] = "";//记录地点？怎么想的
            $map_save['logintime'] = time();
            $map_save['loginuser'] = $userId;
            $map_save['object'] = 3;//记录对象: 1_业主, 2_DD维修, 3_社区维修, 4_APP管理员, 5_物业管理员, 6_平台管理员
            M("LoginLog")->add($map_save);
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "登陆失败, 账号不存在！";
        }
        $this->_return($res_data);
    }

}