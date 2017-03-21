<?php
namespace AppKeeper\Controller;
use AppKeeper\Common\AppKeeperController;

class UnitController extends AppKeeperController {

    function _initialize(){
		parent::_initialize();
		$this->_model = M("Unit");
	}


    //管理员通知选择单元(若楼栋下有单元使用此接口)
    public function selectUnit(){
        $buildingid = $this->_data['buildingid'];//选择的楼栋id（多个用','（英文逗号）拼接起来）
        if(empty($buildingid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        $model = M("Unit");
        $map['buildingid'] = array("in",$buildingid);
        $unitList = $this->_model->field("u.id,concat(u.uname,'(',b.name,')') as u_b")->join(" as u left join xly_building as b on b.id=u.buildingid")->where($map)->select();
        if(empty($unitList)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        // p($this->_model->getlastsql());
        $res_data['status'] = 1;
        $res_data['msg'] = "";
        $res_data['data'] = $unitList;
        $this->_return($res_data);
    }


}