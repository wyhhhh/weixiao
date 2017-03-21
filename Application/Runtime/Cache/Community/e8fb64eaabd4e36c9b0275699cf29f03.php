<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>楼栋手工添加/Excel批量录入</title>
    <link href="/wuyeguanli/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/style.css" rel="stylesheet">
<link href="/wuyeguanli/Public/system/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/wuyeguanli/Public/system/js/common.js"></script>
    <style type="text/css"> 
    .divcss5{
        font-size:25px;
        margin : 0px 0px 12px 0px;
    }
    .divcss3{
        font-size:25px;
        margin : 0px 0px 7px 0px;
    }
    .divsss{
        margin : 0px 0px 10px 0px;
    }.divsss2{
        margin : 0px -60px 5px 0px;
    }
    </style> 
</head>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>楼栋添加</h5>
                        <div class="ibox-tools">
                        </div>
                    </div>
                    <div class="ibox-content">
                            <!-- 批量添加 -->
                        <form action="<?php echo U('Building/uploads');?>" method="POST" id="mainForm" enctype="multipart/form-data">
                            <center>     <label  class="divcss5">Excel批量楼栋录入 </label>                            
                                    <input type="hidden" class="divsss2"name="MAX_FILE_SIZE" value="1000000">
                                    <input type="file"  class="divsss2" name="myfile">
                            <input  type="submit" onclick="btn_submit()" value="上传Excel" class="btn btn-primary divsss">                                 
                            </center>
                           <center>
                           <a href="<?php echo U('Building/download');?>"> <button type="button" class="btn btn-outline btn-default"  data-uri="<?php echo U('Building/download');?>"><a href="<?php echo U('Building/download');?>">下载格式Excel
                           <i class="glyphicon glyphicon-plus " aria-hidden="true"></i></button>   </a>
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
                  roleid: {
                    required: "请选择管理员角色"
                  },
                  username: {
                    required: "请输入管理员名称"
                  },
                  password: {
                    required: "请为此管理员设定登录密码"
                  },
                  status: {
                    required: "请选择是否启用此管理员"
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

</body>
</html>