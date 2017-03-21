<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class IndexController extends ManageController {

	function _initialize()
	{
		parent::_initialize(); 
	}

	//生成4位数验证码
    public function verify() {
    	$Verify = new \Think\Verify();  
    	$Verify->fontSize = 18;//字体大小
	    $Verify->length   = 4;//个数
	    $Verify->useNoise = true;//是否添加杂点
	    $Verify->useCurve = true;//是否使用混淆曲线
	    $Verify->codeSet = '0123456789';//验证码字符集
	    //$Verify->codeSet = '0123456789QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm';//验证码字符集
	    $Verify->imageW = 120;//数字宽度
	    $Verify->imageH = 38;//图片宽度
	    //$Verify->expire = 600;
	    $Verify->entry();
    }
    
	public function Login_out() {
        if (IS_AJAX) {
            session(null);
            // $this->ajaxReturn(0,'试试你错了没！');
        }
    }

	//登录页面
    public function index(){ 
    	if (IS_AJAX) {
            $map['username'] = I("post.username");
            $code = $map['code'];
            unset($map['code']); 
            //p($map);
            // $res = check_verify($code);
            // if ($res != 1) {
            //     $this->ajaxReturn(0,'验证码错误！');
            // }
            // else {
                $mod = M("Manages");
                $result = $mod ->where($map) ->find();
                //使用用户名、密码和状态的方式进行认证
                if ($result) {
                    $hashedPassword = $result['password'];
                    $password = I("post.password");
                    $res = check_str($password,$hashedPassword);
                    // $password = encrypt_str($password);
                    if (!$res) 
                    {
                       $this->ajaxReturn(0,'密码错误！');
                    }
                    if ($result['status'] != 1) {
                        $this->ajaxReturn(0,'你的账号被禁用！');
                    }
                    session('manageid', $result['id']); //管理员ID 
                    session('managename', $result['username']); //用户名
                    /******添加登录记录开始******/
                    //获取ip及参考地址
                    $ip = get_client_ip();
                    $Ip = new \Org\Net\IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
                    $area = $Ip->getlocation($ip); // 获取某个IP地址所在的位置
                    $area = $area['country']." ".$area['area'];//拼接地址
                        //$area = trim($area)?$area:"";
                    //添加数据
                    // $data = array();
                    // $data['manageid'] = $result['id'];//管理员id
                    // $data['addtime'] = time();
                    // $data['ip'] = $ip;
                    // $data['area'] = $area;
                   // M('ManagesLog')->add($data);
                        $data = array();  
                        $data['id'] = $result['id'];
                        $data['logintime'] = time();
                        $data['loginip'] = get_client_ip();
                        $data['logincount'] = array('exp', 'logincount + 1');
                        $mod ->save($data);
                    /******添加登录记录结束******/
                    $this->ajaxReturn(1,'登录成功！');
                } 
                else
                {
                    $this->ajaxReturn(0,'用户不存在！');
                } 
        	// } 
            return;
        }
        $this->display("login");
    }

    //退出登录
    public function LoginOut()
    {
        session("manageid",null);
        session("managename",null);
    }
    
    //管理首页
    public function main()
    {
        $permissions = $this->_manage['permissions'];
        $this->_nav($permissions,'1');
    	$this->display();
    }
}