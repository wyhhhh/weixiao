<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class HelpMessageController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
	}

	public function _before_getList()
    {
        $map = array();
        $fields = "";

        
        //搜索设置
        $name = I('get.name');
        if ($name != "") {
            $map['name'] = array('like',"%$name%");
        }
        $fields = "id,name,sort,addtime,if(isshow = 1,'显示','不显示') as isshow";
        $data['map'] = $map;
        $data['fields'] = $fields;
        return $data;
    }
    
}