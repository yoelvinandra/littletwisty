<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perusahaan extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array("model_master_perusahaan"));
    }
	
	public function index()
	{
		$kodeMenu = $this->input->get('kode');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu);
	}

	public function comboGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_perusahaan->comboGrid($this->setPaginationGrid());
		echo json_encode($response);
	}

	public function dataGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_perusahaan->dataGrid($this->setPaginationGrid(), $this->setFilterGrid());	
		echo json_encode($response);
	}
		
	public function simpan(){
		$id     = $this->input->post('IDPERUSAHAAN');
		$kode   = $this->input->post('KODEPERUSAHAAN');
		$nama   = $this->input->post('NAMAPERUSAHAAN');
		$status = $this->input->post('STATUS') ?? 0;
		if ($nama == '') die(json_encode(array('errorMsg' => 'Nama PERUSAHAAN Tidak Boleh Kosong')));

		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
			$setting = $this->model_master_config->getConfigAll('MPERUSAHAAN','KODEPERUSAHAAN');
			if($setting->VALUE == "AUTO"){
				//custom filter
				$filter['lokasi'] = $lokasi;
				$filter['tgltrans'] = $tgltrans;
				$kode = autogen($setting,$filter);
			}
			//CEK APAKAH KODE & NAMA SUDAH DIGUNAKAN
			$response = $this->model_master_perusahaan->cek_valid_kode($kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			
			$response = $this->model_master_perusahaan->cek_valid_nama($nama);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			$status = 1;
			$edit = false;
		} else {
			//jika edit
			//cek apakah nama sudah digunakan
			$response = $this->model_master_perusahaan->cek_valid_nama($nama,$kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			$edit = true;
		}
		
		$data_values = array (
			'IDPERUSAHAAN'   => $id,
			'KODEPERUSAHAAN' => $kode,
			'NAMAPERUSAHAAN' => $nama,
			'ALAMAT'         => $this->input->post('ALAMAT'),
			'KODEPOS'        => $this->input->post('KODEPOS'),
			'KOTA'           => $this->input->post('KOTA'),
			'PROPINSI'       => $this->input->post('PROPINSI'),
			'NEGARA'         => $this->input->post('NEGARA'),
			'TELP'           => $this->input->post('TELP'),
			'FAX'            => $this->input->post('FAX'),
			'NPWP'			 => $this->input->post('NPWP'),
			'INFORMASIREKENING'	=> $this->input->post('INFORMASIREKENING'),
			'CATATAN'        => $this->input->post('CATATAN'),
			'USERENTRY'      => $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLENTRY'       => date("Y-m-d"),
			'STATUS'         => $status
		);

		$response = $this->model_master_perusahaan->simpan($id,$data_values,$edit);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		
		// panggil fungsi untuk log history
		log_history(
			$kode,
			'MASTER PERUSAHAAN',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'mperusahaan',
					'kode'  => 'kodeperusahaan'
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
	
	function hapus(){
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');

		$exe = $this->model_master_perusahaan->hapus($id);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }

		echo json_encode(array('success' => true));

		log_history(
			$kode,
			'MASTER PERUSAHAAN',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}
	
	function getAll(){
		$query = $this->model_master_perusahaan->getAllPerusahaan();
		echo json_encode($query);
	}
	
	function getPerusahaanPerUser(){
		$user = $this->input->post('user',$_SESSION[NAMAPROGRAM]['USERID']);

		if ($user == 0)
			$query = $this->model_master_perusahaan->getAllPerusahaan();
		else
			$query = $this->model_master_perusahaan->getPerusahaanPerUser($user);
		
		echo json_encode($query);
	}

	function getPerusahaanLogin(){
		$user = $this->input->post('user',$_SESSION[NAMAPROGRAM]['USERID']);

		if ($user == 'vision' || $user == 'VISION')
			$query = $this->model_master_perusahaan->getAllPerusahaan();
		else
			$query = $this->model_master_perusahaan->getPerusahaanLogin(strtoupper($user));
		
		echo json_encode($query);
	}
}
