<!DOCTYPE HTML>
<html>
<head>
<title> ..:: Faktur Penjualan ::.. </title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/faktur_style.css" >
<style type="text/css">
.info_transaksi{
	width:197px;
}
.info_alamat{
	width:120px;
}
.customer{
	width:420px;
}

.tabel_perusahaan tr td{
	width:66%;	
}

.rp{
	width:100px;
}

.tbl-header{
}

.font-header-kecil{
	font-family:tahoma;
	font-size:22px;
	font-weight:bold;
}

.font-header-kecil_1{
	font-family:Tahoma;
	font-size:8pt;
	letter-spacing:0px;
}

.font-body-kecil{
	font-family:Tahoma;
	font-size:12pt;
	letter-spacing:0px;
	word-spacing: 0px;
}
.font-body-kecil1{
	font-family:Tahoma;
	font-size:13pt;
	letter-spacing:0px;
	word-spacing: 0px;
}
.font-body-note{
	font-family:Tahoma;
	font-size:10pt;
	letter-spacing:0px;
	word-spacing: 0px;
}
.garis{
	font-family:Tahoma;
	font-size:14pt;
}

td{
	font-weight:bold;
	font-stretch: condensed;
}

</style>
</head>

<body width="100%" onload="window.print();">

<!-- BAGIAN HEADER -->
<?php
$headerperusahaan;
$header;
$detail;
$footer;

$perusahaan = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
$currency = $_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'];
$CI =& get_instance();	
$CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);

$sql = "select a.*,c.NAMALOKASI,a.GRANDTOTAL,b.USERNAME,d.NAMACUSTOMER,a.CATATANCUSTOMER
	from TPENJUALAN a 
	left join muser b on b.USERID = a.USERENTRY 
	left join mlokasi c on c.idlokasi = a.idlokasi
	left join mcustomer d on d.idcustomer = a.idcustomer
	where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDPENJUALAN = {$idtrans}";	
$r = $CI->db->query($sql)->row();
		
	
//PERUSAHAAN
$sql = "select distinct  * from MPERUSAHAAN where idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}";

$rp = $CI->db->query($sql)->row();

//ALAMAT PERUSAHAAN
		$alamatp = $rp->ALAMAT;
		if($rp->KOTA != null && $rp->ALAMAT != null){ $kotap = ",";} 
		$kotap .= $rp->KOTA;
		$propinsip = $rp->PROPINSI;
		if($rp->NEGARA != null && $rp->PROPINSI != null){ $negarap = "-";}
		$negarap .= $rp->NEGARA;

if($r->PPNRP != null){
$ppn ='<td  valign="top"class="font-body-kecil right">PPN</td>
		<td  valign="top" class="font-body-kecil right">Rp.</td>
			<td valign="top"class="font-body-kecil rp right" > '.number($r->PPNRP, true, 0).'</td>';		
}

$NPWP='NPWP. '.$r->NPWP;
if($cetakNPWP == 'no')
{
	 $NPWP = ''; 
	 $pembulatanrp ='';
	 $ppn='';
}
if($rp->NPWP != null){ $npwp_perusahaan = 'NPWP. '.$rp->NPWP;}
?>

	<div style="width:305px;height:auto; //border:1px solid;">
	<table>
		<table border="0" class="tabel_perusahaan" >
			<tr>
				<td valign="top" align="center" class="font-header-kecil">
					<?=$rp->NAMAPERUSAHAAN ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="center" class="font-header-kecil_1" style="font-size:10pt; font-weight:bold;">
					<?=$r->NAMALOKASI ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="center" class="font-header-kecil_1">
					<?=$alamatp.$kotap?><br><?=$rp->TELP?>
				</td>
			</tr>
		</table>
	
	
		<!-- DETAIL BARANG -->
		<?php
		$sql = "select a.HARGAKURS,a.PPNRP,a.KETERANGAN AS NAMABARANG,a.JML,a.HARGAKURS,a.SUBTOTALKURS,a.DISC,a.PAKAIPPN
				from TPENJUALANDTL a 
				left join TPENJUALAN a1 on a.IDPENJUALAN = a1.IDPENJUALAN	
				left join MBARANG b on b.IDBARANG = a.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDPENJUALAN = {$idtrans}
				order by a.URUTAN";		
		$rows = $CI->db->query($sql)->result(); 
		
		
		
		
		$DPP = 0;
		$PPNRP = 0;
		$Subtotal = 0;
		$Pembulatan = 0;
		$disc = 0;
		$MaxWord = 22;
		$i = 0;
		$urutan = 1;
		
		
		?>
		<br>
		<table  border="0" >
		<tr><td class="font-body-kecil1" valign="top">Tgl  </td><td valign="top"> : </td><td class="font-body-kecil1"><?=$r->TGLTRANS?></td></tr>
		<tr><td class="font-body-kecil1" valign="top">No  </td><td valign="top"> : </td><td class="font-body-kecil1"><?=$r->KODEPENJUALAN?></td></tr>
		
			<tr <?php if($r->NAMACUSTOMER == ""){ echo "hidden";}?>><td class="font-body-kecil1" valign="top">Customer  </td><td valign="top"> : </td><td class="font-body-kecil1"><?=$r->NAMACUSTOMER?> <?=$r->CATATANCUSTOMER != ""?'('.$r->CATATANCUSTOMER.')':""?></td></tr>
		
		<tr><td class="font-body-kecil1" valign="top">Kasir  </td><td valign="top"> : </td><td class="font-body-kecil1"><?=$r->USERNAME?></td></tr>
		</table>
		<hr>	
		<table width="305px" border="0" >

			<?php foreach($rows as $row){
				if($row->PAKAIPPN == 0){
					$pakaippn = "TIDAK";
					$dpp = $row->SUBTOTALKURS;
				}
				else if($row->PAKAIPPN == 1){
					$pakaippn = "EXCL";
					$dpp = $row->SUBTOTALKURS;
				}
				else if($row->PAKAIPPN == 2){
					$pakaippn = "INCL";
					$dpp = $row->SUBTOTALKURS - $row->PPNRP;
				}
				
				
			?>
			<tr>
				<td valign="top"class="font-body-kecil" width="100%" colspan="2"><?php if ($row->KETERANGAN == ""){ echo $row->NAMABARANG; }else{ echo $row->NAMABARANG." (".$row->KETERANGAN.")";} ?></td>
			</tr>
			<tr>
				<td>
				
				<table>
					<tr>
						<td valign="top"class="font-body-kecil left" width="10px"><?=number($row->JML,true,0)?> </td>
						<td valign="top"class="font-body-kecil left" width="20px">X</td>
						<td valign="top"class="font-body-kecil " align="right" ><?=number($row->HARGAKURS,true,0)?></td>
					</tr>
				</table>

				</td>
				<td valign="top"class="font-body-kecil  " align="right"><?php echo number(($row->JML*$row->HARGAKURS),true, 0); ?></td>
			</tr>
			<?php 
				$DPP += $dpp;
				$PPNRP += $row->PPNRP;
				$Subtotal += ($row->JML*$row->HARGAKURS);
				$disc += ($row->JML*$row->DISC);

			}
			
		echo '</table> <hr>';
		?>
		<table width="305px">
			
			<?php 
			if($PPNRP != 0 || $disc != 0){
			echo '<tr>
					<td  valign="top" class="font-body-kecil right" style="min-width:190px;">Total </td>
					<td  valign="top" class="font-body-kecil right" style="min-width:25px;">Rp </td>
					<td valign="top"class="font-body-kecil rp "align="right">'.number($Subtotal, true, 0).'</td>
				</tr>';
			}
			if($PPNRP != 0){
				
			echo '<tr>
					<td valign="top"class="font-body-kecil right" >DPP</td>
					<td  valign="top" class="font-body-kecil right" >Rp </td>
					<td valign="top"class="font-body-kecil rp  " align="right" >'.number(($DPP), true, 0).'</td>
				</tr>';
				
			echo '<tr>
				<td valign="top"class="font-body-kecil right">PPN</td>
				<td  valign="top" class="font-body-kecil right" >Rp </td>
				<td valign="top"class="font-body-kecil rp  "  align="right" >'.number(($PPNRP), true, 0).'</td>
			</tr>';
			}
			if($disc != 0){

			echo '<tr>
					<td valign="top"class="font-body-kecil right" >Disc</td>
					<td  valign="top" class="font-body-kecil right" >Rp </td>
					<td valign="top"class="font-body-kecil rp  " align="right" >'.number($disc, true, 0).'</td>
				</tr>';
			}
			if($row->PEMBULATAN != 0){
			echo '<tr>
				<td valign="top"class="font-body-kecil right">Pembulatan / Diskon</td>
				<td  valign="top" class="font-body-kecil right" >Rp </td>
				<td valign="top"class="font-body-kecil rp " align="right">'.number(($row->PEMBULATAN), true, 0).'</td>
			</tr>';
			}
			if($row->UANGMUKA != 0){
			echo '<tr>
				<td valign="top"class="font-body-kecil right">Uang Muka</td>
				<td  valign="top" class="font-body-kecil right" >Rp </td>
				<td valign="top"class="font-body-kecil rp  "  align="right">'.number(($row->UANGMUKA), true, 0).'</td>
			</tr>';
			}
			echo '<tr>
				<td valign="top"class="font-body-kecil right" style="font-weight:bold;">Grand Total</td>
				<td  valign="top" class="font-body-kecil right" style="font-weight:bold;">Rp </td>
				<td valign="top"class="font-body-kecil rp  "  align="right" style="font-weight:bold;">'.number(($r->GRANDTOTAL), true, 0).'</td>
			</tr>';
			
			if($r->POTONGANRP != 0)
			{
    			echo '<tr>
    				<td valign="top"class="font-body-kecil right" style="font-weight:bold;">Potongan</td>
    				<td  valign="top" class="font-body-kecil right" style="font-weight:bold;">Rp </td>
    				<td valign="top"class="font-body-kecil rp  "  align="right" style="font-weight:bold;">'.number(($r->POTONGANRP), true, 0).'</td>
    			</tr>';
    			
    			echo '<tr>
    				<td valign="top"class="font-body-kecil right" style="font-weight:bold;">Grand Total *</td>
    				<td  valign="top" class="font-body-kecil right" style="font-weight:bold;">Rp </td>
    				<td valign="top"class="font-body-kecil rp  "  align="right" style="font-weight:bold;">'.number(($r->GRANDTOTALDISKON), true, 0).'</td>
    			</tr>';
    			echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
			}
			
			?>
			<!--
			<tr>
				<td valign="top"class="font-body-kecil right"style="font-weight:bold;">Grand Total</td>
				<td  valign="top" class="font-body-kecil right" style="font-weight:bold;">Rp </td>
				
				<td valign="top"class="font-body-kecil rp  border-atas" align="right" style="font-weight:bold;" ><?=number($r->GRANDTOTAL, true, 0)?></td>
			</tr>
			-->
			<tr>
				<td valign="top"class="font-body-kecil right"style="font-weight:bold;">Bayar</td>
				<td  valign="top" class="font-body-kecil right" style="font-weight:bold;">Rp </td>
				<td valign="top"class="font-body-kecil rp  " align="right" style="font-weight:bold;" ><?=number($r->PEMBAYARAN, true, 0)?></td>
			</tr>
			<tr>
				<td valign="top"class="font-body-kecil right"style="font-weight:bold;">Kembali</td>
				<td  valign="top" class="font-body-kecil right" style="font-weight:bold;">Rp </td>
				<!--<td valign="top"class="font-body-kecil rp  " align="right" style="font-weight:bold;" ><?=number($r->PEMBAYARAN-($DPP + $PPNRP+ $row->PEMBULATAN + $row->UANGMUKA), true, 0)?></td>-->
				<td valign="top"class="font-body-kecil rp  " align="right" style="font-weight:bold;" ><?=number($r->PEMBAYARAN-$r->GRANDTOTALDISKON, true, 0)?></td>
			</tr>
			
			<tr><td><br></td></tr>
			<tr>
				<td colspan="3" class="font-body-note ">
					<table>
						<tr valign="top">
							<td class="font-body-note ">NOTE : </td>
							<td class="font-body-note "><?=$r->CATATAN?></td>
						</tr>
					</table>
				</td>
				
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td colspan="3" class="font-body-note " align="center">***** Terima Kasih Atas Kunjungan Anda *****</td></tr>
		</table>
		<br><br>
		<hr></hr>
	</div>
		
	
</body>
</html>