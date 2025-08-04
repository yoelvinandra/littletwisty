<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_inventori_opname extends MY_Model{

	public function getAll(){
		$data = $this->db->query("select * from TOPNAMESTOK");
		return $data->result();
	}
	
	public function laporan($filter){
	}
	
	public function comboGrid()
	{
		/*if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODEOPNAMESTOK';
		}
		$sql = "select a.KODEOPNAMESTOK, a.IDOPNAMESTOK, a.TGLTRANS, a.USERENTRY,a.IDLOKASI, b.KODELOKASI,b.NAMALOKASI
				from TOPNAMESTOK a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where (a.KODEOPNAMESTOK like ?) and a.CLOSING = 0 and (a.STATUS = 'S') and a.IDLOKASI = ? 
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";

		//echo $sql;		
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%',$pagination['lokasi']));
		*/
		
		$sql = "select distinct a.KODEOPNAMESTOK as VALUE, concat(a.TGLTRANS,' - ',a.KODEOPNAMESTOK) as TEXT,  a.TGLTRANS, a.USERENTRY,a.IDLOKASI, b.KODELOKASI,b.NAMALOKASI
				from TOPNAMESTOK a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				order by a.TGLTRANS DESC, a.KODEOPNAMESTOK DESC";

		//echo $sql;		
		$query = $this->db->query($sql);
		
		$data['rows'] = $query->result();
		return $data;
	}
	public function comboGridTransaksi($status){
		
		if($status != "")
		{	
			$whereStatus = count($status)>0 ? " and (a.status='".implode("' or a.status='", $status)."')" : '';
		}
		
		$sql = "select distinct concat(a.IDOPNAMESTOK,' | ',a.KODEOPNAMESTOK) as VALUE, concat(a.TGLTRANS,' - ',a.KODEOPNAMESTOK) as TEXT,  a.TGLTRANS, a.USERENTRY,a.IDLOKASI, b.KODELOKASI,b.NAMALOKASI
				from TOPNAMESTOK a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereStatus
				order by a.TGLTRANS DESC, a.KODEOPNAMESTOK DESC";
        $query = $this->db->query($sql);
		
		$data['rows'] = $query->result();
		return $data;
	}
	public function comboGridBarang($pagination,$id)
	{
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODEBARANG';
		}

		$sql = "select b.KODEBARANG as KODE, b.NAMABARANG as NAMA,
		        a.IDBARANG as ID,a.SATUAN,a.SATUANUTAMA,a.JML,a.TERPENUHI,a.SISA
				from TOPNAMESTOKDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where (b.KODEBARANG like ? or b.NAMABARANG like ?) and a.IDOPNAMESTOK = ? and a.TUTUP = 0
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%',$idopnamestok));

		$data['rows'] = $query->result();
		return $data;
	}
	
	public function dataGrid($pagination, $filter)
	{	
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODEOPNAMESTOK';
		}

		$data = [];		
		$sql = "select a.IDOPNAMESTOK,a.TGLTRANS,a.KODEOPNAMESTOK,a.CATATAN,d.USERNAME as USERINPUT, a.TGLENTRY as TGLINPUT,e.USERNAME as USERBATAL, a.TGLBATAL, a.STATUS,a.ALASANBATAL
				from TOPNAMESTOK a
				left join MUSER d on a.USERENTRY = d.USERID
				left join MUSER e on a.USERBATAL = e.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				{$filter['sql']}
				order by a.TGLTRANS DESC, a.KODEOPNAMESTOK DESC";
		$q = $this->db->query($sql, $filter['param']);
		$data["rows"]  = $q->result();
		$data["total"] = count($data["rows"]);
		
		return $data;
	}
	
	function loadData($idtrans){
		$sql = "select a.*,b.KODEBARANG, b.NAMABARANG
				from TOPNAMESTOKDTL a
				inner join TOPNAMESTOK a1 on a.IDOPNAMESTOK = a1.IDOPNAMESTOK
				inner join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDOPNAMESTOK = '{$idtrans}'
				order by a.URUTAN";
		$query = $this->db->query($sql)->result();
		return $query;
	}

	function loadDataHeader($idtrans){
		$sql = "select a.IDOPNAMESTOK as IDTRANS, a.KODEOPNAMESTOK as KODETRANS, a.TGLTRANS, b.IDLOKASI,b.KODELOKASI,b.NAMALOKASI,a.CATATAN
				from TOPNAMESTOK a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDOPNAMESTOK = $idtrans   and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by TGLTRANS";
		$query         = $this->db->query($sql)->row();
		return $query;
	}
	function loadDataDetail($idtrans,$kodetrans){
		$whereTrans = "";
	    if($idtrans != "")
	    {
	        $whereTrans = "and a.IDOPNAMESTOK = '{$idtrans}'";
	    }
	    
	    if($kodetrans != "")
	    {
	         $whereTrans = "and a.KODEOPNAMESTOK = '{$kodetrans}'";
	    }
		$data = [];		
        $sql = "select b.IDBARANG as ID, b.KODEBARANG as KODE,b.NAMABARANG as NAMA, a.JML as QTY,b.SATUAN2 as SATUANKECIL, b.HARGABELI as HARGA
				from TOPNAMESTOKDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereTrans   and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by URUTAN";

		$query         = $this->db->query($sql)->result();
		return $query;
	}
	
	function loadDataOpnamePenyesuaian($idtrans) {
				
		$sql = "select a.IDLOKASI,a.TGLTRANS, c.IDBARANG as ID, c.KODEBARANG as KODE,c.NAMABARANG as NAMA, b.JML as QTY,c.SATUAN2 as SATUANKECIL, c.HARGABELI as HARGA,ifnull(if(c.KONVERSI1 = 0, 1,c.KONVERSI1),1) as KONVERSI
				from TOPNAMESTOK a
				inner join TOPNAMESTOKDTL b on a.IDOPNAMESTOK=b.IDOPNAMESTOK
				inner join MBARANG c on b.IDBARANG=c.IDBARANG
				inner join MLOKASI d on a.IDLOKASI=d.IDLOKASI
				where a.IDOPNAMESTOK=$idtrans and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and c.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and d.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}" ;				
		
		$q = $this->db->query($sql)->result();		

		foreach($q as $item) {
			$result   = get_saldo_stok_new($_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],$item->ID, $item->IDLOKASI, $item->TGLTRANS);
			$saldoQty = $result->QTY;
			$konversi = $item->KONVERSI;
			
			$jml     = $item->QTY;
			$selisih = $saldoQty!=$jml ? ($jml-$saldoQty) : 0;
			
			$items[] = array(
				'ID'   		 => $item->ID,
				'KODE' 		 => $item->KODE,
				'NAMA' 		 => $item->NAMA,
				'SATUANKECIL'=> $item->SATUANKECIL,
				'SALDO'      => $saldoQty,
				'QTY'        => $item->QTY,
				'SELISIH'    => $selisih,
				'HARGA'      => $item->HARGA,
			);
		}
		return $items;
	}
	
	function simpan(&$idtrans,$data,$a_detail,$edit)
	{
		// start transaction
		$tr = $this->db->trans_begin();
		
		if($edit){
			$this->db->where("IDOPNAMESTOK",$idtrans);
			$this->db->update('TOPNAMESTOK',$data);
		}else{
			$this->db->insert('TOPNAMESTOK',$data);
		}
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return 'Simpan Data Gagal <br>Kesalahan Pada Header Data Transaksi';
		}
		
		if($edit){
			//hapus detail terlebih dahulu
			$this->db->where("IDOPNAMESTOK",$idtrans)->delete('TOPNAMESTOKDTL');
		}else{
			//mengambil auto increment
			$idtrans = $this->db->insert_id();
		}
		
		// query detail
		$i = 0;
		foreach ($a_detail as $item) {
			$data_values = array (
				'IDOPNAMESTOK'=>$idtrans,
				'IDPERUSAHAAN'=>$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
				'IDBARANG'=>$item->idbarang,
				'URUTAN'=>$i, 
				'JML' => $item->jml,
				'SATUAN'=>$item->satuan,
				'SATUANUTAMA'=>$item->satuan,
				'KONVERSI'=>0,
			);
			$this->db->insert('TOPNAMESTOKDTL',$data_values);
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				return 'Simpan Data Gagal <br>Kesalahan Pada Detail Data Transaksi';
				$i++;
			}
			
			//$result = get_konversi_satuanutama($item->idbarang,$item->satuan);
			if(!empty($result)){
				//update satuan utama & konversi
				$this->db->set('SATUANUTAMA',$result->SATUANUTAMA)
						->set('KONVERSI',$result->KONVERSI)
						->where('IDBARANG',$item->idbarang)
						->where('IDOPNAMESTOK',$idtrans)
						->update('TOPNAMESTOKDTL');
			}
		}
		$this->db->trans_commit();
		return '';
	}
	
	function batal($idtrans,$alasan){
		$this->db->trans_begin();
		$data = array(
			'USERBATAL'=> $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLBATAL'=> date('Y.m.d'),
			'JAMBATAL'=>date('H:i:s'),
			'ALASANBATAL' => $alasan,
			'STATUS'=>'D',
		);
		$this->db->where("IDOPNAMESTOK",$idtrans);
		$this->db->update('TOPNAMESTOK',$data);
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data Transaksi Tidak Dapat Dibatalkan'; 
		}
		$this->db->trans_commit();
		return '';
	}
	
	function ubahStatusJadiSlip($idtrans){
		$this->db->trans_begin();
		$this->db->set('STATUS','S')
				->where('IDOPNAMESTOK',$idtrans)
				->update('TOPNAMESTOK');
				
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data transaksi tidak dapat Diubah menjadi Slip'; 
		}
		$this->db->trans_commit();
		return '';
	}
	
	function ubahStatusJadiInput($idtrans){
		$this->db->trans_begin();
		
		$data = array (
			'USERBATAL' => $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLBATAL'=> date('Y.m.d'),
			'JAMBATAL'=>date('H:i:s'),
			'STATUS'=>'I',
		);
		$this->db->where("IDOPNAMESTOK",$idtrans);
		$this->db->update('TOPNAMESTOK',$data);
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data Transaksi Tidak Dapat Dibatalkan'; 
		}
		$this->db->trans_commit();
		return '';
	}
	
	function getStatusTrans($id){
		return $this->db->where('IDOPNAMESTOK',$id)->get('TOPNAMESTOK')->row()->STATUS;
	}
}
?>