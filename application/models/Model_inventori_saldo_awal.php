<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_inventori_saldo_awal extends MY_Model{

	public function getAll(){
		$data = $this->db->query("select * from SALDOSTOK");
		return $data->result();
	}
	
	public function laporan($filter){
	}
	
	public function comboGrid()
	{
		/*if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODESALDOSTOK';
		}
		// // $sql = "select a.KODESALDOSTOK, a.IDSALDOSTOK, a.TGLTRANS, a.USERENTRY,a.IDLOKASI, b.KODELOKASI,b.NAMALOKASI
				from SALDOSTOK a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where (a.KODESALDOSTOK like ?) and a.CLOSING = 0 and (a.STATUS = 'S' or a.STATUS = 'P') and a.IDLOKASI = ? 
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%',$pagination['lokasi']));
		*/
		
		$sql = "select distinct a.KODESALDOSTOK as VALUE, concat(a.TGLTRANS,' - ',a.KODESALDOSTOK) as TEXT,  a.TGLTRANS, a.USERENTRY,a.IDLOKASI, b.KODELOKASI,b.NAMALOKASI
				from SALDOSTOK a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				and a.kodesaldostok not like 'CLS%' and a.CATATAN <> 'SALDO DARI HITUNG HPP'
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				order by a.TGLTRANS DESC, a.KODESALDOSTOK DESC";
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
				from SALDOSTOKDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where (b.KODEBARANG like ? or b.NAMABARANG like ?) and a.IDSALDOSTOK = ? and a.TUTUP = 0
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%',$idsaldostok));

		$data['rows'] = $query->result();
		return $data;
	}
	
	public function dataGrid($pagination, $filter)
	{	
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODESALDOSTOK';
		}

		$data = [];		
		$sql = "select a.IDSALDOSTOK,a.TGLTRANS,a.KODESALDOSTOK,a.GRANDTOTAL,a.CATATAN,d.USERNAME as USERINPUT, a.TGLENTRY as TGLINPUT,e.USERNAME as USERBATAL, a.TGLBATAL, a.STATUS,a.ALASANBATAL
				from SALDOSTOK a
				left join MUSER d on a.USERENTRY = d.USERID
				left join MUSER e on a.USERBATAL = e.USERID
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				{$filter['sql']}
				order by a.TGLTRANS DESC, a.KODESALDOSTOK DESC";
		$q = $this->db->query($sql, $filter['param']);
		$data["rows"]  = $q->result();
		$data["total"] = count($data["rows"]);
		
		return $data;
	}
	
	function loadData($idtrans){
		$sql = "select a.*,b.KODEBARANG, b.NAMABARANG
				from SALDOSTOKDTL a
				inner join SALDOSTOK a1 on a.IDSALDOSTOK = a1.IDSALDOSTOK
				inner join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDSALDOSTOK = {$idtrans}
				order by a.URUTAN";
		$query = $this->db->query($sql)->result();
		return $query;
	}
	function loadDataHeader($idtrans){
		$sql = "select a.IDSALDOSTOK as IDTRANS, a.KODESALDOSTOK as KODETRANS, a.TGLTRANS, b.IDLOKASI,b.KODELOKASI,b.NAMALOKASI,a.CATATAN
				from SALDOSTOK a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDSALDOSTOK = $idtrans  and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by TGLTRANS";
		$query         = $this->db->query($sql)->row();
		return $query;
	}
	function loadDataDetail($idtrans,$kodetrans){
		$whereTrans = "";
	    if($idtrans != "")
	    {
	        $whereTrans = "and a.IDSALDOSTOK = '{$idtrans}'";
	    }
	    
	    if($kodetrans != "")
	    {
	         $whereTrans = "and a.KODESALDOSTOK = '{$kodetrans}'";
	    }
	    
        $sql = "select a.IDBARANG as ID, b.KODEBARANG as KODE,b.NAMABARANG as NAMA, a.JML as QTY,a.HARGA,a.SATUAN as 	SATUANKECIL
				from SALDOSTOKDTL a
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
			$this->db->where("IDSALDOSTOK",$idtrans);
			$this->db->update('SALDOSTOK',$data);
		}else{
			$this->db->insert('SALDOSTOK',$data);
		}
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return 'Simpan Data Gagal <br>Kesalahan Pada Header Data Transaksi';
		}
		
		if($edit){
			//hapus detail terlebih dahulu
			$this->db->where("IDSALDOSTOK",$idtrans)->delete('SALDOSTOKDTL');
		}else{
			//mengambil auto increment
			$idtrans = $this->db->insert_id();
		}
		
		// query detail
		$i = 1;
		foreach ($a_detail as $item) {			
			if ($item->jml > 0) {
				$data_values = array (
					'IDSALDOSTOK'   => $idtrans,
					'IDPERUSAHAAN'  => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
					'IDBARANG'      => $item->idbarang,
					'KODESALDOSTOK' => $data['KODESALDOSTOK'],
					'URUTAN'        => $i,
					'JML'           => $item->jml,
					'JMLKECIL'	 	=> 0,
					'SATUAN'        => $item->satuan,
					'SATUANUTAMA'   => $item->satuan,
					'KONVERSI'      => 0,
					'KONVERSI1'     => 0,
					'KONVERSI2'     => 0,
					'HARGA'         => $item->harga,
					'SUBTOTAL'      => $item->subtotal,
				);
				$i++;
				$this->db->insert('SALDOSTOKDTL',$data_values);
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					return 'Simpan Data Gagal <br>Kesalahan Pada Detail Data Transaksi';					
				}
				
				//$result = get_konversi_satuanutama($item->idbarang,$item->satuan);
				$sql = "select konversi1, konversi2 from mbarang 
						where idbarang = {$item->idbarang}";			
				$query   = $this->db->query($sql);
				$result = $query->row();

				if(!empty($result)){
					//update satuan utama & konversi
					$this->db->set('SATUANUTAMA',$data_values['SATUANUTAMA'])
							 ->set(array('KONVERSI'=>$result->KONVERSI1, 'JMLKECIL'=>$result->KONVERSI2*$item->jml, 'KONVERSI1'=>$result->KONVERSI1, 'KONVERSI2'=>$result->KONVERSI2))
							 ->where('IDBARANG',$item->idbarang)
							 ->where('IDSALDOSTOK',$idtrans)
							 ->update('SALDOSTOKDTL');
				}
			}
			$i++;
		}
		$this->db->trans_commit();
		return $this->ubahStatusJadiSlip($idtrans);
	}
	
	function batal($idtrans,$alasan){
		$this->db->trans_begin();
		$data = array(
			'USERBATAL'   => $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLBATAL'    => date('Y.m.d'),
			'JAMBATAL'    => date('H:i:s'),
			'ALASANBATAL' => $alasan,
			'STATUS'      => 'D',
		);
		$this->db->where("IDSALDOSTOK",$idtrans);
		$this->db->update('SALDOSTOK',$data);
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
				->where('IDSALDOSTOK',$idtrans)
				->update('SALDOSTOK');
				
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
			'TGLBATAL'  => date('Y.m.d'),
			'JAMBATAL'  => date('H:i:s'),
			'STATUS'    => 'I',
		);
		$this->db->where("IDSALDOSTOK",$idtrans);
		$this->db->update('SALDOSTOK',$data);
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data Transaksi Tidak Dapat Dibatalkan'; 
		}
		$this->db->trans_commit();
		return '';
	}
	
	function getStatusTrans($id){
		return $this->db->where('IDSALDOSTOK',$id)->get('SALDOSTOK')->row()->STATUS;
	}
	
	function getTgl($id = 0,$idlokasi){
		$sql = "select max(TGLTRANS) as TGL, IDLOKASI
				from SALDOSTOK
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
					and IDSALDOSTOK <> '{$idlokasi}'
					and IDLOKASI = {$idlokasi}
					and STATUS <> 'D'";
		if($this->db->query($sql)->num_rows()==1){
			$tgl = $this->db->query($sql)->row();
		}else
			$tgl = '';
		return $tgl;
	}
}
?>