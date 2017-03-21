<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class RepairController extends AppUserController {

    function _initialize(){
		parent::_initialize();
		
	}
    //我的维修订单接口
    public function repair(){
        $userid = $this->_data['userid'];//当前登录用户的ID
        $pageNumber = $this->_data['pageNumber'];//当前第几页
        if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
        if (empty($userid)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $this->_return($res_data);
        }
        $model = M("Repair");
        $count = $model->count($mod_pk);
        $pagesize = 5;
        $pager = new \Think\Page($count,$pagesize);
        $map = array();
        $map['r.userid'] = $userid;
        $map['r.isdelete'] = 0;//是否删除: 0_正常（默认）, 1_已删除
        $map['r.status'] = 1;//维修发布状态: 1_正常（默认）, 2_主动撤销,  3_管理员取消
        $repair = $model->field("r.id,rr.status,r.typename,r.images,r.name,r.description,r.service_place,from_UNIXTIME(r.addtime,'%Y-%m-%d %H:%i') as addtime")->where($map)->join("as r left join ".$this->_qz."repair_relation as rr on rr.repairid = r.id")->limit(($pageNumber-1)*5,$pager->listRows)->order("addtime desc")->select();
        if (empty($repair)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "没有数据";
            $this->_return($res_data);
        }
        $res_data['data'] = $repair;
        foreach ($repair as $key => $value) {//转换base64图片格式
            $images = $value['images'];
            $images = json_decode($images,true);
            // $repair[$key]['images'] = $images;
            $res_data['data'][$key]['images'] = $images;
        }
        // foreach ($repair as $key => $item) {
        //         $res_data['data'][$key] = $item;       
        //         $model = M("RepairRelation");//查询服务详情
        //         $map = array();
        //         $map['repairid'] = $item['id'];
        //         $repair_relation = $model->field("status")->where($map)->find();  
        //         // P($repair_relation);
        //         if($repair_relation){       
        //             if ($repair_relation['status'] == 0) {
        //                 $res_data['data'][$key]['status'] = "分配维修人员";
        //                 }elseif ($repair_relation['status'] == 1) {
        //                     $res_data['data'][$key]['status'] = "正在维修";
        //                 }elseif ($repair_relation['status'] == 2) {
        //                     $res_data['data'][$key]['status'] = "维修完成";
        //                 }elseif ($repair_relation['status'] == 3) {
        //                     $res_data['data'][$key]['status'] = "用户取消";
        //                 }elseif ($repair_relation['status'] == 4) {
        //                     $res_data['data'][$key]['status'] = "管理员取消";
        //                 };
        //             };  
        //         }
        if (!empty($repair)) {
            $res_data['status'] = 1;
            $res_data['msg'] = "订单加载成功";  
        }

        $this->_return($res_data);
    }
    //我的维修订单删除接口
    public function delete(){
        $repairid = $this->_data['repairid'];//当前删除的订单的ID
        $map = array();
        $map['repairid'] = $repairid;
        $repairdata = M('RepairRelation')->field('paystatus')->where($map)->find();
        if (empty($repairdata)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "没有数据";
            $this->_return($res_data);
        }
        if ($repairdata['paystatus'] == 1 || $repairdata['paystatus'] == 3) {
            //用户缴费状态: 0_未缴费（默认）, 1_已缴费, 2_缴费退款中, 3_已退款,只有已缴费或者已退款的订单，用户才可以删除
            $map = array();
            $map['id'] = $repairid;
            $map['status'] = 1;//维修发布状态: 1_正常（默认）, 2_主动撤销,  3_管理员取消
            $data['isdelete'] = 1;//是否删除: 0_正常（默认）, 1_已删除
            $result = M("Repair")->where($map)->save($data);
            $res_data['status'] = 1;
            $res_data['msg'] = "删除成功";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "只有已缴费或者已退款的订单，才可以删除";
            $this->_return($res_data);
        }
        $this->_return($res_data);
    }


    //维修订单详情接口
    public function repairDetail(){
        $repairid = $this->_data['repairid'];
        // $communityid = $this->_data['communityid'];
        if (empty($repairid)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $this->_return($res_data);
        }
        $map = array();
        $map['id'] = $repairid;
        $map['status'] = 1;//维修发布状态: 1_正常（默认）, 2_主动撤销,  3_管理员取消
        $repair = M("Repair")->field("service_place,description,name,images,from_UNIXTIME(service_starttime,'%Y-%m-%d %H:%i') as service_starttime")->where($map)->find();
        if (empty($repair)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "没有数据";
            $this->_return($res_data);
        }
        $images = $repair['images'];
        $images = json_decode($images,true);
        $repair['images'] = $images;
        // $model = M("RepairRelation");//查询服务详情
        // $map = array();
        // $map['repairid'] = $repairid;
        // $repair_relation = $model->field(" repairuserid,repairid,status,paystatus,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime ")->where($map)->find();  
        // // P($repair_relation);
        // if($repair_relation){    
        //     $res_data['status'] = 1;      
        //     $res_data['data'] = $repair_relation;
        //     if ($repair_relation['status'] == 0) {
        //         $res_data['data']['status'] = "分配维修人员";
        //         }elseif ($repair_relation['status'] == 1) {
        //             $res_data['data']['status'] = "正在维修";
        //         }elseif ($repair_relation['status'] == 2) {
        //             $res_data['data']['status'] = "维修完成";
        //         }elseif ($repair_relation['status'] == 3) {
        //             $res_data['data']['status'] = "用户取消";
        //         }elseif ($repair_relation['status'] == 4) {
        //         $res_data['data']['status'] = "管理员取消";
        //         };
        //     if ($repair_relation['paystatus'] == 0) {
        //             $res_data['data']['paystatus'] ="未缴费";
        //         }elseif ($repair_relation['paystatus'] == 1) {
        //             $res_data['data']['paystatus'] ="已缴费";
        //         }elseif ($repair_relation['paystatus'] == 2) {
        //             $res_data['data']['paystatus'] ="缴费退款中";
        //         }elseif ($repair_relation['paystatus'] == 3) {
        //         $res_data['data']['paystatus'] ="已退款";
        //         };
        //         $res_data['msg'] = "详情加载成功";
        //     }    
        $res_data['status'] = 1;
        $res_data['data'] = $repair;
        $res_data['msg'] = "详情加载成功";
        $this->_return($res_data);
    }
        //催单接口
        public function reminder(){
            $repairid = $this->_data['repairid'];//当前维修订单的ID
            if (empty($repairid)) {
                $res_data['status'] = 0;
                $res_data['msg'] = "非法操作！";
                $this->_return($res_data);
            }
            $date = time();  //获取当前催单时间
            $map = array();
            $map['repairid'] = $repairid;
            $repairdata = M("RepairRelation")->field("lasy_hie,reminder")->where($map)->find();
            if (empty($repairdata)) {
                $res_data['status'] = 0;
                $res_data['msg'] = "没有数据";
                $this->_return($res_data);
            }
            $config = M("Config")->field("hie_interval")->find();
            $hie_interval = $config['hie_interval']*60000;//规定标准时间差3600000秒
            $lasy_hie = $repairdata['lasy_hie'];//上一次催单的时间
            $difference = ($date - $lasy_hie);
            if (empty($repairdata['lasy_hie']) || $hie_interval<=$difference) {// 上次催单时间为空或者催单时间间隔大于系统规定催单时间间隔时，可进行催单
                $reminder['lasy_hie'] = $date;//当前催单时间入库
                $reminder['reminder'] = array("exp","reminder+1");//催单次数加1;
                $map = array();
                $map['repairid'] = $repairid;
                $result = M("RepairRelation")->where($map)->save($reminder);
                $res_data['status'] = 1;
                $res_data['msg'] = "催单成功";

            }else{
                $res_data['status'] = 0;
                $res_data['msg'] = "1个小时内不能连续催单";
            }
            $this->_return($res_data);
        }

        //取消订单接口 
        public function cancle(){
            $repairid = $this->_data['repairid'];//取消的维修订单的ID
            $cancle['reason'] = $this->_data['cancle'];//提交的取消原因
            if (empty($repairid) || empty($cancle)) {
                $res_data['status'] = 0;
                $res_data['msg'] = "非法操作！";
                $this->_return($res_data);
            }
            $map = array();
            $map['id'] = $repairid;
            $data['status'] = 2;//维修发布状态: 1_正常（默认）, 2_主动撤销,  3_管理员取消
            $result1 = M("Repair")->where($map)->save($data);//数据库订单逻辑删除
            if (empty($result1)) {
                $res_data['status'] = 0;
                $res_data['msg'] = "没有数据";
                $this->_return($res_data);
            }
            $model = M("RepairRelation");
            $result2 = $model->where($map)->save($cancle);
            if ($result2) {
                $res_data['status'] = 1;
                $res_data['msg'] = "取消成功";               
            }else{
                $res_data['status'] = 0;
                $res_data['msg'] = "你已经取消过了";
            }
            $this->_return($res_data);

        }
        //异议反馈接口
        public function dissent(){
            $repairrelationid = $this->_data['repairrelationid'];//当前维修主表订单的ID
            $userid = $this->_data['userid'];//当前登录用户的ID
            $content = $this->_data['content'];//提交的内容
            $image = $this->_data['images'];//上传的图片 (没做)
            if (empty($userid) || empty($repairrelationid)) {
                $res_data['status'] = 0;
                $res_data['msg'] = "非法操作！";
                $this->_return($res_data);
            }
            if (empty($content)) {
                $res_data['status'] = 0;
                $res_data['msg'] = "请填写内容";
                $this->_return($res_data);
            }
            $time = time();
            $data['userid'] = $userid;
            $data['repairrelationid'] = $repairrelationid;
            $data['content'] = $content;
            $data['images'] = $image;
            $data['addtime'] = $time;
            $map = array();
            $map['repairrelationid'] = $repairrelationid;
            $result = M("RepairDissent")->where($map)->add($data);
            if ($result) {
                $res_data['status'] = 1;
                $res_data['msg'] = "反馈提交成功";
            }
            $this->_return($res_data);
        }



        //支付成功之后的评分接口
        public function grade(){
            $repairUserid = $this->_data['repairUserid'];//当前维修人员的ID
            $repairid = $this->_data['repairid'];//当前维修订单的ID
            $score['evaluate'] = $this->_data['score'];//获取当前得到的星星数
            if (empty($repairUserid) || empty($repairid)) {
                $res_data['status'] = 0;
                $res_data['msg'] = "非法操作";
                $this->_return($res_data);
            }
            $map = array();
            $map['repairid'] = $repairid;
            $result = M("RepairRelation")->where($map)->save($score);//把星星数写入服务关联表记录
                if (empty($result)) {
                $res_data['status'] = 0;
                $res_data['msg'] = "没有数据";
                $this->_return($res_data);
            }
            $map = array();
            $map['repairuserid'] = $repairUserid;
            $evaluate = M("RepairRelation")->field("evaluate")->where($map)->select();
            foreach ($evaluate as $key => $item) {
                $allevaluate[$key]=$item['evaluate'];
            }
            $allevaluatedata = array_sum($allevaluate);//计算所有订单的评分的总和
            $count = M("RepairRelation")->where($map)->count($mod_pk);//计算当前维修人员总共的维修订单数量
            $average = round($allevaluatedata/$count);//得到当前维修人员的平均评分
            $averagedata['grade'] = $average;
            $map = array();
            $map['id'] = $repairUserid;
            $map['isshow'] = 1;//是否启用: 0_不启用, 1_启用（默认）
            $map['status'] = 2;//审核状态: 0_待审核（默认）, 1_初审通过, 2_复审通过
            $map['sequestration'] = 1;//账号状态: 0_封号, 1_正常（默认）
            $result = M("RepairUser")->where($map)->save($averagedata);
            $res_data['status'] = 1;
            $res_data['msg'] = "感谢你的评价";
            $this->_return($res_data);
        }

        //用户端维修/服务主页
        public function index(){
            $model = M("Banner");
            //查询用户维修主页banner
            $pagesize = 3;
            $count = $model->count($mod_pk);
            $pager = new \Think\Page($count, $pagesize);
            $map['location'] = 2;//location 广告位置: 1_物业/首页（默认）, 2_维修页面
            $banner = $model->field("title,url,image")->where($map)->order("sort asc")->limit($pager->firstRow.','.$pager->listRows)->select();
            $res_data['status'] = 1;
            $res_data['msg'] = "";
            $res_data['data'] = $banner;

            $this->_return($res_data);
        }
        
        //用户端维修/服务选择大类类型
        public function servicesBigType(){
            $type = $this->_data['type'];//需要传入服务类型
            $res_data = array();
            //防止空type和乱传数据
            if($type==""||($type!=1&&$type!=2)){
                $res_data['status'] = 0;
                $res_data['msg'] = "非法操作！";
                $res_data['data'] = "";
            }else{
                $model = M("RepairType");
                $map['isshow'] = 1;//isshow 是否显示: 0_不显示, 1_显示（默认） 注：不显示的商品类型下的商品全部会下架
                $map['type'] = $type;//type 服务类型: 1_维修, 2_家政服务
                $map['pid'] = 0;//最大的类型
                $repairType = $model->field("id,name")->where($map)->order("sort asc")->select();
                $res_data['status'] = 1;
                $res_data['msg'] = "";
                $res_data['data'] = $repairType;
            }

            $this->_return($res_data);
        }

        //用户端维修/服务选择小类类型
        public function servicesSmallType(){
            $typeid = $this->_data['typeid'];//需要传入服务大类类型id
            $res_data = array();
            //防止空type和乱传数据
            if($typeid==""){
                $res_data['status'] = 0;
                $res_data['msg'] = "非法操作！";
                $res_data['data'] = "";
            }else{
                $model = M("RepairType");
                $map['isshow'] = 1;//isshow 是否显示: 0_不显示, 1_显示（默认） 注：不显示的商品类型下的商品全部会下架
                $map['pid'] = $typeid;//最大的类型
                $repairType = $model->field("id,name")->where($map)->order("sort asc")->select();
                $res_data['status'] = 1;
                $res_data['msg'] = "";
                $res_data['data'] = $repairType;
            }
            $this->_return($res_data);
        }

        //用户端发布维修/服务
        public function servicesRepair(){
            $typeid = $this->_data['typeid'];//需要传入服务小类类型id
            $userid = $this->_data['userid'];//需要传入当前登录的用户id
            $baseImages = $this->_data['images'];//上传的图片(base64数组)
            $service_starttime = $this->_data['service_starttime'];//期望服务开始时间
            $service_endtime = $this->_data['service_endtime'];//期望服务结束时间
            $res_data = array();
            //防止空type和乱传数据
            if($typeid==""){
                $res_data['status'] = 0;
                $res_data['msg'] = "非法操作！";
                $res_data['data'] = "";
                $this->_return($res_data);
            }else if($userid==""){
                $res_data['status'] = 0;
                $res_data['msg'] = "用户不存在或登录超时！";
                $res_data['data'] = "";
                $this->_return($res_data);
            }
            //确认用户是否存在
            $map_user['id'] = $userid;
            $users_return = M("Users")->field("id")->where($map_user)->find();
            if(!$users_return){
                $res_data['status'] = 0;
                $res_data['msg'] = "用户不存在或登录超时！";
                $res_data['data'] = "";
                $this->_return($res_data);
            }
            //确认类型是否存在
            $map_repair_type['id'] = $typeid;
            $repair_type_return = M("RepairType")->field("id,name")->where($map_repair_type)->find();
            if(!$repair_type_return){
                $res_data['status'] = 0;
                $res_data['msg'] = "服务类型不存在！";
                $res_data['data'] = "";
                $this->_return($res_data);
            }
            $file_prefix = "img_";//前缀
            $image = $this->upload_images($baseImages,$file_prefix);//url路径
            $imgdata = explode(",", $image);//路径转成数组
            $img = json_encode($imgdata);//数组转换成JSON数组
            $model = M("Repair");
            $map = $this->_data;//最大的类型
            $map['service_starttime'] = strtotime($service_starttime);
            $map['service_endtime'] = strtotime($service_endtime);
            $map['addtime'] = time();
            $map['status'] = 1;//status 维修发布状态: 1_正常（默认）, 2_主动撤销,  3_管理员取消
            $map['typename'] = $repair_type_return['name'];
            $map['images'] = $img;
            $repair_return = $model->add($map);
            if($repair_return){
                $res_data['status'] = 1;
                $res_data['msg'] = "发布成功！";
                $res_data['data'] = $repair_return;
            }else{
                $res_data['status'] = 0;
                $res_data['msg'] = "发布失败！";
                $res_data['data'] = "";
            }

            // //通知消息 发送到维修员
            // $map_i['type'] = 1;//type 消息类型:1_物业/系统通知DD维修,2_物业/系统通知社区服务人员, 3_系统推送消息到用户/住户, 4_网站反馈结果通知消息
            // $map_i['title'] = $name;
            // $map_i['content'] = $description;
            // $map_i['addtime'] = time();
            // $return_i = M("Information")->add($map_i);
            // //
            // $map_ir['isread'] = 0;//读取状态:0_未读（默认）, 1_已读
            // $map_ir['ralationid'] = $;//应该由谁来读取这条消息
            // $map_ir['informationid'] = $return_i;//关联的通知消息id
            // $return_ir = M("InformationRelation")->add($map_ir);

            $this->_return($res_data);
        }


}