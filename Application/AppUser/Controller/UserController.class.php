<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class UserController extends AppUserController {

    function _initialize(){
		parent::_initialize();
		
	}

    // //获取token啊。。。。
    // public function get_token($userId,$name,$portraitUri)
    // {
    //     srand((double)microtime()*1000000);
    //     $appKey = '8brlm7uf8bcz3'; // 开发者 App Key
    //     $appSecret = 'iH0kzREkuOt'; // 开发者平台分配的 App Secret。
    //     $nonce = rand(); // 获取随机数。
    //     $timestamp = time(); // 获取时间戳。
    //     $signature = sha1($appSecret.$nonce.$timestamp);
    //     // p($appSecret."=".$nonce."=".$timestamp);

    //     $method = 'POST';
    //     $url='http://api.cn.ronghub.com/user/getToken.json'; 
    //     $header[] = 'App-Key:'.$appKey;
    //     $header[] = 'Nonce:'.$nonce;
    //     $header[] = 'Timestamp:'.$timestamp;
    //     $header[] = 'Signature:'.$signature;

    //     $param = array();
    //     $param['userId'] = $userId;
    //     $param['name'] = $name;
    //     $param['portraitUri'] = $portraitUri;

    //     //获取token
    //     $token = send_request($url, $param, $header,$method = 'POST',$refererUrl = '', $timeout = 30, $proxy = false);
    //     $token = json_decode($token,true);

    //     return $token['token'];
    // }

    public function user(){
        //用户详细信息接口
        $userid = $this->_data['userid'];//当前用户id  
        if (empty($userid)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $model = M("Users");
        $map = array();
        $map['id'] = $userid;
        $map['status'] = 1; //状态：0_不启用,1_启用
        $map['sequestration'] = 1;//1—正常0_封号
        $userDetail = $model->field("id,pet_name,head_portrait,birthdate,sex,phone,token")->where($map)->find();
        if (empty($userDetail)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "没有数据";
            $this->_return($res_data);
        }
        //之前的代码，用不着了，but 先留着
        // $token = $userDetail['token'];
        // if(empty($token)){
        //     //去获取token
        //     $userId = $userDetail['id'];
        //     $name = $userDetail['pet_name'];
        //     $portraitUri = $userDetail['head_portrait'];
        //     $token = $this->get_token($userId,$name,$portraitUri);
        //     $userDetail['token'] = $token;
        //     //更新token到数据库
        //     M("Users")->where("id=".$userid)->setField("token",$token);
        // }

        if ($userDetail) {
            $res_data['status'] = 1;
            $res_data['msg'] = "加载成功";
            $res_data['data'] = $userDetail;
        }

        $this->_return($res_data);
    }
    //上传头像接口
    public function updatePortrait(){
        $userid = $this->_data['userid'];//当前用户id  
        $baseImages = $this->_data['baseImages'];//修改上传的图片base64的图片
        if (empty($userid)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        if (empty($baseImages)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "请上传图片！";
            $this->_return($res_data);
        }
        $file_prefix = "tx_";
        $url = $this->upload_images($baseImages,$file_prefix);//获取新的图片地址
        $map = array();
        $map['id'] = $userid;
        $map['status'] = 1;//1_启用,0_不启用
        $map['sequestration'] = 1;//1_正常,0_封号
        $imagesUrl['head_portrait'] = $url;
        $result = M("Users")->where($map)->save($imagesUrl);
        if ($result) {
            $res_data['status'] = 1;
            $res_data['msg'] = "头像修改成功！";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "修改失败";
        }
        $this->_return($res_data);
    }
    //修改性别接口
    public function updateSex(){
        $userid = $this->_data['userid'];//当前用户id
        $newSex = $this->_data['sex'];//前端修改的性别
        if (empty($userid) || $newSex=="") {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $newSexData['sex'] = $newSex;
        $model = M("Users");
        $map = array();
        $map['id'] = $userid;
        $result = $model->field('sex')->where($map)->save($newSexData);    
        if($result){
            $res_data['status'] = 1;
            $res_data['msg'] = "性别修改成功";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "性别修改失败";
        }
        $this->_return($res_data);
    }
    //修改昵称接口
    public function updateName(){
        $userid = $this->_data['userid'];//当前用户id
        $newName = $this->_data['pet_name'];//前端修改的昵称
        if (empty($userid) || empty($newName)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $newNameData['pet_name'] = $newName;
        $model = M("Users");
        $map = array();
        $map['pet_name'] = $newName;
        $result = M("Users")->field("pet_name")->where($map)->find();
        if ($result) {
            $res_data['status'] = 0;
            $res_data['msg'] = "用户名已被占用";
        }else{
            $map = array();
            $map['id'] = $userid;
            $data = $model->field('pet_name')->where($map)->save($newNameData);    
            if($data){
                $res_data['status'] = 1;
                $res_data['msg'] = "昵称修改成功";
            }else{
                $res_data['status'] = 0;
                $res_data['msg'] = "昵称修改失败";
            }
        }
        $this->_return($res_data);
    }
    //修改手机的接口
    public function updatePhone(){
        $userid = $this->_data['userid'];//当前用户id
        $newPhone = $this->_data['newPhone'];
        if (empty($userid) || empty($newPhone)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $newPhoneData['phone'] = $newPhone;
        $model = M("Users");
        $map = array();
        $map['id'] = $userid;        
        $result = $model->field("phone")->where($map)->save($newPhoneData);      
        if($result){
            $res_data['status'] = 1;
            $res_data['msg'] = "修改成功";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "修改失败";
        }
        $this->_return($res_data);
    }
    //子账号管理接口
    public  function childUser(){
        $userid = $this->_data['userid'];//当前用户的ID
        if (empty($userid)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $map = array(); 
        $map['userid'] = $userid;
        $map['status'] = 1;//状态：0_不启用,1_启用
        $data = M("Users")->field("house_id")->where($map)->find();
        if (empty($data)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "没有数据";
            $this->_return($res_data);
        }
        $houseid = $data['house_id'];
        $map = array();
        $map['houseid'] = $houseid;
        $map['isproprietor'] = 0;//是否为业主，0_不是业主（默认）,1_业主
        $childdata = M("UserHouse")->field("userid")->where($map)->select();
        foreach ($childdata as $key => $item) {
            $map['id'] = $item['userid'];
            $result = M("Users")->field("id,real_name,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime")->where($map)->find();
            $res_data['data'][$key] = $result;
        }
        $res_data['status'] = 1;
        $res_data['msg'] = "加载成功";
        $this->_return($res_data);
    }
    //子账号删除接口
    public function delete(){
        $id = $this->_data['id'];//当前删除子账号的ID
        if (empty($id)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $map = array();
        $map['id'] = $id;
        $result = M("Users")->where($map)->delete();
        $map = array();
        $map['userid'] = $id;//删除关联表的记录 
        $map['isproprietor'] = 0;
        $result = M("UserHouse")->where($map)->delete();
        if ($result) {
            $res_data['status'] = 1;
            $res_data['msg'] = "删除成功";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "删除失败";
        }
        $this->_return($res_data);
    }
    //添加子账号验证接口
    public function verify(){
        $userid = $this->_data['userid'];//当前登录的用户的ID
        $inputPhone = $this->_data['inputPhone'];//获取前端输入的手机后四位
        if (empty($userid) || empty($inputPhone)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $map = array();
        $map['id'] = $userid;
        $result = M("Users")->field("phone")->where($map)->find();
        $phone = substr($result['phone'], -4);//手机号后四位
        if ($inputPhone==$phone) {
            $res_data['status'] = 1;
            $res_data['msg'] = "输入正确";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "手机号后四位输入错误";
        } 
        $this->_return($res_data);     
    }
    //点击获取验证码接口
    public function getverify(){
        $newPhone = $this->_data['newPhone'];
        //如果验证成功，执行下一步操作(没写)    
    }

    //添加子账号接口
    public function addUser(){
        $phone = $this->_data['phone'];//子用户的电话
        $verify = $this->_data['verify'];//获取到的验证码
        //如果验证码验证成功，执行下面的添加操作
        $userid = $this->_data['userid'];//当前登录的用户的ID
        $real_name = $this->_data['real_name'];//子用户的姓名
        if (empty($userid) || empty($phone) || empty($real_name)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $userdata['real_name'] = $real_name;
        $userdata['phone'] = $phone;
        $map = array();
        $map['id'] = $userid;
        $map['status'] = 1;
        $result = M("Users")->field("house_id")->where($map)->find();
        $map = array();
        $map['houseid'] = $result['house_id'];
        $house = M("UserHouse")->field()->where($map)->select();
        foreach ($house as $key => $item) {
            if ($item['isproprietor'] == 1 && $item['userid'] == $userid) { //是否为业主，0_不是业主（默认）,1_业主
                $addtime = time();
                $userdata['addtime'] = $addtime;
                $userdata['house_id'] = $item['houseid'];
                $data = M("Users")->add($userdata);
                // P($data);
                // exit;
                // $map = array();
                // $UserHouse = M("Users")->field("userid")->where($map)->find();
                $UserHouseDate['userid'] = $data;
                $UserHouseDate['houseid'] = $item['houseid'];
                $UserHouseDate[$key]['isproprietor'] = 0; 
                $data2 = M("UserHouse")->add($UserHouseDate);
            if ($data2) {
                $res_data['status'] = 1;
                $res_data['msg'] = "添加成功"; 
                }else{
                    $res_data['status'] = 0;
                    $res_data['msg'] = "不是用户不能添加子账号";
                }              
            }
        }
        $this->_return($res_data);
    }
    //我的发布商品接口
    public function issueGoods(){
        $userid = $this->_data['userid'];//当前登录的用户的ID
        $communityid = $this->_data['communityid'];//当前所在社区ID
        $pageNumber = $this->_data['pageNumber'];//当前第几页
        if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
        if (empty($userid) || empty($communityid)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $count = M("Goods")->count($mod_pk);
        $pagesize = 4;
        $pager = new \Think\Page($count,$pagesize);
        $map = array();
        $map['userid'] = $userid;
        $map['communityid'] = $communityid;
        $goods = M("Goods")->field("id,name,price,description,images,number,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime,number")->where($map)->order("addtime desc")->limit(($pageNumber-1)*4,$pager->listRows)->select();
        if (empty($goods)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "没有数据";
            $this->_return($res_data);
        }
        foreach ($goods as $key => $value) {
            $images = $value['images'];
            $images = json_decode($images,true);
            $goods[$key]['images'] = $images;
            $res_data['data'][$key]['images'] = $goods;
        }
        foreach ($goods as $key => $item) {
            $res_data['data'][$key] = $item;
            // $date = date('Y-m-d H:i:s',$item['addtime']);//格式化时间
            // $res_data['data'][$key]['addtime'] = $date;
            $map = array();
            $map['status'] = 1;//状态：0_不启用,1_启用
            $map['userid'] = $userid;
            $user = M("Users")->field("head_portrait,pet_name")->where($map)->find();
            $head_portrait = $user['head_portrait'];
            $pet_name = $user['pet_name'];
            $res_data['data'][$key]['head_portrait'] = $head_portrait;
            $res_data['data'][$key]['pet_name'] = $pet_name;
        }
        $res_data['status'] = 1;
        $res_data['msg'] = "加载成功";
        $this->_return($res_data);
    }
    //我的发布帖子接口
    public function issuePost(){
        $userid = $this->_data['userid'];//当前登录的用户的ID
        $communityid = $this->_data['communityid'];//当前所在社区ID
        $pageNumber = $this->_data['pageNumber'];//当前第几页
        if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
        $count = M("Post")->count($mod_pk);
        $pagesize = 4;
        $pager = new \Think\Page($count,$pagesize);
        $map = array();
        $map['userid'] = $userid;
        $map['status'] = 1;//审核状态(0_待审黑,1_通过审核,-1_审核未通过，默认为通过审核)
        $map['communityid'] = $communityid;
        $post = M("Post")->field("id,title,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime,content,images")->where($map)->order("addtime desc")->limit(($pageNumber-1)*4,$pager->listRows)->select();
        if (empty($post)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "没有数据";
            $this->_return($res_data);
        }
        foreach ($post as $key => $value) {
            $images = $value['images'];
            $images = json_decode($images,true);
            $post[$key]['images'] = $images;
            $res_data['data'][$key]['images'] = $post;
        }
        foreach ($post as $key => $item) {
            $res_data['data'][$key] = $item;
            // $date = date('Y-m-d H:i:s',$item['addtime']);//格式化时间
            // $res_data['data'][$key]['addtime'] = $date;
            $map = array();
            $map['status'] = 1;//状态：0_不启用,1_启用
            $map['userid'] = $userid;
            $user = M("Users")->field("pet_name")->where($map)->find();
            $pet_name = $user['pet_name'];
            $res_data['data'][$key]['pet_name'] = $pet_name;
        }
        $res_data['status'] = 1;
        $res_data['msg'] = "加载成功";
        $this->_return($res_data);
    }
    //我的发布-删除接口
    public function publishDelete(){
        $typeid = $this->_data['typeid'];//1_商品，2_帖子
        $userid = $this->_data['userid'];//当前登录的用户ID
        $id = $this->_data['id'];//删除的帖子或者商品的ID
        if (empty($typeid)||empty($userid)||empty($id)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作！";
            $this->_return($res_data);
        }
        //商品
        if ($typeid==1) {
            $map = array();
            $map['userid'] = $userid;
            $map['id'] = $id;
            $result = M("Goods")->where($map)->delete();
            if ($result) {
                $res_data['status'] = 1;
                $res_data['msg'] = "商品删除成功!";
            }else{
                $res_data['status'] = 0;
                $res_data['msg'] = "商品删除失败!";
            }
        }elseif ($typeid==2) {
            $map = array();
            $map['userid'] = $userid;
            $map['id'] = $id;
            $result = M("Post")->where($map)->delete();
            if ($result) {
                $res_data['status'] = 1;
                $res_data['msg'] = "帖子删除成功!";
            }else{
                $res_data['status'] = 0;
                $res_data['msg'] = "帖子删除失败!";
            }
        }
        $this->_return($res_data);
    }
    //通知消息接口(全局通用)
    public function announce(){
        $peopleid = $this->_data['peopleid'];//当前登录人员的ID(包括所有人员)
        $pageNumber = $this->_data['pageNumber'];//当前第几页
        $type = $this->_data['type'];//消息类型:1_物业管理通知DD维修,2_物业通知社区服务人员, 3_系统推送消息到用户/住户, 4_网站反馈结果通知消息
        if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
        if (empty($peopleid) || empty($type)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $map = array();
        $map['i.type'] = $type;
        $map['ir.ralationid'] = $peopleid;
        $count = M("information")->join("as i left join ".$this->_qz."information_relation as ir on ir.informationid = i.id")->where($map)->count($mod_pk);
        $pagesize = 5;
        $pager = new \Think\Page($count,$pagesize);//isread 读取状态:0_未读（默认）, 1_已读
        $data = M("information")->field("i.id,i.title,i.type,i.content,from_UNIXTIME(i.addtime,'%Y-%m-%d %H:%i') as addtime,i.content,ir.isread")->join("as i left join ".$this->_qz."information_relation as ir on ir.informationid = i.id")->where($map)->order("id asc")->limit(($pageNumber-1)*5,$pager->listRows)->select();
        if (empty($data)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "没有数据";
            $this->_return($res_data);
        }
        $res_data['data'] = $data;
        $res_data['status'] = 1;
        $res_data['msg'] = "加载成功";
        $this->_return($res_data);
    }
    //通知消息详情接口(全局通用)
    public function announceDetail(){
        $id = $this->_data['id'];//消息的ID
        $isread = $this->_data['isread'];//前端读取之后，返回给服务器的状态_1
        $peopleid = $this->_data['peopleid'];//当前登录人员的ID(包括所有人员)
        if (empty($peopleid) || empty($isread) || empty($id)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        if ($isread == 1) {//1_已读
            $time = time();
            $data['isread'] = $isread;
            $data['readtime'] = $time;//获取读取时间
            $map = array();
            $map['ralationid'] = $peopleid;
            $map['informationid'] = $id;
            $result = M("InformationRelation")->where($map)->save($data);//修改数据库里的读取状态和读取时间
        if ($result) {
            $res_data['status'] = 1;
            $res_data['msg'] = "加载成功，且已读";
        }
            $map = array();
            $map['id'] = $id;
            $data = M("Information")->field("id,title,content,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime
")->where($map)->find();
            $res_data['data'] = $data;
            // $res_data['data']['addtime'] = date("Y-m-d H:i",$data['addtime']);
        }else{
            $res_data['msg'] = "读取状态返回错误";
            $res_data['status'] = 0;
        }
        $this->_return($res_data);
    }
    //通知消息删除接口(全局通用)
    public function announceDelete(){
        $id = $this->_data['id'];//删除选中的消息ID
        $peopleid = $this->_data['peopleid'];//当前登录人员ID(包括所有人员)
        if (empty($peopleid) || empty($id)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        $map = array();
        //消息类型:1_物业管理通知DD维修,2_物业通知社区服务人员, 3_系统推送消息到用户/住户, 4_网站反馈结果通知消息
        $map['informationid'] = $id;
        $map['ralationid'] = $peopleid;
        $result = M("InformationRelation")->where($map)->delete();
        if ($result) {
            $res_data['status'] = 1;
            $res_data['msg'] = "删除成功";
        }else{
            $res_data['status'] = 0;
            $res_data['msg'] = "删除失败";
        }
        $this->_return($res_data);
    }
    //通知消息，全部标为已读功能接口
    public function allisread(){
        $ids = $this->_data['ids'];//所有消息的ID，以逗号形式隔开
        $peopleid = $this->_data['peopleid'];//当前登录的人员ID()
        $isread = $this->_data['isread'];//1_已读，0_未读
        $arr = explode(",", $ids);
        foreach ($arr as $key => $item) {
            $time = time();
            $data['isread'] = $isread;
            $data['readtime'] = $time;//获取读取时间
            $map = array();
            $map['ralationid'] = $peopleid;
            $map['informationid'] = $item;
            $result = M("InformationRelation")->where($map)->save($data);//修改数据库里的读取状态和读取时间
        }
        if ($result) {   
            $res_data['status'] =1;
            $res_data['msg'] = "全部标为已读";
        }else{
            $res_data['status'] =0;
            $res_data['msg'] = "操作失败";
        }
        $this->_return($res_data);
    }
    //支付成功之后的评分接口
    public function grade(){
        $repairUserid = $this->_data['repairUserid'];//当前维修人员的ID
        $repairid = $this->_data['repairid'];//当前维修订单的ID
        $score['evaluate'] = $this->_data['score'];//获取当前得到的星星数
        $map = array();
        $map['repairid'] = $repairid;
        $result = M("RepairRelation")->where($map)->save($score);//把星星数写入服务关联表记录
        $map = array();
        $map['repairuserid'] = $repairUserid;
        $evaluate = M("RepairRelation")->field("evaluate")->where($map)->select();
        if (empty($evaluate)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "没有数据";
            $this->_return($res_data);
        }
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
   


}