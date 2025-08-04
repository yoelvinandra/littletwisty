<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['model_inventori_pembelian','model_master_lokasi']);
	}
	
	public function index(){
		$kodeMenu = $this->input->get('kode');
		// $config['TRANSAKSIPO'] = "HEADER";
		$config['KODE'] = $this->model_master_config->getConfig('TBELI','KODEBELI');
		$config['INPUTHARGA'] = $this->model_master_config->getAkses($kodeMenu)["INPUTHARGA"];
		$config['LIHATHARGA'] = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];
		$config['PPNPERSEN'] = $this->model_master_config->getPPN();
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu,$config);
	}
	
	public function comboGridBarang($idtrans = ""){
		$this->output->set_content_type('application/json');
		$pagination            = $this->setPaginationGrid();
		$pagination['idtrans'] = $idtrans;
		$response              = $this->model_inventori_pembelian->comboGridBarang($pagination);
		
		echo json_encode($response);
	}
	
	public function dataGrid() {
		//$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'kodebeli';
		//$data_param = array($_SESSION['KODELOKASI']);

		$filter = $this->setFilterGrid();
		
		if ($this->input->post('kodetrans')!='') {
			$filter['sql'] .= " and a.KODEBELI like ? ";
			$filter['param'][] = '%'.$this->input->post('kodetrans').'%';
		}
		if ($this->input->post('nama')!='') {
			$filter['sql'] .= " and c.NAMASUPPLIER like ? ";
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

		$response = $this->model_inventori_pembelian->dataGrid($this->setPaginationGrid(), $filter);
		echo json_encode($response); 
	}
	public function loadHeader(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$mode = $this->input->post('mode');
		
		$response = $this->model_inventori_pembelian->loadDataHeader($id);

		echo json_encode($response);
	}

	public function loadDetail(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');
		$mode = $this->input->post('mode');
		
		$response = $this->model_inventori_pembelian->loadDataDetail($id,$kode);

		echo json_encode($response);
	}
	
	public function loadData(){
		$idtrans = $this->input->post('idtrans');
		$query = $this->model_inventori_pembelian->loadData($idtrans);
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
                'idbeli'          	 => $rs->IDBELI,
				'kodebeli'           => $rs->KODEBELI,
				'idpo'               => $rs->IDPO,
				'kodepo'             => $rs->KODEPO,
				'kodetransreferensi' => $rs->KODETRANSREFERENSI,
				'adanpwp'            => $rs->ADANPWP,
				'idbarang'           => $rs->IDBARANG,
				'kodebarang'         => $rs->KODEBARANG,
                'namabarang'         => $rs->NAMABARANG,
                'jmlpo'            	 => $rs->JMLPO,
				'terpenuhipo'        => $rs->TERPENUHIPO,
				'sisapo'             => $rs->SISAPO,
				'jmlbeli'            => $rs->JML,
				'jmlreturbeli'       => 0,
				'jmlbonus'           => $rs->JMLBONUS,
				'satuan'             => $rs->SATUAN,
				'satuanutama'        => $rs->SATUANUTAMA,
				'konversi'           => $rs->KONVERSI,
				'idcurrency'         => $rs->IDCURRENCY,
				'currency'           => $rs->SIMBOL,
				'harga'              => $rs->HARGA,
				'discpersen'         => $rs->DISCPERSEN,
				'disc'               => $rs->DISC,
				'disckurs'           => $rs->DISCKURS,
				'subtotal'           => $rs->SUBTOTAL,
				'nilaikurs'          => $rs->NILAIKURS,
				'hargakurs'          => $rs->HARGAKURS,
				'subtotalkurs'       => $rs->SUBTOTALKURS,
				'pakaippn'           => $pakaippn,
				'ppnpersen'          => $rs->PPNPERSEN,
				'ppnrp'              => $rs->PPNRP,
				'dpp'                => $dpp,
				'pph22persen'        => $rs->PPH22PERSEN,
				'pph22rp'            => $rs->PPH22RP,
				'akunbiaya'          => $rs->AKUNBIAYA,
				'idakunbiaya'        => $rs->IDAKUNBIAYA,
				'akunhutang'         => $rs->AKUNHUTANG,
				'idakunhutang'       => $rs->IDAKUNHUTANG,
				'detaillo'           => $detail
			);
		}
		echo json_encode(array(
			'success' => true,
			'detail' => $items,
		));	
	}
    
    //Rekap dan BBM tidak dipakai
	public function loadDataRekap(){
		$idtrans = $this->input->post('idtrans');
		$query = $this->model_inventori_pembelian->loadDataRekap($idtrans);
		$items = array();
		foreach($query as $rs){
			$items[] = array(
				'idbarang'   => $rs->IDBARANG,
				'kodebarang' => $rs->KODEBARANG,
				'namabarang' => $rs->NAMABARANG,
				'tutup'      => $rs->TUTUP,
				'jml'        => $rs->JML,
				'jmlbonus'   => $rs->JMLBONUS,
				'sisa'       => $rs->SISA,
				'terpenuhi'  => $rs->TERPENUHI,
				'satuan'     => $rs->SATUAN
			);
		}
		echo json_encode(array(
			'success' => true,
			'detail' => $items,
		));	
	}
		
	public function simpan(){
		$a_detail  = json_decode($_POST['data_detail']);
		
		$id         = $this->input->post('IDBELI');
		$kodetrans  = $this->input->post('KODEBELI');
		$idpo       = $this->input->post('IDPO');
		$kodepo     = $this->input->post('KODEPO');
		$idlokasi   = $this->input->post('IDLOKASI');
		$lokasi     = $this->input->post('KODELOKASI');
		$idsupplier = $this->input->post('IDSUPPLIER');
		$catatan    = $this->input->post('CATATAN');
		$tgltrans   = $this->input->post('TGLTRANS');
		
		if($idpo == null)
		{
			$idpo = 0;
			$kodepo = "-";
			
		}
		if (count($a_detail)<1) die(json_encode(array('errorMsg' => 'Detail Transaksi Tidak Boleh Kosong')));
		
		//cek_data($a_detail, 'kodebarang', 'mbarang');
		//CEK TRANSAKSI SUDAH BERLANJUT!!//
		//huruf kecil karena mengakses array berhuruf kecil!!
		//table yang akan dicek
		$table[2]          = 'tbeli'; // untuk cek status
		$table[0]          = 'tbelidtl';//jika 1 to 1,isi tabel header
		$fieldname[0]      = 'kodebeli';
		$fieldcondition[0] = 'idbeli';
		
		//table detail yg dicek
		$table[1]          = 'tpo';
		$table[3]          = 'tpodtl';
		$fieldname[1]      = 'kodepo';
		$fieldcondition[1] = 'idpo';
		$fieldmessage      = "Kode PO";
		
		//$response = cekTransaksiSudahBerlanjutLite($id,$a_detail,$fieldname,$table,$fieldcondition,$fieldmessage);
		if ($response != ''){
            die(json_encode(array('errorMsg' => $response)));
		}
		//CEK TRANSAKSI SUDAH BERLANJUT!!//

		//cek_valid_data('MLOKASI', 'IDLOKASI', $idlokasi, 'Lokasi');
       // cek_valid_data('MSUPPLIER', 'IDSUPPLIER', $idsupplier, 'Supplier');
        //if($idpo && $idpo != 0)cek_valid_data('TPO', 'IDPO', $idpo, 'PO');

		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
			$setting = $this->model_master_config->getConfigAll('TBELI','KODEBELI');
			if($setting->VALUE == "AUTO"){
				$row = $this->model_master_lokasi->getLokasi($idlokasi);
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
			'IDPERUSAHAAN'       => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'IDLOKASI'           => $idlokasi,
			'KODEBELI'           => $kodetrans, 
			'IDPO'	             => $idpo,
			'KODEPO'	         => $kodepo,
			//'NOINVOICESUPPLIER'  => $this->input->post('NOINVOICESUPPLIER'),
			'IDSUPPLIER'         => $idsupplier,
			'TGLTRANS'           => $tgltrans,
			'IDSYARATBAYAR'      => $this->input->post('IDSYARATBAYAR'),
			'TGLJATUHTEMPO'      => $this->input->post('TGLJATUHTEMPO'),
			'TGLENTRY'           => date('Y.m.d'),
			'JAMENTRY'           => date('H:i:s'),
			'USERENTRY'          => $_SESSION[NAMAPROGRAM]['USERID']??$userid,
			'TOTAL'              => $this->input->post('TOTAL'),
			'PPNRP'              => $this->input->post('PPNRP'),
			'PPH22RP'            => $this->input->post('PPH22RP'),
			'PEMBULATAN'         => $this->input->post('PEMBULATAN'),
			'GRANDTOTAL'         => $this->input->post('GRANDTOTAL'),
			'CATATAN'            => $catatan, 
			'STATUS'             => 'S',
			'CLOSING'            => 0
		);
		
		$response = $this->model_inventori_pembelian->simpan($id,$data_values,$a_detail,$edit);
		if ($response != ''){
			die(json_encode(array('errorMsg' => $response)));
		}

		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'PEMBELIAN',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'tbeli',
					'kode'  => 'KODEBELI'
				),
				array(
					'nama'  => 'detail',
					'tabel' => 'tbelidtl',
					'kode'  => 'KODEBELI'
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
		$status    = $this->model_inventori_pembelian->getStatusTrans($idtrans);

		if ($status=='P') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');

		$exe = $this->model_inventori_pembelian->batal($idtrans,$alasan);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'PEMBELIAN',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}
	
	function ubahStatusJadiInput(){
		$idtrans   = $_POST['idtrans'];
		$kodetrans = $_POST['kodetrans'];
		$status    = $this->model_inventori_pembelian->getStatusTrans($idtrans);

		if ($status=='P' or $status=='I') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');

		$exe = $this->model_inventori_pembelian->ubahStatusJadiInput($idtrans);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'PEMBELIAN',
			'BATAL CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}
	function ubahStatusJadiSlip(){
		$idtrans   = $this->input->post('idtrans');
		$kodetrans = $this->input->post('kodetrans');

		$exe = $this->model_inventori_pembelian->ubahStatusJadiSlip($idtrans);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		echo json_encode(array('success' => true));

		log_history(
			$kodetrans,
			'PEMBELIAN',
			'CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}
	
	function getStatusTrans($idtrans=""){
		$idtrans = $this->input->post('idtrans');
		$status = $this->model_inventori_pembelian->getStatusTrans($idtrans);
		echo json_encode(array('status' => $status));
    }
    
	function cetak($idTrans){
		$data['idtrans'] = $idTrans;
		//$data['namasubcustomer'] = $namaSubcustomer;
		$this->load->view('reports/v_faktur_beli_pembelian.php', $data);
	}
}
