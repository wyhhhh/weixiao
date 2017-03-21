<?php 
namespace Common\Common;
use Think\Controller; 
class BaseController extends Controller {

    public function _initialize() {
        parent::_initialize();
        header("Content-type: text/html;charset=utf-8");
        Load('extend');
        ////消除所有的magic_quotes_gpc转义
        //Input::noGPC();

        //控制器名称
        $this->_name = CONTROLLER_NAME;
        //方法名称
        $this->_method = ACTION_NAME;
        //表前缀
        $this->_qz = C('DB_PREFIX');
    }
    
    // 查询地址，新版的
    public function ajax_getAreaNew()
    {
        //查询地址
        if(!IS_AJAX) return;
        $key = I('post.key');
        $map = array();
        if (!empty($key)) {
            $map['name'] = array('like',"%$key%");
        }
        //排出省市---
        $list = M('AreaNew')->where($map)->limit('20')->select();
        session("testarea",$list);
        $this->ajaxReturn(1,$list,'--');
    }

    //ajax返回地址
    public function ajax_getArea()
    {
        $pcode = I('get.code');
        $type = I('get.type');//省_1,市_2,区_3---省可以省略
        $key = I('get.keyword');//搜索关键字
        if (IS_POST) {
            $pcode = I('post.code');
            $type = I('post.type');//省_1,市_2,区_3---省可以省略
            $key = I('post.keyword');//搜索关键字
        }
        $list = $this->_getArea($type,$pcode,$keyword);
        $this->ajaxReturn(1,$list);
    } 

    /*
    *生成维修人员工号 返回规定格式的随机号
    *@param  $num 传入需要的数量(默认6位数)  $prefix 前缀  $suffix 后缀 (所有参数若无可不传)
    *@return  返回规定格式的随机号
    */
    public function randomJobNumber($num,$prefix="",$suffix=""){
        if(empty($num)){
            $num = 6;//默认6位
        }
        $isNum = is_numeric($num);
        if(!$isNum){
            return "不是数字！";
        }
        $chars_1 = "0123456789";
        $random_str = "";

        $numberData = 1;
        while($numberData==1){
            $random_str = "";
            for ($i=0; $i < $num; $i++) { 
                $n =substr($chars_1,(mt_rand()%strlen($chars_1)),1);//单个随机号码生成
                $random_str .= $n."";
            }
            $random_str = $prefix.$random_str.$suffix;

            //查看是否已经存在这样的number了
            $xly_repair_user = M("RepairUser")->where("job_number='$random_str'")->find();
            if(empty($xly_repair_user)){
                $numberData = 0;
            }
        }

        return $random_str;
    }
    
    /*
    * 执行添加操作
    * $arr 相关数据
    * 数据实例
    * $data = array('name'=>"test","pwd"=>'testpwd');
    * $arr[] = array('modName'=>"Test","data"=>$data,'fk'=>'','fkindex'=>'0');
    *
    * $data = array('name'=>"test1","pwd"=>'test1pwd');
    * $arr[] = array('modName'=>"Test1","data"=>$data,'fk'=>'testid','fkindex'=>'0');
    *
    * $data = array('name'=>"test2","pwd"=>'test3pwd');
    * $arr[] = array('modName'=>"Test2","data"=>$data,'fk'=>array('testid','test2id'),'fkindex'=>array('0','1'));
    * fk 为外键字段名，fkindex 为外键在相关数据中的第几张表(从0开始，下标)的主键值，当存在多个外键时,以数组的形式传参数，fk 和 fkindex 的值，必须一一对应，且必须为多个时才使用数组，单个时不能使用数组
    * fkindex=>0 插入数据默认id为第一站表的id
    */
    public function _insert($arr)
    { 
        $resArr = array();
        //循环验证(creata)数据是否合法
        foreach ($arr as $key => $data) {   
            //获取mod名称实例化model
            $mod = D($data['modName']);
            $tempdata = array();
            //通过data create进行验证
            if (false === $tempdata = $mod->create($data['data'])) {
                $resArr['status'] = 0;
                $resArr['data'] = $mod->getError();
                //返回相关状态信息
                return $resArr;
            }
            else{
                //将数据设置为通过creata封装后的数据
                $arr[$key][$data] = $tempdata;
            }
        }
        $ids = '';
        $id = "";
        $arrid = array();
        //循环入库
        foreach ($arr as $key => $data) {
            //获取model名称并进行实例化
            $mod = M($data['modName']);
            //获取外键名称
            $fkname = $data['fk'];
            if (!empty($fkname) && !empty($id)) {
                $fkindex = $data['fkindex'];
                if ($fkindex != "") {
                    if (is_array($fkindex)) {
                        foreach ($fkindex as $key_index => $value) {
                            //设置外键
                            $data['data'][$fkname[$key_index]] = $arrid[$value];
                        }
                    }
                    else
                    {
                        //设置外键
                        $data['data'][$fkname] = $arrid[$fkindex];   
                    }
                }
                else
                {
                    //设置外键
                    $data['data'][$fkname] = $id;   
                }
            }
            $id = $mod -> add($data['data']);
            $arrid[$key] = $id;
            $ids .= $id.",";
        }
        $ids = trim($ids,",");
        $resArr['status'] = 1;
        $resArr['data'] = $ids;
        //返回相关状态信息
        return $resArr;
    }

    /*
    * 查询地址
    * $type 查询类型(省_1,市_2,区_3),当为省时可不传
    * $pcode 上级(省或市)的code，查询下级地区
    * $keyword 关键字，根据关键字查地区名称
    */
    public function _getArea($type = '',$pcode = '',$keyword = '')
    {
        $map = array();
        if (((empty($pcode) && empty($type)) || (empty($pcode) && $type == 1)) || (!empty($pcode) && ($type == 1 || $type == 2 || $type == 3))) {
            if (!empty($key)) {
                $map['name'] = array('like',$key);
            }
            if ($type == 1 || empty($type)) {
                $map['code'] = array('like',"%0000");   
            }
            else if($type == 2) {
                $pcode = substr($pcode,0,2);
                $map['left(code,CHAR_LENGTH(code)-4)'] = array('like',"$pcode%");
                $map['substring(code,3,6)'] = array('neq',"0000");
                $map['substring(code,5,6)'] = "00"; 
            }
            else if($type == 3) {
                //left(CODE,len(CODE)-2) like '%".$s."' and substring(CODE,5,7)!=00 and  substring(CODE,5,7)!=01
                $pcode = substr($pcode,0,4);
                $map['left(code,CHAR_LENGTH(code)-2)'] = array('like',"$pcode%");
                $map['substring(code,5,6)'] = array('neq',"00");
                $map['substring(code,5,7)'] = array('neq',"01");
            }
            $list = M('Area')->where($map)->select();
            return $list;
        }
    }

    // //查询隶属性质
    // public function ajax_getSubjection()
    // {
    //     $key = I('post.key');
    //     $map = array();
    //     if (!empty($key)) {
    //         $map['name'] = array('like',"%$key%");
    //     }
    //     $list = M('Subjection')->field('id,name')->where($map)->limit('20')->select();
    //     $this->ajaxReturn(1,$list," ");//单位隶属
    // }

    /**
     * 导入excel文件 并返回数据
     * @param  string $file excel文件路径
     * @return array        excel文件内容数组
     */
    function read_excel($file,$model_name){
        $filepath = $_SERVER['DOCUMENT_ROOT'].$file;
        // 判断文件是什么格式
        $type = pathinfo($filepath); 
        $type = strtolower($type["extension"]);
        $type=$type==='csv' ? $type : 'Excel5';
        ini_set('max_execution_time', '0');
        Vendor('PHPExcel.PHPExcel');
        Vendor('PHPExcel.PHPExcel.IOFactory');
        // 判断使用哪种格式
        $objReader = \PHPExcel_IOFactory::createReader($type);
        $objPHPExcel = $objReader->load($filepath); 
        $sheet = $objPHPExcel->getActiveSheet(0); 
        // 取得总行数 
        $highestRow = $sheet->getHighestRow();     
        // 取得总列数      
        $highestColumn = $sheet->getHighestColumn(); 
        $highestColumn =  \PHPExcel_Cell::columnIndexFromString($highestColumn);  
        //从第一行开始读取数据 封装为php数组
        //读取第一行，行头
        $data_header = array();
        $data_header_excel = array();
        // $temp = $sheet->getCellByColumnAndRow(6,1)->getValue();
        // p($temp);
        // die();
        $config_list = M($model_name)->select();
        $config_list_new = array();
        foreach ($config_list as $key => $value) {
            $field = $value['field'];
            // if (!empty($field)) {
            $config_list_new[$value['showname']] = $value;
            // }
        }
        for ($i = 0; $i < $highestColumn; $i++) {  
            $header_value = (string)$sheet->getCellByColumnAndRow($i,1)->getValue();
            $data_header_excel[$i] = $header_value;

            $field = $config_list_new[$header_value]['field'];
            $showname = $config_list_new[$header_value]['showname'];
            $header = empty($field)?$header_value:$field; 
            $data_header[$i] = $header;
        } 
        $excelData = array();
        for ($row = 2; $row <= $highestRow; $row++) {
            //$key = 0;
            for ($col = 0; $col < $highestColumn; $col++) {
                $value = (string)$sheet->getCellByColumnAndRow($col, $row)->getValue();
                $header_value = $data_header_excel[$col];
                // p($header_value);
                $config = $config_list_new[$header_value];
                //根据字典将数据替换成对应格式
                if (!empty($config['dictionary'])) {
                    $dictionary = json_decode($config['dictionary'],true);
                    $value = $dictionary['导入'][$value];
                }
                $excelData[$row - 2][$data_header[$col]] =  $value;
            } 
        }
        // p($excelData);
        $this->_delFile($file);
        return $excelData;
    }

    protected function _delFile($path)
    {
        $path = $_SERVER['DOCUMENT_ROOT'].$path;
        if(is_file($path))  //判断是否存在该文件
        {
            unlink($path);  //删除文件
        }
    }
    
    //ajax_上传图片
    public function ajax_upload_files() {
        //上传图片
        if (!empty($_FILES['img']['name'])) {
            $result = $this->_upload_files('images/'.$this->_name);
            if ($result['status'] != 1) {
                $this->ajaxReturn(0, $result['data']);
            }
            else
            {
                $data = $result['data']['img'];
                $this->ajaxReturn(1,$data['savepath'].$data['savename']);
            }
        } 
        else
        {
            $this->ajaxReturn(0, '请选择图片！');
        }
    }

    //字段名
    //后缀
    public function _uploadFile($inputname,$exts = array('jpg', 'gif', 'png', 'jpeg'),$foldername = "file")
    { 
        $b = true;
        if (is_array($inputname)) 
        {
            foreach ($inputname as $key => $name) {
                $b = !empty($_FILES[$name]);
            }
        }
        else
        {
            $b = !empty($_FILES[$inputname]);
        }
        //上传图片
        if ($b) {
            $result = $this->_upload_files($foldername.'/'.$this->_name,$exts);
            if ($result['status'] != 1) {
                if (IS_AJAX) {
                    $this->ajaxReturn(0, $result['data']);
                }
                else{
                    return -1;
                }
            }
            else
            { 
                $data = array();
                $result_data = $result['data'];
                if (is_array($inputname)) 
                {
                    foreach ($inputname as $key => $name) {
                        $data[$name] = $result_data[$name]['savepath'].$result_data[$name]['savename'];
                    }
                }
                else
                {
                    $data = $result_data[$inputname]['savepath'].$result_data[$inputname]['savename'];
                }
                return $data;
                //$this->ajaxReturn(1,$data['savepath'].$data['savename']);
            }
        } 
        else {
            return 0;
        }
    }

    /**
     * 上传文件
     * $savePath 储存路径
     * $exts 允许后缀
     */
    protected function _upload_files($savePath = '',$exts = array('jpg', 'gif', 'png', 'jpeg')) {
        $upload = new \Think\Upload(); 
        $upload->maxSize   =     13145728 ;// 设置附件上传大小
        $upload->exts      =     $exts;// 设置附件上传类型
        $upload->rootPath  =     '.'; // 设置附件上传根目录
        $upload->savePath  =     '/Public/UploadFile/'.$savePath.'/'; // 设置附件上传（子）目录 
        //自定义上传规则
        $upload->subType = 'date';                      //子目录创建方式，默认为hash，可以设置为hash或者date 
        $upload->dateFormat = 'Ymd';                     //子目录方式为date的时候指定日期格式  
        $info = $upload->upload();//$this->_upload_init($upload); 
        if(!$info) {// 上传错误提示错误信息
            return array('status'=>0,'data'=>$upload->getError());
        }
        else{// 上传成功
            return array('status'=>1,'data'=>$info);
        }
    }

    //仅仅上传附件使用
    public function ajax_upload_enclosure() {
        //上传图片
        if (!empty($_FILES['img']['name'])) {
            $result = $this->_upload_enclosure('enclosure/'.$this->_name);
            if ($result['status'] != 1) {
                $this->ajaxReturn(0, $result['data']);
            }
            else
            {
                $data = $result['data']['img'];
                $this->ajaxReturn(1,$data['savepath'].$data['savename']);
            }
        } 
        else {
            $this->ajaxReturn(0, '请选择图片！');
        }
    }

    /**
     * 上传文件 附件
     */
    protected function _upload_enclosure($savePath = '') {
        $upload = new \Think\Upload(); 
        $upload->maxSize   =     13145728 ;// 设置附件上传大小
        $upload->exts      =     array('ZIP','rar','7z','zip','jar','txt','doc','docx','wps','swf','pdf','ppt','xls','xlsx','jpg','png');// 设置附件上传类型
        $upload->rootPath  =     '.'; // 设置附件上传根目录
        $upload->savePath  =     '/Public/UploadFile/'.$savePath.'/'; // 设置附件上传（子）目录 
        //自定义上传规则
        $info = $upload->upload();//$this->_upload_init($upload); 
        if(!$info) {// 上传错误提示错误信息
            return array('status'=>0,'data'=>$upload->getError());
        }
        else{// 上传成功
            return array('status'=>1,'data'=>$info);
        }
    }
    
    /**
     *
     * Enter 导出excel共同方法 ...
     * @param unknown_type $expTitle
     * @param unknown_type $expCellName
     * @param unknown_type $expTableData
     */
    protected function exportExcel($expTitle, $expCellName, $expTableData) {
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle); //文件名称
        $fileName = $xlsTitle . $_SESSION['account'] . date('_YmdHis'); //or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new PHPExcel();
        $cellName = array(
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            'AA',
            'AB',
            'AC',
            'AD',
            'AE',
            'AF',
            'AG',
            'AH',
            'AI',
            'AJ',
            'AK',
            'AL',
            'AM',
            'AN',
            'AO',
            'AP',
            'AQ',
            'AR',
            'AS',
            'AT',
            'AU',
            'AV',
            'AW',
            'AX',
            'AY',
            'AZ'
        );
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1'); //合并单元格
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 3) , $expTableData[$i][$expCellName[$j][0]]);
            }
        }
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls"); //attachment新窗口打印inline本窗口打印
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    //将时间转化为天
    protected function tranSecToMHDY($second) {
        $day = ($second / 3600) / 24;
        return $day . "天";
    }

    /**
     * AJAX返回数据标准
     *
     * @param int $status
     * @param string $msg
     * @param mixed $data
     * @param string $dialog
     */
    protected function ajaxReturn($status = 1, $msg = '', $data = '', $dialog = '') {
        if (empty($msg) && empty($data) && empty($dialog)) {
            parent::ajaxReturn($status);
        }
        else
        {
            parent::ajaxReturn(array(
                'status' => $status,
                'msg' => $msg,
                'data' => $data,
                'dialog' => $dialog
            ));
        }
    }

    //请求指定地址
    function getHtml($url)
    {
        $ch = curl_init();   
        $timeout = 50;  
        curl_setopt ($ch, CURLOPT_URL,$url);  
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);  
        $file_contents = curl_exec($ch);  
        curl_close($ch);  
        return $file_contents;
    }
    
    /*
    * 查询单个数据并将其assign到模板上
    * $modName 模型名称
    * $assignName assign到模板上的名称 
    * $map 条件(你懂的)
    * $strSort 排序方式 如：sort asc,id desc
    * $fields 字段列表(你懂的) 
    * $arrJoins join 数组
    */
    public function assignInfo($modName,$assignName,$map = array(),$strSort = '',$fields = '*',$arrJoins = array())
    {
        $list = $this->_select($modName,$arrJoins,$map,$fields,$strSort,'');
        //M($modName)->fields($fields)->where($map)->order($strSort)->select();
        if (IS_AJAX) {
            return $list['rows'][0];
            // $this->ajaxReturn($list['rows'][0]);
        } 
        // p($list);
        $this->assign($assignName,$list['rows'][0]);
        //p($list['rows'][0]);
        //p(M('')->getlastSql());
    }

    /*
    * 查询单张表并将其assign到模板上
    * $modName 模型名称
    * $assignName assign到模板上的名称 
    * $map 条件(你懂的)
    * $strSort 排序方式 如：sort asc,id desc
    * $fields 字段列表(你懂的) 
    * $arrJoins join 数组
    */
    public function assignList($modName,$assignName,$map = array(),$strSort = '',$fields = '*',$arrJoins = array())
    {
        $list = $this->_select($modName,$arrJoins,$map,$fields,$strSort,'');//M($modName)->fields($fields)->where($map)->order($strSort)->select();
        // session('test',M()->getlastSql());
        if (IS_AJAX)
        {
            return $list['rows'];
            // $this->ajaxReturn($list['rows']);
        }
        //p($list['rows']);
        $this->assign($assignName,$list['rows']);
    }

    /*
    * 多表查询
    * $modName 模型名称
    * $arrjoins join 数组
    * 数据实例
    * $arrJoins = array('0'=>' as test left join bbibm_test1 as test1 on test.id = test1.testid ')
    * $map 条件(你懂的)
    * $fields 字段列表(你懂的)
    * $strorder 排序方式 如：sort asc,id desc
    * $pagesize 每页显
    */
    public function _select($modName,$arrJoins,$map,$fields = "*",$strorder,$pagesize)
    {  
        $strJoins = "";
        foreach ($arrJoins as $key => $join) {
            $strJoins .= " ".$join." ";
            //$joins->join($join);
        }
        $mod = M($modName);
        //如果需要分页
        if ($pagesize) {  
            $count = $mod->join($strJoins)->where($map)->count();
            $pager = new \Think\Page($count, $pagesize); 
        }
        $select = $mod->field($fields)->join($strJoins)->where($map)->order($strorder);
        $this->list_relation && $select->relation(true);
        if ($pagesize) {
            $select->limit($pager->firstRow.','.$pager->listRows);
            $page = $pager->show();
            //$this->assign("page", $page);
        }
        $list = $select->select();
        //存储ajax_getList
        session("selectsql",M($this->_name)->getlastSql());
        $data['rows'] = $list;
        $data['total'] = empty($count) ? 0 : $count;   
        return $data;
    }

}

