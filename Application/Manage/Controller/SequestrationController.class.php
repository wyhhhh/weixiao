<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class SequestrationController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->assignList("Manages","mname",$map = array(),$strSort = '',$fields = '*',$arrJoins);
  
    }
    // public function _before_index()
    // {
    //     if (I("get.key") == 2) {
    //         return "indextwo";
    //     }
    // }
    //封号/解封记录
    public function _before_getList()
    { 
            $key = I("get.key");
            $sid = I("get.sid");
            session('key',$key);
            if ($key == 1) {
                $map = array();
                $fields = "";
                $arrJoins = array();
                if (!empty($sid)) {
                    $map['u.id'] = $sid; 
                }
                //搜索设置
                $name = I('get.name');
                if ($name != "") {
                    $map['name'] = array('like',"%$name%");
                }
                $manageid = I('get.manageid');
                if ($manageid != "") {
                    $map['m.id'] = $manageid;
                }
                $map['s.object_type'] = 1;//封号对象: 1_业主, 2_服务人员, 3_社区维修人员

                //链表
                $arrJoins[] = "as s left join ".$this->_qz."users as u on u.id = s.userid";
                $arrJoins[] = " left join ".$this->_qz."Manages as m on m.id = s.manageid";


                $fields = "s.id,s.managename,s.addtime as addtime,from_UNIXTIME(s.deadline_time, '%Y-%m-%d %H:%i') as deadline_time,case s.type when 1 then '按时间封号' when 2 then '永久封号' when 3 then '解封' end as type,s.deadline_time,s.reason,u.real_name as uname";
                $data['map'] = $map;
                $data['arrJoins'] = $arrJoins;
                $data['fields'] = $fields;
                return $data;
            }
            if ($key == 2) {
                $rid = I('get.rid');
                // session('rid',$rid);
                // exit();
                $map = array();
                $fields = "";
                $arrJoins = array();
                //搜索设置
                if (!empty($rid)) {
                    $map['s.userid'] = $rid;
                }
                $name = I('get.name');
                if ($name != "") {
                    $map['name'] = array('like',"%$name%");
                }
                $manageid = I('get.manageid');
                if ($manageid != "") {
                    $map['m.id'] = $manageid;
                }
                $map['s.object_type'] = 2;//封号对象: 1_业主, 2_服务人员, 3_社区维修人员

                //链表
                $arrJoins[] = "as s left join ".$this->_qz."repair_user as ru on ru.id = s.userid";
                $arrJoins[] = " left join ".$this->_qz."Manages as m on m.id = s.manageid";


                $fields = "s.id,s.managename,s.addtime as addtime,from_UNIXTIME(s.deadline_time, '%Y-%m-%d %H:%i') as deadline_time,case s.type when 1 then '按时间封号' when 2 then '永久封号' when 3 then '解封' end as type,s.deadline_time,s.reason,ru.pet_name as pname";
                $data['map'] = $map;
                $data['arrJoins'] = $arrJoins;
                $data['fields'] = $fields;
                return $data;
            }
        
    }
    
}