<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends MY_Controller {
	public function __construct(){
		parent::__construct();
        $this->load->helper(['form', 'url']);
        $this->load->library(['upload','Spout']); 
		$this->load->model(['model_jual_penjualan','model_master_lokasi','model_master_customer','model_master_barang']);
	}
	
	public function index(){
		$kodeMenu = $this->input->get('kode');
		//$config['TRANSAKSISO'] = $this->model_master_config->getConfig('TPENJUALAN','TRANSAKSISO');
		// $config['TRANSAKSISO'] ='HEADER';
		$config['KODE'] = $this->model_master_config->getConfig('TPENJUALAN','KODEPENJUALAN');
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
		$response              = $this->model_jual_penjualan->comboGridBarang($pagination);
		
		echo json_encode($response);
    }
    
	public function dataGrid() {
		$filter = $this->setFilterGrid();
		
		if ($this->input->post('kodetrans')!='') {
			$filter['sql'] .= "and a.KODEPENJUALAN like ?";
			$filter['param'][] = '%'.$this->input->post('kodetrans').'%';
		}
		if ($this->input->post('kodebbk')!='') {
			$filter['sql'] .= "and a.KODEBBK like ?";
			$filter['param'][] = '%'.$this->input->post('kodebbk').'%';
		}
		if ($this->input->post('nama')!='') {
			$filter['sql'] .= "and NAMACUSTOMER like ?";
			$search = str_replace(' ','%',$this->input->post('nama'));
			$filter['param'][] = '%'.$search.'%';
		}
		if ($this->input->post('kota')!='') {
			$filter['sql'] .= "and c.KOTA like ?";
			$search = str_replace(' ','%',$this->input->post('kota'));
			$filter['param'][] = '%'.$search.'%';
		}
		$temp_tgl_aw = TGLAWALFILTER;
		$tgl_aw = $this->input->post('tglawal') =='' ? "and a.TGLTRANS>='$temp_tgl_aw'" : "and a.TGLTRANS>='".ubah_tgl_firebird($this->input->post('tglawal'))."'";
		$tgl_ak = $this->input->post('tglakhir')=='' ? "and a.TGLTRANS<='$temp_tgl_ak'" : "and a.TGLTRANS<='".ubah_tgl_firebird($this->input->post('tglakhir'))."'";
		$status = explode(",",$this->input->post('status'));
		$status = count($status)>0 ? "and (a.STATUS='".implode("' or a.STATUS='", $status)."')" : '';
		$filter['sql'] .= $tgl_aw;
		$filter['sql'] .= $tgl_ak;
		$filter['sql'] .= $status;
		
		$this->output->set_content_type('application/json');

		$response = $this->model_jual_penjualan->dataGrid($this->setPaginationGrid(), $filter);
		echo json_encode($response); 
	}

	public function loadDetail(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id')??"";
		$idcustomer = $this->input->post('idcustomer')??"";
		$kode = $this->input->post('kode')??"";
		$mode = $this->input->post('mode');
		
		if($mode == "ubah")
		{
			$response = $this->model_jual_penjualan->loadDataDetail($id,$kode,$idcustomer);
		}
		else
		{
			
		}

		echo json_encode($response);
	}
	
	public function loadDataRekap(){
		$idtrans = $this->input->post('idtrans');
		$query = $this->model_jual_penjualan->loadDataRekap($idtrans);
		$items = array();
		foreach($query as $rs){
			$items[] = array(
				'idbarang'   => $rs->IDBARANG,
				'kodebarang' => $rs->KODEBARANG,
				'namabarang' => $rs->NAMABARANG,
				'tutup'      => $rs->TUTUP,
				'jml'        => $rs->JML,
				'sisa'       => $rs->JML,
				'terpenuhi'  => 0,
				'satuan'     => $rs->SATUAN
			);
		}
		echo json_encode(array(
			'success' => true,
			'detail' => $items,
		));	
	}
	
	//untuk informasi pada form PO
	public function informasiRealisasiPenjualan(){
		$idtrans = $this->input->post('idtrans');
		$idbarang = $this->input->post('idbarang');
		$query = $this->model_jual_penjualan->informasiRealisasiPenjualan($idtrans,$idbarang);
		$items = array();
		foreach($query as $rs){
			$items[] = array(
				'kodepenjualan' => $rs->KODEPENJUALAN,
				'tgltrans'      => $rs->TGLTRANS,
				'jml'           => $rs->JML,
				'satuan'        => $rs->SATUAN,
				'userentry'     => $rs->USERENTRY
			);
		}
		echo json_encode($items);	
	}
		
	public function simpan(){
		$a_detail      = json_decode($_POST['data_detail']);
		$id            = $this->input->post('IDPENJUALAN');
		$kodetrans     = $this->input->post('KODEPENJUALAN');
		$idlokasi      = $this->input->post('IDLOKASI');
		//$lokasi        = $this->input->post('KODELOKASI');
		$idcustomer    = $this->input->post('IDCUSTOMER');
		//$idmarketing   = $this->input->post('IDMARKETING');
		$catatan       = $this->input->post('CATATAN');
		
		$tgltrans      = $this->input->post('TGLTRANS');
		$nofakturpajak = $this->input->post('NOFAKTURPAJAK') ?? NULL;
		
		
		
		if (count($a_detail)<1) die(json_encode(array('errorMsg' => 'Detail Transaksi Tidak Boleh Kosong')));
		//cek_data($a_detail, 'kodebarang', 'mbarang');
		
		//CEK TRANSAKSI SUDAH BERLANJUT!!//
		//huruf kecil karena mengakses array berhuruf kecil!!
		
		//table yang akan dicek
		$table[2]          = 'tpenjualan'; // untuk cek status
		$table[0]          = 'tpenjualandtl';//jika 1 to 1,isi tabel header
		$fieldname[0]      = 'kodepenjualan';
		$fieldcondition[0] = 'idpenjualan';
		
		//table detail yg dicek
		$table[1]          = 'tso';
		$table[3]          = 'tsodtl';
		$fieldname[1]      = 'kodeso';
		$fieldcondition[1] = 'idso';
		$fieldmessage      = "Kode SO";
		
		//$response = cekTransaksiSudahBerlanjutLite($id,$a_detail,$fieldname,$table,$fieldcondition,$fieldmessage);
		if ($response != ''){
			die(json_encode(array('errorMsg' => $response)));
		}
		//CEK TRANSAKSI SUDAH BERLANJUT!!//

		//cek_valid_data('MLOKASI', 'IDLOKASI', $idlokasi, 'Lokasi');
		//cek_valid_data('MCUSTOMER', 'IDCUSTOMER', $idcustomer, 'Customer');
		//cek_valid_data('MMARKETING', 'IDMARKETING', $idmarketing, 'Marketing');

		$mode = $this->input->post('mode');
		if ($mode=='tambah') {
			$row = $this->model_master_lokasi->getLokasi($idlokasi);
			$lokasi = $row->KODELOKASI;
			$setting = $this->model_master_config->getConfigAll('TPENJUALAN','KODEPENJUALAN');
			if($setting->VALUE == "AUTO"){
				//custom filter
				$filter['lokasi'] = $lokasi;
				$filter['tgltrans'] = $tgltrans;
				
				$kodetrans = autogen($setting,$filter);
            }
            
			if ($nofakturpajak != NULL){
				$response = $this->model_jual_penjualan->cek_valid_fakturpajak($nofakturpajak);
				if($response != ''){
					die(json_encode(array('errorMsg' => $response)));
				}
			}
			$edit = 0;
		}
		else{
			if ($nofakturpajak != NULL){
				$response = $this->model_jual_penjualan->cek_valid_fakturpajak($nofakturpajak,$id);
				if($response != ''){
					die(json_encode(array('errorMsg' => $response)));
				}
			}
			$edit = 1;
		}
		
		// query header
		$data_values = array (
			'IDPERUSAHAAN'    => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'KODEPENJUALAN'   => $kodetrans,
			'IDLOKASI'        => $idlokasi,
			'IDCUSTOMER'      => $idcustomer,
			'JENISTRANSAKSI'  => "OFFLINE",
			'KODETRANSREFERENSI' => "",
			'CATATANCUSTOMER' =>$this->input->post('CATATANCUSTOMER'),
			//'IDMARKETING'     => $idmarketing,
			//'IDBBK'           => $this->input->post('IDBBK',''),
			//'KODEBBK'         => $this->input->post('KODEBBK',''),
			//'TGLBBK'          => $this->input->post('TGLBBK',''),
			//'IDSO'            => $this->input->post('IDSO',''),
			//'KODESO'          => $this->input->post('KODESO',''),
			//'TGLSO'           => $this->input->post('TGLSO',''),
			//'IDDO'            => $this->input->post('IDDO',''),
			//'KODEDO'          => $this->input->post('KODEDO',''),
			//'TGLDO'           => $this->input->post('TGLDO',''),
			//'IDDOSEMENTARA'   => $this->input->post('IDDOSEMENTARA',''),
			//'KODEDOSEMENTARA' => $this->input->post('KODEDOSEMENTARA',''),
			//'TGLDOSEMENTARA'  => $this->input->post('TGLDOSEMENTARA',''),
			//'NOFAKTURPAJAK'   => $nofakturpajak,
			'TGLTRANS'        => $tgltrans,//$tgltrans
			//'IDSYARATBAYAR'   => $this->input->post('IDSYARATBAYAR'),
			//'TGLJATUHTEMPO'   => $this->input->post('TGLJATUHTEMPO'),
			'TGLENTRY'        => date('Y.m.d'),
			'JAMENTRY'        => date('H:i:s'),
			'USERENTRY'       => $_SESSION[NAMAPROGRAM]['USERID'],
			'TOTAL'           => $this->input->post('TOTAL'),
			'DPPLAINRP'       => $this->input->post('DPPLAINRP')??0,
			'PPNRP'           => $this->input->post('PPNRP'),
			'GRANDTOTAL'      => $this->input->post('GRANDTOTAL'),
			'POTONGANRP'      => $this->input->post('POTONGANRP')??0,
			'POTONGANPERSEN'  => $this->input->post('POTONGANPERSEN')??0,
			'GRANDTOTALDISKON'=> $this->input->post('GRANDTOTALDISKON'),
			'PEMBAYARAN'      => $this->input->post('PEMBAYARAN'),
			'CATATAN'         => $catatan,
			'STATUS'          => 'I',
			'CLOSING'         => 0
		);
	
		$response = $this->model_jual_penjualan->simpan($id,$data_values,$a_detail,$edit);
		if (!is_int($response)){
			die(json_encode(array('errorMsg' => is_int($response))));
		}

		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'PENJUALAN',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'tpenjualan',
					'kode'  => 'kodepenjualan'
				),
				array(
					'nama'  => 'detail',
					'tabel' => 'tpenjualandtl',
					'kode'  => 'kodepenjualan'
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);
		
		$exe = $this->model_jual_penjualan->ubahStatusJadiSlip($response);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
// 		$response = $this->model_jual_penjualan->getDataJual($kodetrans);
		echo json_encode(array('success' => true,'row'=>$response));
	}
	
	function batalTrans(){
		$idtrans   = $_POST['idtrans'];
		$kodetrans = $_POST['kodetrans'];
		$alasan    = $_POST['alasan'];
		$status    = $this->model_jual_penjualan->getStatusTrans($idtrans);

		if ($status=='P') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');

		$exe = $this->model_jual_penjualan->batal($idtrans,$alasan);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'PENJUALAN',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}
	
	function ubahStatusJadiInput(){
		$idtrans   = $_POST['idtrans'];
		$kodetrans = $_POST['kodetrans'];
		$status    = $this->model_jual_penjualan->getStatusTrans($idtrans);

		if ($status=='P' or $status=='I') die(json_encode(array('errorMsg' => 'Transaksi Tidak Dapat Dibatalkan')));
		//cek_periode(get_tgl_trans('tpo', 'kodepo', $kodetrans), 'hapus');
		
		$exe = $this->model_jual_penjualan->ubahStatusJadiInput($idtrans);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		// panggil fungsi untuk log history
		log_history(
			$kodetrans,
			'PENJUALAN',
			'BATAL CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);

		echo json_encode(array('success' => true));
	}
	function ubahStatusJadiSlip(){
		$idtrans = $this->input->post('idtrans');
		$kodetrans = $this->input->post('kodetrans');

		$exe = $this->model_jual_penjualan->ubahStatusJadiSlip($idtrans);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		echo json_encode(array('success' => true));

		log_history(
			$kodetrans,
			'PENJUALAN',
			'CETAK',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}
	
	function getStatusTrans($idtrans=""){
		$idtrans = $this->input->post('idtrans');
		$status = $this->model_jual_penjualan->getStatusTrans($idtrans);
		echo json_encode(array('status' => $status));
	}
	
	function cetak($idTrans){
		$data['idtrans'] = $idTrans;
		$data['cetakNPWP'] = "yes";
		//$data['namasubcustomer'] = $namaSubcustomer;
		$this->load->view('reports/v_faktur_jual_penjualan.php', $data);
	}
	
	function cetakKaratu($idTrans){
		$data['idtrans'] = $idTrans;
		$data['cetakNPWP'] = "yes";
		//$data['namasubcustomer'] = $namaSubcustomer;
		$this->load->view('reports/v_faktur_jual_penjualan_karatu.php', $data);
	}
	
	function cetakPajak($idTrans){
		$data['idtrans'] = $idTrans;
		$data['cetakNPWP'] = "yes";
		//$data['namasubcustomer'] = $namaSubcustomer;
		$this->load->view('reports/v_faktur_jual_penjualan_pajak.php', $data);
	}

	function cetakNoNPWP($idTrans){
		$data['idtrans'] = $idTrans;
		$data['cetakNPWP'] = "no";
		//$data['namasubcustomer'] = $namaSubcustomer;
		$this->load->view('reports/v_faktur_jual_penjualan.php', $data);
	}
	
	function dashboardGrandTotal(){
		$this->output->set_content_type('application/json');
		$response = $this->model_jual_penjualan->dashboardGrandTotal($this->input->post('periode'),$this->input->post('tglawal'),$this->input->post('tglakhir'),$this->input->post('customer')??'0',$this->input->post('lokasi')??'');
		
		echo json_encode($response);
	}
	
	function dashboardItem(){
		$this->output->set_content_type('application/json');
		$response = $this->model_jual_penjualan->dashboardItem($this->input->post('periode'),$this->input->post('tglawal'),$this->input->post('tglakhir'),$this->input->post('customer')??'0',$this->input->post('lokasi')??'',$this->input->post('kategoriWarna')??'',$this->input->post('kategoriSize')??'');
		
		echo json_encode($response);
	}
	
	function dashboardCustomer(){
		$this->output->set_content_type('application/json');
		$response = $this->model_jual_penjualan->dashboardCustomer($this->input->post('periode'),$this->input->post('tglawal'),$this->input->post('tglakhir'),$this->input->post('barang')??'0',$this->input->post('customer')??'0');
		
		echo json_encode($response);
	}
	
	function dashboardStok(){
		$this->output->set_content_type('application/json');
		$response = $this->model_jual_penjualan->dashboardStok($this->input->post('lokasi')??'0',$this->input->post('limitStok')??'0');
		
		echo json_encode($response);
	}
		
	public function importExcel() {
        // Configure the upload settings
        $config['upload_path'] = './assets/excelData/';
        $config['allowed_types'] = '*'; // Allow only Excel files
        $config['max_size'] = 10024; // Max file size (in KB)
		
        $this->upload->initialize($config);
        if ($this->upload->do_upload('excelFile')) {
            // Get the uploaded file data
            $fileData = $this->upload->data();
            $filePath = $fileData['full_path'];

            // Read the uploaded Excel file
            $data = $this->readExcelFile($filePath);
            unlink($filePath);
            
            
            $idlokasi = $this->model_master_lokasi->getLokasiOnline()->IDLOKASI;

            // Output the data (for testing purposes)
            foreach($data as $item)
            {
                $customer = $this->insertCustomer($item);

                $a_detail      = $item['DATADETAIL'];
        		$idlokasi      = $idlokasi;
        		$catatan       = "";
        		$tgltrans      = $item['TGLTRANS'];
        		
        		if (count($a_detail)<1) die(json_encode(array('errorMsg' => 'Detail Transaksi Tidak Boleh Kosong')));
        		
        		$penjualan = $this->model_jual_penjualan->getDataJualOnline($item['KODETRANSREFERENSI']);
        		$status = "";
        		if($penjualan->ADA)
        		{ 
        		    $id = $penjualan->IDPENJUALAN;
        		    $kodetrans = $penjualan->KODEPENJUALAN;
        		    $mode = "ubah";
        		    $status = $penjualan->STATUS;
        		}
        		else
        		{
        		    $mode = "tambah";
        		}
        		
        		if($status != $item['STATUS'])
        		{
            		if ($mode=='tambah') {
            			$row = $this->model_master_lokasi->getLokasi(4); // PABRIK
            			$lokasi = $row->KODELOKASI;
            			$setting = $this->model_master_config->getConfigAll('TPENJUALAN','KODEPENJUALAN');
            			if($setting->VALUE == "AUTO"){
            				//custom filter
            				$filter['lokasi'] = $lokasi;
            				$filter['tgltrans'] = $tgltrans;
            				
            				$kodetrans = autogen($setting,$filter);
                        }
                        
            			if ($nofakturpajak != NULL){
            				$response = $this->model_jual_penjualan->cek_valid_fakturpajak($nofakturpajak);
            				if($response != ''){
            					die(json_encode(array('errorMsg' => $response)));
            				}
            			}
            			$edit = 0;
            		}
            		else{
            			$edit = 1;
            		}
            		
            		// query header
            		$data_values = array (
            			'IDPERUSAHAAN'    => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
            			'KODEPENJUALAN'   => $kodetrans,
            			'IDLOKASI'        => $idlokasi,
            			'IDCUSTOMER'      => $customer['IDCUSTOMER'],
            			'JENISTRANSAKSI'  => "ONLINE",
            			'KODETRANSREFERENSI' => $item['KODETRANSREFERENSI'],
            			'CATATANCUSTOMER' => '',
            			'TGLTRANS'        => $tgltrans,//$tgltrans
            			'TGLENTRY'        => $item['TGLENTRY'],
            			'JAMENTRY'        => $item['JAMENTRY'],
            			'USERENTRY'       => $item['USERENTRY'],
            			'TOTAL'           => $item['TOTAL'],
            			'PPNRP'           => $item['PPNRP'],
            			'GRANDTOTAL'      => $item['GRANDTOTAL'],
            			'POTONGANRP'      => $item['POTONGANRP'],
            			'POTONGANPERSEN'  => $item['POTONGANPERSEN'],
            			'GRANDTOTALDISKON'=> $item['GRANDTOTALDISKON'],
            			'PEMBAYARAN'      => $item['PEMBAYARAN'],
            			'CATATAN'         => '',
            			'STATUS'          => $item['STATUS'],
            			'CLOSING'         => 0
            		);
            		
            	    $a_detail = json_decode(json_encode($a_detail));
            	    
            		foreach($a_detail as $itemBarang)
            		{
            		    $barang = $this->model_master_barang->getDataBarangBySKU($itemBarang->sku);
                        //GET DATA BARANG
                        $itemBarang->namabarang = $barang->NAMABARANG;
                        $itemBarang->idbarang = $barang->IDBARANG;
            		}
            	    
            		$response = $this->model_jual_penjualan->simpan($id,$data_values,$a_detail,$edit);
            		if ($response != ''){
            			die(json_encode(array('errorMsg' => $response)));
            		}
            
            		// panggil fungsi untuk log history
            		log_history(
            			$kodetrans,
            			'PENJUALAN ONLINE',
            			$mode,
            			array(
            				array(
            					'nama'  => 'header',
            					'tabel' => 'tpenjualan',
            					'kode'  => 'kodepenjualan'
            				),
            				array(
            					'nama'  => 'detail',
            					'tabel' => 'tpenjualandtl',
            					'kode'  => 'kodepenjualan'
            				),
            			),
            			$_SESSION[NAMAPROGRAM]['USERID']
            		);
            		
            		if($data_values['STATUS'] == 'S')
            		{
            		    $response = $this->model_jual_penjualan->getDataJual($kodetrans);
            		    $exe = $this->model_jual_penjualan->ubahStatusJadiSlip($response->IDPENJUALAN);
                		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
                
                		log_history(
                			$response->KODEPENJUALAN,
                			'PENJUALAN ONLINE',
                			'CETAK',
                			[],
                			$_SESSION[NAMAPROGRAM]['USERID']
                		);
            		}
            		else if($data_values['STATUS'] == 'D')
            		{
            		    $response = $this->model_jual_penjualan->getDataJual($kodetrans);
                		$exe = $this->model_jual_penjualan->batal($response->IDPENJUALAN,"BATAL TRANSAKSI ONLINE");
                		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }

                        log_history(
                            $response->KODEPENJUALAN,
                			'PENJUALAN ONLINE',
                			'HAPUS',
                			[],
                			$_SESSION[NAMAPROGRAM]['USERID']
                		);            		    
            		}
        		}
		
            }
            echo '<script>window.history.back();</script>';
        } else {
            // Upload failed, display error
            echo $this->upload->display_errors();
        }
    }
    
    public function insertCustomer($item){
        $customer = $this->model_master_customer->checkCustomerByAddress($item);
        $idcustomer = $customer->IDCUSTOMER;
        if(!$customer->ADA)
        {
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
    		
    		$data_values = array (
    			'IDPERUSAHAAN'    => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
    			'KODECUSTOMER'    => $kode,
    			'NAMACUSTOMER'    => $item['NAMACUSTOMER'],
    			'ALAMAT'          => $item['ALAMATCUSTOMER'],
    			'KOTA'            => $item['KOTACUSTOMER'],
    			'PROVINSI'        => $item['PROVINSICUSTOMER'],
    			'NEGARA'          => $item['NEGARACUSTOMER'],
    			'TELP'            => $item['TELPCUSTOMER'],
    			'EMAIL'           => $item['EMAILCUSTOMER'],
    			'NAMABANK'        => "",
    			'NOREKENING'      => "",
    			'CATATAN'         => "",
    			'USERENTRY'       => $_SESSION[NAMAPROGRAM]['USERID'],
    			'TGLENTRY'        => date("Y-m-d"),
    			'DISKONMEMBER'    => 0,
    			'MEMBER'          => 0,
    			'KONSINYASI'      => 0,
    			'STATUS'          => 1
    		);
    		
    		
    		$response = $this->model_master_customer->simpan($id,$data_values,$edit);
    		if (!is_integer($response)){
    			// generate an error... or use the log_message() function to log your error
    		    return $response;
    		}
    		
    		$idcustomer = $response;
    		
    		// panggil fungsi untuk log history
    		log_history(
    			$kode,
    			'MASTER CUSTOMER ONLINE',
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
        }

		return [
		    'IDCUSTOMER' => $idcustomer,
		    'NAMACUSTOMER' => $item['NAMACUSTOMER']
		];
    }
    
     // Method untuk membaca file Excel
    public function readExcelFile($filePath)
    {
        $data = $this->spout->readXlsxPenjualan($filePath);
        return $data;
    }

    // Method untuk menulis data ke file Excel
    public function writeExcelFile()
    {
        $data = [
            ['Name', 'Email', 'Age'],
            ['John Doe', 'johndoe@example.com', 28],
            ['Jane Smith', 'janesmith@example.com', 22],
        ];

        $filePath = FCPATH . 'uploads/output.xlsx'; // Path untuk menyimpan file Excel
        $this->spout->writeXlsx($filePath, $data);

        echo "File Excel berhasil dibuat!";
    }
}
