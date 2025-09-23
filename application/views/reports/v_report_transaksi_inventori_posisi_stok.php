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

$query  = $CI->db->query("select namalokasi,idlokasi from mlokasi where 1=1 $whereLokasi and idperusahaan = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} '")->result();
$lokasi = '';
foreach($query as $item) {
	if ($lokasi=='') {
		$lokasi .= $item->NAMALOKASI;
	} else {
		$lokasi .= ', '.$item->NAMALOKASI;
	}	
}
if($grouplokasi != "")
{
    $lokasi = $grouplokasi;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<?php
if ($tampil=='POSISISTOK'){
	echo "<title> ..:: Laporan Posisi Stok ::.. </title>";
} else if ($tampil=='DetailPosisiStokLokasi'){
	echo "<title> ..:: Laporan Posisi Stok ::.. </title>";
}
?>
	<style>
    table{
	 border-collapse: collapse;
	}
	
     .HEADER {
        font-family: Tahoma, Verdana, Geneva, sans-serif;
        font-weight: bold;
        font-size: 18px;
        color: #000;
        text-align:left;
    }
    .HEADERPERIODE {
        font-family: Tahoma, arial, sans-serif, Verdana, Geneva ;
        font-weight: bold;
        font-size: 16px;
        color: #000;
        text-align:left;
    }
    #tabelket{
        font-family:Tahoma, Verdana, Geneva, sans-serif;
        font-size:10px;
        font-weight:bold;
        padding: 2px;
    }
	.header{
		 font-weight:bold;
		 background:#6caef5;
	}
	.footer{
		 font-weight:bold;
		 background:#b3e0ff;
	}
    .det{
        font-family:Tahoma, Verdana, Geneva, sans-serif;
        font-size:10px;
        padding: 2px;
		
    }
    
    .detukuran{
        font-family:Tahoma, Verdana, Geneva, sans-serif;
        font-size:16px;
        padding-left: 5px;
        padding-top:5px;
        padding-bottom:5px;
    }
    
    .headerukuran{
        padding-top:5px;
        padding-bottom:5px;
    }
    
    .jmlukuran{
        padding-top:5px;
        padding-bottom:5px;
    }
    
    .lokasi{
        padding-top:5px;
        padding-bottom:5px;
        padding-top:5px;
        padding-bottom:5px;
    }
    
    .limit{
        background:#fffdd2;
    }
    
    .out{
        background:#ff5959;
    }
    </style>
</head>
<body>
<?php
$totalBarang   = 0;
$totalPOBarang   = 0;


if ($tampil=='POSISISTOKLOKASI'){ // MENAMPILKAN DATA POSISI STOK
  	cetakHeader($tampil,'Laporan Posisi Stok Per Lokasi'.$txtmark,$perusahaan,$lokasi,$keterangangudang,$tgl_akhir);
  	
  	$logic = explode(" ",explode("SUM(c.JML)",$whereJML)[1])[1];
	$value = explode(" ",explode("SUM(c.JML)",$whereJML)[1])[2];

	$this->html_table->set_hr(true);

	$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
	
	$arrayBarang = "<table style='white-space: nowrap;' border='1px solid'><tr>
					<td id='tableket' rowspan='2'  class='det header' align='center' width=40>No</td>
					<td id='tableket' rowspan='2'  class='det header' align='center' width=200>Nama Barang</td>
					";
	$queryLokasi = $query;
	
	$urutanLokasi=0;
	$IDLokasi = array();

	foreach($queryLokasi as $r) {
		$arrayLokasi .= "<td valign='top' align='center' id='tableket'  class='det header' colspan='2' width=160> ".$r->NAMALOKASI." </td>";
		$IDLokasi[$urutanLokasi] = $r->IDLOKASI;		
		$urutanLokasi++;
	}
	$arrayLokasi .= "<td valign='top' align='center' id='tableket' class='det header' rowspan='2' width=60> Total Jml</td>";
	$arrayLokasi .= "<td valign='top' align='center' id='tableket' class='det header' rowspan='2' width=100> Total Nilai Barang</td>";
	$arrayLokasi .= "<tr>";
	foreach($queryLokasi as $r) {
		$arrayLokasi .= "<td valign='top' align='center' id='tableket' class='det header' width=60>Jml</td>";
		$arrayLokasi .= "<td valign='top' align='center' id='tableket' class='det header' width=100>Nilai Barang</td>";
	}
	$arrayLokasi .="<tr>";
	
	if($queryLokasi != null)
	{
		//NAMA BARANG
		$sqlBarang = "select distinct NAMABARANG, IDBARANG
						from MBARANG 
						where (1=1 $whereFilter) and IDPERUSAHAAN = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}'
							  and stok=1						
						order by SUBSTRING(URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(URUTANTAMPIL, 2) AS UNSIGNED) ASC
						";		
						
		$queryBarang  = $CI->db->query($sqlBarang)->result();
		$urutanBarang = 0;
		$IDBarang     = array();
		$NamaBarang   = array();
		$JumlahBarang = array();
		$HargaBarang  = array();

		$arrayHeader = $arrayBarang.$arrayLokasi;
		$urutan = 0;
		
		foreach($queryBarang as $r) {
			$urutan++;
			
			$IDBarang[$urutanBarang] = $r->IDBARANG;		
			$NamaBarang[$urutanBarang] = $r->NAMABARANG;		
			$urutanBarang++;								
		}
			
		$totalkeseluruhanharga = 0;
		$totalkeseluruhanjlm   = 0;
		for($i=0 ; $i < count($IDBarang) ; $i++) {
			$totaljmlbarang   = 0;
			$totalhargabarang = 0;
			$arrayDetail .= "<tr><td valign='top' id='tableket' align='center'  class='det'> ".($i+1)." </td>		
							 <td valign='top' id='tableket'  class='det' >".$NamaBarang[$i]." </td>";	
							
			for($j=0 ; $j < count($IDLokasi) ; $j++) {				
				$sqlLokasi = "select sum(x.JML) as JML, sum(x.SUBTOTAL) as SUBTOTAL
								from(
									select sum(if(a.MK = 'M', a.JML, -a.JML)) as JML,
									sum(if(a.MK = 'M', a.TOTALHARGA, -a.TOTALHARGA)) as SUBTOTAL
									from KARTUSTOK a
									inner join MBARANG c on c.IDBARANG = a.IDBARANG
									inner join MLOKASI d on d.IDLOKASI = a.IDLOKASI
									where d.IDLOKASI =".$IDLokasi[$j]." and c.IDBARANG =".$IDBarang[$i]."
									and a.TGLTRANS <= '{$tgl_akhir}'
									and a.IDPERUSAHAAN = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}'
									and c.stok=1 
									
									union all
									
									select sum(c.JML) as JML, sum(c.SUBTOTAL) as SUBTOTAL
									from SALDOSTOK a
									inner join SALDOSTOKDTL c on a.KODESALDOSTOK = c.KODESALDOSTOK 
									inner join MBARANG b on c.IDBARANG = b.IDBARANG
									inner join MLOKASI d on a.IDLOKASI = d.IDLOKASI
									where d.IDLOKASI =".$IDLokasi[$j]." and c.IDBARANG =".$IDBarang[$i]."
									and a.TGLTRANS <= '{$tgl_akhir}'
									and a.IDPERUSAHAAN = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}' 
									and b.stok=1 and a.KODESALDOSTOK not like 'CLS%'
									and a.status <> 'D'
								) as x";	
				
				$detail= $CI->db->query($sqlLokasi);
    		        	  
				if($detail->num_rows() > 0)
				{ 						
					//ISI DATA
					$rs = $detail->row();
				
    				$styleClass = "";
    				$warning = false;
    				if($logic == "=")
                	{
                		if(number($rs->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) == $value)
            			{
            			    $warning = true;
            			}
                	}
                	else if($logic == "!=")
                	{
                		if(number($rs->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) != $value)
            			{
            			    $warning = true;
            			}
                	}
                	else if($logic == ">")
                	{
                		if(number($rs->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) > $value)
            			{
            			    $warning = true;
            			}
                	}
                	else if($logic == ">=")
                	{
                		if(number($rs->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) >= $value)
            			{
            			    $warning = true;
            			}
                	}
                	else if($logic == "<")
                	{
                		if(number($rs->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) < $value)
            			{
            			    $warning = true;
            			}
                	}
                	else if($logic == "<=")
                	{
                		if(number($rs->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) <= $value)
            			{
            			    $warning = true;
            			}
                	}
                	
                	if($warning)
                	{
                	    $styleClass = 'bgcolor="#fffdd2"';
                	}
    				
        		    if(number($rs->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) <= 0)
        		    {
        		        $styleClass = 'bgcolor="#ff5959"';
        		    }
        		    
					$arrayDetail .= "<td valign='top'  class='det' id='tableket'  align='right' $styleClass >".number($rs->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])." </td>
					            	 <td valign='top' id='tableket'  class='det' align='right'  $styleClass >".($LIHATHARGA?number($rs->SUBTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X')." </td>";
					$totaljmlbarang        += $rs->JML;
					$totalhargabarang      += $rs->SUBTOTAL;
					$totalkeseluruhanharga += $rs->SUBTOTAL;
					$totalkeseluruhanjml   += $rs->JML;
					$JumlahBarang[$j]      += $rs->JML;
					$HargaBarang [$j]      += $rs->SUBTOTAL;
					$totalBarang           += $rs->JML;
				}
				else
				{
					$arrayDetail .= "<td valign='top'  class='det' id='tableket'  align='right' $styleClass >".number(0,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])."</td>
									 <td valign='top' id='tableket'  class='det' align='right'  $styleClass >".($LIHATHARGA?number(0,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X')." </td>";
					$totalkeseluruhanharga += 0;
					$totalkeseluruhanjml   += 0;
					$JumlahBarang[$j]      += 0;
					$HargaBarang [$j]      += 0;
					$totaljmlbarang        += 0;
					$totalhargabarang      += 0;
				}				
			}
			$arrayDetail .= "<td valign='top'  class='det' id='tableket' align='right'>".($LIHATHARGA?number($totaljmlbarang,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X')."</td>";
			$arrayDetail .= "<td valign='top'  class='det' id='tableket' align='right'>".($LIHATHARGA?number($totalhargabarang,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X')."</td></tr>";
		}
		$arrayFooter = "<tr> <td valign='top' class='det footer' align='right' id='tableket' colspan=2>Total</td>";
		for($i=0;$i<count($queryLokasi);$i++) {
			$arrayFooter .= "<td valign='top' class='det footer' align='right' id='tableket'>".number($JumlahBarang[$i],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])." </td>
		 					 <td valign='top' class='det footer' align='right' id='tableket'>".($LIHATHARGA?number($HargaBarang[$i],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X')." </td>";
		}
		$arrayFooter .= "<td valign='top'  class='det footer' id='tableket' align='right'>".($LIHATHARGA?number($totalkeseluruhanjml,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X')."</td>";
		$arrayFooter .= "<td valign='top'  class='det footer' id='tableket' align='right'>".($LIHATHARGA?number($totalkeseluruhanharga,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X')."</td></tr>";
	}	
	echo $arrayHeader.$arrayDetail.$arrayFooter;

} else if ($tampil=='POSISISTOK'){

    $query = $CI->db->query($sql)->result();
	cetakHeader($tampil,'Laporan Posisi Stok Lokasi (Rekap)'.$txtmark,$perusahaan,$lokasi,$keterangangudang,$tgl_akhir);

	$this->html_table->set_hr(true);
	
	$kodetrans   = '';
	$koderef     = '';
	$total       = array();
	$gtotal      = array();
	$urutan      = 0;
	$semuaLokasi = 0;
	
	$sqlLokasi = "select NAMALOKASI from MLOKASI ";
	$sqlLokasi = $CI->db->query($sqlLokasi)->result();

	
	if(count($sqlLokasi)-1 != $jumlahLokasi)
	{
		foreach($query as $r) {
			$a_merge = array();
			$a_merge2 = array();
			
			if ($koderef <> $r->KODELOKASI) {
				if ($koderef <> '') {
					$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
					$this->html_table->set_td(array(
						array('valign'=>'top', 'align'=>'right', 'colspan'=>4, 'id'=>'tabelket', 'values'=>'Total'),
						array('valign'=>'top', 'align'=>'right', 'id'=>'tabelket', 'values'=>number($total['jml'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
						array('valign'=>'top', 'align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?$LIHATHARGA?number($total['total'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X':'X'),
					));

					$this->html_table->line_break();
				}

				$this->html_table->set_tr();
				$this->html_table->set_td(array(
					array('id'=>'tabelket', 'colspan'=>6, 'values'=>'Lokasi : '.$r->KODELOKASI.' - '.$r->NAMALOKASI),
				));

				$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
				$this->html_table->set_th(array(
					array('valign'=>'top', 'align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
					array('valign'=>'top', 'align'=>'center', 'id'=>'tabelket', 'width'=>60,  'values'=>'Kode'),
					array('valign'=>'top', 'align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Nama Barang'),
					array('valign'=>'top', 'align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Satuan'),
					array('valign'=>'top', 'align'=>'center', 'id'=>'tabelket', 'width'=>70,  'values'=>'Stok'),
					array('valign'=>'top', 'align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Nilai Barang ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
				));

				$koderef = $r->KODELOKASI;
				$total = array();
				$urutan=0;
			}

			$urutan++;
			$urutan2 = 0;
			if ($r->STATUS == 'I') $warna2 = '#FFFFFF';
			else if ($r->STATUS == 'S') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_S'];
			else if ($r->STATUS == 'P') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'];
			else if ($r->STATUS == 'D') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'];
			
			$a_merge = array(
				array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'values'=>$urutan),
				array('valign'=>'top', 'align'=>'center',  'class'=>'det', 'values'=>$r->KODEBARANG),
				array('valign'=>'top', 'align'=>'left',  'class'=>'det', 'values'=>$r->NAMABARANG.' '.$r->TIPE),			
				array('valign'=>'top', 'align'=>'center','class'=>'det', 'values'=>$r->SATUAN),
				array('valign'=>'top', 'align'=>'right', 'class'=>'det', 'values'=>number($r->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('valign'=>'top', 'align'=>'right', 'class'=>'det', 'values'=>$LIHATHARGA?number($r->SUBTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			);

			$urutan2++;
			$warna = $urutan2%2==0 ? '#FFFFCC' : '#FFFFFF';
			
			$total['jml']   += $r->JML;
			$total['jual']  += $r->HARGAJUAL;
			$total['total'] += $r->SUBTOTAL;
			
			$gtotal['jml']   += $r->JML;
			$gtotal['jual']  += $r->HARGAJUAL;
			$gtotal['total'] += $r->SUBTOTAL;
			$totalBarang     += $r->JML;

			
			if ($rs->TUTUP==1) $warna = '#CCCCCC';

			$this->html_table->set_tr();
			$this->html_table->set_td(array_merge(
				$a_merge,
				array(
						
				),
				$a_merge2
			));
		}
		if ($koderef <> '') {
			$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
			$this->html_table->set_td(array(
				array('valign'=>'top', 'align'=>'right', 'colspan'=>4, 'id'=>'tabelket', 'values'=>'Total'),
				array('valign'=>'top', 'align'=>'right', 'id'=>'tabelket', 'values'=>number($total['jml'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('valign'=>'top', 'align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?$LIHATHARGA?number($total['total'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X':'X'),
			));
			$this->html_table->line_break();

			$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
			$this->html_table->set_td(array(
				array('valign'=>'top', 'align'=>'right', 'colspan'=>4, 'id'=>'tabelket', 'values'=>'Total'),
				array('valign'=>'top', 'align'=>'right', 'id'=>'tabelket', 'values'=>number($gtotal['jml'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('valign'=>'top', 'align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?$LIHATHARGA?number($gtotal['total'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X':'X'),
			));
		}

		echo $this->html_table->generate_table();
	}
	else
	{
		$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));
		$this->html_table->set_th(array(
			array('valign'=>'top', 'align'=>'center', 'id'=>'tabelket', 'width'=>30,  'values'=>'No.'),
			array('valign'=>'top', 'align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Kode Barang'),
			array('valign'=>'top', 'align'=>'center', 'id'=>'tabelket', 'width'=>300, 'values'=>'Nama Barang'),
			array('valign'=>'top', 'align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Satuan'),
			array('valign'=>'top', 'align'=>'center', 'id'=>'tabelket', 'width'=>50,  'values'=>'Stok'),
			array('valign'=>'top', 'align'=>'center', 'id'=>'tabelket', 'width'=>100, 'values'=>'Saldo ('.$_SESSION[NAMAPROGRAM]['SIMBOLCURRENCY'].')'),
		));
		foreach($query as $r) {
			$a_merge = array();
			$a_merge2 = array();
			
			$urutan++;
			$urutan2 = 0;
			if ($r->STATUS == 'I') $warna2 = '#FFFFFF';
			else if ($r->STATUS == 'S') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_S'];
			else if ($r->STATUS == 'P') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'];
			else if ($r->STATUS == 'D') $warna2 = $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'];
			
			$a_merge = array(
				array('valign'=>'top', 'align'=>'center', 'class'=>'det', 'values'=>$urutan),
				array('valign'=>'top', 'align'=>'center',  'class'=>'det', 'values'=>$r->KODEBARANG),
				array('valign'=>'top', 'align'=>'left',  'class'=>'det', 'values'=>$r->NAMABARANG.' '.$r->TIPE),			
				array('valign'=>'top', 'align'=>'center','class'=>'det', 'values'=>$r->SATUAN),
				array('valign'=>'top', 'align'=>'right', 'class'=>'det', 'values'=>number($r->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
				array('valign'=>'top', 'align'=>'right', 'class'=>'det', 'values'=>$LIHATHARGA?number($r->SUBTOTAL,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X'),
			);
			//$a_merge2 = array(
		//		array('valign'=>'top', 'align'=>'left',   'class'=>'det', 'bgcolor'=>$warna2, 'values'=>$r->CATATAN),
		//	);

			$urutan2++;
			$warna = $urutan2%2==0 ? '#FFFFCC' : '#FFFFFF';
			
			$total['jml']   += $r->JML;
			$total['jual']  += $r->HARGAJUAL;
			$total['total'] += $r->SUBTOTAL;
			
			$gtotal['jml']   += $r->JML;
			$gtotal['jual']  += $r->HARGAJUAL;
			$gtotal['total'] += $r->SUBTOTAL;
			$totalBarang     += $r->JML;

			
			if ($rs->TUTUP==1) $warna = '#CCCCCC';

			$this->html_table->set_tr();
			$this->html_table->set_td(array_merge(
				$a_merge,
				array(
						
				),
				$a_merge2
			));
		}
		$this->html_table->set_tr(array('bgcolor'=>'#B3E0FF'));
		$this->html_table->set_td(array(
			array('valign'=>'top', 'align'=>'right', 'colspan'=>4, 'id'=>'tabelket', 'values'=>'Total'),
			array('valign'=>'top', 'align'=>'right', 'id'=>'tabelket', 'values'=>number($gtotal['jml'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
			array('valign'=>'top', 'align'=>'right', 'id'=>'tabelket', 'values'=>$LIHATHARGA?$LIHATHARGA?number($gtotal['total'],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']):'X':'X'),
		));
		echo $this->html_table->generate_table();
	}
} else if ($tampil=='POSISISTOKSIZE'){ // MENAMPILKAN DATA POSISI STOK
  	cetakHeader($tampil,'Laporan Posisi Stok Per Ukuran'.$txtmark,$perusahaan,$lokasi,$keterangangudang,$tgl_akhir);

	$this->html_table->set_hr(true);

	$this->html_table->set_tr(array('bgcolor'=>'#6CAEF5'));

	$arrayTable = " <br><br><table style='white-space: nowrap;' border='1px solid'><tr>
					<td rowspan='2'  class='detukuran headerukuran' align='center' width=500><strong>STYLE</strong></td>
					<td rowspan='2'  class='detukuran headerukuran' align='center' width=200><strong>COLOR</strong></td>
					<td colspan='".($maxukuran - $minukuran + 1)."'  class='detukuran headerukuran' align='center'><strong>SIZE</strong></td>
					</tr>";
					
	$arrayTable .= "<tr>";
	for($x = $minukuran ; $x <= $maxukuran ; $x++)
    {
        $arrayTable .= "<td class='detukuran jmlukuran' align='center' width=50><strong>".$x."</strong></td>";
    }
	$arrayTable .= "</tr>";
	
	//LOKASI
	$queryLokasi = $query;
	
	
    //BARANG
	$sqlBarang = "select distinct NAMABARANG,WARNA,SIZE, IDBARANG,KATEGORI
					from MBARANG 
					where (1=1 $whereFilter) and IDPERUSAHAAN = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}'
						  and stok=1						
					order by SUBSTRING(URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(URUTANTAMPIL, 2) AS UNSIGNED) ASC
					";		
					
	$queryBarang  = $CI->db->query($sqlBarang)->result();
	$arraynamaBarangWarna = [];
	foreach($queryBarang as $rBarang) {
    	        $arrayNamaBarang = explode(" | ",$rBarang->NAMABARANG);
    	        $namaBarang = $arrayNamaBarang[0];
    	        $warnaBarang = $arrayNamaBarang[1];
    	        if(($namaBarang.$warnaBarang) != $namaBarangWarna)
    	        {
    	            $namaBarangWarna = ($namaBarang.$warnaBarang);
    	            array_push($arraynamaBarangWarna,(object) [
        	            'BARANG' => $namaBarang,
        	            'WARNA' => $warnaBarang,
        	            'BARANGWARNA'=> ($namaBarang.$warnaBarang)
    	            ]);
    	        }
	 }
	 
	$logic = explode(" ",explode("SUM(c.JML)",$whereJML)[1])[1];
	$value = explode(" ",explode("SUM(c.JML)",$whereJML)[1])[2];
	

	 
	if($queryLokasi != null)
	{
	    if($stokPerLokasi == 0)
	    {
	        
	        $whereLokasi = "";
	        $whereLokasiNama = "";
	        foreach($queryLokasi as $rLokasi) {
	            $whereLokasi .= ($rLokasi->IDLOKASI.",");
	            $whereLokasiNama .= ($rLokasi->NAMALOKASI.", ");
	        }
	        
	        $queryLokasi = array();
	        $obj1 = new stdClass();
	        
	        if($grouplokasi != "")
            {
                 $obj1->NAMALOKASI = $lokasi = $grouplokasi;
            }
            else
            {
                 $obj1->NAMALOKASI = substr($whereLokasiNama,0,-2);
            }

            $obj1->IDLOKASI = substr($whereLokasi,0,-1);
            
            // Add objects to the array
            $queryLokasi[] = $obj1;
	    }
	    
    	foreach($queryLokasi as $rLokasi) {
    		$arrayTable .= "<tr><td valign='top' align='center' class='detukuran headerukuran lokasi' bgcolor='#6c1c1f' paddingtop='100' colspan='".(($maxukuran - $minukuran)+3)."'><font color='white'><strong>TOTAL STOK ".$rLokasi->NAMALOKASI."</strong></font></td>";
            
            //CHECK DATA PO
            $sqlPO = "select tpodtlbrg.IDBARANG,sum(tpodtlbrg.sisa) as JMLPO from tpo
                      inner join tpodtlbrg on tpo.idpo = tpodtlbrg.idpo
                      where tpo.IDPERUSAHAAN = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}'
                      and tpo.status in ('I','S','C') and tpo.idlokasi in ({$rLokasi->IDLOKASI})
                      and tpodtlbrg.tutup = 0
                      group by tpodtlbrg.idbarang
                      ";
            $dataPO= $CI->db->query($sqlPO)->result();
            
            
    	    foreach($arraynamaBarangWarna as $rBarangWarna) {
        	    $arrayTable .= "</tr>";
        	    $arrayTable .= "<tr>";
        	    $arrayTable .= "<td class='detukuran'>".$rBarangWarna->BARANG."</td>";
        	    $arrayTable .= "<td class='detukuran' align='center'>".$rBarangWarna->WARNA."</td>";
        	    
        	    for($x = $minukuran ; $x <= $maxukuran ; $x++)
        	    {
                    $ada = false;
                    $idbarang = "0";
                    foreach($queryBarang as $rBarang) {
                        $namaBarangWarna = explode(" | ",$rBarang->NAMABARANG);
                        if($rBarangWarna->BARANGWARNA == ($namaBarangWarna[0].$namaBarangWarna[1]) && $x == $rBarang->SIZE)
                        {
                            $ada = true;
                            $idbarang = $rBarang->IDBARANG;
                        }
                    }
                    $jmlPO = "";
                    foreach($dataPO as $itemPO)
                    {
                        if($idbarang == $itemPO->IDBARANG && $itemPO->JMLPO != 0)
                        {
                            $jmlPO = "<span style='float:right;'><table><tr><td bgcolor='#90EE90' align='center'>+".number($itemPO->JMLPO,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])."</td></tr></table></span>";
                            $totalPOBarang += $itemPO->JMLPO;
                        }
                    }
                    
                    if($ada)
                    {
                         $sqlStok = "select sum(x.JML) as JML, sum(x.SUBTOTAL) as SUBTOTAL
    							from(
    								select sum(if(a.MK = 'M', a.JML, -a.JML)) as JML,
    								sum(if(a.MK = 'M', a.TOTALHARGA, -a.TOTALHARGA)) as SUBTOTAL
    								from KARTUSTOK a
    								inner join MBARANG c on c.IDBARANG = a.IDBARANG
    								inner join MLOKASI d on d.IDLOKASI = a.IDLOKASI
    								where d.IDLOKASI in (".$rLokasi->IDLOKASI.") and c.IDBARANG =".$idbarang."
    								and a.TGLTRANS <= '{$tgl_akhir}'
    								and a.IDPERUSAHAAN = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}'
    								and c.stok=1 
    								
    								union all
    								
    								select sum(c.JML) as JML, sum(c.SUBTOTAL) as SUBTOTAL
    								from SALDOSTOK a
    								inner join SALDOSTOKDTL c on a.KODESALDOSTOK = c.KODESALDOSTOK 
    								inner join MBARANG b on c.IDBARANG = b.IDBARANG
    								inner join MLOKASI d on a.IDLOKASI = d.IDLOKASI
    								where d.IDLOKASI in (".$rLokasi->IDLOKASI.") and c.IDBARANG =".$idbarang."
    								and a.TGLTRANS <= '{$tgl_akhir}'
    								and a.IDPERUSAHAAN = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}' 
    								and b.stok=1 and a.KODESALDOSTOK not like 'CLS%'
    								and a.status <> 'D'
    							) as x";	
                           
    		        	  $queryStok= $CI->db->query($sqlStok)->row();
    		        	  $styleClass = "";
                          $warning = false;
                          if($logic == "=")
            			  {
            			  	if(number($queryStok->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) == $value)
        		        	{
        		        	    $warning = true;
        		        	}
            			  }
            			  else if($logic == "!=")
            			  {
            			  	if(number($queryStok->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) != $value)
        		        	{
        		        	    $warning = true;
        		        	}
            			  }
            			  else if($logic == ">")
            			  {
            			  	if(number($queryStok->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) > $value)
        		        	{
        		        	    $warning = true;
        		        	}
            			  }
            			  else if($logic == ">=")
            			  {
            			  	if(number($queryStok->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) >= $value)
        		        	{
        		        	    $warning = true;
        		        	}
            			  }
            			  else if($logic == "<")
            			  {
            			  	if(number($queryStok->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) < $value)
        		        	{
        		        	    $warning = true;
        		        	}
            			  }
            			  else if($logic == "<=")
            			  {
            			  	if(number($queryStok->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) <= $value)
        		        	{
        		        	    $warning = true;
        		        	}
            			  }
            			  
            			  if($warning)
            			  {
        		        	 $styleClass = 'bgcolor="#fffdd2"';
            			  }
				        
				        
			                $totalBarang     += $queryStok->JML;
    		        	  
    		        	  if(number($queryStok->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']) <= 0)
    		        	  {
    		        	      $styleClass = 'bgcolor="#ff5959"';
    		        	  }
    		        	  
    		        	  $arrayTable .= "<td class='detukuran jmlukuran' align='center' ".$styleClass.">".number($queryStok->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])."  ".$jmlPO."</td>";
                    }
                    else
                    {
                          $arrayTable .= "<td class='detukuran jmlukuran' align='center'>  ".$jmlPO."</td>";
                    }
        	    
        	    }
        	}
        	$arrayTable .= "</tr>";
        }
	}	
	echo $arrayTable;

} 
?>
</div>
<script>
    document.getElementById("TOTALBARANG").innerHTML = "Total Barang : "+ '<?php echo $totalBarang; ?>'+" <span style='background:#90EE90;'>+"+ '<?php echo $totalPOBarang; ?>'+"</span>";
</script>
<?php

function cetakHeader($tampil,$Keterangan, $perusahaan, $lokasi, $gudang, $TglAw){
    echo '<strong class="HEADER">'.$_SESSION[NAMAPROGRAM]['NAMAPERUSAHAAN'].'</strong><br>';
    echo '<strong class="HEADER">'.$Keterangan.'</strong>';
	echo '<br>';
    echo '<strong class="HEADERPERIODE">Lokasi : '.$lokasi.'</strong>';
	echo '<br>';
	if ($gudang!=''){
		echo '<strong class="HEADERPERIODE">Gudang : '.$gudang.'</strong>';
		echo '<br>';
	}
	echo '<strong class="HEADERPERIODE">Periode : '.$TglAw.'</strong>';
	echo '<br><br>';
    echo '<strong class="HEADERPERIODE" id="TOTALBARANG"></strong>';
}

?>
