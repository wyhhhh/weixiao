<?php
namespace AppCommunityRepair\Controller;
use AppCommunityRepair\Common\AppCommunityRepairController;

class CommunityRepairController extends AppCommunityRepairController {

    function _initialize(){
		parent::_initialize();
	}
	//社区维修人员详情接口
	public function CommunityRepair(){
		$CommunityRepairid = $this->_data['CommunityRepairid'];//当前登录的社区维修人员的ID
		$communityid = $this->_data['communityid'];//当前登录社区维修人员的社区ID
		if (empty($CommunityRepairid) || empty($communityid)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "非法操作";
			$this->_return($res_data);
		}
		$map = array();
		$map['id'] = $CommunityRepairid;
		$map['communityid'] = $communityid;
		$map['isshow'] = 1;//是否启用: 0_不启用, 1_启用（默认）
		$map['status'] = 2;//审核状态: 0_待审核（默认）, 1_初审通过, 2_复审通过
		$map['sequestration'] = 1;//账号状态: 0_封号, 1_正常（默认）
		$result = M("CommunityRepair")->field("phone,name,pet_name,sex,head_portrait,birthday")->where($map)->find();
		if (!empty($result)) {
			$res_data['status'] = 1;
			$res_data['data'] = $result;
			$res_data['msg'] = "个人详情加载成功";
		}else{
			$res_data['status'] = 0;
			$res_data['msg'] = "加载失败";
		}
		$this->_return($res_data);
	}
	//上传头像接口
    public function updatePortrait(){
        $CommunityRepairid = $this->_data['CommunityRepairid'];//当前用户id  
        $baseImages = $this->_data['baseImages'];//修改上传的图片base64的图片
        if (empty($CommunityRepairid)) {
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
        $map['id'] = $CommunityRepairid;
        $map['status'] = 1;//1_启用,0_不启用
        $map['sequestration'] = 1;//1_正常,0_封号
        $map['isshow'] = 1;//是否启用: 0_不启用, 1_启用（默认）
        $imagesUrl['head_portrait'] = $url;
        $result = M("CommunityRepair")->where($map)->save($imagesUrl);
        if ($result) {
            $res_data['status'] = 1;
            $res_data['msg'] = "头像修改成功！";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "修改失败";
        }
        $this->_return($res_data);
    }
	//社区维修人员修改昵称接口
	public function updatePetname(){
		$CommunityRepairid = $this->_data['CommunityRepairid'];//当前登录的社区维修人员的ID
		if (empty($CommunityRepairid)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "非法操作";
			$this->_return($res_data);
		}
		$newName = $this->_data['newName'];//修改的新的昵称
		$data['pet_name'] = $newName;
		$map = array();
		$map['id'] = $CommunityRepairid;
		$result = M("CommunityRepair")->where($map)->save($data);
		if ($result) {
			$res_data['status'] = 1;
			$res_data['data'] = $result;
			$res_data['msg'] = "昵称修改成功";	
		}else{
			$res_data['status'] = 0;
			$res_data['msg'] = "修改失败";
		}
		$this->_return($res_data);
	}
	//社区维修人员修改性别接口
	public function updateSex(){
		$CommunityRepairid = $this->_data['CommunityRepairid'];////当前登录的社区维修人员的ID
		$newSex = $this->_data['newSex'];//修改的性别，性别:0_女, 1_男, 2_保密（默认）,
		if (empty($CommunityRepairid)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "非法操作";
			$this->_return($res_data);
		}
		$data['sex'] = $newSex;
		$map = array();
		$map['id'] = $CommunityRepairid;
		$result = M("CommunityRepair")->where($map)->save($data);
		if ($result) {
			$res_data['status'] = 1;
			$res_data['data'] = $result;
			$res_data['msg'] = "性别修改成功";	
		}else{
			$res_data['status'] = 0;
			$res_data['msg'] = "修改失败";
		}
		$this->_return($res_data);
	}
	//获取新手机的验证码
	public function getVerify(){
		$inputPhone = $this->_data['inputPhone'];//前端获取的新的手机号
		$verify = $this->_data['verify'];
		//调用发送验证码第三方接口,如果验证正确，执行下一步
		$res_data['data']['istrue'] = 1;//1_表示验证成功
		$res_data['status'] = 1;
		$this->_return($res_data);
	}
	//社区维修人员修改手机号获取当前手机号接口
	public function pet_phone(){
		$CommunityRepairid = $this->_data['CommunityRepairid'];////当前登录的社区维修人员的ID
		if (empty($CommunityRepairid)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "非法操作";
			$this->_return($res_data);
		}
		$map = array();
		$map['id'] = $CommunityRepairid; //当前登录的社区维修人员的ID
		$data1 = M("CommunityRepair")->where($map)->find();
		if (empty($data1)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "没有数据";
			$this->_return($res_data);
		}
		$res_data['data'] = $data1['phone'];//当前报修人员的手机号
		$res_data['status'] = 1;
		$res_data['msg'] = "手机号加载成功";
		$this->_return($res_data);
	}
	//社区维修人员修改手机号-确认-接口
	public function updatePhone(){
		$istrue = $this->_data['istrue'];//获取验证码的结果，
		$CommunityRepairid = $this->_data['CommunityRepairid'];////当前登录的社区维修人员的ID
		$newPhone = $this->_data['newPhone'];//修改的手机号
		if ($istrue == 1) {	
			if (empty($CommunityRepairid)) {
				$res_data['status'] = 0;
				$res_data['msg'] = "非法操作";
				$this->_return($res_data);
			}
			$data['phone'] = $newPhone;
			$map = array();
			$map['id'] = $CommunityRepairid;
			$result = M("CommunityRepair")->where($map)->save($data);
			if (empty($result)) {
				$res_data['status'] = 0;
				$res_data['msg'] = "修改失败";
				$this->_return($res_data);		
			}
			$res_data['status'] = 1;
			$res_data['msg'] = "手机号修改成功";
		}
		$this->_return($res_data);
	}
	


}