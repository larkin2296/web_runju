<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\BaseDataModel;
use think\Ajaxpage;

class ListTable extends Controller
{ 
	public function index($a,$list = ''){
	    $where = $this->get_where($a);
        $res = DB::name('house_rent_data')
            ->where(array('rent_type'=>array('=',$a)))
            ->paginate(5)
            ->each(function($item,$key){
                $data = new BaseDataModel;
                $item['furniture_list'] = $data->get_furniture_name($item['furniture']);

                $item['key_list'] = $data->get_key_data($item['key_word']);
                return $item;
            });
	    $dis = DB::name('location_data')
                ->where(array('parent_id'=>array('=',1),'show_label'=>array('=',1),'location_name'=>array('<>','不限')))
                ->select();
	    $house_type = DB::name('house_type_data')
                ->select();
        $page = $res->render();
        $this->assign('page', $page);
        $this->assign('dis',$dis);
        $this->assign('h_type',$house_type);
        $this->assign('list',$res);
        $this->assign('type',$a);
        return $this->fetch();		
	}
	public function get_where($a,$s_data=''){
        $where = [];
        if($s_data == ''){
            $where['rent_type'] = $a;
        }else{
            $data = json_decode($s_data);
            foreach($data as $name=>$val){
                if($val != -1){
                    if($name == 'price'){
                        if($val == 8000){
                            $where[$name] = array('>=',$val);
                        }else{
                            $where[$name] = array('BETWEEN',[$val,$val+2000]);
                        }
                    }else{
                        $where[$name] = array('=',$val);
                    }
                }
            }
            $where['rent_type'] = $a;
        }
        return $where;
    }
	public function get_data(){
        $page = $_POST["page"]; //传值页数
        $num = 5;  //每页想要显示的数据条数
        $tiao = ($page-1)*$num;
        $where = $this->get_where(1,$_POST['s_data']);
        $s_data =  DB::name('house_rent_data')
                 ->where($where)
                 ->limit($tiao,5)
                 ->select();

        foreach($s_data as &$val){
            $data = new BaseDataModel;
            $val['furniture_list'] = $data->get_furniture_name($val['furniture']);

            $val['key_list'] = $data->get_key_data($val['key_word']);
        }
        $arr['data'] = $s_data;
        $s_count =  DB::name('house_rent_data')
            ->where($where)
            ->count();
        $arr['count'] = ceil($s_count/5);;
        return $arr;
    }
}