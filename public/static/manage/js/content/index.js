/**
 * 跳转到指定的页面
 * @param node
 */
function ContentSkipUrl(note){
	document.getElementById('ifmContentContent').src = note.url;
	$("#layout_title").html('<li><a href="'+ note.url +'" class="icon-home">'+ note.text +'</a></li>');
} 
