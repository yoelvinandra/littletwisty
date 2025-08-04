<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_currency extends MY_Model{
	public function get(){
		$data = $this->db->query("select * from MCURRENCY where STATUS = 1 and IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} ");
		return $data->row();
	}
	
	public function getByKode($kode){
		$data = $this->db->query("select * from MCURRENCY where KODECURRENCY = '{$kode}' and IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}");
		return $data->row();
	}

	public function getAll(){
		$data = $this->db->query("select * from MCURRENCY");
		return $data->result();
	}
	
	public function comboGrid($pagination){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODECURRENCY';
		}
		
		$sql = "select IDCURRENCY as ID, KODECURRENCY as KODE, NAMACURRENCY as NAMA, SIMBOL
				from MCURRENCY a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					and (KODECURRENCY like ? or NAMACURRENCY like ?)
					and 1=1 {$pagination['status']}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%'));

		$data['rows'] = $query->result();
		return $data;
	}

	public function dataGrid($pagination, $filter){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODECURRENCY';
		}

		$data = [];

		$sql = "select count(*) as ROW
				from MCURRENCY a
				left join MUSER b on a.USERENTRY = b.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
					and 1=1 {$filter['sql']}";
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
			$this->db->where("IDPERUSAHAAN",$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					->where("IDCURRENCY",$id);
			$this->db->update('MCURRENCY',$data);
		}else{
			$this->db->insert('MCURRENCY',$data);
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
				->where('IDCURRENCY',$id)
				->delete('MCURRENCY');
		if ($this->db->trans_status() === FALSE) { 
			$this->trans_rollback();
			return 'Data Currency Tidak Dapat Dihapus, Data Sudah Digunakan Pada Transaksi'; 
		}
		
		$this->db->trans_commit();
		return '';
	}
	
	function cek_valid_kode($kode){
		$sql = "select KODECURRENCY, NAMACURRENCY
				from MCURRENCY a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
					and KODECURRENCY = ? ";
		$query = $this->db->query($sql, $kode)->row();
		if(isset($query)){
			return 'Kode Currency Sudah Digunakan Oleh Currency ('.$query->KODECURRENCY.') '.$query->NAMACURRENCY.', Kode Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function cek_valid_nama($nama,$kode=" "){
		$sql = "select KODECURRENCY, NAMACURRENCY 
				from MCURRENCY a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
						and KODECURRENCY <> ? 
						and NAMACURRENCY = ?";
		$query = $this->db->query($sql, array($kode,$nama))->row();
		if(isset($query)){
			return 'Nama Currency Sudah Digunakan Oleh Currency ('.$query->KODECURRENCY.') '.$query->NAMACURRENCY.', Nama Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function rate($tgl,$idcurrency){
		$query = $this->db->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])	
						->where('TGLAKTIF<=',$tgl)
						->where('IDCURRENCY',$idcurrency)
						->order_by('TGLAKTIF','DESC')
						->get('MCURRENCYKURS')->row();
		return $query;
	}

	function all_rate($tgl){
		$temp_array =  array();
		$kodecurrency = $this->model_master_config->getConfig('GLOBAL','MAINCURRENCY');
		$query = $this->db->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->where('KODECURRENCY !=',$kodecurrency)	
						->get('MCURRENCY')->result();
						
		foreach($query as $item){
			$sql = "select *
					from MCURRENCYKURS a
					where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
							and TGLAKTIF<='{$tgl}' 
							and IDCURRENCY={$item->IDCURRENCY} 
					order by TGLAKTIF desc";
			$rs = $this->db->query($sql)->row();
			$temp_array[] = array(
				'idcurrency' => $rs->IDCURRENCY,
				'kurs' => $rs->KURS ?? 0
			);
		}
		return $temp_array;
	}
}
?>