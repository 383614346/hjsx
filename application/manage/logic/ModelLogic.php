<?php
namespace app\manage\logic;
use think\Db;
/**
 * 模型逻辑层
 * @author lc
 *
 */
class ModelLogic {
   
	/**
	 * 判断代码是否存在
	 * @param int $id
	 * @param string $code
	 */
	public function IsExists($id,$code){
	    return Db::name("model")->where("ID","<>", $id)
	    ->where("Code",$code)->count();
	}
	/**
	 * 获取模型下拉列表
	 * @param int $siteID 当前站点
	 */
	public function GetDropDownList($siteID){
	    $dataList =  Db::name("model")->where("SiteID", $siteID)->field("ID,Name")
	    ->order("ID desc")->select();
	    $showList = [];
	    foreach ($dataList as $v){
	        $showList[] = ["Key"=>$v["Name"],"Value" =>$v["ID"]];
	    }
	    return $showList;
	}
	
}