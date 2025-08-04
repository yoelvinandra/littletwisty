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
    <title>..:: Laporan Master User ::..</title>
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
	<div align="left" style="width:950px">
		<div id="header">LAPORAN MASTER USER</div>
		<hr>
		<?php
	
	$tbl = new html_table();

	$tbl->set_tr(array('bgcolor'=>'#9CD0ED'));
	$tbl->set_th(array(
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>30,  'values'=>'User ID'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Nama'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Akses Utama'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'User Input'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'User Ubah'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'User Hapus'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Tgl Input'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Tgl Ubah'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Tgl Hapus'),

	));
	
	foreach($query as $item) {
		$urutan++;
		$warna = ($urutan % 2==0) ? '#FFFFCC' : '#FFFFFF';
		$warna = ($item->STATUS==0) ? '#bdbdbd' : $warna;
		
		$tbl->set_tr(array('bgcolor'=>$warna));
		$tbl->set_td(array(
			array('id'=>'detail', 'values'=>$item->USERID),
			array('id'=>'detail', 'values'=>$item->USERNAME),
			array('id'=>'detail', 'values'=>$item->AKSESUTAMA),
			array('id'=>'detail', 'align'=>'center', 'values'=>$item->USERENTRY),
			array('id'=>'detail', 'align'=>'center', 'values'=>$item->USERUBAH),
			array('id'=>'detail', 'align'=>'center', 'values'=>$item->USERHAPUS),
			array('id'=>'detail', 'align'=>'center', 'values'=>$item->TGLENTRY),
			array('id'=>'detail', 'align'=>'center', 'values'=>$item->TGLUBAH),
			array('id'=>'detail', 'align'=>'center', 'values'=>$item->TGLHAPUS),
		));
	}
	
	echo $tbl->generate_table();
	?>
	</div>
</body>
</html>