<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class RepairController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
        $map['isshow'] = 1;//是否启用: 0_不启用, 1_启用（默认）
        $map['status'] = 2;//审核状态: 0_待审核（默认）, 1_初审通过, 2_复审通过
        $map['sequestration'] = 1;//账号状态: 0_封号, 1_正常（默认）
        $this->assignList("RepairUser","repairuser",$map,$strSort = '',$fields = 'id,pet_name',$arrJoins);
	}
    // public function index(){
    //     if ($_GET['id']) {
    //         session('repairid',$_GET['id']);  
    //     };
    //     $this->display();
    // }

	public function _before_getList()
    {
        $id = I('get.id');
        $map = array();
        if (!empty($id)) {
            $map['ru.id'] = $id;     
        }
        $fields = "";
        $arrJoins = array();
        
        //搜索设置
        $name = I('get.name');
        if ($name != "") {
            $map['u.real_name'] = array('like',"%$name%");
        }
        $repairuser = I('get.repairuser');
        if ($repairuser != "") {
            $map['ru.id'] = array('like',"%$repairuser%");
        }
        $status = I('get.status');
        if ($status != "") {
            $map['rr.status'] = $status;
        }
        $paystatus = I("get.paystatus");
        if ($paystatus != "") {
            $map['rr.paystatus'] = $paystatus;
        }
        $map['ru.isshow'] = 1;//是否启用: 0_不启用, 1_启用（默认）
        $map['ru.status'] = 2;//审核状态: 0_待审核（默认）, 1_初审通过, 2_复审通过
        $map['ru.sequestration'] = 1;//账号状态: 0_封号, 1_正常（默认）
        $map['rt.isshow'] = 1;//是否服务类型显示: 0_不显示, 1_显示（默认）

        $arrJoins[] = "as r left join ".$this->_qz."repair_type as rt on rt.id = r.typeid";//链接服务类型表
        $arrJoins[] = "left join ".$this->_qz."users as u on u.id = r.userid";//链接住户表
        $arrJoins[] = "left join ".$this->_qz."repair_relation as rr on rr.repairid = r.id";//链接服务关联表
        $arrJoins[] = "left join ".$this->_qz."repair_user as ru on ru.id = rr.repairuserid";//链接DD维修人员表
        $fields = "r.id,rr.repairuserid,rr.repairid,rr.status,case rr.paystatus when 0 then '未交费' when 1 then '已缴费' when 2 then '缴费退款中' when 3 then '已退款' end as paystatus,rr.evaluate as evaluate,ru.pet_name,r.service_place as service_place,from_UNIXTIME(r.addtime,'%Y-%m-%d %H:%i') as addtime,u.real_name as real_name,if (rt.type = 1,'维修','家政服务') as type,case rr.status when 0 then '分配维修员' when 1 then '正在维修' when 2 then '维修完成' when 3 then '用户取消' when 4 then '管理员取消' end as status";
        //维修发布状态status: 1_正常（默认）, 2_主动撤销,  3_管理员取消
        //type:1_维修，2_家政服务
        $data['map'] = $map;
        $data['arrJoins'] = $arrJoins;
        $data['fields'] = $fields;
        return $data;
    }
    //订单查看详情之前，对数据的处理
    public function _before_detail_data($data){
        $map = array();
        $map['r.id'] = $data['id'];
        $list = M("Repair")->field("r.id,r.name,r.description,rr.reason,rr.reminder,rr.repairuserid,rr.repairid,rr.status,case rr.paystatus when 0 then '未交费' when 1 then '已缴费' when 2 then '缴费退款中' when 3 then '已退款' end as paystatus,rr.evaluate as evaluate,ru.pet_name,r.service_place as service_place,from_UNIXTIME(r.addtime,'%Y-%m-%d %H:%i') as addtime,u.real_name as real_name,if (rt.type = 1,'维修','家政服务') as type,case rr.status when 0 then '分配维修员' when 1 then '正在维修' when 2 then '维修完成' when 3 then '用户取消' when 4 then '管理员取消' end as status")->join("as r left join ".$this->_qz."repair_type as rt on rt.id = r.typeid left join ".$this->_qz."users as u on u.id = r.userid left join ".$this->_qz."repair_relation as rr on rr.repairid = r.id left join ".$this->_qz."repair_user as ru on ru.id = rr.repairuserid")->where($map)->find();
        $data = $list;
        return $data;
    }
    //订单支付详情之前，对数据的处理
    public function paydetail() {
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
            if(method_exists($this, '_before_paydetail_data'))
            {
               $info = $this->_before_paydetail_data($info);
            }
            $this->assign('info', $info);
            if (IS_AJAX)
            {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            }
            else
            {
                if(method_exists($this, '_before_paydetail')){
                   $templet = $this->_before_paydetail();
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
    //订单支付详情之前，对数据的处理
    public function _before_paydetail_data($data){
        // session("detail",$data);
        $map = array();
        $map['r.id'] = $data['id'];
        $list = M("Repair")->field("u.real_name,rr.pay_money,case rr.paystatus when 0 then '未交费' when 1 then '已缴费' when 2 then '缴费退款中' when 3 then '已退款' end as paystatus")->join("as r left join ".$this->_qz."users as u on r.userid = u.id left join ".$this->_qz."repair_relation as rr on rr.repairid = r.id")->where($map)->find();
        $data = $list;
        return $data;
    }
}