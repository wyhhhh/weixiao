<?php
namespace AppKeeper\Controller;
use AppKeeper\Common\AppKeeperController;

class BuildingController extends AppKeeperController {

    function _initialize(){
		parent::_initialize();
		
	}

    //管理员通知选择楼栋
    public function selectBuilding(){
        $type = $this->_data['type'];//2_通知社区维修, 3_通知用户
        $communityid = $this->_data['communityid'];//当前社区id
        if(empty($type)||empty($communityid)){
            // p($communityid."===".$type);
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $model = M("Building");
        $map['communityid'] = $communityid;
        // open_unit 是否启用单元: 0_不启用（默认）, 1_启用
        $building = $model->field("id,name,open_unit")->where($map)->select();
        if(empty($building)){
            // p($communityid."===".$type);
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $res_data['status'] = 1;
        $res_data['msg'] = "";
        $res_data['data'] = $building;
        $this->_return($res_data);
    }

    // //管理员通知选择楼栋直接发布
    // public function selectBuildingIssue(){
    //     $communityid = $this->_data['communityid'];//当前社区id
    //     $buildingid = $this->_data['buildingid'];//选择的楼栋id（多个用','（英文逗号）拼接起来）
    //     if(empty($communityid)||empty($buildingid)){
    //         $res_data['status'] = 0;
    //         $res_data['msg'] = "非法操作！";
    //         $res_data['data'] = "";
    //         $this->_return($res_data);
    //     }

    //     //先找到楼层
    //     $map_floor['buildingid'] = array("in",$buildingid);
    //     $map_floor['communityid'] = $communityid;
    //     $floorList = M("Floor")->field("GROUP_CONCAT(id) as ids")->where($map_floor)->find();
    //     $map = array();
    //     $floorids = $floorList['ids'];
    //     if(!empty($floorids)){
    //         $map['floorid'] = array("in",$floorids);
    //     }
    //     // 找到房屋
    //     $model = M("House");
    //     $map['communityid'] = $communityid;
    //     $houseList = $model->field("id,name")->where($map)->select();
    //     //直接发布通知

    //     $res_data['status'] = 1;
    //     $res_data['msg'] = "";
    //     $res_data['data'] = $houseList;

    //     $this->_return($res_data);
    // }


}