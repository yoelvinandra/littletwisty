<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(['model_master_supplier']);
	}
	
	public function index(){
		$kodeMenu = $this->input->get('kode');
		$config['KODE'] = $this->model_master_config->getConfig('MSUPPLIER','KODESUPPLIER');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu, $config);
	}

	public function comboGridBadanUsaha() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_supplier->comboGridBadanUsaha($this->setPaginationGrid());
		echo json_encode($response);
	}
	
	public function comboGridTransaksi() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_supplier->comboGridTransaksi($this->setPaginationGrid());
		echo json_encode($response);
	}

	public function dataGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_supplier->dataGrid($this->setPaginationGrid(), $this->setFilterGrid());
		echo json_encode($response);
	}
		
	public function simpan(){
        // die (json_encode(array('success' => false,'errorMsg' => 'cek swal')));
		$id     = $this->input->post('IDSUPPLIER','');
		$kode   = $this->input->post('KODESUPPLIER');
		$nama   = $this->input->post('NAMASUPPLIER');
		$status = $this->input->post('STATUS') ?? 0;
		if ($nama == '') die(json_encode(array('errorMsg' => 'Nama Supplier Tidak Boleh Kosong')));

		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
			$setting = $this->model_master_config->getConfigAll('MSUPPLIER','KODESUPPLIER');
			if($setting->VALUE == "AUTO"){
				//custom filter
				$filter['lokasi'] = $lokasi;
				$filter['tgltrans'] = $tgltrans;
				$kode = autogen($setting,$filter);
			}
			//CEK APAKAH KODE & NAMA SUDAH DIGUNAKAN
			$response = $this->model_master_supplier->cek_valid_kode($kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
            }
            
			$response = $this->model_master_supplier->cek_valid_nama($nama);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			
			$status = 1;
			$edit = false;
		}
		else{
			//jika edit
			//cek apakah nama sudah digunakan
			$response = $this->model_master_supplier->cek_valid_nama($nama,$kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
            }
            
			$edit = true;
		}
		
		$data_values = array (
			'IDPERUSAHAAN'    => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'KODESUPPLIER'    => $kode,
			'NAMASUPPLIER'    => $this->input->post('NAMASUPPLIER'),
			'BADANUSAHA'      => $this->input->post('BADANUSAHA'),
			'ALAMAT'          => $this->input->post('ALAMAT'),
			'TELP'            => $this->input->post('TELP'),
			'FAX'             => $this->input->post('FAX'),
			'EMAIL'           => $this->input->post('EMAIL'),
			'WEBSITE'         => $this->input->post('WEBSITE'),
			'CONTACTPERSON'   => $this->input->post('CONTACTPERSON'),
			'TELPCP'          => $this->input->post('TELPCP'),
			'EMAILCP'         => $this->input->post('EMAILCP'),
			'NPWP'            => $this->input->post('NPWP'),
			'IDSYARATBAYAR'   => $this->input->post('IDSYARATBAYAR'),
			'NAMABANK'        => $this->input->post('NAMABANK'),
			'NOREKENING'      => $this->input->post('NOREKENING'),
			'CATATAN'         => $this->input->post('CATATAN'),
			'USERENTRY'       => $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLENTRY'        => date("Y-m-d"),
			'STATUS'          => $status
		);
		$response = $this->model_master_supplier->simpan($id,$data_values,$edit);
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

		$exe = $this->model_master_supplier->hapus($id);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		echo json_encode(array('success' => true));

		log_history(
			$kode,
			'MASTER SUPPLIER',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}
}
