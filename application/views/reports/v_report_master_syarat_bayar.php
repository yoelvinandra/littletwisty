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
	<title> ..:: Laporan Syarat Bayar ::.. </title>
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
	<div id="header">Laporan Syarat Bayar</div>
	<?php
	
	$tbl = new html_table();

	$tbl->set_hr(true);

	$tbl->set_tr(array('bgcolor'=>'#9CD0ED'));
	$tbl->set_th(array(
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>30,  'values'=>'Kode'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Nama'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Lama Bayar (Hari)'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>250, 'values'=>'Catatan'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'User Input'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'User Ubah'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'User Hapus'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50,  'values'=>'Tgl. Input'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50,  'values'=>'Tgl. Ubah'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50,  'values'=>'Tgl. Hapus'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>30,  'values'=>'Status'),
	));
	
	foreach($query as $item) {
		$urutan++;
		$warna = ($urutan % 2==0) ? '#FFFFCC' : '#FFFFFF';
		$warna = ($item->STATUS==0) ? '#bdbdbd' : $warna;
		
		$tbl->set_tr(array('bgcolor'=>$warna));
		$tbl->set_td(array(
			array('id'=>'detail', 'values'=>$item->KODESYARATBAYAR),
			array('id'=>'detail', 'values'=>$item->NAMASYARATBAYAR),
			array('id'=>'detail', 'values'=>$item->SELISIH),
			array('id'=>'detail', 'values'=>$item->REMARK),
			array('id'=>'detail', 'values'=>$item->USERENTRY),
			array('id'=>'detail', 'values'=>$item->USERUBAH),
			array('id'=>'detail', 'values'=>$item->USERHAPUS),
			array('id'=>'detail', 'align'=>'center', 'values'=>ubah_tgl_indo($item->TGLENTRY)),
			array('id'=>'detail', 'align'=>'center', 'values'=>ubah_tgl_indo($item->TGLUBAH)),
			array('id'=>'detail', 'align'=>'center', 'values'=>ubah_tgl_indo($item->TGLHAPUS)),
			array('id'=>'detail', 'align'=>'center', 'values'=>($item->STATUS==1 ? 'Aktif' : 'Non')),
		));
	}
	
	echo $tbl->generate_table();
	?>
</body>
</html>