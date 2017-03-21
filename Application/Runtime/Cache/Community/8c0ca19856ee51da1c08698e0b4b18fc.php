<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>联盟微信</title>
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
                        <h5>联盟微信</h5>
                        <div class="ibox-tools">
                        </div>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal m-t" id="mainForm" data-NoClientValidate="1">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">微信号名称</label>
                                <div class="col-sm-8">
                                	<input placeholder="请输入微信号名称" name="name" class="form-control" type="text"  value="<?php echo ($info['name']); ?>" required="true" > 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">微信号</label>
                                <div class="col-sm-8">
                                    <input placeholder="请输入微信号" name="wxid" class="form-control" type="text" required="true" value="<?php echo ($info["wxid"]); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">属性</label>
                                <div class="col-sm-8">
                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" required="true" name="type" checked="" value="1" <?php if(($info["type"]) == "2"): ?>checked='checked'<?php endif; ?> > 学校
                                    </label>
                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" required="true" name="type" value="0" <?php if(($info["type"]) == "1"): ?>checked='checked'<?php endif; ?> > 学院
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">是否启用：</label>
                                <div class="col-sm-8">
                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" required="true" name="status" checked="" value="1" <?php if(($info["status"]) == "1"): ?>checked='checked'<?php endif; ?> > 启用
                                    </label>
                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" required="true" name="status" value="0" <?php if(($info["status"]) == "0"): ?>checked='checked'<?php endif; ?> > 不启用
                                    </label>
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
                  type: {
                    required: "请选择属性"
                  },
                  status: {
                    required: "请选择是否启用"
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
    <link href="/2017/weixiao/Public/system/css/chosen_style.css" rel="stylesheet">
    <script src="/2017/weixiao/Public/system/js/search.js"></script>
    <script type="text/javascript">
    $(function() {
        // ajax即时查询
        $(".showsel").bind('click',function(){
            clickShowSel(this);           
        });
    });
    </script>
    <!--即时请求‘下拉框’组件 end-->
</body>
</html>