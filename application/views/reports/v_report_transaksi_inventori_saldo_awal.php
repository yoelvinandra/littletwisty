<?php
session_start();
if ($excel == 'ya'){
	include dirname(__FILE__)."/../export_to_excel.php";
}

if($errorMsg != ''){
	echo "<script>alert('$errorMsg'); window.close();</script>";
}

$CI =& get_instance();	
$CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);

$query  = $CI->db->query("select namalokasi from mlokasi where 1=1 $whereLokasi and idperusahaan = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} '")->result();
$lokasi = '';
foreach($query as $item) {
	if ($lokasi=='') {
		$lokasi .= $item->NAMALOKASI;
	} else {
		$lokasi .= ', '.$item->NAMALOKASI;
	}	
}
if($grouplokasi != "")
{
    $lokasi = $grouplokasi;
}
//echo $sql;

$query = $CI->db->query($sql)->result();

?>
<!DOCTYPE HTML>
<html>
	<head>
	<title> ..:: <?=$title?>::.. </title>
	<style>
    .HEADER {
        font-family: Tahoma,Verdana, Geneva, sans-serif;
        font-weight: bold;
        font-size: 18px;
        color: #000;
        text-align:left;
    }
    .HEADERPERIODE {
        font-family: Tahoma,arial, sans-serif, Verdana, Geneva ;
        font-weight: bold;
        font-size: 16px;
        color: #000;
        text-align:left;
    }
    #tabelket{
        font-family:Tahoma,Verdana, Geneva, sans-serif;
        font-size:10px;
        font-weight:bold;
        padding: 2px;	
    }
    .det{
        font-family:Tahoma, Verdana, Geneva, sans-serif;
        font-size:10px;
        padding: 2px;
		border-bottom:0px;
    }
	.det_kosong{
        font-family:Tahoma, Verdana, Geneva, sans-serif;
        font-size:10px;
        padding: 2px;
		border-top:0px;
		border-bottom:0px;
    }
    </style>
</head>
<body>
	<div align="left">
<?php
$CetakFooter  = 0;
$Total        = 0;
$TotalLokasi  = 0;
$GrandTotal   = 0;

if ($tampil=='REGISTER'){     
   cetak_header($title, $_SESSION[NAMAPROGRAM]['NAMAPERUSAHAAN'], $lokasi, $tglAw, $tglAk);
	
	$this->html_table->set_hr(true);	
	
	$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
	$this->html_table->set_th(array(
		array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),		
		array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Lokasi'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>110, 'values'=>'No. Trans'),		
		array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Tgl. Trans'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Kd. Barang'),		
		array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Nama Barang'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Jml'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Satuan'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Harga'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Subtotal'),
	));

	$KodePerusahaan = '';
	$KodeLokasi = '';
	$KodeSaldoStok = '';
	$urutan = 0;
	foreach($query as $item) {
		$a_merge = array();
		
		if ($KodeSaldoStok!=$item->KODESALDOSTOK) {
			if ($KodeSaldoStok!=''){
				
				$Total = 0;
			}
		    $KodeSaldoStok = $item->KODESALDOSTOK;
			$urutan++;
			$warna = $urutan%2==0 ? '#cfcfcf' : '#FFFFFF';
			if ($item->STATUS == 'I') $warna2 = '#FFFFFF';
			else if ($item->STATUS == 'S') $warna2=$_SESSION[WARNA_STATUS_S];
			else if ($item->STATUS == 'P') $warna2=$_SESSION[WARNA_STATUS_P]; 
			else if ($item->STATUS == 'D') $warna2=$_SESSION[WARNA_STATUS_D]; 
			
			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),			
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$item->NAMALOKASI),		
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$item->KODESALDOSTOK),								
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($item->TGLTRANS)),
			);
		}
		else
		{
			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),					
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
			);
		}
		
		$this->html_table->set_tr(array('bgcolor'=>$warna));
		$this->html_table->set_td(array_merge($a_merge, array(
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>$item->KODEBARANG), 			
			array('valign'=>'top','align'=>'left',   'class'=>'det', 'values'=>$item->NAMABARANG),
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>number($item->JML, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>$item->SATUAN),
			array('valign'=>'top','align'=>'right', 'class'=>'det', 'values'=>$LIHATHARGA?number($item->HARGA, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top','align'=>'right', 'class'=>'det', 'values'=>$LIHATHARGA?number($item->SUBTOTAL, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		)));
		$Total      += $item->SUBTOTAL;
		$GrandTotal += $item->SUBTOTAL;		
	}
    $this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
	$this->html_table->set_td(array(
		array('align'=>'right', 'colspan'=>9, 'id'=>'tabelket', 'values'=>'Total'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($Total,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
	));
	
    $this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
	$this->html_table->set_td(array(
		array('align'=>'right', 'colspan'=>9, 'id'=>'tabelket', 'values'=>'Grand Total'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($GrandTotal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
	));
	echo $this->html_table->generate_table();	
} else if ($tampil=='REGISTERLOKASI'){ 
   cetak_header($title, $_SESSION[NAMAPROGRAM]['NAMAPERUSAHAAN'], $lokasi, $tglAw, $tglAk);
	
	$tbl = new html_table();
	
	$this->html_table->set_hr(true);	
	
	
	if($query != null)
	{
		$KodeLokasi    = '';
		$KodeSaldoStok = '';
		$urutan = 0;
		foreach($query as $item) {
			$a_merge = array();
			
			if ($KodeSaldoStok!=$item->KODESALDOSTOK) {
				
				
				if($KodeSaldoStok!=''){	
					$Total = 0;
				}
				$urutan++;
				$KodeSaldoStok = $item->KODESALDOSTOK;
				
				$warna = $urutan%2==0 ? '#cfcfcf' : '#FFFFFF';
				if ($item->STATUS == 'I') $warna2 = '#FFFFFF';
				else if ($item->STATUS == 'S') $warna2=$_SESSION[WARNA_STATUS_S];
				else if ($item->STATUS == 'P') $warna2=$_SESSION[WARNA_STATUS_P]; 
				else if ($item->STATUS == 'D') $warna2=$_SESSION[WARNA_STATUS_D]; 
				
				$a_merge = array(
					array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),			
					array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$item->KODESALDOSTOK),				
					array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($item->TGLTRANS)),
				);
				
			}
			else
			{
				$a_merge = array(
					array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
					array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
					array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				);
			}
			
			if ($KodeLokasi!=$item->KODELOKASI){
				if ($KodeLokasi!=''){
					$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
					$this->html_table->set_td(array(
						array('align'=>'right', 'colspan'=>8, 'id'=>'tabelket', 'values'=>'Total Lokasi '.$NamaLokasi),
						array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($TotalLokasi,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					));								
					$TotalLokasi = 0;
					$urutan=0;
				}
				$KodeLokasi = $item->KODELOKASI;
				$NamaLokasi = $item->NAMALOKASI;
				$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
				$this->html_table->set_td(array(
					array('align'=>'left', 'id'=>'tabelket', 'colspan'=>9, 'width'=>30,  'values'=>'Lokasi : '.$item->NAMALOKASI),		
				));
				$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
				$this->html_table->set_th(array(
					array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),		
					array('align'=>'center', 'id'=>'tabelket', 'width'=>110, 'values'=>'No. Trans'),		
					array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Tgl. Trans'),
					array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Kd. Barang'),		
					array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Nama Barang'),
					array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Jml'),
					array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Satuan'),
					array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Harga'),
					array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Subtotal'),
				));
				
			}
		
			
			$this->html_table->set_tr(array('bgcolor'=>$warna));
			$this->html_table->set_td(array_merge($a_merge, array(
				array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>$item->KODEBARANG), 			
				array('valign'=>'top','align'=>'left',   'class'=>'det', 'values'=>$item->NAMABARANG),
				array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>number($item->JML, true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>$item->SATUAN),
				array('valign'=>'top','align'=>'right', 'class'=>'det', 'values'=>$LIHATHARGA?number($item->HARGA, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top','align'=>'right', 'class'=>'det', 'values'=>$LIHATHARGA?number($item->SUBTOTAL, true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			)));
			$Total       += $item->SUBTOTAL;
			$TotalLokasi += $item->SUBTOTAL;
			$GrandTotal  += $item->SUBTOTAL;		
		}
		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>8, 'id'=>'tabelket', 'values'=>'Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($Total,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		));
		
		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>8, 'id'=>'tabelket', 'values'=>'Total Lokasi '.$NamaLokasi),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($TotalLokasi,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		));								

		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>8, 'id'=>'tabelket', 'values'=>'Grand Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($GrandTotal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		));
		
		echo $this->html_table->generate_table();	
	}
}  else if ($tampil=='REKAP') {
     cetak_header($title, $_SESSION[NAMAPROGRAM]['NAMAPERUSAHAAN'], $lokasi, $tglAw, $tglAk);
	
	$tbl = new html_table();
		
	$this->html_table->set_hr(true);

	$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
	$this->html_table->set_th(array(
		array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Trans'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>80, 'values'=>'Total Barang'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Grand Total ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Keterangan'),
	));

	$kodetrans = '';
	$total = array();
	$urutan = 0;
	foreach($query as $r) {
		$urutan++;
		$warna = $urutan%2==0 ? '#cfcfcf' : '#FFFFFF';

		if ($r->STATUS == 'I') $warna2 = '#FFFFFF';
		else if ($r->STATUS == 'S') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_S'];
		else if ($r->STATUS == 'C') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_C'];
		else if ($r->STATUS == 'P') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'];
		else if ($r->STATUS == 'D') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'];
        
        $total['qty'] += $LIHATHARGA?$r->QTY:0;
		$total['grandtotal'] += $LIHATHARGA?$r->GRANDTOTAL:0;

		$this->html_table->set_tr();
		$this->html_table->set_td(array(
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($r->TGLTRANS)),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODETRANS),
			array('valign'=>'top', 'align'=>'center',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>number($r->QTY,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->GRANDTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->CATATAN),
		));

	}

	$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
	$this->html_table->set_td(array(
		array('align'=>'right', 'id'=>'tabelket', 'colspan'=>3, 'values'=>'Grand Total'),
		array('align'=>'center', 'id'=>'tabelket', 'values'=>number($total['qty'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['grandtotal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		array(),
	));
		echo $this->html_table->generate_table();	

}	
?>
</div>
</body>
</html>
<?php
function cetak_header($title,$perusahaan,$lokasi,$tglAw,$tglAk) {
    echo '<strong class="HEADER">'.$perusahaan.'</strong>';
	echo '<br>';
	echo '<strong class="HEADER">'.$title.'</strong>';
	echo '<br>';
    echo '<strong class="HEADERPERIODE">Lokasi : '.$lokasi.'</strong>';
	echo '<br>';
}
?>