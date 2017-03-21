<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>车位管理</title>
		<meta name="keywords" content="车位管理">
		<meta name="车位管理">
		<link href="/wuyeguanli/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/style.css" rel="stylesheet">
<link href="/wuyeguanli/Public/system/css/common.css" rel="stylesheet">
		<script type="text/javascript" src="/wuyeguanli/Public/system/js/common.js"></script>
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
						<h5>车位管理</h5>
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
	                                <input id="search_name" style="width: 200px;" placeholder="请输入车位号或者用户姓名" type="text"  class="form-control" >
									<select style="width: 150px;" class="form-control" id = "status">
	                                	<option selected="" value="">车位状态</option>
                                        <option value="0">租赁</option>
                                        <option value="1">购买</option>
                                    </select>
									<select style="width: 150px;" class="form-control" id = "paysty">
	                                	<option selected="" value="">缴费方式</option>
                                        <option value="0">月付</option>
                                        <option value="1">年费</option>
                                    </select>
									<select style="width: 150px;" class="form-control" id = "isss">
	                                	<option selected="" value="">是否可用</option>
                                        <option value="0">否</option>
                                        <option value="1">是</option>
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
		                                    <button type="button" class="btn btn-outline btn-default J_bat"  onclick="fun1()" data-uri="<?php echo U('ajax_gai');?>">批量修改管理费
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
						paysty: $("#paysty").val(),
						isss: $("#isss").val()
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
						field: 'number',
						sortable: true,
						title: '车位号',
						align: 'left'
					},
					{
						field: 'real_name',
						sortable: true,
						title: '车位所有人',
						align: 'left'
					},
					{
						field: 'license_plate',
						sortable: true,
						title: '车牌',
						align: 'left'
					},
					{
						field: 'status',
						sortable: true,
						title: '车位状态',
						align: 'left'
					},
					{
						field: 'paystyle',
						sortable: true,
						title: '缴费方式',
						align: 'left'
					},
					{
						field: 'paymoney',
						sortable: true,
						title: '阶段应缴金额（单价）',
						align: 'left'
					},
					{
						field: 'manage_fees',
						sortable: true,
						title: '管理费',
						align: 'left'
					},
					{
						field: 'pay_lasttime',
						sortable: true,
						title: '缴费截止日期',
						align: 'left',
						formatter:function (value,row,index){
							return UnixTimeToDate(value,"y/m/d h:i");
						}
					},
					{
						field: 'finall_paytime',
						sortable: true,
						title: '上一次缴费日期',
						align: 'left',
						formatter:function (value,row,index){
							return UnixTimeToDate(value,"y/m/d h:i");
						}
					},
					{
						field: 'isdo',
						sortable: true,
						title: '是否可用',
						align: 'left'
					},
					{
						field: 'remark',
						sortable: true,
						title: '备注',
						align: 'left'
					},	
					{
						field: '',
						title: '操作',
						align: 'left',
						formatter: function(value, row, index) { 
							//主键id
							var aid = row.id;							
							var id = row.aid; 
							var userid = row.userid;
							//名称标题之类的
							var number = row.number;
							var name = row.license_plate;
							var number = row.number;
							//操作显示代码
							var strs = "";
							//编辑
							var editUrl = "<?php echo U('edit');?>?id=" + aid;
							var edit = '<a href="javascript:void(0);" onclick="showDialog(this)"  data-uri="' + editUrl + '" data-title="编辑" data-width="1000" data-height="800" >编辑</a>&nbsp;';
							strs += " " + edit;


							//切换车位状态
							var oldstatus = row.status; 
							var newstatus = 0;
							var status_text = "租赁";
							if(oldstatus == "租赁")
							{
								status_text = "已经购买";	
								newstatus = 1;	
							}
							var edit_filed_url = "<?php echo U('ajax_field_edits');?>";
							var edit_filed = "<a data-ifs='status' data-val='" + newstatus + "' data-id='" + aid + "' href='javascript:void(0)' data-uri='" + edit_filed_url + "'   onclick='updateField(this)' data-msg='你确认要修改车位编号" + number + "的显示状态吗？' >" + status_text + "</a>";
							strs += " " + edit_filed;


							//切换缴费状态
							var oldstatus = row.paystyle; 
							var newstatus = 0;
							var status_text = "按月缴费";
							if(oldstatus == "按月缴费")
							{
								status_text = "按年缴费";	
								newstatus = 1;	
							}
							var edit_filed_url = "<?php echo U('ajax_field_edits');?>";
							var edit_filed = "<a data-ifs='paystyle' data-val='" + newstatus + "' data-id='" + aid + "' href='javascript:void(0)' data-uri='" + edit_filed_url + "'   onclick='updateField(this)' data-msg='你确认要修改车位编号" + number + "的显示状态吗？' >" + status_text + "</a>";
							strs += " " + edit_filed;

							//缴费详情
							var editUrl = "<?php echo U('Community/Payfees/index');?>?name=" + id+"&&type=" + 5 ;
							var edit = '<a href="javascript:void(0);" onclick="showDialog(this)" data-full="1" data-uri="' + editUrl + '" data-title="缴费详情" data-width="1000" data-height="800" >缴费详情</a>&nbsp;';
							strs += " " + edit;
							var userUrl = "<?php echo U('Community/Users/index');?>?uid=" + userid;
							var user = '<a href="javascript:void(0);" onclick="showDialog(this);" data-full="1" data-uri="' + userUrl + '" data-title="用户详情" data-width="1000" data-height="800" >用户详情</a>&nbsp;';
							strs += " " + user;
							//删除
							var deleteUrl = "<?php echo U('ajax_delete');?>";
							var del = '<a href="javascript:void(0);" onclick="confirmDelete(this)" data-uri="' + deleteUrl + '" data-id="' + row.aid + '" data-msg="确认要将删除' + name + '吗?" >删除</a>&nbsp;';
							strs += " " + del;
							var background = '<a href = "">详细</a>&nbsp;';
							strs += " " + background;
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
			var names = 'manage_fees';
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
				nums = prompt("修改管理费单价为:","");
			}
		</script>
	</body> 
</html>