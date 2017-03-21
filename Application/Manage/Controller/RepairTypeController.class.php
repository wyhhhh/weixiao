<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class RepairTypeController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
      // session("id",$_GET['id']);

    }

    public function _before_getList()
    {
        $map = array();
        $fields = "";
        
        //搜索设置
        $name = I('get.name');
        if ($name != "") {
            $map['name'] = array('like',"%$name%");
        }
        $type = I('get.type');
        if (!empty($type)) {
            $map['rt.type'] = $type;
        }
        $fields = "rt.id,rt.name,rt.sort,rt.pid,rt2.name as rname,if(rt.isshow = 1,'显示','不显示') as isshow,if(rt.type = 1,'维修','家政服务') as type";
        $arrJoins[] = "as rt left join ".$this->_qz."repair_type as rt2 on rt2.id = rt.pid";
        $data['map'] = $map;
        $data['fields'] = $fields;
        $data['arrJoins'] = $arrJoins;
        return $data;
    }
    public function getSonValue(){
        $id = I('post.id');//取得顶级类型ID
        $map = array();
        $map['type'] = $id;
        $map['pid'] = 0;
        $map['topid'] = 0;
        $typeSon = M("RepairType")->field("id,name")->where($map)->select();
        $str .="<option value='0' selected='selected'>无</option>";
        foreach ($typeSon as $key => $item) {
            $str.= "<option value = ".$item['id'].">".$item['name']."</option>";
        }
        echo $str;
    }
    //编辑之前。对数据的处理
    public  function _before_edit_data($data){
        $map = array();
        $map['type'] = $data['type'];
        $map['pid'] = 0;
        $map['topid'] = 0;
        $type = M("RepairType")->field("id,name")->where($map)->select();
        $this->assign('type',$type);
        return $data;
    }
    
}