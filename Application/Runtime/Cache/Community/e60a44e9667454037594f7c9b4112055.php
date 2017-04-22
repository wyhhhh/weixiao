<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>分类管理</title>
		<meta name="keywords" content="分类管理">
		<meta name="分类管理">
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
						<h5>分类管理</h5>
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
	                                <input id="search_name" style="width: 200px;" placeholder="请输入分类名称" type="text"  class="form-control" >
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
<!-- 		                                    <button type="button" class="btn btn-outline btn-default J_showdialog" data-uri='<?php echo U("addall");?>' data-title="添加" data-width="1000" data-height="800">批量添加
		                                    </button>
		                                    <button type="button" class="btn btn-outline btn-default J_bat"  onclick="fun1()" data-uri="<?php echo U('ajax_gai');?>">
		                                        批量修改物管费
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
						field: 'name',
						sortable: true,
						title: '类名',
						align: 'left'
					},
					{
						field: 'typenum',
						sortable: true,
						title: '子分类',
						align: 'left'
					},
					{
						field: 'status',
						sortable: true,
						title: '是否启用',
						align: 'left'
					},
					{
						field: 'addtime',
						sortable: true,
						title: '添加时间',
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
							var name = row.name; 
							//操作显示代码
							var strs = "";
							//编辑
							var editUrl = "<?php echo U('edit');?>?id=" + id;
							var edit = '<a href="javascript:void(0);" onclick="showDialog(this)"  data-uri="' + editUrl + '" data-title="编辑" data-width="1000" data-height="800" >编辑</a>&nbsp;';
							//删除
							var deleteUrl = "<?php echo U('ajax_delete');?>";
							var del = '<a href="javascript:void(0);" onclick="confirmDelete(this)" data-uri="' + deleteUrl + '" data-id="' + row.id + '" data-msg="确认要将删除' + name + '吗?" >删除</a>&nbsp;';
							if (id==1) 
							{
								var backgroundUrl = "<?php echo U('Community/Reading/index');?>?id=" + id;
							}else{
								var backgroundUrl = "<?php echo U('Community/Readtypes/index');?>?id=" + id;
							};
							var background = '<a href="javascript:void(0);" onclick="showDialog(this)" data-full="1" data-uri="' + backgroundUrl + '" data-title="详细" data-width="1000" data-height="800" >详细</a>&nbsp;';
							strs += " " + edit;
							strs += " " + background;
							strs += " " + del;
							return strs;
						}
					}]
				}));

				/***初始化数据表格 end***/
			});
			function post(URL, PARAMS) {        
			    var temp = document.createElement("form");        
			    temp.action = URL;        
			    temp.method = "post";        
			    temp.style.display = "none";        
			    for (var x in PARAMS) {        
			        var opt = document.createElement("textarea");        
			        opt.name = x;        
			        opt.value = PARAMS[x];        
			        // alert(opt.name)        
			        temp.appendChild(opt);        
			    }        
			    document.body.appendChild(temp);        
			    temp.submit();        
			    return temp;        
			}  

			var nums = 555;
			var names = 'unit_price';
			function fun1() {
				if (empty(tableid)) {
					e=this;
					var me = $(e);
					var tableid = me.attr('data-tableid');
					tableid = "tablelist";
				}
				var list = $('#' + tableid).bootstrapTable('getAllSelections');
				var length = list.length;
				if(length == 0){
					swal({
						title: "请至少选择一行",
						timer: 1000,
						showConfirmButton: false,
						type: "error"
					});
					return;
				}
				nums = prompt("修改物管费单价为:","");
			}
		</script>
	</body> 
</html>