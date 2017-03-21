<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class GoodsController extends AppUserController {

    function _initialize(){
		parent::_initialize();
		$this->_model = M("Goods");
	}
	//用户端-商品列表
	public function goods(){
		$userid = $this->_data['userid'];//当前登录的用户的ID
		$pageNumber = $this->_data['pageNumber'];//当前第几页
		if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
        if (empty($userid)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "非法操作";
			$this->_return($res_data);
		}
        $map = array(); 
        $map['u.id'] = $userid;//
		$community = M("Users")->field("h.communityid")->join("as u left join ".$this->_qz."house as h on h.id = u .house_id")->where($map)->find();
		if (empty($community)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "没有数据";
			$this->_return($res_data);
		}
        $communityid = $community['communityid'];//链接房屋表,获取用户所在社区
        $map = array();
        $map['communityid'] = $communityid;//获取到的社区ID
        $map['status'] = 1;//状态: 0_下架, 1_上架（默认）
        $map['toppid'] = 0;
        // $map['pid'] = 0;
        $count = $this->_model->count($mod_pk);
        $pagesize = 6;
        $pager = new \Think\Page($count,$pagesize);//实例化分页类
        //查询当前用户社区的所有商品
        $goods = $this->_model->field("id,name,price,userid,description,comment,images,praise_count,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime")->where($map)->order("addtime desc")->limit(($pageNumber-1)*6,$pager->listRows)->select();
        foreach ($goods as $key => $value) {
            $images = $value['images'];
            $images = json_decode($images,true);
            $goods[$key]['images'] = $images;
            $res_data['data'][$key]['images'] = $goods;
        }
        
        if (!empty($goods)) {
	        foreach ($goods as $key => $item) {
		        	$res_data['data'][$key] = $item;
		        	// $images = $item['images'];
            		// $images = json_decode($images,true);
           		 	// $goodsimage[$key] = $images;
		        	$gooduserid = $item['userid'];
		        	$map = array();
		        	$map['u.id'] = $gooduserid;//链接用户表,找个每个商品对应用户ID的信息
		        	$gooduserdata = M("Users")->field("u.head_portrait as head_portrait,u.pet_name as pet_name,h.number as hnumber,h.communityid as communityid")->join("as u left join ".$this->_qz."house as h on h.id = u.house_id")->where($map)->find();
		        	$res_data['data'][$key]['head_portrait'] = $gooduserdata['head_portrait'];
		        	$res_data['data'][$key]['pet_name'] = $gooduserdata['pet_name'];
		        	$res_data['data'][$key]['number'] = $gooduserdata['hnumber'];
		        	$res_data['data'][$key]['communityid'] = $gooduserdata['communityid'];
	        		// $res_data['data'][$key]['images'] = $goodsimage;
	        	}	
		        	$res_data['status'] = 1;
		        	$res_data['msg'] = "商品列表加载成功"; 
	        }else{
		        	$res_data['status'] = 0;
		        	$res_data['msg'] = "商品列表加载失败";
        }
        	$this->_return($res_data);
	}
	//用户端-商品详情接口
	public function goodsDetail(){
		$id = $this->_data['id'];//商品的ID
		$userid = $this->_data['userid'];//当前登录的用户的ID
		$communityid = $this->_data['communityid'];//社区ID
		$pageNumber = $this->_data['pageNumber'];//当前第几页
		if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
		$map = array();
		$map['g.id'] = $id;
		$map['g.communityid'] = $communityid;
		$map['g.status'] = 1;//状态: 0_下架, 1_上架（默认）
		$goodsdata = M("Goods")->field("from_UNIXTIME(g.addtime,'%Y-%m-%d %H-%m') as addtime,u.head_portrait,u.pet_name,g.comment,g.images,g.name,h.number as number,g.price,g.description,g.name,g.praise_count")->join("as g left join ".$this->_qz."users as u on g.userid = u.id left join ".$this->_qz."house as h on h.id = u.house_id")->where($map)->find();
        //图片转化成JSON数组
        if (empty($goodsdata)) {
        	$res_data['status'] = 0;
        	$res_data['msg'] = "错误数据";
        	$this->_return($res_data);
        }
		$images = $goodsdata['images'];
	    $images = json_decode($images,true);
	    $goodsdata['images'] = $images;
		if ($goodsdata) {
			$res_data['data'] = $goodsdata;
			$res_data['data']['type'] = "商品";
			$res_data['status'] = 1;
			$map = array();
			$map['userid'] = $userid;
	        $map['praiseid'] = $id;
	        $map['type'] = 3;//对应类型: 1_福利(广告), 2_新闻,3_商品
	        $result = M("PraiseLog")->field("addtime")->where($map)->find();
	        if (!empty($result['addtime'])) {
	            $res_data['data']['ispraise'] = 1;//1_已赞，0_未赞
	        } else{
				$res_data['data']['ispraise'] = 0;//1_已赞，0_未赞
	        }
	        $map = array();
			$map['type'] = 2;
			$map['userid'] = $userid;
			$map['relationid'] = $id;
			$result = M("Collect")->where($map)->find();
			if ($result) {
				// $res_data['status'] = 0;
				$res_data['data']['iscollect'] = 1;//已经收藏过
			}else{
				// $res_data['status'] = 0;
				$res_data['data']['iscollect'] = 0;//没有收藏过
			}
			// $res_data['msg'] = "你已经收藏过了";
			// $this->_return($res_data);
			$res_data['msg'] = "商品详情加载成功";
		}else{
			$res_data['status'] = 0;
			$res_data['msg'] = "商品详情加载失败";
		}
		//论坛评论 begin
		$map_select['g.toppid'] = $id;
		$count = M("Goods")->count($mod_pk);
        $pagesize = 4;
        $pager = new \Think\Page($count,$pagesize);
		/* P代表下一级数据 */
		// 在前端展示这里的p用作正常显示的评论数据，p2用作被@的展示效果
		$replyList = M("Goods")->field("g.id as gid,u.head_portrait,u.pet_name,g.description,from_UNIXTIME(g.addtime,'%Y-%m-%d %H:%i') as addtime_g,ifnull(g2.id,'') g2id,ifnull(g2.description,'') description2,ifnull(from_UNIXTIME(g2.addtime,'%Y-%m-%d %H:%i'),'') as addtime_g2")->join(" as g left join ".$this->_qz."goods as g2 on g.pid=g2.id left join ".$this->_qz."users as u on u.id = g.userid")->where($map_select)->order("g.addtime desc,g.id desc")->limit(($pageNumber-1)*4,$pager->listRows)->select();
		//帖子浏览数量+1
		$map_save['id'] = $id;
		$map_save['browse_count'] = array("exp","browse_count+1");
		M("Goods")->save($map_save);
		//论坛评论 end
		$res_data['data']['reply'] = $replyList;
		$this->_return($res_data);
	}

	//用户端-商品发布接口
	public function publishGoods(){
		$userid = $this->_data['userid'];//当前登录的用户的ID	
		if (empty($userid)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "非法操作";
			$this->_return($res_data);
		}
		$name = $this->_data['name'];//商品的名字
		if (empty($name)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "请填写商品名称";
			$this->_return($res_data);
		}
		$price = $this->_data['price'];//商品的价格
		if (empty($price)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "请填写商品价格";
			$this->_return($res_data);
		}
		$description = $this->_data['description'];//商品的描述
		if (empty($description)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "请输入商品的描述";
			$this->_return($res_data);
		}
		$images = $this->_data['images'];//上传的图片
		$file_prefix = "img_";
		$imgdata = $this->upload_images($images,$file_prefix);
		$img = explode(",", $imgdata);
		$baseimage = json_encode($img);
		$addtime = time();//获取发布时间
		$map = array();
		$map['u.status'] = 1;//状态：0_不启用,1_启用
		$map['u.id'] = $userid;
		$community = M("Users")->field("h.communityid,h.number")->join(" as u left join ".$this->_qz."house as h on h.id = u.house_id")->where($map)->find();
		if (empty($community)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "没有数据";
			$this->_return($res_data);
		}
		$communityid = $community['communityid'];//获得当前登录用户的社区ID
		$number = $community['number'];
		$map = array();
		$data['userid'] = $userid;
		$data['name'] = $name;
		$data['price'] = $price;
		$data['description'] = $description;
		$data['images'] = $baseimage;
		$data['addtime'] = $addtime;
		$data['communityid'] = $communityid;
		$data['number'] = $number;
		$result = M("Goods")->where($map)->add($data);
		if ($result) {
			$res_data['status'] = 1;
			$res_data['msg'] = "发布成功";
		}else{
			$res_data['status'] = 0;
			$res_data['msg'] = "发布失败";
		}
		$this->_return($res_data);
	}
	//商品的点赞接口
	public function getPraise(){
		$userid = $this->_data['userid'];//当前登录的用户
		if (empty($userid)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "请登录！";
			$this->_return($res_data);
		}
        $goodsid = $this->_data['goodsid'];//商品id
        $type = 3;//对应类型: 1_福利(广告), 2_新闻,3_商品
        $map = array();
        $map['userid'] = $userid;
        $map['praiseid'] = $goodsid;
        $map['type'] = $type;
        $result = M("PraiseLog")->field("addtime")->where($map)->find();
        if (!empty($result['addtime'])) {
            $res_data['status'] = 0;
            $res_data['data']['ispraise'] = 1;//1_已赞，0_未赞
            $res_data['msg'] = "你已经赞过了";
            $this->_return($res_data);
        }else{
            $map = array();
            $map['id'] = $goodsid;
            $map['status'] = 1;// 状态: 0_下架, 1_上架（默认）
            // $map['userid'] = $userid;//判断当前的登录的用户的ID
            $praise['praise_count'] = array("exp","praise_count+1");//点赞+1
            $result = M("Goods")->where($map)->save($praise);
            if($result){
	            $map = array();//点赞记录
	            $addtime = time();
	            $pricelog['type'] = 3;
	            $pricelog['userid'] = $userid;//点赞用户ID
	            $pricelog['praiseid'] = $goodsid;//点赞对象表ID
	            $pricelog['addtime'] = $addtime;
	            $resultlog = M("PraiseLog")->where($map)->add($pricelog);
	            if ($resultlog) {
	            	$res_data['status'] = 1;
	            	$res_data['msg'] = "点赞成功";
	            	$res_data['data']['ispraise'] = 0;
	            }else{
	            	$res_data['status'] = 0;
            		$res_data['msg'] = "非法操作";
	            }
            }else{
            	$res_data['status'] = 0;
            	$res_data['msg'] = "非法操作";
            }         	
        	$this->_return($res_data);
        }
	}


	//用户端 评论商品 --- 对象是商品
	public function commentGoods(){
		$userid = $this->_data['userid'];//当前登录的用户的ID
		$goodsid = $this->_data['goodsid'];//要回复商品的ID
		$communityid = $this->_data['communityid'];//社区ID
		$content = $this->_data['content'];//内容

		if(empty($userid)||empty($goodsid)||empty($communityid)||empty($content)){
			$res_data['status'] = 0;
			$res_data['data'] = "";
			$res_data['msg'] = "非法操作";
			$this->_return($res_data);
		}

		//当前要回复的对象（主帖/评论/回复）
		$goods_this = $this->_model->field("userid")->where("id=".$goodsid)->find();
		$goods_userid = $goods_this['userid'];

		//查看商品是否存在评论 begin
		$map_g['toppid'] = $goodsid;//商品id
		$map_g['pid'] = array("exp"," is Null ");//pid为空的才是评论
		$goods = $this->_model->field("floor,sort")->order("id desc,addtime desc")->where($map_g)->find();
		//查看商品是否存在评论 end
		$sort = 1; $floor = 1; $lastfloor = 1; $comment = 1;
		if(!empty($goods)){
			if(!empty($goods['sort'])){
				$sort = ($goods['sort']+1);
			}
			if(!empty($goods['floor'])){
				$floor = ($goods['floor']+1);
				$lastfloor = $floor;//最后楼层等于当前评论的商品楼层(最后楼层是记录在商品里面的字段)
			}
		}

		$map['communityid'] = $communityid;
		$map['userid'] = $userid;//用户id(管理员帖时，为管理员id)
		$map['toppid'] = $goodsid;//(主贴)商品id
		$map['description'] = $content;//评论内容
		$map['sort'] = $sort;
		$map['floor'] = $floor;
		$map['addtime'] = time();
		$goods_return_add = $this->_model->add($map);

		//处理主帖楼层、最后评论时间、评论数量等操作
		$map_save['id'] = $goodsid;
		$map_save['floor'] = $floor;
		$map_save['lastfloor'] = $lastfloor;
		$map_save['lastwritebacktime'] = time();
		$map_save['comment'] = array("exp","comment+1");
		$this->_model->save($map_save);

		//发送通知消息到相应的用户
		$map_save3['relationid'] = $goodsid;//关联的帖子/商品数据id
		$map_save3['replyid'] = $goods_return_add;//关联的回复数据id
		$map_save3['type'] = 2;//类型: 1_帖子, 2_商品
		$map_save3['addtime'] = time();
		$information_return_save = M("Information2")->add($map_save3);
		$map_save4['userid'] = $goods_userid;//消息关联用户id
		$map_save4['informationid'] = $information_return_save;//消息表id
		M("InformationRelation2")->add($map_save4);

		$res_data['status'] = 1;
		$res_data['data'] = "";
		$res_data['msg'] = "评论成功";
		$this->_return($res_data);
	}

	//回复 评论/回复 --- 对象是商品回复或评论
	public function replyComment(){
		$userid = $this->_data['userid'];//当前登录的用户的ID
		$goodsid = $this->_data['goodsid'];//要回复商品的ID
		$replyid = $this->_data['replyid'];//要回复的评论或回复的ID
		$communityid = $this->_data['communityid'];//社区ID
		$content = $this->_data['content'];//内容
		if(empty($userid)||empty($goodsid)||empty($replyid)||empty($communityid)||empty($content)){
			$res_data['status'] = 0;
			$res_data['data'] = "";
			$res_data['msg'] = "非法操作";
			$this->_return($res_data);
		}

		//当前要回复的对象（主帖/评论/回复）
		$goods_this = $this->_model->field("userid")->where("id=".$replyid)->find();
		$goods_userid = $goods_this['userid'];

		$map['communityid'] = $communityid;
		$map['userid'] = $userid;//用户ID(管理员帖时，为管理员id)
		$map['pid'] = $replyid;//回复的商品ID
		$map['toppid'] = $goodsid;//(主贴)商品ID
		//
		$map['description'] = $content;//(主贴)商品id
		$map['addtime'] = time();
		$goods_return_add = $this->_model->add($map);

		//处理被回复数据的楼层、最后评论时间、评论数量等操作
		$map_save['id'] = $replyid;
		$map_save['comment'] = array("exp","comment+1");
		$this->_model->save($map_save);
		
		//处理主帖楼层、最后评论时间、评论数量等操作
		$map_save2['id'] = $goodsid;
		$map_save2['floor'] = $floor;
		$map_save2['lastfloor'] = $lastfloor;
		$map_save2['lastwritebacktime'] = time();
		$map_save2['comment'] = array("exp","comment+1");
		$this->_model->save($map_save2);

		//发送通知消息到相应的用户
		$map_save3['relationid'] = $goodsid;//关联的帖子/商品数据id
		$map_save3['replyid'] = $goods_return_add;//关联的回复数据id
		$map_save3['type'] = 2;//类型: 1_帖子, 2_商品
		$map_save3['addtime'] = time();
		$information_return_save = M("Information2")->add($map_save3);
		$map_save4['userid'] = $goods_userid;//消息关联用户id
		$map_save4['informationid'] = $information_return_save;//消息表id
		M("InformationRelation2")->add($map_save4);

		$res_data['status'] = 1;
		$res_data['data'] = "";
		$res_data['msg'] = "回复成功";
		$this->_return($res_data);
	}

	//(商品)查看回复详情 对象是此回复的上下级
	public function replyDetail(){
		//通过点击的数据获取上下级的数据
		$id = $this->_data['id'];//当前点击的 评论/回复的ID
		if(empty($id)){
			$res_data['status'] = 0;
			$res_data['data'] = "";
			$res_data['msg'] = "非法操作";
			$this->_return($res_data);
		}

		$map['id'] = $id;
		$goods_this = $this->_model->field("id,description,pid,ifnull(from_UNIXTIME(addtime,'%Y-%m-%d %H:%i'),'') as addtime")->where($map)->find();//找到当前数据
		$pid = $goods_this['pid'];//根据当前数据的pid找上一级
		unset($goods_this['pid']);
		$map_prev['id'] = $pid;
		$goods_prev = $this->_model->field("id,description,ifnull(from_UNIXTIME(addtime,'%Y-%m-%d %H:%i'),'') as addtime")->where($map_prev)->find();//找到上一条数据
		// p($this->_model->getlastsql());
		// return;
		$map_next['pid'] = $id;//根据当前id找下一级
		$goods_next = $this->_model->field("id,description,ifnull(from_UNIXTIME(addtime,'%Y-%m-%d %H:%i'),'') as addtime")->where($map_next)->select();//找到上一条数据

		$res_data['status'] = 1;
		$res_data['data']['prev'] = $goods_prev;
		$res_data['data']['this'] = $goods_this;
		$res_data['data']['next'] = $goods_next;
		$res_data['msg'] = "";
		$this->_return($res_data);
	}



}