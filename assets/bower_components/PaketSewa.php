<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaketSewa extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(['model_master_paket_sewa', 'model_master_gedung']);
	}
	
	public function index(){
		$kodeMenu = $this->input->get('kode');
		$config['KODE'] = $this->model_master_config->getConfig('MPAKETSEWA','KODEPAKETSEWA');
		$config['daftarGedung'] = $this->model_master_gedung->getAll();
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu, $config);
	}
	
	public function comboGrid() {
		$this->output->set_content_type('application/json');
		$pagination = $this->setPaginationGrid();
		$response   = $this->model_master_paket_sewa->comboGrid($pagination);
		
		echo json_encode($response);
	}

	public function dataGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_paket_sewa->dataGrid();
		echo json_encode($response);
	}
		
	public function simpan(){
		//die(json_encode(array('errorMsg' => 'tes')));
		$id             = $this->input->post('IDPAKETSEWA');
		$kode           = $this->input->post('KODEPAKETSEWA');
		$nama           = $this->input->post('NAMAPAKETSEWA');
		$akunpendapatan = $this->input->post('AKUNPENDAPATAN');
		$status         = $this->input->post('STATUS') ?? 0;

        if ($nama == '') die(json_encode(array('errorMsg' => 'Nama Paket Member Tidak Boleh Kosong')));
        if ($akunpendapatan == '') die(json_encode(array('errorMsg' => 'Akun Pendapatan Tidak Boleh Kosong')));

		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
			$setting = $this->model_master_config->getConfigAll('MPAKETSEWA','KODEPAKETSEWA');
			if($setting->VALUE == "AUTO"){
				//custom filter
				//$filter['lokasi'] = $lokasi;
				//$filter['tgltrans'] = $tgltrans;
				$kode = autogen($setting,$filter);
			}
			//CEK APAKAH KODE & NAMA SUDAH DIGUNAKAN
			$response = $this->model_master_paket_sewa->cek_valid_kode($kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			
			$response = $this->model_master_paket_sewa->cek_valid_nama($nama);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			$status = 1;
			$edit = false;
		}
		else{
			//jika edit
			//cek apakah nama sudah digunakan
			$response = $this->model_master_paket_sewa->cek_valid_nama($nama,$kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			$edit = true;
		}

		$data_values = array (
			'IDPERUSAHAAN'       => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'KODEPAKETSEWA'      => $kode,
			'NAMAPAKETSEWA'      => $nama,
			'NAMAGEDUNG'		 => $this->input->post('NAMAGEDUNG'),
			'JENISSEWA'          => $this->input->post('JENISSEWA'),
			'WARNA'		         => $this->input->post('WARNA'),
			'BOLEHDICICIL'       => $this->input->post('BOLEHDICICIL',0),
			'PERIODEPAKAI'       => $this->input->post('PERIODEPAKAI',0),
			'JUMLAH'             => $this->input->post('JUMLAH',1),
			'HARGA'              => $this->input->post('HARGA',0),
			'TGLAWALBERLAKU'     => $this->input->post('TGLAWALBERLAKU'),
			'TGLAKHIRBERLAKU'    => $this->input->post('TGLAKHIRBERLAKU'),
			'KODEAKUNPENDAPATAN' => $akunpendapatan,
			'CATATAN'            => $this->input->post('CATATAN'),
			'USERENTRY'          => $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLENTRY'           => date("Y-m-d"),
			'STATUS'             => $status
		);

		$response = $this->model_master_paket_sewa->simpan($id,$data_values,$edit);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		
		// panggil fungsi untuk log history
		log_history(
			$kode,
			'MASTER PAKET SEWA',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'mpaketsewa',
					'kode'  => 'kodepaketsewa'
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true,'errorMsg' => ''));
	}

	function hapus(){
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');

		$exe = $this->model_master_paket_sewa->hapus($id);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		echo json_encode(array('success' => true));

		log_history(
			$kode,
			'MASTER PAKET SEWA',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}
}
