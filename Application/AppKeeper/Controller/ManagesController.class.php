<?php
namespace AppKeeper\Controller;
use AppKeeper\Common\AppKeeperController;

class ManagesController extends AppKeeperController {

    function _initialize(){
		parent::_initialize();
		
	}
    //管理员端-个人详情接口
    // 注: manageid 实际应该为keeperid，因前期写入代码误写manageid，改动造成前后端太大变动，故一错再错
    public function Manage(){
        $manageid = $this->_data['manageid'];//当前登录的管理员ID
        if (empty($manageid)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $map = array();
        $map['id'] = $manageid;
        $map['status'] = 1;//状态，0_不启用，1_启用
        $result = M("Keeper")->field("phone,username")->where($map)->find();
        if(empty($result)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $res_data['status'] = 1;
        $res_data['data'] = $result;
        $res_data['msg'] = "加载成功";
        $this->_return($res_data);
    }
    //  //修改头像接口
    // public function updatePortrait(){
    //     $manageid = $this->_data['manageid'];//当前登录的管理员ID
    //     if (empty($userid)) {
    //         $res_data['status'] = 0;
    //         $res_data['msg'] = "非法操作";
    //         $this->_return($res_data);
    //         return;
    //     }     
    //     if($result){
    //         $res_data['status'] = 1;//状态：0_不启用,1_启用
    //         $res_data['msg'] = "修改成功";
    //     }else{
    //         $res_data['msg'] = "修改失败";
    //     }
    //     $this->_return($res_data);
    // }
    //修改性别接口
    // public function updateSex(){
    //     $manageid = $this->_data['manageid'];//当前登录的管理员ID
    //     $newSex = $this->_data['sex'];//前端修改的性别
    //     if (empty($userid) || empty($sex)) {
    //         $res_data['status'] = 0;
    //         $res_data['msg'] = "非法操作";
    //         $this->_return($res_data);
    //         return;
    //     }
    //     $newSexData['sex'] = $newSex;
    //     $model = M("Users");
    //     $map = array();
    //     $map['userid'] = $userid;
    //     $result = $model->field('sex')->where($map)->save($newSexData);    
    //     if($result){
    //         $res_data['status'] = 1;
    //         $res_data['msg'] = "性别修改成功";
    //     }else{
    //         $res_data['status'] = 0;
    //         $res_data['msg'] = "性别修改失败";
    //     }
    //     $this->_return($res_data);
    // }
    //修改昵称接口
    public function updateName(){
        $manageid = $this->_data['manageid'];//当前登录的管理员ID
        $newName = $this->_data['username'];//前端修改的昵称
        if (empty($manageid) || empty($newName)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $newNameData['username'] = $newName;
        $model = M("Keeper");
        $map = array();
        $map['username'] = $newName;
        $result = M("Keeper")->field("username")->where($map)->find();
        if ($result) {
            $res_data['status'] = 0;
            $res_data['msg'] = "用户名已被占用";
        }else{
            $map = array();
            $map['id'] = $manageid;
            $data = $model->where($map)->save($newNameData);    
            if($data){
                $res_data['status'] = 1;
                $res_data['msg'] = "昵称修改成功";
            }else{
                $res_data['status'] = 0;
                $res_data['msg'] = "昵称修改失败";
            }
        }
        $this->_return($res_data);
    }
    //修改手机的接口
    public function updatePhone(){
        $manageid = $this->_data['manageid'];//当前登录的管理员ID
        $newPhone = $this->_data['newPhone'];//修改的新的手机号
        if (empty($manageid) || empty($newPhone)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $newPhoneData['phone'] = $newPhone;
        $model = M("Keeper");
        $map = array();
        $map['id'] = $manageid;        
        $result = $model->where($map)->save($newPhoneData);      
        if($result){
            $res_data['status'] = 1;
            $res_data['msg'] = "修改成功";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "修改失败";
        }
        $this->_return($res_data);
    }

}