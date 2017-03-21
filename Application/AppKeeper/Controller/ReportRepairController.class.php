<?php
namespace AppKeeper\Controller;
use AppKeeper\Common\AppKeeperController;

class ReportRepairController extends AppKeeperController {

    function _initialize(){
		parent::_initialize();
		$this->_model = M("ReportRepair");
		
	}

    //管理员端 报修清单
    public function reportRepairList(){
    	$communityid = $this->_data['communityid'];//当前社区id
        $pageNumber = $this->_data['pageNumber'];//当前第几页
        if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
        if(empty($communityid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $count = $this->_model->count($mod_pk);
        $pagesize = 5;
        $pager = new \Think\Page($count,$pagesize);
        $map['rr.communityid'] = $communityid;//当前社区id
        $reportRepairList = $this->_model->field("rr.id,u.real_name,rr.place,from_UNIXTIME(rr.addtime,'%Y-%m-%d %H:%i') as addtime")->join(" as rr left join ".$this->_qz."users as u on u.id=rr.userid")->where($map)->order(" rr.addtime desc")->limit(($pageNumber-1)*5,$pager->listRows)->select();  
        if(empty($reportRepairList)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }     
    	$res_data['status'] = 1;
        $res_data['data'] = $reportRepairList;
    	$res_data['msg'] = "";
    	$this->_return($res_data);
    }

    //管理员端 查看报修详情
    public function reportRepairDetail(){
    	$reportRepairid = $this->_data['reportRepairid'];//当前查看的报修id
        if(empty($reportRepairid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $map['rr.id'] = $reportRepairid;//当前社区id
        $reportRepair = $this->_model->field("rr.id,u.real_name,u.head_portrait,rr.place,from_UNIXTIME(rr.addtime,'%Y-%m-%d %H:%i') as addtime,rr.description,rr.images")->join(" as rr left join ".$this->_qz."users as u on u.id=rr.userid")->where($map)->find();
        if(empty($reportRepair)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
    	$res_data['status'] = 1;
        $res_data['data'] = $reportRepair;
    	$res_data['msg'] = "";
    	$this->_return($res_data);
    }

    //管理员端 重复报修
    public function repeartReportRepair(){
        $reportRepairid = $this->_data['reportRepairid'];//当前报修id
        if(empty($reportRepairid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        //先确认这个信息是不是存在 以及是否处理过了 begin
        $reportRepair = $this->_model->field("id,status")->find($reportRepairid);
        if(empty($reportRepair)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $id = $reportRepair['id'];
        $status = $reportRepair['status'];
        if(empty($id)){
            $res_data['status'] = 0;
            $res_data['msg'] = "报修信息不存在或被已删除！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }else if($status==4){
            $res_data['status'] = 0;
            $res_data['msg'] = "报修信息已标记过！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        //先确认这个信息是不是存在 以及是否处理过了 end
        $map['id'] = $reportRepairid;
        $map['status'] = 4;//处理情况: 0_待处理（默认）, 1_已指派维修员, 2_正在处理, 3_已处理, 4_重复报修, 5_误报
        $reportRepair_return = $this->_model->save($map);
        if($reportRepair_return){
            $res_data['status'] = 1;
            $res_data['msg'] = "操作成功！";
            $res_data['data'] = "";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "操作失败！";
            $res_data['data'] = "";
        }
        $this->_return($res_data);
    }

    //管理员端 误报
    public function errorReportRepair(){
        $reportRepairid = $this->_data['reportRepairid'];//当前报修id
        if(empty($reportRepairid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        //先确认这个信息是不是存在 以及是否处理过了 begin
        $reportRepair = $this->_model->field("id,status")->find($reportRepairid);
        if(empty($reportRepair)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $id = $reportRepair['id'];
        $status = $reportRepair['status'];
        if(empty($id)){
            $res_data['status'] = 0;
            $res_data['msg'] = "报修信息不存在或被已删除！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }else if($status==5){
            $res_data['status'] = 0;
            $res_data['msg'] = "报修信息已标记过！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        //先确认这个信息是不是存在 以及是否处理过了 end
        $map['id'] = $reportRepairid;
        $map['status'] = 5;//处理情况: 0_待处理（默认）, 1_已指派维修员, 2_正在处理, 3_已处理, 4_重复报修, 5_误报
        $reportRepair_return = $this->_model->save($map);
        if($reportRepair_return){
            $res_data['status'] = 1;
            $res_data['msg'] = "操作成功！";
            $res_data['data'] = "";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "操作失败！";
            $res_data['data'] = "";
        }
        $this->_return($res_data);
    }

    //管理员端 点击'派送' 执行派送维修人员
    public function sendCommunityRepair(){
        $reportRepairid = $this->_data['reportRepairid'];//当前操作的报修信息id
        $communityRepairid = $this->_data['communityRepairid'];//当前要派送的维修员id
        if(empty($reportRepairid)||empty($communityRepairid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        // 判断 维修员 是否存在
        $return = verifyDataExist("CommunityRepair",$communityRepairid);//公用方法 判断一个表的指定id数据是否存在
        if(!$return){
            $res_data['status'] = 0;
            $res_data['msg'] = "派送的维修员已删除或不存在！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        // $map['id'] = $reportRepairid;
        $map['repairid'] = $communityRepairid;
        $communityRepair = $this->_model->where("id=".$reportRepairid)->save($map);
        $res_data['status'] = 1;
        $res_data['msg'] = "操作成功！";
        $res_data['data'] = "";
        $this->_return($res_data);
    }


}