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
                        <form method="post"  class="form-horizontal m-t" id="mainForm" data-NoClientValidate="1">
                            </center>                    
                            <div class="form-group">
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">楼栋简称(栋/動)</label>
                                <div class="col-sm-8">
                                    <input placeholder="请输入楼栋简称" name="name" class="form-control" type="text"  value="<?php echo ($info["name"]); ?>" required="true" > 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">楼栋名称</label>
                                <div class="col-sm-8">
                                    <input placeholder="请输入楼栋名称" name="alias_name" class="form-control" type="text"  value="<?php echo ($info["alias_name"]); ?>" required="true" > 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">物管费单价</label>
                                <div class="col-sm-8">
                                    <input placeholder="输入物管费单价" name="unit_price" class="form-control" type="text" value="<?php echo ($info["unit_price"]); ?>" required="true" >
                                </div>
                            </div>
<!--                             <div class="form-group">
                                <label class="col-sm-3 control-label">单元</label>
                                <div class="col-sm-8">
                                    <input placeholder="分别输入单元名称(用,)分割" name="dy" class="form-control" type="text" value="<?php echo ($info["dy"]); ?>" required="true" >
                                </div>
                            </div>
                           <div class="form-group">
                                <label class="col-sm-3 control-label">楼层数</label>
                                <div class="col-sm-8">
                                    <input placeholder="输入该楼栋楼层数" name="lc" class="form-control" type="text" value="<?php echo ($info["lc"]); ?>" required="true" >
                                </div>
                            </div>
                           <div class="form-group">
                                <label class="col-sm-3 control-label">层房间数</label>
                                <div class="col-sm-8">
                                    <input placeholder="输入每层房间数" name="fj" class="form-control" type="text" value="<?php echo ($info["fj"]); ?>" required="true" >
                                </div>
                            </div> -->
<!--                             <div class="form-group">
                                <label class="col-sm-3 control-label">是否启用：</label>
                                <div class="col-sm-8">
                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" required="true" name="status" checked="" value="1" > 启用
                                    </label>
                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" required="true" name="status" value="0" > 不启用
                                    </label>
                                </div>
                            </div> -->
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