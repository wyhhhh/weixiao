var currentItem;
var currentUrl;
function clickShowSel(e) {
    var me = $(e);
    currentItem = $("#" + me.parent().attr('id'));
    currentUrl = currentItem.attr('data-url');//请求地址不可少
    var con = currentItem.find(".detail");
    if (con.css("display") == "none") {
        con.show();
        currentItem.find('.searchKey').focus();
    } else {
        con.hide();
    }
}
function propertychange(e) {
    //重新定位当前取的的id和url，解决同时存在两个请求的时候定位位置错误问题 begin
    var me = $(e);
    currentItem = $("#" + me.parent().parent().parent().attr('id'));
    currentUrl = currentItem.attr('data-url');//请求地址不可少
    //重新定位当前取的的id和url，解决同时存在两个请求的时候定位位置错误问题 end

    //此数据是用来作为附加条件的
    var currentCond = currentItem.data('cond');//若是存在一个查询附加的条件(这个是做添加用户选择社区后房间的筛选条件做的功能)
    if(currentCond==undefined||currentCond==""){currentCond="";}

    var keyword = $.trim($(e).val());
    if (keyword == "") {
        currentItem.find(".itemList").html('');
        oldkey = keyword;
        return;
    }
    if (oldkey == keyword) {
        return;
    }
    oldkey = keyword;
    currentItem.find(".loading").text("加载中...");
    $.post(currentUrl, {
        "key": keyword,
        "condition":currentCond
    },
    function(res) {
        if (res.status == 0) return;
        var data = res.msg;
        var strHtml = "";
        if (data.length == 0) {
            currentItem.find(".itemList").html('<li  class="active-result result-selected" style="" >未查询到结果</li>');
            currentItem.find(".loading").text("");
            return
        }
        for (var i = 0; i < data.length; i++) {
            var selStyle = "";
            if (i == 0) {
                selStyle = "highlighted"
            }
            var item = data[i];
            strHtml += '<li class="active-result result-selected ' + selStyle + '" style="" data-id="' + item.id + '" >' + item.name + '</li>'
        }
        currentItem.find(".itemList").html(strHtml);
        currentItem.find(".itemList li").hover(function() {
            currentItem.find(".highlighted").removeClass("highlighted");
            $(this).addClass("highlighted")
        });
        currentItem.find(".itemList li").click(function() {
            setItem();
        });
        currentItem.find(".loading").text("")
    });
    //$("#BranchName").val()
}

var oldkey = "";
function setItem() {
    var con = currentItem.find(".highlighted");
    var text = con.text();
    var data_id = con.data("id");
    currentItem.find(".selItem").text(text);
    //设置选中项
    currentItem.find(".area_data").val(text);
    currentItem.find(".area_data").data("id",data_id);
    currentItem.find(".detail").hide();
    //是否有回调函数
    if(typeof(setItemBack) !== "undefined"){
        setItemBack();
    }
}
function keyUp(e) {
    var currKey = 0,
    e = e || event;
    currKey = e.keyCode || e.which || e.charCode;
    var keyName = String.fromCharCode(currKey);
    if (currKey == 13) {
        if (currentItem.find(".highlighted").length > 0 && currentItem.find(".detail").css("display") != "none") 
        {
            setItem();
        }
    } else if (currKey == 38) {
        if (currentItem.find(".highlighted").length > 0 && currentItem.find(".detail").css("display") != "none") 
        {
            var highlighted = currentItem.find(".highlighted");
            highlighted.removeClass("highlighted");
            var prev = highlighted.prev("li");
            if (prev.length == 0) return;
            prev.addClass("highlighted")
        }
    } else if (currKey == 40) {
        if (currentItem.find(".highlighted").length > 0 && currentItem.find(".detail").css("display") != "none") 
        {
            var highlighted = currentItem.find(".highlighted");
            highlighted.removeClass("highlighted");
            var next = highlighted.next("li");
            if (next.length == 0) return;
            highlighted.next("li").addClass("highlighted")
        }
    }
}

$(function() {
    document.onkeyup = keyUp;
});