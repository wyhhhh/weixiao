<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑福利</title>
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
                        <h5>编辑福利</h5>
                        <div class="ibox-tools">
                        </div>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal m-t" id="mainForm" data-NoClientValidate="1">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">福利标题：</label>
                                <div class="col-sm-8">
                                    <input placeholder="请输入福利标题" name="title" class="form-control" type="text"  required="true" value="<?php echo ($info["title"]); ?>" > 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">福利内容：</label>
                                <div class="col-sm-8">
                                    <div id="content" class="summernote"><?php echo (htmlspecialchars_decode($info["content"])); ?></div>
                                    <input type="hidden" id="hid_content" name="content" value="<?php echo ($info['content']); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">显示状态：</label>
                                <div class="col-sm-8">
                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" required="true" name="isshow" checked="" value="1" <?php if(($info["isshow"]) == "1"): ?>checked='checked'<?php endif; ?> > 显示
                                    </label>
                                    <label class="checkbox-inline i-checks">
                                        <input type="radio" required="true" name="isshow" value="0" <?php if(($info["isshow"]) == "0"): ?>checked='checked'<?php endif; ?> > 不显示
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">排序</label>
                                <div class="col-sm-8">
                                    <input placeholder="请输入排序" name="sort" class="form-control" type="text" required="true" number='true' value="<?php echo ($info["sort"]); ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">选择社区发布范围：</label>
                                <div class="col-sm-8">
                                    <div id="jstree"></div>
                                    <input type="hidden" name="communityids" id="hid_communityids" />
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
                  title: {
                    required: "请输入福利标题"
                  },
                  sort: {
                    required: "请输入排序",
                    number: "排序必须为数字"
                  },
                  isshow: {
                    required: "请选择是否显示福利",
                    number: "请选择是否显示"
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
    
    <!-- jsTree plugin javascript begin-->
    <link href="/wuyeguanli/Public/hplus/css/plugins/jsTree/style.min.css" rel="stylesheet">
    <script src="/wuyeguanli/Public/hplus/js/plugins/jsTree/jstree.min.js"></script>
    <script type="text/javascript">
        $(function (){
            $('#jstree').jstree({
                "checkbox" : {
                    "keep_selected_style" : false
                },
                'plugins':["checkbox"],
                'core': 
                {
                    'data' : <?php echo ($navigations); ?>
                }
            });
             
        });
        function btn_submit(){
            var ids = $('#jstree').jstree().get_checked();
            $("#hid_communityids").val(ids);//树 选择处理
            $("#hid_content").val($("#content").code());//编辑器内容处理
            $("#mainForm").submit();
        }
    </script>

    <!--编辑器 begin-->
    <link href="/wuyeguanli/Public/hplus/css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="/wuyeguanli/Public/hplus/css/plugins/summernote/summernote-bs3.css" rel="stylesheet">
    <script src="/wuyeguanli/Public/hplus/js/plugins/summernote/summernote.min.js"></script>
    <script src="/wuyeguanli/Public/hplus/js/plugins/summernote/summernote-zh-CN.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.summernote').summernote({
                lang: 'zh-CN'
            });
        });
    </script>
    <!--编辑器 end-->

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