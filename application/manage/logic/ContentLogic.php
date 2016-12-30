<?php
namespace app\manage\logic;
use think\Db;
/**
 * 内容逻辑层
 * @author lc
 *
 */
class ContentLogic {
    /**
     * 获取当前栏目的最大排序编号
     * @param string $tableName 表名称
     * @param number $navigationID 栏目编号
     * @return number 最大排序编号
     */
    public function GetMaxSort($tableName,$navigationID){
        $data = Db::table($tableName)->order("Sort desc")
        ->where("NavigationID", $navigationID)->field("Sort")->find();
        if($data){
            return $data["Sort"] + 1;
        }
        return 1;
    }
    /**
     * 获取编辑页面输入元素
     * @param int $modelID 模型编号
     * @param string $editHtml 显示的编辑html
     * @param string $editDefaultHtml 初始化编辑器html内容
     */
    public function GetContentEditFieldHtml($modelID,$editData,&$editHtml,&$editDefaultHtml){
        $modelFieldList = Db::name("model_field")->where("ModelID", $modelID)
        ->order("Sort")->select();
        //获取所有显示的html
        foreach ($modelFieldList as $v){
            //跳过系统变量
            if($v["FieldNameCode"] == "ID" || $v["FieldNameCode"] == "Title" || $v["FieldNameCode"] == "Sort" || $v["FieldNameCode"] == "NavigationID" ){
                continue;
            }
            $editHtml .= $v["FieldHtml"];
        }
        //初始化每个选项的值
        if(!empty($editData)){
            $editDefaultHtml .= "<script type=\"text/javascript\">";
            foreach ($modelFieldList as $v){
                switch($v["FieldType"]){
                    //多文本
                    case ModelFieldLogic::AreaText:
                        $editDefaultHtml .= " var ". $v["FieldNameCode"] ." = \"". $editData[$v["FieldNameCode"]] ."\";";
                        $editDefaultHtml .= " if(". $v["FieldNameCode"] ." != \"\"){";
                        $editDefaultHtml .= " $(\"#". $v["FieldNameCode"] ."\").val(". $v["FieldNameCode"] .");";
                        $editDefaultHtml .= " }";
                        break;
                    //多选框
                    case ModelFieldLogic::CheckBox:
                        $fieldNameCodeValue =  "";
                        if(!empty($editData[$v["FieldNameCode"]])){
                            if(strpos($editData[$v["FieldNameCode"]], ',')){
                                $fieldNameCodeValue = implode(",", $editData[$v["FieldNameCode"]]);
                            }else{
                                $fieldNameCodeValue = $editData[$v["FieldNameCode"]];
                            }
                        }
                        $editDefaultHtml .= " var ". $v["FieldNameCode"] ." = \"".  $fieldNameCodeValue ."\";";
                        $editDefaultHtml .= " if(". $v["FieldNameCode"] ." != \"\"){";
                        $editDefaultHtml .= " var ". $v["FieldNameCode"] ."Array = ". $v["FieldNameCode"] .".split(',');";
                        $editDefaultHtml .= " var ". $v["FieldNameCode"] ."ArrayCount = ". $v["FieldNameCode"] ."Array.length;";
                        $editDefaultHtml .= " for(var i = 0; i < ". $v["FieldNameCode"] ."ArrayCount; i++){";
                        $editDefaultHtml .= "   $(\"input:checkbox[name=". $v["FieldNameCode"] ."][value=\"+ ". $v["FieldNameCode"] ."Array[i] +\"]\").prop(\"checked\", 'checked');";
                        $editDefaultHtml .= " }";
                        $editDefaultHtml .= " }";
                        break;
                    //下拉列表
                    case ModelFieldLogic::DropDownList:
                        $editDefaultHtml .= " var ". $v["FieldNameCode"] ." = \"". $editData[$v["FieldNameCode"]] ."\";";
                        $editDefaultHtml .= " if(". $v["FieldNameCode"] ." != \"\"){";
                        $editDefaultHtml .= " $(\"#". $v["FieldNameCode"] ."\").val(". $v["FieldNameCode"] .");";
                        $editDefaultHtml .= " }";
                        break;
                    //富文本编辑器
                    case ModelFieldLogic::EditText:
                        $editDefaultHtml .= " var ". $v["FieldNameCode"] ." = \"". $editData[$v["FieldNameCode"]] ."\";";
                        $editDefaultHtml .= " if(". $v["FieldNameCode"] ." != \"\"){";
                        $editDefaultHtml .= " ". $v["FieldNameCode"] ."UEdit.ready(function() { ";
                        $editDefaultHtml .= "  ". $v["FieldNameCode"] ."UEdit.setContent(". $v["FieldNameCode"] .");";
                        $editDefaultHtml .= "  });";
                        $editDefaultHtml .= " }";
                        break;
                    //文件单选
                    case ModelFieldLogic::FileOneSelect:
                        $editDefaultHtml .= " var ". $v["FieldNameCode"] ." = \"". $editData[$v["FieldNameCode"]] ."\";";
                        $editDefaultHtml .= " if(". $v["FieldNameCode"] ." != \"\"){";
                        $editDefaultHtml .= " $(\"#". $v["FieldNameCode"] ."\").val(". $v["FieldNameCode"] .");";
                        $editDefaultHtml .= " }";
                        break;
                    //图片多选
                    case ModelFieldLogic::ImageMoreSelect:
                        $editDefaultHtml .= " var ". $v["FieldNameCode"] ." = \"". $editData[$v["FieldNameCode"]] ."\";";
                        $editDefaultHtml .= " if(". $v["FieldNameCode"] ." != \"\"){";
                        $editDefaultHtml .= " $(\"#". $v["FieldNameCode"] ."\").val(". $v["FieldNameCode"] .");";
                        $editDefaultHtml .= " }";
                        break;
                    //图片单选
                    case ModelFieldLogic::ImageOneSelect:
                        $editDefaultHtml .= " var ". $v["FieldNameCode"] ." = \"". $editData[$v["FieldNameCode"]] ."\";";
                        $editDefaultHtml .= " if(". $v["FieldNameCode"] ." != \"\"){";
                        $editDefaultHtml .= " $(\"#Show". $v["FieldNameCode"] ."\").attr(\"src\",". $v["FieldNameCode"] .");";
                        $editDefaultHtml .= " $(\"#". $v["FieldNameCode"] ."\").val(". $v["FieldNameCode"] .");";
                        $editDefaultHtml .= " }";
                        break;
                    //单选
                    case ModelFieldLogic::Radio:
                        $editDefaultHtml .= " var ". $v["FieldNameCode"] ." = \"". $editData[$v["FieldNameCode"]] ."\";";
                        $editDefaultHtml .= " if(". $v["FieldNameCode"] ." != \"\"){";
                        $editDefaultHtml .= " $(\"input:radio[name=". $v["FieldNameCode"] ."][value='". $editData[$v["FieldNameCode"]] ."']\").prop('checked', 'checked');";
                        $editDefaultHtml .= " }";
                        break;
                    //文本
                    case ModelFieldLogic::Text:
                        $editDefaultHtml .= " var ". $v["FieldNameCode"] ." = \"". $editData[$v["FieldNameCode"]] ."\";";
                        $editDefaultHtml .= " if(". $v["FieldNameCode"] ." != \"\"){";
                        $editDefaultHtml .= " $(\"#". $v["FieldNameCode"] ."\").val(". $v["FieldNameCode"] .");";
                        $editDefaultHtml .= " }";
                        break;
                }
                
            }
            $editDefaultHtml .= "</script>";
        }
    }
}