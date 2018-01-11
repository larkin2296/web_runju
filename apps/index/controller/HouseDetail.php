<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class HouseDetail extends Controller
{ 
	public function index(){
        return $this->fetch();		
	}
}