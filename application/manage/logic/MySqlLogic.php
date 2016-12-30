<?php
namespace app\manage\logic;
use think\Db;
/**
 * MySql数据库逻辑层
 * @author lc
 *
 */
class MySqlLogic {
    public $SiteID = 0;
    public $SiteCode = "";
    function __construct($siteID = 0){
        $site = [];
        if($siteID == 0){
            $site = Db::name("site")->order("ID")->find();
        }else{
            $site = Db::name("site")->where("ID", $siteID)->find();
        }
        $this->SiteCode = $site["Code"];
        $this->SiteID = $site["ID"];
        if(!$site){
            throw new \Exception("未能获取到站点信息", 500);
        }
    }
    /**
     * 获取表名称
     * @param int $modelID 模型编号
     */
    public function GetTableName($modelID){
        $data = Db::name("model")->where("ID",$modelID)->field("Code")->find();
        return $this->SiteCode."_". $data["Code"];
    }
    
    /**
     * 创建表
     * @param string $tableCode 创建表名称
     */
    public function GetCreateTableSql($tableCode){
        $sql = "create table ". $this->SiteCode. "_" .$tableCode ."(".
            " ID int primary key not null auto_increment, ".
            " Title varchar(255), ".
            " NavigationID int, ".
            " Sort int default 0, ".
            " AddTime int".
            ")";
        return $sql;
    }
    /**
     * 获取删除表结构
     * @param array $codeArray 要删除的表编号集合
     */
    public function GetDeleteTableSql($codeArray){
        $sql = "";
        foreach($codeArray as $v){
            $sql .= " drop table ". $this->SiteCode."_".$v["Code"].";";
        }
        return $sql;
    }
    
    /**
     * 获取添加表列sql语句
     * @param array $fieldModel 传入字段模型
     */
    public function GetAddFieldSql($siteCode,$fieldModel){
        $model = Db::name("model")->where("ID",$fieldModel["ModelID"])->field("Code")->find();
//         if(!$model){
//             throw new \Exception("生成添加字段sql语句时，未能获取到模型数据", 500);
//         }
        $modelCode = $model["Code"];
        $sql = '';
        switch ($fieldModel["FieldType"]){
    	        case 1://文本
    	            $sql = "alter table ". $siteCode."_". $modelCode ." add column ". $fieldModel["FieldNameCode"] ." varchar(255);";
    	            break;
    	        case 2://多文本
    	            $sql = "alter table ". $siteCode."_". $modelCode ." add column ". $fieldModel["FieldNameCode"] ." varchar(2000);";
    	            break;
    	        case 3://富文本
    	            $sql = "alter table ". $siteCode."_". $modelCode ." add column ". $fieldModel["FieldNameCode"] ." text;";
    	            break;
    	        case 4://单选
    	            $sql = "alter table ". $siteCode."_". $modelCode ." add column ". $fieldModel["FieldNameCode"] ." varchar(50);";
    	            break;
                case 5://多选
                    $sql = "alter table ". $siteCode."_". $modelCode ." add column ". $fieldModel["FieldNameCode"] ." varchar(255);";
                    break;
                case 6://下拉列表
                    $sql = "alter table ". $siteCode."_". $modelCode ." add column ". $fieldModel["FieldNameCode"] ." varchar(50);";
                    break;
                case 7://文件选择
                    $sql = "alter table ". $siteCode."_". $modelCode ." add column ". $fieldModel["FieldNameCode"] ." varchar(255);";
                    break;
                case 8://图片单选
                    $sql = "alter table ". $siteCode."_". $modelCode ." add column ". $fieldModel["FieldNameCode"] ." varchar(255);";
                    break;
                case 9://图片多选
                    $sql = "alter table ". $siteCode."_". $modelCode ." add column ". $fieldModel["FieldNameCode"] ." varchar(2000);";
                    break;
        }
        return $sql;
        //alter table   table1 add id int unsigned not Null auto_increment primary key
    }
    /**
     * 获取修改表字段结构sql
     * @param string $siteCode 当前站点代码
     * @param array $fieldModel 当前字段模型
     * @throws \Exception
     * @return 修改字段sql
     */
    public function GetUpdateFieldSql($siteCode,$fieldModel){
        //alter table 表名称 change 字段原名称 字段新名称 字段类型 
        $model = Db::name("model")->where("ID",$fieldModel["ModelID"])->field("Code")->find();
//         if(!$model){
//             throw new \Exception("生成修改字段sql语句时，未能获取到模型数据", 500);
//         }
        $modelCode = $model["Code"];
        //获取原来的字段信息
        $oldModelFiled = Db::name("model_field")->where("ID", $fieldModel["ID"])->find();
//         if(!$oldModelFiled){
//             throw new \Exception("未能获取到旧模型数据", 500);
//         }
        $sql = '';
        switch ($fieldModel["FieldType"]){
            case 1://文本
                $sql = "alter table ". $siteCode."_". $modelCode ." change ". $oldModelFiled["FieldNameCode"] ." ". $fieldModel["FieldNameCode"] ." varchar(255);";
                break;
            case 2://多文本
                $sql = "alter table ". $siteCode."_". $modelCode ." change ". $oldModelFiled["FieldNameCode"] ." ". $fieldModel["FieldNameCode"] ." varchar(2000);";
                break;
            case 3://富文本
                $sql = "alter table ". $siteCode."_". $modelCode ." change ". $oldModelFiled["FieldNameCode"] ." ". $fieldModel["FieldNameCode"] ." text;";
                break;
            case 4://单选
                $sql = "alter table ". $siteCode."_". $modelCode ." change ". $oldModelFiled["FieldNameCode"] ." ". $fieldModel["FieldNameCode"] ." varchar(50);";
                break;
            case 5://多选
                $sql = "alter table ". $siteCode."_". $modelCode ." change ". $oldModelFiled["FieldNameCode"] ." ". $fieldModel["FieldNameCode"] ." varchar(255);";
                break;
            case 6://下拉列表
                $sql = "alter table ". $siteCode."_". $modelCode ." change ". $oldModelFiled["FieldNameCode"] ." ". $fieldModel["FieldNameCode"] ." varchar(50);";
                break;
            case 7://文件选择
                $sql = "alter table ". $siteCode."_". $modelCode ." change ". $oldModelFiled["FieldNameCode"] ." ". $fieldModel["FieldNameCode"] ." varchar(255);";
                break;
            case 8://图片单选
                $sql = "alter table ". $siteCode."_". $modelCode ." change ". $oldModelFiled["FieldNameCode"] ." ". $fieldModel["FieldNameCode"] ." varchar(255);";
                break;
            case 9://图片多选
                $sql = "alter table ". $siteCode."_". $modelCode ." change ". $oldModelFiled["FieldNameCode"] ." ". $fieldModel["FieldNameCode"] ." varchar(2000);";
                break;
        }
        return $sql;
    }
    /**
     * 生成删除模型字段sql
     * @param array $modelField 字段模型
     */
    public function GetDeleteFieldSql($siteCode,$fieldModel){
        $model = Db::name("model")->where("ID",$fieldModel["ModelID"])->field("Code")->find();
        $modelCode = $model["Code"];
        $sql = "ALTER TABLE ".$siteCode."_". $modelCode ." DROP ". $fieldModel["FieldNameCode"] ."; ";
        return $sql;
    }
}