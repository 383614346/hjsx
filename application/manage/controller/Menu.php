<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use think\Db;
use app\manage\logic\MenuLogic;

class Menu extends ManageBase
{
    public function index(){
        $menuLogic = new MenuLogic();
        $list = $menuLogic->GetAllMenuTree();
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
        $menuLogic = new MenuLogic();
        $id = input("ID/a");
        if(empty($id) || !is_array($id)){
            $this->error("请选择要排序的项目",url("index"));
        }
        Db::startTrans();
        $result = true;
        foreach ($id as $value){
            $update_array = ["ID" =>$value,"Sort"=> input("Sort".$value),"UpdateTime" => time()];
            $result= Db::table("mc_menu")->update($update_array);
            if(!$result){
                break;
            }
        }
        if($result){
            //清空缓存
            $menuLogic->ClearCache();
            Db::commit();
            $this->success("排序成功");
        }else{
            Db::rollback();
            $this->error("排序失败");
        }
    }
    
    /**
     * 操作删除
     */
    private function opt_index_delete(){
        $menuLogic = new MenuLogic();
        $id = input("ID/a");
        if(empty($id) || !is_array($id)){
            $this->error("请选择要删除的项目",url("index"));
        }
        //判断是否允许删除
        $id = implode(',',$id);
        $result = Db::table("mc_menu")->where("ParentID","in",$id)->select();
        if(count($result) != 0) {
            $this->error("包含子项，请先删除所有子项。");
        }
        $result = Db::table("mc_menu")->delete($id);
        if($result){
            $menuLogic->ClearCache();
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }
    
    function show_edit(){
        $this->InitDropDownListSelect();
        return $this->fetch("show_edit");
    }
    
    function update_edit(){
        $this->InitDropDownListSelect();
        $id = input("id");
        $menu_model = Db::table("mc_menu")->where("id",$id)->find();
        $this->assign("menu", $menu_model);
        return $this->fetch("edit");
    }
    
    private function InitDropDownListSelect(){
        $menuLogic = new MenuLogic();
        $this->assign("ParentID_drop_down_array",$menuLogic->GetDropdownMenuArray());
    }
    
    function post_edit(){
        $menuLogic = new MenuLogic();
        $input_model["ID"] = input("ID", 0);
        $input_model["Title"] = input("Title");
        $input_model["ControllerName"] = input("ControllerName");
        $input_model["ActionName"] = input("ActionName");
        $input_model["Sort"] = input("Sort");
        $input_model["ParentID"] =  input("ParentID",0);
        
        $menuLogic->ClearCache();
        //添加
        if($input_model["ID"] == 0){
            $input_model["Sort"] = $menuLogic->GetMaxsortByParentid($input_model["ParentID"]);
            $result = Db::table("mc_menu")->insert($input_model);
            if($result){
                $this->success("添加成功", url("show_edit"));
            }else{
                $this->error("添加失败");
            }
        }else{//修改
            $result = Db::table("mc_menu")->update($input_model);
            if($result){
                $this->success("修改成功",url("update_edit",["id"=>$input_model["ID"]]));
            }else{
                $this->error("修改失败");
            }
        }
        
    }
}
