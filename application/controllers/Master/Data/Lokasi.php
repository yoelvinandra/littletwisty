<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lokasi extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(['model_master_lokasi']);
	}
	
	public function index(){
		$kodeMenu = $this->input->get('kode');
		$config['KODE'] = $this->model_master_config->getConfig('MLOKASI','KODELOKASI');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu,$config);
	}
	
	public function browse_data($jenis = '', $aktif = 1) {
        // $this->output->set_content_type('application/json');
        // $pagination = array();
        $response = $this->model_master_lokasi->comboGrid($pagination);
        
        $data = array();
        foreach ($response['rows'] as $r){
            array_push($data, array(
                "id"   => $r->KODE,
                "text" => "<option>(".$r->KODE.")   ".$r->NAMA."</option>",
            ));
        }
		echo json_encode(array("results"=>$data));
	}
	
	public function getAll() {
		$this->output->set_content_type('application/json');
		$iduser = $this->input->post("iduser");
		$response = $this->model_master_lokasi->getAll($this->setPaginationGrid(),$iduser);
		echo json_encode($response);
	}

	public function getLokasiDefault(){
		$response = $this->model_master_lokasi->getLokasiDefault();
		echo json_encode($response);
	}

	public function cekLokasiDefault(){
		$kodelokasi = $this->input->post('kodelokasi');
		$response = $this->model_master_lokasi->cekLokasiDefault($kodelokasi);
		echo json_encode($response);
	}
	
	public function cekGroupLokasi(){
	    $group = json_decode($this->input->post('group'));
		$response = $this->model_master_lokasi->cekGroupLokasi($group);
		echo json_encode($response);
	}

	public function dataGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_lokasi->dataGrid($this->setPaginationGrid(), $this->setFilterGrid());
		echo json_encode($response);
	}
	
	public function gantiSessionLokasi(){
		$_SESSION[NAMAPROGRAM]['IDLOKASI'] = $this->input->post('lokasi');
		echo true;
	}
	
	public function simpan(){
		$id     = $this->input->post('IDLOKASI');
		$kode   = $this->input->post('KODELOKASI');
		$nama   = $this->input->post('NAMALOKASI');
        $status = $this->input->post('STATUS') ?? 0;
        
        
        $all = $this->input->post('ALL') ?? 0;
        $marketplace = $this->input->post('MARKETPLACE') ?? 0;
        $konsinyasi = $this->input->post('KONSINYASI') ?? 0;
        
        $grouplokasi = "";
        
        if($all != 0)
        {
            $grouplokasi .= "ALL,";
        }
        if($marketplace != 0)
        {
            $grouplokasi .= "MARKETPLACE,";
        }
        if($konsinyasi != 0)
        {
            $grouplokasi .= "KONSINYASI,";
        }
        
        if($grouplokasi != "")
        {
            $grouplokasi = substr($grouplokasi, 0, -1);
        }
     
		if ($nama == '') die(json_encode(array('errorMsg' => 'Nama Lokasi Tidak Boleh Kosong')));
	
		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
            $setting = $this->model_master_config->getConfigAll('MLOKASI','KODELOKASI');
			if($setting->VALUE == "AUTO"){
				//custom filter
				// $filter['lokasi']   = $lokasi;
				// $filter['tgltrans'] = $tgltrans;
				$kode       = autogen($setting,$filter);
			}
			//CEK APAKAH KODE & NAMA SUDAH DIGUNAKAN
			$response = $this->model_master_lokasi->cek_valid_kode($kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			
			$response = $this->model_master_lokasi->cek_valid_nama($nama);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			$status = 1;
			$edit = false;
		}
		else{
			//jika edit
			//cek apakah nama sudah digunakan
			$response = $this->model_master_lokasi->cek_valid_nama($nama,$kode);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			$edit = true;
		}
		
		$data_values = array (
			'IDPERUSAHAAN'             => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'KODELOKASI'               => $kode,
			'GROUPLOKASI'              => $grouplokasi,
			'NAMALOKASI'               => $this->input->post('NAMALOKASI'),
			'CATATAN'                  => $this->input->post('CATATAN'),
			'USERENTRY'                => $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLENTRY'                 => date("Y-m-d"),
			'STATUS'                   => $status
		);

		$response = $this->model_master_lokasi->simpan($id,$data_values,$edit);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		
		// panggil fungsi untuk log history
		log_history(
			$kode,
			'MASTER LOKASI',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'mlokasi',
					'kode'  => 'kodelokasi'
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
	
	function hapus(){
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');

		$exe = $this->model_master_lokasi->hapus($id);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		echo json_encode(array('success' => true));

		log_history(
			$kode,
			'MASTER LOKASI',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}

	// method ini digunakan di master user
	// untuk mendapatkan lokasi per user
	function getLokasiPerUser() {
		$this->output->set_content_type('application/json');

		$id = $this->input->post('user');

		$response = $this->model_master_lokasi->getLokasiPerUser($id);

		echo json_encode($response);
	}
}
