<?php
namespace Community\Controller;
use Community\Common\ManageController;
use Community\Common\ExcelReader;
use Community\Common\OLERead;
use Think\Controller;
class WeChatController extends IndexController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->_name='wechat';
	}
    public function index(){  
        // if ($_GET['id']) {
        //     session("Reading",$_GET['id']);
        // } 
        // $db=M('');
        // $sql="SELECT rts.id,rt.name,rts.name as names FROM `wx_read_type` as rt left join wx_Read_types as rts on rts.typeid = rt.id ORDER BY id";
        // $rolelist =$db->query($sql);
        // $this->assign('rolelist',$rolelist); 
        $this->display("index");
    }
	public function _before_getList(){
        $map = array();
        $fields = "";
        //搜索设置
        $name = I('get.name');
        if ($name != "") {
            $map['name'] = array('like',"%$name%");
        } 
        $fields = "id,name,wxid,if(type=2,'学校微信号','学院微信号') as type,if(status=1,'显示','不显示') as status";
        $data['map'] = $map;
        $data['fields'] = $fields;
        return $data;
    }

    public function _before_insert($data){
        $data["addtime"] = time();
        return $data;
    }

    public function test2(){

        if(IS_POST){
            session("ss","ASD");
            $this->ajaxReturn(1, ' ');
        }
        $this->display();
    }

    //删除判断方法预留
    // public function _before_delete($ids){
    //     $map = array("in",$ids);
        // M("Building")->where($map)->select();
    // }
    

}