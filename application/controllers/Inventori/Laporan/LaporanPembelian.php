<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanPembelian extends MY_Controller {
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
		$data['errorMsg'] = '';
		
		$perusahaan   = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
		$grouplokasi  = $this->input->post('group_lokasi');
		$lokasi       = $this->input->post('txt_lokasi',[]);
		$jenisStatus  = $this->input->post('cbStatus',[]);
		$jenisTrans   = $this->input->post('cbJenis',[]);
		$tampil       = $this->input->post('data_tampil');
		$filter       = $this->input->post('data_filter');	
		
		//BEDAKAN PEMBELIAN DAN RETUR BELI
		$data = json_decode($filter);
		$arrKolom = array();
		$arrOperator = array();
		for($i = 0; $i<count($data);$i++)
		{
			$arrKolom[$i] = $data[$i]->KOLOM;
			$arrOperator[$i] = $data[$i]->OPERATOR;
		}
		
		array_multisort($arrKolom, SORT_ASC, $arrOperator, SORT_ASC, $data);
		for($i = 0; $i<count($data);$i++)
		{
			if($data[$i]->KOLOM != $kolomLama || $data[$i]->OPERATOR != $operatorLama)
			{
				$logika = " AND ";
				$kolomLama = $data[$i]->KOLOM;
				$operatorLama = $data[$i]->OPERATOR;
			}
			else
			{
				$logika = " OR ";
			}
		
			if($data[$i]->KOLOM == "TBELI.KODEBELI")
			{
				if($data[$i]->OPERATOR == "ADALAH")
				{
					$whereKodeBeli.= $logika.$data[$i]->KOLOM." = '".addslashes($data[$i]->NILAI)."'";
				}
				else if($data[$i]->OPERATOR == "TIDAK MENCAKUP")
				{
					$whereKodeBeli.= $logika.$data[$i]->KOLOM." != '".addslashes($data[$i]->NILAI)."'";
				}
				else if($data[$i]->OPERATOR == "BERISI KATA")
				{
					$whereKodeBeli.= $logika.$data[$i]->KOLOM." LIKE '%".addslashes($data[$i]->NILAI)."%'";
				}
				else if($data[$i]->OPERATOR == "TIDAK BERISI KATA")
				{
					$whereKodeBeli.= $logika.$data[$i]->KOLOM." NOT LIKE '%".addslashes($data[$i]->NILAI)."%'";
				}
				else if($data[$i]->OPERATOR == "DIMULAI DENGAN")
				{
					$whereKodeBeli.= $logika.$data[$i]->KOLOM." LIKE '".addslashes($data[$i]->NILAI)."%'";
				}
				else if($data[$i]->OPERATOR == "TIDAK DIMULAI DENGAN")
				{
					$whereKodeBeli.= $logika.$data[$i]->KOLOM." NOT LIKE '".addslashes($data[$i]->NILAI)."%'";
				}
				else if($data[$i]->OPERATOR == "DIAKHIRI DENGAN")
				{
					$whereKodeBeli.= $logika.$data[$i]->KOLOM." LIKE '%".addslashes($data[$i]->NILAI)."'";
				}
				else if($data[$i]->OPERATOR == "TIDAK DIAKHIRI DENGAN")
				{
					$whereKodeBeli.= $logika.$data[$i]->KOLOM." NOT LIKE '%".addslashes($data[$i]->NILAI)."'";
				}
				else if($data[$i]->OPERATOR == "KOSONG")
				{
					$whereKodeBeli.= $logika.$data[$i]->KOLOM." IS NULL OR ".$data[$i]->KOLOM." = '' ";
				}
				else if($data[$i]->OPERATOR == "TIDAK KOSONG")
				{
					$whereKodeBeli.= $logika.$data[$i]->KOLOM." IS NOT NULL OR ".$data[$i]->KOLOM." != '' ";
				}
			}
			else if($data[$i]->KOLOM == "TRETURBELI.KODERETURBELI")
			{
				if($data[$i]->OPERATOR == "ADALAH")
				{
					$whereKodeReturBeli.= $logika.$data[$i]->KOLOM." = '".addslashes($data[$i]->NILAI)."'";
				}
				else if($data[$i]->OPERATOR == "TIDAK MENCAKUP")
				{
					$whereKodeReturBeli.= $logika.$data[$i]->KOLOM." != '".addslashes($data[$i]->NILAI)."'";
				}
				else if($data[$i]->OPERATOR == "BERISI KATA")
				{
					$whereKodeReturBeli.= $logika.$data[$i]->KOLOM." LIKE '%".addslashes($data[$i]->NILAI)."%'";
				}
				else if($data[$i]->OPERATOR == "TIDAK BERISI KATA")
				{
					$whereKodeReturBeli.= $logika.$data[$i]->KOLOM." NOT LIKE '%".addslashes($data[$i]->NILAI)."%'";
				}
				else if($data[$i]->OPERATOR == "DIMULAI DENGAN")
				{
					$whereKodeReturBeli.= $logika.$data[$i]->KOLOM." LIKE '".addslashes($data[$i]->NILAI)."%'";
				}
				else if($data[$i]->OPERATOR == "TIDAK DIMULAI DENGAN")
				{
					$whereKodeReturBeli.= $logika.$data[$i]->KOLOM." NOT LIKE '".addslashes($data[$i]->NILAI)."%'";
				}
				else if($data[$i]->OPERATOR == "DIAKHIRI DENGAN")
				{
					$whereKodeReturBeli.= $logika.$data[$i]->KOLOM." LIKE '%".addslashes($data[$i]->NILAI)."'";
				}
				else if($data[$i]->OPERATOR == "TIDAK DIAKHIRI DENGAN")
				{
					$whereKodeReturBeli.= $logika.$data[$i]->KOLOM." NOT LIKE '%".addslashes($data[$i]->NILAI)."'";
				}
				else if($data[$i]->OPERATOR == "KOSONG")
				{
					$whereKodeReturBeli.= $logika.$data[$i]->KOLOM." IS NULL OR ".$data[$i]->KOLOM." = '' ";
				}
				else if($data[$i]->OPERATOR == "TIDAK KOSONG")
				{
					$whereKodeReturBeli.= $logika.$data[$i]->KOLOM." IS NOT NULL OR ".$data[$i]->KOLOM." != '' ";
				}
			}
			else
			{
				if($data[$i]->TIPEDATA == "STRING" )
				{
					if($data[$i]->OPERATOR == "ADALAH")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." = '".addslashes($data[$i]->NILAI)."'";
					}
					else if($data[$i]->OPERATOR == "TIDAK MENCAKUP")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." != '".addslashes($data[$i]->NILAI)."'";
					}
					else if($data[$i]->OPERATOR == "BERISI KATA")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." LIKE '%".addslashes(str_replace(' ', '%', $data[$i]->NILAI))."%'";
					}
					else if($data[$i]->OPERATOR == "TIDAK BERISI KATA")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." NOT LIKE '%".addslashes($data[$i]->NILAI)."%'";
					}
					else if($data[$i]->OPERATOR == "DIMULAI DENGAN")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." LIKE '".addslashes($data[$i]->NILAI)."%'";
					}
					else if($data[$i]->OPERATOR == "TIDAK DIMULAI DENGAN")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." NOT LIKE '".addslashes($data[$i]->NILAI)."%'";
					}
					else if($data[$i]->OPERATOR == "DIAKHIRI DENGAN")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." LIKE '%".addslashes($data[$i]->NILAI)."'";
					}
					else if($data[$i]->OPERATOR == "TIDAK DIAKHIRI DENGAN")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." NOT LIKE '%".addslashes($data[$i]->NILAI)."'";
					}
					else if($data[$i]->OPERATOR == "KOSONG")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." IS NULL OR ".$data[$i]->KOLOM." = '' ";
					}
					else if($data[$i]->OPERATOR == "TIDAK KOSONG")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." IS NOT NULL OR ".$data[$i]->KOLOM." != '' ";
					}
				}
				else if($data[$i]->TIPEDATA == "NUMBER")
				{		
					if($data[$i]->OPERATOR == "SAMA DENGAN")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." = ".$data[$i]->NILAI."";
					}
					else if($data[$i]->OPERATOR == "TIDAK SAMA DENGAN")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." != ".$data[$i]->NILAI."";
					}
					else if($data[$i]->OPERATOR == "LEBIH BESAR DARI")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." > ".$data[$i]->NILAI."";
					}
					else if($data[$i]->OPERATOR == "LEBIH BESAR SAMA DENGAN")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." >= ".$data[$i]->NILAI."";
					}
					else if($data[$i]->OPERATOR == "LEBIH KECIL DARI")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." < ".$data[$i]->NILAI."";
					}
					else if($data[$i]->OPERATOR == "LEBIH KECIL SAMA DENGAN")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." <= ".$data[$i]->NILAI."";
					}
					else if($data[$i]->OPERATOR == "NOL")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." = 0 ";
					}
					else if($data[$i]->OPERATOR == "TIDAK NOL")
					{
						$whereFilter.= $logika.$data[$i]->KOLOM." != 0 ";
					}
				}
			}
		}

		//LOKASI
		$whereLokasi = count($lokasi)>0 ? " and (MLOKASI.idlokasi='".implode("' or MLOKASI.idlokasi='", $lokasi)."')" : '';
		
		//PERUSAHAAN
		$wherePerusahaanBeli = "and TBELI.idperusahaan=$perusahaan";
		$wherePerusahaanReturBeli = "and TRETURBELI.idperusahaan=$perusahaan";
		
		//TANGGAL
		$tglTrans = explode(" - ",$this->input->post('tglTrans'));
		
		$tgl_aw = ubah_tgl_firebird($tglTrans[0]);
		$tgl_ak = ubah_tgl_firebird($tglTrans[1]);
		
		$whereTanggalBeli = "and TBELI.tgltrans between '$tgl_aw' and '$tgl_ak'";
		$whereTanggalReturBeli = "and TRETURBELI.tgltrans between '$tgl_aw' and '$tgl_ak'";
		
		//STATUS
		$whereStatusBeli .= count($jenisStatus)>0 ? " and (TBELI.status='".implode("' or TBELI.status='", $jenisStatus)."')" : '';
		$whereStatusReturBeli .= count($jenisStatus)>0 ? " and (TRETURBELI.status='".implode("' or TRETURBELI.status='", $jenisStatus)."')" : '';

		if (strpos($tampil,"REGISTER") !== FALSE) {
			//NO TRANSAKSI	
			
			if (in_array('BELI', $jenisTrans)) {
				$sql = "select 1 as Nomor, tbeli.catatan, tbeli.tgltrans,tbeli.status, tbeli.kodebeli as kodetrans, tbeli.noinvoicesupplier,  msupplier.kodesupplier, msupplier.namasupplier,
					   tbeli.total, tbeli.ppnrp, tbeli.pph22rp, tbeli.grandtotal, tbeli.pembulatan, mbarang.KodeBarang, mbarang.NamaBarang, tbelidtl.Jml, tbelidtl.Satuan, tbelidtl.harga, mcurrency.simbol,
					   tbelidtl.NilaiKurs, tbelidtl.HargaKurs, tbelidtl.DiscPersen, tbelidtl.Disc,  tbelidtl.subtotal, tbelidtl.subtotalKurs,tbelidtl.pakaippn,tbelidtl.ppnrp as ppnbarang
						from tbeli 
						inner join tbelidtl  	on tbeli.idbeli=tbelidtl.idbeli
						left join mcurrency  	on tbelidtl.idcurrency=mcurrency.idcurrency
						left join mbarang  	on tbelidtl.idbarang=mbarang.idbarang and mbarang.stok = 1
						inner join msupplier  	on tbeli.idsupplier=msupplier.idsupplier
						left join mlokasi  		on tbeli.idlokasi = mlokasi.idlokasi
						left join msyaratbayar  on tbeli.idsyaratbayar = msyaratbayar.idsyaratbayar
						where (1=1 $whereKodeBeli $whereFilter) $wherePerusahaanBeli $whereTanggalBeli $whereLokasi $whereStatusBeli";
			}
			if (in_array('RETUR', $jenisTrans)) {
				
				$sql .= in_array('BELI', $jenisTrans) ? ' union ' : '';

				$sql .= "select 2 as Nomor,  treturbeli.catatan, treturbeli.tgltrans, treturbeli.status, treturbeli.kodereturbeli as kodetrans,'' as noinvoicesupplier, msupplier.kodesupplier, msupplier.namasupplier,
						-treturbeli.total as total, -treturbeli.ppnrp as ppnrp, -treturbeli.pph22rp as pph22rp, -treturbeli.grandtotal as grandtotal,treturbeli.pembulatan, mbarang.KodeBarang, mbarang.NamaBarang, -treturbelidtl.Jml as jml, treturbelidtl.Satuan, -treturbelidtl.harga as harga, mcurrency.simbol,
						treturbelidtl.NilaiKurs, -treturbelidtl.HargaKurs as HargaKurs, treturbelidtl.DiscPersen as Disc, -treturbelidtl.Disc as DiscRp, -treturbelidtl.subtotal as subtotal, -treturbelidtl.subtotalKurs as subtotalKurs,treturbelidtl.pakaippn,-treturbelidtl.ppnrp as ppnbarang
						from treturbeli 
						inner join treturbelidtl  		on treturbeli.idreturbeli = treturbelidtl.idreturbeli
						left join mcurrency  			on treturbelidtl.idcurrency=mcurrency.idcurrency
						left join mbarang  			on treturbelidtl.idbarang=mbarang.idbarang and mbarang.stok = 1
						inner join msupplier  			on treturbeli.idsupplier=msupplier.idsupplier
						left join mlokasi  				on treturbeli.idlokasi = mlokasi.idlokasi
						left join msyaratbayar  		on treturbeli.idsyaratbayar = msyaratbayar.idsyaratbayar
						where (1=1 $whereKodeReturBeli $whereFilter) $wherePerusahaanReturBeli $whereTanggalReturBeli $whereLokasi $whereStatusReturBeli			
						group by kodebarang,kodetrans";
			}
		} else if (strpos($tampil,"REKAP") !== FALSE) {
			if (in_array('BELI',$jenisTrans)) {
				
				$sql = "select 1 as NOMOR,tbeli.CATATAN, tbeli.TGLTRANS,tbeli.STATUS, tbeli.KODEBELI as KODETRANS, tbeli.NOINVOICESUPPLIER, msupplier.KODESUPPLIER, msupplier.NAMASUPPLIER, tbeli.TOTAL, tbeli.PPNRP, tbeli.PPH22RP, tbeli.GRANDTOTAL,tbeli.PEMBULATAN,
				        SUM(tbelidtl.jml) as QTY
						from tbeli 
						inner join msupplier  	on tbeli.idsupplier=msupplier.idsupplier
						inner join tbelidtl  	on tbeli.idbeli=tbelidtl.idbeli
						left join mbarang  	on tbelidtl.idbarang=mbarang.idbarang
						left join mcurrency  	on tbelidtl.idcurrency=mcurrency.idcurrency
						left join mlokasi  		on tbeli.idlokasi = mlokasi.idlokasi
						left join msyaratbayar  on tbeli.idsyaratbayar = msyaratbayar.idsyaratbayar
						where (1=1  $whereKodeBeli $whereFilter) $wherePerusahaanBeli $whereTanggalBeli $whereLokasi $whereStatusBeli
						group by 1,  tbeli.CATATAN, tbeli.TGLTRANS, tbeli.KODEBELI, msupplier.KODESUPPLIER, msupplier.NAMASUPPLIER";
			}
			if (in_array('RETUR', $jenisTrans)) {
				
				$sql .= in_array('BELI', $jenisTrans) ? ' union ' : '';

				$sql .= "select 2 as NOMOR,treturbeli.CATATAN, treturbeli.TGLTRANS, treturbeli.STATUS,treturbeli.KODERETURBELI as KODETRANS, '' AS NOINVOICESUPPLIER,
								msupplier.KODESUPPLIER, msupplier.NAMASUPPLIER, -treturbeli.TOTAL as TOTAL, -treturbeli.PPNRP as PPNRP, -treturbeli.PPH22RP as PPH22RP, -treturbeli.GRANDTOTAL as GRANDTOTAL,treturbeli.PEMBULATAN,
								SUM(treturbelidtl.jml) as QTY
						from treturbeli 
						inner join treturbelidtl  		on treturbeli.idreturbeli = treturbelidtl.idreturbeli
						left join mbarang  			on treturbelidtl.idbarang=mbarang.idbarang
						inner join msupplier  			on treturbeli.idsupplier=msupplier.idsupplier
						left join mcurrency  			on treturbelidtl.idcurrency=mcurrency.idcurrency
						left join mlokasi  				on treturbeli.idlokasi = mlokasi.idlokasi
						left join msyaratbayar  		on treturbeli.idsyaratbayar = msyaratbayar.idsyaratbayar
						where (1=1 $whereKodeReturBeli $whereFilter) $wherePerusahaanReturBeli $whereTanggalReturBeli $whereLokasi $whereStatusReturBeli			
						group by 2,  treturbeli.CATATAN, treturbeli.TGLTRANS, treturbeli.KODERETURBELI, msupplier.KODESUPPLIER, msupplier.NAMASUPPLIER";
			}
		}

		if ($tampil=='REGISTER') {
			$sql = "select * from ($sql) as abc order by tgltrans, nomor, kodetrans";
			$title = 'Laporan Pembelian - Register';
		}else if ($tampil=='REKAP') {
			$sql = "select * from ($sql) as abc order by tgltrans, nomor, kodetrans";
			$title = 'Laporan Pembelian - Rekap Berdasarkan Faktur';
		}

		$data['title']       = $title;
		$data['tampil']      = $tampil;
		$data['tgl_aw']      = $tgl_aw;
		$data['tgl_ak']      = $tgl_ak;
		$data['sql']         = $sql;
		$data['grouplokasi'] = $grouplokasi;
		$data['whereLokasi'] = $whereLokasi;
		$data['excel']       = $this->input->post('excel');
		$data['filename']    = $this->input->post('file_name');
		$data['LIHATHARGA']  = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];
		$dir = 'reports/v_';

		$this->load->view($dir."report_transaksi_inventori_pembelian.php",$data);
	}
}
