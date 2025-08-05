<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanPenjualan extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$perusahaan = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
		if($perusahaan != ''){
			//load database perusahaan,jadikan default
			//$this->db->close();
			//$this->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		}
	}
	
	public function index()
	{
		$kodeMenu = $this->input->get('kode');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu);
	}
	public function laporan()
	{
		$this->load->library('html_table');
		$kodeMenu = $this->input->get('kode');

		$perusahaan   = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
		$grouplokasi  = $this->input->post('group_lokasi');
		$lokasi       = $this->input->post('txt_lokasi',[]);
		$jenisStatus  = $this->input->post('cbStatus',[]);
		$jenisStatusMarketplace  = $this->input->post('cbStatusMarketplace',[]);
		$tampil       = $this->input->post('data_tampil');
		$filter       = $this->input->post('data_filter');	
		
		//KONVERSI JADI QUERY
		$whereFilter = where_laporan($filter);
		
		//LOKASI
		$whereLokasi = count($lokasi)>0 ? " and (MLOKASI.IDLOKASI='".implode("' or MLOKASI.IDLOKASI='", $lokasi)."')" : '';
		
		//MARKETPLACE
		$lokasiMarketplace = $lokasi;
		$stringLokasiMarketplace = "";
		foreach($lokasi as $itemLokasi)
		{
		    $stringLokasiMarketplace .= $itemLokasi.",";
		}
		$stringLokasiMarketplace = substr($stringLokasiMarketplace, 0, -1);
		
		if(count($lokasi) > 0)
		{
		  //CARI LOKASI MARKETPLACE
          $sqlMarketplace = "SELECT count(IDLOKASI) as ada FROM MLOKASI WHERE GROUPLOKASI like '%MARKETPLACE%' and IDLOKASI in ($stringLokasiMarketplace)";
          $adaOnline = $this->db->query($sqlMarketplace)->row()->ADA > 0 ? true : false;
		}
		
		//PERUSAHAAN
		$wherePerusahaan = "and TPENJUALAN.idperusahaan=$perusahaan";
		
		//TANGGAL
		$tglTrans = explode(" - ",$this->input->post('tglTrans'));
		
		$tgl_aw = ubah_tgl_firebird($tglTrans[0]);
		$tgl_ak = ubah_tgl_firebird($tglTrans[1]);
		
		$whereTanggal = "and TPENJUALAN.tgltrans between '$tgl_aw' and '$tgl_ak'";
		$whereTanggalMarketplace = "and TPENJUALANMARKETPLACE.tgltrans between '$tgl_aw' and '$tgl_ak'";

		//STATUS
		$whereStatus .= count($jenisStatus)>0 ? " and (TPENJUALAN.status='".implode("' or TPENJUALAN.status='", $jenisStatus)."')" : '';
		
		$whereStatusMarketplace = '';
		foreach($jenisStatusMarketplace as $itemStatusMarketplace)
		{
           $parts = explode("|", $itemStatusMarketplace);
           
           if (count($parts) == 2) {
               // Two values: map to two different fields
               $whereStatusMarketplace .= " OR (tpenjualanmarketplace.status = '" . $parts[0] . "' AND tpenjualanmarketplace.statusmarketplace = '" . $parts[1] . "')";
           } else {
               // One or more values: OR conditions on status
               $statuses = explode("|", $itemStatusMarketplace[0]);
               $conditions = array_map(function($status) {
                   return "tpenjualanmarketplace.status = '" . $status . "'";
               }, $statuses);
           
               $whereStatusMarketplace .= " OR (" . implode(" OR ", $conditions) . ")";
           }
		}
		
		if (strpos($tampil,"REGISTER") !== FALSE) {

			$sql = "select 1 as Nomor, mcustomer.namacustomer,mcustomer.kota, tpenjualan.catatan, tpenjualan.STATUS, tpenjualan.tgltrans, tpenjualan.kodepenjualan as kodetrans, tpenjualan.TOTAL, tpenjualan.PPNRP, tpenjualan.GRANDTOTAL, tpenjualan.nofakturpajak, 
			              mbarang.KodeBarang, tpenjualandtl.keterangan as NamaBarang, tpenjualandtl.Jml, tpenjualandtl.Satuan, tpenjualandtl.harga, tpenjualandtl.ppnrp as ppnrpdtl,
						  tpenjualandtl.NilaiKurs, tpenjualandtl.HargaKurs, tpenjualandtl.DiscPersen, tpenjualandtl.Disc, tpenjualandtl.subtotal, tpenjualandtl.subtotalKurs, tpenjualandtl.subtotalKurs AS subtotalkurs2, tpenjualandtl.pakaippn
						  ,tpenjualan.catatancustomer,tpenjualan.potonganrp,tpenjualan.pembayaran,tpenjualan.GRANDTOTALDISKON,tpenjualan.KODETRANSREFERENSI,tpenjualan.JENISTRANSAKSI, '' as statusmarketplace 
					from tpenjualan 
					inner join tpenjualandtl  	on tpenjualan.idpenjualan=tpenjualandtl.idpenjualan
					inner join mbarang  		on tpenjualandtl.idbarang=mbarang.idbarang and mbarang.stok = 1
					left join mcustomer  		on tpenjualan.idcustomer=mcustomer.idcustomer
					left join mlokasi  			on tpenjualan.idlokasi = mlokasi.idlokasi
					where (1=1 $whereFilter) $wherePerusahaan $whereTanggal $whereLokasi $whereStatus 
					";
			
			if($adaOnline)
			{
    			$whereFilterMarketplace =  str_replace("KODEPENJUALAN", "KODEPENJUALANMARKETPLACE",str_replace("TPENJUALAN", "TPENJUALANMARKETPLACE", $whereFilter));
    			
    			$sql .= " UNION ALL 
    			    select 2 as Nomor, CONCAT(tpenjualanmarketplace.marketplace,' - ',tpenjualanmarketplace.username) as namacustomer,tpenjualanmarketplace.kota,tpenjualanmarketplace.CATATANPENJUAL as catatan, tpenjualanmarketplace.STATUS, tpenjualanmarketplace.tgltrans, tpenjualanmarketplace.kodepenjualanmarketplace as kodetrans, tpenjualanmarketplace.TOTALHARGA as TOTAL, 0 as PPNRP, tpenjualanmarketplace.totalharga as GRANDTOTAL, '' as nofakturpajak, 
    			           mbarang.KodeBarang, mbarang.namabarang as NamaBarang, tpenjualanmarketplacedtl.Jml, mbarang.Satuan, tpenjualanmarketplacedtl.harga, 0 as ppnrpdtl,
						  0 as NilaiKurs, tpenjualanmarketplacedtl.harga as HargaKurs, 0 as DiscPersen, 0 as Disc, tpenjualanmarketplacedtl.total as subtotal, tpenjualanmarketplacedtl.total as subtotalKurs, tpenjualanmarketplacedtl.total AS subtotalkurs2, '' as pakaippn
						  ,tpenjualanmarketplace.CATATANPEMBELI as catatancustomer,(tpenjualanmarketplace.TOTALHARGA - tpenjualanmarketplace.totalpendapatanpenjual) as potonganrp,tpenjualanmarketplace.TOTALBAYAR as pembayaran, tpenjualanmarketplace.totalpendapatanpenjual as GRANDTOTALDISKON,'' as KODETRANSREFERENSI,'ONLINE' as JENISTRANSAKSI, tpenjualanmarketplace.statusmarketplace 
    				from tpenjualanmarketplace 
    				INNER JOIN TPENJUALANMARKETPLACEDTL on tpenjualanmarketplace.IDPENJUALANMARKETPLACE = TPENJUALANMARKETPLACEDTL.IDPENJUALANMARKETPLACE
    				INNER JOIN MCUSTOMER ON TPENJUALANMARKETPLACE.MARKETPLACE = MCUSTOMER.NAMACUSTOMER
    				INNER JOIN MBARANG on TPENJUALANMARKETPLACEDTL.idbarang=mbarang.idbarang and mbarang.stok = 1
    				where (1=1 $whereFilterMarketplace) $whereTanggalMarketplace $whereLokasiMarketplace $whereStatusMarketplace";
			}
	
		} else if (strpos($tampil,"RINCIAN") !== FALSE) {

			$sql = "
			select a.tgltrans,a.kodebarang,a.namabarang, SUM(a.jml) as jml 
			from
			(select tpenjualan.tgltrans,mbarang.kodebarang,mbarang.namabarang, SUM(tpenjualandtl.jml) as jml
					from tpenjualan 
					inner join tpenjualandtl  	on tpenjualan.idpenjualan=tpenjualandtl.idpenjualan
					inner join mbarang  		on tpenjualandtl.idbarang=mbarang.idbarang and mbarang.stok = 1
					left join mcustomer  		on tpenjualan.idcustomer=mcustomer.idcustomer
					left join mlokasi  			on tpenjualan.idlokasi = mlokasi.idlokasi
					where (1=1 $whereFilter) $wherePerusahaan $whereTanggal $whereLokasi $whereStatus and tpenjualandtl.KETERANGAN != 'ONGKIR' 
				    group by tpenjualan.tgltrans,mbarang.namabarang
					";
					
			if($adaOnline)
			{
    			$whereFilterMarketplace =  str_replace("KODEPENJUALAN", "KODEPENJUALANMARKETPLACE",str_replace("TPENJUALAN", "TPENJUALANMARKETPLACE", $whereFilter));
    			
    			$sql .= " UNION ALL 
    			    select tpenjualanmarketplace.tgltrans,mbarang.kodebarang,mbarang.namabarang, SUM(TPENJUALANMARKETPLACEDTL.jml) as jml
    				from tpenjualanmarketplace 
    				INNER JOIN TPENJUALANMARKETPLACEDTL on tpenjualanmarketplace.IDPENJUALANMARKETPLACE = TPENJUALANMARKETPLACEDTL.IDPENJUALANMARKETPLACE
    				INNER JOIN MCUSTOMER ON TPENJUALANMARKETPLACE.MARKETPLACE = MCUSTOMER.NAMACUSTOMER
    				INNER JOIN MBARANG on TPENJUALANMARKETPLACEDTL.idbarang=mbarang.idbarang and mbarang.stok = 1
    				where (1=1 $whereFilterMarketplace) $whereTanggalMarketplace $whereLokasiMarketplace $whereStatusMarketplace
    				group by DATE_FORMAT(tpenjualanmarketplace.tgltrans, '%Y-%m-%d'), MBARANG.namabarang ) as a
    				group by a.TGLTRANS, a.namabarang
    				";
			}
			else
			{
			    $sql .= ") as a
			    group by a.TGLTRANS, a.namabarang";
			}
			
			$sqlTotal = "
			select a.kodebarang,a.namabarang, SUM(a.jml) as jml 
			from
			(select mbarang.kodebarang,mbarang.namabarang, SUM(tpenjualandtl.jml) as jml
					from tpenjualan 
					inner join tpenjualandtl  	on tpenjualan.idpenjualan=tpenjualandtl.idpenjualan
					inner join mbarang  		on tpenjualandtl.idbarang=mbarang.idbarang and mbarang.stok = 1
					left join mcustomer  		on tpenjualan.idcustomer=mcustomer.idcustomer
					left join mlokasi  			on tpenjualan.idlokasi = mlokasi.idlokasi
					where (1=1 $whereFilter) $wherePerusahaan $whereTanggal $whereLokasi $whereStatus and tpenjualandtl.KETERANGAN != 'ONGKIR' 
				    group by mbarang.kodebarang
					";
					
			if($adaOnline)
			{
    			$whereFilterMarketplace =  str_replace("KODEPENJUALAN", "KODEPENJUALANMARKETPLACE",str_replace("TPENJUALAN", "TPENJUALANMARKETPLACE", $whereFilter));
    			
    			$sqlTotal .= " UNION ALL 
    			    select mbarang.kodebarang,mbarang.namabarang, SUM(TPENJUALANMARKETPLACEDTL.jml) as jml
    				from tpenjualanmarketplace 
    				INNER JOIN TPENJUALANMARKETPLACEDTL on tpenjualanmarketplace.IDPENJUALANMARKETPLACE = TPENJUALANMARKETPLACEDTL.IDPENJUALANMARKETPLACE
    				INNER JOIN MCUSTOMER ON TPENJUALANMARKETPLACE.MARKETPLACE = MCUSTOMER.NAMACUSTOMER
    				INNER JOIN MBARANG on TPENJUALANMARKETPLACEDTL.idbarang=mbarang.idbarang and mbarang.stok = 1
    				where (1=1 $whereFilterMarketplace) $whereTanggalMarketplace $whereLokasiMarketplace $whereStatusMarketplace
    				group by MBARANG.kodebarang ) as a
    				group by a.kodebarang
    				";
			}
			else
			{
			    $sqlTotal .= ") as a
			    group by a.kodebarang";
			}
	
		} else if (strpos($tampil,"REKAP") !== FALSE) {
			$sql = "select 1 as NOMOR, mcustomer.namacustomer,mcustomer.kota, tpenjualan.CATATAN,tpenjualan.STATUS, tpenjualan.TGLTRANS, tpenjualan.KODEPENJUALAN as KODETRANS,
				tpenjualan.TOTAL, tpenjualan.PPNRP, tpenjualan.GRANDTOTAL,tpenjualan.POTONGANRP,tpenjualan.POTONGANPERSEN,tpenjualan.PEMBAYARAN,tpenjualan.JENISTRANSAKSI 
				,tpenjualan.catatancustomer,tpenjualan.GRANDTOTALDISKON,
				SUM(tpenjualandtl.jml) as QTY, '' as statusmarketplace
				from tpenjualan 
				inner join tpenjualandtl  	on tpenjualan.idpenjualan=tpenjualandtl.idpenjualan
				inner join mbarang  	    on tpenjualandtl.idbarang=mbarang.idbarang and mbarang.stok = 1
				left join mcustomer  		on tpenjualan.idcustomer=mcustomer.idcustomer
				left join mlokasi  			on tpenjualan.idlokasi = mlokasi.idlokasi
				where (1=1 $whereFilter) $wherePerusahaan $whereTanggal $whereLokasi $whereStatus 
				group by 1, tpenjualan.CATATAN, tpenjualan.TGLTRANS, tpenjualan.KODEPENJUALAN ";
			
			if($adaOnline)
			{
    			$whereFilterMarketplace =  str_replace("KODEPENJUALAN", "KODEPENJUALANMARKETPLACE",str_replace("TPENJUALAN", "TPENJUALANMARKETPLACE", $whereFilter));
    			
    			$sql .= " UNION ALL 
    			    select 2 as NOMOR, CONCAT(tpenjualanmarketplace.marketplace,' - ',tpenjualanmarketplace.username) as namacustomer,tpenjualanmarketplace.kota, tpenjualanmarketplace.CATATANPENJUAL as CATATAN,tpenjualanmarketplace.STATUS, tpenjualanmarketplace.TGLTRANS, tpenjualanmarketplace.KODEPENJUALANMARKETPLACE as KODETRANS,
    				tpenjualanmarketplace.TOTALHARGA as TOTAL, 0 as PPNRP, tpenjualanmarketplace.TOTALHARGA as GRANDTOTAL,(tpenjualanmarketplace.TOTALHARGA - tpenjualanmarketplace.totalpendapatanpenjual) as POTONGANRP,0 as POTONGANPERSEN,tpenjualanmarketplace.TOTALBAYAR as PEMBAYARAN,'ONLINE' as JENISTRANSAKSI 
    				,tpenjualanmarketplace.CATATANPEMBELI as catatancustomer,tpenjualanmarketplace.totalpendapatanpenjual as GRANDTOTALDISKON,
    				SUM(tpenjualanmarketplace.TOTALBARANG) as QTY, tpenjualanmarketplace.statusmarketplace 
    				from tpenjualanmarketplace 
    				INNER JOIN TPENJUALANMARKETPLACEDTL on tpenjualanmarketplace.IDPENJUALANMARKETPLACE = TPENJUALANMARKETPLACEDTL.IDPENJUALANMARKETPLACE
    				INNER JOIN MCUSTOMER ON TPENJUALANMARKETPLACE.MARKETPLACE = MCUSTOMER.NAMACUSTOMER
    				INNER JOIN MBARANG on TPENJUALANMARKETPLACEDTL.idbarang=mbarang.idbarang and mbarang.stok = 1
    				where (1=1 $whereFilterMarketplace) $whereTanggalMarketplace $whereLokasiMarketplace $whereStatusMarketplace
    				group by 2, tpenjualanmarketplace.CATATANPENJUAL, tpenjualanmarketplace.TGLTRANS, tpenjualanmarketplace.KODEPENJUALANMARKETPLACE ";
			}
		}

			
		if ($tampil=='REGISTER') {
			$sql = "select * from ($sql) as abc order by  tgltrans, nomor, kodetrans, kodebarang";
			$title = 'Laporan Penjualan - Register';
		} else if ($tampil=='RINCIAN') {
			$sql = "select * from ($sql) as abc order by  tgltrans, kodebarang";
			$title = 'Laporan Penjualan - Harian';
		} else if ($tampil=='REKAP') {
			$sql = "select * from ($sql) as abc order by  tgltrans, nomor, kodetrans";
			$title = 'Laporan Penjualan - Rekap Berdasarkan Faktur';
		} else if ($tampil=='HARIAN') {
			$title = 'Laporan Penjualan - Harian Berdasarkan Barang';
		} 
		 else if ($tampil=='HARIANTHERMAL') {
			$title = 'Laporan Penjualan - Harian';
		} 

		$data['title']       = $title;
		$data['tampil']      = $tampil;
		$data['tgl_aw']      = $tgl_aw;
		$data['tgl_ak']      = $tgl_ak;
		$data['lokasiMarketplace'] = $lokasiMarketplace;
		$data['whereFilter'] = $whereFilter;
		$data['$whereFilterMarketplace'] = $whereFilterMarketplace;
		$data['sql']         = $sql;
		$data['sqlTotal']    = $sqlTotal;
		$data['grouplokasi'] = $grouplokasi;
		$data['whereLokasi'] = $whereLokasi;
		$data['whereTanggal'] = $whereTanggal;
		$data['whereStatus'] = $whereStatus;
		$data['excel']       = $this->input->post('excel');
		$data['filename']    = $this->input->post('file_name');
		$data['LIHATHARGA']  = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];

		$dir = 'reports/v_';
		$this->load->view($dir."report_transaksi_jual_penjualan.php",$data);
		
	}
}
