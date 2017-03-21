<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>回帖管理</title>
		<meta name="keywords" content="回帖管理">
		<meta name="回帖管理">
		<link href="/wuyeguanli/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/wuyeguanli/Public/hplus/css/style.css" rel="stylesheet">
<link href="/wuyeguanli/Public/system/css/common.css" rel="stylesheet">
	</head>
	<body class="gray-bg">
	    <!--<div class="row wrapper border-bottom white-bg page-heading">
	        <div class="col-sm-4">
	            <h2>回帖管理</h2>
	            <ol class="breadcrumb">
	                <li>
	                    <a href="javascript:void(0);">主页</a>
	                </li>
	                <li>
	                    <a href="javascript:void(0);">系统管理</a>
	                </li>
	                <li>
	                    <strong>回帖管理</strong>
	                </li>
	            </ol>
	        </div>
	    </div>-->
		<div class="wrapper wrapper-content animated">
			<div class="ibox float-e-margins">
				<!-- Panel Style -->
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>回帖管理</h5>
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
	                                <input id="search_name" style="width: 240px;" placeholder="请输入发帖者姓名或帖子标题" type="text"  class="form-control" >
	                                <select style="width: 150px;" class="form-control" name = "communityid"
                                    id= "communityid" required="true">
                                        <option selected="" value = "">请选择社区</option>
                                        <?php if(is_array($cname)): $i = 0; $__LIST__ = $cname;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$volist): $mod = ($i % 2 );++$i;?><option value="<?php echo ($volist["id"]); ?>"><?php echo ($volist["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
									<select style="width: 160px;" class="form-control" name = "status"
                                    id= "status" required="true">
                                        <option selected="" value = "">请选择审核状态</option>
                                        <option value="0">待审核</option>
                                        <option value="1">通过审核</option>
                                        <option value="-1">未通过审核</option>
                                        <!-- 审核状态(0_待审黑,1_通过审核,-1_审核未通过，默认为通过审核 -->
                                        </volist>
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
		                                    <!-- <button type="button" class="btn btn-outline btn-default J_batchDelete"  data-uri="<?php echo U('ajax_delete');?>">
		                                        <i class="glyphicon glyphicon-trash" aria-hidden="true"></i>
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
			var url = "<?php echo U('ajax_getList',array('key'=>'2'));?>?id=<?php echo ($_GET['id']); ?>";
			// 搜索 （根据条件重新加载数据）
			function searchSubmit(){
				$("#tablelist").bootstrapTable("removeAll");
				$('#tablelist').bootstrapTable('refresh', {
					silent: true, 
					query: {
						name: $("#search_name").val(),
						status: $("#status").val(),
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
						field: 'title',
						sortable: true,
						title: '帖子主题',
						align: 'left',
						formatter: function(value){
							if (!empty(value)) {
								var str = "";
								str = value.substr(0,5);
								return str;
							}

						}
					},
					{
						field: 'status',
						sortable: true,
						title: '帖子审核状态',
						align: 'left',
						formatter: function(value){
							var return_text = "";
							if (value==1) {
								return_text = "通过审核";
							}else if (value==-1) {
								return_text = "<font style='color:red;'>审核未通过</font>";
							}else {
								return_text = "待审核";
							}
							return return_text;
						}
					},
					{
						field: 'content',
						sortable: true,
						title: '回复内容',
						align: 'left',
						formatter: function(value){
							if (!empty(value)) {
								var str = "";
								str = value.substr(0,5);
								return str;
							}

						}
					},
					{
						field: 'addtime',
						sortable: true,
						title: '回复时间',
						align: 'left'
						// formatter: function(value,row,index){
						// 	return UnixTimeToDate(value,"y-m-d H:i");
						// }
					},
					{
						field: 'cname',
						sortable: true,
						title: '所在社区',
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
							var name = row.uname;
							//操作显示代码
							var strs = "";
							//修改审核状态
							var oldstatus = row.status;
							var newstatus = -1;
							// 审核状态(0_待审核,1_通过审核,-1_审核未通过，默认为通过审核)
							var status_text = "不通过审核";
							if (oldstatus != 1) {
								newstatus = 1;
								status_text = "通过审核";
							}
							var edit_filed_url = "<?php echo U('ajax_field_edit');?>";
							var unset = "<a data-val='" + newstatus + "' data-id='" + id + "' href='javascript:void(0)' data-uri='" + edit_filed_url + "'   onclick='updateField(this)' data-msg='你确认要修改" + name + "的审核状态吗？' >" + status_text + "</a>";
							strs += " " + unset;

							//查看详情
							var detUrl = "<?php echo U('detailtwo');?>?id=" + id;
							var detail = '&nbsp;<a href="javascript:void(0);" onclick="showDialog(this)"  data-uri="' + detUrl + '" data-title="详情" data-width="800" data-height="600" >查看详情</a>&nbsp;';
							strs += " " + detail;
							return strs;
						}
					}]
				}));
				/***初始化数据表格 end***/
			});
		</script>
	</body> 
</html>