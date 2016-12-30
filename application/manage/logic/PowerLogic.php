<?php
namespace app\manage\logic;
use think\Db;

class PowerLogic {
    /**
     * 获取显示的权限信息
     */
	public function GetShowPowerTreeArray(){
	   $dataArray = array();
	   $list = Db::table("mc_menu")->where("ParentID",0)->order("Sort")->select();
	   foreach($list as $k=>&$v){
	       $v["Type"] = 1;
	       $v["Level"] = 0;
	       $dataArray[]  = $v;
	       $this->GetChildPowerTreeArray($dataArray, $v["ID"], 1);
	   }
	   return $dataArray;
	}
	/**
	 * 获取树形结构的
	 * @param unknown $dataArray
	 * @param unknown $parentID
	 * @param unknown $level
	 */
	public function GetChildPowerTreeArray(&$dataArray, $parentID,$level){
	    $list = Db::table("mc_menu")->where("ParentID", $parentID)->order("Sort")->select();
	    foreach ($list as $k=>&$v){
	        $v["Type"] = 1;
	        $v["Level"] = $level;
	        $dataArray[] = $v;
	        $this->GetChildPowerTreeArray($dataArray, $v["ID"], ($level+1));
	    }
	    //如果已经是根目录就开始添加权限
	    if(!$list){
	        $childData[0]["Name"] = "查询";
	        $childData[0]["Code"] = "select";
	        $childData[0]["ParentID"] = $parentID;
	        $childData[1]["Name"] = "增加";
	        $childData[1]["Code"] = "add";
	        $childData[1]["ParentID"] = $parentID;
	        $childData[2]["Name"] = "修改";
	        $childData[2]["Code"] = "update";
	        $childData[2]["ParentID"] = $parentID;
	        $childData[3]["Name"] = "删除";
	        $childData[3]["Code"] = "delete";
	        $childData[3]["ParentID"] = $parentID;
	        //获取通用权限
	        $commonPowerList = Db::table("mc_common_power")
	        ->where("MenuID", $parentID)->order("ID")->select();
	        $j = count($commonPowerList);
	        for($i = 0; $i < $j; $i++){
	            $tempData["ID"] = $parentID;
	            $tempData["Name"] = $commonPowerList[$i]["Name"];
	            $tempData["Code"] = $commonPowerList[$i]["Code"];
	            $tempData["ParentID"] = $parentID;
	            $childData[] = $tempData;
	        }
	        $data["Type"] = 2;
	        $data["Level"] = $level;
	        $data["ID"] = $parentID;
	        $data["Child"] = $childData;
	        $dataArray[] = $data;
	    }
	    
	}
	
	/**
	 * 获取通用
	 * @param unknown $menuID
	 */
	public function GetCommonPowerList($model_db,$menuID){
	    return Db::table("mc_common_power")->where("MenuID", $menuID)->select();
	    
	}
}