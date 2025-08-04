<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_inventori_returbeli extends MY_Model{

	//param yang berhubungan dengan transaksi
	public $param = array(
		"id"       => "IDBBK",
		"kode"     => "KODEBBK",
		"idbarang" => "IDBARANG",
		"table"    => "TBBK",
		"tabledtl" => "TBBKDTLBRG",
	);
	
	public function getAll(){
		$data = $this->db->query("select * from TRETURBELI");
		return $data->result();
	}
	
	public function laporan($filter){
	}
	
	public function comboGrid()
	{
		/*$sql = "select a.KODERETURBELI, a.IDRETURBELI, a.TGLTRANS, a.USERENTRY,a.IDLOKASI, b.KODELOKASI,b.NAMALOKASI
				from TRETURBELI a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and (a.KODERETURBELI like ?) and a.CLOSING = 0 and a.STATUS in ('S','P') and a.IDLOKASI = ? and a.IDSUPPLIER = ? 
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%',$pagination['lokasi'],$pagination['supplier']));
		*/
		
		$sql = "select distinct a.KODERETURBELI as VALUE, concat(a.TGLTRANS,' - ',a.KODERETURBELI) as TEXT, a.USERENTRY,a.IDLOKASI, b.KODELOKASI,b.NAMALOKASI
				from TRETURBELI a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by a.TGLTRANS DESC, a.KODERETURBELI DESC";
		$query = $this->db->query($sql);
		
		$data['rows'] = $query->result();
		return $data;
	}
	
	public function comboGridBarang($pagination)
	{
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'NAMA';
		}

		$sql = "select distinct b.KODEBARANG as KODE, b.NAMABARANG as NAMA,
		        b.IDBARANG as ID,a.SATUAN,a.SATUANUTAMA,a.KONVERSI,a.HARGA,a.JML,a.PAKAIPPN,a.PPNPERSEN
				from TRETURBELIDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and (b.KODEBARANG like ? or b.NAMABARANG like ?) and a.TUTUP = 0 and a.IDRETURBELI = ?
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%',$pagination['idtrans']));

		$data['rows'] = $query->result();
		return $data;
	}
	
	public function dataGrid($pagination, $filter){
		$data = [];		
        $sql = "select a.IDRETURBELi, a.TGLTRANS, a.KODERETURBELI, b.NAMASUPPLIER, a.TOTAL,a.PPNRP as PPN,a.GRANDTOTAL,  a.CATATAN, d.USERNAME as USERINPUT, a.TGLENTRY as TGLINPUT,e.USERNAME as USERBATAL, a.TGLBATAL, a.STATUS,a.ALASANBATAL
				from TRETURBELI a
				left join MSUPPLIER b on a.IDSUPPLIER = b.IDSUPPLIER
				left join MUSER d on a.USERENTRY = d.USERID
				left join MUSER e on a.USERBATAL = e.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				{$filter['sql']}
				order by TGLTRANS DESC,  KODERETURBELI DESC";
		//{$filter['sql']}
		$q             = $this->db->query($sql, $filter['param']);
		$data["rows"]  = $q->result();
		$data["total"] = count($data["rows"]);
		return $data;
	}
	
	function loadDataHeader($idtrans){
		$sql = "select a.IDRETURBELI as IDTRANS, a.KODERETURBELI as KODETRANS, a.TGLTRANS,b.IDSUPPLIER, b.NAMASUPPLIER, b.ALAMAT as ALAMATSUPPLIER,b.TELP as TELPSUPPLIER, a.TGLJATUHTEMPO,a.CATATAN,a.ALASANBATAL, CONCAT(c.KODESYARATBAYAR,' | ',c.NAMASYARATBAYAR,' | ',c.SELISIH,' | ',c.IDSYARATBAYAR) as SYARATBAYAR,a.IDLOKASI
				from TRETURBELI a
				left join MSUPPLIER b on a.IDSUPPLIER = b.IDSUPPLIER
				left join MSYARATBAYAR c on a.IDSYARATBAYAR = c.IDSYARATBAYAR
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDRETURBELI = $idtrans  and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by TGLTRANS";
		$query         = $this->db->query($sql)->row();
		return $query;
	}
	function loadDataDetail($idtrans,$kodetrans){
	    $whereTrans = "";
	    if($idtrans != "")
	    {
	        $whereTrans = "and a.IDRETURBELI = '{$idtrans}'";
	    }
	    
	    if($kodetrans != "")
	    {
	         $whereTrans = "and a.KODERETURBELI = '{$kodetrans}'";
	    }
		$sql = "select b.IDBARANG as ID, b.KODEBARANG as KODE,b.NAMABARANG as NAMA, a.JML as QTY, a.DISCPERSEN as DISKON, a.HARGA as HARGA,a.PAKAIPPN,a.SATUAN as SATUANBESAR
				from TRETURBELIDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereTrans and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by URUTAN";
		$query         = $this->db->query($sql)->result();
		return $query;
	}
	
	function loadData($idtrans){
		$sql = "select distinct a.KODERETURBELI,a.IDBBK,a.ADANPWP,
				b.KODEBBK,a.IDBARANG,c.KODEBARANG,c.NAMABARANG,
				a.JML,a.JMLBONUS,a.SATUAN,b.SATUANUTAMA, b.KONVERSI,a.IDCURRENCY, d.SIMBOL,a.HARGA,a.DISCPERSEN,a.DISC,a.DISCKURS,a.SUBTOTAL,a.NILAIKURS,a.HARGAKURS,a.SUBTOTALKURS,a.URUTAN,a.PAKAIPPN,a.PPNPERSEN,a.PPNRP,a.PPH22PERSEN,a.PPH22RP,e1.NAMAPERKIRAAN as AKUNBIAYA,a.IDAKUNBIAYA,e2.NAMAPERKIRAAN as AKUNHUTANG,a.IDAKUNHUTANG
				from TRETURBELIDTL a 
				left join TRETURBELI a1 on a.IDRETURBELI = a1.IDRETURBELI
				left join TBBKDTLBRG b on a.IDBBK = b.IDBBK and a.IDBARANG = b.IDBARANG
				left join MBARANG c on a.IDBARANG = c.IDBARANG
				left join MCURRENCY d on a.IDCURRENCY = d.IDCURRENCY
				left join MPERKIRAAN e1 on a.IDAKUNBIAYA = e1.IDPERKIRAAN
				left join MPERKIRAAN e2 on a.IDAKUNHUTANG = e2.IDPERKIRAAN
 				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDRETURBELI = {$idtrans}
				order by URUTAN";
		$query = $this->db->query($sql)->result();
		return $query;
	}
	
	function loadDataRekap($idtrans){
		$sql = "select a.IDBARANG, b.KODEBARANG, b.NAMABARANG, a.TUTUP,
				a.JML,a.JMLBONUS, a.SISA, a.TERPENUHI,a.SATUAN
				from TRETURBELIDTLBRG a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDRETURBELI = '{$idtrans}'
				order by a.URUTAN";
		$query = $this->db->query($sql)->result();
		return $query;
	}
	
	function informasiBBK($idtrans,$idbarang){
		$sql = "select a.KODERETURBELI,a.JML,a.SATUAN,
				a1.TGLTRANS,a1.USERENTRY,b.KODESUPPLIER,b.NAMASUPPLIER
				from TRETURBELIDTL a
				inner join TRETURBELI a1 on a.IDRETURBELI = a1.IDRETURBELI
				inner join MSUPPLIER b on a1.IDSUPPLIER = b.IDSUPPLIER
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDBBK = '{$idtrans}' and a.IDBARANG = '{$idbarang}' and (a1.STATUS = 'S' or a1.STATUS = 'P')";
		$query = $this->db->query($sql)->result();
		return $query;
	}
		
	function simpan($idtrans,$data,$a_detail,$edit)
	{
		// start transaction
		$this->db->trans_begin();
		if($edit){
			$this->db->where("IDRETURBELI",$idtrans)
				     ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
			$this->db->update('TRETURBELI',$data);
		}else{
			$this->db->insert('TRETURBELI',$data);
		}
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return 'Simpan Data Gagal <br>Kesalahan Pada Header Data Transaksi';
		}
		
		if($edit){
			//cancel_tutup_trans("tpolain",$idtrans);
			$this->db->where("IDRETURBELI",$idtrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					 ->delete('TRETURBELIDTL');
		}else{
			//mengambil auto increment
			$idtrans = $this->db->insert_id();
		}
		// query detail
		$i = 1;
		foreach ($a_detail as $item) {
			//$response = cek_valid_data_detail('MBARANG','IDBARANG',$item->idbarang,'Satuan','SATUAN',$item->satuan,'SATUAN2',$item->satuan,'SATUAN3',$item->satuan);
			if($response != ''){
				$this->db->trans_rollback();
				return $response;
			}
			
			//mengecek apakah ada transaksi yang sudah close
			/*$exe =  cek_status_close($this->param,$item->idbbk,$item->idbarang);
			if($exe != ''){
				//jika sudah ditutup
				$this->db->trans_rollback();
				return $exe;
			}*/
			
			//cek tanggal apa melebihi atau tidak
			/*$exe = get_tgl_trans('TBBK','IDBBK',$item->idbbk);
			if($exe > $data['TGLTRANS']){
				//jika tanggal melebihi
				$this->db->trans_rollback();
				return 'Kesalahan pada Tanggal Transaksi untuk Kode '.$item->kodebbk;
			}*/
			
			if($item->pakaippn == "TIDAK")$pakaippn = 0;
			else if($item->pakaippn == "EXCL")$pakaippn = 1;
			else if($item->pakaippn == "INCL")$pakaippn = 2;
			$data_values = array (
				'IDPERUSAHAAN'  => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
				'IDRETURBELI'   => $idtrans,
				'KODERETURBELI' => $data['KODERETURBELI'],
				'IDBARANG'      => $item->idbarang,
				'IDCURRENCY'    => 1,
				'URUTAN'        => $i,
				'JML'           => $item->jml,
				'SATUAN'        => $item->satuan,
				'SATUANUTAMA'   => $item->satuanutama,
				'KONVERSI'      => $item->konversi,
				'HARGA'         => $item->harga,
				'DISCPERSEN'    => $item->discpersen,
				'DISC'          => $item->disc,
				'DISCKURS'      => $item->disckurs,
				'SUBTOTAL'      => $item->subtotal,
				'NILAIKURS'     => $item->nilaikurs,
				'HARGAKURS'     => $item->hargakurs,
				'SUBTOTALKURS'  => $item->subtotalkurs,
				'PAKAIPPN'      => $pakaippn,
				'PPNPERSEN'     => $item->ppnpersen,
				'PPNRP'         => $item->ppnrp,
				'PPH22RP'       => $item->pph22rp,
				'PPH22PERSEN'   => $item->pph22persen,
				'TUTUP'         => 0
			);		
			$this->db->where('IDBARANG',$item->idbarang) 
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					 ->insert('TRETURBELIDTL',$data_values);
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				return 'Simpan Data Gagal <br>Kesalahan Pada Detail Data Transaksi';
			}
			$i++;
		}
		$this->db->trans_commit();
		return $this->ubahStatusJadiSlip($idtrans);
	}
	
	function batal($idtrans,$alasan){
		$this->db->trans_begin();
		
		$sql = "select a.*
				from TRETURBELIDTL a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDRETURBELI = {$idtrans}";
		$query = $this->db->query($sql)->result();
		
		$kodetrans = $this->db->select('KODERETURBELI')
						->where('IDRETURBELI',$idtrans)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('TRETURBELI')->row()->KODERETURBELI;
					
		
		//HAPUS KARTUSTOK
		$approve = 0;
		$exe = hapus_kartu_stok($kodetrans,$approve);	
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		//HAPUS KARTUSTOK
		
		$data = array (
			'TGLBATAL'    => date('Y.m.d'),
			'JAMBATAL'    => date('H:i:s'),
			'USERBATAL'   => $_SESSION[NAMAPROGRAM]['USERID'],
			'ALASANBATAL' => $alasan,
			'STATUS'      => 'D',
		);
		$this->db->where("IDRETURBELI",$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
		$this->db->update('TRETURBELI',$data);
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data Transaksi Tidak Dapat Dibatalkan'; 
		}
		$this->db->trans_commit();
		return '';
	}
	
	function ubahStatusJadiSlip($idtrans){
		// start transaction
		$tr = $this->db->trans_begin();
		
		$sql = "select a.*,a1.IDLOKASI,a1.TGLTRANS,c.KODEBARANG,c.NAMABARANG
				from TRETURBELIDTL a
				left join TRETURBELI a1 on a.IDRETURBELI = a1.IDRETURBELI
				left join MBARANG c on a.IDBARANG = c.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDRETURBELI = {$idtrans}";
		$query = $this->db->query($sql)->result();
		
		$sql = "select a.*
		  		from TRETURBELI a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDRETURBELI = {$idtrans}";
		$query = $this->db->query($sql)->row();

		$sql = "select KODERETURBELI as KODETRANS, b.BADANUSAHA, b.NAMASUPPLIER
		from TRETURBELI a
		left join MSUPPLIER b on b.IDSUPPLIER = a.IDSUPPLIER
		where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				and a.IDRETURBELI = {$idtrans}";
		$query = $this->db->query($sql)->row();

		//HAPUS KARTUSTOK//
		$kodetrans = $query->KODETRANS;
		$approve   = 0;
		$exe       = hapus_kartu_stok($kodetrans,$approve);
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		//HAPUS KARTUSTOK//		

		//INSERT KARTUSTOK//
		$param = array(
			"id"        => "IDRETURBELI",
			"kode"      => "KODERETURBELI",
			"table"     => "TRETURBELI",
			"tabledtl"  => "TRETURBELIDTL",
			"kodetrans" => $query->KODETRANS,
			"mk"        => "K",
			"jenis"       => "RETUR BELI",
            "keterangan"  => "RETUR BELI KE ".$query->BADANUSAHA.". ".$query->NAMASUPPLIER,
            "jmlbonus"    => 1,
			"jenisbarang" => 'STOK',
			//jika 1 artinya transreferensi yg diisi
			//jika -1, status diisi 1 karena tidak perlu approve
			"approve"          => -1,
			//"idtransreferensi" => "IDBBK"
		);
		$exe = insert_kartu_stok($idtrans,$param);	
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		
		//INSERT KARTUSTOK//
		
		//ubah status jadi S ketika selesai menutup
		$this->db->set('STATUS','S')
				 ->where('IDRETURBELI',$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				 ->update('TRETURBELI');
		if($this->db->trans_status()===FALSE){
			$this->db->trans_rollback();
			return $exe;
		}	
		$this->db->trans_commit();
	}
	
	function ubahStatusJadiInput($idtrans){
		$this->db->trans_begin();
		
		//kembalikan status menjadi 'I'
		$data = array (
			'STATUS'    =>'I',
		);
		$this->db->where("IDRETURBELI",$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
		$this->db->update('TRETURBELI',$data);
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data Transaksi Tidak Dapat Dibatalkan'; 
		}
		
		$sql = "select a.*
				from TRETURBELIDTL a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDRETURBELI = {$idtrans}";
		$query = $this->db->query($sql)->result();
		
		foreach ($query as $item) {
			//update terpenuhi karena pasti 1TO1
			$this->db->set('TERPENUHI',0)
					 ->set('SISA',$item->JML)
					 ->where('IDBBK',$item->IDBBK)
					 ->where('IDBARANG',$item->IDBARANG)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					 ->update('TBBKDTLBRG');
			if($this->db->trans_status()===FALSE){
				$this->db->trans_rollback();
				return 'Update Terpenuhi gagal';
			}	
			
			//true pada ubahStatusJadiSlip, false pada batal_ubahStatusJadiSlip
			tutup_all_trans($this->param,$item->IDBBK,false);	
		}
		
		$kodetrans = $this->db->select('KODERETURBELI')
						->where('IDRETURBELI',$idtrans)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('TRETURBELI')->row()->KODERETURBELI;
		
		//HAPUS KARTUSTOK
		$approve = 0;
		$exe = hapus_kartu_stok($kodetrans,$approve);	
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		//HAPUS KARTUSTOK
		
		//hapus semua yg berhubungan dengan trans 
		$sql = "select KODERETURBELI,IDBBK
				from TRETURBELI 
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDRETURBELI = {$idtrans}";
		$query = $this->db->query($sql)->row();
		
		//update status menjadi S karena sudah terjadi pemakaian
		$this->db->set('STATUS','S')
				->where('IDBBK',$query->IDBBK)
				->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				->update('TBBK');
				
		if($this->db->trans_status()===FALSE){
			$this->db->trans_rollback();
			return 'Update status gagal';
		}	
		
		$exe = $this->db->where('KODETRANS',$query->KODERETURBELI)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->delete('KARTUHUTANG');
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 'Delete Kartuhutang Gagal';
		}
		$this->db->trans_commit();
		return '';
	}
		
	function getStatusTrans($id){
		return $this->db->where('IDRETURBELI',$id)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('TRETURBELI')->row()->STATUS;
	}
	
	function cekDetailTrans($idtrans=0,$a_detail){
		foreach($a_detail as $item){
			$sql = "select c.KODEBBK as KODETRANS,a.KODERETURBELI as KODELAIN
					from TRETURBELI a
					left join TRETURBELIDTL b on a.IDRETURBELI = b.IDRETURBELI
					left join TBBK c on b.IDBBK = c.IDBBK
					where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.STATUS = 'I' and a.IDRETURBELI != {$idtrans} and b.IDBBK = {$item->idbbk}";
			$query = $this->db->query($sql)->row();
			if($query != null)
				return $query;
		}
		return '';
	}
}
?>