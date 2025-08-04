<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Satuan extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(['model_master_satuan']);
	}
	
	public function comboGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_satuan->comboGrid($this->setPaginationGrid());
		
		echo json_encode($response);
	}
}
