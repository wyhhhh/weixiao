<?php
namespace AppKeeper\Controller;
use AppKeeper\Common\AppKeeperController;

class InformationController extends AppKeeperController {

    function _initialize(){
		parent::_initialize();
		
	}

	//社区报修人员消息读取状态
    public function noticeRead(){
        // $manageid = $this->_data['manageid'];//当前管理员用户的ID
        $informationid = $this->_data['informationid'];//发布的通知消息的ID
        $isread = $this->_data['isread'];//读取状态:0_未读（默认）, 1_已读
        $username = $this->_data['username'];//要搜索的用户名
        // $type = $this->_data['type'];//消息类型:1_物业管理通知DD维修,2_物业通知社区服务人员, 3_系统推送消息到用户/住户, 4_网站反馈结果通知消息
        if(empty($informationid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

    	$model = M("Information");
    	$pagesize = 10;
    	$count = $model->count($mod_pk);
    	$pager = new \Think\Page($count, $pagesize);
      $map['i.id'] = $informationid;
    	// $map['i.type'] = $type;//消息类型:1_物业管理通知DD维修,2_物业通知社区服务人员, 3_系统推送消息到用户/住户, 4_网站反馈结果通知消息
      if($isread!=""){
          $map['ir.isread'] = $isread;
      }
      if(!empty($username)){
          $map['cr.name'] = array('like',"%$username%");
      }
      // $map['i.manageid'] = $manageid;
      $information = $model->field("cr.name,case ir.isread when 1 then '已读' when 0 then '未读' end as isread")->join(" as i left join ".$this->_qz."information_relation as ir on ir.informationid=i.id left join ".$this->_qz."community_repair as cr on ir.ralationid=cr.id")->where($map)->order("i.addtime desc")->limit($pager->firstRow.','.$pager->listRows)->select();
      if(empty($information)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
      // p($model->getlastsql());
    	$res_data['status'] = 1;
        $res_data['msg'] = "";
        $res_data['data'] = $information;
    	$this->_return($res_data);
    }

    //管理员发出的消息通知列表
    public function informationList(){
      $type = $this->_data['type'];//通知消息的类型type (维修员、住户)
    	$manageid = $this->_data['manageid'];//当前管理员用户的ID
        $pageNumber = $this->_data['pageNumber'];//当前第几页
        if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
    	if(empty($manageid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $this->_return($res_data);
        }
    	$model = M("Information");
    	$pagesize = 7;
    	$count = $model->count($mod_pk);
    	$pager = new \Think\Page($count, $pagesize);
    	$map['manageid'] = $manageid;
        if(!empty($type)){
           $map['type'] = $type;//消息类型:1_物业管理通知DD维修,2_物业通知社区维修人员, 3_系统推送消息到用户/住户, 4_网站反馈结果通知消息
        }else{
            //默认只需2，3类型的
    	    $map['type'] = array("in","2,3");//消息类型:1_物业管理通知DD维修,2_物业通知社区维修人员, 3_系统推送消息到用户/住户, 4_网站反馈结果通知消息
        }
    	$information = $model->field("id,type,title,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime")->where($map)->order("addtime desc")->limit(($pageNumber-1)*5,$pager->listRows)->select();	
      if(empty($information)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
    	$res_data['status'] = 1;
    	$res_data['msg'] = "列表加载成功";
        $res_data['data'] = $information;
    	$this->_return($res_data);
    }

    //管理员消息通知详情查看
    public function informationDetail(){
    	$informationid = $this->_data['informationid'];//当前信息的ID
    	if(empty($informationid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
    	$model = M("Information");
    	$map['id'] = $informationid;
    	$information = $model->field("title,content,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime")->where($map)->find(); 	
      if(empty($information)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
    	$res_data['status'] = 1;
    	$res_data['msg'] = "";
      $res_data['data'] = $information;
    	$this->_return($res_data);
    }

    //管理员发布通知消息给 用户/维修员
    public function informationIssuance(){
        $title = $this->_data['title'];//通知消息的标题
        $content = $this->_data['content'];//通知消息的内容
        $manageid = $this->_data['manageid'];//当前操作管理员id
        $type = $this->_data['type'];//对象数据 type 2_物业通知社区服务人员, 3_系统推送消息到用户/住户
        //定位 located  1_通知定位到的是楼栋, 2_通知定位到的是单元, 3_通知定位到的是楼层
        $located = $this->_data['located'];//传入的数据必须是推送到用户才存在这个字段(这里用户才会有楼栋和房屋的概念)
        $ids = $this->_data['ids'];//传输的id数据，多个用','（英文逗号）隔开

        $manageuser = getFieldData("Keeper",$manageid,"username");//去获取需要的管理员名字

        //数据不全 非法操作判断
        if(empty($ids)||empty($type)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        //要推送的对象是用户时，传入的可能是楼栋id、单元id、楼层id或房屋id，这里统一做处理
        if($type==3){
            if($located==1){
                //楼栋
                $map_['b.id'] = array("in",$ids);
            }else if($located==2){
                //单元
                $map_['u.id'] = array("in",$ids);
            }else if($located==3){
                //楼层
                $map_['f.id'] = array("in",$ids);
            }
            $map_['h.id'] = array("gt",0);
            //得到房屋id
            $r_list = M("Building")->field("GROUP_CONCAT(h.id) as ids")->join(" b left join xly_unit as u on u.buildingid=b.id left join xly_floor f on f.unitid=u.id left join xly_house h on h.floorid=f.id")->where($map_)->find();
            $h_ids = $r_list['ids'];//房屋id
            //为空，选择下的没有用户可通知
            if(empty($h_ids)){
                $res_data['status'] = 0;
                $res_data['msg'] = "通知发布失败, 当前选择下没有可通知用户";
                $res_data['data'] = "";
                $this->_return($res_data);
            }
            //得到用户id
            $map['h.id'] = array("in",$h_ids);
            $map['u.id'] = array("gt",0);
            $users_list = M("House")->field("GROUP_CONCAT(u.id) as ids")->join(" as h left join xly_user_house uh on uh.houseid=h.id left join xly_users u on u.id=uh.userid ")->where($map)->find();
            $ids = $users_list['ids'];
        }
        
        $ids_array = array();
        $ids_array = split(",", $ids);
        $data['type'] = $type;
        $data['title'] = $title;
        $data['content'] = $content;
        $data['addtime'] = time();
        $data['manageid'] = $manageid;
        $data['manageuser'] = $manageuser['username'];
        $information_return = M("Information")->add($data);
        $data_ir = array();
        //封装数组
        foreach ($ids_array as $key => $value) {
            $data_ir[$key]['isread'] = 0;//isread 读取状态:0_未读（默认）, 1_已读
            $data_ir[$key]['ralationid'] = $value;
            $data_ir[$key]['informationid'] = $information_return;
        }
        $ir_return = M("InformationRelation")->addAll($data_ir);

        $res_data['status'] = 1;
        $res_data['msg'] = "发布成功";
        $res_data['data'] = "";
        $this->_return($res_data);
    }

  //   //管理员发布通知消息给 用户/维修员
  //   public function informationIssuance(){
  //   	$title = $this->_data['title'];//通知消息的标题
  //   	$content = $this->_data['content'];//通知消息的内容
  //   	$manageid = $this->_data['manageid'];//当前操作管理员id
  //       $type = $this->_data['type'];//对象数据 type 2_物业通知社区服务人员, 3_系统推送消息到用户/住户
  //       //定位 located  1_通知定位到的是楼栋, 2_通知定位到的是单元, 3_通知定位到的是楼层
  //       $located = $this->_data['located'];//传入的数据必须是推送到用户才存在这个字段(这里用户才会有楼栋和房屋的概念)
  //       $ids = $this->_data['ids'];//传输的id数据，多个用','（英文逗号）隔开

  //       $manageuser = getFieldData("Keeper",$manageid,"username");//去获取需要的管理员名字

  //       //数据不全 非法操作判断
  //       if(empty($ids)||empty($type)){
  //   		$res_data['status'] = 0;
  //           $res_data['msg'] = "非法操作！";
  //           $res_data['data'] = "";
  //           $this->_return($res_data);
  //           return;
  //   	}
  //       //这里判断要推送的对象是用户时，传入的可能是楼栋id、单元id、楼层id或房屋id，这里统一做处理
  //       if($type==3){
  //           if($located!=1&&$located!=2&&$located!=3){
  //               $res_data['status'] = 0;
  //               $res_data['msg'] = "非法操作！";
  //               $res_data['data'] = "";
  //               $this->_return($res_data);
  //               return;
  //           }
  //           //1 这里处理的是传入楼栋id时
  //           if($located==1){
  //               $located=2;//保证能进入下一个if
  //               //楼栋  这里 ids 为楼栋 先找到此楼下的单元
  //               $map_unit['buildingid'] = array("in",$ids);
  //               $unitList = M("Unit")->field("GROUP_CONCAT(id) as ids")->where($map_unit)->find();
  //               $ids = $unitList['ids'];//下面的单元
  //               if(empty($ids)){
  //                   //下面没有单元，不让进行下一步..........
  //                   $res_data['status'] = 0;
  //                   $res_data['msg'] = "通知发布失败, 当前选择楼栋下没有可通知用户";
  //                   $res_data['data'] = "";
  //                   $this->_return($res_data);
  //               }
  //           }
  //           //2 这里处理的是传入单元id时
  //           if($located==2){
  //               $located=3;//保证能进入下一个if
  //               $map_floor['unitid'] = array("in",$ids);
  //               //单元  这里 ids 为单元 先找到此单元下的楼层
  //               $floorList = M("Floor")->field("GROUP_CONCAT(id) as ids")->where($map_floor)->find();
  //               $ids = $floorList['ids'];//下面的楼层
  //               if(empty($ids)){
  //                   //下面没有楼层，不让进行下一步..........
  //                   $res_data['status'] = 0;
  //                   $res_data['msg'] = "通知发布失败, 当前选择单元下没有可通知用户";
  //                   $res_data['data'] = "";
  //                   $this->_return($res_data);
  //               }
  //           }
  //           //3 这里处理的是传入楼层id时
  //           if($located==3){
  //               //楼层  这里 ids 为楼层 先找到此楼层下的房屋
  //               $map_house['floorid'] = array("in",$ids);
  //               $houseList = M("House")->field("GROUP_CONCAT(id) as ids")->where($map_house)->find();
  //               $ids = $houseList['ids'];//下面的房屋
  //               if(empty($ids)){
  //                   //下面没有房屋，不让进行下一步..........
  //                   $res_data['status'] = 0;
  //                   $res_data['msg'] = "通知发布失败, 当前选择楼层下没有可通知用户";
  //                   $res_data['data'] = "";
  //                   $this->_return($res_data);
  //               }
  //               //房屋  这里 ids 为房屋
  //           }
  //           //这里得到的是房屋id了
  //           $map['h.id'] = array("in",$ids);
  //           $map['u.id'] = array("gt",0);
  //           //这里的 ids 是房屋号id时
  //           $users_list = M("House")->field("GROUP_CONCAT(u.id) as ids")->join(" as h left join xly_user_house uh on uh.houseid=h.id left join xly_users u on u.id=uh.userid ")->where($map)->find();
  //           $ids = $users_list['ids'];
  //       }

  //       $ids_array = array();
	 //    $ids_array = split(",", $ids);
  //   	$data['type'] = $type;
  //   	$data['title'] = $title;
  //   	$data['content'] = $content;
  //   	$data['addtime'] = time();
  //   	$data['manageid'] = $manageid;
  //   	$data['manageuser'] = $manageuser['username'];
  //   	$information_return = M("Information")->add($data);
  //   	$data_ir = array();
  //   	//封装数组
  //   	foreach ($ids_array as $key => $value) {
	 //    	$data_ir[$key]['isread'] = 0;//isread 读取状态:0_未读（默认）, 1_已读
	 //    	$data_ir[$key]['ralationid'] = $value;
	 //    	$data_ir[$key]['informationid'] = $information_return;
  //   	}
		// $ir_return = M("InformationRelation")->addAll($data_ir);

  //   	$res_data['status'] = 1;
  //   	$res_data['msg'] = "发布成功";
  //       $res_data['data'] = "";
  //       $this->_return($res_data);
  //   }

    // //管理员端 通知消息类别 类别(维修员/用户)
    // public function informationType(){
    //     $manageid = $this->_data['manageid'];//当前操作管理员id
    //     if(empty($manageid)){
    //         $res_data['status'] = 0;
    //         $res_data['msg'] = "非法操作！";
    //         $res_data['data'] = "";
    //         $this->_return($res_data);
    //         return;
    //     }
    //     $model = M("Information");
    //     $map['manageid'] = $manageid;
    //     //type 消息类型:1_物业管理通知DD维修,2_物业通知社区服务人员, 3_系统推送消息到用户/住户, 4_网站反馈结果通知消息
    //     $map['type'] = 1;//物业管理通知DD维修
    //     $information_1 = $model->where($map)->count();
    //     $map['type'] = 2;//物业通知社区服务人员
    //     $information_2 = $model->where($map)->count();

    //     $res_data['status'] = 1;
    //     $res_data['msg'] = "";
    //     $type = array();
    //     //消息类型:1_物业管理通知DD维修,2_物业通知社区维修人员, 3_系统推送消息到用户/住户, 4_网站反馈结果通知消息
    //     $type[0]['count'] = $information_1;
    //     $type[0]['name'] = "维修人员";
    //     $type[0]['type'] = 2;
    //     $type[1]['count'] = $information_2;
    //     $type[1]['name'] = "住户";
    //     $type[1]['type'] = 3;

    //     $res_data['data'] = $type;
    //     $this->_return($res_data);
    // }


}