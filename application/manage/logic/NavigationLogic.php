<?php
namespace app\manage\logic;
use think\Db;
use think\Cache;

class NavigationLogic
{
    public $_CacheAllNavigationListKey = "CacheAllNavigationListKey";
    /**
     * 
     */
    public function GetAllNavigationTree(){
        $allNavigationList = Cache::get($this->_CacheAllNavigationListKey);
        if(empty($allNavigationList)){
            $allNavigationList = array();
            $this->GetNavigationChildTree($allNavigationList,0,0);
            Cache::set($this->_CacheAllNavigationListKey,$allNavigationList,86400);
        }
        return $allNavigationList;
        
    }
    /**
     * 获取当前栏目和所属子栏目下拉列表数据
     * @param number $currentNavigation 当前栏目编号
     * @param number $level
     * @return array 下拉列表数组
     */
    public function GetCurrentAndChildTreeDropDownList($currentNavigation,$level = 0){
        $data = Db::name("navigation")->where("ID", $currentNavigation)
        ->field("ID,Title")->find();
        $listArray[] = $data;
        $this->GetNavigationChildTree($listArray,$data["ID"],1);
        return $listArray;
    }
    /**
     * 回去树形方式的子目录
     * @param unknown $listArray
     * @param unknown $parentID
     * @param number $level
     */
    public function GetNavigationChildTree(&$listArray = array(), $parentID = 0, $level = 0){
        $sql = "select n.*,m.Name as ModelName,np.Title as ParentName,s.Name as SiteName ".
            " from mc_navigation as n".
            " left join mc_site as s on s.ID = n.SiteID".
            " left join mc_model as m on n.ModelID = m.ID".
            " left join mc_navigation as np on n.ParentID = np.ID".
            " where n.ParentID = :ParentID". 
            " order by n.Sort".
            "";
        $para["ParentID"] = $parentID;
        $navigationArray = Db::query($sql,$para);
        if(count($navigationArray) == 0){
            return;
        }
        foreach($navigationArray as $key=>&$value){
            $value["Title"] = "|".str_repeat ("&nbsp;|", $level) ."-".$value["Title"];
            $listArray[] = $value;
            $this->GetNavigationChildTree($listArray, $value["ID"], ($level + 1));
        }
    }
    
	/**
	 * 获取下拉列表框的值
	 */
	public function GetDropdownMenuArray(){		
		$all_menu_list =Cache::get($this->_CacheAllNavigationListKey);
	    if(empty($all_menu_list)){
            $all_menu_list = array();
            $this->GetNavigationChildTree($all_menu_list,0,0);
            Cache::set($this->_CacheAllNavigationListKey,$all_menu_list,86400);
        }
		return $all_menu_list;
	}
	/**
	 * 清楚菜单下拉列表框的缓存
	 */
	function ClearCache(){
	    Cache::rm($this->_CacheAllNavigationListKey);
	}
	/**
	 * 通过parentID获取当前最大的排序编号
	 * @param unknown $parentID
	 */
	function GetMaxsortByParentid($parentID){		
		$maxID = 0;
		$result = Db::name("navigation")->where("ParentID", $parentID)
		->order("sort desc")->find();
		if($result){
			$maxID = $result["Sort"] + 1;
		}
		return $maxID;
	}
}
