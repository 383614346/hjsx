<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use think\Db;

class Platform extends ManageBase
{
    public function index()
    {
        // 初始化后台系统菜单
        $this->init_menu();
        return $this->fetch();
    }

    /**
     * 初始化后台系统菜单
     */
    function init_menu()
    {
        // 获取当前登录人的角色
        $manageInfo = cookie("manage_info");
        $admin_model = Db::table("mc_member")
            ->where("LoginGUID", $manageInfo["LoginGUID"])
            ->find();
        if (!$admin_model) {
            $this->error("未能获取到您的信息，请重新登录", url("index/index"));
        }
        if (!$admin_model["RoleID"]) {
            $this->error("请联系管理员为您分配权限", url("index/index"));
        }
        $menu_array = Db::table("mc_menu")
            ->where("ParentID",0)
            ->order("Sort")
            ->select();
        
        $this->init_menu_getchild($menu_array);
       
        //判断获取当前权限所能查看的栏目
        $menu_array = $this->init_menu_valid_power($menu_array);
        $this->view->menu_array = $menu_array;
    }
    /**
     * 递归循环获取所有子项
     * @return NULL
     */
    private function init_menu_getchild(&$menu_array){
        if(count($menu_array) == 0){
            return ;
        }
        foreach ($menu_array as &$v){
            $v["ChildArray"] = Db::table("mc_menu")->where("ParentID",$v["ID"])
            ->order("Sort")
            ->select();
            $this->init_menu_getchild($v["ChildArray"]);
        }
    }
    /**
     * 判断指定菜单是否有操作权限
     */
    private function init_menu_valid_power($menu_array){
        if($this->_CURRENTLOGINMANAGE["RoleID"] == -1){
            return $menu_array;
        }
        //获取当前用户所有权限
        $power_list = Db::table("tsyx_power")->where("RoleID",$this->_CURRENTLOGINMANAGE["RoleID"])
        ->select();
        $show_menu_array = $this->init_menu_recurrence_valid_power($menu_array,$power_list);
        return $show_menu_array;
    }
    /**
     * 递归判断用户是否有权限
     */
    private function init_menu_recurrence_valid_power($menu_array,$power_list){
        if(!$menu_array){
            return;
        }
        if(empty($menu_array)){
            return;
        }
        $show_menu_array = array();
       
        foreach ($menu_array as $v){
            if(!empty($v["ChildArray"])){
                //递归调用
                $child_menu_array = $this->init_menu_recurrence_valid_power($v["ChildArray"],$power_list);
                if(count($child_menu_array) > 0){
                    $show_menu = $v;
                    $show_menu["ChildArray"] = $child_menu_array;
                    $show_menu_array[] = $show_menu;
                }
            }
        }
        //移除没有权限的项目
        foreach ($menu_array as $v){
            foreach($power_list as $pv){
                if($v["ID"] == $pv["MenuID"]){
                    $show_menu_array[] = $v;
                    break;
                }
            }
        }
        return $show_menu_array;
    }
}
