<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanPO extends MY_Controller {
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
		
		$perusahaan  = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
		$grouplokasi  = $this->input->post('group_lokasi');
		$lokasi      = $this->input->post('txt_lokasi',[]);
		$jenisStatus = $this->input->post('cbStatus',[]);
		$tampil      = $this->input->post('data_tampil');
		$filter      = $this->input->post('data_filter');
		 
		//KONVERSI JADI QUERY
		$whereFilter = where_laporan($filter);
		
		//LOKASI
		$whereLokasi = count($lokasi)>0 ? " and (MLOKASI.idlokasi='".implode("' or MLOKASI.idlokasi='", $lokasi)."')" : '';
		
	
		//PERUSAHAAN
		$wherePerusahaan = "and TPO.idperusahaan=$perusahaan";
		
		//TANGGAL
		$tglTrans = explode(" - ",$this->input->post('tglTrans'));
		
		$tgl_aw = ubah_tgl_firebird($tglTrans[0]);
		$tgl_ak = ubah_tgl_firebird($tglTrans[1]);
		$whereTanggal = "and TPO.tgltrans between '$tgl_aw' and '$tgl_ak'";

		//STATUS
		$whereStatus .= count($jenisStatus)>0 ? " and (TPO.status='".implode("' or TPO.status='", $jenisStatus)."')" : '';
	
		
		if (strpos($tampil,"REGISTER") !== FALSE) {
			$sql = "select 1 as Nomor, tpo.catatan, tpo.tgltrans,tpo.status, tpo.kodepo as kodetrans, msupplier.kodesupplier, msupplier.namasupplier, tpo.total,  tpo.ppnrp, tpo.pph22rp,tpo.grandtotal,tpo.pembulatan, mbarang.KodeBarang, mbarang.NamaBarang, tpodtl.Jml, tpodtlbrg.terpenuhi, tpodtlbrg.sisa, tpodtl.Satuan, tpodtl.harga, mcurrency.simbol, tpodtl.NilaiKurs, tpodtl.HargaKurs, tpodtl.DiscPersen, tpodtl.Disc,tpodtl.subtotal, tpodtl.subtotalKurs	  
					from tpo 
					inner join msupplier on tpo.idsupplier = msupplier.idsupplier
					inner join tpodtl    on tpo.idpo = tpodtl.idpo
					inner join tpodtlbrg on tpodtl.idpo = tpodtlbrg.idpo and tpodtl.idbarang = tpodtlbrg.idbarang
					inner join mbarang   on tpodtl.idbarang=mbarang.idbarang and mbarang.stok = 1
					left join  mcurrency on tpodtl.idcurrency=mcurrency.idcurrency
					left join  mlokasi 	 on tpo.idlokasi = mlokasi.idlokasi
					where (1=1 $whereFilter) $wherePerusahaan $whereTanggal $whereLokasi $whereStatus";

		} else if (strpos($tampil,"REKAP") !== FALSE) {
			$sql = "select 1 as NOMOR,tpo.CATATAN, tpo.TGLTRANS,tpo.STATUS, tpo.KODEPO as KODETRANS, msupplier.KODESUPPLIER, msupplier.NAMASUPPLIER, tpo.TOTAL, tpo.PPNRP, tpo.PPH22RP, tpo.GRANDTOTAL,tpo.PEMBULATAN,
			        SUM(tpodtl.jml) as QTY
					from tpo 
					inner join msupplier on tpo.idsupplier=msupplier.idsupplier
					inner join tpodtl    on tpo.idpo=tpodtl.idpo
					inner join mbarang   on tpodtl.idbarang=mbarang.idbarang and mbarang.stok = 1
					left join  mcurrency on tpodtl.idcurrency=mcurrency.idcurrency
					left join  mlokasi   on tpo.idlokasi = mlokasi.idlokasi
					where (1=1 $whereFilter)  $wherePerusahaan $whereTanggal $whereLokasi $whereStatus
					group by 1, tpo.CATATAN, tpo.TGLTRANS, tpo.KODEPO, msupplier.KODESUPPLIER, msupplier.NAMASUPPLIER";
		}
		
		if ($tampil=='REGISTER') {
			$sql = "select * from ($sql) as a order by tgltrans, nomor, kodetrans, kodebarang";
			$title = 'Laporan Purchase Order - Register';
		} else if ($tampil=='REKAP') {
			$sql = "select * from ($sql) as a order by tgltrans, nomor, kodetrans";
			$title = 'Laporan Purchase Order - Rekap Berdasarkan Faktur';
		}
		
		$data['title']       = $title;
		$data['tampil']      = $tampil;
		$data['tgl_aw']      = $tgl_aw;
		$data['tgl_ak']      = $tgl_ak;
		$data['sql']         = $sql;
		$data['grouplokasi'] = $grouplokasi;
		$data['whereLokasi'] = $whereLokasi;
		$data['excel']       = $this->input->post('excel');
		$data['filename']    = $this->input->post('file_name');
		$data['LIHATHARGA']  = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];
		
		$dir = 'reports/v_';
		$this->load->view($dir."report_transaksi_inventori_pesananbeli.php",$data);
	}
}
