<?php
namespace app\manage\logic;
use think\Db;
use think\Cache;

class MenuLogic
{
    public $_CacheAllMenuListKey = "all_menu_list";
    /**
     * 
     */
    public function GetAllMenuTree(){
        $all_menu_list = Cache::get($this->_CacheAllMenuListKey);
        if(empty($all_menu_list)){
            $all_menu_list = array();
            $this->GetMenuChildTree($all_menu_list,0,0);
            Cache::set($this->_CacheAllMenuListKey,$all_menu_list,86400);
        }
        return $all_menu_list;
        
    }
    /**
     * 回去树形方式的子目录
     * @param unknown $listArray
     * @param unknown $parentID
     * @param number $level
     */
    public function GetMenuChildTree(&$listArray = array(), $parentID = 0, $level = 0){
        $menu_array = Db::table("mc_menu")->where("ParentID",$parentID)
        ->order("Sort")->select();
        if(count($menu_array) == 0){
            return;
        }
        foreach($menu_array as $key=>&$value){
            $value["Title"] = "|".str_repeat ("&nbsp;|", $level) ."-".$value["Title"];
            $listArray[] = $value;
            
            $this->GetMenuChildTree($listArray, $value["ID"], ($level + 1));
        }
    }
    
	/**
	 * 获取下拉列表框的值
	 */
	public function GetDropdownMenuArray(){		
		$all_menu_list =Cache::get($this->_CacheAllMenuListKey);
	    if(empty($all_menu_list)){
            $all_menu_list = array();
            $this->GetMenuChildTree($all_menu_list,0,0);
            Cache::set($this->_CacheAllMenuListKey,$all_menu_list,86400);
        }
		return $all_menu_list;
	}
	/**
	 * 清楚菜单下拉列表框的缓存
	 */
	function ClearCache(){
	    Cache::rm($this->_CacheAllMenuListKey);
	}
	/**
	 * 通过parentID获取当前最大的排序编号
	 * @param unknown $parentID
	 */
	function GetMaxsortByParentid($parentID){		
		$maxID = 0;
		$result = Db::table("mc_menu")->where(" ParentID", $parentID)
		->order(" sort desc ")->find();
		if($result){
			$maxID = $result["Sort"] + 1;
		}
		return $maxID;
	}
}
