<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use think\Db;


class ManageBase extends Controller
{
    /**
     * 当前登录用户信息
     * @var unknown
     */
    public $_CURRENTLOGINMANAGE = null;
    private $SiteCode = "";
    private $SiteID = 0;
    function _initialize()
    {
        //判断用户是否登陆
        $this->valid_is_login();
//         $this->Init();
    }
    /**
     * 获取当前站点代码
     */
    public function GetSiteCode(){
        if(empty($this->SiteCode)){
            if($this->SiteID < 1){
                $tempSiteID = input("siteid");
                $data = Db::name("site")->where("ID", $tempSiteID)->find();
                if($data){
                    $this->SiteCode = $data["Code"];
                    $this->SiteID = $tempSiteID;
                }else{
                    $data = Db::name("site")->find();
                    $this->SiteCode = $data["Code"];
                    $this->SiteID = $data["ID"];
                }
            }
        }
        return $this->SiteCode;
    }
    /**
     * 获取当前站点编号
     */
    public function GetSiteID(){
        if($this->SiteID < 1){
            $tempSiteID = input("siteid/d",0);
            if($tempSiteID == 0){
                $data = Db::name("site")->find();
                $this->SiteCode = $data["Code"];
                $this->SiteID = $data["ID"];
            }
        }
        return $this->SiteID;
    }
    
//     function Init(){
//         $iframe = input("iframe");
//         if(!empty($iframe)){
//                 view("iframe",$iframe);
//         }
//         $backfunction = input("backfunction");
//         if(!empty($backfunction)){
//             view("backfunction",$backfunction);
//         }
//     }
    
    
    
    /**
     * 获取iframe需要的参数
     * @param unknown $urlArray
     */
//     function SetIframeUrlParam(&$urlArray){
//         $iframe = input("iframe");
//         if(!empty($iframe)){
//             $urlArray["iframe"] = 1;
//         }
//         $backfunction = input("backfunction");
//         if(!empty($backfunction)){
//             $urlArray["backfunction"] = input("backfunction");
//         }
//     }
    
    
    /**
     * 判断用户是否有权限
     */
    function IsPower($controllerName, $powerArray = array(),$isTip = true){
        
        if(count($powerArray) == 0){
            if($isTip){
                $this->error("请输入您要验证的权限");
            }
            return 0;
        }
        $cookie_array = cookie("manage_info");
        if($this->_CURRENTLOGINMANAGE["RoleID"] == -1){
            return 1;
        }
        //获取菜单编号
        $menu_model = Db::table("tsyx_menu")->where("ControllerName",$controllerName)->find();
        if(!$menu_model){
            if($isTip){
                $this->error("未能查询到菜单信息");
            }
            return 0;
        }
        
        
        $where["RoleID"] = ["=",$this->_CURRENTLOGINMANAGE["RoleID"]];
        $where["MenuID"] = ["=",$menu_model["ID"]];
        $whereCode = [];
        for($i = 0; $i < count($powerArray); $i++){
            $item = &$powerArray[$i];
            if($i == 0){
                $whereCode[] = ["=",$item];
            }
        }
        if(count($whereCode) > 0){
            $whereCode[] = "or";
        }
        $where["Code"] = $whereCode;
        $count = Db::table("tsyx_power")
        ->where($where)
        ->count();
        if($count){
            return 1;
        }
        if($isTip){
            $this->error("您没有权限请联系管理员",url("Platform/index"));
        }
        return 0;
    }
   
    
    /**
     * 判断用户是否登陆
     */
    function valid_is_login(){
        $cookie_array = cookie("manage_info");
        //如果cookie为空
        if(!$cookie_array){
            $this->error("请先登陆。", url('index/index'));
        }
        //判断用户是否登陆
        $result = Db::table("mc_member")->where("LoginGUID", $cookie_array["LoginGUID"] )->find();
        $this->_CURRENTLOGINMANAGE = $result;
        if($result){
            $this->assign("manage_name",$result["NiceName"]);
        }else{
            $this->error("请先登陆。", url('index/index'));
        }
    }
    /**
     * 写入日志信息
     * @param unknown $text
     */
    function log($text){
        $text = date("Y-m-d H:i")."    ".json_encode($text,JSON_UNESCAPED_UNICODE).PHP_EOL;
        file_put_contents("./log.txt", $text, FILE_APPEND);
    }
    /**
     * * 指定位置插入字符串 * @param $str 原字符串 * @param $i 插入位置 * @param $substr 插入字符串 * @return string 处理后的字符串
     */
    public function insertToStr($str, $i, $substr)
    {
        // 指定插入位置前的字符串
        $startstr = "";
        for ($j = 0; $j < $i; $j ++) {
            $startstr .= $str[$j];
        }
        // 指定插入位置后的字符串
        $laststr = "";
        for ($j = $i; $j < strlen($str); $j ++) {
            $laststr .= $str[$j];
        }
        // 将插入位置前，要插入的，插入位置后三个字符串拼接起来
        $str = $startstr . $substr . $laststr;
        // 返回结果
        return $str;
    }
    public function create_guid() {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = // chr(123)"{"
        substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid,12, 4).$hyphen
        .substr($charid,16, 4).$hyphen
        .substr($charid,20,12);
       // .chr(125);// "}"
        return $uuid;
    }
    
}
