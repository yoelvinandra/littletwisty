<?php
session_start();
if ($excel == 'ya'){
	include dirname(__FILE__)."/../export_to_excel.php";
}
if($errorMsg != ''){
	echo "<script>alert('$errorMsg'); window.close();</script>";
}

//echo $sql;
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
$query = $CI->db->query($sql)->result();
?>
<!DOCTYPE HTML>
<html>
	<head>
	<title> ..:: <?=$title?> ::.. </title>
	<style>
    .HEADER {
        font-family: Tahoma, Verdana, Geneva, sans-serif;
        font-weight: bold;
        font-size: 18px;
        color: #000;
        text-align:left;
    }
    .HEADERPERIODE {
        font-family: Tahoma, arial, sans-serif, Verdana, Geneva ;
        font-weight: bold;
        font-size: 16px;
        color: #000;
        text-align:left;
    }
    #tabelket{
        font-family:Tahoma, Verdana, Geneva, sans-serif;
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
	<div >
<?php

if ($tampil=='REGISTER') {
  	cetak_header($tampil,$title,$lokasi,$tgl_aw,$tgl_ak);
	$this->html_table->set_hr(true);

	$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
	$this->html_table->set_th(array(
		array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Trans'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Kirim'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'Kode Lokasi Asal'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'Nama Lokasi Asal'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'Kode Lokasi Tujuan'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'Nama Lokasi Tujuan'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Kode Barang'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>200, 'values'=>'Nama Barang'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'Satuan'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Jml'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Keterangan'),
	));

	$kodetrans = '';
	$total = array();
	$urutan = 0;
	$i = 0;
	foreach($query as $r) {
		$a_merge = array();
		$a_merge2 = array();
		if ($kodetrans!=$r->KODETRANS) {
		    $kodetrans = $r->KODETRANS;

			$urutan++;
			$urutan2 = 0;

			if ($r->STATUS == 'I') $warna2 = '#FFFFFF';
			else if ($r->STATUS == 'S') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_S'];
			else if ($r->STATUS == 'P') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'];
			else if ($r->STATUS == 'D') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'];

			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($r->TGLTRANS)),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($r->TGLKIRIM)),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODETRANS),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODELOKASIASAL),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->NAMALOKASIASAL),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODELOKASITUJUAN),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->NAMALOKASITUJUAN),
			);
			$a_merge2 = array(
				array('valign'=>'top',  'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->CATATAN),
			);
		}
		else
		{
			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
			);
			$a_merge2 = array(
				array('valign'=>'top',  'align'=>'left',   'class'=>'det_kosong', 'bgcolor'=>$warna2),
			);
		}
		$urutan2++;
		$warna = $urutan2%2==0 ? '#cfcfcf' : '#FFFFFF';
		if ($rs->TUTUP==1) $warna = '#CCCCCC';

		$this->html_table->set_tr();
		$this->html_table->set_td(array_merge(
			$a_merge,
			array(
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'center', 'values'=>$r->KODEBARANG),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'values'=>$r->NAMABARANG),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'center', 'values'=>$r->SATUAN),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),				
			), $a_merge2
		));

		$i++;
		$total['jml'] += $r->JML;
		$total['harga'] += $r->HARGA;
		$total['subtotal'] += $r->SUBTOTAL;
	}
    $this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
	$this->html_table->set_td(array(
		array('align'=>'right', 'colspan'=>11, 'id'=>'tabelket', 'values'=>'Total'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>number($total['jml'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
		array('align'=>'right',  'id'=>'tabelket', 'values'=>''),
	));

	echo $this->html_table->generate_table();
} else if ($tampil=='REGISTERBARANG') {
  	cetak_header($tampil,$title,$lokasi,$tgl_aw,$tgl_ak);

	$this->html_table->set_hr(true);

	$kodetrans = '';
	$koderef = '';
	$total = array();
	$urutan = 0;
	foreach($query as $r) {
		$a_merge = array();
		$a_merge2 = array();

		if ($koderef <> $r->KODEBARANG) {
			$urutan = 0;
			if ($koderef <> '') {
				$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
				$this->html_table->set_td(array(
					array('align'=>'right', 'colspan'=>9, 'id'=>'tabelket', 'values'=>'Total'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>number($total['jml'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>''),
				));

				$this->html_table->line_break();
			}

			$this->html_table->set_tr();
			$this->html_table->set_td(array(
				array('id'=>'tabelket', 'colspan'=>11, 'values'=>'Barang : '.$r->NAMABARANG.' - '.$r->KODEBARANG),
			));

			$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
			$this->html_table->set_th(array(
				array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Trans'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Kirim'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'Kode Lokasi Asal'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'Nama Lokasi Asal'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'Kode Lokasi Tujuan'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'Nama Lokasi Tujuan'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'Satuan'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Jml'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Keterangan'),
			));

			$koderef = $r->KODEBARANG;
			$total = array();
		}

		$kodetrans = $r->KODETRANS;
		$urutan++;
		$urutan2 = 0;
		if ($r->STATUS == 'I') $warna2 = '#FFFFFF';
		else if ($r->STATUS == 'S') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_S'];
		else if ($r->STATUS == 'P') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'];
		else if ($r->STATUS == 'D') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'];
		
		$a_merge = array(
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($r->TGLTRANS)),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($r->TGLKIRIM)),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODETRANS),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODELOKASIASAL),
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->NAMALOKASIASAL),
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODELOKASITUJUAN),
			array('valign'=>'top','align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->NAMALOKASITUJUAN),
		);
		$a_merge2 = array(
			array('valign'=>'top', 'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->CATATAN),
		);

		$urutan2++;
		$warna = $urutan2%2==0 ? '#cfcfcf' : '#FFFFFF';

		$total['jml']       += $r->JML;

		$gtotal['jml']       += $r->JML;

		
		if ($rs->TUTUP==1) $warna = '#CCCCCC';

		$this->html_table->set_tr();
		$this->html_table->set_td(array_merge(
			$a_merge,
			array(
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'center', 'values'=>$r->SATUAN),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->JML, true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			),
			$a_merge2
		));
	}
	if ($koderef <> '') {
		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>9, 'id'=>'tabelket', 'values'=>'Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>number($total['jml'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>''),
		));

		$this->html_table->line_break();

		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>9, 'id'=>'tabelket', 'values'=>'Grand Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>number($gtotal['jml'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>''),
		));
	}

	echo $this->html_table->generate_table();
} else if ($tampil=='REKAP') {
  	cetak_header($tampil,$title,$lokasi,$tgl_aw,$tgl_ak);
	$this->html_table->set_hr(true);

	$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
	$this->html_table->set_th(array(
		array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Trans'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Kirim'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans'),
	    array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'Kode Lokasi Asal'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'Nama Lokasi Asal'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'Kode Lokasi Tujuan'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'Nama Lokasi Tujuan'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>80, 'values'=>'Total Barang'),
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

		$this->html_table->set_tr();
		$this->html_table->set_td(array(
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($r->TGLTRANS)),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($r->TGLKIRIM)),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODETRANS),
			array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODELOKASIASAL),
			array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->NAMALOKASIASAL),
			array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODELOKASITUJUAN),
			array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->NAMALOKASITUJUAN),
			array('valign'=>'top', 'align'=>'center',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>number($r->QTY,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('valign'=>'top', 'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->CATATAN),
		));

	}

	$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
	$this->html_table->set_td(array(
		array('align'=>'right', 'id'=>'tabelket', 'colspan'=>8, 'values'=>'Grand Total'),
		array('align'=>'center', 'id'=>'tabelket', 'values'=>number($total['qty'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
		array(),
	));

	echo $this->html_table->generate_table();
}
?>
	</div>
</body>
</html>
<?php
function cetak_header($tampil,$Keterangan,$lokasi,$TglAw, $TglAk) {
    echo '<strong class="HEADER">'.$_SESSION[NAMAPROGRAM]['NAMAPERUSAHAAN'].'</strong><br>';
    echo '<strong class="HEADER">'.$Keterangan.'</strong>';
	echo '<br>';
    echo '<strong class="HEADERPERIODE">Lokasi : '.$lokasi.'</strong>';
	echo '<br>';
   	echo '<strong class="HEADERPERIODE">Periode : '.$TglAw.' s/d '.$TglAk.'</strong>';
}
?>