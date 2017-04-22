<?php
namespace Community\Controller;
use Community\Common\ManageController;
use Common\Common\SystemController;
use Community\Common\ExcelReader;
use Community\Common\OLERead;
use Think\Controller;
class ExcelController extends SystemController {

    public function user()
    { 
        $da="16-17.xls";
        $date=$this->Excel($da);
        for ($i=1;$date[1][$i]; $i++) 
        {
            switch ($date[1][$i]) 
            {
                case '姓名':
                    for ($j=2;$date[$j][$i]; $j++) { 
                        $jj=$j-2;
                        $data[$jj]['name']=$date[$j][$i];
                    }
                    break;
                case '学号':
                    for ($j=2;$date[$j][$i]; $j++) { 
                        $jj=$j-2;
                        $data[$jj]['stu_number']=$date[$j][$i];
                    }
                    break;
                case 'ѧԺ':
                    for ($j=2;$date[$j][$i]; $j++) { 
                        $jj=$j-2;
                        $data[$jj]['stu_college']=$date[$j][$i];
                    }
                    break;
                case '职务':
                    for ($j=2;$date[$j][$i]; $j++) { 
                        $jj=$j-2;
                        if ($date[$j][$i]=="干事") {
                            $data[$jj]['cadre']=0;
                        }else{
                            $data[$jj]['cadre']=1;
                        }
                    }                    
                    break;
                case '所属':
                    for ($j=2;$date[$j][$i]; $j++) { 
                        $jj=$j-2;
                        $data[$jj]['department']=$date[$j][$i];
                    }
                    break;
                case '性别':
                    for ($j=2;$date[$j][$i]; $j++) { 
                        $jj=$j-2;
                        $data[$jj]['sex']=$date[$j][$i];
                    }
                case '电话':
                    for ($j=2;$date[$j][$i]; $j++) { 
                        $jj=$j-2;
                        $data[$jj]['phone']=$date[$j][$i];
                    }
                    break;                
                default:
                    # code...
                    break;
            }
        }
        for ($i=0;$data[$i]; $i++) { 
            $data1=$data[$i];
            // var_dump($data1);
            $db=M("user")->add($data1);
        }

    }

    public function user_class()
    { 
        $data['cadre']=1;
        $student=M("user")->where($data)->order('id asc')->select();
        $get_url=$_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"].U('Users/index');
        for ($i=0;$i==0; $i++) 
        { 
            $postdata['student']=$student[$i]['stu_number'];
            $this->postCurlDatas($get_url, $postdata, $other_options = array());
        }
    }
}