<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransferPersediaan extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['model_inventori_transfer','model_master_lokasi']);
	}
	
	public function index()
	{
		$kodeMenu = $this->input->get('kode');
		$config['KODE'] = $this->model_master_config->getConfig('TTRANSFER','KODETRANSFER');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu,$config);
	}
	
	public function comboGridBBK($lokasi='',$lokasitujuan='') {
		$this->output->set_content_type('application/json');
		
		$pagination     			= $this->setPaginationGrid();
		$pagination['lokasi']       = $lokasi;
		$pagination['lokasitujuan'] = $lokasitujuan;
		$response				    = $this->model_inventori_transfer->comboGridBBK($pagination);
		echo json_encode($response);
	}
	
	public function comboGridBarang($idtransfer = ""){
		$this->output->set_content_type('application/json');
		$response = $this->model_inventori_transfer->comboGridBarang($this->setPaginationGrid(),$idtransfer);
		
		echo json_encode($response);
	}

	public function dataGrid() {
		//$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'kodebeli';
		//$data_param = array($_SESSION['KODELOKASI']);

		$filter = $this->setFilterGrid();
		
		if ($this->input->post('kodetrans')!='') {
			$filter['sql'] .= "and a.KODETRANSFER like ?";
			$filter['param'][] = '%'.$this->input->post('kodetrans').'%';
		}
		
		$temp_tgl_aw = TGLAWALFILTER;
		$tgl_aw = $this->input->post('tglawal') =='' ? "and a.TGLTRANS>='$temp_tgl_aw'" : "and a.TGLTRANS>='".ubah_tgl_firebird($this->input->post('tglawal'))."'";
		$tgl_ak = $this->input->post('tglakhir')=='' ? "and a.TGLTRANS<='".date('Y-m-d')."'" : "and a.TGLTRANS<='".ubah_tgl_firebird($this->input->post('tglakhir'))."'";
		$status = explode(",",$this->input->post('status'));
		$status = count($status)>0 ? "and (a.STATUS='".implode("' or a.STATUS='", $status)."')" : '';
		$filter['sql'] .= $tgl_aw;
		$filter['sql'] .= $tgl_ak;
		$filter['sql'] .= $status;
		
		$this->output->set_content_type('application/json');

		$response = $this->model_inventori_transfer->dataGrid($this->setPaginationGrid(), $filter);
		echo json_encode($response); 
	}
	public function loadHeader(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$mode = $this->input->post('mode');
		
		$response = $this->model_inventori_transfer->loadDataHeader($id);

		echo json_encode($response);
	}
	public function loadData(){
		$idtrans = $this->input->post('idtrans');
		$query = $this->model_inventori_transfer->loadData($idtrans);
		$items = array();
		foreach($query as $rs){
			$items[] = array(
				'idbarang'   => $rs->IDBARANG,
				'kodebarang' => $rs->KODEBARANG,
				'namabarang' => $rs->NAMABARANG,
				'jml'        => $rs->JML,
				'terpenuhi'  => $rs->TERPENUHI,
				'sisa'       => $rs->SISA,
				'satuan'     => $rs->SATUAN,
				'konversi'   => $rs->KONVERSI,
				'tutup'      => $rs->TUTUP,
				'catatan'    => $rs->CATATAN,
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
		
		$response = $this->model_inventori_transfer->loadDataDetail($id,$kode);

		echo json_encode($response);
	}
	public function loadDataDetail(){
		$idtrans = $this->input->post('idtrans');
		$query = $this->model_inventori_transfer->loadData($idtrans);
		$items = array();
		foreach($query as $rs){
			if($rs->PAKAIPPN == 0){
				$pakaippn = "TIDAK";
				$dpp = $rs->SUBTOTALKURS;
			}
			else if($rs->PAKAIPPN == 1){
				$pakaippn = "EXCL";
				$dpp = $rs->SUBTOTALKURS;
			}
			else if($rs->PAKAIPPN == 2){
				$pakaippn = "INCL";
				$dpp = $rs->SUBTOTALKURS - $rs->PPNRP;
			}
			$items[] = array(
				'idtransreferensi'			=> $rs->IDTRANSFER,
				'kodetransreferensi'		=> $rs->KODETRANSFER,
				'idsyaratbayar'				=> $rs->IDSYARATBAYAR,
				'namasyaratbayar'			=> $rs->NAMASYARATBAYAR,
				'idbarang'		            => $rs->IDBARANG,
				'kodebarang'	            => $rs->KODEBARANG,
				'namabarang' 	            => $rs->NAMABARANG,
				'jmltransreferensi' 		=> $rs->JML,
				'terpenuhitransreferensi' 	=> $rs->TERPENUHI,
				'sisatransreferensi' 		=> 0,
				'jmlbbk' 		            => $rs->JML - $rs->TERPENUHI,
				'satuan' 		            => $rs->SATUAN,
				'satuanutama' 		        => $rs->SATUANUTAMA,
				'konversi' 		            => $rs->KONVERSI,
				'idcurrency' 	            => $_SESSION[NAMAPROGRAM]['IDCURRENCY'],
				'currency' 		            => $_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'],
				'harga'	 		            => 0,
				'hargaref'	 		        => 0,
				'discpersen'	 		    => 0,
				'disc'	 		            => 0,
				'discrp'	 	            => 0,
				'subtotal' 		            => 0,
				'nilaikurs' 	            => 0,
				'hargakurs'		            => 0,
				'subtotalkurs' 	            => 0,
				'pakaippn' 	            	=> "TIDAK",
				'ppnpersen' 	            => 0,
				'ppnrp' 	            	=> 0,
				'dpp' 	            	    => $dpp
			);
		}
		echo json_encode(array(
			'success' => true,
			'detail' => $items,
		));	
	}
	public function simpan(){
		$a_detail  = json_decode($_POST['data_detail']);

		//cek_data($a_detail, 'kodebarang', 'mbarang');

		$id             = $this->input->post('IDTRANSFER');
		$kodetrans      = $this->input->post('KODETRANSFER');
		$idlokasiasal   = $this->input->post('IDLOKASIASAL');
		$idlokasitujuan = $this->input->post('IDLOKASITUJUAN');
		$catatan        = $this->input->post('CATATAN');
		$tgltrans       = $this->input->post('TGLTRANS');

		if (count($a_detail)<1) die(json_encode(array('errorMsg' => 'Detail Transaksi Tidak Boleh Kosong')));

		if($idlokasiasal == $idlokasitujuan)
			die(json_encode(array('errorMsg' => 'Lokasi Asal dan Tujuan Harus Berbeda')));
		
		//cek_valid_data('MLOKASI', 'KODELOKASI', $lokasiasal, 'Lokasi Asal');
		//cek_valid_data('MLOKASI', 'KODELOKASI', $lokasitujuan, 'Lokasi Tujuan');

		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
			$setting = $this->model_master_config->getConfigAll('TTRANSFER','KODETRANSFER');
			if($setting->VALUE == "AUTO"){
				$row = $this->model_master_lokasi->getLokasi($idlokasiasal);
				$lokasi = $row->KODELOKASI;
				//custom filter
				$filter['lokasi'] = $lokasi;
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
			'IDPERUSAHAAN'     => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'KODETRANSFER'     => $kodetrans,
			'IDLOKASIASAL'     => $idlokasiasal,
			'IDLOKASITUJUAN'   => $idlokasitujuan,
			'IDCUSTOMER'       => $this->input->post('IDCUSTOMER')??0,
			'TGLTRANS'         => $tgltrans,
			'TGLKIRIM'         => $this->input->post('TGLKIRIM'),
			'TGLENTRY'         => date('Y.m.d'),
			'JAMENTRY'         => date('H:i:s'),
			'USERENTRY'        => $_SESSION[NAMAPROGRAM]['USERID'],
			'CATATAN'          => $catatan,
			'STATUS'           => 'S',
			'CLOSING'          => 0
		);
		
		$response = $this->model_inventori_transfer->simpan($id,$data_values,$a_detail,$edit);
		if ($response != '')
		{
			die(json_encode(array('errorMsg' => $response)));
		}
		

		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'TRANSFER PERSEDIAAN',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'ttransfer',
					'kode'  => 'kodetransfer'
				),
				array(
					'nama'  => 'detail',
					'tabel' => 'ttransferdtl',
					'kode'  => 'kodetransfer'
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}
	
	function batalTrans(){
		$idtrans = $_POST['idtrans'];
		$kodetrans = $_POST['kodetrans'];
		$alasan = $_POST['alasan'];
		$status    = $this->model_inventori_transfer->getStatusTrans($idtrans);

		if ($status=='P') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');

		$exe = $this->model_inventori_transfer->batal($idtrans,$alasan);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'TRANSFER PERSEDIAAN',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}

	function ubahStatusJadiSlip(){
		$idtrans = $this->input->post('idtrans');
		$kodetrans = $this->input->post('kodetrans');

		$msg = $this->model_inventori_transfer->ubahStatusJadiSlip($idtrans);
		if ($msg != '') { die(json_encode(array('errorMsg'=>$msg))); }

		log_history(
			$kodetrans,
			'TRANSFER PERSEDIAAN',
			'CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));	
	}

	function ubahStatusJadiInput(){
		$idtrans = $_POST['idtrans'];
		$kodetrans = $_POST['kodetrans'];
		$status    = $this->model_inventori_transfer->getStatusTrans($idtrans);

		if ($status=='P' or $status=='I') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');

		//CEK TRANSAKSI SUDAH DIGUNAKAN!!//
		//huruf kecil karena mengakses array berhuruf kecil!!
		//table parent yang akan dicek
		$table[0]          = 'tbbk';
		$table[1]          = 'tbbkdtl';
		$fieldname		   = 'kodebbk';
		$fieldcondition[0] = 'idbbk';
		$fieldcondition[1] = 'idtransreferensi';
		$jenistrans		   = 'TRANSFER';
		
		$response = cekTransaksiSudahDigunakan($idtrans,$fieldname,$table,$fieldcondition,$jenistrans);
		if ($response != ''){
			die(json_encode(array('errorMsg' => $response)));
		}
		//CEK TRANSAKSI SUDAH DIGUNAKAN!!//
		
		$exe = $this->model_inventori_transfer->ubahStatusJadiInput($idtrans);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'TRANSFER PERSEDIAAN',
			'BATAL CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}
	
	function getStatusTrans($idtrans=""){
		$idtrans = $this->input->post('idtrans');
		$status = $this->model_inventori_transfer->getStatusTrans($idtrans);
		echo json_encode(array('status' => $status));
	}
	
	function cetak($idTrans){
		$data['idtrans'] = $idTrans;
		//$data['namasubcustomer'] = $namaSubcustomer;
		
        $this->load->library('html_table');
		$this->load->view('reports/v_faktur_inventori_transfer_persediaan_daftar_harga.php', $data);
	}
	
	function cetakSuratJalan($idTrans){
		$data['idtrans'] = $idTrans;
		//$data['namasubcustomer'] = $namaSubcustomer;
		
        $this->load->library('html_table');
		$this->load->view('reports/v_faktur_inventori_transfer_persediaan_surat_jalan.php', $data);
	}
}
