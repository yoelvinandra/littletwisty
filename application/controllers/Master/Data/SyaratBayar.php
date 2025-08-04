<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Syaratbayar extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(['model_master_syaratbayar']);
	}
	
	public function index(){
		$kodeMenu = $this->input->get('kode');
		$config['KODE'] = $this->model_master_config->getConfig('MSYARATBAYAR','KODESYARATBAYAR');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu,$config);
	}
	
	public function comboGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_syaratbayar->comboGrid($this->setPaginationGrid());
		echo json_encode($response);
	}

	public function dataGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_syaratbayar->dataGrid($this->setPaginationGrid(), $this->setFilterGrid());
		echo json_encode($response);
	}
		
	public function simpan(){
		$id     = $this->input->post('IDSYARATBAYAR');
		$kode   = $this->input->post('KODESYARATBAYAR');
		$nama   = $this->input->post('NAMASYARATBAYAR');
		$status = $this->input->post('STATUS') ?? 0;
		if ($nama == '') die(json_encode(array('errorMsg' => 'Nama Syarat Bayar Tidak Boleh Kosong')));

		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
			$setting = $this->model_master_config->getConfigAll('MSYARATBAYAR','KODESYARATBAYAR');
			if($setting->VALUE == "AUTO"){
				//custom filter
				$filter['lokasi'] = $lokasi;
				$filter['tgltrans'] = $tgltrans;
				$kode = autogen($setting,$filter);
			}
			//CEK APAKAH KODE & NAMA SUDAH DIGUNAKAN
			$response = $this->model_master_syaratbayar->cek_valid_kode($kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			
			$response = $this->model_master_syaratbayar->cek_valid_nama($nama);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			$status = 1;
			$edit = false;
		}
		else{
			//jika edit
			//cek apakah nama sudah digunakan
			$response = $this->model_master_syaratbayar->cek_valid_nama($nama,$kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			$edit = true;
		}
		
		$data_values = array (
			'IDPERUSAHAAN'    => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'KODESYARATBAYAR' => $kode,
			'NAMASYARATBAYAR' => $nama,
			'SELISIH'         => $this->input->post('SELISIH',0),
			'CATATAN'         => $this->input->post('CATATAN'),
			'USERENTRY'       => $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLENTRY'        => date("Y-m-d"),
			'STATUS'          => $status
		);

		$response = $this->model_master_syaratbayar->simpan($id,$data_values,$edit);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		
		// panggil fungsi untuk log history
		log_history(
			$kode,
			'MASTER SYARAT BAYAR',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'msyaratbayar',
					'kode'  => 'kodesyaratbayar'
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
	
	function hapus(){
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');

		$exe = $this->model_master_syaratbayar->hapus($id);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }

		echo json_encode(array('success' => true));

		log_history(
			$kode,
			'MASTER SYARAT BAYAR',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}
}
