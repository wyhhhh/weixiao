<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class RepairRelationController extends AppUserController {

    function _initialize(){
		parent::_initialize();
		
	}

	//用户端维/服务预约信息
    public function servicesReserve(){
        $repairid = $this->_data['repairid'];//需要传入服务id
        $res_data = array();
        //防止空type和乱传数据
        if(empty($repairid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        //加载维修附表信息
        $repair = getFieldData("Repair",$repairid,"name,description,images");
        if(empty($repair)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $name = $repair['name'];
        $description = $repair['description'];

        //找出服务DD服务人员服务比较好又空闲的一个维修员/家政自动接单
        $map_repair_user['ru.sequestration'] = 1;//sequestration 账号状态: 0_封号, 1_正常（默认）
        $map_repair_user['ru.status'] = 2;//status 审核状态: 0_待审核（默认）, 1_初审通过, 2_复审通过
        $map_repair_user['ru.isshow'] = 1;//isshow 是否启用: 0_不启用, 1_启用（默认）
        $map_repair_user['rr.status'] = array("exp","!=1");//status 维修完成状态: 0_分配维修员（默认）, 1_正在维修 , 2_维修完成 , 3_用户取消 , 4_管理员取消
        $repair_user_return = M("RepairUser")->join(" as ru left join ".$this->_qz."repair_relation as rr on rr.repairuserid=ru.id")->field("ru.id,ru.job_number,pet_name,phone,head_portrait")->where($map_repair_user)->order("grade desc")->limit(1)->find();
        if($repair_user_return){
            $repair_userid = $repair_user_return['id'];//系统自动找到的DD维修员
            //插入分配的订单的维修员数据
            $map_repair_relation['repairid'] = $repairid;
            $map_repair_relation['repairuserid'] = $repair_userid;
            $map_repair_relation['status'] = 0;//status 完成状态: 0_分配维修员（默认）
            $map_repair_relation['paystatus'] = 0;//paystatus 用户缴费状态: 0_未缴费（默认）, 1_已缴费, 2_缴费退款中, 3_已退款
            $map_repair_relation['addtime'] = time();
            $repair_relation_return = M("RepairRelation")->add($map_repair_relation);

            //通知消息 发送到维修员
            $map_i['type'] = 1;//type 消息类型:1_物业/系统通知DD维修,2_物业/系统通知社区服务人员, 3_系统推送消息到用户/住户, 4_网站反馈结果通知消息
            $map_i['title'] = $name;
            $map_i['content'] = $description;
            $map_i['addtime'] = time();
            $return_i = M("Information")->add($map_i);
            //
            $map_ir['isread'] = 0;//读取状态:0_未读（默认）, 1_已读
            $map_ir['ralationid'] = $repair_userid;//应该由谁来读取这条消息
            $map_ir['informationid'] = $return_i;//关联的通知消息id
            $return_ir = M("InformationRelation")->add($map_ir);

            unset($repair_user_return['id']);//返回数据不要id
            //找到符合的服务人员
            $res_data['status'] = 1;
            $res_data['msg'] = "";
            $res_data['data'] = $repair_user_return;
        }else{
            //没有符合的服务人员
            $res_data['status'] = 0;
            $res_data['msg'] = "没有找到符合要求的维修人员！";
            $res_data['data'] = "";
        }
        

        $this->_return($res_data);
    }   

}