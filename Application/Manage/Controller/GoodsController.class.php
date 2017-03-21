<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class GoodsController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->assignList("community","cname",$map = array(),$strSort = '',$fields = '*',$arrJoins);
	}

	public function _before_getList()
    {
        $key =I('get.key');
        
        // session('gid',$gid);
        // session('key',$key);
        if ($key == 1) {
            $map = array();
            $fields = "";
            $arrJoins = array();
            
            //搜索设置
            $name = I('get.name');
            if ($name != "") {
                $where['g.name']  = array('like',"%$name%");
                $where['u.real_name']  = array('like',"%$name%");
                $where['_logic'] = 'or';
                $map['_complex'] = $where;
            }
            $status = I('get.status');
            if ($status != "") {
                $map['g.status'] = $status;
            }
            $community = I('get.community');
            if ($community !="") {
                $map['c.id'] = $community;
            }
            $map['g.toppid'] = array("exp","is Null");

            $arrJoins[] = "as g left join ".$this->_qz."users as u on u.id = g.userid";//链接住户表
            $arrJoins[] = "left join ".$this->_qz."community as c on c.id = g.communityid";//链接社区表
            $fields = "g.id,u.real_name as uname,g.price,g.name,g.images,c.name as cname,g.status";
            $data['arrJoins'] = $arrJoins;
            $data['map'] = $map;
            $data['fields'] = $fields;
        }else if ($key == 2) {
            $gid = I('get.gid');
            $arrJoins = array();
            $map = array();
            $fields = "";
             // 名称
            if (!empty($gid)) {
                $map['g.id'] = $gid;
            }
            $name = I('get.name');
            if ($name != "") {
                $where['g.name']  = array('like',"%$name%");
                $where['uname']  = array('like',"%$name%");
                $where['_logic'] = 'or';
                $map['_complex'] = $where;
            }
            $communityid = I('get.communityid');
            if ($communityid !="") {
                $map['g2.communityid'] = $communityid;
            }
            //字段列表
            $fields = "g2.id,g.name,g2.description,u.real_name as uname,from_UNIXTIME(g2.addtime,'%Y-%m-%d %H:%i') as addtime,c.name as cname";
            $map['g2.toppid'] = array("exp","is not Null");

            //连表
            $arrJoins[] = "as g left join ".$this->_qz."goods as g2 on g2.toppid = g.id";
            $arrJoins[] = "left join ".$this->_qz."users as u on u.id = g2.userid";
            $arrJoins[] = "left join ".$this->_qz."community as c on c.id = g2.communityid";
            $data['arrJoins'] = $arrJoins;
            $data['map'] = $map;
            $data['fields'] = $fields;
        }
        return $data;
    }
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
    //留言详情显示之前对数据的操作
    public function _before_detail_data($data){
        $map = array();
        $map['g.id'] = $data['id'];
        $result = M('Goods')->field("g.id,g2.name,g.description,u.real_name as uname,from_UNIXTIME(g.addtime,'%Y-%m-%d %H:%i') as addtime")->where($map)->join("as g left join ".$this->_qz."goods as g2 on g.toppid = g2.id left join ".$this->_qz."users as u on u.id = g.userid")->find();
        $data = $result;
        return $data;
    }

}