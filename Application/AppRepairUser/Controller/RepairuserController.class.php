<?php
namespace AppRepairUser\Controller;
use AppRepairUser\Common\AppRepairUserController;

class RepairuserController extends AppRepairUserController {

    function _initialize(){
		parent::_initialize();
		$this->_model = M("RepairUser");
	}
    //服务人员详细信息接口
    public function repairuser(){
        $repairuserid = $this->_data['repairuserid'];//当前服务人员的id
        if(empty($repairuserid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $this->_return($res_data);
        }
        $map = array();
        $map['id'] = $repairuserid;
        $map['status'] = 2; //审核状态: 0_待审核（默认）, 1_初审通过, 2_复审通过
        $map['isshow'] = 1;//是否启用: 0_不启用, 1_启用（默认）
        $map['sequestration'] = 1;//账号状态: 0_封号, 1_正常（默认）
        $userDetail = $this->_model->field("id,pet_name,head_portrait,from_UNIXTIME(birthday,'%Y-%m-%d %H:%i') as birthday,sex,phone,purse")->where($map)->find();
        if (empty($userDetail)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "没有数据";
            $this->_return($res_data);
        }
        $res_data['data'] = $userDetail;
        $res_data['status'] = 1;
        $res_data['msg'] = "加载成功";
        $this->_return($res_data);
    }
    //修改头像接口
    public function updatePortrait(){
        $userid = $this->_data['repairuserid'];//当前服务人员的id  
        $baseImages = $this->_data['baseImages'];//修改上传的图片base64的图片
        if (empty($userid)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        if (empty($baseImages)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "请上传图片！";
            $this->_return($res_data);
        }
        $file_prefix = "tx_";
        $url = $this->upload_images($baseImages,$file_prefix);//获取新的图片地址
        $map = array();
        $map['id'] = $userid;
        $map['isshow'] = 1;//是否启用: 0_不启用, 1_启用（默认）
        $map['status'] = 2;//审核状态: 0_待审核（默认）, 1_初审通过, 2_复审通过
        $map['sequestration'] = 1;//1_正常,0_封号
        $imagesUrl['head_portrait'] = $url;
        $result = M("RepairUser")->where($map)->save($imagesUrl);
        if ($result) {
            $res_data['status'] = 1;
            $res_data['msg'] = "头像修改成功！";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "修改失败";
        }
        $this->_return($res_data);
    }
    //修改性别接口
    public function updateSex(){
        $repairuserid = $this->_data['repairuserid'];//当前服务人员的id
        $newSex = $this->_data['sex'];//前端修改的性别，性别:0_女, 1_男, 2_保密（默认）,
        if(empty($repairuserid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $newSexData['sex'] = $newSex;
        $map = array();
        $map['id'] = $repairuserid;
        $result = $this->_model->field('sex')->where($map)->save($newSexData);
        if (empty($result)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "修改失败";
            $this->_return($res_data);
        }   
        $res_data['status'] = 1;
        $res_data['msg'] = "性别修改成功";
        $this->_return($res_data);
    }
    //修改昵称接口
    public function updateName(){
        $repairuserid = $this->_data['repairuserid'];//当前服务人员的id
        $newName = $this->_data['pet_name'];//前端修改的昵称
        if(empty($repairuserid) || empty($newName)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $newNameData['pet_name'] = $newName;
        $map = array();
        $map['pet_name'] = $newName;
        $result = $this->_model->field("pet_name")->where($map)->find();
        if ($result) {
            $res_data['status'] = 0;
            $res_data['msg'] = "用户名已被占用";
        }else{
            $map = array();
            $map['id'] = $repairuserid;
            $data = $this->_model->field('pet_name')->where($map)->save($newNameData);    
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
    public function getverfiy(){
        $newPhone = $this->_data['newPhone'];
        //调用发送验证码的接口，给当前的新手机号发送一个验证码
        
        // $res_data['data']['newPhone'] = $newPhone;
        $res_data['status'] = 1;
        $res_data['msg'] = "验证码发送成功";
    }
    //修改手机的接口
    public function updatePhone(){
        $repairuserid = $this->_data['repairuserid'];//当前服务人员的id
        $newPhone = $this->_data['newPhone'];//获取新的手机号
        $verfiy = $this->_data['verfiy'];//获取得到的验证码
        if(empty($repairuserid) || empty($newPhone) || empty($verfiy)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        //郭茹验证码正确，则进行下面的操作 （没写）
        $newPhoneData['phone'] = $newPhone;
        $map = array();
        $map['status'] = 2; //审核状态: 0_待审核（默认）, 1_初审通过, 2_复审通过
        $map['isshow'] = 1;//是否启用: 0_不启用, 1_启用（默认）
        $map['sequestration'] = 1;//账号状态: 0_封号, 1_正常（默认）  
        $map['phone'] = $newPhone;
        $result = $this->_model->field("phone")->where($map)->find();     
        if ($result) {
             $res_data['status'] = 0;
             $res_data['msg'] = "手机号已存在,请重新输入";
         } else{
            $map = array();
            $map['status'] = 2; //审核状态: 0_待审核（默认）, 1_初审通过, 2_复审通过
            $map['isshow'] = 1;//是否启用: 0_不启用, 1_启用（默认）
            $map['sequestration'] = 1;//账号状态: 0_封号, 1_正常（默认）
            $map['id'] = $repairuserid; 
            $result2 = $this->_model->field("phone")->where($map)->save($newPhoneData);
            if($result2){
                $res_data['status'] = 1;
                $res_data['msg'] = "手机号修改成功";
            }else{
                $res_data['status'] = 0;
                $res_data['msg'] = "手机号修改失败";
            }
         }
        $this->_return($res_data);
    }

}