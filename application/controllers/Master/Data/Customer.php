<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CUSTOMER extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(['model_master_customer']);
	}
	
	public function index(){
		$kodeMenu = $this->input->get('kode');
		$config['KODE'] = $this->model_master_config->getConfig('MCUSTOMER','KODECUSTOMER');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu, $config);
	}

	public function comboGridBadanUsaha() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_customer->comboGridBadanUsaha($this->setPaginationGrid());
		echo json_encode($response);
	}
	
	public function comboGridTransaksi() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_customer->comboGridTransaksi($this->setPaginationGrid());
		echo json_encode($response);
	}

	public function dataGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_customer->dataGrid($this->setPaginationGrid(), $this->setFilterGrid());
		echo json_encode($response);
	}
		
	public function simpan(){
        // die (json_encode(array('success' => false,'errorMsg' => 'cek swal')));
		$id     = $this->input->post('IDCUSTOMER','');
		$kode   = $this->input->post('KODECUSTOMER');
		$nama   = $this->input->post('NAMACUSTOMER');
		$status = $this->input->post('STATUS') ?? 0;
		$member = $this->input->post('MEMBER') ?? 0;
		$konsinyasi = $this->input->post('KONSINYASI') ?? 0;
		if ($nama == '') die(json_encode(array('errorMsg' => 'Nama CUSTOMER Tidak Boleh Kosong')));

		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
			$setting = $this->model_master_config->getConfigAll('MCUSTOMER','KODECUSTOMER');
			if($setting->VALUE == "AUTO"){
				//custom filter
				$filter['lokasi'] = $lokasi;
				$filter['tgltrans'] = $tgltrans;
				$kode = autogen($setting,$filter);
			}
			//CEK APAKAH KODE & NAMA SUDAH DIGUNAKAN
			$response = $this->model_master_customer->cek_valid_kode($kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
            }
			
			$status = 1;
			$edit = false;
		}
		else{
			//jika edit
			//cek apakah nama sudah digunakan
			$response = $this->model_master_customer->cek_valid_nama($nama,$kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
            }
            
			$edit = true;
		}
		
		$data_values = array (
			'IDPERUSAHAAN'    => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'KODECUSTOMER'    => $kode,
			'NAMACUSTOMER'    => $this->input->post('NAMACUSTOMER'),
			'ALAMAT'          => $this->input->post('ALAMAT'),
			'KOTA'            => $this->input->post('KOTA')??"",
			'PROVINSI'        => $this->input->post('PROVINSI')??"",
			'NEGARA'          => $this->input->post('NEGARA')??"",
			'TELP'            => $this->input->post('TELP'),
			'EMAIL'           => $this->input->post('EMAIL'),
			'NAMABANK'        => $this->input->post('NAMABANK'),
			'NOREKENING'      => $this->input->post('NOREKENING'),
			'NPWP'            => $this->input->post('NPWP')??"",   
			'CATATAN'         => $this->input->post('CATATAN'),
			'USERENTRY'       => $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLENTRY'        => date("Y-m-d"),
			'DISKONMEMBER'    => $this->input->post('DISKONMEMBER') ?? 0,
			'MEMBER'          => $member,
			'KONSINYASI'      => $konsinyasi,
			'STATUS'          => $status
		);
		
		
		$response = $this->model_master_customer->simpan($id,$data_values,$edit);
		if (!is_numeric($response)){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		
		// panggil fungsi untuk log history
		log_history(
			$kode,
			'MASTER CUSTOMER',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'mCUSTOMER',
					'kode'  => 'kodeCUSTOMER'
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
	
	function hapus(){
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');
		
		$exe = $this->model_master_customer->hapus($id);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		echo json_encode(array('success' => true));

		log_history(
			$kode,
			'MASTER CUSTOMER',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}
}
