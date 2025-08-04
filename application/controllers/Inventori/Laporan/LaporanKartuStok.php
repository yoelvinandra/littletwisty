<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanKartuStok extends MY_Controller {
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
		$perusahaan       = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
		$grouplokasi  = $this->input->post('group_lokasi');
		$lokasi          = $this->input->post('txt_lokasi',[]);
		$filter      = $this->input->post('data_filter');
		$tampil      = $this->input->post('rd_jenistampil');
		
		//KONVERSI JADI QUERY
		$whereFilter = where_laporan($filter);
		
		//PERUSAHAAN
		$wherePerusahaan = "and kartustok.idperusahaan=$perusahaan";
		
		//LOKASI
		$whereLokasi = count($lokasi)>0 ? " and (MLOKASI.idlokasi='".implode("' or MLOKASI.idlokasi='", $lokasi)."')" : '';
	
		//TANGGAL
		$tglTrans = explode(" - ",$this->input->post('tglTrans'));
		
		//TANGGAL di REPORT
		$tgl_aw = $tglTrans[0]=='' ? date("Y-m-d") : $tgl_aw = ubah_tgl_firebird($tglTrans[0]);
		$tgl_ak = $tglTrans[1]=='' ? $tgl_aw : ubah_tgl_firebird($tglTrans[1]);

		
		$whereBarangAktif = '';
		if ($this->input->post('cb_BarangAktif')==1){
			$whereBarangAktif = ' and MBARANG.status=1';
		}
		if ($this->input->post('cb_BarangTransaksi')==1) {			
			$sql = "select distinct(mbarang.kodebarang),mbarang.idbarang,mbarang.namabarang, mbarang.Satuan, mbarang.Satuan2, mbarang.Satuan3
					from mbarang  
					inner join kartustok  on mbarang.idbarang=kartustok.idbarang			
					inner join mlokasi    on mlokasi.idlokasi=kartustok.idlokasi			
					where (1=1 $whereFilter)  $wherePerusahaan $whereBarangAktif
					$whereLokasi and kartustok.tgltrans='".$this->input->post('txt_tgl_transaksi')."' and mbarang.stok = 1";
		} else {
			$sql = "select distinct a.urutantampil, a.idbarang, a.KodeBarang, a.NamaBarang, a.Satuan, a.Satuan2, a.Satuan3
					from (
						select distinct mbarang.urutantampil, mbarang.idbarang, mbarang.KodeBarang, mbarang.NamaBarang, mbarang.Satuan, mbarang.Satuan2, mbarang.Satuan3
						from mbarang 
						inner join KartuStok  on mbarang.idBarang=kartustok.idBarang
						inner join mlokasi    on mlokasi.idlokasi=kartustok.idlokasi	
						where (1=1 $whereFilter) $whereBarangAktif $whereLokasi $wherePerusahaan and mbarang.stok = 1

						union

						select distinct mbarang.urutantampil, mbarang.idbarang, mbarang.KodeBarang, mbarang.NamaBarang, mbarang.Satuan, mbarang.Satuan2, mbarang.Satuan3
						from mbarang 
						inner join SaldoStokDtl  	on mbarang.idBarang=saldostokdtl.idBarang
						inner join SaldoStok  		on saldostok.idsaldostok=saldostokdtl.idSaldostok
						inner join mlokasi   		on mlokasi.idlokasi=saldostok.idlokasi	
						where (1=1 $whereFilter) and saldostokdtl.Jml>0  $whereBarangAktif $whereLokasi and saldostok.idperusahaan=$perusahaan and SaldoStok.KODESALDOSTOK NOT LIKE 'CLS%' and mbarang.stok = 1 and SaldoStok.STATUS <> 'D'
					) a 
					order by SUBSTRING(a.URUTANTAMPIL, 1, 1) ASC ,
    						 CAST(SUBSTRING(a.URUTANTAMPIL, 2) AS UNSIGNED) ASC,a.namabarang,a.kodebarang";	
		}
		
		$data['sql']         = $sql;
		$data['grouplokasi'] = $grouplokasi;
		$data['tglAw']       = $tgl_aw;
		$data['tglAk']       = $tgl_ak;
		$data['whereLokasi'] = $whereLokasi;
		$data['Lokasi']      = $this->input->post('txt_namalokasi');
		$data['jenistampil'] = $tampil;		
		$data['excel']       = $this->input->post('excel');
		$data['filename']    = $this->input->post('file_name');
		$data['LIHATHARGA']  = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];

		$dir = 'reports/v_';
		$this->load->view($dir."report_transaksi_inventori_kartu_stok.php",$data);
	}
}

