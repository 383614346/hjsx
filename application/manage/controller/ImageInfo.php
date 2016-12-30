<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use think\Db;
use app\manage\logic\ImageTypeLogic;
class ImageInfo extends ManageBase {
    
    public function ImageSelectTest(){
        return $this->fetch();
    }
    /**
     * ajax获取图片列表
     */
    public function AjaxGetMoreImageList(){
        $ids = trim(input("ids"));
        if(empty($ids)){
            $jsonData["Status"] = 2;
            $jsonData["Msg"] = "请输入您要查询的图片编号";
            return json($jsonData,JSON_UNESCAPED_UNICODE);
        }
        $imageList = Db::table("mc_image_info")->where("ID","in", $ids)->select();
        $imageList = $this->ImageListSort($imageList, $ids);
        $jsonData["Status"] = 1; 
        $jsonData["Data"] = $imageList;
        return json($jsonData);
        //json($jsonData, JSON_UNESCAPED_UNICODE);
    }
    /**
     * 将图片进行排序
     * @param unknown $imageList
     * @param unknown $idsStr
     */
    public function ImageListSort($imageList, $idsStr){
        $idsArray = explode(",", $idsStr);
        $newImageList = array();
        foreach ($idsArray as $k=>$v){
            foreach ($imageList as $ck=>$cv){
                if($cv["ID"] == $v){
                    $newImageList[] = $cv;
                    break;
                }
            }
        }
        return $newImageList;
    }
    
    /**
     * 多图片选择窗口
     */
    public function MoreImageSelectBox(){
        $ImageTypeLogic = new ImageTypeLogic();
        $fileTypeList = $ImageTypeLogic->GetAllImageTypeTree();
        $this->view->ImageTypeList = $fileTypeList;
        //获取容器编号
        $BoxElementID = input("BoxElementID");
        $ValueElementID = input("ValueElementID");
        $this->view->BoxElementID=$BoxElementID;
        $this->view->ValueElementID = $ValueElementID;
        return $this->fetch();
    }
    /**
     * 单图片选择窗口
     */
    public function OneImageSelectBox(){
        $ImageTypeLogic = new ImageTypeLogic();
        $fileTypeList = $ImageTypeLogic->GetAllImageTypeTree();
        $this->view->ImageTypeList = $fileTypeList;
        //获取容器编号
        $BoxElementID = input("BoxElementID");
        $ValueElementID = input("ValueElementID");
        $this->view->BoxElementID = $BoxElementID;
        $this->view->ValueElementID = $ValueElementID;
        return $this->fetch();
    }
    
    /**
     * ajax获取文件的相关信息
     */
    public function AjaxGetImageInfoList(){
        $searchImageType = input("SearchImageType",-1);
        $searchTitle = trim(input("SearchTitle"));
        $currentPage = input("CurrentPage",1);
        $PageSize = input("PageSize",10);
        $where = [];
        if($searchImageType != -1){
            $where["ImageType"] = ["=",$searchImageType];
        }
        if(!empty($searchTitle)){
            $where["Title"] = ["like","%". $searchTitle ."%"];
        }
        $count = Db::table("mc_image_info")
        ->where($where)
        ->count();
        $list = Db::table("mc_image_info")
        ->where($where)
        ->page($currentPage.','.$PageSize)
        ->order("UpdateTime desc")
        ->field("ID,ImagePath,Title")
        ->select();
        $jsonData["Status"] = 1;
        $jsonData["Count"] = $count;
        $jsonData["CurrentPage"] = $currentPage;
        $jsonData["PageSize"] = $PageSize;
        $jsonData["Data"] = $list;
        return json($jsonData);
    }
    public function index($isShow = true){
        $this->IsPower("FileInfo",array("select"),true);
        $ImageTypeLogic = new ImageTypeLogic();
        $dropDownList = $ImageTypeLogic->GetAllImageTypeTree();
        $this->view->ImageTypeDropDownList = $dropDownList;
        $serachFileType = input("SerachImageType", -1);
        $serachTitle = input("SerachTitle");
        $where=[];
        $pageArray = array();
        if($serachFileType != -1){
            $where["ImageType"] = ['=',$serachFileType];
            $pageArray["ImageType"] = $serachFileType;
        }
        if(!empty($serachTitle)){
            $where["Title"] = ['like',"%". $serachTitle ."%"];
            $pageArray["Title"] = $serachTitle;
        }
        $list = Db::table("mc_image_info")
        ->order("UpdateTime desc")
        ->where($where)
        ->paginate(25);
        $this->view->Serach = $pageArray;
        $this->assign("List", $list);
        $this->view->page = $list->render();
        if($isShow){
           return $this->fetch();
        }
    }
    

        function opt_index(){
            $opt = input("operate");
            //删除
            if($opt == "delete"){
                $this->delete();
            }else if($opt == "update"){
                $this->UpdateImg();
            }else if($opt == "serach"){
                $this->index(false);
               return $this->fetch("index");
            }
            
        }
        /**
         * 修改选择的图片
         */
        private function UpdateImg(){
            $id = input("ID/a");
            if(count($id) == 0){
                $this->error("请选择您要修改的图片");
            }
            $idStr = implode(",", $id);
            $this->redirect("FileUpload",array("ids" => $idStr));
        }
    
        /**
         * 删除分类
         */
        private function delete(){
            $this->IsPower("image_info",array("delete"),true);
            $id = input("ID/a");
            if(empty($id) || !is_array($id)){
                $this->error("请选择要删除的项目",url("index"));
            }
            $ids = implode(',',$id);
            $imgList = Db::table("mc_image_info")->where("ID","in",$ids)
            ->field("ImagePath")->select();
            //删除所有的图片
            foreach($imgList as $k=>$v){
                unlink(".". $v["ImagePath"]);                
            }
            $result = Db::table("mc_image_info")->where("ID","in",$ids)->delete();
            if($result){
                $this->success("删除成功。",url("index"));
            }else{
                $this->error("删除失败");
            }
        }
    
    public function FileUpload($isShow = true){
        $this->IsPower("FileInfo",array("update"),true);
        $ImageTypeLogic = new ImageTypeLogic();
        $ids = input("ids");
        $fileTypeList = $ImageTypeLogic->GetAllImageTypeTree();
        $this->view->FileTypeList = $fileTypeList;
        $fileType = input("FileType",-1);
        if($fileType == -1){
            $this->view->FileType = $fileTypeList[0]["ID"];
        }else{
            $this->view->FileType =$fileType;
        }
        if(!empty($ids)){
            $list = Db::table("mc_image_info")->where("ID","in",$ids)->select();
            $this->view->UpdateImgList = $list;
            $this->log($list);
        }
        if($isShow){
            return $this->fetch();
        }
        
    }
    /**
     * 修改上传文件的信息
     */
    public function FileUploadUpdate(){
        $ID = input("ID/a");
        $Title = input("Title/a");
        $Content = input("Content/a");
        //要修改的数量
        $updateNumber = count($ID);
        //修改成功的数量
        $updateSuccessNumber = 0;
        $iCount = count($ID);
        for($i=0;$i < $iCount;$i++){
            $update["Title"] = $Title[$i];
            $update["Content"] = $Content[$i];
            $update["UpdateTime"] = time();
            $result = Db::table("mc_image_info")->where("ID", $ID[$i])->update($update);
            if($result){
                $updateSuccessNumber++;
            }
        }
        if($updateNumber == $updateSuccessNumber){
            $this->success("更新成功");
        }else if($updateSuccessNumber == 0){
            $this->error("更新失败");
        }else if($updateNumber > $updateSuccessNumber){
            $this->error("部分更新失败");
        }
    }
    
    /**
     * 处理文件
     */
    public function FileUploadOpt(){
        $this->IsPower("image_info",array("add"),true);
        $fileTypeID = input("FileType");
        //获取表单上传文件
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $savePath = ROOT_PATH . 'public' . DS . 'static'. DS .'uploads'. DS .'images';
        $info = $file->move($savePath,true,false);
        if($info){
            $savePath =  '/static/uploads/images/'. $info->getSaveName();
            $savePath = str_replace('\\', '/', $savePath);
            $addData["ImagePath"] = $savePath;
            $addData["Title"] = $info->getFileName();
            $addData["ImageType"] = $fileTypeID;
            $addData["AddTime"] = time();
            $addData["UpdateTime"] = time();
            $result = Db::table("mc_image_info")->insertGetId($addData);
            if(!$result){
                $jsonData["status"] = 2;
                $jsonData["msg"] = "保存图片到数据库失败";
                return json($jsonData,JSON_UNESCAPED_UNICODE);
            }
            $addData["ID"] =  $result;
        }else{
            $jsonData["status"] = 2;
            $jsonData["msg"] = $file->getError();
            return json($jsonData,JSON_UNESCAPED_UNICODE);
        }
        $jsonData["status"] = 1;
        $jsonData["msg"] = "上传成功";
        $jsonData["data"] = $this->ReturnSuccessFileInfo($addData);
        return json($jsonData,JSON_UNESCAPED_UNICODE);
    }
    
   
    
    
    /**
     * 返回上传成功过后显示的图片信息
     */
    private function ReturnSuccessFileInfo($addData){
        $data["ID"] = $addData["ID"];
        $data["ImagePath"] = $addData["ImagePath"];
        $data["Title"] = $addData["Title"];
        return $data;
    }
    
    /**
     * 传入文件类型字符串，获取文件所属类型
     * @param unknown $typeStr
     */
    private function GetFileType($typeStr){
        switch ($typeStr){
            case "image/jpeg":
                return "jpg";
                break;
            case "image/png":
                return "png";
                break;
            default:
                $this->log("获取图片类型失败：". $typeStr);
                return "";
                break ;
        }
    } 
}