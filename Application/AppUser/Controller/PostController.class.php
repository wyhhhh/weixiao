<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class PostController extends AppUserController {

    function _initialize(){
		parent::_initialize();
		$this->_model = M("Post");
	}
	//用户端-微卖-论坛接口
	public function post(){
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
		$community = M("Users")->field("h.communityid")->join("as u left join ".$this->_qz."house as h on h.id = u.house_id")->where($map)->find();
		if (empty($community)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "没有数据";
			$this->_return($res_data);
		}
        $communityid = $community['communityid'];//链接房屋表,获取用户所在社区
        $count = M("post")->count($mod_pk);
        $pagesize = 5;
        $pager = new \Think\Page($count,$pagesize);//实例化分页类
		$map = array();
		$map['p.communityid'] = $communityid;
		$map['p.status'] = 1;//审核状态(0_待审黑,1_通过审核,-1_审核未通过，默认为通过审核)
		// $map['toppid'] = array("exp"," is Null ");//toppid为空的才是帖子
		$map['p.toppid'] = 0;
		$post = M("Post")->field("u.pet_name,p.id,p.userid,p.title,p.images,p.browse_count,from_UNIXTIME(p.addtime,'%Y-%m-%d %H:%i') as addtime")->join("as p left join ".$this->_qz."users as u on u.id = p.userid")->where($map)->order("p.addtime desc")->limit(($pageNumber-1)*5,$pager->listRows)->select();
		// $res_data['data'] = $post;
		// $this->_return($res_data);
		foreach ($post as $key => $value) {
            $images = $value['images'];
            $images = json_decode($images,true);
            $post[$key]['images'] = $images;
            // $res_data['data'][$key]['images'] = $post;
        }
        if ($post) {
			$res_data['data'] = $post;
			$res_data['status'] = 1;
			$res_data['msg'] = "帖子列表加载成功";	
        }else{
        	$res_data['status'] = 0;
        	$res_data['msg'] = "帖子列表加载失败";
        }
		$this->_return($res_data);
	}
	//用户端-微卖-论坛详情接口
	public function postDetail(){
		$id = $this->_data['id'];//当前论坛的ID
		$userid = $this->_data['userid'];//当前发布的论坛用户的ID,不是登录的用户ID
		$loginUserid = $this->_data['loginUserid'];//当前登录的用户ID
		$pageNumber = $this->_data['pageNumber'];//当前第几页
        if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
		if (empty($userid)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "非法操作";
			$this->_return($res_data);
		}
		$model = M("Post");
		$map = array();
		$map['p.id'] = $id;
		// $map['userid'] = $userid;
		$map['p.status'] = 1;//审核状态(0_待审黑,1_通过审核,-1_审核未通过，默认为通过审核)
		$post = $model->field("u.pet_name,u.head_portrait,p.title,p.content,p.comment,p.images,p.browse_count,from_UNIXTIME(p.addtime,'%Y-%m-%d %H:%i') as addtime,h.number as address")->join("as p left join ".$this->_qz."users as u on u.id = ".$userid." left join ".$this->_qz."house as h on h.id = u.house_id")->where($map)->find();
		//图片转化成JSON数组
		if (empty($post)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "没有数据";
			$this->_return($res_data);
		}
		$images = $post['images'];
	    $images = json_decode($images,true);
	    $post['images'] = $images;
		$res_data['status'] = 1;
		$res_data['data'] = $post;
		$res_data['msg'] = "论坛详情加载成功";
		//收藏
		$map = array();
		$map['type'] = 1;//1_帖子，2_商品
		$map['userid'] = $loginUserid;
		$map['relationid'] = $id;
		$result = M("Collect")->where($map)->find();
		if ($result) {
			$res_data['data']['iscollect'] = 1;//已经收藏过
		}else{
			$res_data['data']['iscollect'] = 0;//没有收藏过
		}
		//论坛评论 begin
		$map_select['p.toppid'] = $id;
		$count = $model->count($mod_pk);
        $pagesize = 4;
        $pager = new \Think\Page($count,$pagesize);
		/* P代表下一级数据 */
		// 在前端展示这里的p用作正常显示的评论数据，p2用作被@的展示效果
		$replyList = $model->field("p.id as pid,u.head_portrait,u.pet_name,p.content,from_UNIXTIME(p.addtime,'%Y-%m-%d %H:%i') as addtime_p,ifnull(p2.id,'') p2id,ifnull(p2.content,'') content2,ifnull(from_UNIXTIME(p2.addtime,'%Y-%m-%d %H:%i'),'') as addtime_p2")->join(" as p left join xly_post as p2 on p.pid=p2.id left join ".$this->_qz."users as u on u.id = p.userid")->where($map_select)->order("p.addtime desc,p.id desc")->limit(($pageNumber-1)*4,$pager->listRows)->select();
		//帖子浏览数量+1
		$map_save['id'] = $id;
		$map_save['browse_count'] = array("exp","browse_count+1");
		$model->save($map_save);

		//论坛评论 end
		$res_data['data']['reply'] = $replyList;
		$this->_return($res_data);
	}
	//用户端-微卖-发布帖子接口
	public function publishPost(){
		$userid = $this->_data['userid'];//当前登录的用户的ID
		$title = $this->_data['title'];//帖子的标题
		$content = $this->_data['content'];//帖子的内容
		$images = $this->_data['images'];//上传的我图片(base64数组)
		if (empty($userid) || empty($title) || empty($content)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "请填写完整信息";
			$this->_return($res_data);
		}
		$map = array();
        $map['u.id'] = $userid;//
		$community = M("Users")->field("h.communityid")->join("as u left join ".$this->_qz."house as h on h.id = u.house_id")->find();
		if (empty($community)) {
			$res_data['status'] = 0;
			$res_data['msg'] = "数据错误";
			$this->_return($res_data);
		}
		$communityid = $community['communityid'];//找到当前登录用户的社区ID
		$map = array();
        $file_prefix = "img_";
		$img = $this->upload_images($images,$file_prefix);//接收前端base64的图片数组，返回的是url路径
		$baseimage = explode(",", $img);//保存为数组
		$base =json_encode($baseimage);//把刷组转换成成
		// $base = array();
		// foreach ($baseimage as $key=>$value) {
		// 	// $base= $value; 
		// 	$base = json_decode($value,true);
		// 	$base2[$key] = $base;
		// }
		// $images = $baseimage['images'];
		// $base = array_values($baseimage);
		// $this->_return($base);
		$addtime = time();//当前发布的时间
		$postdata['title'] = $title;
		$postdata['content'] = $content;
		$postdata['communityid'] = $communityid;
		$postdata['userid'] = $userid;
		$postdata['images'] = $base;
		$postdata['addtime'] = $addtime;
		$result = M("Post")->where($map)->add($postdata);
		if ($result) {
			$res_data['status'] = 1;
			$res_data['msg'] = "帖子发布成功";
		}else{
			$res_data['status'] = 0;
			$res_data['msg'] = "帖子发布失败";
		}
		$this->_return($res_data);
	}

	//用户端 回帖 (评论帖子) --- 对象是帖子
	public function replyPost(){
		$userid = $this->_data['userid'];//当前登录的用户的ID
		$postid = $this->_data['postid'];//要回复主贴帖子的ID
		$communityid = $this->_data['communityid'];//社区ID
		$content = $this->_data['content'];//内容

		if(empty($userid)||empty($postid)||empty($communityid)||empty($content)){
			$res_data['status'] = 0;
			$res_data['data'] = "";
			$res_data['msg'] = "非法操作";
			$this->_return($res_data);
		}

		//当前要回复的对象（主帖/评论/回复）
		$post_this = $this->_model->field("userid")->where("id=".$postid)->find();
		$post_userid = $post_this['userid'];

		//查看帖子是否存在评论 begin
		$map_p['toppid'] = $postid;//帖子id
		// $map_p['pid'] = array("exp"," is Null ");//pid为空的才是评论
		$map_p['pid'] = 0;
		$post = $this->_model->field("floor,sort")->order("id desc,addtime desc")->where($map_p)->find();
		//查看帖子是否存在评论 end
		$sort = 1; $floor = 1; $lastfloor = 1; $comment = 1;
		if(!empty($post)){
			if(!empty($post['sort'])){
				$sort = ($post['sort']+1);
			}
			if(!empty($post['floor'])){
				$floor = ($post['floor']+1);
				$lastfloor = $floor;//最后楼层等于当前评论的帖子楼层(最后楼层是记录在主帖里面的字段)
			}
		}

		$map['communityid'] = $communityid;
		$map['userid'] = $userid;//用户id(管理员帖时，为管理员id)
		// $map['pid'] = $postid;//回复的帖子 (主贴)帖子id
		$map['toppid'] = $postid;//(主贴)帖子id
		// $map['issystem'] = 0;//是否为管理员帖(0_否,1_是)
		// $map['istop'] = 0;//是否置顶(0_否,1_是)(暂时未用)
		// $map['iseite'] = 0;//是否精华(0_否,1_是)
		// $map['status'] = 1;//审核状态(0_待审黑,1_通过审核,-1_审核未通过，默认为通过审核)
		// $map['deletestatus'] = 0;//删除状态: 0_正常（默认）, 1_删除 
		$map['content'] = $content;//评论内容
		$map['sort'] = $sort;
		$map['floor'] = $floor;
		$map['addtime'] = time();
		$post_return_add = $this->_model->add($map);

		//处理主帖楼层、最后评论时间、评论数量等操作
		$map_save['id'] = $postid;
		$map_save['floor'] = $floor;
		$map_save['lastfloor'] = $lastfloor;
		$map_save['lastwritebacktime'] = time();
		$map_save['comment'] = array("exp","comment+1");
		$this->_model->save($map_save);

		//发送通知消息到相应的用户
		$map_save3['relationid'] = $postid;//关联的帖子/商品数据id
		$map_save3['replyid'] = $post_return_add;//关联的回复数据id
		$map_save3['type'] = 1;//类型: 1_帖子, 2_商品
		$map_save3['addtime'] = time();
		$information_return_save = M("Information2")->add($map_save3);
		$map_save4['userid'] = $post_userid;//消息关联用户id
		$map_save4['informationid'] = $information_return_save;//消息表id
		M("InformationRelation2")->add($map_save4);

		$res_data['status'] = 1;
		$res_data['data'] = "";
		$res_data['msg'] = "评论成功";
		$this->_return($res_data);
	}

	//回复 评论/回复 --- 对象是回复或评论
	public function replyComment(){
		$userid = $this->_data['userid'];//当前登录的用户的ID
		$postid = $this->_data['postid'];//要回复主贴帖子的ID
		$replyid = $this->_data['replyid'];//要回复的评论或回复的ID
		$communityid = $this->_data['communityid'];//社区ID
		$content = $this->_data['content'];//内容
		if(empty($userid)||empty($postid)||empty($replyid)||empty($communityid)||empty($content)){
			$res_data['status'] = 0;
			$res_data['data'] = "";
			$res_data['msg'] = "非法操作";
			$this->_return($res_data);
		}

		//当前要回复的对象（主帖/评论/回复）
		$post_this = $this->_model->field("userid")->where("id=".$replyid)->find();
		$post_userid = $post_this['userid'];

		$map['toppid'] = $postid;//(主贴)帖子ID
		$map['communityid'] = $communityid;
		$map['userid'] = $userid;//用户ID(管理员帖时，为管理员id)
		$map['pid'] = $replyid;//回复的帖子ID
		//
		$map['content'] = $content;//(主贴)帖子id
		$map['addtime'] = time();
		$post_return_add = $this->_model->add($map);

		//处理被回复数据的楼层、最后评论时间、评论数量等操作
		$map_save['id'] = $replyid;
		$map_save['comment'] = array("exp","comment+1");
		$this->_model->save($map_save);
		
		//处理主帖楼层、最后评论时间、评论数量等操作
		$map_save2['id'] = $postid;
		$map_save2['floor'] = $floor;
		$map_save2['lastfloor'] = $lastfloor;
		$map_save2['lastwritebacktime'] = time();
		$map_save2['comment'] = array("exp","comment+1");
		$this->_model->save($map_save2);

		//发送通知消息到相应的用户
		$map_save3['relationid'] = $postid;//关联的帖子/商品数据id
		$map_save3['replyid'] = $post_return_add;//关联的回复数据id
		$map_save3['type'] = 1;//类型: 1_帖子, 2_商品
		$map_save3['addtime'] = time();
		$information_return_save = M("Information2")->add($map_save3);
		$map_save4['userid'] = $post_userid;//消息关联用户id
		$map_save4['informationid'] = $information_return_save;//消息表id
		M("InformationRelation2")->add($map_save4);

		$res_data['status'] = 1;
		$res_data['data'] = "";
		$res_data['msg'] = "回复成功";
		$this->_return($res_data);
	}

	//(帖子)查看回复详情 对象是此回复的上下级
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
		$post_this = $this->_model->field("id,content,pid,ifnull(from_UNIXTIME(addtime,'%Y-%m-%d %H:%i'),'') as addtime")->where($map)->find();//找到当前数据
		$pid = $post_this['pid'];//根据当前数据的pid找上一级
		unset($post_this['pid']);
		$map_prev['id'] = $pid;
		$post_prev = $this->_model->field("id,content,ifnull(from_UNIXTIME(addtime,'%Y-%m-%d %H:%i'),'') as addtime")->where($map_prev)->find();//找到上一条数据
		// p($this->_model->getlastsql());
		// return;
		$map_next['pid'] = $id;//根据当前id找下一级
		$post_next = $this->_model->field("id,content,ifnull(from_UNIXTIME(addtime,'%Y-%m-%d %H:%i'),'') as addtime")->where($map_next)->select();//找到上一条数据

		$res_data['status'] = 1;
		$res_data['data']['prev'] = $post_prev;
		$res_data['data']['this'] = $post_this;
		$res_data['data']['next'] = $post_next;
		$res_data['msg'] = "";
		$this->_return($res_data);
	}

	//用户 举报帖子
	public function reportPost(){
		$id = $this->_data['id'];//举报对象id
		$content = $this->_data['content'];//举报内容
		if(empty($id)||empty($content)){
			$res_data['status'] = 0;
			$res_data['data'] = "";
			$res_data['msg'] = "非法操作";
			$this->_return($res_data);
		}

		$map_add['addtime'] = time();
		$map_add['type'] = 1;//举报对象类型: 1_帖子, 2_微淘(商品)
		$map_add['relationid'] = $id;
		$map_add['content'] = $content;
		$return_add = M("Report")->add($map_add);

		$res_data['status'] = 1;
		$res_data['data'] = "";
		$res_data['msg'] = "举报已提交";
		$this->_return($res_data);
	}

	//帖子评论列表查询 测试 test
	public function test(){
		/*p为下一级评论*/
		// select p.id as pid,p.content c1,p2.id p2id,p2.content c2 from xly_post as p left join xly_post as p2 on p.pid=p2.id where p.toppid=1
		$map_p['toppid'] = 1;
		$map_p['pid'] = array("exp"," is Null ");
		$post = $this->_model->field("id,floor,sort")->order("id desc,addtime desc")->where($map_p)->find();
		p($this->_model->getlastsql());
		return;
		$postid = 1;
		$post = M("Post")->field("*")->where("id=".$postid)->find();
		$postList = M("Post")->field("*")->join("")->where("topid=".$postid)->select();
	}
	//测试
	public function imagetest(){
		$url = $this->_data['url'];
		$baseimage = explode(",", $url);
		$res_data['data'] = $baseimage;
		$this->_return($res_data);
	}

}