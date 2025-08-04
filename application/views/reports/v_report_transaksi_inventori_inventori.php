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

$query  = $CI->db->query("select namalokasi from mlokasi d where 1=1 $whereLokasi and idperusahaan = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} '")->result();
$lokasi = '';
foreach($query as $item) {
	if ($lokasi=='') {
		$lokasi .= $item->NAMALOKASI;
	} else {
		$lokasi .= ', '.$item->NAMALOKASI;
	}	
}

$query = $CI->db->query($sql)->result();

?>
<!DOCTYPE HTML>
<html>
<head>
<?php
	echo "<title> ..:: Laporan Inventori  ::.. </title>";

?>
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
    .det{
        font-family:Tahoma, Verdana, Geneva, sans-serif;
        font-size:10px;
        padding: 2px;
		vertical-align:text-top;
		
    }
    </style>
</head>
<body>
<?php
$kodebarang   	= '';
$CetakFooter    = 0;
$TotalPiutang   = 0;
$TotalPelunasan = 0;
$TotalTolakan   = 0;
$TotalSaldoNota = 0;
$TotalGiro      = 0;
$TotalSaldoBuku = 0;

if ($tampil=='kategori'){ // MENAMPILKAN DATA POSISI STOK
  	cetakHeader($tampil,'Laporan Inventori Berdasarkan Produk',$lokasi,$tglAw,$tglAk);
	
	
	$tbl = new html_table();
	$tbl->set_hr(true);
	$countIndex = 1;
	$header = array('Saldo Awal', 'Beli', 'Jual', 'Retur Beli', 'Terima Transfer','Transfer','Adjustment','Saldo Akhir');		
	
	foreach ($header as $head) {
		if ($i==1) $color = '#99FF99';

		if ($i==2) $color = '#FFFF99';

		if ($i==3) {
			$color = '#9CD0ED';
			$i = 0;
		}
		
		if($countIndex == count($header))
		{
			$color = '#FF9E79';
		}
		
		$countIndex++;

		$i++;

		$th[] = array('align'=>'center', 'id'=>'tabelket', 'width'=>210, 'colspan'=>3, 'bgcolor'=>$color, 'values'=>$head);

		$td[] = array('align'=>'center', 'id'=>'tabelket', 'width'=>60, 'bgcolor'=>$color, 'values'=>'Qty');
		$td[] = array('align'=>'center', 'id'=>'tabelket', 'width'=>80, 'bgcolor'=>$color, 'values'=>'Amount');
		$td[] = array('align'=>'center', 'id'=>'tabelket', 'width'=>70, 'bgcolor'=>$color, 'values'=>'Average');
	}
	
	$tbl->set_tr();
	$tbl->set_th(array_merge(array(
	array('align'=>'center', 'id'=>'tabelket', 'width'=>30, 'rowspan'=>2, 'values'=>'No.'),
	array('align'=>'center', 'id'=>'tabelket', 'width'=>250, 'rowspan'=>2, 'values'=>'Produk'),
	), $th));

	$tbl->set_tr();
	$tbl->set_td($td);
	$urutan = 0;
	$total = array();
	$amount = array();

	foreach ($query as $r) {
		$Konversi = 1;
			$urutan++;
		if ($r->KONVERSI2>0) {
			$Konversi = $r->KONVERSI2*$r->KONVERSI1;
		} else if ($r->KONVERSI1>0){
			$Konversi = $r->KONVERSI1;
		}
		
	
		$SaldoAwal =($r->SALDOAWAL); 			$SaldoAwalRp =($r->SALDOAWALRP);
		$Beli =($r->BELI); 						$BeliRp =($r->BELIRP);
		$Jual =($r->JUAL);						$JualRp =($r->JUALRP);
		$ReturBeli =($r->RETURBELI); 						$ReturBeliRp =($r->RETURBELIRP);
		$TerimaTransfer =($r->TERIMATRANSFER); 	$TerimaTransferRp =($r->TERIMATRANSFERRP);
		$Transfer =($r->TRANSFER); 				$TransferRp =($r->TRANSFERRP);
		$Adjustment	  =($r->ADJUSTMENT);			$AdjustmentRp 	=($r->ADJUSTMENTRP);
		
		$SaldoAkhir	  =	 $SaldoAwal+$Beli+$TerimaTransfer
						 +$Adjustment+$Jual+$ReturBeli+$Transfer;
						
		$SaldoAkhirRp = $SaldoAwalRp+$BeliRp+$TerimaTransferRp
						+$AdjustmentRp+$JualRp+$ReturBeliRp+$TransferRp;

		/*'bgcolor'=>'#99FF99',;

		'bgcolor'=>'#FFFF99',;

		'bgcolor'=>'#9CD0ED',;
	*/		
		
		$tbl->set_tr();
		$tbl->set_td(array(
			array('class'=>'det', 'align'=>'center','values'=>$urutan),
			array('class'=>'det', 'values'=>$r->KATEGORI),
			
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFFFF', 'values'=>number($SaldoAwal, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFFFF', 'values'=>$LIHATHARGA?number($SaldoAwalRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFFFF', 'values'=>number(($SaldoAwal!=0 ? $SaldoAwalRp/$SaldoAwal : 0))),
			
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#99FF99', 'values'=>number($Beli, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#99FF99', 'values'=>$LIHATHARGA?number($BeliRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#99FF99', 'values'=>number(($Beli!=0 ? $BeliRp/$Beli : 0))),
			
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFF99', 'values'=>number($Jual, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFF99', 'values'=>$LIHATHARGA?number($JualRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFF99', 'values'=>number(($Jual!=0 ? $JualRp/$Jual : 0))),
			
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#9CD0ED', 'values'=>number($ReturBeli, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#9CD0ED', 'values'=>$LIHATHARGA?number($ReturBeliRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#9CD0ED', 'values'=>number(($ReturBeli!=0 ? $ReturBeliRp/$ReturBeli : 0))),

			                                                    
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#99FF99', 'values'=>number($TerimaTransfer, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#99FF99', 'values'=>$LIHATHARGA?number($TerimaTransferRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#99FF99', 'values'=>number(($TerimaTransfer!=0 ? $TerimaTransferRp/$TerimaTransfer : 0))),
			                                                    
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFF99', 'values'=>number($Transfer, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFF99', 'values'=>$LIHATHARGA?number($TransferRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFF99', 'values'=>number(($Transfer!=0 ? $TransferRp/$Transfer : 0))),
			                                                    
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#9CD0ED', 'values'=>number($Adjustment, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#9CD0ED', 'values'=>$LIHATHARGA?number($AdjustmentRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#9CD0ED', 'values'=>number(($Adjustment!=0 ? $AdjustmentRp/$Adjustment : 0))),
		 
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FF9E79', 'values'=>number($SaldoAkhir, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FF9E79', 'values'=>$LIHATHARGA?number($SaldoAkhirRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FF9E79', 'values'=>number(($SaldoAkhir!=0 ? $SaldoAkhirRp/$SaldoAkhir : 0))),
					
                                                                
		));                                                     
                                                                
		$total['SaldoAwal'] += $SaldoAwal; 			 $total['SaldoAwalRp'] += $SaldoAwalRp;					 $total['aSaldoAwal'] +=(($SaldoAwal!=0 ? $SaldoAwalRp/$SaldoAwal : 0));
		$total['Beli'] += $Beli; 					 $total['BeliRp'] += $BeliRp;                            $total['aBeli'] +=(($Beli!=0 ? $BeliRp/$Beli : 0));
		$total['Jual'] += $Jual; 					 $total['JualRp'] += $JualRp;                            $total['aJual'] +=(($Jual!=0 ? $JualRp/$Jual : 0));
		$total['ReturBeli'] += $ReturBeli; 			 $total['ReturBeliRp'] += $ReturBeliRp;                  $total['aReturBeli'] +=(($ReturBeli!=0 ? $ReturBeliRp/$ReturBeli : 0));
		$total['TerimaTransfer'] += $TerimaTransfer; $total['TerimaTransferRp'] += $TerimaTransferRp;        $total['aTerimaTransfer'] +=(($TerimaTransfer!=0 ? $TerimaTransferRp/$TerimaTransfer : 0));
		$total['Transfer'] += $Transfer; 			 $total['TransferRp'] += $TransferRp;                    $total['aTransfer'] +=(($Transfer!=0 ? $TransferRp/$Transfer : 0));
		$total['Adjustment'] += $Adjustment; 		 $total['AdjustmentRp'] += $AdjustmentRp;                $total['aAdjustment'] +=(($Adjustment!=0 ? $AdjustmentRp/$Adjustment : 0));
		$total['SaldoAkhir'] += $SaldoAkhir; 		 $total['SaldoAkhirRp'] += $SaldoAkhirRp;                $total['aSaldoAkhir'] +=(($SaldoAkhir!=0 ? $SaldoAkhirRp/$SaldoAkhir : 0));
	
	}
	
	
	$tbl->set_tr();
		$tbl->set_td(array(
		
			array('id'=>'tabelket', 'colspan'=>'2', 'bgcolor'=>'#B3E0FF', 'align'=>'right', 'values'=>"Total"),
			
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['SaldoAwal'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['SaldoAwalRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aSaldoAwal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['Beli'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['BeliRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aBeli'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['Jual'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['JualRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aJual'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['ReturBeli'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['ReturBeliRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aReturBeli'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['TerimaTransfer'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['TerimaTransferRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aTerimaTransfer'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['Transfer'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['TransferRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aTransfer'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['Adjustment'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['AdjustmentRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aAdjustment'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#FF9E79', 'values'=>number($total['SaldoAkhir'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#FF9E79', 'values'=>$LIHATHARGA?number($total['SaldoAkhirRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#FF9E79', 'values'=>$LIHATHARGA?number($total['aSaldoAkhir'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
	
	));
	
echo $tbl->generate_table();

} else if ($tampil=='barang'){
	cetakHeader($tampil,'Laporan Inventori Berdasarkan Varian',$lokasi,$tglAw,$tglAk);
	
	
	$tbl = new html_table();
	$tbl->set_hr(true);
	$countIndex = 1;
	$header = array('Saldo Awal', 'Beli', 'Jual', 'Retur Beli', 'Terima Transfer','Transfer','Adjustment','Saldo Akhir');		
	
	foreach ($header as $head) {
		if ($i==1) $color = '#99FF99';

		if ($i==2) $color = '#FFFF99';

		if ($i==3) {
			$color = '#9CD0ED';
			$i = 0;
		}
		
		if($countIndex == count($header))
		{
			$color = '#FF9E79';
		}
		$countIndex++;
		$i++;

		$th[] = array('align'=>'center', 'id'=>'tabelket', 'width'=>210, 'colspan'=>3, 'bgcolor'=>$color, 'values'=>$head);

		$td[] = array('align'=>'center', 'id'=>'tabelket', 'width'=>60, 'bgcolor'=>$color, 'values'=>'Qty');
		$td[] = array('align'=>'center', 'id'=>'tabelket', 'width'=>80, 'bgcolor'=>$color, 'values'=>'Amount');
		$td[] = array('align'=>'center', 'id'=>'tabelket', 'width'=>70, 'bgcolor'=>$color, 'values'=>'Average');
	}
	
	$tbl->set_tr();
	$tbl->set_th(array_merge(array(
	array('align'=>'center', 'id'=>'tabelket', 'width'=>30, 'rowspan'=>2, 'values'=>'No.'),
	array('align'=>'center', 'id'=>'tabelket', 'width'=>80, 'rowspan'=>2, 'values'=>'Kode Varian'),
	array('align'=>'center', 'id'=>'tabelket', 'width'=>250, 'rowspan'=>2, 'values'=>'Nama Varian'),
	), $th));

	$tbl->set_tr();
	$tbl->set_td($td);
	$urutan = 0;
	$total = array();
	$amount = array();

	foreach ($query as $r) {
		$Konversi = 1;
			$urutan++;
		if ($r->KONVERSI2>0) {
			$Konversi = $r->KONVERSI2*$r->KONVERSI1;
		} else if ($r->KONVERSI1>0){
			$Konversi = $r->KONVERSI1;
		}

		 $Konversi =($Konversi);
					
		$SaldoAwal =($r->SALDOAWAL); 			$SaldoAwalRp =($r->SALDOAWALRP);
		$Beli =($r->BELI); 					$BeliRp =($r->BELIRP);
		$Jual =($r->JUAL);						$JualRp =($r->JUALRP);
		$ReturBeli =($r->RETURBELI); 						$ReturBeliRp =($r->RETURBELIRP);
		$TerimaTransfer =($r->TERIMATRANSFER); 	$TerimaTransferRp =($r->TERIMATRANSFERRP);
		$Transfer =($r->TRANSFER); 				$TransferRp =($r->TRANSFERRP);
		$Adjustment	  =($r->ADJUSTMENT);			$AdjustmentRp 	=($r->ADJUSTMENTRP);
	
		$SaldoAkhir	  =	 $SaldoAwal+$Beli+$TerimaTransfer+$Pemakaian
						 +$Adjustment+$Jual+$ReturBeli+$Transfer;
						
		$SaldoAkhirRp = $SaldoAwalRp+$BeliRp+$TerimaTransferRp+$PemakaianRp
						+$AdjustmentRp+$JualRp+$ReturBeliRp+$TransferRp;

		/*'bgcolor'=>'#99FF99',;

		'bgcolor'=>'#FFFF99',;

		'bgcolor'=>'#9CD0ED',;
	*/		
		
		$tbl->set_tr();
		$tbl->set_td(array(
			array('class'=>'det', 'align'=>'center','values'=>$urutan),
			array('class'=>'det', 'align'=>'center','values'=>$r->KODEBARANG),
			array('class'=>'det', 'values'=>$r->NAMABARANG),
			
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFFFF', 'values'=>number($SaldoAwal, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFFFF', 'values'=>$LIHATHARGA?number($SaldoAwalRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFFFF', 'values'=>number(($SaldoAwal!=0 ? $SaldoAwalRp/$SaldoAwal : 0))),
			
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#99FF99', 'values'=>number($Beli, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#99FF99', 'values'=>$LIHATHARGA?number($BeliRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#99FF99', 'values'=>number(($Beli!=0 ? $BeliRp/$Beli : 0))),
			
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFF99', 'values'=>number($Jual, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFF99', 'values'=>$LIHATHARGA?number($JualRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFF99', 'values'=>number(($Jual!=0 ? $JualRp/$Jual : 0))),
			
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#9CD0ED', 'values'=>number($ReturBeli, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#9CD0ED', 'values'=>$LIHATHARGA?number($ReturBeliRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#9CD0ED', 'values'=>number(($ReturBeli!=0 ? $ReturBeliRp/$ReturBeli : 0))),			                                                   
			                                                    
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#99FF99', 'values'=>number($TerimaTransfer, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#99FF99', 'values'=>$LIHATHARGA?number($TerimaTransferRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#99FF99', 'values'=>number(($TerimaTransfer!=0 ? $TerimaTransferRp/$TerimaTransfer : 0))),
			                                                    
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFF99', 'values'=>number($Transfer, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFF99', 'values'=>$LIHATHARGA?number($TransferRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FFFF99', 'values'=>number(($Transfer!=0 ? $TransferRp/$Transfer : 0))),
							
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#9CD0ED', 'values'=>number($Adjustment, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#9CD0ED', 'values'=>$LIHATHARGA?number($AdjustmentRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#9CD0ED', 'values'=>number(($Adjustment!=0 ? $AdjustmentRp/$Adjustment : 0))),
			
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FF9E79', 'values'=>number($SaldoAkhir, false, 0)),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FF9E79', 'values'=>$LIHATHARGA?number($SaldoAkhirRp, true, $_SESSION[NAMAPROGRAM]["DECIMALDIGITAMOUNT"]):'X'),
			array('class'=>'det', 'align'=>'right', 'bgcolor'=>'#FF9E79', 'values'=>number(($SaldoAkhir!=0 ? $SaldoAkhirRp/$SaldoAkhir : 0))),
			
		));

		$total['SaldoAwal'] += $SaldoAwal; 			 $total['SaldoAwalRp'] += $SaldoAwalRp;					 $total['aSaldoAwal'] +=(($SaldoAwal!=0 ? $SaldoAwalRp/$SaldoAwal : 0));
		$total['Beli'] += $Beli; 					 $total['BeliRp'] += $BeliRp;                            $total['aBeli'] +=(($Beli!=0 ? $BeliRp/$Beli : 0));
		$total['Jual'] += $Jual; 					 $total['JualRp'] += $JualRp;                            $total['aJual'] +=(($Jual!=0 ? $JualRp/$Jual : 0));
		$total['ReturBeli'] += $ReturBeli; 			 $total['ReturBeliRp'] += $ReturBeliRp;                  $total['aReturBeli'] +=(($ReturBeli!=0 ? $ReturBeliRp/$ReturBeli : 0));
		$total['TerimaTransfer'] += $TerimaTransfer; $total['TerimaTransferRp'] += $TerimaTransferRp;        $total['aTerimaTransfer'] +=(($TerimaTransfer!=0 ? $TerimaTransferRp/$TerimaTransfer : 0));
		$total['Transfer'] += $Transfer; 			 $total['TransferRp'] += $TransferRp;                    $total['aTransfer'] +=(($Transfer!=0 ? $TransferRp/$Transfer : 0));
		$total['Adjustment'] += $Adjustment; 		 $total['AdjustmentRp'] += $AdjustmentRp;                $total['aAdjustment'] +=(($Adjustment!=0 ? $AdjustmentRp/$Adjustment : 0));
		$total['SaldoAkhir'] += $SaldoAkhir; 		 $total['SaldoAkhirRp'] += $SaldoAkhirRp;    			 $total['aSaldoAkhir'] +=(($SaldoAkhir!=0 ? $SaldoAkhirRp/$SaldoAkhir : 0));
	}
	
	
	$tbl->set_tr();
		$tbl->set_td(array(
		
			array('id'=>'tabelket', 'colspan'=>'3', 'bgcolor'=>'#B3E0FF', 'align'=>'right', 'values'=>"Total"),
			
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['SaldoAwal'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['SaldoAwalRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aSaldoAwal'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['Beli'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['BeliRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aBeli'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['Jual'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['JualRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aJual'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['ReturBeli'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['ReturBeliRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aReturBeli'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['TerimaTransfer'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['TerimaTransferRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aTerimaTransfer'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['Transfer'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['TransferRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aTransfer'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>number($total['Adjustment'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['AdjustmentRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#B3E0FF', 'values'=>$LIHATHARGA?number($total['aAdjustment'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#FF9E79', 'values'=>number($total['SaldoAkhir'], false, 0)),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#FF9E79', 'values'=>$LIHATHARGA?number($total['SaldoAkhirRp'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			array('id'=>'tabelket', 'align'=>'right', 'bgcolor'=>'#FF9E79', 'values'=>$LIHATHARGA?number($total['aSaldoAkhir'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
	));
	
echo $tbl->generate_table();
}
?>
</div>
<?php

function cetakHeader($tampil,$Keterangan,$lokasi, $TglAw,$TglAk){
    echo '<strong class="HEADER">'.$_SESSION[NAMAPROGRAM]['NAMAPERUSAHAAN'].'</strong><br>';
    echo '<strong class="HEADER">'.$Keterangan.'</strong>';
	echo '<br>';
    echo '<strong class="HEADERPERIODE">Lokasi : '.$lokasi.'</strong>';
	echo '<br>';
	echo '<strong class="HEADERPERIODE">Periode : '.$TglAw.' s/d '.$TglAk.'</strong>';
}

?>
