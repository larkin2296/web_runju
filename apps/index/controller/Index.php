<?php
namespace app\index\controller;

    use think\Controller;
    use think\Db;

class index extends Controller
{
    public function index(){
	    $where = array('rent_type'=>array('=',1),'house_level'=>array('=',1));
        $res = DB::name('house_rent_data')
                ->where($where)
                ->select();
        foreach($res as &$val){
            $arr = array();
            for($i=1;$i<11;$i++){
                $arr[] = $val['pic_'.$i];
            }
            $val['pic_list'] = $arr;
        }
        $whe = array('rent_type'=>array('=',0),'house_level'=>array('=',1));
        $res_1 = DB::name('house_rent_data')
            ->where($whe)
            ->select();
        foreach($res_1 as &$value){
            $arr = array();
            for($i=1;$i<11;$i++){
                $arr[] = $value['pic_'.$i];
            }
            $value['pic_list'] = $arr;
        }

        $this->assign('house',$res);
        $this->assign('hou',$res_1);
        return $this->fetch();		
	}
	public function left(){
		return $this->fetch();			
	}
}
