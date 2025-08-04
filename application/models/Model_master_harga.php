<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_harga extends MY_Model{
    
    public function dataGrid($idcustomer){
		$whereCustomer = "";
		if($idcustomer != 0)
		{
		    $whereCustomer = " and a.IDCUSTOMER = $idcustomer";
		}
		$data = [];

		$sql = "select MAX(a.HARGACORET) as HARGACORET,MAX(a.HARGAKONSINYASI) as HARGAKONSINYASI,c.HARGAJUAL,c.HARGABELI, a.IDBARANG,c.KODEBARANG,c.NAMABARANG,a.IDCUSTOMER
				from MHARGA a
				inner join MBARANG c on a.IDBARANG = c.IDBARANG and c.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereCustomer and c.KODEBARANG != 'XXXXX'
				GROUP BY c.IDBARANG
				ORDER BY SUBSTRING(c.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(c.URUTANTAMPIL, 2) AS UNSIGNED) ASC";
		$query = $this->db->query($sql);
		$data['rows'] = $query->result();

		return $data;
	}
	
	public function dataGridHeader($idcustomer){
		$whereCustomer = "";
		if($idcustomer != 0)
		{
		    $whereCustomer = " and a.IDCUSTOMER = $idcustomer";
		}
		$data = [];

		$sql = "select MAX(a.HARGACORET) as HARGACORET,MAX(a.HARGAKONSINYASI) as HARGAKONSINYASI,c.HARGAJUAL,c.HARGABELI,a.IDBARANG,c.KODEBARANG,c.KATEGORI as NAMABARANG,a.IDCUSTOMER
				from MHARGA a
				inner join MBARANG c on a.IDBARANG = c.IDBARANG and c.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereCustomer and c.KODEBARANG != 'XXXXX'
				GROUP BY c.KATEGORI
				ORDER BY SUBSTRING(c.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(c.URUTANTAMPIL, 2) AS UNSIGNED) ASC";
		$query = $this->db->query($sql);
		$data['rows'] = $query->result();

		return $data;
	}
	
	public function simpanHarga($data,$allcustomer,$varian){
	    
		$this->db->trans_begin();
	    
	    //BUKAN SEMUA CUSTOMER, TAPI VARIAN
	    if($allcustomer == "false" && $varian == "true")
        {
    		foreach($data as $item)
    		{	
    		    $this->db->where("IDCUSTOMER",$item->idcustomer)
    		             ->where("IDBARANG",$item->idbarang)
        		         ->update('MHARGA',array(
                		    'HARGACORET' => $item->harganew,
                		    'HARGAKONSINYASI' => $item->hargakonsinyasinew        
                		));
                		
        		if ($this->db->trans_status() === FALSE){
        			$this->db->trans_rollback();
        			return 'Gagal menyimpan pada database';
        		}
        		
        		 $this->db->where("IDBARANG",$item->idbarang)
        		         ->update('MBARANG',array(
                		    'HARGAJUAL' => $item->hargajualnew,
                		    'HARGABELI' => $item->hargabelinew        
                		));
                		
        		if ($this->db->trans_status() === FALSE){
        			$this->db->trans_rollback();
        			return 'Gagal menyimpan pada database';
        		}
    		}
        }
        else if($allcustomer == "true" && $varian == "true")  //SEMUA CUSTOMER, TAPI VARIAN
        {
    		foreach($data as $item)
    		{	
    		    $this->db->where("IDBARANG",$item->idbarang)
        		         ->update('MHARGA',array(
                		    'HARGACORET' => $item->harganew,
                		    'HARGAKONSINYASI' => $item->hargakonsinyasinew        
                		));
                		
        		if ($this->db->trans_status() === FALSE){
        			$this->db->trans_rollback();
        			return 'Gagal menyimpan pada database';
        		}
        		
        		$this->db->where("IDBARANG",$item->idbarang)
        		         ->update('MBARANG',array(
                		    'HARGAJUAL' => $item->hargajualnew,
                		    'HARGABELI' => $item->hargabelinew        
                		));
                		
        		if ($this->db->trans_status() === FALSE){
        			$this->db->trans_rollback();
        			return 'Gagal menyimpan pada database';
        		}
    		}
        }
        else if($allcustomer == "true" && $varian == "false")  //SEMUA CUSTOMER, TAPi BUKAN VARIAN
        {
    		foreach($data as $item)
    		{	
		    
    		    $sql = "SELECT idbarang FROM MBARANG a WHERE a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and kategori in (select x.kategori from mbarang x where x.idbarang = {$item->idbarang})";
    		    
    		    $allBarang = $this->db->query($sql)->result();
    		    
    		    foreach($allBarang as $itemBarang)
    		    {
        		    $this->db->where("IDBARANG",$itemBarang->IDBARANG)
            		         ->update('MHARGA',array(
                    		    'HARGACORET' => $item->harganew,
                    		    'HARGAKONSINYASI' => $item->hargakonsinyasinew        
                    		));
                    		
            		if ($this->db->trans_status() === FALSE){
            			$this->db->trans_rollback();
            			return 'Gagal menyimpan pada database';
            		}
            		
            		$this->db->where("IDBARANG",$itemBarang->IDBARANG)
        		     ->update('MBARANG',array(
                	    'HARGAJUAL' => $item->hargajualnew,
                	    'HARGABELI' => $item->hargabelinew        
                	));
                		
            		if ($this->db->trans_status() === FALSE){
            			$this->db->trans_rollback();
            			return 'Gagal menyimpan pada database';
            		}
    		    }
    		}
        }
        else if($allcustomer == "false" && $varian == "false")  //BUKAN SEMUA CUSTOMER, TAPi BUKAN VARIAN
        {
    		foreach($data as $item)
    		{	
		    
    		    $sql = "SELECT idbarang FROM MBARANG a WHERE a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}  and kategori in (select x.kategori from mbarang x where x.idbarang = {$item->idbarang})";
    		   
    		    $allBarang = $this->db->query($sql)->result();
 
    		    foreach($allBarang as $itemBarang)
    		    {
        		    $this->db->where("IDCUSTOMER",$item->idcustomer)
        		             ->where("IDBARANG",$itemBarang->IDBARANG)
            		         ->update('MHARGA',array(
                    		    'HARGACORET' => $item->harganew,
                    		    'HARGAKONSINYASI' => $item->hargakonsinyasinew        
                    		));
                    		
            		if ($this->db->trans_status() === FALSE){
            			$this->db->trans_rollback();
            			return 'Gagal menyimpan pada database';
            		}
            		
            		$this->db->where("IDBARANG",$itemBarang->IDBARANG)
        		     ->update('MBARANG',array(
                	    'HARGAJUAL' => $item->hargajualnew,
                	    'HARGABELI' => $item->hargabelinew        
                	));
                		
            		if ($this->db->trans_status() === FALSE){
            			$this->db->trans_rollback();
            			return 'Gagal menyimpan pada database';
            		}
    		    }
    		}
    		
        }
		
		$this->db->trans_commit();
		return '';
	}
}
?>