/* admin.js */
/**
 * **********************后台操作JS************************
 * ajax 状态显示
 * confirmurl 操作询问
 * showdialog 弹窗表单
 * attachment_icon 附件预览效果
 * preview 预览图片大图
 * cate_select 多级菜单动态加载
 * 
 * http://www.bbibm.com
 * author: rex
 */
; $(function ($) {
    ////AJAX请求效果
    //$('#').ajaxStart(function () {
    //    $(this).show();
    //}).ajaxSuccess(function () {
    //    $(this).hide();
    //});

    //确认操作
    $('.J_confirmurl').bind('click', function () {
        var self = $(this),
		uri = self.attr('data-uri'),
		acttype = self.attr('data-acttype'),
		title = (self.attr('data-title') != undefined) ? self.attr('data-title') : "",
		msg = self.attr('data-msg'),
		callback = self.attr('data-callback');

        layer.confirm(msg, function (index) {
            var me = $(this);
            if (acttype == 'ajax') {
                $.get(uri, function (result) {
                    if (result.status == 1) {
                        //me.parents("tr").remove();
                        layer.msg(result.msg, {
                            icon: 1,
                            time: 1000
                        });
                        if (callback != undefined) {
                            eval(callback + '(self)');
                        } else {
                            window.location.reload();
                        }
                    } else {
                        layer.msg(result.msg, {
                            icon: 2,
                            time: 1000
                        });
                    }
                });
            }
            else {
                location.href = uri;
            }
        });
    });

    //弹窗表单
    $('.J_showdialog').bind('click', function () {
        var self = $(this),
			dtitle = self.attr('data-title'),
			did = self.attr('data-id'),
			duri = self.attr('data-uri'),
			dwidth = parseInt(self.attr('data-width')),
			dheight = parseInt(self.attr('data-height')),
			dpadding = (self.attr('data-padding') != undefined) ? self.attr('data-padding') : '',
			dcallback = self.attr('data-callback'),
            full = self.attr('data-full');
        //全屏时显示全屏
        if (full == "1") {
            var index = layer.open({
                type: 2,
                title: dtitle,
                content: duri,
                fix: true,
                maxmin: false
            });
            layer.full(index);
        }
        else
        { 
            //var index = layer_show(dtitle, duri, dwidth ? dwidth : 'auto', dheight ? dheight : 'auto');
            //layer.full(index);全屏
            var windowHight = ($(window).height() - 50);
            if (dheight > windowHight) {
                dheight = windowHight;
            } 
            var windowWidth = ($(window).width() - 50);
            if (dwidth > windowWidth) {
                dwidth = windowWidth;
            }
            var index = layer.open({
                type: 2,
                area: [dwidth+'px', dheight +'px'],
                fix: true, //固定,
                maxmin: false,
                shade:0.4,
                title: dtitle,
                content: duri
            });
        }
        return false;
    });

    $('.J_attachment_icons').bind('mouseover', function () {
        var ftype = $(this).attr('file-type');
        var rel = $(this).attr('file-rel');
        switch (ftype) {
            case 'image':
                if (!$(this).find('.attachment_tip')[0]) {
                    $('<div class="attachment_tip" style="width:160px; height:80px;"><img width="160" height="80" src="' + rel + '" /></div>').prependTo($(this)).fadeIn();
                } else {
                    $(this).find('.attachment_tip').fadeIn();
                }
                break;
        }
    }).bind('mouseout', function () {
        $('.attachment_tip').hide();
    });
});

(function ($) {
    //联动菜单
    $.fn.cate_select = function (options) {
        var settings = {
            field: 'J_cate_id',
            mode: 1,//1、id值  2、项值 3、所有项值-隔开
            top_option: '--请选择--',
            id: 'id',
            title: 'title',
            after: false,//后置操作
            split: '-'
        };
        if (options) {
            $.extend(settings, options);
        }

        var self = $(this),
            pid = self.attr('data-pid'),
            level = self.attr('data-level');
        uri = self.attr('data-uri'),
        selected = self.attr('data-selected'),
        selected_arr = [];
        if (selected != undefined && selected != '0') {
            if (selected.indexOf(settings.split)) {
                selected_arr = selected.split(settings.split);
            } else {
                selected_arr = [selected];
            }
        }
        self.nextAll('.J_cate_select').remove();
        $('<option value="">--' + settings.top_option + '--</option>').appendTo(self);
        $.getJSON(uri, { id: pid, level: level }, function (result) {
            if (result.status == '1') {
                for (var i = 0; i < result.data.length; i++) {
                    $('<option value="' + result.data[i][settings.id] + '">' + result.data[i][settings.title] + '</option>').appendTo(self);
                }
            }
            if (selected_arr.length > 0) {
                //IE6 BUG
                setTimeout(function () {
                    if (settings.mode == 3) {
                        self.find('option:contains("' + selected_arr[0] + '")').attr("selected", true);
                    } else {
                        self.find('option:[value="' + selected_arr[0] + '"]').attr("selected", true);
                    }
                    self.trigger('change');
                }, 1);
            }
        });

        var j = 1;
        $('.J_cate_select').die('change').bind('change', function () {
            var _this = $(this),
            _pid = _this.val();
            _level = parseInt(_this.attr('data-level')) + 1;
            _this.nextAll('.J_cate_select').remove();
            if (_pid != '') {
                $.getJSON(uri, { id: _pid, level: _level }, function (result) {
                    if (result.status == '1') {

                        var _childs = $('<select class="J_cate_select mr10" data-pid="' + _pid + '" data-level="' + _level + '"><option value="">--' + settings.top_option + '--</option></select>')
                        if (result.data == null) return;
                        for (var i = 0; i < result.data.length; i++) {
                            $('<option value="' + result.data[i][settings.id] + '">' + result.data[i][settings.title] + '</option>').appendTo(_childs);
                        }
                        _childs.insertAfter(_this);
                        if (selected_arr[j] != undefined) {
                            //IE6 BUG
                            //setTimeout(function(){
                            if (settings.mode == 3) {
                                _childs.find('option:contains("' + selected_arr[j] + '")').attr("selected", true);
                            } else {
                                _childs.find('option:[value="' + selected_arr[j] + '"]').attr("selected", true);
                            }
                            _childs.trigger('change');
                            //}, 1);
                        }
                        j++;
                    }
                });
                $('#' + settings.field).val(_pid);
                if (settings.mode == 3) {
                    var val = "";
                    $('.J_cate_select').each(function () {
                        val += "-" + $(this).find("option:selected").html();
                    })
                    if (val.length > 0) {
                        val = val.substring(1);
                    }
                    $('#' + settings.field).val(val);
                }
            } else {
                $('#' + settings.field).val(_this.attr('data-pid'));
                if (settings.mode == 3) {
                    var val = "";
                    $('.J_cate_select').each(function () {
                        val += "-" + $(this).find("option:selected").html();
                    })
                    if (val.length > 0) {
                        val = val.substring(1);
                    }
                    $('#' + settings.field).val(val);
                }
            }
            if (settings.after != false) {
                settings.after(_pid, _level, _this.find("option:selected").html());
            }
        });
    }
})(jQuery);

/*
* @name 列表操作(排序，修改值，状态切换，批量操作)
* @url http://www.bbibm.com
*/
(function ($) {
    $.fn.listTable = function (options) {
        var self = this,
            local_url = window.location.search,
            settings = {
                url: $(self).attr('data-acturi')
            }
        if (options) {
            $.extend(settings, options);
        }
        //整理排序
        var params = local_url.substr(1).split('&');
        var sort, order;
        for (var i = 0; i < params.length; i++) {
            var param = params[i];
            var arr = param.split('=');
            if (arr[0] == 'sort') {
                sort = arr[1];
            }
            if (arr[0] == 'order') {
                order = arr[1];
            }
        }
        //历史排序
        $('th.sorting', $(self)).each(function () {
            if ($(this).attr('data-field') == sort) {
                if (order == 'asc') {
                    $(this).attr('data-order', 'asc');
                    $(this).addClass("sorting_asc");
                } else if (order == 'desc') {
                    $(this).attr('data-order', 'desc');
                    $(this).addClass("sorting_desc");
                }
            }
        });//.addClass('sort_th');
        //排序
        //.sorting_asc
        //.sorting_desc
        $('th.sorting', $(self)).bind('click', function () {
            var s_name = $(this).attr('data-field'),
                s_order = $(this).attr('data-order'),
                sort_url = (local_url.indexOf('?') < 0) ? '?' : '';
            sort_url += '&sort=' + s_name + '&order=' + (s_order == 'asc' ? 'desc' : 'asc');
            local_url = local_url.replace(/&sort=(.+?)&order=(.+?)$/, '');
            location.href = local_url + sort_url;
            return false;
        });
        //更改状态
        $('a[data-tdtype="toggle"]', $(self)).bind('click', function () {
            var img = this,
                // s_val = ($(img).attr('data-value')) == 0 ? 1 : 0,
                s_val = ($(img).attr('data-value')),
                s_name = $(img).attr('data-field'),
                s_id = $(img).attr('data-id'),
                s_msg = $(img).attr('data-msg');
            layer.confirm(s_msg, function (index) {
                $.getJSON(settings.url, { id: s_id, field: s_name, val: s_val }, function (result) {
                    if (result.status == 1) {
                        window.location.reload();
                    }
                    else
                    { 
                        layer.msg(result.msg, {
                            icon: 2,
                            time: 1000
                        }); 	
                    }
                });
            });
            return false;
        });
        ////修改
        //$('span[data-tdtype="edit"]', $(self)).bind('click', function () {
        //    var s_val = $(this).text(),
        //    s_name = $(this).attr('data-field'),
        //    s_id = $(this).attr('data-id'),
        //    width = $(this).width();
        //    $('<input type="text" class="lt_input_text" value="' + s_val + '" />').width(width).focusout(function () {
        //        $(this).prev('span').show().text($(this).val());
        //        if ($(this).val() != s_val) {
        //            $.getJSON(settings.url, { id: s_id, field: s_name, val: $(this).val() }, function (result) {
        //                if (result.status == 0) {
        //                    $.pinphp.tip({ content: result.msg, icon: 'error' });
        //                    $('span[data-field="' + s_name + '"][data-id="' + s_id + '"]').text(s_val);
        //                    return;
        //                }
        //            });
        //        }
        //        $(this).remove();
        //    }).insertAfter($(this)).focus().select();
        //    $(this).hide();
        //    return false;
        //});
        ////图片切换
        //$('img[data-tdtype="toggle"]', $(self)).bind('click', function () {
        //    var img = this,
        //        s_val = ($(img).attr('data-value')) == 0 ? 1 : 0,
        //        s_name = $(img).attr('data-field'),
        //        s_id = $(img).attr('data-id'),
        //        s_src = $(img).attr('src');
        //    $.getJSON(settings.url, { id: s_id, field: s_name, val: s_val }, function (result) {
        //        if (result.status == 1) {
        //            if (s_src.indexOf('disabled') > -1) {
        //                $(img).attr({ 'src': s_src.replace('disabled', 'enabled'), 'data-value': s_val });
        //            } else {
        //                $(img).attr({ 'src': s_src.replace('enabled', 'disabled'), 'data-value': s_val });
        //            }
        //        }
        //    });
        //    return false;
        //});
        ////切换
        //$('span[data-tdtype="toggle"]', $(self)).bind('click', function () {
        //    var img = this,
        //        s_val = ($(img).attr('data-value')) == 0 ? 1 : 0,
        //        s_name = $(img).attr('data-field'),
        //        s_id = $(img).attr('data-id');
        //    $.getJSON(settings.url, { id: s_id, field: s_name, val: s_val }, function (result) {
        //        if (result.status == 1) {
        //            window.location.reload();
        //        }
        //    });
        //    return false;
        //});
        //批量操作
        $('a[data-tdtype="batch_action"]').bind('click', function () {
            var btn = this;
            if ($('.J_checkitem:checked').length == 0) {
                layer.msg("请选择要操作的项目！", {
                    icon: 2,
                    time: 1000
                });
                //$.pinphp.tip({content:lang.plsease_select_rows, icon:'alert'});
                return false;
            }
            var ids = '';
            $('.J_checkitem:checked').each(function () {
                ids += $(this).val() + ',';
            });
            ids = ids.substr(0, (ids.length - 1));
            var uri = $(btn).attr('data-uri') + '?' + $(btn).attr('data-name') + '=' + ids,
                msg = $(btn).attr('data-msg'),
                acttype = $(btn).attr('data-acttype'),
                title = ($(btn).attr('data-title') != undefined) ? $(this).attr('data-title') : "";
            if (msg != undefined) {
                layer.confirm(msg, function (index) {
                    action();
                });
                //layer_show(title, duri, dwidth ? dwidth : 'auto', dheight ? dheight : 'auto');
                //$.dialog({
                //    id: 'confirm',
                //    title: title,
                //    width: 200,
                //    padding: '10px 20px',
                //    lock: true,
                //    content: msg,
                //    ok: function () {
                //        action();
                //    },
                //    cancel: function () { }
                //});
            } else {
                action();
            }

            function action() {
                if (acttype == 'ajax_form') {
                    var did = $(btn).attr('data-id'),
                        dwidth = parseInt($(btn).attr('data-width')),
                        dheight = parseInt($(btn).attr('data-height'));
                    $.dialog({
                        id: did,
                        title: title,
                        width: dwidth ? dwidth : 'auto',
                        height: dheight ? dheight : 'auto',
                        padding: '',
                        lock: true,
                        ok: function () {
                            var info_form = this.dom.content.find('#info_form');
                            if (info_form[0] != undefined) {
                                info_form.submit();
                                return false;
                            }
                        },
                        cancel: function () { }
                    });
                    $.getJSON(uri, function (result) {
                        if (result.status == 1) {
                            $.dialog.get(did).content(result.data);
                        }
                    });
                } else if (acttype == 'ajax') {
                    $.getJSON(uri, function (result) {
                        if (result.status == 1) {
                            layer.msg(result.msg, {
                                icon: 1,
                                time: 1000
                            });
                            //$.pinphp.tip({ content: result.msg });
                            window.location.reload();
                        } else {
                            layer.msg(result.msg, {
                                icon: 2,
                                time: 1000
                            });
                            //$.pinphp.tip({ content: result.msg, icon: 'error' });
                        }
                    });
                } else {
                    location.href = uri;
                }
            }
        });
    };
    

})(jQuery);

 