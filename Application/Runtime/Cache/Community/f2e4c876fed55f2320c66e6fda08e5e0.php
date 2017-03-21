<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>推文管理</title>
    <link href="/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/Public/hplus/css/style.css" rel="stylesheet">
<link href="/Public/system/css/common.css" rel="stylesheet">
</head>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>推文管理</h5>
                        <div class="ibox-tools">
                        </div>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal m-t" id="mainForm" data-NoClientValidate="1">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">分类</label>
                                <div class="col-sm-8">
                                    <select style="width: 150px;" class="form-control" name="typeid" id="typeid" required="true">
                                        <option selected="" value="">请选择类别</option>
                                        <?php if(is_array($rolelist)): $i = 0; $__LIST__ = $rolelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vol["id"]); ?>" <?php if(($vol["id"]) == $info["typeid"]): ?>selected='selected'<?php endif; ?> ><?php echo ($vol["name"]); ?>-<?php echo ($vol["names"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
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
    <script src="/Public/hplus/js/jquery.min.js?v=2.1.4" charset="UTF-8"></script>
<script src="/Public/hplus/js/bootstrap.min.js?v=3.3.6" charset="UTF-8"></script>
<script src="/Public/system/js/common.js" charset="UTF-8"></script>

<!-- 自定义js -->
<script src="/Public/hplus/js/content.js" charset="UTF-8"></script>




    <!-- jQuery Validation plugin javascript begin-->
    <script src="/Public/hplus/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="/Public/hplus/js/plugins/validate/messages_zh.min.js"></script>
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
                  status: {
                    required: "请选择是否启用此管理员"
                  }
                }
            }); 
        });
    </script>
    <!-- jQuery Validation plugin javascript end-->

    <!--弹出层组件 begin-->
    <link href="/Public/hplus/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="/Public/hplus/js/plugins/sweetalert/sweetalert.min.js"></script>
    <!--弹出层组件 end-->
    
    <!--jquery form begin-->
    <script type="text/javascript" src="/Public/system/js/jquery.form.js"></script>
    <!--jquery form end-->
    
    <!--layer begin-->
    <script src="/Public/hplus/js/plugins/layer/layer.min.js"></script>
    <!--layer end-->
    
    <script type="text/javascript" src="/Public/system/js/form-common.js"></script>
    
    <script type="text/javascript">
        function btn_submit(){
            $("#mainForm").submit();
        }
    </script>  
    <!--iCheck begin-->
    <link href="/Public/hplus/css/plugins/iCheck/custom.css" rel="stylesheet">
    <script src="/Public/hplus/js/plugins/iCheck/icheck.min.js"></script>
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