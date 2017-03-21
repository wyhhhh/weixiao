<?php
namespace AppUser\Controller;
use AppUser\Common\AppUserController;

class WelfareController extends AppUserController {

    function _initialize(){
		parent::_initialize();
		$this->_model = M("Welfare");
	}

    //福利列表
    public function welfareList(){
        $communityid = $this->_data['communityid'];//当前用户的登录的社区ID
        $pageNumber = $this->_data['pageNumber'];//当前第几页
        if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
        if (empty($communityid)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
    	$count = $this->_model->count($mod_pk);
        $pagesize = 4;
        $pager = new \Think\Page($count,$pagesize);
        // $map['communityids'] = array("exp","");
        // $map['isshow'] = 1;//isshow 是否显示: 0_不显示, 1_显示（默认）
        $strWhere = "1=1 and isshow=1 and find_in_set('".$communityid."',communityids)";
    	$welfareList = $this->_model->field("id,title,content,addtime,images,browse_count,praise_count,234 as commentnum")->where($strWhere)->order("addtime desc")->limit(($pageNumber-1)*4,$pager->listRows)->select();
        if (empty($welfareList)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        foreach ($welfareList as $key => $value) {
            $images = $value['images'];
            $images = json_decode($images,true);
            $welfareList[$key]['images'] = $images;
        }
    	$res_data['status'] = 1;
    	$res_data['msg'] = "列表加载成功";
    	$res_data['data'] = $welfareList;
        
    	$this->_return($res_data);
    }

    //福利详情页面
    public function welfareDetail(){
    	$welfareid = $this->_data['welfareid'];//福利id
        $userid = $this->_data['userid'];//当前登录的用户的ID
        $pageNumber = $this->_data['pageNumber'];//当前第几页
        if (empty($pageNumber) || $pageNumber == 0) {
            $pageNumber = 1;
        }
        $map['id'] = $welfareid;
        $map['isshow'] = 1;//isshow 是否显示: 0_不显示, 1_显示（默认）
    	$welfare = $this->_model->field("id,title,content,images,praise_count,23 as commentnum,from_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime")->where($map)->find();
        if (empty($welfare)) {
            $res_data['status'] = 0;
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }
        //处理图片 start
        $images = $welfare['images'];
        $images = json_decode($images,true);
        $welfare['images'] = $images;
        //处理图片 end

        $res_data['status'] = 1;
        $res_data['msg'] = "详情加载成功";
        $res_data['data'] = $welfare;
        $map = array();//判断是否已赞
        $map['type'] = 1;
        $map['praiseid'] = $welfareid;
        $map['userid'] = $userid;
        $result = M("PraiseLog")->field("id")->where($map)->find();
        if (!empty($result['id'])) {
            $res_data['data']['ispraise'] = 1;//1_已赞，0_未赞
        }else{
            $res_data['data']['ispraise'] = 0;//1_已赞，0_未赞
        }
        //浏览数据+1
        $map = array();
        $map['id'] = $welfareid;
        $map['isshow'] = 1;//isshow 是否显示: 0_不显示, 1_显示（默认）
        $browsedata['browse_count'] = array("exp","browse_count+1");//浏览次数+1
        $browse = $this->_model->where($map)->save($browsedata);

        //福利评论 begin
        $map_select['w.toppid'] = $welfareid;
        $count = M("Goods")->count($mod_pk);
        $pagesize = 4;
        $pager = new \Think\Page($count,$pagesize);
        /* P代表下一级数据 */
        // 在前端展示这里的p用作正常显示的评论数据，p2用作被@的展示效果
        $replyList = M("Welfare")->field("w.id as gid,u.head_portrait,u.pet_name,w.content,from_UNIXTIME(w.addtime,'%Y-%m-%d %H:%i') as addtime_g,ifnull(w2.id,'') g2id,ifnull(w2.content,'') description2,ifnull(from_UNIXTIME(w2.addtime,'%Y-%m-%d %H:%i'),'') as addtime_g2")->join(" as w left join ".$this->_qz."welfare as w2 on w.pid=w2.id left join ".$this->_qz."users as u on u.id = w.userid")->where($map_select)->order("w.addtime desc,w.id desc")->limit(($pageNumber-1)*4,$pager->listRows)->select();
        //帖子浏览数量+1
        $map_save['id'] = $welfareid;
        $map_save['browse_count'] = array("exp","browse_count+1");
        M("Goods")->save($map_save);
        //福利评论 end
        $res_data['data']['reply'] = $replyList;

    	$this->_return($res_data);
    }
    //福利详情页-点赞接口
    public function getPraise(){
        $userid = $this->_data['userid'];//当前登录的用户
        $welfareid = $this->_data['welfareid'];//福利id
        $type = 1;//对应类型: 1_福利(广告), 2_新闻,3_福利
        $map = array();
        $map['userid'] = $userid;
        $map['praiseid'] = $welfareid;
        $map['type'] = $type;
        $result = M("PraiseLog")->field("addtime")->where($map)->find();
        if (!empty($result['addtime'])) {
            $res_data['status'] = 0;
            $res_data['data']['ispraise'] = 1;//1_已赞，0_未赞
            $res_data['msg'] = "你已经赞过了";
            $this->_return($res_data);
        }else{
            $map = array();
            $map['id'] = $welfareid;
            $map['isshow'] = 1;// 是否显示: 0_不显示, 1_显示（默认）
            $price['praise_count'] = array('exp','praise_count+1');//点赞+1
            $result = $this->_model->where($map)->save($price);
            $map = array();//点赞记录
            $addtime = time();
            $pricelog['type'] = 1;
            $pricelog['userid'] = $userid;//点赞用户ID
            $pricelog['praiseid'] = $welfareid;//点赞对象表ID
            $pricelog['addtime'] = $addtime;
            $resultlog = M("PraiseLog")->where($map)->add($pricelog);
            $res_data['status'] = 1;
            $res_data['msg'] = "点赞成功";
        }
        $this->_return($res_data);
    }


    //用户端 评论福利 --- 对象是福利
    public function commentWelfare(){
        $userid = $this->_data['userid'];//当前登录的用户的ID
        $welfareid = $this->_data['welfareid'];//要回复福利的ID
        $communityid = $this->_data['communityid'];//社区ID
        $content = $this->_data['content'];//内容

        if(empty($userid)||empty($welfareid)||empty($communityid)||empty($content)){
            $res_data['status'] = 0;
            $res_data['data'] = "";
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }

        //查看福利是否存在评论 begin
        $map_w['toppid'] = $welfareid;//福利id
        $map_w['pid'] = array("exp"," is Null ");//pid为空的才是评论
        $welfare = $this->_model->field("floor,sort")->order("id desc,addtime desc")->where($map_w)->find();
        //查看福利是否存在评论 end
        $sort = 1; $floor = 1; $lastfloor = 1; $comment = 1;
        if(!empty($welfare)){
            if(!empty($welfare['sort'])){
                $sort = ($welfare['sort']+1);
            }
            if(!empty($welfare['floor'])){
                $floor = ($welfare['floor']+1);
                $lastfloor = $floor;//最后楼层等于当前评论的福利楼层(最后楼层是记录在福利里面的字段)
            }
        }

        $map['communityid'] = $communityid;
        $map['userid'] = $userid;//用户id(管理员帖时，为管理员id)
        $map['toppid'] = $welfareid;//(主贴)福利id
        $map['content'] = $content;//评论内容
        $map['sort'] = $sort;
        $map['floor'] = $floor;
        $map['addtime'] = time();
        $welfare_return_add = $this->_model->add($map);

        //处理主帖楼层、最后评论时间、评论数量等操作
        $map_save['id'] = $welfareid;
        $map_save['floor'] = $floor;
        $map_save['lastfloor'] = $lastfloor;
        $map_save['lastwritebacktime'] = time();
        $map_save['comment'] = array("exp","comment+1");
        $this->_model->save($map_save);

        $res_data['status'] = 1;
        $res_data['data'] = "";
        $res_data['msg'] = "评论成功";
        $this->_return($res_data);
    }

    //回复 评论/回复 --- 对象是福利回复或评论
    public function replyComment(){
        $userid = $this->_data['userid'];//当前登录的用户的ID
        $welfareid = $this->_data['welfareid'];//要回复福利的ID
        $replyid = $this->_data['replyid'];//要回复的评论或回复的ID
        $communityid = $this->_data['communityid'];//社区ID
        $content = $this->_data['content'];//内容
        if(empty($userid)||empty($welfareid)||empty($replyid)||empty($communityid)||empty($content)){
            $res_data['status'] = 0;
            $res_data['data'] = "";
            $res_data['msg'] = "非法操作";
            $this->_return($res_data);
        }

        $map['communityid'] = $communityid;
        $map['userid'] = $userid;//用户ID(管理员帖时，为管理员id)
        $map['pid'] = $replyid;//回复的福利ID
        $map['toppid'] = $welfareid;//(主贴)福利ID
        //
        $map['content'] = $content;//(主贴)福利id
        $map['addtime'] = time();
        $welfare_return_add = $this->_model->add($map);

        //处理被回复数据的楼层、最后评论时间、评论数量等操作
        $map_save['id'] = $replyid;
        $map_save['comment'] = array("exp","comment+1");
        $this->_model->save($map_save);
        
        //处理主帖楼层、最后评论时间、评论数量等操作
        $map_save2['id'] = $welfareid;
        $map_save2['floor'] = $floor;
        $map_save2['lastfloor'] = $lastfloor;
        $map_save2['lastwritebacktime'] = time();
        $map_save2['comment'] = array("exp","comment+1");
        $this->_model->save($map_save2);

        $res_data['status'] = 1;
        $res_data['data'] = "";
        $res_data['msg'] = "回复成功";
        $this->_return($res_data);
    }

    //(福利)查看回复详情 对象是此回复的上下级
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
        $welfare_this = $this->_model->field("id,content,pid,ifnull(from_UNIXTIME(addtime,'%Y-%m-%d %H:%i'),'') as addtime")->where($map)->find();//找到当前数据
        $pid = $welfare_this['pid'];//根据当前数据的pid找上一级
        unset($welfare_this['pid']);
        $map_prev['id'] = $pid;
        $welfare_prev = $this->_model->field("id,content,ifnull(from_UNIXTIME(addtime,'%Y-%m-%d %H:%i'),'') as addtime")->where($map_prev)->find();//找到上一条数据
        // p($this->_model->getlastsql());
        // return;
        $map_next['pid'] = $id;//根据当前id找下一级
        $welfare_next = $this->_model->field("id,content,ifnull(from_UNIXTIME(addtime,'%Y-%m-%d %H:%i'),'') as addtime")->where($map_next)->select();//找到上一条数据

        $res_data['status'] = 1;
        $res_data['data']['prev'] = $welfare_prev;
        $res_data['data']['this'] = $welfare_this;
        $res_data['data']['next'] = $welfare_next;
        $res_data['msg'] = "";
        $this->_return($res_data);
    }


}