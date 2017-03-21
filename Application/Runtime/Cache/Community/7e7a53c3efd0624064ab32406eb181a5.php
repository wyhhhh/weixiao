<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>缴费查询</title>
		<meta name="keywords" content="缴费查询">
		<meta name="缴费查询">
		<link href="/wuyeguanli/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/style.css" rel="stylesheet">
<link href="/wuyeguanli/Public/system/css/common.css" rel="stylesheet">
	</head>
	<body class="gray-bg">
	    <!--<div class="row wrapper border-bottom white-bg page-heading">
	        <div class="col-sm-4">
	            <h2>车位管理</h2>
	            <ol class="breadcrumb">
	                <li>
	                    <a href="javascript:void(0);">主页</a>
	                </li>
	                <li>
	                    <a href="javascript:void(0);">系统管理</a>
	                </li>
	                <li>
	                    <strong>车位管理</strong>
	                </li>
	            </ol>
	        </div>
	    </div>-->
		<div class="wrapper wrapper-content animated">
			<div class="ibox float-e-margins">
				<!-- Panel Style -->
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>缴费查询</h5>
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
	                                <input id="search_name" style="width: 200px;" placeholder="请输入用户姓名" type="text"  class="form-control" >
									<select style="width: 150px;" class="form-control" id = "status">
	                                	<option selected="" value="">缴费类型</option>
                                        <option value="1">水费</option>
                                        <option value="2">电费</option>
                                        <option value="3">气费</option>
                                        <option value="4">物业</option>
                                        <option value="5">车位</option>
                                    </select>
	                                <button onclick="searchSubmit()" type="button" id="search_btn" class="btn btn-w-s btn-primary">搜索</button>
							</div>
						</div>
						<div class="row row-lg">
							<div class="col-sm-12">
								<div class="example-wrap">
									<div class="example">
										<div class="btn-group hidden-xs" id="table-toolbar" role="group">
		                                    <button type="button" class="btn btn-outline btn-default J_showdialog" data-uri='<?php echo U("addall");?>' data-title="添加" data-width="1000" data-height="800">Excel录入
		                                    </button>
		                                    <button type="button" class="btn btn-outline btn-default J_showdialog" data-uri='<?php echo U("Needsend/index");?>' data-title="添加" data-width="1000" data-height="800">基础设置
		                                    </button>
		                                    <button type="button" class="btn btn-outline btn-default J_sends"  onclick="fun1()" data-uri="<?php echo U('ajax_sends');?>">批量发送
		                                    </button>
		                                    <button type="button" class="btn btn-outline btn-default J_allsends"  onclick="" data-uri="<?php echo U('ajax_all_sends');?>">未缴费发送
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
						status: $("#status").val(),
						community: $("#community").val()
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
						field: 'real_name',
						sortable: true,
						title: '用户',
						align: 'left'
					},
					{
						field: 'types',
						sortable: true,
						title: '缴费类型',
						align: 'left'
					},
					{
						field: 'money',
						sortable: true,
						title: '金额',
						align: 'left'
					},
					{
						field: 'number',
						sortable: true,
						title: '编号',
						align: 'left'
					},
					{
						field: 'addtime',
						title: '订单时间',
						sortable: true,
						align: 'left',
						formatter:function (value,row,index){
							return UnixTimeToDate(value,"y/m/d h:i");
						}
					},
					{
						field: 'paytime',
						sortable: true,
						title: '缴费时间',
						align: 'left',
						formatter:function (value,row,index){
							return UnixTimeToDate(value,"y/m/d h:i");
						}
					},
					{
						field: 'addtimes',
						sortable: true,
						title: '缴费情况',
						align: 'left',
					},
					{
						field: '',
						title: '操作',
						align: 'left',
						formatter: function(value, row, index) { 
							//主键id							
							var id = row.type; 
							//名称标题之类的
							var name = row.userid; 
							var idd = row.id;
							var userid = row.userid;
							//操作显示代码
							var strs = "";
							//删除
							var userUrl = "<?php echo U('Community/Users/index');?>?uid=" + name;
							var user = '<a href="javascript:void(0);" onclick="showDialog(this);" data-full="1" data-uri="' + userUrl + '" data-title="车位/房屋详情" data-width="1000" data-height="800" >车位/房屋详情</a>&nbsp;';
							strs += " " + user;
							var userUrl = "<?php echo U('Community/Users/index');?>?uid=" + name;
							var user = '<a href="javascript:void(0);" onclick="showDialog(this);" data-full="1" data-uri="' + userUrl + '" data-title="用户详情" data-width="1000" data-height="800" >用户详情</a>&nbsp;';
							strs += " " + user;
							var backUrl = "<?php echo U('ajax_sends');?>";
							var background = "<a data-id='" + userid + "' href='javascript:void(0)' data-uri='" + backUrl + "'   onclick='upd(this)' data-msg='你确定要发送缴费通知吗？' >缴费通知</a>";
							if(row.status==0)
							{strs += " " + background;}
							return strs;
						}
					}]
				}));

				/***初始化数据表格 end***/
			});
function upd(e)
{
	var me = $(e);
	var ifs = 0;
	var ifs = me.attr('data-ifs'); 
	var uri = me.attr('data-uri'); 
	var val = me.attr('data-val');
	var msg = me.attr('data-msg');
	var id = me.attr('data-id');
	
	var me = $(e);
	var tableid = me.attr('data-tableid');
	if (empty(tableid)) {
		tableid = "tablelist";
	}
	var list = $('#' + tableid).bootstrapTable('getAllSelections');
	var length = list.length;
	if(ifs==0){
	if(length == 0){
		swal({
			title: "请至少选择一行",
			timer: 1000,
			showConfirmButton: false,
			type: "error"
		});
		return;
	}
	}
	var ids = "";
	//獲取選中行id
	for (i = 0;i < list.length;i++) {
		var item = list[i];
		ids += "," + item['id'];
	}
	var ids = ids.substr(1,ids.length - 1);
	//执行修改
	swal({
		title: msg,
		type: "",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "确定",
		cancelButtonText: "取消",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function(isConfirm) {
		if (isConfirm) {
			index = layer.load(2);
			$.post(uri, {
				id: id,
			},
			function(result) {
				layer.close(index);
				if (result.status == 1) {
					swal({
						title: "操作成功",
						type: "success",
						timer: 1000
					});
					if(!empty(searchSubmit)){
						searchSubmit();
					}
					else if ($('#tablelist').length > 0) {
						//重新加载数据
						$('button[name="refresh"]').trigger("click");
					}
				}
				else {
					swal({
						title: "发送失败",
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
			}
		</script>
	</body> 
</html>