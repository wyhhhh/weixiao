<?php
namespace Common\Common;
use Common\Common\BaseController;
class AppController extends BaseController{
	function _initialize(){
		parent::_initialize(); 

		$data = array();
		if (IS_POST) {
			$data = I("post.");
		}else{
			$data = I("get.");	
		}
		$this->_data = $data;

		$this->_config = M("Config")->limit(1)->find();
		// C("SITE_HEADPORTRAIT",$config['default_headportrait']);//网站用户默认头像
		// p($config);
        // $this->assign("config",$config);

	}

	public function _return($data = array())
	{
    if (!isset($data['status'])) $data['status'] = 1;
    if (!isset($data['msg'])) $data['msg'] = "";
    if (!isset($data['data'])) $data['data'] = "";
    
		$str = json_encode($data);
		if (IS_POST) {
        //$str = base64_encode($str);
		}
		echo $str;
		die();
	} 
 
	/**
	* 模拟post进行url请求
	* @param string $url
	* @param string $param
	*/
	function request_post($url = '', $post = array()) {
		if (empty($url)) {
		    return false;
		}
		$interfaceUrl = C('InterfaceUrl');
		$url = $interfaceUrl.'/'.$url;
		// foreach ($post as $key => $value) {
		//		$post[$key] = $this->passport_encrypt($value,$this->_key);
		// }
		$data['data'] = $this->passport_encrypt(json_encode($post),$this->_key);

		$param = http_build_query($data);
		$postUrl = $url;
		$curlPost = $param;
		$ch = \curl_init();//初始化curl
		curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
		curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch);//运行curl
		curl_close($ch);
		$data = $this->passport_decrypt($data,$this->_key);
		return json_decode($data,true);

	}

	/**
	* Passport 加密函数
	*
	* @param                string          等待加密的原字串
	* @param                string          私有密匙(用于解密和加密)
	*
	* @return       string          原字串经过私有密匙加密后的结果
	*/
	function passport_encrypt($txt, $key) {
        // 使用随机数发生器产生 0~32000 的值并 MD5()
        srand((double)microtime() * 1000000);
        $encrypt_key = md5(rand(0, 32000));
        // 变量初始化
        $ctr = 0;
        $tmp = '';
        // for 循环，$i 为从 0 开始，到小于 $txt 字串长度的整数
        for($i = 0; $i < strlen($txt); $i++) {
                // 如果 $ctr = $encrypt_key 的长度，则 $ctr 清零
                $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
                // $tmp 字串在末尾增加两位，其第一位内容为 $encrypt_key 的第 $ctr 位，
                // 第二位内容为 $txt 的第 $i 位与 $encrypt_key 的 $ctr 位取异或。然后 $ctr = $ctr + 1
                $tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
        }
        // 返回结果，结果为 passport_key() 函数返回值的 base64 编码结果
        return base64_encode($this->passport_key($tmp, $key));
	}

    /**
    * Passport 解密函数
    *
    * @param                string          加密后的字串
    * @param                string          私有密匙(用于解密和加密)
    *
    * @return       string          字串经过私有密匙解密后的结果
    */
    function passport_decrypt($txt, $key) { 
        // $txt 的结果为加密后的字串经过 base64 解码，然后与私有密匙一起，
        // 经过 passport_key() 函数处理后的返回值
        $txt = $this->passport_key(base64_decode($txt), $key);
        // 变量初始化
        $tmp = '';
        // for 循环，$i 为从 0 开始，到小于 $txt 字串长度的整数
        for ($i = 0; $i < strlen($txt); $i++) {
                // $tmp 字串在末尾增加一位，其内容为 $txt 的第 $i 位，
                // 与 $txt 的第 $i + 1 位取异或。然后 $i = $i + 1
                $tmp .= $txt[$i] ^ $txt[++$i];
        }
        // 返回 $tmp 的值作为结果
        return $tmp;
    }

	/**
    * Passport 信息(数组)编码函数
    *
    * @param                array           待编码的数组
    *
    * @return       string          数组经编码后的字串
    */
    function passport_encode($array) {
		// 数组变量初始化
		$arrayenc = array();
		// 遍历数组 $array，其中 $key 为当前元素的下标，$val 为其对应的值
		foreach($array as $key => $val) {
		        // $arrayenc 数组增加一个元素，其内容为 "$key=经过 urlencode() 后的 $val 值"
		        $arrayenc[] = $key.'='.urlencode($val);
		}
		// 返回以 "&" 连接的 $arrayenc 的值(implode)，例如 $arrayenc = array('aa', 'bb', 'cc', 'dd')，
		// 则 implode('&', $arrayenc) 后的结果为 ”aa&bb&cc&dd"
		return implode('&', $arrayenc);
    }

	/*
	*辅助函数
	*/
	function passport_key($str,$encrypt_key){
		$encrypt_key=md5($encrypt_key);
		$ctr=0;
		$tmp='';
		for($i=0;$i<strlen($str);$i++){
			$ctr=$ctr==strlen($encrypt_key)?0:$ctr;
			$tmp.=$str[$i] ^ $encrypt_key[$ctr++];
		}
		return $tmp;
	}



	/*
	*验证业主手机号是否正确 后四位尾数，锁定用户表
	*@param   _phone_tail手机号后四位  _userid当前业主id
	*@return  boolean 布尔值:存在ture  不存在false 
	*/
	// protected function verifyPhoneExist($_phone_tail,$_userid){
	// 	$bollean_return = false;
	// 	$map_verify['id'] = $_userid;
	// 	$verify_return = M("Users")->where($map_verify)->find();
	// 	$phone = $verify_return['phone'];
	// 	if(!empty($phone)){
	// 		$tail = substr($phone, strlen($phone)-4);//截取到尾部4位数
	// 		if($tail==$_phone_tail){
	// 			$bollean_return = true;
	// 		}
	// 	}
	// 	return $bollean_return;
	// }

	/*
	*验证并修改手机号码
	*@param   _phone_tail手机号  _userid当前业主id
	*@return  boolean 布尔值:存在ture  不存在false 
	*/
    // protected function modifyPhone($_name,$_userid,$_phone){
    // 	$map_data['id'] = $_userid;
    // 	$map_data['phone'] = $_phone;
    //     $verify_return = M($_name)->save($map_data);
    // }

	/*
	*将HTML实体转化为字符标签
	*@param   _data当前数据
	*@return  _data转化后的数据
	*/
	// protected function transformHtmls($_data){
	// 	$_data = htmlspecialchars_decode($_data);
	// 	return $_data;
	// }

	/*
	*剥去字符串中的 HTML、XML 以及 PHP 的标签
	*@param   _data当前数据
	*@return  _data转化后的数据
	*/
	// protected function transformPhoneStrip($_data){
	// 	$_data = strip_tags($_data);
	// 	return $_data;
	// }

	//初始ip地址加载
    public function init_ip(){
        // $ip_1 = $_SERVER['SERVER_PORT'];//端口
        // $ip_2 = $_SERVER['SERVER_NAME'];//主机名
        $ip_host = "http://".$_SERVER['HTTP_HOST'];
        return $ip_host;
    }

    // //通知消息发送统一接口
    // public function send_information($map_i,$map_ir){
    // 	// $map_t[0]['isread'] = 1;
    // 	// $map_t[0]['readtime'] = time();
    // 	// $map_t[1]['isread'] = 1;
    // 	// $map_t[1]['readtime'] = time();
    // 	// $map_t[2]['isread'] = 1;
    // 	// $map_t[2]['readtime'] = time();
    // 	$return_i = M("Information")->add($map_i);
    // 	foreach ($map_ir as $key => $value) {
    // 		$map_ir[$key]['informationid'] = $return_i;
    // 	}
    // 	$return_ir = M("InformationRelation")->addAll($map_ir);
    // 	$return = true;
    // 	if(!$return_ir){
    // 		$return = false;
    // 	}
    // 	return $return;
    // }

    //上传图片示例  images图片流，多个用','隔开  file_prefix图片前缀
    public function upload_images($images,$file_prefix = ""){
        //需要传入的图片是base64的数据流 多个用','隔开
        $images = trim($images,',');
        $images = explode(',', $images);
        $filename = $file_prefix;
        $i = 0;
        $uplaods_path = "";
        foreach ($images as $key => $value) {
            if (!empty($value)) {
                $imagepath = $this->pri_SaveImage($filename,$value);
                $uplaods_path .= $imagepath.",";
            }
        }
        if(!empty($uplaods_path)){
        	$uplaods_path = substr($uplaods_path,0,strlen($uplaods_path)-1);
        }
        return $uplaods_path;
    }

    //保存图片
    public function pri_SaveImage($filename,$filebase)
    {  
        $filebase = base64_decode($filebase);
        $title = $filename.date('YmdHisms').rand(1000, 9999);
        $file_path = 'Public/UploadFile/appimages/'.$title.".png";
        $filestatus = file_put_contents($file_path, $filebase);
        if ($filestatus) {
            return "/".$file_path; 
        }
        return "";
    }

	//获取token啊。。。。
    protected function get_token($user_id,$name,$portrait_uri)
    {
        srand((double)microtime()*1000000);
        $appKey = '8brlm7uf8bcz3'; // 开发者 App Key
        $appSecret = 'iH0kzREkuOt'; // 开发者平台分配的 App Secret。
        $nonce = rand(); // 获取随机数。
        $timestamp = time(); // 获取时间戳。
        $signature = sha1($appSecret.$nonce.$timestamp);
        // p($appSecret."=".$nonce."=".$timestamp);

        $method = 'POST';
        $url='http://api.cn.ronghub.com/user/getToken.json'; 
        $header[] = 'App-Key:'.$appKey;
        $header[] = 'Nonce:'.$nonce;
        $header[] = 'Timestamp:'.$timestamp;
        $header[] = 'Signature:'.$signature;

        $param = array();
        $param['userId'] = $user_id;
        $param['name'] = $name;
        $param['portraitUri'] = $portrait_uri;

        //获取token
        $token = send_request($url, $param, $header,$method = 'POST',$refererUrl = '', $timeout = 30, $proxy = false);
        $token = json_decode($token,true);

        return $token['token'];
    }
	
	
}