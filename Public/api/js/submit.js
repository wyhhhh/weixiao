/* submit.js */
/**
 * **********************后台操作JS************************
 *J_main_form_add 添加
 *J_main_form_edit 修改
 */
var index = 0;
$(function(){
	//表单
	$("#J_main_form").Validform({
		tiptype:2,
		callback:function(form){
			var submit_stop = $("#submit_stop").val();//检查不允许提交标识，为1不执行提交
			if(submit_stop=="1"){
				return false;
			}
			index = layer.load(2);
			$('#J_main_form').ajaxSubmit({success:J_from,dataType:'json'});
			return false;
		}
	});
    // $('.skin-minimal input').iCheck({
    //     checkboxClass: 'icheckbox-blue',
    //     radioClass: 'iradio-blue',
    //     increaseArea: '20%'
    // }); 
	
});

//成功后调用的方法（成功;result.status == 1）
function J_from(result){
	layer.close(index);
	if (result.status == '1') 
	{
	    layer.confirm('操作成功！', {
	        closeBtn: 0,
	     	icon: 1,
	        btn: ['确定'] //按钮
	    },
	    function() {
	    	var isp = $('#J_main_form').attr('data-isp');
	    	if(isp == 0){
	    		//查看是否有successUrl
	    		var successUrl = $('#J_main_form').attr("data-successUrl");
	    		if (!empty(successUrl)) {
	    			window.location = successUrl;
	    			return;
	    		}
	        	document.location.reload();
	    	}
	    	else
	    	{
		        parent.document.location.reload();	
	    	}
	    });
	} 
	else {
	    layer.msg(result.msg, {
	        icon: 2,
	        closeBtn: 0,
	        time: 2000
	    }); 
	}
} 

function empty (str) {
	return (str == null || str == undefined || str == '' || typeof(str) == undefined);
}

$(function (){
	//下拉框填充数据
	if ($(".J_select").length > 0){
		$(".J_select").each(function(){
			var me = $(this); 	
		    var url = me.attr("data-url");
		    var selid = me.attr("data-sel");
		    $.post(url, function(res) {
		        var data = res.data;
		        //是否为Pid,如果是信息下的情况，第一个分类的value则为空，否则则为0
		        var ispid = me.attr('data-ispid');
		        var val1 = "0";
		        if(ispid == "0"){
		        	val1 = "";
		        }
		        me.append("<option value='"+val1+"'>请选择分类</option>");
	            //遍历option
	            $.each(data,function(key, val) {
	            	var issel = "";
	            	if (selid == val.id) {
	            		issel = " selected='selected' ";
	            	} 
	            	// alert(val.name);
	                var html = "<option " + issel + " value='" + val.id + "'>" + val.html + val.name + "</option>";
	                me.append(html);
	            }); 
		    });  
		});
	}
});
