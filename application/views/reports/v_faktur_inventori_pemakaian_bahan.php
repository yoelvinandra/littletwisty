<!DOCTYPE HTML>
<html>
<head>
<title> ..:: Faktur Pemakaian ::.. </title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/faktur_style.css" >
<style type="text/css">
.satuan{
	width:20%;
}
.jumlah{
	width:100px;
}
.tabel_perusahaan tr td{
	width:75%;
}
.info_transaksi{
	width:217px;
}
.customer{
	width:400px;
}
table .fieldset{
	height:65px;
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
$informasirekening = $_SESSION[NAMAPROGRAM]['INFORMASIREKENING'];
$CI =& get_instance();	
$CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
$currency = $_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'];

//$transaksi = $CI->model_master_config->getConfig('TBBM','TRANSREFERENSI');


$sql = "select c.USERNAME,a.KODEPEMAKAIANBAHAN, a.TGLTRANS,b.NAMALOKASI
				from TPEMAKAIANBAHAN a
				left join MLOKASI b on a.IDLOKASI = b.IDLOKASI
				left join MUSER c on a.USERENTRY = c.USERID 
				left join MUSER d on a.USERBATAL = d.USERID	
				where a.IDPEMAKAIANBAHAN = ? && a.IDPERUSAHAAN =  ".$perusahaan."";
				
$r = $CI->db->query($sql, [$idtrans])->row();
//TRANSAKSI HEADER
if($transaksi == "HEADER")
{
	$transheader='<tr>
		<td valign="top"class="font-header">No. SO</td>
		<td valign="top"class="font-body">: '.$r->KODESO.' &nbsp </td>
	</tr>';
}

//TRANSAKSI DETAIL
if($transaksi == "DETAIL")
{
	$transdetail='<th class="center tbl-header border-atas border-bawah border-kanan" width="15%">No.SO</th>';
	$borderdetail='<td valign="top"class="font-body border-kanan"></td>';
	$borderbawahdetail='<td valign="top"class="font-body border-bawah border-kanan"></td>';
}

									
//CUSTOMER, SUBCUSTOMER
if($r->NAMASUBCUSTOMER != null){
	$namasubcustomer= "(".$r->NAMASUBCUSTOMER.")" ;
}

//ALAMAT KIRIM
	if(alamat_length($r->ALAMATKIRIM) == null){ 
	$alamatkirim= alamat_length($r->ALAMATCUSTOMER);
	}else{
	$alamatkirim= alamat_length($r->ALAMATKIRIM);
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
					<td valign="top" rowspan="3" class="title"> PEMAKAIAN</td>

				</tr>
				<tr>
					<td valign="top"class="font-body">'.$alamatp.$kotap.'</td>	
				</tr>
			</table>
		';
	 ?>
	

			<?php $header='
				<table border="0">
					<tr>
					<td valign="top"valign="top" class="info_transaksi">
								<table class="fieldset info_transaksi" border="0">
								<tr valign="top"><td>
								<table>
									'.$transheader.'
									<tr>
										<td valign="top"class="font-header">No. Pemakaian</td>
										<td valign="top"class="font-body">: '.$r->KODEPEMAKAIANBAHAN.'</td>
									</tr>
									<tr>
										<td valign="top"class="font-header">Lokasi</td>
										<td valign="top"class="font-body">: '.$r->NAMALOKASI.'</td>
									</tr>
									<tr>
										<td valign="top"class="font-header">Tgl. Trans</td>
										<td valign="top"class="font-body">: '.$r->TGLTRANS.' </td>
									</tr>
								</table>
								</td></tr>	
								</table>
						</td>
						<td valign="top"valign="top" class="customer">
									<table border="0" class="fieldset customer" >
									<tr valign="top"><td>
									<table>
										<tr>
											<td valign="top"class="font-body">Catatan </td>
											<td valign="top"class="font-body">: </td>
											<td valign="top"class="font-body">'.$r->CATATAN.' </td>
										</tr>
									</table>
									</td></tr>
									</table>
						</td>
					</tr>
				</table>';
				?>

			<!-- DETAIL BARANG -->
			<?php
			$sql = "select b.KODEBARANG,b.NAMABARANG, a.JML ,a.SATUAN 
				from TPEMAKAIANBAHANDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDPEMAKAIANBAHAN = '{$idtrans}' order by a.URUTAN";
			$rows = $CI->db->query($sql)->result();
			
			$detail='<table width="100%" class="tabel_detail" border="0">
				<tr>
					<th class="border-kiri center tbl-header no border-atas border-bawah border-kanan" >NO</th>
					<th class="center tbl-header border-atas border-bawah border-kanan" width="11%">Kode</th>
					<th class="center tbl-header border-atas border-bawah border-kanan" width="51%">Nama</th>
					<th class="center tbl-header border-atas border-bawah border-kanan" width="6%">Jml</th>
					<th class="center tbl-header border-atas border-bawah border-kanan" width="6%" >Sat</th>
				</tr>';
				
			$footer = '<tr>
							<td valign="top"class="border-kiri border-bawah border-kanan"></td>
							<td valign="top"class="border-bawah border-kanan"></td>
							<td valign="top"class="border-bawah border-kanan"></td>
							<td valign="top"class="border-bawah border-kanan"></td>
							<td valign="top"class="border-bawah border-kanan"></td>
							<td valign="top"class="border-bawah border-kanan"></td>
						</tr>
					</table>
				<table>
				
			</table>
				<br>
					<table style="width:100%">
						<tr>
							<td valign="top"width="10%" valign="top">
								<table width="100%">
									<tr>
										<td valign="top"class="font-body center">Pembuat</td>
									</tr>
									<tr>
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
							<td valign="top"width="10%" valign="top">
								<table width="100%">
									<tr>
										<td valign="top"class="font-body center">Menyetujui</td>
									</tr>
									<tr>
									<tr>
									<td valign="top"class="font-body center" height="40px"></td>
									</tr>
									<tr>
										<td valign="top"class="font-body center">(................)</td>
									</tr>
									<tr>
									<td valign="top"class="font-body center">&nbsp;</td>
									</tr>
									
								</table>
							</td>
							
						</tr>
					</table>
					</td>
				</tr>
			</table>
			<div class="font-body" style="margin-left:550px; margin-top:-15px;">Hal : 1 of 1</div>
		</div>
		';
		
			
			?>
			
					<?php
					$DPP = 0;
					$PPNRP = 0;
					$Subtotal = 0;
					$Pembulatan = 0;

					$max_item = 10;
					$halaman =1;
					$i = 0;
					$total = array();
					
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
								
							if($i%$max_item==0)
							{
								echo $headerperusahaan;
								echo $header;
								echo $detail;
							}
					?>
						<tr>
							<td valign="top"class="border-kiri border-kanan center font-body "><?=$i+1?>.</td>
							<td valign="top"class="border-kiri border-kanan center font-body "><?=$rs->KODEBARANG?>.</td>
							<td valign="top"class="border-kanan font-body "><?=$rs->NAMABARANG?></td>
							<td valign="top"class="border-kanan center font-body "><?=number($rs->JML, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])?></td>
							<td valign="top"class="center font-body border-kanan "><?=$rs->SATUAN?></td>
							
						</tr>
						
					
						
					<?php
						$DPP += $dpp;
						$PPNRP += $rs->PPNRP;
						$Pembulatan += $r->PEMBULATAN;
						
						//PPH, PEMBULATAN
						$pph22='';
						$pembulatanrp='';

						if($Tax != 0)
						{
							$pph22='<tr><td valign="top"class="font-header right">PPH22</td>
								<td valign="top"class="font-body right">'.number($Tax, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td></tr>';
						}

						if( $r->PEMBULATAN != 0){ 
							$pembulatanrp='<tr><td valign="top"class="font-header right">Pembulatan</td>
							<td valign="top"class="font-body  right" >'.number($r->PEMBULATAN, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']).'</td></tr>';
						}
							
					
					?>
						
			<!-- FOOTER -->
			<?php
			$footer = '<tr>
							<td valign="top"class="border-kiri border-bawah border-kanan"></td>
							<td valign="top"class="border-bawah border-kanan"></td>
							<td valign="top"class="border-bawah border-kanan"></td>
							<td valign="top"class="border-bawah border-kanan"></td>
							<td valign="top"class="border-bawah border-kanan"></td>
							
						</tr>
					</table>
				<table>
			</table>
				<br>
					<table style="width:100%">
						<tr>
							<td valign="top"width="10%" valign="top">
								<table width="100%">
									<tr>
										<td valign="top"class="font-body center">Pembuat</td>
									</tr>
									<tr>
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
							<td valign="top"width="10%" valign="top">
								<table width="100%">
									<tr>
										<td valign="top"class="font-body center">Menyetujui</td>
									</tr>
									<tr>
									<tr>
									<td valign="top"class="font-body center" height="40px"></td>
									</tr>
									<tr>
										<td valign="top"class="font-body center">(................)</td>
									</tr>
									<tr>
									<td valign="top"class="font-body center">&nbsp;</td>
									</tr>
									
								</table>
							</td>
							
						</tr>
					</table>
					</td>
				</tr>
			</table>
			<div class="font-body" style="margin-left:550px; margin-top:-15px;">'.halaman($halaman,count($rows),$max_item).'</div>
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
							
						</tr>';
					}
					
					$DPP = 0;
					$PPNRP =0;
					$Tax = 0;
					$Subtotal =0;
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
							
						</tr>';
					}
				$DPP = 0;
				$PPNRP =0;
				$Tax = 0;
				$Subtotal =0;
				$count=0;
				echo $footer;
			}
			?>
		
</body>
</html>
