<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gaode extends CI_Controller {
	public function __construct(){
		parent::__construct();
		xhprof_enable(XHPROF_FLAGS_MEMORY);
	}
	public function index(){
		$sql="SELECT * from gaode_test";
        $list=$this->db->query($sql)->result_array();
        $sum=0;
        foreach ($list as $k => $v) {
            if($k==count($list)-1)break;
            $returndata=$this->getgaodeTraffic("{$v['lnglat']}","{$list[$k+1]['lnglat']}","");
            $res=$returndata['route']['paths'][0]['distance'];
            $this->db->where("id",$v['id'])->update("gaode_test",array("dis"=>$res));
            $sum+=$res;
            $this->db->where("id",$v['id']+1)->update("gaode_test",array("all_dis"=>$sum));
        }
		$xhprof_data = xhprof_disable();
		$xhprof_root = "/home/wwwroot/xhprof/";//这里填写的就是你的xhprof的路径

		include_once $xhprof_root."/xhprof_lib/utils/xhprof_lib.php";
		include_once $xhprof_root."/xhprof_lib/utils/xhprof_runs.php";

        $xhprof_runs = new XHprofRuns_Default();
		$run_id = $xhprof_runs->save_run($xhprof_data, "test");
		// display raw xhprof data for the profiler run
		// echo "http://{$_SERVER['SERVER_ADDR']}/xhprof/xhprof_html/index.php?run=$run_id&source=test\n";
		// var_dump($xhprof_data);
    }
    /**
     * 获取高德路况
     * @author caiyupeng 2018-03-16T14:40:45+0800
     * @param  [type] $startlonlat [description]
     * @param  [type] $endlonlat   [description]
     * @param  [type] $waypoints   [description]
     * @return [type]              [description]
     */
    public function getgaodeTraffic($startlonlat,$endlonlat,$waypoints){
		$url="http://restapi.amap.com/v3/direction/driving?origin=$startlonlat&destination=$endlonlat&key=e7903645ff35a21fcb5c5659fdeda288&strategy=19&waypoints=$waypoints";
		// echo $url;
        $statusdata=json_decode(network_get($url,array()),true);
        return $statusdata;
        // p($statusdata);
        // $statusdata=$statusdata['route']['paths'][0];
        // $steps=$statusdata['steps'];
        // return $steps;
    }
}
