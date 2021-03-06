<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>联盟数据</title>
		<meta name="keywords" content="联盟数据">
		<meta name="联盟数据">
		<link href="/2017/weixiao/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/2017/weixiao/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/2017/weixiao/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/2017/weixiao/Public/hplus/css/style.css" rel="stylesheet">
<link href="/2017/weixiao/Public/system/css/common.css" rel="stylesheet">
	</head>
	<body class="gray-bg">
	    <!--<div class="row wrapper border-bottom white-bg page-heading">
	        <div class="col-sm-4">
	            <h2>联盟数据</h2>
	            <ol class="breadcrumb">
	                <li>
	                    <a href="javascript:void(0);">主页</a>
	                </li>
	                <li>
	                    <a href="javascript:void(0);">系统管理</a>
	                </li>
	                <li>
	                    <strong>联盟数据</strong>
	                </li>
	            </ol>
	        </div>
	    </div>-->
		<div class="wrapper wrapper-content animated">
			<div class="ibox float-e-margins">
				<!-- Panel Style -->
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>联盟数据</h5>
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
	                                <input id="search_name" style="width: 200px;" placeholder="请输入微信号名字" type="text"  class="form-control" >
	                                <button onclick="searchSubmit()" type="button" id="search_btn" class="btn btn-w-s btn-primary">搜索</button>
							</div>
						</div>
						<div class="row row-lg">
							<div class="col-sm-12">
								<div class="example-wrap">
									<div class="example">
										<div class="btn-group hidden-xs" id="table-toolbar" role="group">
											<button type="button" class="btn btn-outline btn-default J_sendpost " data-uri="<?php echo U('Community/Readtypes/index');?>" data-title="更新" data-width="1000" data-height="800">
		                                        <i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
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
						name: $("#search_name").val()
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
					sortOrder: "asc",
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
						field: 'time',
						sortable: true,
						title: '时间',
						align: 'left'
					},
					{
						field: 'number',
						sortable: true,
						title: '总推文数',
						align: 'left'
					},
					{
						field: 'reading',
						sortable: true,
						title: '总阅读量',
						align: 'left'
					},
					{
						field: 'addtime',
						sortable: true,
						title: '生成时间',
						align: 'left',
						formatter:function (value,row,index){
							return UnixTimeToDate(value,"y/m/d h:i");
						}
					},
					{
						field: '',
						title: '操作',
						align: 'left',
						formatter: function(value, row, index) { 
							//主键id							
							var id = row.id; 
							//名称标题之类的
							var name = row.time; 
							//操作显示代码
							var strs = "";
							//详细
							var backgroundUrl = "<?php echo U('Community/WeChatmouth/index');?>?id=" + id;
							var background = '<a href="javascript:void(0);" onclick="showDialog(this)" data-full="1" data-uri="' + backgroundUrl + '" data-title="'+name+'" data-width="1000" data-height="800" >详细</a>&nbsp;';
							strs += " " + background;
							//编辑
							var editUrl = "<?php echo U('edit');?>?id=" + id;
							var edit = '<a href="javascript:void(0);" class="J_sendpost"  data-uri="' + editUrl + '" data-title="导出">导出</a>&nbsp;';
							strs += " " + edit;
							var editsUrl = "<?php echo U('ajax_delete');?>";
							var edits = '<a href="javascript:void(0);" class="J_sendpost" data-uri="' + editsUrl + '" data-id="' + row.id + '" data-msg="确认要将更新' + name + '吗?" >更新</a>&nbsp;';
							strs += " " + edits;
							return strs;
						}
					}]
				}));
				/***初始化数据表格 end***/
			});
		</script>
	</body> 
</html>