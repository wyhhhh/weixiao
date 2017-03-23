<?php
namespace Community\Controller;
use Community\Common\ManageController;
use Community\Common\ExcelReader;
use Community\Common\OLERead;
use Think\Controller;
class ReadingController extends IndexController {
    function _initialize()
    {
        parent::_initialize();
    }
    public function index(){  
        if ($_SESSION['Readings'] != "") {
            $time=time();
            $time=$time-300;
            if ($_SESSION['Readings']<$time) {
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
            session("Readings",time());
        }
        if ($_GET['id']) {
            session("Reading",$_GET['id']);
        } 
        $db=M('');
        $sql="SELECT rts.id,rt.name,rts.name as names FROM `wx_read_type` as rt left join wx_Read_types as rts on rts.typeid = rt.id ORDER BY id";
        $rolelist =$db->query($sql);
        $this->assign('rolelist',$rolelist); 
        $this->display("index");
    }
    public function _before_getList(){
        $map = array();
        $fields = "";
        $arrJoins = array();
        // session("status",I('get.'));
        //搜索设置
        if ($_SESSION['Reading'] != "") {
            $names=$_SESSION['Reading'];
            $where['rts.id']  = array('like',"$names");
            $where['_logic'] = 'or';
            session('Reading',null);
            $map['_complex'] = $where;
        }
        $typeid = I('get.typeid');
        if ($typeid != "") {
            $where['rts.id']  = array('like',"$typeid");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        $name = I('get.name');
        $mouth1 = I('get.mouth1');
        $year = I('get.year');
        $mouth = I('get.mouth');
        if ($year != ""&&$mouth != "") {
            if ($mouth1) {
                if ($mouth1>$mouth) {
                    for ($is2=$mouth; $is2>=$mouth1 ; $is2++) { 
                        $where['Reading.time']  = array('like',"%$year-$is2-%");
                    }
                }elseif ($mouth1<$mouth) {
                    for ($is2=$mouth; $is2>=$mouth1 ; $is2++) { 
                        $where['Reading.time']  = array('like',"%$year-$is2-%");
                    }
                }else{
                    $where['Reading.time']  = array('like',"%$year-$mouth-%");
                }
            }else{
                $where['Reading.time']  = array('like',"%$year-$mouth-%");
            }
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }elseif ($year != "") {
            $where['Reading.time']  = array('like',"%$year-%");
            // $where['time']  = array('like',"%-$mouth-%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }elseif ($mouth != "") {
            // $where['time']  = array('like',"%$year-%");
            if ($mouth1) {
                if ($mouth1>$mouth) {
                    for ($is2=$mouth; $is2<=$mouth1 ; $is2++) { 
                        $where['Reading.time']  = array('like',"%-$is2-%");
                    }
                }elseif ($mouth1<$mouth) {
                    for ($is2=$mouth; $is2>=$mouth1 ; $is2++) { 
                        $where['Reading.time']  = array('like',"%-$is2-%");
                    }
                }else{
                    $where['Reading.time']  = array('like',"%-$mouth-%");
                }
                $where['_logic'] = 'or';
                $map['_complex'] = $where;
            }else{
                $where['Reading.time']  = array('like',"%-$mouth-%");
                $where['_logic'] = 'or';
                $map['_complex'] = $where;
            }
        }
        if ($name != "") {
            $where['Reading.title']  = array('like',"%$name%");
            // $where['time']  = array('like',"%$year-%");
            // $where['time']  = array('like',"%-$mouth-%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        //链接查询
        $arrJoins[] = " as Reading left join ".$this->_qz."read_types as rts on rts.id = Reading.typeid ";
        $arrJoins[] = "left join ".$this->_qz."read_type as rt on rt.id = rts.typeid ";
        //字段列表
        $fields = "if(Reading.status=1,'显示','不显示') as status,Reading.id,Reading.title,Reading.url,Reading.time,Reading.reading,concat(rts.name,'-',rt.name) as type";
        $data['arrJoins'] = $arrJoins;
        $data['map'] = $map;
        $data['fields'] = $fields;
        return $data;
    }
    public function editall(){
        if (IS_POST)
        { 
            $typeid = I('post.typeid'); 
            $id = explode(',',$_SESSION['editall']);
            session('editall',null);
            $date['typeid']=$typeid;
            for ($i=0;$id[$i]; $i++) { 
                $data['id']=$id[$i];
                $rolelist = M('reading')->where($data)->save($date);
            }
            if ($rolelist) {
                IS_AJAX && $this->ajaxReturn(1, '操作成功！' , '', 'edit');
                $this->success('操作成功！');
            }else{
                IS_AJAX && $this->ajaxReturn(0,'操作失败！');
                $this->error('操作失败！');
            }
        }else{
            $ids = I('get.id');
            $db=M('');
            $sql="SELECT rts.id,rt.name,rts.name as names FROM `wx_read_type` as rt left join wx_Read_types as rts on rts.typeid = rt.id ORDER BY id";
            $rolelist =$db->query($sql);
            $this->assign('rolelist',$rolelist);     
            session("editall",$ids);    
            $this->display("editall");
        }
    }
    //加载添加页面之前
    public function _before_add(){
        //查询角色
        // $rolelist = M('Community')->field("id,name")->order('id desc')->select();
        // $this->assign('rolelist',$rolelist);
        $time=time();
        $this->assign('time',$time);
    }
    public function _before_all_insert($date) {
        $date['sort'] = $date['dy'];
        return $date;
    }
    //入库之前
    public function _before_insert($data){
        return $data;
    }

    //修改之前
    public function _before_edit(){
        //查询角色
        $db=M('');
        $sql="SELECT rts.id,rt.name,rts.name as names FROM `wx_read_type` as rt left join wx_Read_types as rts on rts.typeid = rt.id ORDER BY id";
        $rolelist =$db->query($sql);
        $this->assign('rolelist',$rolelist);        
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