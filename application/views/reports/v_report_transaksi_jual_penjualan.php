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
if($tampil != 'HARIAN' && $tampil != 'HARIANTHERMAL'){
	$query = $CI->db->query($sql)->result();
}
?>
<!DOCTYPE HTML>
<html>
	<head>
	<title> ..:: <?=$title?> ::.. </title>
	<style>
    table{
	 border-collapse: collapse;
	}
	
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
	.header{
		 font-weight:bold;
		 background:#6caef5;
	}
	.footer{
		 font-weight:bold;
		 background:#b3e0ff;
	}
	.font-header-kecil{
		font-family: Tahoma, Verdana, Geneva, sans-serif;
		font-size:8pt;
		letter-spacing:1.5px;
	}
	.header_kecil{
		 font-weight:bold;
		 font-family: Tahoma, Verdana, Geneva, sans-serif;
		
	}
	.footer_kecil{
		 font-weight:bold;
		 font-family: Tahoma, Verdana, Geneva, sans-serif;
		
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
$HEADERFAKTURPAJAK = array("hidden"=>true);
$NOFAKTURPAJAK = array("hidden"=>true);

	
if ($tampil=='REGISTER') {
	
  	cetak_header($tampil,$title,$lokasi,$tgl_aw,$tgl_ak);
	$this->html_table->set_hr(true);

	$kodetrans = '';
    $tgltrans = '';
	$total = array();
	$urutan = 0;
	$i = 0;
	foreach($query as $r) {
		$a_merge = array();
		$a_merge2 = array();
		
		if ($tgltrans!=date('Y-m-d', strtotime($r->TGLTRANS))) {
		    $urutan = 0;
		    
		    if($tgltrans != "")
    		{		    
    	        $this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
            	$this->html_table->set_td(array(
            		array('align'=>'right', 'colspan'=> 3, 'id'=>'tabelket', 'values'=>'Total'),
            		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['totalharian'],true,0):'X'),
            		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['potonganharian'],true,0):'X'),	
            		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['dpplainharian'],true,0):'X'),	
            		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['ppnharian'],true,0):'X'),	
            		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['grandtotaldiskonharian'],true,0):'X'),
            		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pembayaranharian'],true,0):'X'),
            		array('align'=>'right', 'colspan'=>6, 'id'=>'tabelket', 'values'=>''),
            	));
    	        $total['totalharian'] = 0;
    	        $total['potonganharian'] = 0;
    	        $total['dpplainharian'] = 0;
    	        $total['ppnharian'] = 0;
    	        $total['grandtotaldiskonharian'] = 0;
    	        $total['pembayaranharian'] = 0;
		    }
	        
		    $tgltrans = date('Y-m-d', strtotime($r->TGLTRANS));
	        
		    $dataHari = [
    		    "Sunday"    => "MINGGU",   
    		    "Monday"    => "SENIN",   
    		    "Tuesday"   => "SELASA",   
    		    "Wednesday" => "RABU",     
    		    "Thursday"  => "KAMIS",    
    		    "Friday"    => "JUMAT",   
    		    "Saturday"  => "SABTU",      
    		];
    		
    		$hari = $dataHari[date('l', strtotime($tgltrans))];
    		
	        $this->html_table->set_tr(array('bgcolor'=>'yellow'));
        	$this->html_table->set_th(array(
        		array('align'=>'left', 'id'=>'tabelket', 'colspan'=>14,  'values'=>"HARI ".$hari." - ".$tgltrans),	
        	));
		    
		    $this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
        	$this->html_table->set_th(array(
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>60, 'values'=>'J. Trans'),
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>120, 'values'=>'No. Trans'),
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Total ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Potongan ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'DPP Lain ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'PPN ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
        	    array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Grand Total * ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Pembayaran ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Customer'),
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>200, 'values'=>'Nama Barang'),
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Jml'),
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Harga ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Subtotal ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>70, 'values'=>'Keterangan'),
        	));
		}
		
		if ($kodetrans!=$r->KODETRANS) {
		    $kodetrans = $r->KODETRANS;

			$urutan++;
			$urutan2 = 0;
			if ($r->STATUS == 'I' || $r->STATUS == 1) $warna2 = '#FFFFFF';
			else if ($r->STATUS == 'S' || $r->STATUS == 2) $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_S'];
			else if ($r->STATUS == 'P' || ($r->STATUS == 3  && ($r->STATUSMARKETPLACE == 'COMPLETED' || $r->STATUSMARKETPLACE == 'CONFIRMED'))) $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'];
			else if ($r->STATUS == 'D'|| ($r->STATUS == 3 && ($r->STATUSMARKETPLACE == 'CANCELLED' || $r->STATUSMARKETPLACE == 'CANCELED')) || $r->STATUS == 4) $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'];


				$a_merge = array(
					array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
					array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->JENISTRANSAKSI),
					array('valign'=>'top',  'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODETRANS),
					array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->TOTAL,true,0):'X'),
					array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->POTONGANRP,true,0):'X'),
					array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->DPPLAINRP,true,0):'X'),
					array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PPNRP,true,0):'X'),
				    array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->GRANDTOTALDISKON,true,0):'X'),
					array('valign'=>'top',  'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PEMBAYARAN,true,0):'X'),
					array('valign'=>'top',  'class'=>'det',     'bgcolor'=>$warna2, 'values'=>$r->NAMACUSTOMER."<br>".$r->KOTA."<br>".$r->CATATANCUSTOMER),
				);
				$a_merge2 = array(
					array('valign'=>'top',  'align'=>'center',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->CATATAN),
				);
            $total['totalharian'] += $LIHATHARGA?$r->TOTAL:0;
            $total['potonganharian'] += $LIHATHARGA?$r->POTONGANRP:0;
    	    $total['dpplainharian'] +=  $LIHATHARGA?$r->DPPLAINRP:0;
    	    $total['ppnharian'] +=  $LIHATHARGA?$r->PPNRP:0;
			$total['grandtotaldiskonharian'] += $LIHATHARGA?$r->GRANDTOTALDISKON:0;
            $total['pembayaranharian'] += $LIHATHARGA?$r->PEMBAYARAN:0;
			$total['total'] += $LIHATHARGA?$r->GRANDTOTAL:0;
			$total['potongan'] += $LIHATHARGA?$r->POTONGANRP:0;
			$total['dpplain'] +=  $LIHATHARGA?$r->DPPLAINRP:0;
    	    $total['ppn'] +=  $LIHATHARGA?$r->PPNRP:0;
			$total['grandtotaldiskon'] += $LIHATHARGA?$r->GRANDTOTALDISKON:0;
			$total['pembayaran'] += $LIHATHARGA?$r->PEMBAYARAN:0;
		}
		else
		{
			
				$a_merge = array(
					array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
					array('valign'=>'top',  'align'=>'center', 'class'=>'det_kosong', 'bgcolor'=>$warna2),
					array('valign'=>'top',  'align'=>'left',   'class'=>'det_kosong',   'bgcolor'=>$warna2),
					array('valign'=>'top',  'align'=>'left',   'class'=>'det_kosong',   'bgcolor'=>$warna2),
					array('valign'=>'top',  'align'=>'left',   'class'=>'det_kosong',   'bgcolor'=>$warna2),
					array('valign'=>'top',  'align'=>'left',   'class'=>'det_kosong', 'bgcolor'=>$warna2),
					array('valign'=>'top',  'align'=>'left',   'class'=>'det_kosong', 'bgcolor'=>$warna2),
					array('valign'=>'top',  'align'=>'left',   'class'=>'det_kosong', 'bgcolor'=>$warna2),
					array('valign'=>'top',  'align'=>'left',   'class'=>'det_kosong', 'bgcolor'=>$warna2),
					array('valign'=>'top',  'align'=>'left',   'class'=>'det_kosong', 'bgcolor'=>$warna2),
				);
				$a_merge2 = array(
					array('valign'=>'top',  'align'=>'left',   'class'=>'det_kosong', 'bgcolor'=>$warna2),
				);
		}
		$urutan2++;
		$warna = $urutan2%2==0 ? '#cfcfcf' : '#FFFFFF';

		$diskon = $LIHATHARGA?$r->DISC:0;

		if ($rs->TUTUP==1) $warna = '#CCCCCC';

		$KetDisc = '';
		$KetDisc = $LIHATHARGA?$r->DISCPERSEN:'0';
		
		if($r->NAMABARANG == "ONGKIR")
		{
		    $warna = 'orange';
		}

		$this->html_table->set_tr();
		$this->html_table->set_td(array_merge(
			$a_merge,
			array(
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'values'=>$r->NAMABARANG),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'center', 'values'=>number($r->JML, true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($r->HARGAKURS,true,0):'X'),
				array('valign'=>'top','class'=>'det', 'bgcolor'=>$warna, 'align'=>'right', 'values'=>$LIHATHARGA?number($r->SUBTOTALKURS,true,0):'X'),
			),
			$a_merge2
		));

		$i++;
	}
	
	$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
    $this->html_table->set_td(array(
    	array('align'=>'right', 'colspan'=> 3, 'id'=>'tabelket', 'values'=>'Total'),
    	array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['totalharian'],true,0):'X'),
        array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['potonganharian'],true,0):'X'),
        array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['dpplainharian'],true,0):'X'),
        array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['ppnharian'],true,0):'X'),
        array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['grandtotaldiskonharian'],true,0):'X'),
        array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pembayaranharian'],true,0):'X'),
    	array('align'=>'right', 'colspan'=>6, 'id'=>'tabelket', 'values'=>''),
    ));
            	
    $this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
	$this->html_table->set_td(array(
		array('align'=>'right', 'colspan'=> 3, 'id'=>'tabelket', 'values'=>'Grand Total'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['total'],true,0):'X'),
        array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['potongan'],true,0):'X'),
        array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['dpplain'],true,0):'X'),
        array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['ppn'],true,0):'X'),
        array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['grandtotaldiskon'],true,0):'X'),
        array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pembayaran'],true,0):'X'),
		array('align'=>'right', 'colspan'=>6, 'id'=>'tabelket', 'values'=>''),
	));

	echo $this->html_table->generate_table();
} else if ($tampil=='REKAP') {
  	cetak_header($tampil,$title,$lokasi,$tgl_aw,$tgl_ak);
	
	
	$SPACEFOOTER = 5;
	
	$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
	$this->html_table->set_th(array(
		array('align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Hari'),	
		array('align'=>'center', 'id'=>'tabelket', 'width'=>80,  'values'=>'Tgl. Trans'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'J. Trans'),	
		array('align'=>'center', 'id'=>'tabelket', 'width'=>120,  'values'=>'No. Trans'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Total Barang'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Total ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Potongan ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'DPP Lain ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'PPN ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Grand Total * ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>100,  'values'=>'Pembayaran ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		array('align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Customer'),	
		array('align'=>'center', 'id'=>'tabelket', 'width'=>70, 'values'=>'Keterangan'),
	));
	

	$kodetrans = '';
	$total = array();
	$urutan = 0;
	foreach($query as $r) {
		$urutan++;
		$warna = $urutan%2==0 ? '#cfcfcf' : '#FFFFFF';
		
	
		if ($r->STATUS == 'I' || $r->STATUS == 1) $warna2 = '#FFFFFF';
		else if ($r->STATUS == 'S' || $r->STATUS == 2) $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_S'];
		else if ($r->STATUS == 'P' || ($r->STATUS == 3  && ($r->STATUSMARKETPLACE == 'COMPLETED' || $r->STATUSMARKETPLACE == 'CONFIRMED'))) $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'];
		else if ($r->STATUS == 'D'|| ($r->STATUS == 3 && ($r->STATUSMARKETPLACE == 'CANCELLED' || $r->STATUSMARKETPLACE == 'CANCELED')) || $r->STATUS == 4) $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'];

		$tgltrans = $r->TGLTRANS;
	    
	    $dataHari = [
    	    "Sunday"    => "MINGGU",   
    	    "Monday"    => "SENIN",   
    	    "Tuesday"   => "SELASA",   
    	    "Wednesday" => "RABU",     
    	    "Thursday"  => "KAMIS",    
    	    "Friday"    => "JUMAT",   
    	    "Saturday"  => "SABTU",      
    	];
    	
    	$hari = $dataHari[date('l', strtotime($tgltrans))];
		$total['qty'] += $r->QTY;
		$total['total'] += $LIHATHARGA?$r->TOTAL:0;
		$total['potonganrp'] += $LIHATHARGA?$r->POTONGANRP:0;
		$total['dpplainrp'] += $LIHATHARGA?$r->DPPLAINRP:0;
		$total['ppnrp'] += $LIHATHARGA?$r->PPNRP:0;
		$total['grandtotaldiskon'] += $LIHATHARGA?$r->GRANDTOTALDISKON:0;
		$total['pembayaran'] += $LIHATHARGA?$r->PEMBAYARAN:0;
		
		$this->html_table->set_tr();
		$this->html_table->set_td(array(
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$hari),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$tgltrans),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->JENISTRANSAKSI),
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->KODETRANS),
			array('valign'=>'top', 'align'=>'center',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>number($r->QTY,true,0)),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->TOTAL,true,0):'X'),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->POTONGANRP,true,0):'X'),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->DPPLAINRP,true,0):'X'),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PPNRP,true,0):'X'),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->GRANDTOTALDISKON,true,0):'X'),
			array('valign'=>'top', 'align'=>'right',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->PEMBAYARAN,true,0):'X'),
			array('valign'=>'top', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->NAMACUSTOMER."<br>".$r->KOTA."<br>".$r->CATATANCUSTOMER),
			array('valign'=>'top', 'align'=>'center',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->CATATAN),
		));
	}

	$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
	$this->html_table->set_td(array(
		array('align'=>'right', 'id'=>'tabelket', 'colspan'=>5, 'values'=>'Grand Total'),
		array('align'=>'center', 'id'=>'tabelket', 'values'=> number($total['qty'],true,0)),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['total'],true,0):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['potonganrp'],true,0):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['dpplainrp'],true,0):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['ppnrp'],true,0):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['grandtotaldiskon'],true,0):'X'),
		array('align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?number($total['pembayaran'],true,0):'X'),
		array(),
		array(),
	));

	echo $this->html_table->generate_table();
} else if ($tampil=='RINCIAN') {
  	cetak_header($tampil,$title,$lokasi,$tgl_aw,$tgl_ak);

	$tgltrans = '';
	$urutan = 0;
	foreach($query as $r) {
	    
	    if($tgltrans != date('Y-m-d', strtotime($r->TGLTRANS)))
	    {
	        $urutan = 0;
	        $tgltrans = date('Y-m-d', strtotime($r->TGLTRANS));
	        
	        $dataHari = [
    		    "Sunday"    => "MINGGU",   
    		    "Monday"    => "SENIN",   
    		    "Tuesday"   => "SELASA",   
    		    "Wednesday" => "RABU",     
    		    "Thursday"  => "KAMIS",    
    		    "Friday"    => "JUMAT",   
    		    "Saturday"  => "SABTU",      
    		];
    		
    		$hari = $dataHari[date('l', strtotime($tgltrans))];
	        
	        $this->html_table->set_tr(array('bgcolor'=>'yellow'));
        	$this->html_table->set_th(array(
        		array('align'=>'left', 'id'=>'tabelket', 'colspan'=>3,  'values'=>"HARI ".$hari." - ".$tgltrans),	
        	));
        	
	        $this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
        	$this->html_table->set_th(array(
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>20,  'values'=>'No'),	
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>200,  'values'=>'Produk'),		
        		array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Jumlah'),
        	));
	    }
		$urutan++;
		$warna = $urutan%2==0 ? '#FFFFCC' : '#FFFFFF';
		
		$this->html_table->set_tr();
		$this->html_table->set_td(array(
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
			array('valign'=>'top', 'align'=>'left', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->NAMABARANG),
			array('valign'=>'top', 'align'=>'center',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($r->JML,true,0):'X'),
		));
	}
	
	$this->html_table->set_tr(array('bgcolor'=>'orange'));
    $this->html_table->set_th(array(
    	array('align'=>'center', 'id'=>'tabelket', 'colspan'=>3,  'values'=>"TOTAL KESELURUHAN"),	
    ));
	
	$urutan = 0;
	$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
    $this->html_table->set_th(array(
    	array('align'=>'center', 'id'=>'tabelket', 'width'=>20,  'values'=>'No'),	
    	array('align'=>'center', 'id'=>'tabelket', 'width'=>200,  'values'=>'Produk'),		
    	array('align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Jumlah'),
    ));
    
    $query = $CI->db->query($sqlTotal)->result();
    
    foreach($query as $item){
        $urutan++;
		
		$this->html_table->set_tr();
		$this->html_table->set_td(array(
			array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$urutan),
			array('valign'=>'top', 'align'=>'left', 'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$item->NAMABARANG),
			array('valign'=>'top', 'align'=>'center',  'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$LIHATHARGA?number($item->JML,true,0):'X'),
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
	echo '<br><br>';

}
?>