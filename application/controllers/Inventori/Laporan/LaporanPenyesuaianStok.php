<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanPenyesuaianStok extends MY_Controller {
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
		$perusahaan  = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
		$grouplokasi  = $this->input->post('group_lokasi');
		$lokasi      = $this->input->post('txt_lokasi',[]);
		$filter      = $this->input->post('data_filter');
		$tampil      = $this->input->post('data_tampil');
		
		//KONVERSI JADI QUERY
		$whereFilter = where_laporan($filter);
	
		//LOKASI
		$whereLokasi = count($lokasi)>0 ? " and (MLOKASI.idlokasi='".implode("' or MLOKASI.idlokasi='", $lokasi)."')" : '';
		
		//PERUSAHAAN
		$wherePerusahaan = "and TPENYESUAIANSTOK.idperusahaan=$perusahaan";
		
		//TANGGAL
		$tglTrans = explode(" - ",$this->input->post('tglTrans'));
		
		$tgl_aw = ubah_tgl_firebird($tglTrans[0]);
		$tgl_ak = ubah_tgl_firebird($tglTrans[1]);

		$whereTanggal = "and TPENYESUAIANSTOK.tgltrans between '$tgl_aw' and '$tgl_ak'";
        
        if (strpos($tampil,"REGISTER") !== FALSE) {
    		$sql = "select tpenyesuaianstok.KodePenyesuaianStok, topnamestok.kodeopnamestok, tpenyesuaianstok.TglTrans, mlokasi.KodeLokasi, mlokasi.NamaLokasi, tpenyesuaianstok.Catatan, mbarang.KodeBarang, mbarang.NamaBarang, tpenyesuaianstokdtl.Jml, tpenyesuaianstokdtl.Selisih, tpenyesuaianstokdtl.Satuan,tpenyesuaianstokdtl.HARGA,tpenyesuaianstokdtl.SUBTOTAL
    				from tpenyesuaianstok  
    				inner join tpenyesuaianstokdtl  on tpenyesuaianstok.idPenyesuaianStok = tpenyesuaianstokdtl.idPenyesuaianStok 				
    				inner join mbarang  			on tpenyesuaianstokdtl.idbarang=mbarang.idbarang and mbarang.stok = 1			
    				inner join mlokasi  			on tpenyesuaianstok.idlokasi=mlokasi.idlokasi
    				left join topnamestok  			on topnamestok.IDOPNAMESTOK=tpenyesuaianstok.IDOPNAMESTOK
    				where (1=1 $whereFilter) and tpenyesuaianstok.Status<>'B' $wherePerusahaan $whereTanggal $whereLokasi";
        }  else if (strpos($tampil,"REKAP") !== FALSE) {
			$sql = "select tpenyesuaianstok.KodePenyesuaianStok as kodetrans, topnamestok.kodeopnamestok, tpenyesuaianstok.TglTrans, mlokasi.KodeLokasi, mlokasi.NamaLokasi, tpenyesuaianstok.Catatan,tpenyesuaianstok.status
			        from tpenyesuaianstok
    				inner join tpenyesuaianstokdtl  on tpenyesuaianstok.idPenyesuaianStok = tpenyesuaianstokdtl.idPenyesuaianStok 				
    				inner join mbarang  			on tpenyesuaianstokdtl.idbarang=mbarang.idbarang and mbarang.stok = 1			
    				inner join mlokasi  			on tpenyesuaianstok.idlokasi=mlokasi.idlokasi
    				left join topnamestok  			on topnamestok.IDOPNAMESTOK=tpenyesuaianstok.IDOPNAMESTOK
    				where (1=1 $whereFilter) and tpenyesuaianstok.Status<>'B' $wherePerusahaan $whereTanggal $whereLokasi
					group by 1, tpenyesuaianstok.CATATAN, tpenyesuaianstok.TGLTRANS, tpenyesuaianstok.KodePenyesuaianStok";
		}
				
		if ($tampil=='REGISTER') {
			$sql  .= " order by tpenyesuaianstok.tgltrans, tpenyesuaianstok.KodePenyesuaianStok, SUBSTRING(mbarang.URUTANTAMPIL, 1, 1) ASC ,
    						CAST(SUBSTRING(mbarang.URUTANTAMPIL, 2) AS UNSIGNED) ASC, mbarang.kodebarang";
			$title = 'Laporan Penyesuaian Stok - Register';
		} else if ($tampil=='REGISTERBARANG') {
			$sql  .= " order by SUBSTRING(mbarang.URUTANTAMPIL, 1, 1) ASC ,
    						CAST(SUBSTRING(mbarang.URUTANTAMPIL, 2) AS UNSIGNED) ASC,mbarang.kodebarang, mbarang.NamaBarang, tpenyesuaianstok.tgltrans, tpenyesuaianstok.KodePenyesuaianStok, mbarang.kodebarang"; 
			$title = 'Laporan Penyesuaian Stok - Register Berdasarkan Barang';
		} else if ($tampil=='REKAP') {
			$sql = "select * from ($sql) as a order by tgltrans, kodetrans";
			$title = 'Laporan Penyesuaian Stok - Rekap Berdasarkan Faktur';
		}	

		//ambil query sesuai filter
		$data['sql']         = $sql;
		$data['grouplokasi'] = $grouplokasi;
		$data['whereLokasi'] = $whereLokasi;
		$data['tampil']      = $tampil;		
		$data['title']       = $title;		
		$data['tglAw']       = $tgl_aw;
		$data['tglAk']       = $tgl_ak;
		$data['excel']       = $this->input->post('excel');
		$data['filename']    = $this->input->post('file_name');
		$data['LIHATHARGA']  = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];

		$dir = 'reports/v_';
		$this->load->view($dir."report_transaksi_inventori_penyesuaian_stok.php",$data);
	}
}
