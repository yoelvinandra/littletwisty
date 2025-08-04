<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_supplier extends MY_Model{

	public function getAll(){
		$data = $this->db->query("select * from MSUPPLIER");
		return $data->result();
	}
	
	public function laporan($filter){
		$where = "";
		$filterparam = array();
		if($filter['rdsupp'] == 1){
			//untuk nama dan kode textbox
			
			$filter['nama'] = str_replace(' ','%',$filter['nama']);
			$filter['nama'] = '%'.$filter['nama'].'%';
			$where .= "and a.NAMASUPPLIER LIKE ?";
			array_push($filterparam,$filter['nama']);
			
			$filter['kode'] = str_replace(' ','%',$filter['kode']);
			$filter['kode'] = '%'.$filter['kode'].'%';
			$where .= "and a.KODESUPPLIER LIKE ?";
			array_push($filterparam,$filter['kode']);
			
		}else if($filter['rdsupp'] == 2){
			//untuk list id yang ditampilkan
			$where .= "and (a.IDSUPPLIER ='' ";
			foreach($filter['listid'] as $kode){
				$where .= "or a.IDSUPPLIER = ?";
				array_push($filterparam,$kode);
			}
			$where .= ")";
			
		}else if($filter['rdsupp'] == 3){
			//untuk range yang ditampilkan
			$where .= "and a.NAMASUPPLIER >= ?";
			array_push($filterparam,$filter['namaawal']);
			$where .= "and a.NAMASUPPLIER <= ?";
			array_push($filterparam,$filter['namaakhir']);
		}
		$sql =  "select * 
				from MSUPPLIER a
				left join MSYARATBAYAR b on a.IDSYARATBAYAR = b.IDSYARATBAYAR
				where 1=1 $where
				order by a.NAMASUPPLIER ASC";
		$query = $this->db->query($sql,$filterparam);
		return $query->result();
	}
	
	public function comboGrid(){
		/*if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODESUPPLIER';
		}

		$sql = "select KODESUPPLIER as KODE, CONCAT(NAMASUPPLIER,' ',IF(BADANUSAHA!='-', CONCAT(', ',BADANUSAHA),' ')) as NAMA,
					IDSUPPLIER as ID,IF(KOTA IS NULL, ALAMAT,CONCAT(ALAMAT,',',KOTA)) AS ALAMAT, KOTA,TELP,NOREKENING,CONTACTPERSON,
					TELPCP,IDSYARATBAYAR,NPWP
				from MSUPPLIER  
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and (KODESUPPLIER like ? or NAMASUPPLIER like ?)
						and 1=1	{$pagination['status']}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%'));
		*/
		
		$sql = "select CONCAT(KODESUPPLIER,' | ',NAMASUPPLIER,' | ',IF(ALAMAT is null, '',ALAMAT),' | ',IF(TELP is null, '',TELP)) as VALUE, CONCAT(KODESUPPLIER,' - ',NAMASUPPLIER) as TEXT
				from MSUPPLIER  
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				ORDER BY NAMASUPPLIER";
		$query = $this->db->query($sql);	
		$data['rows'] = $query->result();
		return $data;
	}
	
	public function comboGridTransaksi(){	
		$sql = "select IDSUPPLIER as ID,KODESUPPLIER as KODE,NAMASUPPLIER as NAMA,ALAMAT,TELP
				from MSUPPLIER  
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				ORDER BY NAMASUPPLIER";
		$query = $this->db->query($sql);	
		$data['rows'] = $query->result();
		return $data;
	}
	
	public function comboGridApproveLain($pagination){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODESUPPLIER';
		}

		$sql = "select KODESUPPLIER as KODE, NAMASUPPLIER as 	NAMA,
					IDSUPPLIER as ID,IF(KOTA IS NULL, ALAMAT,CONCAT(ALAMAT,',',KOTA)) AS ALAMAT, KOTA,TELP,NOREKENING,CONTACTPERSON,
					TELPCP,IDSYARATBAYAR,NPWP
				from MSUPPLIER a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					and (KODESUPPLIER like ? or NAMASUPPLIER like ?)
					and a.IDSUPPLIER in (
						select a.IDSUPPLIER1 as IDSUPPLIER
						from TLAINPRDTL a
						left join TLAINPR b on a.IDLAINPR = b.IDLAINPR
						where a.APPROVE1 = 1 and b.STATUS = 'S' 
							and b.IDLOKASI = ?
					union all
						select a.IDSUPPLIER2 as IDSUPPLIER
						from TLAINPRDTL a
						left join TLAINPR b on a.IDLAINPR = b.IDLAINPR
						where a.APPROVE2 = 1 and b.STATUS = 'S'
							and b.IDLOKASI = ?
					union all
						select a.IDSUPPLIER3 as IDSUPPLIER
						from TLAINPRDTL a
						left join TLAINPR b on a.IDLAINPR = b.IDLAINPR
						where a.APPROVE3 = 1 and b.STATUS = 'S'
							and b.IDLOKASI = ?
					) {$pagination['status']}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%',$pagination['lokasi'],$pagination['lokasi'],$pagination['lokasi']));

		$data['rows'] = $query->result();
		return $data;
	}

	public function comboGridApproveAset($pagination){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODESUPPLIER';
		}

		$sql = "select KODESUPPLIER as KODE, NAMASUPPLIER as 	NAMA,
					IDSUPPLIER as ID,IF(KOTA IS NULL, ALAMAT,CONCAT(ALAMAT,',',KOTA)) AS ALAMAT, KOTA,TELP,NOREKENING,CONTACTPERSON,
					TELPCP,IDSYARATBAYAR,NPWP
				from MSUPPLIER a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					and (KODESUPPLIER like ? or NAMASUPPLIER like ?)
					and a.IDSUPPLIER in (
						select a.IDSUPPLIER1 as IDSUPPLIER
						from TASETPRDTL a
						left join TASETPR b on a.IDASETPR = b.IDASETPR
						where a.APPROVE1 = 1 and b.STATUS = 'S' 
							and b.IDLOKASI = ?
					union all
						select a.IDSUPPLIER2 as IDSUPPLIER
						from TASETPRDTL a
						left join TASETPR b on a.IDASETPR = b.IDASETPR
						where a.APPROVE2 = 1 and b.STATUS = 'S'
							and b.IDLOKASI = ?
					union all
						select a.IDSUPPLIER3 as IDSUPPLIER
						from TASETPRDTL a
						left join TASETPR b on a.IDASETPR = b.IDASETPR
						where a.APPROVE3 = 1 and b.STATUS = 'S'
							and b.IDLOKASI = ?
					) {$pagination['status']}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%',$pagination['lokasi'],$pagination['lokasi'],$pagination['lokasi']));

		$data['rows'] = $query->result();
		return $data;
	}

	public function comboGridBadanUsaha($pagination){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODECUSTOMER';
		}

		$sql = "select distinct a.BADANUSAHA
				from MSUPPLIER a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
						and a.BADANUSAHA != '' 
						and a.BADANUSAHA like ?
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%'));

		$data['rows'] = $query->result();
		return $data;
	}

	public function namaReferensi(){
		$sql = "select KODESUPPLIER as KODE, NAMASUPPLIER as NAMA,IDSUPPLIER as ID,IF(KOTA IS NULL, ALAMAT,CONCAT(ALAMAT,',',KOTA)) AS ALAMAT, KOTA,TELP, IDSYARATBAYAR, CONTACTPERSON
				from MSUPPLIER a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and (KODESUPPLIER like ? or NAMASUPPLIER like ?)
						and 1=1 {$pagination['status']}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%'));

		$data['rows'] = $query->result();
		return $data;
	}

	public function dataGrid(){
		
		$data = [];

		$sql = "select c.USERNAME as USERBUAT, a.*, concat(a.NAMASUPPLIER,', ',a.BADANUSAHA) as NAMASUPPLIER2, b.NAMASYARATBAYAR
				from MSUPPLIER a
				left join MSYARATBAYAR b on a.IDSYARATBAYAR = b.IDSYARATBAYAR 
				left join MUSER c on a.USERENTRY = c.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				ORDER BY a.NAMASUPPLIER";
		$query = $this->db->query($sql);
		$data['rows'] = $query->result();

		return $data;
	}
		
	function simpan($id,$data,$edit){
		$this->db->trans_begin();
		
		if($edit){
			$this->db->where("IDSUPPLIER",$id);
			$this->db->update('MSUPPLIER',$data);
			
		}else{
			$this->db->insert('MSUPPLIER',$data);
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
		
		if(checkSupplierPadaTransaksi($id) == 1)
		{
		    return 'Data Supplier Tidak Dapat Dihapus, Data Sudah Digunakan Pada Transaksi'; 
		}
		
		$this->db->where("IDPERUSAHAAN",$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				->where('IDSUPPLIER',$id)
				->delete('MSUPPLIER');
		if ($this->db->trans_status() === FALSE) { 
			$this->trans_rollback();
			return 'Data Supplier Tidak Dapat Dihapus'; 
		}
		
		$this->db->trans_commit();
		return '';
	}
	
	function cek_valid_kode($kode){
		$sql = "select KODESUPPLIER, NAMASUPPLIER 
				from MSUPPLIER a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and KODESUPPLIER = ?";
		$query = $this->db->query($sql, $kode)->row();
		if(isset($query)){
			return 'Kode Supplier Sudah Digunakan Oleh Supplier ('.$query->KODESUPPLIER.') '.$query->NAMASUPPLIER.', Kode Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function cek_valid_nama($nama,$kode=" "){
		$sql = "select KODESUPPLIER, NAMASUPPLIER 
				from MSUPPLIER a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and KODESUPPLIER <> ?
						and NAMASUPPLIER = ?";
		$query = $this->db->query($sql, array($kode,$nama))->row();
		if(isset($query)){
			return 'Nama Supplier Sudah Digunakan Oleh Supplier ('.$query->KODESUPPLIER.') '.$query->NAMASUPPLIER.', Nama Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
}
?>