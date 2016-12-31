$(function(){
	//ajax获取文件数据
	AjaxGetFileInfoList(1,12);
});

/**
 * 用户点击确定选择
 */
function OneSelectOver(){
	var imgIdStr = $("#imgIds").val();
	if(imgIdStr == ""){
		return;
	}
	var imgIdArray = imgIdStr.split(",");
	var icount = imgIdArray.length;
	var html = "";
	var htmlModel = '<div class="item">'+
		'<div class="img"><img src="@Path" /></div>'+
		'<div class="title">@Title</div>'+
		'</div>';
	for(var i=0;i < icount;i++){
		html += htmlModel;
		var id = imgIdArray[i];
		var item = $(".item[ID="+ id +"]");
		var path = item.attr("Path");
		html = html.replace(/@Path/g, item.attr("Path"));
    	html = html.replace(/@Title/g, item.attr("Title"));
	}
	if(boxElementID != ""){
		parent.$("#" + boxElementID).html(html);
	}
	parent.$("#" + valueElementID).val(imgIdStr);
	parent.CloseIframe();
}
/**
 * 初始化图片选择事件
 */
function InitClick(){
	$(".serach_img_box .item").dblclick(function(){
		var id = $(this).attr("ID");
		var item = $(".item[ID="+ id +"]");
		var path = item.attr("Path");
		parent.$("#" + valueElementID).val(path);
		parent.$("#" + boxElementID).attr("src",path);
		parent.CloseIframe();
	});
}

/**
 * ajax获取文件数据
 * @param currentPage
 * @param pageSize
 */
function AjaxGetFileInfoList(currentPage,pageSize){
	var html = "";
	var htmlModel = '<div class="item" ID="@ID" Path="@Path" >'+
		'<img src="@ShowPath" />'+
		'<div class="title">'+
		'@Title'+
	'</div>'+
	'</div>';
	var searchFileType = $("#SearchFileType").val();
	var searchTitle = $("#SearchTitle").val();
	$.ajax({
        type: 'post',
        cache:false,
        url: _AjaxRoot + '/manage/file_info/AjaxGetFileInfoList.html',
        data: { "SearchFileType": searchFileType, "SearchTitle": searchTitle, "CurrentPage": currentPage, "PageSize": pageSize },
        dataType: 'json',
        success: function (data) {
            if (ValidIsError(data)) {
                return;
            }
            $.each(data.Data,function(i,item){
            	html += htmlModel;
            	html = html.replace(/@ShowPath/g, item.ShowPath);
            	html = html.replace(/@Path/g, item.Path);
            	html = html.replace(/@ID/g, item.ID);
            	html = html.replace(/@Title/g, item.Title);
            });
            $(".serach_img_box").html(html);
            InitClick();
            jsPaging_CreatePage("page",data.Count,data.PageSize,data.CurrentPage,"AjaxGetFileInfoList");
        },
        error: function (error) {
        	alert("获取文件信息发生错误，请重试");
        }
    });
}