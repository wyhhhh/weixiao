<?php
namespace AppKeeper\Controller;
use AppKeeper\Common\AppKeeperController;

class HouseController extends AppKeeperController {

    function _initialize(){
		parent::_initialize();
		
	}


    //管理员通知选择房屋
    public function selectHouse(){
        //定位 located  1_楼栋id, 2_单元id 
        $located = $this->_data['located'];//传入的数据是楼栋还是单元id
        $ids = $this->_data['ids'];//选择的楼栋/单元id（多个用','（英文逗号）拼接起来）
        if(empty($ids)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        // 楼栋
        if($located==1){
            $map['b.id'] = array("in",$ids);
        }else if($located==2){
            $map['u.id'] = array("in",$ids);
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $houseList = M("House")->field("b.name bname,u.uname,f.fname,h.id,h.name housename")->join(" as h left join xly_floor f on h.floorid=f.id left join xly_unit u on f.unitid=u.id left join xly_building as b on u.buildingid=b.id")->where($map)->select();

        $res_data['status'] = 1;
        $res_data['msg'] = "";
        $res_data['data'] = $houseList;

        $this->_return($res_data);
    }



}