<?php
namespace app\mobile\controller;

use think\Controller;

class MyOrder extends Controller
{
    public function order_list()
    {
        
        return $this->fetch();
    }
}
