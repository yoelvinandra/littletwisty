<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Currency extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(['model_master_currency']);
	}
		
	public function index(){
		$kodeMenu = $this->input->get('kode');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu);
	}
	
	public function comboGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_currency->comboGrid($this->setPaginationGrid());
		echo json_encode($response);
	}

	public function dataGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_currency->dataGrid($this->setPaginationGrid(), $this->setFilterGrid());
		echo json_encode($response);
	}

	public function simpan(){
		$id     = $this->input->post('IDCURRENCY');
		$kode   = $this->input->post('KODECURRENCY');
		$nama   = $this->input->post('NAMACURRENCY');
		$status = $this->input->post('STATUS') ?? 0;
		if ($nama == '') die(json_encode(array('errorMsg' => 'Nama Currency Tidak Boleh Kosong')));

		if ($kode=='') {
			//jika insert baru
			$kode = get_max_urutan('currency', 'currency', "", 2);
			
			//CEK APAKAH KODE & NAMA SUDAH DIGUNAKAN
			$response = $this->model_master_currency->cek_valid_kode($kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			
			$response = $this->model_master_currency->cek_valid_nama($nama);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
		} else {
			//jika edit
			//cek apakah nama sudah digunakan
			$response = $this->model_master_currency->cek_valid_nama($nama,$kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
		}

		if ($this->input->post('mode') == 'tambah') {
			$status = 1;
			$edit = false;
		} else {
			$edit = true;
		}
		
		$data_values = array (
			'IDPERUSAHAAN' => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'KODECURRENCY' => $kode,
			'NAMACURRENCY' => $nama,
			'SIMBOL'       => $this->input->post('SIMBOL'),
			'USERENTRY'    => $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLENTRY'     => date("Y-m-d"),
			'STATUS'       => $status
		);

		$response = $this->model_master_currency->simpan($id,$data_values,$edit);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		
		// panggil fungsi untuk log history
		log_history(
			$kode,
			'MASTER SUPPLIER',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'msupplier',
					'kode'  => 'kodesupplier'
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
	
	function hapus(){
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');

		$exe = $this->model_master_currency->hapus($id);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }

		echo json_encode(array('success' => true));

		log_history(
			$kode,
			'MASTER CURRENCY',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}
	
	function rate(){
		$tgl        = $this->input->post('tanggal') ?? date('Y.m.d');
		$idcurrency = $this->input->post('idcurrency');
		
		$rs = $this->model_master_currency->rate($tgl,$idcurrency);

		echo json_encode(array('success' => true, 'kurs' => $rs->KURS ?? 0));
	}

	function all_rate(){
		$tgl        = $this->input->post('tanggal') ?? date('Y.m.d');
		$result 	= $this->model_master_currency->all_rate($tgl);

		echo json_encode(array('success' => true,'data_detail' => $result));
	}
}
