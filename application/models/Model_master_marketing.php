<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_marketing extends MY_Model{

	public function getAll(){
		$data = $this->db->query("select * from MMARKETING where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}");
		return $data->result();
	}
	
	public function laporan($filter){
	}
	
	public function comboGrid(){
		$sql = "select CONCAT(a.KODEMARKETING,' | ',a.NAMAMARKETING) as VALUE, CONCAT(a.KODEMARKETING,' - ',a.NAMAMARKETING) as TEXT
				from MMARKETING a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by a.NAMAMARKETING";
		$query = $this->db->query($sql);

		$data['rows'] = $query->result();
		return $data;
	}

	public function dataGrid(){

		$data = [];

		$sql = "select b.USERNAME as USERBUAT, a.*
				from MMARKETING a
				left join MUSER b on a.USERENTRY = b.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				order by a.NAMAMARKETING";
		$query = $this->db->query($sql);
        $data['rows'] = $query->result();
        
		return $data;
	}
	//TAMBAH
	function getDataHeader($idtrans)
	{
		$sql = "select b.USERNAME as USERBUAT, a.*
				from MMARKETING a
				left join MUSER b on a.USERENTRY = b.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				and a.IDMARKETING = $idtrans ";
		
		$query = $this->db->query($sql)->row();

		return $query;
    }
    
	function simpan($id,$data,$edit){
		$this->db->trans_begin();
		
		if($edit){
			$this->db->where("IDMARKETING",$id);
			$this->db->update('MMARKETING',$data);
		}else{
			$this->db->insert('MMARKETING',$data);
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
				->where('IDMARKETING',$id)
				->delete('MMARKETING');
		if ($this->db->trans_status() === FALSE) { 
			$this->trans_rollback();
			return 'Data Marketing Tidak Dapat Dihapus, Data Sudah Digunakan Pada Transaksi'; 
		}
		
		$this->db->trans_commit();
		return '';
	}
	
	function cek_valid_kode($kode){
		$sql = "select KODEMARKETING, NAMAMARKETING 
				from MMARKETING a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and KODEMARKETING = ?";
		$query = $this->db->query($sql, $kode)->row();
		if(isset($query)){
			return 'Kode Marketing Sudah Digunakan Oleh Marketing ('.$query->KODEMARKETING.') '.$query->NAMAMARKETING.', Kode Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function cek_valid_nama($nama,$kode=" "){
		$sql = "select KODEMARKETING, NAMAMARKETING 
				from MMARKETING a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and KODEMARKETING <> ? 
						and NAMAMARKETING = ?";
		$query = $this->db->query($sql, array($kode,$nama))->row();
		if(isset($query)){
			return 'Nama Marketing Sudah Digunakan Oleh Marketing ('.$query->KODEMARKETING.') '.$query->NAMAMARKETING.', Nama Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
}
?>