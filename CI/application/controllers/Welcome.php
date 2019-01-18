<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct(){
		parent::__construct();
		xhprof_enable();
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
	public function index1(){
		for($a=0;$a<100;$a++){
			// echo $a;
			base64_encode($a);
			json_encode($a);
		}
		$sql="SELECT * from org_employee";
		$detail=$this->db->query($sql)->result_array();
		$xhprof_data = xhprof_disable();
		$xhprof_root = "/home/wwwroot/xhprof/";//这里填写的就是你的xhprof的路径

		include_once $xhprof_root."/xhprof_lib/utils/xhprof_lib.php";
		include_once $xhprof_root."/xhprof_lib/utils/xhprof_runs.php";

		$xhprof_runs = new XHprofRuns_Default();
		$run_id = $xhprof_runs->save_run($xhprof_data, "test");
		// display raw xhprof data for the profiler run
		echo "http://{$_SERVER['SERVER_ADDR']}/xhprof/xhprof_html/index.php?run=$run_id&source=test\n";
		// var_dump($xhprof_data);
	}
	public function tesst(){
		echo phpinfo();
	}
}
