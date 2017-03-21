<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>用户(业主)管理</title>
		<meta name="keywords" content="用户(业主)管理">
		<meta name="用户(业主)管理">
		<link href="/wuyeguanli/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/style.css" rel="stylesheet">
<link href="/wuyeguanli/Public/system/css/common.css" rel="stylesheet">
	</head>
	<body class="gray-bg">
	    <!--<div class="row wrapper border-bottom white-bg page-heading">
	        <div class="col-sm-4">
	            <h2>用户(业主)管理</h2>
	            <ol class="breadcrumb">
	                <li>
	                    <a href="javascript:void(0);">主页</a>
	                </li>
	                <li>
	                    <a href="javascript:void(0);">系统管理</a>
	                </li>
	                <li>
	                    <strong>用户(业主)管理</strong>
	                </li>
	            </ol>
	        </div>
	    </div>-->
		<div class="wrapper wrapper-content animated">
			<div class="ibox float-e-margins">
				<!-- Panel Style -->
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>用户(业主)管理</h5>
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
	                                <input id="search_name" style="width: 300px;" placeholder="请输入用户姓名或者手机号" type="text"  class="form-control" >
	                                <select style="width: 150px;" class="form-control" name = "communityid"
                                    id= "communityid" required="true">
                                        <option selected="" value = "">请选择社区</option>
                                        <?php if(is_array($mname)): $i = 0; $__LIST__ = $mname;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$volist): $mod = ($i % 2 );++$i;?><option value="<?php echo ($volist["id"]); ?>"><?php echo ($volist["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
	                                <button onclick="searchSubmit()" type="button" id="search_btn" class="btn btn-w-s btn-primary">搜索</button>

							</div>
						</div>
						<div class="row row-lg">
							<div class="col-sm-12">
								<div class="example-wrap">
									<div class="example">
										<div class="btn-group hidden-xs" id="table-toolbar" role="group">
											<button type="button" class="btn btn-outline btn-default J_showdialog" data-uri='<?php echo U("add");?>' data-title="添加" data-width="1000" data-height="800">
		                                        <i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
		                                    </button>
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
						name: $("#search_name").val(),
						communityid: $("#communityid").val()
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
					sortName: "u.id",
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
						field: 'real_name',
						sortable: true,
						title: '姓名',
						align: 'left'
					},
					{
						field: 'phone',
						sortable: true,
						title: '手机号',
						align: 'left'
					},
					{
						field: 'sex',
						sortable: true,
						title: '性别',
						align: 'left'
					},
					{
						field: 'birthdate',
						sortable: true,
						title: '出生年月',
						align: 'left',
						formatter:function (value,row,index){
							return UnixTimeToDate(value,"y/m");
						}
					},
					{
						field: 'hname',
						sortable: true,
						title: '房号',
						align: 'left'
						
					},
					{
						field: 'name',
						sortable: true,
						title: '所在社区',
						align: 'left'
						
					},
					{
						field: 'isproprietor',
						sortable: true,
						title: '是否为业主',
						align: 'left'
						
					},
					{
						field: 'status',
						sortable: true,
						title: '是否启用',
						align: 'left'
					},
					{
						field: 'sequestration',
						sortable: true,
						title: '账号状态',
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
							//封号
							var oldsequestration = row.sequestration;
							var newsequestration = 1;
							// var sequestration_text = "已封号";
							if (oldsequestration == "正常") {
								var unsetUrl = "<?php echo U('unout');?>?id=" + id;
								var unset = '<a href="javascript:void(0);" onclick="showDialog(this)"  data-uri="' + unsetUrl + '" data-title="封号" data-width="800" data-height="450" >封号</a>&nbsp;';
								strs += " " + unset;
							}
							// else{
							// 	var unset = '<a style="color:#8B2500;">已封号</a>';
							// 	strs += " " + unset;
							// }
							//解封
							if (oldsequestration !== "正常") {
								var openUrl = "<?php echo U('open');?>?id=" + id;
								var open = '<a href="javascript:void(0);" onclick="showDialog(this)"  data-uri="' + openUrl + '" data-title="解封" data-width="500" data-height="380" >解封</a>&nbsp;';
								strs += " " + open;	
							}
							//查看封号记录
							var detUrl = "<?php echo U('sequestration/index');?>?sid=" + id + "&key=1"; 
							var detail = '&nbsp;<a href="javascript:void(0);" onclick="showDialog(this)"  data-uri="' + detUrl + '" data-title="封号记录" data-width="1200" data-height="800" >查看封号记录</a>&nbsp;';
							strs += " " + detail;
							//编辑
							var editUrl = "<?php echo U('edit');?>?id=" + id;
							var edit = '<a href="javascript:void(0);" onclick="showDialog(this)"  data-uri="' + editUrl + '" data-title="编辑" data-width="1000" data-height="800" >编辑</a>';
							strs += " " + edit;							
							return strs;
						}
					}]
				}));
				/***初始化数据表格 end***/
			});
		</script>
	</body> 
</html>