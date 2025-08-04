<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_lokasi extends MY_Model{
	public function getAll($pagination="",$iduser="") {
		if($iduser != ""){
			$pilihLokasi = "if((SELECT COUNT(*) from MUSERLOKASI WHERE MUSERLOKASI.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and MUSERLOKASI.IDUSER = {$iduser} AND MUSERLOKASI.IDLOKASI = MLOKASI.IDLOKASI) > 0,1,0 ) ";
		}
		else{
			$pilihLokasi = "0";
		}
		//CEK AKSES USER LOKASI
		$sql = "SELECT MLOKASI.*, $pilihLokasi AS PILIHLOKASI 
				FROM MLOKASI
				where MLOKASI.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					order by MLOKASI.ONLINE desc, MLOKASI.NAMALOKASI";
				
		$query = $this->db->query($sql);
		$data['rows'] = $query->result();
		return $data;

		//return $this->db->get("MLOKASI")->result();
	}

	public function getLokasiDefault(){
		$sql = "select * from MLOKASI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and a.LOKASIDEFAULT = 1";
		return $this->db->query($sql)->row();
	}
	
	public function getLokasi($idlokasi){
		$sql = "select a.kodelokasi from MLOKASI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and a.idlokasi = $idlokasi
				";
		return $this->db->query($sql)->row();
	}
	
	public function getLokasiOnline(){
		$sql = "select a.* from MLOKASI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and a.ONLINE = 1";
		return $this->db->query($sql)->row();
	}
	
	public function setLokasi(){
		$sql = "select a.IDLOKASI from MLOKASI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDLOKASI in (
							select IDLOKASI
							from MUSERLOKASI
							where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDUSER = {$_SESSION[NAMAPROGRAM]['IDUSER']}
						) LIMIT 1";
		return $this->db->query($sql)->row()->IDLOKASI;
	}
	
	public function comboGrid(){

		/*$sql = "select IDLOKASI as ID, KODELOKASI as KODE, NAMALOKASI as NAMA, ALAMAT, KOTA, PROPINSI, NEGARA
				from MLOKASI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and (KODELOKASI like ? or NAMALOKASI like ?)
						and IDLOKASI in (
							select IDLOKASI
							from MUSERLOKASI
							where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDUSER = {$_SESSION[NAMAPROGRAM]['IDUSER']}
						)
						and 1=1 {$pagination['status']}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%'));*/
		
		$sql = "select a.IDLOKASI as VALUE, a.NAMALOKASI as TEXT,a.*
				from MLOKASI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and a.IDLOKASI in (
							select IDLOKASI
							from MUSERLOKASI
							where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDUSER = {$_SESSION[NAMAPROGRAM]['IDUSER']}
						)	
				order by a.ONLINE desc, A.NAMALOKASI
				";
		$query = $this->db->query($sql);

		$data['rows'] = $query->result();
		return $data;
	}
	
	public function cekGroupLokasi($group){

		/*$sql = "select IDLOKASI as ID, KODELOKASI as KODE, NAMALOKASI as NAMA, ALAMAT, KOTA, PROPINSI, NEGARA
				from MLOKASI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and (KODELOKASI like ? or NAMALOKASI like ?)
						and IDLOKASI in (
							select IDLOKASI
							from MUSERLOKASI
							where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDUSER = {$_SESSION[NAMAPROGRAM]['IDUSER']}
						)
						and 1=1 {$pagination['status']}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%'));*/
		$data['rows'] = [];
		foreach($group as $item)
		{
		    $sql = "select ifnull(GROUP_CONCAT(a.IDLOKASI ORDER BY A.ONLINE DESC, A.NAMALOKASI ),'') as VALUE, '$item' as TEXT
				from MLOKASI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and a.IDLOKASI in (
							select IDLOKASI
							from MUSERLOKASI
							where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDUSER = {$_SESSION[NAMAPROGRAM]['IDUSER']}
						)
						and a.GROUPLOKASI like '%$item%'
				order by a.ONLINE desc, A.NAMALOKASI
				";
			
		    $query = $this->db->query($sql)->row();
		    array_push($data['rows'],$query);
		}
		return $data;
	}
	
	public function comboGridMaster(){

		/*$sql = "select IDLOKASI as ID, KODELOKASI as KODE, NAMALOKASI as NAMA, ALAMAT, KOTA, PROPINSI, NEGARA
				from MLOKASI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and (KODELOKASI like ? or NAMALOKASI like ?)
						and IDLOKASI in (
							select IDLOKASI
							from MUSERLOKASI
							where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDUSER = {$_SESSION[NAMAPROGRAM]['IDUSER']}
						)
						and 1=1 {$pagination['status']}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%'));*/
		
		$sql = "select CONCAT(a.KODELOKASI,' | ',a.NAMALOKASI) as VALUE, CONCAT(a.KODELOKASI,' - ',a.NAMALOKASI) as TEXT
				from MLOKASI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					and IDLOKASI in (
							select IDLOKASI
							from MUSERLOKASI
							where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDUSER = {$_SESSION[NAMAPROGRAM]['IDUSER']}
						)
					order by a.ONLINE desc, A.NAMALOKASI";
		$query = $this->db->query($sql);

		$data['rows'] = $query->result();
		return $data;
	}

	public function cekLokasiDefault($kodelokasi){
		$sql = "select count(*) as LOKASIDEFAULT from MLOKASI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and a.LOKASIDEFAULT = 1
						and a.KODELOKASI != '{$kodelokasi}'";
		return $this->db->query($sql)->row()->LOKASIDEFAULT;
	}

	public function dataGrid(){

		$data = [];
		$sql = "select a.*, b.USERNAME as USERBUAT
				from MLOKASI a
				left join MUSER b on a.USERENTRY = b.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
					order by a.ONLINE desc, A.NAMALOKASI
				";
						
		$query = $this->db->query($sql);
		$data['rows'] = $query->result();

		return $data;
	}

	function simpan($id,$data,$edit){
		$this->db->trans_begin();
		
		if($data['LOKASIDEFAULT'] == 1){
			$this->db->set("LOKASIDEFAULT",0)
					->where("IDPERUSAHAAN",$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					->update('MLOKASI');
		}

		if($edit){
			$this->db->where("IDPERUSAHAAN",$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					 ->where("IDLOKASI",$id);
			$this->db->update('MLOKASI',$data);
		}else{
			$this->db->insert('MLOKASI',$data);
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
				 ->where('IDLOKASI',$id)
				 ->delete('MLOKASI');
		if ($this->db->trans_status() === FALSE) { 
			$this->trans_rollback();
			return 'Data Lokasi Tidak Dapat Dihapus, Data Sudah Digunakan Pada Transaksi'; 
		}
		
		$this->db->trans_commit();
		return '';
	}
	
	function cek_valid_kode($kode){
		$sql = "select KODELOKASI, NAMALOKASI 
				from MLOKASI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					  and KODELOKASI = ?";
		$query = $this->db->query($sql, $kode)->row();
		if(isset($query)){
			return 'Kode Lokasi Sudah Digunakan Oleh Lokasi ('.$query->KODELOKASI.') '.$query->NAMALOKASI.', Kode Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function cek_valid_nama($nama,$kode=" "){
		$sql = "select KODELOKASI, NAMALOKASI 
				from MLOKASI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
					  and KODELOKASI <> ? 
					  AND NAMALOKASI = ?  ";
		$query = $this->db->query($sql, array($kode,$nama))->row();
		if(isset($query)){
			return 'Nama Lokasi Sudah Digunakan Oleh Lokasi ('.$query->KODELOKASI.') '.$query->NAMALOKASI.', Nama Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}

	function getLokasiPerUser($id) {
		$sql = "select a.IDLOKASI, b.KODELOKASI, b.NAMALOKASI
				from MUSERLOKASI a
				inner join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				      and a.IDUSER = ?";
		$query = $this->db->query($sql, array($id));

		return $query->result();
	}
}
?>