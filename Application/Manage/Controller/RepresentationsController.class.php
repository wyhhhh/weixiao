<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class RepresentationsController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->assignList("RepairUser","rname",$map = array(),$strSort = '',$fields = '*',$arrJoins);
	}
    //申诉管理
	public function _before_getList()
    {
        $map = array();
        $fields = "";
        $arrJoins = array();
        
        //搜索设置
        $name = I('get.name');
        if ($name != "") {
            $map['ru.pet_name'] = array('like',"%$name%");
        }
        $status = I('get.status');
        if ($status != "") {
            $map['rt.status'] = $status;
        }
        $arrJoins[] = "as rt left join ".$this->_qz."repair_relation as rr on rr.id = rt.repairrelationid";
        $arrJoins[] = "left join ".$this->_qz."repair_user as ru on ru.id = rr.repairuserid";

        $fields = "rt.id,ru.pet_name,rr.evaluate,rt.images,rt.reason,from_UNIXTIME(rt.addtime,'%Y-%m-%d %H:%i') as addtime,if(rt.status = 1,'已处理','未处理') as status";
        $data['map'] = $map;
        $data['arrJoins'] = $arrJoins;
        $data['fields'] = $fields;
        return $data;
    }
     //订单查看详情之前，对数据的处理
    public function _before_detail_data($data){
        $map = array();
        $map['rr.id'] = $data['repairrelationid'];
        $list = M("RepairRelation")->field("r.id,r.name,r.description,rr.reason,rr.repairuserid,rr.repairid,rr.status,case rr.paystatus when 0 then '未交费' when 1 then '已缴费' when 2 then '缴费退款中' when 3 then '已退款' end as paystatus,rr.evaluate as evaluate,ru.pet_name,r.service_place as service_place,from_UNIXTIME(rr.addtime,'%Y-%m-%d %H:%i') as addtime,u.real_name as real_name,if (rt.type = 1,'维修','家政服务') as type,case rr.status when 0 then '分配维修员' when 1 then '正在维修' when 2 then '维修完成' when 3 then '用户取消' when 4 then '管理员取消' end as status")->join("as rr left join ".$this->_qz."representations as rs on rs.repairrelationid = rr.id left join ".$this->_qz."repair as r on rr.repairid = r.id left join ".$this->_qz."repair_type as rt on rt.id = r.typeid left join ".$this->_qz."users as u on u.id = r.userid  left join ".$this->_qz."repair_user as ru on ru.id = rr.repairuserid ")->where($map)->find();
        $data = $list;
        // session("dingdan",$data);
        return $data;
    }

    //服务人员
     public function waitress() {
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
            if(method_exists($this, '_before_waitress_data'))
            {
               $info = $this->_before_waitress_data($info);
            }
            $this->assign('info', $info);
            if (IS_AJAX)
            {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            }
            else
            {
                if(method_exists($this, '_before_waitress')){
                   $templet = $this->_before_waitress();
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
    //服务人员详情之前，对数据的处理
    public function _before_waitress_data($data){
        // session("detail",$data);
        $map = array();
        $map['rr.id'] = $data['repairrelationid'];
        $list = M("RepairRelation")->field("ru.phone,ru.job_number,ru.pet_name,if(ru.sex = 1,'男' ,'女') as sex,ru.grade,c.name as cname")->join("as rr left join ".$this->_qz."repair_user as ru on rr.repairuserid = ru.id left join ".$this->_qz."community as c on ru.communityid = c.id")->where($map)->find();
        $data = $list;
        return $data;
    }

    //业主信息
     public function owner() {
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
            if(method_exists($this, '_before_owner_data'))
            {
               $info = $this->_before_owner_data($info);
            }
            $this->assign('info', $info);
            if (IS_AJAX)
            {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            }
            else
            {
                if(method_exists($this, '_before_owner')){
                   $templet = $this->_before_owner();
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
    //查询业主信息之前，对数据的处理
    public function _before_owner_data($data){
        // session("detail",$data);
        $map = array();
        $map['rr.id'] = $data['repairrelationid'];
        $list = M("RepairRelation")->field("u.phone,u.real_name,if(u.sex = 1,'男','女') as sex,from_UNIXTIME(u.addtime,'%Y-%m-%d %H:%i') as addtime")->join("as rr left join ".$this->_qz."repair as r on r.id = rr.repairid left join ".$this->_qz."users as u on u.id = r.userid")->where($map)->find();
        $data = $list;
        return $data;
    }


}