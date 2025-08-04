<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanBarang extends MY_Controller {
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
		$lokasi      = $this->input->post('txt_lokasi',[]);
		$jenisStatus = $this->input->post('cbStatus',[]);
		$filter      = $this->input->post('data_filter');
		 
		//KONVERSI JADI QUERY
		$whereFilter = where_laporan($filter);
		
		//PERUSAHAAN
		$wherePerusahaan = "and MBARANG.idperusahaan=$perusahaan";

		//STATUS
		$whereStatus .= "and MBARANG.STATUS LIKE '%".$jenisStatus[0]."%'";


		$sql = "select mbarang.*
				from mbarang 
				where (1=1 $whereFilter) $wherePerusahaan $whereStatus
				group by namabarang
				order by  SUBSTRING(URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(URUTANTAMPIL, 2) AS UNSIGNED) ASC";
		

		$data['sql']      = $sql;
		$data['excel']    = $this->input->post('excel');
		$data['filename'] = $this->input->post('file_name');
		
		$dir = 'reports/v_';
		$this->load->view($dir."report_master_barang.php",$data);
	}
}
