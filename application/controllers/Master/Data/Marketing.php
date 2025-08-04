<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marketing extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(['model_master_marketing']);
	}
	
	public function index(){
		$kodeMenu = $this->input->get('kode');
		$config['KODE'] = $this->model_master_config->getConfig('MMARKETING','KODEMARKETING');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu, $config);
	}
	
	public function comboGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_marketing->comboGrid($this->setPaginationGrid());
		echo json_encode($response);
	}

	public function dataGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_marketing->dataGrid($this->setPaginationGrid(), $this->setFilterGrid());
		echo json_encode($response);
	}
		
	public function simpan(){
		$id     = $this->input->post('IDMARKETING','');
		$kode   = $this->input->post('KODEMARKETING');
		$nama   = $this->input->post('NAMAMARKETING');
		$status = $this->input->post('STATUS') ?? 0;
		if ($nama == '') die(json_encode(array('errorMsg' => 'Nama Marketing Tidak Boleh Kosong')));

		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
			$setting = $this->model_master_config->getConfigAll('MMARKETING','KODEMARKETING');
			if($setting->VALUE == "AUTO"){
				//custom filter
				$filter['lokasi'] = $lokasi;
				$filter['tgltrans'] = $tgltrans;
				$kode = autogen($setting,$filter);
			}
			//CEK APAKAH KODE & NAMA SUDAH DIGUNAKAN
			$response = $this->model_master_marketing->cek_valid_kode($kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			
			$response = $this->model_master_marketing->cek_valid_nama($nama);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			$status = 1;
			$edit = false;
		}
		else{
			//jika edit
			//cek apakah nama sudah digunakan
			$response = $this->model_master_marketing->cek_valid_nama($nama,$kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			$edit = true;
		}
		
		$data_values = array (
			'IDPERUSAHAAN'  => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'KODEMARKETING' => $kode,
			'NAMAMARKETING' => $nama,
			'ALAMAT'        => $this->input->post('ALAMAT'),
			'TELP'          => $this->input->post('TELP'),
			'HP'            => $this->input->post('HP'),
			'EMAIL'         => $this->input->post('EMAIL'),
			'CATATAN'       => $this->input->post('CATATAN'),
			'USERENTRY'     => $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLENTRY'      => date("Y-m-d"),
			'STATUS'        => $status
		);

		$response = $this->model_master_marketing->simpan($id,$data_values,$edit);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		
		// panggil fungsi untuk log history
		log_history(
			$kode,
			'MASTER MARKETING',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'mmarketing',
					'kode'  => 'kodemarketing'
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
	
	function hapus(){
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');

		$exe = $this->model_master_marketing->hapus($id);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		echo json_encode(array('success' => true));

		log_history(
			$kode,
			'MASTER MARKETING',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}
	
		//TAMBAHAN
	function getFormLink($kodeMenu)
	{
		$data['mode']				= $this->input->post('mode');
		$data['view']   			= $this->input->post('view');
		$idtrans 					= $this->input->post('data');
		
		if($data['mode'] == "ubah") $data["row"] = $this->model_master_marketing->getDataHeader($idtrans);
		
		$data['KODE'] 				= $this->model_master_config->getConfig('MMARKETING','KODEMARKETING');
		$data['kodemenu'] 			= $kodeMenu;
		
		$this->load->view("/forms/v_form_".strtolower($data['view']),$data);
	}
}
