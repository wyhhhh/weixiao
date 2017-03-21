$(function () {
    //J_province 省
    //J_city 市
    //J_district 区县
    //获取地址
    var province = $("select.J_province");
    var city = $("select.J_city");
    var district = $("select.J_district");
    
    //初始化省市区
    if (province.length > 0) province.html('<option value="">请选择省</option>');
    if (city.length > 0) city.html('<option value="">请选择市</option>');
    if (district.length > 0) district.html('<option value="">请选择区县</option>');

	//存在省
    if (province.length > 0) {
    	//设置省选项
        setOptions(province);  
        //设置省选中
        setSelOption(province);
        //存在市
        if (city.length > 0) {
        	//绑定省事件，获取市选项
            province.change(function () {
                var me = $(this);
                var code = me.find('option:selected').data('code');
                //绑定市选项
                setOptions(city,2,code);
            	//设置默认选中项
            	setSelOption(city);
                //如果存在区县，则设置区县选项
                if (district.length > 0) district.html('<option value="">请选择区县</option>');
            });
            //再次设置省，为了重新出发选择改变时间
            setSelOption(province);
            if (district.length > 0) {
                city.change(function () {
                    var me = $(this);
                    var code = me.find('option:selected').data('code');
                    setOptions(district,3,code); 
                }); 
                setSelOption(province);
                setSelOption(city);
                setSelOption(district);
            }
        }
    }
});

//判断为空
function empty(str) {
    str = $.trim(str);
    return (str == "" || str == 'undefined' || str == "null" || str == null || typeof (str) == undefined);
}

function setSelOption(con) {
    var selName = con.attr('data-selname');
    var selCode = con.attr('data-selcode');
    if (!empty(selName)) {
        con.find('option[value='+selName+']').attr('selected',"selected");
        con.change();
    }
    else if(!empty(selCode)){
        con.find('option[data-code='+selCode+']').attr('selected',"selected");
        con.change();
    }
}
function setOptions() {
    var con = arguments[0];
    var type = arguments[1] ? arguments[1] : '1';
    var code = arguments[2] ? arguments[2] : "";
    var url = $("#hid_area").val();
    var message = "省";
    if (type == "2") {
        message = "市";
    }
    else if(type == "3")
    {
        message = "区县";
    }
    $.ajax(
    {
      url: url,
      async: false,
      data:{'type':type,code:code},
      success:function (res) {
            if (res.status == 1) {
                var data = res.msg;
                var strHtml = '<option value="">请选择' + message + '</option>';
                var sel = "";
                for (var i = 0; i < data.length; i++) {
                    var item = data[i]; 
                    strHtml += "<option data-code='" + item.code + "' value='" + item.name + "'>" + item.name + "</option>";
                }
                con.html(strHtml);
            }
            else
            {
                
            }
        }
    }); 
}