<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use PDO;
use think\Db;


class Index extends Controller
{
    public function index()
    {
        $view = new View();
        return $view->fetch();
    }
    
    public function login_post(){
        $username = input("username");
        $password = input("password");
        $code = input("code");
        
        if(empty($username)){
            $this->error("请输入登录用户名称");
            return;
        }
        if(empty($password)){
            $this->error("请输入登录密码");
            return;
        }
        if(empty($code)){
            $this->error("请输入验证码");
            return;
        }
        if(!captcha_check($code)){
            $this->error("验证码不正确");
            return;
        };
        $whereArray["LoginName"] = $username;
        $whereArray["PassWorld"] = md5($password);
        $model = Db::table("mc_member")->where($whereArray)->find();
        if($model){
            $login_guid = time();
            //保存用户的guid
            $update_data["LoginGUID"] = $login_guid;
            $result = Db::table("mc_member")->where("ID",$model["ID"])->update($update_data);
            if($result){//修改guid成
                //设置cookie
                $cookie_array = array("NiceName" => $model["NiceName"],"LoginGUID"=>$login_guid);
                cookie("manage_info",$cookie_array);
                $this->redirect(url("platform/index"));
            }else{
                $this->error("修改登陆信息失败，请于管理员联系。");
            }
        
        }else{
            $this->error("账号或密码不正确。");
            return;
        }
        
    }
    
    public function exit_login(){
        cookie("manage_info",null);
        $this->success("退出成功", url("index/index"));
    }
    
}
