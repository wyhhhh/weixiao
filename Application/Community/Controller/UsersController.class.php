<?php
namespace Community\Controller;
use Community\Common\ManageController;
use Community\Common\ExcelReader;
use Common\Common\SystemController;
use Community\Common\OLERead;
use Think\Controller;
class UsersController extends SystemController {

    function get_html($url){
         $ch = curl_init();
         $timeout = 10;
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
         curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36');
         curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
         $html = curl_exec($ch);
         return $html;
    }
    public function index() { 
        if($_POST['student'])
        {
            $student=$_POST['student'];
            $data_1['stu_number']=$_POST['student'];
            $rolelist_stu = M('user')->where($data_1)->select();
            if ($rolelist_stu) {
                # code...
            }else{
                echo "信息错误";
                exit;
            }
            error_reporting(E_ALL^E_NOTICE^E_WARNING);
            header('Content-Type: text/html; charset=UTF-8');
            // $url="http://localhost/2017/pc/UTF-8.php"; 
            $url="http://jwzx.host.congm.in:88/jwzxtmp/kebiao/kb_stu.php?xh=".$student;//外网内入
            // $url="http://jwzx.cqupt.edu.cn/jwzxtmp/kebiao/kb_stu.php?xh=".$student;
            $str=$this->get_html($url);
            $regex3 = '/(.*?)<div class="printTable">(.*?)<div id="kbStuTabs-list">/s';
            $regex4 = "<tr style='text-align:center'>";
            // $str = 'http://<td >1</td><td >2</td><td ></td><td ></td><td ></td><td ></td>.html';
            $matches = array();
            preg_match($regex3, $str, $mat);
            // echo $mat[2];
            $content=explode($regex4, $mat[2]);
            // var_dump($content);
            for ($all=1;$content[$all]; $all++) { 
                switch ($all) {
                    case '1':
                        $regex = "#^<tr style='text-align:center'><td style='font-weight:bold;'>1、2节</td>(.*?)\</tr>$#s";
                            echo "<p>------------------1、2节------------------<p>";
                            $class="1";
                        break;
                    case '2':
                        $regex = "#^<tr style='text-align:center'><td style='font-weight:bold;'>3、4节</td>(.*?)\</tr>$#s";
                            echo "<p>------------------3、4节------------------<p>";
                            $class="2";
                        break;
                    case '3':
                        $regex = "";
                        break;
                    case '4':
                        $regex = "#^<tr style='text-align:center'><td style='font-weight:bold;'>5、6节</td>(.*?)\</tr>$#s";
                            echo "<p>------------------5、6节------------------<p>";
                            $class="3";
                        break;
                    case '5':
                        $regex = "#^<tr style='text-align:center'><td style='font-weight:bold;'>7、8节</td>(.*?)\</tr>$#s";
                            echo "<p>------------------7、8节------------------<p>";
                            $class="4";
                        break;
                    case '6':
                        $regex = "";
                        break;
                    case '7':
                        $regex = "#^<tr style='text-align:center'><td style='font-weight:bold;'>9、10节</td>(.*?)\</tr>$#s";
                            echo "<p>------------------9、10节------------------<p>";
                            $class="5";
                        break;
                    case '8':
                        $regex = "#^<tr style='text-align:center'><td style='font-weight:bold;'>11、12节</td>(.*?)\</tr>$#s";
                            echo "<p>------------------11、12节------------------<p>";
                        break;  
                    default:
                        exit;
                        break;
                }
                if ($regex!="") {
                    $regex1= "/(<td >(.*?)<\/td>)(<td >(.*?)<\/td>)(<td >(.*?)<\/td>)(<td >(.*?)<\/td>)(<td >(.*?)<\/td>)(<td >(.*?)<\/td>)(<td >(.*?)<\/td>)/s";
                    $regex2="/<td >(.*?)<\/td><td >(.*?)<\/td><td >(.*?)<\/td><td >(.*?)<\/td><td >(.*?)<\/td><td >(.*?)<\/td><td >(.*?)<\/td>/s";
                    $regex3="/<td >(.*?)<hr>(.*?)<\/td>/s";

                    $matches = array();
                    $matches1 = array(); 
                    $a1="<br>";
                    $a2="<font";
                    $a4=",";
                    $ass= "<tr style='text-align:center'>".$content[$all];
                    if(preg_match($regex, "<tr style='text-align:center'>".$content[$all], $matches)){
                    }
                    if(preg_match($regex1, $matches[1], $matches1)){
                    }
                    $a=1;

                        preg_match($regex2, $matches1[0], $matches2);
                        for ($i=1;$i<=5; $i++) 
                        {  

                            if ($matches2[$i]=="<td ></td>"||$matches2[$i]=="") 
                            {
                                echo "<p>星期".$a."无课<p>";
                                for ($j=1; $j < 18; $j++) { 
                                    $data['week_id']=$j;
                                    $data['day']=$a; 
                                    $data['work']=$class; 
                                    $rolelist = M('user_work_week')->where($data)->select();
                                    $data_1['stu_number']=$student;
                                    $rolelist_stu = M('user')->where($data_1)->select();
                                    $date['work_id']=$rolelist[0]['id'];
                                    $date['stu_id']=$rolelist_stu[0]['id'];
                                    $rolelist_add = M('user_work_null')->add($date);
                                }
                            }elseif (strstr($matches2[$i],"<hr>")) 
                            {
                                preg_match($regex3,"<td >".$matches2[$i]."</td>", $matches3);
                                for ($i2=1;$matches3[$i2]; $i2++) 
                                { 
                                    $contents=explode($a1, $matches3[$i2]);
                                    $right=explode($a2, $contents[3]);
                                    if (strstr($right[0],"周")) 
                                    {
                                        echo "<p>星期$a-".$right[0];
                                        $lianshang=0;
                                        if (strstr($matches2[$i2],"节连上")) {
                                            echo "连上";
                                            $lianshang=1;
                                        }
                                        if (strstr($right[0],",")) 
                                        {
                                            $class_time=explode($a4, $right[0]);
                                            for ($k=0;$class_time[$k]; $k++) 
                                            {
                                                if (strstr($class_time[$k],"单")||strstr($class_time[$k],"双")) 
                                                {
                                                    if (strstr($class_time[$k],"单")) {
                                                        $s_D=1;
                                                    }
                                                    $class_times=explode($class_time[$k],"周");
                                                    if (strstr($class_times[0],"-")) 
                                                    {
                                                        $l_if=0;
                                                        $class_a=explode($class_times[0],"-");
                                                        for ($l=0; $l_if==1 ; $l++) { 
                                                            if ($s_D==1) {
                                                                if ($class_a[0]%2==0) {
                                                                    $class_a[0]++;
                                                                }
                                                            }else{
                                                                if ($class_a[0]%2==1) {
                                                                    $class_a[0]++;
                                                                }
                                                            }
                                                            if ($class_a[0]>$class_a[1]) {
                                                                $l_if=1;
                                                            }else{
                                                                $num[$l]=$class_a[0];
                                                            }
                                                            $class_a[0]++;
                                                        }
                                                        for ($l=0;$num[$l]; $l++) { 
                                                            $data['week_id']=$num[$l];
                                                            $data['day']=$a; 
                                                            $data['work']=$class; 
                                                            $rolelist = M('user_work_week')->where($data)->select();
                                                            $rolelist_stu = M('user')->where($data_1)->select();
                                                            $date['work_id']=$rolelist[0]['id'];
                                                            $date['stu_id']=$rolelist_stu[0]['id'];
                                                            $rolelist_add = M('user_work_null')->add($date);
                                                            if ($lianshang==1) {
                                                                $data['week_id']=$num[$l];
                                                                $data['day']=$a; 
                                                                $class2=$class++;
                                                                $data['work']=$class2; 
                                                                $rolelist = M('user_work_week')->where($data)->select();
                                                                $rolelist_stu = M('user')->where($data_1)->select();
                                                                $date['work_id']=$rolelist[0]['id'];
                                                                $date['stu_id']=$rolelist_stu[0]['id'];
                                                                $rolelist_add = M('user_work_null')->add($date);
                                                            }
                                                        }
                                                    }else
                                                    {
                                                        // 无 -
                                                        $class_times=explode($class_time[$k],"周");
                                                        $l_class=1;
                                                        $l_if=0;
                                                        for ($l=0 ; $l_if==1 ; $l++) {
                                                            if ($l==$class_times[0]) 
                                                            {
                                                                $l_class++;
                                                            }
                                                            $num[$l]=$l_class;
                                                            $l_class++;
                                                            if ($l_class==19) {
                                                                $l_if==1;
                                                            }
                                                        }
                                                        for ($l=0;$num[$l]; $l++) 
                                                        { 
                                                            $data['week_id']=$num[$l];
                                                            $data['day']=$a; 
                                                            $data['work']=$class; 
                                                            $rolelist = M('user_work_week')->where($data)->select();
                                                            $rolelist_stu = M('user')->where($data_1)->select();
                                                            $date['work_id']=$rolelist[0]['id'];
                                                            $date['stu_id']=$rolelist_stu[0]['id'];
                                                            $rolelist_add = M('user_work_null')->add($date);
                                                            if ($lianshang==1) {
                                                                $data['week_id']=$num[$l];
                                                                $data['day']=$a; 
                                                                $class2=$class++;
                                                                $data['work']=$class2; 
                                                                $rolelist = M('user_work_week')->where($data)->select();
                                                                $rolelist_stu = M('user')->where($data_1)->select();
                                                                $date['work_id']=$rolelist[0]['id'];
                                                                $date['stu_id']=$rolelist_stu[0]['id'];
                                                                $rolelist_add = M('user_work_null')->add($date);
                                                            }
                                                        }
                                                    }
                                                }else
                                                {
                                                    //无单双周
                                                    $class_times=explode($class_time[$k],"周");
                                                    if (strstr($class_times[0],"-")) 
                                                    {
                                                        $l_if=0;
                                                        $class_a=explode($class_times[0],"-");
                                                        for ($l=0; $l_if==1 ; $l++) { 
                                                            if ($class_a[0]>$class_a[1]) 
                                                            {
                                                                $l_if=1;
                                                            }else{
                                                                $num[$l]=$class_a[0];
                                                            }
                                                            $class_a[0]++;
                                                        }
                                                        for ($l=0;$num[$l]; $l++) { 
                                                            $data['week_id']=$num[$l];
                                                            $data['day']=$a; 
                                                            $data['work']=$class; 
                                                            $rolelist = M('user_work_week')->where($data)->select();
                                                            $rolelist_stu = M('user')->where($data_1)->select();
                                                            $date['work_id']=$rolelist[0]['id'];
                                                            $date['stu_id']=$rolelist_stu[0]['id'];
                                                            $rolelist_add = M('user_work_null')->add($date);
                                                            if ($lianshang==1) {
                                                                $data['week_id']=$num[$l];
                                                                $data['day']=$a; 
                                                                $class2=$class++;
                                                                $data['work']=$class2; 
                                                                $rolelist = M('user_work_week')->where($data)->select();
                                                                $rolelist_stu = M('user')->where($data_1)->select();
                                                                $date['work_id']=$rolelist[0]['id'];
                                                                $date['stu_id']=$rolelist_stu[0]['id'];
                                                                $rolelist_add = M('user_work_null')->add($date);
                                                            }
                                                        }
                                                    }else
                                                    {
                                                        // 无 -
                                                        $l_class=1;
                                                        $l_if=0;
                                                        for ($l=0 ; $l_if==1 ; $l++) {
                                                            if ($l==$class_times[0]) 
                                                            {
                                                                $l_class++;
                                                            }
                                                            $num[$l]=$l_class;
                                                            $l_class++;
                                                            if ($l_class==19) {
                                                                $l_if==1;
                                                            }
                                                        }
                                                        for ($l=0;$num[$l]; $l++) 
                                                        { 
                                                            $data['week_id']=$num[$l];
                                                            $data['day']=$a; 
                                                            $data['work']=$class; 
                                                            $rolelist = M('user_work_week')->where($data)->select();
                                                            $rolelist_stu = M('user')->where($data_1)->select();
                                                            $date['work_id']=$rolelist[0]['id'];
                                                            $date['stu_id']=$rolelist_stu[0]['id'];
                                                            $rolelist_add = M('user_work_null')->add($date);
                                                            if ($lianshang==1) {
                                                                $data['week_id']=$num[$l];
                                                                $data['day']=$a; 
                                                                $class2=$class++;
                                                                $data['work']=$class2; 
                                                                $rolelist = M('user_work_week')->where($data)->select();
                                                                $rolelist_stu = M('user')->where($data_1)->select();
                                                                $date['work_id']=$rolelist[0]['id'];
                                                                $date['stu_id']=$rolelist_stu[0]['id'];
                                                                $rolelist_add = M('user_work_null')->add($date);
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }else
                                        {
                                        //无 , 
                                            if (strstr($right[$k],"单")||strstr($right[$k],"双")) 
                                            {
                                                if (strstr($right[$k],"单")) {
                                                    $s_D=1;
                                                }
                                                $class_times=explode($right[$k],"周");
                                                if (strstr($class_times[0],"-")) 
                                                {
                                                    $l_if=0;
                                                    $class_a=explode($class_times[0],"-");
                                                    for ($l=0; $l_if==1 ; $l++) { 
                                                        if ($s_D==1) {
                                                            if ($class_a[0]%2==0) {
                                                                $class_a[0]++;
                                                            }
                                                        }else{
                                                            if ($class_a[0]%2==1) {
                                                                $class_a[0]++;
                                                            }
                                                        }
                                                        if ($class_a[0]>$class_a[1]) {
                                                            $l_if=1;
                                                        }else{
                                                            $num[$l]=$class_a[0];
                                                        }
                                                        $class_a[0]++;
                                                    }
                                                    for ($l=0;$num[$l]; $l++) { 
                                                        $data['week_id']=$num[$l];
                                                        $data['day']=$a; 
                                                        $data['work']=$class; 
                                                        $rolelist = M('user_work_week')->where($data)->select();
                                                        $rolelist_stu = M('user')->where($data_1)->select();
                                                        $date['work_id']=$rolelist[0]['id'];
                                                        $date['stu_id']=$rolelist_stu[0]['id'];
                                                        $rolelist_add = M('user_work_null')->add($date);
                                                        if ($lianshang==1) {
                                                            $data['week_id']=$num[$l];
                                                            $data['day']=$a; 
                                                            $class2=$class++;
                                                            $data['work']=$class2; 
                                                            $rolelist = M('user_work_week')->where($data)->select();
                                                            $rolelist_stu = M('user')->where($data_1)->select();
                                                            $date['work_id']=$rolelist[0]['id'];
                                                            $date['stu_id']=$rolelist_stu[0]['id'];
                                                            $rolelist_add = M('user_work_null')->add($date);
                                                        }
                                                    }
                                                }else
                                                {
                                                    // 无 -
                                                    $l_class=1;
                                                    $l_if=0;
                                                    for ($l=0 ; $l_if==1 ; $l++) {
                                                        if ($l==$class_times[0]) 
                                                        {
                                                            $l_class++;
                                                        }
                                                        $num[$l]=$l_class;
                                                        $l_class++;
                                                        if ($l_class==19) {
                                                            $l_if==1;
                                                        }
                                                    }
                                                    for ($l=0;$num[$l]; $l++) 
                                                    { 
                                                        $data['week_id']=$num[$l];
                                                        $data['day']=$a; 
                                                        $data['work']=$class; 
                                                        $rolelist = M('user_work_week')->where($data)->select();
                                                        $rolelist_stu = M('user')->where($data_1)->select();
                                                        $date['work_id']=$rolelist[0]['id'];
                                                        $date['stu_id']=$rolelist_stu[0]['id'];
                                                        $rolelist_add = M('user_work_null')->add($date);
                                                        if ($lianshang==1) {
                                                            $data['week_id']=$num[$l];
                                                            $data['day']=$a; 
                                                            $class2=$class++;
                                                            $data['work']=$class2; 
                                                            $rolelist = M('user_work_week')->where($data)->select();
                                                            $rolelist_stu = M('user')->where($data_1)->select();
                                                            $date['work_id']=$rolelist[0]['id'];
                                                            $date['stu_id']=$rolelist_stu[0]['id'];
                                                            $rolelist_add = M('user_work_null')->add($date);
                                                        }
                                                    }
                                                }
                                            }else
                                            {
                                                //无单双周
                                                $class_times=explode($right[$k],"周");
                                                if (strstr($class_times[0],"-")) 
                                                {
                                                    $l_if=0;
                                                    $class_a=explode($class_times[0],"-");
                                                    for ($l=0; $l_if==1 ; $l++) { 
                                                        if ($class_a[0]>$class_a[1]) 
                                                        {
                                                            $l_if=1;
                                                        }else{
                                                            $num[$l]=$class_a[0];
                                                        }
                                                        $class_a[0]++;
                                                    }
                                                    for ($l=0;$num[$l]; $l++) { 
                                                        $data['week_id']=$num[$l];
                                                        $data['day']=$a; 
                                                        $data['work']=$class; 
                                                        $rolelist = M('user_work_week')->where($data)->select();
                                                        $rolelist_stu = M('user')->where($data_1)->select();
                                                        $date['work_id']=$rolelist[0]['id'];
                                                        $date['stu_id']=$rolelist_stu[0]['id'];
                                                        $rolelist_add = M('user_work_null')->add($date);
                                                        if ($lianshang==1) {
                                                            $data['week_id']=$num[$l];
                                                            $data['day']=$a; 
                                                            $class2=$class++;
                                                            $data['work']=$class2; 
                                                            $rolelist = M('user_work_week')->where($data)->select();
                                                            $rolelist_stu = M('user')->where($data_1)->select();
                                                            $date['work_id']=$rolelist[0]['id'];
                                                            $date['stu_id']=$rolelist_stu[0]['id'];
                                                            $rolelist_add = M('user_work_null')->add($date);
                                                        }
                                                    }
                                                }else
                                                {
                                                    // 无 -
                                                    $l_class=1;
                                                    $l_if=0;
                                                    for ($l=0 ; $l_if==1 ; $l++) {
                                                        if ($l==$right[0]) 
                                                        {
                                                            $l_class++;
                                                        }
                                                        $num[$l]=$l_class;
                                                        $l_class++;
                                                        if ($l_class==19) {
                                                            $l_if==1;
                                                        }
                                                    }
                                                    for ($l=0;$num[$l]; $l++) 
                                                    { 
                                                        $data['week_id']=$num[$l];
                                                        $data['day']=$a; 
                                                        $data['work']=$class; 
                                                        $rolelist = M('user_work_week')->where($data)->select();
                                                        $rolelist_stu = M('user')->where($data_1)->select();
                                                        $date['work_id']=$rolelist[0]['id'];
                                                        $date['stu_id']=$rolelist_stu[0]['id'];
                                                        $rolelist_add = M('user_work_null')->add($date);
                                                        if ($lianshang==1) {
                                                            $data['week_id']=$num[$l];
                                                            $data['day']=$a; 
                                                            $class2=$class++;
                                                            $data['work']=$class2; 
                                                            $rolelist = M('user_work_week')->where($data)->select();
                                                            $rolelist_stu = M('user')->where($data_1)->select();
                                                            $date['work_id']=$rolelist[0]['id'];
                                                            $date['stu_id']=$rolelist_stu[0]['id'];
                                                            $rolelist_add = M('user_work_null')->add($date);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }else
                            {
                                $contents=explode($a1, $matches2[$i]);
                                $right=explode($a2, $contents[3]);
                                echo "<p>星期$a-".$right[0];
                                $lianshang=0;
                                if (strstr($matches2[$i2],"节连上")) {
                                    echo "连上";
                                    $lianshang=1;
                                }
                                if (strstr($right[0],",")) 
                                {
                                    $class_time=explode($a4, $right[0]);
                                    for ($k=0;$class_time[$k]; $k++) 
                                    {
                                        if (strstr($class_time[$k],"单")||strstr($class_time[$k],"双")) 
                                        {
                                            if (strstr($class_time[$k],"单")) {
                                                $s_D=1;
                                            }
                                            $class_times=explode($class_time[$k],"周");
                                            if (strstr($class_times[0],"-")) 
                                            {
                                                $l_if=0;
                                                $class_a=explode($class_times[0],"-");
                                                for ($l=0; $l_if==1 ; $l++) { 
                                                    if ($s_D==1) {
                                                        if ($class_a[0]%2==0) {
                                                            $class_a[0]++;
                                                        }
                                                    }else{
                                                        if ($class_a[0]%2==1) {
                                                            $class_a[0]++;
                                                        }
                                                    }
                                                    if ($class_a[0]>$class_a[1]) {
                                                        $l_if=1;
                                                    }else{
                                                        $num[$l]=$class_a[0];
                                                    }
                                                    $class_a[0]++;
                                                }
                                                for ($l=0;$num[$l]; $l++) { 
                                                    $data['week_id']=$num[$l];
                                                    $data['day']=$a; 
                                                    $data['work']=$class; 
                                                    $rolelist = M('user_work_week')->where($data)->select();
                                                    $rolelist_stu = M('user')->where($data_1)->select();
                                                    $date['work_id']=$rolelist[0]['id'];
                                                    $date['stu_id']=$rolelist_stu[0]['id'];
                                                    $rolelist_add = M('user_work_null')->add($date);
                                                    if ($lianshang==1) {
                                                        $data['week_id']=$num[$l];
                                                        $data['day']=$a; 
                                                        $class2=$class++;
                                                        $data['work']=$class2; 
                                                        $rolelist = M('user_work_week')->where($data)->select();
                                                        $rolelist_stu = M('user')->where($data_1)->select();
                                                        $date['work_id']=$rolelist[0]['id'];
                                                        $date['stu_id']=$rolelist_stu[0]['id'];
                                                        $rolelist_add = M('user_work_null')->add($date);
                                                    }
                                                }
                                            }else
                                            {
                                                // 无 -
                                                $class_times=explode($class_time[$k],"周");
                                                $l_class=1;
                                                $l_if=0;
                                                for ($l=0 ; $l_if==1 ; $l++) {
                                                    if ($l==$class_times[0]) 
                                                    {
                                                        $l_class++;
                                                    }
                                                    $num[$l]=$l_class;
                                                    $l_class++;
                                                    if ($l_class==19) {
                                                        $l_if==1;
                                                    }
                                                }
                                                for ($l=0;$num[$l]; $l++) 
                                                { 
                                                    $data['week_id']=$num[$l];
                                                    $data['day']=$a; 
                                                    $data['work']=$class; 
                                                    $rolelist = M('user_work_week')->where($data)->select();
                                                    $rolelist_stu = M('user')->where($data_1)->select();
                                                    $date['work_id']=$rolelist[0]['id'];
                                                    $date['stu_id']=$rolelist_stu[0]['id'];
                                                    $rolelist_add = M('user_work_null')->add($date);
                                                    if ($lianshang==1) {
                                                        $data['week_id']=$num[$l];
                                                        $data['day']=$a; 
                                                        $class2=$class++;
                                                        $data['work']=$class2; 
                                                        $rolelist = M('user_work_week')->where($data)->select();
                                                        $rolelist_stu = M('user')->where($data_1)->select();
                                                        $date['work_id']=$rolelist[0]['id'];
                                                        $date['stu_id']=$rolelist_stu[0]['id'];
                                                        $rolelist_add = M('user_work_null')->add($date);
                                                    }
                                                }
                                            }
                                        }else
                                        {
                                            //无单双周
                                            $class_times=explode($class_time[$k],"周");
                                            if (strstr($class_times[0],"-")) 
                                            {
                                                $l_if=0;
                                                $class_a=explode($class_times[0],"-");
                                                for ($l=0; $l_if==1 ; $l++) { 
                                                    if ($class_a[0]>$class_a[1]) 
                                                    {
                                                        $l_if=1;
                                                    }else{
                                                        $num[$l]=$class_a[0];
                                                    }
                                                    $class_a[0]++;
                                                }
                                                for ($l=0;$num[$l]; $l++) { 
                                                    $data['week_id']=$num[$l];
                                                    $data['day']=$a; 
                                                    $data['work']=$class; 
                                                    $rolelist = M('user_work_week')->where($data)->select();
                                                    $rolelist_stu = M('user')->where($data_1)->select();
                                                    $date['work_id']=$rolelist[0]['id'];
                                                    $date['stu_id']=$rolelist_stu[0]['id'];
                                                    $rolelist_add = M('user_work_null')->add($date);
                                                    if ($lianshang==1) {
                                                        $data['week_id']=$num[$l];
                                                        $data['day']=$a; 
                                                        $class2=$class++;
                                                        $data['work']=$class2; 
                                                        $rolelist = M('user_work_week')->where($data)->select();
                                                        $rolelist_stu = M('user')->where($data_1)->select();
                                                        $date['work_id']=$rolelist[0]['id'];
                                                        $date['stu_id']=$rolelist_stu[0]['id'];
                                                        $rolelist_add = M('user_work_null')->add($date);
                                                    }
                                                }
                                            }else
                                            {
                                                // 无 -
                                                $l_class=1;
                                                $l_if=0;
                                                for ($l=0 ; $l_if==1 ; $l++) {
                                                    if ($l==$class_times[0]) 
                                                    {
                                                        $l_class++;
                                                    }
                                                    $num[$l]=$l_class;
                                                    $l_class++;
                                                    if ($l_class==19) {
                                                        $l_if==1;
                                                    }
                                                }
                                                for ($l=0;$num[$l]; $l++) 
                                                { 
                                                    $data['week_id']=$num[$l];
                                                    $data['day']=$a; 
                                                    $data['work']=$class; 
                                                    $rolelist = M('user_work_week')->where($data)->select();
                                                    $rolelist_stu = M('user')->where($data_1)->select();
                                                    $date['work_id']=$rolelist[0]['id'];
                                                    $date['stu_id']=$rolelist_stu[0]['id'];
                                                    $rolelist_add = M('user_work_null')->add($date);
                                                    if ($lianshang==1) {
                                                        $data['week_id']=$num[$l];
                                                        $data['day']=$a; 
                                                        $class2=$class++;
                                                        $data['work']=$class2; 
                                                        $rolelist = M('user_work_week')->where($data)->select();
                                                        $rolelist_stu = M('user')->where($data_1)->select();
                                                        $date['work_id']=$rolelist[0]['id'];
                                                        $date['stu_id']=$rolelist_stu[0]['id'];
                                                        $rolelist_add = M('user_work_null')->add($date);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }else
                                {
                                //无 , 
                                    if (strstr($right[$k],"单")||strstr($right[$k],"双")) 
                                    {
                                        if (strstr($right[$k],"单")) {
                                            $s_D=1;
                                        }
                                        $class_times=explode($right[$k],"周");
                                        if (strstr($class_times[0],"-")) 
                                        {
                                            $l_if=0;
                                            $class_a=explode($class_times[0],"-");
                                            for ($l=0; $l_if==1 ; $l++) { 
                                                if ($s_D==1) {
                                                    if ($class_a[0]%2==0) {
                                                        $class_a[0]++;
                                                    }
                                                }else{
                                                    if ($class_a[0]%2==1) {
                                                        $class_a[0]++;
                                                    }
                                                }
                                                if ($class_a[0]>$class_a[1]) {
                                                    $l_if=1;
                                                }else{
                                                    $num[$l]=$class_a[0];
                                                }
                                                $class_a[0]++;
                                            }
                                            for ($l=0;$num[$l]; $l++) { 
                                                $data['week_id']=$num[$l];
                                                $data['day']=$a; 
                                                $data['work']=$class; 
                                                $rolelist = M('user_work_week')->where($data)->select();
                                                $rolelist_stu = M('user')->where($data_1)->select();
                                                $date['work_id']=$rolelist[0]['id'];
                                                $date['stu_id']=$rolelist_stu[0]['id'];
                                                $rolelist_add = M('user_work_null')->add($date);
                                                if ($lianshang==1) {
                                                    $data['week_id']=$num[$l];
                                                    $data['day']=$a; 
                                                    $class2=$class++;
                                                    $data['work']=$class2; 
                                                    $rolelist = M('user_work_week')->where($data)->select();
                                                    $rolelist_stu = M('user')->where($data_1)->select();
                                                    $date['work_id']=$rolelist[0]['id'];
                                                    $date['stu_id']=$rolelist_stu[0]['id'];
                                                    $rolelist_add = M('user_work_null')->add($date);
                                                }
                                            }
                                        }else
                                        {
                                            // 无 -
                                            $l_class=1;
                                            $l_if=0;
                                            for ($l=0 ; $l_if==1 ; $l++) {
                                                if ($l==$class_times[0]) 
                                                {
                                                    $l_class++;
                                                }
                                                $num[$l]=$l_class;
                                                $l_class++;
                                                if ($l_class==19) {
                                                    $l_if==1;
                                                }
                                            }
                                            for ($l=0;$num[$l]; $l++) 
                                            { 
                                                $data['week_id']=$num[$l];
                                                $data['day']=$a; 
                                                $data['work']=$class; 
                                                $rolelist = M('user_work_week')->where($data)->select();
                                                $rolelist_stu = M('user')->where($data_1)->select();
                                                $date['work_id']=$rolelist[0]['id'];
                                                $date['stu_id']=$rolelist_stu[0]['id'];
                                                $rolelist_add = M('user_work_null')->add($date);
                                                if ($lianshang==1) {
                                                    $data['week_id']=$num[$l];
                                                    $data['day']=$a; 
                                                    $class2=$class++;
                                                    $data['work']=$class2; 
                                                    $rolelist = M('user_work_week')->where($data)->select();
                                                    $rolelist_stu = M('user')->where($data_1)->select();
                                                    $date['work_id']=$rolelist[0]['id'];
                                                    $date['stu_id']=$rolelist_stu[0]['id'];
                                                    $rolelist_add = M('user_work_null')->add($date);
                                                }
                                            }
                                        }
                                    }else
                                    {
                                        //无单双周
                                        $class_times=explode($right[$k],"周");
                                        if (strstr($class_times[0],"-")) 
                                        {
                                            $l_if=0;
                                            $class_a=explode($class_times[0],"-");
                                            for ($l=0; $l_if==1 ; $l++) { 
                                                if ($class_a[0]>$class_a[1]) 
                                                {
                                                    $l_if=1;
                                                }else{
                                                    $num[$l]=$class_a[0];
                                                }
                                                $class_a[0]++;
                                            }
                                            for ($l=0;$num[$l]; $l++) { 
                                                $data['week_id']=$num[$l];
                                                $data['day']=$a; 
                                                $data['work']=$class; 
                                                $rolelist = M('user_work_week')->where($data)->select();
                                                $data_1['stu_number']=$student;
                                                $rolelist_stu = M('user')->where($data_1)->select();
                                                $date['work_id']=$rolelist[0]['id'];
                                                $date['stu_id']=$rolelist_stu[0]['id'];
                                                $rolelist_add = M('user_work_null')->add($date);
                                                if ($lianshang==1) {
                                                    $data['week_id']=$num[$l];
                                                    $data['day']=$a; 
                                                    $class2=$class++;
                                                    $data['work']=$class2; 
                                                    $rolelist = M('user_work_week')->where($data)->select();
                                                    $data_1['stu_number']=$student;
                                                    $rolelist_stu = M('user')->where($data_1)->select();
                                                    $date['work_id']=$rolelist[0]['id'];
                                                    $date['stu_id']=$rolelist_stu[0]['id'];
                                                    $rolelist_add = M('user_work_null')->add($date);
                                                }
                                            }
                                        }else
                                        {
                                            // 无 -
                                            $l_class=1;
                                            $l_if=0;
                                            for ($l=0 ; $l_if==1 ; $l++) {
                                                if ($l==$right[0]) 
                                                {
                                                    $l_class++;
                                                }
                                                $num[$l]=$l_class;
                                                $l_class++;
                                                if ($l_class==19) {
                                                    $l_if==1;
                                                }
                                            }
                                            for ($l=0;$num[$l]; $l++) 
                                            { 
                                                $data['week_id']=$num[$l];
                                                $data['day']=$a; 
                                                $data['work']=$class; 
                                                $rolelist = M('user_work_week')->where($data)->select();    
                                                $rolelist_stu = M('user')->where($data_1)->select();
                                                $date['work_id']=$rolelist[0]['id'];
                                                $date['stu_id']=$rolelist_stu[0]['id'];
                                                $rolelist_add = M('user_work_null')->add($date);
                                                if ($lianshang==1) {
                                                    $data['week_id']=$num[$l];
                                                    $data['day']=$a; 
                                                    $class2=$class++;
                                                    $data['work']=$class2; 
                                                    $rolelist = M('user_work_week')->where($data)->select();
                                                    $rolelist_stu = M('user')->where($data_1)->select();
                                                    $date['work_id']=$rolelist[0]['id'];
                                                    $date['stu_id']=$rolelist_stu[0]['id'];
                                                    $rolelist_add = M('user_work_null')->add($date);
                                                }
                                            }
                                        }
                                    }
                                }
                                    
                            }
                            //更新天天数
                            $a++;

                        }   
                }
            }
        }
    }
    
}
?>