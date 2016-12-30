<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use think\Db;
use app\manage\logic\NavigationLogic;
use app\manage\logic\SiteLogic;
use app\manage\logic\ModelLogic;

class Navigation extends ManageBase
{
    public function index(){
        $navigationLogic = new NavigationLogic();
        $navigationLogic->ClearCache();
        $list = $navigationLogic->GetAllNavigationTree();
        $this->view->list = $list;
        return $this->fetch();
    }
    
    function opt_index(){
        $opt = input("operate");
        //删除
        if($opt == "delete"){
            $this->opt_index_delete();
        }else if($opt == "sort"){//排序
            $this->opt_index_sort();
        }
    
    }
    /**
     * 排序
     */
    private function opt_index_sort(){
        $navigationLogic = new NavigationLogic();
        $ida = input("ID/a");
        if(empty($ida) || !is_array($ida)){
            $this->error("请选择要排序的项目",url("index"));
        }
        $count = count($ida);
        $successCount = 0;
        foreach ($ida as $value){
            $update = ["ID" =>$value,"Sort"=> input("Sort".$value)];
            $result= Db::name("navigation")->update($update);
            if($result){
                $successCount++;
            }
        }
        if($successCount == 0){
            //清空缓存
            $navigationLogic ->ClearCache();
            $this->error("排序失败");
        }else if($successCount == $count){
            //清空缓存
            $navigationLogic ->ClearCache();
            $this->success("排序成功");
        }else{
            $this->success("部分排序成功");
        }
    }
    
    /**
     * 操作删除
     */
    private function opt_index_delete(){
        $navigationLogic = new NavigationLogic();
        $id = input("ID/a");
        if(empty($id) || !is_array($id)){
            $this->error("请选择要删除的项目",url("index"));
        }
        //判断是否允许删除
        $id = implode(',',$id);
        $count = Db::name("navigation")->where("ParentID","in",$id)->count();
        if($count != 0) {
            $this->error("包含子项，请先删除所有子项。");
        }
        $result = Db::name("navigation")->delete($id);
        if($result){
            $navigationLogic->ClearCache();
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }
    
    
    function edit(){
        $navigationLogic = new NavigationLogic();
        $siteLogic = new SiteLogic();
        $modelLogic = new ModelLogic();
        
        $this->assign("ParentID_drop_down_array",$navigationLogic->GetDropdownMenuArray());
        //设置站点下拉列表
        $siteDropDownList = $siteLogic->GetDropDownList();
        $this->assign("SiteDropDownList", $siteDropDownList);
        //设置模型下拉列表
        $modelDropDownList = $modelLogic->GetDropDownList($siteDropDownList[0]["Value"]);
        $this->assign("ModelDropDownList", $modelDropDownList);
        $id = input("id",0);
        $data = [];
        if($id != 0){
            $data = Db::name("navigation")->where("id",$id)->find();
            $this->assign("data", $data);
        }
        $this->assign("data", $data);
        return $this->fetch("edit");
    }
    /**
     * 通过传入的站点信息获取模型下拉列表
     */
    public function JsonGetModelList(){
        $siteid = input("siteid");
        $modelLogic = new ModelLogic();
        $list = $modelLogic->GetDropDownList($siteid);
        $jsonData["Status"] = 1;
        $jsonData["Msg"] = "";
        $jsonData["Data"] = $list;
        return json($jsonData); 
    }
    
    
    function post_edit(){
        $navigationLogic = new NavigationLogic();
        $input_model["ID"] = input("ID", 0);
        $input_model["Title"] = input("Title");
        $input_model["DefaultImage"] = input("DefaultImage");
        $input_model["HoverImage"] = input("HoverImage");
        $input_model["Controller"] = input("Controller");
        $input_model["Action"] = input("Action");
        $input_model["BackController"] = input("BackController");
        $input_model["BackAction"] = input("BackAction");
        $input_model["ParentID"] = input("ParentID");
        $input_model["SiteID"] = input("SiteID");
        $input_model["Url"] = input("Url");
        $input_model["Type"] = input("Type");
        $input_model["Sort"] = input("Sort",0);
        $input_model["ModelID"] =  input("ModelID",0);
        $navigationLogic->ClearCache();
        //添加
        if($input_model["ID"] == 0){
            $addData = $input_model;
            unset($addData["ID"]);
            if($addData["Sort"] == 0){
                $addData["Sort"] = $navigationLogic->GetMaxsortByParentid($input_model["ParentID"]);
            }
            $result = Db::name("navigation")->insert($addData);
            if($result){
                $this->success("添加成功", url("edit"));
            }else{
                $this->error("添加失败");
            }
        }else{//修改
            $update = $input_model;
            unset($update["ID"]);
            $result = Db::name("navigation")->where("ID",$input_model["ID"])->update($update);
            if($result){
                $this->success("修改成功",url("edit",["id"=>$input_model["ID"]]));
            }else{
                $this->error("修改失败");
            }
        }
    }
}
