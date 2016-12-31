$(function(){
	InitTipBox();
	Init();
});

function Init(){
	//如果是iframe窗口
	var isIframe = $("#iframe").val();
	if(isIframe == 1 || isIframe == "1"){
		$("#btnCloseIframe").show();
		//调用回调方法
		var backfunction = $("#backfunction").val();
		if(backfunction != "" && backfunction != undefined && backfunction != "undefined"){
			$("#btnCloseIframe").attr("onclick","javascript:parent."+ backfunction);	
		}
	}
}

/**
 * 显示正在加载中的图片
 */
function ShowLoading(){
	var width = 200;
	var height = 50;
	$(".TipDivBox").width(width);
	$(".TipDivBox").height(height);
	$("#divLoading").show();
	$("#ifmContent").hide();
	var locationWidth = parseInt(width / 2);
	var locationHeight = parseInt(height / 2);
	$(".TipDivBox").css("margin","-"+ locationHeight + "px 0 0 -" + locationWidth + "px");
	$("#deleteBespeakTimeDescBox").show();
}
/**
 * 关闭加载中的图片
 */
function CloseLoading(){
	$("#divLoading").hide();
	$("#ifmContent").show();
	$("#deleteBespeakTimeDescBox").hide();
}
/**
 * 关闭iframe
 */
function CloseIframe(isreload){
	$("#deleteBespeakTimeDescBox").hide();
	$("#ifmContent").prop("src", '');
	if(!empty(isreload)){
		window.location.reload();
	}
}
/**
 * 显示iframe
 * @param width
 * @param height
 * @param url
 */
function ShowIframe(width,height,url,returnfunction){
	$(".TipDivBox").width(width);
	$(".TipDivBox").height(height);
	$("#ifmContent").show();
	$("#ifmContent").width(width);
	$("#ifmContent").height(height);
	//判断是否有传入url参数
	if(url.indexOf("?") > 0){
		url += "&iframe=1";
	}else{
		url += "?iframe=1";
	}
	if(returnfunction != undefined && returnfunction != "" && returnfunction != null){
		url += "&backfunction=" + encodeURIComponent(returnfunction);
	}
	$("#ifmContent").prop("src", url);
	var locationWidth = parseInt(width / 2);
	var locationHeight = parseInt(height / 2);
	$(".TipDivBox").css("margin","-"+ locationHeight + "px 0 0 -" + locationWidth + "px");
	$("#deleteBespeakTimeDescBox").show();
}

//设置弹出框的样式
function InitTipBox(){
	window.onresize = function(){
		$(".TipDiv").width($(window).width() + "px");
		$(".TipDiv").height($(window).height() + "px");
	}
	window.onresize();
}