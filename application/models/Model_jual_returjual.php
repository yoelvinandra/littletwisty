<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_jual_returjual extends MY_Model{
	//param yang berhubungan dengan transaksi
	public $param = array(
		"id"       => "IDSO",
		"kode"     => "KODESO",
		"idbarang" => "IDBARANG",
		"table"    => "TSO",
		"tabledtl" => "TSODTL",
	);
	
	public function getAll(){
		$data = $this->db->query("select * from TRETURJUAL");
		return $data->result();
	}
	
	public function getDataReturJual($kodetrans){
		$data = $this->db->query("select * from TRETURJUAL where kodereturjual = '$kodetrans' and idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}");
		return $data->row();
	}
	
	public function comboGrid(){
		
		$sql = "select distinct a.kodereturjual as VALUE, concat(a.TGLTRANS,' - ',a.kodereturjual) as TEXT, a.USERENTRY,a.IDLOKASI, b.KODELOKASI,b.NAMALOKASI
				from TRETURJUAL a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by a.TGLTRANS";
		$query = $this->db->query($sql);
		$data['rows'] = $query->result();
		return $data;
	}
	
	public function comboGridBarang($pagination){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODEBARANG';
		}

		$sql = "select distinct b.KODEBARANG as KODE, b.NAMABARANG as NAMA,
		        b.IDBARANG as ID,b.SATUAN,a.SATUANUTAMA,a.KONVERSI,a.JML,a.HARGA,a.DISCPERSEN,a.DISC,a.DISCKURS,a.PAKAIPPN,a.PPNPERSEN
				from TRETURJUALDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and (b.KODEBARANG like ? or b.NAMABARANG like ?) and a.IDRETURJUAL = ?
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%',$pagination['idtrans']));

		$data['rows'] = $query->result();
		return $data;
	}
	
	public function dataGrid($pagination, $filter){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'kodereturjual';
		}
		
		$data = [];		
		$sql = "select b.IDLOKASI,b.NAMALOKASI, a.IDRETURJUAL, a.TGLTRANS, a.kodereturjual, a.TOTAL,a.CATATAN,a.CATATANCUSTOMER,ifnull(a.POTONGANPERSEN,0) as POTONGANPERSEN, ifnull(a.POTONGANRP,0) as POTONGANRP ,a.PEMBAYARAN,
					   a.PPNRP as PPN, a.GRANDTOTAL, a.GRANDTOTALDISKON,a.CATATAN, d.USERNAME as USERINPUT, a.TGLENTRY as TGLINPUT, '' as HARI,
					   a.JAMENTRY, e.USERNAME as USERBATAL, a.TGLBATAL, a.STATUS, a.ALASANBATAL,CONCAT(a.CATATANCUSTOMER) as NAMACUSTOMER,f.KODECUSTOMER,f.ALAMAT as ALAMATCUSTOMER,f.TELP as TELPCUSTOMER,f.MEMBER,f.KONSINYASI,f.DISKONMEMBER
				from TRETURJUAL a
				left join MLOKASI b on a.IDLOKASI=b.IDLOKASI and a.IDPERUSAHAAN=b.IDPERUSAHAAN
				left join MUSER d on a.USERENTRY = d.USERID
				left join MUSER e on a.USERBATAL = e.USERID
				left join MCUSTOMER f on a.IDCUSTOMER=f.IDCUSTOMER and a.IDPERUSAHAAN=f.IDPERUSAHAAN
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				$whereLokasi {$filter['sql']}
				order by a.TGLTRANS DESC, a.kodereturjual DESC, a.TGLENTRY, a.JAMENTRY";
		
		$q             = $this->db->query($sql, $filter['param']);
		$data["rows"]  = $q->result();
		$data["total"] = count($data["rows"]);
		
		$dataHari = [
		    "Sunday"    => "MINGGU",   
		    "Monday"    => "SENIN",   
		    "Tuesday"   => "SELASA",   
		    "Wednesday" => "RABU",     
		    "Thursday"  => "KAMIS",    
		    "Friday"    => "JUMAT",   
		    "Saturday"  => "SABTU",      
		];
		
		foreach($data["rows"] as $d)
		{
		    $d->HARI = $dataHari[date('l', strtotime($d->TGLTRANS))];
		}
		
		return $data;
	}

	function loadDataDetail($idtrans){
		
		$data = [];		
        $sql = "select b.IDBARANG as ID, b.KODEBARANG as KODE, a.JML as QTY, a.HARGA,a.SATUANREF as SATUANKECIL,a.PAKAIPPN,c.PEMBAYARAN,ifnull(a.DISCPERSEN,0) as DISKON,ifnull(a.DISC,0) as DISKONRP
				,d.ALAMAT as ALAMATCUSTOMER,d.TELP as TELPCUSTOMER,d.IDCUSTOMER,d.KODECUSTOMER,d.NAMACUSTOMER,c.CATATANCUSTOMER,a.KETERANGAN as NAMA,c.GRANDTOTAL,c.GRANDTOTALDISKON,c.POTONGANRP,c.POTONGANPERSEN
				from TRETURJUALDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				left join TRETURJUAL c on a.IDRETURJUAL = c.IDRETURJUAL
				left join MCUSTOMER d on c.IDCUSTOMER = d.IDCUSTOMER
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDRETURJUAL = $idtrans
				order by URUTAN";

		$query         = $this->db->query($sql)->result();
		return $query;
	}
	
	function loadDataRekap($kodetrans){
		$sql = "select b.IDBARANG, b.KODEBARANG, b.NAMABARANG,a.TUTUP,
				a.JML, a.SATUAN
				from TRETURJUALDTL a
				inner join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDRETURJUAL = '{$kodetrans}'
				order by a.URUTAN";
		$query = $this->db->query($sql)->result();
		return $query;
	}
	
	function informasiRealisasiPenjualan($idtrans,$idbarang){
		$sql = "select a.kodereturjual,a.JML,a.SATUAN,a1.TGLTRANS,a1.USERENTRY
				from TRETURJUALDTL a
				inner join TRETURJUAL a1 on a.IDRETURJUAL = a1.IDRETURJUAL
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDBBK = '{$idtrans}' and a.IDBARANG = '{$idbarang}' and (a1.STATUS = 'S' or a1.STATUS = 'P')";
		$query = $this->db->query($sql)->result();
		return $query;
	}
		
	function simpan($idtrans,$data,$a_detail,$edit){
		// start transaction
		$tr = $this->db->trans_begin();
		
		if($edit){
			$this->db->where("IDRETURJUAL",$idtrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
			$this->db->update('TRETURJUAL',$data);
		}else{
			$this->db->insert('TRETURJUAL',$data);
        }
        
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 'Simpan Data Gagal <br>Kesalahan Pada Header Data Transaksi';
		}
		
		if($edit){
			$this->db->where("IDRETURJUAL",$idtrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					 ->delete('TRETURJUALDTL');
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
			/*
			$sql = "select NAMABARANG
                from MBARANG a
                where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
                        and a.IDBARANG = {$item->idbarang}";
			$query = $this->db->query($sql)->row();
		
			if(get_saldo_stok($_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],$item->idbarang,$data['IDLOKASI'],$data['TGLTRANS'],$data['kodereturjual'],$item->jml)->QTY < 0)
			{
				
				$this->db->trans_rollback();
				return 'Stok '.$query->NAMABARANG." minus";
			}
            */
            if ($item->jml > 0) {
                if($item->pakaippn == "TIDAK")$pakaippn = 0;
                else if($item->pakaippn == "EXCL")$pakaippn = 1;
                else if($item->pakaippn == "INCL")$pakaippn = 2;
                $data_values = array (
                    'IDPERUSAHAAN'  => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
                    'IDRETURJUAL'   => $idtrans,
                    'kodereturjual' => $data['kodereturjual'],
                    'KETERANGAN' 	=> $item->namabarang,
                    //'IDBBK'         => $item->idso,
                    'IDBARANG'      => $item->idbarang,
                    'URUTAN'        => $i,
                    'JML'           => $item->jml,
                    //'JMLBONUS'      => $item->jmlbonus,
                    'SATUANREF'     => $item->satuan,
                    'SATUAN'        => $item->satuan,
                    //'SATUANUTAMA'   => $item->satuanutama,
                    //'KONVERSI'      => $item->konversi,
					'IDCURRENCY'    => 1,
                    'HARGA'         => $item->harga,
                    //'NILAIKURS'     => $item->nilaikurs,
                    'HARGAKURS'     => $item->hargakurs,
                    'DISCPERSEN'    => $item->discpersen,
                    'DISC'          => $item->disc,
                    'DISCKURS'      => $item->disckurs,
                    'SUBTOTAL'      => $item->subtotal,
                    'SUBTOTALKURS'  => $item->subtotalkurs,
                    'PAKAIPPN'      => $pakaippn,
                    'PPNPERSEN'     => $item->ppnpersen,
                    'PPNRP'         => $item->ppnrp,
                    'TUTUP'         => 0
                );
				
                $this->db->insert('TRETURJUALDTL',$data_values);
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    return 'Simpan Data Gagal <br>Kesalahan Pada Detail Data Transaksi';
                }
                $i++;
            }else{
				$this->db->trans_rollback();
				return "Jumlah Barang {$item->namabarang} Tidak Boleh 0";
			}
		}
		
		$this->db->trans_commit();
		return '';
	}
	
	function batal($idtrans,$alasan){
		$this->db->trans_begin();
		
		//HAPUS KARTUSTOK//
		$kodetrans = $this->db->select('kodereturjual')
						      ->where('IDRETURJUAL',$idtrans)
						      ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						      ->get('TRETURJUAL')->row()->kodereturjual;

		$exe = $this->db->where("kodetrans",$kodetrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					 ->delete('KARTUSTOK');
		
		$data = array (
			'TGLBATAL'    => date('Y.m.d'),
			'JAMBATAL'    => date('H:i:s'),
			'USERBATAL'   => $_SESSION[NAMAPROGRAM]['USERID'],
			'ALASANBATAL' => $alasan,
			'STATUS'      => 'D',
		);
		$this->db->where("IDRETURJUAL",$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
		$this->db->update('TRETURJUAL',$data);
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data Transaksi Tidak Dapat Dibatalkan'; 
		}
		$this->db->trans_commit();
		return '';
	}
	
	function ubahStatusJadiSlip($idtrans){
		// start transaction
		$this->db->trans_begin();
		
		//HAPUS KARTUSTOK//
		$kodetrans = $this->db->select('kodereturjual')
						      ->where('IDRETURJUAL',$idtrans)
						      ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						      ->get('TRETURJUAL')->row()->kodereturjual;

		$exe = $this->db->where("kodetrans",$kodetrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					 ->delete('KARTUSTOK');
					 
		if($exe != true){
			$this->db->trans_rollback();
			return $exe;
		}
		
        //INSERT KARTUSTOK//
		$sql = "select *,b.NAMACUSTOMER,b.CATATAN AS CATATANCUSTOMER,ifnull(e.KODEPENJUALAN,"") as KODEPENJUALAN
                from TRETURJUAL a
				left join MCUSTOMER b on b.IDCUSTOMER = a.IDCUSTOMER
				left join TRETURJUALDTL c on c.IDRETURJUAL = a.IDRETURJUAL
				inner join MBARANG d on d.IDBARANG = c.IDBARANG and d.STOK = 1
				left join TPENJUALAN e on e.IDPENJUALAN = a.IDPENJUALAN
                where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
                        and a.IDRETURJUAL = {$idtrans}";
		
        $query = $this->db->query($sql)->result();
		
		foreach($query as $row)
		{
		    $kodepenjualan = "";
		    if($row->KODEPENJUALAN != "")
		    {
		        $kodepenjualan = "#".$row->KODEPENJUALAN;
		    }
		    
			$param = array(
				"IDPERUSAHAAN" => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
				"IDLOKASI" => $row->IDLOKASI,
				"MODUL" => 'RETUR JUAL',
				"IDTRANS" => $row->IDRETURJUAL,
				"KODETRANS" => $row->KODERETURJUAL,
				"IDBARANG" => $row->IDBARANG,
				"KONVERSI1" => 1,
				"KONVERSI2" => 1,
				"TGLTRANS" => $row->TGLTRANS,
				"JENISTRANS" => 'JUAL',
				"KETERANGAN" => 'RETUR JUAL DARI '.$row->NAMACUSTOMER.' ('.$row->CATATANCUSTOMER.')'.$kodepenjualan,
				"MK" =>  'K',
				"JML" => $row->JML,
				"TOTALHARGA" => $row->SUBTOTAL,
				"STATUS" => '1',
			);
			$exe =  $this->db->insert('KARTUSTOK',$param);
		
			if($exe != true){
				$this->db->trans_rollback();
				return $exe;
			}
		}
       

		//ubah status jadi S ketika selesai menutup
		$this->db->set('STATUS','S')
				 ->where('IDRETURJUAL',$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				 ->update('TRETURJUAL');
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
			'STATUS'=>'I',
		);
		$this->db->where("IDRETURJUAL",$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
		$this->db->update('TRETURJUAL',$data);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return 'Data Transaksi Tidak Dapat Dibatalkan'; 
		}
		
		$sql = "select a.IDBBK,a.kodereturjual
				from TRETURJUALDTL a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDRETURJUAL = {$idtrans}";
		$item = $this->db->query($sql)->row();
		
		//Kembalikan status ke 'P' untuk detail transaksi
		$this->db->set("STATUS",'S')
				->where("IDSO",$item->IDBBK)
				->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				->update("TSO");
		if($this->db->trans_status()===FALSE){
			return 'Update Status SO Gagal';
		}
		
		//HAPUS KARTUSTOK//
		$kodetrans = $this->db->select('kodereturjual')
						->where('IDRETURJUAL',$idtrans)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('TRETURJUAL')->row()->kodereturjual;
		$approve = 0;
		$exe = hapus_kartu_stok($kodetrans,$approve);	
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		//HAPUS KARTUSTOK//
		
		$this->db->trans_commit();
		return '';
	}

	function cek_valid_fakturpajak($nofakturpajak, $IDRETURJUAL = 0){
		$sql = "select IDRETURJUAL, kodereturjual, NOFAKTURPAJAK 
				from TRETURJUAL a
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and IDRETURJUAL <> {$IDRETURJUAL}
						and NOFAKTURPAJAK = '{$nofakturpajak}'";
		$query = $this->db->query($sql)->row();
		if(isset($query)){
			return 'No Faktur Pajak ('.$query->NOFAKTURPAJAK.') Sudah Digunakan Oleh Transaksi ('.$query->kodereturjual.'), Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}

	function getStatusTrans($id){
		return $this->db->where('IDRETURJUAL',$id)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('TRETURJUAL')->row()->STATUS;
	}
}
?>