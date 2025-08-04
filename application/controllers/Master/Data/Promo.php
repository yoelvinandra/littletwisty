<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promo extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(['model_master_promo']);
	}
	
	public function index(){
		$kodeMenu = $this->input->get('kode');
		$config['KODE'] = $this->model_master_config->getConfig('MPROMO','KODEPROMO');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu, $config);
	}

	public function dataGridPromo() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_promo->dataGridPromo($this->setPaginationGrid(), $this->setFilterGrid());
		echo json_encode($response);
	}
		
	public function simpanPromo(){
		$data     = json_decode($this->input->post('data'));
		
		$response = $this->model_master_promo->updateDiskon($data);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		
		// panggil fungsi untuk log history
		log_history(
			$kode,
			'MASTER CUSTOMER DARI PROMO',
			'ubah',
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'mcustomer',
					'kode'  => 'kodecustomer'
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
	
	public function simpanHarga(){
		$data     = json_decode($this->input->post('data'));
		
		$response = $this->model_master_promo->updateHarga($data);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		
		// panggil fungsi untuk log history
		log_history(
			$kode,
			'HARGA KONSINYASI',
			'ubah',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
}
