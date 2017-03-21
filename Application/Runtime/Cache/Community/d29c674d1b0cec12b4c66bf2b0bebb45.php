<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>订单详情</title>
		<meta name="keywords" content="订单详情">
		<meta name="订单详情">
		<link href="/wuyeguanli/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/style.css" rel="stylesheet">
<link href="/wuyeguanli/Public/system/css/common.css" rel="stylesheet">
	</head>
	<body class="gray-bg">
	    <!--<div class="row wrapper border-bottom white-bg page-heading">
	        <div class="col-sm-4">
	            <h2>楼层管理</h2>
	            <ol class="breadcrumb">
	                <li>
	                    <a href="javascript:void(0);">主页</a>
	                </li>
	                <li>
	                    <a href="javascript:void(0);">系统管理</a>
	                </li>
	                <li>
	                    <strong>楼层管理</strong>
	                </li>
	            </ol>
	        </div>
	    </div>-->
		<div class="wrapper wrapper-content animated">
			<div class="ibox float-e-margins">
				<!-- Panel Style -->
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>订单详情</h5>
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

							</div>
						</div>
						<div class="row row-lg">
							<div class="col-sm-12">
								<div class="example-wrap">
									<div class="example">
										<div class="btn-group hidden-xs" id="table-toolbar" role="group">
											
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
		<!--layer end-->

		<!--sweetalert begin-->
		<link href="/wuyeguanli/Public/hplus/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
		<script src="/wuyeguanli/Public/hplus/js/plugins/sweetalert/sweetalert.min.js"></script>
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
						field: 'r_id',
						title: 'ID',
						sortable: true,
						align: 'left'
					},
					{
						field: 'r_order_number',
						sortable: true,
						title: '订单号码',
						align: 'left'
					},
					{
						field: 'runame',
						sortable: true,
						title: '服务人员',
						align: 'left'
					},
					{
						field: 'r_status',
						sortable: true,
						title: '订单状态',
						align: 'left'
					},
					{
						field: 'r_paystatus',
						sortable: true,
						title: '用户缴费状态',
						align: 'left'
					},
					{
						field: 'r_pay_money',
						sortable: true,
						title: '缴费金额',
						align: 'left'
					},
					{

						field: 'r_remark',
						sortable: true,
						title: '支付备注',
						align: 'left'
					},
					{
						field: 'r_evaluate',
						sortable: true,
						title: '评价星级',
						align: 'left'
					},
					{
						field: 'r_reason',
						sortable: true,
						title: '取消原因',
						align: 'left'
					},
					{
						field: 'r_reminder',
						sortable: true,
						title: '催单次数',
						align: 'left'
					},
					{
						field: 'r_isread',
						sortable: true,
						title: '是否读取',
						align: 'left'
					},
					{
						field: 'r_addtime',
						sortable: true,
						title: '提交时间',
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
							var uid = row.uid;
							//名称标题之类的
							var name = row.name; 
							//操作显示代码
							var strs = "";
							//编辑
							var backgroundUrl1 = "<?php echo U('Community/Repair/index');?>?id=" + id;
							var background1 = '<a href="javascript:void(0);" onclick="showDialog(this)" data-full="1" data-uri="' + backgroundUrl1 + '" data-title="订单详细" data-width="1000" data-height="800" >订单详细</a>&nbsp;';
							// strs += " " + background1;

							var backgroundUrl2 = "<?php echo U('Community/Users/index');?>?id=" + uid;
							var background2 = '<a href="javascript:void(0);" onclick="showDialog(this)" data-full="1" data-uri="' + backgroundUrl2 + '" data-title="用户详细" data-width="1000" data-height="800" >用户详细</a>&nbsp;';
							// strs += " " + background2;

							var backgroundUrl3 = "<?php echo U('Community/Repair/index');?>?id=" + id;
							var background3 = '<a href="javascript:void(0);" onclick="showDialog(this)" data-full="1" data-uri="' + backgroundUrl3 + '" data-title="服务人员详细" data-width="1000" data-height="800" >服务人员详细</a>&nbsp;';
							// strs += " " + background3;

							//删除
							var deleteUrl = "<?php echo U('ajax_delete');?>";
							var del = '<a href="javascript:void(0);" onclick="confirmDelete(this)" data-uri="' + deleteUrl + '" data-id="' + row.id + '" data-msg="确认要将删除' + name + '吗?" >删除</a>&nbsp;';
							// strs += " " + del;
							return strs;
						}
					}]
				}));
				/***初始化数据表格 end***/
			});
		</script>
	</body> 
</html>