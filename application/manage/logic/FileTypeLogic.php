<?php
namespace app\manage\logic;
use think\Db;
use think\Cache;

class FileTypeLogic{
    /**
     *  图片分类树形缓存健名
     */
    public $_CaseNameFileTypeTree = "FileTypeTree";
    /**
     * 
     */
    public function GetAllFileTypeTree(){
        $file_type_tree = Cache::get($this->_CaseNameFileTypeTree);
        if(empty($file_type_tree)){
            $this->GetFileTypeChildTree($file_type_tree,0,0);
            Cache::set($this->_CaseNameFileTypeTree,$file_type_tree);
        }
        return $file_type_tree;
    }
    /**
     * 回去树形方式的子目录
     * @param unknown $listArray
     * @param unknown $parentID
     * @param number $level
     */
    public function GetFileTypeChildTree(&$listArray = array(), $parentID = 0, $level = 0){
        $menu_array = Db::table("mc_file_type")->where("ParentID",$parentID)
        ->order("ID desc")->select();
        foreach($menu_array as $key=>&$value){
            $value["Title"] = "|".str_repeat ("&nbsp;|", $level) ."-".$value["Title"];
            $listArray[] = $value;
            $this->GetFileTypeChildTree($listArray, $value["ID"], ($level + 1));
        }
    }
    
	/**
	 * 清楚菜单下拉列表框的缓存
	 */
	function ClearCache(){
	    Cache::rm($this->_CaseNameFileTypeTree);
	}
	
}