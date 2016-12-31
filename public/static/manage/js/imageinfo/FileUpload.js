var state = '';
var FileType = "";
$(function(){
	//初始化文件上传控件
	InitFileUpload();
});

/**
 * 图片上传成功过后显示图片的信息
 */
function UploadSuccessShowFileInfo(data){
	var html = "";
	var htmlModel = '<div class="item clearfix">'+
		'<div class="left">'+
			'<img src="'+ data.data.ImagePath +'" />'+
		'</div>'+
		'<div class="right">'+
			'<input type="hidden" name="ID[]" value="'+ data.data.ID +'" />'+
			'<input type="text" name="Title[]" class="title" value="'+ data.data.Title +'" /><br />'+
			'<textarea name="Content[]" class="content"></textarea>'+
		'</div>'+
	'</div>';
	$("#MsgBox").append(htmlModel);
	$("#btnSubmit").show();
}

/**
 * 初始化文件上传控件
 */
function InitFileUpload(){
	var uploader = WebUploader.create({
	    // swf文件路径
	    swf: '/Public/Scripts/Common/webuploader-0.1.5/Uploader.swf',

	    // 文件接收服务端。
	    server: _AjaxRoot + '/Manage/image_info/FileUploadOpt.html',
	    formData: {  
	        FileType: FileType  
	    },
	    // 选择文件的按钮。可选。
	    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
	    pick: '#picker',
//	    extensions: 'gif,jpg,jpeg,bmp,png',
//	    mimeTypes: 'image/*',
	    // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
	    resize: false
	});
	
	// 当有文件被添加进队列的时候
	uploader.on( 'fileQueued', function( file ) {
	    $("#thelist").append( '<div id="' + file.id + '" class="item">' +
	        '<h4 class="info">' + file.name + '</h4>' +
	        '<p class="state">等待上传...</p>' +
	    '</div>' );
	});
	// 文件上传过程中创建进度条实时显示。
	uploader.on( 'uploadProgress', function( file, percentage ) {
	    var $li = $( '#'+file.id ),
	        $percent = $li.find('.progress .progress-bar');

	    // 避免重复创建
	    if ( !$percent.length ) {
	        $percent = $('<div class="progress progress-striped active">' +
	          '<div class="progress-bar" role="progressbar" style="width: 0%">' +
	          '</div>' +
	        '</div>').appendTo( $li ).find('.progress-bar');
	    }

	    $li.find('p.state').text('上传中');

	    $percent.css( 'width', percentage * 100 + '%' );
	});
	//上传成功
	uploader.on( 'uploadSuccess', function( file,data ) {
	    $( '#'+file.id ).find('p.state').text(data.msg);
	    if(parseInt(data.status) == 1){
	    	$( '#'+file.id ).remove();
	    	//显示出上传的文件信息
	    	UploadSuccessShowFileInfo(data);
	    }
	});
	//上传失败
	uploader.on( 'uploadError', function( file,data ) {
	    $( '#'+file.id ).find('p.state').text('发生错误');
	});
	//不管成功还是失败都调用
	uploader.on( 'uploadComplete', function( file ) {
		state = "";
	    $( '#'+file.id ).find('.progress').fadeOut();
	});
	//用户点击上传
	 $("#ctlBtn").on( 'click', function() {
	        if (state == 'uploading' ) {
	        	state = "";
	            uploader.stop();
	        } else {
	        	state = "uploading";
	            uploader.upload();
	        }
	  });
}