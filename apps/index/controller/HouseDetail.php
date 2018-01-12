<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\BaseDataModel;

class HouseDetail extends Controller
{ 
	public function index($id){
	    $res = DB::name('house_rent_data')
                ->where('id','=',$id)
                ->select();
        for($i=1;$i<11;$i++){
            $pic[$i] = $res[0]['pic_'.$i];
        }
        $data = new BaseDataModel;
        $fur = $data->get_furniture_name($res[0]['furniture']);

        $key = $data->get_key_data($res[0]['key_word']);
        $this->assign('pic',$pic);
        $this->assign('s_pic',$pic);
        $this->assign('fur',$fur);
        $this->assign('k_word',$key);
        $this->assign('house',$res[0]);
        return $this->fetch();		
	}
}