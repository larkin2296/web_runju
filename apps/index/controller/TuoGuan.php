<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\BaseDataModel;

class TuoGuan extends Controller
{
    public function index(){
        return $this->fetch();
    }
    public function save_data(){
        $data = new BaseDataModel;
        foreach($_POST['data'] as $val){
            $arr[$val['name']] = $val['value'];
        }
        $res = $data->save_trust_msg($arr,$_POST['type']);
        return $res;
    }
}