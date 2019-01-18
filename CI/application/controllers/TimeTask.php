<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TimeTask extends CI_Controller {
	public function __construct(){
		parent::__construct();
    }
    //30分钟
    public function oneHour(){
        $this->jd();
    }
    //1分钟
    public function oneMin(){
        $this->getJdGoodsInfo();
    }
    //京东价格抓取
    public function jd(){
        
        $sql="SELECT * from jd_list";
        $list=$this->db->query($sql)->result_array();
        // var_dump($list);
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
                if($v1['price']!=$v['p']){
                    $this->sendToDingding("价格变动:".$v1['name']." {$v1['price']}->{$v['p']}");
                    $this->db->where("id",$v1['id'])->update("jd_list",["price"=>$v['p']]);
                }
                
            }   
        }
    }
    private function sendToDingding($content){
        $url="https://oapi.dingtalk.com/robot/send?access_token=57c855f9f587fb2f0ad266d3aa4aad20e539ff143e9a215858b4cb20e5783e08";
        $data=array(
            "msgtype"=>"text",
            "text"=>array(
                "content"=>$content
            ),
            // "at"=>array(
            //     "atMobiles"=>array("18816794028","18218477373","13048105545"),
            //     "isAtAll"=>false
            // )
        );
        $res=json_decode(network_postforjson($url,$data),true);
        return $res['errcode']==0?true:false;
    }
    public function getJdGoodsInfo(){
        // $start=100001;
        for($i=0;$i<20;$i++){
            $sql="SELECT * from jd_index where id=1";
            $index=$this->db->query($sql)->result_array();
            $index=$index[0]['index'];
            $target=[];
            for($a=0;$a<100;$a++){
                $target[]=$index+$a;
            }
            $target_indexs=implode(",",$target);
            $url="https://yx.3.cn/service/info.action?ids=$target_indexs&callback=";
            $detail=network_get($url);
            $detail= mb_convert_encoding($detail, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
            // die();
            if(!empty($detail)){
                $detail=json_decode($detail,true);
                // var_dump($detail);
                // die();
                $insert_data=[];
                foreach ($detail as $k => $v) {
                    $v['jd_id']=$k;
                    $insert_data[]=$v;
                }
                $this->db->insert_batch("jd_goods_info",$insert_data);
            }
            $index+=100;
            $this->db->where("id",1)->update("jd_index",["index"=>$index]);
        }
    }
}