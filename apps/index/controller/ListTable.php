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
	    $data = new BaseDataModel();
	    if(!empty($id = $data->get_station_name($list))){
            $where['under_station'] = $id[0];
        }else if(!empty($id = $data->get_district_name($list))){
            $where['district'] = $id[0];
        }
        if(empty($house = DB::name('house_rent_data')->where($where)->select())){
            $where = array('rent_type'=>array('=',$a));
            $this->assign('tishi','找不到结果');
        }else{
            $this->assign('tishi','');
        }
        $res = DB::name('house_rent_data')
            ->where($where)
            ->paginate(5)
            ->each(function($item,$key){
                $data = new BaseDataModel;
                $item['furniture_list'] = $data->get_furniture_name($item['furniture']);

                $item['key_list'] = $data->get_key_data($item['key_word']);
                return $item;
            });

       // echo db('house_rent_data')->getLastSql();
       // print_r($res);
	    $dis = DB::name('location_data')
                ->where(array('parent_id'=>array('=',1),'show_label'=>array('=',1),'location_name'=>array('<>','不限')))
                ->select();
        $under = DB::name('underground_data')
            ->where(array('parent_id'=>array('=',1),'show_label'=>array('=',1),'underground_name'=>array('<>','不限')))
            ->limit(7)
            ->select();
	    $house_type = DB::name('house_type_data')
                ->select();

        $page = $res->render();
        $this->assign('page', $page);
        $this->assign('list',$res);
        $this->assign('dis',$dis);
        $this->assign('h_type',$house_type);
        $this->assign('under',$under);
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
        $where = $this->get_where($_POST['type'],$_POST['s_data']);
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
        $arr['count'] = ceil($s_count/5);
        return $arr;
    }
    public function get_street(){
	    $data = new BaseDataModel();
	    $res = $data->get_street_data($_POST['distr']);
	    return $res;
    }
    public function get_underground(){
        $data = new BaseDataModel();
        $res = $data->get_station_data($_POST['line']);
        return $res;
    }
    public function get_search(){
        $page = $_POST["page"]; //传值页数
        $num = 5;  //每页想要显示的数据条数
        $tiao = ($page-1)*$num;
        $data = new BaseDataModel();
        $table = 'house_rent_data';
        $where = $data->get_house_where($_POST['data'],$_POST['type']);
        $where['rent_type'] = $_POST['type'];
        $s_data =  DB::view($table,'*')
            ->view('location_data',['location_name'],'location_data.l_id=house_rent_data.street')
            ->where($where)
            ->limit($tiao,5)
            ->select();
//        echo db('house_rent_data')->getLastSql();
//return;
        foreach($s_data as &$val){
            $data = new BaseDataModel;
            $val['furniture_list'] = $data->get_furniture_name($val['furniture']);

            $val['key_list'] = $data->get_key_data($val['key_word']);
        }

        $arr['data'] = $s_data;
        $s_count =  DB::view($table,'*')
            ->view('location_data',['location_name'],'location_data.l_id=house_rent_data.street')
            ->where($where)
            ->count();
        $arr['count'] = ceil($s_count/5);
        return $arr;
    }
}