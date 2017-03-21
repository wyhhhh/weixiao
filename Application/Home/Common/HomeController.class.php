<?php
namespace Home\Common;
use Common\Common\BaseController;

class HomeController extends BaseController{ 
	function _initialize()
	{
		parent::_initialize();
        
        //配置需要登录的地址
     //    $arr = array();
     //    $arr[] = strtolower('Users:index');//用户个人中心界面
     //    $arr[] = strtolower('IntegralProduct:detail');//积分产品界面
     //    $arr[] = strtolower('ProductOrder:add');//订单界面
     //    // $arr[] = strtolower('Users:index');//
     //    $temp_str = strtolower($this->_name.":".$this->_method);
    	// $userid = session('userid');
     //    if (in_array($temp_str, $arr)) {
     //    	if (empty($userid)) {
	    //     	if (IS_AJAX) {
	    //     		$this->ajaxReturn(0,'登录超时！');
	    //     	}
	    //     	else
	    //     	{
	    //     		//跳转到登录
	    //     		$this->redirect('Home/Users/login');
     //                die();
	    //     	}
     //    	}
     //        else
     //        {
     //            //获取用户禁用
     //            $user = M('Users')->field('status')->find($userid);
     //            if ($user['status'] != 1) {
     //                if (IS_AJAX)
     //                {
     //                    $this->ajaxReturn(0,'你的账号被禁用，请联系管理员！');
     //                }
     //                else
     //                {
     //                    $this->redirect('Home/Users/login');
     //                }
     //                die();
     //            }

     //        }
     //    }

     //    if(!empty($userid)){
     //        $t_users = M("Users")->field("mail")->find($userid);
     //        $this->assign("t_users",$t_users);
     //    }

     //    if (!IS_AJAX && !IS_POST) {
     //        //网站基本信息
     //        $config = M("Config")->find();
     //        $this->assign("Config",$config);
     //    }

     //    $map_pt['pid'] = 0;
     //    $productType = M("ProductType")->field("id,pid,name,name_e,isnav,ishot")->where($map_pt)->order("id desc")->select();
     //    $this->assign("productType",$productType);
     //    $map_s['typeid'] = 1;
     //    $map_s['isshow'] = 1;//显示
     //    $single_1 = M("Single")->field("id,title,content,addtime,typeid")->where($map_s)->order("id desc")->select();
     //    $this->assign("single_1",$single_1);
     //    $map_s['typeid'] = 2;
     //    $single_2 = M("Single")->field("id,title,content,addtime,typeid")->where($map_s)->order("id desc")->select();
     //    $this->assign("single_2",$single_2);
     //    $map_s['typeid'] = 3;
     //    $single_3 = M("Single")->field("id,title,content,addtime,typeid")->where($map_s)->order("id desc")->select();
     //    $this->assign("single_3",$single_3);
     //    $map_s['typeid'] = 4;
     //    $single_4 = M("Single")->field("id,title,content,addtime,typeid")->where($map_s)->order("id desc")->select();
     //    $this->assign("single_4",$single_4);
     //    $map_i['pid'] = array("exp","!=0");
     //    $map_i['isshow'] = 1;
     //    $infoType = M("InfoType")->field("id,name,name_e")->where($map_i)->order("id desc")->select();
     //    $this->assign("infoType",$infoType);
     //    $map_s_b['isshow'] = 1;
     //    $map_s_b['isbottom'] = 1;
     //    $single_b = M("Single")->field("id,title,typeid")->where($map_s_b)->order("id desc")->select();
     //    $this->assign("single_b",$single_b);
   }

   //ajax返回地址
    public function ajax_getArea()
    {
        $pcode = I('get.code');
        $type = I('get.type');//省_1,市_2,区_3---省可以省略
        $key = I('get.keyword');//搜索关键字
        if (IS_POST) {
            $pcode = I('post.code');
            $type = I('post.type');//省_1,市_2,区_3---省可以省略
            $key = I('post.keyword');//搜索关键字
        }
        $list = $this->_getArea($type,$pcode,$keyword);
        $this->ajaxReturn(1,$list);
    }

    /*
    * 查询地址
    * $type 查询类型(省_1,市_2,区_3),当为省时可不传
    * $pcode 上级(省或市)的code，查询下级地区
    * $keyword 关键字，根据关键字查地区名称
    */
    public function _getArea($type = '',$pcode = '',$keyword = '')
    {
        $map = array();
        if (((empty($pcode) && empty($type)) || (empty($pcode) && $type == 1)) || (!empty($pcode) && ($type == 1 || $type == 2 || $type == 3))) {
            if (!empty($key)) {
                $map['name'] = array('like',$key);
            }
            if ($type == 1 || empty($type)) {
                $map['code'] = array('like',"%0000");   
            }
            else if($type == 2) {
                $pcode = substr($pcode,0,2);
                $map['left(code,CHAR_LENGTH(code)-4)'] = array('like',"$pcode%");
                $map['substring(code,3,6)'] = array('neq',"0000");
                $map['substring(code,5,6)'] = "00"; 
            }
            else if($type == 3) {
                //left(CODE,len(CODE)-2) like '%".$s."' and substring(CODE,5,7)!=00 and  substring(CODE,5,7)!=01
                $pcode = substr($pcode,0,4);
                $map['left(code,CHAR_LENGTH(code)-2)'] = array('like',"$pcode%");
                $map['substring(code,5,6)'] = array('neq',"00");
                $map['substring(code,5,7)'] = array('neq',"01");
            }
            $list = M('Area')->where($map)->select();
            return $list;
        }
    }
   
}