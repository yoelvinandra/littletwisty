<?php
session_start();
$CI =& get_instance();	
$CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);

$sql = "select a.KODETRANSFER, a.TGLTRANS,a.TGLKIRIM,b.KODELOKASI as KODELOKASIASAL,b.NAMALOKASI as NAMALOKASIASAL,b1.KODELOKASI as KODELOKASITUJUAN,b1.NAMALOKASI as NAMALOKASITUJUAN,c.USERNAME,d.USERNAME as USERBATAL
				from TTRANSFER a
				left join MLOKASI b on a.IDLOKASIASAL = b.IDLOKASI  and a.idperusahaan = b.idperusahaan
				left join MLOKASI b1 on a.IDLOKASITUJUAN = b1.IDLOKASI  and a.idperusahaan = b1.idperusahaan
				left join MUSER c on a.USERENTRY = c.USERID  and a.idperusahaan = c.idperusahaan
				left join MUSER d on a.USERBATAL = d.USERID  and a.idperusahaan = d.idperusahaan
				where a.IDTRANSFER = ? && a.IDPERUSAHAAN =  {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}";
				
$r = $CI->db->query($sql, [$idtrans])->row();

$sqlMinMaxUkuran = "SELECT (SELECT MIN(x.SIZE) FROM `MBARANG` x WHERE x.SIZE > MIN(MBARANG.SIZE)) as MINSIZE, MAX(MBARANG.SIZE) as MAXSIZE FROM `MBARANG` WHERE 1";
$MinMaxUkuran = $this->db->query($sqlMinMaxUkuran)->row();

?>

<html>
<head>
<?php
echo "<title> ..:: Daftar Harga ::.. </title>";
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
	}
	.footer{
		 font-weight:bold;
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
    </style>
</head>
<body>

<?php

	cetakHeader($r);

	$this->html_table->set_hr(true);

	$this->html_table->set_tr();
	
	$maxukuran = $MinMaxUkuran->MAXSIZE;
	$minukuran = $MinMaxUkuran->MINSIZE;

	$arrayTable = " <br><br><table style='white-space: nowrap;' border='1px solid'><tr>
					<td rowspan='2'  class='detukuran headerukuran' align='center' width=500><strong>STYLE</strong></td>
					<td rowspan='2'  class='detukuran headerukuran' align='center' width=200><strong>COLOR</strong></td>
					<td colspan='".($maxukuran - $minukuran + 1)."'  class='detukuran headerukuran' align='center'><strong>SIZE</strong></td>
					<td rowspan='2'  class='detukuran headerukuran' align='center' width=100><strong>PRICE</strong></td>
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
					where IDPERUSAHAAN = '{$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}'
						  and stok=1 and status = 1						
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
	 
	$sqlDetail = "select a.IDBARANG,a.JML,a.SATUAN,a.HARGA
                				from TTRANSFERDTL a
                				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and a.IDTRANSFER = '{$idtrans}' order by a.URUTAN";	
    $queryDetail = $CI->db->query($sqlDetail)->result();
    
    $totalBarang = 0;
	
    foreach($arraynamaBarangWarna as $rBarangWarna) {
        $arrayTable .= "<tr>";
        $arrayTable .= "<td class='detukuran'>".$rBarangWarna->BARANG."</td>";
        $arrayTable .= "<td class='detukuran' align='center'>".$rBarangWarna->WARNA."</td>";
        $arrayHarga = [];
        $total = 0;
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
            
            if($ada)
            {
                $barangTransfer = false;
                   foreach($queryDetail as $itemDetail)
                   {
                       if($idbarang == $itemDetail->IDBARANG)
                       {
                            $barangTransfer = true;
            	            $arrayTable .= "<td class='detukuran jmlukuran' align='center' ".$styleClass.">".number($itemDetail->JML,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])."</td>";
            	            $adaHarga = false;
            	            for($c = 0 ; $c < count($arrayHarga) ; $c++)
            	            {
            	                if($arrayHarga[$c] == $itemDetail->HARGA)
            	                {
            	                    $adaHarga = true;
            	                }
            	            }
            	            
            	            $total += ($itemDetail->JML * $itemDetail->HARGA);
            	            $totalBarang += $itemDetail->JML;
            	            
            	            if(!$adaHarga)
            	            {
            	                array_push($arrayHarga,$itemDetail->HARGA);
            	            }
                       }
                   }
                   if(!$barangTransfer)
                   {
                    $arrayTable .= "<td class='detukuran jmlukuran' align='center'></td>";
                   }
            	  
            }
            else
            {
                  $arrayTable .= "<td class='detukuran jmlukuran' align='center'></td>";
            }
        
        }
        
        if(count($arrayHarga) > 1)
        { 
            $arrayTable .= "<td class='detukuran' align='right'>".number($arrayHarga[0],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])." - ".number($arrayHarga[count($arrayHarga) - 1],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']) ."</td>";
        }
        else
        {
            $arrayTable .= "<td class='detukuran' align='right'>".($arrayHarga[0] == null ? "" : number($arrayHarga[0],true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']))."</td>";
        }
    }
    $arrayTable .= "</tr>";	
    
	echo $arrayTable;
?>
<script>
    document.getElementById("TOTALBARANG").innerHTML = "Total Barang : <?=number($totalBarang,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'])?>";
</script>
</div>
<?php

function cetakHeader($r){
    echo '<img src="'.base_url().'assets/'.$_SESSION[NAMAPROGRAM]['KODEPERUSAHAAN'].'/logo.png" class="user-image" alt="User Image" height="40"><br>';
    // echo '<strong class="HEADER">'.$_SESSION[NAMAPROGRAM]['NAMAPERUSAHAAN'].'</strong><br>';
    echo '<strong class="HEADER">DAFTAR HARGA</strong>';
    echo '<br>';
    echo '<strong class="HEADERPERIODE">No : '.$r->KODETRANSFER.' </strong>';
	echo '<br>';
    echo '<strong class="HEADERPERIODE">Dari : '.$r->NAMALOKASIASAL.' ke : '.$r->NAMALOKASITUJUAN.' </strong>';
	echo '<br>';
	echo '<strong class="HEADERPERIODE">Tgl Trans : '.$r->TGLTRANS.'  Tgl Kirim : '.$r->TGLKIRIM.'</strong>';
	echo '<br><br>';
	echo '<strong class="HEADERPERIODE" id="TOTALBARANG"></strong>';
}

?>