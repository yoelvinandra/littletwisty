<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanOpnameStok extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$perusahaan = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
		if($perusahaan != ''){
			//load database perusahaan,jadikan default
			//$this->db->close();
			//$this->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		}
	}
	
	public function index()
	{
		$kodeMenu = $this->input->get('kode');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu);
	}
    public function laporan()
	{		
		$this->load->library('html_table');
		$kodeMenu = $this->input->get('kode');
		
		//load filter
		$perusahaan     = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
		$lokasi       	= $this->input->post('txt_lokasi',[]);
		$filter      	= $this->input->post('data_filter');
		$tampil      	= $this->input->post('data_tampil');
		
		//KONVERSI JADI QUERY
		$whereFilter = where_laporan($filter);
		
		//LOKASI
		$whereLokasi = count($lokasi)>0 ? " and (MLOKASI.idlokasi='".implode("' or MLOKASI.idlokasi='", $lokasi)."')" : '';
		
		//PERUSAHAAN
		$wherePerusahaan = "and TOPNAMESTOK.idperusahaan=$perusahaan";
		
		//TANGGAL
		$tglTrans = explode(" - ",$this->input->post('tglTrans'));
		
		$tgl_aw = ubah_tgl_firebird($tglTrans[0]);
		$tgl_ak = ubah_tgl_firebird($tglTrans[1]);

		$whereTanggal = "and TOPNAMESTOK.tgltrans between '$tgl_aw' and '$tgl_ak'";
		
		$sql = "select topnamestok.KodeOpnameStok, topnamestok.TglTrans, mlokasi.KodeLokasi, mlokasi.NamaLokasi, topnamestok.Catatan, mbarang.KodeBarang, mbarang.NamaBarang, topnamestokdtl.Jml, topnamestokdtl.Satuan
				from TOpnameStok  
				inner join TOpnameStokDtl  	on topnamestok.idOpnameStok = topnamestokdtl.idOpnameStok 
				inner join mbarang  	 	on topnamestokdtl.idbarang=mbarang.idbarang and mbarang.stok = 1			
				inner join mlokasi 		 	on topnamestok.idlokasi=mlokasi.idlokasi
				where (1=1 $whereFilter) and topnamestok.Status<>'B' $wherePerusahaan $whereTanggal $whereLokasi";
			
		if ($tampil=='REGISTER') {
			$sql  .= " order by TOpnameStok.tgltrans, TOpnameStok.KodeOpnameStok, SUBSTRING(mbarang.URUTANTAMPIL, 1, 1) ASC ,
    							CAST(SUBSTRING(mbarang.URUTANTAMPIL, 2) AS UNSIGNED) ASC, mbarang.kodebarang";
			$title = 'Laporan Opname Stok - Register';
		} else if ($tampil=='REGISTERBARANG') {
			$sql  .= " order by SUBSTRING(mbarang.URUTANTAMPIL, 1, 1) ASC ,
    							CAST(SUBSTRING(mbarang.URUTANTAMPIL, 2) AS UNSIGNED) ASC, mbarang.NamaBarang, TOpnameStok.tgltrans, TOpnameStok.KodeOpnameStok, mbarang.kodebarang"; 
			$title = 'Laporan Opname Stok - Register Berdasarkan Barang';
		}		
		
		//ambil query sesuai filter
		$data['sql']         = $sql;
		$data['whereLokasi'] = $whereLokasi;
		$data['tampil']      = $tampil;				
		$data['title']       = $title;		
		$data['tglAw']       = $tgl_aw;
		$data['tglAk']       = $tgl_ak;
		$data['excel']       = $this->input->post('excel');
		$data['filename']    = $this->input->post('file_name');
		$data['LIHATHARGA']  = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];
		
		
		$dir = 'reports/v_';
		$this->load->view($dir."report_transaksi_inventori_opname_stok.php",$data);
	}
}
