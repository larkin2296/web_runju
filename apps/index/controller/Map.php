<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\BaseDataModel;

class Map extends Controller
{
    public function index(){
        $where = array('parent_id'=>array('=','1'),'show_label'=>array('=','1'),'location_name'=>array('<>','不限'));
        $a = DB::name('location_data')
            ->where($where)
            ->select();
        $num = 0;
        foreach($a as $key=>$value){
            $new[$key]['title'] = $value['location_name'];
            $num += DB::name('house_rent_data')
                ->where(array('district'=>array('=',$value['l_id']),'house_status'=>array('=',3)))
                ->count();
            $cou = DB::name('house_rent_data')
                    ->where(array('district'=>array('=',$value['l_id']),'house_status'=>array('=',3)))
                    ->count();
            $new[$key]['num'] = $cou;
        }

        $this->assign('district',json_encode($new));
        $this->assign('house_num',$num);
        return $this->fetch();
    }
    public function get_street(){
        $a = DB::name('location_data')
            ->where('location_name','=',$_POST['loc_name'])
            ->column('l_id');
        $result = DB::name('house_rent_data')
                ->where(array('district'=>array('=',$a[0]),'house_status'=>array('=',3)))
                ->column('address');
        $result = array_unique($result);
        $i = 0;
        foreach($result as $val){
            $res = DB::name('house_rent_data')
                ->where(array('address'=>array('=',$val),'house_status'=>array('=',3)))
                ->count();
            $arr[$i]['address'] = $val;
            $arr[$i]['num'] = $res;
            $i++;
        }
       return json_encode($arr);
    }
    public function get_list(){
        $a = DB::name('house_rent_data')
                ->where('address','=',$_POST['loc_name'])
                ->select();
        $model = new BaseDataModel;
        foreach($a as &$value){
            $key = explode(',',$value['key_word']);
            $key_list = $model->get_key_data($value['key_word']);
            $value['key_word_list'] = $key_list;
        }
        return $a;

    }
    public function get_address(){
            $res = DB::name('house_rent_data')
                ->where(array('address'=>array('=',$_POST['loc_name']),'house_status'=>array('=',3)))
                ->count();
            $a = DB::name('house_rent_data')
                ->where('address','=',$_POST['loc_name'])
                ->select();
            $model = new BaseDataModel;
            foreach($a as &$value){
                $key_list = $model->get_key_data($value['key_word']);
                $location_name = $model->get_area_name($value['district']);
                $value['key_word_list'] = $key_list;
                $value['location_name'] = $location_name;
            }
            $arr['overlay']['address'] = $_POST['loc_name'];
            $arr['overlay']['num'] = $res;
            $arr['list'] = $a;
        return json_encode($arr);
    }
}
