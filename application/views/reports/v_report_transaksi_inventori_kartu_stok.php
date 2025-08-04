<?php
session_start();
if ($excel == 'ya'){
	include dirname(__FILE__)."/../export_to_excel.php";
}

if($errorMsg != ''){
	echo "<script>alert('$errorMsg'); window.close();</script>";
}

$perusahaan=$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
$CI =& get_instance();	
$CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);

$query  = $CI->db->query("select namalokasi from mlokasi  where 1=1 $whereLokasi and idperusahaan = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} '")->result();
$lokasi = '';
foreach($query as $item) {
	if ($lokasi=='') {
		$lokasi .= $item->NAMALOKASI;
	} else {
		$lokasi .= ', '.$item->NAMALOKASI;
	}	
}
$query = $CI->db->query($sql)->result();
if($grouplokasi != "")
{
    $lokasi = $grouplokasi;
}

?>
<!DOCTYPE HTML>
<html>
<head>
<?php
echo "<title> ..:: Kartu Stok ::.. </title>";
?>
	<style>
    .HEADER {
        font-family: Tahoma,Verdana, Geneva, sans-serif;
        font-weight: bold;
        font-size: 18px;
        color: #000;
        text-align:left;
    }
    .HEADERPERIODE {
        font-family: Tahoma,arial, sans-serif, Verdana, Geneva;
        font-weight: bold;
        font-size: 16px;
        color: #000;
        text-align:left;
    }
	.hapus-border-bawah{
		border-bottom:1px solid #FFF;
	}

	.hapus-border-atas{
		border-top:1px solid #FFF;
	}

	.hapus-border-kiri{
		border-left:1px solid #FFF;
	}

	.hapus-border-kanan{
		border-right:1px solid #FFF;
	}

    #tabelket{
        font-family:Tahoma,Verdana, Geneva, sans-serif;
        font-size:10px;
        font-weight:bold;
        padding: 2px;
    }
    .det{
        font-family:Tahoma,Verdana, Geneva, sans-serif;
        font-size:10px;
        padding: 2px;
    }
    .det2{
        font-family:Tahoma,Verdana, Geneva, sans-serif;
        font-size:9px;
        font-weight:normal;
        padding: 2px;
    }
    </style>
</head>
<body>
<?php
cetak_header($tampil, 'Laporan Kartu Stok ', $_SESSION[NAMAPROGRAM]['NAMAPERUSAHAAN'], $lokasi, $tglAw, $tglAk);
$this->html_table->set_hr(true);

$masuk         = 0;
$keluar        = 0;
$masukRp       = 0;
$keluarRp      = 0;
$TotalMasuk    = 0;
$TotalKeluar   = 0;
$TotalMasukRp  = 0;
$TotalKeluarRP = 0;
$Konversi1     = 0;
$Konversi2     = 0;

$temp_barang = '';
foreach($query as $item) {
	$Konversi1 = $item->KONVERSI1;
	$Konversi2 = $item->KONVERSI2;
	$Satuan    = $item->SATUAN;
	$Satuan2   = $item->SATUAN2;
	$Satuan3   = $item->SATUAN3;

	$wherePerusahaan = " and a.idperusahaan=".$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
	
	if ($temp_barang != $item->KODEBARANG) {
		if ($temp_barang!='') {
			$this->html_table->line_break();
		}

		$TotalMasuk    = 0;
		$TotalKeluar   = 0;
		$TotalMasukRp  = 0;
		$TotalKeluarRp = 0;
		$JmlAwal       = 0;
		$RpAwal        = 0;

		$temp_barang = $item->KODEBARANG;

		$ket_satuan = $Satuan;
	
		if($jenistampil == 'STOK')
		{
			$this->html_table->set_tr();
			$this->html_table->set_td(array(
			array('colspan'=>6, 'id'=>'tabelket', 'values'=>'Barang : '.$item->NAMABARANG.' ('.$item->KODEBARANG.') | '.$ket_satuan),
			));

			$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
			$this->html_table->set_th(array(
				array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Tanggal'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans.'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Keterangan'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Masuk'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Keluar'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Saldo'),
			));
		}
		else if($jenistampil == 'HARGA')
		{
			$this->html_table->set_tr();
			$this->html_table->set_td(array(
				array('colspan'=>6, 'id'=>'tabelket', 'values'=>'Barang : '.$item->NAMABARANG.' ('.$item->KODEBARANG.') | '.$ket_satuan),
			));

			$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
			$this->html_table->set_th(array(
				array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Tanggal'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans.'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Keterangan'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Masuk (Rp)'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Keluar (Rp)'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Saldo (Rp)'),
			));
		}
		else
		{
			$this->html_table->set_tr();
			$this->html_table->set_td(array(
				array('colspan'=>9, 'id'=>'tabelket', 'values'=>'Barang : '.$item->NAMABARANG.' ('.$item->KODEBARANG.') | '.$ket_satuan),
			));

			$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
			$this->html_table->set_th(array(
				array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Tanggal'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans.'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Keterangan'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Masuk'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Keluar'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Saldo'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Masuk (Rp)'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Keluar (Rp)'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Saldo (Rp)'),
			));
		}
		

		$sql = "select max(a.Tgltrans) as TglAwal
				from SaldoStok a 
				inner join saldostokdtl b on a.idSaldoStok=b.idSaldoStok 
				inner join mlokasi on a.idlokasi = mlokasi.idlokasi
				where b.idBarang= {$item->IDBARANG} 
					  and a.tgltrans<'$tglAw' 
					  and a.status<>'D' and  a.kodesaldostok NOT LIKE 'CLS%'
					  $wherePerusahaan $whereLokasi ";
		
		$qsaldo = $CI->db->query($sql);
		$rsaldo = $qsaldo->row();
		
		if ($rsaldo->TGLAWAL==NULL or $rsaldo->TGLAWAL=='') {
			$TglAwal = '01.01.1900';
			$Saldo   = 0;
			$Rupiah  = 0;

		} else{
			$TglAwal = $rsaldo->TGLAWAL;
			
			$sql = "select sum(c.jml) as awal, sum(c.SubTotal) as Rupiah
					from SaldoStok A 
					inner join SaldoStokDtl c on a.idSaldoStok=c.idSaldoStok
					inner join MBarang b on c.idBarang=B.idBarang
					inner join mlokasi  on a.idlokasi=mlokasi.idlokasi
					where B.idBarang=$item->IDBARANG
						  and A.TglTrans='$TglAwal'
						  and A.status<>'D'
						  $wherePerusahaan $whereLokasi ";
		
			$qsaldo = $CI->db->query($sql);
			$rsaldo = $qsaldo->row();
	
			$Saldo	= $rsaldo->AWAL;
			$Rupiah	= $rsaldo->RUPIAH;
		}
		
		$sql = "select sum(if(a.MK='M', a.jml, -a.jml)) as jml, 
		               sum(if(a.MK='M', a.totalharga, -a.totalharga)) as totalharga
				from KartuStok a 
				inner join mbarang b on a.idbarang=b.idbarang
				inner join mlokasi on a.idlokasi = mlokasi.idlokasi
				where a.idBarang=$item->IDBARANG
					  and a.TglTrans>'$TglAwal'
					  and a.TglTrans<'$tglAw' 
					  and a.jml>0 $wherePerusahaan $whereLokasi 
				";
		
		$qsaldo = $CI->db->query($sql);
		$rsaldo = $qsaldo->row();
		
		$Saldo	+= $rsaldo->JML;
		$Rupiah	+= $rsaldo->TOTALHARGA;
		
		$JmlAwal = $Saldo;
		$RpAwal  = $Rupiah;

		$date = date($tglAw);
		$newdate = strtotime ( '-1 day' , strtotime ( $date ) ) ;
		$newdate = date ( 'Y-m-d' , $newdate );
				
		if($jenistampil == 'STOK')
		{
			$this->html_table->set_tr();
			$this->html_table->set_td(array(
				array('align'=>'center', 'class'=>'det',  'values'=>ubah_tgl_indo($newdate)),
				array('align'=>'left',   'class'=>'det',  'values'=>''),
				array('align'=>'left',   'class'=>'det',  'values'=>'Saldo Awal'),
				array('align'=>'right',  'class'=>'det',  'values'=>number(0, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('align'=>'right',  'class'=>'det',  'values'=>number(0, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('align'=>'right',  'class'=>'det',  'bgcolor'=>($JmlAwal<0 ? '#ff6a6a' : ''), 'values'=>number($JmlAwal, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			));
		}
		else if($jenistampil == 'HARGA')
		{
			$this->html_table->set_tr();
			$this->html_table->set_td(array(
				array('align'=>'center', 'class'=>'det',  'values'=>ubah_tgl_indo($newdate)),
				array('align'=>'left',   'class'=>'det',  'values'=>''),
				array('align'=>'left',   'class'=>'det',  'values'=>'Saldo Awal'),
				array('align'=>'right',  'class'=>'det',  'values'=>$LIHATHARGA?number(0, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('align'=>'right',  'class'=>'det',  'values'=>$LIHATHARGA?number(0, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('align'=>'right',  'class'=>'det',  'values'=>$LIHATHARGA?number($RpAwal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			));
		}
		else
		{
			$this->html_table->set_tr();
			$this->html_table->set_td(array(
				array('align'=>'center', 'class'=>'det',  'values'=>ubah_tgl_indo($newdate)),
				array('align'=>'left',   'class'=>'det',  'values'=>''),
				array('align'=>'left',   'class'=>'det',  'values'=>'Saldo Awal'),
				array('align'=>'right',  'class'=>'det',  'values'=>number(0, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('align'=>'right',  'class'=>'det',  'values'=>number(0, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('align'=>'right',  'class'=>'det',  'bgcolor'=>($JmlAwal<0 ? '#ff6a6a' : ''), 'values'=>number($JmlAwal, false, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('align'=>'right',  'class'=>'det',  'values'=>$LIHATHARGA?number(0, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('align'=>'right',  'class'=>'det',  'values'=>$LIHATHARGA?number(0, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('align'=>'right',  'class'=>'det',  'values'=>$LIHATHARGA?number($RpAwal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			));
		}
		
		$sql = "select a.KodeTrans, a.TglTrans,a.MK,a.Jml, a.TotalHarga, a.keterangan, a.urutan as Urutan
				from(
				
				select a.kodesaldostok as KodeTrans, a.TglTrans,'M' as MK,c.Jml, c.subtotal as TotalHarga, 'Saldo Awal' as keterangan, 0 as Urutan
				from SaldoStok A
				inner join SaldoStokDtl c ON a.idSaldoStok=c.idSaldoStok
				inner join MBarang b ON c.idBarang=B.idBarang
				inner join mlokasi on a.idlokasi = mlokasi.idlokasi
				where b.idbarang=$item->IDBARANG 
					  and a.TglTrans>='$tglAw' 
					  and a.TglTrans<='$tglAk' 
					  and a.status<>'D'
					  and c.jml>0 $wherePerusahaan $whereLokasi and  a.kodesaldostok NOT LIKE 'CLS%'
					  
				union
				
				select a.KodeTrans, a.TglTrans, a.MK, a.JmL as JML, a.TotalHarga as TOTALHARGA, a.keterangan, a.Urutan
				from KartuStok a
				inner join mbarang b ON a.idbarang=b.idbarang
				inner join mlokasi on a.idlokasi = mlokasi.idlokasi
				where b.idbarang=$item->IDBARANG
					  and a.TglTrans>='$tglAw' 
					  and a.TglTrans<='$tglAk'
					  and a.jml>0 $wherePerusahaan $whereLokasi 
					  
				) a
				order by a.TglTrans asc, a.Urutan asc"; 

		$qkartu = $CI->db->query($sql)->result();

		foreach($qkartu as $rkartu) {
			/*$Konversi = 1;
			
			if ($rkartu->KONVERSI2>0) {
				$Konversi = $rkartu->KONVERSI2 * $rkartu->KONVERSI1;
			} else if ($rkartu->KONVERSI1>0) {
				$Konversi = $rkartu->KONVERSI1;
			}*/
			if ($rkartu->MK=='M') {
				$JmlAwal      += $rkartu->JML;
				$RpAwal       += $rkartu->TOTALHARGA;
				$TotalMasuk   += $rkartu->JML;
				$TotalMasukRp += $rkartu->TOTALHARGA;

				$Masuk    = $rkartu->JML;
				$Keluar   = 0;
				$MasukRp  = $rkartu->TOTALHARGA;
				$KeluarRp = 0;
			} else if ($rkartu->MK=='K') {
				$JmlAwal       -= $rkartu->JML;
				$RpAwal        -= $rkartu->TOTALHARGA;
				$TotalKeluar   += $rkartu->JML;
				$TotalKeluarRp += $rkartu->TOTALHARGA;

				$Masuk	  = 0;
				$Keluar	  = $rkartu->JML;
				$MasukRp  = 0;
				$KeluarRp = $rkartu->TOTALHARGA;
			}
			else
			{
				$JmlAwal      += $rkartu->JML;
				$RpAwal       += $rkartu->TOTALHARGA;
				$Masuk   	   = $rkartu->JML;
				$MasukRp 	   = $rkartu->TOTALHARGA;
				
				$Keluar	  = 0;
				$KeluarRp = 0;
			}
		
			$this->html_table->set_tr();
			
			if($jenistampil == 'STOK')
			{
				$this->html_table->set_td(array(
					array('align'=>'center', 'class'=>'det', 'values'=>ubah_tgl_indo($rkartu->TGLTRANS)),
					array('class'=>'det','align'=>'center', 'values'=>$rkartu->KODETRANS),
					array('class'=>'det','values'=>$rkartu->KETERANGAN),
					array('align'=>'right', 'class'=>'det', 'values'=>number($Masuk, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
					array('align'=>'right', 'class'=>'det', 'values'=>number($Keluar, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
					array('align'=>'right', 'class'=>'det', 'bgcolor'=>($JmlAwal<0 ? '#ff6a6a' : ''), 'values'=>number($JmlAwal, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				));
			}
			else if($jenistampil == 'HARGA')
			{
				$this->html_table->set_td(array(
					array('align'=>'center', 'class'=>'det', 'values'=>ubah_tgl_indo($rkartu->TGLTRANS)),
					array('class'=>'det','align'=>'center', 'values'=>$rkartu->KODETRANS),
					array('class'=>'det', 'values'=>$rkartu->KETERANGAN),
					array('align'=>'right', 'class'=>'det', 'values'=>$LIHATHARGA?number($MasukRp,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'class'=>'det', 'values'=>$LIHATHARGA?number($KeluarRp,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'class'=>'det', 'values'=>$LIHATHARGA?number($RpAwal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				));
			}
			else
			{
				$this->html_table->set_td(array(
					array('align'=>'center', 'class'=>'det', 'values'=>ubah_tgl_indo($rkartu->TGLTRANS)),
					array('class'=>'det','align'=>'center', 'values'=>$rkartu->KODETRANS),
					array('class'=>'det', 'values'=>$rkartu->KETERANGAN),
					array('align'=>'right', 'class'=>'det', 'values'=>number($Masuk, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
					array('align'=>'right', 'class'=>'det', 'values'=>number($Keluar, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
					array('align'=>'right', 'class'=>'det', 'bgcolor'=>($JmlAwal<0 ? '#ff6a6a' : ''), 'values'=>number($JmlAwal, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
					array('align'=>'right', 'class'=>'det', 'values'=>$LIHATHARGA?number($MasukRp,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'class'=>'det', 'values'=>$LIHATHARGA?number($KeluarRp,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'class'=>'det', 'values'=>$LIHATHARGA?number($RpAwal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				));
			}
			
		}
	}
}

echo $this->html_table->generate_table();
?>
</div>
<?php
function cetak_header($tampil,$keterangan,$perusahaan,$Lokasi,$tglAw, $tglAk) {
	echo '<strong class="HEADERPERIODE">'.$perusahaan.'</strong>';
	echo '<br>';
    echo '<strong class="HEADER">'.$keterangan.'</strong>';
	echo '<br>';
	echo '<strong class="HEADERPERIODE">Lokasi : '.$Lokasi.'</strong>';
	echo '<br>';
   	echo '<strong class="HEADERPERIODE">Periode : '.$tglAw.' s/d '.$tglAk.'</strong>';
}

?>
