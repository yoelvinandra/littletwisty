<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends MY_Controller {
	public function __construct(){
		parent::__construct();
        $this->load->helper(['form', 'url']);
        $this->load->library(['upload','Spout']); 
		$this->load->model(['model_master_barang']);
	}
	
	public function index(){
		$kodeMenu = $this->input->get('kode');
		$config['KODE'] = $this->model_master_config->getConfig('MBARANG','KODEBARANG');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu, $config);
	}
	
	public function satuanBarang($idbarang){
		$pagination = $this->setPaginationGrid();
		$pagination['idbarang'] = $idbarang;
		$query = $this->model_master_barang->satuanBarang($pagination);
		$rows = array();
		foreach($query as $rs){
			if($rs->SATUAN<>'')
			$rows[] = $rs;
		}
		echo json_encode($rows);
	}
	
	public function hargaBeliTerakhir(){
		$idbarang = $this->input->post('idbarang');
		$query = $this->model_master_barang->hargaBeliTerakhir($idbarang);
		$hargabeli = "";
		if(!$query->HARGABELI){
			$hargabeli = $this->model_master_barang->hargaBeliAwal($idbarang);
		}else{
			$hargabeli = $query->HARGABELI;
		}
		echo json_encode($hargabeli);
	}
	
	public function hargaJualTerakhir(){
		$idbarang   = $this->input->post('idbarang');
		$idcustomer = $this->input->post('idcustomer');
		$query      = $this->model_master_barang->hargaJualTerakhir($idbarang,$idcustomer);
		echo json_encode($query->HARGAJUAL??0);
	}
	
	public function hargaBarang(){
		$idbarang   = $this->input->post('idbarang');
		$idsupp     = $this->input->post('idsupp','');
		$idcustomer = $this->input->post('idcustomer','');
		$tgltrans   = $this->input->post('tgltrans');
		//cek di mhargabeli dulu jika ada post idsupp
		if($idsupp != ''){
			$query = $this->model_master_barang->hargaBeliBarang($idbarang,$idsupp,$tgltrans);
			if($query->HARGA || $query->HARGA == ''){
				//jika tidak ada, cek di mbarang
				$query = $this->model_master_barang->hargaBarang($idbarang);
				if($query->HARGABELI == null || $query->HARGABELI == ''){
					echo json_encode(0);
				}else{
					echo json_encode($query->HARGABELI);
				}
			}else{
				echo json_encode($query->HARGA);
			}
		}else if($idcustomer != ''){
			$query = $this->model_master_barang->hargaJualBarang($idbarang,$idcustomer,$tgltrans);
			if($query->HARGA || $query->HARGA == ''){
				//jika tidak ada, cek di mbarang
				$query = $this->model_master_barang->hargaBarang($idbarang);
				if($query->HARGAJUALMAX == null || $query->HARGAJUALMAX == ''){
					echo json_encode(0);
				}else{
					echo json_encode($query->HARGAJUALMAX);
				}
			}else{
				echo json_encode($query->HARGA);
			}
		}
		else{
			echo json_encode(0);
		}
	}
	
	public function hargaBarangRef(){
		$idbarang = $this->input->post('idbarang');
		$idcustomer = $this->input->post('idcustomer','');
		$tgltrans = $this->input->post('tgltrans');
		
		$query = $this->model_master_barang->hargaJualBarang($idbarang,$idcustomer,$tgltrans);
		if($query->HARGAREF == ''){
			echo json_encode(0);
		}else{
			echo json_encode($query->HARGAREF);
		}	
	}
	
	public function getLastPerkiraan(){
		$response = $this->model_master_barang->getLastPerkiraan();
		echo json_encode($response);
	}

	public function getStok() {
		$this->output->set_content_type('application/json');
		
		$barang = $this->input->post('barang');
		$lokasi = $this->input->post('lokasi');
		$tgl    = $this->input->post('tgl');

		$row   = $this->model_master_barang->getStok($barang, $lokasi, $tgl);
		echo json_encode($row);
	}
	
	public function comboGridStok() {
		$this->output->set_content_type('application/json');
		$pagination = $this->setPaginationGrid();
		$response   = $this->model_master_barang->comboGridStok($pagination);
		$data       = array();
		foreach($response['rows'] as $item){
			//dapatkan satuan utama dan konversi
			$result = get_konversi_satuanutama($item->ID,$item->SATUAN);
			
			//gabungkan dengan result yg didapatkan dalam bentuk array
			$datas = array_merge((array)$item,(array)$result);
			
			//tambahkan kedalam array baru setelah diconvert jadi object
			array_push($data,(object)$datas);
		}
		$response['rows'] = (array)$data;
		echo json_encode($response);
    }
    
	public function comboGridBarangKategori(){
		$pagination = $this->setPaginationGrid();
		$query = $this->model_master_barang->comboGridBarangKategori($pagination);
		$rows = array();
		foreach($query as $rs){
			if($rs->NAMAKATEGORI<>'')
				$rows[] = $rs;
		}
		echo json_encode($rows);
	}
	
	public function barangKategori() {
		$this->output->set_content_type('application/json');
		$idbarang = $this->input->post('id',0);
		$response = $this->model_master_barang->barangKategori($idbarang);
		echo json_encode(array(
			'success' => true,
			'detail'  => $response,
		));
	}

	public function cekTransaksiBarang(){
		$idbarang = $this->input->post('idbarang');
		$adaTrans = $this->model_master_barang->cekTransaksiBarang($idbarang);
		echo $adaTrans;
	}

	public function dataGrid() {
		$this->output->set_content_type('application/json');
		$jenis = $this->input->post('jenis','');
		$response = $this->model_master_barang->dataGrid($jenis);
		echo json_encode($response);
	}
	
	public function dataGridVarian() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_barang->dataGridVarian();
		echo json_encode($response);
	}
	
	public function getDataVarian($kategori=""){
		$this->output->set_content_type('application/json');
		$response = $this->model_master_barang->getDataVarian(str_replace("%2F","%",str_replace("%7C","%",str_replace("%20"," ",$kategori))));
		echo json_encode($response);
	}
	
	public function comboGridTransaksi(){
		$this->output->set_content_type('application/json');
		$mode = $this->input->post('mode');
		$kategori = $this->input->post('kategori');
		$transaksi= $this->input->post('transaksi');
		
		
		if($mode == "BIASA")
		{
			$code = "";
			$qty = 1;
			$response = $this->model_master_barang->comboGridTransaksi($code,$qty,$mode,$transaksi,$kategori);
		}
		if($mode == "SALDOAWAL")
		{
			$code = "";
			$qty = 0;
			$response = $this->model_master_barang->comboGridTransaksi($code,$qty,$mode,$transaksi,$kategori);
		}

		echo json_encode($response);
	}
	
	public function comboGridMarketplace(){
		$this->output->set_content_type('application/json');
		$kategori = $this->input->post('kategori');
		$marketplace = $this->input->post('marketplace');
		
		$response = $this->model_master_barang->comboGridMarketplace(str_replace("%2F","%",str_replace("%7C","%",str_replace("%20"," ",$kategori))),$marketplace);

		echo json_encode($response);
	}
	
	public function comboGridTransaksiStok(){
	    $this->output->set_content_type('application/json');
	    $mode = $this->input->post('mode');
		$kategori = $this->input->post('kategori');
		$transaksi= $this->input->post('transaksi');
		$tgltrans = $this->input->post('tgltrans');
		$lokasi= $this->input->post('lokasi');
		$code = "";
		$qty = 1;
		$response = $this->model_master_barang->comboGridTransaksiStok($code,$qty,$mode,$transaksi,$kategori,$tgltrans,$lokasi);
	}
	
	public function comboGridKategoriSaja(){
		$this->output->set_content_type('application/json');
		$response = $this->model_master_barang->comboGridKategoriSaja($kategori);
		echo json_encode($response);
	}
	
	public function comboGridKategori(){
		$this->output->set_content_type('application/json');
		$response = $this->model_master_barang->comboGridKategori($kategori);
		echo json_encode($response);
	}
	
	public function getDataBarang(){
		$this->output->set_content_type('application/json');
		$mode = $this->input->post('mode');

		$code = $this->input->post('barcode');
		$qty = $this->input->post('qty');
		$jenisharga = $this->input->post('jenisharga')??"2"; // HARGA JUAL
		$response = $this->model_master_barang->getDataBarang($code,$qty,$mode,$jenisharga);


		echo json_encode($response);
	}
	
	public function getGambarBarang(){
		$this->output->set_content_type('application/json');
		$idbarang = $this->input->post('idbarang');
		$response = $this->model_master_barang->getGambarBarang($idbarang);


		echo json_encode($response);
	}
	
	public function simpanAll(){
	    ini_set('memory_limit', '128M');
	    $dataHeader = (object) [
	       'DESKRIPSI'      => $this->input->post('DESKRIPSI',''),
	       'KATEGORIONLINE' => $this->input->post('KATEGORIONLINE',''),
	       'KATEGORI'       => $this->input->post('KATEGORI',''),
	       'PANJANG'        => $this->input->post('PANJANG','0'),
	       'LEBAR'          => $this->input->post('LEBAR','0'),
	       'TINGGI'         => $this->input->post('TINGGI','0'),
	       'BERAT'          => $this->input->post('BERAT','0'),
	   ];
	   
	   $dataVarian = json_decode($this->input->post('datavarian'));
	   $response = $this->simpan($dataHeader,$dataVarian);
	   
        if ($response != ''){
        	// generate an error... or use the log_message() function to log your error
        	die(json_encode(array('errorMsg' => $response)));
        }

	    echo json_encode(array('success' => true,'errorMsg' => ''));
	}
	
	public function simpan($dataHeader,$dataVarian){
	    $urutan = $this->model_master_barang->getUrutanTampil($dataHeader->KATEGORI,$dataHeader->KATEGORIONLINE,$item->MODE,$item->KODEBARANG);
	    foreach($dataVarian as $item)
	    {   
	        $urutan['URUTAN']++;
	        
    		$id             = $item->IDBARANG;
    		$kode           = $item->KODEBARANG;
    		$warna          = $item->WARNA??"";
    		$ukuran         = $item->SIZE??"0";
    		$nama           = $item->NAMABARANG??"";
    		$kategori       = $dataHeader->KATEGORI;
    		$satuan         = $item->SATUAN;
    		$konversi1      = 1;
    		$satuan2        = $item->SATUAN;
    		$status         = $item->STATUS ?? 0;
    		$barcode        = $item->BARCODE??"";
    		$stok           = 1;
            
            if($kategori == "") die(json_encode(array('errorMsg' => "Kategori Tidak Boleh Kosong")));
    		if($nama == '') die(json_encode(array('errorMsg' => 'Nama Produk Tidak Boleh Kosong')));
            if($satuan == "") die(json_encode(array('errorMsg' => "Satuan Produk Tidak Boleh Kosong")));
           
    		$mode = $item->MODE;
    		if ($mode=='tambah') {
    			$setting = $this->model_master_config->getConfigAll('MBARANG','KODEBARANG');
    			if($setting->VALUE == "AUTO"){
    				//custom filter
    				//$filter['lokasi'] = $lokasi;
    				//$filter['tgltrans'] = $tgltrans;
    				$kode = autogen($setting,$filter);
    			}
    			//CEK APAKAH KODE & NAMA SUDAH DIGUNAKAN
    			$response = $this->model_master_barang->cek_valid_kode($kode);
    			if($response != ''){
    				die(json_encode(array('errorMsg' => $response)));
    			}
    			
    			if($barcode != "")
    			{
        			$response = $this->model_master_barang->cek_valid_barcode($barcode);
        			if($response != ''){
        				die(json_encode(array('errorMsg' => $response)));
        			}
    			}
    			
    			if($item->SKUSHOPEE != "")
    			{
        			$response = $this->model_master_barang->cek_valid_sku_shopee($item->SKUSHOPEE);
        			if($response != ''){
        				die(json_encode(array('errorMsg' => $response)));
        			}
    			}
    			
    			if($item->SKUTIKTOK != "")
    			{
        			$response = $this->model_master_barang->cek_valid_sku_tiktok($item->SKUTIKTOK);
        			if($response != ''){
        				die(json_encode(array('errorMsg' => $response)));
        			}
    			}
    			
    			if($item->SKULAZADA != "")
    			{
        			$response = $this->model_master_barang->cek_valid_sku_lazada($item->SKULAZADA);
        			if($response != ''){
        				die(json_encode(array('errorMsg' => $response)));
        			}
    			}
    			
    			$response = $this->model_master_barang->cek_valid_nama($nama);
    			if($response != ''){
    				die(json_encode(array('errorMsg' => $response)));
    			}
    			$status = 1;
    			$edit = false;
    		}
    		else{
    			//jika edit
    			//cek apakah nama sudah digunakan
    			$response = $this->model_master_barang->cek_valid_nama($nama,$kode);
    			if($response != ''){
    				die(json_encode(array('errorMsg' => $response)));
    			}
    			$edit = true;
    		}
    		$data_values = array (
    			'IDPERUSAHAAN'   => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
    			'KODEBARANG'     => $kode,
    			'NAMABARANG'     => strtoupper($nama),
    			'BARCODE'     	 => strtoupper($barcode),
    			'WARNA'     	 => strtoupper($warna),
    			'SIZE'     	     => $ukuran,
    			'KATEGORIONLINE' => $dataHeader->KATEGORIONLINE??'',
    			'KATEGORI'       => $kategori,
    			'DESKRIPSI'      => $dataHeader->DESKRIPSI??'',
    			'STOK'       	 => $stok,
    			'SATUAN'         => strtoupper($satuan),
    			'SATUAN2'        => strtoupper($satuan2),
    			'SATUAN3'        => "",
    			'KONVERSI1'      => $konversi1,
    			'KONVERSI2'      => 1,
    			'HARGABELI'      => $item->HARGABELI ?? 0,
    			'HARGAJUAL'      => $item->HARGAJUAL ?? 0,
    			'SKUGRAB'        => $item->SKUGRAB ?? "",
    			'SKUGOJEK'       => strtoupper($item->SKUGOJEK ?? ""),
    			'SKUSHOPEE'      => strtoupper($item->SKUSHOPEE ?? ""),
    			'SKUTOKPED'      => strtoupper($item->SKUTOKPED ?? ""),
    			'SKUTIKTOK'      => strtoupper($item->SKUTIKTOK ?? ""),
    			'SKULAZADA'      => strtoupper($item->SKULAZADA ?? ""),
    			'BERAT'          => $dataHeader->BERAT ?? 0,
    			'PANJANG'        => $dataHeader->PANJANG ?? 0,
    			'LEBAR'          => $dataHeader->LEBAR ?? 0,
    			'TINGGI'         => $dataHeader->TINGGI ?? 0,
    			'LIMITMIN'       => 0, //$this->input->post('LIMITMIN') ?? 0,
    			'LIMITMAX'       => 99000, //$this->input->post('LIMITMAX') ?? 0,
    			'URUTANTAMPIL'   => $urutan['ABJAD'].$urutan['URUTAN'],
    			'CATATAN'        => "",
    			'USERENTRY'      => $_SESSION[NAMAPROGRAM]['USERID'],
    			'TGLENTRY'       => date("Y-m-d"),
    			'STATUS'         => $status
    		);
    
    		$response = $this->model_master_barang->simpan($id,$data_values,$edit);
    		
    		// panggil fungsi untuk log history
    		log_history(
    			$kode,
    			'MASTER BARANG',
    			$mode,
    			array(
    				array(
    					'nama'  => 'header',
    					'tabel' => 'mbarang',
    					'kode'  => 'kodebarang'
    				),
    			),
    			$_SESSION[NAMAPROGRAM]['USERID']
    		);
    		if($response != "")
    		{
    		    return $response;
    		}
	    }
	    
    		
    	return '';
	}
	
	public function ubahUrutanTampil(){
	  ini_set('memory_limit', '128M');
	   
	  $response = $this->model_master_barang->ubahUrutanTampil(json_decode($this->input->post('dataKategori')));
	   
        if ($response != ''){
        	// generate an error... or use the log_message() function to log your error
        	die(json_encode(array('errorMsg' => $response)));
        }

	    echo json_encode(array('success' => true,'errorMsg' => ''));
	}

	function hapus(){
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');

		$exe = $this->model_master_barang->hapus($id);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		echo json_encode(array('success' => true));

		log_history(
			$kode,
			'MASTER BARANG',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}
	
	function hapusAll(){
		$kategori = $this->input->post('kategori');

		$exe = $this->model_master_barang->hapusHeader(str_replace("%2F","%",str_replace("%7C","%",str_replace("%20"," ",$kategori))));
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		echo json_encode(array('success' => true));

		log_history(
			$kode,
			'MASTER BARANG',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}
	
	function importExcelUrutan(){
	    // Configure the upload settings
        $config['upload_path'] = './assets/excelData/';
        $config['allowed_types'] = '*'; // Allow only Excel files
        $config['max_size'] = 10024; // Max file size (in KB)
		
        $this->upload->initialize($config);
        if ($this->upload->do_upload('excelFileUrutan')) {
            // Get the uploaded file data
            $fileData = $this->upload->data();
            $filePath = $fileData['full_path'];

            // Read the uploaded Excel file
            $data = $this->readExcelFileUrutan($filePath);
            unlink($filePath);
            $response = $this->model_master_barang->updateUrutanTampil($data);
            if($response == "")
            {
                echo '<script>window.history.back();</script>';
            }
            else
            {
                echo $response;
            }
        } else {
            // Upload failed, display error
            echo $this->upload->display_errors();
        }
	}
	
	 public function readExcelFileUrutan($filePath)
    {
        $data = $this->spout->readXlsxUrutanBarang($filePath);
        return $data;
    }
}
