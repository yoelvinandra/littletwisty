<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Harga extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(['model_master_harga']);
	}
	
	public function index(){
		$kodeMenu = $this->input->get('kode');
		$config['KODE'] = $this->model_master_config->getConfig('MHARGA','KODEHARGA');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu, $config);
	}
	
	public function dataGrid($idcustomer="0") {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_harga->dataGrid($idcustomer);
		
		echo json_encode($response);
	}
	
	public function dataGridHeader($idcustomer="0") {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_harga->dataGridHeader($idcustomer);
		
		echo json_encode($response);
	}
	
	public function dataGridNone() {
		$this->output->set_content_type('application/json');
		
		echo json_encode(array('rows' => []));
	}
	
	public function simpanHarga(){
		$data     = json_decode($this->input->post('data_detail'));
		
		$allcustomer = $this->input->post('allcustomer')??"false";
		$varian = $this->input->post('varian')??"false";

		$response = $this->model_master_harga->simpanHarga($data,$allcustomer,$varian);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		
		// panggil fungsi untuk log history
		log_history(
			$kode,
			'SIMPAN HARGA',
			'ubah',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
}
