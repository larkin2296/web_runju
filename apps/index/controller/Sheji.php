<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\BaseDataModel;

class SheJi extends Controller
{
    public function index(){
        return $this->fetch();
    }
}