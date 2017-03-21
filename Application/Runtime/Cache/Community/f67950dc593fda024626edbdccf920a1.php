<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>发送通知</title>
    <link href="/wuyeguanli/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/style.css" rel="stylesheet">
<link href="/wuyeguanli/Public/system/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/wuyeguanli/Public/jquery.alerts.js"></script>
    <script type="text/javascript" src="/wuyeguanli/Public/jQuery.FillOptions.js"></script>
</head>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>发送通知</h5>
                        <div class="ibox-tools">
                        </div>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal m-t" id="mainForm" data-NoClientValidate="1">
<!--                             <div class="form-group">
                                <label class="col-sm-3 control-label">楼栋</label>
                                <div class="col-sm-8">
                                    <select style="width: 150px;" class="form-control" name="communityid" id="communityid" required="true">
                                        <option selected="" value="">选择楼栋</option>
                                        <?php if(is_array($rolelists)): $i = 0; $__LIST__ = $rolelists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vols): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vols["id"]); ?>" <?php if(($vols["id"]) == $info["communityid"]): ?>selected='selected'<?php endif; ?> ><?php echo ($vols["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">标题</label>
                                <div class="col-sm-8">
                                    <input placeholder="请输入标题" name="title" class="form-control" type="text" required="true"value="<?php echo ($info["title"]); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">内容</label>
                                <div class="col-sm-8">
                                    <input placeholder="请输入内容" name="content" class="form-control" type="text" required="true"value="<?php echo ($info["content"]); ?>">
                                </div>
                            </div>
                            <form name="f1">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">楼栋</label>
                                <div class="col-sm-8">
                            <select  style="width: 150px;" class="form-control" name="buildingid" id="buildingid" required="true" onchange="sel(this.value, 1)">
                                        <option selected="" value="">选择楼栋</option>
                                        <?php if(is_array($rolelists)): $i = 0; $__LIST__ = $rolelists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vols): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vols["id"]); ?>" <?php if(($vols["id"]) == $info["buildingid"]): ?>selected='selected'<?php endif; ?> ><?php echo ($vols["alias_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                                </div>
                            </div>
                                <div class="form-group">
                                <label class="col-sm-3 control-label">单元</label>
                                <div class="col-sm-8">
                            <select  style="width: 150px;" class="form-control"name="unitid" id="sel2" required="true" onchange="sel(this.value, 2)">   
                            <option value="0">单元</option>  
                            </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">楼层</label>
                                <div class="col-sm-8">
                            <select  style="width: 150px;" class="form-control"name="fioorid" id="sel3" required="true" onchange="sel(this.value, 3)">
                            <option value="0">楼层</option>
                            </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">房号</label>
                                <div class="col-sm-8">
                            <select  style="width: 150px;" class="form-control"name="houseid" id="sel4" required="true" onchange="sel(this.value, 4)">
                            <option value="0">房号</option>
                            </select>
                                </div>
                            </div>
                            </form> 
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-3">
                                    <button class="btn btn-primary" type="button" onclick="btn_submit()">提交</button>
                                </div>
                            </div>
                            <input type="hidden" id="" name="id" value="<?php echo ($info['id']); ?>" />
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
                    required: "请输入标题"
                  },
                  content: {
                    required: "请输入内容"
                  },
                  buildingid: {
                    required: "请选择楼栋"
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

    <!--下拉框组件 begin-->
    <link href="/wuyeguanli/Public/hplus/css/plugins/chosen/chosen_change/chosen.css" rel="stylesheet">
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
    <script src="/wuyeguanli/Public/Common/search.js"></script>
    <script type="text/javascript">
    $(function() {
        // ajax即时查询
        $(".showsel").bind('click',function(){
            clickShowSel(this);           
        });
    });
    </script>
<script type="text/javascript">
var data = [];
function sel(v, c){
    $.get('<?php echo U("add111");?>', { parent: v, type: c },function(ret){
        if(ret.success==true){
            var list = ret.list;
            var selobj = document.getElementById('sel'+(c+1));
            for (i=1;i<list.length;i++){
                selobj.options[i] = new Option(list[i].name,list[i].id);
            }
        }
    },'json');
 
}
</script>
    <!-- 下拉自行封装js end -->
    <!--下拉框组件 end-->
</body>
</html>