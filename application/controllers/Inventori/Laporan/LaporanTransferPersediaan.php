<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanTransferPersediaan extends MY_Controller {
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
		$data['errorMsg'] = '';
		
		$perusahaan   = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
		$lokasi       = $this->input->post('txt_lokasi',[]);
		$grouplokasi  = $this->input->post('group_lokasi');
		$lokasiTujuan = $this->input->post('txt_lokasi_tujuan',[]);
		$grouplokasiTujuan  = $this->input->post('txt_group_lokasi_tujuan');
		$jenisStatus = $this->input->post('cbStatus',[]);
		$tampil      = $this->input->post('data_tampil');
		$filter      = $this->input->post('data_filter');
		 
		//KONVERSI JADI QUERY
		$whereFilter = where_laporan($filter);
		
		//WHERELOKASI
		$whereLokasiAsal = count($lokasi)>0 ? " and (MLOKASI.idlokasi='".implode("' or MLOKASI.idlokasi='", $lokasi)."')" : '';
		$whereLokasiTujuan = count($lokasiTujuan)>0 ? " and (MLOKASITUJUAN.idlokasi='".implode("' or MLOKASITUJUAN.idlokasi='", $lokasiTujuan)."')" : '';
		
		//PERUSAHAAN
		$wherePerusahaan = "and TTRANSFER.idperusahaan=$perusahaan";
		
		//TANGGAL
		$tglTrans = explode(" - ",$this->input->post('tglTrans'));
		
		$tgl_aw = ubah_tgl_firebird($tglTrans[0]);
		$tgl_ak = ubah_tgl_firebird($tglTrans[1]);
		$whereTanggal = "and TTRANSFER.tgltrans between '$tgl_aw' and '$tgl_ak'";

		//STATUS
		$whereStatus .= count($jenisStatus)>0 ? " and (TTRANSFER.status='".implode("' or TTRANSFER.status='", $jenisStatus)."')" : '';
	
		

		if (strpos($tampil,"REGISTER") !== FALSE) {
			$sql = "select 1 as Nomor, ttransfer.catatan, ttransfer.tgltrans,ttransfer.tglkirim,ttransfer.status,ttransfer.kodetransfer as kodetrans, mbarang.urutantampil, mbarang.KodeBarang, mbarang.NamaBarang, mlokasi.kodelokasi as kodelokasiasal,mlokasi.namalokasi as namalokasiasal,mlokasitujuan.kodelokasi as kodelokasitujuan,mlokasitujuan.namalokasi as namalokasitujuan, ttransferdtl.Jml, ttransferdtl.Satuan
					from ttransfer 
					inner join ttransferdtl 		 on ttransfer.idtransfer=ttransferdtl.idtransfer
					inner join mbarang  			 on ttransferdtl.idbarang=mbarang.idbarang and mbarang.stok = 1
					left join mlokasi  	 			 on ttransfer.idlokasiasal = mlokasi.idlokasi
					left join mlokasi MLOKASITUJUAN	 on ttransfer.idlokasitujuan = mlokasitujuan.idlokasi
					where (1=1 $whereFilter) $wherePerusahaan $whereTanggal $whereLokasiAsal $whereLokasiTujuan $whereStatus";
		} else if (strpos($tampil,"REKAP") !== FALSE) {
			$sql = "select 1 as Nomor, ttransfer.catatan, ttransfer.tgltrans,ttransfer.tglkirim,ttransfer.status,ttransfer.kodetransfer as kodetrans, mlokasi.kodelokasi as kodelokasiasal,mlokasi.namalokasi as namalokasiasal,mlokasitujuan.kodelokasi as kodelokasitujuan,mlokasitujuan.namalokasi as namalokasitujuan,
			        SUM(ttransferdtl.jml) as QTY
					from ttransfer 
					inner join ttransferdtl 		 on ttransfer.idtransfer=ttransferdtl.idtransfer
					inner join mbarang  			 on ttransferdtl.idbarang=mbarang.idbarang and mbarang.stok = 1
					left join mlokasi  	 			 on ttransfer.idlokasiasal = mlokasi.idlokasi
					left join mlokasi MLOKASITUJUAN	 on ttransfer.idlokasitujuan = mlokasitujuan.idlokasi
					where (1=1 $whereFilter) $wherePerusahaan $whereTanggal $whereLokasiAsal $whereLokasiTujuan $whereStatus
					group by 1, ttransfer.CATATAN, ttransfer.TGLTRANS, ttransfer.KODETRANSFER";
		}
		
		if ($tampil=='REGISTER') {
			$sql = "select * from ($sql) as a order by tgltrans, nomor, kodetrans, SUBSTRING(URUTANTAMPIL, 1, 1) ASC ,
    							CAST(SUBSTRING(URUTANTAMPIL, 2) AS UNSIGNED) ASC, kodebarang";
			$title = 'Laporan Transfer Persediaan - Register';
		} else if ($tampil=='REGISTERBARANG') {
			$sql = "select * from ($sql) as a order by SUBSTRING(URUTANTAMPIL, 1, 1) ASC ,
    							CAST(SUBSTRING(URUTANTAMPIL, 2) AS UNSIGNED) ASC, kodebarang, tgltrans, nomor, kodetrans";
			$title = 'Laporan Transfer Persediaan - Register Berdasarkan Barang';
		}  else if ($tampil=='REKAP') {
			$sql = "select * from ($sql) as a order by tgltrans, nomor, kodetrans";
			$title = 'Laporan Transfer Persediaan - Rekap Berdasarkan Surat Jalan';
		}
		
		$data['title']       = $title;
		$data['tampil']      = $tampil;
		$data['tgl_aw']      = $tgl_aw;
		$data['tgl_ak']      = $tgl_ak;
		$data['sql']         = $sql;
		$data['grouplokasi'] = $grouplokasi;
		$data['whereLokasi'] = $whereLokasiAsal;
		$data['excel']       = $this->input->post('excel');
		$data['filename']    = $this->input->post('file_name');
		$data['LIHATHARGA']  = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];
		
		$dir = 'reports/v_';
		$this->load->view($dir."report_transaksi_inventori_transfer_persediaan.php",$data);
	}
}
