<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_satuan extends MY_Model{
	//public $maindb;

	public function __construct(){
		//$this->maindb = $this->load->database('default',true);	
	}
	
	public function getAll(){
		$data = $this->db->query("select * from MSATUAN");
		return $data->result();
	}
	
	public function laporan($filter){
	}
	
	public function comboGrid($pagination){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'SATUAN';
		}

		// $sql = "select IDSATUAN as ID, KODESATUAN as KODE, NAMASATUAN as NAMA
		// 		from MSATUAN a
		// 		where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
		// 				and (KODESATUAN like ? or NAMASATUAN like ?)
		// 				and 1=1 {$pagination['status']}
		// 		order by {$pagination['sort']} {$pagination['order']}
		// 		limit 0, 30";
		$sql = "select IDSATUAN as ID, SATUAN
				from MSATUAN a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and (SATUAN like ?)
						and 1=1 {$pagination['status']}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%'));

		$data['rows'] = $query->result();
		return $data;
	}

	public function dataGrid($pagination, $filter){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODESATUAN';
		}

		$data = [];

		$sql = "select count(*) as ROW
				from MSATUAN a
				left join MUSER b on a.USERENTRY = b.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and 1=1 {$filter['sql']}";
		$query = $this->db->query($sql, $filter['param']);

		$data['total'] = $query->row()->ROW;

		$sql = str_replace('count(*) as ROW', "b.USERNAME as USERBUAT, a.*", $sql)." order by {$pagination['sort']} {$pagination['order']} limit {$pagination['offset']}, {$pagination['rows']}";$query = $this->db->query($sql, $filter['param']);

		$data['rows'] = $query->result();

		return $data;
	}
		
	function simpan($id,$data,$edit){
		$this->db->trans_begin();
		
		if($edit){
			//update di database utama 
			$this->db->where("IDSATUAN",$id);
			$this->db->update('MSATUAN',$data);
		}else{
			//insert baru di database utama
			$this->db->insert('MSATUAN',$data);
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
		
		$this->db->where("IDPERUSAHAAN",$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				->where('IDSATUAN',$id)
				->delete('MSATUAN');
		if ($this->db->trans_status() === FALSE) { 
			$this->trans_rollback();
			return 'Data Satuan Tidak Dapat Dihapus, Data Sudah Digunakan Pada Transaksi'; 
		}
		
		$this->trans_commit();
		return '';
	}
	
	function cek_valid_kode($kode){
		$sql = "select KODESATUAN, NAMASATUAN 
				from MSATUAN a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and KODESATUAN = ?";
		$query = $this->db->query($sql, $kode)->row();
		if(isset($query)){
			return 'Kode Satuan Sudah Digunakan Oleh Satuan ('.$query->KODESATUAN.') '.$query->NAMASATUAN.', Kode Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function cek_valid_nama($nama,$kode=" "){
		$sql = "select KODESATUAN, NAMASATUAN 
				from MSATUAN a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and KODESATUAN <> ? 
						and NAMASATUAN = ?";
		$query = $this->db->query($sql, array($kode,$nama))->row();
		if(isset($query)){
			return 'Nama Satuan Sudah Digunakan Oleh Satuan ('.$query->KODESATUAN.') '.$query->NAMASATUAN.', Nama Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
}
?>