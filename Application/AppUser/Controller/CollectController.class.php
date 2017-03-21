<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class CollectController extends AppUserController {

    function _initialize(){
		parent::_initialize();
	}
	//我的收藏接口
	public function collect(){
		$userid = $this->_data['userid'];//当前用户的ID
		$pageNumber = $this->_data['pageNumber'];//当前第几页
		if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
		$map = array();
		$map['userid'] = $userid; 
		$count = M("Collect")->count($mod_pk);
		$pagesize = 5;
		$pager = new \Think\Page($count,$pagesize);
		$collect = M("Collect")->field("id,relationid,type,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime")->where($map)->order("id asc")->limit(($pageNumber-1)*5,($pager->listRows))->select();//分页处理
		if (empty($collect)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "没有数据";
			$this->_return($res_data);
		}
		foreach ($collect as $key => $item) {
					//type收藏类型:1_帖子, 2_商品
				if($item['type'] == 1){
					$postid = $item['relationid'];
					$map = array();
					$map['id'] = $postid;
					$postdata = M("Post")->field("title,content,images,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime")->where($map)->find();
					$images = $postdata['images'];//多个图片显示，传给前端要以JSON 形式
           			$images = json_decode($images,true);
            		$postdata['images'] = $images;
					$res_data['data'][$key] = $postdata;
					$res_data['data'][$key]['id'] = $item['id'];
					$res_data['data'][$key]['type'] = $item['type'];
				}
				if ($item['type'] == 2) {
					$goodsid = $item['relationid'];
					$map = array();
					$map['id'] = $goodsid;
					$goodsdata = M("Goods")->field("name,description,images,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime")->where($map)->find();
					$images = $goodsdata['images'];//多个图片显示，传给前端要以JSON 形式
           			$images = json_decode($images,true);
            		$goodsdata['images'] = $images;
					$res_data['data'][$key] = $goodsdata;				
					$res_data['data'][$key]['id'] = $item['id'];
					$res_data['data'][$key]['type'] = $item['type'];
				}					
		}
		$res_data['status'] = 1;
		$res_data['msg'] = "加载成功";
		$this->_return($res_data);
	}
	//我的收藏删除接口
	public function delete(){
		$id = $this->_data['id'];//当前点击删除时对应的ID
		$map = array();
		$map['id'] = $id;
		$result = M("Collect")->where($map)->delete();
		if ($result) {
			$res_data['status'] = 1;
			$res_data['msg'] = "删除成功";
		}else{
			$res_data['status'] = 0;
			$res_data['msg'] = "删除失败";
		}
		$this->_return($res_data);
	}
	//点击收藏功能接口
	public function getCollect(){
		$relationid = $this->_data['id'];//当前商品或者帖子的ID
		$userid = $this->_data['userid'];//当前登录的用户的ID
		$type = $this->_data['type'];//收藏类型:1_帖子, 2_商品
		$addtime = time();
		if (empty($relationid)||empty($userid)||empty($type)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "非法操作！";
			$this->_return($res_data);
		}
		$map = array();
		$map['type'] = $type;
		$map['userid'] = $userid;
		$map['relationid'] = $relationid;
		$result = M("Collect")->where($map)->find();
		if ($result) {
			$res_data['status'] = 0;
			$res_data['data']['iscollect'] = 1;//已经收藏
			$res_data['msg'] = "你已经收藏过了";
			$this->_return($res_data);
		}else{
			$data['relationid'] = $relationid; 
			$data['userid'] = $userid; 
			$data['type'] = $type; 
			$data['addtime'] = $addtime; 
			$result = M("Collect")->add($data);
			if ($result) {
				$res_data['status'] = 1;
				$res_data['data']['iscollect'] = 0;//未收藏
				$res_data['msg'] = "收藏成功";
			}else{
				$res_data['status'] = 0;
				$res_data['msg'] = "收藏失败";
			}
			$this->_return($res_data);		
		}
	}
	//点击取消收藏接口
	// public function outCollect(){
	// 	$relationid = $this->_data['id'];//当前商品或者帖子的ID
	// 	$userid = $this->_data['userid'];//当前登录的用户的ID
	// 	$type = $this->_data['type'];//收藏类型:1_帖子, 2_商品
	// 	$addtime = time();
	// 	if (empty($relationid)||empty($userid)||empty($type)) {
	// 		$res_data['status'] = 0;
	// 		$res_data['msg'] = "非法操作！";
	// 		$this->_return($res_data);
	// 	}
	// 	$map = array();
	// 	$map['type'] = $type;
	// 	$map['userid'] = $userid;
	// 	$map['relationid'] = $relationid;
	// 	$result = M("Collect")->where($map)->delete();
	// 	if ($result) {
	// 		$res_data['status'] = 1;
	// 		// $res_data['data']['iscollect'] = 1;//取消
	// 		$res_data['msg'] = "取消收藏成功";
	// 	}else{
	// 		$res_data['status'] = 0;
	// 		$res_data['msg'] = "取消收藏失败";
	// 	}
	// 		$this->_return($res_data);
	// }
}