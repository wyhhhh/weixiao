<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class BannerController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
	}

	public function _before_getList(){
        $map = array();
        $fields = "";
        //搜索设置
        $name = I('get.name');
        if ($name != "") {
            $map['title'] = array('like',"%$name%");
        }
        
        $fields = "id,title,image,url,sort,if(isshow = 1,'显示','不显示') as isshow";
        $map['location'] = 2;     //广告位置: 1_物业/首页（默认）, 2_维修页面
        $data['map'] = $map;
        $data['fields'] = $fields;
        return $data;
    }
    //加载添加页面之前
    public function _before_add(){
        //查询有效的社区
        $map = array();
        $map['isusage'] = 1;//isusage 是否启用: 0_不启用, 1_启用（默认）
        $fields = "name as text,id";
        $list = $this->_getCommunityList($map,$fields);
        $this->assign("navigations",json_encode($list)); 
    }
      //获取有效社区
    public function _getCommunityList($map,$fields,$qx=""){
        $community = M("Community")->field($fields)->where($map)->order('id asc')->select();
        if(!empty($qx)){
            foreach ($community as $key => $value) {
                if(in_array($value['id'], $qx)){
                    $community[$key]['state']['selected'] = "true";
                }
            }
        }
        return $community;
    }

    public function _before_insert($data){
        // session('data',I('post.'));
        // exit();
        $data['location'] = 2;//广告位置: 1_物业/首页（默认）, 2_维修页面
        $filename = array("image");
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
        //图片验证
        if (empty($data['image'])) {
            $this->ajaxReturn(1,'请上传图片!');
        }
        return $data;
    }
    
    public function _before_update($data){
        $data['location'] = 2;
        $filename = array("image");
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
        return $data;
    }

    //修改字段之前
    public function _before_field_edit()
    {
        $data = array();
        //范围多个用逗号隔开，如: 0,1 （不需要则为空）
        $data['range'] = "0,1";
        //条件 (除传过来的id外的附加条件，不需要则为空)
        $map = array();
        $data['map'] = $map;
        //字段名 (必须设置)
        $data['fieldname'] = "isshow";

        return $data;
    }
    //修改之前，对数据的处理
    public function _before_edit_data($data){
        $id = $data['id'];
        $Banner = M("Banner")->find($id);
        $range = $Banner['range'];
        $range = explode(',',$range);
        //查询有效的社区
        $map = array();
        $map['isusage'] = 1;//isusage 是否启用: 0_不启用, 1_启用（默认）
        $fields = "name as text,id";
        $list = $this->_getCommunityList($map,$fields,$range);
        $this->assign("navigations",json_encode($list)); 
        return $data;
    }
    
}