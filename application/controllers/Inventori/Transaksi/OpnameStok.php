<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OpnameStok extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['model_inventori_opname','model_master_lokasi','model_master_barang']);
	}

	public function index()
	{
		$kodeMenu = $this->input->get('kode');
		$config['KODE'] = $this->model_master_config->getConfig('TOPNAMESTOK','KODEOPNAMESTOK');
		$config['INPUTHARGA'] = $this->model_master_config->getAkses($kodeMenu)["INPUTHARGA"];
		$config['LIHATHARGA'] = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu,$config);
	}

	public function comboGridBarang($idpr = ""){
		$this->output->set_content_type('application/json');
		$response = $this->model_inventori_opname->comboGridBarang($this->setPaginationGrid(),$idpr);

		echo json_encode($response);
	}
	
	public function dataGrid() {
		//$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'kodebeli';
		//$data_param = array($_SESSION['KODELOKASI']);

		$filter = $this->setFilterGrid();

		if ($this->input->post('kodetrans')!='') {
			$filter['sql'] .= "and a.kodeopnamestok like ?";
			$filter['param'][] = '%'.$this->input->post('kodetrans').'%';
		}

		$temp_tgl_aw = TGLAWALFILTER;
		$tgl_aw = $this->input->post('tglawal') =='' ? "and a.tgltrans>='$temp_tgl_aw'" : "and a.tgltrans>='".ubah_tgl_firebird($this->input->post('tglawal'))."'";
		$tgl_ak = $this->input->post('tglakhir')=='' ? "and a.tgltrans<='".date('Y-m-d')."'" : "and a.tgltrans<='".ubah_tgl_firebird($this->input->post('tglakhir'))."'";
		$status = explode(",",$this->input->post('status'));
		$status = count($status)>0 ? "and (a.STATUS='".implode("' or a.STATUS='", $status)."')" : '';
		$filter['sql'] .= $tgl_aw;
		$filter['sql'] .= $tgl_ak;
		$filter['sql'] .= $status;

		$this->output->set_content_type('application/json');

		$response = $this->model_inventori_opname->dataGrid($this->setPaginationGrid(), $filter);
		echo json_encode($response);
	}
	
	public function loadHeader(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$mode = $this->input->post('mode');
		
		$response = $this->model_inventori_opname->loadDataHeader($id);

		echo json_encode($response);
	}
	

	public function loadData(){
		$idtrans = $this->input->post('idtrans');
		$query   = $this->model_inventori_opname->loadData($idtrans);
		$items   = array();
		foreach($query as $rs){
			$items[] = array(
				'idbarang'		=> $rs->IDBARANG,
				'kodebarang'	=> $rs->KODEBARANG,
				'namabarang' 	=> $rs->NAMABARANG,
				'jml' 			=> $rs->JML,
				'satuan' 		=> $rs->SATUAN,
				'konversi' 		=> $rs->KONVERSI,
			);
		}
		echo json_encode(array(
			'success' => true,
			'detail' => $items,
		));
	}
	public function loadDetail(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');
		$mode = $this->input->post('mode');
		
		$response = $this->model_inventori_opname->loadDataDetail($kode);

		echo json_encode($response);
	}

	public function loadDataOpnamePenyesuaian(){
		$id = $this->input->post('id');
		$mode = $this->input->post('mode');
		$response   = $this->model_inventori_opname->loadDataOpnamePenyesuaian($id);
		echo json_encode($response);
	}

	public function simpan(){
		$a_detail  = json_decode($_POST['data_detail']);

		//cek_data($a_detail, 'kodebarang', 'mbarang');

		$id        = $this->input->post('IDOPNAME');
		$kodetrans = $this->input->post('KODEOPNAME');
		$idlokasi  = $this->input->post('IDLOKASI');
		$tgltrans  = $this->input->post('TGLTRANS');
		$catatan   = $this->input->post('CATATAN');

		if (count($a_detail)<1) die(json_encode(array('errorMsg' => 'Detail Transaksi Tidak Boleh Kosong')));

		cek_valid_data('MLOKASI', 'IDLOKASI', $idlokasi, 'Lokasi');

		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
			$setting = $this->model_master_config->getConfigAll('TOPNAMESTOK','KODEOPNAMESTOK');
			if($setting->VALUE == "AUTO"){
				$row = $this->model_master_lokasi->getLokasi($idlokasi);
				$lokasi = $row->KODELOKASI;
				//custom filter
				$filter['lokasi']   = $lokasi;
				$filter['tgltrans'] = $tgltrans;
				$kodetrans = autogen($setting,$filter);
			}
			$edit = 0;
		}
		else{
			$edit = 1;
		}

		// query header
		$data_values = array (
			'KODEOPNAMESTOK' => $kodetrans,
			'IDPERUSAHAAN'   => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'IDLOKASI'       => $idlokasi,
			'TGLTRANS'       => $tgltrans,
			'CATATAN'        => $catatan,
			'USERENTRY'      => $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLENTRY'       => date('Y.m.d'),
			'JAMENTRY'       => date('H:i:s'),
			'STATUS'         => 'S',
			'CLOSING'        => 0
		);

		$response = $this->model_inventori_opname->simpan($id,$data_values,$a_detail,$edit);
		if ($response != '')
		{
			die(json_encode(array('errorMsg' => $response)));
		}

		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'OPNAME STOK',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'topnamestok',
					'kode'  => 'KODEOPNAMESTOK'
				),
				array(
					'nama'  => 'detail',
					'tabel' => 'topnamestokdtl',
					'kode'  => 'IDOPNAMESTOK',
					'id'  => $id,
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}

	function batalTrans(){
		$idtrans   = $_POST['idtrans'];
		$kodetrans = $_POST['kodetrans'];
		$alasan    = $_POST['alasan'];
		$status    = $this->model_inventori_opname->getStatusTrans($idtrans);

		if ($status=='P') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');

		$exe = $this->model_inventori_opname->batal($idtrans,$alasan);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }

		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'OPNAME STOK',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}

	function ubahStatusJadiInput(){
		$idtrans   = $_POST['idtrans'];
		$kodetrans = $_POST['kodetrans'];
		$status    = $this->model_inventori_opname->getStatusTrans($idtrans);

		if ($status=='P' or $status=='I') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');

		$exe = $this->model_inventori_opname->ubahStatusJadiInput($idtrans);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }

		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'OPNAME STOK',
			'BATAL CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}
	function ubahStatusJadiSlip(){
		$idtrans = $this->input->post('idtrans');
		$kodetrans = $this->input->post('kodetrans');

		$exe = $this->model_inventori_opname->ubahStatusJadiSlip($idtrans);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }

		echo json_encode(array('success' => true));

		log_history(
			$kodetrans,
			'OPNAME STOK',
			'CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}

	function getStatusTrans(){
		$idtrans = $this->input->post('idtrans');
		$status  = $this->model_inventori_opname->getStatusTrans($idtrans);
		echo json_encode(array('status' => $status));
	}
	function cetak($idTrans){
		$data['idtrans'] = $idTrans;
		//$data['namasubcustomer'] = $namaSubcustomer;
		$this->load->view('reports/v_faktur_inventori_opname_stok.php', $data);
	}
}
