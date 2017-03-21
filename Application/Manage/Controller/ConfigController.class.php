<?php
namespace Manage\Controller;
use Manage\Common\ManageController;
use Think\Controller;
class ConfigController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
	}

    public function _before_edit_data($info) {
        $info = M("Config")->find();
        
        return $info;
    }

    public function _before_update($data){
    	$data['location'] = 2;
        $filename = array("default_headportrait");
        //允许文件后缀，和前端设置对应
        $exts = array('jpg', 'gif', 'png', 'jpeg');
        $filedata = $this->_uploadFile($filename,$exts);
        if ($filedata)
        {
            $data = array_merge($filedata,$data);
        }else{
            foreach ($filename as $key => $value) {
                unset($data[$value]);
            }
        }
        // //图片验证
        // if (empty($data['default_headportrait'])) {
        //     $this->ajaxReturn(1,'请上传图片!');
        // }
        return $data;
    }
    

	// public function _before_update($data){
 //        $addtime = time();
 //        $data['addtime'] = $addtime;
 //        $filename = array("head_portrait");
 //        //允许文件后缀，和前端设置对应
 //        $exts = array('jpg', 'gif', 'png', 'jpeg');
 //        $filedata = $this->_uploadFile($filename,$exts);
 //        if ($filedata)
 //        {
 //            $data = array_merge($filedata,$data);
 //        }
 //        else{
 //            foreach ($filename as $key => $value) {
 //                unset($data[$value]);
 //            }
 //        }
 //        return $data;
 //    } 
    

}