<!DOCTYPE HTML>
<html>
<head>
<title> ..:: Faktur Pembelian Pesanan Persediaan ::.. </title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/faktur_style.css" >
<style type="text/css">
.tabel_perusahaan tr td{
	width:57%;
}
table .fieldset{
	height:90px;
}

.customer{
	width:430px;
}
.info_transaksi{
	width:185px;
}
</style>
</head>

<body width="100%" onload="window.print();" >

<!-- BAGIAN HEADER -->
<?php
$perusahaan = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
$currency = $_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'];
$CI =& get_instance();	
$CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);

$transaksi = $CI->model_master_config->getConfig('TPO','TRANSAKSIPR');

$sql = "select a.*,b.KODELOKASI,b.NAMALOKASI,c.KODESUPPLIER,c.NAMASUPPLIER,c.BADANUSAHA,c.ALAMAT as ALAMATSUPPLIER,c.CONTACTPERSON,c.TELPCP,d.USERNAME
			from TPO a
			left join MLOKASI b on a.IDLOKASI = b.IDLOKASI  and a.idperusahaan = b.idperusahaan
			left join MSUPPLIER c on a.IDSUPPLIER = c.IDSUPPLIER  and a.idperusahaan = c.idperusahaan
			left join MUSER d on a.USERENTRY = d.USERID  and a.idperusahaan = d.idperusahaan
			
			where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDLOKASI in (
					select IDLOKASI
					from MUSERLOKASI
					where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDUSER = {$_SESSION[NAMAPROGRAM]['IDUSER']}
				)
		and a.idpo = ?";
		
$r = $CI->db->query($sql, [$idtrans])->row();

//ALAMAT SUPPLIER
		$alamat = alamat_length($r->ALAMATSUPPLIER);
		if($r->KOTASUPPLIER != null  && alamat_length($r->ALAMATSUPPLIER) != null){$kota = "-"; } 
		$kota.=$r->KOTASUPPLIER;
		$propinsi = "<br>".$r->PROPINSISUPPLIER;
		if($r->NEGARASUPPLIER != null && $r->PROPINSISUPPLIER != null){$negara = "-"; }
		$negara.=$r->NEGARASUPPLIER;
		
//TRANSAKSI HEADER
if($transaksi == "HEADER")
{
	$transheader='<tr>
		<td valign="top"> <span class="font-header">No. PR : </span> <span class="font-body"> '.$r->KODEPR.'</span></td>
	</tr>';
}

//TRANSAKSI DETAIL
if($transaksi == "DETAIL")
{
	$transdetail='<th class="center tbl-header border-atas border-bawah border-kanan" width="15%">No.PR</th>';
	$borderdetail='<td valign="top"class="border-bawah border-kanan"></td>';
	$borderbawahdetail='<td valign="top"class="border-bawah border-kanan"></td>';
}
//PEMBULATAN
if( $r->PEMBULATAN != 0){ 
	$pembulatanrp='<tr><td valign="top"class="font-header right">Pembulatan</td>
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
?>

<?php $headerperusahaan='

	<div style="width:620px;height:500px; //border:1px solid; margin-bottom:26px;">
	<table style="width:620px;">
		<table border="0" class="tabel_perusahaan">
			<tr>
				
				<td valign="top"class="font-header">'.$rp->NAMAPERUSAHAAN .'</td>
				<td valign="top" rowspan="3" class="title"> PESANAN PEMBELIAN</td>

			</tr>
			<tr>
				<td valign="top"class="font-body">'.$alamatp.$kotap.'</td>	
			</tr>
		</table>
	'; ?>
	
	<?php $header='
			<table border="0">
				<tr>
					<td valign="top">
							<table class="fieldset customer"  border="0">
							<tr valign="top"><td>
							<table>
								<tr>
									<td valign="top"class="font-header" >Dari </td>	
								</tr>
								<tr>
									<td valign="top"class="font-body"> '.($namasupplier = $r->NAMASUPPLIER.", ".($r->BADANUSAHA?$r->BADANUSAHA:"")).'<br>'.$alamat.$kota.$propinsi.$negara.'</td>
								</tr>
							</table>
							</td></tr>
							</table>
					</td>
					
					<td valign="top"valign="top">
							<table class="fieldset info_transaksi" border="0">
							<tr valign="top"><td>
							<table>
								
								<tr>
									<td valign="top"class="font-header">No. PO</td>
									<td valign="top"class="font-body">: '.$r->KODEPO.'</td>
								</tr>
								<tr>
									<td valign="top"class="font-header">Tgl. Trans</td>
									<td valign="top"class="font-body">: '.$r->TGLTRANS.'</td>
								</tr>
								<tr>
									<td valign="top"class="font-body">&nbsp;</td>
								</tr>
								<tr>
									<td valign="top"class="font-header">CP</td>
									<td valign="top"class="font-body">: '.$r->CONTACTPERSON.'</td>
								</tr>
								<tr>
									<td valign="top"class="font-header">Telp CP</td>
									<td valign="top"class="font-body">: '.$r->TELPCP.'</td>
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
		$sql = "select a.KODEPO,a.IDBARANG,a.JML,a.URUTAN,
				 c.KODEBARANG, c.NAMABARANG, a.SATUAN, a.KONVERSI, a.IDCURRENCY, d.SIMBOL, a.HARGA,a.DISCPERSEN,a.DISC,a.DISCKURS,a.SUBTOTAL,a.NILAIKURS,a.HARGAKURS,a.SUBTOTALKURS,a.PAKAIPPN,a.PPNPERSEN,a.PPNRP,a.PPH22PERSEN,a.PPH22RP
				from TPODTL a 
				inner join TPO a1 on a.IDPO = a1.IDPO  and a.idperusahaan = a1.idperusahaan
				inner join MBARANG c on a.IDBARANG = c.IDBARANG  and a.idperusahaan = c.idperusahaan
				inner join MCURRENCY d on a.IDCURRENCY = d.IDCURRENCY  and a.idperusahaan = d.idperusahaan
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDPO = {$idtrans} order by a.URUTAN";
						
		$rows = $CI->db->query($sql)->result();  
		
		$detail='
		<table width="620px" style="margin-top:10px;" border="0" >
			<tr>
				<th class="border-kiri center tbl-header border-atas border-kanan border-bawah" width="3%">NO</th>
				<td valign="top"class="tbl-header center border-atas border-kanan border-bawah"width="8%">Kode</td>
				<th class="center tbl-header border-atas border-kanan border-bawah" width="42%">Nama Barang</th>
				<th class="center tbl-header border-atas border-kanan border-bawah" width="5%">Jml</th>
				<th class="center tbl-header border-atas border-kanan border-bawah" width="5%">Sat</th>
				<th class="center tbl-header border-atas border-kanan border-bawah" width="11%">Harga</th>
				<th class="center tbl-header border-atas border-kanan border-bawah" width="8%">Disc</th>
				<th class="center tbl-header border-atas border-bawah border-kanan" width="16%">Subtotal</th>
			</tr>';
			
		
		
		$DPP = 0;
		$PPNRP = 0;
		$Subtotal = 0;
		$Pembulatan = 0;
		$Tax = 0;
		$halaman =1;
		$max_item = 10;
		$i = 0;
		$total = array();
		$totalBarang = 0;
		
		if(count($rows) == 0){
			echo $headerperusahaan;
			echo $header;
			echo $detail;
			for ($i=0; $i < $max_item; $i++) {	
					echo '<tr>
						<td valign="top"class=" font-body border-kanan border-kiri ">&nbsp;</td>
						<td valign="top"class=" font-body border-kanan">&nbsp;</td>
						<td valign="top"class=" font-body border-kanan">&nbsp;</td>
						<td valign="top"class=" font-body border-kanan">&nbsp;</td>
						<td valign="top"class=" font-body border-kanan">&nbsp;</td>
						<td valign="top"class=" font-body border-kanan">&nbsp;</td>
						<td valign="top"class=" font-body border-kanan">&nbsp;</td>
						<td valign="top"class=" font-body border-kanan">&nbsp;</td>
					</tr>';
					}
			echo $footer;
		}
		
		foreach ($rows as $rs) {
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
					
			$DPP += $dpp;
			$PPNRP += $rs->PPNRP;
			$Tax += $rs->PPH22RP;
			$Subtotal += $rs->SUBTOTAL;
		
			
			//PPH
			$pph22='';

			if($Tax != 0)
			{
				$pph22='<tr><td valign="top"class="font-header right">PPH22</td>
					<td valign="top"class="font-body right">'.number($Tax, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td></tr>';
			}

		}
		
		
		echo $headerperusahaan;
		echo $header;
		echo $detail;
		
		foreach ($rows as $rs) {
		?>
			
			<tr>
				<td valign="top"class="border-kiri center font-body border-kanan" valign="top"><?=$i+1?>.</td>
				<td valign="top"class="border-kiri center font-body border-kanan" valign="top"><?=$rs->KODEBARANG?></td>
				<td valign="top"class="font-body border-kanan" valign="top"><div class="font-body" style="font-weight:bold; " valign="top"><?=$rs->NAMABARANG?></div><?=$rs->SPEKBARANG?></td>
				<td valign="top"class="center font-body border-kanan" valign="top"><?=number($rs->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])?></td>
				<td valign="top"class="center font-body border-kanan" valign="top"><?=$rs->SATUAN?></td>
				<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->HARGAKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
				<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->DISCKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
				<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->SUBTOTALKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
			</tr>
			
		<?php
		$i++;
		$totalBarang += $rs->JML;
		}
		
		$footer='
		<tr>
			<td valign="top"class="border-kiri border-bawah border-kanan"></td>
			<td valign="top"class="border-bawah border-kanan"></td>
			<td valign="top"class="border-bawah border-kanan"></td>
			<td valign="top"class="border-bawah border-kanan"></td>
			<td valign="top"class="border-bawah border-kanan"></td>
			<td valign="top"class="border-bawah border-kanan"></td>
			<td valign="top"class="border-bawah border-kanan"></td>
			<td valign="top"class="border-bawah border-kanan"></td>
		</tr>
		
			<tr>
				<td valign="top" colspan="5" valign="top">
				
					<table style="width:100%">
						<tr>
							<td valign="top"  colspan="7">
							<table>
								<tr valign="top">
								<td class="font-header">Total Barang</td>
								<td class="font-body ">: </td>
								<td class="font-body ">'.number($totalBarang,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']).'</td>
								</tr>
								<tr valign="top">
								<td class="font-body ">Catatan</td>
								<td class="font-body ">: </td>
								<td class="font-body ">'.format_remark($r->CATATAN).'</td>
								</tr>
							</table>
							</td>
						</tr>
						<tr>
							<td valign="top" valign="top">
								<table width="100%">
								<tr><td>&nbsp;</td></tr>
									<tr>
										<td valign="top"class="font-body center">Pembuat</td>
									</tr>
									
									<tr>
									<td valign="top"class="font-body center" height="40px"></td>
									</tr>
									<tr>
									<td valign="top"class="font-body center">'.nama_terang($r->USERNAME).'</td>
									</tr>
								</table>
							</td>
							<td valign="top" valign="top">
								<table width="100%">
								<tr> &nbsp; </tr>
									<tr>
										<td valign="top"class="font-body center">Menyetujui</td>
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
				<td valign="top"colspan="4	" valign="top">
					<table width="100%">
						<tr>
							<td valign="top"class="font-header right" width="54%" >Total</td>
							<td valign="top"class="font-body right">'.number($Subtotal, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
						</tr>
						<tr>
							<td valign="top"class="font-header right" >DPP</td>
							<td valign="top"class="font-body right">'.number($DPP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
						</tr>
						<tr>
							<td valign="top"class="font-header right">PPN</td>
							<td valign="top"class="font-body right">'.number($PPNRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
						</tr>
						<tr>
							'.$pph22.'
						</tr>
						<tr>
							'.$pembulatanrp.'
						</tr>
						<tr>
							<td valign="top"class="font-header right">Grand Total</td>
							<td valign="top"class="font-body right border-atas">'.number($DPP + $PPNRP+ $r->PEMBULATAN-$Tax, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		</div>
		';	
		echo $footer;
		?>
		
		
	

</body>
</html>
