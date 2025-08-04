<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SaldoAwalStok extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['model_inventori_saldo_awal','model_master_lokasi']);
	}
	
	public function index()
	{
		$kodeMenu = $this->input->get('kode');
		$config['KODE'] = $this->model_master_config->getConfig('SALDOSTOK','KODESALDOSTOK');
		$config['INPUTHARGA'] = $this->model_master_config->getAkses($kodeMenu)["INPUTHARGA"];
		$config['LIHATHARGA'] = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu,$config);
	}
	
	public function comboGrid($lokasi='') {
		$this->output->set_content_type('application/json');
		
		$pagination = $this->setPaginationGrid();
		$pagination['lokasi'] = $lokasi;
		$response = $this->model_inventori_saldo_awal->comboGrid($pagination);
		echo json_encode($response);
	}
	
	public function comboGridBarang($idpr = ""){
		$this->output->set_content_type('application/json');
		$response = $this->model_inventori_saldo_awal->comboGridBarang($this->setPaginationGrid(),$idpr);
		
		echo json_encode($response);
	}


	public function dataGrid() {
		//$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'kodebeli';
		//$data_param = array($_SESSION['KODELOKASI']);
		$temp_tgl_aw = TGLAWALFILTER;
		$tgl_aw = $this->input->post('tglawal') =='' ? "and a.TGLTRANS>='$temp_tgl_aw'" : "and a.TGLTRANS>='".ubah_tgl_firebird($this->input->post('tglawal'))."'";
		$tgl_ak = $this->input->post('tglakhir')=='' ? "and a.TGLTRANS<='".date('Y-m-d')."'" : "and a.TGLTRANS<='".ubah_tgl_firebird($this->input->post('tglakhir'))."'";
		
		$status = explode(",",$this->input->post('status'));
		$status = count($status)>0 ? "and (a.STATUS='".implode("' or a.STATUS='", $status)."')" : '';
		$filter['sql'] .= $tgl_aw;
		$filter['sql'] .= $tgl_ak;
		$filter['sql'] .= $status;
		$this->output->set_content_type('application/json');

		$response = $this->model_inventori_saldo_awal->dataGrid($this->setPaginationGrid(), $filter);
		
		echo json_encode($response);
	}
	
	public function loadHeader(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$mode = $this->input->post('mode');
		
		$response = $this->model_inventori_saldo_awal->loadDataHeader($id);

		echo json_encode($response);
	}
	
	public function loadData(){
		$idtrans = $this->input->post('idtrans');
		$query = $this->model_inventori_saldo_awal->loadData($idtrans);
		$items = array();
		foreach($query as $rs){
			$items[] = array(
				'idbarang'		=> $rs->IDBARANG,
				'kodebarang'	=> $rs->KODEBARANG,
				'namabarang' 	=> $rs->NAMABARANG,
				'jml' 			=> $rs->JML,
				'satuan' 		=> $rs->SATUAN,
				'satuanutama'	=> $rs->SATUANUTAMA,
				'konversi' 		=> $rs->KONVERSI,
				'harga' 		=> $rs->HARGA,
				'subtotal' 		=> $rs->SUBTOTAL,
				'detaillo'		=> $detail
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
		
		$response = $this->model_inventori_saldo_awal->loadDataDetail($id,$kode);

		echo json_encode($response);
	}
	
	public function supplierPerusahaan(){
		$kodesupplier = $this->input->post('kodesupplier');
		$exe = $this->model_master_supplier->getSupplierPerusahaan($kodesupplier);
		$items = array_column($exe,'KODEPERUSAHAAN');
		$json['hubungan_perusahaan'] = $items;
		echo json_encode($json);
	}
		
	public function simpan(){
		$a_detail  = json_decode($_POST['data_detail']);

		//cek_data($a_detail, 'kodebarang', 'mbarang');

		$id    			 = $this->input->post('IDSALDO');
		$kodetrans    	 = $this->input->post('KODESALDO');
		$idlokasi    	 = $this->input->post('IDLOKASI');
		$tgltrans        = $this->input->post('TGLTRANS');
		$grandtotal      = $this->input->post('GRANDTOTAL');
		$catatan         = $this->input->post('CATATAN')??'';
		
		
		if (count($a_detail)<1) die(json_encode(array('errorMsg' => 'Detail Transaksi Tidak Boleh Kosong')));

		cek_valid_data('MLOKASI', 'IDLOKASI', $idlokasi, 'Lokasi');

		$row = $this->model_inventori_saldo_awal->getTgl($id,$idlokasi);

		if($row->TGL != '' && $tgltrans != $row->TGL ){
			die(json_encode(array('errorMsg' => 'Tanggal Saldo Awal untuk lokasi tersebut harus menggunakan Tanggal '.$row->TGL)));
		}
		
		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
			$setting = $this->model_master_config->getConfigAll('SALDOSTOK','KODESALDOSTOK');
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
			'KODESALDOSTOK' => $kodetrans, 
			'IDLOKASI'      => $idlokasi,
			'IDPERUSAHAAN'  => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'TGLTRANS'      => $tgltrans,
			'TGLENTRY'      => date('Y.m.d'),
			'JAMENTRY'		=> date('H:i:s'),
			'USERENTRY' 	=> $_SESSION[NAMAPROGRAM]['USERID'],
			'GRANDTOTAL' 	=> $grandtotal,
			'CATATAN'		=> $catatan, 
			'STATUS'		=> 'S',
			'CLOSING'		=> 0
		);

		$response = $this->model_inventori_saldo_awal->simpan($id,$data_values,$a_detail,$edit);
		if ($response != '') {
			die(json_encode(array('errorMsg' => $response)));
		}

		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'SALDO AWAL STOK',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'saldostok',
					'kode'  => 'KODESALDOSTOK'
				),
				array(
					'nama'  => 'detail',
					'tabel' => 'saldostokdtl',
					'kode'  => 'KODESALDOSTOK'
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}
	
	function batalTrans(){
		$idtrans   = $_POST['idtrans'];
		$kodetrans = $_POST['kodetrans'];
		$alasan	   = $_POST['alasan'];
		$status	   = $this->model_inventori_saldo_awal->getStatusTrans($idtrans);

		if ($status=='P') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');

		$exe = $this->model_inventori_saldo_awal->batal($idtrans,$alasan);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'SALDO AWAL STOK',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}

	function ubahStatusJadiInput(){
		$idtrans = $_POST['idtrans'];
		$kodetrans = $_POST['kodetrans'];
		$status    = $this->model_inventori_saldo_awal->getStatusTrans($idtrans);

		if ($status=='P' or $status=='I') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');

		$exe = $this->model_inventori_saldo_awal->ubahStatusJadiInput($idtrans);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'SALDO AWAL STOK',
			'BATAL CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}
	function ubahStatusJadiSlip(){
		$idtrans = $this->input->post('idtrans');
		$kodetrans = $this->input->post('kodetrans');

		$exe = $this->model_inventori_saldo_awal->ubahStatusJadiSlip($idtrans);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }

		log_history(
			$kodetrans,
			'SALDO AWAL STOK',
			'CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}
	
	function getStatusTrans($idtrans=""){
		$idtrans = $this->input->post('idtrans');
		$status = $this->model_inventori_saldo_awal->getStatusTrans($idtrans);
		echo json_encode(array('status' => $status));
	}
	
	function cetak($idTrans){
		$data['idtrans'] = $idTrans;
		//$data['namasubcustomer'] = $namaSubcustomer;
		$this->load->view('reports/v_faktur_inventori_saldo_awal_stok.php', $data);
	}
}
