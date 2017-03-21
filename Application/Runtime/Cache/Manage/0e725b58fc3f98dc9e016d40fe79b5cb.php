<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑社区</title>
    <link href="/wuyeguanli/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/style.css" rel="stylesheet">
<link href="/wuyeguanli/Public/system/css/common.css" rel="stylesheet">
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
                                	<input placeholder="请输入社区名称" name="name" class="form-control" type="text"  value="<?php echo ($info["name"]); ?>" required="true" > 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">搜索关键字：</label>
                                <div class="col-sm-8">
                                    <input placeholder="请输入搜索关键字" name="keyword" class="form-control" type="text"  value="<?php echo ($info["keyword"]); ?>" required="true" > 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">网站描述：</label>
                                <div class="col-sm-8">
                                    <input placeholder="请输入网站描述" name="description" class="form-control" type="text"  value="<?php echo ($info["description"]); ?>" required="true" > 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">催促维修等待时间间隔(分钟)：</label>
                                <div class="col-sm-8">
                                    <input placeholder="请输入催促维修等待时间间隔(分钟)" name="hie_interval" class="form-control" type="text"  value="<?php echo ($info["hie_interval"]); ?>" required="true" digits="true" > 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">网站邮件地址：</label>
                                <div class="col-sm-8">
                                    <input placeholder="请输入网站邮件地址" name="mail" class="form-control" type="text"  value="<?php echo ($info["mail"]); ?>" required="true" email="true" > 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">网站联系电话：</label>
                                <div class="col-sm-8">
                                    <input placeholder="请输入网站联系电话" name="phone" class="form-control" type="text"  value="<?php echo ($info["phone"]); ?>" required="true" > 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">用户默认头像：</label>
                                <div class="col-sm-8">
                                    <input type="file" name='default_headportrait' data-path='<?php echo ($info["default_headportrait"]); ?>' class="form-control" required="true">
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
	<script src="/wuyeguanli/Public/hplus/js/jquery.min.js?v=2.1.4" charset="UTF-8"></script>
<script src="/wuyeguanli/Public/hplus/js/bootstrap.min.js?v=3.3.6" charset="UTF-8"></script>
<script src="/wuyeguanli/Public/system/js/common.js" charset="UTF-8"></script>
<!-- 自定义js -->
<script src="/wuyeguanli/Public/hplus/js/content.js" charset="UTF-8"></script>




    <!-- jQuery Validation plugin javascript begin-->
    <script src="/wuyeguanli/Public/hplus/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="/wuyeguanli/Public/hplus/js/plugins/validate/messages_zh.min.js"></script>
    <script type="text/javascript">
    	$(function (){
    		//验证提示信息
	    	$("#mainForm").validate({
			    messages: {
			      name: {
			        required: "请输入社区名称"
			      },
			      keyword: {
			        required: "请输入搜索关键字"
			      },
                  hie_interval: {
                    required: "请输入催促维修等待时间间隔(分钟)",
                    digits: "时间(分钟)必须为数字"
                  },
                  mail: {
                    required: "请输入网站邮件地址",
                    email: "请注意邮件地址格式"
                  },
                  phone: {
                    required: "请输入网站联系电话"
                  },
                  default_headportrait: {
                    required: "请上传用户默认头像"
                  }
			    }
			});	
    	});
    </script>
    <!-- jQuery Validation plugin javascript end-->

    <!--弹出层组件 begin-->
    <link href="/wuyeguanli/Public/hplus/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="/wuyeguanli/Public/hplus/js/plugins/sweetalert/sweetalert.min.js"></script>
    <!--弹出层组件 end-->
    
    <!--jquery form begin-->
    <script type="text/javascript" src="/wuyeguanli/Public/system/js/jquery.form.js"></script>
    <!--jquery form end-->
    
    <!--layer begin-->
    <script src="/wuyeguanli/Public/hplus/js/plugins/layer/layer.min.js"></script>
    <!--layer end-->
    
    <script type="text/javascript" src="/wuyeguanli/Public/system/js/form-common.js"></script>
    
    <script type="text/javascript">
    	function btn_submit(){
    		$("#mainForm").submit();
    	}
    </script>  
    <!--iCheck begin-->
    <link href="/wuyeguanli/Public/hplus/css/plugins/iCheck/custom.css" rel="stylesheet">
    <script src="/wuyeguanli/Public/hplus/js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
    <!--iCheck end-->

    <!--上传图片 begin-->   
    <script src="/wuyeguanli/Public/hplus/js/plugins/prettyfile/bootstrap-prettyfile.js"></script>
    <script type="text/javascript">
        $('input[type="file"]').prettyFile({text:"选择图片",ext:"png,jpeg,jpg,gif",errorMsg:"类型错误"});
        $("#mainForm").validate({
            messages: {
              file_test: {
                required: "请输入上传图片"
              }
            }
        });
    </script>
    <!-- 上传图片 end-->
</body>
</html>