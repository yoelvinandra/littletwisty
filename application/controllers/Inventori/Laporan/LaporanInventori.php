<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanInventori extends MY_Controller {
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
		
		//load filter
		$perusahaan     = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
		$lokasi      	= $this->input->post('txt_lokasi',[]);
		$filter      	= $this->input->post('data_filter');
		$tampil      	= $this->input->post('data_tampil');
		
		//KONVERSI JADI QUERY
		$whereFilter = where_laporan($filter);
		
		//LOKASI
		$whereLokasi = count($lokasi)>0 ? " and (d.idlokasi='".implode("' or d.idlokasi='", $lokasi)."')" : '';
		
		//TANGGAL
		$tglTrans = explode(" - ",$this->input->post('tglTrans'));
		
		$tgl_aw = ubah_tgl_firebird($tglTrans[0]);
		$tgl_ak = ubah_tgl_firebird($tglTrans[1]);

		$whereTanggal = "and a.tgltrans between '$tgl_aw' and '$tgl_ak'";
		
		//PERUSAHAAN
		$wherePerusahaan = "and a.idperusahaan=$perusahaan";
	   
		if($tampil == 'kategori')
		{
			$GroupByFilter = 	"MBarang.kategori";
		}
		else if($tampil == 'barang')
		{
			$GroupByFilter = 	"MBarang.namabarang";
		}


			//JGN LUPA DI SUM
			$sql = " select MBarang.KodeBarang, MBarang.Kategori, MBarang.NamaBarang,   MBarang.satuan, MBarang.satuan2, MBarang.satuan3, MBarang.konversi1, MBarang.konversi2,
						   SUM(a1.Jml) as SaldoAwal, SUM(a1.SubTotal) as SaldoAwalRp,
						   0 as Beli, 0 as BeliRp,
						   0 as Jual, 0 as JualRp,
						   0 as TerimaTransfer, 0 as TerimaTransferRp,
						   0 as Transfer, 0 as TransferRp,
						   0 as Adjustment, 0 as AdjustmentRp,
						   0 as ReturBeli, 0 as ReturBeliRp
					from SaldoStok a
					inner join SaldoStokDtl a1 on a.idSaldoStok=a1.idSaldoStok
					inner join MBarang on MBarang.idbarang=a1.idbarang and mbarang.stok = 1
					
					inner join MLokasi d on d.idlokasi=a.idlokasi
					where a.TglTrans = (select max(TglTrans) as TglTrans
										from SaldoStok
										where TglTrans<='$tgl_aw')
						  and (1=1 $whereFilter) $whereLokasi  and a.idperusahaan = $perusahaan
					group by $GroupByFilter
					
					UNION ALL
					
					select MBarang.KodeBarang, MBarang.Kategori, MBarang.NamaBarang,   MBarang.satuan, MBarang.satuan2, MBarang.satuan3, MBarang.konversi1, MBarang.konversi2,
						   SUM(if(a.MK='M', a.Jml, -a.Jml)) as SaldoAwal, SUM(if(a.MK='M', a.TotalHarga, -a.TotalHarga)) as SaldoAwalRp,
						   0 as Beli, 0 as BeliRp,
						   0 as Jual, 0 as JualRp,
						   0 as TerimaTransfer, 0 as TerimaTransferRp,
						   0 as Transfer, 0 as TransferRp,
						   0 as Adjustment, 0 as AdjustmentRp,
						   0 as ReturBeli, 0 as ReturBeliRp
					from KartuStok a
					inner join MBarang on a.idBarang=MBarang.idBarang and mbarang.stok = 1
					
					inner join MLokasi d on d.idlokasi=a.idlokasi
					where a.TglTrans > (select if(max(TglTrans) is null, '01.01.2014',max(TglTrans)) as TglTrans
										from SaldoStok
										where TglTrans<='$tgl_aw') and
						  a.TglTrans < '$tgl_aw'
						  and (1=1 $whereFilter) $whereLokasi  and a.idperusahaan = $perusahaan
					group by $GroupByFilter
					
					UNION ALL
					
					select MBarang.KodeBarang, MBarang.Kategori, MBarang.NamaBarang,   MBarang.satuan, MBarang.satuan2, MBarang.satuan3, MBarang.konversi1, MBarang.konversi2,
						   0 as SaldoAwal, 0 as SaldoAwalRp,
						   SUM(a.jml) as Beli,  SUM(a.totalharga) as BeliRp,
						   0 as Jual, 0 as JualRp,
						   0 as TerimaTransfer, 0 as TerimaTransferRp,
						   0 as Transfer, 0 as TransferRp,
						   0 as Adjustment, 0 as AdjustmentRp,
						   0 as ReturBeli, 0 as ReturBeliRp
					from KartuStok a
					inner join MBarang on a.idBarang=MBarang.idBarang and mbarang.stok = 1
					
					inner join MLokasi d on d.idlokasi=a.idlokasi
					where a.JenisTrans like 'BELI%' and
						  a.TglTrans >='$tgl_aw' and
					      a.TglTrans <='$tgl_ak'
						  and (1=1 $whereFilter) $whereLokasi  and a.idperusahaan = $perusahaan
					group by $GroupByFilter
					
					UNION ALL
					
					select MBarang.KodeBarang, MBarang.Kategori, MBarang.NamaBarang,   MBarang.satuan, MBarang.satuan2, MBarang.satuan3, MBarang.konversi1, MBarang.konversi2,
						   0 as SaldoAwal, 0 as SaldoAwalRp,
						   0 as Beli,  0 as BeliRp,
						    SUM(-a.jml) as Jual,  SUM(-a.totalharga) as JualRp,
						   0 as TerimaTransfer, 0 as TerimaTransferRp,
						   0 as Transfer, 0 as TransferRp,
						   0 as Adjustment, 0 as AdjustmentRp,
						   0 as ReturBeli, 0 as ReturBeliRp
					from KartuStok a
					inner join MBarang on a.idBarang=MBarang.idBarang and mbarang.stok = 1
					
					inner join MLokasi d on d.idlokasi=a.idlokasi
					where a.JenisTrans like 'JUAL%' and
						  a.TglTrans >='$tgl_aw' and
					      a.TglTrans <='$tgl_ak'
						  and (1=1 $whereFilter) $whereLokasi  and a.idperusahaan = $perusahaan
					group by $GroupByFilter					
					
					UNION ALL
					
					select MBarang.KodeBarang, MBarang.Kategori, MBarang.NamaBarang,   MBarang.satuan, MBarang.satuan2, MBarang.satuan3, MBarang.konversi1, MBarang.konversi2,
						   0 as SaldoAwal, 0 as SaldoAwalRp,
						   0 as Beli,  0 as BeliRp,
						   0 as Jual, 0 as JualRp,
						    SUM(a.jml) as TerimaTransfer,  SUM(a.totalharga) as TerimaTransferRp,
						   0 as Transfer, 0 as TransferRp,
						   0 as Adjustment, 0 as AdjustmentRp,
						   0 as ReturBeli, 0 as ReturBeliRp
					from KartuStok a
					inner join MBarang on a.idBarang=MBarang.idBarang and mbarang.stok = 1
					
					inner join MLokasi d on d.idlokasi=a.idlokasi
					where a.JenisTrans like 'TERIMA TRANSFER%' and
						  a.TglTrans >='$tgl_aw' and
					      a.TglTrans <='$tgl_ak'
						  and (1=1 $whereFilter) $whereLokasi  and a.idperusahaan = $perusahaan
					group by $GroupByFilter
					
					UNION ALL
					
					select MBarang.KodeBarang, MBarang.Kategori, MBarang.NamaBarang,   MBarang.satuan, MBarang.satuan2, MBarang.satuan3, MBarang.konversi1, MBarang.konversi2,
						   0 as SaldoAwal, 0 as SaldoAwalRp,
						   0 as Beli,  0 as BeliRp,
						   0 as Jual, 0 as JualRp,
						   0 as TerimaTransfer, 0 as TerimaTransferRp,
						   SUM(-a.jml) as Transfer,  SUM(-a.totalharga) as TransferRp,
						   0 as Adjustment, 0 as AdjustmentRp,
						   0 as ReturBeli, 0 as ReturBeliRp
					from KartuStok a
					inner join MBarang on a.idBarang=MBarang.idBarang and mbarang.stok = 1
					
					inner join MLokasi d on d.idlokasi=a.idlokasi
					where a.JenisTrans like 'TRANSFER%' and
						  a.TglTrans >='$tgl_aw' and
					      a.TglTrans <='$tgl_ak'
						  and (1=1 $whereFilter) $whereLokasi  and a.idperusahaan = $perusahaan
					group by $GroupByFilter		
						 
					UNION ALL
					
					select MBarang.KodeBarang, MBarang.Kategori, MBarang.NamaBarang,   MBarang.satuan, MBarang.satuan2, MBarang.satuan3, MBarang.konversi1, MBarang.konversi2,
						   0 as SaldoAwal, 0 as SaldoAwalRp,
						   0 as Beli,  0 as BeliRp,
						   0 as Jual, 0 as JualRp,
						   0 as TerimaTransfer, 0 as TerimaTransferRp,
						   0 as Transfer,  0 as TransferRp,
						   SUM(if(a.MK='M', a.Jml, -a.Jml)) as Adjustment,  SUM(if(a.MK='M', a.TotalHarga, -a.TotalHarga)) as AdjustmentRp,
						   0 as ReturBeli, 0 as ReturBeliRp
					from KartuStok a
					inner join MBarang on a.idBarang=MBarang.idBarang and mbarang.stok = 1
					
					inner join MLokasi d on d.idlokasi=a.idlokasi
					where a.JenisTrans like 'ADJUSTMENT%' and
						  a.TglTrans >='$tgl_aw' and
					      a.TglTrans <='$tgl_ak'
						  and (1=1 $whereFilter) $whereLokasi  and a.idperusahaan = $perusahaan
					group by $GroupByFilter			
						
					UNION ALL
					
					select MBarang.KodeBarang, MBarang.Kategori, MBarang.NamaBarang,   MBarang.satuan, MBarang.satuan2, MBarang.satuan3, MBarang.konversi1, MBarang.konversi2,
						   0 as SaldoAwal, 0 as SaldoAwalRp,
						   0 as Beli,  0 as BeliRp,
						   0 as Jual, 0 as JualRp,
						    SUM(a.jml) as TerimaTransfer,  SUM(a.totalharga) as TerimaTransferRp,
						   0 as Transfer, 0 as TransferRp,
						   0 as Adjustment, 0 as AdjustmentRp,
						   SUM(-a.jml) as ReturBeli, SUM(-a.totalharga) as ReturBeliRp
					from KartuStok a
					inner join MBarang on a.idBarang=MBarang.idBarang and mbarang.stok = 1
					
					inner join MLokasi d on d.idlokasi=a.idlokasi
					where a.JenisTrans like 'RETUR BELI%' and
						  a.TglTrans >='$tgl_aw' and
					      a.TglTrans <='$tgl_ak'
						  and (1=1 $whereFilter) $whereLokasi  and a.idperusahaan = $perusahaan
					group by $GroupByFilter
			";
	
		if($tampil == 'kategori')
		{			
			//DIJADIKAN SATU BERDASARKAN KATEGORI		
			$sql = "select a.KodeBarang, a.kategori, a.NamaBarang,  a.satuan, a.satuan2, a.satuan3, a.konversi1, a.konversi2,
						  SUM(a.SaldoAwal)		 as SaldoAwal,			SUM(a.SaldoAwalRp)		 as SaldoAwalRp,
						  SUM(a.Beli) 			 as Beli,  				SUM(a.BeliRp) 			 as BeliRp,
						  SUM(a.Jual) 			 as Jual, 				SUM(a.JualRp) 			 as JualRp,
						  SUM(a.TerimaTransfer) as TerimaTransfer, 		SUM(a.TerimaTransferRp)  as TerimaTransferRp,
						  SUM(a.Transfer)  	 	as Transfer,  			SUM(a.TransferRp)  	 	 as TransferRp,
						  SUM(a.Adjustment) 	 as Adjustment, 		SUM(a.AdjustmentRp) 	 as AdjustmentRp,
						  SUM(a.ReturBeli) 	 	as ReturBeli, 			SUM(a.ReturBeliRp) 	 	 as ReturBeliRp
			from ($sql) as a
			group by a.kategori
			order by a.kategori";
		}
		else if($tampil == 'barang')
		{
			//DIJADIKAN SATU BERDASARKAN BARANG
			$sql = "select a.KodeBarang, a.kategori, a.NamaBarang,   a.satuan, a.satuan2, a.satuan3, a.konversi1, a.konversi2,
						  SUM(a.SaldoAwal)		 as SaldoAwal,			SUM(a.SaldoAwalRp)		 as SaldoAwalRp,
						  SUM(a.Beli) 			 as Beli,  				SUM(a.BeliRp) 			 as BeliRp,
						  SUM(a.Jual) 			 as Jual, 				SUM(a.JualRp) 			 as JualRp,
						  SUM(a.TerimaTransfer) as TerimaTransfer, 		SUM(a.TerimaTransferRp)  as TerimaTransferRp,
						  SUM(a.Transfer)  	 	as Transfer,  			SUM(a.TransferRp)  	 	 as TransferRp,
						  SUM(a.Adjustment) 	 as Adjustment, 		SUM(a.AdjustmentRp) 	 as AdjustmentRp,
						  SUM(a.ReturBeli) 	 	as ReturBeli, 			SUM(a.ReturBeliRp) 	 	 as ReturBeliRp
			from ($sql) as a
			group by a.namabarang
			ORDER BY SUBSTRING(a.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(a.URUTANTAMPIL, 2) AS UNSIGNED) ASC,a.kodebarang,a.namabarang";
			
		}

		
		$data['sql']                 = $sql;
		$data['tglAw']               = $tgl_aw;
		$data['tglAk']               = $tgl_ak;
		$data['tgltransaksi']        = $tgltransaksi;
		$data['whereLokasi']         = $whereLokasi;
		$data['tampil']              = $tampil;
		$data['excel']               = $this->input->post('excel');
		$data['filename']            = $this->input->post('file_name');
		$data['LIHATHARGA']          = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];
		
			
				
		$dir = 'reports/v_';
		$this->load->view($dir."report_transaksi_inventori_inventori.php",$data);
	}
}
