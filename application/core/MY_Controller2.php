<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('header');
		$this->load->view('footer');
	}

	// fungsi untuk menampilkan page
	public function setPage($kodeMenu = '',$config = array())
	{
		// cek jika user tidak punya hak akses maka munculkan warning
		if ($kodeMenu == '')
			show_404();
		if(is_null($_SESSION[NAMAPROGRAM]['user']))
			redirect(base_url());
		// dapatkan namamenu, namaclass dan tipe
		$row = $this->model_master_menu->get($kodeMenu);

		// cek jika kodemenu tidak ada dalam master menu maka munculkan warning
		if (is_null($row))
			show_404();

		$dir = 'pages/v';

		// buat nama file
		// jika nama menu mengandung & / ' ' akan diganti otomatis menjadi '_'
		$tempFile = str_replace(array(' & ', ' / ', ' '), '_', $row->NAMAVIEW);

		//mengirim data jenis dari mmenu ke view
		$config['JENIS'] = $row->JENIS ?? '';

		$pages = strtolower($dir . '_' . $tempFile);
		if (strpos(strtoupper($row->NAMAMENU),"LAPORAN")>-1){
			$str = $row->NAMAMENU;
		} else {	
			$str = str_replace('_', ' ', $row->NAMAVIEW);
			if ($row->JENIS!=''){
				$str = str_replace($row->NAMACLASS, ucfirst(strtolower($row->JENIS)), $str);
			}
		}	

		$data = ['menu'      => $str,
				 'kodemenu'  => $kodeMenu,
				 'perusahaan'=> $_SESSION[NAMAPROGRAM]['NAMAPERUSAHAAN']];
		$data = array_merge($data,$config);
		
		$dataheader = ['hakaksesperusahaan' => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
					   'username' => $_SESSION[NAMAPROGRAM]['USERNAME'],
					   'menu' => $row->NAMAMENU];
		$this->load->view('header',$dataheader);
		$this->load->view($pages, $data);
		$this->load->view('footer');
	}

	public function setPaginationGrid()
	{
		$page   = $this->input->post('page');
		$rows   = $this->input->post('rows');
		$order  = $this->input->post('order');
		$sort   = $this->input->post('sort');
		$q      = $this->input->post('q');
		$status = $this->input->get('status');

		if (is_null($page))
			$page = 1;

		if (is_null($rows))
			$rows = 20;

		if (is_null($order))
			$order = 'asc';
		
		if (is_null($q))
			$q = '';
		else
			$q = strtoupper($q);
		
		if (is_null($status) && $status=='all')
			$status = '';
		else
			$status = 'and status=1';
		
		$offset = ($page - 1) * $rows;

		return [
			'page'   => $page,
			'rows'   => $rows,
			'order'  => $order,
			'sort'   => $sort,
			'q'      => $q,
			'status' => $status,
			'offset' => $offset
		];
	}

	public function setFilterGrid()
	{
		$postFilter = $this->input->post('filterRules');

		if (is_null($postFilter)) {
			$filter = [];
		} else {
			$filter = json_decode($postFilter);
		}
		$sqlFilter = '';
		$sqlParam = array();
		if (count($filter) > 0) {
			foreach ($filter as $item) {
				$field = strtoupper($item->field);
				if($field == "CATATAN")$field = "a.CATATAN";
				if($field == "TGLENTRY")$field = "a.TGLENTRY";
				if($field == "USERENTRY")$field = "a.USERENTRY";
				if($field == "USERBUAT")$field = "a.USERENTRY";
				if($field == "USERHAPUS")$field = "a.USERBATAL";
				if($field == "STATUS")$field = "a.STATUS";
				$sqlFilter .= 'and '.$field.' like ? ';
				$sqlParam[] = '%'.str_replace(" ","%",$item->value).'%';
			}
			if ($sqlFilter <> '') {
				$sqlFilter = ' and ('.substr($sqlFilter, 3).') ';
			}
		}
		return [
			'sql'   => $sqlFilter,
			'param' => $sqlParam,
		];
	}
}
