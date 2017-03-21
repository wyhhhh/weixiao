$(function () {
    var frm = $("#mainForm");
    if (frm.length > 0) {
        frm.submit(function () {
            var b = $(this).form('validate');
//          b = true;
            if (b) {
                //开启加载
                index = layer.load(2);
                $(this).ajaxSubmit({
                    success: function (data) {
                        layer.close(index);
                        if (data.status == 1) {
                            var successmsg = "操作成功！";
                            if (!empty($.trim(data['msg']))) {
                                successmsg = data['msg'];
                            }
                            index = layer.confirm(successmsg, {
                                closeBtn: 0,
                                icon: 1,
                                scrollbar: false,
                                btn: ['确定']//按钮
                            },
                            function ()
                            {
                                var isp = frm.attr('data-isp');
                                if (isp == 0) {
                                    //查看是否有successUrl
                                    var successUrl = frm.attr("data-successUrl");
                                    if (!empty(successUrl)) {
                                        window.location = successUrl;
                                        return;
                                    }
                                    document.location.reload();
                                }
                                else 
                                {
                                	frm[0].reset();
                                	//查询是否有列表，有的话，则重新加载列表。不刷新数据
                                	if($('#datalist',parent.document).length > 0)
                                	{
                                		var selector = ".pagination-load"
                                		if($('#btn_search',parent.document).length > 0){
                                			selector = "#btn_search";
                                		}
                                		closeWindow(parent.document,'win',selector);
                                	}
                                	else if ($('#datalist').length > 0)
                                	{
                                		var selector = ".pagination-load"
                                		if($('#btn_search').length > 0){
								            $('#btn_search').trigger("click");
                                		}
                                		else
                                		{
                                			$('#datalist').datagrid('load');
                                		}
                                	}
                                	else
                                	{
                                		parent.document.location.reload();
                                	}
                                }
                        		layer.close(index);
                            });
                        }
                        else {
                            layer.msg(data.msg, {
                                icon: 2,
                                closeBtn: 0,
                                time: 2000
                            });
                        }
                    },
                    //处理完成
                    //resetForm: true,
                    dataType: 'json'
                });
            }
            return false;
        });
    }
});