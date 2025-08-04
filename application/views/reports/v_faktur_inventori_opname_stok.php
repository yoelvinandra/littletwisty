<!DOCTYPE HTML>
<html>
<head>
<title> ..:: Faktur Bukti Opname Stok ::.. </title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/faktur_style.css" >
<style type="text/css">
.tabel_perusahaan tr td{
	width:70%;
}

.info_transaksi{
	width:260px;
}
.customer{
	width:357px;
}

.kode{
	width:120px;
}
.jumlah{
	width:30px;
}
.satuan{
	width:30px;
}
table .fieldset{
	height:80px;
}

</style>
</head>

<body width="100%" onload="window.print();">

<!-- BAGIAN HEADER -->
<?php
$perusahaan = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
$informasirekening = $_SESSION[NAMAPROGRAM]['INFORMASIREKENING'];
$CI =& get_instance();	
$CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
$currency = $_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'];
$transaksi = $CI->model_master_config->getConfig('TBBM','TRANSREFERENSI');


$sql = "select a.*,b.KODELOKASI,b.NAMALOKASI
				from TOPNAMESTOK a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI  and a.idperusahaan = b.idperusahaan
				where a.IDOPNAMESTOK = ? && a.idperusahaan = ".$perusahaan."";
			
$r = $CI->db->query($sql, [$idtrans])->row();

$sql = "select distinct  * from MPERUSAHAAN where IDPERUSAHAAN = ".$perusahaan."";

$rp = $CI->db->query($sql)->row();

?>

<table class="faktur">
	<tr valign="top">
		<td>
		<table border="0" class="tabel_perusahaan">
			<tr>
				
				<td valign="top"class="font-header"><?= $rp->NAMAPERUSAHAAN ?></td>
				<td valign="top" rowspan="3" class="title"> OPNAME STOK </td>

			</tr>
			<tr>
				<td valign="top"class="font-body"><?= $rp->ALAMAT?> <?php if($rp->KOTA != null){?> - <?php }?> <?= $rp->KOTA ?> </td>	
			</tr>
		</table>

		<div>
			<table border="0">
				<tr>
					<td valign="top"valign="top" class="info_transaksi">
						<table class="fieldset info_transaksi" border="0">
							<tr valign="top"><td>
							<table>
								<tr>
									<td valign="top"class="font-header" >No. Opname Stok</td>
									<td valign="top"class="font-body">: <?= $r->KODEOPNAMESTOK ?></td>
								</tr>
								<tr>
									<td valign="top"class="font-header">Tgl Trans </td>
									<td valign="top"class="font-body">: <?= $r->TGLTRANS ?></td>
								</tr>
								<?php if($transaksi == "HEADER"){?>
									<?php if($r->JENISTRANSAKSI == "PEMBELIAN"){?>
									<tr>	
										<td valign="top"class="font-header" >No.PO </td>
									</tr>	
									<tr>
										<td valign="top"class="font-body" > <?= $r->KODETRANSREFERENSI ?> &nbsp </td>
									</tr>
									<?php }?>
									<?php if($r->JENISTRANSAKSI == "RETUR"){?>
									<tr>	
										<td valign="top"class="font-header" >No. Retur</td>
									</tr>	
									<tr>
										<td valign="top"class="font-body" > <?= $r->KODETRANSREFERENSI ?> &nbsp </td>
									</tr>
									<?php }?>
									<?php if($r->JENISTRANSAKSI == "TRANSFER"){?>
									<tr>	
										<td valign="top"class="font-header" >No. Transfer</td>
									</tr>	
									<tr>
										<td valign="top"class="font-body" > <?= $r->KODETRANSREFERENSI ?> &nbsp </td>
									</tr>
									<?php }?>
								<?php } ?>
								<tr>
									<td valign="top"class="font-header">Lokasi </td>
									<td valign="top"class="font-body">: <?= $r->NAMALOKASI ?></td>
								</tr>
							</table>
							</td></tr>
						</table>
					</td>
					<td valign="top"valign="top" class="customer">
							<table class="fieldset customer" border="0">
								<tr valign="top"><td>
								<table>
									<tr>
										<td valign="top"class="font-body">Catatan </td>
										<td valign="top"class="font-body">: </td>
										<td valign="top"class="font-body"><?= $r->CATATAN ?> </td>
									</tr>
								</table>
								</td></tr>
							</table>
						</td>
				</tr>
			</table>

		<!-- DETAIL BARANG -->
		<?php
		$sql = "select a.*,b.KODEBARANG, b.NAMABARANG
				from TOPNAMESTOKDTL a
				left join TOPNAMESTOK a1 on a.IDOPNAMESTOK = a1.IDOPNAMESTOK  and a.idperusahaan = a1.idperusahaan
				left join MBARANG b on a.IDBARANG = b.IDBARANG  and a.idperusahaan = b.idperusahaan
				where a.IDOPNAMESTOK = '{$idtrans}' && a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} order by a.URUTAN";
			$rows = $CI->db->query($sql)->result();

		?>

			<table width="100%" class="faktur_detail" border="0">
			<tr>

				<th class="border-kiri no center tbl-header border-atas border-bawah border-kanan">NO</th>
				<?php if($transaksi == "DETAIL"){?>
					<?php if($r->JENISTRANSAKSI == "PEMBELIAN"){?>	
						<th class="font-header kode center tbl-header border-atas border-bawah border-kanan">No.PO </th>
					<?php }?>
					<?php if($r->JENISTRANSAKSI == "RETUR"){?>	
						<th class="font-header kode center tbl-header border-atas border-bawah border-kanan">No. Retur</th>
					<?php }?>
					<?php if($r->JENISTRANSAKSI == "TRANSFER"){?>
						<th class="font-header kode center tbl-header border-atas border-bawah border-kanan">No. Transfer</th>	
					<?php }?>
					
				<?php }?>
				<th class="center tbl-header border-atas border-bawah border-kanan" width="300">Nama</th>
				<th class="center satuan tbl-header border-atas border-bawah border-kanan">Jml</th>                                    
				<th class="center satuan tbl-header border-atas border-bawah border-kanan">Sat</th>                                    
				
			</tr>
			<?php
			$DPP = 0;
			$PPNRP = 0;
			$Subtotal = 0;
			$Pembulatan = 0;

			$max_item = 11;
			$i = 1;
			$total = array();
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
			?>
				<tr>
					<td valign="top"class="border-kiri border-kanan center font-body "><?=$i?>.</td>
					<td valign="top"class="border-kanan font-body "><?=$rs->NAMABARANG?></td>
					<td valign="top"class=" center font-body border-kanan"><?=number($rs->JML, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])?></td>
					<td valign="top"class=" center font-body border-kanan"><?=$rs->SATUAN?></td>
					
				</tr>
			<?php
				$DPP += $dpp;
				$PPNRP += $rs->PPNRP;
				$Pembulatan += $r->PEMBULATAN;
				$i++;
			}

			?>
			<tr>
					<td  valign="top" class="border-kiri border-kanan border-bawah"></td>
					<td  valign="top" class="border-bawah border-kanan"></td>
					<td  valign="top" class="border-bawah border-kanan"></td>
		
					<td  valign="top" class="border-bawah border-kanan"></td>
					
			</tr>
			</table>

		<!-- FOOTER -->
		<br>
			<table style="width:100%">
				<tr>
					<td  valign="top" width="10%" valign="top">
					<table width="100%">
							<tr>
								<td  valign="top" class="font-body center">Pembuat</td>
							</tr>
							
							<tr>
							<td  valign="top" class="total center" height="30px"></td>
							</tr>
							<td  valign="top" class="total center">&nbsp;</td>
							<tr>
								<td  valign="top" class="font-body center">
								<?php $len = strlen($r->USERENTRY);
								if($len != null)
									{
									echo "(";
									for($i=0;$i<$len/2;$i++)
									{
										echo "&nbsp;";
									}
										echo $r->USERENTRY;
									for($i=$len/2;$i<$len;$i++)
									{
										echo "&nbsp;";
									}
									echo ")";
								}
								else
								{
									echo "(................)";
								}?>
								</td>
							</tr>
							
							
						</table>
					</td>
					<td  valign="top" width="10%" valign="top">
						<table width="100%">
							<tr>
								<td  valign="top" colspan="2" class="font-body center">Menyetujui</td>
							</tr>
							
							<tr>
							<td  valign="top" class="total2 center" height="30px"></td>
							</tr>
							<td  valign="top" class="total center">&nbsp;</td>
							<tr>
								<td  valign="top" valign="top" class="font-body center">(................)</td>
							</tr>
							
						</table>
					</td>
				</tr>
			</table>
			<!--<table style="width:100%">
				<tr>
					
					<td  valign="top" class=" center font-body">Putih:Penagihan</td>
				
					<td  valign="top" class=" center font-body">Merah:Finance</td>

					<td  valign="top" class=" center font-body">Kuning:Accounting</td>

					<td  valign="top" class=" center font-body">Hijau:Arsip</td>

					<td  valign="top" class=" center font-body">Biru:Customer</td>

				</tr>
			</table>-->
		</td>
	</tr>
</table>
</body>
</html>