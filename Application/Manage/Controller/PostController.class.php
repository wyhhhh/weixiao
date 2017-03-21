<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class PostController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->assignList("community","cname",$map = array(),$fields = "name");
	}

    public function _before_getList()
    {
        $key = I('get.key');
        
        if($key==1){
            // 主帖
            $arrJoins = array();
            $map = array();
            $fields = "";
             // 名称
            $name = I('get.name');
            if ($name != "") {
                $where['p.title']  = array('like',"%$name%");
                $where['uname']  = array('like',"%$name%");
                $where['_logic'] = 'or';
                $map['_complex'] = $where;
            }
            $status = I('get.status');
            if ($status !="") {
                $map['p.status'] = $status;
            }
            $communityid = I('get.communityid');
            if ($communityid !="") {
                $map['p.communityid'] = $communityid;
            }
            $map['p.toppid'] = array("exp","is Null");
            //字段列表
            $fields = "p.id,from_UNIXTIME(p.addtime,'%Y-%m-%d %H:%i') as addtime,c.name as cname,p.istop,p.title,p.content,p.status,p.sort,u.real_name as uname";
            //连表
            $arrJoins[] = "as p left join ".$this->_qz."users as u on u.id = p.userid";
            $arrJoins[] = "left join ".$this->_qz."community as c on c.id = p.communityid";
            $data['arrJoins'] = $arrJoins;
            $data['map'] = $map;
            $data['fields'] = $fields;
        }else if($key==2){
            $id = I('get.id');
            // 回复/评论
            $arrJoins = array();
            $map = array();
            $fields = "";
             // 名称
            $name = I('get.name');
            if ($name != "") {
                $where['p.title']  = array('like',"%$name%");
                $where['uname']  = array('like',"%$name%");
                $where['_logic'] = 'or';
                $map['_complex'] = $where;
            }
            if (!empty($id)) {
                $map['p.toppid'] = $id;//获取自动检索ID
            }else{
                $map['p.toppid'] = array("exp","is not NULL");//是否是回帖    
            }
            $status = I('get.status');
            if ($status !="") {
                $map['p.status'] = $status;
            }
            $communityid = I('get.communityid');
            if ($communityid !="") {
                $map['p.communityid'] = $communityid;
            }
            //审核状态(0_待审核,1_通过审核,-1_审核未通过，默认为通过审核)
            //字段列表
            $fields = "p.id,from_UNIXTIME(p.addtime,'%Y-%m-%d %H:%i') as addtime,c.name as cname,p2.title,p.content,p.status,u.real_name as uname";
            //连表 p2是帖子表
            $arrJoins[] = "as p left join ".$this->_qz."post as p2 on p.toppid = p2.id";
            $arrJoins[] = "left join ".$this->_qz."users as u on u.id = p.userid";
            $arrJoins[] = "left join ".$this->_qz."community as c on c.id = p.communityid";
            $data['arrJoins'] = $arrJoins;
            $data['map'] = $map;
            $data['fields'] = $fields;
        }
        return $data;
    }
    //帖子详情显示之前
    public function _before_detail_data($data){
        $map = array();
        $map['p.id'] = $data['id'];
        //链表查询
        $list = M("Post")->field("p.id,from_UNIXTIME(p.addtime,'%Y-%m-%d %H:%i') as addtime,p.title,p.content,case p.status when 0 then '待审核' when 1 then '已审核' when -1 then '审核未通过' end as status,u.real_name as uname")->join("as p left join ".$this->_qz."users as u on u.id = p.userid")->where($map)->find();
        $data = $list;
        return $data;
    }
     /**
    * 重写回帖详情查看
    */
    public function detailtwo() {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST)
        { 
            //
        } 
        else 
        {
            $id =I('get.'.$pk);// $this->_get($pk, 'intval');
            if (!$id) {
                $id = 1;
            }
            $info = $mod->find($id);
            if (empty($info)) {
                $this->show('你查询的数据不存在！','utf-8');
                die();
            } 
            //为编辑时有其他的表关联数据而打造
            if(method_exists($this, '_before_detailtwo_data'))
            {
               $info = $this->_before_detailtwo_data($info);
            }
            $this->assign('info', $info);
            if (IS_AJAX)
            {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            }
            else
            {
                if(method_exists($this, '_before_detailtwo')){
                   $templet = $this->_before_detailtwo();
                }
                if (empty($templet)) {
                    $this->display();
                }
                else
                {
                    $this->display($templet);
                }
            }
        }
    }
    //显示详情之前
    public function _before_detailtwo_data($data){
        // exit();
        $map = array();
        $map['p.toppid'] = $data['toppid'];
        $map['p.id'] = $data['id'];
        $list = M("Post")->field("p.id,from_UNIXTIME(p.addtime,'%Y-%m-%d %H:%i') as addtime,p2.title as title,p.content as content,case p.status when 0 then '待审核' when 1 then '已审核' when -1 then '审核未通过' end as status,u.real_name as uname")->join(" as p left join ".$this->_qz."post as p2 on p.toppid = p2.id left join ".$this->_qz."users as u on u.id = p.userid")->where($map)->find();
        $data = $list;
        session('data2',$data);
        return $data;
    }


     //修改字段之前
    public function _before_field_edit()
    {
        $data = array();
        //范围多个用逗号隔开，如: 0,1 （不需要则为空）
        $data['range'] = "-1,0,1";
        //条件 (除传过来的id外的附加条件，不需要则为空)
        $map = array();
        $data['map'] = $map;
        //字段名 (必须设置)
        $istop = I("get.istop");
        $status = I("get.status");
        if ($istop == "0" || $istop == "1") {
            $data['fieldname'] = "istop"; 
        }
        if ($status == "-1"||$status == "0"||$status == "1") {
            $data['fieldname'] = "status";
        }
        return $data;
    }
}