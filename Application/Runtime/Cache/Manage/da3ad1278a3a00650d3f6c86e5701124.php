<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>意见反馈管理</title>
		<meta name="keywords" content="意见反馈管理">
		<meta name="意见反馈管理">
		<link href="/wuyeguanli/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/style.css" rel="stylesheet">
<link href="/wuyeguanli/Public/system/css/common.css" rel="stylesheet">
	</head>
	<body class="gray-bg">
		<div class="wrapper wrapper-content animated">
			<div class="ibox float-e-margins">
				<!-- Panel Style -->
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>意见反馈管理</h5>
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
							<div class="col-sm-12 me-search">
									<input id="search_name" style="width: 250px;" placeholder="请输入内容关键字或者用户名" type="text"  class="form-control">
									<select style="width: 150px;" class="form-control" id = "status">
	                                	<option selected="" value="">请选择</option>
                                        <option value="0">已查看</option>
                                        <option value="1">未查看</option>
                                    </select>
	                                <button onclick="searchSubmit()" type="button" id="search_btn" class="btn btn-w-s btn-primary">搜索</button>
							</div>
						</div>
						<div class="row row-lg">
							<div class="col-sm-12">
								<div class="example-wrap">
									<div class="example">
										<div class="btn-group hidden-xs" id="table-toolbar" role="group">
											 <!--<button type="button" class="btn btn-outline btn-default J_showdialog" >
		                                        <i class="fa fa-check" aria-hidden="true"></i>
		                                    </button> -->
		                                    <button type="button" class="btn btn-outline btn-default J_batchDelete"  data-uri="<?php echo U('ajax_delete');?>">
		                                        <i class="glyphicon glyphicon-trash" aria-hidden="true"></i>
		                                    </button>
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
		<script src="/wuyeguanli/Public/hplus/js/jquery.min.js?v=2.1.4" charset="UTF-8"></script>
<script src="/wuyeguanli/Public/hplus/js/bootstrap.min.js?v=3.3.6" charset="UTF-8"></script>
<script src="/wuyeguanli/Public/system/js/common.js" charset="UTF-8"></script>
<!-- 自定义js -->
<script src="/wuyeguanli/Public/hplus/js/content.js" charset="UTF-8"></script>




		<!-- Bootstrap table begin-->
		<link href="/wuyeguanli/Public/hplus/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
		<script src="/wuyeguanli/Public/system/js/bootstrap-table.config.js"></script>
		<script src="/wuyeguanli/Public/hplus/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
		<script src="/wuyeguanli/Public/hplus/js/plugins/bootstrap-table/bootstrap-table-mobile.min.js"></script>
		<script src="/wuyeguanli/Public/hplus/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
		<!-- Bootstrap table end-->

		<!--layer begin-->
		<script src="/wuyeguanli/Public/hplus/js/plugins/layer/layer.min.js"></script>

		<!--sweetalert begin-->
		<link href="/wuyeguanli/Public/hplus/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
		<script src="/wuyeguanli/Public/hplus/js/plugins/sweetalert/sweetalert.min.js"></script>
		<!--sweetalert end-->

		<script type="text/javascript">
			// 搜索 （根据条件重新加载数据）
			function searchSubmit(){
				$("#tablelist").bootstrapTable("removeAll");
				$('#tablelist').bootstrapTable('refresh', {
					silent: true,
					query: {
						name: $("#search_name").val(),
						status: $("#status").val()
					}
				});
			}
			$(function() {
				/***初始化数据表格 begin***/
				var url = "<?php echo U('ajax_getList');?>";
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
						align: 'center',		
					},
					{
						field: 'id',
						title: 'ID',
						sortable: true,
						align: 'left',
					},
					{
						field: 'real_name',
						sortable: true,
						title: '用户名',
						align: 'left',
					},
					{
						field: 'content',
						sortable: true,
						title: '反馈内容',
						align: 'left',
						formatter:function(value){
							if (!empty(value)) {
								var str = "";
								str = value.substr(0,5);
								return str;
							};   
						}	
					},
					{
						field: 'addtime',
						sortable: true,
						title: '反馈时间',
						align: 'left'
						// formatter:function(value){
						// 	return UnixTimeToDate(value,"y/m/d h:i");
						// }
					},
					{
						field: 'status',
						sortable: true,
						title: '查看状态',
						align: 'left',
					},
					{
						field: '',
						title: '操作',
						align: 'left',
						formatter: function(value, row, index) {
							//通过formatter可以自定义列显示的内容
							//value：当前field的值，即id
							//row：当前行的数据
							//主键ID
							var id = row.id; 
							var name = row.real_name; 
							//操作显示代码
							var strs = "";
							//标记为已查看
							var oldstatus = row.status; 
							var newstatus = 1;
							var status_text = "已查看";
							if(oldstatus == "已查看")
								{
									status_text = "未查看";	
									newstatus = 0;	
								}
							var edit_filed_url = "<?php echo U('ajax_field_edit');?>";
							var edit_filed = "<a data-val='" + newstatus + "' data-id='" + id + "' href='javascript:void(0)' data-uri='" + edit_filed_url + "'   onclick='updateField(this)' data-msg='你确认要修改反馈内容的查看状态吗？' >" + status_text + "</a>";
								strs += " " + edit_filed;
							//查看详情
							var detUrl = "<?php echo U('detail');?>?id=" + id;
							var detail = '<a href="javascript:void(0);" onclick="showDialog(this)"  data-uri="' + detUrl + '" data-title="详情" data-width="800" data-height="400" >查看详情</a>&nbsp;';
								strs += " " + detail;
							//删除
							var deleteUrl = "<?php echo U('ajax_delete');?>";
							var del = '<a href="javascript:void(0);" onclick="confirmDelete(this)" data-uri="' + deleteUrl + '" data-id="' + row.id + '" data-msg="确认要删除吗?" >删除</a>&nbsp;';
								strs += " " + del;
							return strs;
						}
					}]
				}));
				/***初始化数据表格 end***/
			});
		</script>
	</body> 
</html>