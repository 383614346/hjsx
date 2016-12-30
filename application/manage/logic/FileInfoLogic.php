<?php
namespace app\manage\logic;

use think\Db;
use think\Cache;

class FileInfoLogic
{

    /**
     * 通过传入文件扩展名获取文件图片icon
     *
     * @param string $ext            
     */
    public function GetFileIcon($ext)
    {
        $path = "/static/common/images/icon/file/";
        switch ($ext) {
            case "rar":
                $path .= "rar.png";
                break;
            case "mp4":
                $path .= "mp4.png";
                break;
            case "mp3":
                $path .= "mp3.png";
                break;
            case "flv":
                $path .= "flv.png";
                break;
            case "txt":
                $path .= "txt.png";
                break;
            case "doc":
                $path .= "word.png";
            case "docx":
                $path .= "word.png";
                break;
            case "xls":
                $path .= "excel.png";
            case "xlsx":
                $path .= "excel.png";
                break;
            case "zip":
                $path .= "zip.png";
                break;
            case "ppt":
                $path .= "powerpoint.png";
                break;
            case "pptx":
                $path .= "powerpoint.png";
                break;
        }
        return $path;
    }
}