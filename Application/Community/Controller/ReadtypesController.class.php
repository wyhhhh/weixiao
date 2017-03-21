<?php
namespace Community\Controller;
use Community\Common\ManageController;
use Community\Common\ExcelReader;
use Community\Common\OLERead;
use Think\Controller;
class ReadtypesController extends IndexController {
    function _initialize()
    {
        parent::_initialize(); 
        $this->_name='Read_types';
    }
    public function index(){ 
        if ($_GET['id']) {
            session("Readtypes",$_GET['id']);
        } 
        $this->display("index");
    }
    public function _before_getList(){
        $map = array();
        $fields = "";
        $arrJoins = array();
        // session("status",I('get.'));
        //搜索设置
        if ($_SESSION['Readtypes'] != "") {
            $names=$_SESSION['Readtypes'];
            $where['typeid']  = array('like',"$names");
            $where['_logic'] = 'or';
            session('Readtypes',null);
            $map['_complex'] = $where;
        }
        $name = I('get.name');
        if ($name != "") {
            $where['name']  = array('like',"%$name%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        //链接查询
        // $arrJoins[] = " as building left join ".$this->_qz."community as cu on cu.id = building.communityid ";
        //字段列表
        $fields = "id,name,if(status=1,'显示','不显示') as status,(select count(*) from wx_reading where wx_reading.typeid = wx_Read_types.id) as number,addtime";
        $data['arrJoins'] = $arrJoins;
        $data['map'] = $map;
        $data['fields'] = $fields;
        return $data;
    }

    //加载添加页面之前
    public function _before_add(){
        //查询角色
    }
    public function _before_all_insert($date) {
        return $date;
    }
    //入库之前
    public function _before_insert($data){
        $data['addtime'] = time();
        return $data;
    }

    //修改之前
    public function _before_edit(){
        //查询角色
        // $rolelist = M('Community')->field("id,name")->order('id desc')->select();
        // $this->assign('rolelist',$rolelist);        
    }

    function _before_update($data){
        $data['addtime'] = time();
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
    //删除判断方法预留
    // public function _before_delete($ids){
    //     $map = array("in",$ids);
        // M("Building")->where($map)->select();
    // }

}