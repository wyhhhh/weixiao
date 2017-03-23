<?php 
namespace Common\Common;

class SystemController extends BaseController {
    public function _initialize() {
        parent::_initialize();  
        if (IS_GET && !IS_AJAX) {
            $key = I('get.key');
            $group = MODULE_NAME; 
        }
    }

//post访问网页获取值
function postCurlDatas($get_url, $postdata = '', $other_options = array()) {
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $get_url); // 要访问的地址
//    curl_setopt($curl, CURLOPT_USERAGENT, $GLOBALS ['user_agent']);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_POST, true); // 发送一个常规的Post请求 
    curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false); // 禁用全局DNS缓存 
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata); //此参数必须在上面的参数之后，切记
    if (!empty($other_options['userpwd'])) {
        curl_setopt($curl, CURLOPT_USERPWD, $other_options['userpwd']);
    }
    if (!empty($other_options['time_out'])) {
        curl_setopt($curl, CURLOPT_TIMEOUT, $other_options['time_out']);
    } else {
        curl_setopt($curl, CURLOPT_TIMEOUT, 5); // 设置超时限制防止死循环
    }
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回   
    $ret = curl_exec($curl); // 执行操作    
    if ($ret === false) {
        echo 'Curl error: ' . curl_error($curl);
        curl_close($curl);
        return false;
    }
    if ($other_options['return_detail'] == true) {
        $detail = curl_getinfo($curl);
        if (is_array($detail)) {
            $detail['return_content'] = $ret;
        }
        $ret = $detail;
    }
    curl_close($curl); // 关闭CURL会话
    return $ret;
}
public function Pushs2($content,$title,$url,$tag)
    {
        $gurl=U('Community/PushTo/index');
        $po['type']=9;
        $po['content']=$content;
        $po['title']=$title;
        $po['url']=$url;
        $po['tag']=$tag;
        $po['ids']=2;
        $a=$this->postCurlDatas($gurl,$po,$other_options = array());
        return 1;
    }
//解析Excel
    public function Excel($da)
    {
        if(session('Excel')!=""){
            $timesss=session('Excel');
        };
        $Excel=time();
        if ($timesss>$Excel-5) {
            return 3;
        }
        define('NUM_BIG_BLOCK_DEPOT_BLOCKS_POS', 0x2c);  
        define('SMALL_BLOCK_DEPOT_BLOCK_POS', 0x3c);  
        define('ROOT_START_BLOCK_POS', 0x30);  
        define('BIG_BLOCK_SIZE', 0x200);  
        define('SMALL_BLOCK_SIZE', 0x40);  
        define('EXTENSION_BLOCK_POS', 0x44);  
        define('NUM_EXTENSION_BLOCK_POS', 0x48);  
        define('PROPERTY_STORAGE_BLOCK_SIZE', 0x80);  
        define('BIG_BLOCK_DEPOT_BLOCKS_POS', 0x4c);  
        define('SMALL_BLOCK_THRESHOLD', 0x1000);  
        define('SIZE_OF_NAME_POS', 0x40);  
        define('TYPE_POS', 0x42);  
        define('START_BLOCK_POS', 0x74);  
        define('SIZE_POS', 0x78);  
        define('IDENTIFIER_OLE', pack("CCCCCCCC", 0xd0, 0xcf, 0x11, 0xe0, 0xa1, 0xb1, 0x1a, 0xe1));  
        define('SPREADSHEET_EXCEL_READER_BIFF8', 0x600);  
        define('SPREADSHEET_EXCEL_READER_BIFF7', 0x500);  
        define('SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS', 0x5);  
        define('SPREADSHEET_EXCEL_READER_WORKSHEET', 0x10);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOF', 0x809);  
        define('SPREADSHEET_EXCEL_READER_TYPE_EOF', 0x0a);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET', 0x85);  
        define('SPREADSHEET_EXCEL_READER_TYPE_DIMENSION', 0x200);  
        define('SPREADSHEET_EXCEL_READER_TYPE_ROW', 0x208);  
        define('SPREADSHEET_EXCEL_READER_TYPE_DBCELL', 0xd7);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FILEPASS', 0x2f);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NOTE', 0x1c);  
        define('SPREADSHEET_EXCEL_READER_TYPE_TXO', 0x1b6);  
        define('SPREADSHEET_EXCEL_READER_TYPE_RK', 0x7e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_RK2', 0x27e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MULRK', 0xbd);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MULBLANK', 0xbe);  
        define('SPREADSHEET_EXCEL_READER_TYPE_INDEX', 0x20b);  
        define('SPREADSHEET_EXCEL_READER_TYPE_SST', 0xfc);  
        define('SPREADSHEET_EXCEL_READER_TYPE_EXTSST', 0xff);  
        define('SPREADSHEET_EXCEL_READER_TYPE_CONTINUE', 0x3c);  
        define('SPREADSHEET_EXCEL_READER_TYPE_LABEL', 0x204);  
        define('SPREADSHEET_EXCEL_READER_TYPE_LABELSST', 0xfd);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NUMBER', 0x203);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NAME', 0x18);  
        define('SPREADSHEET_EXCEL_READER_TYPE_ARRAY', 0x221);  
        define('SPREADSHEET_EXCEL_READER_TYPE_STRING', 0x207);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA', 0x406);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA2', 0x6);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMAT', 0x41e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_XF', 0xe0);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOOLERR', 0x205);  
        define('SPREADSHEET_EXCEL_READER_TYPE_UNKNOWN', 0xffff);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR', 0x22);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS', 0xE5);  
        define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS', 25569);  
        define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1904', 24107);  
        define('SPREADSHEET_EXCEL_READER_MSINADAY', 86400);  
        define('SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT', "%s");    
        $data =new \Community\Common\ExcelReader();  
        $data->ExcelReader();
        $data->setOutputEncoding('CP936');  
        $data->read('./uploads/'.$da);  
        error_reporting(E_ALL ^ E_NOTICE);  
        $fname = $data->sheets[0]['cells'][3][2];//前缀
        $lname = $data->sheets[0]['cells'][3][4];//后缀
        $dif = $data->sheets[0]['cells'][3][6];//单元是否
        $dif = characet($dif);
        $Building = M("Building");
        $Unit = M("Unit");
        $Floor = M("Floor");
        $House = M("House");
        $dada=array();
        $a=6;
        for ($i=0; $data->sheets[0]['cells'][$a][1] ; $i++) { 
            $a=$i+6;
            $name = $fname.$data->sheets[0]['cells'][$a][1].$lname;//编号
            $name= characet($name);
                        $dada[$i]['name']=$name; 
            $alias_name = $data->sheets[0]['cells'][$a][1].$lname;
            $alias_name= characet($alias_name);
                        $dada[$i]['alias_name']=$alias_name; 
            $dyss = $data->sheets[0]['cells'][$a][2];//单元
            $dyss= characet($dyss);
                        $dada[$i]['dyss']=$dyss; 
            $lc = $data->sheets[0]['cells'][$a][3];//楼层 
            $lc= characet($lc);
                        $dada[$i]['lc']=$lc;
            $fs = $data->sheets[0]['cells'][$a][4];//户数
            $fs= characet($fs);
                        $dada[$i]['fs']=$fs; 
            $wg = $data->sheets[0]['cells'][$a][5];//物管费
            $wg= characet($wg);
                        $dada[$i]['wg']=$wg; 
            $mj = $data->sheets[0]['cells'][$a][6];//房屋面积
            $mj= characet($mj);
                        $dada[$i]['mj']=$mj; 
            $a++;

        }
        $time=time();
        for ($i1=0;$dada[$i1]['name']!=""; $i1++) { 
            $name = $dada[$i1]['name'];
            $alias_name = $dada[$i1]['alias_name'];
            $dyss = $dada[$i1]['dyss'];
            $lc = $dada[$i1]['lc'];
            $fs = $dada[$i1]['fs'];
            $wg = $dada[$i1]['wg'];
            $mj = $dada[$i1]['mj'];
            //楼栋创建
            $builds = array();
            $cid=$_SESSION['communityid'];
            $builds['communityid'] = $cid;
            $builds['name'] = $alias_name;
            $builds['alias_name'] = $name;
            $builds['addtime'] = $time;
            $builds['unit_price'] = $wg;
            $Building->add($builds);
            $build = $Building->where("alias_name='$name' AND communityid = '$cid' ")->select();
            $bid = $build[0]['id'];
            //单元创建
            $dy=explode(",", $dyss);
            for ($b=0; $dy[$b] ; $b++) { 
                $Units = array();
                $Units['uname'] = $dy[$b]."单元";
                $uname=$dy[$b]."单元";
                $Units['buildingid'] = $bid;
                $Units['communityid'] = $cid;
                $Units['addtime'] = $time;
                $Unit->add($Units);
                $Unit1 = $Unit->where($Units)->select();
                $uid = $Unit1[0]['id'];

         //        //楼层创建
                for ($c=1; $c <= $lc; $c++) { 
                    $Floors = array();
                    $Floors['fname'] = $c."楼";
                    $fname = $c."楼";
                    $Floors['unitid'] = $uid;
                    $Floors['communityid'] = $cid;
                    $Floors['addtime'] = $time;
                    $Floor->add($Floors);
                    $Floor1 = $Floor->where($Floors)->select();
                    $fid = $Floor1[0]['id'];
                    for ($d=1; $d <= $fs; $d++) { 
                        if($d<=9)
                        {
                            $hou=$c."0".$d;
                        }else{
                            $hou=$c.$d;
                        }
                        $Houses = array();
                        $Houses['floorid'] = $fid;
                        $Houses['name'] = $hou;
                        $Houses['number'] = $alias_name."-".$uname."-".$hou;
                        $Houses['addtime'] = time();
                        $Houses['house_area'] = $mj."m²";
                        $Houses['communityid'] = $cid;
                        $House->add($Houses);
                    }       
                }

            }
         }
         return 1;
    }
    public function Excel_read_user($da)
    {
        if(session('Excel_read_user')!=""){
            $timesss=session('Excel_read_user');
        };
        $Excel_read_user=time();
        if ($timesss>$Excel_read_user-5) {
            return 3;
        }
        session('Excel_read_user',$Excel_read_user);
        define('NUM_BIG_BLOCK_DEPOT_BLOCKS_POS', 0x2c);  
        define('SMALL_BLOCK_DEPOT_BLOCK_POS', 0x3c);  
        define('ROOT_START_BLOCK_POS', 0x30);  
        define('BIG_BLOCK_SIZE', 0x200);  
        define('SMALL_BLOCK_SIZE', 0x40);  
        define('EXTENSION_BLOCK_POS', 0x44);  
        define('NUM_EXTENSION_BLOCK_POS', 0x48);  
        define('PROPERTY_STORAGE_BLOCK_SIZE', 0x80);  
        define('BIG_BLOCK_DEPOT_BLOCKS_POS', 0x4c);  
        define('SMALL_BLOCK_THRESHOLD', 0x1000);  
        define('SIZE_OF_NAME_POS', 0x40);  
        define('TYPE_POS', 0x42);  
        define('START_BLOCK_POS', 0x74);  
        define('SIZE_POS', 0x78);  
        define('IDENTIFIER_OLE', pack("CCCCCCCC", 0xd0, 0xcf, 0x11, 0xe0, 0xa1, 0xb1, 0x1a, 0xe1));  
        define('SPREADSHEET_EXCEL_READER_BIFF8', 0x600);  
        define('SPREADSHEET_EXCEL_READER_BIFF7', 0x500);  
        define('SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS', 0x5);  
        define('SPREADSHEET_EXCEL_READER_WORKSHEET', 0x10);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOF', 0x809);  
        define('SPREADSHEET_EXCEL_READER_TYPE_EOF', 0x0a);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET', 0x85);  
        define('SPREADSHEET_EXCEL_READER_TYPE_DIMENSION', 0x200);  
        define('SPREADSHEET_EXCEL_READER_TYPE_ROW', 0x208);  
        define('SPREADSHEET_EXCEL_READER_TYPE_DBCELL', 0xd7);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FILEPASS', 0x2f);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NOTE', 0x1c);  
        define('SPREADSHEET_EXCEL_READER_TYPE_TXO', 0x1b6);  
        define('SPREADSHEET_EXCEL_READER_TYPE_RK', 0x7e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_RK2', 0x27e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MULRK', 0xbd);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MULBLANK', 0xbe);  
        define('SPREADSHEET_EXCEL_READER_TYPE_INDEX', 0x20b);  
        define('SPREADSHEET_EXCEL_READER_TYPE_SST', 0xfc);  
        define('SPREADSHEET_EXCEL_READER_TYPE_EXTSST', 0xff);  
        define('SPREADSHEET_EXCEL_READER_TYPE_CONTINUE', 0x3c);  
        define('SPREADSHEET_EXCEL_READER_TYPE_LABEL', 0x204);  
        define('SPREADSHEET_EXCEL_READER_TYPE_LABELSST', 0xfd);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NUMBER', 0x203);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NAME', 0x18);  
        define('SPREADSHEET_EXCEL_READER_TYPE_ARRAY', 0x221);  
        define('SPREADSHEET_EXCEL_READER_TYPE_STRING', 0x207);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA', 0x406);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA2', 0x6);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMAT', 0x41e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_XF', 0xe0);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOOLERR', 0x205);  
        define('SPREADSHEET_EXCEL_READER_TYPE_UNKNOWN', 0xffff);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR', 0x22);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS', 0xE5);  
        define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS', 25569);  
        define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1904', 24107);  
        define('SPREADSHEET_EXCEL_READER_MSINADAY', 86400);  
        define('SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT', "%s");    
        $data =new \Community\Common\ExcelReader();  
        $data->ExcelReader();
        $data->setOutputEncoding('CP936');  
        $data->read('./uploads/'.$da);  
        error_reporting(E_ALL ^ E_NOTICE);  
        for ($i=4;$data->sheets[0]['cells'][$i][1] ; $i++) { 
            if ($data->sheets[0]['cells'][$i][1]=="") {
                break;
            }
            $id=$data->sheets[0]['cells'][$i][1];
            $user['real_name']=$data->sheets[0]['cells'][$i][2];//姓名
            $user['real_name'] = iconv("GB2312", "UTF-8",$user['real_name']);
            $user['id_card']=$data->sheets[0]['cells'][$i][3];//身份证号
            $user['sex']=$data->sheets[0]['cells'][$i][4];//性别
            $user['sex'] = iconv("GB2312", "UTF-8",$user['sex']);
            switch ($user['sex']) {
                case '男':
                    $user['sex']=1;
                    break;
                case '女':
                    $user['sex']=0;
                    break;
                default:
                    $user['sex']=2;
                    break;
            }
            $user['phone']=$data->sheets[0]['cells'][$i][5];//手机号
            $user['build']=$data->sheets[0]['cells'][$i][6];//楼栋名
            $user['build'] = iconv("GB2312", "UTF-8",$user['build']);
            $user['unit']=$data->sheets[0]['cells'][$i][7];//单元名
            $user['unit'] = iconv("GB2312", "UTF-8",$user['unit']);
            $user['floor']=$data->sheets[0]['cells'][$i][8];//楼层号
            $user['floor'] = iconv("GB2312", "UTF-8",$user['floor']);
            $user['house']=$data->sheets[0]['cells'][$i][9];//房号
            $user['addtime']=time();
            $bui['alias_name']=$user['build'];
            $bui['communityid']=$_SESSION['communityid'];;
            $building = M("building")->where($bui)->select();
            for ($i1=0; $building[$i1]!="" ; $i1++) { 
                $unit['buildingid']=$building[$i1]['id'];
                $unit['uname']=$user['unit'];
                $units = M("unit")->where($unit)->select();
                for ($i2=0;$units[$i2]!=""; $i2++) { 
                    $floor['unitid']=$units[$i2]['id'];
                    $floor['fname']=$user['floor'];
                    $flo = M("floor")->where($floor)->select();
                    for ($i3=0;$flo[$i3]!=""; $i3++) { 
                        $house['floorid']=$flo[$i3]['id'];
                        $house['name']=$user['house'];
                        $hous = M("house")->where($house)->select();
                        $housrid=$hous[0]['id'];
                    }
                }
            }
            $user['house_id']=$housrid;
            $user['birthdate']=substr($user['id_card'],6,-4);
            $user['password']=substr($user['phone'],-6);
            $user['password']=encrypt_str($user['password']);//加密
            if($ids==$id){
                return 1;
            }
            $ids=$data->sheets[0]['cells'][$i][1];
            $res = M("users")->add($user);
        };
        if($res){
            return 1;
        }else{
            return 0;
        }
    }


    public function Excel_community_repair($da)
    {
        if(session('Excel_community_repair')!=""){
            $timesss=session('Excel_community_repair');
        };
        $Excel_read_user=time();
        if ($timesss>$Excel_read_user-5) {
            return 3;
        }
        session('Excel_read_user',$Excel_read_user);
        define('NUM_BIG_BLOCK_DEPOT_BLOCKS_POS', 0x2c);  
        define('SMALL_BLOCK_DEPOT_BLOCK_POS', 0x3c);  
        define('ROOT_START_BLOCK_POS', 0x30);  
        define('BIG_BLOCK_SIZE', 0x200);  
        define('SMALL_BLOCK_SIZE', 0x40);  
        define('EXTENSION_BLOCK_POS', 0x44);  
        define('NUM_EXTENSION_BLOCK_POS', 0x48);  
        define('PROPERTY_STORAGE_BLOCK_SIZE', 0x80);  
        define('BIG_BLOCK_DEPOT_BLOCKS_POS', 0x4c);  
        define('SMALL_BLOCK_THRESHOLD', 0x1000);  
        define('SIZE_OF_NAME_POS', 0x40);  
        define('TYPE_POS', 0x42);  
        define('START_BLOCK_POS', 0x74);  
        define('SIZE_POS', 0x78);  
        define('IDENTIFIER_OLE', pack("CCCCCCCC", 0xd0, 0xcf, 0x11, 0xe0, 0xa1, 0xb1, 0x1a, 0xe1));  
        define('SPREADSHEET_EXCEL_READER_BIFF8', 0x600);  
        define('SPREADSHEET_EXCEL_READER_BIFF7', 0x500);  
        define('SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS', 0x5);  
        define('SPREADSHEET_EXCEL_READER_WORKSHEET', 0x10);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOF', 0x809);  
        define('SPREADSHEET_EXCEL_READER_TYPE_EOF', 0x0a);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET', 0x85);  
        define('SPREADSHEET_EXCEL_READER_TYPE_DIMENSION', 0x200);  
        define('SPREADSHEET_EXCEL_READER_TYPE_ROW', 0x208);  
        define('SPREADSHEET_EXCEL_READER_TYPE_DBCELL', 0xd7);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FILEPASS', 0x2f);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NOTE', 0x1c);  
        define('SPREADSHEET_EXCEL_READER_TYPE_TXO', 0x1b6);  
        define('SPREADSHEET_EXCEL_READER_TYPE_RK', 0x7e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_RK2', 0x27e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MULRK', 0xbd);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MULBLANK', 0xbe);  
        define('SPREADSHEET_EXCEL_READER_TYPE_INDEX', 0x20b);  
        define('SPREADSHEET_EXCEL_READER_TYPE_SST', 0xfc);  
        define('SPREADSHEET_EXCEL_READER_TYPE_EXTSST', 0xff);  
        define('SPREADSHEET_EXCEL_READER_TYPE_CONTINUE', 0x3c);  
        define('SPREADSHEET_EXCEL_READER_TYPE_LABEL', 0x204);  
        define('SPREADSHEET_EXCEL_READER_TYPE_LABELSST', 0xfd);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NUMBER', 0x203);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NAME', 0x18);  
        define('SPREADSHEET_EXCEL_READER_TYPE_ARRAY', 0x221);  
        define('SPREADSHEET_EXCEL_READER_TYPE_STRING', 0x207);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA', 0x406);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA2', 0x6);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMAT', 0x41e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_XF', 0xe0);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOOLERR', 0x205);  
        define('SPREADSHEET_EXCEL_READER_TYPE_UNKNOWN', 0xffff);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR', 0x22);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS', 0xE5);  
        define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS', 25569);  
        define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1904', 24107);  
        define('SPREADSHEET_EXCEL_READER_MSINADAY', 86400);  
        define('SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT', "%s");    
        $data =new \Community\Common\ExcelReader();  
        $data->ExcelReader();
        $data->setOutputEncoding('CP936');  
        $data->read('./uploads/'.$da);  
        error_reporting(E_ALL ^ E_NOTICE);  
        for ($i=4;$data->sheets[0]['cells'][$i][1] ; $i++) { 
            if ($data->sheets[0]['cells'][$i][1]=="") {
                break;
            }
            $id=$data->sheets[0]['cells'][$i][1];
            $user['name']=$data->sheets[0]['cells'][$i][2];//姓名
            $user['phone']=$data->sheets[0]['cells'][$i][4];//手机号
            $user['sex']=$data->sheets[0]['cells'][$i][3];//性别
            switch ($user['sex']) {
                case '男':
                    $user['sex']=1;
                    break;
                case '女':
                    $user['sex']=0;
                    break;
                default:
                    $user['sex']=2;
                    break;
            }
            $user['birthday']=$data->sheets[0]['cells'][$i][6];//在职状态
            $user['isshow']=$data->sheets[0]['cells'][$i][6];//在职状态
            $user['status']=$data->sheets[0]['cells'][$i][7];//审核状态
            $user['sequestration']=$data->sheets[0]['cells'][$i][8];//账号状态
            $user['addtime']=time();
            $user['password']=substr($user['phone'],-6);
            $user['password']=encrypt_str($user['password']);//加密
            $user['communityid']=$_SESSION['communityid'];
            switch ($user['isshow']) {
                case '不启用':
                    $user['isshow']=0;
                    break;
                
                default:
                    $user['isshow']=1;
                    break;
            }
            switch ($user['status']) {
                case '初审通过':
                    $user['status']=1;
                    break;
                case '复审通过':
                    $user['status']=2;
                    break;
                default:
                    $user['status']=0;
                    break;
            }
            switch ($user['sequestration']) {
                case '封号':
                    $user['sequestration']=0;
                    break;
                
                default:
                    $user['sequestration']=1;
                    break;
            }
            $res = M("community_repair")->add($user);
        };
        if($res){
            return 1;
        }else{
            return 0;
        }
    }

 public function Excelx_repair_user($da)
    {
        if(session('Excel_community_repair')!=""){
            $timesss=session('Excel_community_repair');
        };
        $Excel_read_user=time();
        if ($timesss>$Excel_read_user-5) {
            return 3;
        }
        session('Excel_read_user',$Excel_read_user);
        define('NUM_BIG_BLOCK_DEPOT_BLOCKS_POS', 0x2c);  
        define('SMALL_BLOCK_DEPOT_BLOCK_POS', 0x3c);  
        define('ROOT_START_BLOCK_POS', 0x30);  
        define('BIG_BLOCK_SIZE', 0x200);  
        define('SMALL_BLOCK_SIZE', 0x40);  
        define('EXTENSION_BLOCK_POS', 0x44);  
        define('NUM_EXTENSION_BLOCK_POS', 0x48);  
        define('PROPERTY_STORAGE_BLOCK_SIZE', 0x80);  
        define('BIG_BLOCK_DEPOT_BLOCKS_POS', 0x4c);  
        define('SMALL_BLOCK_THRESHOLD', 0x1000);  
        define('SIZE_OF_NAME_POS', 0x40);  
        define('TYPE_POS', 0x42);  
        define('START_BLOCK_POS', 0x74);  
        define('SIZE_POS', 0x78);  
        define('IDENTIFIER_OLE', pack("CCCCCCCC", 0xd0, 0xcf, 0x11, 0xe0, 0xa1, 0xb1, 0x1a, 0xe1));  
        define('SPREADSHEET_EXCEL_READER_BIFF8', 0x600);  
        define('SPREADSHEET_EXCEL_READER_BIFF7', 0x500);  
        define('SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS', 0x5);  
        define('SPREADSHEET_EXCEL_READER_WORKSHEET', 0x10);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOF', 0x809);  
        define('SPREADSHEET_EXCEL_READER_TYPE_EOF', 0x0a);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET', 0x85);  
        define('SPREADSHEET_EXCEL_READER_TYPE_DIMENSION', 0x200);  
        define('SPREADSHEET_EXCEL_READER_TYPE_ROW', 0x208);  
        define('SPREADSHEET_EXCEL_READER_TYPE_DBCELL', 0xd7);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FILEPASS', 0x2f);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NOTE', 0x1c);  
        define('SPREADSHEET_EXCEL_READER_TYPE_TXO', 0x1b6);  
        define('SPREADSHEET_EXCEL_READER_TYPE_RK', 0x7e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_RK2', 0x27e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MULRK', 0xbd);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MULBLANK', 0xbe);  
        define('SPREADSHEET_EXCEL_READER_TYPE_INDEX', 0x20b);  
        define('SPREADSHEET_EXCEL_READER_TYPE_SST', 0xfc);  
        define('SPREADSHEET_EXCEL_READER_TYPE_EXTSST', 0xff);  
        define('SPREADSHEET_EXCEL_READER_TYPE_CONTINUE', 0x3c);  
        define('SPREADSHEET_EXCEL_READER_TYPE_LABEL', 0x204);  
        define('SPREADSHEET_EXCEL_READER_TYPE_LABELSST', 0xfd);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NUMBER', 0x203);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NAME', 0x18);  
        define('SPREADSHEET_EXCEL_READER_TYPE_ARRAY', 0x221);  
        define('SPREADSHEET_EXCEL_READER_TYPE_STRING', 0x207);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA', 0x406);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA2', 0x6);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMAT', 0x41e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_XF', 0xe0);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOOLERR', 0x205);  
        define('SPREADSHEET_EXCEL_READER_TYPE_UNKNOWN', 0xffff);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR', 0x22);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS', 0xE5);  
        define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS', 25569);  
        define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1904', 24107);  
        define('SPREADSHEET_EXCEL_READER_MSINADAY', 86400);  
        define('SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT', "%s");    
        $data =new \Community\Common\ExcelReader();  
        $data->ExcelReader();
        $data->setOutputEncoding('CP936');  
        $data->read('./uploads/'.$da);  
        error_reporting(E_ALL ^ E_NOTICE);  
        for ($i=4;$data->sheets[0]['cells'][$i][1] ; $i++) { 
            if ($data->sheets[0]['cells'][$i][1]=="") {
                break;
            }
            $user['job_number']=$data->sheets[0]['cells'][$i][1];
            $user['job_number'] = iconv("GB2312", "UTF-8",$user['job_number']);
            $user['name']=$data->sheets[0]['cells'][$i][2];//姓名
            $user['phone']=$data->sheets[0]['cells'][$i][4];//手机号
            $user['sex']=$data->sheets[0]['cells'][$i][3];//性别
            switch ($user['sex']) {
                case '男':
                    $user['sex']=1;
                    break;
                case '女':
                    $user['sex']=0;
                    break;
                default:
                    $user['sex']=2;
                    break;
            }
            $user['birthday']=$data->sheets[0]['cells'][$i][6];//出生日期
            $user['isshow']=$data->sheets[0]['cells'][$i][6];//在职状态
            $user['status']=$data->sheets[0]['cells'][$i][7];//审核状态
            $user['sequestration']=$data->sheets[0]['cells'][$i][8];//账号状态
            $user['addtime']=time();
            $user['password']=substr($user['phone'],-6);
            $user['password']=encrypt_str($user['password']);//加密
            $user['communityid']=$_SESSION['communityid'];
            switch ($user['isshow']) {
                case '不启用':
                    $user['isshow']=0;
                    break;
                
                default:
                    $user['isshow']=1;
                    break;
            }
            switch ($user['status']) {
                case '初审通过':
                    $user['status']=1;
                    break;
                case '复审通过':
                    $user['status']=2;
                    break;
                default:
                    $user['status']=0;
                    break;
            }
            switch ($user['sequestration']) {
                case '封号':
                    $user['sequestration']=0;
                    break;
                
                default:
                    $user['sequestration']=1;
                    break;
            }
            $res = M("repair_user")->add($user);
        };
        if($res){
            return 1;
        }else{
            return 0;
        }
    }

    public function Excel_read_pay($da)
    {
        define('NUM_BIG_BLOCK_DEPOT_BLOCKS_POS', 0x2c);  
        define('SMALL_BLOCK_DEPOT_BLOCK_POS', 0x3c);  
        define('ROOT_START_BLOCK_POS', 0x30);  
        define('BIG_BLOCK_SIZE', 0x200);  
        define('SMALL_BLOCK_SIZE', 0x40);  
        define('EXTENSION_BLOCK_POS', 0x44);  
        define('NUM_EXTENSION_BLOCK_POS', 0x48);  
        define('PROPERTY_STORAGE_BLOCK_SIZE', 0x80);  
        define('BIG_BLOCK_DEPOT_BLOCKS_POS', 0x4c);  
        define('SMALL_BLOCK_THRESHOLD', 0x1000);  
        define('SIZE_OF_NAME_POS', 0x40);  
        define('TYPE_POS', 0x42);  
        define('START_BLOCK_POS', 0x74);  
        define('SIZE_POS', 0x78);  
        define('IDENTIFIER_OLE', pack("CCCCCCCC", 0xd0, 0xcf, 0x11, 0xe0, 0xa1, 0xb1, 0x1a, 0xe1));  
        define('SPREADSHEET_EXCEL_READER_BIFF8', 0x600);  
        define('SPREADSHEET_EXCEL_READER_BIFF7', 0x500);  
        define('SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS', 0x5);  
        define('SPREADSHEET_EXCEL_READER_WORKSHEET', 0x10);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOF', 0x809);  
        define('SPREADSHEET_EXCEL_READER_TYPE_EOF', 0x0a);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET', 0x85);  
        define('SPREADSHEET_EXCEL_READER_TYPE_DIMENSION', 0x200);  
        define('SPREADSHEET_EXCEL_READER_TYPE_ROW', 0x208);  
        define('SPREADSHEET_EXCEL_READER_TYPE_DBCELL', 0xd7);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FILEPASS', 0x2f);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NOTE', 0x1c);  
        define('SPREADSHEET_EXCEL_READER_TYPE_TXO', 0x1b6);  
        define('SPREADSHEET_EXCEL_READER_TYPE_RK', 0x7e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_RK2', 0x27e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MULRK', 0xbd);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MULBLANK', 0xbe);  
        define('SPREADSHEET_EXCEL_READER_TYPE_INDEX', 0x20b);  
        define('SPREADSHEET_EXCEL_READER_TYPE_SST', 0xfc);  
        define('SPREADSHEET_EXCEL_READER_TYPE_EXTSST', 0xff);  
        define('SPREADSHEET_EXCEL_READER_TYPE_CONTINUE', 0x3c);  
        define('SPREADSHEET_EXCEL_READER_TYPE_LABEL', 0x204);  
        define('SPREADSHEET_EXCEL_READER_TYPE_LABELSST', 0xfd);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NUMBER', 0x203);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NAME', 0x18);  
        define('SPREADSHEET_EXCEL_READER_TYPE_ARRAY', 0x221);  
        define('SPREADSHEET_EXCEL_READER_TYPE_STRING', 0x207);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA', 0x406);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA2', 0x6);  
        define('SPREADSHEET_EXCEL_READER_TYPE_FORMAT', 0x41e);  
        define('SPREADSHEET_EXCEL_READER_TYPE_XF', 0xe0);  
        define('SPREADSHEET_EXCEL_READER_TYPE_BOOLERR', 0x205);  
        define('SPREADSHEET_EXCEL_READER_TYPE_UNKNOWN', 0xffff);  
        define('SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR', 0x22);  
        define('SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS', 0xE5);  
        define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS', 25569);  
        define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1904', 24107);  
        define('SPREADSHEET_EXCEL_READER_MSINADAY', 86400);  
        define('SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT', "%s");    
        $data =new \Community\Common\ExcelReader();  
        $data->ExcelReader();
        $data->setOutputEncoding('CP936');  
        $data->read('./uploads/'.$da);  
        error_reporting(E_ALL ^ E_NOTICE);  
        for ($i=4;$data->sheets[0]['cells'][$i][1] ; $i++) { 
            $ids[$i-4]=$data->sheets[0]['cells'][$i][1];//编号
            $money[$i-4]['shui']=$data->sheets[0]['cells'][$i][6];//水费
            $money[$i-4]['dian']=$data->sheets[0]['cells'][$i][8];//电费
            $money[$i-4]['qi']=$data->sheets[0]['cells'][$i][10];//气费
            $number[$i-4]['shui']=$data->sheets[0]['cells'][$i][7];//水费编号
            $number[$i-4]['dian']=$data->sheets[0]['cells'][$i][9];//电费编号
            $number[$i-4]['qi']=$data->sheets[0]['cells'][$i][11];//气费编号           
        }
        $Aa=$this->found_need_send(0,$ids,$money,$number,1);
        if($Aa==1){
            return 1;
        }else{
            return 0;
        }
    }





    function post ( $url ,  $param = array ()){
        if (! is_array ( $param )){
            throw   new   Exception ( "参数必须为array" );
        }
        $httph  = curl_init ( $url );
        curl_setopt ( $httph ,  CURLOPT_SSL_VERIFYPEER ,   0 );
        curl_setopt ( $httph ,  CURLOPT_SSL_VERIFYHOST ,   1 );
        curl_setopt ( $httph , CURLOPT_RETURNTRANSFER , 1 );
        curl_setopt ( $httph ,  CURLOPT_USERAGENT ,   "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)" );
        curl_setopt ( $httph ,  CURLOPT_POST ,   1 ); //设置为POST方式 
        curl_setopt ( $httph ,  CURLOPT_POSTFIELDS ,  $param );
        curl_setopt ( $httph ,  CURLOPT_RETURNTRANSFER , 1 );
        curl_setopt ( $httph ,  CURLOPT_HEADER , 1 );
        $rst = curl_exec ( $httph );
        curl_close ( $httph );
        return  $rst ;
    }
function characet($data){
  if( !empty($data) ){
    $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;
    if( $fileType != 'UTF-8'){
      $data = mb_convert_encoding($data ,'utf-8' , $fileType);
    }
  }
  return $data;
}
    function GetInt4d($data, $pos)  
    {  
        $value = ord($data[$pos]) | (ord($data[$pos + 1]) << 8) | (ord($data[$pos + 2]) << 16) | (ord($data[$pos + 3]) << 24);  
        if ($value >= 4294967294) {  
            $value = -2;  
        }  
        return $value;  
    }  
    public function _nav($permissions,$type)
    {
        $mod = M('Navigations');
        $map = array();
        $map['status'] = 1;
        $map['type'] = $type;
        $qx = $permissions; 
        if ($qx) {
            if ($qx != 'all') {
                $map['id'] = array('in',$permissions);
            }
            // $map['group'] = MODULE_NAME;
            $list = $mod->where($map)->order('sort asc,id asc')->select();
            // p(M('Navigations')->getlastSql());
            $list_new = array();
            //为了保证顺序,优先设置顶级分类
            foreach ($list as $key => $value) {
                $id = $value['id'];
                $pid = $value['pid'];
                $toppid = $value['toppid'];
                //顶级分类
                if ($pid == 0 && $toppid == 0) {
                    //设置info
                    $list_new[$id]['info'] = $value;
                    //可以移除了,貌似不会影响结果
                    unset($list[$key]);
                }
            }
            //重新组装list
            foreach ($list as $key => $value) {
                $id = $value['id'];
                $pid = $value['pid'];
                $toppid = $value['toppid'];
                $ischild = $value['ischild'];
                $key_new = '';
                //顶级分类
                if ($pid == 0 && $toppid == 0) {
                    //设置info
                    //$list_new[$id]['info'] = $value;
                }
                //二级分类
                else if ($pid == $toppid) {
                    //本级信息
                    $list_new[$toppid]['childs'][$id]['info'] = $value;

                    // $list_new[$toppid]['childs'][$id] = $value;
                }
                else
                {
                    $list_new[$toppid]['childs'][$pid]['childs'][$id]['info'] = $value;
                }
            } 
            // p($list_new);
            $this->assign('list',$list_new);
        }
    }
//ajax_delete
    //验证权限
    public function _check_qx($user,$arr = array(),$type)
    {
        $temp = $this->_name.":".$this->_method;
        //验证用户权限信息
        if (!in_array($temp, $arr)) {
            //用户类型
            $usertype = $type;
            $group = MODULE_NAME;
            $controller = $this->_name;
            $method = $this->_method;
            $interrelated_g_c_m = $group.'_'.$controller.'_'.$method;
            //权限验证 分组，控制器，方法
            $key = I('get.key');
            $strwhere = " (`group` = '$group' and controller = '$controller' and method = '$method' ) or interrelated_g_c_m like '%$interrelated_g_c_m%' ";
            if ($key)
            {
                $strwhere = "( `key` = '$key' and  (`group` = '$group' and controller = '$controller' and method = '$method' )) or interrelated_g_c_m like '%$interrelated_g_c_m%' ";
            }
            $strwhere .= " and type = $usertype"; 
            //获取包含 "当前路径" 的栏目
            $navlist = M('Navigations')->field('id')->where($strwhere)->select(); 
            $qx = $user['permissions'];
            if ($qx != 'all') {
                $arrqx = explode(',',$qx);
                $b = false;
                foreach ($navlist as $key => $nav) {
                    if (in_array($nav['id'],$arrqx)) {
                        $b = true;
                    }
                }
                if (!$b) {
                    if (IS_AJAX) {
                       $this->ajaxReturn(0,"访问出错！");
                    }
                    $this->show('<font color="" size="5">访问出错！</font>','utf-8');
                    die();
                }
            }
        }
    }

    public function ajax_delete()
    {
        if (IS_AJAX) {
            $ids = I('post.id');
            if (empty($ids)) {
                $this->ajaxReturn(0,'非法操作');
            }
            $ids = trim($ids,',');
            $map = array();
            if (method_exists($this, '_before_delete')) {
                $map = $this->_before_delete($ids);
            }
            //加上id条件
            $map['id'] = array('in',$ids);

            $res = M($this->_name)->where($map)->delete();
            if ($res) {
                $this->ajaxReturn(1,'操作成功！');
            }
            $this->ajaxReturn(0,'操作失败！');
        }
    }

    public function ajax_sends()
    {
        if (IS_AJAX) {
            $ids = I('post.id');
            if (empty($ids)) {
                $this->ajaxReturn(0,'非法操作');
            }
            $content="缴费通知";
            $title="点击查看详情";
            $url="";//未知
            $ids = trim($ids,',');
            //发送接口
            for ($i=0;$ids[$i]; $i++) { 
                $ids[$i]="user".$ids[$i];
                $res=$this->Pushs2($content,$title,$url,$ids[$i]);
            }
            if ($res) {
                $this->ajaxReturn(1,'发送成功！');
            }else{
                $this->ajaxReturn(0,'发送失败！');
            }
        }else{
            $ids = I('get.id');
            $content="缴费通知";
            $title="点击查看详情";
            $url="";//未知
            //发送接口
            $ids="user".$ids;
            $res=$this->Pushs2($content,$title,$url,$ids);
            if ($res) {
                $this->ajaxReturn(1,'发送成功！');
            }else{
                $this->ajaxReturn(0,'发送失败！');
            }
        }
    }

    public function found_need_send($num=0,$ids=array(),$money=array(),$number=array(),$ifs=0)
    {
        $numbers=0;
        if (IS_AJAX) {
            $num = I('post.num');
            $numbers=1;
            if($num==1||$num==2||$num==3){
                $ids = I('post.id');
                $money = I('post.money');
                $number = I('post.number');
                $ids = trim($ids,',');
                $money = trim($money,',');
                $number = trim($number,',');
            }
        }
        $id = $num;
        $j=0;
        $map=array();
        $map['type']=$id;
        $map['confirm']=0;
        $map['communityid']=1;
        $res1 = M("needsend")->where($map)->select();
        //处理time和现在时间
        $time=time();
        $time=date("d",$time);
        $new_time=$res1[0]['time'];
        if ($new_time==$time||$ifs==1) {
            $hou['communityid']=1;
            if ($num==4) 
            {
                $all = M("")->query("SELECT us.id,bd.unit_price FROM `xly_users` as us left join xly_house as hs on hs.id = us.house_id left join xly_floor as fl on fl.id = hs.floorid left join xly_unit as un on un.id = fl.unitid left join xly_building as bd on bd.id = un.buildingid AND us.status = 1 WHERE bd.id != '' AND un.id != '' AND fl.id != '' ORDER BY id asc");
                for ($i_all=0;$all[$i_all]['id']; $i_all++) { 
                    $userid[$i_all]['userid']=$all[$i_all]['id'];
                    $userid[$i_all]['money']=$all[$i_all]['unit_price'];
                }
            }elseif ($num==5) {
                $all = M("")->query("SELECT us.id,park.manage_fees FROM `xly_parking` as park left join xly_users as us on us.id = park.userid WHERE park.paystyle = 0 AND park.userid != '' AND park.isdo = 1 ORDER BY id ");
                for ($i_all=0;$all[$i_all]['id']; $i_all++) { 
                    $userid[$i_all]['userid']=$all[$i_all]['id'];
                    $userid[$i_all]['money']=$all[$i_all]['manage_fees'];
                }
            }
            if ($numbers==1&&$num!=4&&$num!=5) {
                $data['type']=$num;
                if($num==1||$num==2||$num==3)
                {
                    for ($pay=0;$userid[$pay]; $pay++) { 
                        $data['userid']=$ids[$pay];
                        $data['money']=$money[$pay];
                        $data['number']=$number[$pay];
                        //$data['addtime']=time();
                        $res = M("payfees")->add($data);
                    }
                }
            }
            if($num==4||$num==5){
                $data['type']=$num;
                for ($pay=0;$userid[$pay]['money']; $pay++) { 
                    $data['userid']=$userid[$pay]['userid'];
                    $data['money']=$userid[$pay]['money'];
                    $data['number']="2017".$pay."00";
                    //$data['addtime']=time();
                    $res = M("payfees")->add($data);
                }
                $data2['confirm']=1;
                $res1 = M("needsend")->where($map) -> save($data2);

            }else{
                for ($pay=0;$ids[$pay]; $pay++) {
                    $resss1['house_id']=$ids[$pay];
                    $resss1['status']=1;
                    $resss = M("users")->where($resss1)->select();;
                    if ($money[$pay]['shui']!="") {
                        $data['userid']=$resss[0]['id'];
                        $data['money']=$money[$pay]['shui'];
                        $data['type']=1;
                        $data['number']=$number[$pay]['shui'];
                        //$data['addtime']=time();
                        $res = M("payfees")->add($data);                  
                    }

                    if ($money[$pay]['dian']!="") {
                        $data['userid']=$resss[0]['id'];
                        $data['money']=$money[$pay]['dian'];
                        $data['type']=2;
                        $data['number']=$number[$pay]['dian'];
                        //$data['addtime']=time();
                        $res = M("payfees")->add($data);
                    }
                    if ($money[$pay]['qi']!="") {
                        $data['userid']=$resss[0]['id'];
                        $data['money']=$money[$pay]['qi'];
                        $data['type']=3;
                        $data['number']=$number[$pay]['qi'];
                        //$data['addtime']=time();
                        $res = M("payfees")->add($data);
                    }

                }     
            }
        }
        if ($res) {
            return 1;
        }
        return 0;
    }
    //自动生成Excel
function downloadXls($str){ 
    $filename='楼栋表(可调整高宽2016版本有提示).xls';
    $filename = !empty($filename) ? $filename : die('nothing'); 

    //header 的作用是 新建一个被下载的test.xls 
    header("Content-Type: application/vnd.ms-excel; charset=utf8"); 
    header("Content-Disposition: attachment; filename=$filename"); 

    //这里需要被输出的内容直接输出到test.xls文件中 
    echo $str;
} 
    //删除一定数据
    public function delete_allthing()
    {
        if (IS_AJAX) {
            $time = I('post.time');
            //时间
            $res = M($this->_name)->where($map)->delete();
            if ($res) {
                $this->ajaxReturn(1,'操作成功！');
            }
            $this->ajaxReturn(0,'操作失败！');
        }
    }
//未交费通知
    public function ajax_all_sends()
    {
        //发送接口
        $map['paytime']="";
        $ress = M('payfees')->where($map)->select();
        $content="缴费通知";
        $title="点击查看详情";
        $url="";//未知
        //发送接口
        for ($i=0;$ress[$i]; $i++) { 
            $ids="user".$ress[$i]['userid'];
            $res=$this->Pushs2($content,$title,$url,$ids);
        }
        if ($res) {
            $this->ajaxReturn(1,'发送成功！');
        }
        $this->ajaxReturn(0,'发送失败！');
    }

    public function ajax_gai()
    {
        if (IS_AJAX) {
            $ids = I('post.id');
            $num = I('post.value');
            $name = I('post.name');
            $data["$name"]=$num;
            if (empty($ids)) {
                $this->ajaxReturn(0,'非法操作');
            }
            $ids = trim($ids,',');
            $map = array();
            //加上id条件
            $map['id'] = array('in',$ids);

            $res = M($this->_name)->where($map)->save($data);
            if ($res) {
                $this->ajaxReturn(1,'操作成功！');
            }
            $this->ajaxReturn(0,'操作失败！');
        }
    }

    /* 
    * 修改指定条件下指定数据
    * $map 修改条件
    * $data 修改数据
    */
    public function _update($modName,$data,$map)
    {
        $mod = D($modName);
        if (false === $tempdata = $mod->create($data)) {
            $resArr['status'] = 0;
            $resArr['data'] = $mod->getError();
            //返回相关状态信息
            return $resArr;
        } 
        $count = $mod->where($map)->save($tempdata);
        if (empty($count)) {
            $resArr['status'] = 0;
            $resArr['data'] = "操作失败！";
            return $resArr;
        }
        $resArr['status'] = 1;
        $resArr['data'] = $count;
        return $resArr;
        //echo M()->getlastSql();
    }

    //返回类型的toppid
    public function getTypeTopPid($typeid,$typeModName)
    {
        if ($typeid == "0") {
            return 0;
        }
        //查询pid
        $map = array();
        $map['id'] = $typeid;
        $type = M($typeModName)->field("id,toppid")->where($map)->find();
        $toppid = $type['toppid'];
        //为o则代表为自己
        if ($toppid == 0) {
            $toppid = $type['id'];
        }
        return $toppid;
    }


    /**
     * 列表页面(一般用作)
     */
    public function index() { 
        $templet = "";
        if(method_exists($this, '_before_index')){
           $templet = $this->_before_index();
        }
        if (empty($templet)) {
            $this->display();
        }
        else
        {
            $this->display($templet);
        }
    }

    /*添加和修改时验证名称时候重复
    * $name 要验证的名称值
    * $id 验证名称时需排出的id值(用于修改时验证)
    * $namefiledname 名称字段名((默认为name))
    * $map 其他条件
    * $modelname 控制器名称
    *
    * 返回值:true_存在重名,false_不存在重名
    */
    public function _check_name($name,$id = '',$namefiledname = "name",$map=array(),$modelname='')
    {
        //验证名称重复(当前角色)
        $map[$namefiledname] = $name;
        if ($id) {
            //排除自己
            $map['id'] = array('neq',$id);
        }
   
        if(empty($modelname)){
            $modelname = $this->_name;
        }
        $info = M($modelname)->where($map)->find();  
        if (empty($info)) {
            return false;
        }
        return true;
    }

    public function add1()
    {
        if(method_exists($this, '_before_add1')){
           $this->_before_add1();
        }
        $this->display();
    }

    public function _getType()
    {
        $list = $this->_get_type();
        $data = array();
        foreach ($list as $key => $value) {
            $str = $value;
            $index = stripos($str,"_");
            $id =  substr($str,0,$index);
            $text =  substr($str,$index + 1,strlen($str));
            $data[$key]['id'] = $id;
            $data[$key]['name'] = $text;
        }
        return $data;
    }

    //返回有层级的类型项
    public function _get_type($data = array(), $pid=0, &$result=array(), $deep = 0) {
        if ($pid == 0) {
            $mod_name = $this->_type_name;
            if (empty($mod_name)) {
                $mod_name = $this->_name;
            }
            $data = M($mod_name)->order('sort asc,id desc')->select();
        }
        $deep += 1;
        foreach ( $data as $key => $val ) {
            if ( $pid == $val['pid'] ) {
                $result[] = $val['id']."_┝".str_repeat("☞", $deep - 1).$val['name'];
                $this->_get_type( $data, $val['id'],  $result, $deep );
            }
        }
        return $result;
    }


    //递归查询类型
    public function _getTypeList($pid = 0){ 
        $map['pid'] = $pid;
        $mod_name = $this->_type_name;
        if (empty($mod_name)) {
            $mod_name = $this->_name;
        }
        $list = M($mod_name)->where($map)->order('sort asc,id desc')->select();
        $arr = array(); 
        if($list && count($list)){//如果有子类 
            foreach ($list as $key => $item) {
                $item['children'] = $this->_getTypeList($item['id']); //调用函数，传入参数，继续查询下级 
                $arr[] = $item; //组合数组 
            } 
            return $arr; 
        } 
    }

    /**
     * 添加
     */
    public function add() {
        $mod = D($this->_name);
        if (IS_AJAX || IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_insert')) {
                $data = $this->_before_insert($data);
            }
            if ($mod->add($data)) {
                if (method_exists($this, '_after_insert')) {
                    $id = $mod->getLastInsID();
                    $this->_after_insert($id);
                }
                IS_AJAX && $this->ajaxReturn(1, '操作成功！' , '', 'add');
                $this->success('操作成功！');
            } else {
                IS_AJAX && $this->ajaxReturn(0, '操作失败！');
                $this->error('操作失败！');
            }
        } else {
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            } else {
                if( method_exists($this, '_before_add')){
                   $this->_before_add();
                }
                $this->display();
            }
        }
    }

    /**
    * 修改
    */
    public function edit() {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST)
        { 
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }
            if (false !== $mod->save($data)) {
                if (method_exists($this, '_after_update')) {
                    $id = $data['id'];
                    $this->_after_update($id);
                }
                IS_AJAX && $this->ajaxReturn(1, '操作成功！' , '', 'edit');
                $this->success('操作成功！');
            } else {
                IS_AJAX && $this->ajaxReturn(0,'操作失败！');
                $this->error('操作失败！');
            }
        } 
        else 
        {
            $id =I('get.'.$pk);// $this->_get($pk, 'intval');
            if (!$id) {
                $id = 1;
            }
            $info = $mod->find($id);
            if (empty($info)) {
                $this->show('你查询的数据不存在！','utf-8');
                die();
            } 
            //为编辑时有其他的表关联数据而打造
            if(method_exists($this, '_before_edit_data'))
            {
               $info = $this->_before_edit_data($info);
            }
            $this->assign('info', $info);
            if (IS_AJAX)
            {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            }
            else
            {
                if(method_exists($this, '_before_edit')){
                   $templet = $this->_before_edit();
                }
                if (empty($templet)) {
                    $this->display();
                }
                else
                {
                    $this->display($templet);
                }
            }
        }
    }

    /**
    * 详情查看
    */
    public function detail() {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST)
        { 
            //
        } 
        else 
        {
            $id =I('get.'.$pk);// $this->_get($pk, 'intval');
            if (!$id) {
                $id = 1;
            }
            $info = $mod->find($id);
            if (empty($info)) {
                $this->show('你查询的数据不存在！','utf-8');
                die();
            } 
            //为编辑时有其他的表关联数据而打造
            if(method_exists($this, '_before_detail_data'))
            {
               $info = $this->_before_detail_data($info);
            }
            $this->assign('info', $info);
            if (IS_AJAX)
            {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            }
            else
            {
                if(method_exists($this, '_before_detail')){
                   $templet = $this->_before_detail();
                }
                if (empty($templet)) {
                    $this->display();
                }
                else
                {
                    $this->display($templet);
                }
            }
        }
    }

    public function del_file() {
        $file_list = $_REQUEST['sid'];
        $this->_destory_file($file_list);
    }

    protected function _destory_file($file_list) {
        if (isset($file_list)) {
            if (is_array($file_list)) {
                $where["sid"] = array(
                    "in",
                    $file_list
                );
            }
            else {
                $where["sid"] = array(
                    'in',
                    array_filter(explode(',', $file_list))
                );
            }
        }
        $model = M("File");
        $where['module'] = MODULE_NAME;
        $admin = $this->config['auth']['admin'];
        if ($admin) {
            $where['user_id'] = array(
                'eq',
                get_user_id()
            );
        };
        $list = $model->where($where)->select();
        $save_path = get_save_path();
        foreach ($list as $file) {
            if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/" . $save_path . $file['savename'])) {
                unlink($_SERVER["DOCUMENT_ROOT"] . "/" . $save_path . $file['savename']);
            }
        }
        $result = $model->where($where)->delete();
        if ($result !== false) {
            return true;
        }
        else {
            return false;
        }
    }

    //修改制定字段
    public function ajax_field_edit()
    {
        if (IS_AJAX) {
            //id
            $ids = I('post.ids');
            $ids = trim($ids,','); 
            //修改的值
            $val = I('post.val');
            $map = array();
            $map['id'] = array('in',$ids);
            $fieldname = "";
            //设置field
            if (method_exists($this, '_before_field_edit')) {
                $arr = $this->_before_field_edit($data);
                $fieldname = $arr['fieldname'];
                if (empty($fieldname))
                {
                    $this->ajaxReturn(0,'操作失败1！');
                }
                $map = array_merge($map,$arr['map']);
                //值的范围，设计用逗号隔开
                if ($arr['range']!=232) {
                    $range = $arr['range'];
                    $arr_range = explode(',', $range); 
                    if (!in_array($val, $arr_range))
                    {
                        $this->ajaxReturn(0,"操作失败2！");
                    }
                }
            }
            $data = array();
            $data[$fieldname] = $val;
            $res = M($this->_name)->where($map)->save($data);
            if ($res) {
                $this->ajaxReturn(1,"操作成功！");
            }
            else
            {
                $this->ajaxReturn(0,"操作失败3！");
            }
        }        
    }
   
    public function ajax_field_edit_status()
    {
        if (IS_AJAX) {
            //id
            $name = I('post.name');
            $ids = I('post.ids');
            $ids = trim($ids,','); 
            //修改的值
            $val = I('post.val');
            $map = array();
            $map['id'] = array('in',$ids);
            //设置field
            $data = array();
            $data['status'] = $val;
            $res = M($this->_name)->where($map)->save($data);
            if ($res) {
                $this->ajaxReturn(1,"操作成功！");
            }
            else
            {
                $this->ajaxReturn(0,"操作失败3！");
            }
        }        
    }

    //修改单个字段
    public function ajax_field_edits()
    {
        if (IS_AJAX) {
            //if
            $if = I('post.ifs');
            $if = trim($if,','); 
            //id
            $ids = I('post.ids');
            $ids = trim($ids,','); 
            //修改的值
            $val = I('post.val');
            $map = array();
            $map['id'] = array('in',$ids);
            $fieldname = "";
            //设置field
            if (method_exists($this, '_before_field_edit')) {
                $arr = $this->_before_field_edit($data);
                $fieldname = $arr['fieldname'];
                if (empty($fieldname))
                {
                    $this->ajaxReturn(0,'操作失败1！');
                }
                $map = array_merge($map,$arr['map']);
                //值的范围，设计用逗号隔开
                $range = $arr['range'];
                $arr_range = explode(',', $range); 
                if (!in_array($val, $arr_range))
                {
                    $this->ajaxReturn(0,"操作失败2！");
                }
            }
            $data = array();
            $data[$if] = $val;
            $res = M($this->_name)->where("id='$ids'")->save($data);
            if ($res) {
                $this->ajaxReturn(1,"操作成功！");
            }
            else
            {
                $this->ajaxReturn(0,$ids.$if."操作失败3！".$val);
            }
        }        
    } 
    //ajax 返回列表
    public function ajax_getList()
    {
        if (IS_AJAX || true) {
            $pagesize = I('get.pageSize');//每页显示数量
            if (empty($pagesize)) {
                $pagesize = 10;
            }
            //排序名称
            $sortname = I('get.sortName');
            //排序方式 
            $sortorder = I('get.sortOrder');
            // session('get',I('get.'));
            $strOrder = "";
            if (!empty($sortname) && !empty($sortorder)) {
                //session('qw','dsdsdsds');
                $strOrder = $sortname." ".$sortorder;
            } 
            if (method_exists($this, '_before_getList')) {
                $data = $this->_before_getList();
            }
            $arrJoins = $data['arrJoins'];
            $map = $data['map'];
            $fields = empty($data['fields'])?"*":$data['fields'];
            $nopage = I('post.nopage');
            if ($nopage == 1) {
                $pagesize = "";
            }  
            $list = $this->_select($this->_name,$arrJoins,$map,$fields,$strOrder,$pagesize);
            if (method_exists($this, '_after_getList')) {
                $list = $this->_after_getList($list);
            }
            $list['rows'] = count($list['rows']) > 0 ? $list['rows'] : false;
            $this->ajaxReturn($list);
        }
    }

    //输出session
    public function psession()
    {
        $name = I('get.name');
        p(session($name));
    }
    
}

