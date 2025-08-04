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
		if (isset($_SESSION[NAMAPROGRAM]['user'])) {
			$this->load->view('header');
			$this->load->view('footer');
		} else {
			redirect('auth/Login');
		}
		
	}
	//AWAL COPY
	private function splitAtUpperCase($s) {
        return preg_split('/(?=[A-Z])/', $s, -1, PREG_SPLIT_NO_EMPTY);
	}
	
	private function starts_with_upper($str) {
		$chr = mb_substr ($str, 0, 1, "UTF-8");
		return mb_strtolower($chr, "UTF-8") != $chr;
	}
	
	private function setNama($str){
		$a = str_split($str);

		$i = 0;
		$gede = 0;
		$dataGede = array();
		$max = strlen($str);
		while($i < $max){
			if ($this->starts_with_upper($a[$i])) {
				$gede++;
				$dataGede[] = $i;
			} else {
				if ($gede > 0) {
					$j = 1;
					$x = count($dataGede)-1;
					
					while ($j < $x) {
						$a[$dataGede[$j]] = strtolower($a[$dataGede[$j]]);

						$j++;
					}
					$gede = 0;
					$dataGede = array();
				}
			}
			
			$i++;
		}

		if ($gede > 0) {
			$j = 1;
			$x = count($dataGede);
			
			while ($j < $x) {
				$a[$dataGede[$j]] = strtolower($a[$dataGede[$j]]);
								
				$j++;                  
							
			}
			$gede = 0;
			$dataGede = array();
		}

		return implode('', $a);
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
		// untuk memproses view panduan

		// dapatkan folder
		$temp = explode('.',$row->URUTAN);
		$pathFile = 'panduan/';

		$folderName .= $this->model_master_menu->getByUrutan($temp[0].'.'.$temp[1]);
		$folderName .= ' '.$this->model_master_menu->getByUrutan($temp[0]);

		$pathFile .= $folderName.'/';

		// dapatkan nama file pdf
		$namaClass = $this->setNama($row->NAMACLASS);
		if ($row->JENIS <> '') {
			if (strpos(strtolower($row->NAMACLASS),"kas") > -1) {
				// $namaClass = ucfirst(strtolower($row->JENIS));
				$namaClass = str_replace('Kas', ucfirst(strtolower($row->JENIS)), $row->NAMACLASS);
			} else {
				// remove angka dari jenis
				$temp = preg_replace('/[0-9]+/', '', strtolower($row->JENIS));
				$namaClass = ucfirst($temp).''.$row->NAMACLASS;
			}
		}

		$temp = $this->splitAtUpperCase($this->setNama($namaClass));

		$temp = array_unique($temp);

		$pathFile .= implode(' ', $temp).'.pdf';

		// akhir script panduan
		//mengirim data jenis dari mmenu ke view
		$config['JENIS'] = $row->JENIS ?? '';	
		$str = str_replace('_', ' ', $row->NAMAVIEW);
		if ($row->JENIS!=''){
			$str = str_replace('Kas', ucfirst(strtolower($row->JENIS)), $str);
		}
		$data = ['menu'      => $str,
				 'kodemenu'  => $kodeMenu,
				 'ppnpersen' => $config["PPNPERSEN"],
				 'perusahaan'=> $_SESSION[NAMAPROGRAM]['NAMAPERUSAHAAN']];
		$data = array_merge($data,$config);
		
		$dataheader = ['hakaksesperusahaan' => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
					   'username' => $_SESSION[NAMAPROGRAM]['USERNAME'],
					   'pdfFile' => $pathFile,
					   'menu' => $row->NAMAMENU];
 
                       
		if (substr($row->NAMAVIEW,0,7) != "Laporan")
		{
			$this->load->view('header',$dataheader);
		}else{
			$this->load->view('header_css');
		}
	
		$this->load->view($pages, $data);
		
		if (substr($row->NAMAVIEW,0,7) != "Laporan")
		{
			$this->load->view('footer');
		}else{
			$this->load->view('footer_js');
		}	
	}
	
	//AKHIR COPY
	
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
