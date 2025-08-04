<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_jurnal extends MY_Model{
    function get($transaksi, $jenis = '') {
		$tempSql = ($jenis <> '') ? " and a.jenis = '{$jenis}'" : '';

		$sql = "select a.*, b.idperkiraan, b.namaperkiraan
				from settingjurnallink a
				left join mperkiraan b on a.kodeperkiraan = b.kodeperkiraan
				where a.idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and
				 	   b.idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and
				 	   a.transaksi = '{$transaksi}'
				 	   {$tempSql};";
		$q = $this->db->query($sql);
		return $q->result();
	}

	function loadAll() {
		$sql = "select a.*, b.idperkiraan, b.namaperkiraan
				from settingjurnallink a
				left join mperkiraan b on a.kodeperkiraan = b.kodeperkiraan and b.idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				where a.idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by a.transaksi, a.urutan, a.saldo, a.jenis";
		$q = $this->db->query($sql);
		return $q->result();
	}

	function simpan($data) {
		$sql = "update settingjurnallink
				set kodeperkiraan = ? 
				where transaksi = ? and 
					  jenis = ? and 
					  idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}";
		foreach ($data as $item) {
			$this->db->query($sql, [$item->KODEPERKIRAAN, $item->TRANSAKSI, $item->JENIS]);
		}
		
		return '';
	}
}
?>