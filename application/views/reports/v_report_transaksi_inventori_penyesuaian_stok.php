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
//echo $sql;
if($grouplokasi != "")
{
    $lokasi = $grouplokasi;
}
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
        font-family:Tahoma,Verdana, Geneva, sans-serif;
        font-size:10px;
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
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Trans.'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>110, 'values'=>'No. Trans.'),
// 		array('align'=>'center', 'id'=>'tabelket', 'width'=>110, 'values'=>'No. Opname'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Lokasi'),		
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Kode Barang'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>200, 'values'=>'Nama Barang'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Jml Opname'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Selisih'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Satuan'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Harga'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Subtotal'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>250, 'values'=>'Catatan'),
	));
	$temp_kodetrans = '';
	$urutan = 0;
	foreach($query as $item) {
		$a_merge = array();
		$a_merge2 = array();
		if ($temp_kodetrans!=$item->KODEPENYESUAIANSTOK) {
		    if ($temp_kodetrans<>'') {
			}
			$temp_kodetrans = $item->KODEPENYESUAIANSTOK;
			$urutan++;
			$warna = $urutan%2==0 ? '#cfcfcf' : '#FFFFFF';

			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'values'=>$urutan),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'values'=>ubah_tgl_indo($item->TGLTRANS)),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'values'=>$item->KODEPENYESUAIANSTOK),
				// array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'values'=>$item->KODEOPNAMESTOK),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'values'=>$item->NAMALOKASI),				
			);
			
			$a_merge2 = array(
			array('valign'=>'top',  'align'=>'left', 'class'=>'det', 'values'=>$item->CATATAN),
			);

		}
		else
		{
			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong'),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong'),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong'),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong'),
			);
			
			$a_merge2 = array(
			array('valign'=>'top',  'align'=>'left', 'class'=>'det_kosong'),
			);
		}
		
		
		
		

		
		$this->html_table->set_tr(array('bgcolor'=>$warna));
		$this->html_table->set_td(array_merge(
			$a_merge,
			array(
				array('class'=>'det', 'valign'=>'top','align'=>'center', 'values'=>$item->KODEBARANG),
				array('class'=>'det', 'valign'=>'top','align'=>'left',   'values'=>$item->NAMABARANG),
				array('class'=>'det', 'valign'=>'top','align'=>'right',  'values'=>number($item->JML,false,0)),
				array('class'=>'det', 'valign'=>'top','align'=>'right',  'values'=>number($item->SELISIH,false,0)),
				array('class'=>'det', 'valign'=>'top','align'=>'center', 'values'=>$item->SATUAN),
				array('class'=>'det', 'align'=>'right',  'values'=>$LIHATHARGA?number($item->HARGA,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('class'=>'det', 'align'=>'right',  'values'=>$LIHATHARGA?number($item->SUBTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			),
			$a_merge2
		));
		
		$t_harga += $LIHATHARGA?$item->HARGA:0;
		$t_subtotal += $LIHATHARGA?$item->SUBTOTAL:0;
	}


	$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
	$this->html_table->set_td(array(
		array('align'=>'right', 'colspan'=>9, 'id'=>'tabelket', 'values'=>'Total'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($t_harga,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($t_subtotal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		array()
	));

} else if ($tampil=='REGISTERLOKASI') {
	$temp_kodetrans = ''; $kodelokasi = '';
	$urutan = 0;
	$g_subtotal = 0;
	foreach($query as $item) {
		$a_merge = array();
		$a_merge2 = array();
		
		if ($kodelokasi!=$item->NAMALOKASI) {
			if ($kodelokasi<>'') {
				$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
				$this->html_table->set_td(array(
					array('align'=>'right', 'colspan'=>8, 'id'=>'tabelket', 'values'=>'Total'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($t_harga,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($t_subtotal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array()
				));
				$this->html_table->line_break();
			}
			$t_subtotal = 0;
			$t_harga = 0;
			$urutan = 0;
			$temp_kodetrans = '';
			$kodelokasi = $item->NAMALOKASI;

			$this->html_table->set_tr();
			$this->html_table->set_td(array(
				array('colspan'=>12, 'id'=>'tabelket', 'values'=>'Lokasi : '.$item->NAMALOKASI.' - '.$item->KODELOKASI),
			));

			$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));			
			$this->html_table->set_th(array(
				array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>110, 'values'=>'No. Trans.'),
				// array('align'=>'center', 'id'=>'tabelket', 'width'=>110, 'values'=>'No. Opname'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Trans.'),	
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Kode Barang'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>200, 'values'=>'Nama Barang'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Jml Opname'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Selisih'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Satuan'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Harga'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Subtotal'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>250, 'values'=>'Catatan'),	
			));
		}
		if ($temp_kodetrans!=$item->KODEPENYESUAIANSTOK) {
		    $temp_kodetrans = $item->KODEPENYESUAIANSTOK;
			$urutan++;
			$warna = $urutan%2==0 ? '#cfcfcf' : '#FFFFFF';

			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'values'=>$urutan),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'values'=>$item->KODEPENYESUAIANSTOK),
				// array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'values'=>$item->KODEOPNAMESTOK),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'values'=>ubah_tgl_indo($item->TGLTRANS)),
				
			);
			
			
			$a_merge2 = array(
				array('valign'=>'top',  'align'=>'left', 'class'=>'det', 'values'=>$item->CATATAN),
			);
		}
		else
		{
			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'values'=>$urutan),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'values'=>$item->KODEPENYESUAIANSTOK),
				// array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'values'=>$item->KODEOPNAMESTOK),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'values'=>ubah_tgl_indo($item->TGLTRANS)),
				
			);
			
			
			$a_merge2 = array(
				array('valign'=>'top',  'align'=>'left', 'class'=>'det_kosong', 'values'=>$item->CATATAN),
			);
		}

		$t_subtotal += $LIHATHARGA?$item->SUBTOTAL:0;
		$t_harga += $LIHATHARGA?$item->HARGA:0;
		$g_subtotal += $LIHATHARGA?$item->SUBTOTAL:0;
		$g_harga += $LIHATHARGA?$item->HARGA:0;

		

		$this->html_table->set_tr(array('bgcolor'=>$warna));
		$this->html_table->set_td(array_merge(
			$a_merge,
			array(
				array('class'=>'det', 'valign'=>'top','align'=>'center', 'values'=>$item->KODEBARANG),
				array('class'=>'det', 'valign'=>'top','values'=>$item->NAMABARANG),
				array('class'=>'det', 'valign'=>'top','align'=>'right', 'values'=>number($item->JML,false,0)),
				array('class'=>'det', 'valign'=>'top','align'=>'right', 'values'=>number($item->SELISIH,false,0)),
				array('class'=>'det', 'valign'=>'top','align'=>'center', 'values'=>$item->SATUAN),
				array('class'=>'det', 'align'=>'right',  'values'=>$LIHATHARGA?number($item->HARGA,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('class'=>'det', 'align'=>'right',  'values'=>$LIHATHARGA?number($item->SUBTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X')
			),
			$a_merge2
		));
	};
	if($query != null)
	{
		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>8, 'id'=>'tabelket', 'values'=>'Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($t_harga,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($t_subtotal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array()
		));

		$this->html_table->line_break();

		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>8, 'id'=>'tabelket', 'values'=>'Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($g_harga,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($g_subtotal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array()
		));
	}

} else if ($tampil=='REGISTERBARANG') {
	$temp_kodetrans = ''; $temp_barang = '';
	$urutan = 0;
	foreach($query as $item) {
		if ($temp_barang!=$item->KODEBARANG) {
			if ($temp_barang!='') {
				
				$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
				$this->html_table->set_td(array(
					array('align'=>'right', 'colspan'=>7, 'id'=>'tabelket', 'values'=>'Total'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($t_harga,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($t_subtotal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array()
				));
		
				if ($_SESSION['LOKASIPUSAT'] == 1) {
					$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
					$this->html_table->set_td(array(
						array('align'=>'right', 'colspan'=>7, 'id'=>'tabelket', 'values'=>'Total'),
						array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($t_harga,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
						array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($t_subtotal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
						array()
					));
				}
				$this->html_table->line_break();
			}
		
			$t_subtotal = 0;
			$t_harga = 0; 
			$urutan = 0;
			$temp_kodetrans = '';
			$temp_barang = $item->KODEBARANG;

			$this->html_table->set_tr();
			$this->html_table->set_td(array(
				array('colspan'=>10, 'id'=>'tabelket', 'values'=>'Barang : '.$item->NAMABARANG.' - ('.$item->KODEBARANG.')'),
			));

			$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));			
			$this->html_table->set_th(array(
				array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>110, 'values'=>'No. Trans.'),
				// array('align'=>'center', 'id'=>'tabelket', 'width'=>110, 'values'=>'No. Opname'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Trans.'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Lokasi'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Jml Opname'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Selisih'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Satuan'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Harga'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Subtotal'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>250, 'values'=>'Catatan'),
			));
		}
		if ($temp_kodetrans!=$item->KODEPENYESUAIANSTOK) {
		    $temp_kodetrans = $item->KODEPENYESUAIANSTOK;
			$urutan++;
			$warna = $urutan%2==0 ? '#cfcfcf' : '#FFFFFF';

			$a_merge = array(
				array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'values'=>$urutan),
				array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'values'=>$item->KODEPENYESUAIANSTOK),
				// array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'values'=>$item->KODEOPNAMESTOK),
				array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'values'=>ubah_tgl_indo($item->TGLTRANS)),
				
				array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'values'=>$item->NAMALOKASI),
			);
		}

		$t_subtotal += $LIHATHARGA?$item->SUBTOTAL:0;
		$t_harga += $LIHATHARGA?$item->HARGA:0;
		$g_subtotal += $LIHATHARGA?$item->SUBTOTAL:0;
		$g_harga += $LIHATHARGA?$item->HARGA:0;

		$a_merge2 = array();
		$a_merge2 = array(
			array('valign'=>'top', 'align'=>'left', 'class'=>'det', 'values'=>$item->CATATAN),
		);

		$this->html_table->set_tr(array('bgcolor'=>$warna));
		$this->html_table->set_td(array_merge(
			$a_merge,
			array(
				array('class'=>'det', 'align'=>'right', 'values'=>number($item->JML,false,0)),
				array('class'=>'det', 'align'=>'right', 'values'=>number($item->SELISIH,false,0)),
				array('class'=>'det', 'align'=>'center', 'values'=>$item->SATUAN),
				array('class'=>'det', 'align'=>'right', 'valign'=>'top', 'values'=>$LIHATHARGA?number($item->HARGA,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('class'=>'det', 'align'=>'right', 'valign'=>'top', 'values'=>$LIHATHARGA?number($item->SUBTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X')
	
			),
			$a_merge2
		));

	}
	if($query != null)
	{
		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>7, 'id'=>'tabelket', 'values'=>'Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($t_harga,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($t_subtotal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array()
		));

		$this->html_table->line_break();

		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>7, 'id'=>'tabelket', 'values'=>'Grand Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($g_harga,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($g_subtotal,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array()
		));
	}
} else if ($tampil=='REKAP') {
	$this->html_table->set_hr(true);

	$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
	$this->html_table->set_th(array(
		array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Trans'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans'),
		//array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Referensi'),
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

		$this->html_table->set_tr();
		$this->html_table->set_td(array(
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($r->TGLTRANS)),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODETRANS),
			array('valign'=>'top', 'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->CATATAN),
		));

	}
}

echo $this->html_table->generate_table();
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
    echo '<strong class="HEADERPERIODE">'.$lokasi.'</strong>';
	echo '<br>';
   	echo '<strong class="HEADERPERIODE">Periode : '.$tglAw.' s/d '.$tglAk.'</strong>';
}
?>