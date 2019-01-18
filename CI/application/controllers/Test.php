<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use voku\helper\HtmlDomParser;
class Test extends CI_Controller {
	public function __construct(){
		parent::__construct();
    }
    public function index(){
       
        $this->test1();
        $this->test2();
    }
    private function test1(){
        
        $base=$this->config->item('threeScreenApiBaseUrl');
        $url="http://".$base."/api/v1/vehicle/getvehiclelist";
        echo network_get($url);
    }
    private function test2(){
        echo 2;
    }
    public function jd(){
        $sql="SELECT * from js_list";
        $list=$this->db->query($sql)->result_array();
        var_dump($list);
        foreach ($list as $k1 => $v1) {
            $url="https://pe.3.cn/prices/mgets?source=wxsq&skuids={$v1['jd_id']}&callback=";
            $detail=network_get($url);
            $detail=json_decode(substr($detail,1,-3),true);
            foreach ($detail as $k => $v) {
                $data=$v;
                $data['jd_id']=$v['id'];
                $data['date_day']=date("Y-m-d");
                unset($data['id']);
                $this->db->insert("jd",$data);
            }   
        }
        
    }
}