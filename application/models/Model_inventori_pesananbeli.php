<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_inventori_pesananbeli extends MY_Model{
	
	//param yang berhubungan dengan transaksi
	public $param = array(
		"id"       => "IDPO",
		"kode"     => "KODEPO",
		"idbarang" => "IDBARANG",
		"table"    => "TPO",
		"tabledtl" => "TPODTL",
	);
	
	public function getAll(){
		$data = $this->db->query("select * from TPO");
		return $data->result();
	}
	
	public function comboGrid(){
		/*
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODEPO';
		}

		$sql = "select a.KODEPO, a.IDPO, a.TGLTRANS,
					a.USERENTRY,a.ADANPWP,a.IDLOKASI,
					b.KODELOKASI,b.NAMALOKASI,a.CATATAN
				from TPO a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI and b.IDPERUSAHAAN = a.IDPERUSAHAAN
                where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
                        and a.STATUS in ('S','P')
                        and (a.KODEPO like ?) 
                        and a.CLOSING = 0 
                        and a.IDLOKASI = ?
                        and a.IDSUPPLIER = ?
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
        $query = $this->db->query($sql, array('%'.$pagination['q'].'%',$pagination['lokasi'],$pagination['supplier']));
		*/
		
		$sql = "select distinct a.KODEPO as VALUE, concat(a.TGLTRANS,' - ',a.KODEPO,' - ',ifnull(a.CATATAN,'')) as TEXT,
					a.USERENTRY,a.ADANPWP,a.IDLOKASI,
					b.KODELOKASI,b.NAMALOKASI,a.CATATAN
				from TPO a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI and b.IDPERUSAHAAN = a.IDPERUSAHAAN
                where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
                order by a.TGLTRANS DESC,a.KODEPO DESC
        ";
        $query = $this->db->query($sql);
		
		$data['rows'] = $query->result();
		return $data;
	}
	
	public function comboGridTransaksi($status){
		/*
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODEPO';
		}

		$sql = "select a.KODEPO, a.IDPO, a.TGLTRANS,
					a.USERENTRY,a.ADANPWP,a.IDLOKASI,
					b.KODELOKASI,b.NAMALOKASI,a.CATATAN
				from TPO a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI and b.IDPERUSAHAAN = a.IDPERUSAHAAN
                where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
                        and a.STATUS in ('S','P')
                        and (a.KODEPO like ?) 
                        and a.CLOSING = 0 
                        and a.IDLOKASI = ?
                        and a.IDSUPPLIER = ?
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
        $query = $this->db->query($sql, array('%'.$pagination['q'].'%',$pagination['lokasi'],$pagination['supplier']));
		*/
		if($status != "")
		{	
			$whereStatus = count($status)>0 ? " and (a.status='".implode("' or a.status='", $status)."')" : '';
		}
		
		$sql = "select distinct concat(a.IDPO,' | ',a.KODEPO) as VALUE, concat(a.TGLTRANS,' - ',a.KODEPO,' - ',ifnull(a.CATATAN,'')) as TEXT,
					a.USERENTRY,a.ADANPWP,a.IDLOKASI,
					b.KODELOKASI,b.NAMALOKASI,a.CATATAN
				from TPO a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI and b.IDPERUSAHAAN = a.IDPERUSAHAAN
                where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereStatus
                order by a.TGLTRANS DESC,a.KODEPO DESC
        ";
        $query = $this->db->query($sql);
		
		$data['rows'] = $query->result();
		return $data;
	}
	
	public function comboGridBarang($pagination,$idtrans){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODEBARANG';
		}

		$sql = "select distinct b.KODEBARANG as KODE, b.NAMABARANG as NAMA,
                        b.IDBARANG as ID, a.SATUAN, a.SATUANUTAMA, a.KONVERSI, b.HARGABELI, a.JML, a1.TERPENUHI, a1.SISA, 
                        a.HARGA, a.DISCPERSEN, a.DISC, a.DISCKURS, a.PAKAIPPN, a.PPNPERSEN, a.PPH22PERSEN
                from TPODTL a
                left join TPODTLBRG a1 on a1.IDPO = a.IDPO and a1.IDBARANG = a.IDBARANG and a1.IDPERUSAHAAN = a.IDPERUSAHAAN
				left join MBARANG b on a.IDBARANG = b.IDBARANG and b.IDPERUSAHAAN = a.IDPERUSAHAAN
                where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
                        and (b.KODEBARANG like ? or b.NAMABARANG like ?) 
                        and a.TUTUP = 0 
                        and a.IDPO = ?
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%',$idtrans));

		$data['rows'] = $query->result();
		return $data;
	}
	
	public function comboGridFilter($pagination){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODETRANS';
		}

		$sql = "select distinct a.KODEPO as KODETRANS, 
					a.IDPO as IDTRANS, a.TGLTRANS, a.USERENTRY,a.IDLOKASI,b.KODELOKASI,b.NAMALOKASI
				from TPO a
				left join TPODTLBRG a1 on a.IDPO = a1.IDPO and a1.TUTUP = 0
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					and (a.KODEPO like ?)
					and (a.IDLOKASI like ?)
				order by a.TGLTRANS DESC,{$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%',$pagination['lokasi']));

		$data['rows'] = $query->result();
		return $data;
	}
	
	public function comboGridforKas($pagination, $idSupplier = ''){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'a.KODEPO';
		}

		$tempSql = '';
		if ($idSupplier <> '') {
			$tempSql = ' and a.idsupplier = '.$idSupplier;
		}

		$sql = "select a.idpo, a.kodepo, b.namasupplier, a.tgltrans, a.grandtotal
				from TPO a
				inner join msupplier b on a.idsupplier = b.idsupplier
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and 
					  a.kodepo like ? and 
					  a.status = 'S'
					  {$tempSql}
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%'));

		$data['rows'] = $query->result();
		return $data;
	}
	
	public function dataGrid($pagination, $filter){
		$data = [];		
        $sql = "select a.IDPO, a.TGLTRANS, a.KODEPO, b.NAMASUPPLIER, a.TOTAL,a.PPNRP as PPN,a.GRANDTOTAL, a.CATATAN, d.USERNAME as USERINPUT, a.TGLENTRY as TGLINPUT,e.USERNAME as USERBATAL, a.TGLBATAL, a.STATUS,a.ALASANBATAL
				from TPO a
				left join MSUPPLIER b on a.IDSUPPLIER = b.IDSUPPLIER
				left join MUSER d on a.USERENTRY = d.USERID
				left join MUSER e on a.USERBATAL = e.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				{$filter['sql']}
				order by a.TGLENTRY DESC,a.JAMENTRY DESC, a.KODEPO DESC";
		
		$q             = $this->db->query($sql, $filter['param']);
		$data["rows"]  = $q->result();
		$data["total"] = count($data["rows"]);
		return $data;
	}
	
	function loadDataHeader($idtrans){
		$sql = "select a.IDPO as IDTRANS, a.KODEPO as KODETRANS, a.TGLTRANS, b.IDSUPPLIER,b.NAMASUPPLIER, b.ALAMAT as ALAMATSUPPLIER,b.TELP as TELPSUPPLIER, a.TGLJATUHTEMPO,a.CATATAN,a.ALASANBATAL, CONCAT(c.KODESYARATBAYAR,' | ',c.NAMASYARATBAYAR,' | ',c.SELISIH,' | ',c.IDSYARATBAYAR) as SYARATBAYAR,a.IDLOKASI
				from TPO a
				left join MSUPPLIER b on a.IDSUPPLIER = b.IDSUPPLIER
				left join MSYARATBAYAR c on a.IDSYARATBAYAR = c.IDSYARATBAYAR
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDPO = $idtrans  and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by TGLTRANS";
		$query         = $this->db->query($sql)->row();
		return $query;
	}
	
    function loadDataDetail($idtrans,$kodetrans){
        $whereTrans = "";
	    if($idtrans != "")
	    {
	        $whereTrans = "and a.IDPO = '{$idtrans}'";
	    }
	    
	    if($kodetrans != "")
	    {
	         $whereTrans = "and a.KODEPO = '{$kodetrans}'";
	    }
		$sql = "select b.IDBARANG as ID,b.KODEBARANG as KODE,b.NAMABARANG as NAMA, a.JML as QTY, a.DISCPERSEN as DISKON, a.HARGA as HARGA,a.PAKAIPPN,a.SATUAN as SATUANBESAR
				from TPODTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereTrans and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by URUTAN";
		$query         = $this->db->query($sql)->result();
		return $query;
	}
	
	function loadDataDetailSisa($idtrans,$kodetrans){
        $whereTrans = "";
	    if($idtrans != "")
	    {
	        $whereTrans = "and a.IDPO = '{$idtrans}'";
	    }
	    
	    if($kodetrans != "")
	    {
	         $whereTrans = "and a.KODEPO = '{$kodetrans}'";
	    }
		$sql = "select b.IDBARANG as ID,b.KODEBARANG as KODE,b.NAMABARANG as NAMA, c.SISA as QTY, a.DISCPERSEN as DISKON, a.HARGA as HARGA,a.PAKAIPPN,a.SATUAN as SATUANBESAR
				from TPODTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				left join TPODTLBRG c on a.IDBARANG = c.IDBARANG and a.IDPO = c.IDPO
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereTrans and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and c.SISA > 0 and C.TUTUP = 0
				order by a.URUTAN";
		$query         = $this->db->query($sql)->result();
		return $query;
	}
    
	function loadDataPembayaran($idtrans){
		$sql = "select TGLPEMBAYARAN,AMOUNT
				from KARTUUANGMUKA
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDTRANSREFERENSI = {$idtrans} and JENISTRANSAKSI = 'BELI'
				order by URUTAN";
		$query = $this->db->query($sql)->result();
		return $query;
	}
	
	function informasiPR($idtrans,$idbarang){
		$sql = "select a.KODEPO,a.JML,a.SATUAN,
				a1.TGLTRANS,a1.USERENTRY,b.KODESUPPLIER,b.NAMASUPPLIER
				from TPODTL a
				inner join TPO a1 on a.IDPO = a1.IDPO
				inner join MSUPPLIER b on a1.IDSUPPLIER = b.IDSUPPLIER
                where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
                        and a.IDPR = '{$idtrans}' 
                        and a.IDBARANG = '{$idbarang}' 
                        and (a1.STATUS = 'S' or a1.STATUS = 'P')";
		$query = $this->db->query($sql)->result();
		return $query;
	}
		
	function simpan($idtrans,$data,$a_detail,$a_detail_pembayaran,$edit){
		// start transaction
		$this->db->trans_begin();
		
		if($edit){
			$this->db->where("IDPO",$idtrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
			$this->db->update('TPO',$data);
		}else{
			$this->db->insert('TPO',$data);
        }
        
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 'Simpan Data Gagal <br>Kesalahan Pada Header Data Transaksi';
		}
		
		if($edit){
			//cancel_tutup_trans("tpo",$idtrans);
			$this->db->where("IDPO",$idtrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
                     ->delete('TPODTL');
            $this->db->where("IDPO",$idtrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					 ->delete('TPODTLBRG');
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
			
			if ($item->jml > 0) {			
				//cari satuan utama dan konversi dulu
				//$result = get_konversi_satuanutama($item->idbarang,$item->satuan);
			
				if($item->pakaippn == "TIDAK")$pakaippn = 0;
				else if($item->pakaippn == "EXCL")$pakaippn = 1;
				else if($item->pakaippn == "INCL")$pakaippn = 2;
				
				$data_values = array (
					'IDPO'         => $idtrans,
					'KODEPO'       => $data['KODEPO'],
                    'IDPERUSAHAAN' => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
					'IDBARANG'     => $item->idbarang,
					'URUTAN'       => $i,
					'JML'          => $item->jml,
					'SATUAN'       => $item->satuan,
					'SATUANUTAMA'  => $result->SATUANUTAMA??'',
					'KONVERSI'     => $result->KONVERSI??0,
					'IDCURRENCY'   => 1,
					'HARGA'        => $item->harga,
					'NILAIKURS'    => $item->nilaikurs,
					'HARGAKURS'    => $item->hargakurs,
					'DISCPERSEN'   => $item->discpersen,
					'DISC'         => $item->disc,
					'DISCKURS'     => $item->disckurs,
					'SUBTOTAL'     => $item->subtotal,
					'SUBTOTALKURS' => $item->subtotalkurs,
					'PAKAIPPN'     => $pakaippn,
					'PPNPERSEN'    => $item->ppnpersen,
					'PPNRP'        => $item->ppnrp,
					'PPH22PERSEN'  => $item->pph22persen,
					'PPH22RP'      => $item->pph22rp,
					'TUTUP'        => 0					
				);
				$this->db->insert('TPODTL',$data_values);
				if ($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					return 'Simpan Data Gagal <br>Kesalahan Pada Detail Data Transaksi';
					
				}
                
                $data_brg = array(
                    'IDPO'         => $idtrans,
                    'KODEPO'       => $data['KODEPO'],
                    'IDPERUSAHAAN' => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
                    'IDBARANG'     => $item->idbarang,
                    'URUTAN'       => $i,
                    'JML'          => $item->jml,
                    'TERPENUHI'    => 0,
                    'SISA'         => $item->jml,
                    'SATUAN'       => $item->satuan,
                    'SATUANUTAMA'  => $result->SATUANUTAMA??'',
                    'KONVERSI'     => $result->KONVERSI??0,
                    'TUTUP'        => 0
                );

                $this->db->insert('TPODTLBRG',$data_brg);

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
		$kodetrans = $this->db->select('KODEPO')
						->where('IDPO',$idtrans)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('TPO')->row()->KODEPO;
		$approve = 0;
		$exe = hapus_kartu_stok($kodetrans,$approve);	
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		//HAPUS KARTUSTOK//
		
		$data = array (
			'TGLBATAL'    => date('Y.m.d'),
			'JAMBATAL'    => date('H:i:s'),
			'USERBATAL'   => $_SESSION[NAMAPROGRAM]['USERID'],
			'ALASANBATAL' => $alasan,
			'STATUS'      => 'D',
		);
		$this->db->where("IDPO",$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
		$this->db->update('TPO',$data);
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
		
		/*
		//HAPUS KARTUSTOK//
		$kodetrans = $this->db->select('KODEPO')
						->where('IDPO',$idtrans)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('TPO')->row()->KODEPO;
		$approve = 0;
		$exe = hapus_kartu_stok($kodetrans,$approve);	
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		
		//INSERT KARTUSTOK//
		$sql = "select b.BADANUSAHA,b.NAMASUPPLIER
					from TPO a
					left join MSUPPLIER b on a.IDSUPPLIER = b.IDSUPPLIER
					where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDPO = {$idtrans}";
		$query = $this->db->query($sql)->row();
		$param = array(
			"id"               => "IDPO",
			"kode"             => "KODEPO",
			"table"            => "TPO",
			"tabledtl"         => "TPODTL",
			"mkpo"             => "M",
			"mk"               => "",
			"mkso"             => "",
			"jenis"            => "PO",
			"keterangan"       => "PO DARI ".$query->BADANUSAHA." ".$query->NAMASUPPLIER,
			"jenisbarang"	   => 'STOK',
			//jika 1 artinya transreferensi yg diisi (mengapprove)
			//jika -1, status diisi 1 karena tidak perlu approve
			"approve"          => -1,
			"idtransreferensi" => ""
		);
		$exe = insert_kartu_stok($idtrans,$param);	
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		//INSERT KARTUSTOK//
		*/
		
		//ubah status jadi S ketika selesai menutup
		$this->db->set('STATUS','S')
				->where('IDPO',$idtrans)
				->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				->update('TPO');
		if($this->db->trans_status()===FALSE){
			$this->db->trans_rollback();
			return $exe;
		}	
		$this->db->trans_commit();
	}
	
	function ubahStatusJadiInput($idtrans){
		$this->db->trans_begin();
		/*
		//HAPUS KARTUSTOK//
		$kodetrans = $this->db->select('KODEPO')
						->where('IDPO',$idtrans)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('TPO')->row()->KODEPO;
		$approve = 0;
		$exe = hapus_kartu_stok($kodetrans,$approve);	
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		//HAPUS KARTUSTOK//
		*/
		//kembalikan status menjadi 'I'
		$data = array (
			'STATUS'=>'I',
		);
		
		$this->db->where("IDPO",$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
		$this->db->update('TPO',$data);
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data Transaksi Tidak Dapat Dibatalkan'; 
		}
		
		$this->db->trans_commit();
		return '';
	}
		
	function getStatusTrans($id){
		return $this->db->where('IDPO',$id)
					->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					->get('TPO')->row()->STATUS;
	}
	
	
	function checkSisa($idpo,$idbarang){
		$sql = "select a.KODEBELI as KODE, a.JML
				from tbelidtl a
				left join tbeli b on a.IDBELi = b.IDBELI
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and b.IDPO = $idpo  and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDBARANG = $idbarang and b.STATUS != 'D'
				order by b.TGLTRANS";
		$query         = $this->db->query($sql)->result();
		return $query;
	}
	
	function checkTutupPO($idpo,$idbarang){
		$sql = "select a.TUTUP, a.ALASANTUTUP
				from tpodtlbrg a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDPO = $idpo  and a.IDBARANG = $idbarang and TUTUP = 1
        ";
		$query         = $this->db->query($sql)->row();
		return $query;
	}
	
	function checkPOBelumTutup($idbarang){
		$sql = "select a.IDPO, a.IDBARANG, a.KODEPO, b.TGLTRANS, CAST(a.SISA AS SIGNED) as SISA,b.CATATAN
				from TPODTLBRG a
				inner join TPO b on a.IDPO = b.IDPO
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDBARANG = $idbarang and a.SISA != 0 and a.TUTUP = 0 and b.STATUS != 'D'
				order by b.TGLTRANS DESC";
		$query         = $this->db->query($sql)->result();
		return $query;
	}
	
	function tutupPO($data){
		$this->db->trans_begin();
		/*
		//HAPUS KARTUSTOK//
		$kodetrans = $this->db->select('KODEPO')
						->where('IDPO',$idtrans)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('TPO')->row()->KODEPO;
		$approve = 0;
		$exe = hapus_kartu_stok($kodetrans,$approve);	
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		//HAPUS KARTUSTOK//
		*/
		//kembalikan status menjadi 'I'
		foreach($data as $item)
		{
    		$data = array (
    			'TUTUP'=>'1',
    			'ALASANTUTUP' => $item->ALASAN
    		);
    		
    		$this->db->where("IDPO",$item->IDPO)
    				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
    				 ->where('IDBARANG',$item->IDBARANG);
    		$this->db->update('TPODTLBRG',$data);
    		if ($this->db->trans_status() === FALSE) { 
    			$this->db->trans_rollback();
    			return 'Data Transaksi Tidak Dapat Ditutup'; 
    		}
    		
    		if($item->IDPO != null)
            {
        		//cek all barang tpo done
        		$sql = "select SISA,TUTUP
        			    from TPODTLBRG
        			    where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDPO = ".$item->IDPO;
        		$dataSisa = $this->db->query($sql)->result();
        		
        		$done = true;
        		foreach($dataSisa as $itemSisa){
        		    if($itemSisa->SISA != 0 && $itemSisa->TUTUP == 0)
        		    {
        		         $done = false;
        		    }
        		}
        		
        		if($done)
        		{
        			tutup_all_trans($this->param,$item->IDPO,true);	
        		}
        		else
        		{
        		  //KHUSUS TUTUP PO, STATUSNYA BIARKAN SAJA
        		  //  $this->db->set('STATUS','C')
        				// ->where('IDPO',$item->IDPO)
        				// ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
        				// ->update('TPO');
        		}
            }
		}
		
		$this->db->trans_commit();
		return '';
	}
}
?>