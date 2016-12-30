<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use think\Db;
use app\manage\logic\ModelLogic;
use app\manage\logic\SiteLogic;
use app\manage\logic\MySqlLogic;
use think\Exception;
use app\manage\logic\ModelFieldLogic;

class Model extends ManageBase
{
    /**
     * 获取表名称
     * @param int $siteID 所属站点编号
     * @param int $modelID 所属模型编号
     */
    public function GetTableName($siteCode,$modelID){
        $data = Db::name("model")->where("ID", $modelID)->field("Code")->find();
        return $siteCode."_". $data["Code"];
    }
    public function index(){
        $this->IsPower("model",["select"]);
       
        $list = Db::name("model")->alias("m")->order("m.ID desc")
        ->join("mc_site s","s.ID = m.SiteID","left")
        ->field("m.*,s.Name as SiteName")->select();
        
        $this->view->list = $list;
        return $this->fetch();
    }
    
    function opt_index(){
        $opt = input("operate");
        //删除
        if($opt == "delete"){
            $this->IsPower("model",["delete"]);
            $this->opt_index_delete();
        }
    }
    
    private function opt_index_delete(){
        $mysqlLogic = new MySqlLogic();
        $ida = input("ID/a");
        $ids = implode(",", $ida);
        
        $codeList = Db::name("model")->where("ID","in", $ids)->field("Code")->select();
        Db::startTrans();
        //判断表模型是否可以删除
        Db::name("model_field")->where("ModelID","in",$ids)->delete();
        $count = Db::name("navigation")->where("ModelID","in",$ids)->count();
        if($count){
            $this->error("该模型已经在导航使用，请先修改导航模型");
        }
        //删除数据库表结构
        $result = Db::name("model")->where("ID","in", $ids)->delete();
        if(!$result){
            $this->error("删除失败，请重试");
        }
        try{
            $sql = $mysqlLogic->GetDeleteTableSql($codeList);
            if(!empty($sql)){
                Db::execute($sql);
            }
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            throw new Exception($e,500);
        }
        $this->success("删除成功",url("index"));
    }
    
    function edit(){
        $this->IsPower("model",["add","update"]);
        $siteLogic = new SiteLogic();
        $id = input("id",0);
        //初始化站点选择
        $this->assign("SiteList", $siteLogic->GetDropDownList()); 
        $model = Db::name("model")->where("ID", $id)->find();
        $this->assign("data", $model);
        return $this->fetch("edit");
    }
    
    function post_edit(){
        $modelLogic = new ModelLogic();
        $input_data["SiteID"] = input("SiteID/d",0);
        $input_data["ID"] = input("ID/d",0);
        $input_data["Name"] = input("Name");
        $input_data["Code"] = input("Code");
        if($input_data["Code"] == "mc"){
            $this->error("mc为系统库Code，请使用其他的Code");
        }
        $mysqlLogic = new MySqlLogic($input_data["SiteID"]);
        if($input_data["ID"] == 0){
            $this->IsPower("model",["add"]);
            //判断code是否存在
            if($modelLogic->IsExists(0, $input_data["Code"])){
                $this->error("Code已经存在，请更换");
            }
            $addData = $input_data;
            unset($addData["ID"]);
            Db::startTrans();
            //保存到数据库
            $modelID = Db::name("model")->insertGetId($addData);
            if(!$modelID){
                $this->error("添加失败，请重试");
            }
            //创建默认字段
            unset($addData);
            $addData = [
              ["FieldName" => "编号","FieldNameCode" => "ID","FieldType" => 1,"ModelID" => $modelID,"Sort" => 0,"IsSystem" => 1],
              ["FieldName" => "标题","FieldNameCode" => "Title","FieldType" => 1,"ModelID" => $modelID,"Sort" => 0,"IsSystem" => 1],
              ["FieldName" => "所属栏目","FieldNameCode" => "NavigationID","FieldType" => 6,"ModelID" => $modelID,"Sort" => 0,"IsSystem" => 1],
              ["FieldName" => "排序","FieldNameCode" => "Sort","FieldType" => 1,"ModelID" => $modelID,"Sort" => 0,"IsSystem" => 1]
            ];
            
            $result = Db::name("model_field")->insertAll($addData);
            if(!$result){
                $this->error("添加默认字段失败，请重试");
            }
            try{
                //创建表结构
                $sql = $mysqlLogic->GetCreateTableSql($input_data["Code"]);
                Db::execute($sql);
                Db::commit();
            }catch(\Exception $e){
                Db::rollback();
                throw new Exception($e,500);
            }
            $this->success("保存成功",url("edit"));
        }else{//修改
            $this->IsPower("model",["update"]);
            //判断code是否存在
            if($modelLogic->IsExists($input_data["ID"], $input_data["Code"])){
                $this->error("Code已经存在，请更换");
            }
            $updateData["Name"] = $input_data["Name"];
            $result = Db::name("model")->where("ID",$input_data["ID"])->update($updateData);
            if($result){
                $this->success("修改成功",url("edit",["id"=>$input_data["ID"]]));
            }else{
                $this->error("修改失败，请重试");
            }
        }
    }
    /**
     * 查看模型字段列表
     */
    public function model_field_list(){
        $this->IsPower("model",["select"]);
        $modelFieldLogic = new ModelFieldLogic();
        
        $modelID = input("model_id");
        $this->GetModelFieldListUrlData();
        
        $list = Db::name("model_field")->where("ModelID",$modelID)
        ->alias("mf")->join("mc_model m","m.ID = mf.ModelID","left")
        ->field("mf.*,m.Name as ModelName")
        ->order("mf.Sort")->select();
        foreach ($list as &$v){
            //展现形式
            $v["FieldTypeName"] = $modelFieldLogic->GetFieldTypeName($v["FieldType"]);
            //获取验证方式
            $v["FieldNameValidTypeName"] = $modelFieldLogic->GetValidTypeName($v);
        }
        $this->assign("list",$list);
        return $this->fetch();
    }
    /**
     * 模型字段操作
     */
    public function opt_model_field(){
        $opt = input("operate");
        //删除
        if($opt == "delete"){
            $this->opt_model_field_delete();
        }else if($opt == "sort"){//排序
            $this->opt_model_field_sort();
        }
    }
    /**
     * 排序
     */
    private function opt_model_field_sort(){
        $ids = input("ID/a",[]);
        if(count($ids) < 1){
            $this->error("请选择您要排序的字段");
        }
        $count = count($ids);
        $success = 0;
        foreach ($ids as $v){
            $updata["Sort"] = input("Sort".$v);
            $result = Db::name("model_field")->where("ID", $v)->update($updata);
            if($result){
                $success++;
            }
        }
        if($success == 0){
            $this->error("修改排序失败");
        }
        if($success == $count){
            $this->success("修改排序成功",url("model_field_list", $this->GetModelFieldListUrlData()));
        }
        $this->success("部分修改排序成功",url("model_field_list", $this->GetModelFieldListUrlData()));
    }
    
    
    /**
     * 删除模型字段
     */
    private function opt_model_field_delete(){
        $this->IsPower("model",["delete"]);
        $mysqlLogic = new MySqlLogic();
        
        $ida = input("ID/a",[]);
        if(count($ida) < 1){
            $this->error("请选择您要删除的模型字段");
        }
        $ids = implode(",", $ida);
        $modelFieldCodeList = Db::name("model_field")->where("ID","in",$ids)->field('ID,FieldNameCode,ModelID')->select();
        Db::startTrans();
        Db::name("model_field")->where("ID","in",$ids)->delete();
        try{
            foreach($modelFieldCodeList as $v){
                $sql = $mysqlLogic->GetDeleteFieldSql($this->GetSiteCode(), $v);
                Db::execute($sql);
            }
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            throw new \Exception($e,500);
        }
        $this->success("删除成功",url("model_field_list",$this->GetModelFieldListUrlData()));
    }
    
    private function GetModelFieldListUrlData(){
        $this->assign("return_url",input("return_url"));
        $this->assign("model_id",input("model_id"));
        return ["model_id" => input("model_id"),
            "return_url" => input("return_url")
        ];
    }
    /**
     * 模型字段编辑
     */
    public function model_field_edit(){
        $this->IsPower("model",["add","update"]);
        $modelFieldLogic = new ModelFieldLogic();
        $modelLogic = new ModelLogic();
        //获取当前模型下拉列表
        $this->assign("ModelDropDownList", $modelLogic->GetDropDownList($this->GetSiteID()));
        //获取字段验证类型下拉列表
        $this->assign("FieldNameValidTypeDropDownList", $modelFieldLogic->GetFieldNameValidTypeDropDownList());
        //填写类型
        $this->assign("FieldTypeDropDownList", $modelFieldLogic->GetFieldTypeDropDownList());
        
        $id = input("id/d",0);
        $data = [];
        if($id != 0){
            $data = Db::name("model_field")->where("ID", $id)->find();
        }
        $this->assign("data",$data);
        return $this->fetch();
    }
    
    /**
     * 用户提交数据
     */
    public function post_model_field_edit(){
        $modelFieldLogic = new ModelFieldLogic();
        $mysqlLogic = new MySqlLogic();
        
        $inputData["ID"] = input("ID/d",0);
        $inputData["FieldName"] = input("FieldName");
        $inputData["FieldNameCode"] = input("FieldNameCode");
        $inputData["FieldNameMaxLength"] = input("FieldNameMaxLength","");
        $inputData["FieldNameValidType"] = input("FieldNameValidType/a",[]);
        foreach ($inputData["FieldNameValidType"] as $v){
            $inputData["FieldNameValidTypeTip"][] = input("FieldNameValidTypeTip".$v);
        }
        $inputData["FieldNameTip"] = input("FieldNameTip");
        $inputData["FieldType"] = input("FieldType");
        $inputData["FieldWidth"] = input("FieldWidth","");
        $inputData["FieldHeight"] = input("FieldHeight","");
        $inputData["FieldDefault"] = input("FieldDefault");
        $inputData["FieldHtml"] = input("FieldHtml");
        $inputData["ModelID"] = input("ModelID");
        $inputData["Sort"] = input("Sort");
        $inputData["FieldClass"] = input("FieldClass");
        //获取生成填写字段html
        $inputData["FieldHtml"] = $modelFieldLogic->CreateFieldHtml($inputData);
        //判断
        if($inputData["ID"] == 0){
            $this->IsPower("model",["add"]);
            $addData = $inputData;
            if(!empty($addData["FieldNameValidType"])){
                $addData["FieldNameValidType"] = implode("|", $addData["FieldNameValidType"]);
            }
            if(!empty($addData["FieldNameValidTypeTip"])){
                $addData["FieldNameValidTypeTip"] = implode("|", $addData["FieldNameValidTypeTip"]);
            }
            unset($addData["ID"]);
            $data["Sort"] = $modelFieldLogic->GetMaxSort($inputData["ModelID"]);
            Db::startTrans();
            try{
                $result = Db::name("model_field")->insert($addData);
                if(!$result){
                    $this->error("保存字段信息到数据库失败");
                }
                $sql = $mysqlLogic->GetAddFieldSql($this->GetSiteCode(), $addData);
                Db::execute($sql);
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                throw new \Exception($e,500);
            }
            $this->success("操作成功",url("model_field_edit"));
        }else{
            $this->IsPower("model",["update"]);
            $updateData = $inputData;
            if(!empty($updateData["FieldNameValidType"])){
                $updateData["FieldNameValidType"] = implode("|", $updateData["FieldNameValidType"]);
            }else{
                $updateData["FieldNameValidType"] = "";
            }
            if(!empty($updateData["FieldNameValidTypeTip"])){
                $updateData["FieldNameValidTypeTip"] = implode("|", $updateData["FieldNameValidTypeTip"]);
            }else{
                $updateData["FieldNameValidTypeTip"] = "";
            }
            unset($updateData["ID"]);
            unset($updateData["ModelID"]);
            Db::startTrans();
            $result = Db::name("model_field")->where("ID",$inputData["ID"])->update($updateData);
            if(!$result){
                $this->error("保存字段信息到数据库失败");
            }
            try{
                $sql = $mysqlLogic->GetUpdateFieldSql($this->GetSiteCode(), $inputData);
                Db::execute($sql);
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                throw new \Exception($e,500);
            }
            $this->success("修改成功",url("model_field_edit",["id" => $inputData["ID"]]));
        }
    }
}
