$(function(){
	//初始化多图片选择显示的照片
	InItMoreSelectShow()
});
/**
 * 初始化多图片选择显示的照片
 */
function InItMoreSelectShow(){
	$(".MoreImageSelectValue").each(function(i,item){
			var boxElementID = $(item).attr("ShowBox");
			var valueElementID = $(item).attr("id");
			
			var htmlModel = '<div class="item" id="@ID">'+
			'<div class="img"><img src="@Path" /></div>'+
			'<div class="title">@Title</div>'+
			'<div class="opt"><a href="javascript:FileInfoImageUp(@ID,\''+ boxElementID +'\',\''+ valueElementID +'\');">上移</a><a href="javascript:FileInfoImageDelete(@ID,\''+ boxElementID +'\',\''+ valueElementID +'\');" class="delete">删除</a><a href="javascript:FileInfoImageDown(@ID,\''+ boxElementID +'\',\''+ valueElementID +'\');">下移</a></div>'+
			'</div>';
			var ids = $(item).val();
			if(ids == ''){
				return true;
			}
			$.ajax({
		        type: 'post',
		        cache:false,
		        url: _AjaxRoot + '/manage/image_info/AjaxGetMoreImageList.html',
		        data: { "ids":ids },
		        dataType: 'json',
		        success: function (data) {
		            if (ValidIsError(data)) {
		                return;
		            }
		            var html = "";
		            if(data.Data != null){
		            	$.each(data.Data,function(di,ditem){
		            		html += htmlModel;
		            		html = html.replace(/@ID/g, ditem.ID);
		            		html = html.replace(/@Path/g, ditem.ImagePath);
		            		html = html.replace(/@Title/g, ditem.Title);
		            	});
		            }
		            $("#" + boxElementID).html(html);
		        },
		        error: function (error) {
		        	console.log("获取多图片信息发生错误，请重试");
		        }
		    });
	});
}

/**
 * 图片向上移动
 * @param imgID
 */
function FileInfoImageUp(imgID,boxElementID,valueElementID){
	imgID = parseInt(imgID);
	var imgIdsStr = $("#" + valueElementID).val();
	var imgIdsArray = imgIdsStr.split(",");
	var icount = imgIdsArray.length;
	var upImgId = "";
	var upImgIndex = 0;
	for(var i=0;i<icount;i++){
		var id = parseInt(imgIdsArray[i]);
		if(id == imgID){
			if(i == 0){
				break;
			}
			upImgIndex = (i - 1);
			upImgId = imgIdsArray[(i-1)];
			break;
		}
	}
	if(upImgId == ""){
		return;
	}
	var upImgObj = $("#"+ boxElementID).find(".item[ID="+ upImgId +"]");
	var currentImgObj = $("#"+ boxElementID).find(".item[ID="+ imgID +"]");
	currentImgObj.insertBefore(upImgObj);
	//重新计算图片编号的顺序
	imgIdsArray[(upImgIndex+ 1)] = imgIdsArray[upImgIndex];
	imgIdsArray[upImgIndex] = imgID;
	imgIdsStr = imgIdsArray.join(",");
	$("#" + valueElementID).val(imgIdsStr);
} 

/**
 * 图片向上移动
 * @param imgID
 */
function FileInfoImageDown(imgID,boxElementID,valueElementID){
	imgID = parseInt(imgID);
	var imgIdsStr = $("#" + valueElementID).val();
	var imgIdsArray = imgIdsStr.split(",");
	var icount = imgIdsArray.length;
	var downImgId = "";
	var downImgIndex = 0;
	for(var i=0;i<icount;i++){
		var id = parseInt(imgIdsArray[i]);
		if(id == imgID){
			if(i == (icount - 1)){
				break;
			}
			downImgIndex = (i + 1);
			downImgId = imgIdsArray[(i+1)];
			break;
		}
	}
	if(downImgId == ""){
		return;
	}
	var downImgObj = $("#"+ boxElementID).find(".item[ID="+ downImgId +"]");
	var currentImgObj = $("#"+ boxElementID).find(".item[ID="+ imgID +"]");
	downImgObj.insertBefore(currentImgObj);
	//重新计算图片编号的顺序
	imgIdsArray[(downImgIndex - 1)] = imgIdsArray[downImgIndex];
	imgIdsArray[downImgIndex] = imgID;
	imgIdsStr = imgIdsArray.join(",");
	$("#" + valueElementID).val(imgIdsStr);
} 

/**
 * 删除图片
 * @param imgID
 */
function FileInfoImageDelete(imgID,boxElementID,valueElementID){
	var newImgIdsStr = "";
	var imgIdsStr = $("#" + valueElementID).val();
	var imgIdsArray = imgIdsStr.split(",");
	var icount = imgIdsArray.length;
	for(var i = 0; i < icount;i++){
		if(parseInt(imgIdsArray[i]) == parseInt(imgID)){
			continue;
		}
		if(newImgIdsStr == ""){
			newImgIdsStr = imgIdsArray[i];
		}else{
			newImgIdsStr  += "," + imgIdsArray[i];
		}
	}
	$("#" + valueElementID).val(newImgIdsStr);
	$("#"+ boxElementID).find(".item[ID="+ imgID +"]").remove();
} 