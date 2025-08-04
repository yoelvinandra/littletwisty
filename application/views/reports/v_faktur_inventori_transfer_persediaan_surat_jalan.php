<!DOCTYPE HTML>
<html>
<head>
<title> ..:: Surat Jalan ::.. </title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/faktur_style.css" >
<style type="text/css">
.satuan{
	width:20%;
}
.jumlah{
	width:100px;
}
.tabel_perusahaan tr td{
	width:70%;
}
.info_transaksi{
	width:317px;
}
.customer{
	width:300px;
}
table .fieldset{
	height:95px;
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


$sql = "select a.*,b.KODELOKASI as KODELOKASIASAL,b.NAMALOKASI as NAMALOKASIASAL,b1.KODELOKASI as KODELOKASITUJUAN,b1.NAMALOKASI as NAMALOKASITUJUAN,c.USERNAME,d.USERNAME as USERBATAL
				from TTRANSFER a
				left join MLOKASI b on a.IDLOKASIASAL = b.IDLOKASI  and a.idperusahaan = b.idperusahaan
				left join MLOKASI b1 on a.IDLOKASITUJUAN = b1.IDLOKASI  and a.idperusahaan = b1.idperusahaan
				left join MUSER c on a.USERENTRY = c.USERID  and a.idperusahaan = c.idperusahaan
				left join MUSER d on a.USERBATAL = d.USERID  and a.idperusahaan = d.idperusahaan
				where a.IDTRANSFER = ? && a.IDPERUSAHAAN =  ".$perusahaan."";
				
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
					
					<td valign="top" class="font-header"> <img src="'.base_url().'assets/'.$_SESSION[NAMAPROGRAM]['KODEPERUSAHAAN'].'/logo.png" class="user-image" alt="User Image" height="26"></td>
					<td valign="top" rowspan="3" class="title">SURAT JALAN</td>

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
										<td valign="top"class="font-header">No. Transfer</td>
										<td valign="top"class="font-body">: '.$r->KODETRANSFER.'</td>
									</tr>
									<tr>
										<td valign="top"class="font-header">Asal</td>
										<td valign="top"class="font-body">: '.$r->NAMALOKASIASAL.'</td>
									</tr>
									<tr>
										<td valign="top"class="font-header">Tujuan</td>
										<td valign="top"class="font-body">: '.$r->NAMALOKASITUJUAN.'</td>
									</tr>
									<tr>
										<td valign="top"class="font-header">Tgl. Trans</td>
										<td valign="top"class="font-body">: '.$r->TGLTRANS.' </td>
									</tr>
									<tr>
										<td valign="top"class="font-header">Tgl. Kirim</td>
										<td valign="top"class="font-body">: '.$r->TGLKIRIM.' </td>
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
			$sql = "select a.*,b.KODEBARANG,b.NAMABARANG,0 as IDSYARATBAYAR,'' as NAMASYARATBAYAR
				from TTRANSFERDTL a
				left join MBARANG b on a.IDBARANG = b.IDBARANG  and a.idperusahaan = b.idperusahaan
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDTRANSFER = '{$idtrans}' order by a.URUTAN";
			$rows = $CI->db->query($sql)->result();
			
			$detail='<table width="100%" class="tabel_detail" border="0">
				<tr>
					<th class="border-kiri center tbl-header no border-atas border-bawah border-kanan" >NO</th>
					<th class="center tbl-header border-atas border-bawah border-kanan" width="7%">Kode</th>
					<th class="center tbl-header border-atas border-bawah border-kanan" width="43%">Nama</th>
					<th class="center tbl-header border-atas border-bawah border-kanan" width="6%">Jml</th>
					<th class="center tbl-header border-atas border-bawah border-kanan" width="6%" >Sat</th>
					<th class="center tbl-header border-atas border-bawah border-kanan" width="12%" >Harga</th>
				</tr>';
					$i = 0;
					
					echo $headerperusahaan;
					echo $header;
					echo $detail;
					
					foreach ($rows as $rs) {
					    $totalBarang += $rs->JML;
					?>
						<tr>
							<td valign="top"class="border-kiri border-kanan center font-body "><?=$i+1?>.</td>
							<td valign="top"class="border-kiri border-kanan center font-body "><?=$rs->KODEBARANG?></td>
							<td valign="top"class="border-kanan font-body "><?=$rs->NAMABARANG?></td>
							<td valign="top"class="border-kanan center font-body "><?=number($rs->JML, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])?></td>
							<td valign="top"class="center font-body border-kanan "><?=$rs->SATUAN?></td>
							<td valign="top"class="border-kanan right font-body "><?=number($rs->HARGA, true, $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?></td>
						</tr>
						
			<!-- FOOTER -->
			<?php
			$i++;
				}
				
				
				
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
		</div>
		';
						echo $footer;
			
	?>
		
</body>
</html>
