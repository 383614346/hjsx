<?php
namespace app\mobile\controller;

use think\Controller;

class PersonalCenter extends Controller
{
    public function index()
    {
        
        return $this->fetch();
    }
}
