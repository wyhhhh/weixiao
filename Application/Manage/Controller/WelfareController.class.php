<?php
namespace Manage\Controller;
use Manage\Common\ManageController;
use Think\Controller;
class WelfareController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
	}

	public function _before_getList(){
        $map = array();
        $fields = "";
        //搜索设置
        $title = I('get.title');
        if ($title != "") {
            $map['title'] = array('like',"%$title%");
        } 
        $fields = "id,title,content,images,addtime,browse_count,praise_count,if(isshow = 1,'显示','不显示') as isshow";
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

    //入库之前
    public function _before_insert($data){
        $data['addtime'] = time();
        $data['browse_count'] = 0;
        $data['praise_count'] = 0;
        $data['type'] = 0;//type 发布类型(0_平台发布，1_社区发布)
        $data['manageid'] = $this->_manage['id'];
        $data['managename'] = $this->_manage['username'];
        $filename = array("images");
        //允许文件后缀，和前端设置对应
        $exts = array('jpg', 'gif', 'png', 'jpeg');
        $filedata = $this->_uploadFile($filename,$exts);
        if ($filedata)
        {
            $data = array_merge($filedata,$data);
        }
        else
        {
            foreach ($filename as $key => $value) {
                unset($data[$value]);
            }
        }
        return $data;
    }
    //编辑入库之前
    public  function _before_update($data){
        $filename = array("images");
        //允许文件后缀，和前端设置对应
        $exts = array('jpg', 'gif', 'png', 'jpeg');
        $filedata = $this->_uploadFile($filename,$exts);
        if ($filedata)
        {
            $data = array_merge($filedata,$data);
        }
        else
        {
            foreach ($filename as $key => $value) {
                unset($data[$value]);
            }
        }
        return $data;
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

    //加载编辑页面之前
    public function _before_edit_data($data){
        $id = $data['id'];
        $welfare = M("Welfare")->find($id);
        $communityids = $welfare['communityids'];
        $communityids = explode(',',$communityids);
        //查询有效的社区
        $map = array();
        $map['isusage'] = 1;//isusage 是否启用: 0_不启用, 1_启用（默认）
        $fields = "name as text,id";
        $list = $this->_getCommunityList($map,$fields,$communityids);
        $this->assign("navigations",json_encode($list)); 
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

}