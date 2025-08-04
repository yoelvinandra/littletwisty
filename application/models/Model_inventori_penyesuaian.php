<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_inventori_penyesuaian extends MY_Model{

	public function getAll(){
		$data = $this->db->query("select * from TPENYESUAIANSTOK");
		return $data->result();
	}
	
	public function laporan($filter){
	}
	
	public function comboGrid()
	{
		/*
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODEPENYESUAIANSTOK';
		}
		$sql = "select a.KODEPENYESUAIANSTOK, a.IDPENYESUAIANSTOK, a.TGLTRANS, a.USERENTRY,a.IDLOKASI, b.KODELOKASI,b.NAMALOKASI
				from TPENYESUAIANSTOK a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where (a.KODEPENYESUAIANSTOK like ?) and a.CLOSING = 0 and (a.STATUS = 'S' or a.STATUS = 'P') and a.IDLOKASI = ? 
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%',$pagination['lokasi']));
		*/
		
		$sql = "select distinct a.KODEPENYESUAIANSTOK as VALUE, concat(a.TGLTRANS,' - ',a.KODEPENYESUAIANSTOK) as TEXT, a.TGLTRANS, a.USERENTRY,a.IDLOKASI, b.KODELOKASI,b.NAMALOKASI
				from TPENYESUAIANSTOK a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				order by a.TGLTRANS DESC, a.KODEPENYESUAIANSTOK DESC";

		//echo $sql;		
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
				from TPENYESUAIANSTOKDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where (b.KODEBARANG like ? or b.NAMABARANG like ?) and a.IDPENYESUAIANSTOK = ? and a.TUTUP = 0
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%',$idpenyesuaianstok));

		$data['rows'] = $query->result();
		return $data;
	}

	public function dataGrid($pagination, $filter)
	{	
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODEPENYESUAIANSTOK';
		}

		$data = [];		
		$sql = "select a.IDPENYESUAIANSTOK,a.TGLTRANS,a.KODEPENYESUAIANSTOK,b.KODEOPNAMESTOK,a.CATATAN,d.USERNAME as USERINPUT, a.TGLENTRY as TGLINPUT,e.USERNAME as USERBATAL, a.TGLBATAL, a.STATUS,a.ALASANBATAL
				from TPENYESUAIANSTOK a 
				left join TOPNAMESTOK b on a.IDOPNAMESTOK = b.IDOPNAMESTOK
				left join MUSER d on a.USERENTRY = d.USERID
				left join MUSER e on a.USERBATAL = e.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				{$filter['sql']}
				order by a.TGLTRANS DESC, a.KODEPENYESUAIANSTOK DESC";
		$q = $this->db->query($sql, $filter['param']);
		$data["rows"]  = $q->result();
		$data["total"] = count($data["rows"]);
		
		return $data;
	}
	
	function loadDataBarang($idlokasi,$tgltrans) {
				
		$sql = "select MBARANG.IDBARANG as ID, MBARANG.KODEBARANG as KODE,MBARANG.NAMABARANG as NAMA, 0 as QTY,MBARANG.SATUAN2 as SATUANKECIL, MBARANG.HARGABELI as HARGA,ifnull(if(MBARANG.KONVERSI1 = 0, 1,MBARANG.KONVERSI1),1) as KONVERSI
				from MBARANG
				where MBARANG.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and MBARANG.STOK = 1 and MBARANG.STATUS = 1 ORDER BY  SUBSTRING(MBARANG.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(MBARANG.URUTANTAMPIL, 2) AS UNSIGNED) ASC" ;				
		
		$q = $this->db->query($sql)->result();		

		foreach($q as $item) {
			$result   = get_saldo_stok_new($_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],$item->ID, $idlokasi, $tgltrans);
			$saldoQty = $result->QTY??0;
			$konversi = $item->KONVERSI;
			
			$jml     = $item->QTY;
			$selisih = $saldoQty!=$jml ? ($jml-$saldoQty) : 0;
			
			$items[] = array(
				'ID'   		 => $item->ID,
				'KODE' 		 => $item->KODE,
				'NAMA' 		 => $item->NAMA,
				'SATUANKECIL'=> $item->SATUANKECIL,
				'SALDO'      => $saldoQty,
				'QTY'        => $saldoQty,
				'SELISIH'    => 0,
				'HARGA'      => $item->HARGA,
			);
		}
		return $items;
	}
	
	function loadData($idtrans){
		$sql = "select a.*,b.KODEBARANG, b.NAMABARANG
				from TPENYESUAIANSTOKDTL a
				inner join TPENYESUAIANSTOK a1 on a.IDPENYESUAIANSTOK = a1.IDPENYESUAIANSTOK
				inner join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPENYESUAIANSTOK = '{$idtrans}'
				order by a.URUTAN";
		$query = $this->db->query($sql)->result();
		return $query;
	}

	function loadDataHeader($idtrans){
		$sql = "select a.IDPENYESUAIANSTOK as IDTRANS, a.KODEPENYESUAIANSTOK as KODETRANS, a.TGLTRANS, b.IDLOKASI,b.KODELOKASI,b.NAMALOKASI,concat(a.IDOPNAMESTOK,' | ',ifnull(c.KODEOPNAMESTOK,'')) as OPNAMESTOK,a.CATATAN
				from TPENYESUAIANSTOK a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI 
				left join TOPNAMESTOK c on a.IDOPNAMESTOK = c.IDOPNAMESTOK  and c.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDPENYESUAIANSTOK = $idtrans  and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				order by TGLTRANS";
		$query         = $this->db->query($sql)->row();
		return $query;
	}

	function loadDataDetail($idtrans,$kodetrans){
		$whereTrans = "";
	    if($idtrans != "")
	    {
	        $whereTrans = "and a.IDPENYESUAIANSTOK = '{$idtrans}'";
	    }
	    
	    if($kodetrans != "")
	    {
	         $whereTrans = "and a.KODEPENYESUAIANSTOK = '{$kodetrans}'";
	    }
		$data = [];		
        $sql = "select b.IDBARANG as ID, b.KODEBARANG as KODE,b.NAMABARANG as NAMA, a.JML as QTY,a.SELISIH,a.HARGA,a.SATUAN as 	SATUANKECIL
				from TPENYESUAIANSTOKDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereTrans  and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by URUTAN";

		$query         = $this->db->query($sql)->result();
		return $query;
	}
		
	function simpan(&$idtrans,$data,$a_detail,$edit)
	{
		// start transaction
		$tr = $this->db->trans_begin();
		
		if($edit){
			$this->db->where("IDPENYESUAIANSTOK",$idtrans);
			$this->db->update('TPENYESUAIANSTOK',$data);
		}else{
			$this->db->insert('TPENYESUAIANSTOK',$data);
		}
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return 'Simpan Data Gagal <br>Kesalahan Pada Header Data Transaksi';
		}
		
		if($edit){
			//hapus detail terlebih dahulu
			$this->db->where("IDPENYESUAIANSTOK",$idtrans)->delete('TPENYESUAIANSTOKDTL');
		}else{
			//mengambil auto increment
			$idtrans = $this->db->insert_id();
		}
		
		// query detail
		$i = 0;		
		foreach ($a_detail as $item) {
			$data_values = array (
				'IDPERUSAHAAN'      => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
				'IDPENYESUAIANSTOK' => $idtrans,
				'KODEPENYESUAIANSTOK' => $data['KODEPENYESUAIANSTOK'],
				'IDBARANG'          => $item->idbarang,
				'URUTAN'            => $i,
				'JML'               => $item->jml,
				'SELISIH'           => $item->selisih,
				'SATUAN'            => $item->satuan,
				'SATUANUTAMA'       => $item->satuan,
				'KONVERSI'          => 1,
				'HARGA'       	    => $item->harga,
				'SUBTOTAL'          => $item->subtotal,
			);
			$this->db->insert('TPENYESUAIANSTOKDTL',$data_values);
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
						 ->where('IDPENYESUAIANSTOK',$idtrans)
						 ->update('TPENYESUAIANSTOKDTL');
			}
		}
		return $this->ubahStatusJadiSlip($idtrans,false);
	}
	
	function batal($idtrans,$alasan){
		$this->db->trans_begin();
		
		$query = $this->db->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
							->where('IDPENYESUAIANSTOK',$idtrans)
							->get('TPENYESUAIANSTOK')->row();
		//HAPUS KARTUSTOK//
		$this->db->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				->where('KODETRANS',$query->KODEPENYESUAIANSTOK)
				->delete('KARTUSTOK');
		//HAPUS KARTUSTOK//
		
		$data = array(
			'USERBATAL'=> $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLBATAL'=> date('Y.m.d'),
			'JAMBATAL'=>date('H:i:s'),
			'ALASANBATAL' => $alasan,
			'STATUS'=>'D',
		);
		$this->db->where("IDPENYESUAIANSTOK",$idtrans);
		$this->db->update('TPENYESUAIANSTOK',$data);
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data transaksi tidak dapat dibatalkan'; 
		}
		
		$query = $this->db->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
							->where('IDPENYESUAIANSTOK',$idtrans)
							->get('TPENYESUAIANSTOK')->row();
		
		//HAPUS KARTUSTOK//
		
		$this->db->set('STATUS','S')
				->where('IDOPNAMESTOK',$query->IDOPNAMESTOK)
				->update('TOPNAMESTOK');
				
		$this->db->trans_commit();
		return '';
	}
	
	function ubahStatusJadiSlip($idtrans,$trans = true){
		// start transaction
		if($trans)$this->db->trans_begin();
		
		$query = $this->db->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
							->where('IDPENYESUAIANSTOK',$idtrans)
							->get('TPENYESUAIANSTOK')->row();
		//HAPUS KARTUSTOK//
		$this->db->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				->where('KODETRANS',$query->KODEPENYESUAIANSTOK)
				->delete('KARTUSTOK');
		//HAPUS KARTUSTOK//
		
		
		$sql = "select a.*,b.IDBARANG,b.JML,b.SELISIH,b.SATUAN,b.SATUANUTAMA,
					b.KONVERSI,b.HARGA,b.SUBTOTAL,c.KONVERSI1,c.KONVERSI2,
					c.NAMABARANG
				from TPENYESUAIANSTOK a
				left join TPENYESUAIANSTOKDTL b on a.IDPENYESUAIANSTOK = b.IDPENYESUAIANSTOK
				left join MBARANG c on b.IDBARANG = c.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
					and a.IDPENYESUAIANSTOK = {$idtrans}";
		$query = $this->db->query($sql)->result();
		
		
	
		//INSERT KARTUSTOK//
		foreach($query as $item){
			$mk = 'M';$selisih = $item->SELISIH;
			if($selisih == 0)continue;
			if($selisih <0){
				$mk = 'K';
				$selisih *= -1;
			}
			
			$data = array (
				'IDPERUSAHAAN'      => $item->IDPERUSAHAAN,
				'IDLOKASI'          => $item->IDLOKASI,
				'MODUL'      	    => "INVENTORI",
				'IDTRANS'      		=> $item->IDPENYESUAIANSTOK,
				'KODETRANS'  		=> $item->KODEPENYESUAIANSTOK,
				'IDTRANSREFERENSI'  => $item->IDPENYESUAIANSTOK,
				'KODETRANSREFERENSI'=> $item->KODEPENYESUAIANSTOK,
				'IDBARANG'  		=> $item->IDBARANG,
				'KONVERSI1'  		=> $item->KONVERSI1,
				'KONVERSI2'  		=> $item->KONVERSI2,
				'TGLTRANS'          => $item->TGLTRANS,
				'JENISTRANS'        => 'ADJUSTMENT',
				'KETERANGAN'        => 'ADJUSTMENT UNTUK '.$item->NAMABARANG,
				'MKPO'              => '', 
				'JMLPO'             => 0,
				'MK'                => $mk, 
				'JML'               => $selisih,
				'MKSO'              => '', 
				'JMLSO'             => 0,
				'TOTALHARGA'        => $item->SUBTOTAL,
				'STATUS'            => 1,
			);
			
			$this->db->insert('KARTUSTOK',$data);
			if($this->db->trans_status === false){
				$this->db->trans_rollback();
				return 'Input Data Kartu Stok Gagal';
			}
		}		
		//INSERT KARTUSTOK//
		
		$this->db->set('STATUS','P')
				->where('IDOPNAMESTOK',$query[0]->IDOPNAMESTOK)
				->update('TOPNAMESTOK');
		
		
		$this->db->set('STATUS','S')
				->where('IDPENYESUAIANSTOK',$idtrans)
				->update('TPENYESUAIANSTOK');
				
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data transaksi tidak dapat Diubah menjadi Slip'; 
		}
		$this->db->trans_commit();
		return '';
	}
	
	function ubahStatusJadiInput($idtrans){
		$this->db->trans_begin();
		
		$query = $this->db->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
							->where('IDPENYESUAIANSTOK',$idtrans)
							->get('TPENYESUAIANSTOK')->row();
		
		//HAPUS KARTUSTOK//
		$this->db->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				->where('KODETRANS',$query->KODEPENYESUAIANSTOK)
				->delete('KARTUSTOK');
		//HAPUS KARTUSTOK//
		
		$this->db->set('STATUS','S')
				->where('IDOPNAMESTOK',$query->IDOPNAMESTOK)
				->update('TOPNAMESTOK');
		
		$data = array (
			'USERBATAL' => $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLBATAL'=> date('Y.m.d'),
			'JAMBATAL'=>date('H:i:s'),
			'STATUS'=>'I',
		);
		$this->db->where("IDPENYESUAIANSTOK",$idtrans);
		$this->db->update('TPENYESUAIANSTOK',$data);
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data transaksi tidak dapat dibatalkan'; 
		}
		$this->db->trans_commit();
		return '';
	}
	
	function getStatusTrans($id){
		return $this->db->where('IDPENYESUAIANSTOK',$id)->get('TPENYESUAIANSTOK')->row()->STATUS;
	}
}
?>