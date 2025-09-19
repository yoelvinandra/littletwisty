<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PesananPembelian extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(['model_inventori_pesananbeli','model_master_lokasi']);
	}
	
	public function index(){
		$kodeMenu              = $this->input->get('kode');
		$config['TRANSAKSIPR'] = $this->model_master_config->getConfig('TPO','TRANSAKSIPR');
		$config['KODE']        = $this->model_master_config->getConfig('TPO','KODEPO');
		$config['INPUTHARGA']  = $this->model_master_config->getAkses($kodeMenu)["INPUTHARGA"];
		$config['LIHATHARGA']  = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];
		$config['PPNPERSEN']   = $this->model_master_config->getPPN();
		
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu,$config);
	}
	
	public function comboGridBarang($idtrans = ''){
		$this->output->set_content_type('application/json');
		$response = $this->model_inventori_pesananbeli->comboGridBarang($this->setPaginationGrid(),$idtrans);
		echo json_encode($response);
	}
	
	public function comboGridTransaksi(){
		$this->output->set_content_type('application/json');
		$status = $this->input->post('status');
		$response = $this->model_inventori_pesananbeli->comboGridTransaksi($status);
		echo json_encode($response);
	}
	
	public function comboGridBBM($lokasi='',$referensi = '') {
		$this->output->set_content_type('application/json');
		
		$pagination              = $this->setPaginationGrid();
		$pagination['lokasi']    = $lokasi;
		$pagination['referensi'] = $referensi;
		$response                = $this->model_inventori_pesananbeli->comboGridBBM($pagination);
		echo json_encode($response);
	}

	public function comboGridforKas($idSupplier = ''){
		$this->output->set_content_type('application/json');
		$response = $this->model_inventori_pesananbeli->comboGridforKas($this->setPaginationGrid(), $idSupplier);
		
		echo json_encode($response);
	}
	
	public function comboGridFilter($lokasi = '%') {
		$this->output->set_content_type('application/json');
		
		$pagination = $this->setPaginationGrid();
		$pagination['lokasi'] = $lokasi;
		$response = $this->model_inventori_pesananbeli->comboGridFilter($pagination);
		echo json_encode($response);
	}
	
	public function dataGrid() {
		//$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'kodebeli';
		//$data_param = array($_SESSION['KODELOKASI']);
		$filter = $this->setFilterGrid();
		
		if ($this->input->post('kodetrans')!='') {
			$filter['sql'] .= "and a.KODEPO like ?";
			$filter['param'][] = '%'.$this->input->post('kodetrans').'%';
		}
		if ($this->input->post('nama')!='') {
			$filter['sql'] .= "and c.NAMASUPPLIER like ?";
			$search = str_replace(' ','%',$this->input->post('nama'));
			$filter['param'][] = '%'.$search.'%';
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

		$response = $this->model_inventori_pesananbeli->dataGrid($this->setPaginationGrid(), $filter);
		echo json_encode($response); 
	}
    
	public function loadHeader(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$mode = $this->input->post('mode');
		
		$response = $this->model_inventori_pesananbeli->loadDataHeader($id);

		echo json_encode($response);
	}

	public function loadDetail(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');
		$mode = $this->input->post('mode');
		
		$response = $this->model_inventori_pesananbeli->loadDataDetail($id,$kode);

		echo json_encode($response);
	}
	
	public function checkSisa(){
		$this->output->set_content_type('application/json');
		$idpo = $this->input->post('idpo');
		$idbarang = $this->input->post('idbarang');
		
		$response = $this->model_inventori_pesananbeli->checkSisa($idpo,$idbarang);

		echo json_encode($response);
	}
	
	public function loadDetailSisa(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');
		$mode = $this->input->post('mode');
		
		$response = $this->model_inventori_pesananbeli->loadDataDetailSisa($id,$kode);

		echo json_encode($response);
	}
	
    public function loadDataDetail(){
		$idtrans = $this->input->post('idtrans');
		$query = $this->model_inventori_pesananbeli->loadDataDetail($idtrans);
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
				'idpo'		    	 => $rs->IDPO,
				'kodepo'		     => $rs->KODEPO,
				'idbarang'		     => $rs->IDBARANG,
				'kodebarang'	     => $rs->KODEBARANG,
				'namabarang' 	     => $rs->NAMABARANG,
				'jmlpo' 		     => $rs->JML,
				'terpenuhipo' 		 => $rs->TERPENUHI,
				'sisapo' 		     => 0,
				'jmlbeli' 		     => $rs->SISA,
				'jmlbonus' 		     => 0,
				'satuan' 		     => $rs->SATUAN,
				'konversi' 		     => $rs->KONVERSI,
				'idcurrency' 	     => $rs->IDCURRENCY,
				'currency' 		     => $rs->SIMBOL,
				'harga'	 		     => $rs->HARGA,
				'discpersen'	 	 => $rs->DISCPERSEN,
				'disc'	 		     => $rs->DISC,
				'disckurs'	 	     => $rs->DISCKURS,
				'subtotal' 		     => $rs->SUBTOTAL,
				'nilaikurs' 	     => $rs->NILAIKURS,
				'hargakurs'		     => $rs->HARGAKURS,
				'subtotalkurs' 	     => $rs->SUBTOTALKURS,
				'pakaippn' 	         => $pakaippn,
				'ppnpersen' 	     => $rs->PPNPERSEN,
				'ppnrp' 	         => $rs->PPNRP,
				'dpp'			     => $dpp
			);
		}
		echo json_encode(array(
			'success' => true,
			'detail' => $items,
		));	
	}
	
	public function loadDataPembayaran(){
		$idtrans = $this->input->post('idtrans');
		$query = $this->model_inventori_pesananbeli->loadDataPembayaran($idtrans);
		$items = array();
		foreach($query as $rs){
			$items[] = array(
				'tglpembayaran' => $rs->TGLPEMBAYARAN,
				'amount'        => $rs->AMOUNT
			);
		}
		echo json_encode(array(
			'success' => true,
			'detail' => $items,
		));	
	}
	
	public function InformasiPR(){
		$idtrans = $this->input->post('idtrans');
		$idbarang = $this->input->post('idbarang');
		$query = $this->model_inventori_pesananbeli->informasiPR($idtrans,$idbarang);
		$items = array();
		foreach($query as $rs){
			$items[] = array(
				'kodepo'       => $rs->KODEPO,
				'tgltrans'     => $rs->TGLTRANS,
				'kodesupplier' => $rs->KODESUPPLIER,
				'namasupplier' => $rs->NAMASUPPLIER,
				'jml'          => $rs->JML,
				'satuan'       => $rs->SATUAN,
				'userentry'    => $rs->USERENTRY
			);
		}
		echo json_encode($items);	
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
		//$a_detail_pembayaran  = json_decode($_POST['data_detail_pembayaran']);

		$id    			 = $this->input->post('IDPO');
		$kodetrans    	 = $this->input->post('KODEPO');
		$idlokasi    	 = $this->input->post('IDLOKASI');
		$idsupplier    	 = $this->input->post('IDSUPPLIER');
		$catatan         = $this->input->post('CATATAN');
		$tgltrans        = $this->input->post('TGLTRANS');

		if (count($a_detail)<1) die(json_encode(array('errorMsg' => 'Detail Transaksi Tidak Boleh Kosong')));
		
		//cek_data($a_detail, 'kodebarang', 'mbarang');		
		
		//cek_valid_data('MLOKASI', 'IDLOKASI', $idlokasi, 'Lokasi');
		//cek_valid_data('MSUPPLIER', 'IDSUPPLIER', $idsupplier, 'Supplier');

		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
			$row = $this->model_master_lokasi->getLokasi($idlokasi);
			$lokasi = $row->KODELOKASI;
			$setting = $this->model_master_config->getConfigAll('TPO','KODEPO');
			if($setting->VALUE == "AUTO"){
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
			'KODEPO'           => $kodetrans, 
			'IDLOKASI'         => $idlokasi,
            'NOPOMANUAL'       => $this->input->post('NOPOMANUAL',''),
			'IDSUPPLIER'       => $idsupplier,
			'ADANPWP'     	   => $this->input->post('ADANPWP',0),
			'TGLTRANS'         => $tgltrans,
			'TGLENTRY'         => date('Y.m.d'),
			'JAMENTRY'         => date('H:i:s'),
			'USERENTRY'        => $_SESSION[NAMAPROGRAM]['USERID'],
			'IDSYARATBAYAR'    => $this->input->post('IDSYARATBAYAR'),
			'TGLJATUHTEMPO'    => $this->input->post('TGLJATUHTEMPO'),
			'TOTAL'            => $this->input->post('TOTAL'),
			'PPNRP'            => $this->input->post('PPNRP'),
			'PPH22RP'          => $this->input->post('PPH22RP'),
			'PEMBULATAN'       => $this->input->post('PEMBULATAN'),
			'GRANDTOTAL'       => $this->input->post('GRANDTOTAL'),
			'CATATAN'          =>$catatan, 
			'STATUS'           =>'S',
			'CLOSING'          =>0
		);
		
		$response = $this->model_inventori_pesananbeli->simpan($id,$data_values,$a_detail,$a_detail_pembayaran,$edit);
		if ($response != ''){
			die(json_encode(array('errorMsg' => $response)));
		}
		
		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'PESANAN PEMBELIAN',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'tpo',
					'kode'  => 'KODEPO'
				),
				array(
					'nama'  => 'detail',
					'tabel' => 'tpodtl',
					'kode'  => 'KODEPO'
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
		$status    = $this->model_inventori_pesananbeli->getStatusTrans($idtrans);

		if ($status=='P') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');

		$exe = $this->model_inventori_pesananbeli->batal($idtrans,$alasan);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'PESANAN PEMBELIAN',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}
	
	function ubahStatusJadiInput(){
		$idtrans = $_POST['idtrans'];
		$kodetrans = $_POST['kodetrans'];
		$status    = $this->model_inventori_pesananbeli->getStatusTrans($idtrans);

		if ($status=='P' or $status=='I') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');

		//CEK TRANSAKSI SUDAH DIGUNAKAN!!//
		//huruf kecil karena mengakses array berhuruf kecil!!
		
		//table parent yang akan dicek
		$table[0]          = 'tbeli'; // untuk cek status
		$table[1]          = 'tbelidtl';//jika 1 to 1,isi tabel header
		$fieldname		   = 'kodebeli';
		$fieldcondition[0] = 'idbeli';
		$fieldcondition[1] = 'idbbm';
		
		$response = cekTransaksiSudahDigunakan($idtrans,$fieldname,$table,$fieldcondition);
		if ($response != ''){
			die(json_encode(array('errorMsg' => $response)));
		}
		//CEK TRANSAKSI SUDAH DIGUNAKAN!!//
		
		$exe = $this->model_inventori_pesananbeli->ubahStatusJadiInput($idtrans);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'PESANAN PEMBELIAN',
			'BATAL CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}
	function ubahStatusJadiSlip(){
		$idtrans = $this->input->post('idtrans');
		$kodetrans = $this->input->post('kodetrans');

		$exe = $this->model_inventori_pesananbeli->ubahStatusJadiSlip($idtrans);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		echo json_encode(array('success' => true));

		log_history(
			$kodetrans,
			'PESANAN PEMBELIAN',
			'CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}
	
	function getStatusTrans($idtrans=""){
		$idtrans = $this->input->post('idtrans');
		$status = $this->model_inventori_pesananbeli->getStatusTrans($idtrans);
		echo json_encode(array('status' => $status));
	}
	
	function cetak($idTrans){
		$data['idtrans'] = $idTrans;
		//$data['namasubcustomer'] = $namaSubcustomer;
		$this->load->view('reports/v_faktur_beli_pemesanan.php', $data);
	}
	
	function checkPOBelumTutup($idbarang){
	    $response['rows']   = $this->model_inventori_pesananbeli->checkPOBelumTutup($idbarang);
		$response['count'] = count($response['rows']);
	    echo json_encode($response);
	}
	
	function tutupPO(){
	   	$a_detail  = json_decode($_POST['dataTutupPO']);

		$exe = $this->model_inventori_pesananbeli->tutupPO($a_detail);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		echo json_encode(array('success' => true));
	}
}
