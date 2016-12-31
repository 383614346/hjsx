$(function(){
	//GetIsTip();
})

function GetIsTip(){
	AjaxGetIsTip();
	setInterval("AjaxGetIsTip()",60000);
}

function AjaxGetIsTip(){
	$.ajax({
        type: 'post',
        cache:false,
        url: '/Manage/TipInfo/AjaxGetIsTip',
        data: { "r": Math.random()},
        dataType: 'json',
        success: function (data) {
            if (ValidIsError(data)) {
                return;
            }
            var html = "";
            if(data.Data == 1){
            	html = '<img src="/Public/Images/Manage/TipInfo/HaveInformationIcon.gif" style="height: 28px;position: relative;top: -4px;" /><a href="javascript:SkipUrl(\'提醒列表\',\'/Manage/TipInfo/index.html\');">您有新的提醒</a>';
            }else{
            	html = '<img src="/Public/Images/Manage/TipInfo/NoInformationIcon.png" style="height: 28px;position: relative;top: -4px;" /><a href="javascript:SkipUrl(\'提醒列表\',\'/Manage/TipInfo/index.html\');">查看提醒</a>';
            }
            html += "&nbsp;&nbsp;&nbsp;";
            $("#spanTipInfoBox").html(html);
        },
        error: function (error) {
        	//alert("获取提醒信息失败。");
        }
    });
}