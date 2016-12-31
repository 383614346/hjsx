/**
 * 判断ajax返回的数据是否包含错误
 * @param data
 * @returns {Boolean}
 */
function ValidIsError(data){
	if(data.Status != 1 && data.status != 1){
		if(data.Msg == undefined || data.Msg == "" || data.Msg == null){
			alert(data.msg);
		}else{
			alert(data.Msg);
		}
		return true;
	}
	return false;
}