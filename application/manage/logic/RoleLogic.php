<?php
namespace app\manage\logic;
use think\Db;

class RoleLogic {
    /**
     * 获取下拉列表选择项
     */
	public function GetSelectArray(){
	   $list = Db::table("mc_role")->order("ID desc")->select();
	   return $list;
	}
}