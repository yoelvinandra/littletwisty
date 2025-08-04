<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_menu extends MY_Model{
	public $maindb;
	public function getAll() {
		return $this->treeMenuAdmin('', '', 0);
	}
	
	//COPY
	public function get($id){
		$this->db->select('KODEMENU, NAMACLASS, NAMAMENU, TIPE, JENIS,NAMAVIEW,URUTAN');
		$this->db->where('KODEMENU', $id);
		$query = $this->db->get('MMENU');
		return $query->row();
	}
	
	function getMenuAkses($iduser){
		return $this->treeMenu('', '', 0, $iduser);
	}
	
	//COPY
	public function getByUrutan($urutan){
		$this->db->select('NAMAMENU');
		$this->db->where('URUTAN', $urutan);
		$query = $this->db->get('MMENU');
		return $query->row()->NAMAMENU;
	}
	
	private function treeMenuAdmin($parent = '', $namainduk = '', $i=0) {
		$i++;
		$aData = array();
		if ($parent <> '') {
			$sql = "select distinct a.KODEMENU, a.KODEINDUK, a.NAMAMENU, a.URUTAN, a.TIPE, a.NAMACLASS, A.ICON
					from MMENU a 
					where a.KODEINDUK = '{$parent}' and a.STATUS = 1
					order by a.urutan";
		} else {
			$sql = "select KODEMENU, KODEINDUK, NAMAMENU, URUTAN, TIPE, NAMACLASS, ICON
					from MMENU
					where (KODEINDUK is null or KODEINDUK = '') and STATUS = 1
					order by urutan";
		}
		$query = $this->db->query($sql);
		foreach ($query->result() as $rs) {
			$aData[] = array(
				'kodeinduk' => $rs->KODEINDUK,
				'namainduk' => $namainduk,
				'kodemenu'  => $rs->KODEMENU,
				'namamenu'  => $rs->NAMAMENU,
				'tipe'      => $rs->TIPE,
				'icon'      => $rs->ICON,
				'namaclass' => $rs->NAMACLASS,
				'children'  => $this->treeMenuAdmin($rs->KODEMENU, $rs->NAMAMENU, $i),
			);
		} 
		return $aData;
	}

	private function treeMenu($parent = '', $namainduk = '', $i=0, $userID = '') {
		$i++;
		$aData = array();
		
		if ($parent <> '') {
			if ($userID <> '') {
				$temp_sql = ' and b.iduser = '.$userID;
			} else {
				$temp_sql = '';
			}

			if ($i==2) { // level 2
				$sql = "select distinct a.KODEMENU, a.KODEINDUK, a.NAMAMENU, a.URUTAN, a.TIPE, a.NAMACLASS, A.ICON
						from MMENU a 
						where a.KODEINDUK = '{$parent}'  and a.status = 1
						order by a.urutan";
			} else { // level 3
				$sql = "select distinct a.KODEMENU, a.KODEINDUK, a.NAMAMENU, a.URUTAN, a.TIPE, a.NAMACLASS, A.ICON,
							   b.HAKAKSES, B.TAMBAH, B.UBAH, B.HAPUS, B.CETAK, B.BATALCETAK, B.INPUTHARGA, B.LIHATHARGA
						from MMENU a 
						left join muserakses b on a.KODEMENU = b.KODEMENU {$temp_sql}
						where a.KODEINDUK = '{$parent}' and
							  b.hakakses = 1  and a.status = 1
						order by a.urutan";
			}
		} else {
		    
    		//KHUSUS DASHBOARD
    		if ($userID <> '') {
    			$temp_sql = ' and b.iduser = '.$userID;
    		} else {
    			$temp_sql = '';
    		}
    			
    		$sql = "select distinct a.KODEMENU, a.KODEINDUK, a.NAMAMENU, a.URUTAN, a.TIPE, a.NAMACLASS, A.ICON,
    					   b.HAKAKSES, B.TAMBAH, B.UBAH, B.HAPUS, B.CETAK, B.BATALCETAK, B.INPUTHARGA, B.LIHATHARGA
    				from MMENU a 
    				left join muserakses b on a.KODEMENU = b.KODEMENU {$temp_sql}
    				where a.KODEMENU = 'D0001' and
    					  b.hakakses = 1 and a.status = 1
    				order by a.urutan";
    						
    		$query = $this->db->query($sql);
    		foreach ($query->result() as $rs) {
    			$aData[] = array(
    				'kodeinduk' => $rs->KODEINDUK,
    				'namainduk' => $namainduk,
    				'kodemenu' => $rs->KODEMENU,
    				'namamenu' => $rs->NAMAMENU,
    				'tipe' => $rs->TIPE,
    				'icon' => $rs->ICON,
    				'namaclass' => $rs->NAMACLASS,
    				'children' => '',
    			);
    		} 
		
			$sql = "select KODEMENU, KODEINDUK, NAMAMENU, URUTAN, TIPE, NAMACLASS, ICON
					from MMENU
					where (KODEINDUK is null or KODEINDUK = '') and KODEMENU != 'D0001' and STATUS = 1
					order by urutan";
		}
		
		$query = $this->db->query($sql);
		foreach ($query->result() as $rs) {
			$aData[] = array(
				'kodeinduk' => $rs->KODEINDUK,
				'namainduk' => $namainduk,
				'kodemenu' => $rs->KODEMENU,
				'namamenu' => $rs->NAMAMENU,
				'tipe' => $rs->TIPE,
				'icon' => $rs->ICON,
				'namaclass' => $rs->NAMACLASS,
				'children' => $this->treeMenu($rs->KODEMENU, $rs->NAMAMENU, $i, $userID),
			);
		} 
		return $aData;
	}
}
?>