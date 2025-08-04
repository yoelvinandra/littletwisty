<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Model_master_perusahaan extends MY_Model{
	
	public function __construct()
	{
		
	}

	public function comboGrid($pagination){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODEPERUSAHAAN';
		}

		$sql = "select a.IDPERUSAHAAN as ID, a.KODEPERUSAHAAN as KODE, a.NAMAPERUSAHAAN as NAMA
				from MPERUSAHAAN a
				where b.IDUSER = {$_SESSION[NAMAPROGRAM]['IDUSER']} and
					  (a.KODEPERUSAHAAN like ? or a.NAMAPERUSAHAAN like ?)
					  {$pagination['status']}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%'));

		$data['rows'] = $query->result();
		return $data;
	}

	public function dataGrid($pagination, $filter){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODEPERUSAHAAN';
		}

		$data = [];

		$sql = "select count(*) as ROW
				from MPERUSAHAAN a
				left join MUSER b on a.USERENTRY = b.USERID
				where 1=1 {$filter['sql']}";
		$query = $this->db->query($sql, $filter['param']);
		$data['total'] = $query->row()->ROW;

		$sql = str_replace('count(*) as ROW', "b.USERNAME as USERBUAT, a.*", $sql)
						." order by {$pagination['sort']} {$pagination['order']} limit {$pagination['offset']}, {$pagination['rows']}";
		$query = $this->db->query($sql, $filter['param']);
		$data['rows'] = $query->result();

		return $data;
	}

	function simpan($id,$data,$edit){
		$this->db->trans_begin();
		
		if($edit){
			$this->db->where("IDPERUSAHAAN",$id);
			$this->db->update('MPERUSAHAAN',$data);
		}else{
			$this->db->insert('MPERUSAHAAN',$data);
		}
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 'Gagal menyimpan pada database';
		}
		
		$this->db->trans_commit();
		return '';
	}
	
	function hapus($id){
		$this->db->trans_begin();
		
		$this->db->where('IDPERUSAHAAN',$id)
				 ->delete('MPERUSAHAAN');
		if ($this->db->trans_status() === FALSE) { 
			$this->rollback($DB);
			return 'Data Perusahaan Tidak Dapat Dihapus, Data Sudah Digunakan Pada Transaksi'; 
		}
		
		$this->db->trans_commit();
		return '';
	}

    function getPerusahaan($id) {
		return $this->db->where("IDPERUSAHAAN", $id)->get("MPERUSAHAAN")->row();
	}
	
	function getAllPerusahaan(){
		return $this->db->get("MPERUSAHAAN")->result();
	}

	function getPerusahaanPerUser($id) {
		$sql = "select A.IDPERUSAHAAN, B.KODEPERUSAHAAN,B.NAMAPERUSAHAAN
				from MUSER A
				inner join MPERUSAHAAN B ON A.IDPERUSAHAAN = B.IDPERUSAHAAN
				where A.IDUSER = ?";
		$query = $this->db->query($sql, array($id));

		return $query->result();
	}

	function getPerusahaanLogin($id) {
		$sql = "select a.IDPERUSAHAAN, c.KODEPERUSAHAAN, c.NAMAPERUSAHAAN, c.TEMAWARNA,c.WARNAFONT
				from muser a				
				inner join MPERUSAHAAN c ON a.IDPERUSAHAAN = c.IDPERUSAHAAN
				where A.userid = ?";
		$query = $this->db->query($sql, array($id));

		return $query->row();
	}

	function cek_valid_kode($kode){
		$sql = "select KODEPERUSAHAAN, NAMAPERUSAHAAN 
				from MPERUSAHAAN a
				where KODEPERUSAHAAN = ?";
		$query = $this->db->query($sql, $kode)->row();
		if(isset($query)){
			return 'Kode Perusahaan Sudah Digunakan Oleh Perusahaan ('.$query->KODEPERUSAHAAN.') '.$query->NAMAPERUSAHAAN.', Kode Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function cek_valid_nama($nama,$kode=" "){
		$sql = "select KODEPERUSAHAAN, NAMAPERUSAHAAN 
				from MPERUSAHAAN a 
				where KODEPERUSAHAAN <> ? and 
					  NAMAPERUSAHAAN = ?";
		$query = $this->db->query($sql, array($kode,$nama))->row();
		if(isset($query)){
			return 'Nama Perusahaan Sudah Digunakan Oleh Perusahaan ('.$query->KODEPERUSAHAAN.') '.$query->NAMAPERUSAHAAN.', Nama Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
}