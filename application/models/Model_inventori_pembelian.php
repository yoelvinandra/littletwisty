<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_inventori_pembelian extends MY_Model{

	//param yang berhubungan dengan transaksi
	public $param = array(
		"id"       => "IDPO",
		"kode"     => "KODEPO",
		"idbarang" => "IDBARANG",
		"table"    => "TPO",
		"tabledtl" => "TPODTL",
	);
	
	public function getAll(){
		$data = $this->db->query("select * from TBELI");
		return $data->result();
	}
	
	public function laporan($filter){
	}
	
	public function comboGrid(){
		/*if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODEBELI';
		}

		$sql = "select a.KODEBELI, a.IDBELI, a.TGLTRANS, a.USERENTRY,a.IDLOKASI, b.KODELOKASI,b.NAMALOKASI
				from TBELI a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI and b.IDPERUSAHAAN=a.IDPERUSAHAAN				
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and (a.KODEBELI like ?) and a.CLOSING = 0 and a.STATUS in ('S','P') and a.IDLOKASI = ? and a.IDSUPPLIER = ? 
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%',$pagination['lokasi'],$pagination['supplier']));
		*/
		$sql = "select distinct a.KODEBELI as VALUE, concat(a.TGLTRANS,' - ',a.KODEBELI) as TEXT, a.USERENTRY,a.IDLOKASI, b.KODELOKASI,b.NAMALOKASI
				from TBELI a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI and b.IDPERUSAHAAN=a.IDPERUSAHAAN
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by a.TGLTRANS DESC, a.KODEBELI DESC";
		$query = $this->db->query($sql);
		$data['rows'] = $query->result();
		return $data;

	}
	
	public function comboGridBarang($pagination){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'NAMA';
		}

		$sql = "select distinct b.KODEBARANG as KODE, 
				b.NAMABARANG as NAMA, b.IDBARANG as ID,
				a.SATUAN,a.SATUANUTAMA,a.KONVERSI,
				a.HARGA,a.JML,a.NILAIKURS,
				a.DISCPERSEN,a.DISC,a.DISCKURS,
				a.PAKAIPPN,a.PPNPERSEN,
				a.PPH22PERSEN
				from TBELIDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and (b.KODEBARANG like ? or b.NAMABARANG like ?) and a.TUTUP = 0 and a.IDBELI = ?
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%',$pagination['idtrans']));

		$data['rows'] = $query->result();
		return $data;
	}
	
	public function dataGrid($pagination, $filter){
		$data = [];		
        $sql = "select a.IDBELI, a.TGLTRANS, a.KODEBELI, b.NAMASUPPLIER,c.KODEPO, a.TOTAL,a.PPNRP as PPN,a.GRANDTOTAL, a.CATATAN, d.USERNAME as USERINPUT, a.TGLENTRY as TGLINPUT,e.USERNAME as USERBATAL, a.TGLBATAL, a.STATUS,a.ALASANBATAL
				from TBELI a
				left join MSUPPLIER b on a.IDSUPPLIER = b.IDSUPPLIER
				left join TPO c on a.IDPO = c.IDPO
				left join MUSER d on a.USERENTRY = d.USERID
				left join MUSER e on a.USERBATAL = e.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				{$filter['sql']}
				order by TGLTRANS DESC,  KODEBELI DESC";
		//{$filter['sql']}
		$q             = $this->db->query($sql, $filter['param']);
		$data["rows"]  = $q->result();
		$data["total"] = count($data["rows"]);
		return $data;
	}
	
	function loadData($idtrans){
		$sql = "select distinct a.IDBELI,a.KODEBELI,a.IDBBM as IDPO,a.ADANPWP,
                    b.KODEPO,a.IDBARANG,c.KODEBARANG,c.NAMABARANG,
                    a.JML,a.JMLBONUS,a.SATUAN,b.SATUANUTAMA, b.KONVERSI,a.IDCURRENCY, d.SIMBOL,
                    b1.JML as JMLPO, b1.TERPENUHI as TERPENUHIPO, b1.SISA as SISAPO,
                    a.HARGA,a.DISCPERSEN,a.DISC,a.DISCKURS,a.SUBTOTAL,a.NILAIKURS,a.HARGAKURS,a.SUBTOTALKURS,
                    a.URUTAN,a.PAKAIPPN,a.PPNPERSEN,a.PPNRP,a.PPH22PERSEN,a.PPH22RP,
                    e1.NAMAPERKIRAAN as AKUNBIAYA,a.IDAKUNBIAYA,e2.NAMAPERKIFRAAN as AKUNHUTANG,a.IDAKUNHUTANG
				from TBELIDTL a 
				left join TBELI a1 on a.IDBELI = a1.IDBELI
                left join TPODTL b on a.IDBBM = b.IDPO
                left join TPODTLBRG b1 on b1.IDPO = b.IDPO and b1.IDBARANG =  a.IDBARANG
				left join MBARANG c on a.IDBARANG = c.IDBARANG
				left join MCURRENCY d on a.IDCURRENCY = d.IDCURRENCY
				left join MPERKIRAAN e1 on a.IDAKUNBIAYA = e1.IDPERKIRAAN
				left join MPERKIRAAN e2 on a.IDAKUNHUTANG = e2.IDPERKIRAAN
 				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDBELI = {$idtrans}
				order by URUTAN";
		$query = $this->db->query($sql)->result();
		return $query;
	}
	function loadDataHeader($idtrans){
		$sql = "select a.IDBELI as IDTRANS, a.KODEBELI as KODETRANS, a.TGLTRANS, b.IDSUPPLIER,b.NAMASUPPLIER, b.ALAMAT as ALAMATSUPPLIER,b.TELP as TELPSUPPLIER, a.TGLJATUHTEMPO,  CONCAT(c.KODESYARATBAYAR,' | ',c.NAMASYARATBAYAR,' | ',c.SELISIH,' | ',c.IDSYARATBAYAR) as SYARATBAYAR,a.IDLOKASI,concat(a.IDPO,' | ',a.KODEPO) as NOPO,a.CATATAN
				from TBELI a
				left join MSUPPLIER b on a.IDSUPPLIER = b.IDSUPPLIER
				left join MSYARATBAYAR c on a.IDSYARATBAYAR = c.IDSYARATBAYAR
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDBELI = $idtrans  and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by TGLTRANS";
		$query         = $this->db->query($sql)->row();
		return $query;
	}
	
	function loadDataDetail($idtrans,$kodetrans){
	    $whereTrans = "";
	    if($idtrans != "")
	    {
	        $whereTrans = "and a.IDBELI = '{$idtrans}'";
	    }
	    
	    if($kodetrans != "")
	    {
	         $whereTrans = "and a.KODEBELI = '{$kodetrans}'";
	    }
		$sql = "select b.IDBARANG as ID,b.KODEBARANG as KODE,b.NAMABARANG as NAMA, a.JML as QTY, a.DISCPERSEN as DISKON, a.HARGA as HARGA,a.PAKAIPPN,a.SATUAN as SATUANBESAR
				from TBELIDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereTrans and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by URUTAN";
		$query         = $this->db->query($sql)->result();
		return $query;
	}
	
	function informasiPO($idtrans,$idbarang){
		$sql = "select a.KODEBELI,a.JML,a.SATUAN,
				    a1.TGLTRANS,a1.USERENTRY,b.KODESUPPLIER,b.NAMASUPPLIER
				from TBELIDTL a
				inner join TBELI a1 on a.IDBELI = a1.IDBELI
				inner join MSUPPLIER b on a1.IDSUPPLIER = b.IDSUPPLIER
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDPO = '{$idtrans}' and a.IDBARANG = '{$idbarang}' and (a1.STATUS = 'S' or a1.STATUS = 'P')";
		$query = $this->db->query($sql)->result();
		return $query;
	}
		
	function simpan($idtrans,$data,$a_detail,$edit){
		// start transaction
		$this->db->trans_begin();
		if($edit){
			$this->db->where("IDBELI",$idtrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
			$this->db->update('TBELI',$data);
		}else{
			$this->db->insert('TBELI',$data);
        }
        
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 'Simpan Data Gagal <br>Kesalahan Pada Header Data Transaksi';
		}
		
		if($edit){
			//cancel_tutup_trans("tpolain",$idtrans);
			$this->db->where("IDBELI",$idtrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					 ->delete('TBELIDTL');
		}else{
			//mengambil auto increment
			$idtrans = $this->db->insert_id();
		}
		
		// query detail
		$i = 1; $jmlkosong = 0;

		foreach ($a_detail as $item) {
			/*
			if(get_saldo_stok($_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],$item->idbarang,$data['IDLOKASI'],$data['TGLTRANS'],$data['KODEBELI'],$item->jml)->QTY < 0)
			{
				$this->db->trans_rollback();
				return 'Stok '.$item->namabarang." minus";
			}
			*/
            if($item->jml > 0){
                

                $exe = get_tgl_trans('TPO','IDPO',$item->idpo);

                if($exe > $data['TGLTRANS']){
                    //jika tanggal melebihi
                    $this->db->trans_rollback();
                    return 'Kesalahan pada Tanggal Transaksi untuk Kode '.$item->kodepo;
                }
                  
                if($item->pakaippn == "TIDAK")$pakaippn = 0;
                else if($item->pakaippn == "EXCL")$pakaippn = 1;
                else if($item->pakaippn == "INCL")$pakaippn = 2;
                $data_values = array (
                    'IDPERUSAHAAN' => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
                    'IDBELI'       => $idtrans,
                    'KODEBELI'     => $data['KODEBELI'],
                    'IDPO'        =>  $item->idpo,
                    //'ADANPWP'      => $item->adanpwp,
                    'IDBARANG'     => $item->idbarang,
                    'IDCURRENCY'   => 1,
                    'URUTAN'       => $i,
                    'JML'          => $item->jml,
                    //'JMLBONUS'     => $item->jmlbonus,
                    'SATUAN'       => $item->satuan,
                    'SATUANUTAMA'  => $result->SATUANUTAMA??'',
                    'KONVERSI'     => $result->KONVERSI??0,
                    'HARGA'        => $item->harga,
                    'DISCPERSEN'   => $item->discpersen,
                    'DISC'         => $item->disc,
                    'DISCKURS'     => $item->disckurs,
                    'SUBTOTAL'     => $item->subtotal,
                    'NILAIKURS'    => $item->nilaikurs,
                    'HARGAKURS'    => $item->hargakurs,
                    'SUBTOTALKURS' => $item->subtotalkurs,
                    'PAKAIPPN'     => $pakaippn,
                    'PPNPERSEN'    => $item->ppnpersen,
                    'PPNRP'        => $item->ppnrp,
                    'PPH22RP'      => $item->pph22rp,
                    'PPH22PERSEN'  => $item->pph22persen,
                    'TUTUP'        => 0
                );		
			
                $this->db->where('IDPO',$item->idpo)
                        ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
                        ->where('IDBARANG',$item->idbarang)
                        ->insert('TBELIDTL',$data_values);
						
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    return 'Simpan Data Gagal <br>Kesalahan Pada Detail Data Transaksi';
				}

            }else{
				$jmlkosong++;
			}
            $i++;
        }
		// return count($a_detail) . ' ' . $jmlkosong;
        if($jmlkosong == count($a_detail)){
			$this->db->trans_rollback();
			return 'Harus Terdapat Minimal 1 Barang Dalam Transaksi';
		}

		$this->db->trans_commit();
		return $this->ubahStatusJadiSlip($idtrans);
	}
	
	function batal($idtrans,$alasan){
		$this->db->trans_begin();
		
		$sql = "select a.*, a.IDBARANG, b.IDPO, c.JML as JMLPO, c.TERPENUHI as TERPENUHIPO, c.SISA as SISAPO
                from TBELIDTL a
                left join TPO b on b.IDPO = a.IDPO
                left join TPODTLBRG c on c.IDPO = b.IDPO and c.IDBARANG = a.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDBELI = {$idtrans}";
		$query = $this->db->query($sql)->result();
        
        if($query[0]->IDPO != null)
        {
            //tidak 1to1 karena 1 PO bisa banyak DTL ~~~
    		foreach ($query as $item) {
                //update terpenuhi karena pasti 1TO1
                $this->db->set('TERPENUHI',$item->TERPENUHIPO - $item->JML)
                            ->set('SISA',$item->SISAPO + $item->JML)
                            ->where('IDPO',$item->IDPO)
                            ->where('IDBARANG',$item->IDBARANG)
                            ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
                            ->update('TPODTLBRG');
    			if($this->db->trans_status()===FALSE){
    				$this->db->trans_rollback();
    				return 'Update Terpenuhi gagal';
    			}
    		}
    		
    		//cek all barang tpo done
    		$sql = "select TERPENUHI
    			    from TPODTLBRG
    			    where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDPO = ".$query[0]->IDPO;
    		$dataSisa = $this->db->query($sql)->result();
    		
            $adaTerpenuhi = false;
    		foreach($dataSisa as $itemSisa){
    		    if($itemSisa->TERPENUHI != 0)
    		    {
    		         $adaTerpenuhi = true;
    		    }
    		}
    		
    		if(!$adaTerpenuhi)
    		{
    	    	tutup_all_trans($this->param,$query[0]->IDPO,false);	
    		}
    		else
    		{
    		    $this->db->set('STATUS','C')
    				->where('IDPO',$query[0]->IDPO)
    				->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
    				->update('TPO');
    		}
        }
		
		//HAPUS KARTUSTOK//
		$kodetrans = $this->db->select('KODEBELI')
						      ->where('IDBELI',$idtrans)
						      ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						      ->get('TBELI')->row()->KODEBELI;

		$exe = $this->db->where("kodetrans",$kodetrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					 ->delete('KARTUSTOK');
					 
		if($exe != true){
			$this->db->trans_rollback();
			return $exe;
		}
		
		$data = array (
			'TGLBATAL'    => date('Y.m.d'),
			'JAMBATAL'    => date('H:i:s'),
			'USERBATAL'   => $_SESSION[NAMAPROGRAM]['USERID'],
			'ALASANBATAL' => $alasan,
			'STATUS'      => 'D',
		);
		$this->db->where("IDBELI",$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
		$this->db->update('TBELI',$data);
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
		
		
		
		$sql = "select a.*
			    from TBELI a
			    where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDBELI = {$idtrans}";
		$query = $this->db->query($sql)->row();
		
		//HAPUS KARTUSTOK//
		$kodetrans = $this->db->select('KODEBELI')
						      ->where('IDBELI',$idtrans)
						      ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						      ->get('TBELI')->row()->KODEBELI;

		$exe = $this->db->where("kodetrans",$kodetrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					 ->delete('KARTUSTOK');
					 
		if($exe != true){
			$this->db->trans_rollback();
			return $exe;
		}
		
		//cek apakah sudah close atau belum
		$sql = "select STATUS
			    from TPO 
			    where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDPO = {$query->IDPO}";
		$status = $this->db->query($sql)->row()->STATUS;

		if ($status=='I'or $status=='D'){
			$this->db->trans_rollback();
			return 'Transaksi tidak dapat diproses karena transaksi pesanan berstatus "I" atau "D"';
		}
		
		//update terpenuhi di TPODTLBRG
		$sql = "select a.*, a.IDBARANG, b.IDPO, c.JML as JMLPO, c.TERPENUHI as TERPENUHIPO, c.SISA as SISAPO
                from TBELIDTL a
                left join TPO b on b.IDPO = a.IDPO
                left join TPODTLBRG c on c.IDPO = b.IDPO and c.IDBARANG = a.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDBELI = {$idtrans}";
		$query = $this->db->query($sql)->result();
		
		if($query[0]->IDPO != null)
        {
    		// if($this->model_master_config->getConfig($this->param['table'],'RELASI')=='1TO1'){
    			//PASTI 1 TO 1
    			foreach ($query as $item) {
                    $this->db->set('TERPENUHI',$item->TERPENUHIPO + $item->JML + $item->JMLBONUS)
                            ->set('SISA',$item->SISAPO - $item->JML - $item->JMLBONUS)
                            ->where('IDPO',$item->IDPO)
                            ->where('IDBARANG',$item->IDBARANG)
                            ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
                            ->update('TPODTLBRG');
    				if($this->db->trans_status()===FALSE){
    					$this->db->trans_rollback();
    					return 'Update Terpenuhi gagal';
    				}
    			}
    		// }	
    		
    		//cek all barang tpo done
    		$sql = "select SISA,TUTUP
    			    from TPODTLBRG
    			    where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDPO = ".$query[0]->IDPO;
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
    			tutup_all_trans($this->param,$query[0]->IDPO,true);	
    		}
    		else
    		{
    		    $this->db->set('STATUS','C')
    				->where('IDPO',$query[0]->IDPO)
    				->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
    				->update('TPO');
    		}
        }
		
        //INSERT KARTUSTOK//
		$sql = "select *
                from TBELI a
                left join MSUPPLIER b on b.IDSUPPLIER = a.IDSUPPLIER
				left join TBELIDTL c on c.IDBELI = a.IDBELI
                where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
                        and a.IDBELI = {$idtrans}";
						
       $query = $this->db->query($sql)->result();

		foreach($query as $row)
		{
			$param = array(
				"IDPERUSAHAAN" => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
				"IDLOKASI" => $row->IDLOKASI,
				"MODUL" => 'PEMBELIAN',
				"IDTRANS" => $row->IDBELI,
				"KODETRANS" => $row->KODEBELI,
				"IDBARANG" => $row->IDBARANG,
				"KONVERSI1" => 1,
				"KONVERSI2" => 1,
				"TGLTRANS" => $row->TGLTRANS,
				"JENISTRANS" => 'BELI',
				"KETERANGAN" => 'PEMBELIAN DARI '.$row->NAMASUPPLIER,
				"MK" =>  'M',
				"JML" => $row->JML,
				"TOTALHARGA" => $row->SUBTOTAL,
				"STATUS" => 'S',
			);
			$exe =  $this->db->insert('KARTUSTOK',$param);
		
			if($exe != true){
				$this->db->trans_rollback();
				return $exe;
			}
		}
       

		//ubah status jadi S ketika selesai menutup
		$this->db->set('STATUS','S')
				->where('IDBELI',$idtrans)
				->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				->update('TBELI');
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
		$this->db->where("IDBELI",$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
		$this->db->update('TBELI',$data);
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data Transaksi Tidak Dapat Dibatalkan'; 
		}
		
		$sql = "select a.*, a.IDBARANG, b.IDPO, c.JML as JMLPO, c.TERPENUHI as TERPENUHIPO, c.SISA as SISAPO
                from TBELIDTL a
                left join TPO b on b.IDPO = a.IDPO
                left join TPODTLBRG c on c.IDPO = b.IDPO and c.IDBARANG = a.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDBELI = {$idtrans}";
		$query = $this->db->query($sql)->result();
		
		 if($query[0]->IDPO != null)
        {
            //tidak 1to1 karena 1 PO bisa banyak DTL ~~~
    		foreach ($query as $item) {
                //update terpenuhi karena pasti 1TO1
                $this->db->set('TERPENUHI',$item->TERPENUHIPO - $item->JML)
                            ->set('SISA',$item->SISAPO + $item->JML)
                            ->where('IDPO',$item->IDPO)
                            ->where('IDBARANG',$item->IDBARANG)
                            ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
                            ->update('TPODTLBRG');
    			if($this->db->trans_status()===FALSE){
    				$this->db->trans_rollback();
    				return 'Update Terpenuhi gagal';
    			}	
    		}
    		
    		//cek all barang tpo done
    		$sql = "select TERPENUHI
    			    from TPODTLBRG
    			    where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDPO = ".$query[0]->IDPO;
    		$dataSisa = $this->db->query($sql)->result();
    		
            $adaTerpenuhi = false;
    		foreach($dataSisa as $itemSisa){
    		    if($itemSisa->TERPENUHI != 0)
    		    {
    		         $adaTerpenuhi = true;
    		    }
    		}
    		
    		if(!$adaTerpenuhi)
    		{
    	    	tutup_all_trans($this->param,$query[0]->IDPO,false);	
    		}
    		else
    		{
    		    $this->db->set('STATUS','C')
    				->where('IDPO',$query[0]->IDPO)
    				->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
    				->update('TPO');
    		}
        }
    		
		//hapus semua yg berhubungan dengan trans 
		$sql = "select KODEBELI,IDBBM
				from TBELI 
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDBELI = {$idtrans}";
		$query = $this->db->query($sql)->row();
		
		//HAPUS KARTUHUTANG
		$exe = $this->db->where('KODETRANS',$query->KODEBELI)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->delete('KARTUHUTANG');
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 'Delete Kartuhutang Gagal';
		}
		//HAPUS KARTUHUTANG
		
		//HAPUS KARTUSTOK//
		$kodetrans = $query->KODEBELI;
		$approve   = 0;
		$exe       = hapus_kartu_stok($kodetrans,$approve);
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		//HAPUS KARTUSTOK//
		
		$exe = $this->db->where('KODETRANS',$query->KODEBELI)
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
		return $this->db->where('IDBELI',$id)
					->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					->get('TBELI')->row()->STATUS;
	}	
}
?>