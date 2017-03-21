<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class LoginController extends AppUserController {

    function _initialize(){
		parent::_initialize();
	}

    //登录页面
    public function login(){
       //验证 登录
        $mod = M("Users");
        $putPhone = $this->_data['phone'];
        $putPassword = $this->_data['password'];
        if (empty($putPhone) || empty($putPassword)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $map = array();
        $map['phone'] = $putPhone;
        // $map['password'] = $putPassword;
        // $map['status'] = 1;//状态：0_不启用,1_启用 === (这个好像没啥用了，被sequestration替代了)
        $result = $mod ->field('id,password,pet_name,head_portrait,token,house_id,sequestration')->where($map)->find();
        //返回数据拼接、判断
        if ($result) {
            $userId = $result['id'];//用户id
            $token = $result['token'];//用户token
            $hashedPassword = $result['password'];
            $sequestration = $result['sequestration'];//状态：0_不启用,1_启用
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
            //找到用户当前定位的社区 begin
            $house_id = $result['house_id'];//用户当前默认定位的社区房屋
            if(empty($house_id)){
                $res_data['status'] = 0;
                $res_data['msg'] = "登陆失败, 当前用户没有默认社区！";
                $this->_return($res_data);
            }
            $map_h['id'] = $house_id;
            $house = M("House")->field("communityid")->where($map_h)->find();
            $communityid = $house['communityid'];//用户当前定位的社区
            $res = check_str($putPassword,$hashedPassword);//去验证
            //密码验证
            if (!$res){
                $res_data['status'] = 0;
                $res_data['msg'] = "密码错误！";
                $this->_return($res_data);
            }
            //号被封
            if ($sequestration != 1){
                $map_limit['userid'] = $userId;
                $map_limit['object_type'] = 1;//封号对象: 1_业主, 2_服务人员, 3_社区维修人员
                $map_limit['type'] = array("neq","3");//封号类型: 1_按时间封号, 2_永久封号, 3_解封
                $result_limit = M("Sequestration") ->field('id,type,deadline_time,reason')->where($map_limit)->order('addtime desc')->find();
                // p(M("Sequestration") ->getlastsql());
                // return;
                $type = $result_limit['type'];
                $deadline_time = $result_limit['deadline_time'];
                $reason = $result_limit['reason'];
                if($type == 1){
                    //按时间封号，查看一下是否可以解封了
                    if($deadline_time <= time()){
                        $map_s['userid'] = $userId;
                        $map_s['object_type'] = 1;
                        $map_s['type'] = 3;
                        $map_s['reason'] = "封号时间到, 系统自动解封";
                        $map_s['addtime'] = time();
                        $map_s['managename'] = "系统自动操作";
                        $map_s['manageid'] = 0;
                        //启动解封
                        $map_user['sequestration'] = 1;//账号状态: 0_封号, 1_正常（默认）
                        M("Users")->where("id=".$userId)->save($map_user);
                        //解封做记录
                        M("Sequestration") ->add($map_s);   
                    }else{
                        $res_data['status'] = 0;
                        $res_data['msg'] = "暂时封号, 封号原因:".$reason;
                        $res_data['fh_status'] = 1;// 1 封号
                        //封装data数据
                        $data['type'] = $type;//封号类型
                        $data['deadline_time'] = date("Y-m-d H:i",$deadline_time);//截止时间
                        $data['reason'] = $reason;//原因
                        $res_data['data'] = $data;
                        $this->_return($res_data);
                    }
                }else if($type == 2){
                    $res_data['status'] = 0;
                    $res_data['msg'] = "永久封号, 封号原因:".$reason;
                    $res_data['fh_status'] = 1;// 1 封号
                    //封装data数据
                    $data['type'] = $type;//封号类型
                    $data['deadline_time'] = "永久封号";//截止时间
                    $data['reason'] = $reason;//原因
                    $res_data['data'] = $data;
                    $this->_return($res_data);
                }else{
                    //这个是不明情况的封号，没有封号记录，用户状态却是被封号
                    $res_data['status'] = 0;
                    $res_data['msg'] = "封号, 若有疑问请联系管理员!";
                    $res_data['fh_status'] = 1;// 1 封号
                    //封装data数据
                    $data['type'] = $type;//封号类型
                    $data['deadline_time'] = date("Y-m-d H:i",$deadline_time);//截止时间
                    $data['reason'] = $reason;//原因
                    $res_data['data'] = $data;
                    $this->_return($res_data);
                }
            }
            //找到用户当前定位的社区 end
            if(empty($token)){
                // $userId = $result['id'];//用户id
                $name = $result['pet_name'];//昵称
                $portraitUri = $result['head_portrait'];//头像
                $token = $this->get_token($userId,$name,$portraitUri);
                //更新token到数据库
                M("Users")->where($map)->setField("token",$token);
            }
            $res_data['status'] = 1;
            $res_data['data']['userid'] = $userId;
            $res_data['data']['communityid'] = $communityid;
            $res_data['data']['token'] = $token;
            $res_data['msg'] = "登录成功";
            
            //这个是返回的是 当前请求的Host头 begin
            $ip_host = $this->init_ip();
            $res_data['data']['ip_host'] = $ip_host;
            //end

            //登录记录日志
            $ip = get_client_ip();
            // $ip = "125.85.66.89";
            $res = file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=$ip");
            $place = json_decode($res,true);
            $dataplace = $place['country'].$place['province'].$place['district'].$place['isp'].$place['type'].$place['desc'];
            $map_save = array();
            $map_save['ip'] = $ip;
            $map_save['place'] = $dataplace;
            $map_save['logintime'] = time();
            $map_save['loginuser'] = $userId;
            $map_save['object'] = 1;//记录对象: 1_业主, 2_DD维修, 3_社区维修, 4_APP管理员, 5_物业管理员, 6_平台管理员
            M("LoginLog")->add($map_save);
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "登陆失败, 账号不存在！";
        }
        $this->_return($res_data);
    }

    //忘记密码接口
    public function reset(){
        $phone = $this->_data['phone'];//当前输入的手机号
        $verify = $this->_data['verify'];//接受到的验证码
        $newPassword = $this->_data['newPassword'];
        //此处省略无数字
        //如果验证码输入正确。进行下面的操作（没写验证）
        $data['password'] = md5($newPassword);
        $map = array();
        $map['phone'] = $phone;
        $result = M("Users")->where($map)->save($data);
        if ($result) {
            $res_data['status'] = 1;
            $res_data['msg'] = "密码修改成功";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "密码修改失败";
        }
        $this->_return($res_data);
    }
}