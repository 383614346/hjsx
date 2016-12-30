<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use think\Db;
use app\manage\logic\SiteLogic;

class Site extends ManageBase
{
    public function index(){
        $list = Db::name("site")->order("ID")->select();
        $this->view->list = $list;
        return $this->fetch();
    }
    
    function opt_index(){
        $opt = input("operate");
        //删除
        if($opt == "delete"){
            $this->opt_index_delete();
        }else if($opt == "default"){//设为默认
            $this->opt_index_default();
        }
    }
    
    private function opt_index_default(){
        $ida = input("ID/a",0);
        $siteLogic = new SiteLogic();
        $result = $siteLogic->SetDefault($ida[0]);
        if($result){
            $this->success("操作成功",url("index"));
        }else{
            $this->error("操作失败，请重试");
        }
    }
    
    private function opt_index_delete(){
        $ida = input("ID/a");
        $ids = implode(",", $ida);
        $result = Db::name("site")->where("ID","in", $ids)->delete();
        if($result){
            $this->success("删除成功",url("index"));
        }else{
            $this->error("删除失败，请重试");
        }
    }
    
    function show_edit(){
        return $this->fetch("edit");
    }
    
    function update_edit(){
        $id = input("id",0);
        $model = Db::name("site")->where("ID", $id)->find();
        $this->assign("data", $model);
        return $this->fetch("edit");
    }
    
    function post_edit(){
        $siteLogic = new SiteLogic();
        $input_data["ID"] = input("ID/d",0);
        $input_data["Name"] = input("Name");
        $input_data["Code"] = input("Code");
        $input_data["IsDefault"] = input("IsDefault/d",0);
        if($input_data["ID"] == 0){
            //判断code是否存在
            if($siteLogic->IsExists(0, $input_data["Code"])){
                $this->error("Code已经存在，请更换");
            }
            //判断是否取消默认站点
            if($input_data["IsDefault"]){
                $siteLogic->SetDefault(0);
            }
            $addData = $input_data;
            unset($addData["ID"]);
            $result = Db::name("site")->insert($addData);
            if($result){
                $this->success("添加成功",url("show_edit"));
            }else{
                $this->error("添加失败，请重试");
            }
        }else{//修改
            //判断code是否存在
            if($siteLogic->IsExists($input_data["ID"], $input_data["Code"])){
                $this->error("Code已经存在，请更换");
            }
            //判断是否取消默认站点
            if($input_data["IsDefault"]){
                $siteLogic->SetDefault(0);
            }
            $updateData = $input_data;
            unset($updateData["ID"]);
            unset($updateData["Code"]);
            $result = Db::name("site")->where("ID",$input_data["ID"])->update($updateData);
            if($result){
                $this->success("修改成功",url("update_edit",["id"=>$input_data["ID"]]));
            }else{
                $this->error("修改失败，请重试");
            }
        }
    }
}
