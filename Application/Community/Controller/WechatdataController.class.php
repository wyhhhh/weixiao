<?php
namespace Community\Controller;
use Community\Common\ManageController;
use Community\Common\ExcelReader;
use Community\Common\OLERead;
use Think\Controller;
class WechatdataController extends IndexController {

    function _initialize()
	{
        parent::_initialize(); 
        $this->_name='wechat_data';
	}
    public function index(){
        if ($_SESSION['Wechatss1'] != "") {
            $time=time();
            $time=$time-300;
            if ($_SESSION['Wechatss1']<$time) {
                $data['ipid']=$_SESSION['ipid'];
                $data['type']=1;
                $data['from']=$this->_name;
                $data['addtime']=time();
                $rolelist = M('manage_ip_use')->add($data);
            }else{
            }
        }else{
            $data['ipid']=$_SESSION['ipid'];
            $data['type']=1;
            $data['from']=$this->_name;
            $data['addtime']=time();
            $rolelist = M('manage_ip_use')->add($data);
            session("Wechatss1",time());
        }

        $mouth=date("m",time());
        $year=date("Y",time());
        $mouth=$mouth-1;
        if ($mouth<10) {
            $mouth="0".$mouth;
        }
        if ($mouth==0) {
            $mouth=12;
            $year=$year-1;
        }
        $times=$year."-".$mouth;
        $time=date("d",time());
        if ($time>9) {
            $data['time']=$times;
            $rolelist = M('wechat_data')->where($data)->select();
            if ($rolelist[0]) {

            }else{
                $this->edit_time_data($year,$mouth);
            }
        }
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
        $fields = "id,time,reading,number,addtime";
        $data['map'] = $map;
        $data['fields'] = $fields;
        return $data;
    }
    public function edit_time_data($year,$mouth){
        $alltime=$year.'-'.$mouth;
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
        $data['status']=1;
        $user = M('wechat')->where($data)->select();
        $number=0;
        $readind=0;
        for ($i1=0;$user[$i1]; $i1++) { 
            $wxid=$user[$i1]['wxid'];
            $id=$user[$i1]['id'];
            $db=M('');
            $sql="SELECT SUM(reading) as reading FROM wx_wechat_data_all WHERE time>=$a AND time<=$b AND wxid='$wxid';";
            $rolelist1 =$db->query($sql);
            $sql="SELECT SUM(status) as number FROM wx_wechat_data_all WHERE time>=$a AND time<=$b AND wxid='$wxid';";
            $rolelist2 =$db->query($sql);
            $map['wx_id']=$id;
            $map['time']=$alltime;
            $map['tw_number']=$rolelist2[0]['number'];
            $map['wx_number']=$rolelist1[0]['reading'];
            $role=M("wechat_data_wxh")->add($map);
            $readind=$readind+$rolelist1[0]['reading'];
            $number=$number+$rolelist2[0]['number'];
        }
        $add['time']=$alltime;
        $add['reading']=$readind;
        $add['number']=$number;
        $add['addtime']=time();
        $role=M("wechat_data")->add($add);

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