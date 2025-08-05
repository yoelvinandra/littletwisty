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

$query  = $CI->db->query("select namalokasi as namalokasi from mlokasi  where 1=1 $whereLokasi and idperusahaan = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} '")->result();
$lokasi = 'Region : ';

foreach($query as $item) {
		$lokasi .= $item->NAMALOKASI.', ';
}
$lokasi = substr_replace($lokasi, "", -2);

$query = $CI->db->query($sql)->result();

?>
<!DOCTYPE HTML>
<html>
	<head>
	<title> ..:: <?=$title?> ::.. </title>
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
    .det2{
        font-family:Tahoma,Verdana, Geneva, sans-serif;
        font-size:9px;
        font-weight:normal;
        padding: 2px;
    }
    </style>
	
</head>
<body>
	<div >
<?php

cetak_header($title, $_SESSION[NAMAPROGRAM]['NAMAPERUSAHAAN'], $lokasi, $tglAw, $tglAk);
	
$this->html_table->set_hr(true);

if ($tampil=='REGISTER') {    		
	$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
	$this->html_table->set_th(array(
		array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>110, 'values'=>'No. Trans'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Lokasi'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Tgl. Trans.'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Kd. Barang'),		
		array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Nama Barang'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Jml'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Satuan'),
	));
	$jml = 0;
	$KodeOpname = '';
	$urutan = 0;
	foreach($query as $item) {
		$a_merge = array();
		
		if ($KodeOpname!=$item->KODEOPNAMESTOK) {
		    $KodeOpname = $item->KODEOPNAMESTOK;
			$urutan++;
			$warna = $urutan%2==0 ? '#cfcfcf' : '#FFFFFF';
			if ($item->STATUS == 'I') $warna2 = '#FFFFFF';
			else if ($item->STATUS == 'S') $warna2=$_SESSION[WARNA_STATUS_S];
			else if ($item->STATUS == 'P') $warna2=$_SESSION[WARNA_STATUS_P]; 
			else if ($item->STATUS == 'D') $warna2=$_SESSION[WARNA_STATUS_D]; 
			
			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$item->KODEOPNAMESTOK),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$item->NAMALOKASI),
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
			array('valign'=>'top','align'=>'right', 'class'=>'det', 'values'=>number($item->JML, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>$item->SATUAN),
		)));
		$jml += $item->JML;
	}
	$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
	$this->html_table->set_td(array(
		array('align'=>'right', 'colspan'=>6, 'id'=>'tabelket', 'values'=>'Total'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>number($jml,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
		array()
	));	
} else if ($tampil=='REGISTERLOKASI') { 	
	$KodeOpname = ''; 
	$kodelokasi = '';
	$urutan = 0;
	foreach($query as $item) {
		if ($kodelokasi!=$item->NAMALOKASI) {
			$urutan = 0;
			if ($kodelokasi!='') {
				
				$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
				$this->html_table->set_td(array(
					array('align'=>'right', 'colspan'=>5, 'id'=>'tabelket', 'values'=>'Total'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>number($jml,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
					array()
				));	
				$this->html_table->line_break();
			}
			$urutan = 0;
			$jml = 0;
			$kodelokasi = $item->NAMALOKASI;
			
			$this->html_table->set_tr();
			$this->html_table->set_td(array(
				array('colspan'=>7, 'id'=>'tabelket', 'values'=>'Lokasi : '.$item->NAMALOKASI.' - '.$item->KODELOKASI),
			));
			
			$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
			$this->html_table->set_th(array(
				array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'bgcolor'=>$warna2, 'values'=>'No.'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>110, 'bgcolor'=>$warna2, 'values'=>'No. Trans'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'bgcolor'=>$warna2, 'values'=>'Tgl. Trans'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'bgcolor'=>$warna2, 'values'=>'Kd. Barang'),				
				array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'bgcolor'=>$warna2, 'values'=>'Nama Barang'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'bgcolor'=>$warna2, 'values'=>'Jml'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'bgcolor'=>$warna2, 'values'=>'Satuan'),
			));
		}
		
		$a_merge = array();
		if ($KodeOpname!=$item->KODEOPNAMESTOK) {
		    $KodeOpname=$item->KODEOPNAMESTOK;
			$urutan++;
			$warna = $urutan%2==0 ? '#cfcfcf' : '#FFFFFF';
			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'values'=>$urutan),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'values'=>$item->KODEOPNAMESTOK),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'values'=>ubah_tgl_indo($item->TGLTRANS)),
			);
		}
		else
		{
			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong'),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong'),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong'),
			);
		}
		$this->html_table->set_tr(array('bgcolor'=>$warna));
		$this->html_table->set_td(array_merge($a_merge, array(
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>$item->KODEBARANG),
			array('valign'=>'top','align'=>'left',   'class'=>'det', 'values'=>$item->NAMABARANG),
			array('valign'=>'top','align'=>'right', 'class'=>'det', 'values'=>number($item->JML, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>$item->SATUAN),
		)));
		$jml += $item->JML;
		$jmlTot += $item->JML;
		
			if ($kodelokasi!=$item->NAMALOKASI) {
				
				$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
				$this->html_table->set_td(array(
					array('align'=>'right', 'colspan'=>5, 'id'=>'tabelket', 'values'=>'Total'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>number($jml,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
					array()
				));	
				$this->html_table->line_break();
			}
			
	}
	if($query != null)
	{
		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>5, 'id'=>'tabelket', 'values'=>'Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>number($jml,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array()
		));	
		$this->html_table->line_break();
		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>5, 'id'=>'tabelket', 'values'=>'Grand Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>number($jmlTot,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array()
		));	
	}
	
} else if ($tampil=='REGISTERBARANG') { 

	$tbl = new html_table();
	
	$this->html_table->set_hr(true);
	
	$KodeOpname = ''; $kodebarang = '';
	$urutan = 0;
	foreach($query as $item) {
		if ($kodebarang!=$item->KODEBARANG) {
			$urutan = 0;
			if ($kodebarang!='') {
			
				$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
				$this->html_table->set_td(array(
					array('align'=>'right', 'colspan'=>4, 'id'=>'tabelket', 'values'=>'Total'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>number($jml,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
					array()
				));	
				$this->html_table->line_break();
			}
			$urutan = 0;
			$jml = 0;
				$this->html_table->set_tr();
				$this->html_table->set_td(array(
					array('colspan'=>6, 'id'=>'tabelket', 'values'=>'Barang : '.$item->NAMABARANG.' - ('.$item->KODEBARANG.')'),
				));

				$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
				$this->html_table->set_th(array(
					array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
					array('align'=>'center', 'id'=>'tabelket', 'width'=>110, 'values'=>'No. Trans'),
					array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Lokasi'),
					array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Tgl. Trans.'),				
					array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Jml'),
					array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Satuan'),
				));

				$kodebarang = $item->KODEBARANG;
		
			}

			
		$urutan++;
		$warna = $urutan%2==0 ? '#cfcfcf' : '#FFFFFF';

		$this->html_table->set_tr(array('bgcolor'=>$warna));
		$this->html_table->set_td(array(
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>$urutan),
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>$item->KODEOPNAMESTOK),
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>$item->NAMALOKASI),
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>ubah_tgl_indo($item->TGLTRANS)),			
			array('valign'=>'top','align'=>'right', 'class'=>'det', 'values'=>number($item->JML, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'values'=>$item->SATUAN),
		));
		$jml += $item->JML;
		$jmlTot += $item->JML;
	}
	if($query != null)
	{
		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>4, 'id'=>'tabelket', 'values'=>'Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>number($jml,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array()
		));	
		$this->html_table->line_break();
		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>4, 'id'=>'tabelket', 'values'=>'Grand Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>number($jmlTot,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array()
		));	
	}
}
echo $this->html_table->generate_table();
?>
</div>
</body>
</html>
<?php
function cetak_header($title, $perusahaan, $lokasi, $tglAw, $tglAk) {
    echo '<strong class="HEADER">'.$perusahaan.'</strong>';
	echo '<br>';
    echo '<strong class="HEADER">'.$title.'</strong>';
	echo '<br>';
    echo '<strong class="HEADERPERIODE">'.$lokasi.'</strong>';
	echo '<br>';
   	echo '<strong class="HEADERPERIODE">Periode : '.$tglAw.' s/d '.$tglAk.'</strong>';
}
?>