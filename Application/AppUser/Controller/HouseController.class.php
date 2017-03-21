<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class HouseController extends AppUserController {

    function _initialize(){
		parent::_initialize();
		
	}   
    //我的房屋接口
    public function house(){
        $userid = $this->_data['userid'];//当前登录用户ID
        $pageNumber = $this->_data['pageNumber'];//当前第几页
        if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
        $model = M("Users");
        $map = array();
        // $map['isproprietor'] = 1;//是否为业主，0_不是业主（默认）,1_业主
        $user = $model->field(" ur.houseid ")->join(" as u left join ".$this->_qz."user_house as ur on ur.userid = ".$userid."")->where($map)->order(" u.id asc ")->find();
        if (empty($user)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "没有数据";
            $this->_return($res_data);
        }
        $houseid = $user['houseid'];
        $map = array();
        $map['id'] = $houseid;
        $count = M("House")->count($mod_pk);
        $pagesize = 5;
        $pager = new \Think\Page($pagesize,$count);
        $house = M("House")->field("id,name,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime,house_area")->where($map)->order("id asc")->limit(($pageNumber-1)*5,$pager->listRows)->select();
            $res_data['status'] = 1;
            $res_data['msg'] = "房屋加载成功";
        $this->_return($res_data);
    }
    //我的房屋详情页接口
    public function houseDetail(){
        $houseid = $this->_data['houseid'];//房屋ID
        $map = array();
        $map['id'] = $houseid;
        $house = M("House")->field("id,floorid,name,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime,house_area,pay_fees_deadline")->where($map)->find();
        if ($house) {
            $res_data['status'] = 1;
            $res_data['data'] = $house;
            $res_data['msg'] = "加载成功";
        }else{
            $res_data['status'] = 0;
            $res_datap['msg'] = "加载失败";
        }
        $this->_return($res_data);
    }
    //房屋解绑接口
    public function unwrap(){
        $userid = $this->_data['userid'];//当前登录用户的ID
        $inputPhone = $this->_data['inputPhone'];//获取前端输入的手机后四位
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
    //验证码接口
    public function verfiy(){
        $verfiy = $this->_data['verfiy'];//获取验证码
        //如果验证成功,执行删除房屋操作
        $userid = $this->_data['houseid'];//房屋的ID
        $map = array();
        $map['id'] = $houseid;
        $house = M("House")->field("floorid,name,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime,house_area,pay_fees_deadline")->where($map)->delete();
        if ($house) {
            $res_data['status'] = 1;
            $res_data['msg'] = "解绑成功";
        }else{
            $res_data['status'] = 0;
            $res_datap['msg'] = "解绑失败";
        }
        $this->_return($res_data);

    }

    //用户端 房屋切换-房屋列表
    public function houseSwitchingList(){
        $userid = $this->_data['userid'];//用户的ID
        if(empty($userid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        //找到用户一些数据
        $users = getFieldData("Users",$userid,"house_id");
        $house_id = $users['house_id'];//当前的房屋ID
        if(empty($users)){
            $res_data['status'] = 0;
            $res_data['msg'] = "用户不存在";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        $map['uh.userid'] = $userid;
        $map['uh.isproprietor'] = 1;//是否为业主，0_不是业主（默认）,1_业主
        $houseList = M("House")->field("h.id,h.number,c.name communityname")->join(" as h left join xly_user_house as uh on h.id=uh.houseid left join xly_community as c on c.id=h.communityid")->where($map)->select();

        $res_data['status'] = 1;
        $res_data['data']['houselist'] = $houseList;
        $res_data['data']['house_id'] = $house_id;
        $res_data['msg'] = "";
        $this->_return($res_data);
    }


    //用户端 执行房屋切换
    public function houseSwitching(){
        $userid = $this->_data['userid'];//选中将要切换到的房屋ID
        $houseid = $this->_data['houseid'];//选中将要切换到的房屋ID
        if(empty($userid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        //找到用户一些数据
        $users = getFieldData("Users",$userid,"house_id");
        $houseid2 = $users['house_id'];//选中将要切换到的房屋ID
        if(empty($users)){
            $res_data['status'] = 0;
            $res_data['msg'] = "用户不存在";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        if($houseid == $houseid2){
            $res_data['status'] = 0;
            $res_data['msg'] = "切换房屋对象为当前房屋";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        //找到当前用户要绑定的房屋的信息
        $house = getFieldData("House",$houseid,"id,communityid");
        $communityid = $house['communityid'];//社区id
        if(empty($house)){
            $res_data['status'] = 0;
            $res_data['msg'] = "房屋不存在";
            $res_data['data'] = "";
            $this->_return($res_data);
        }
        if(empty($communityid)){
            $res_data['status'] = 0;
            $res_data['msg'] = "该房屋没有绑定社区";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        //验证这个房屋是否此人的
        $map['uh.userid'] = $userid;
        $map['uh.isproprietor'] = 1;//是否为业主，0_不是业主（默认）,1_业主
        $houseList = M("House")->field("h.id")->join(" as h left join xly_user_house as uh on h.id=uh.houseid left join xly_community as c on c.id=h.communityid")->where($map)->select();
        // $houseList = array_column($houseList, 'id', 'name');
        // $hList = array_column($houseList, 'id');//转一维化后的数据 因环境问题，这个暂时不用，已注释
        //转一维化后的数据
        $hList = array();
        foreach ($houseList as $key => $value) {
            $hList[$key] = $value['id'];
        }

        if(!in_array($houseid, $hList)){
            $res_data['status'] = 0;
            $res_data['msg'] = "切换房屋失败,房屋不属于用户";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        //切换
        $map_save['id'] = $userid;
        $map_save['house_id'] = $houseid;
        $users_save = M("Users")->save($map_save);
        if(!$users_save){
            $res_data['status'] = 0;
            $res_data['msg'] = "切换房屋失败";
            $res_data['data'] = "";
            $this->_return($res_data);
        }

        $res_data['status'] = 1;
        $res_data['data']['communityid'] = $communityid;
        $res_data['msg'] = "房屋已切换";
        $this->_return($res_data);
    }


}