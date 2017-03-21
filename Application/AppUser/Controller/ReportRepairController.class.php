<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class ReportRepairController extends AppUserController {

    function _initialize(){
		parent::_initialize();
		$this->_model = M("ReportRepair");
	}
    //客户端发布报修
    public function publishReport(){
        $userid = $this->_data['userid'];//当前提交报修的用户ID
        $description = $this->_data['description'];//问题描述
        $place = $this->_data['place'];//问题出现的地点
        $images = $this->_data['images'];//上传的照片
        $addtime = time();
        if(empty($userid) || empty($description) || empty($place)){//验证合法性
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $map = array();
        $map['u.id'] = $userid;
        $community = M("Users")->field("h.communityid")->join("as u left join ".$this->_qz."house as h on h.id = u.house_id")->where($map)->find();
        if (empty($community)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "没有数据";
            $this->_return($res_data);
        }
        $file_prefix = "img_";
        $baseimage = $this->upload_images($images,$file_prefix);//获取图片的url
        $imgdata = explode(",",$baseimage);//转成数组
        $img = json_encode($imgdata);//数组转成json数组
        $data['communityid'] = $community['communityid'];//链接房屋表，写入当前用户所在的社区ID
        $data['userid'] = $userid;  
        $data['description'] = $description;
        $data['place'] = $place;
        $data['addtime'] = $addtime;
        $data['images'] = $img;
        $result = $this->_model->add($data);
        if ($result) {
            $res_data['status'] = 1;
            $res_data['msg'] = "提交成功";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "提交失败，请重新提交";
        }
        $this->_return($res_data);
    }











}