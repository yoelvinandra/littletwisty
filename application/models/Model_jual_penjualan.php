<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_jual_penjualan extends MY_Model{
	//param yang berhubungan dengan transaksi
	public $param = array(
		"id"       => "IDSO",
		"kode"     => "KODESO",
		"idbarang" => "IDBARANG",
		"table"    => "TSO",
		"tabledtl" => "TSODTL",
	);
	
	public function getAll(){
		$data = $this->db->query("select * from TPENJUALAN");
		return $data->result();
	}
	
	public function getDataJual($kodetrans){
		$data = $this->db->query("select * from TPENJUALAN where kodepenjualan = '$kodetrans' and idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}");
		return $data->row();
	}
	
	public function getDataJualOnline($kodetransreferensi){
		$data = $this->db->query("select count(IDPENJUALAN) as ADA, IDPENJUALAN, KODEPENJUALAN, STATUS from TPENJUALAN where kodetransreferensi = '$kodetransreferensi' and idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}");
		return $data->row();
	}
	
	public function comboGrid(){
		
		$sql = "select distinct a.KODEPENJUALAN as VALUE, concat(a.TGLTRANS,' - ',a.KODEPENJUALAN) as TEXT, a.USERENTRY,a.IDLOKASI, b.KODELOKASI,b.NAMALOKASI
				from TPENJUALAN a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by a.TGLTRANS, a.KODEPENJUALAN DESC";
		$query = $this->db->query($sql);
		$data['rows'] = $query->result();
		return $data;
	}
	
	public function comboGridTransReferensi(){
		
		$sql = "select distinct a.KODEPENJUALANMARKETPLACE as VALUE, concat(a.TGLTRANS,' - ',a.KODEPENJUALANMARKETPLACE) as TEXT
				from TPENJUALANMARKETPLACE a
				where a.KODEPENJUALANMARKETPLACE is not null and KODEPENJUALANMARKETPLACE <> ''
				order by a.TGLTRANS, a.KODEPENJUALANMARKETPLACE DESC";
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
				from TPENJUALANDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and (b.KODEBARANG like ? or b.NAMABARANG like ?) and a.IDPENJUALAN = ?
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 30";
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%',$pagination['idtrans']));

		$data['rows'] = $query->result();
		return $data;
	}
	
	public function dataGrid($pagination, $filter){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'KODEPENJUALAN';
		}
		
		$data = [];		
		$sql = "select b.IDLOKASI,b.NAMALOKASI, a.IDPENJUALAN, a.TGLTRANS, a.KODEPENJUALAN, a.TOTAL,a.CATATAN,a.CATATANCUSTOMER,ifnull(a.POTONGANPERSEN,0) as POTONGANPERSEN, ifnull(a.POTONGANRP,0) as POTONGANRP ,a.PEMBAYARAN,
					   a.PPNRP as PPN, a.GRANDTOTAL, a.GRANDTOTALDISKON,a.CATATAN, d.USERNAME as USERINPUT, a.TGLENTRY as TGLINPUT, '' as HARI, a.KODETRANSREFERENSI,a.JENISTRANSAKSI,
					   a.JAMENTRY, e.USERNAME as USERBATAL, a.TGLBATAL, a.STATUS, a.ALASANBATAL,f.NAMACUSTOMER,f.KODECUSTOMER,f.ALAMAT as ALAMATCUSTOMER,f.TELP as TELPCUSTOMER,f.MEMBER,f.KONSINYASI,f.DISKONMEMBER
				from TPENJUALAN a
				left join MLOKASI b on a.IDLOKASI=b.IDLOKASI and a.IDPERUSAHAAN=b.IDPERUSAHAAN
				left join MUSER d on a.USERENTRY = d.USERID
				left join MUSER e on a.USERBATAL = e.USERID
				left join MCUSTOMER f on a.IDCUSTOMER=f.IDCUSTOMER and a.IDPERUSAHAAN=f.IDPERUSAHAAN
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				$whereLokasi {$filter['sql']}
				order by a.TGLTRANS DESC, a.KODEPENJUALAN DESC, a.TGLENTRY, a.JAMENTRY";
		
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

	function loadDataDetail($idtrans,$kodetrans,$idcustomer){
	    
	    $whereTrans = "";
	    $dataCustomer = "";
	    $harga = "0";
	    
	    if($idtrans != ""){
	        $whereTrans = "and a.IDPENJUALAN = $idtrans";
	        $harga = "a.HARGA";
	    }
	    else if($kodetrans != ""){
	        $whereTrans = "and a.KODEPENJUALAN = '$kodetrans'";

	        if($idcustomer != "1"){
    	       $sqlCustomer = "SELECT MCUSTOMER.KONSINYASI, MHARGA.* FROM MHARGA 
    	                        INNER JOIN MCUSTOMER ON MHARGA.IDCUSTOMER = MCUSTOMER.IDCUSTOMER
    	                        WHERE MCUSTOMER.IDCUSTOMER = $idcustomer"; 
    	       $dataCustomer = $this->db->query($sqlCustomer)->result();	 
    	    }
    	    else {
	            $harga = "b.HARGAJUAL";
    	    }
	    }
		
		$data = [];		
        $sql = "select b.IDBARANG as ID, b.KODEBARANG as KODE, a.JML as QTY, ".$harga." as HARGA,a.SATUANREF as SATUANKECIL,a.PAKAIPPN,c.PEMBAYARAN,ifnull(a.DISCPERSEN,0) as DISKON,ifnull(a.DISC,0) as DISKONRP
				,d.ALAMAT as ALAMATCUSTOMER,d.TELP as TELPCUSTOMER,d.IDCUSTOMER,d.KODECUSTOMER,d.NAMACUSTOMER,c.CATATANCUSTOMER,a.KETERANGAN as NAMA,c.GRANDTOTAL,c.GRANDTOTALDISKON,c.POTONGANRP,c.POTONGANPERSEN
				from TPENJUALANDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				left join TPENJUALAN c on a.IDPENJUALAN = c.IDPENJUALAN
				left join MCUSTOMER d on c.IDCUSTOMER = d.IDCUSTOMER
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereTrans  and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}  and c.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by URUTAN";

		$query         = $this->db->query($sql)->result();
		
		if($kodetrans != "" && $idcustomer != "1")
		{
    		foreach($query as $rs)
    		{
    		    foreach($dataCustomer as $rsCustomer)
        		{
        		    if($rs->ID == $rsCustomer->IDBARANG)
        		    {
            		    if($rsCustomer->KONSINYASI == 1)
            		    {
            		        $rs->HARGA = $rsCustomer->HARGAKONSINYASI;
            		    }
            		    else
            		    {
            		        $rs->HARGA = $rsCustomer->HARGACORET;
            		    }
        		    }
        		}
    		}
		}
		
		return $query;
	}
	
	function loadDataRekap($kodetrans){
		$sql = "select b.IDBARANG, b.KODEBARANG, b.NAMABARANG,a.TUTUP,
				a.JML, a.SATUAN
				from TPENJUALANDTL a
				inner join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDPENJUALAN = '{$kodetrans}'  and b.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				order by a.URUTAN";
		$query = $this->db->query($sql)->result();
		return $query;
	}
	
	function informasiRealisasiPenjualan($idtrans,$idbarang){
		$sql = "select a.KODEPENJUALAN,a.JML,a.SATUAN,a1.TGLTRANS,a1.USERENTRY
				from TPENJUALANDTL a
				inner join TPENJUALAN a1 on a.IDPENJUALAN = a1.IDPENJUALAN
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDBBK = '{$idtrans}' and a.IDBARANG = '{$idbarang}' and (a1.STATUS = 'S' or a1.STATUS = 'P')";
		$query = $this->db->query($sql)->result();
		return $query;
	}
		
	function simpan($idtrans,$data,$a_detail,$edit){
		// start transaction
		$tr = $this->db->trans_begin();
		
		if($edit){
			$this->db->where("IDPENJUALAN",$idtrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
			$this->db->update('TPENJUALAN',$data);
		}else{
			$this->db->insert('TPENJUALAN',$data);
        }
        
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 'Simpan Data Gagal <br>Kesalahan Pada Header Data Transaksi';
		}
		
		if($edit){
			$this->db->where("IDPENJUALAN",$idtrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					 ->delete('TPENJUALANDTL');
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
		
			if(get_saldo_stok($_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],$item->idbarang,$data['IDLOKASI'],$data['TGLTRANS'],$data['KODEPENJUALAN'],$item->jml)->QTY < 0)
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
                    'IDPENJUALAN'   => $idtrans,
                    'KODEPENJUALAN' => $data['KODEPENJUALAN'],
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
				
                $this->db->insert('TPENJUALANDTL',$data_values);
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
		return intval($idtrans);
	}
	
	function batal($idtrans,$alasan){
		$this->db->trans_begin();
		
		//HAPUS KARTUSTOK//
		$kodetrans = $this->db->select('KODEPENJUALAN')
						      ->where('IDPENJUALAN',$idtrans)
						      ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						      ->get('TPENJUALAN')->row()->KODEPENJUALAN;

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
		$this->db->where("IDPENJUALAN",$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
		$this->db->update('TPENJUALAN',$data);
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
		$kodetrans = $this->db->select('KODEPENJUALAN')
						      ->where('IDPENJUALAN',$idtrans)
						      ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						      ->get('TPENJUALAN')->row()->KODEPENJUALAN;

		$exe = $this->db->where("kodetrans",$kodetrans)
					 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					 ->delete('KARTUSTOK');
					 
		if($exe != true){
			$this->db->trans_rollback();
			return $exe;
		}
		
        //INSERT KARTUSTOK//
		$sql = "select *,b.NAMACUSTOMER,b.CATATAN AS CATATANCUSTOMER
                from TPENJUALAN a
				left join MCUSTOMER b on b.IDCUSTOMER = a.IDCUSTOMER
				left join TPENJUALANDTL c on c.IDPENJUALAN = a.IDPENJUALAN
				inner join MBARANG d on d.IDBARANG = c.IDBARANG and d.STOK = 1
                where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
                        and a.IDPENJUALAN = {$idtrans}";
		
        $query = $this->db->query($sql)->result();
		
		foreach($query as $row)
		{
			$param = array(
				"IDPERUSAHAAN" => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
				"IDLOKASI" => $row->IDLOKASI,
				"MODUL" => 'PENJUALAN',
				"IDTRANS" => $row->IDPENJUALAN,
				"KODETRANS" => $row->KODEPENJUALAN,
				"IDBARANG" => $row->IDBARANG,
				"KONVERSI1" => 1,
				"KONVERSI2" => 1,
				"TGLTRANS" => $row->TGLTRANS,
				"JENISTRANS" => 'JUAL',
				"KETERANGAN" => 'PENJUALAN KE '.$row->NAMACUSTOMER.' ('.$row->CATATANCUSTOMER.')',
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
				 ->where('IDPENJUALAN',$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				 ->update('TPENJUALAN');
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
		$this->db->where("IDPENJUALAN",$idtrans)
				 ->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
		$this->db->update('TPENJUALAN',$data);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return 'Data Transaksi Tidak Dapat Dibatalkan'; 
		}
		
		$sql = "select a.IDBBK,a.KODEPENJUALAN
				from TPENJUALANDTL a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDPENJUALAN = {$idtrans}";
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
		$kodetrans = $this->db->select('KODEPENJUALAN')
						->where('IDPENJUALAN',$idtrans)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('TPENJUALAN')->row()->KODEPENJUALAN;
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

	function cek_valid_fakturpajak($nofakturpajak, $idpenjualan = 0){
		$sql = "select IDPENJUALAN, KODEPENJUALAN, NOFAKTURPAJAK 
				from TPENJUALAN a
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
						and IDPENJUALAN <> {$idpenjualan}
						and NOFAKTURPAJAK = '{$nofakturpajak}'";
		$query = $this->db->query($sql)->row();
		if(isset($query)){
			return 'No Faktur Pajak ('.$query->NOFAKTURPAJAK.') Sudah Digunakan Oleh Transaksi ('.$query->KODEPENJUALAN.'), Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}

	function getStatusTrans($id){
		return $this->db->where('IDPENJUALAN',$id)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('TPENJUALAN')->row()->STATUS;
	}
	
	function dashboardGrandTotal($periode,$tglawal,$tglakhir,$customer = 0, $lokasi = ""){
	    $whereCustomer = " and a.idcustomer not in (select idcustomer from mcustomer where kodecustomer in (SELECT GROUP_CONCAT(DISTINCT CONCAT('X',marketplace)) AS data FROM TPENJUALANMARKETPLACE))";
	    if($customer != 0)
	    {
	        $whereCustomer .= " and a.idcustomer = $customer";
	    }
	    
	    $data = [];
	    if($periode == "1")
	    {
	        array_push($data,array(
	            'label'    => $tglawal,
	            'tglawal'  => $tglawal,
	            'tglakhir' => $tglakhir
	        ));
	    }
	    if($periode == "2")
	    {
	        array_push($data,array(
	            'label'    => $tglawal,
	            'tglawal'  => $tglawal,
	            'tglakhir' => $tglawal
	        ));
	        
	        array_push($data,array(
	            'label'    => $tglakhir,
	            'tglawal'  => $tglakhir,
	            'tglakhir' => $tglakhir
	        ));
	        
	    }
	    if($periode == "3")
	    {
	        array_push($data,array(
	            'label'    => $tglawal,
	            'tglawal'  => $tglawal,
	            'tglakhir' => $tglawal
	        ));
	        
	        $sqlRange = "SELECT DATEDIFF('".$tglakhir."', '".$tglawal."') AS DATERANGE";
	        $dateRange = $this->db->query($sqlRange)->row()->DATERANGE;
	        
	        $sqlDate = "SELECT DATE('".$tglawal."') + INTERVAL a DAY AS date
                FROM (SELECT @rownum := @rownum + 1 AS a
                      FROM information_schema.columns, (SELECT @rownum := 0) AS r
                      LIMIT $dateRange ) AS x";
            $dataDate = $this->db->query($sqlDate)->result();
            
            foreach($dataDate as $itemDate)
            {
                array_push($data,array(
    	            'label'    => $itemDate->DATE,
    	            'tglawal'  => $itemDate->DATE,
    	            'tglakhir' => $itemDate->DATE
    	        ));
            }
	        
	    }
	    if($periode == "4")
	    {
	        $yearAkhir = explode("-",$tglakhir)[0];
	        $monthAkhir = explode("-",$tglakhir)[1];
	        $dateAkhir = explode("-",$tglakhir)[2];
	        
	        for($x = 1 ; $x <= $dateAkhir; $x++)
	        {
	            if($x < 10)
	            {
	                $x = '0'.$x;    
	            }
	            
	            array_push($data,array(
    	            'label'    => $x,
    	            'tglawal'  => $yearAkhir.'-'.$monthAkhir.'-'.$x,
    	            'tglakhir' => $yearAkhir.'-'.$monthAkhir.'-'.$x,
    	        ));
	        }
	    }
	    if($periode == "5")
	    {
	        $yearAkhir = explode("-",$tglakhir)[0];
	        $monthAkhir = explode("-",$tglakhir)[1];
	        $dateAkhir = explode("-",$tglakhir)[2];
	        $arrMonth = ["","Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
	        
	        for($x = 1 ; $x < $monthAkhir; $x++)
	        {
	            $sqlDate = "select last_day('".$yearAkhir."-".$x."-01') as LASTDAY";
	            $lastDay = $this->db->query($sqlDate)->row()->LASTDAY;
	            array_push($data,array(
    	            'label'    => $arrMonth[$x],
    	            'tglawal'  => $yearAkhir."-".explode("-",$lastDay)[1]."-01",
    	            'tglakhir' => $lastDay
    	        ));
	        }
	        
	        array_push($data,array(
    	        'label'    => $arrMonth[$x],
    	        'tglawal'  => $yearAkhir."-".$monthAkhir."-01",
    	        'tglakhir' => $tglakhir
    	    ));
	    }
	    else if($periode == "99")
	    {
	        array_push($data,array(
	            'label'    => $tglawal,
	            'tglawal'  => $tglawal,
	            'tglakhir' => $tglawal
	        ));
	        
	        $sqlRange = "SELECT DATEDIFF('".$tglakhir."', '".$tglawal."') AS DATERANGE";
	        $dateRange = $this->db->query($sqlRange)->row()->DATERANGE;

	        $sqlDate = "SELECT DATE('".$tglawal."') + INTERVAL a DAY AS date
                FROM (SELECT @rownum := @rownum + 1 AS a
                      FROM information_schema.columns, (SELECT @rownum := 0) AS r
                      LIMIT $dateRange ) AS x";
            $dataDate = $this->db->query($sqlDate)->result();
            
            foreach($dataDate as $itemDate)
            {
                array_push($data,array(
    	            'label'    => $itemDate->DATE,
    	            'tglawal'  => $itemDate->DATE,
    	            'tglakhir' => $itemDate->DATE
    	        ));
            }
	        
	    }
	    
	    $sql = "";
	    $index = 0;
	    $adaOnline = true;
	    if($lokasi != "")
	    {
	        $whereLokasi = "and a.IDLOKASI in ($lokasi)";
	        
    	    //CARI LOKASI MARKETPLACE
            $sqlMarketplace = "SELECT count(IDLOKASI) as ada FROM MLOKASI WHERE GROUPLOKASI like '%MARKETPLACE%' and IDLOKASI in ($lokasi)";
            $adaOnline = $this->db->query($sqlMarketplace)->row()->ADA > 0 ? true : false;
	    }
	    
	    foreach($data as $item)
	    {
	        if($index != 0)
	        {
	           $sql .= " union all ";    
	        }
	        
    	    //PENDAPATAN HARI INI
            $sql .= "select a.label, sum(a.GRANDTOTAL) as GRANDTOTAL, sum(a.JMLNOTA) as JMLNOTA, sum(a.TOTALBARANG) as TOTALBARANG
                    from
                    (select '".$item['label']."' as LABEL, ifnull(SUM(a.GRANDTOTALDISKON),0) as GRANDTOTAL,count(a.IDPENJUALAN) as JMLNOTA,
                    IFNULL(SUM((SELECT SUM(B.JML) FROM TPENJUALANDTL b WHERE a.IDPENJUALAN = b.IDPENJUALAN)) ,0) AS TOTALBARANG
                    from tpenjualan a
            		where (1=1)  and a.idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}  $whereLokasi $whereCustomer AND a.tgltrans >= '".$item['tglawal']."' AND a.tgltrans <= '".$item['tglakhir']."' and a.status = 'S'
            ";
            
            if($adaOnline)
            {
                if($customer != 0)
        	    {
        	        $sqlCustomerMarketplace = "SELECT NAMACUSTOMER FROM MCUSTOMER WHERE IDCUSTOMER = $customer";
        	        $customerMarketplace = $this->db->query($sqlCustomerMarketplace)->row()->NAMACUSTOMER;
        	        $whereCustomerMarketplace = " and a.MARKETPLACE = '$customerMarketplace'";
        	    }
        	    
                $sql .= " UNION ALL ";
                $sql .= "select '".$item['label']."' as LABEL, ifnull(SUM(a.TOTALPENDAPATANPENJUAL),0) as GRANDTOTAL,count(a.IDPENJUALANMARKETPLACE) as JMLNOTA,
                        IFNULL(SUM(a.TOTALBARANG),0) AS TOTALBARANG
                        from tpenjualanmarketplace a
                		where (1=1) $whereCustomerMarketplace AND a.tgltrans >= '".$item['tglawal']." 00:00:00' AND a.tgltrans <= '".$item['tglakhir']." 23:59:59' and a.statusmarketplace = 'COMPLETED')
                		as a
                		GROUP BY a.LABEL
                ";
                
                // select '".$item['label']."' as LABEL, ifnull(SUM(a.TOTALPENDAPATANPENJUAL),0) as GRANDTOTAL,count(a.IDPENJUALANMARKETPLACE) as JMLNOTA,
                //         IFNULL(SUM(a.TOTALBARANG - if(a.BARANGSAMPAI = 1,a.TOTALBARANGPENGEMBALIAN,0)),0) AS TOTALBARANG
                //         from tpenjualanmarketplace a
                // 		where (1=1) $whereCustomerMarketplace AND a.tgltrans >= '".$item['tglawal']." 00:00:00' AND a.tgltrans <= '".$item['tglakhir']." 23:59:59' and a.statusmarketplace = 'COMPLETED')
                // 		as a
                // 		GROUP BY a.LABEL
            }
            else
            {
                $sql .= ") as a GROUP BY a.LABEL";
            }
            
            $index++;
	    }
	    
        $data['result'] = $this->db->query($sql)->result();
        
        return $data;
	}
	
	function dashboardItem($periode,$tglawal,$tglakhir,$customer = 0, $lokasi = "", $kategoriWarna = "", $kategoriSize = ""){
	    
	    $whereCustomer = " and a.idcustomer not in (select idcustomer from mcustomer where kodecustomer in (SELECT GROUP_CONCAT(DISTINCT CONCAT('X',marketplace)) AS data FROM TPENJUALANMARKETPLACE))";
	    if($customer != 0)
	    {
	        $whereCustomer .= " and a.idcustomer = $customer";
	    }
	    
	    if($lokasi != "")
	    {
	        $whereLokasi = "and a.IDLOKASI in ($lokasi)";
	    }
	    
	    $sqlBarang = "select * from mbarang where status = 1 and idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and mbarang.kodebarang <> 'XXXXX' group by kategori";
	    $dataBarang = $this->db->query($sqlBarang)->result();
        $sql = "";
        $index = 0;
        
        $adaOnline = true;
	    if($lokasi != "")
	    {
	        $whereLokasi = "and a.IDLOKASI in ($lokasi)";
	        
    	    //CARI LOKASI MARKETPLACE
            $sqlMarketplace = "SELECT count(IDLOKASI) as ada FROM MLOKASI WHERE GROUPLOKASI like '%MARKETPLACE%' and IDLOKASI in ($lokasi)";
            $adaOnline = $this->db->query($sqlMarketplace)->row()->ADA > 0 ? true : false;
	    }
	    
	    foreach($dataBarang as $itemBarang)
	    {
	        if($index != 0)
	        {
	           $sql .= " union all ";    
	        }
	        
    	    //PENDAPATAN HARI INI
            $sql .= "select a.NAMA, sum(a.QTY) as QTY, URUTANTAMPIL
                    from
                    ( select '".explode(" | ",$itemBarang->NAMABARANG)[0]."' as NAMA, IFNULL(SUM(b.jml),0) as QTY,'".$itemBarang->URUTANTAMPIL."' as URUTANTAMPIL
            		from TPENJUALAN a
            		inner join TPENJUALANDTL b on a.IDPENJUALAN = b.IDPENJUALAN
            		inner join MBARANG c on b.IDBARANG = c.IDBARANG
            		where (1=1) $whereLokasi and c.kategori = '".$itemBarang->KATEGORI."' and a.idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereCustomer AND a.tgltrans >= '".$tglawal."' AND a.tgltrans <= '".$tglakhir."' and a.status = 'S'
            ";
            
            if($adaOnline)
            {
                $whereBarangStok = "and c.kategori = '".$itemBarang->KATEGORI."'" ;
                if($customer != 0)
        	    {
        	        $sqlCustomerMarketplace = "SELECT NAMACUSTOMER FROM MCUSTOMER WHERE IDCUSTOMER = $customer";
        	        $customerMarketplace = $this->db->query($sqlCustomerMarketplace)->row()->NAMACUSTOMER;
        	        $whereCustomerMarketplace = " and a.MARKETPLACE = '$customerMarketplace'";
        	    }
        	    
                $sql .= " UNION ALL ";
                $sql .= "select '".explode(" | ",$itemBarang->NAMABARANG)[0]."' as NAMA, 
                        IFNULL(SUM(b.jml),0) as QTY,
                        '".$itemBarang->URUTANTAMPIL."' as URUTANTAMPIL
                        from tpenjualanmarketplace a
                        inner join tpenjualanmarketplacedtl b on a.idpenjualanmarketplace = b.idpenjualanmarketplace
                        inner join mbarang c on b.idbarang = c.idbarang
                		where (1=1) $whereCustomerMarketplace AND a.tgltrans >= '".$tglawal." 00:00:00' AND a.tgltrans <= '".$tglakhir." 23:59:59' and a.statusmarketplace = 'COMPLETED'
                		$whereBarangStok
                		)
                		as a
                		GROUP BY a.NAMA
                ";
            }
            else
            {
                $sql .= ") as a GROUP BY a.NAMA";
            }
            
            $index++;
	    }
	    
	    if(count($dataBarang) > 0)
	    {
            $dataQty = $this->db->query($sql)->result();
	    }
        
        $ada = false;
        
        foreach($dataQty as $itemQty)
        {
            if($itemQty->QTY != 0)
           {
                $ada = true;
           }
        }
        
        if(!$ada)
        {
           $dataQty = []; 
        }
        
        usort($dataQty, function($a, $b) {
            if ($a->QTY == $b->QTY) {
                // If QTY is the same, sort by another property (e.g., name) in ascending order
                return strcmp($a->URUTANTAMPIL, $b->URUTANTAMPIL);  // or $a->name - $b->name for numeric
            }
            return $b->QTY - $a->QTY;  // Descending order
        });
        
        //WARNA
	    $sqlBarang = "select * from mbarang where status = 1 and idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and mbarang.kodebarang <> 'XXXXX' and namabarang like '%".$kategoriWarna."%' group by warna";
	    $dataBarang = $this->db->query($sqlBarang)->result();
        $sql = "";
        $index = 0;
	    foreach($dataBarang as $itemBarang)
	    {
	        if($index != 0)
	        {
	           $sql .= " union all ";    
	        }
	        
    	    //PENDAPATAN HARI INI
            $sql .= "select a.WARNA, sum(a.QTY) as QTY, URUTANTAMPIL
                    from
                    (select '".$itemBarang->WARNA."' as WARNA,ifnull(SUM(b.jml),0) as QTY, '".$itemBarang->URUTANTAMPIL."' as URUTANTAMPIL
            		from TPENJUALAN a
            		inner join TPENJUALANDTL b on a.IDPENJUALAN = b.IDPENJUALAN
            		inner join MBARANG c on b.IDBARANG = c.IDBARANG
            		where (1=1) $whereLokasi and c.kategori = '$itemBarang->KATEGORI' and c.warna = '".$itemBarang->WARNA."' and a.idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereCustomer AND a.tgltrans >= '".$tglawal."' AND a.tgltrans <= '".$tglakhir."' and a.status = 'S'
            ";
            
            if($adaOnline)
            {
               $whereBarangStok = "and (c.kategori = '$itemBarang->KATEGORI' and c.warna = '$itemBarang->WARNA')";
                if($customer != 0)
        	    {
        	        $sqlCustomerMarketplace = "SELECT NAMACUSTOMER FROM MCUSTOMER WHERE IDCUSTOMER = $customer";
        	        $customerMarketplace = $this->db->query($sqlCustomerMarketplace)->row()->NAMACUSTOMER;
        	        $whereCustomerMarketplace = " and a.MARKETPLACE = '$customerMarketplace'";
        	    }
        	    
                $sql .= " UNION ALL ";
                $sql .= "select '".$itemBarang->WARNA."' as WARNA, 
                        IFNULL(SUM(b.jml),0) as QTY ,
                        '".$itemBarang->URUTANTAMPIL."' as URUTANTAMPIL
                        from tpenjualanmarketplace a
                        inner join tpenjualanmarketplacedtl b on a.idpenjualanmarketplace = b.idpenjualanmarketplace
                        inner join mbarang c on b.idbarang = c.idbarang
                		where (1=1) $whereCustomerMarketplace AND a.tgltrans >= '".$tglawal." 00:00:00' AND a.tgltrans <= '".$tglakhir." 23:59:59' and a.statusmarketplace = 'COMPLETED'
                		$whereBarangStok
                		)
                		as a
                		GROUP BY a.WARNA
                ";
            }
            else
            {
                $sql .= ") as a GROUP BY a.WARNA";
            }
            
            $index++;
	    }
	    
	    
	    if(count($dataBarang) > 0)
	    {
            $dataWarna = $this->db->query($sql)->result();
	    }
        $ada = false;
        
        foreach($dataWarna as $itemWarna)
        {
            if($itemWarna->QTY != 0)
           {
                $ada = true;
           }
        }
        
        if(!$ada)
        {
           $dataWarna = []; 
        }
        
        usort($dataWarna, function($a, $b) {
            if ($a->QTY == $b->QTY) {
                // If QTY is the same, sort by another property (e.g., name) in ascending order
                return strcmp($a->URUTANTAMPIL, $b->URUTANTAMPIL);  // or $a->name - $b->name for numeric
            }
            return $b->QTY - $a->QTY;  // Descending order
        });
        
        //UKURAN
	    $sqlBarang = "select * from mbarang where status = 1 and idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and mbarang.kodebarang <> 'XXXXX' and namabarang like '%".$kategoriSize."%' group by size";
	    $dataBarang = $this->db->query($sqlBarang)->result();
        $sql = "";
        $index = 0;
	    foreach($dataBarang as $itemBarang)
	    {
	        if($index != 0)
	        {
	           $sql .= " union all ";    
	        }
	        
    	    //PENDAPATAN HARI INI
            $sql .= "select a.SIZE, sum(a.QTY) as QTY, URUTANTAMPIL
                    from
                    ( select '".$itemBarang->SIZE."' as SIZE,ifnull(SUM(b.jml),0) as QTY,'".$itemBarang->URUTANTAMPIL."' as URUTANTAMPIL
            		from TPENJUALAN a
            		inner join TPENJUALANDTL b on a.IDPENJUALAN = b.IDPENJUALAN
            		inner join MBARANG c on b.IDBARANG = c.IDBARANG
            		where (1=1) $whereLokasi and c.kategori = '$itemBarang->KATEGORI' and c.size = '".$itemBarang->SIZE."' and a.idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereCustomer AND a.tgltrans >= '".$tglawal."' AND a.tgltrans <= '".$tglakhir."' and a.status = 'S'
            ";
            
            if($adaOnline)
            {
               $whereBarangStok = "and (c.kategori = '$itemBarang->KATEGORI' and c.size = '$itemBarang->SIZE')";
                if($customer != 0)
        	    {
        	        $sqlCustomerMarketplace = "SELECT NAMACUSTOMER FROM MCUSTOMER WHERE IDCUSTOMER = $customer";
        	        $customerMarketplace = $this->db->query($sqlCustomerMarketplace)->row()->NAMACUSTOMER;
        	        $whereCustomerMarketplace = " and a.MARKETPLACE = '$customerMarketplace'";
        	    }
        	    
                $sql .= " UNION ALL ";
                $sql .= "select '".$itemBarang->SIZE."' as SIZE, 
                        IFNULL(SUM(b.jml),0) as QTY ,
                        '".$itemBarang->URUTANTAMPIL."' as URUTANTAMPIL
                        from tpenjualanmarketplace a
                        inner join tpenjualanmarketplacedtl b on a.idpenjualanmarketplace = b.idpenjualanmarketplace
                        inner join mbarang c on b.idbarang = c.idbarang
                		where (1=1) $whereCustomerMarketplace AND a.tgltrans >= '".$tglawal." 00:00:00' AND a.tgltrans <= '".$tglakhir." 23:59:59' and a.statusmarketplace = 'COMPLETED'
                		$whereBarangStok
                		)
                		as a
                		GROUP BY a.SIZE
                ";
            }
            else
            {
                $sql .= ") as a GROUP BY a.SIZE";
            }
            
            $index++;
	    }
	    
	    if(count($dataBarang) > 0)
	    {
            $dataUkuran = $this->db->query($sql)->result();
	    }
        
        $ada = false;
        
        foreach($dataUkuran as $itemUkuran)
        {
            if($itemUkuran->QTY != 0)
           {
                $ada = true;
           }
        }
        
        if(!$ada)
        {
           $dataUkuran = []; 
        }
        
        usort($dataUkuran, function($a, $b) {
            if ($a->QTY == $b->QTY) {
                // If QTY is the same, sort by another property (e.g., name) in ascending order
                return strcmp($a->URUTANTAMPIL, $b->URUTANTAMPIL);  // or $a->name - $b->name for numeric
            }
            return $b->QTY - $a->QTY;  // Descending order
        });
        
        $data['result'] = [
          'qty' => $dataQty,  
          'warna' => $dataWarna,  
          'ukuran' => $dataUkuran,  
        ];
        
        return $data;
	}	
	
	function dashboardCustomer($periode,$tglawal,$tglakhir,$barang = "0", $customer = "0"){
	    
	    //CUSTOMER
	    $whereCustomer = " and a.idcustomer not in (select idcustomer from mcustomer where kodecustomer in (SELECT GROUP_CONCAT(DISTINCT CONCAT('X',marketplace)) AS data FROM TPENJUALANMARKETPLACE))";
	    $whereBarang = "";
	    $whereBarangStok = "";
	    $paramBarang = "(SELECT SUM(x.GRANDTOTALDISKON) FROM TPENJUALAN x 
	                        WHERE x.idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} AND x.tgltrans >= '".$tglawal."' AND x.tgltrans <= '".$tglakhir."' and x.status = 'S' and c.IDCUSTOMER = x.IDCUSTOMER
	                        and x.idcustomer not in (select idcustomer from mcustomer where kodecustomer in (SELECT GROUP_CONCAT(DISTINCT CONCAT('X',marketplace)) AS data FROM TPENJUALANMARKETPLACE))
	                    )";
	    $paramBarangMarketplace = "(SELECT SUM(x.TOTALPENDAPATANPENJUAL) FROM TPENJUALANMARKETPLACE x 
	                        WHERE x.tgltrans >= '".$tglawal." 00:00:00' AND x.tgltrans <= '".$tglakhir." 23:59:59' and x.statusmarketplace = 'COMPLETED' and x.MARKETPLACE = a.MARKETPLACE
	                    )";
	    if($barang != "0")
	    {
	        $kodebarang = explode(" | ",$barang)[0];
	        $sqlBarang = "select IDBARANG from mbarang where kodebarang = '{$kodebarang}'";
	        $idbarang = $this->db->query($sqlBarang)->row()->IDBARANG;
	        $whereBarang = " and b.idbarang = $idbarang";
	        $whereBarangStok = "and c.idbarang = '$idbarang'";
	        $paramBarang = "0";
	        $paramBarangMarketplace = "0";
	    }
	    
        
	    $sql = "
	    SELECT a.KODECUSTOMER,a.NAMA,SUM(a.QTY) as QTY,SUM(a.GRANDTOTAL) as GRANDTOTAL
	        from(
                select c.KODECUSTOMER,c.NAMACUSTOMER as NAMA, ifnull(SUM(b.jml),0) as QTY, $paramBarang as GRANDTOTAL
        		from TPENJUALAN a
        		inner join TPENJUALANDTL b on a.IDPENJUALAN = b.IDPENJUALAN
        		inner join MCUSTOMER c on a.IDCUSTOMER = c.IDCUSTOMER
        		where (1=1) $whereBarang and a.idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} AND a.tgltrans >= '".$tglawal."' AND a.tgltrans <= '".$tglakhir."' and a.status = 'S'
        		$whereCustomer
        	    Group by c.KODECUSTOMER
        	    
        	    UNION ALL
        	    
        	    select CONCAT('X',a.MARKETPLACE) as KODECUSTOMER,a.MARKETPLACE as NAMA, 
        	    IFNULL(SUM(b.jml),0) as QTY, 
                $paramBarangMarketplace as GRANDTOTAL
        		from TPENJUALANMARKETPLACE a
        		inner join tpenjualanmarketplacedtl b on a.idpenjualanmarketplace = b.idpenjualanmarketplace
                inner join mbarang c on b.idbarang = c.idbarang
        		where (1=1) AND a.tgltrans >= '".$tglawal." 00:00:00' AND a.tgltrans <= '".$tglakhir." 23:59:59' and a.statusmarketplace = 'COMPLETED'
        		$whereBarangStok
        	    Group by CONCAT('X', A.MARKETPLACE)
        	) as a
        	Group by a.KODECUSTOMER
        	Having QTY > 0
        ";
        $dataCustomer = $this->db->query($sql)->result();
	    
	    usort($dataCustomer, function($a, $b) {
            return $b->QTY - $a->QTY;  // Descending order
        });
	    
        $data['resultCustomer'] = $dataCustomer;
        
        
        //KOTA
        $whereBarang = "";
	    $whereBarangStok = "";
	    $paramBarang = "(SELECT SUM(x.GRANDTOTALDISKON) FROM TPENJUALAN x 
	    	                inner join MCUSTOMER xc on x.IDCUSTOMER = xc.IDCUSTOMER
	                        WHERE x.idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} AND x.tgltrans >= '".$tglawal."' AND x.tgltrans <= '".$tglakhir."' and x.status = 'S' and xc.KOTA = c.KOTA
	                        and x.idcustomer not in (select idcustomer from mcustomer where kodecustomer in (SELECT GROUP_CONCAT(DISTINCT CONCAT('X',marketplace)) AS data FROM TPENJUALANMARKETPLACE))
	                    )";
	    $paramBarangMarketplace  = "(SELECT SUM(x.TOTALPENDAPATANPENJUAL) FROM TPENJUALANMARKETPLACE x 
	                        WHERE x.tgltrans >= '".$tglawal." 00:00:00' AND x.tgltrans <= '".$tglakhir." 23:59:59' and x.statusmarketplace = 'COMPLETED' and x.MARKETPLACE = a.MARKETPLACE and x.KOTA = a.KOTA
	                    )";
	                    
	    if($barang != "0")
	    {
	        $kodebarang = explode(" | ",$barang)[0];
	        $sqlBarang = "select IDBARANG from mbarang where kodebarang = '{$kodebarang}'";
	        $idbarang = $this->db->query($sqlBarang)->row()->IDBARANG;
	        $whereBarang = " and b.idbarang = $idbarang";
	        $whereBarangStok = "and c.idbarang = '$idbarang'";
	        $paramBarangMarketplace = "0";
	    }
	    
	    $whereCustomer = " and a.idcustomer not in (select idcustomer from mcustomer where kodecustomer in (SELECT GROUP_CONCAT(DISTINCT CONCAT('X',marketplace)) AS data FROM TPENJUALANMARKETPLACE))";
	    
	    $whereCustomerMarketplace = "";
	    
	    if($customer != "0")
	    {
	        $whereCustomer .= " and c.IDCUSTOMER = $customer";
	        
	        $sqlCustomerMarketplace = "SELECT NAMACUSTOMER FROM MCUSTOMER WHERE IDCUSTOMER = $customer";
        	$customerMarketplace = $this->db->query($sqlCustomerMarketplace)->row()->NAMACUSTOMER;
        	$whereCustomerMarketplace = " and a.MARKETPLACE = '$customerMarketplace'";
	    }
	    
        
	    $sql = "
	    SELECT a.NAMA,SUM(a.QTY) as QTY,SUM(a.GRANDTOTAL) as GRANDTOTAL
	        from(
                select if(c.NAMACUSTOMER = 'UMUM','UMUM',IFNULL(KOTA,'TANPA KOTA')) as NAMA, ifnull(SUM(b.jml),0) as QTY, $paramBarang as GRANDTOTAL
        		from TPENJUALAN a
        		inner join TPENJUALANDTL b on a.IDPENJUALAN = b.IDPENJUALAN
        		inner join MCUSTOMER c on a.IDCUSTOMER = c.IDCUSTOMER
        		where (1=1) $whereBarang and a.idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} AND a.tgltrans >= '".$tglawal."' AND a.tgltrans <= '".$tglakhir."' and a.status = 'S'
        		$whereCustomer
        	    Group by NAMA
        	    
        	    UNION ALL
        	    
        	    select IFNULL(a.KOTA,'TANPA KOTA') as NAMA, 
        	    IFNULL(SUM(b.jml),0) as QTY, 
                $paramBarangMarketplace as GRANDTOTAL
        		from TPENJUALANMARKETPLACE a
        		inner join tpenjualanmarketplacedtl b on a.idpenjualanmarketplace = b.idpenjualanmarketplace
                inner join mbarang c on b.idbarang = c.idbarang
        		where (1=1) AND a.tgltrans >= '".$tglawal." 00:00:00' AND a.tgltrans <= '".$tglakhir." 23:59:59' and a.statusmarketplace = 'COMPLETED'
        		$whereBarangStok $whereCustomerMarketplace
        	    Group by NAMA
        	) as a
        	Group by a.NAMA
        	Having QTY > 0
        ";
        $dataKota = $this->db->query($sql)->result();
	    
	    usort($dataKota, function($a, $b) {
            return $b->QTY - $a->QTY;  // Descending order
        });
	    
        $data['resultKota'] = $dataKota;
        
        return $data;
	}
	
	function dashboardStok($lokasi = "0",$limitStok = "0"){
	    $whereLokasi = " and mlokasi.idlokasi = $lokasi";
	    
	    $sql = "select mbarang.NAMABARANG, sum(saldostokdtl.JML) as QTY,mbarang.KODEBARANG,mbarang.URUTANTAMPIL
			from SALDOSTOK 
			left join SALDOSTOKDTL  on saldostok.KODESALDOSTOK = saldostokdtl.KODESALDOSTOK 
			left join MBARANG 		 on saldostokdtl.IDBARANG = mbarang.IDBARANG and STOK = 1
			left join MLOKASI  	 on saldostok.IDLOKASI = mlokasi.IDLOKASI
			where (1=1) and saldostok.TGLTRANS <= '".date('Y-m-d')."' and 
				  saldostok.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and
				  mbarang.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and
				  mlokasi.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				  $whereLokasi and saldostok.KODESALDOSTOK not like 'CLS%' and SALDOSTOK.STATUS <> 'D'
			group by SALDOSTOKDTL.idbarang

			union all

			select mbarang.NAMABARANG, sum(if(kartustok.MK = 'M', kartustok.JML, -kartustok.JML)) as QTY, mbarang.KODEBARANG,mbarang.URUTANTAMPIL
			from KARTUSTOK 
			left join MBARANG  	on kartustok.IDBARANG = mbarang.IDBARANG and STOK = 1
			left join MLOKASI  	on kartustok.IDLOKASI = mlokasi.IDLOKASI
			where (1=1) and kartustok.TGLTRANS <= '".date('Y-m-d')."' and 
				  kartustok.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and
				  mbarang.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and
				  mlokasi.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				  $whereLokasi
			group by KARTUSTOK.idbarang
				";
				

		$sql = "select c.NAMABARANG as NAMA,
					   sum(c.QTY) as QTY,c.KODEBARANG,c.URUTANTAMPIL
				from ($sql) as c
				group by c.kodebarang
				Having (1=1 and QTY < $limitStok)
				order by SUBSTRING(c.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(c.URUTANTAMPIL, 2) AS UNSIGNED) ASC, NAMA, c.KODEBARANG";
				
		$dataStok = $this->db->query($sql)->result();
	    
	    usort($dataStok, function($a, $b) {
	        if ($a->QTY == $b->QTY) {
                // If QTY is the same, sort by another property (e.g., name) in ascending order
                return strcmp($a->URUTANTAMPIL, $b->URUTANTAMPIL);  // or $a->name - $b->name for numeric
            }
            return $a->QTY - $b->QTY;  // Ascending order
        });
	    
        $data['result'] = $dataStok;
        
        return $data;
	}
}
?>