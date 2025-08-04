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
	width:65%;	
}

.rp{
	width:100px;
}
table .fieldset{
	height:90px;
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

$transaksi = $CI->model_master_config->getConfig('TPENJUALAN','TRANSAKSIBBK');

$sql = "select a.*,b.KODELOKASI,b.NAMALOKASI,c.NAMACUSTOMER,c.ALAMAT as ALAMATCUSTOMER,c.KOTA as KOTACUSTOMER,c.PROVINSI as PROPINSICUSTOMER,c.NEGARA as NEGARACUSTOMER,a.POTONGANPERSEN,a.POTONGANRP
				,d.USERNAME
				from TPENJUALAN a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				left join MCUSTOMER c on a.IDCUSTOMER = c.IDCUSTOMER
				left join MUSER d on a.USERENTRY = d.USERID  and a.idperusahaan = d.idperusahaan
				left join MPERUSAHAAN h on h.IDPERUSAHAAN = a.IDPERUSAHAAN  and a.idperusahaan = h.idperusahaan
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDLOKASI in (
					select IDLOKASI
					from MUSERLOKASI
					where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDUSER = {$_SESSION[NAMAPROGRAM]['IDUSER']}
				) 
				and a.IDPENJUALAN = ? ";
			
$r = $CI->db->query($sql, [$idtrans])->row();

$namacustomer = $r->NAMACUSTOMER;

//ALAMAT CUSTOMER
		$alamat = alamat_length($r->ALAMATCUSTOMER);
		if($r->KOTACUSTOMER != null  && alamat_length($r->ALAMATCUSTOMER) != null){$kota = "-"; } 
		$kota.=$r->KOTACUSTOMER;
		$propinsi = "<br>".$r->PROPINSICUSTOMER;
		if($r->NEGARACUSTOMER != null && $r->PROPINSICUSTOMER != null){$negara = "-"; }
		$negara.=$r->NEGARACUSTOMER;

//PEMBULATAN
if( $r->PEMBULATAN != 0){ 
			$pembulatanrp='<tr><td colspan="2" valign="top"class="font-header right">Pembulatan</td>
			<td valign="top"class="font-body  right" >'.number($r->PEMBULATAN, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td></tr>';
		}
						 

	
//PERUSAHAAN
$sql = "select distinct  * from MPERUSAHAAN where IDPERUSAHAAN = ".$perusahaan."";

$rp = $CI->db->query($sql)->row();

//ALAMAT PERUSAHAAN
		$alamatp = $rp->ALAMAT;
		if($rp->KOTA != null && $rp->ALAMAT != null){ $kotap = "-";} 
		$kotap .= $rp->KOTA;
		$propinsip = $rp->PROPINSI;
		if($rp->NEGARA != null && $rp->PROPINSI != null){ $negarap = "-";}
		$negarap .= $rp->NEGARA;


if(floatval($r->POTONGANRP) > 0)
		{
		$diskon = '	<td colspan="2" valign="top"class="font-header  right">Pot Member '.($r->POTONGANPERSEN == 0 ? '' : number($r->POTONGANPERSEN,true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])).'%</td>
					<td valign="top"class="font-body rp right" >'.number(-$r->POTONGANRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>';
		}
		
if(floatval($PPNRP) > 0)
		{						
		$ppn ='<td colspan="2" valign="top"class="font-header right">PPN</td>
			<td valign="top"class="font-body rp right" >'.number($PPNRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>';
		}	
		
$NPWP=''.$r->NPWP;
if($cetakNPWP == 'no')
{
	 $NPWP = ''; 
	 $pembulatanrp ='';
	 $ppn='';
}
if($rp->NPWP != null){ $npwp_perusahaan = 'NPWP. '.$rp->NPWP;}
?>

	<?php $headerperusahaan='
	<div style="width:620px;height:500px; //border:1px solid; margin-bottom:26px;">
	<table style="width:620px;">
		<table border="0" class="tabel_perusahaan">
			<tr>
				
				<td valign="top" class="font-header"> <img src="'.base_url().'assets/'.$_SESSION[NAMAPROGRAM]['KODEPERUSAHAAN'].'/logo.png" class="user-image" alt="User Image" height="26"></td>
				<td valign="top" rowspan="3" class="title"> NOTA PENJUALAN</td>

			</tr>
			<tr>
				<td valign="top"class="font-body">'.$alamatp.$kotap.'</td>	
			</tr>
			<tr>
				<td valign="top"class="font-body">'.$npwp_perusahaan.'</td>	
			</tr>
		</table>
	';?>
	
	<?php $header='
			<table border="0">
				<tr>
					<td valign="top"valign="top" class="customer">
							<table class="fieldset customer" border="0">
							<tr valign="top"><td>
							<table>
								<tr>
									<td valign="top"class="font-header" >Kepada Yth </td>	
								</tr>
								<tr>
									<td valign="top"class="font-body">'.$namacustomer.'<br>'.$alamat.$kota.$propinsi.$negara.'</td>
								</tr>
								<tr>
									<td valign="top" class="font-body">'.$NPWP.'</td>
								</tr>
							</table>
							</td></tr>
							</table>
					</td>
					<td valign="top"valign="top" class="info_transaksi">
							<table class="fieldset info_transaksi" border="0">
							<tr valign="top"><td>
							<table>

								<tr>
									<td valign="top"class="font-header">No. Jual</td>
									<td valign="top"class="font-body">: '.$r->KODEPENJUALAN.'</td>
								</tr>
								<tr>
									<td valign="top"class="font-header">Tgl. Trans</td>
									<td valign="top"class="font-body">: '.$r->TGLTRANS.'</td>
								</tr>
								<tr>
									<td valign="top"class="font-header">Lokasi</td>
									<td valign="top"class="font-body">: '.$r->NAMALOKASI.'</td>
								</tr>
							</table>
							</td></tr>
							</table>
					</td>
					
				</tr>
			</table>
	';?>
		<!-- DETAIL BARANG -->
		<?php
		
		
		$sql = "select a.IDPENJUALAN,a.KODEPENJUALAN,
				a.IDBARANG,a.JML,a.JMLREF,a.JMLBONUS,
				c.KODEBARANG, a.KETERANGAN as NAMABARANG, a.SATUAN,a.SATUANUTAMA,  a.KONVERSI, a.IDCURRENCY, d.SIMBOL, a.HARGA,a.HARGAREF,a.DISCPERSEN,a.DISC,a.DISCKURS,a.SUBTOTAL,a.NILAIKURS,a.HARGAKURS,a.SUBTOTALKURS,a.URUTAN,a.PAKAIPPN,a.PPNRP,a.PPNPERSEN,a1.IDSYARATBAYAR
				from TPENJUALANDTL a 
				left join TPENJUALAN a1 on a.IDPENJUALAN = a1.IDPENJUALAN  and a.idperusahaan = a1.idperusahaan
				left join MBARANG c on a.IDBARANG = c.IDBARANG  and a.idperusahaan = c.idperusahaan
				left join MCURRENCY d on a.IDCURRENCY = d.IDCURRENCY  and a.idperusahaan = d.idperusahaan
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDPENJUALAN = {$idtrans}
				order by a.URUTAN";		
		$rows = $CI->db->query($sql, [$idtrans])->result(); 
		
		
		
		
		$DPP = 0;
		$PPNRP = 0;
		$Subtotal = 0;
		$Pembulatan = 0;
		$Tax = 0;
		$halaman =1;
		$max_item = 10;
		$i = 0;
		
		
		//HALAMAN of
		
		
		/*if($transaksi == "DETAIL")
			{
				$DetailH = '<td valign="top"class="font-header center border-atas border-kanan border-bawah"width="25%">No. BBK</td>';
			}*/
			
		$detail='<table width="620px" style="margin-top:10px; " border="0" >
			<tr>
				<th class="border-kiri center no tbl-header border-atas border-kanan border-bawah">NO</th>
				'.$DetailH.'
		
				<th class="center tbl-header border-atas border-kanan border-bawah" width="48%">Nama Barang </th>
				<th class="center tbl-header border-atas border-kanan border-bawah" width="5%">Jml </th>
				<th class="center tbl-header border-atas border-kanan border-bawah" width="5%">Sat</th>
				<th class="center tbl-header border-atas border-kanan border-bawah" width="10%">Harga</th>
				<th class="center tbl-header border-atas border-kanan border-bawah" width="11%">Disc</th>
				<th class="center tbl-header border-atas border-bawah border-kanan" width="14%">Subtotal</th>
			</tr>';
			
		$footer='
			<tr>
				<td valign="top"class="font-body border-kiri border-bawah border-kanan"></td>
				<td valign="top"class="font-body border-bawah border-kanan"></td>
				<td valign="top"class="font-body border-bawah border-kanan"></td>
				
				<td valign="top"class="font-body border-bawah border-kanan"></td>
				<td valign="top"class="font-body border-bawah border-kanan"></td>
				<td valign="top"class="font-body border-bawah border-kanan"></td>
				<td valign="top"class="font-body border-bawah border-kanan"></td>
			</tr>
			<tr>
				<td valign="top"colspan="4" rowspan="2" valign="top">
					<table style="width:100%" >
						<tr>
							<td valign="top"class="font-body " colspan="7">
							<table>
								<tr valign="top">
								<td>Terbilang</td>
								<td>: </td>
								<td>'.terbilang($DPP + $PPNRP - $r->POTONGANRP).'</td>
								</tr>
							</table>
							</td>
						</tr>
						<tr>
							<td valign="top"class="font-body "  colspan="7">
							<table>
								<tr valign="top">
								<td>Catatan</td>
								<td>: </td>
								<td>'.format_remark($r->CATATAN).'</td>
								</tr>
							</table>
							</td>
						</tr>
						<tr valign="top" class="font-body" >
							<td width="150px">&nbsp;</td>
								<td valign="top" valign="top" hidden>
								<table width="100%" >
									<tr>
										<td valign="top"class="font-body center">Diketahui</td>
									</tr>
									
									<tr>
									<td valign="top"class="font-body center" height="40px"></td>
									</tr>
									<tr>
										<td valign="top"class="font-body center">
										'.nama_terang($r->USERNAME).'
									</td>
									</tr>
								</table>
							</td>
							<td valign="top"valign="top" hidden>
								<table width="100%">
									<tr>
										<td valign="top"class="font-body center">Penjualan</td>
									</tr>
								
									<tr>
									<td valign="top"class="total center" height="40px"></td>
									</tr>
									<tr>
										<td valign="top"class="font-body center">(................)</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					
					<td valign="top"colspan="3" valign="top">
						<table width="100%" >
							<tr>
								<td colspan="2" width="60%" valign="top" class="font-header right">Total</td>
								<td valign="top"class="font-body rp right">'.number($Subtotal, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
							<tr>
								<td colspan="2" valign="top"class="font-header  right">DPP</td>
								<td valign="top"class="font-body rp right" >'.number($DPP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
							<tr>
							    '.$diskon.'
							</tr>
							<tr>
								'.$ppn.'
							</tr>
							<tr>
								<td colspan="2" valign="top"class="font-header right">Grand Total</td>
								<td valign="top"class="font-body rp right border-atas" >'.number($DPP + $PPNRP - $r->POTONGANRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
						
						</table>
					</td>
				</tr>
				</table>
			<br>
			<div class="font-body" style="margin-top:0px; float:left;">Tgl Cetak : '.date("Y-m-d").'</div>
			<div class="font-body" style="margin-left:550px; margin-top:0px;">Hal : 1 of 1</div>	
			</div>
			';
		
		
		?>

		
		
		<?php 
		
		$total = array();
		$count = 0;
		$urutan = 0;
		$hitung = 0;
		$countRows = count($rows);
		$countBonus = 0;
		if(count($rows) == 0){
			echo $headerperusahaan;
			echo $header;
			echo $detail;
			for ($i=0; $i < $max_item; $i++) {
					
						echo '<tr>
							<td valign="top"class="font-body border-kiri border-kanan">&nbsp;</td>
							<td valign="top"class="font-body border-kanan">&nbsp;</td>
							<td valign="top"class="font-body border-kanan">&nbsp;</td>
							
							<td valign="top"class="font-body border-kanan">&nbsp;</td>
							<td valign="top"class="font-body border-kanan">&nbsp;</td>
							<td valign="top"class="font-body border-kanan">&nbsp;</td>
							<td valign="top"class="font-body border-kanan">&nbsp;</td>
						</tr>';
			}
			echo $footer;
		}
		foreach ($rows as $rs) {
			$countBonus = $rs->COUNTBONUS;
			if($rs->SATUANREF != ""){
						if($rs->PAKAIPPN == 0){
							$pakaippn = "TIDAK";
							$dpp = $rs->JMLREF * $rs->HARGAREF;
						}
						else if($rs->PAKAIPPN == 1){
							$pakaippn = "EXCL";
							$dpp = $rs->JMLREF * $rs->HARGAREF;
						}
						else if($rs->PAKAIPPN == 2){
							$pakaippn = "INCL";
							$dpp = ($rs->JMLREF * $rs->HARGAREF) - $rs->PPNRP;
						}
					}else{
						if($rs->PAKAIPPN == 0){
							$pakaippn = "TIDAK";
							$dpp = $rs->SUBTOTALKURS;
						}
						else if($rs->PAKAIPPN == 1){
							$pakaippn = "EXCL";
							$dpp = $rs->SUBTOTALKURS;
						}
						else if($rs->PAKAIPPN == 2){
							$pakaippn = "INCL";
							$dpp = $rs->SUBTOTALKURS - $rs->PPNRP;
						}
					}
			
			if($rs->SATUANREF != ""){			
				$SUBTOTAL = ($rs->JMLREF * $rs->HARGAREF);
			}
			else
			{
				$SUBTOTAL = ($rs->SUBTOTALKURS);
			}
			$DPP += $dpp;
			$PPNRP += $rs->PPNRP;
			$Tax += $rs->PPH22RP;
			$Subtotal += $SUBTOTAL;
			
			
			
		
		//PPH
		$pph22='';

			if($Tax != 0)
			{
				$pph22='<tr><td colspan="2" valign="top"class="font-header right">PPH22</td>
					<td valign="top"class="font-body right">'.number($Tax, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td></tr>';
			}
			
			
				
		}
		
		if(floatval($r->POTONGANRP) > 0)
		{
		$diskon = '	<td colspan="2" valign="top"class="font-header  right">Pot Member '.($r->POTONGANPERSEN == 0 ? '' : number($r->POTONGANPERSEN,true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])).'%</td>
					<td valign="top"class="font-body rp right" >'.number(-$r->POTONGANRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>';
		}
		
		if(floatval($PPNRP) > 0)
		{						
		$ppn ='<td colspan="2" valign="top"class="font-header right">PPN</td>
			<td valign="top"class="font-body rp right" >'.number($PPNRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>';
		}
	
		if($cetakNPWP == 'no')
			{
				 $pph22 = '';
				 $ppn = '';
			}
		$add = 0;
		foreach ($rows as $rs) {
			
				
			if($i==0)
			{
				echo $headerperusahaan;
				echo $header;
				echo $detail;
			}
			
			
		
		?>
			
			<tr>
				<td valign="top"class="border-kiri center font-body border-kanan" valign="top"><?=$urutan+1?>.</td>
				
				<td valign="top"class="font-body border-kanan" valign="top"><?=$rs->NAMABARANG?></td>

				
				<?php if($rs->SATUANREF != ""){?>
					<td valign="top"class="font-body border-kanan right" valign="top"><?=number($rs->JMLREF, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])?> </td></td>
					<td valign="top"class="center font-body border-kanan" valign="top"><?=$rs->SATUANREF?></td>
					<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->HARGAREF, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
					<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->DISCKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
					<td valign="top"class="right font-body border-kanan" valign="top"><?=number(($rs->JMLREF * $rs->HARGAREF), true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
				<?php
				
				}else{?>
					<td valign="top"class="font-body border-kanan right" valign="top"><?=number($rs->JML, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])?></td>
					<td valign="top"class="center font-body border-kanan" valign="top"><?=$rs->SATUAN?></td>
					<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->HARGAKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
					<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->DISCKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
					<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->SUBTOTALKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
				
				<?php 
				}?>
				
			</tr>
			<?php 
			
			if($rs->JMLBONUS != 0){ 
			echo "<tr>";
				echo '<td valign="top"class="font-body border-kiri border-kanan" valign="top"></td>';
				echo '<td valign="top"class="font-body border-kanan" valign="top">**Bonus</td>';
				echo '<td valign="top"class="font-body border-kanan center" valign="top">'.number($rs->JMLBONUS, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']).'</td>';
				echo '<td valign="top"class="center font-body border-kanan" valign="top">-</td>';
				echo '<td valign="top"class="center font-body border-kanan" valign="top">-</td>';
				echo '<td valign="top"class="center font-body border-kanan" valign="top">-</td>';
				echo '<td valign="top"class="center font-body border-kanan" valign="top"></td>';
				
			echo "</tr>";
			$add++;
			}
			?>
			
		<?php

		$footer='
			<tr>
				<td valign="top"class="font-body border-kiri border-bawah border-kanan"></td>
				<td valign="top"class="font-body border-bawah border-kanan"></td>
				<td valign="top"class="font-body border-bawah border-kanan"></td>
				<td valign="top"class="font-body border-bawah border-kanan"></td>
				<td valign="top"class="font-body border-bawah border-kanan"></td>
				<td valign="top"class="font-body border-bawah border-kanan"></td>
				
				<td valign="top"class="font-body border-bawah border-kanan"></td>
			</tr>
			<tr>
				<td valign="top"colspan="4" rowspan="2" valign="top">
					<table style="width:100%" >
						<tr>
							<td valign="top"class="font-body " colspan="7">
							<table>
								<tr valign="top">
								<td>Terbilang</td>
								<td>: </td>
								<td>'.terbilang($DPP + $PPNRP - $r->POTONGANRP).'</td>
								</tr>
							</table>
							</td>
						</tr>
						<tr>
							<td valign="top"class="font-body "  colspan="7">
							<table>
								<tr valign="top">
								<td>Catatan</td>
								<td>: </td>
								<td>'.format_remark($r->CATATAN).'</td>
								</tr>
							</table>
							</td>
						</tr>
						<tr valign="top" class="font-body" >
							<td width="150px">&nbsp;</td>
								<td valign="top" valign="top" hidden>
								<table width="100%" >
									<tr>
										<td valign="top"class="font-body center">Diketahui</td>
									</tr>
									
									<tr>
									<td valign="top"class="font-body center" height="40px"></td>
									</tr>
									<tr>
										<td valign="top"class="font-body center">
										'.nama_terang($r->USERNAME).'
									</td>
									</tr>
								</table>
							</td>
							<td valign="top"valign="top" hidden>
								<table width="100%">
									<tr>
										<td valign="top"class="font-body center">Penjualan</td>
									</tr>
								
									<tr>
									<td valign="top"class="total center" height="40px"></td>
									</tr>
									<tr>
										<td valign="top"class="font-body center">(................)</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					
					<td valign="top"colspan="3" valign="top">
						<table width="100%" >
							<tr>
								<td colspan="2" width="60%" valign="top" class="font-header right">Total</td>
								<td valign="top"class="font-body rp right">'.number($Subtotal, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
							<tr>
								<td colspan="2" valign="top"class="font-header  right">DPP</td>
								<td valign="top"class="font-body rp right" >'.number($DPP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
							<tr>
							    '.$diskon.'
							</tr>
							<tr>
								'.$ppn.'
							</tr>
							<tr>
								<td colspan="2" valign="top"class="font-header right">Grand Total</td>
								<td valign="top"class="font-body rp right border-atas" >'.number($DPP + $PPNRP - $r->POTONGANRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
						
						</table>
					</td>
				</tr>
				</table>
			<br>
			<div class="font-body" style="margin-top:0px; float:left;">Tgl Cetak : '.date("Y-m-d").'</div>
			<div class="font-body" style="margin-left:550px; margin-top:0px;">'.halaman($halaman,(count($rows)+$countBonus),($max_item)).'</div>	
			</div>
			
				';
				
			$urutan++;
			$count++;
			$hitung++;
			$i++;
			
			//cek untuk munculkan footer
			
			$footer2 ='
						<tr>
							<td valign="top"class="font-body border-atas">&nbsp;</td>
							<td valign="top"class="font-body border-atas">&nbsp;</td>
							<td valign="top"class="font-body border-atas">&nbsp;</td>
						
							<td valign="top"class="font-body border-atas">&nbsp;</td>
							<td valign="top"class="font-body border-atas">&nbsp;</td>
							<td valign="top"class="font-body border-atas">&nbsp;</td>
							<td valign="top"class="font-body border-atas">&nbsp;</td>
						</tr>
						</table>
			            <br>
			            <div class="font-body" style="margin-top:0px; float:left;">Tgl Cetak : '.date("Y-m-d").'</div>
						<div class="font-body" style="margin-left:550px; margin-top:135px;">'.halaman($halaman,(count($rows)+$countBonus),$max_item).' </div>	
						</div>';

			if(($countRows+$countBonus) == ($count+$countBonus))
			{
				$count_all = ($count+$countBonus);
				for (; ($count_all) < ($max_item*$halaman); $count_all++) {
					
						echo '<tr>
							<td valign="top"class="font-body border-kiri border-kanan">&nbsp;</td>
							<td valign="top"class="font-body border-kanan">&nbsp;</td>
							<td valign="top"class="font-body border-kanan">&nbsp;</td>
							<td valign="top"class="font-body border-kanan">&nbsp;</td>
							<td valign="top"class="font-body border-kanan">&nbsp;</td>
							<td valign="top"class="font-body border-kanan">&nbsp;</td>
							
							<td valign="top"class="font-body border-kanan">&nbsp;</td>
						</tr>';
					}
					
				echo $footer;
				$halaman++;
				
			}
			else if(($hitung+$add) % ($max_item) == 0 || ($hitung+$add) % ($max_item+1) == 0)
			{

				$add = 0;
				$hitung = 0;
				echo $footer2;
				echo $headerperusahaan;
				echo $header;
				echo $detail;
				$halaman++;
			}

		}
			
			
			
		?>
			
		
		
		<!-- Add Tax -->
		<?php
		/*$sql = "select distinct b.NAMAPAJAK as PAJAK1,c.NAMAPAJAK as PAJAK2, d.NAMAPAJAK as PAJAK3
						from TBELI a 
						left join MPAJAK b on a.IDPAJAKLAIN1 = b.IDPAJAK
						left join MPAJAK c on a.IDPAJAKLAIN2 = c.IDPAJAK
						left join MPAJAK d on a.IDPAJAKLAIN3 = d.IDPAJAK
					where a.idasetpo = ? && a.idperusahaan = ".$perusahaan."";
						
		$rows = $CI->db->query($sql, [$idtrans])->result(); 
		
		$NamaTax = "";

		foreach($rows as $taxes)
		{
			$NamaTax .= $taxes->PAJAK1.",";
			if($taxes->PAJAK2 != null)
			$NamaTax .= $taxes->PAJAK2.",";
			if($taxes->PAJAK3 != null)
			$NamaTax .= $taxes->PAJAK3.",";
		}

		$NamaTax =substr($NamaTax,0,-1);*/
		?>
		
		<!-- FOOTER -->
		
		
	
</body>
</html>