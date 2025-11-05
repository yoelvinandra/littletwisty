<!DOCTYPE HTML>
<html>
<head>
<title> ..:: Faktur Penjualan Pajak ::.. </title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/faktur_style.css" >
<style type="text/css">
.info_transaksi{
	width:247px;
}
.info_alamat{
	width:120px;
}
.customer{
	width:370px;
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

$sql = "select a.*,b.KODELOKASI,b.NAMALOKASI,c.NAMACUSTOMER,c.ALAMAT as ALAMATCUSTOMER,c.KOTA as KOTACUSTOMER,c.PROVINSI as PROPINSICUSTOMER,c.NEGARA as NEGARACUSTOMER,a.POTONGANPERSEN,a.POTONGANRP,a.PPNRP
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
$catatancustomer = format_remark($r->CATATANCUSTOMER);

//ALAMAT CUSTOMER
		$alamat = alamat_length($r->ALAMATCUSTOMER);
		if($r->KOTACUSTOMER != null  && alamat_length($r->ALAMATCUSTOMER) != null){$kota = "-"; } 
		$kota.=$r->KOTACUSTOMER;
		if($alamat != "" ||$kota != "") $propinsi = "<br>";
		$propinsi .= $r->PROPINSICUSTOMER;
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
		$diskon = '	<td colspan="2" valign="top"class="font-header  right">Member '.($r->POTONGANPERSEN == 0 ? '' : number($r->POTONGANPERSEN,true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])).'%</td>
					<td valign="top"class="font-body rp right" >'.number(-$r->POTONGANRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>';
		}
		
if(floatval($r->PPNRP) > 0)
		{						
		$ppn ='<td colspan="2" valign="top"class="font-header right">PPN</td>
			<td valign="top"class="font-body rp right" >'.number($r->PPNRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>';
		}	
		
if(floatval($r->DPPLAINRP) > 0)
		{						
		$dpp ='<td colspan="2" valign="top"class="font-header right">DPP Lain</td>
			<td valign="top"class="font-body rp right" >'.number($r->DPPLAINRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>';
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
				
				<td valign="top" class="font-header"> <img src="'.base_url().'assets/'.$_SESSION[NAMAPROGRAM]['KODEPERUSAHAAN'].'/logo-perusahaan.jpeg" class="user-image" alt="User Image" height="26"></td>
				<td valign="top" class="title"> NOTA PENJUALAN</td>

			</tr>
			<tr>
				<td valign="top" colspan="2" class="font-body">Factory  :  Jl. Raya Pradah Indah 39, kel Pradah Kec sambikerep</td>	
			</tr>
			<tr>
				<td valign="top" colspan="2"class="font-body">Telp. (031) 7319400 – 7319401    Fax ( 031) 7318134 Surabaya</td>	
			</tr>
		</table>
		<br>
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
									<td valign="top"class="font-body">'.$namacustomer.'<br>'.$alamat.$kota.$propinsi.$negara.$catatancustomer.'</td>
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
									<td valign="top"class="font-header" width="55px">No. Jual</td>
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
		
		
		
		$Subtotal = 0;
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
		?>

		
		
		<?php 
		
		$total = array();
		$count = 0;
		$urutan = 0;
		$hitung = 0;
		$countRows = count($rows);
		$countBonus = 0;
		$totalBarang = 0;
		foreach ($rows as $rs) {
			$countBonus = $rs->COUNTBONUS;
			if($rs->SATUANREF != ""){
						if($rs->PAKAIPPN == 0){
							$pakaippn = "TIDAK";
						}
						else if($rs->PAKAIPPN == 1){
							$pakaippn = "EXCL";
						}
						else if($rs->PAKAIPPN == 2){
							$pakaippn = "INCL";
						}
					}else{
						if($rs->PAKAIPPN == 0){
							$pakaippn = "TIDAK";
						}
						else if($rs->PAKAIPPN == 1){
							$pakaippn = "EXCL";
						}
						else if($rs->PAKAIPPN == 2){
							$pakaippn = "INCL";
						}
					}
			
			if($rs->SATUANREF != ""){			
				$SUBTOTAL = ($rs->JMLREF * $rs->HARGAREF);
			}
			else
			{
				$SUBTOTAL = ($rs->SUBTOTALKURS);
			}
			$Subtotal += $SUBTOTAL;
			
		}
		
		if(floatval($r->POTONGANRP) > 0)
		{
		    $arrayPotongan = explode("+",$r->POTONGANPERSEN);
		    $potongan = "";
		    for($p = 0 ; $p < count($arrayPotongan); $p++)
		    {
		        $potongan .= $arrayPotongan[$p]."%+";
		    }
		    
		    $potongan = rtrim($potongan,"+");
		
		$diskon = '	<td colspan="2" valign="top"class="font-header  right">Member '.($potongan == "0" ? '' : $potongan).'</td>
					<td valign="top"class="font-body rp right" >'.number(-$r->POTONGANRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>';
		}
		
		if(floatval($r->PPNRP) > 0)
		{						
		$ppn ='<td colspan="2" valign="top"class="font-header right">PPN</td>
			<td valign="top"class="font-body rp right" >'.number($r->PPNRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>';
		}
		
		if(floatval($r->DPPLAINRP) > 0)
		{						
		$dpp ='<td colspan="2" valign="top"class="font-header right">DPP Lain</td>
			<td valign="top"class="font-body rp right" >'.number($r->DPPLAINRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>';
		}
	
		if($cetakNPWP == 'no')
			{
				 $pph22 = '';
				 $ppn = '';
			}
		$add = 0;
		
		echo $headerperusahaan;
		echo $header;
		echo $detail;
				
		foreach ($rows as $rs) {
			
		?>
			
			<tr>
				<td valign="top"class="border-kiri center font-body border-kanan" valign="top"><?=$urutan+1?>.</td>
				
				<td valign="top"class="font-body border-kanan" valign="top"><?=$rs->NAMABARANG?></td>

				
				<?php if($rs->SATUANREF != ""){?>
					<td valign="top"class="font-body border-kanan center" valign="top"><?=number($rs->JMLREF, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])?> </td></td>
					<td valign="top"class="center font-body border-kanan" valign="top"><?=$rs->SATUANREF?></td>
					<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->HARGAREF, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
					<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->DISCKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
					<td valign="top"class="right font-body border-kanan" valign="top"><?=number(($rs->JMLREF * $rs->HARGAREF), true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
				<?php
				
				}else{?>
					<td valign="top"class="font-body border-kanan center" valign="top"><?=number($rs->JML, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])?></td>
					<td valign="top"class="center font-body border-kanan" valign="top"><?=$rs->SATUAN?></td>
					<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->HARGAKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
					<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->DISCKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
					<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->SUBTOTALKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
				
				<?php 
				}?>
				
			</tr>
			<?php 
			$totalBarang += $rs->JML;
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
				
			$urutan++;
			$count++;
			$hitung++;
			$i++;
		}
			

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
				<td valign="top"colspan="2" rowspan="2" valign="top">
					<table style="width:100%; margin-top:2px;">
						<tr>
							<td valign="top" colspan="7">
							<table>
								<tr valign="top">
								<td class="font-header " width="68">Terbilang</td>
								<td class="font-body ">: </td>
								<td class="font-body ">'.terbilang($Subtotal - $r->POTONGANRP + $r->PPNRP).'</td>
								</tr>
								<tr valign="top">
    							<td class="font-header">Total Barang</td>
    							<td class="font-body ">: </td>
    							<td class="font-body ">'.number($totalBarang,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']).'</td>
								</tr>
							</table>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="7">
							<table style="margin-top:5px;">
								<tr valign="top">
								<td class="font-body " width="68">Catatan</td>
								<td class="font-body ">: </td>
								<td class="font-body ">'.format_remark($r->CATATAN).'</td>
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
					</td>
					
    				<td valign="top" colspan="3" >
    				
        				<table width="100%" style="margin-top:2px;">
        					<tr valign="top">
        					<td class="font-body " colspan="3"><b>Bank Transfer</b><br>UOB 303-303-590-8<br>KARATU ABADI JAYA,CV</td>
        					</tr>
        				</table>
    				</td>
					
					<td valign="top"colspan="2" valign="top">
						<table width="100%" style="margin-top:2px;">
							<tr>
								<td colspan="2" width="44%" valign="top" class="font-header right">Total</td>
								<td valign="top"class="font-body rp right">'.number($Subtotal, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
							<tr>
							    '.$diskon.'
							</tr>
							<tr>
								'.$ppn.'
							</tr>
							<tr>
								<td colspan="2" valign="top"class="font-header right">Grand Total</td>
								<td valign="top"class="font-body rp right border-atas" >'.number($Subtotal - $r->POTONGANRP + $r->PPNRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
						
						</table>
					</td>
				</tr>
				</table>
			<div class="font-body" style="margin-top:0px; float:left;">Tgl Cetak : '.date("Y-m-d").'</div>	
			</div>
			
		';
		
		echo $footer;
		
		?>
		
							<!--<tr>-->
							<!--	'.$dpp.'-->
							<!--</tr>-->
	
</body>
</html>