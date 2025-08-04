<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanPosisiStok extends MY_Controller {
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
		$grouplokasi  = $this->input->post('group_lokasi');
		$lokasi      = $this->input->post('txt_lokasi',[]);
		$perusahaan  = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
		
		$tampil      = $this->input->post('data_tampil');
		$filter      = $this->input->post('data_filter');
		$cbPerLokasi  = $this->input->post('cbStokPerLokasi')??"0";
		
		//KONVERSI JADI QUERY
		$data = json_decode($filter);
	
		//SORTING AGAR BISA TENTUKAN AND DAN OR
		$arrKolom = array();
		$arrOperator = array();
		
		//CEK KOLOM DENGAN YANG LAMA
		$kolomLama = "";
		$operatorLama ="";
		$logika ="";
		
		$kolom;
		$operator;
		
		for($i = 0; $i<count($data);$i++)
		{
			$arrKolom[$i] = $data[$i]->KOLOM;
			$arrOperator[$i] = $data[$i]->OPERATOR;
		}
		
		array_multisort($arrKolom, SORT_ASC, $arrOperator, SORT_ASC, $data);
		
		//KONVERSI QUERY
		for($i = 0; $i<count($data);$i++)
		{
			
			if($data[$i]->KOLOM != $kolomLama || $data[$i]->OPERATOR != $operatorLama)
			{
				$logika = " ) AND (";
				$kolomLama = $data[$i]->KOLOM;
				$operatorLama = $data[$i]->OPERATOR;
			}
			else
			{
				$logika = " OR ";
			}
				
			if($data[$i]->TIPEDATA == "STRING")
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
			else if($data[$i]->TIPEDATA == "NUMBER" && $data[$i]->KOLOM == "SUM(c.JML)")
			{		
				if($data[$i]->OPERATOR == "SAMA DENGAN")
				{
					$whereJML.= $logika.$data[$i]->KOLOM." = ".$data[$i]->NILAI."";
				}
				else if($data[$i]->OPERATOR == "TIDAK SAMA DENGAN")
				{
					$whereJML.= $logika.$data[$i]->KOLOM." != ".$data[$i]->NILAI."";
				}
				else if($data[$i]->OPERATOR == "LEBIH BESAR DARI")
				{
					$whereJML.= $logika.$data[$i]->KOLOM." > ".$data[$i]->NILAI."";
				}
				else if($data[$i]->OPERATOR == "LEBIH BESAR SAMA DENGAN")
				{
					$whereJML.= $logika.$data[$i]->KOLOM." >= ".$data[$i]->NILAI."";
				}
				else if($data[$i]->OPERATOR == "LEBIH KECIL DARI")
				{
					$whereJML.= $logika.$data[$i]->KOLOM." < ".$data[$i]->NILAI."";
				}
				else if($data[$i]->OPERATOR == "LEBIH KECIL SAMA DENGAN")
				{
					$whereJML.= $logika.$data[$i]->KOLOM." <= ".$data[$i]->NILAI."";
				}
				else if($data[$i]->OPERATOR == "NOL")
				{
					$whereJML.= $logika.$data[$i]->KOLOM." = 0 ";
				}
				else if($data[$i]->OPERATOR == "TIDAK NOL")
				{
					$whereJML.= $logika.$data[$i]->KOLOM." != 0 ";
				}
			}
		}
		
		//LOKASI
		$whereLokasi = count($lokasi)>0 ? " and (MLOKASI.idlokasi='".implode("' or MLOKASI.idlokasi='", $lokasi)."')" : '';
		
		//TANGGAL
		$tgl_ak = ubah_tgl_firebird($this->input->post('txt_tgl_ak'));

        
        if($tampil == "POSISISTOK")
        {
            
            $groupByLokasi = "";
            $groupByLokasiGroup = "";
            $namalokasi = "'Gabungan Lokasi'";
            $kodelokasi = "'-'";
            if($cbPerLokasi == 1)
    	    {
    	        $namalokasi = "c.NAMALOKASI";
    	        $kodelokasi = "c.KODELOKASI";
                $groupByLokasi = ", mlokasi.kodelokasi";
                $groupByLokasiGroup  = "c.kodelokasi,";
    	    }
    	    
    		$sql = "select mbarang.urutantampil, mbarang.idbarang, mbarang.KODEBARANG, mbarang.NAMABARANG,  mbarang.WARNA, mbarang.SIZE, saldostok.IDLOKASI, mlokasi.NAMALOKASI, mlokasi.KODELOKASI, 
    					   mbarang.SATUAN, mbarang.SATUAN2, mbarang.SATUAN3, mbarang.KONVERSI1, mbarang.KONVERSI2, 
    					   mbarang.HARGAJUAL, sum(saldostokdtl.JML) as JML, sum(saldostokdtl.SUBTOTAL) as SUBTOTAL, 0 as jmlpo, 0 as jmlso
    				from SALDOSTOK 
    				left join SALDOSTOKDTL  on saldostok.KODESALDOSTOK = saldostokdtl.KODESALDOSTOK 
    				left join MBARANG 		 on saldostokdtl.IDBARANG = mbarang.IDBARANG and STOK = 1
    				left join MLOKASI  	 on saldostok.IDLOKASI = mlokasi.IDLOKASI
    				where (1=1 $whereFilter) and saldostok.TGLTRANS <= '$tgl_ak' and 
    					  saldostok.IDPERUSAHAAN = $perusahaan and
    					  mbarang.IDPERUSAHAAN = $perusahaan and
    					  mlokasi.IDPERUSAHAAN = $perusahaan
    					  $whereLokasi and saldostok.KODESALDOSTOK not like 'CLS%' and SALDOSTOK.STATUS <> 'D'
    				group by SALDOSTOKDTL.idbarang $groupByLokasi
    
    				union all
    
    				select mbarang.urutantampil, mbarang.idbarang, mbarang.KODEBARANG, mbarang.NAMABARANG, mbarang.WARNA, mbarang.SIZE, kartustok.IDLOKASI, mlokasi.NAMALOKASI, mlokasi.KODELOKASI, 
    					   mbarang.SATUAN, mbarang.SATUAN2, mbarang.SATUAN3, mbarang.KONVERSI1, mbarang.KONVERSI2,
    					   mbarang.HARGAJUAL, sum(if(kartustok.MK = 'M', kartustok.JML, -kartustok.JML)) as JML, 
    					   sum(if(kartustok.MK = 'M', kartustok.TOTALHARGA, -kartustok.TOTALHARGA)) as SUBTOTAL,sum(kartustok.jmlpo) as jmlpo, sum(kartustok.jmlso) as jmlso
    				from KARTUSTOK 
    				left join MBARANG  	on kartustok.IDBARANG = mbarang.IDBARANG and STOK = 1
    				left join MLOKASI  	on kartustok.IDLOKASI = mlokasi.IDLOKASI
    				where (1=1 $whereFilter) and kartustok.TGLTRANS <= '$tgl_ak' and 
    					  kartustok.IDPERUSAHAAN = $perusahaan and
    					  mbarang.IDPERUSAHAAN = $perusahaan and
    					  mlokasi.IDPERUSAHAAN = $perusahaan
    					  $whereLokasi
    				group by KARTUSTOK.idbarang $groupByLokasi
    				";
    				
    		$sql = "select c.URUTANTAMPIL, c.IDBARANG,c.KODEBARANG, c.NAMABARANG, c.WARNA, c.SIZE,  $namalokasi as NAMALOKASI, $kodelokasi as KODELOKASI, c.SATUAN, c.SATUAN2, 
    					   c.SATUAN3, c.KONVERSI1, c.KONVERSI2, c.HARGAJUAL,
    					   sum(c.jml) as jml, sum(c.subtotal) as subtotal, sum(c.jmlpo) as jmlpo, sum(c.jmlso) as jmlso
    				from ($sql) as c
    				group by c.kodebarang,c.kodelokasi
    				having (1=1 $whereJML) 
    				order by $groupByLokasiGroup SUBSTRING(c.URUTANTAMPIL, 1, 1) ASC ,
							CAST(SUBSTRING(c.URUTANTAMPIL, 2) AS UNSIGNED) ASC
							, c.NAMABARANG, c.KODEBARANG";
							
        }
        else if($tampil == "POSISISTOKSIZE")
        {
    		$sqlMinMaxUkuran = "SELECT (SELECT MIN(x.SIZE) FROM `MBARANG` x WHERE x.SIZE > MIN(MBARANG.SIZE)) as MINSIZE, MAX(MBARANG.SIZE) as MAXSIZE FROM `MBARANG` WHERE 1";
    		$MinMaxUkuran = $this->db->query($sqlMinMaxUkuran)->row();
        }
				
				
		$data['sql']                 = $sql;
		$data['whereJML']            = $whereJML;
		$data['tgl_akhir']           = $tgl_ak;
		$data['minukuran']           = $MinMaxUkuran->MINSIZE;
		$data['maxukuran']           = $MinMaxUkuran->MAXSIZE;
		$data['tgl_akhir']           = $tgl_ak;
		$data['grouplokasi']         = $grouplokasi;
		$data['whereLokasi']         = $whereLokasi;
		$data['whereFilter']         = $whereFilter;
		$data['jumlahLokasi']		 = count($lokasi);
		$data['tampil']              = $tampil;
		$data['excel']               = $this->input->post('excel');
		$data['filename']            = $this->input->post('file_name');
		$data['LIHATHARGA']          = $this->model_master_config->getAkses($kodeMenu)["LIHATHARGA"];
		$data['stokPerLokasi']       = $cbPerLokasi;
		
		
		$dir = 'reports/v_';
		$this->load->view($dir."report_transaksi_inventori_posisi_stok.php",$data);
	}
}
