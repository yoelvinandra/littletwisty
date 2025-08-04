<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_customer extends MY_Model{

	public function getAll(){
		$data = $this->db->query("select * from MCUSTOMER");
		return $data->result();
	}
	
	public function laporan($filter){
		$where = "";
		$filterparam = array();
		if($filter['rdcust'] == 1){
			//untuk nama dan kode textbox
			
			$filter['nama'] = str_replace(' ','%',$filter['nama']);
			$filter['nama'] = '%'.$filter['nama'].'%';
			$where .= "and a.NAMACUSTOMER LIKE ?";
			array_push($filterparam,$filter['nama']);
			
			$filter['kode'] = str_replace(' ','%',$filter['kode']);
			$filter['kode'] = '%'.$filter['kode'].'%';
			$where .= "and a.KODECUSTOMER LIKE ?";
			array_push($filterparam,$filter['kode']);
			
		}else if($filter['rdcust'] == 2){
			//untuk list id yang ditampilkan
			$where .= "and (a.IDCUSTOMER ='' ";
			foreach($filter['listid'] as $kode){
				$where .= "or a.IDCUSTOMER = ?";
				array_push($filterparam,$kode);
			}
			$where .= ")";
			
		}else if($filter['rdcust'] == 3){
			//untuk range yang ditampilkan
			$where .= "and a.NAMACUSTOMER >= ?";
			array_push($filterparam,$filter['namaawal']);
			$where .= "and a.NAMACUSTOMER <= ?";
			array_push($filterparam,$filter['namaakhir']);
		}
		$sql =  "select * 
				from MCUSTOMER a
				left join MSYARATBAYAR b on a.IDSYARATBAYAR = b.IDSYARATBAYAR
				AND SUBSTRING(a.KODECUSTOMER, 1, 1) = 'C'
				where 1=1 $where
				order by a.NAMACUSTOMER ASC";
		$query = $this->db->query($sql,$filterparam);
		return $query->result();
	}
	
	public function comboGrid(){
		/*if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODECUSTOMER';
		}

		$sql = "select KODECUSTOMER as KODE, CONCAT(NAMACUSTOMER,' ',IF(BADANUSAHA!='-', CONCAT(', ',BADANUSAHA),' ')) as NAMA,
					IDCUSTOMER as ID,IF(KOTA IS NULL, ALAMAT,CONCAT(ALAMAT,',',KOTA)) AS ALAMAT, KOTA,TELP,NOREKENING,CONTACTPERSON,
					TELPCP,IDSYARATBAYAR,NPWP
				from MCUSTOMER  
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and (KODECUSTOMER like ? or NAMACUSTOMER like ?)
						and 1=1	{$pagination['status']}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%'));
		*/
		
		$sql = "select CONCAT(KODECUSTOMER,' | ',NAMACUSTOMER) as VALUE, CONCAT(KODECUSTOMER,' - ',NAMACUSTOMER,' - ',KOTA) as TEXT
				from MCUSTOMER  
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} AND SUBSTRING(KODECUSTOMER, 1, 1) = 'C'
				ORDER BY NAMACUSTOMER";
		$query = $this->db->query($sql);
		
		$data['rows'] = $query->result();
		
		
		
		$sql = "select CONCAT(KODECUSTOMER,' | ',NAMACUSTOMER) as VALUE, CONCAT(KODECUSTOMER,' - ',NAMACUSTOMER,' - ',KOTA) as TEXT
				from MCUSTOMER  
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} AND SUBSTRING(KODECUSTOMER, 1, 1) <> 'C'
				ORDER BY NAMACUSTOMER";
		$query = $this->db->query($sql);	
		
		foreach($query->result() as $item)
		{
		    array_push($data['rows'],$item);
		}
		return $data;
	}
	
	public function comboGridDashboard(){
		/*if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODECUSTOMER';
		}

		$sql = "select KODECUSTOMER as KODE, CONCAT(NAMACUSTOMER,' ',IF(BADANUSAHA!='-', CONCAT(', ',BADANUSAHA),' ')) as NAMA,
					IDCUSTOMER as ID,IF(KOTA IS NULL, ALAMAT,CONCAT(ALAMAT,',',KOTA)) AS ALAMAT, KOTA,TELP,NOREKENING,CONTACTPERSON,
					TELPCP,IDSYARATBAYAR,NPWP
				from MCUSTOMER  
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and (KODECUSTOMER like ? or NAMACUSTOMER like ?)
						and 1=1	{$pagination['status']}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%'));
		*/
		
		$sql = "select IDCUSTOMER as VALUE, NAMACUSTOMER as TEXT
				from MCUSTOMER  
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} AND SUBSTRING(KODECUSTOMER, 1, 1) = 'C'
				ORDER BY NAMACUSTOMER";
		$query = $this->db->query($sql);
		
		$data['rows'] = $query->result();
		
		
		
		$sql = "select IDCUSTOMER as VALUE, NAMACUSTOMER as TEXT
				from MCUSTOMER  
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} AND SUBSTRING(KODECUSTOMER, 1, 1) <> 'C'
				ORDER BY NAMACUSTOMER";
		$query = $this->db->query($sql);	
		
		foreach($query->result() as $item)
		{
		    array_push($data['rows'],$item);
		}
		return $data;
	}
	
	public function comboGridTransaksi(){	
	    
		
		$sql = "select IDCUSTOMER as ID,KODECUSTOMER as KODE,NAMACUSTOMER as NAMA,ALAMAT,TELP,CATATAN AS CATATANCUSTOMER, MEMBER, KONSINYASI, DISKONMEMBER
				from MCUSTOMER  
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				AND SUBSTRING(KODECUSTOMER, 1, 1) = 'C' and STATUS = 1
				ORDER BY NAMACUSTOMER";
		$query = $this->db->query($sql);
		
		$data['rows'] = $query->result();
		
	    $sql = "select IDCUSTOMER as ID,KODECUSTOMER as KODE,NAMACUSTOMER as NAMA,ALAMAT,TELP,CATATAN AS CATATANCUSTOMER, MEMBER, KONSINYASI, DISKONMEMBER
				from MCUSTOMER  
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				AND SUBSTRING(KODECUSTOMER, 1, 1) <> 'C' and STATUS = 1
				ORDER BY NAMACUSTOMER";
		$query = $this->db->query($sql);
		
		foreach($query->result() as $item)
		{
		    array_push($data['rows'],$item);
		}
		
		return $data;
	}
	
	public function comboGridApproveLain($pagination){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODECUSTOMER';
		}

		$sql = "select KODECUSTOMER as KODE, NAMACUSTOMER as 	NAMA,
					IDCUSTOMER as ID,IF(KOTA IS NULL, ALAMAT,CONCAT(ALAMAT,',',KOTA)) AS ALAMAT, KOTA,TELP,NOREKENING,CONTACTPERSON,
					TELPCP,IDSYARATBAYAR,NPWP
				from MCUSTOMER a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					and (KODECUSTOMER like ? or NAMACUSTOMER like ?)
					and a.IDCUSTOMER in (
						select a.IDCUSTOMER1 as IDCUSTOMER
						from TLAINPRDTL a
						left join TLAINPR b on a.IDLAINPR = b.IDLAINPR
						where a.APPROVE1 = 1 and b.STATUS = 'S' 
							and b.IDLOKASI = ?
					union all
						select a.IDCUSTOMER2 as IDCUSTOMER
						from TLAINPRDTL a
						left join TLAINPR b on a.IDLAINPR = b.IDLAINPR
						where a.APPROVE2 = 1 and b.STATUS = 'S'
							and b.IDLOKASI = ?
					union all
						select a.IDCUSTOMER3 as IDCUSTOMER
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
			$pagination['sort'] = 'KODECUSTOMER';
		}

		$sql = "select KODECUSTOMER as KODE, NAMACUSTOMER as 	NAMA,
					IDCUSTOMER as ID,IF(KOTA IS NULL, ALAMAT,CONCAT(ALAMAT,',',KOTA)) AS ALAMAT, KOTA,TELP,NOREKENING,CONTACTPERSON,
					TELPCP,IDSYARATBAYAR,NPWP
				from MCUSTOMER a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					and (KODECUSTOMER like ? or NAMACUSTOMER like ?)
					and a.IDCUSTOMER in (
						select a.IDCUSTOMER1 as IDCUSTOMER
						from TASETPRDTL a
						left join TASETPR b on a.IDASETPR = b.IDASETPR
						where a.APPROVE1 = 1 and b.STATUS = 'S' 
							and b.IDLOKASI = ?
					union all
						select a.IDCUSTOMER2 as IDCUSTOMER
						from TASETPRDTL a
						left join TASETPR b on a.IDASETPR = b.IDASETPR
						where a.APPROVE2 = 1 and b.STATUS = 'S'
							and b.IDLOKASI = ?
					union all
						select a.IDCUSTOMER3 as IDCUSTOMER
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
				from MCUSTOMER a
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
		$sql = "select KODECUSTOMER as KODE, NAMACUSTOMER as NAMA,IDCUSTOMER as ID,IF(KOTA IS NULL, ALAMAT,CONCAT(ALAMAT,',',KOTA)) AS ALAMAT, KOTA,TELP, IDSYARATBAYAR, CONTACTPERSON
				from MCUSTOMER a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and (KODECUSTOMER like ? or NAMACUSTOMER like ?)
						AND SUBSTRING(a.KODECUSTOMER, 1, 1) = 'C'
						and 1=1 {$pagination['status']} 
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%'));

		$data['rows'] = $query->result();
		
		$sql = "select KODECUSTOMER as KODE, NAMACUSTOMER as NAMA,IDCUSTOMER as ID,IF(KOTA IS NULL, ALAMAT,CONCAT(ALAMAT,',',KOTA)) AS ALAMAT, KOTA,TELP, IDSYARATBAYAR, CONTACTPERSON
				from MCUSTOMER a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and (KODECUSTOMER like ? or NAMACUSTOMER like ?)
						AND SUBSTRING(a.KODECUSTOMER, 1, 1) <> 'C'
						and 1=1 {$pagination['status']} 
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%'));

		foreach($query->result() as $item)
		{
		    array_push($data['rows'],$item);
		}
		
		return $data;
	}

	public function dataGrid(){
		
		$data = [];

		$sql = "select c.USERNAME as USERBUAT, a.*, a.NAMACUSTOMER as NAMACUSTOMER2
				from MCUSTOMER a
				left join MUSER c on a.USERENTRY = c.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} AND SUBSTRING(a.KODECUSTOMER, 1, 1) = 'C'
				ORDER BY a.NAMACUSTOMER";
		$query = $this->db->query($sql);
		$data['rows'] = $query->result();
		
		$sql = "select c.USERNAME as USERBUAT, a.*, a.NAMACUSTOMER as NAMACUSTOMER2
				from MCUSTOMER a
				left join MUSER c on a.USERENTRY = c.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} AND SUBSTRING(a.KODECUSTOMER, 1, 1) <> 'C'
				ORDER BY a.NAMACUSTOMER";
		$query = $this->db->query($sql);
    
		foreach($query->result() as $item)
		{
		    array_push($data['rows'],$item);
		}
		return $data;
	}
		
	function simpan($id,$data,$edit){
		$this->db->trans_begin();
		
		if($edit){
			$this->db->where("IDCUSTOMER",$id);
			$this->db->update('MCUSTOMER',$data);
			
		}else{
			$this->db->insert('MCUSTOMER',$data);
			$id = $this->db->insert_id();
		}
		
		
		$sqlExist = "
            SELECT group_concat(IDBARANG) as ID
            FROM MHARGA
            WHERE IDCUSTOMER = $id";
        
        $idbarang = $this->db->query($sqlExist)->row()->ID;
        $whereBarang = "";
        if($idbarang != null)
        {
            $whereBarang = " and idbarang not in ($idbarang)";
        }
        
        $queryBarang = [];
        
    	$sqlBarang = "select IDBARANG, HARGAJUAL from mbarang where status = 1 and stok = 1 and idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereBarang"; 
  
    	$queryBarang = $this->db->query($sqlBarang)->result(); 
    	$index = 0;
    	
    	foreach($queryBarang as $itemBarang){
            if($index == 50)
            {   
                // Sleep for 500 milliseconds (0.5 seconds)
                usleep(500000);
                $index = 0;
            }
            
            $index++;
        	$this->db->insert('MHARGA',[
        	    "IDPERUSAHAAN"      => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
        	    "IDBARANG"          => $itemBarang->IDBARANG,
        	    "IDCUSTOMER"        => $id,
        	    "HARGACORET"        => $itemBarang->HARGAJUAL,
        	    "HARGAKONSINYASI"   => $itemBarang->HARGAJUAL
        	]);
    	}
		
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 'Gagal menyimpan pada database';
		}
		
		$this->db->trans_commit();
		return $id;
	}
	
	function hapus($id){
		$this->db->trans_begin();
		
		if(checkCustomerPadaTransaksi($id) == 1)
		{
		    $this->db->trans_rollback();
    		return 'Data Customer Tidak Dapat Dihapus, Data Sudah Digunakan Pada Transaksi'; 
		}
		
		$this->db->where("IDPERUSAHAAN",$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				->where('IDCUSTOMER',$id)
				->delete('MCUSTOMER');
		if ($this->db->trans_status() === FALSE) { 
			$this->trans_rollback();
			return 'Data Customer Tidak Dapat Dihapus'; 
		}
		
		$this->db->where("IDPERUSAHAAN",$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				->where('IDCUSTOMER',$id)
				->delete('MHARGA');
		if ($this->db->trans_status() === FALSE) { 
			$this->trans_rollback();
			return 'Data Customer Tidak Dapat Dihapus'; 
		}
		
		$this->db->trans_commit();
		return '';
	}
	
	function cek_valid_kode($kode){
		$sql = "select KODECUSTOMER, NAMACUSTOMER 
				from MCUSTOMER a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and KODECUSTOMER = ?";
		$query = $this->db->query($sql, $kode)->row();
		if(isset($query)){
			return 'Kode Customer Sudah Digunakan Oleh Customer ('.$query->KODECUSTOMER.') '.$query->NAMACUSTOMER.', Kode Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function cek_valid_nama($nama,$kode=" "){
		$sql = "select KODECUSTOMER, NAMACUSTOMER 
				from MCUSTOMER a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and KODECUSTOMER <> ?
						and NAMACUSTOMER = ?";
		$query = $this->db->query($sql, array($kode,$nama))->row();
		if(isset($query)){
			return 'Nama Customer Sudah Digunakan Oleh Customer ('.$query->KODECUSTOMER.') '.$query->NAMACUSTOMER.', Nama Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function checkCustomerByAddress($item){
	    $sql = "SELECT COUNT(IDCUSTOMER) as ADA,IDCUSTOMER
            FROM MCUSTOMER
                WHERE ALAMAT = '".$item['ALAMATCUSTOMER']."'
                and KOTA =  '".$item['KOTACUSTOMER']."'
                and PROVINSI =  '".$item['PROVINSICUSTOMER']."'
                and NEGARA =  '".$item['NEGARACUSTOMER']."'
                and NAMACUSTOMER =  '".$item['NAMACUSTOMER']."'";
    
            
        return $this->db->query($sql)->row();
	}
}
?>