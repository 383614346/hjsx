<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use think\Db;
use app\manage\logic\MySqlLogic;
use app\manage\logic\ContentLogic;
use app\manage\logic\ModelFieldLogic;
use app\manage\logic\ModelLogic;
use app\manage\logic\NavigationLogic;

class Content extends ManageBase
{
    public function index(){
        $this->IsPower("content",["select"]);
        $this->assign("list",[]);
        return $this->fetch();
    }
    
    public function opt_index(){
        $opt = input("operate");
        //删除
        if($opt == "delete"){
            $this->opt_index_delete();
        }else if($opt == "sort"){//排序
            $this->opt_index_sort();
        }
    }
    
    public function opt_index_sort(){
        $this->IsPower("content",["update"]);
        $initPagePara = $this->GetContenListtUrl();
        $ida = input("ID/a");
        if(empty($ida) || !is_array($ida)){
            $this->error("请选择要排序的项目",url("content_list", $initPagePara));
        }
        $mysqlLogic = new MySqlLogic();
        
        $count = count($ida);
        $successCount = 0;
        $tableName = $mysqlLogic->GetTableName($initPagePara["model_id"]);
        foreach ($ida as $value){
            $update = ["ID" =>$value,"Sort"=> input("Sort".$value)];
            $result= Db::table($tableName)->update($update);
            if($result){
                $successCount++;
            }
        }
        if($successCount == 0){
            //清空缓存
            $this->error("排序失败");
        }else if($successCount == $count){
            //清空缓存
            $this->success("排序成功",url("content_list", $initPagePara));
        }else{
            $this->success("部分排序成功",url("content_list", $initPagePara));
        }
    }
    
    public function opt_index_delete(){
        $this->IsPower("content",["delete"]);
        $initPagePara = $this->GetContenListtUrl();
        $ida = input("ID/a");
        if(empty($ida) || !is_array($ida)){
            $this->error("请选择要排序的项目",url("content_list", $initPagePara));
        }
        $mysqlLogic = new MySqlLogic();
        $ids = implode(",", $ida);
        $tableName = $mysqlLogic->GetTableName($initPagePara["model_id"]);
        $result = Db::table($tableName)->where("ID","in",$ids)->delete();
        if($result){
            $this->success("删除成功",url("content_list", $initPagePara));
        }else{
            $this->error("删除失败");
        }
    }
    
    /**
     * 获取树形信息
     */
    public function AjaxGetTreeList(){
        $this->IsPower("content",["select"]);
        $id = input("id",0);
        $jsonData = [];
        $list = Db::name("navigation")->where("ParentID", $id)->order("Sort")
        ->field("ID,Title,ModelID")
        ->select();
        
        foreach ($list as $v){
            $data["id"] = $v["ID"];
            $data["text"] = $v["Title"];
            $data["state"] = $this->ValidHasChild($id) ? 'closed' : 'open';
            $data["url"] = url("content_list",["navigation_id"=>$v["ID"],"model_id" => $v["ModelID"]]);
            $jsonData[] = $data;
        }
        
        return json($jsonData);
    }
    private function ValidHasChild($id){
        $count = Db::name("navigation")->where("ParentID", $id)->count();
        return $count > 0 ? true : false;
    }
    
    public function content_list(){
        $this->IsPower("content",["select"]);
        $mysqlLogic = new MySqlLogic();
        $initDatePara = $this->GetContenListtUrl();
        $tableName = $mysqlLogic->GetTableName($initDatePara["model_id"]);
//         $navigationModel = Db::name("navigation")->where("ID", $inputDate["navigation_id"])->find();
        $list = Db::table($tableName)
        ->where("NavigationID", $initDatePara["navigation_id"])
        ->order("Sort desc")->paginate(20);
        
        $this->assign("list",$list);
        $this->assign("page", $list->render());
        return $this->fetch();
    }
    
    public function content_edit(){
        $this->IsPower("content",["update"]);
        $mysqlLogic = new MySqlLogic();
        $contentLogic = new ContentLogic();
        $navigationLogic = new NavigationLogic();

        $inputData = $this->GetContenListtUrl();
        $inputData["ID"] = input("id/d",0);
        //设置所属菜单下拉列表
        $naviagationDropDownList = $navigationLogic->GetCurrentAndChildTreeDropDownList($inputData["navigation_id"]);
        $this->assign("NavigationDropDownList", $naviagationDropDownList);
        
        $tableName = $mysqlLogic->GetTableName($inputData["model_id"]);
        $data = [];
        if(!empty($inputData["ID"])){
            $data = Db::table($tableName)->where("ID", $inputData["ID"])->find();
        }
        //获取编辑元素html
        $editHtml = "";
        $editDefaultHtml = "";
        $contentLogic->GetContentEditFieldHtml($inputData["model_id"],$data, $editHtml, $editDefaultHtml);
        $this->assign("EditHtml", $editHtml);
        $this->assign("EditDefaultHtml", $editDefaultHtml);
        
        return $this->fetch();
    }
    
    public function post_content_edit(){
        $this->IsPower("content",["update"]);
        $mysqlLogic = new MySqlLogic();
        $contentLogic = new ContentLogic();
        $modelFieldLogic = new ModelFieldLogic();
        //页面数据
        $initPageData = $this->GetContenListtUrl();
        //用户输入数据        
        $inputData = $modelFieldLogic->GetUserInputField($initPageData["model_id"], input("param."));
        if($inputData["ID"] == 0){//添加
            $insertData = $inputData;
            unset($insertData["ID"]);
            $tableName = $mysqlLogic->GetTableName($initPageData["model_id"]);
            if($insertData["Sort"] == 0){
                $insertData["Sort"] = $contentLogic->GetMaxSort($tableName, $initPageData["navigation_id"]);
            }
            $result = Db::table($tableName)->insert($insertData);
            if($result){
                $this->success("操作成功",url("content_edit",$initPageData));
            }else{
                $this->error("添加失败请重试");
            }
        }else{//修改
            $updateData = $inputData;
            unset($updateData["ID"]);
            $tableName = $mysqlLogic->GetTableName($initPageData["model_id"]);
            $result = Db::table($tableName)->where("ID", $inputData["ID"])->update($updateData);
            if($result){
                $initPageData["id"] = $inputData["ID"];
                $this->success("修改成功",url("content_edit",$initPageData));
            }else{
                $this->error("添加失败请重试");
            }
        }
                
    }
    private function GetContenListtUrl(){
        $para["navigation_id"] = input("navigation_id",0);
        $this->assign("navigation_id",$para["navigation_id"]);
        $para["model_id"] = input("model_id/d",0);
        $this->assign("model_id",$para["model_id"]);
        return $para;
    }
}
