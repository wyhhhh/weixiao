<?php
namespace Manage\Controller;

use Manage\Common\ManageController;
use Think\Controller;
class ManageRoleController extends ManageController {

    function _initialize()
	{
        parent::_initialize(); 
	}

    public function _before_getList()
    {
        $arrJoins = array();
        $map = array();
        $fields = "";
        //连接查询

        //条件
         // 名称
        $name = I('get.name');
        if ($name != "") {
            $map['name'] = array('like',"%$name%");
        }

        //字段列表
        $fields = "id,name,sort,isdel";

        //连表


        $data['arrJoins'] = $arrJoins;
        $data['map'] = $map;
        $data['fields'] = $fields;
        return $data;
    }

    //删除之前
    public function _before_delete($ids)
    {
        //删除条件
        $map['isdel'] = 1;
        return $map;
    }

    //添加显示之前
    public function _before_add()
    {
        //查询权限信息
        //permissions
        $map = array();
        $map['pid'] = 0;
        $map['status'] = 1;
        $map['type'] = 1;//类型 平台Manage type=1, 物业Community type=2 
        $fields = "name as text,id";
        $list = $this->_getNavList($map,$fields);
        $this->assign("navigations",json_encode($list));
    }

    //修改显示之前
    public function _before_edit_data($data)
    {
        $map = array();
        $map['pid'] = 0;
        $map['status'] = 1;
        $map['type'] = 1;//类型 平台Manage type=1, 物业Community type=2 
        $fields = "name as text,id";
        //查询权限 
        $qx = $data['permissions'];
        $qx_arr = explode(',',$qx);
        $list = $this->_getNavList($map,$fields,$qx_arr);
        $this->assign("navigations",json_encode($list));
        return $data;
    }

    //递归查询导航
    public function _getNavList($map,$fields,$qx = "")
    {
        // if ($qx == "all")
        // {
        //     $fields .= ",true as selected";
        // }
        // else
        // {
        //     $fields .= ",if(find_in_set(id, '$qx') > 0,'true','false') as selected ";
        // }
        //源字段基础之上加上主键id，以及选中状态(是否已设置权限)
        $list = M('Navigations')->field($fields.",id as tempkey")->where($map)->order('sort asc,id desc')->select();
        $arr = array();
        if($list && count($list)){//如果有子类 
            foreach ($list as $key => $item) {
                $map['pid'] = $item['tempkey'];
                //设置状态
                if (in_array($item['tempkey'] , $qx) || $qx[0] == "all") {
                    $item['state']['selected'] = "true"; 
                }
                unset($item['tempkey']);
                $children = $this->_getNavList($map,$fields); //调用函数，传入参数，继续查询下级 
                if (!empty($children)) {
                    $item['children'] = $children;
                }
                $arr[] = $item; //组合数组 
            }
            return $arr;
        }
    }

    public function _before_insert($data)
    {
        //上传图片/文件
        $filename = array("test");
        //$inputname,$exts = array('jpg', 'gif', 'png', 'jpeg'),$foldername = "image"
        $filedata = $this->_uploadFile($filename);
        if ($filedata)
        {
            $data = array_merge($filedata,$data);
        }
        else
        {
            foreach ($filename as $key => $value) {
                unset($data[$value]);
            }
        }
        return $data;
    }
}