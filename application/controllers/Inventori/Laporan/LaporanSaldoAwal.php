<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanSaldoAwal extends MY_Controller {
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
		$grouplokasi  = $this->input->post('group_lokasi');
		$lokasi      	= $this->input->post('txt_lokasi',[]);
		$filter      	= $this->input->post('data_filter');
		$tampil      	= $this->input->post('data_tampil');
		
		//KONVERSI JADI QUERY
		$whereFilter = where_laporan($filter);
		
		//LOKASI
		$whereLokasi = count($lokasi)>0 ? " and (MLOKASI.idlokasi='".implode("' or MLOKASI.idlokasi='", $lokasi)."')" : '';
		
		//PERUSAHAAN
		$wherePerusahaan = "and SALDOSTOK.idperusahaan=$perusahaan";

        if (strpos($tampil,"REGISTER") !== FALSE) {
		$sql = " select saldostok.KodeSaldoStok, saldostok.TglTrans, mlokasi.KodeLokasi, mlokasi.namalokasi, 
						saldostok.Catatan, mbarang.KodeBarang, mbarang.NamaBarang, saldostokdtl.Jml, saldostokdtl.Satuan, saldostokdtl.harga, saldostokdtl.subtotal						
			     from SaldoStok  
					  inner join SaldoStokDtl 	on saldostok.idSaldoStok = saldostokdtl.idSaldoStok 
					  inner join mbarang  		on saldostokdtl.idbarang=mbarang.idbarang and mbarang.stok = 1
					  inner join mlokasi  		on saldostok.idlokasi=mlokasi.idlokasi
				where (1=1 $whereFilter) $wherePerusahaan $whereLokasi and saldostok.Status<>'D'
				and SALDOSTOK.kodesaldostok not like 'CLS%' and SALDOSTOK.CATATAN <> 'SALDO DARI HITUNG HPP' ";
        }  else if (strpos($tampil,"REKAP") !== FALSE) {
			$sql = "select saldostok.KodeSaldoStok as kodetrans, saldostok.TglTrans, mlokasi.KodeLokasi, mlokasi.namalokasi,saldostok.status,
						saldostok.Catatan,saldostok.grandtotal,SUM(SaldoStokDtl.jml) as QTY		
    			     from SaldoStok  
    					  inner join SaldoStokDtl 	on saldostok.idSaldoStok = saldostokdtl.idSaldoStok 
    					  inner join mbarang  		on saldostokdtl.idbarang=mbarang.idbarang and mbarang.stok = 1
    					  inner join mlokasi  		on saldostok.idlokasi=mlokasi.idlokasi
    				where (1=1 $whereFilter) $wherePerusahaan $whereLokasi and saldostok.Status<>'D'
    				and SALDOSTOK.kodesaldostok not like 'CLS%' and SALDOSTOK.CATATAN <> 'SALDO DARI HITUNG HPP'
					group by 1, saldostok.CATATAN, saldostok.TGLTRANS, saldostok.KODESALDOSTOK";
		}

		if ($tampil=='REGISTER') {
			$sql .= " order by SaldoStok.tgltrans, SaldoStok.KodeSaldoStok,MBARANG.kodebarang";
			$title = 'Laporan Saldo Awal - Register';
		} else if ($tampil=='REGISTERLOKASI'){
			$sql .= " order by MLOKASI.NamaLokasi, SaldoStok.tgltrans, SaldoStok.KodeSaldoStok, MBARANG.kodebarang"; 
			$title = 'Laporan Saldo Awal - Register Berdasarkan Lokasi';	
		}  else if ($tampil=='REKAP') {
			$sql = "select * from ($sql) as a order by a.tgltrans, a.kodetrans";
			$title = 'Laporan Saldo Awal - Rekap Berdasarkan Faktur';
		}

		//ambil query sesuai filter
		$data['sql']         = $sql;
		$data['grouplokasi'] = $grouplokasi;
		$data['whereLokasi'] = $whereLokasi;
		$data['tampil']      = $tampil;		
		$data['title']       = $title;		
		$data['excel']       = $this->input->post('excel');
		$data['filename']    = $this->input->post('file_name');
		$data['LIHATHARGA']  = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];
		
		$dir = 'reports/v_';
		$this->load->view($dir."report_transaksi_inventori_saldo_awal.php",$data);
	}
}
