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
        for ($i=0;$student[$i]; $i++) 
        { 
            $postdata['student']=$student[$i]['stu_number'];
            $this->postCurlDatas($get_url, $postdata, $other_options = array());
        }
    }
    public function user_pb_week()
    { 
        //分单双周
        
        //不分单双周
    }
    public function user_pb_short()
    { 
        //短时间内排班
        $student=M("user_work_week")->order('id desc')->select();
        //获取所有课程时间内无课人数
        $db=M('');
        $sql="SELECT ww.id,wn.stu_id FROM `wx_user_work_week` as ww left join wx_user_work_null as wn on wn.work_id = ww.id WHERE wn.stu_id is  not  null ORDER BY ww.id ASC ;";
        $rolelist =$db->query($sql);
        $id=0;
        $k=0;
        for ($i=0;$rolelist[$i]; $i++) 
        { 
            $stu_id=$rolelist[$i]['stu_id'];
            if ($id==0) {
                $id=$rolelist[$i]['id'];
                $date[$id][$k]=$rolelist[$i]['stu_id'];
                $k++;
            }elseif ($id==$rolelist[$i]['id']) {
                $date[$id][$k]=$rolelist[$i]['stu_id'];
                $k++;
                $ii=$i+1;
                if ($rolelist[$ii]=="") {
                    $date[$id]['num']=$k;
                }
            }else{
                $date[$id]['num']=$k;
                $k=0;
                $id=$rolelist[$i]['id'];
                $date[$id][$k]=$rolelist[$i]['stu_id'];
                $k++;
                $ii=$i+1;
                if ($rolelist[$ii]=="") {
                    $date[$id]['num']=$k;
                }
            }
            if ($stu[$stu_id]=="") {
                $ks[$stu_id]=0;
                $stu[$stu_id][$ks[$stu_id]]=$id;
                $stu[$stu_id]['num']=1;
            }else{
                $ks[$stu_id]=$ks[$stu_id]+1;
                $stu[$stu_id][$ks[$stu_id]]=$id;
                $ids=$stu[$stu_id]['num'];
                $ids++;
                $stu[$stu_id]['num']=$ids;
            }
        }
        echo "学生";
        echo var_dump($stu);
        echo "<p>空课";
        echo var_dump($date);
        echo "<p>-----------------------------------<p>排序后";
        sort($date[]['num']);
        echo "<P>";
        echo var_dump($date);
        echo "<p>-----------------------------------<p>";
        for ($i=1;$date[$i]['num']; $i++) 
        { 
            //如果该时间端只有一个学生有空
            if ($date[$i]['num']<=1) 
            {
                $work[$i]['stu']=$date[$i][0];
                $stu_id=$date[$i][0];
                //如果该学生只有该时间有空则
                if ($stu[$stu_id]['num']==1) {
                    //该学生只有当前时间有空
                }else
                {
                    for ($m=0;$stu[$stu_id][$m]; $m++) 
                    { 
                        //缓存该学生其他有空时间
                        $nuss[$m]=$stu[$stu_id][$m];
                    }
                    for ($m=0;$nuss[$m]; $m++) 
                    {                 
                        $id=$nuss[$m];   
                        for ($p=0;$date[$id][$p]; $p++) 
                        {   
                            //如果找到则替换
                            if ($date[$id][$p]==$stu_id) 
                            {
                                $date[$id]['num']=$date[$id]['num']-1;
                                $pp=$p+1;
                                if ($date[$id][$pp]=="") 
                                {
                                    $date[$id][$p]="";
                                }else{
                                    for ($l=0;$date[$id][$pp]; $l++) { 
                                        $a[$l]=$date[$id][$pp];
                                        $pp++;
                                    }
                                    for ($l=0;$a[$l]; $l++) { 
                                        $date[$id][$p]=$a[$l];
                                        $p++;
                                    }
                                    $date[$id][$p]="";
                                }
                             } 
                            // echo "<P>";
                            // echo var_dump($date);
                            // echo "<p>-----------------------------------<p>";
                        }
                    }
                }
            }else{
                //如果该时间端有两个及其以上学生有空
                for ($j=0;$date[$i][$j]; $j++) 
                { 
                    $stu_id=$date[$i][$j];
                    $j1[$j]['id']=$stu[$stu_id]['num'];
                    $j1[$j]['name']=$stu_id;
                }
                //以升序排列
                sort($j1);
                //找到空最少学生
                $j1[0];
                $work[$i]['stu']=$j1[0]['name'];
                $stu_id=$j1[0]['name'];
                //如果该学生只有该时间有空则
                if ($stu[$stu_id]['num']==1) {
                    //该学生只有当前时间有空
                }else
                {
                    for ($m=0;$stu[$stu_id][$m]; $m++) 
                    { 
                        //缓存该学生其他有空时间
                        $nuss[$m]=$stu[$stu_id][$m];
                    }
                    //删除该学生其他有课时间
                    for ($m=0;$nuss[$m]; $m++) 
                    { 
                        $id=$nuss[$m];
                        for ($p=0;$date[$id][$p]; $p++) 
                        {   
                            //如果找到则替换
                            if ($date[$id][$p]==$stu_id) 
                            {
                                $date[$id]['num']=$date[$id]['num']-1;
                                $pp=$p+1;
                                if ($date[$id][$pp]=="") 
                                {
                                    $date[$id][$p]="";
                                }else{
                                    for ($l=0;$date[$id][$pp]; $l++) { 
                                        $a[$l]=$date[$id][$pp];
                                        $pp++;
                                    }
                                    for ($l=0;$a[$l]; $l++) { 
                                        $date[$id][$p]=$a[$l];
                                        $p++;
                                    }
                                    $a=array();
                                    $date[$id][$p]="";
                                }
                            } 
                            // echo "<P>";
                            // echo var_dump($date);
                            // echo "<p>-----------------------------------<p>";
                        }
                    }
                }
            }
        }
        echo "排序后:";
        echo var_dump($work);
    }
}