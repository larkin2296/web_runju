<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\BaseDataModel;

class ListTable extends Controller
{ 
	public function index($a){
	    $res = DB::name('house_rent_data')
            ->where(array('rent_type'=>array('=',$a)))
            ->paginate(5)
            ->each(function($item,$key){
                $data = new BaseDataModel;
                $item['furniture_list'] = $data->get_furniture_name($item['furniture']);

                $item['key_list'] = $data->get_key_data($item['key_word']);
                return $item;
            });
        $page = $res->render();
        $this->assign('page', $page);
        $this->assign('list',$res);
        return $this->fetch();		
	}
}