<!DOCTYPE HTML>
<html>
<head>
<title> ..:: Faktur Pembelian ::.. </title>
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
	height:85px;
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

$transaksi = $CI->model_master_config->getConfig('TBELI','TRANSAKSIBBM');

$sql = "select a.*,f.SELISIH,b.KODELOKASI,b.NAMALOKASI,
				c.KODESUPPLIER,c.NAMASUPPLIER,c.BADANUSAHA,c.ALAMAT as ALAMATSUPPLIER,
				d.USERNAME,e.USERNAME as USERBATAL,a.NOINVOICESUPPLIER
				from TBELI a
				inner join MLOKASI b on a.IDLOKASI = b.IDLOKASI and a.IDPERUSAHAAN=b.IDPERUSAHAAN 
				inner join MSUPPLIER c on a.IDSUPPLIER = c.IDSUPPLIER  and a.idperusahaan = c.idperusahaan
				left join MUSER d on a.USERENTRY = d.USERID and d.IDPERUSAHAAN=b.IDPERUSAHAAN
				left join MUSER e on a.USERBATAL = e.USERID and e.IDPERUSAHAAN=b.IDPERUSAHAAN				
				inner join MSYARATBAYAR f on a.IDSYARATBAYAR = f.IDSYARATBAYAR  and a.idperusahaan = f.idperusahaan
				
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDLOKASI in (
					   select IDLOKASI
					   from MUSERLOKASI
					   where IDUSER = {$_SESSION[NAMAPROGRAM]['IDUSER']} and IDPERUSAHAAN={$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
				     )
				and a.IDBELI = ? ";
			
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
		<td valign="top"class="font-header">No. PO</td>
		<td valign="top"class="font-body">: '.$r->KODEPO.' &nbsp </td>
	</tr>';
}

//TRANSAKSI DETAIL
if($transaksi == "DETAIL")
{
	$transdetail='<th class="center tbl-header border-atas border-bawah border-kanan" width="15%">No.BBM</th>';
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
				<td valign="top" rowspan="3" class="title"> NOTA PEMBELIAN</td>

			</tr>
			<tr>
				<td valign="top"class="font-body">'.$alamatp.$kotap.'</td>	
			</tr>
		</table>
	'; ?>
	   
	<?php $header='<table border="0">
				<tr>
					<td valign="top"valign="top" class="customer">
							<table class="fieldset customer" border="0">
							<tr valign="top"><td>
							<table >
								<tr>
									<td valign="top"class="font-header" >Kepada Yth </td>	
								</tr>
								<tr>
									<td valign="top"class="font-body"> '.($namasupplier = $r->NAMASUPPLIER.", ".($r->BADANUSAHA?$r->BADANUSAHA:"")).'<br>'.$alamat.$kota.$propinsi.$negara.'&nbsp </td>
								</tr>
							</table>
							</td></tr>
							</table>
					</td>
					<td valign="top"valign="top" class="info_transaksi">
							<table class="fieldset info_transaksi" border="0">
							<tr valign="top"><td>
							<table>
								<td valign="top"class="font-header">No. Beli</td>
									<td valign="top"class="font-body">: '.$r->KODEBELI.'</td>
								<tr>
									<td valign="top"class="font-header">Tgl. Trans</td>
									<td valign="top"class="font-body">: '.$r->TGLTRANS.'</td>
								</tr>
								'.$transheader.'
							</table>
							</td></tr>
							</table>
					</td>
					
				</tr>
		'; ?>
		
		<!-- DETAIL BARANG -->
		<?php
		$sql = "select distinct a.KODEBELI,
				a.IDBARANG,c.KODEBARANG,c.NAMABARANG,
				a.JML,a.JMLBONUS,a.SATUAN, a.HARGA,a.DISCPERSEN,a.DISC,a.DISCKURS,a.SUBTOTAL,a.NILAIKURS,a.HARGAKURS,a.SUBTOTALKURS,a.URUTAN,a.PAKAIPPN,a.PPNPERSEN,a.PPNRP
				from TBELIDTL a 
				left join TBELI a1 on a.IDBELI = a1.IDBELI  and a.idperusahaan = a1.idperusahaan
				left join MBARANG c on a.IDBARANG = c.IDBARANG  and a.idperusahaan = c.idperusahaan
				left join MCURRENCY d on a.IDCURRENCY = d.IDCURRENCY  and a.idperusahaan = d.idperusahaan
 				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDBELI = {$idtrans} order by a.URUTAN";
						
		$rows = $CI->db->query($sql, [$idtrans])->result();  
		
		$detail ='<table width="620px" style="margin-top:10px; " border="0" >
				<tr>
					<th class="border-kiri center tbl-header border-atas border-kanan border-bawah" width="4%">NO</th>	
					'.$transdetail.'
					<th class="center tbl-header border-atas border-kanan border-bawah" width="37%">Nama Barang </th>
					<th class="center tbl-header border-atas border-kanan border-bawah" width="5%">Jml </th>
					<th class="center tbl-header border-atas border-kanan border-bawah" width="5%">Sat</th>
					<th class="center tbl-header border-atas border-kanan border-bawah" width="14%">Harga</th>
					<th class="center tbl-header border-atas border-kanan border-bawah" width="8%">Disc</th>
					<th class="center tbl-header border-atas border-bawah border-kanan" width="14%">Subtotal</th>
				</tr>
				';
				
		$footer='
		<tr>
				<td valign="top"class="border-kiri border-bawah border-kanan"></td>
				'.$borderbawahdetail.'
				<td valign="top"class="border-bawah border-kanan"></td>
				<td valign="top"class="border-bawah border-kanan"></td>
				<td valign="top"class="border-bawah border-kanan"></td>
				<td valign="top"class="border-bawah border-kanan"></td>
				<td valign="top"class="border-bawah border-kanan"></td>
				<td valign="top"class="border-bawah border-kanan"></td>
		</tr>
		<tr>
					<td valign="top"colspan="4" rowspan="2" valign="top">
					<table style="width:100%" >
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
						<tr>
							<td valign="top" valign="top">
								<table width="100%" >
								<tr> &nbsp; </tr>
									<tr>
										<td valign="top"class="font-body center">Pembuat</td>
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
							<td valign="top"valign="top">
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
					
					<td valign="top"colspan="3" valign="top">
						<table width="100%" >
							<tr>
								<td valign="top"class="font-header  right" width="60%">Total</td>
								<td valign="top"class="font-body rp right">'.number($Subtotal, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
							<tr>
								<td valign="top"class="font-header  right">DPP</td>
								<td valign="top"class="font-body rp right" >'.number($DPP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
							<tr>
								<td valign="top"class="font-header right">PPN</td>
								<td valign="top"class="font-body rp right" >'.number($PPNRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
							<tr>'.$pph22.'
							</tr>
							<tr>
								'.$pembulatanrp.'
							</tr>
							<tr>
								<td valign="top"class="font-header right">Grand Total</td>
								<td valign="top"class="font-body rp right border-atas" >'.number($DPP + $PPNRP+ $r->PEMBULATAN-$Tax, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
							
						</table>
					</td>
				</tr>
				</table>
				
				</table>
			<div class="font-body" style="margin-left:550px; margin-top:0px;">Hal : 1 of 1</div>	
			</div>
			
				';
				
		
		
		?>

		
			
		<?php
		$DPP = 0;
		$PPNRP = 0;
		$Subtotal = 0;
		$Pembulatan = 0;
		$Tax = 0;
		$halaman =1;
		$max_item = 10;
		$i = 0;
		$total = array();
		
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
			$Subtotal += $rs->SUBTOTALKURS;
			
			
			//PPH
			$pph22='';

			if($Tax != 0)
			{
				$pph22='<tr><td valign="top"class="font-header right">PPH22</td>
					<td valign="top"class="font-body right">'.number($Tax, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td></tr>';
			}
		}
		
		foreach ($rows as $rs) {
					
					
					
			if($i%$max_item==0)
			{
				echo $headerperusahaan;
				echo $header;
				echo $detail;
			}
		?>
			
			<tr>
				<td valign="top"class="border-kiri center font-body border-kanan" valign="top"><?=$i+1?>.</td>
				<?php if($transaksi == "DETAIL"){?>
					<td valign="top"class="font-body center border-kanan"valign="top" > <?= $rs->KODEBBM ?></td>
				<?php }?>
				<td valign="top"class="font-body border-kanan" valign="top"><?=$rs->NAMABARANG?></td>
				<td valign="top"class="font-body border-kanan center" valign="top"><?=number($rs->JML, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])?></td>
				<td valign="top"class="center font-body border-kanan" valign="top"><?=$rs->SATUAN?></td>
				<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->HARGAKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
				<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->DISCKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
				<td valign="top"class="right font-body border-kanan" valign="top"><?=number($rs->SUBTOTALKURS, true,  $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>

			</tr>
			
		<?php
			
			
			
		$footer='
		<tr>
				<td valign="top"class="border-kiri border-bawah border-kanan"></td>
				'.$borderbawahdetail.'
				<td valign="top"class="border-bawah border-kanan"></td>
				<td valign="top"class="border-bawah border-kanan"></td>
				<td valign="top"class="border-bawah border-kanan"></td>
				<td valign="top"class="border-bawah border-kanan"></td>
				<td valign="top"class="border-bawah border-kanan"></td>
				<td valign="top"class="border-bawah border-kanan"></td>
		</tr>
		<tr>
					<td valign="top"colspan="4" rowspan="2" valign="top">
					<table style="width:100%" >
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
						<tr>
							<td valign="top" valign="top">
								<table width="100%" >
								<tr> &nbsp; </tr>
									<tr>
										<td valign="top"class="font-body center">Pembuat</td>
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
							<td valign="top"valign="top">
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
					
					<td valign="top"colspan="3" valign="top">
						<table width="100%" >
							<tr>
								<td valign="top"class="font-header  right" width="60%">Total</td>
								<td valign="top"class="font-body rp right">'.number($Subtotal, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
							<tr>
								<td valign="top"class="font-header  right">DPP</td>
								<td valign="top"class="font-body rp right" >'.number($DPP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
							<tr>
								<td valign="top"class="font-header right">PPN</td>
								<td valign="top"class="font-body rp right" >'.number($PPNRP, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
							<tr>'.$pph22.'
							</tr>
							<tr>
								'.$pembulatanrp.'
							</tr>
							<tr>
								<td valign="top"class="font-header right">Grand Total</td>
								<td valign="top"class="font-body rp right border-atas" >'.number($DPP + $PPNRP+ $r->PEMBULATAN-$Tax, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td>
							</tr>
							
						</table>
					</td>
				</tr>
				</table>
				
				</table>
			<div class="font-body" style="margin-left:550px; margin-top:0px;">'.halaman($halaman,count($rows),$max_item).'</div>	
			</div>
			
				'; 
			
			$count++;
			$i++;
			
			//cek untuk munculkan footer
			if($count == $max_item)
			{
				for (; $count < $max_item; $count++) {
					
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

				$count=0;
				$halaman++;
				echo $footer;
				
			}
			
							
		}
		
		//cek waktu kurang dari lima, untuk munculkan footer
			if($count < $max_item && $count != 0)
			{
				for (; $count < $max_item; $count++) {
					
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
					
				$count=0;
				echo $footer;
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
		<?php 
		?>

</body>
</html>
