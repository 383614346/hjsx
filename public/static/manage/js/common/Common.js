function empty(value){
	
	if(value == null || value == undefined  || value == "undefined" ){
		return true;
	}
	if(!isNaN(value)){
		if(value < 1){
			return true;
		}
	}else{
		value = value.replace(/(^\s*)|(\s*$)/g, "");  
		if(value == "" || value.length == 0){
			return true;
		}
	}
	
	return false;
}

/**
 * 移除前后空格
 * @param value
 * @returns
 */
function trim(value){
	 return value.replace(/(^\s*)|(\s*$)/g, "");
}