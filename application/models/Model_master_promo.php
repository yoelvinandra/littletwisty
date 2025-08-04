<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_promo extends MY_Model{

	public function dataGridPromo(){
		
		$data = [];

		$sql = "select a.IDCUSTOMER,FORMAT(a.DISKONMEMBER,0) as DISKONMEMBER,a.KODECUSTOMER, a.NAMACUSTOMER
				from MCUSTOMER a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.member = 1
				ORDER BY a.NAMACUSTOMER";
		$query = $this->db->query($sql);
		$data['rows'] = $query->result();

		return $data;
	}
		
	function updateDiskon($data){
		$this->db->trans_begin();
	
		foreach($data as $item)
		{	
		    $this->db->where("IDCUSTOMER",$item->id)
    		        ->update('MCUSTOMER',array(
            		    'DISKONMEMBER' => $item->diskonmemberbaru    
            		));
    		
    		if ($this->db->trans_status() === FALSE){
    			$this->db->trans_rollback();
    			return 'Gagal menyimpan pada database';
    		}
		}
		
		$this->db->trans_commit();
		return '';
	}
}
?>