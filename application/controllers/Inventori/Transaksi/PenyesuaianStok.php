<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenyesuaianStok extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['model_inventori_penyesuaian','model_master_lokasi']);
	}

	public function index()
	{
		$kodeMenu = $this->input->get('kode');
		$config['KODE'] = $this->model_master_config->getConfig('TPENYESUAIANSTOK','KODEPENYESUAIANSTOK');
		$config['INPUTHARGA'] = $this->model_master_config->getAkses($kodeMenu)["INPUTHARGA"];
		$config['LIHATHARGA'] = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu,$config);
	}

	public function comboGridBarang($idpr = ""){
		$this->output->set_content_type('application/json');
		$response = $this->model_inventori_penyesuaian->comboGridBarang($this->setPaginationGrid(),$idpr);

		echo json_encode($response);
	}


	public function dataGrid() {
		//$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'kodebeli';
		//$data_param = array($_SESSION['KODELOKASI']);

		$filter = $this->setFilterGrid();

		if ($this->input->post('kodetrans')!='') {
			$filter['sql'] .= "and a.kodepenyesuaianstok like ?";
			$filter['param'][] = '%'.$this->input->post('kodetrans').'%';
		}

		$temp_tgl_aw = TGLAWALFILTER;
		$tgl_aw      = $this->input->post('tglawal') == '' ? "and a.tgltrans>='$temp_tgl_aw'" : "and a.tgltrans>='".ubah_tgl_firebird($this->input->post('tglawal'))."'";
		$tgl_ak      = $this->input->post('tglakhir')== '' ? "and a.tgltrans<='".date('Y-m-d')."'" : "and a.tgltrans<='".ubah_tgl_firebird($this->input->post('tglakhir'))."'";
		$status = explode(",",$this->input->post('status'));
		$status = count($status)>0 ? "and (a.STATUS='".implode("' or a.STATUS='", $status)."')" : '';
		$filter['sql'] .= $tgl_aw;
		$filter['sql'] .= $tgl_ak;
		$filter['sql'] .= $status;

		$this->output->set_content_type('application/json');

		$response = $this->model_inventori_penyesuaian->dataGrid($this->setPaginationGrid(), $filter);
		echo json_encode($response);
	}
	
	public function loadDataBarang(){
	    $this->output->set_content_type('application/json');
		$idlokasi = $this->input->post('idlokasi');
		$tgltrans = $this->input->post('tgltrans');
		
		$response['rows'] = $this->model_inventori_penyesuaian->loadDataBarang($idlokasi,$tgltrans);

		echo json_encode($response);
	}
	
	public function loadHeader(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$mode = $this->input->post('mode');
		
		$response = $this->model_inventori_penyesuaian->loadDataHeader($id);

		echo json_encode($response);
	}

	public function loadData(){
		$idtrans = $this->input->post('idtrans');
		$query   = $this->model_inventori_penyesuaian->loadData($idtrans);
		$items   = array();
		foreach($query as $rs){
			$items[] = array(
				'idbarang'		=> $rs->IDBARANG,
				'kodebarang'	=> $rs->KODEBARANG,
				'namabarang' 	=> $rs->NAMABARANG,
				'jml' 			=> $rs->JML,
				'selisih' 		=> $rs->SELISIH,
				'satuan' 		=> $rs->SATUAN,
				'konversi' 		=> $rs->KONVERSI,
				'harga'			=> $rs->HARGA,
				'subtotal'		=> $rs->SUBTOTAL,
			);
		}
		echo json_encode(array(
			'success' => true,
			'detail'  => $items,
		));
	}
	
	public function loadDetail(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');
		$mode = $this->input->post('mode');
		
		$response = $this->model_inventori_penyesuaian->loadDataDetail($id,$kode);

		echo json_encode($response);
	}
	
	public function supplierPerusahaan(){
		$kodesupplier          		 = $this->input->post('kodesupplier');
		$exe                   		 = $this->model_master_supplier->getSupplierPerusahaan($kodesupplier);
		$items                 		 = array_column($exe,'KODEPERUSAHAAN');
		$json['hubungan_perusahaan'] = $items;
		echo json_encode($json);
	}

	public function simpan(){
		$a_detail  = json_decode($_POST['data_detail']);

		//cek_data($a_detail, 'kodebarang', 'mbarang');

		$kodetrans    = $this->input->post('KODEPENYESUAIAN');
		$id           = $this->input->post('IDPENYESUAIAN');
		$idopnamestok = $this->input->post('IDOPNAME');
		$idlokasi     = $this->input->post('IDLOKASI');
		$tgltrans     = $this->input->post('TGLTRANS');
		$catatan      = $this->input->post('CATATAN');
		
		if($idopnamestok == null)
		{
			$idopnamestok = 0;
		}
		
		if (count($a_detail)<1) die(json_encode(array('errorMsg' => 'Detail Transaksi Tidak Boleh Kosong')));

		cek_valid_data('MLOKASI', 'IDLOKASI', $idlokasi, 'Lokasi');

		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
			$setting = $this->model_master_config->getConfigAll('TPENYESUAIANSTOK','KODEPENYESUAIANSTOK');
			if($setting->VALUE == "AUTO"){
				//custom filter
				$row = $this->model_master_lokasi->getLokasi($idlokasi);
				$lokasi = $row->KODELOKASI;
				$filter['lokasi']   = $lokasi;
				$filter['tgltrans'] = $tgltrans;
				        $kodetrans  = autogen($setting,$filter);
			}
			$edit = 0;
		}
		else{
			$edit = 1;
		}
		// query header
		$data_values = array (
			'IDPERUSAHAAN'        => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'KODEPENYESUAIANSTOK' => $kodetrans,
			'IDLOKASI'            => $idlokasi,
			'IDOPNAMESTOK'        => $idopnamestok,
			'TGLTRANS'            => $tgltrans,
			'CATATAN'             => $catatan,
			'TGLENTRY'            => date('Y.m.d'),
			'JAMENTRY'            => date('H:i:s'),
			'USERENTRY'           => $_SESSION[NAMAPROGRAM]['USERID'],
			'STATUS'              => 'S',
			'CLOSING'             => 0
		);

		$response = $this->model_inventori_penyesuaian->simpan($id,$data_values,$a_detail,$edit);
		if ($response != '')
		{
			die(json_encode(array('errorMsg' => $response)));
		}

		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'PENYESUAIAN STOK',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'tpenyesuaianstok',
					'kode'  => 'kodepenyesuaianstok'
				),
				array(
					'nama'  => 'detail',
					'tabel' => 'tpenyesuaianstokdtl',
					'kode'  => 'idpenyesuaianstok',
					'id'  => $id,
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true,'idtrans'=>$id));
	}

	function batalTrans(){
		$idtrans   = $_POST['idtrans'];
		$kodetrans = $_POST['kodetrans'];
		$alasan    = $_POST['alasan'];
		$status    = $this->model_inventori_penyesuaian->getStatusTrans($idtrans);

		if ($status=='P') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');

		$exe = $this->model_inventori_penyesuaian->batal($idtrans,$alasan);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }

		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'PENYESUAIAN STOK',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}

	function ubahStatusJadiInput(){
		$idtrans   = $_POST['idtrans'];
		$kodetrans = $_POST['kodetrans'];
		$status    = $this->model_inventori_penyesuaian->getStatusTrans($idtrans);

		if ($status=='P' or $status=='I') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');

		$exe = $this->model_inventori_penyesuaian->ubahStatusJadiInput($idtrans);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }

		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'PENYESUAIAN STOK',
			'BATAL CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}
	function ubahStatusJadiSlip(){
		$idtrans = $this->input->post('idtrans');
		$kodetrans = $this->input->post('kodetrans');

		$exe = $this->model_inventori_penyesuaian->ubahStatusJadiSlip($idtrans);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }

		echo json_encode(array('success' => true));

		log_history(
			$kodetrans,
			'PENYESUAIAN STOK',
			'CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}

	function getStatusTrans(){
		$idtrans = $this->input->post('idtrans');
		$status  = $this->model_inventori_penyesuaian->getStatusTrans($idtrans);
		echo json_encode(array('status' => $status));
	}
	function cetak($idTrans){
		$data['idtrans'] = $idTrans;
		//$data['namasubcustomer'] = $namaSubcustomer;
		$this->load->view('reports/v_faktur_inventori_penyesuaian_stok.php', $data);
	}
}
