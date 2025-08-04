<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanPerusahaan extends MY_Controller {
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
		$perusahaan= $this->input->post('txt_perusahaan',[]);
		$status    = $this->input->post('rbStatus');
		
		if($status == "AKTIF"){
			$wherestatus = " and status = 1"; 	
		}
		else if($status == "TIDAK"){
			$wherestatus = " and status = 0"; 	
		}
		$whereperusahaan = count($perusahaan)>0 ? " and (kodeperusahaan='".implode("' or kodeperusahaan='", $lokasi)."')" : '';
		
		

		$sql = "select * from mperusahaan where 1=1 ".$whereperusahaan.$wherestatus." order by kodeperusahaan asc";
		
		$data['sql']      = $sql;
		$data['excel']    = $this->input->post('excel');
		$data['filename'] = $this->input->post('file_name');
		
		$dir = 'reports/v_';
		
		$this->load->view($dir."report_master_perusahaan.php",$data);
	}
}
