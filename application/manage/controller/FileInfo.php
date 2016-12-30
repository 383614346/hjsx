<?php
namespace app\manage\controller;
use think\Controller;
use think\View;
use think\Db;
use app\manage\logic\FileTypeLogic;
use app\manage\logic\FileInfoLogic;
class FileInfo extends ManageBase {
    
    public function FileSelectTest(){
        return $this->fetch();
    }
    
    /**
     * 单文件选择窗口
     */
    public function OneFileSelectBox(){
        $fileTypeLogic = new FileTypeLogic();
        $fileTypeList = $fileTypeLogic->GetAllFileTypeTree();
        $this->view->FileTypeList = $fileTypeList;
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
    public function AjaxGetFileInfoList(){
        $this->IsPower("file_info",array("update"),true);
        $fileInfoLogic = new FileInfoLogic();
        $fileType = input("FileType",-1);
        $fileTitle = trim(input("FileTitle"));
        $currentPage = input("CurrentPage",1);
        $PageSize = input("PageSize",10);
        $where = [];
        if($fileType != -1){
            $where["FileType"] = ["=",$fileType];
        }
        if(!empty($fileTitle)){
            $where["Title"] = ["like","%". $fileType ."%"];
        }
        $count = Db::table("mc_file_info")
        ->where($where)
        ->count();
        $list = Db::table("mc_file_info")
        ->where($where)
        ->page($currentPage.','.$PageSize)
        ->order("UpdateTime desc")
        ->field("ID,Path,Title,FileExt")
        ->select();
        foreach ($list as &$v){
            $v["ShowPath"] = $fileInfoLogic->GetFileIcon($v["FileExt"]);
        }
        $jsonData["Status"] = 1;
        $jsonData["Count"] = $count;
        $jsonData["CurrentPage"] = $currentPage;
        $jsonData["PageSize"] = $PageSize;
        $jsonData["Data"] = $list;
        return json($jsonData,JSON_UNESCAPED_UNICODE);
    }
    public function index($isShow = true){
        $this->IsPower("FileInfo",array("select"),true);
        $fileTypeLogic = new FileTypeLogic();
        $dropDownList = $fileTypeLogic->GetAllFileTypeTree();
        $this->view->FileTypeDropDownList = $dropDownList;
        $serachFileType = input("SerachFileType", -1);
        $serachTitle = input("SerachTitle");
        
        $where = "1=1";
        $pageArray = array();
        if($serachFileType != -1){
            $where["FileType"] = ['=',$serachFileType];
            $pageArray["SerachFileType"] = $serachFileType;
        }
        if(!empty($serachTitle)){
            $where["Title"] = ['like',"%". $serachTitle ."%"];
            $pageArray["SerachTitle"] = $serachTitle;
        }
        $pageList = Db::table("mc_file_info")
        ->order("UpdateTime desc")
        ->where($where)
        ->paginate(25);
        $fileInfoLogic = new FileInfoLogic();
        
        $list = $pageList->toArray()["data"];
        foreach ($list as &$v){
            $v["ShowPath"] = $fileInfoLogic->GetFileIcon($v["FileExt"]);
        }
        $this->assign("List", $list);
        $this->view->page = $pageList->render();
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
                $this->error("请选择您要修改的文件");
            }
            $idStr = implode(",", $id);
            $this->redirect(url("ShowEditFileInfo",array("ids" => $idStr)));
        }
    
        /**
         * 删除分类
         */
        private function delete(){
            $this->IsPower("FileInfo",array("delete"),true);
            $id = input("ID/a");
            if(empty($id) || !is_array($id)){
                $this->error("请选择要删除的项目",url("index"));
            }
            $ids = implode(',',$id);
            $imgList = Db::name("file_info")->where("ID","in",$ids)
            ->field("Path")->select();
            //删除所有的图片
            foreach($imgList as $k=>$v){
                if(file_exists(".". $v["Path"])){
                    unlink(".". $v["Path"]);
                }
            }
            $result = Db::name("file_info")->where("ID","in",$ids)->delete();
            if($result){
                $this->success("删除成功。",url("index"));
            }else{
                $this->error("删除失败");
            }
        }
    
    public function ShowEditFileInfo(){
        $this->IsPower("file_info",array("update"),true);
        $fileInfoLogic = new FileInfoLogic();
        $fileTypeLogic = new FileTypeLogic();
        $ids = input("ids");
        $fileTypeList = $fileTypeLogic->GetAllFileTypeTree();
        $this->view->FileTypeList = $fileTypeList;
        $fileType = input("FileType",-1);
        if(!empty($ids)){
            $list = Db::name("file_info")->where("ID","in",$ids)->select();
            foreach ($list as &$v){
                $v["ShowPath"] = $fileInfoLogic->GetFileIcon($v["FileExt"]);
            }
            $this->view->UpdateImgList = $list;
            if(count($list) > 0){
                $this->view->FileType = $list[0]["FileType"];
            }
        }
        return $this->fetch();
    }
    /**
     * 修改编辑的文件信息
     */
    public function UpdateFileUpload(){
        $ID = input("ID/a");
        $Title = input("Title/a");
        $Content = input("Content/a");
        $fileType = input('FileType');
        //要修改的数量
        $updateNumber = count($ID);
        //修改成功的数量
        $updateSuccessNumber = 0;
        $iCount = count($ID);
        for($i=0;$i < $iCount;$i++){
            $update["Title"] = $Title[$i];
            $update["Content"] = $Content[$i];
            $update["UpdateTime"] = time();
            $update["FileType"] = $fileType;
            $result = Db::name("file_info")->where("ID", $ID[$i])->update($update);
            if($result){
                $updateSuccessNumber++;
            }
        }
        $idStr = implode(",", $ID);
        if($updateNumber == $updateSuccessNumber){
            $this->success("更新成功",url("ShowEditFileInfo",array("ids" => $idStr)));
        }else if($updateSuccessNumber == 0){
            $this->error("更新失败",url("ShowEditFileInfo",array("ids" => $idStr)));
        }else if($updateNumber > $updateSuccessNumber){
            $this->error("部分更新失败",url("ShowEditFileInfo",array("ids" => $idStr)));
        }
    }
    
    /**
     * 修改上传文件的信息
     */
    public function EditFileUpload(){
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
            $result = Db::name("file_info")->where("ID", $ID[$i])->update($update);
            if($result){
                $updateSuccessNumber++;
            }
        }
        $idStr = implode(",", $ID);
        if($updateNumber == $updateSuccessNumber){
            $this->success("更新成功",url("FileUpload",array("ids" => $idStr)));
        }else if($updateSuccessNumber == 0){
            $this->error("更新失败",url("FileUpload",array("ids" => $idStr)));
        }else if($updateNumber > $updateSuccessNumber){
            $this->error("部分更新失败",url("FileUpload",array("ids" => $idStr)));
        }
    }
    public function FileUpload(){
        $this->IsPower("file_info",array("add"),true);
//         $fileInfoLogic = new FileInfoLogic();
        $fileTypeLogic = new FileTypeLogic();
//         $ids = input("ids");
        $fileTypeList = $fileTypeLogic->GetAllFileTypeTree();
        $this->view->FileTypeList = $fileTypeList;
        $fileType = input("FileType",-1);
        if($fileType == -1){
            $fileType = $fileTypeList[0]["ID"];
        }
        $this->view->FileType = $fileType;
        return $this->fetch();
    }
    /**
     * 处理文件
     */
    public function FileUploadOpt(){
        $this->IsPower("file_info",array("add"),true);
        $fileType = input("FileType");
        //获取表单上传文件
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $savePath = ROOT_PATH . 'public' . DS . 'static'. DS .'uploads'. DS .'files';
        $info = $file->move($savePath,true,false);
        if($info){
            $savePath =  '/static/uploads/files/'. $info->getSaveName();
            $savePath = str_replace('\\', '/', $savePath);
            $addData["Path"] = $savePath;
            $addData["Title"] = $info->getFileName();
            $addData["FileType"] = $fileType;
            $addData["FileExt"] = strtolower($info->getExtension());
            $addData["AddTime"] = time();
            $addData["UpdateTime"] = time();
            $result = Db::name("file_info")->insertGetId($addData);
            if(!$result){
                $jsonData["status"] = 2;
                $jsonData["msg"] = "保存文件到系统失败";
                return json($jsonData);
            }
            $addData["ID"] = $result;
        }else{
            $jsonData["status"] = 2;
            $jsonData["msg"] = $file->getError();
            return json($jsonData);
        }
        $jsonData["status"] = 1;
        $jsonData["msg"] = "上传成功";
        $jsonData["data"] = $this->ReturnSuccessFileInfo($addData);
        return json($jsonData);
    }
    
    /**
     * 返回上传成功过后显示的图片信息
     */
    private function ReturnSuccessFileInfo($addData){
        $fileInfoLogic = new FileInfoLogic();
        $data["ID"] = $addData["ID"];
        $data["Path"] = $fileInfoLogic->GetFileIcon($addData["FileExt"]);
        $data["Title"] = $addData["Title"];
        $data["FileExt"] = $addData["FileExt"];
        return $data;
    }
    
    
    
}