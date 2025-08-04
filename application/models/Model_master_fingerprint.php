<?php
class Model_master_fingerprint extends MY_Model{
	function getDevice() {
		$sql 	= "select * from MFPDEVICE order by NAMADEVICE ASC";
		$result	= $this->db->query($sql)->result_array();
		return $result;
	}
	
	function getDeviceAcSn($vc) {
		$sql 	= "select * from MFPDEVICE where VC ='{$vc}'";
		$result	= $this->db->query($sql)->result_array();
		return $result;
	}
	
	function getDeviceBySn($sn) {
		$sql 	= "select * from MFPDEVICE where SN ='{$sn}'";
		$result	= $this->db->query($sql)->result_array();
		return $result;
	}
	
	function deviceCheckSn($sn) {
		$query = $this->db->select("count(SN) as ct")
					->from('MFPDEVICE')
					->where('SN',$sn)
					->get()->result();
		if ($query->ct != 0 && $query->ct != '') {
			return "SN sudah terdaftar";
		} else {
			return 1;
		}

	}
}