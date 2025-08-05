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
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>150, 'values'=>'Supplier'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Total ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'PPN ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'PPH ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Pembulatan ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Grand Total ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		//array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Pembulatan ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Kode Barang'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>200, 'values'=>'Nama Barang'),
		//array('align'=>'center', 'id'=>'tabelket', 'width'=>200, 'values'=>'Tipe'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'Satuan'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Jml'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Terpenuhi'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Sisa'),
		//array('align'=>'center', 'id'=>'tabelket', 'width'=>40,  'values'=>'Curr'),
		//array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Harga'),
		//array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Kurs ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Harga ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Disc (%)'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Disc ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Subtotal ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
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
	    	else if ($r->STATUS == 'C') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_C'];
			else if ($r->STATUS == 'P') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'];
			else if ($r->STATUS == 'D') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'];

			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($r->TGLTRANS)),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODETRANS),
				array('valign'=>'top',  'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->NAMASUPPLIER),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->TOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PPNRP,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PPH22RP,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PEMBULATAN,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->GRANDTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				//array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>number($r->PEMBULATAN,true,0)),
			);
			$a_merge2 = array(
				//array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->GRANDTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top',  'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->CATATAN),
			);
			$total['subtotal']   += $LIHATHARGA?$r->SUBTOTALKURS:0;
			$total['total']      += $LIHATHARGA?$r->TOTAL:0;
			$total['ppn']        += $LIHATHARGA?$r->PPNRP:0;
			$total['pph']        += $LIHATHARGA?$r->PPH22RP:0;
			$total['pembulatan'] += $LIHATHARGA?$r->PEMBULATAN:0;
			$total['grandtotal'] += $LIHATHARGA?$r->GRANDTOTAL:0;
		}
		else
		{
			if ($r->STATUS == 'I') $warna2 = '#FFFFFF';
			else if ($r->STATUS == 'S') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_S'];
			else if ($r->STATUS == 'C') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_C'];
			else if ($r->STATUS == 'P') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'];
			else if ($r->STATUS == 'D') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'];

			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'left',   'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det_kosong', 'bgcolor'=>$warna2),
				//array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>number($r->PEMBULATAN,true,0)),
			);
			$a_merge2 = array(
				//array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->GRANDTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top',  'align'=>'left',   'class'=>'det_kosong', 'bgcolor'=>$warna2),
			);
		}
		$urutan2++;
		$warna = $urutan2%2==0 ? '#cfcfcf' : '#FFFFFF';

		$diskon = $LIHATHARGA?$r->DISC:0;

		if ($rs->TUTUP==1) $warna = '#CCCCCC';

		$KetDisc = '';
		$KetDisc = $LIHATHARGA?$r->DISCPERSEN:'0';

		$this->html_table->set_tr();
		$this->html_table->set_td(array_merge(
			$a_merge,
			array(
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'center', 'values'=>$r->KODEBARANG),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'values'=>$r->NAMABARANG),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'center', 'values'=>$r->SATUAN),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->TERPENUHI,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->SISA,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'center', 'values'=>$r->SIMBOL),
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($r->HARGA,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->NILAIKURS)),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($r->HARGAKURS,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($KetDisc,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($diskon,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($r->SUBTOTALKURS,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),				
			), $a_merge2
		));

		$i++;
	}
    $this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
	$this->html_table->set_td(array(
		array('align'=>'right', 'colspan'=>4, 'id'=>'tabelket', 'values'=>'Grand Total'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['total'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['ppn'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pph'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pembulatan'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['grandtotal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		array('align'=>'right', 'colspan'=>11, 'id'=>'tabelket', 'values'=>''),
	));

	echo $this->html_table->generate_table();
} else if ($tampil=='REGISTERSUPPLIER') {
  	cetak_header($tampil,$title,$lokasi,$tgl_aw,$tgl_ak);
	$this->html_table->set_hr(true);

	$kodetrans = '';
	$koderef = '';
	$total = array();
	$urutan = 0;
	foreach($query as $r) {
		$a_merge = array();
		$a_merge2 = array();

		if ($koderef <> $r->KODESUPPLIER) {
			$urutan = 0;
			if ($koderef <> '') {
				$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
				$this->html_table->set_td(array(
					array('align'=>'right', 'colspan'=>4, 'id'=>'tabelket', 'values'=>'Total'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['total'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['ppn'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pph'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pembulatan'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['grandtotal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'colspan'=>11, 'id'=>'tabelket', 'values'=>''),
				));

				$this->html_table->line_break();
			}

			$this->html_table->set_tr();
			$this->html_table->set_td(array(
				array('id'=>'tabelket', 'colspan'=>20, 'values'=>'Supplier : '.$r->NAMASUPPLIER.' - '.$r->KODESUPPLIER),
			));

			$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
			$this->html_table->set_th(array(
				array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Trans'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Beli'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>80, 'values'=>'Total ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				//array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Pembulatan ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'PPN ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'PPH ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Pembulatan ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Grand Total ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Kode Barang'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>200, 'values'=>'Nama Barang'),
				//array('align'=>'center', 'id'=>'tabelket', 'width'=>200, 'values'=>'Tipe'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'Satuan'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Jml'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Terpenuhi'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Sisa'),
				//array('align'=>'center', 'id'=>'tabelket', 'width'=>40,  'values'=>'Curr'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Harga'),
				//array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Kurs ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				//array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Harga ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Disc (%)'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Disc ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Subtotal ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Keterangan'),
			));

			$koderef = $r->KODESUPPLIER;
			$total = array();
		}

		if ($kodetrans!=$r->KODETRANS) {
		    $kodetrans = $r->KODETRANS;
			$urutan++;
			$urutan2 = 0;
			if ($r->STATUS == 'I') $warna2 = '#FFFFFF';
			else if ($r->STATUS == 'S') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_S'];
			else if ($r->STATUS == 'C') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_C'];
			else if ($r->STATUS == 'P') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'];
			else if ($r->STATUS == 'D') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'];

			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($r->TGLTRANS)),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODETRANS),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODEBELI),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->TOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PPNRP,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PPH22RP,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PEMBULATAN,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->GRANDTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			);
			$a_merge2 = array(
				array('valign'=>'top',  'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->CATATAN),
			);

		}
		else
		{
			if ($r->STATUS == 'I') $warna2 = '#FFFFFF';
			else if ($r->STATUS == 'S') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_S'];
			else if ($r->STATUS == 'P') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'];
			else if ($r->STATUS == 'D') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'];

			$a_merge = array(
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det_kosong', 'bgcolor'=>$warna2),
				array('valign'=>'top',  'align'=>'right',  'class'=>'det_kosong', 'bgcolor'=>$warna2),
			);                                                                                                      
			$a_merge2 = array(                                                                                      
				array('valign'=>'top',  'align'=>'left',   'class'=>'det_kosong', 'bgcolor'=>$warna2),
			);
		}
		$urutan2++;
		$warna = $urutan2%2==0 ? '#cfcfcf' : '#FFFFFF';

		$diskon = $LIHATHARGA?$r->DISC:0;

		$total['subtotal']   += $LIHATHARGA?$r->SUBTOTALKURS:0;
		$total['total']      += $LIHATHARGA?$r->TOTAL:0;
		$total['ppn']        += $LIHATHARGA?$r->PPNRP:0;
		$total['pph']        += $LIHATHARGA?$r->PPH22RP:0;
		$total['pembulatan']        += $LIHATHARGA?$r->PEMBULATAN:0;
		$total['grandtotal'] += $LIHATHARGA?$r->GRANDTOTAL:0;

		$gtotal['subtotal']   += $LIHATHARGA?$r->SUBTOTALKURS:0;
		$gtotal['total']      += $LIHATHARGA?$r->TOTAL:0;
		$gtotal['ppn']        += $LIHATHARGA?$r->PPNRP:0;
		$gtotal['pph']        += $LIHATHARGA?$r->PPH22RP:0;
		$gtotal['pembulatan']        += $LIHATHARGA?$r->PEMBULATAN:0;
		$gtotal['grandtotal'] += $LIHATHARGA?$r->GRANDTOTAL:0;

		if ($rs->TUTUP==1) $warna = '#CCCCCC';

		$KetDisc = '';
		$KetDisc = $LIHATHARGA?$r->DISCPERSEN:'0';

		$this->html_table->set_tr();
		$this->html_table->set_td(array_merge(
			$a_merge,
			array(
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'center', 'values'=>$r->KODEBARANG),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'values'=>$r->NAMABARANG),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'center', 'values'=>$r->SATUAN),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->JML, true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->TERPENUHI,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->SISA,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'center', 'values'=>$r->SIMBOL),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($r->HARGA,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->NILAIKURS)),
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($r->HARGAKURS,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($KetDisc,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($diskon,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($r->SUBTOTALKURS,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($r->PPNRP,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($LIHATHARGA?$r->SUBTOTALKURS:0,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']2,true,0)),
			),
			$a_merge2
		));
	}
	if ($koderef <> '') {
		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>4, 'id'=>'tabelket', 'values'=>'Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['total'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['ppn'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pph'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pembulatan'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['grandtotal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'colspan'=>11, 'id'=>'tabelket', 'values'=>''),
		));

		$this->html_table->line_break();

		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>4, 'id'=>'tabelket', 'values'=>'Grand Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($gtotal['total'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($gtotal['ppn'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($gtotal['pph'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($gtotal['pembulatan'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($gtotal['grandtotal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'colspan'=>11, 'id'=>'tabelket', 'values'=>''),
		));
	}

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
					array('align'=>'right', 'colspan'=>11, 'id'=>'tabelket', 'values'=>'Total'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>number($total['jml'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>number($total['terpenuhi'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>number($total['sisa'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
					array('align'=>'right', 'colspan'=>3, 'id'=>'tabelket', 'values'=>''),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['subtotal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>''),
				));

				$this->html_table->line_break();
			}

			$this->html_table->set_tr();
			$this->html_table->set_td(array(
				array('id'=>'tabelket', 'colspan'=>19, 'values'=>'Barang : '.$r->NAMABARANG.' - '.$r->KODEBARANG),
			));

			$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
			$this->html_table->set_th(array(
				array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Trans'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Beli'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>150, 'values'=>'Supplier'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Total ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'PPN ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'PPH ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>70, 'values'=>'Pembulatan ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Grand Total ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'Satuan'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Jml'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Terpenuhi'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Sisa'),
				//array('align'=>'center', 'id'=>'tabelket', 'width'=>40,  'values'=>'Curr'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Harga'),
				//array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Kurs ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				//array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Harga ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Disc (%)'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Disc ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Subtotal ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
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
		else if ($r->STATUS == 'C') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_C'];
		else if ($r->STATUS == 'P') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'];
		else if ($r->STATUS == 'D') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'];
		
		$a_merge = array(
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($r->TGLTRANS)),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODETRANS),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODEBELI),
			array('valign'=>'top', 'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->NAMASUPPLIER),
			array('valign'=>'top', 'align'=>'right',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->TOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'right',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PPNRP,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'right',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PPH22RP,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'right',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PEMBULATAN,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'right',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->GRANDTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		);
		$a_merge2 = array(
			array('valign'=>'top', 'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->CATATAN),
		);

		$urutan2++;
		$warna = $urutan2%2==0 ? '#cfcfcf' : '#FFFFFF';

		$diskon = $LIHATHARGA?$r->DISC:0;

		$total['jml']       += $r->JML;
		$total['terpenuhi'] += $r->TERPENUHI;
		$total['sisa']      += $r->SISA;
		$total['subtotal']  += $LIHATHARGA?$r->SUBTOTALKURS:0;

		$gtotal['jml']       += $r->JML;
		$gtotal['terpenuhi'] += $r->TERPENUHI;
		$gtotal['sisa']      += $r->SISA;
		$gtotal['subtotal']  += $LIHATHARGA?$r->SUBTOTALKURS:0;

		if ($rs->TUTUP==1) $warna = '#CCCCCC';

		$KetDisc = '';
		$KetDisc = $LIHATHARGA?$r->DISCPERSEN:'0';
		
		$this->html_table->set_tr();
		$this->html_table->set_td(array_merge(
			$a_merge,
			array(
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'center', 'values'=>$r->KODEBARANG),
				//array('class'=>'det', 'bgcolor'=>$warna, 'values'=>$r->NAMABARANG),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'center', 'values'=>$r->SATUAN),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->JML, true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->TERPENUHI, true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->SISA, true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'center', 'values'=>$r->SIMBOL),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($r->HARGA,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($r->NILAIKURS)),
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($r->HARGAKURS,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($KetDisc,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($diskon,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($r->SUBTOTALKURS,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($r->PPNRP,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
				//array('class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>number($LIHATHARGA?$r->SUBTOTALKURS:0,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']2,true,0)),
			),
			$a_merge2
		));
	}
	if ($koderef <> '') {
		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>11, 'id'=>'tabelket', 'values'=>'Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>number($total['jml'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>number($total['terpenuhi'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>number($total['sisa'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('align'=>'right', 'colspan'=>3, 'id'=>'tabelket', 'values'=>''),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['subtotal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>''),
		));

		$this->html_table->line_break();

		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'colspan'=>11, 'id'=>'tabelket', 'values'=>'Grand Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>number($gtotal['jml'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>number($gtotal['terpenuhi'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>number($gtotal['sisa'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('align'=>'right', 'colspan'=>3, 'id'=>'tabelket', 'values'=>''),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($gtotal['subtotal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
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
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans'),
		//array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Referensi'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>200, 'values'=>'Supplier'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>80, 'values'=>'Total Barang'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Total ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'PPN ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'PPH ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Pembulatan ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
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
		$total['total'] += $LIHATHARGA?$r->TOTAL:0;
		$total['ppn']   += $LIHATHARGA?$r->PPNRP:0;
		$total['pph']   += $LIHATHARGA?$r->PPH22RP:0;
		$total['pembulatan']   += $LIHATHARGA?$r->PEMBULATAN:0;
		$total['grandtotal'] += $LIHATHARGA?$r->GRANDTOTAL:0;

		$this->html_table->set_tr();
		$this->html_table->set_td(array(
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($r->TGLTRANS)),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODETRANS),
			//array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODEREF),
			array('valign'=>'top', 'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->NAMASUPPLIER),
			array('valign'=>'top', 'align'=>'center',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>number($r->QTY,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->TOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PPNRP,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PPH22RP,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PEMBULATAN,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->GRANDTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->CATATAN),
		));

	}

	$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
	$this->html_table->set_td(array(
		array('align'=>'right', 'id'=>'tabelket', 'colspan'=>4, 'values'=>'Grand Total'),
		array('align'=>'center', 'id'=>'tabelket', 'values'=>number($total['qty'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['total'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['ppn'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pph'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pembulatan'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['grandtotal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
		array(),
	));

	echo $this->html_table->generate_table();
} else if ($tampil=='REKAPSUPPLIER') {
  	cetak_header($tampil,$title,$lokasi,$tgl_aw,$tgl_ak);
	$this->html_table->set_hr(true);

	$referensi = '';
	$total = array();
	$gtotal = array();
	$urutan = 0;
	foreach($query as $r) {
		$urutan++;

		if ($referensi <> $r->KODESUPPLIER) {
			if ($referensi <> '') {
				$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
				$this->html_table->set_td(array(
					array('align'=>'right', 'id'=>'tabelket', 'colspan'=>3, 'values'=>'Total'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['total'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['ppn'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pph'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pembulatan'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['grandtotal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
					array(),
				));

				$this->html_table->line_break();

				$total = array();
			}

			$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
			$this->html_table->set_td(array(
				array('id'=>'tabelket', 'colspan'=>9, 'values'=>'Nama Supplier : '.$r->NAMASUPPLIER.' - '.$r->KODESUPPLIER),
			));

			$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
			$this->html_table->set_th(array(
				array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Tgl. Trans'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans'),
				//array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Referensi'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Total ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'PPN ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'PPH ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Pembulatan ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Grand Total ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Keterangan'),
			));

			$referensi = $r->KODESUPPLIER;
			$urutan = 1;
		}

		$warna = $urutan%2==0 ? '#cfcfcf' : '#FFFFFF';

		if ($r->STATUS == 'I') $warna2 = '#FFFFFF';
		else if ($r->STATUS == 'S') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_S'];
		else if ($r->STATUS == 'C') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_C'];
		else if ($r->STATUS == 'P') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'];
		else if ($r->STATUS == 'D') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'];

		$total['total'] += $LIHATHARGA?$r->TOTAL:0;
		$total['ppn'] += $LIHATHARGA?$r->PPNRP:0;
		$total['pph'] += $LIHATHARGA?$r->PPH22RP:0;
		$total['pembulatan'] += $LIHATHARGA?$r->PEMBULATAN:0;
		$total['grandtotal'] += $LIHATHARGA?$r->GRANDTOTAL:0;

		$gtotal['total'] += $LIHATHARGA?$r->TOTAL:0;
		$gtotal['ppn'] += $LIHATHARGA?$r->PPNRP:0;
		$gtotal['pph'] += $LIHATHARGA?$r->PPH22RP:0;
		$gtotal['pembulatan'] += $LIHATHARGA?$r->PEMBULATAN:0;
		$gtotal['grandtotal'] += $LIHATHARGA?$r->GRANDTOTAL:0;

		$this->html_table->set_tr();

		$this->html_table->set_td(array(
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>ubah_tgl_indo($r->TGLTRANS)),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODETRANS),
			//array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODEREF),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->TOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PPNRP,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PPH22RP,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PEMBULATAN,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->GRANDTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('valign'=>'top', 'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->CATATAN),
		));

	}

	if ($referensi <> '') {
		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'id'=>'tabelket', 'colspan'=>3, 'values'=>'Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['total'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['ppn'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pph'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pembulatan'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['grandtotal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array(),
		));

		$this->html_table->line_break();

		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('align'=>'right', 'id'=>'tabelket', 'colspan'=>3, 'values'=>'Grand Total'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($gtotal['total'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($gtotal['ppn'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($gtotal['pph'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($gtotal['pembulatan'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($gtotal['grandtotal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array(),
		));
	}
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