<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use think\Db;

class Role extends ManageBase{
	
	public function index(){
 	    $list = Db::table("mc_role")->paginate(1);
 	    $this->assign("page",$list->render());
		$this->assign("list",$list);
		return $this->fetch();
	}
	
	function opt_index(){
		$opt = input("operate");		
		//删除
		if($opt == "delete"){
			$this->OptDelete();
		}
	
	}
	
	private function OptDelete(){
	    $id = input("ID/a");
	    if(empty($id) || !is_array($id)){
	        $this->error("请选择要删除的项目",url("index"));
	    }
	    //判断是否允许删除
	    $id = implode(',',$id);
	    $result = Db::table("mc_role")->delete($id);
	    if($result){
	        $this->success("删除成功");
	    }else{
	        $this->error("删除失败");
	    }
	}
	
	function show_edit(){
		return $this->fetch("show_edit");
	}
	
	function update_edit(){
		$id = input("id",0);
		$model = Db::table("mc_role")->where("ID", $id)->find();
		$this->assign("role", $model);
		return $this->fetch("edit");
	}
	
	function post_edit(){
		$input_model["ID"] = input("ID", 0);		
		$input_model["Name"] = input("Name");
		
		//添加
		if($input_model["ID"] == 0){
			$result = Db::table("mc_role")->insert($input_model);
			if($result){
				$this->success("添加成功", url("show_edit"));
			}else{
				$this->error("添加失败");
			}
		}else{//修改
			$result = Db::table("mc_role")->update($input_model);
			if($result){
				$this->success("修改成功",url("update_edit",array("id"=>$input_model["ID"])));
			}else{
				$this->error("修改失败");
			}
		}
	}
}