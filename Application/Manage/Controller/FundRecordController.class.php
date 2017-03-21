<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class FundRecordController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->assignList("community","cname",$map = array(),$strSort = '',$fields = '*',$arrJoins);
	}

	public function _before_getList()
    {
        $uid = I('get.uid');
        $map = array();
        $fields = "";
        $arrJoins = array();
        if (!empty($uid)) {
            $map['ru.id'] = $uid;
        }
        //搜索设置
        $name = I('get.name');
        if ($name != "") {
            $where['ru.pet_name']  = array('like',"%$name%");
            $where['ru.job_number']  = array('like',"%$name%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        $typess = I('get.typess');
        if ($typess != "") {
            $map['fr.typess'] = $typess;
        }
        $status = I('get.status');
        if ($status != "") {
            $map['fr.status'] = $status;
        }
        $arrJoins[] = "as fr left join ".$this->_qz."repair_user as ru on ru.id = fr.userid";//链接住户表
        $arrJoins[] = " left join ".$this->_qz."manages as m on m.id = fr.manageid";//链接管理表
        $fields = "fr.id,ru.pet_name,ru.job_number,fr.reason,if(fr.typess = 1,'收入','支出') as typess,case type when 1 then '系统赠送' when 2 then '正常收入' when 3 then '提现' when 4 then '处罚扣款' end as type,fr.sum as sum,from_UNIXTIME(fr.addtime, '%Y-%m-%d %H:%i') as addtime,m.username as managename,from_UNIXTIME(fr.handletime, '%Y-%m-%d %H:%i') as handletime,case fr.status when 0 then '待处理' when 1 then '提现成功' when 2 then '提现失败' when 3 then '扣款成功' when 4 then '扣款失败' end as status";
        $data['arrJoins'] = $arrJoins;
        $data['map'] = $map;
        $data['fields'] = $fields;
        return $data;
    }
}