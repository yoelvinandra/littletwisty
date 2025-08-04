<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	
	public function connectDB($perusahaan){
		$database = [];
		$config['dbdriver'] = "mysqli";
		$config['dbprefix'] = "";
		$config['pconnect'] = FALSE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = "";
		$config['char_set'] = "utf8";
		$config['dbcollat'] = "utf8_general_ci";
		
		//untuk connect setiap perusahaan
		if(count($perusahaan)>0){
			foreach($perusahaan as $p){
				$query = $this->db->where("IDPERUSAHAAN", $p)->get("MPERUSAHAAN")->row();
				$config['hostname'] = $query->HOST;
				$config['username'] = $query->USER;
				$config['password'] = $query->PASS;
				$config['database'] = $query->DB;
				$database[$p] = $this->load->database($config, TRUE);
			}
		}
		return $database;
	}

	public function begin($DB)
	{
		//untuk begin pada setiap db kecil
		if(count($DB)>0){
			foreach($DB as $d){
				//begin transaction untuk db kecil
				$d->trans_begin();
			}
		}
		$this->db->trans_begin();
	}
	public function commit($DB = null)
	{
		//untuk commit pada setiap db kecil
		if(count($DB)>0){
			foreach($DB as $d){
				//commit transaction untuk db kecil
				$d->trans_commit();
			}
		}
		$this->db->trans_commit();
	}
	public function rollback($perusahaan = null)
	{
		//untuk rollback pada setiap db kecil
		if(count($DB)>0){
			foreach($DB as $d){
				//rollback transaction untuk db kecil
				$d->trans_rollback();
			}
		}
		$this->db->trans_rollback();
	}

}
