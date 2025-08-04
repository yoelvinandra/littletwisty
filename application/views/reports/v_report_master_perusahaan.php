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

$query = $CI->db->query($sql)->result();

?>
<!DOCTYPE HTML>
<html>
<head>
    <title> ..:: Laporan Perusahaan ::.. </title>
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
	<div id="header">LAPORAN PERUSAHAAN</div>
	<?php
	$tbl = new html_table();

	$tbl->set_hr(true);
	
	$tbl->set_tr(array('bgcolor'=>'#9CD0ED'));
	$tbl->set_th(array(
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50,  'values'=>'Kode'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>200, 'values'=>'Nama Perusahaan'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>150, 'values'=>'Alamat'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>150, 'values'=>'Kota'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'Propinsi'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'Negara'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'Kode Pos'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'Telp'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'Fax'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>150, 'values'=>'NPWP'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>400, 'values'=>'Catatan'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'User Entry'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'User Ubah'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'User Hapus'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50,  'values'=>'Tgl. Entry'),
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
			array('valign'=>'top','id'=>'detail', 'align'=>'center','values'=>$item->KODEPERUSAHAAN),
			array('valign'=>'top','id'=>'detail', 'values'=>$item->NAMAPERUSAHAAN),
			array('valign'=>'top','id'=>'detail', 'values'=>$item->ALAMAT),
			array('valign'=>'top','id'=>'detail', 'values'=>$item->KOTA),
			array('valign'=>'top','id'=>'detail', 'values'=>$item->PROPINSI),
			array('valign'=>'top','id'=>'detail', 'values'=>$item->NEGARA),
			array('valign'=>'top','id'=>'detail', 'align'=>'center', 'values'=>$item->KODEPOS),
			array('valign'=>'top','id'=>'detail', 'align'=>'center', 'values'=>$item->TELP),
			array('valign'=>'top','id'=>'detail', 'align'=>'center', 'values'=>$item->FAX),
			array('valign'=>'top','id'=>'detail', 'align'=>'center', 'values'=>$item->NPWP),
			array('valign'=>'top','id'=>'detail', 'values'=>$item->CATATAN),
			array('valign'=>'top','id'=>'detail', 'align'=>'center', 'values'=>$item->USERENTRY),
			array('valign'=>'top','id'=>'detail', 'align'=>'center', 'values'=>$item->USERUBAH),
			array('valign'=>'top','id'=>'detail', 'align'=>'center', 'values'=>$item->USERHAPUS),
			array('valign'=>'top','id'=>'detail', 'align'=>'center', 'values'=>$item->TGLENTRY),
			array('valign'=>'top','id'=>'detail', 'align'=>'center', 'values'=>$item->TGLUBAH),
			array('valign'=>'top','id'=>'detail', 'align'=>'center', 'values'=>$item->TGLHAPUS),
			array('valign'=>'top','id'=>'detail', 'align'=>'center', 'values'=>($item->STATUS==1 ? 'Active' : 'Non Active')),
		));
	}
	
	echo $tbl->generate_table();
	?>
</body>
</html>