<?php
namespace Community\Controller;
use Community\Common\ManageController;
use Community\Common\ExcelReader;
use Community\Common\OLERead;
use Think\Controller;
class WeChatmouthController extends IndexController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->_name='wechat_data_wxh';
	}
    public function index(){  
        $id=$_GET['id'];
        if ($id) {
            $data['id']=$id;
            $rolelist = M('wechat_data')->where($data)->select();
            session("WeChatmouth",$rolelist[0]['time']);
        } 
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
        if ($_SESSION['WeChatmouth'] != "") {
            $names=$_SESSION['WeChatmouth'];
            $where['time']  = array('like',"$names");
            $where['_logic'] = 'or';
            // session('WeChatmouth',null);
            $map['_complex'] = $where;
        }
        $arrJoins[] = " as mouth left join ".$this->_qz."wechat as wt on wt.id = mouth.wx_id ";
        $fields = "wt.name,wt.wxid,mouth.id,mouth.time,mouth.tw_number,mouth.wx_number";
        $data['arrJoins'] = $arrJoins;
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