<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>推文管理</title>
		<meta name="keywords" content="推文管理">
		<meta name="推文管理">
		<link href="/2017/weixiao/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/2017/weixiao/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/2017/weixiao/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/2017/weixiao/Public/hplus/css/style.css" rel="stylesheet">
<link href="/2017/weixiao/Public/system/css/common.css" rel="stylesheet">
	</head>
	<body class="gray-bg">
		<div class="wrapper wrapper-content animated">
			<div class="ibox float-e-margins">
				<!-- Panel Style -->
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>推文管理</h5>
						<div class="ibox-tools">
							<a class="collapse-link">
								<i class="fa fa-chevron-up"></i>
							</a>
							<a class="close-link">
								<i class="fa fa-times"></i>
							</a>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row row-lg">
							<div class="col-sm-12 me-search" >
	                                <input id="search_name"tyle="width: 200px;" placeholder="请输入推文名称" type="text"  class="form-control" >
                                    <select style="width: 150px;" class="form-control" name="typeid" id="typeid" required="true">
                                        <option selected="" value="">请选择类别</option>
                                        <?php if(is_array($rolelist)): $i = 0; $__LIST__ = $rolelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vol["id"]); ?>" <?php if(($vol["id"]) == $info["typeid"]): ?>selected='selected'<?php endif; ?> ><?php echo ($vol["name"]); ?>-<?php echo ($vol["names"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
									<select style="width: 150px;" class="form-control" id = "year">
	                                	<option selected="" value="">选择年份</option>
                                        <option value="2017">2017</option>
                                        <option value="2016">2016</option>
                                    </select>
									<select style="width: 150px;" class="form-control" id = "mouth">
	                                	<option selected="" value="">选择月份</option>
                                        <option value="01">1月</option>
                                        <option value="02">2月</option>
                                        <option value="03">3月</option>
                                        <option value="04">4月</option>
                                        <option value="05">5月</option>
                                        <option value="06">6月</option>
                                        <option value="07">7月</option>
                                        <option value="08">8月</option>
										<option value="09">9月</option>
										<option value="10">10月</option>
										<option value="11">11月</option>
										<option value="12">12月</option>
                                    </select>
<!--                                     ~
									<select style="width: 150px;" class="form-control" id = "mouth1">
	                                	<option selected="" value="">选择月份</option>
                                        <option value="01">1月</option>
                                        <option value="02">2月</option>
                                        <option value="03">3月</option>
                                        <option value="04">4月</option>
                                        <option value="05">5月</option>
                                        <option value="06">6月</option>
                                        <option value="07">7月</option>
                                        <option value="08">8月</option>
										<option value="09">9月</option>
										<option value="10">10月</option>
										<option value="11">11月</option>
										<option value="12">12月</option>
                                    </select> -->
	                                <button onclick="searchSubmit()" type="button" id="search_btn" class="btn btn-w-s btn-primary">搜索</button>
							</div>
						</div>
						<div class="row row-lg">
							<div class="col-sm-12">
								<div class="example-wrap">
									<div class="example">
										<div class="btn-group hidden-xs" id="table-toolbar" role="group">
											<button type="button" class="btn btn-outline btn-default J_sendpost " data-uri='http://weixiaocqupt.cn/old/weixin.php' data-title="添加" data-width="1000" data-height="800">
		                                        <i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
		                                    </button>
<!-- 		                                    <button type="button" class="btn btn-outline btn-default J_batchDelete"  data-uri="<?php echo U('ajax_delete');?>">
		                                        <i class="glyphicon glyphicon-trash" aria-hidden="true"></i>
		                                    </button> -->
		                                    <button type="button" class="btn btn-outline btn-default J_showedit" data-uri='<?php echo U("editall");?>' data-title="批量分组" data-width="1000" data-height="800" data-full="1">批量分组
		                                    </button>
<!-- 		                                    <button type="button" class="btn btn-outline btn-default J_bat"  onclick="fun1()" data-uri="<?php echo U('ajax_gai');?>">
		                                        批量分类
		                                    </button> -->
										</div>
										<table id="tablelist" class="table table-hover table-condensed">
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End Panel Style -->
			</div>
		</div>
		<!-- 全局js -->
		<script src="/2017/weixiao/Public/hplus/js/jquery.min.js?v=2.1.4" charset="UTF-8"></script>
<script src="/2017/weixiao/Public/hplus/js/bootstrap.min.js?v=3.3.6" charset="UTF-8"></script>
<script src="/2017/weixiao/Public/system/js/common.js" charset="UTF-8"></script>

<!-- 自定义js -->
<script src="/2017/weixiao/Public/hplus/js/content.js" charset="UTF-8"></script>




		<!-- Bootstrap table begin-->
		<link href="/2017/weixiao/Public/hplus/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
		<script src="/2017/weixiao/Public/system/js/bootstrap-table.config.js"></script>
		<script src="/2017/weixiao/Public/hplus/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
		<script src="/2017/weixiao/Public/hplus/js/plugins/bootstrap-table/bootstrap-table-mobile.min.js"></script>
		<script src="/2017/weixiao/Public/hplus/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
		<!-- Bootstrap table end-->

		<!--layer begin-->
		<script src="/2017/weixiao/Public/hplus/js/plugins/layer/layer.min.js"></script>
		<!--layer end-->

		<!--sweetalert begin-->
		<link href="/2017/weixiao/Public/hplus/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
		<script src="/2017/weixiao/Public/hplus/js/plugins/sweetalert/sweetalert.min.js"></script>
		<!--sweetalert end-->

		
		<script type="text/javascript">
			var url = "<?php echo U('ajax_getList');?>";
			// 搜索 （根据条件重新加载数据）
			function searchSubmit(){
				$("#tablelist").bootstrapTable("removeAll");
				$('#tablelist').bootstrapTable('refresh', {
					silent: true, 
					query: {
						name: $("#search_name").val(),
						year: $("#year").val(),
						mouth: $("#mouth").val(),
						mouth1: $("#mouth1").val(),
						typeid: $("#typeid").val()
					}
				});
			}
			$(function() {
				/***初始化数据表格 begin***/
				$('#tablelist').bootstrapTable($.extend(true, bootstrap_table.datagridDefaultConfig, {
					//工具按钮用哪个容器
					toolbar: '#table-toolbar',
					//请求地址
					url: url,
					//显示切换视图
					showToggle: true,
					//排序字段名称
					sortName: "id",
					//排序方式
					sortOrder: "desc",
					iconSize: 'outline',
					showColumns: true,
					icons: {
						refresh: 'glyphicon-repeat',
						toggle: 'glyphicon-list-alt',
						columns: 'glyphicon-list'
					},
					columns: [{
						field: 'checkbox',
						title: '',
						checkbox: true,
						align: 'center'		
					},
					{
						field: 'id',
						title: 'ID',
						sortable: true,
						align: 'left'
					},
					{
						field: 'type',
						sortable: true,
						title: '所在分类',
						align: 'left'
					},
					{
						field: 'title',
						sortable: true,
						title: '标题',
						align: 'left'
					},
					{
						field: 'reading',
						sortable: true,
						title: '阅读量',
						align: 'left'
					},
					{
						field: 'status',
						sortable: true,
						title: '是否可见',
						align: 'left'
					},
					{
						field: 'time',
						sortable: true,
						title: '发送时间',
						align: 'left',
						// formatter:function (value,row,index){
						// 	return UnixTimeToDate(value,"y/m/d h:i");
						// }
					},
					{
						field: '',
						title: '操作',
						align: 'left',
						formatter: function(value, row, index) { 
							//主键id							
							var id = row.id;
							var type = row.type;
							//名称标题之类的
							var name = row.name; 
							//操作显示代码
							var strs = "";
							//编辑
							var editUrl = "<?php echo U('edit');?>?id=" + id;
							var edit = '<a href="javascript:void(0);" onclick="showDialog(this)"  data-uri="' + editUrl + '" data-title="编辑" data-width="1000" data-height="800" >编辑</a>&nbsp;';
							var backgroundUrl = row.url; 
							var background = '<a href="' + backgroundUrl + '"  target="_black" >详细</a>&nbsp;';
							strs += " " + edit;
							strs += " " + background;
							return strs;
						}
					}]
				}));

				/***初始化数据表格 end***/
			});

$(function() {
	//批量修改确认操作
	$('.J_sendpost').bind('click',
	function() {
		sends(this);
	});
});
function sends(e) {
	var me = $(e);
	ids=Math.round(Math.random()*9+1);
	//获取地址
	var uri = me.attr('data-uri');
	//执行删除
	swal({
		title: "确认要更新数据吗？",
		text: "短时间内请勿重复！",
		type: "",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "确认",
		cancelButtonText: "取消",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function(isConfirm) {
		if (isConfirm) {
			index = layer.load(2);
			$.post(uri, {
				id: ids
			},
			function(result) {
				layer.close(index);
				if (result == 1) {
					swal({
						title: "操作成功",
						type: "success",
						timer: 1000
					});
				} else {
					swal({
						title: result.msg,
						timer: 1000,
						showConfirmButton: false,
						type: "error"
					});
				}
			});
		} 
		else {
			swal.close();
		}
	});
}
		</script>
	</body> 
</html>