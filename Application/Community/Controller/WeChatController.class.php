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
        if ($_SESSION['Wechates'] != "") {
            $time=time();
            $time=$time-300;
            if ($_SESSION['Wechates']>$time) {
                $data['ipid']=$_SESSION['ipid'];
                $data['type']=1;
                $data['database']=$this->_name;
                $data['addtime']=time();
                $rolelist = M('manage_ip_use')->add($data);
            }else{
            }
        }else{
            $data['ipid']=$_SESSION['ipid'];
            $data['type']=1;
            $data['database']=$this->_name;
            $data['addtime']=time();
            $rolelist = M('manage_ip_use')->add($data);
            session("Wechates",time());
        }

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