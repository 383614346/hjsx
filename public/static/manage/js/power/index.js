$(function(){
	//初始化点击名称选中权限
	InitTargetClickEvent();
	//初始化用户点击父栏目选中子栏目
	InitClickParentCkc();
})
/**
 * 初始化用户点击父栏目选中子栏目
 */
function InitClickParentCkc(){
	$(".ckcParent").change(function(){
		IniChildParentCkcChild($(this));
	});
}
/**
 * 递归调用选中
 */
function IniChildParentCkcChild(obj){
	var isCheck = obj.is(':checked');
	var parentID = obj.attr("currentid");
	$("[parent='"+ parentID +"']").each(function(i,item){
		$(item).prop('checked',isCheck);
		IniChildParentCkcChild($(item));
	});
}

function ChangeRole(){
	$("#operate").val("select");
	document.forms[0].submit();
}
/**
 * 初始化点击名称选中权限
 */
function InitTargetClickEvent(){
	$(".ckcTarget").click(function(){
		var value = $(this).attr("for");
		var ckcList = $("input[name='chePower[]']");
		$.each(ckcList,function(i,item){
			var targetValue = $(item).attr("value");
			if(targetValue== value){
				if($(item).prop("checked")){
					$(item).prop("checked",false);
				}else{
					$(item).prop("checked",true);
				}
				
				return false;
			}
		});
	});
}