$(function () {
    //初始化当前选中的栏目
    SelectNavigationInit();
});
/**
 * 是否显示子项
 */
function OnOffChildMenu(elementId){
	$(".child_ul").hide();
	$("#ul"+ elementId).toggle();
}

//初始化当前选中的栏目
function SelectNavigationInit() {
    //主导航被点击
    $(".icon-file-text").click(function () {
        $(".icon-file-text").parent().removeClass("active");
        $(this).parent().addClass("active");
    });
    //点击页面父分类显示选中样式
    $(".liSkip").click(function(){
    	$(".liSkip").removeClass("active");
    	$(this).addClass("active");
    	
    });
    //点击页面跳转显示选中样式
    $(".child_ul li").click(function(){
    	$(".child_ul > li").removeClass("cactive");
    	$(this).addClass("cactive");
    });
}


function GoTo(path) {
    window.location.href = path;
}

function ShowConfirm(msg) {
    if (confirm(msg)) {
        return true;
    }
    return false;
}

function IndexOpt(alert,value) {
	
    if (IsSelectCheckbox()) {
        if (ShowConfirm(alert)) {
            $("#operate").val(value);
            return true;
        }
    }
    return false;
}

//判断用户是否有选中项
function IsSelectCheckbox() {
    if (!$("input[type='checkbox']").is(':checked')) {
        alert("请选择要操作的项目。");
        return false;
    }
    return true;  
}
function SetOpt(value) {
   $("#operate").val(value);
}


function ImagePreview(evenElementId,showImageId,showImageDivId,imageWidth,imageHeight) {
    //选择file 对象
    var docObj = document.getElementById(evenElementId);
    //显示的图片对象
    var imgObjPreview = document.getElementById(showImageId);
    if (docObj.files && docObj.files[0]) {
        //火狐下，直接设img属性
        var viewImageDiv = document.getElementById(showImageDivId);
        viewImageDiv.style.display = 'block';
        imgObjPreview.style.display = 'block';
        //imgObjPreview.style.width = imageWidth + 'px';
        //imgObjPreview.style.height = imageHeight + 'px';
        //imgObjPreview.src = docObj.files[0].getAsDataURL();

        //火狐7以上版本不能用上面的getAsDataURL()方式获取，需要一下方式  
        imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);

    } else {
        //IE下，使用滤镜
        docObj.select();
        docObj.blur();
        var imgSrc = document.selection.createRange().text;
        //显示的div对象
        var localImagId = document.getElementById(showImageDivId);
        //必须设置初始大小
        localImagId.style.width = imageWidth + "px";
        localImagId.style.height = imageHeight + "px";
        //图片异常的捕捉，防止用户修改后缀来伪造图片
        try {
            localImagId.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
            localImagId.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;
        } catch (e) {
            alert("您上传的图片格式不正确，请重新选择!");
            return false;
        }
        imgObjPreview.style.display = 'none';
        document.selection.empty();
    }

    return true;
}

