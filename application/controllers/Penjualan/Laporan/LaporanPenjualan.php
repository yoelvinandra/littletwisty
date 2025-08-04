<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanPenjualan extends MY_Controller {
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

		$perusahaan   = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
		$grouplokasi  = $this->input->post('group_lokasi');
		$lokasi       = $this->input->post('txt_lokasi',[]);
		$jenisStatus  = $this->input->post('cbStatus',[]);
		$tampil       = $this->input->post('data_tampil');
		$filter       = $this->input->post('data_filter');	
		
		//KONVERSI JADI QUERY
		$whereFilter = where_laporan($filter);
		
		//LOKASI
		$whereLokasi = count($lokasi)>0 ? " and (MLOKASI.IDLOKASI='".implode("' or MLOKASI.IDLOKASI='", $lokasi)."')" : '';
		
		//PERUSAHAAN
		$wherePerusahaan = "and TPENJUALAN.idperusahaan=$perusahaan";
		
		//TANGGAL
		$tglTrans = explode(" - ",$this->input->post('tglTrans'));
		
		$tgl_aw = ubah_tgl_firebird($tglTrans[0]);
		$tgl_ak = ubah_tgl_firebird($tglTrans[1]);
		
		$whereTanggal = "and TPENJUALAN.tgltrans between '$tgl_aw' and '$tgl_ak'";

		//STATUS
		$whereStatus .= count($jenisStatus)>0 ? " and (TPENJUALAN.status='".implode("' or TPENJUALAN.status='", $jenisStatus)."')" : '';
		
		if (strpos($tampil,"REGISTER") !== FALSE) {

			$sql = "select 1 as Nomor, mcustomer.namacustomer,mcustomer.kota, tpenjualan.catatan, tpenjualan.STATUS, tpenjualan.tgltrans, tpenjualan.kodepenjualan as kodetrans, tpenjualan.TOTAL, tpenjualan.PPNRP, tpenjualan.GRANDTOTAL, tpenjualan.nofakturpajak, mbarang.KodeBarang, tpenjualandtl.keterangan as NamaBarang, tpenjualandtl.Jml, tpenjualandtl.Satuan, tpenjualandtl.harga, tpenjualandtl.ppnrp as ppnrpdtl,
						  tpenjualandtl.NilaiKurs, tpenjualandtl.HargaKurs, tpenjualandtl.DiscPersen, tpenjualandtl.Disc, tpenjualandtl.subtotal, tpenjualandtl.subtotalKurs, tpenjualandtl.subtotalKurs AS subtotalkurs2, tpenjualandtl.pakaippn
						  ,tpenjualan.catatancustomer,tpenjualan.potonganrp,tpenjualan.pembayaran,tpenjualan.GRANDTOTALDISKON,tpenjualan.KODETRANSREFERENSI,tpenjualan.JENISTRANSAKSI 
					from tpenjualan 
					inner join tpenjualandtl  	on tpenjualan.idpenjualan=tpenjualandtl.idpenjualan
					inner join mbarang  		on tpenjualandtl.idbarang=mbarang.idbarang and mbarang.stok = 1
					left join mcustomer  		on tpenjualan.idcustomer=mcustomer.idcustomer
					left join mlokasi  			on tpenjualan.idlokasi = mlokasi.idlokasi
					where (1=1 $whereFilter) $wherePerusahaan $whereTanggal $whereLokasi $whereStatus 
					";
	
		} else if (strpos($tampil,"RINCIAN") !== FALSE) {

			$sql = "select tpenjualan.tgltrans,mbarang.kodebarang,mbarang.namabarang, SUM(tpenjualandtl.jml) as jml
					from tpenjualan 
					inner join tpenjualandtl  	on tpenjualan.idpenjualan=tpenjualandtl.idpenjualan
					inner join mbarang  		on tpenjualandtl.idbarang=mbarang.idbarang and mbarang.stok = 1
					left join mcustomer  		on tpenjualan.idcustomer=mcustomer.idcustomer
					left join mlokasi  			on tpenjualan.idlokasi = mlokasi.idlokasi
					where (1=1 $whereFilter) $wherePerusahaan $whereTanggal $whereLokasi $whereStatus and tpenjualandtl.KETERANGAN != 'ONGKIR' 
				    group by tpenjualan.tgltrans,mbarang.namabarang
					";
	
		} else if (strpos($tampil,"REKAP") !== FALSE) {

				$sql = "select 1 as NOMOR, mcustomer.namacustomer,mcustomer.kota, tpenjualan.CATATAN,tpenjualan.STATUS, tpenjualan.TGLTRANS, tpenjualan.KODEPENJUALAN as KODETRANS,
					tpenjualan.TOTAL, tpenjualan.PPNRP, tpenjualan.GRANDTOTAL,tpenjualan.POTONGANRP,tpenjualan.POTONGANPERSEN,tpenjualan.PEMBAYARAN,tpenjualan.KODETRANSREFERENSI,tpenjualan.JENISTRANSAKSI 
					,tpenjualan.catatancustomer,tpenjualan.GRANDTOTALDISKON,
					SUM(tpenjualandtl.jml) as QTY
					from tpenjualan 
					inner join tpenjualandtl  	on tpenjualan.idpenjualan=tpenjualandtl.idpenjualan
					inner join mbarang  			on tpenjualandtl.idbarang=mbarang.idbarang and mbarang.stok = 1
					left join mcustomer  		on tpenjualan.idcustomer=mcustomer.idcustomer
					left join mlokasi  			on tpenjualan.idlokasi = mlokasi.idlokasi
					where (1=1 $whereFilter) $wherePerusahaan $whereTanggal $whereLokasi $whereStatus 
					group by 1, tpenjualan.CATATAN, tpenjualan.TGLTRANS, tpenjualan.KODEPENJUALAN ";
		}

			
		if ($tampil=='REGISTER') {
			$sql = "select * from ($sql) as abc order by  tgltrans, nomor, kodetrans, kodebarang";
			$title = 'Laporan Penjualan - Register';
		} else if ($tampil=='RINCIAN') {
			$sql = "select * from ($sql) as abc order by  tgltrans, kodebarang";
			$title = 'Laporan Penjualan - Harian';
		} else if ($tampil=='REKAP') {
			$sql = "select * from ($sql) as abc order by  tgltrans, nomor, kodetrans";
			$title = 'Laporan Penjualan - Rekap Berdasarkan Faktur';
		} else if ($tampil=='HARIAN') {
			$title = 'Laporan Penjualan - Harian Berdasarkan Barang';
		} 
		 else if ($tampil=='HARIANTHERMAL') {
			$title = 'Laporan Penjualan - Harian';
		} 

		$data['title']       = $title;
		$data['tampil']      = $tampil;
		$data['tgl_aw']      = $tgl_aw;
		$data['tgl_ak']      = $tgl_ak;
		$data['whereFilter'] = $whereFilter;
		$data['sql']         = $sql;
		$data['grouplokasi'] = $grouplokasi;
		$data['whereLokasi'] = $whereLokasi;
		$data['whereTanggal'] = $whereTanggal;
		$data['whereStatus'] = $whereStatus;
		$data['excel']       = $this->input->post('excel');
		$data['filename']    = $this->input->post('file_name');
		$data['LIHATHARGA']  = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];

		$dir = 'reports/v_';
		$this->load->view($dir."report_transaksi_jual_penjualan.php",$data);
		
	}
}
