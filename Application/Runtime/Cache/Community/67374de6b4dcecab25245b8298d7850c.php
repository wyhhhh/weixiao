<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑社区</title>
    <link href="/2017/weixiao/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/2017/weixiao/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/2017/weixiao/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/2017/weixiao/Public/hplus/css/style.css" rel="stylesheet">
<link href="/2017/weixiao/Public/system/css/common.css" rel="stylesheet">
</head>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>编辑社区</h5>
                        <div class="ibox-tools">
                        </div>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal m-t" id="mainForm" data-NoClientValidate="1">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">社区名称：</label>
                                <div class="col-sm-8">
                                	<input placeholder="请输入社区名称" name="name" class="form-control" type="text"  value="<?php echo ($info['name']); ?>" required="true" > 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">位置(省市区)：</label>
                                <div class="col-sm-8">
                                    <div class="chzn-container chzn-container-single" id="ddl1" data-url="<?php echo U('ajax_getAreaNew');?>" style="float:left;">
                                      <input type="hidden" class="area_data" name="town" value="<?php echo ($info["town"]); ?>" />
                                      <a tabindex="0" href="javascript:void(0);" class="showsel chzn-single chzn-single-with-drop" style="height:34px;">
                                        <?php if(($info["town"]) != ""): ?><span class="selItem" style=""><?php echo ($info["town"]); ?></span>
                                        <?php else: ?>
                                            <span class="selItem" style="">--请选择--</span><?php endif; ?>
                                        <span class="loading"></span>
                                      </a>
                                      <div class="chzn-drop detail" style="">
                                        <div class="chzn-search ">
                                          <input class="searchKey"  tabindex="-1" style="" type="text" oninput="propertychange(this)" onpropertychange="propertychange(this)" placeholder="请输入关键词查询"  />
                                        </div>
                                        <ul class="itemList chzn-results"></ul>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">详细地址：</label>
                                <div class="col-sm-8">
                                    <input placeholder="请输入社区位置" name="address" class="form-control" type="text" required="true" value="<?php echo ($info["address"]); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-3">
                                    <button class="btn btn-primary" type="button" onclick="btn_submit()">提交</button>
                                </div>
                            </div>
                            <input type="hidden" id="" name="id" value="<?php echo ($info["id"]); ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script src="/2017/weixiao/Public/hplus/js/jquery.min.js?v=2.1.4" charset="UTF-8"></script>
<script src="/2017/weixiao/Public/hplus/js/bootstrap.min.js?v=3.3.6" charset="UTF-8"></script>
<script src="/2017/weixiao/Public/system/js/common.js" charset="UTF-8"></script>

<!-- 自定义js -->
<script src="/2017/weixiao/Public/hplus/js/content.js" charset="UTF-8"></script>




    <!-- jQuery Validation plugin javascript begin-->
    <script src="/2017/weixiao/Public/hplus/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="/2017/weixiao/Public/hplus/js/plugins/validate/messages_zh.min.js"></script>
    <script type="text/javascript">
    	$(function (){
    		//验证提示信息
	    	$("#mainForm").validate({
			    messages: {
			      name: {
			        required: "请输入社区名称"
			      },
			      town: {
			        required: "请输入社区位置(省市区)"
			      },
                  address: {
                    required: "请输入社区详细地址"
                  }
			    }
			});	
    	});
    </script>
    <!-- jQuery Validation plugin javascript end-->

    <!--弹出层组件 begin-->
    <link href="/2017/weixiao/Public/hplus/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="/2017/weixiao/Public/hplus/js/plugins/sweetalert/sweetalert.min.js"></script>
    <!--弹出层组件 end-->
    
    <!--jquery form begin-->
    <script type="text/javascript" src="/2017/weixiao/Public/system/js/jquery.form.js"></script>
    <!--jquery form end-->
    
    <!--layer begin-->
    <script src="/2017/weixiao/Public/hplus/js/plugins/layer/layer.min.js"></script>
    <!--layer end-->
    
    <script type="text/javascript" src="/2017/weixiao/Public/system/js/form-common.js"></script>
    
    <script type="text/javascript">
    	function btn_submit(){
    		$("#mainForm").submit();
    	}
    </script>  
    <!--iCheck begin-->
    <link href="/2017/weixiao/Public/hplus/css/plugins/iCheck/custom.css" rel="stylesheet">
    <script src="/2017/weixiao/Public/hplus/js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
    <!--iCheck end-->

    <!--即时请求‘下拉框’组件 begin-->
    <link href="/2017/weixiao/Public/hplus/css/plugins/chosen/chosen_change/chosen.css" rel="stylesheet">
    <!-- ajax下拉样式强制 begin -->
    <style type="text/css">
        .chzn-container .showsel {
            width:290px;
            float:left;
            display:block;
            height:28px;
            border-radius:0px;
            border:1px #d5d5d5 solid;
            background-image:-webkit-gradient(linear,0 0,0 100%,color-stop(20%,#ffffff),color-stop(50%,#ffffff),color-stop(52%,#ffffff),color-stop(100%,#ffffff));
        }
        .chzn-container .selItem{
            float:left;
            font-size:12px;
            color:#000;
            line-height:30px;
        }
        .chzn-container .loading {
            display:block;
            float:right;
        }
        .chzn-container .detail {
            border: 1px #d5d5d5 solid;
            border-top: none;
            width:290px;
            top:24px;
            display:none;
            padding-right: 6px;
        }
        .searchKey{
            width: 268px;
        }
    </style>
    <!-- ajax下拉样式强制 end -->
    <!-- 下拉自行封装js begin -->
    <script src="/2017/weixiao/Public/Common/search.js"></script>
    <script type="text/javascript">
    $(function() {
        // ajax即时查询
        $(".showsel").bind('click',function(){
            clickShowSel(this);           
        });
    });
    </script>
    <!-- 下拉自行封装js end -->
    <!--即时请求‘下拉框’组件 end-->
</body>
</html>