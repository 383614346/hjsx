$(function(){
	//ajax获取文件数据
	AjaxGetFileInfoList(1,12);
});

/**
 * 用户点击确定选择
 */
function MoreSelectOver(){
	var imgIdStr = $("#imgIds").val();
	if(imgIdStr == ""){
		return;
	}
	var imgIdArray = imgIdStr.split(",");
	var icount = imgIdArray.length;
	var html = "";
	var htmlModel = '<div class="item" id="@ID">'+
		'<div class="img"><img src="@Path" /></div>'+
		'<div class="title">@Title</div>'+
		'<div class="opt"><a href="javascript:FileInfoImageUp(@ID,\''+ boxElementID +'\',\''+ valueElementID +'\');">上移</a><a href="javascript:FileInfoImageDelete(@ID,\''+ boxElementID +'\',\''+ valueElementID +'\');" class="delete">删除</a><a href="javascript:FileInfoImageDown(@ID,\''+ boxElementID +'\',\''+ valueElementID +'\');">下移</a></div>'+
		'</div>';
	for(var i=0;i < icount;i++){
		html = htmlModel;
		var id = imgIdArray[i];
		var item = $(".showSelectImg[ID="+ id +"]");
		html = html.replace(/@ID/g, id);
		html = html.replace(/@Path/g, item.attr("Path"));
    	html = html.replace(/@Title/g, item.attr("Title"));
    	//判断图片是否已经存在
    	if(!ValidParentIsExistsImg(id,valueElementID)){
	    	parent.$("#" + boxElementID).append(html);
	    	var imgIdsValue = parent.$("#" + valueElementID).val();
	    	if(imgIdsValue != ""){
	    		imgIdsValue = imgIdsValue + "," + id;
	    	}else{
	    		imgIdsValue = id;
	    	}
	    	parent.$("#" + valueElementID).val(imgIdsValue);
    	}
	}
	parent.CloseIframe();
}
/**
 * 判断父窗口是否已经存在了选择的图片
 */
function ValidParentIsExistsImg(imgID,valueElementID){
	imgID = parseInt(imgID);
	var imgIdsValue = parent.$("#" + valueElementID).val();
	var imgIdArray = imgIdsValue.split(",");
	var icount = imgIdArray.length;
	for(var i=0;i < icount;i++){
		var id =  parseInt(imgIdArray[i]);
		if(id == imgID){
			return true;
		}
	}
	return false;
}
/**
 * 初始化图片选择事件
 */
function InitClick(){
	$(".serach_img_box .item").click(function(){
		var id = $(this).attr("ID");
		var imgIds = $("#imgIds").val();
		var imgIdArray = imgIds.split(',');
		var newImgIdStr = ""; 
		var isExists = false;
		for(var i=0;i < imgIdArray.length;i++){
			if(imgIdArray[i] == id){
				isExists = true;
				continue;
			}
			if(newImgIdStr == ""){
				newImgIdStr = imgIdArray[i];
			}else{
				newImgIdStr += "," + imgIdArray[i];
			}
		}
		if(!isExists){
			if(newImgIdStr == ""){
				newImgIdStr = id;
			}else{
				newImgIdStr += "," + id;
			}
			$(this).addClass("active");
			ShowSelectImage(id);
		}else{
			$(this).removeClass("active");
			RemoveSelectImage(id);
		}
		$("#imgIds").val(newImgIdStr);
	});
}

/**
 * 显示选择的图片
 */
function ShowSelectImage(id){
	var item = $(".item[ID="+ id +"]");
	var id = item.attr("ID");
	var path = item.attr("Path");
	var title = item.attr("Title");
	var htmlModel = '<div class="item showSelectImg" ID="'+ id +'" Title="'+ title +'" Path="'+ path +'" ><img src="'+ path +'" /></div>'; 
	$("#show_select_image_box").append(htmlModel);
}
/**
 * 取消选择的图片
 */
function RemoveSelectImage(id){
	var item = $(".item[ID="+ id +"]");
	$(".showSelectImg[ID="+ id +"]").remove();
}
/**
 * ajax获取文件数据
 * @param currentPage
 * @param pageSize
 */
function AjaxGetFileInfoList(currentPage,pageSize){
	var html = "";
	var htmlModel = '<div class="item" ID="@ID" Path="@Path" Title="@Title" >'+
		'<img src="@Path" />'+
		'<div class="title">'+
		'@Title'+
	'</div>'+
	'</div>';
	var searchImageType = $("#SearchImageType").val();
	var serachTitle = $("#SerachTitle").val();
	$.ajax({
        type: 'post',
        cache:false,
        url: _AjaxRoot + '/Manage/image_info/AjaxGetImageInfoList.html',
        data: { "SearchImageType": searchImageType, "SearchTitle": serachTitle, "CurrentPage": currentPage, "PageSize": pageSize },
        dataType: 'json',
        success: function (data) {
            if (ValidIsError(data)) {
                return;
            }
            $.each(data.Data,function(i,item){
            	html += htmlModel;
            	html = html.replace(/@Path/g, item.ImagePath);
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