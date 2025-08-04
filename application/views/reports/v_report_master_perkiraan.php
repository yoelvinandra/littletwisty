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
	<title> ..:: Laporan Master Perkiraan ::.. </title>
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
	<div id="header" class="tebal">MASTER PERKIRAAN</div>
	<?php
	$tbl = new html_table();

	$tbl->set_hr(true);
	
	$tbl->set_tr(array('bgcolor'=>'#9CD0ED'));
	$tbl->set_th(array(
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>30,  'values'=>'No.'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>70,  'values'=>'Akun'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>250, 'values'=>'Nama Akun'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50,  'values'=>'Sub Akun Dari'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>140, 'values'=>'Group'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>60,  'values'=>'Saldo'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50,  'values'=>'Tipe'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50,  'values'=>'Akun Kas/Bank'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50,  'values'=>'ID Kas/Bank'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'User Input'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'User Ubah'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>100, 'values'=>'User Hapus'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>70,  'values'=>'Tgl. Input'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>70,  'values'=>'Tgl. Ubah'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>70,  'values'=>'Tgl. Hapus'),
		array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>50,  'values'=>'Status'),
	));
	
	foreach($query as $item) {
		$urutan++;
		$warna = ($urutan % 2==0) ? '#FFFFCC' : '#FFFFFF';
		$warna = ($item->STATUS==0) ? '#bdbdbd' : $warna;

		$tbl->set_tr(array('bgcolor'=>$warna));
		$tbl->set_td(array(
			array('id'=>'detail', 'align'=>'center', 'values'=>$urutan),
			array('id'=>'detail', 'values'=>$item->KODEPERKIRAAN),
			array('id'=>'detail', 'values'=>$item->NAMAPERKIRAAN),
			array('id'=>'detail', 'values'=>$item->INDUK),
			array('id'=>'detail', 'values'=>$item->KELOMPOK),
			array('id'=>'detail', 'values'=>$item->SALDO),
			array('id'=>'detail', 'values'=>$item->TIPE),
			array('id'=>'detail', 'align'=>'center', 'values'=>($item->KASBANK==1 ? 'Cash' : ($item->KASBANK==2 ? 'Bank' : ''))),
			array('id'=>'detail', 'values'=>$item->KODEKASBANK),			
			array('id'=>'detail', 'align'=>'center','values'=>$item->USERENTRY),
			array('id'=>'detail', 'align'=>'center','values'=>$item->USERUBAH),
			array('id'=>'detail', 'align'=>'center','values'=>$item->USERHAPUS),
			array('id'=>'detail', 'align'=>'center', 'values'=>$item->TGLENTRY),
			array('id'=>'detail', 'align'=>'center', 'values'=>$item->TGLUBAH),
			array('id'=>'detail', 'align'=>'center', 'values'=>$item->TGLHAPUS),
			array('id'=>'detail', 'align'=>'center', 'values'=>($item->STATUS==1 ? 'Aktif' : 'Non Aktif')),
		));
	}
	
	echo $tbl->generate_table();
	?>
</body>
</html>