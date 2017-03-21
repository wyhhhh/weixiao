<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class RepairUserController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->assignList("community","cname",$map = array(),$strSort = '',$fields = '*',$arrJoins);
    }
    public function _before_getList()
    {
        $map = array();
        $fields = "";
        $arrJoins = array();
        //搜索设置
      
        $name = I('get.name');
        if ($name != "") {
            $where['ru.pet_name']  = array('like',"%$name%");
            $where['ru.job_number']  = array('like',"%$name%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        $communityid = I('get.communityid');
        if ($communityid != "") {
            $map['c.id'] = $communityid;
        }
        $arrJoins[] = "as ru left join ".$this->_qz."community as c on c.id = ru.communityid";

        $fields = "ru.id,ru.pet_name,ru.job_number,ru.sex,ru.birthday,case ru.status when 0 then '待审核' when 1 then '初审已通过' when 2 then '复审已通过' end as status,ru.head_portrait,ru.grade,c.name as cname,case ru.sex when 0 then '女' when 1 then '男' when 2 then '保密' end as sex,if(ru.isshow = 1,'启用','不启用') as isshow,if(ru.sequestration = 1,'正常','封号') as sequestration";
        $data['map'] = $map;
        $data['arrJoins'] = $arrJoins;
        $data['fields'] = $fields;
        return $data;
    }
 
    //图片上传
    public function _before_insert($data){
        $addtime = time();
        $data['addtime'] = $addtime;
        $filename = array("head_portrait");
        $newtime = strtotime($data['birthday']);
        $data['birthday'] = $newtime;
        //生成工号
        $job_number = $this->randomJobNumber($num,"A","D");
        $data['job_number'] = $job_number;
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
        if (empty($data['head_portrait'])) {
            $this->ajaxReturn(1,'请上传图片!');
        }
        return $data;
        
        
    }
    public function _before_update($data){
        $addtime = time();
        $data['addtime'] = $addtime;
        $newtime = strtotime($data['birthday']);
        $data['birthday'] = $newtime;
        $filename = array("head_portrait");
        //允许文件后缀，和前端设置对应
        $exts = array('jpg', 'gif', 'png', 'jpeg');
        $filedata = $this->_uploadFile($filename,$exts);
        if ($filedata)
        {
            $data = array_merge($filedata,$data);
        }
        else{
            foreach ($filename as $key => $value) {
                unset($data[$value]);
            }
        }
        return $data;
    } 
    public function _before_edit_data($data){
        $birthday = $data['birthday'];
        $data['birthday'] = date('Y-m-d',$birthday);
        return $data;
    }
    //修改初审状态/复审状态
    public function _before_field_edit()
    {
        $data = array();
        //范围多个用逗号隔开，如: 0,1 （不需要则为空）
        $data['range'] = "0,1,2";
        //条件 (除传过来的id外的附加条件，不需要则为空)
        $map = array();
        $data['map'] = $map;
        //字段名 (必须设置)
        $data['fieldname'] = "status";
        return $data;
    }
    //查看封号记录，数据之前的操作
    public function _before_detail_data($data){
        $map = array();
        $map['ru.id'] = $data['id'];
        $map['object_type'] = 2;//封号对象: 1_业主, 2_服务人员, 3_社区维修人员
        $list = M("RepairUser")->field("ru.id,s.managename,from_UNIXTIME(s.addtime, '%Y-%m-%d %H:%i') as addtime,from_UNIXTIME(s.deadline_time, '%Y-%m-%d %H-%i') as deadline_time,case s.type when 1 then '按时间封号' when 2 then '永久封号' when 3 then '解封' end as type,s.reason,if(ru.sequestration = 1,'正常','封号') as sequestration,ru.pet_name as uname")->join("as ru left join ".$this->_qz."Sequestration as s on s.userid = ru.id left join ".$this->_qz."Manages as m on m.id = s.manageid")->where($map)->find();
        $data = $list;
        if (empty($list)) {
            $data['sequestration'] = '正常'; 
        }
        return $data;
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
            $save['object_type'] = 2;//封号对象: 1_业主, 2_服务人员, 3_社区维修人员
            $save['addtime'] = time();
            $save['type'] = $_POST['stype'];
            $save['reason'] = $_POST['reason'];
            $list = M("Sequestration")->where($map)->add($save);
            $map = array();
            $map['id'] = $_POST['id'];
            $data['sequestration'] = 0;//0_封号,1_正常
            $status = M("RepairUser")->where($map)->save($data);
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
    //解封
    public function open(){
        $mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST)
        { 
            $map = array();
            $open['manageid'] = session('manageid');
            $open['managename'] = session('managename');
            $open['userid'] = $_POST['id'];//用户或服务人员ID
            $open['object_type'] = 2;//封号对象: 1_业主, 2_服务人员, 3_社区维修人员 
            $open['type'] = 3; //封号类型: 1_按时间封号, 2_永久封号, 3_解封
            $open['deadline_time'] = NULL;//解封到期时间
            $open['addtime'] = time();//处理时间
            $open['reason'] = $_POST['reason'];//解封原因
            $list = M("Sequestration")->add($open);
            $map = array();
            $map['id'] = $_POST['id'];
            $useropen['sequestration'] = 1;//1_正常，0_封号
            $result = M("RepairUser")->where($map)->save($useropen);
            if (!empty($result) && !empty($list)) {
                 $this->ajaxReturn(1,'解封成功！');
             } else{
                $this->ajaxReturn(0,'操作失败！');
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

    //处罚扣款
    public function withhold(){
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $money = $_POST['sum'];
        $manageid = session("manageid");
        if (IS_POST)
        { 
            $map = array();
            $map['id'] = $manageid;
            $managename = M("Manages")->field("username")->where($map)->find();
            $save['manageid'] = $manageid;
            $save['managename'] = $managename['username'];
            $save['reason'] = $_POST['reason'];
            $save['userid'] = $_POST['id'];
            $save['typess'] = 2;//资金流动类型: 1_收入, 2_支出
            $save['type'] = 4;//操作类型: [收]  1_系统赠送, 2_正常收入, [支]：3_提现, 4_处罚扣款
            $save['sum'] = $_POST['sum'];
            $save['reason'] = $_POST['reason'];
            $save['addtime'] = time();
            $map = array();
            $map['id'] = $_POST['id']; 
            $map['sequestration'] = 1;//1_正常，0_封号
            $map['isshow'] = 1;//启用，不启用
            $purse['purse'] = array("exp","purse-".$money."");//钱包金额扣款处理
            $purse['deduction'] = array("exp","deduction+".$money."");//钱包金额扣款处理
            $sum = M("RepairUser")->where($map)->save($purse);
            if ($sum) {
                $save['status'] = 3;//提现结果: 0_待处理（默认）1_提现成功, 2_提现失败, 3_扣款成功, 4_扣款失败
                $save['handletime'] = time();
                $result = M("FundRecord")->add($save);
                $this->ajaxReturn(1,'扣款成功');
            }else{
                $save['status'] = 4;
                $result = M("FundRecord")->add($save);
                $this->ajaxReturn(0,'扣款失败,账户余额不足');
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
            if(method_exists($this, '_before_withhold_data'))
            {
               $info = $this->_before_withhold_data($info);
            }
            $this->assign('info', $info);
            if (IS_AJAX)
            {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            }
            else
            {
                if(method_exists($this, '_before_withhold')){
                   $templet = $this->_before_withhold();
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



    
}