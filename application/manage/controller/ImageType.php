<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use think\Db;
use app\manage\logic\ImageTypeLogic;

class ImageType extends ManageBase {
    
    public function index(){
        $imageTypeLogic = new ImageTypeLogic();
        $list = $imageTypeLogic->GetAllImageTypeTree();
        $this->view->list = $list;
        return $this->fetch();
    }
    
    function opt_index(){
        $opt = input("operate");
        //删除
        if($opt == "delete"){
            $this->delete();
        }
    
    }
    
    /**
     * 删除分类
     */
    private function delete(){
        $imageTypeLogic = new ImageTypeLogic();
        
        $id = input("ID/a");
        if(empty($id) || !is_array($id)){
            $this->error("请选择要删除的项目",url("index"));
        }
        //判断是否允许删除
        $id = implode(',',$id);
        $result = Db::table("mc_image_type")->where("ParentID","in",$id)->count();
        if($result){
            $this->error("删除分类中存在字，请先删除子分类。");
        }
        $result = Db::table("mc_image_info")->where("ImageType","in",$id)->count();
        if($result){
            $this->error("删除分类中存在文件，请先删除文件。");
        }
         
        $result = Db::table("mc_image_type")->where("ID","in",$id)->delete();
        if($result){
            $imageTypeLogic->ClearCache();
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }
    
    function show_edit(){
        $this->InitEdit();
        return $this->fetch("edit");
    }
    
    function update_edit(){
        $this->InitEdit();
        $id = input("id",0);
        $model = Db::table("mc_image_type")->where("id", $id)->find();
        $this->assign("model", $model);
        return $this->fetch("edit");
    }
    
    private function InitEdit(){
        $imageTypeLogic = new ImageTypeLogic();
        $this->assign("drop_down_array",$imageTypeLogic->GetAllImageTypeTree());
    }
    
    function post_edit(){
        $imageTypeLogic = new ImageTypeLogic();
        $input_model["ID"] = input("ID", 0);
        $input_model["Title"] = trim(input("Title"));
        $input_model["ParentID"] = input("ParentID",0);
        $imageTypeLogic->ClearCache();
        //添加
        if($input_model["ID"] == 0){
            $result = Db::table("mc_image_type")->insert($input_model);
            if($result){
                
                $this->success("添加成功", url("show_edit"));
            }else{
                $this->error("添加失败");
            }
        }else{//修改
            $result = Db::table("mc_image_type")->where("ID", $input_model["ID"])->update($input_model);
            if($result){
                $this->success("修改成功",url("update_edit",array("id"=>$input_model["ID"])));
            }else{
                $this->error("修改失败");
            }
        }
    }
}