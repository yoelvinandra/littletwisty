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

$query  = $CI->db->query("select namalokasi from mlokasi where 1=1 $whereLokasi and idperusahaan = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} '")->result();
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
    <title> ..:: Laporan Barang ::.. </title>
	<style>
	#header {
		font-family: Verdana, Geneva, sans-serif;
		font-size: 16px;
		font-weight: bold;
	}
	
	#detail{
		font-family: Tahoma, Geneva, sans-serif;
		font-size:9px;
		padding:5px;
	}
	.tebal{
		font-weight:bold;
	}
	.kanan{
		text-align:right;	
	}
	.tengah{
		text-align:center;	
	}
	</style>
</head>
<body>
	<div id="header"><?php echo $_SESSION[NAMAPROGRAM]['NAMAPERUSAHAAN'] ?></div>
	<div id="header">Laporan Barang</div>
	<?php
	$tbl = new html_table();

	$tbl->set_hr(true);
	$tbl->set_tr(array('bgcolor'=>'#9CD0ED'));
	$tbl->set_th(array(
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>30,  'values'=>'No.'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100,  'values'=>'Kode Barang'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>300, 'values'=>'Nama Barang'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50, 'values'=>'Satuan Besar'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80, 'values'=>'Konversi 1'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50, 'values'=>'Satuan Kecil'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'Akun Persediaan'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>200, 'values'=>'Nama Akun Persediaan'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'Akun HPP'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>200, 'values'=>'Nama Akun HPP'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'Harga Beli'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'Harga Jual '),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>200, 'values'=>'Catatan'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'User Input'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'User Ubah'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'User Hapus'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50,  'values'=>'Tgl. Input'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50,  'values'=>'Tgl. Ubah'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50,  'values'=>'Tgl. Hapus'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>30,  'values'=>'Status'),
	));
	
	$urutan = 0;

	foreach($query as $item) {
		$urutan++;
		$warna = ($urutan % 2==0) ? '#FFFFCC' : '#FFFFFF';
		$warna = ($item->STATUS==0) ? '#bdbdbd' : $warna;
		$tbl->set_tr(array('bgcolor'=>$warna));
		$tbl->set_td(array(
			array('id'=>'detail','valign'=>'top', 'align'=>'right', 'values'=>$urutan.'.'),
			array('id'=>'detail','valign'=>'top', 'align'=>'center', 'values'=>$item->KODEBARANG),
			array('id'=>'detail','valign'=>'top', 'values'=>$item->NAMABARANG),
			array('id'=>'detail','valign'=>'top','align'=>'center', 'values'=>$item->SATUAN),
			array('id'=>'detail','valign'=>'top','align'=>'right', 'values'=>number($item->KONVERSI1,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])),
			array('id'=>'detail','valign'=>'top','align'=>'center', 'values'=>$item->SATUAN2),
			array('id'=>'detail','valign'=>'top', 'values'=>$item->KODEPERKIRAAN),
			array('id'=>'detail','valign'=>'top', 'values'=>$item->NAMAPERKIRAAN),
			array('id'=>'detail','valign'=>'top', 'values'=>$item->KODEHPP),
			array('id'=>'detail','valign'=>'top', 'values'=>$item->NAMAHPP),
			array('id'=>'detail','valign'=>'top','align'=>'right', 'values'=>number($item->HARGABELI,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])),
			array('id'=>'detail','valign'=>'top', 'align'=>'right','values'=>number($item->HARGAJUAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])),
			array('id'=>'detail','valign'=>'top', 'values'=>$item->CATATAN),			
			array('id'=>'detail','valign'=>'top', 'align'=>'center','values'=>$item->USERENTRY),
			array('id'=>'detail','valign'=>'top', 'align'=>'center','values'=>$item->USERUBAH),
			array('id'=>'detail','valign'=>'top', 'align'=>'center','values'=>$item->USERHAPUS),
			array('id'=>'detail','valign'=>'top', 'align'=>'center', 'values'=>ubah_tgl_indo($item->TGLENTRY)),	
			array('id'=>'detail','valign'=>'top', 'align'=>'center', 'values'=>ubah_tgl_indo($item->TGLUBAH)),	
			array('id'=>'detail','valign'=>'top', 'align'=>'center', 'values'=>ubah_tgl_indo($item->TGLHAPUS)),	
			array('id'=>'detail','valign'=>'top', 'align'=>'center', 'values'=>($item->STATUS==1 ? 'Aktif' : 'Non')),
		));
	}
	
	echo $tbl->generate_table();
	?>
</body>
</html>