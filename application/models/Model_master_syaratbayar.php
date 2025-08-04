<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_syaratbayar extends MY_Model{

	public function getAll(){
		$data = $this->db->query("select * from MSYARATBAYAR");
		return $data->result();
	}
	
	public function laporan($filter){
	}
	
	public function comboGrid(){
		/*if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODESYARATBAYAR';
		}

		$sql = "select IDSYARATBAYAR as ID, KODESYARATBAYAR as KODE, NAMASYARATBAYAR as NAMA,SELISIH
				from MSYARATBAYAR a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and (KODESYARATBAYAR like ? or NAMASYARATBAYAR like ?)
						and 1=1 {$pagination['status']}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%'));
		*/
		
		$sql = "select CONCAT(a.KODESYARATBAYAR,' | ',a.NAMASYARATBAYAR,' | ',a.SELISIH,' | ',a.IDSYARATBAYAR) as VALUE, CONCAT(a.KODESYARATBAYAR,' - ',a.NAMASYARATBAYAR) as TEXT
				from MSYARATBAYAR a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				limit 0, 30";
		$query = $this->db->query($sql);
		
		$data['rows'] = $query->result();
		return $data;
	}

	public function dataGrid($pagination, $filter){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODESYARATBAYAR';
		}

		$data = [];

		$sql = "select count(*) as ROW
				from MSYARATBAYAR a
				left join MUSER b on a.USERENTRY = b.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and 1=1	{$filter['sql']}";
		$query = $this->db->query($sql, $filter['param']);

		$data['total'] = $query->row()->ROW;

		$sql = str_replace('count(*) as ROW', "b.USERNAME as USERBUAT, a.*", $sql)." order by {$pagination['sort']} {$pagination['order']} limit {$pagination['offset']}, {$pagination['rows']}";
		$query = $this->db->query($sql, $filter['param']);

		$data['rows'] = $query->result();

		return $data;
	}
		
	function simpan($id,$data,$edit){
		$this->db->trans_begin();
		
		if($edit){
			$this->db->where("IDSYARATBAYAR",$id);
			$this->db->update('MSYARATBAYAR',$data);
		}else{
			$this->db->insert('MSYARATBAYAR',$data);
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
				->where('IDSYARATBAYAR',$id)
				->delete('MSYARATBAYAR');
		if ($this->db->trans_status() === FALSE) { 
			$this->trans_rollback();
			return 'Data Syarat Bayar Tidak Dapat Dihapus, Data Sudah Digunakan Pada Transaksi'; 
		}
		
		$this->db->trans_commit();
		return '';
	}
	
	function cek_valid_kode($kode){
		$sql = "select KODESYARATBAYAR, NAMASYARATBAYAR 
				from MSYARATBAYAR a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and KODESYARATBAYAR = ?";
		$query = $this->db->query($sql, $kode)->row();
		if(isset($query)){
			return 'Kode Syarat Bayar Sudah Digunakan Oleh Syarat Bayar ('.$query->KODESYARATBAYAR.') '.$query->NAMASYARATBAYAR.', Kode Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function cek_valid_nama($nama,$kode=" "){
		$sql = "select KODESYARATBAYAR, NAMASYARATBAYAR 
				from MSYARATBAYAR a 
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and KODESYARATBAYAR <> ? 
						and NAMASYARATBAYAR = ?";
		$query = $this->db->query($sql, array($kode,$nama))->row();
		if(isset($query)){
			return 'Nama Syarat Bayar Sudah Digunakan Oleh Syarat Bayar ('.$query->KODESYARATBAYAR.') '.$query->NAMASYARATBAYAR.', Nama Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
}
?>