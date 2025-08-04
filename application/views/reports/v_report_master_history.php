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
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>..:: Laporan Master History ::..</title>
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
	<div align="left">
		<div id="header">
		<?php if($tampil == "REGISTER"){ 
            $query = $CI->db->query($sql)->result();
            echo "LAPORAN HISTORY PROGRAM";
            }else{
            echo "LAPORAN HISTORY PROGRAM BARANG";
            
            $sqlLokasi = "select NAMALOKASI from mlokasi where idlokasi in ($whereLokasi)";
            echo "<br>Lokasi : ".$CI->db->query($sqlLokasi)->row()->NAMALOKASI;
            
            $sqlBarang = "select NAMABARANG from mbarang where kodebarang = '$kodeBarang'";
            echo "<br>Barang : ".$CI->db->query($sqlBarang)->row()->NAMABARANG;
            } 
        ?>
        </div>
		<hr>
		<?php
			$tbl = new html_table();
	        if($tampil == "REGISTER")
	        {
            
    			$tbl->set_tr(array('bgcolor'=>'#9CD0ED'));
    			$tbl->set_th(array(
    				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>150,  'values'=>'Tanggal Entry'),
    				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>120,  'values'=>'Kode Trans'),
    				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Aksi'),
    				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'User Entry'),
    				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>400,  'values'=>'Nama File'),
    				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Mac Address'),
    				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'IP Address'),
    
    			));
    			
    			foreach($query as $item) {
    				$urutan++;
    				$warna = ($urutan % 2==0) ? '#FFFFCC' : '#FFFFFF';
    				$warna = ($item->STATUS==0) ? '#bdbdbd' : $warna;
    				
    				$tbl->set_tr(array('bgcolor'=>$warna));
    				$tbl->set_td(array(
    					array('id'=>'detail', 'align'=>'center', 'values'=>$item->TGLHISTORY),
    					array('id'=>'detail', 'align'=>'center','values'=>$item->KODETRANS),
    					array('id'=>'detail', 'align'=>'center','values'=>$item->AKSI),
    					array('id'=>'detail', 'align'=>'center','values'=>$item->USERNAME),
    					array('id'=>'detail', 'values'=>$item->NAMAFILE),
    					array('id'=>'detail', 'align'=>'center','values'=>$item->MACADDRESS),
    					array('id'=>'detail', 'align'=>'center','values'=>$item->IPADDRESS),
    				));
    			}
	        }
	        else
	        {
	            $lokasi = $whereLokasi;
	            if($whereLokasi != "")$whereLokasi = " and a.idlokasi in (".$lokasi.")";
	            if($kodeBarang != "")$whereBarang = " and c.kodebarang = '".$kodeBarang."'";
	            
	            $jenisTransaksi = ["penjualan","beli","returbeli","penyesuaianstok","saldostok"];
	            $statusTransaksi = ["K","M","K","S","M"];
	            for($x = 0 ; $x < count($jenisTransaksi); $x++)
	            {
	               
	                if($jenisTransaksi[$x] == "saldostok")
	                {
	                    $sql .= "
        	                select a.kode".$jenisTransaksi[$x]." as KODETRANS, a.TGLTRANS, b.JML, '".$statusTransaksi[$x]."' as STATUSTRANSAKSI,  '".$jenisTransaksi[$x]."' as JENISTRANSAKSI from ".$jenisTransaksi[$x]." a inner join ".$jenisTransaksi[$x]."dtl b on a.id".$jenisTransaksi[$x]." = b.id".$jenisTransaksi[$x]."  inner join mbarang c on b.idbarang = c.idbarang where 1=1 $whereBarang $whereLokasi
        	            ";
	                }
	                else if($jenisTransaksi[$x] == "penyesuaianstok")
	                {
	                    $sql .= "
        	                select a.kode".$jenisTransaksi[$x]." as KODETRANS, a.TGLTRANS, if(b.SELISIH >= 0 , b.SELISIH, -b.SELISIH) as JML, if(b.SELISIH >= 0 , 'M', 'K') as STATUSTRANSAKSI,  '".$jenisTransaksi[$x]."' as JENISTRANSAKSI from t".$jenisTransaksi[$x]." a inner join t".$jenisTransaksi[$x]."dtl b on a.id".$jenisTransaksi[$x]." = b.id".$jenisTransaksi[$x]."  inner join mbarang c on b.idbarang = c.idbarang where 1=1 $whereBarang $whereLokasi
        	            ";
	                }
	                else
	                {
        	            $sql .= "
        	                select a.kode".$jenisTransaksi[$x]." as KODETRANS, a.TGLTRANS, b.JML, '".$statusTransaksi[$x]."' as STATUSTRANSAKSI , '".$jenisTransaksi[$x]."' as JENISTRANSAKSI from t".$jenisTransaksi[$x]." a inner join t".$jenisTransaksi[$x]."dtl b on a.id".$jenisTransaksi[$x]." = b.id".$jenisTransaksi[$x]."  inner join mbarang c on b.idbarang = c.idbarang where 1=1 $whereBarang $whereLokasi
        	            ";
	                }
    	            
    	             $sql .= "
    	                            union all
    	                     ";
	            }
	            
	      
	            //TRANSFER KELUAR
	            if($whereLokasi != "")$whereLokasi = " and a.idlokasiasal in (".$lokasi.")";
	            $sql .= "
        	           select a.kodetransfer as KODETRANS, a.TGLTRANS, b.JML, 'K' as STATUSTRANSAKSI,  'TRANSFER KELUAR' as JENISTRANSAKSI from ttransfer a inner join ttransferdtl b on a.idtransfer = b.idtransfer  inner join mbarang c on b.idbarang = c.idbarang where 1=1 $whereBarang $whereLokasi
        	           
        	           union all 
        	    ";
        	    
	          
        	    //TRANSFER MASUK
	            if($whereLokasi != "")$whereLokasi = " and a.idlokasitujuan in (".$lokasi.")";
	            $sql .= "
        	           select a.kodetransfer as KODETRANS, a.TGLTRANS, b.JML, 'M' as STATUSTRANSAKSI,  'TRANSFER MASUK' as JENISTRANSAKSI from ttransfer a inner join ttransferdtl b on a.idtransfer = b.idtransfer  inner join mbarang c on b.idbarang = c.idbarang where 1=1 $whereBarang $whereLokasi
        	    ";
        	    
	            $query = $CI->db->query($sql)->result();
	            
	            $whereTrans = "";
	            foreach($query as $item)
	            {
	                $whereTrans .= "'".$item->KODETRANS."',";
	            }
	            
	            if($whereTrans != "")
	            {
	                $whereTrans = substr($whereTrans, 0, -1);
	            
    	            $sql = "select * from historyprogram where kodetrans in ($whereTrans) and aksi in ('TAMBAH','HAPUS') order by TGLENTRY asc ";
    	            $queryHistory = $CI->db->query($sql)->result();
    	            
    	            $tbl->set_tr(array('bgcolor'=>'#9CD0ED'));
        			$tbl->set_th(array(
        				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Tgl Trans'),
        				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>120,  'values'=>'Kode Trans'),
        				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Transaksi'),
        				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Aksi'),
        				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'User Entry'),
        				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>150,  'values'=>'Tanggal Entry'),
        				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Masuk'),
        				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Keluar'),
        				array('id'=>'detail', 'class'=>'tebal', 'align'=>'center', 'width'=>80,  'values'=>'Saldo'),
        
        			));
        			$saldo = 0;
        			foreach($queryHistory as $item) {
        			    foreach($query as $itemJml)
        			    {
        			        if($item->KODETRANS == $itemJml->KODETRANS)
        			        {
                				$warna = ($item->AKSI == 'HAPUS') ? $_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'] : '#FFFFFF';
                				$masuk = 0;
                				$keluar = 0;
                				if($itemJml->STATUSTRANSAKSI == "M")
                				{
                				    if($item->AKSI == "TAMBAH")
                				    {
                				        $masuk = $itemJml->JML;
                				        $saldo +=  $masuk;
                				    }
                				    else if($item->AKSI == "HAPUS")
                				    {
                				        $keluar = $itemJml->JML;
                				        $saldo -=  $keluar;
                				    }
                				}
                				else if($itemJml->STATUSTRANSAKSI == "K")
                				{
                				    if($item->AKSI == "TAMBAH")
                				    {
                    				    $keluar = $itemJml->JML;
                    				    $saldo -=  $keluar;
                				    }
                				    else if($item->AKSI == "HAPUS")
                				    {
                				        $masuk = $itemJml->JML;
                				        $saldo +=  $masuk;
                				    }
                				}
                				else
                				{
                				    $saldo = $itemJml->JML;
                				}
                				
                				$tbl->set_tr(array('bgcolor'=>$warna));
                				$tbl->set_td(array(
                					array('id'=>'detail', 'align'=>'center','values'=>$itemJml->TGLTRANS),
                					array('id'=>'detail', 'align'=>'center','values'=>$item->KODETRANS),
                					array('id'=>'detail', 'align'=>'center','values'=>$itemJml->JENISTRANSAKSI),
                					array('id'=>'detail', 'align'=>'center','values'=>$item->AKSI),
                					array('id'=>'detail', 'align'=>'center','values'=>$item->USERENTRY),
                					array('id'=>'detail', 'align'=>'center', 'values'=>$item->TGLENTRY),
                					array('id'=>'detail', 'align'=>'center','values'=>number($masuk,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
                					array('id'=>'detail', 'align'=>'center','values'=>number($keluar,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
                					array('id'=>'detail', 'align'=>'center','values'=>number($saldo,true,$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'])),
                				));
        			        }
        			    }
        			}
	            }
	        }
    			
    		echo $tbl->generate_table();
		?>
	</div>
</body>
</html>