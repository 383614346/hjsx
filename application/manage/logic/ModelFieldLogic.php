<?php
namespace app\manage\logic;
use think\Db;
/**
 * 模型字段逻辑层
 * @author lc
 *
 */
class ModelFieldLogic {
    
    /**
     * 文本
     */
	const Text = 1;
	/**
	 * 多文本
	 */
	const AreaText = 2;
	/**
	 * 富文本
	 */
	const EditText = 3;
	/**
	 * 单选
	 */
	const Radio = 4;
	/**
	 * 多选
	 */
	const CheckBox = 5;
	/**
	 * 下拉列表
	 */
	const DropDownList = 6;
	/**
	 * 文件选择
	 */
	const FileOneSelect = 7;
	/**
	 * 图片单选
	 */
	const ImageOneSelect = 8;
	/**
	 * 图片多选
	 */
	const ImageMoreSelect = 9;
	/**
	 * 文本
	 */
	const TextName = "文本";
	/**
	 * 多文本
	 */
	const AreaTextName = "多文本";
	/**
	 * 富文本
	 */
	const EditTextName = "富文本";
	/**
	 * 单选
	 */
	const RadioName = "单选";
	/**
	 * 多选
	 */
	const CheckBoxName = "多选";
	/**
	 * 下拉列表
	 */
	const DropDownListName = "下拉列表";
	/**
	 * 文件选择
	 */
	const FileOneSelectName = "文件单选择";
	/**
	 * 图片单选
	 */
	const ImageOneSelectName = "图片单选";
	/**
	 * 图片多选
	 */
	const ImageMoreSelectName = "图片多选";
	/**
	 * 获取字段类型名称
	 * @param int $fileType 
	 */
	public function GetFieldTypeName($fieldType){
	    switch ($fieldType){
	        case ModelFieldLogic::AreaText:
	            return ModelFieldLogic::AreaTextName;
            case ModelFieldLogic::CheckBox:
                return ModelFieldLogic::CheckBoxName;
            case ModelFieldLogic::DropDownList:
                return ModelFieldLogic::DropDownListName;
            case ModelFieldLogic::EditText:
                return ModelFieldLogic::EditTextName;
            case ModelFieldLogic::FileOneSelect:
                return ModelFieldLogic::FileOneSelectName;
            case ModelFieldLogic::ImageMoreSelect:
                return ModelFieldLogic::ImageMoreSelectName;
            case ModelFieldLogic::ImageOneSelect:
                return ModelFieldLogic::ImageOneSelectName;
            case ModelFieldLogic::Radio:
                return ModelFieldLogic::RadioName;
            case ModelFieldLogic::Text:
                return ModelFieldLogic::TextName;
	    }
	}
	/**
	 * 获取验证类型下拉选择
	 */
	public function GetFieldNameValidTypeDropDownList(){
	    return [
	        ["Key"=> "必填验证","Value"=> "required","Tip"=>"请填写该项"],
	        ["Key"=> "日期验证","Value"=> "datetime","Tip"=>"日期格式不正确"],
	        ["Key"=> "大于0整数验证","Value"=> "number","Tip"=>"请填写大于0的整数"],
	        ["Key"=> "整数验证","Value"=> "integer","Tip"=>"不是整数"],
	        ["Key"=> "浮点数验证","Value"=> "double","Tip"=>"不是浮点数"],
	        ["Key"=> "金额验证","Value"=> "money","Tip"=>"金额不正确"],
	        ["Key"=> "英文验证","Value"=> "english","Tip"=>"不是英文"],
	        ["Key"=> "手机验证","Value"=> "mobile","Tip"=>"手机格式不正确"],
// 	        ["Key"=> "电话验证","Value"=> "phone","Tip"=>""],
	        ["Key"=> "座机验证","Value"=> "tel","Tip"=>"座机格式不正确"],
	        ["Key"=> "邮箱验证","Value"=> "email","Tip"=>"邮箱格式不正确"],
	        ["Key"=> "URL验证","Value"=> "url","Tip"=>"URL格式不正确"],
	    ];
	}
	/**
	 * 获取字段类型下拉列表
	 */
	public function GetFieldTypeDropDownList(){
	    return [
	        ["Key"=> ModelFieldLogic::TextName,"Value"=> ModelFieldLogic::Text],
	        ["Key"=> ModelFieldLogic::AreaTextName ,"Value"=> ModelFieldLogic::AreaText],
	        ["Key"=> ModelFieldLogic::EditTextName,"Value"=> ModelFieldLogic::EditText],
	        ["Key"=> ModelFieldLogic::RadioName,"Value"=> ModelFieldLogic::Radio],
	        ["Key"=> ModelFieldLogic::CheckBoxName,"Value"=> ModelFieldLogic::CheckBox],
	        ["Key"=> ModelFieldLogic::DropDownListName,"Value"=> ModelFieldLogic::DropDownList],
	        ["Key"=> ModelFieldLogic::FileOneSelectName,"Value"=> ModelFieldLogic::FileOneSelect],
	        ["Key"=> ModelFieldLogic::ImageOneSelectName,"Value"=>ModelFieldLogic::ImageOneSelect],
	        ["Key"=> ModelFieldLogic::ImageMoreSelectName,"Value"=> ModelFieldLogic::ImageMoreSelect]
	    ];
	}
	/**
	 * 获取当前最大的排序号
	 */
	public function GetMaxSort($modelID = 0){
	    $where = [];
	    if($modelID != 0){
	        $where["ModelID"] = $modelID;
	    }
	    $data = Db::name("model_field")->where($where)->order("Sort desc")->find();
	    if(empty($data)){
	        return 1;
	    }
	    return $data["Sort"] + 1;
	}
	/**
	 * 通过字段模型创建字段html
	 * @param array $fieldModel 字段模型
	 */
	public function CreateFieldHtml($fieldModel){
	    $html = "<div class=\"form-group\">".
	    "<div class=\"label\"><label for=\"readme\">@FieldName：</label></div>".
	    "<div class=\"field\">".
	    $this->GetInputFieldHtml($fieldModel).
	    "</div>".
	    "</div>".
	    "";
	    //替换字段显示名称
	    $html = str_replace("@FieldName", $fieldModel["FieldName"], $html);
	    return $html;
	}
	/**
	 * 通过输入类型获取对应的输入框
	 * @param array $fieldModel
	 */
	private function GetInputFieldHtml($fieldModel){
	    $html = "";
	    switch ($fieldModel["FieldType"]){
	        case 1://文本
	            $html = "<input type=\"text\" class=\"input\" @FieldNameCodeID @FieldNameCodeName @FieldNameValidType @FieldNameTip @FieldDefault  @FieldNameMaxLength @FieldWidth @FieldHeight />";
	            break;
	        case 2://多文本
	            $html = "<textarea @FieldNameCodeID @FieldNameCodeName @FieldNameValidType @FieldNameTip  @FieldNameMaxLength @FieldWidth @FieldHeight >@FieldDefault</textarea>";
	            $html = str_replace("@FieldWidth", $fieldModel["FieldWidth"] ? "cols=\"".$fieldModel["FieldWidth"]."\"":"", $html);
	            $html = str_replace("@FieldHeight", $fieldModel["FieldHeight"] ?"rows=\"".$fieldModel["FieldHeight"]."\"":"", $html);
	            break;
	        case 3://富文本
	            $html = "<script @FieldNameCodeID @FieldNameCodeName type=\"text/plain\">@FieldDefault</script>";
	            $html .= "<script type=\"text/javascript\">var ". $fieldModel["FieldNameCode"] ."UEdit = UE.getEditor('". $fieldModel["FieldNameCode"] ."',{autoHeightEnabled:true});</script>";
	            break;
	        case 4://单选
	            $dataList = explode("|", $fieldModel["FieldDefault"]);
	            $iCount = count($dataList);
	            for($i=0;$i < $iCount; $i++){
	                $data = explode(",", $dataList[$i]);
	                $html .= "<label><input type=\"radio\" id=\"@FieldNameCode". $i ."\" @FieldNameCodeName value=\"". $data[1] ."\" />&nbsp;". $data[0] ."</label>&nbsp;&nbsp;";
	            }
	            break;
            case 5://多选
                $dataList = explode("|", $fieldModel["FieldDefault"]);
                $iCount = count($dataList);
                for($i=0;$i < $iCount; $i++){
                    $data = explode(",", $dataList[$i]);
                    $html .= "<label><input type=\"checkbox\" id=\"@FieldNameCode". $i ."\" @FieldNameCodeName value=\"". $data[1] ."\" />&nbsp;". $data[0] ."</label>&nbsp;&nbsp;";
                }
                break;
            case 6://下拉列表
                $dataList = explode("|", $fieldModel["FieldDefault"]);
                $iCount = count($dataList);
                $html = "<select @FieldNameCodeID @FieldNameCodeName @FieldWidth @FieldHeight >";
                for($i=0;$i < $iCount; $i++){
                    $data = explode(",", $dataList[$i]);
                    $html .= "<option value=\"". $data[1] ."\">". $data[0] ."</option>";
                }
                $html .= "</select>";
                break;
            case 7://文件选择
                $html = "<input type=\"text\" @FieldNameCodeID @FieldNameCodeName />";
                $url = url("file_info/OneFileSelectBox",array("ValueElementID"=> $fieldModel["FieldNameCode"]));
                $html .= "<a class=\"button bg-main\" type=\"button\" onclick=\"javascript:ShowIframe(800,500,'". $url ."');\">选择文件</a>";
                break;
            case 8://图片单选
                $html .= "<img id=\"Show". $fieldModel["FieldNameCode"] ."\" width=\"120\" />";
                $html .= "<input type=\"text\" @FieldNameCodeID @FieldNameCodeName />";
                $url = url("ImageInfo/OneImageSelectBox",["BoxElementID"=> "Show". $fieldModel["FieldNameCode"],"ValueElementID" => $fieldModel["FieldNameCode"]]);
                $html .= "<a class=\"button bg-main\" type=\"button\" onclick=\"javascript:ShowIframe(800,500,'". $url ."');\">选择图片</a>";
                break;
            case 9://图片多选
                $html .= "<div id=\"Show". $fieldModel["FieldNameCode"] ."\" class=\"MoreImageBox clearfix\" ></div>";
                $html .= "<input type=\"hidden\" @FieldNameCodeID @FieldNameCodeName class=\"MoreImageSelectValue\" ShowBox=\"Show". $fieldModel["FieldNameCode"] ."\" />";
                $url = url("ImageInfo/MoreImageSelectBox",["BoxElementID"=> "Show". $fieldModel["FieldNameCode"],"ValueElementID" => $fieldModel["FieldNameCode"]]);
                $html .= "<a class=\"button bg-main\" type=\"button\" onclick=\"javascript:ShowIframe(800,500,'". $url ."');\">选择图片</a>";
                break;
	    }
	    //替换id
	    $html = str_replace("@FieldNameCodeID", "id=\"". $fieldModel["FieldNameCode"] ."\"", $html);
	    //替换name
	    $html = str_replace("@FieldNameCodeName", "name=\"". $fieldModel["FieldNameCode"] ."\"", $html);
	    //替换最大输入数量
	    $html = str_replace("@FieldNameMaxLength", $fieldModel["FieldNameMaxLength"] ? "maxlength=\"". $fieldModel["FieldNameMaxLength"] ."\"" :"", $html);
	    //字段验证类型
	    $validTypeHtml = "";
	    if(!empty($fieldModel["FieldNameValidType"])){
	        $iCount = count($fieldModel["FieldNameValidType"]);
	        $validTypeHtml = "data-validate=\"";
	        for($i = 0; $i < $iCount; $i++){
	            if($i != 0){
	                $validTypeHtml .= ",";
	            }
	            $validTypeHtml .= $fieldModel["FieldNameValidType"][$i].":".$fieldModel["FieldNameValidTypeTip"][$i];
	        }
	        $validTypeHtml .= "\"";
            
	    }
	    $html = str_replace("@FieldNameValidType", $validTypeHtml ?$validTypeHtml:"", $html);
	    //替换字段提示信息
	    $html = str_replace("@FieldNameTip", $fieldModel["FieldNameTip"] ?$fieldModel["FieldNameTip"]:"", $html);
	    //替换字段宽度
	    $html = str_replace("@FieldWidth", $fieldModel["FieldWidth"] ?$fieldModel["FieldWidth"]:"", $html);
	    //替换字段高度
	    $html = str_replace("@FieldHeight", $fieldModel["FieldHeight"] ?$fieldModel["FieldHeight"]:"", $html);
	    //替换默认值
	    $html = str_replace("@FieldDefault", $fieldModel["FieldDefault"] ? "value=\"".$fieldModel["FieldDefault"]."\"":"", $html);
	    
	    return $html;
	}
	/**
	 * 获取验证类型名称
	 * @param array $fieldModel 字段模型
	 */
	public function GetValidTypeName($fieldModel){
	    $fieldNameValidTypeName = "";
	    $fieldNameValidArray = $this->GetFieldNameValidTypeDropDownList();
	    if(!empty($fieldModel["FieldNameValidType"])){
	        $fieldNameValidTypeArray = explode("|", $fieldModel["FieldNameValidType"]);
	        foreach ($fieldNameValidTypeArray as $v){
	            foreach ($fieldNameValidArray as $cv){
	                if($v == $cv["Value"]){
	                    $fieldNameValidTypeName .= $cv["Key"].",";
	                    break;
	                }
	            }
	        }
	    }
	    return $fieldNameValidTypeName;
	}
	/**
	 * 获取字段模型列表
	 * @param int $modelID 所属模型
	 */
	public function GetFieldModelListByModelID($modelID){
	    return Db::name("model_field")->where("ModelID", $modelID)->select();
	}
    /**
     * 获取用户输入的数据数组
     * @param int $modelID 所属模型
     */
	public function GetUserInputField($modelID,$inputArray){
	    
	    $fieldList = $this->GetFieldModelListByModelID($modelID);
	    $inputData = [];
	    foreach($fieldList as $v){
	        $inputData[$v["FieldNameCode"]] = $inputArray[$v["FieldNameCode"]];
	    }
	    return $inputData;
	}
}