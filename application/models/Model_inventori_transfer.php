<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_inventori_transfer extends MY_Model{

	public function getAll(){
		$data = $this->db->query("select * from TTRANSFER");
		return $data->result();
	}
	
	public function laporan($filter){
		return $query->result();
	}
	
	public function comboGrid()
	{
		/*$pagination['sort'] = 'KODETRANSREFERENSI';
		$sql = "select distinct a.KODETRANSFER as KODETRANSREFERENSI, a.IDTRANSFER as IDTRANSREFERENSI,
		               b.KODELOKASI,b.NAMALOKASI, a.IDLOKASITUJUAN as IDLOKASI,a.TGLTRANS,a.TGLKIRIM, x.USERNAME as USERENTRY
				from TTRANSFER a
				left join MLOKASI b on a.IDLOKASITUJUAN = b.IDLOKASI and b.IDPERUSAHAAN=a.IDPERUSAHAAN
				left join MUSER x on a.USERENTRY = x.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and (a.KODETRANSFER like ?) and a.IDLOKASIASAL = ? and a.STATUS = 'S'				
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array(
					'%'.$pagination['q'].'%',
					$pagination['lokasi']
				));
		*/
		
		$sql = "select distinct a.KODETRANSFER as VALUE, concat(a.TGLTRANS,' - ',a.KODETRANSFER) as TEXT, 
		               b.KODELOKASI,b.NAMALOKASI, a.IDLOKASITUJUAN as IDLOKASI,a.TGLTRANS,a.TGLKIRIM, x.USERNAME as USERENTRY
				from TTRANSFER a
				left join MLOKASI b on a.IDLOKASITUJUAN = b.IDLOKASI and b.IDPERUSAHAAN=a.IDPERUSAHAAN
				left join MUSER x on a.USERENTRY = x.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by a.TGLTRANS DESC, a.KODETRANSFER DESC";
		$query = $this->db->query($sql);
		$data['rows'] = $query->result();
		return $data;
	}
	
	public function comboGridBBK($pagination)
	{
		$pagination['sort'] = 'KODETRANSREFERENSI';
		$sql = "select distinct a.KODETRANSFER as KODETRANSREFERENSI, a.IDTRANSFER as IDTRANSREFERENSI,
		               b.KODELOKASI,b.NAMALOKASI, a.IDLOKASITUJUAN as IDLOKASI,a.TGLTRANS,a.TGLKIRIM, x.USERNAME as USERENTRY
				from TTRANSFER a
				left join MLOKASI b on a.IDLOKASITUJUAN = b.IDLOKASI and b.IDPERUSAHAAN=a.IDPERUSAHAAN
				left join MUSER x on a.USERENTRY = x.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and (a.KODETRANSFER like ?) and a.IDLOKASIASAL = ? and a.STATUS = 'S'				
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array(
					'%'.$pagination['q'].'%',
					$pagination['lokasi']
				));

		$data['rows'] = $query->result();
		return $data;
	}
	
	public function comboGridBarang($pagination,$idtransfer)
	{
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'NAMA';
		}

		$sql = "select distinct b.KODEBARANG as KODE, b.NAMABARANG as NAMA,
		        b.IDBARANG as ID, a.SATUAN,a.SATUANUTAMA, a.KONVERSI,a.JML,0 as TERPENUHI,a.JML as SISA,'0' as DISC,0 as PAKAIPPN
				from TTRANSFERDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and (b.KODEBARANG like ? or b.NAMABARANG like ?) and a.TUTUP = 0 and a.IDTRANSFER = ?
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array(
			'%'.$pagination['q'].'%','%'.$pagination['q'].'%',$idtransfer,));
		$data['rows'] = $query->result();
		return $data;
	}
	
	public function dataGrid($pagination, $filter)
	{	
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODETRANSFER';
		}

		$data = [];		
		$sql = "select f.NAMACUSTOMER, a.IDTRANSFER,a.TGLTRANS,a.KODETRANSFER,b.KODELOKASI as KODELOKASIASAL,b.NAMALOKASI as NAMALOKASIASAL,c.KODELOKASI as KODELOKASITUJUAN,c.NAMALOKASI as NAMALOKASITUJUAN,a.CATATAN, d.USERNAME as USERINPUT, a.TGLENTRY as TGLINPUT,e.USERNAME as USERBATAL, a.TGLBATAL, a.STATUS,a.ALASANBATAL
				from TTRANSFER a
				left join MLOKASI b on a.IDLOKASIASAL = b.IDLOKASI
				left join MLOKASI c on a.IDLOKASITUJUAN = c.IDLOKASI
				left join MUSER d on a.USERENTRY = d.USERID
				left join MUSER e on a.USERBATAL = e.USERID
				left join MCUSTOMER f on a.IDCUSTOMER = f.IDCUSTOMER
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				{$filter['sql']}
				order by a.TGLTRANS DESC, a.KODETRANSFER DESC";
		$q = $this->db->query($sql, $filter['param']);
		$data["rows"]  = $q->result();
		$data["total"] = count($data["rows"]);
		return $data;
	}
	
	function loadData($idtrans){
		$sql = "select a.*,b.KODEBARANG,b.NAMABARANG,0 as IDSYARATBAYAR,'' as NAMASYARATBAYAR
				from TTRANSFERDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDTRANSFER = '{$idtrans}'
				order by a.URUTAN";
		$query = $this->db->query($sql)->result();
		return $query;
	}
	
	function loadDataHeader($idtrans){
		$sql = "select a.IDTRANSFER as IDTRANS,a.TGLTRANS,a.KODETRANSFER as KODETRANS,b.IDLOKASI as IDLOKASIASAL,c.IDLOKASI as IDLOKASITUJUAN,a.TGLKIRIM,a.CATATAN,f.NAMACUSTOMER
				from TTRANSFER a
				left join MLOKASI b on a.IDLOKASIASAL = b.IDLOKASI
				left join MLOKASI c on a.IDLOKASITUJUAN = c.IDLOKASI
				left join MCUSTOMER f on a.IDCUSTOMER = f.IDCUSTOMER
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDTRANSFER = $idtrans  and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}  and c.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by TGLTRANS";
		$query         = $this->db->query($sql)->row();
		return $query;
	}
	function loadDataDetail($idtrans,$kodetrans){
		$whereTrans = "";
	    if($idtrans != "")
	    {
	        $whereTrans = "and a.IDTRANSFER = '{$idtrans}'";
	    }
	    
	    if($kodetrans != "")
	    {
	         $whereTrans = "and a.KODETRANSFER = '{$kodetrans}'";
	    }
	    
		$data = [];		
        $sql = "select b.IDBARANG as ID, b.KODEBARANG as KODE,b.NAMABARANG as NAMA, a.JML as QTY,a.TERPENUHI,a.SISA,a.SATUAN as SATUANKECIL
				from TTRANSFERDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereTrans and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by URUTAN";

		$query         = $this->db->query($sql)->result();
		return $query;
	}
	
	function simpan($idtrans,$data,$a_detail,$edit)
	{
		// start transaction
		$tr = $this->db->trans_begin();
		
		if($edit){
			$this->db->where("IDTRANSFER",$idtrans)
					->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
			$this->db->update('TTRANSFER',$data);
		}else{
			$this->db->insert('TTRANSFER',$data);
		}
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return 'Simpan Data Gagal <br>Kesalahan Pada Header Data Transaksi';
		}
		
		if($edit){
			//hapus detail terlebih dahulu
			$this->db->where("IDTRANSFER",$idtrans)
					->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					->delete('TTRANSFERDTL');
		}else{
			//mengambil auto increment
			$idtrans = $this->db->insert_id();
		}
		
		// query detail
		$i = 1;
		
		if($data['IDCUSTOMER'] != 0)
		{
		    $sqlHarga = "select if(MCUSTOMER.KONSINYASI = 1,HARGAKONSINYASI,HARGACORET) as HARGA,MHARGA.IDBARANG from MHARGA 
		                 inner join MCUSTOMER on MCUSTOMER.IDCUSTOMER = MHARGA.IDCUSTOMER
		                 where MHARGA.IDCUSTOMER = {$data['IDCUSTOMER']} and MHARGA.idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
		                 group by MHARGA.IDBARANG";
		    $dataHarga = $this->db->query($sqlHarga)->result();
		}
		    
		foreach ($a_detail as $item) {
	        $harga = 0;
	        foreach($dataHarga as $itemHarga)
	        {
	            if($itemHarga->IDBARANG == $item->idbarang)
	            {
	                $harga = $itemHarga->HARGA;
	            }
	        }
			/*
			if(get_saldo_stok($_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],$item->idbarang,$data['IDLOKASIASAL'],$data['TGLTRANS'],$data['KODETRANSFER'],$item->jml)->QTY < 0)
			{
				$this->db->trans_rollback();
				return 'Stok '.$item->namabarang." minus";
			}
			*/
			//$result = get_konversi_satuanutama($item->idbarang,$item->satuan);
			$data_values = array (
				'IDPERUSAHAAN'  =>$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
				'IDTRANSFER'    =>$idtrans,
				'KODETRANSFER'  =>$data['KODETRANSFER'],
				'IDBARANG'      =>$item->idbarang,
				'URUTAN'        =>$i, 
				'JML'           =>$item->jml,
				'TERPENUHI'     =>0,
				'SISA'          =>$item->jml,
				'HARGA'         =>$harga,
				'SATUAN'        =>$item->satuan,
				'SATUANUTAMA'   =>$result->SATUANUTAMA??'',
				'KONVERSI'      =>$result->KONVERSI??0,
				'TUTUP'      	=>0,
				'CATATAN'       =>$item->catatan
			);
			if ($item->jml > 0) {
				$this->db->insert('TTRANSFERDTL',$data_values);
				if ($this->db->trans_status() === FALSE)
				{
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
		return $this->ubahStatusJadiSlip($idtrans);
	}
	
	function batal($idtrans,$alasan){
		$this->db->trans_begin();
		$data = array (
			'TGLBATAL'=> date('Y.m.d'),
			'JAMBATAL'=>date('H:i:s'),
			'USERBATAL' => $_SESSION[NAMAPROGRAM]['USERID'],
			'ALASANBATAL' => $alasan,
			'STATUS'=>'D',
		);
		$this->db->where("IDTRANSFER",$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
		$this->db->update('TTRANSFER',$data);
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data Transaksi Tidak Dapat Dibatalkan'; 
		}
		
		//HAPUS KARTUSTOK//
		$kodetrans = $this->db->select('KODETRANSFER')
						->where('IDTRANSFER',$idtrans)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('TTRANSFER')->row()->KODETRANSFER;
		$approve = 0;
		$exe = hapus_kartu_stok($kodetrans,$approve);	
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		
		$this->db->trans_commit();
		return '';
	}
	
	function ubahStatusJadiSlip($idtrans){
		$this->db->trans_begin();
		
		//HAPUS KARTUSTOK//
		$kodetrans = $this->db->select('KODETRANSFER')
						->where('IDTRANSFER',$idtrans)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('TTRANSFER')->row()->KODETRANSFER;
		$approve = 0;
		$exe = hapus_kartu_stok($kodetrans,$approve);	
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		
		//INSERT KARTUSTOK//
		$sql = "select a.IDPERUSAHAAN,a.IDLOKASIASAL,a.IDLOKASITUJUAN,
					a.IDTRANSFER as IDTRANS,a.KODETRANSFER as KODETRANS,
					a.TGLTRANS,b.IDBARANG,c.NAMABARANG,b.JML, a.TGLKIRIM,
					d1.NAMALOKASI as LOKASIASAL,d2.NAMALOKASI as LOKASITUJUAN
				from TTRANSFER a
				left join TTRANSFERDTL b on a.IDTRANSFER = b.IDTRANSFER
				left join MBARANG c on b.IDBARANG = c.IDBARANG
				left join MLOKASI d1 on a.IDLOKASIASAL = d1.IDLOKASI
				left join MLOKASI d2 on a.IDLOKASITUJUAN = d2.IDLOKASI
				where a.IDTRANSFER = $idtrans";
		$query = $this->db->query($sql)->result();
		
		foreach($query as $item){
			
			$data = array (
				'IDPERUSAHAAN'      => $item->IDPERUSAHAAN,
				'IDLOKASI'          => $item->IDLOKASIASAL,
				'MODUL'      	    => 'INVENTORI',
				'IDTRANS'      		=> $item->IDTRANS,
				'KODETRANS'  		=> $item->KODETRANS,
				'IDTRANSREFERENSI'  => '',
				'KODETRANSREFERENSI'=> '',
				'IDBARANG'  		=> $item->IDBARANG,
				'KONVERSI1'  		=> 0,
				'KONVERSI2'  		=> 0,
				'TGLTRANS'          => $item->TGLKIRIM,
				'JENISTRANS'        => 'TRANSFER',
				'KETERANGAN'        => "TRANSFER KE ". $item->LOKASITUJUAN,
				'MKPO'              => '', 
				'JMLPO'             => 0,
				'MK'                => 'K', 
				'JML'               => $item->JML,
				'MKSO'              => '', 
				'JMLSO'             => 0,
				'TOTALHARGA'        => 0,
				'STATUS'            => 1,
			);
			$this->db->insert('KARTUSTOK',$data);
			if($this->db->trans_status === false){
				$this->db->trans_rollback();
				return 'Input Data Kartu Stok Gagal';
			}
			
			$data = array (
				'IDPERUSAHAAN'      => $item->IDPERUSAHAAN,
				'IDLOKASI'          => $item->IDLOKASITUJUAN,
				'MODUL'      	    => 'INVENTORI',
				'IDTRANS'      		=> $item->IDTRANS,
				'KODETRANS'  		=> $item->KODETRANS,
				'IDTRANSREFERENSI'  => '',
				'KODETRANSREFERENSI'=> '',
				'IDBARANG'  		=> $item->IDBARANG,
				'KONVERSI1'  		=> 0,
				'KONVERSI2'  		=> 0,
				'TGLTRANS'          => $item->TGLKIRIM,
				'JENISTRANS'        => 'TERIMA TRANSFER',
				'KETERANGAN'        => "TERIMA TRANSFER DARI ". $item->LOKASIASAL,
				'MKPO'              => '', 
				'JMLPO'             => 0,
				'MK'                => 'M', 
				'JML'               => $item->JML,
				'MKSO'              => '', 
				'JMLSO'             => 0,
				'TOTALHARGA'        => 0,
				'STATUS'            => 1,
			);
			
			$this->db->insert('KARTUSTOK',$data);
			if($this->db->trans_status === false){
				$this->db->trans_rollback();
				return 'Input Data Kartu Stok Gagal';
			}
		}
		
		if($exe != ''){
			$this->db->trans_rollback();
			return $exe;
		}
		$this->db->trans_commit();
		
		return '';
	}
	
	function ubahStatusJadiInput($idtrans){
		$this->db->trans_begin();
		
		$data = array (
			'STATUS'=>'I',
		);
		$this->db->where("IDTRANSFER",$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
		$this->db->update('TTRANSFER',$data);
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Proses Error'; 
		}
		$this->db->trans_commit();
		return '';
	}
	
	function getStatusTrans($id){
		return $this->db->where('IDTRANSFER',$id)
					->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					->get('TTRANSFER')->row()->STATUS;
	}
}
?>