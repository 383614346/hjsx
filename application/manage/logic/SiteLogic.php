<?php
namespace app\manage\logic;
use think\Db;
/**
 * 站点逻辑层
 * @author lc
 *
 */
class SiteLogic {
    /**
     * 获取站点下拉列表
     */
    public function GetDropDownList(){
        $list = Db::name("site")->order("ID")->field("ID,Name")->select();
        $showList = [];
        foreach($list as $v){
            $showList[] = ["Key"=>$v["Name"],"Value"=>$v["ID"]];
        }
        return $showList;
    }
    /**
     * 将指定站点设为默认
     */
	public function SetDefault($id){
	    Db::name("site")->where("1=1")->update(["IsDefault"=>0]);
        if($id == 0){
            return true;
        }
       return Db::name("site")->where("ID",$id)->update(["IsDefault"=>1]);
	}
	/**
	 * 判断代码是否存在
	 * @param int $id
	 * @param string $code
	 */
	public function IsExists($id,$code){
	    return Db::name("site")->where("ID","<>", $id)
	    ->where("Code",$code)->count();
	}
}