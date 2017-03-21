<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class UsersController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->assignList("community","mname",$map = array(),$strSort = '',$fields = '*',$arrJoins);
        $this->assignList("Sequestration","stype",$map = array(),$strSort = '',$fields = "case type when 1 then '按时间封号' when 2 then '永久封号' when 3 then '解封' end as type",$arrJoins);
	}

	public function _before_getList()
    {
        $map = array();
        $fields = "";
        $arrJoins = array();
        $name = I('get.name'); 
        if ($name != "") {
            $where['u.real_name']  = array('like',"%$name%");
            $where['u.phone']  = array('like',"%$name%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        $communityid = I('get.communityid');
        if ($communityid != "") {
            $map['c.id'] = $communityid;
        }
        $arrJoins[] = "as u left join ".$this->_qz."house as h on h.id = u.house_id";//连接房屋表
        $arrJoins[] = "left join ".$this->_qz."community as c on c.id = h.communityid";//连接社区表
        $arrJoins[] = "left join ".$this->_qz."user_house as uh on uh.userid = u.id";//连接用户房屋关联表
        // $arrJoins[] = "left join ".$this->_qz."sequestration as s on s.userid = u.id";//链接封号记录表

        $fields = "u.id,u.phone,u.house_id,u.real_name,if(u.sequestration = 1,'正常','封号') as sequestration,u.birthdate,c.name as name,u.status, h.name as hname,if(u.status = 1,'启用','不启用') as status,case u.sex when 0 then '女' when 1 then '男' when 2 then '保密' end as sex,if(uh.isproprietor = 1,'业主','非业主') as isproprietor";
        //性别:0_女, 1_男, 2_保密（默认）,
        $data['arrJoins'] = $arrJoins;
        $data['map'] = $map;
        $data['fields'] = $fields;
        return $data;
    }
    //入库之前
    public function _before_insert($data){
        $houseid = I('post.houseid');
        $time = strtotime($data['birthdate']);//转换成时间戳
        $data['password'] = encrypt_str($data['password']);//加密
        $data['house_id'] = $houseid;
        $data['birthdate'] = $time;
        return $data;
    }
    //入库之后
    public function _after_insert($id){
        $isproprietor = I("post.isproprietor");//获取是否为业主的值
        $house_id = I("post.houseid");//获取房屋号
        $userid = $id;
        $house['userid'] = $id;
        $house['isproprietor'] = $isproprietor;
        $house['houseid'] =  $house_id;
        $result = M("UserHouse")->add($house);
        if ($result) {
            $this->ajaxReturn(1,"添加成功！");
        }else{
            $this->ajaxReturn(0,"用户房屋关系添加失败！");
        }
    }
    //编辑之前，对数据的操作
    public function _before_edit_data($data){
        $map = array();
        $map['u.id'] = $data['id'];
        $list = M("Users")->field("u.id,u.phone,u.house_id,u.sex,u.id_card,h.number as hnumber,u.real_name,u.sequestration,from_UNIXTIME(u.birthdate,'%Y-%m-%d') as birthdate,c.name as cname,c.id as cid,u.status, h.name as hname,u.status,uh.isproprietor as uisproprietor")->join("as u left join ".$this->_qz."house as h on h.id = u.house_id left join ".$this->_qz."community as c on c.id = h.communityid left join ".$this->_qz."user_house as uh on uh.userid = u.id")->where($map)->find();
        $data = $list;
        // session('list',$data);
        return $data;
    }
    //编辑之后
    public function _after_update($data){
        $map = array();
        $map['uh.userid'] = $data['id'];
        $list['isproprietor'] = $data['isproprietor'];
        $result = M("UserHouse")->join("as uh left join ".$this->_qz."users as u on u.id = uh.userid ")->where($map)->save($list);
        $data = $result;
        return $data;
    }
    //详情
    public function _before_detail_data($data){
        $map = array();
        $map['u.id'] = $data['id'];
        $map['object_type'] = 1;//封号对象: 1_业主, 2_服务人员, 3_社区维修人员
        $list = M("Users")->field("u.id,s.managename,from_UNIXTIME(s.addtime, '%Y-%m-%d %H:%i') as addtime,from_UNIXTIME(s.deadline_time, '%Y-%m-%d %H-%i') as deadline_time,s.reason,case s.type when 1 then '按时间封号' when 2 then '永久封号' when 3 then '解封' end as type,if(u.sequestration = 1,'正常','封号') as sequestration,u.real_name as uname")->join("as u left join ".$this->_qz."Sequestration as s on s.userid = u.id left join ".$this->_qz."Manages as m on m.id = s.manageid")->where($map)->find();
        $data = $list;
        if (empty($list)) {
            $data['sequestration'] = '正常'; 
        }
        return $data;

    }
    //解封
    public function open(){
        $mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST)
        { 
            $map = array();
            $open['manageid'] = session("manageid");
            $open['managename'] = session("managename");
            $open['userid'] = $_POST['id'];
            $open['object_type']= 1;//封号对象: 1_业主, 2_服务人员, 3_社区维修人员
            $open['type'] = 3; //封号类型: 1_按时间封号, 2_永久封号, 3_解封
            $open['deadline_time'] = NULL;
            $open['addtime'] = time();
            $open['reason'] = $_POST['reason'];
            $list = M("Sequestration")->where($map)->add($open);
            $map = array();
            $map['id'] = $_POST['id'];
            $useropen['sequestration'] = 1;//1_正常，0_封号
            $result = M("Users")->where($map)->save($useropen);
            if (!empty($result) && !empty($list)) {
                $this->ajaxReturn(1,'解封成功！');
             } else{
                $this->ajaxReturn(0,'解封失败！');
             }
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
            if(method_exists($this, '_before_open_data'))
            {
               $info = $this->_before_open_data($info);
            }
            $this->assign('info', $info);
            if (IS_AJAX)
            {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            }
            else
            {
                if(method_exists($this, '_before_open')){
                   $templet = $this->_before_open();
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
     /**
    * 封号
    */ 
    public function unout() {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $manageid = session('manageid');//获取当前的管理员ID
        if (IS_POST)
        { 
            $map = array();
            $map['id'] = $manageid;
            $managename = M("Manages")->field("username")->where($map)->find();
            $save['manageid'] = $manageid;
            $save['managename'] = $managename['username'];
            $save['userid'] = $_POST['id'];
            if ($_POST['stype'] == 2) {
                $save['deadline_time'] = NULL;
            }else{
                $save['deadline_time'] = strtotime($_POST['deadline_time']);
            }
            $save['object_type'] = 1;//封号对象: 1_业主, 2_服务人员, 3_社区维修人员
            $save['addtime'] = time();
            $save['type'] = $_POST['stype'];
            $save['reason'] = $_POST['reason'];
            $list = M("Sequestration")->where($map)->add($save);
            $map = array();
            $map['id'] = $_POST['id'];
            $data['sequestration'] = 0;//0_封号,1_正常
            $status = M("Users")->where($map)->save($data);
            if (!empty($list) && !empty($status)) {
                $this->ajaxReturn(1, '封号成功！');
            }
            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }
            if (false !== $mod->save($data)) {
                if (method_exists($this, '_after_update')) {
                    $id = $data['id'];
                    $this->_after_update($id);
                }
                IS_AJAX && $this->ajaxReturn(1, '操作成功！' , '', 'unout');
                $this->success('操作成功！');
            } else {
                IS_AJAX && $this->ajaxReturn(0,'操作失败！');
                $this->error('操作失败！');
            }
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
            if(method_exists($this, '_before_unout_data'))
            {
               $info = $this->_before_unout_data($info);
            }
            $this->assign('info', $info);
            if (IS_AJAX)
            {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            }
            else
            {
                if(method_exists($this, '_before_unout')){
                   $templet = $this->_before_unout();
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
    // 查询房号
    public function ajax_getHouse()
    {     
        if(!IS_AJAX) return;
        $key = I('post.key');
        $communityid = I('post.condition');//附加条件
        $map = array();
        $map['communityid'] = $communityid;
        if (!empty($key)) {
            $map['number'] = array('like',"%$key%");
        }
        //排出房号的列表
        $list = M('House')->field("number as name,id")->where($map)->select();//重命名是因为JS中固定字段name,重写name字段
        // session("testarea",$list);
        // session("testarea",M('House')->getlastsql());
        $this->ajaxReturn(1,$list,'--');
    }    
}