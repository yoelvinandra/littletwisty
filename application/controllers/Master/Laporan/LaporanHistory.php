<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanHistory extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
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
		$lokasi       = $this->input->post('txt_lokasi',[]);
		$user      = $this->input->post('txt_nilai_user');
		$kode      = $this->input->post('txt_nilai_kode');
		$barang      = explode(" | ",$this->input->post('txt_nilai_list_barang',[]))[0];
		$pilihtgl  =  $this->input->post('tgl');
		$tampil      = $this->input->post('data_tampil');

		if($pilihtgl == "TGLTRANS")
		{
			//TANGGAL TRANS
			$tglTrans = explode(" - ",$this->input->post('tglTrans'));
			
			$tgl_aw = ubah_tgl_firebird($tglTrans[0]);
			$tgl_ak = ubah_tgl_firebird($tglTrans[1]);
			$whereTanggalTrans = "and SUBSTR(HISTORYPROGRAM.TGLTRANS,1,10) between '$tgl_aw' and '$tgl_ak'";
		}
		else
		{
			//TANGGAL HISTORY
			$tglHistory = explode(" - ",$this->input->post('tglHistory'));
			
			$tgl_aw_h = ubah_tgl_firebird($tglHistory[0]);
			$tgl_ak_h = ubah_tgl_firebird($tglHistory[1]);
			$whereTanggalHistory = "and SUBSTR(HISTORYPROGRAM.TGLENTRY,1,10) between '$tgl_aw_h' and '$tgl_ak_h'";
		}
		
		//USER
		$whereUser = "and MUSER.USERNAME LIKE '%$user%' ";
		
		//KODE
		$whereKode = "and HISTORYPROGRAM.KODETRANS LIKE '%$kode%' ";	
		
		//LOKASI
		$whereLokasi = str_replace("]","", str_replace("[","",json_encode($lokasi)));
		
		//PERUSAHAAN
		$wherePerusahaan = "and HISTORYPROGRAM.IDPERUSAHAAN=$perusahaan";
        if($tampil == "REGISTER")
        {
		    $sql = "SELECT *, HISTORYPROGRAM.TGLENTRY as TGLHISTORY FROM HISTORYPROGRAM 
				LEFT JOIN MUSER ON MUSER.USERID = HISTORYPROGRAM.USERENTRY
				where (1=1) $whereTanggalHistory $whereTanggalTrans $wherePerusahaan $whereUser $whereKode ORDER BY SUBSTR(HISTORYPROGRAM.TGLENTRY,1,10), KODETRANS ASC";
        }
		
		$data['sql']      = $sql;
		$data['excel']    = $this->input->post('excel');
		$data['filename'] = $this->input->post('file_name');
		$data['grouplokasi'] = $grouplokasi;
		$data['whereLokasi'] = $whereLokasi;
		$data['tampil']   = $tampil;
		$data['kodeBarang'] = $barang;
		$dir = 'reports/v_';
		$this->load->view($dir."report_master_history.php",$data);
	}
}
