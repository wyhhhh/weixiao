<?php
namespace AppKeeper\Controller;
use AppKeeper\Common\AppKeeperController;

class CommunityRepairController extends AppKeeperController {

    function _initialize(){
		parent::_initialize();
		$this->_model = M("CommunityRepair");
	}

    //管理员 管理社区维修(报修)人员列表
    public function communityRepairList(){
        $communityid = $this->_data['communityid'];//当前社区id
        if(empty($communityid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $map['communityid'] = $communityid;
        $map['isshow'] = 1;//是否启用: 0_不启用, 1_启用（默认）
        $map['status'] = 2;//审核状态: 0_待审核（默认）, 1_初审通过, 2_审核通过
        // $map['sequestration'] = 1;//账号状态: 0_封号, 1_正常（默认）
        $communityRepair = $this->_model->field("id,name,sequestration")->where($map)->select();
        if(empty($communityRepair)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $res_data['status'] = 1;
        $res_data['msg'] = "";
        $res_data['data'] = $communityRepair;
        $this->_return($res_data);
    }

    //管理员 社区维修(报修)人员封号
    public function communityRepairCuff(){
        $communityRepairid = $this->_data['communityRepairid'];//当前社区保修人员id
        $type = $this->_data['type'];//封号类型: 1_按时间封号, 2_永久封号, 3_解封
        $deadline_time = $this->_data['deadline_time'];//封号截止时间
        $reason = $this->_data['reason'];//封号/解封理由
        $manageid = $this->_data['manageid'];//当前管理员id
        if(empty($communityRepairid)||empty($reason)||empty($manageid)||empty($type)||$type==3){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        //获取到当前操作的数据
        $communityRepair = getFieldData("CommunityRepair",$communityRepairid,"id,name,isshow,status,sequestration");

        //检查数据是否存在
        if(empty($communityRepair)){
            $res_data['status'] = 0;
            $res_data['msg'] = "当前操作的维修人员不存在,请刷新数据再尝试";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $isshow = $communityRepair['isshow'];//是否启用: 0_不启用, 1_启用（默认）
        $status = $communityRepair['status'];//审核状态: 0_待审核（默认）, 1_初审通过, 2_审核通过
        $sequestration = $communityRepair['sequestration'];//账号状态: 0_封号, 1_正常（默认）
        if(empty($sequestration)){$sequestration="0";}
        if($isshow != 1){
            $res_data['status'] = 0;
            $res_data['msg'] = "当前操作的维修人员未启用,请刷新数据再尝试";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        if($status != 2){
            $res_data['status'] = 0;
            $res_data['msg'] = "当前操作的维修人员未通过正式审核,请刷新数据再尝试";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        if($sequestration != 1){
            $res_data['status'] = 0;
            $res_data['msg'] = "当前维修人员已封号,请勿重复操作";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        $keeper = getFieldData("Keeper",$manageid,"id,username");//得到管理员用户名
        ////封号类型: 1_按时间封号, 2_永久封号, 3_解封
        if($type==1&&empty($deadline_time)){
            $res_data['status'] = 0;
            $res_data['msg'] = "短期封号截止时间需要设定";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        //这里做封号记录处理
        $map['userid'] = $communityRepairid;
        $map['object_type'] = 1;//封号对象: 1_业主, 2_服务人员, 3_社区维修人员
        $map['type'] = $type;
        $map['addtime'] = time();
        $map['deadline_time'] = strtotime($deadline_time);
        $map['reason'] = $reason;
        $map['manageid'] = $manageid;
        $map['managename'] = $keeper['username'];
        $return_add = M("Sequestration")->add($map);

        //这里做封号处理
        $map['id'] = $communityRepairid;
        $map['sequestration'] = 0;//账号状态: 0_封号, 1_正常（默认）
        $return_save = $this->_model->save($map);

        if(empty($return_save)){
            $res_data['status'] = 0;
            $res_data['data'] = "";
            $res_data['msg'] = "封号处理失败";
            $this->_return($res_data);
        }        

        $res_data['status'] = 1;
        $res_data['data'] = "";
        $res_data['msg'] = "封号处理成功";
        $this->_return($res_data);
    }

    //管理员 社区维修(报修)人员账号解封
    public function communityRepairDeArchive(){
        $communityRepairid = $this->_data['communityRepairid'];//当前社区保修人员id
        $reason = $this->_data['reason'];//封号/解封理由
        $manageid = $this->_data['manageid'];//当前管理员id
        if(empty($communityRepairid)||empty($reason)||empty($manageid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        //获取到当前操作的数据
        $communityRepair = getFieldData("CommunityRepair",$communityRepairid,"id,name,isshow,status,sequestration");

        //检查数据是否存在
        if(empty($communityRepair)){
            $res_data['status'] = 0;
            $res_data['msg'] = "当前操作的维修人员不存在,请刷新数据再尝试";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $isshow = $communityRepair['isshow'];//是否启用: 0_不启用, 1_启用（默认）
        $status = $communityRepair['status'];//审核状态: 0_待审核（默认）, 1_初审通过, 2_审核通过
        $sequestration = $communityRepair['sequestration'];//账号状态: 0_封号, 1_正常（默认）
        if(empty($sequestration)){$sequestration="0";}
        if($isshow != 1){
            $res_data['status'] = 0;
            $res_data['msg'] = "当前操作的维修人员未启用,请刷新数据再尝试";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        if($status != 2){
            $res_data['status'] = 0;
            $res_data['msg'] = "当前操作的维修人员未通过正式审核,请刷新数据再尝试";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        if($sequestration == 1){
            $res_data['status'] = 0;
            $res_data['msg'] = "当前维修人员已解封,请勿重复操作";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        $keeper = getFieldData("Keeper",$manageid,"id,username");//得到管理员用户名
        //这里做封号记录处理
        $map['userid'] = $communityRepairid;
        $map['object_type'] = 1;//对象: 1_业主, 2_服务人员, 3_社区维修人员
        $map['type'] = 3;//解封
        $map['deadline_time'] = time();//永久封号的截止时间是解除封号的时候
        $map['addtime'] = time();
        $map['reason'] = $reason;
        $map['manageid'] = $manageid;
        $map['managename'] = $keeper['username'];
        $return_add = M("Sequestration")->add($map);

        //这里做封号处理
        $map['id'] = $communityRepairid;
        $map['sequestration'] = 1;//账号状态: 0_封号, 1_正常（默认）
        $return_save = $this->_model->save($map);

        if(empty($return_save)){
            $res_data['status'] = 0;
            $res_data['data'] = "";
            $res_data['msg'] = "解封处理失败";
            $this->_return($res_data);
        }        

        $res_data['status'] = 1;
        $res_data['data'] = "";
        $res_data['msg'] = "解封处理成功";
        $this->_return($res_data);
    }

    //管理员 为社区维修(报修)人员重置密码
    public function resetCommunityRepairPwd(){
        $communityRepairid = $this->_data['communityRepairid'];//当前社区保修人员id
        $newPwd = $this->_data['newPwd'];//当前社区保修人员id
        if(empty($communityRepairid)||empty($newPwd)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        $map['id'] = $communityRepairid;
        $map['password'] = encrypt_str($newPwd);
        $return_save = $this->_model->save($map);
        if(empty($return_save)){
            $res_data['status'] = 0;
            $res_data['msg'] = "修改失败";
        }else{
            $res_data['status'] = 1;
            $res_data['msg'] = "修改成功";
        }
        $res_data['data'] = "";
        $this->_return($res_data);
    }

    //管理员 为社区维修(报修)人员重置手机号
    public function resetPhone(){
        $communityRepairid = $this->_data['communityRepairid'];//当前社区保修人员id
        $phone = $this->_data['phone'];//当前社区保修人员id
        if(empty($communityRepairid)||empty($phone)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        $map['id'] = $communityRepairid;
        $map['phone'] = $phone;
        $return_save = $this->_model->save($map);
        if(empty($return_save)){
            $res_data['status'] = 0;
            $res_data['msg'] = "修改失败";
        }else{
            $res_data['status'] = 1;
            $res_data['msg'] = "修改成功";
        }
        $res_data['data'] = "";
        $this->_return($res_data);
    }

    //管理员 新增维修员
    public function addCommunityRepair(){
        $phone = $this->_data['phone'];
        $password = $this->_data['password'];
        $communityid = $this->_data['communityid'];
        if(empty($phone)||empty($password)||empty($communityid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        $map_find['phone'] = $phone;
        $return_find = $this->_model->where($map_find)->find();
        if(!empty($return_find)){
            $res_data['status'] = 0;
            $res_data['data'] = "";
            $res_data['msg'] = "此手机号已存在,请勿重复添加";
            $this->_return($res_data);
        }
        $map_add['status'] = 2;//0_待审核，1_初审通过,2_复审通过
        $map_add['phone'] = $phone;
        $map_add['password'] = encrypt_str($password);//加密;
        $map_add['addtime'] = time();
        $map_add['communityid'] = $communityid;
        $return_add = $this->_model->add($map_add);

        if(!$return_add){
            $res_data['status'] = 0;
            $res_data['data'] = "";
            $res_data['msg'] = "操作失败";
            $this->_return($res_data);
        }

        $res_data['status'] = 1;
        $res_data['data'] = "";
        $res_data['msg'] = "添加成功";
        $this->_return($res_data);
    }


    //管理员通知选择维修人员
    public function selectCommunityRepair(){
        $type = $this->_data['type'];//2_通知社区维修, 3_通知用户
        $communityid = $this->_data['communityid'];//当前社区id
        if(empty($type)||$type!=2||empty($communityid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        $map['communityid'] = $communityid;
        $map['isshow'] = 1;//是否启用: 0_不启用, 1_启用（默认）
        $map['status'] = 2;//审核状态: 0_待审核（默认）, 1_初审通过, 2_审核通过
        $map['sequestration'] = 1;//账号状态: 0_封号, 1_正常（默认）
        $communityRepair = $this->_model->field("id,name")->where($map)->select();
        $res_data['status'] = 1;
        $res_data['msg'] = "";
        $res_data['data'] = $communityRepair;
        $this->_return($res_data);
    }

    //管理员派送维修员 列表
    public function chooseCommunityRepair(){
        $reportRepairid = $this->_data['reportRepairid'];//当前报修信息id
        $communityid = $this->_data['communityid'];//当前社区id
        if(empty($reportRepairid)||empty($communityid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        // 判断 报修信息 是否存在
        $return = verifyDataExist("ReportRepair",$reportRepairid);//公用方法 判断一个表的指定id数据是否存在
        if(!$return){
            $res_data['status'] = 0;
            $res_data['msg'] = "报修信息已删除或不存在！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        $map['communityid'] = $communityid;
        $map['isshow'] = 1;//是否启用: 0_不启用, 1_启用（默认）
        $map['status'] = 2;//审核状态: 0_待审核（默认）, 1_初审通过, 2_审核通过
        $map['sequestration'] = 1;//账号状态: 0_封号, 1_正常（默认）
        $communityRepair = $this->_model->field("id,name")->where($map)->select();
        $res_data['status'] = 1;
        $res_data['msg'] = "";
        $res_data['data']['communityRepair'] = $communityRepair;
        $res_data['data']['reportRepairid'] = $reportRepairid;
        $this->_return($res_data);
    }

    
}