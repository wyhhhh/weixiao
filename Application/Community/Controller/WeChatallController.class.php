<?php
namespace Community\Controller;
use Community\Common\ManageController;
use Community\Common\ExcelReader;
use Community\Common\OLERead;
use Think\Controller;
class WeChatallController extends IndexController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->_name='wechat_data_all';
	}
    public function index(){  
        $id=$_GET['id'];
        if ($id) {
            $data['id']=$id;
            $rolelist = M('wechat_data_wxh')->where($data)->select();
            $data['id']=$rolelist[0]['wx_id'];
            $role = M('wechat')->where($data)->select();
            session("WeChatall",$role[0]['wxid']);
            session("time",$rolelist[0]['time']);
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
        if ($_SESSION['WeChatall'] != "") {
            $time=$_SESSION['time'];
            $time=explode("-", $time);
            $year=$time[0];
            $mouth=$time[1];
            $time=$year."-".$mouth."-01 00:00:00";
            $a=strtotime($time);
            $mouth=$mouth+1;
            if ($mouth<10) {
                $mouth="0".$mouth;
            }
            if ($mouth==12) {
                $mouth=1;
                $year=$year+1;
            }
            $time=$year."-".$mouth."-01 00:00:00";
            $b=strtotime($time);
            $names=$_SESSION['WeChatall'];
            $where['wth.wxid']  = array('like',"$names");
            $map['wth.time']  = array('GT',"$a");
            $where['wth.time']  = array('LT',"$b");
            $where['_logic'] = 'and';
            // session('WeChatall',null);
            // session('time',null);
            $map['_complex'] = $where;
        }
        $arrJoins[] = " as wth left join ".$this->_qz."wechat as wt on wt.wxid = wth.wxid ";
        $fields = "wt.name,if(wth.status=1,'显示','不显示') as status,wth.wxid,wth.title,wth.url,wth.time,wth.reading,wth.id";
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