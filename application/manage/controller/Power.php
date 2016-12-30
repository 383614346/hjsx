<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use think\Db;
use app\manage\logic\RoleLogic;
use app\manage\logic\PowerLogic;

class Power extends ManageBase{
	
    
	public function index($isShow = true){
	    $roleLogic = new RoleLogic();
	    $roleSelect = $roleLogic->GetSelectArray();
	    $this->view->RoleSelectArray = $roleSelect;
	    //获取显示的权限信息
	    $powerLogic = new PowerLogic();
	    $showPowerArray = $powerLogic->GetShowPowerTreeArray();
	    $this->assign("ShowPowerArray", $showPowerArray);
	    //获取当前角色的所有权限
	    $serachRole = input("SerachRole",0);
	    if($serachRole == 0){
	        $serachRole = $roleSelect[0]["ID"];
	    }
	    $this->assign("SerachRole", $serachRole);
	    $list = Db::table("mc_power")->where("RoleID", $serachRole)->select();
	    $jsonList = json_encode($list,JSON_UNESCAPED_UNICODE);
	    $this->view->DefaultList = $jsonList;
        if($isShow){
           return $this->fetch();
        }	    
	}
	
	function opt_index(){
		$opt = input("operate");		
		//保存权限信息
		if($opt == "save"){
		    $this->OptSave();
		}else if($opt =="select"){
		    $this->index(false);
		    return $this->fetch("index");
		}else if($opt == "PowerCopy"){//用户拷贝权限
		    $this->_PowerCopy();
		}
	}
	/**
	 * 保存
	 */
	private function OptSave(){
	    $serachRole = input("SerachRole");
	    $chePower = input("chePower/a");
	    //删除以前的权限
	    Db::table("mc_power")->where("RoleID", $serachRole)->delete();
	    //添加权限
	    Db::startTrans();
	    $isSuccess = true;
	    foreach ($chePower as $k => $v){
	        $dataSplit = explode("__", $v);
	        $addData["RoleID"] = $serachRole;
	        $addData["MenuID"] = $dataSplit[0];
	        $addData["Code"] = $dataSplit[1];
	        $result = Db::table("mc_power")->insert($addData);
	        if(!$result){
	            $isSuccess = false;
	            break;
	        }
	    }
	    if($isSuccess){
	        Db::commit();
	        $this->success("保存成功",url("index",array("SerachRole"=>$serachRole)));
	    }else{
	        Db::rollback();
	        $this->error("保存失败",url("index",array("SerachRole"=>$serachRole)));
	    }
	}
	
	/**
	 * 权限拷贝页面
	 */
	public function powercopy($isShow = true){
	    $roleLogic = new RoleLogic();
	    //获取角色选项
	    $roleSelectArray = $roleLogic->GetSelectArray();
	    $this->assign("RoleSelectArray", $roleSelectArray);
	    //设置默认想
	    $SourceRole = input("SourceRole");
	    $TargetRole = input("TargetRole");
	    $this->assign("SourceRole",$SourceRole);
	    $this->assign("TargetRole",$TargetRole);
	    if($isShow){
	        return $this->fetch();
	    }
	}
	/**
	 * 处理拷贝权限方法
	 * @param unknown $sourceRole
	 * @param unknown $targetRole
	 */
	private function _PowerCopy(){
	    $sourceRole = input("SourceRole");
	    $targetRole = input("TargetRole");
	    if($sourceRole == $targetRole){
	        $urlArray["SourceRole"] = $sourceRole;
	        $urlArray["TargetRole"] = $targetRole;
	        $this->error("相同角色不能拷贝",url("PowerCopy", $urlArray));
	    }
	    Db::startTrans();
	    //删除拷贝对象以前的数据
	    Db::table("mc_power")->where("RoleID", $targetRole)->delete();
	    //拷贝权限
	    $list = Db::table("mc_power")->where("RoleID", $sourceRole)->select();
	    $isSuccess = true;
	    foreach($list as $k=>$v){
	        $addData["RoleID"] = $targetRole;
	        $addData["MenuID"] = $v["MenuID"];
	        $addData["Code"] =  $v["Code"];
	        $result = Db::table("mc_power")->insert($addData);
	        if(!$result){
	            $isSuccess = false;
	            break;
	        }
	    }
	    if($isSuccess){
	        Db::commit();
	        $this->success("权限拷贝成功",url("PowerCopy"));
	    }else{
	        Db::rollback();
	        $this->error("权限拷贝失败",url("PowerCopy"));
	    }
	}
}
