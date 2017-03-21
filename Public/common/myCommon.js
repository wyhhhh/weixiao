//$(function () {
//  //添加加载代码
//  $('body').append('<div id="loading"></div>');
//});

//自定义字典对象
function MyDic() {
    this.data = new Array();
    //写入
    this.put = function (key, value) {
        this.data[key] = value;
    };
    //返回
    this.get = function (key) {
        return this.data[key];
    };
    //移除
    this.remove = function (key) {
        this.data[key] = null;
    };
    //是否为空
    this.isEmpty = function () {
        return this.data.length == 0;
    };
    //长 度
    this.count = function () {
        return this.data.length;
    };
}

//退出登录
function loginOut(url) {
    layer.confirm("确认退出登录？", {
        btn: ['确认', '取消'] //按钮
    },
	function () {
	    //开启加载
	    var index = layer.load(2);
	    $.post(url,
        function (data) {
            layer.close(index); 
            top.location.href = data;
        });
	});
}

function editRow(event) {
	var me = $(event);
	var title = me.data('title');
	var url = me.data('url');
	var width = me.data('width');
	var height = me.data('height');
	alertWindow(document, url, title, width, height);
}

function editField(event) {
    var me = $(event);
    var msg = me.data('msg');
    var id = me.data('id');
    var status = me.data('status');
    var url = me.data('url');
    //询问框
    layer.confirm(msg, {
        btn: ['确认', '取消'] //按钮
    },
		function () {
		    //开启加载
		    var index = layer.load(2);
		    $.post(url, { id: id, status: status }, function (res) {
		        layer.close(index);
		        if (res.status == 1) {
                    if (typeof (search) != undefined) {
                        search();
                    }
		            layer.msg("操作成功！", {
		                icon: 1,
		                closeBtn: 0,
		                scrollbar: true,
		                time: 2000
		            });
		        }
		        else {
		            layer.msg(res.msg, {
		                icon: 2,
		                closeBtn: 0,
		                time: 2000
		            });
		        }
		    })
		}
	);
}

function deleteData(url, id) {
	
    //开启加载
    index = layer.load(2);
    $.post(url, {
        id: id
    },
        function (data) {
            layer.close(index);
            if (data.status == 1) {
                layer.msg("删除成功！", {
                    icon: 1,
                    scrollbar: false,
                    closeBtn: 0,
                    time: 2000
                });
                if (typeof (search) != undefined) {
                    search();
                }
            }
            else {
                layer.msg(data.msg, {
                    icon: 2,
                    closeBtn: 0,
                    time: 2000
                });
            }
        });
}
//删除提交数据
function deleteOne(event) {
    var me = $(event);
    var url = me.data('url');
    var msg = me.data('msg');
    var id = me.data("id");
    //询问框
    layer.confirm(msg, {
        btn: ['确认', '取消'] //按钮
    }, function () {
        deleteData(url, id);
    }
	);
}


/*公用验证方法开始*/
//验证密码
function ckPwd(str) {
    var myreg = /^[0-9a-z]{6,20}$/;
    return myreg.test(str);
}

//验证邮箱
function ckMail(str) {
    var myreg = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/;
    return myreg.test(str);
}

//判断为空
function empty(str) {
    str = $.trim(str);
    return (str == "" || str == 'undefined' || str == "null" || str == null || typeof (str) == undefined);
}

//验证是否为中文名
function ckName(str) {
    var reg = /^[\u4E00-\u9FA5]+$/; //全都是汉字 
    return reg.test(str);
}

//验证邮政编码
function ckCode(str) {
    var reg = /^[1-9][0-9]{5}$/;
    return reg.test(str);
}

//验证手机号码
function ckPhone(str) {
    var regPartton = /^(1)[0-9]{10}$/;
    return regPartton.test(str);
}

//验证电话
function ckTel(str) {
    var reg = /^[(]?0\d{2,3}[)]?\s*[-]?\s*\d{7,8}$/; //010-87989898 01098989898 (0712)8989898 010 - 23343434 这些格式的座机号码都满足  
    return reg.test(str);
}

//验证用户名
function ckUsername(str) {
    var myreg = /^[0-9a-z]{4,16}$/;
    return myreg.test(str);
}
//验证码
function ckYzm(str) {
    var myreg = /^[0-9.-]{6}$/;
    return myreg.test(str);
}

//验证数字
function ckNum(num) {
    return /^[0-9-]+$/.test(num);
}

//验证QQ
function ckQq(argument) {
    return /^[1-9]{1}[0-9]{4,10}$/.test(argument);
}

//验证QQ
function ckPrice(argument) {
    return /^\d{1,10}(\.\d{1,2})?$/.test(argument);
}

//验证链接
function ckUrl(url) {
	var reg = '(((^https?:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)$';
//	var reg = "/^((https|http|ftp|rtsp|mms)?://)?" // 
//       + "(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" // ftp的user@ 
//       + "(([0-9]{1,3}.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184
//       + "|" // 允许IP和DOMAIN（域名） 
//       + "([0-9a-z_!~*'()-]+.)*" // 域名- www. 
//       + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]." // 二级域名 
//       + "[a-z]{2,6})" // first level domain- .com or .museum 
//       + "(:[0-9]{1,4})?" // 端口- :80 
//       + "((/?)|" // a slash isn't required if there is no file name 
//       + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$/";
    reg = new RegExp(url,'g');
   	return reg.test(url); 
}
/*公用验证方法结束*/

//iframe 高度自适应
function dyniframesize(down) {
    var pTar = null;
    if (document.getElementById) {
        pTar = document.getElementById(down);
    }
    else
    {
        eval('pTar = ' + down + ';');
    }
    if (pTar && !window.opera) {
        //begin resizing iframe 
        pTar.style.display = "block"
        if (pTar.contentDocument && pTar.contentDocument.body.offsetHeight) {
            //ns6 syntax 
            pTar.height = pTar.contentDocument.body.offsetHeight + 20;
            //          pTar.width = pTar.contentDocument.body.scrollWidth + 20;
        } else if (pTar.Document && pTar.Document.body.scrollHeight) {
            //ie5+ syntax 
            pTar.height = pTar.Document.body.scrollHeight;
            //          pTar.width = pTar.Document.body.scrollWidth;
        }
    }
}

/*
* 弹出窗口
* doc top层所在的文档 (如:parent.parent.document)
* url 跳转的地址
* title 窗口的标题
* width 窗口的宽度
* height 窗口的高度
* fun 关闭窗口之前回调的方法（可return false阻止关闭）
* id 弹出层的div的id
*/
function alertWindow() {
    //doc, url, title, width, height, fun,id
    //获取参数
    var doc = arguments[0] ? arguments[0] : "";
    var url = arguments[1] ? arguments[1] : "";
    var title = arguments[2] ? arguments[2] : "";
    var width = arguments[3] ? arguments[3] : "";
    var height = arguments[4] ? arguments[4] : "";
    var fun = arguments[5] ? arguments[5] : function () { };
    var id = arguments[6] ? arguments[6] : "win";
    var maxHeight = $(window).height();
    if (maxHeight < height) {
        height = maxHeight;
    }
    var maxWeight = $(window).width();
    if (maxWeight < width) {
        width = maxWeight;
    }
    var ifrname = "ifr_" + id;
    strHtml = '<div id="' + id + '"><iframe name = "' + ifrname + '" id = "' + ifrname + '" src="' + url + '"  frameborder="0" marginheight="0" marginwidth="0" width="100%" frameborder="0" scrolling="no" onload="javascript:dyniframesize(\'' + ifrname + '\');"  width="100%"></iframe></div>';
    $('body', doc).append(strHtml);
    $('#' + id, doc).window({
        title: title,
        minimizable: false,//是否显示最小化按钮
        width: width + "px",
        height: height + "px",
        draggable: true,
        maximizable: false,//是否显示最大化按钮
        modal: true,
        resizable: false,//改变大小
        onClose: function () {
            //移除
            $("#" + id, doc).remove();
        },
        onBeforeClose: fun
    });

    //获取到蒙版层
    var mask = $(".window-mask", doc);
    //获取到框架
    var win = $(".panel.window", doc);
    var length = mask.length;
    //弹出两层及以上
    if (length > 1) {
        //获取倒数第二个
        var l2_win = win.eq(-2);
        //获取倒数第二个 蒙版
        var l2_mask = mask.eq(-2);
        //获取最后一个元素
        var l1_win = win.eq(-1);
        //设置最后一个在倒数第二个上
        var l2_zindex = l2_win.css("z-index");
        l2_zindex = parseInt(l2_zindex);
        //获取最后一个元素 蒙版
        var l1_mask = mask.eq(-1);
        l1_mask.css('z-index', l2_zindex + 1);
        l1_win.css('z-index', l2_zindex + 2);
        //
        l1_win.next(".window-shadow").css('z-index', "999");

        win = l1_win;

        mask = l1_mask;

    }
    else {
        mask.css('z-index', '9000');
        win.css('z-index', '9001');
        win.next(".window-shadow").css('z-index', "999");

        //关闭按钮设置 标签id
        win.find(".panel-tool-close").attr("id", "close_" + id);
    }
    //关闭按钮设置 标签id
    win.addClass("my_window_" + id);
    //关闭 
    win.next('.window-shadow').addClass("my_window_" + id);

    //关闭按钮设置 标签id
    mask.addClass("my_window_" + id);
}



/*获取指定弹出层的iframe文档
* pdoc 弹出窗口的父级文档（要找的ifream的文档） 默认值:parent.document
* ifrid 弹出层id（与alertWindow对应）,默认值：win
* return 返回document对象
*/
function getIframeDoc() {
    var pdoc = arguments[0] ? arguments[0] : parent.document;
    var ifrid = arguments[1] ? arguments[1] : "ifr_win";
    var isalert = arguments[2] ? arguments[2] : "1";
    if (isalert == "1") {
        ifrid = "ifr_" + ifrid;
    }
    var doc;
    if (document.all) {
        //IE 
        doc = pdoc.frames[ifrid].document;
    }
    else {
        //Firefox 
        doc = pdoc.getElementById(ifrid).contentDocument;
    }
    return doc;
}

/*关闭指定文档的  弹出窗口
* doc 弹出窗口的文档 如:parent.document
* id 弹出层id （与alertWindow对应）,默认值：win
* selector 关闭时，需要模拟点击的控件的选择器如(.btn)
*/
function closeWindow() {
    //获取参数
    var doc = arguments[0] ? arguments[0] : parent.document;
    var id = arguments[1] ? arguments[1] : "win";
    var selector = arguments[2] ? arguments[2] : "";
//  var refdoc = arguments[3] ? arguments[3] : "";
    // alert("#close_" + id);
    // alert($("#close_" + id, doc).parent().html());
    //$("#close_" + id, doc).trigger('click');
    //alert($(".my_window_"+id,doc).length);
    if (selector != "") {
        try {
            $('' + selector, doc).trigger("click");
        }
        catch (e) { }
    }
//  alert($(".my_window_" + id, doc).length);
    $(".my_window_" + id, doc).remove();
    $('#' + id).remove();
    //$('#btn_closewindow').remove();
    // $("body", doc).append("<input type='button' id='btn_closewindow' style='display:none' value='" + id + "' onclick='btn_closeWindow(this)'/>");
    // $('#btn_closewindow', doc).trigger("click");
}

function btn_closeWindow(e) {
    var id = $(e).val();
    $('#' + id).window("close");
    $('#btn_closewindow').remove();
}

/*关闭指定文档的  弹出窗口 并且刷新页面
* doc 弹出窗口的文档 如:parent.document
*/
function closeWindowAndReload(doc) {
    var doc = arguments[0] ? arguments[0] : parent.document;
    var id = arguments[1] ? arguments[1] : "win";
    var clickid = arguments[2] ? arguments[2] : "";
    var refdoc = arguments[3] ? arguments[3] : "";
    if (clickid != "") {
        try {
            $('#' + clickid, refdoc).trigger("click");
        }
        catch (e) { }
    }
    $(".my_window_" + id, doc).remove();
    $('#' + id).remove();
    //$("body", doc).append("<input type='button' id='btn_closewindowandreload' style='display:none' onclick='btn_closeWindowAndReload()'/>");
    //$('#btn_closewindowandreload', doc).trigger("click");
}
function btn_closeWindowAndReload(e) {
    var id = $(e).val();
    $('#' + id).window("close");
    //$('#win').window("close");
    $('#gridlist').datagrid('reload');//重新加载
    $('#btn_closewindowandreload').remove();
}

/*
 * 时间戳格式化为yyyy-mm-dd 返回例如：2015-12-15
*/
function date_format(timestamp) {
    //验证是否为数字
    // if(!/^[0-9]{1,}$/.test(timestamp)){
    // 	return timestamp;
    // }
    //    var myDate = new Date(parseInt(timestamp)*1000);
    //    var strDate = myDate.toLocaleDateString().replace(/\//g, "-");//获取当前日期yyyy-mm-dd
    //    return strDate;
    if (!/^[0-9]{1,}$/.test(timestamp)) {
        return timestamp;
    }
    var myDate = new Date(parseInt(timestamp) * 1000);
    var y = myDate.getFullYear();//获取完整的年份(4位,1970-????)
    var m = myDate.getMonth() + 1; //获取当前月份(0-11,0代表1月)
    var d = myDate.getDate(); //获取当前日(1-31)
    if (m < 10) { m = "0" + m; }
    if (d < 10) { d = "0" + d; }
    var strDate = y + "-" + m + "-" + d;
    return strDate;
}
//alert(date_format2("1432716262"));
/*
 * 时间戳格式化为yyyy-mm-dd HH:mm:ss 返回例如：2015-12-12 15:00
*/
function date_format2(timestamp) {
    //验证是否为数字
    if (!/^[0-9]{1,}$/.test(timestamp)) {
        return timestamp;
    }
    var myDate = new Date(parseInt(timestamp) * 1000);
    //获取当前时间
    // var date = new Date(+new Date()+8*3600*1000).toISOString().replace(/T/g,' ').replace(/\.[\d]{3}Z/,'')

    var y = myDate.getFullYear();//获取完整的年份(4位,1970-????)
    var m = myDate.getMonth() + 1; //获取当前月份(0-11,0代表1月)
    var d = myDate.getDate(); //获取当前日(1-31)
    var h = myDate.getHours(); //获取当前小时数(0-23)
    var mm = myDate.getMinutes(); //获取当前分钟数(0-59)
    var s = myDate.getSeconds(); //获取当前秒数(0-59)
    if (m < 10) { m = "0" + m; }
    if (d < 10) { d = "0" + d; }
    if (h < 10) { h = "0" + h; }
    if (mm < 10) { mm = "0" + mm; }
    if (s < 10) { s = "0" + s; }
    var strDate = y + "-" + m + "-" + d + " " + h + ":" + mm;//+":"+s;
    return strDate;
}

/*
 * 时间戳格式化为HH:mm:ss 返回例如：15:00:00
*/
function date_format3(timestamp) {
    //验证是否为数字
    if (!/^[0-9]{1,}$/.test(timestamp)) {
        return timestamp;
    }
    var myDate = new Date(parseInt(timestamp) * 1000);
    var h = myDate.getHours(); //获取当前小时数(0-23)
    var mm = myDate.getMinutes(); //获取当前分钟数(0-59)
    var s = myDate.getSeconds(); //获取当前秒数(0-59)
    if (h < 10) { h = "0" + h; }
    if (mm < 10) { mm = "0" + mm; }
    if (s < 10) { s = "0" + s; }
    var strDate = h + ":" + mm + ":" + s;
    return strDate;
}

/*
 * 时间格式化为时间戳 返回例如：1412365887780
*/
function format_date(fmdate) {
    var date = new Date(fmdate);
    var timestamp = (date.getTime()) / 1000;
    return timestamp;
}

/*将时间戳转换为时间
* time 时间戳
* format 格式化,年:y,月:m,日:d,时:h,分:i,秒s,如:(y年m-d h:i:s);
* 返回格式化好的时间
*/
function UnixTimeToDate(time, format) {
    var v = time;
    if (/^(-)?\d{1,10}$/.test(v)) {
        v = v * 1000;
    } else if (/^(-)?\d{1,13}$/.test(v)) {
        v = v * 1;
    } else {
        return '-';
    }
    var dateObj = new Date(v);
    // if (dateObj.format('yyyy') == "NaN") { /*alert("时间戳格式不正确");*/return; }
    var y = dateObj.getFullYear();
    var m = (dateObj.getMonth() + 1);
    if (m < 10) {
        m = "0" + m;
    }
    var d = dateObj.getDate();
    if (d < 10) {
        d = "0" + d;
    }
    var h = dateObj.getHours();
    if (h < 10) {
        h = "0" + h;
    }
    var i = dateObj.getMinutes();
    if (i < 10) {
        i = "0" + i;
    }
    var s = dateObj.getSeconds();
    if (s < 10) {
        s = "0" + s;
    }
    format = format.replace('y', y);//替换年-小写
    format = format.replace('Y', y);//替换年-大写
    format = format.replace('m', m);//替换月-小写
    format = format.replace('M', m);//替换月-大写
    format = format.replace('d', d);//替换日-小写
    format = format.replace('D', d);//替换日-小写
    format = format.replace('h', h);//替换时-小写
    format = format.replace('H', h);//替换时-小写
    format = format.replace('i', i);//替换分-小写
    format = format.replace('I', i);//替换分-小写
    format = format.replace('s', s);//替换秒-小写
    format = format.replace('S', s);//替换秒-小写
    //var UnixTimeToDate = dateObj.getFullYear() + '/' + (dateObj.getMonth() + 1) + '/' + dateObj.getDate() + ' ' + dateObj.getHours() + ':' + dateObj.getMinutes() + ':' + dateObj.getSeconds();
    return format;
}



/**       
 * 对Date的扩展，将 Date 转化为指定格式的String       
 * 月(M)、日(d)、12小时(h)、24小时(H)、分(m)、秒(s)、周(E)、季度(q) 可以用 1-2 个占位符       
 * 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)       
 * eg:       
 * (new Date()).pattern("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423       
 * (new Date()).pattern("yyyy-MM-dd E HH:mm:ss") ==> 2009-03-10 二 20:09:04       
 * (new Date()).pattern("yyyy-MM-dd EE hh:mm:ss") ==> 2009-03-10 周二 08:09:04       
 * (new Date()).pattern("yyyy-MM-dd EEE hh:mm:ss") ==> 2009-03-10 星期二 08:09:04       
 * (new Date()).pattern("yyyy-M-d h:m:s.S") ==> 2006-7-2 8:9:4.18       
 */
Date.prototype.pattern = function (fmt) {
    var o = {
        "M+": this.getMonth() + 1, //月份           
        "d+": this.getDate(), //日           
        "h+": this.getHours() % 12 == 0 ? 12 : this.getHours() % 12, //小时           
        "H+": this.getHours(), //小时           
        "m+": this.getMinutes(), //分           
        "s+": this.getSeconds(), //秒           
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度           
        "S": this.getMilliseconds() //毫秒           
    };
    var week = {
        "0": "/u65e5",
        "1": "/u4e00",
        "2": "/u4e8c",
        "3": "/u4e09",
        "4": "/u56db",
        "5": "/u4e94",
        "6": "/u516d"
    };
    if (/(y+)/.test(fmt)) {
        fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }
    if (/(E+)/.test(fmt)) {
        fmt = fmt.replace(RegExp.$1, ((RegExp.$1.length > 1) ? (RegExp.$1.length > 2 ? "/u661f/u671f" : "/u5468") : "") + week[this.getDay() + ""]);
    }
    for (var k in o) {
        if (new RegExp("(" + k + ")").test(fmt)) {
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        }
    }
    return fmt;
}

$(function () {
    if (undefined != $.fn.validatebox) {

        //重写easyui 验证
        $.extend($.fn.validatebox.defaults.rules, {
            idcard: { // 验证身份证
                validator: function (value) {
                    return /^\d{15}(\d{2}[A-Za-z0-9])?$/i.test(value);
                },
                message: '身份证号码格式不正确'
            },
            length: {
                validator: function (value, param) {
                    var len = $.trim(value).length;
                    return len >= param[0] && len <= param[1];
                },
                message: "输入内容长度必须介于{0}和{1}之间."
            },
            phone: { // 验证电话号码
                validator: function (value) {
                    return /^((\d2,3)|(\d{3}\-))?(0\d2,3|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/i.test(value);
                },
                message: '格式不正确,请使用下面格式:023-88888888'
            },
            //
            //验证一个数字可以为负数，且保留两位小数
            negativeNum: {
                validator: function (value) {
                    //^-[1-9]d*$ 
                    return /^(-([1-9]+)|([1-9]+)|([0-9]+\.[0-9]{1,2}))$/.test(value);
                },
                message: '格式不正确'
            },
            mobile: { // 验证手机号码
                validator: function (value) {
                    return /^(13|15|18|17|14)\d{9}$/i.test(value);
                },
                message: '手机号码格式不正确'
            },
            tel: { // 验证手机号码或座机号码
                validator: function (value) {
                    return /^(13|15|18|17|14)\d{9}$/i.test(value) || /^[(]?0\d{2,3}[)]?\s*[-]?\s*\d{7,8}$/.test(value);
                },
                message: '电话格式不正确'
            },
            intOrFloat: { // 验证整数或小数
                validator: function (value) {
                    return /^\d+(\.\d+)?$/i.test(value);
                },
                message: '请输入数字，并确保格式正确'
            },
            price: { // 验证货币
                validator: function (value) {
                    return /^\d{1,10}(\.\d{1,2})?$/i.test(value);
                },
                message: '格式不正确,请保留两位小数'
            },
            loseprice: { // 验证货币
                validator: function (value) {
                    return /^(-){0,1}\d{1,10}(\.\d{1,2})?$/i.test(value);
                },
                message: '格式不正确,请保留两位小数'
            },
            qq: { // 验证QQ,从10000开始
                validator: function (value) {
                    return /^[1-9]\d{4,9}$/i.test(value);
                },
                message: 'QQ号码格式不正确'
            },
            integer: { // 验证整数 可正负数
                validator: function (value) {
                    return /^[+]?[0-9]+\d*$/i.test(value);
                    //return /^([+]?[0-9])|([-]?[0-9])+\d*$/i.test(value);
                },
                message: '请输入整数'
            },
            age: { // 验证年龄
                validator: function (value) {
                    return /^(?:[1-9][0-9]?|1[01][0-9]|120)$/i.test(value);
                },
                message: '年龄必须是0到120之间的整数'
            },
            chinese: { // 验证中文
                validator: function (value) {
                    return /^[\Α-\￥]+$/i.test(value);
                },
                message: '请输入中文'
            },
            english: { // 验证英语
                validator: function (value) {
                    return /^[A-Za-z]+$/i.test(value);
                },
                message: '请输入英文'
            },
            unnormal: { // 验证是否包含空格和非法字符
                validator: function (value) {
                    return /.+/i.test(value);
                },
                message: '输入值不能为空和包含其他非法字符'
            },
            username: { // 验证用户名
                validator: function (value) {
                    return /^[a-zA-Z][a-zA-Z0-9_]{5,15}$/i.test(value);
                },
                message: '用户名不合法（字母开头，允许6-16字节，允许字母数字下划线）'
            },
            faxno: { // 验证传真
                validator: function (value) {
                    //            return /^[+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+$/i.test(value);
                    return /^((\d2,3)|(\d{3}\-))?(0\d2,3|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/i.test(value);
                },
                message: '传真号码不正确'
            },
            zip: { // 验证邮政编码
                validator: function (value) {
                    return /^[0-9]\d{5}$/i.test(value);
                },
                message: '邮政编码格式不正确'
            },
            ip: { // 验证IP地址
                validator: function (value) {
                    return /d+.d+.d+.d+/i.test(value);
                },
                message: 'IP地址格式不正确'
            },
            name: { // 验证姓名，可以是中文或英文
                validator: function (value) {
                    return /^[\Α-\￥]+$/i.test(value) | /^\w+[\w\s]+\w+$/i.test(value);
                },
                message: '请输入姓名'
            },
            date: { // 验证姓名，可以是中文或英文
                validator: function (value) {
                    //格式yyyy-MM-dd或yyyy-M-d
                    return /^(?:(?!0000)[0-9]{4}([-]?)(?:(?:0?[1-9]|1[0-2])\1(?:0?[1-9]|1[0-9]|2[0-8])|(?:0?[13-9]|1[0-2])\1(?:29|30)|(?:0?[13578]|1[02])\1(?:31))|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)([-]?)0?2\2(?:29))$/i.test(value);
                },
                message: '清输入合适的日期格式'
            },
            msn: {
                validator: function (value) {
                    return /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
                },
                message: '请输入有效的msn账号(例：abc@hotnail(msn/live).com)'
            },
            weburl: {
                validator: function (value) {
                    return ckUrl(value);
                },
                message: '请输入正确的网址'
            },
            same: {
                validator: function (value, param) {
                    if ($("#" + param[0]).val() != "" && value != "") {
                        return $("#" + param[0]).val() == value;
                    } else {
                        return true;
                    }
                },
                message: '两次输入的密码不一致！'
            }
        });

    }

});
