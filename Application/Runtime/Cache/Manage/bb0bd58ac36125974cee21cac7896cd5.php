<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>维修家政订单管理</title>
		<meta name="keywords" content="维修家政订单管理">
		<meta name="维修家政订单管理">
		<link href="/wuyeguanli/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/style.css" rel="stylesheet">
<link href="/wuyeguanli/Public/system/css/common.css" rel="stylesheet">
	</head>
	<body class="gray-bg">
	    <!--<div class="row wrapper border-bottom white-bg page-heading">
	        <div class="col-sm-4">
	            <h2>维修家政订单管理</h2>
	            <ol class="breadcrumb">
	                <li>
	                    <a href="javascript:void(0);">主页</a>
	                </li>
	                <li>
	                    <a href="javascript:void(0);">系统管理</a>
	                </li>
	                <li>
	                    <strong>维修家政订单管理</strong>
	                </li>
	            </ol>
	        </div>
	    </div>-->
		<div class="wrapper wrapper-content animated">
			<div class="ibox float-e-margins">
				<!-- Panel Style -->
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>维修家政订单管理</h5>
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
								<input id="search_name" style="width: 150px;" placeholder="请输入住户的姓名" type="text"  class="form-control">
	                            <select style="width: 150px;" class="form-control" id = "repairuser">
	                               	<option selected="" value="">请选择服务人员</option>
	                                <?php if(is_array($repairuser)): $i = 0; $__LIST__ = $repairuser;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$volist): $mod = ($i % 2 );++$i;?><option value="<?php echo ($volist["id"]); ?>"><?php echo ($volist["pet_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                                <select style="width: 150px;" class="form-control" id = "status">
	                                <option selected="" value="">请选择订单状态</option>
                                    <option value="0">分配维修员</option>
                                    <option value="1">正在维修</option>
                                    <option value="2">维修完成</option>
                                    <option value="3">用户取消</option>
                                    <option value="4">管理员取消</option>
                                    <!-- 完成状态: 0_分配维修员（默认）, 1_正在维修 , 2_维修完成 , 3_用户取消 , 4_管理员取消 -->
                                </select>
                                <select style="width: 150px;" class="form-control" id = "paystatus">
	                               	<option selected="" value="">请选择支付状态</option>
                                    <option value="0">未缴费</option>
                                    <option value="1">已缴费</option>
                                    <option value="2">缴费退款中</option>
                                    <option value="3">已退款</option>
                                    <!-- 用户缴费状态: 0_未缴费（默认）, 1_已缴费, 2_缴费退款中, 3_已退款 -->
                                </select>
	                                <button onclick="searchSubmit()" type="button" id="search_btn" class="btn btn-w-s btn-primary">搜索</button>
							</div>
						</div>
						<div class="row row-lg">
							<div class="col-sm-12">
								<div class="example-wrap">
									<div class="example">
										<div class="btn-group hidden-xs" id="table-toolbar" role="group">
											<!-- <button type="button" class="btn btn-outline btn-default J_showdialog" data-uri='<?php echo U("add");?>' data-title="添加" data-width="1000" data-height="800">
		                                        <i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
		                                    </button> -->
		                                    <button type="button" class="btn btn-outline btn-default J_batchDelete"  data-uri="<?php echo U('ajax_delete');?>">
		                                        <i class="glyphicon glyphicon-trash" aria-hidden="true"></i>
		                                    </button>
										</div>
										<table id="tablelist" class="table table-hover table-condensed">
										<input type="hidden" id="" name="id" value="<?php echo ($info["id"]); ?>" />
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
			var url = "<?php echo U('ajax_getList');?>?id=<?php echo ($_GET['id']); ?>";
			// 搜索 （根据条件重新加载数据）
			function searchSubmit(){
				$("#tablelist").bootstrapTable("removeAll");
				$('#tablelist').bootstrapTable('refresh', {
					silent: true, 
					query: {
						name: $("#search_name").val(),
						repairuser: $("#repairuser").val(),
						status: $("#status").val(),
						paystatus: $("#paystatus").val()
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
					sortName: "r.id",
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
						field: 'pet_name',
						sortable: true,
						title: '服务人员',
						align: 'left'
					},
					{
						field: 'type',
						sortable: true,
						title: '服务类型名称',
						align: 'left'
					},
					{
						field: 'real_name',
						sortable: true,
						title: '业主',
						align: 'left'
					},
					{
						field: 'evaluate',
						sortable: true,
						title: '业主评分',
						align: 'left'
					},
					{
						field: 'status',
						sortable: true,
						title: '维修状态',
						align: 'left'
					},
					{
						field: 'service_place',
						sortable: true,
						title: '维修地点',
						align: 'left'
					},
					{
						field: 'addtime',
						sortable: true,
						title: '维修时间',
						align: 'left'
					},
					{
						field: 'paystatus',
						sortable: true,
						title: '用户缴费状态',
						align: 'left'
					},
					{
						field: '',
						title: '操作',
						align: 'left',
						formatter: function(value, row, index) {
							//主键id							
							var id = row.id; 
							//名称标题之类的
							var name = row.name
							//操作显示代码
							var strs = "";

							// //编辑
							// var editUrl = "<?php echo U('edit');?>?id=" + id;
							// var edit = '<a href="javascript:void(0);" onclick="showDialog(this)"  data-uri="' + editUrl + '" data-title="编辑" data-width="1000" data-height="800" >编辑</a>';
							// strs += " " + edit;

							// //删除
							// var deleteUrl = "<?php echo U('ajax_delete');?>";
							// var del = '<a href="javascript:void(0);" onclick="confirmDelete(this)" data-uri="' + deleteUrl + '" data-id="' + id + '" data-msg="确认要将删除' + name + '吗?" >删除</a>';
							// strs += " " + del;
							//查看详情
							var detUrl = "<?php echo U('detail');?>?id=" + id;
							var detail = '&nbsp;<a href="javascript:void(0);" onclick="showDialog(this)"  data-uri="' + detUrl + '" data-title="查看详情" data-width="800" data-height="600" >查看详情</a>&nbsp;';
								strs += " " + detail;
							var paydetailUrl = "<?php echo U('paydetail');?>?id=" + id;
							var paydetail = '&nbsp;<a href="javascript:void(0);" onclick="showDialog(this)"  data-uri="' + paydetailUrl + '" data-title="查看支付详情" data-width="800" data-height="400" >查看支付详情</a>&nbsp;';
								strs += '' + paydetail;
							var refund = '<a href="">退款</a>&nbsp;'
								strs += '' + refund;
							return strs;
						}
					}]
				}));
				/***初始化数据表格 end***/
			});
		</script>
	</body> 
</html>