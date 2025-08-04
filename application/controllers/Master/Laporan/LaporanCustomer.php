<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanCustomer extends MY_Controller {
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
		$wherePerusahaan = "and MCUSTOMER.idperusahaan=$perusahaan";

		//STATUS
		$whereStatus .= "and MCUSTOMER.STATUS LIKE '%".$jenisStatus[0]."%'";

		$sql = "select * from msupplier  
		        where (1=1 $whereFilter) $wherePerusahaan $whereStatus order by namasupplier asc";

		$data['sql']      = $sql;
		$data['excel']    = $this->input->post('excel');
		$data['filename'] = $this->input->post('file_name');
		
		$dir = 'reports/v_';
		$this->load->view($dir."report_master_customer.php",$data);
	}
}
