<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use think\Db;
use app\manage\logic\MenuLogic;

class CommonPower extends ManageBase{
	
	public function index($isShow = true){
	    $this->IsPower("CommonPower",array("select"),true);
	    
	    $serachMenuName = input("SerachMenuName");
	    $serachData["MenuName"] = $serachMenuName;
	    $this->view->Serach = $serachData;
	    $map = [];
	    //搜索条件
	    if(!empty($serachMenuName)){
	        $map["m.Title"] = ['like', '%'. $serachMenuName .'%'];
	    }
	    $list = Db::table("mc_common_power")->alias("c")
	    ->join("mc_menu  m","m.ID = c.MenuID")
	    ->where($map)
	    ->field("c.*,m.Title")
	    ->order("c.ID desc")
	    ->paginate(15);
	    $this->assign("list", $list);
	    $this->assign("page",$list->render());
		if($isShow){
		    return $this->fetch();
		}
		
	}
	
	function opt_index(){
		$opt = input("operate");		
		//删除
		if($opt == "delete"){
			$this->OptDelete();
		}else if($opt == "serach"){
		    $this->index(false);
		    return $this->fetch("index");
		}else{
		    $this->index(false);
		    return $this->fetch("index");
		}
	}
	/**
	 * 删除
	 */
	private function OptDelete(){
	    $this->IsPower("CommonPower",array("delete"),true);
	    $id = input("ID/a");
	    if(empty($id) || !is_array($id)){
	        $this->error("请选择要删除的项目",url("index"));
	    }
	    $id = implode(',',$id);
	    $result = Db::table("mc_common_power")->delete($id);
	    if($result){
	        $this->success("删除成功",url("index"));
	    }else{
	        $this->error("删除失败");
	    }
	}
	
	function show_edit(){
	    $this->IsPower("CommonPower",array("add"),true);
	    $this->InitEdit();
		return $this->fetch("edit");
	}
	
	function update_edit(){
	    $this->IsPower("CommonPower",array("update"),true);
 	    $this->InitEdit();
 	    $data = Db::table("mc_common_power")->where("ID", input("id"))->find();
 	    $this->assign("data", $data);
		return $this->fetch("edit");
	}
	
	private function InitEdit(){
	    $menuLogic = new MenuLogic();
	    //获取下拉选择
	    $menuList = $menuLogic->GetAllMenuTree();
	    $this->assign("MenuList",$menuList);
	}
	
	function post_edit(){
	    $input_model["ID"] = input("ID", 0);		
		$input_model["MenuID"] = input("MenuID");
		$input_model["Code"] = strtolower(trim(input("Code")));
		$input_model["Name"] = trim(input("Name"));
		
		//添加
		if($input_model["ID"] == 0){
		    $this->IsPower("CommonPower",array("add"),true);
		    if($this->IsExistsCode(0, $input_model["MenuID"], $input_model["Code"])){
		        $this->error("已经存在相同代码");
		    }
			$result = Db::table("mc_common_power")->insert($input_model);
			if($result){
				$this->success("添加成功", url("show_edit"));
			}else{
				$this->error("添加失败");
			}
		}else{//修改
		    $this->IsPower("CommonPower",array("update"),true);
		    if($this->IsExistsCode($input_model["ID"], $input_model["MenuID"], $input_model["Code"])){
		        $this->error("已经存在相同代码");
		    }
			$result = Db::table("mc_common_power")->where("ID",$input_model["ID"] )->update($input_model);
			if($result){
				$this->success("修改成功",url("update_edit",array("id"=>$input_model["ID"])));
			}else{
				$this->error("修改失败");
			}
		}
	}
	/**
	 * 判断指定菜单的，代码是否已经存在
	 * @param unknown $menuId
	 * @param unknown $code
	 */
	private function IsExistsCode($id,$menuId, $code){
	    $count = Db::table("mc_common_power")
	    ->where("ID",$id)
	    ->where("MenuID",$menuId)
	    ->where("Code",$code)
	    ->count();
	    return $count;
	}
}