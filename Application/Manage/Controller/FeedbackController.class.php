<?php
namespace Manage\Controller;
use Manage\Common\ManageController;
use Think\Controller;
class FeedbackController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
	}
	public function _before_getList(){
        $map = array();
        $fields = "";
        $arrJoins = array();
        // session("status",I('get.'));
        //搜索设置
        $name = I('get.name');
        if ($name != "") {
            $where['fb.content']  = array('like',"%$name%");
            $where['u.real_name']  = array('like',"%$name%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        //条件
        $status = I('get.status');
        // session("status",$status);
        if ($status != "") {
            $map['fb.status'] = $status;
        }
        //链接查询
        $arrJoins[] = " as fb left join ".$this->_qz."users as u on u.id = fb.userid ";
        //字段列表
        $fields = "fb.id,fb.userid,from_UNIXTIME(fb.addtime,'%Y-%m-%d %H:%i') as addtime,fb.content,u.real_name,fb.status ";
        $data['arrJoins'] = $arrJoins;
        $data['map'] = $map;
        $data['fields'] = $fields;
        return $data;
    }
    //修改字段之前
    public function _before_field_edit()
    {
        $data = array();
        //范围多个用逗号隔开，如: 0,1 （不需要则为空）
        $data['range'] = "0,1";
        //条件 (除传过来的id外的附加条件，不需要则为空)
        $map = array();
        $data['map'] = $map;
        //字段名 (必须设置)
        $data['fieldname'] = "status";

        return $data;
    }
    //详情显示之前
    public function _before_detail_data($data){
        $map = array();
        $map['fb.id'] = $data['id'];
        //链表查询
        $list = M("Feedback")->field("fb.id,fb.userid,from_UNIXTIME(fb.addtime,'%Y-%m-%d %H:%i') as addtime,fb.content,u.real_name")->join(" as fb left join ".$this->_qz."users as u on u.id = fb.userid ")->where($map)->find();
        $data = $list;
        return $data;
    }


}