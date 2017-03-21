<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>解封</title>
    <link href="/wuyeguanli/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/style.css" rel="stylesheet">
<link href="/wuyeguanli/Public/system/css/common.css" rel="stylesheet">
</head>
<body class="gray-bg">
    <!-- <div class="modal-dialog"> -->
    <!-- <div class="modal-content animated bounceInRight"> -->
    <div class="inmodal" style="background-color:#fff;">
      <div class="modal-header">
        <form method="post" class="form-horizontal m-t" id="mainForm" data-NoClientValidate="1" >
        <h4 class="modal-title">解封</h4>
          <div class="modal-body">
            <div class="form-group">
              <!-- <label>解封原因</label> -->
              <input type="text" name="reason" placeholder="请输入解封原因" class="form-control" required="true">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
            <button type="button" class="btn btn-primary" onclick="btn_submit()">保存</button></div>
            <input type="hidden" id="" name="id" value="<?php echo ($info["id"]); ?>" />
        </form>
      </div>
    </div>
    <!-- </div> -->
    <!-- </div> -->

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
                  reason: {
                    required: "请输入解封原因"
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

            //关闭窗口按钮触发
            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
            $(".btn-white").on("click",function(){
                parent.layer.close(index);
            });
        });
    </script>
    <!--iCheck end-->

</body>
</html>