
/**
 * 获取模型选项
 */
function AjaxGetModelDropDownList(){
	var modelid = $("#ModelID").val();
	$.ajax({
        type: 'post',
        cache:false,
        url: '/Manage/navigation/JsonGetModelList',
        data: { "r": Math.random(),"modelid":modelid},
        dataType: 'json',
        success: function (data) {
            if (ValidIsError(data)) {
                return;
            }
            var html = "";
            var htmlModel = "<option value=\"@Value\">@Key</option>";
            $.each(data.data,function(i,item){
            	html += htmlModel;
            	html = html.replace(/@Value/g, item.Value);
            	html = html.replace(/@Value/g, item.Value);
            });
            $("#ModelID").html(html);
        },
        error: function (error) {
        	alert("获取模型列表失败。");
        }
    });
}