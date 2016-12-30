<?php
namespace app\mobile\controller;

use think\Controller;

class Wx extends Controller
{
    public function qrcode()
    {
        
        return $this->fetch();
    }
}
