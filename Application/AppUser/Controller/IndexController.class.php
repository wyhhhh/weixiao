<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class IndexController extends AppUserController {

    function _initialize(){
		parent::_initialize();
	}

	public function test()
    {
        // //网站基本信息
        // $systemConfig = M("Config")->limit(1)->find();
        // $this->assign("systemConfig",$systemConfig);
        // //title信息
        // C("SITE_TITLE",$systemConfig['mail']);//网站标题
        // C("SITE_KEYWORDS",$systemConfig['phone']);//网站关键字
        // C("SITE_DESCRIPTION",$systemConfig['default_headportrait']);//网站描述
        // p(C("SITE_TITLE"));
        // return;

        // $a = 0;
        // while($a<5){
        //     $a++;
        //     p($a."=");
        // }
        // return;

        // //维修人员工号编号
        // $p = $this->randomJobNumber(4,"A","D");
        // p($p);
        // return;

        // //订单编号生成
        // $p = randomOrderNumber(MZW,218745577);
        // p($p);
        // return;

        // p(get_client_ip());
        // return;

    	echo time();
    	echo "<br>";
    	echo date('Y-m-d H:i:s');
    	echo "<br>";
    	$date = date('Y-m-d');
    	echo strtotime($date);
    	die();
    	$res_data = array();
    	$res_data['state']= "1";
    	$data['name'] = $this->_data['name'];
    	$data['id'] = "测试数据";
    	$res_data['data']= $data;
    	$this->_return($res_data);
    }

    public function index(){
    	$model = M("Banner");
        $communityid = $this->_data['communityid'];//获取当前登录用户的社区ID
    	//查询首页banner
    	$pagesize = 3;
    	$count = $model->count($mod_pk);
    	$pager = new \Think\Page($count, $pagesize);
        $map = array();
        $map['location'] = 1;//广告位置: 1_物业/首页（默认）, 2_维修页面
        $map['isshow'] = 1;//状态0不显示1显示
        $map['range'] = array("like","%$communityid%");
    	$banner = $model->field("title,url,image")->where($map)->order(" id asc")->limit($pager->firstRow.','.$pager->listRows)->select();
    	//查询首页公告
    	$model = M("Notice");
    	$pagesize = 4;
    	$count = $model->count($mod_pk);
    	$pager = new \Think\Page($count, $pagesize);
        $map = array();
        $map['range'] = array("like","%$communityid%");
    	$notice = $model->field("title,content,addtime")->order(" id asc")->limit($pager->firstRow.','.$pager->listRows)->where($map)->select();
        foreach ($notice as $key => $item) {
            $res_data['data']['notice'][$key] = $item;
            $date = date('Y-m-d H:i:s',$item['addtime']);
            $res_data['data']['notice'][$key]['addtime'] = $date;
        }
        //查询新闻信息
        $model = M("Info");
        $pagesize = 3;
        $count = $model->count($mod_pk);
        $pager = new \Think\Page($count, $pagesize);
        $info = $model->field("title,content,images")->order(" id asc")->limit($pager->firstRow.','.$pager->listRows)->select();
        foreach ($info as $key => $value) {
            $images = $value['images'];
            $images = json_decode($images,true);
            $info[$key]['images'] = $images;
        }
        $res_data['data']['banner'] = $banner;
        $res_data['data']['info'] = $info;
        $res_data['status'] = 1;
        $res_data['msg'] = "加载成功";

        //这个是返回的是 当前请求的Host头 begin
        // $ip_host = $this->init_ip();
        // $res_data['data']['ip_host'] = $ip_host;
        //end

    	$this->_return($res_data);
    }

    //测试
    public function cs(){
        // $users = M("Users")->field("id,phone")->select();
        // p($users);
        // $json = '[{"0":"123"},{"1":"456"},{"2":"123"}]';
        // $json = json_decode($json);
        // p($json);
        // return;
        $_phone = I("get.phone");
        $b = verifyMobileLegal($_phone);
        if($b){
            //通过了
            p($_phone." 验证正常!");
        }else{
            p("word哥，你搞错了...");
        }
    }

    //私有_发送验证码
    private  function pri_sendPhoneCode($phone)
    {
        $time = time();
        //查询上次发码时间
        $code_info = M("PhoneCode")->field('time')->where(array('phone'=>$phone))->order("id desc")->find();
        if (!empty($code_info)) {
            $teltime = $code_info["time"];
            //查询配置过期时间(5分钟) 
            $sendcodetimeout = 1 * 60; //发送间隔时间,一分钟
            $difftiem =  $time - $teltime;
            if ($difftiem < $sendcodetimeout) {
                $res_data['status'] = -1;
                $res_data['data'] = "发送间隔时间不低于一分钟！";
                return $res_data;
            } 
        }
        $code = rand(1000,9999);
        //发送验证码
                    //---- 坐等接口
            // /**发码开始 ___ 旧的**/
         //        header("content-type:text/html; charset=gb2312"); 
         //        $id = "htek06069";
         //        $pwd = "123321";
         //        //$phone = "13648306805";
         //        $content = "欢迎使用迷路，让你不再迷路！你的验证码是".$code."。【迷路】";
         //        $content = iconv('UTF-8', 'GB2312', $content);
         //        //$content = mb_convert_encoding($content, "GB2312"); //编码转换为utf-8
         //        $url = "http://www.ht3g.com/htWS/Send.aspx?CorpID=$id&Pwd=$pwd&Mobile=$phone&Content=$content&Cell=&SendTime=";
         //        $this->pri_request_post($url);
      //       /**发码结束**/
            /**发码开始 ___ 新的**/
                header("content-type:text/html; charset=UTF-8"); 
                $post_data = array();
                $post_data['userid'] = 51;
                $post_data['account'] = 'yizu';
                $post_data['password'] = 'yizu';
                $content = "欢迎使用迷路，让你不再迷路！你的验证码是".$code."。【迷路】";
                $post_data['content'] = $content; //短信内容需要用urlencode编码下
                $post_data['mobile'] = $phone;
                $post_data['sendtime'] = ''; //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
                $url='http://sms.ht3g.com/sms.aspx?action=send';
                $o='';
                foreach ($post_data as $k=>$v)
                {
                   $o.="$k=".urlencode($v).'&';
                }
                $post_data=substr($o,0,-1);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
                $result = curl_exec($ch);
            /**发码结束**/
        //删除当前手机之前的验证码
        $map = array();
        $map['phone'] = $phone;
        M("PhoneCode")->where($map)->delete();
        //记录验证码
        $code_data['code'] = $code;
        $code_data['time'] = $time;
        $code_data['phone'] = $phone;
        $res = M("PhoneCode")->add($code_data);
        $res_data['status'] = 1;
        $res_data['data'] = $code;
        return $res_data;
    }

    //上传图片示例
    public function upload_images_test(){
        // $base = base64_decode("Hello World. Testing!");
        // $base = "D://重装之桌面文件保存/picture/1_1036600.jpg";
        // echo file_put_contents("Public/UploadFile/appimages/a.txt",$base);
        if(IS_POST){
            //需要传入的图片是base64的数据流
            $images = $this->_data['images'];
            $path = $this->upload_images($images);
            // $images = trim($images,',');
            // $images = explode(',', $images);
            // $filename = "Img_";
            // $i = 0;
            // foreach ($images as $key => $value) {
            //     if (!empty($value)) {
            //         $imagepath = $this->pri_SaveImage($filename,$value);
            //     }
            // }
            p($path);
        }
        $this->display();
    }

    // //保存图片
    // public function pri_SaveImage($filename,$filebase)
    // {  
    //     $filebase = base64_decode($filebase);
    //     $title = $filename.date('YmdHisms').rand(1000, 9999);
    //     $file_path = 'Public/UploadFile/appimages/'.$title.".png";
    //     $filestatus = file_put_contents($file_path, $filebase);
    //     if ($filestatus) {
    //         return "/".$file_path; 
    //     }
    //     return "";
    // }


}