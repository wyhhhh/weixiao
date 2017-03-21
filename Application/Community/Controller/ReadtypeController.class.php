<?php
namespace Community\Controller;
use Community\Common\ManageController;
use Community\Common\ExcelReader;
use Community\Common\OLERead;
use Think\Controller;
class ReadtypeController extends IndexController {
    function _initialize()
    {
        parent::_initialize(); 
        $this->_name='Read_type';
    }
    public function index(){  
        $this->display("index");
    }
    public function _before_getList(){
        $map = array();
        $fields = "";
        $arrJoins = array();
        // session("status",I('get.'));
        //搜索设置
        $name = I('get.name');
        if ($name != "") {
            $where['name']  = array('like',"%$name%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        //链接查询
        // $arrJoins[] = " as rt left join ".$this->_qz."Read_types as rts on rts.typeid = .id ";
        //字段列表
        $fields = "(select count(*) from wx_Read_types where wx_Read_types.typeid = wx_Read_type.id) as typenum,id,name,if(status=1,'显示','不显示') as status,addtime";
        $data['arrJoins'] = $arrJoins;
        $data['map'] = $map;
        $data['fields'] = $fields;
        return $data;
    }

    //加载添加页面之前
    public function _before_add(){
        //查询角色
        $rolelist = M('read_type')->field("id,name")->order('id asc')->select();
            $role[0]['name']="无";
            $role[0]['id']="0";
        for ($i=0;$rolelist[$i]; $i++) { 
            $i1=$i+1;
            $role[$i1]['name']=$rolelist[$i]['name'];
            $role[$i1]['id']=$rolelist[$i]['id'];
        }
        $this->assign('rolelist',$role);  
    }

    public function _before_all_insert($date) {
        return $date;
    }
    //入库之前
    public function _before_insert($data){
        $typeid=$data['addtime'];
        if ($typeid==0) {
            $data['addtime'] = time();
            return $data;
        }else{
            $data['typeid']=$typeid;
            $data['addtime'] = time();
            $rolelist = M('read_types')->add($data);
            if ($rolelist) {
                return 2;
            }else{
                return 1;
            }
        }
    }

    //修改之前
    public function _before_edit(){
        //查询角色     
    }

    function _before_update($data){
        $data['addtime'] = time();
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
    //删除判断方法预留
    // public function _before_delete($ids){
    //     $map = array("in",$ids);
        // M("Building")->where($map)->select();
    // }

}