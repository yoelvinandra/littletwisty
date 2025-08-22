<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_barang extends MY_Model{

	public function getAll(){
		$data = $this->db->query("select * from MBARANG and IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} ");
		return $data->result();
	}
	
	public function laporan($filter){
	}
	
	public function satuanBarang($pagination){
		$sql = "select SATUAN, KONVERSI, JENIS from(
					select SATUAN ,1 as KONVERSI, 1 as JENIS from MBARANG 
					where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDBARANG = ? and SATUAN like ?
					union
					select SATUAN2 ,KONVERSI1 as KONVERSI, 2 as JENIS 
					from MBARANG where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDBARANG = ? and SATUAN2 like ?
					union
					select SATUAN3 ,KONVERSI2 as KONVERSI, 3 as JENIS from MBARANG 
					where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and IDBARANG = ? and SATUAN3 like ?
				)a";
		$query = $this->db->query($sql,
					array(
						$pagination['idbarang'],'%'.$pagination['q'].'%',
						$pagination['idbarang'],'%'.$pagination['q'].'%',
						$pagination['idbarang'],'%'.$pagination['q'].'%')
					)->result();
		return $query;
	}
	
	public function hargaBeliTerakhir($idbarang){
		$sql = "select (b.SUBTOTALKURS + IF(b.PAKAIPPN = 1,b.PPNRP,0))/b.JML AS HARGABELI
				from TBELI a
				inner join TBELIDTL b on a.IDBELI = b.IDBELI
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
					and a.STATUS = 'S' 
					and b.IDBARANG = ? 
					and a.TGLTRANS = (
						select max(t1.TGLTRANS)
						from TBELI t1
						inner join TBELIDTL t2 ON t1.IDBELI = t2.IDBELI
						where  t1.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
							and t1.STATUS = 'S' 
							and t2.IDBARANG = ?)
							and t2.IDBARANG = ?)
				order by a.KODEBELI";
        $query = $this->db->query($sql, array($idbarang,$idbarang))->row();
        if($query->HARGABELI == null || $query->HARGABELI == 0){
            $sql = "select a.HARGABELI
                    from MBARANG a
                    where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
                            and a.IDBARANG = {$idbarang}";
            $query = $this->db->query($sql)->row();
        }
		return $query;
	}
	
	public function hargaBeliAwal($idbarang){
		$sql = "select HARGA
				from SALDOSTOK a
				inner join SALDOSTOKDTL b on a.IDSALDOSTOK = b.IDSALDOSTOK
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
					and a.STATUS = 'S' 
					and b.IDBARANG = ? 
					and a.KODESALDOSTOK like '%SS%'";
		$query = $this->db->query($sql, array($idbarang))->row();
		return $query->HARGA;
	}
	
	public function hargaJualTerakhir($idbarang,$idcustomer){
		$sql = "select b.SUBTOTALKURS/b.JML AS HARGAJUAL
				from TPENJUALAN a
				inner join TPENJUALANDTL b on a.IDPENJUALAN = b.IDPENJUALAN
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
					and a.STATUS = 'S' 
					and b.IDBARANG = ? 
					and a.IDCUSTOMER = ?
					and a.TGLTRANS = (
						select max(t1.TGLTRANS)
						from TPENJUALAN t1
						inner join TPENJUALANDTL t2 ON t1.IDPENJUALAN = t2.IDPENJUALAN
						where  t1.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
							and t1.STATUS = 'S' 
							and t2.IDBARANG = ?
							and t1.IDCUSTOMER = ?)
				order by a.KODEPENJUALAN";
		$query = $this->db->query($sql, array($idbarang,$idcustomer,$idbarang,$idcustomer))->row();
		return $query;
	}
	
	//mengambil harga di mhargabeli
	public function hargaBeliBarang($idbarang,$idsupp,$tgltrans){
		$sql = "select HARGA 
				from MHARGABELI
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					  and IDBARANG = ? 
					  and IDSUPPLIER = ? 
					  and TGLAKTIF <= ?
				order by TGLAKTIF desc";
		$query = $this->db->query($sql, array($idbarang,$idsupp,$tgltrans))->row();
		return $query;
	}

	//mengambil harga di mhargajual
	public function hargaJualBarang($idbarang,$idcustomer,$tgltrans){
		$sql = "select HARGA,HARGAREF
				from MHARGAJUAL
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					  and IDBARANG = ? 
					  and IDCUSTOMER = ? 
					  and TGLAKTIF <= ?
				order by TGLAKTIF desc";
		$query = $this->db->query($sql, array($idbarang,$idcustomer,$tgltrans))->row();
		return $query;			
	}
	
	//mengambil harga di mbarang
	public function hargaBarang($idbarang){
		$sql = "select HARGABELI,HARGAJUALMIN,HARGAJUALMAX
				from MBARANG
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					  and IDBARANG = ?";
		$query = $this->db->query($sql, array($idbarang))->row();
		return $query;
	}

	public function getLastPerkiraan(){
		$sql = "select a.KODEPERKIRAANPERSEDIAAN, b.NAMAPERKIRAAN as NAMAPERKIRAANPERSEDIAAN,
					 a.KODEPERKIRAANHPP, c.NAMAPERKIRAAN as NAMAPERKIRAANHPP,a.TGLENTRY
				from MBARANG a
				left join MPERKIRAAN b on a.KODEPERKIRAANPERSEDIAAN = b.KODEPERKIRAAN and a.IDPERUSAHAAN = b.IDPERUSAHAAN
				left join MPERKIRAAN c on a.KODEPERKIRAANHPP = c.KODEPERKIRAAN and a.IDPERUSAHAAN = c.IDPERUSAHAAN
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				order by a.TGLENTRY desc";
		$query = $this->db->query($sql)->row();
		return $query;
	}
	
	public function comboGrid(){
		
		/*$sql = "select a.IDBARANG as ID, a.KODEBARANG as KODE, a.NAMABARANG as NAMA, a.SATUAN, a.WEIGHT, a.PARTNUMBER, a.BARCODE, a.CATATAN
				from MBARANG a			
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and 
					 (a.KODEBARANG like ? or a.NAMABARANG like ? or a.PARTNUMBER like ?) 
					 {$pagination['status']} 
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 200";
		
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%','%'.$pagination['q'].'%'));
		*/
		
		$sql = "select CONCAT(a.KODEBARANG,' | ',a.NAMABARANG) as VALUE, CONCAT(a.KODEBARANG,' - ',a.NAMABARANG) as TEXT, a.SATUAN, a.PARTNUMBER, a.BARCODE, a.CATATAN
				from MBARANG a			
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}  and a.stok = 1
				order by  SUBSTRING(a.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(a.URUTANTAMPIL, 2) AS UNSIGNED) ASC";
		
		$query = $this->db->query($sql);

		$data['rows'] = $query->result();
		return $data;
	}
	
	public function comboGridKategoriSaja(){

    	$sql = "SELECT URUTAN as NO, KATEGORI, IDKATEGORISHOPEE,IDKATEGORITIKTOK,IDKATEGORILAZADA FROM MKATEGORI";
    	$data['rows'] = $this->db->query($sql)->result();

		
		return $data;
	}
	
	public function comboGridKategori(){
		
		$sql = "select a.KATEGORI,a.NAMABARANG
				from MBARANG a			
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
				group by a.KATEGORI
				order by SUBSTRING(a.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(a.URUTANTAMPIL, 2) AS UNSIGNED) ASC";
		
		$query = $this->db->query($sql);

		$data['rows'] = $query->result();
		return $data;
	}
	
	function comboGridMarketplace($kategori,$marketplace){
	    
	    $arrKategori = explode("%",$kategori);
		if(count($arrKategori) > 1)
		{ 
		     $whereKategori = "and a.KATEGORI like '$kategori' ";
		}
		else
		{
		    $whereKategori = "and a.KATEGORI = '$kategori' ";
		}
		    
	    $sql = "select a.IDBARANG as ID, a.KODEBARANG as KODE, a.NAMABARANG as NAMA, if(c.KONSINYASI = 1,b.HARGAKONSINYASI,b.HARGACORET) as HARGA, SKU".$marketplace." as SKU, '' as NAMALABEL,  a.WARNA, a.SIZE
    				from MBARANG a
    				inner join MHARGA b on a.IDBARANG = b.IDBARANG
    				inner join MCUSTOMER c on c.IDCUSTOMER = b.IDCUSTOMER
    				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
    				and a.KODEBARANG != 'XXXXX'
    				and c.NAMACUSTOMER = '$marketplace'
    				$whereKategori
    				group by a.IDBARANG
    				order by SUBSTRING(a.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(a.URUTANTAMPIL, 2) AS UNSIGNED) ASC
    				";
	    
    	$query = $this->db->query($sql);
    	
    	//CEK PAKAI BARCODE ATAU TIDAK
    	$data["rows"]  = $query->result();
    	
    	foreach($data['rows'] as $item)
    	{
    	    $item->NAMALABEL = explode(" | ",$item->NAMA)[0];
    	    if(explode(" | ",$item->NAMA) > 1)
    	    {
    	        $item->NAMALABEL .= "<br><i>".$item->WARNA.", ".$item->SIZE."</i>";
    	    }
    	}
		
		return $data;
	}
	
	function comboGridAllMarketplace($marketplace){

		    
	    $sql = "select a.IDBARANG as ID, a.KODEBARANG as KODE, a.NAMABARANG as NAMA, if(c.KONSINYASI = 1,b.HARGAKONSINYASI,b.HARGACORET) as HARGA, SKU".$marketplace." as SKU, '' as NAMALABEL,  a.WARNA, a.SIZE,a.HARGAJUAL,a.KATEGORI,
	                IDINDUKBARANG".$marketplace.",IDBARANG".$marketplace."
    				from MBARANG a
    				inner join MHARGA b on a.IDBARANG = b.IDBARANG
    				inner join MCUSTOMER c on c.IDCUSTOMER = b.IDCUSTOMER
    				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
    				and a.KODEBARANG != 'XXXXX'
    				and c.NAMACUSTOMER = '$marketplace'
    				group by a.KATEGORI
    				order by SUBSTRING(a.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(a.URUTANTAMPIL, 2) AS UNSIGNED) ASC
    				";
	    
    	$query = $this->db->query($sql);
    	
    	//CEK PAKAI BARCODE ATAU TIDAK
    	$data["rows"]  = $query->result();
    	
    	foreach($data['rows'] as $item)
    	{
    	    $item->NAMALABEL = explode(" | ",$item->NAMA)[0];
    	    if(explode(" | ",$item->NAMA) > 1)
    	    {
    	        $item->NAMALABEL .= "<br><i>".$item->WARNA.", ".$item->SIZE."</i>";
    	    }
    	}
		
		return $data;
	}
	
	function comboGridAllMarketplaceVarian($marketplace){

		    
	    $sql = "select a.IDBARANG as ID, a.KODEBARANG as KODE, a.NAMABARANG as NAMA, if(c.KONSINYASI = 1,b.HARGAKONSINYASI,b.HARGACORET) as HARGA, SKU".$marketplace." as SKU, '' as NAMALABEL,  a.WARNA, a.SIZE,a.HARGAJUAL,a.KATEGORI,
	                IDINDUKBARANG".$marketplace.",IDBARANG".$marketplace."
    				from MBARANG a
    				inner join MHARGA b on a.IDBARANG = b.IDBARANG
    				inner join MCUSTOMER c on c.IDCUSTOMER = b.IDCUSTOMER
    				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
    				and a.KODEBARANG != 'XXXXX'
    				and c.NAMACUSTOMER = '$marketplace'
    				order by SUBSTRING(a.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(a.URUTANTAMPIL, 2) AS UNSIGNED) ASC
    				";
	    
    	$query = $this->db->query($sql);
    	
    	//CEK PAKAI BARCODE ATAU TIDAK
    	$data["rows"]  = $query->result();
    	
    	foreach($data['rows'] as $item)
    	{
    	    $item->NAMALABEL = explode(" | ",$item->NAMA)[0];
    	    if(explode(" | ",$item->NAMA) > 1)
    	    {
    	        $item->NAMALABEL .= "<br><i>".$item->WARNA.", ".$item->SIZE."</i>";
    	    }
    	}
		
		return $data;
	}
	
	function comboGridTransaksi($kode,$qty,$mode,$transaksi="",$kategori = ""){
	    $jenis_nomor = explode("_",$transaksi);
	    $gunakanHarga = "a.HARGAJUAL";
	    if($transaksi == '1') //BELI
    	{
    		$gunakanHarga = "a.HARGABELI";
    	}
    		
    	if(count($jenis_nomor) == 2)
    	{
    	    if($jenis_nomor[0] == 'KONSINYASI')
    	    {
    	        $gunakanHarga = "b.HARGAKONSINYASI";
    	    }
    	    
        	else
        	{
    	        $gunakanHarga = "b.HARGACORET";
        	}
        	$onCustomer = "and b.IDCUSTOMER = $jenis_nomor[1]";
    	}
	    
	    $sql = "select a.IDBARANG as ID, a.KODEBARANG as KODE, a.NAMABARANG as NAMA, ".$gunakanHarga." as HARGA, 0 as DISKON, 0 as DISKONRP, $qty as qty,a.SATUAN as SATUANBESAR, if(a.SATUAN2 = '',a.SATUAN,a.SATUAN2) as SATUANKECIL,a.STOK
    				from MBARANG a			
    				inner join MHARGA b on a.IDBARANG = b.IDBARANG $onCustomer
    				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
    				and a.KODEBARANG != 'XXXXX'
    				$whereKode
    				group by a.IDBARANG
    				order by SUBSTRING(a.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(a.URUTANTAMPIL, 2) AS UNSIGNED) ASC
    				";
	    
    	$query = $this->db->query($sql);
    	
    	//CEK PAKAI BARCODE ATAU TIDAK
    	if($mode=="BIASA")
    	{
    		$data["rows"]  = $query->result();
    	}
    	if($mode=="SALDOAWAL")
    	{
    		$data["rows"]  = $query->result();
    	}
		
		return $data;
	}
	
	function comboGridTransaksiStok($kode,$qty,$mode,$transaksi="",$kategori = "",$tgltrans,$lokasi){
	    $jenis_nomor = explode("_",$transaksi);
	    $gunakanHarga = "a.HARGAJUAL";
	    if($transaksi == '1') //BELI
    	{
    		$gunakanHarga = "a.HARGABELI";
    	}
    	
        if(count($jenis_nomor) == 2)
    	{
    	    if($jenis_nomor[0] == 'KONSINYASI')
    	    {
    	        $gunakanHarga = "b.HARGAKONSINYASI";
    	    }
    	    
        	else
        	{
    	        $gunakanHarga = "b.HARGACORET";
        	}
        	$onCustomer = "and b.IDCUSTOMER = $jenis_nomor[1]";
    	}
	    
	    $sql = "select a.IDBARANG as ID, a.KODEBARANG as KODE, a.NAMABARANG as NAMA, ".$gunakanHarga." as HARGA, 0 as DISKON, 0 as DISKONRP, $qty as qty,a.SATUAN as SATUANBESAR, if(a.SATUAN2 = '',a.SATUAN,a.SATUAN2) as SATUANKECIL,a.STOK
    				from MBARANG a			
    				inner join MHARGA b on a.IDBARANG = b.IDBARANG $onCustomer
    				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
    				and a.KODEBARANG != 'XXXXX'
    				$whereKode
    				group by a.IDBARANG
    				order by SUBSTRING(a.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(a.URUTANTAMPIL, 2) AS UNSIGNED) ASC
    				";
	    
    	$query = $this->db->query($sql);
    	
    	//CEK PAKAI BARCODE ATAU TIDAK
    	if($mode=="BIASA")
    	{
    		$data["rows"]  = $query->result();
    		foreach($data["rows"] as $item)
    		{
    		    
    	        $result   = get_saldo_stok_new($_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],$item->ID, $lokasi, $tgltrans);
    	        $item->STOK = $result->QTY;
    		}
    	}
	
		return $data;
	}
	
	function getDataBarang($kode,$qty,$mode,$transaksi){
		
		if($mode=="LANGSUNG")
		{
			$whereKode = "and a.KODEBARANG = '$kode' ";
		}
		if($mode=="BARCODE")
		{
			$whereKode = "and a.BARCODE = '$kode' ";
		}
		
		$jenis_nomor = explode("_",$transaksi);
		
		$gunakanHarga = "a.HARGAJUAL";
	    if($transaksi == '1') //BELI
    	{
    		$gunakanHarga = "a.HARGABELI";
    	}
    	
        if(count($jenis_nomor) == 2)
    	{
    	    if($jenis_nomor[0] == 'KONSINYASI')
    	    {
    	        $gunakanHarga = "b.HARGAKONSINYASI";
    	    }
    	    
        	else
        	{
    	        $gunakanHarga = "b.HARGACORET";
        	}
        	$onCustomer = "and b.IDCUSTOMER = $jenis_nomor[1]";
    	}
	    
	    
	    $sql = "select a.IDBARANG as ID, a.KODEBARANG as KODE, a.NAMABARANG as NAMA, ".$gunakanHarga." as HARGA, 0 as DISKON, 0 as DISKONRP, $qty as qty,a.SATUAN as SATUANBESAR, if(a.SATUAN2 = '',a.SATUAN,a.SATUAN2) as SATUANKECIL,a.STOK
    				from MBARANG a			
    				inner join MHARGA b on a.IDBARANG = b.IDBARANG $onCustomer
    				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
    				and a.KODEBARANG != 'XXXXX'
    				$whereKode
    				group by a.IDBARANG
    				order by SUBSTRING(a.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(a.URUTANTAMPIL, 2) AS UNSIGNED) ASC
    				";
	    
		$query = $this->db->query($sql);
		
		$data["rows"]  = $query->row();
		
		
		return $data;
	}
	
	public function comboGridStok($pagination){
		if (!isset($pagination['sort'])) {
			$pagination['sort'] = 'a.NAMABARANG';
		}
		$sql = "select a.IDBARANG as ID, a.KODEBARANG as KODE, a.NAMABARANG as NAMA, a.SATUAN, a.WEIGHT, a.PARTNUMBER, a.BARCODE, a.CATATAN,sum(if(b.MK = 'M', b.JML, -b.JML)) as JML, sum(if(b.MK = 'M', b.TOTALHARGA, -b.TOTALHARGA)) as SUBTOTAL
				from MBARANG a			
				left join KARTUSTOK b on a.IDBARANG = b.IDBARANG	
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					  and (a.KODEBARANG like ? or a.NAMABARANG like ?) 
					  and 1=1 and a.STATUS = 1
				group by a.IDBARANG, a.KODEBARANG, a.NAMABARANG, a.SATUAN, a.WEIGHT,a.CATATAN
				order by {$pagination['sort']} {$pagination['order']}
				limit 0, 200";
		
		$query = $this->db->query($sql, array('%'.$pagination['q'].'%','%'.$pagination['q'].'%'));

		$data['rows'] = $query->result();
		return $data;
	}

	public function cekTransaksiBarang($idbarang){
		$sql = "select IDPERUSAHAAN, IDBARANG from (
					select a.IDPERUSAHAAN, a.IDBARANG from TSODTL a
					union
					select b.IDPERUSAHAAN, b.IDBARANG from TPODTL b
					union
					select c.IDPERUSAHAAN, c.IDBARANG from SALDOSTOKDTL c
					union
					select d.IDPERUSAHAAN, d.IDBARANG from KARTUSTOK d
				) as e
				where e.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
						and e.IDBARANG = {$idbarang}";
		$query = $this->db->query($sql)->num_rows();
		return $query;
	}

	public function dataGrid($jenis){
	    $whereItemJenis = "";
	    if($jenis == "SHOPEE")
	    {
	        $whereItemJenis = "and a.idindukbarangshopee = 0";
	    }
	    else if($jenis == "TIKTOK")
	    {
	        $whereItemJenis = "and a.idindukbarangtiktok = 0";
	    }
	    else if($jenis == "LAZADA")
	    {
	        $whereItemJenis = "and a.idindukbaranglazada = 0";
	    }
	    
		$data = [];
		$sql = "select a.KATEGORIONLINE, a.KATEGORI,(SELECT count(x.KATEGORI) FROM MBARANG x WHERE x.KATEGORI = a.KATEGORI and (x.WARNA <> '' || x.WARNA <> 0)) as JMLVARIAN,if(MIN(a.HARGAJUAL) <> MAX(a.HARGAJUAL),CONCAT(FORMAT(MIN(a.HARGAJUAL),0),'  -  ',FORMAT(MAX(a.HARGAJUAL),0)),FORMAT(MAX(a.HARGAJUAL),0)) as RANGEHARGAUMUM,MAX(a.TGLENTRY) as TGLENTRY,
		        if(sum(a.STATUS) > 0,1,0) as STATUS,a.DESKRIPSI,a.PANJANG,a.LEBAR,a.TINGGI,a.BERAT,a.KODEBARANG,a.IDBARANG,a.IDINDUKBARANGSHOPEE,a.IDINDUKBARANGTIKTOK,a.IDINDUKBARANGLAZADA,
		        a.HARGAJUAL,a.HARGABELI,a.SKUGOJEK,a.SKUGRAB,a.SKUSHOPEE,a.SKUTIKTOK,a.SKULAZADA,a.SKUTOKPED,a.BARCODE,a.SATUAN
				from MBARANG a
				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} $whereItemJenis
				group by a.KATEGORI
				order by SUBSTRING(a.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(a.URUTANTAMPIL, 2) AS UNSIGNED) ASC";
		$query = $this->db->queryRaw($sql);
		$data['rows'] = $query->result();

		return $data;
	}
	
	public function getDataVarian($kategori=""){
		$data['rows'] = [];
		if($kategori != "")
		{
		    
		    $arrKategori = explode("%",$kategori);
		    if(count($arrKategori) > 1)
		    { 
		         $whereKategori = "and a.KATEGORI like '$kategori' ";
		    }
		    else
		    {
			    $whereKategori = "and a.KATEGORI = '$kategori' ";
		    }
		    
    		$sql = "select a.IDBARANG, a.KODEBARANG, a.NAMABARANG,
    		               a.IDBARANGSHOPEE,a.IDBARANGTIKTOK,a.IDBARANGLAZADA,
    		               a.IDINDUKBARANGSHOPEE,a.IDINDUKBARANGTIKTOK,a.IDINDUKBARANGLAZADA,
    					   a.SATUAN, a.SIZE,a.WARNA,
    					   a.HARGABELI, a.HARGAJUAL, a.CATATAN, 
    					   a.TGLENTRY, a.STATUS, e.USERNAME as USERBUAT,a.SKUSHOPEE,a.SKUTOKPED,a.SKUTIKTOK,a.SKULAZADA,'' as MODE,a.BARCODE, '' as MODE
    				from MBARANG a
    				left join MUSER e on a.USERENTRY = e.USERID
    				where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
    				and (a.WARNA <> '' or a.SIZE <> 0)
    				$whereKategori
    				order by SUBSTRING(a.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(a.URUTANTAMPIL, 2) AS UNSIGNED) ASC";
    		$query = $this->db->query($sql);
    		
    		$data['rows'] = $query->result();
		}

		return $data;
	}
	
	public function dataGridVarian(){
	    
		$sql = "select a.KATEGORIONLINE,a.KATEGORI, a.KODEBARANG, a.NAMABARANG,a.BARCODE,
        			   a.SATUAN, if(a.SIZE = 0, '',a.SIZE) AS UKURAN,a.WARNA,
        			   FORMAT(a.HARGABELI,0) as HARGABELI, FORMAT(a.HARGAJUAL,0) as HARGAJUAL, a.CATATAN, 
        			   a.TGLENTRY, a.STATUS, e.USERNAME as USERBUAT,a.SKUSHOPEE,a.SKUTOKPED,a.SKUTIKTOK,a.SKULAZADA,a.BERAT,a.PANJANG,a.LEBAR,a.TINGGI
        		from MBARANG a
        		left join MUSER e on a.USERENTRY = e.USERID
        		where a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}
        		and (a.WARNA <> '' or a.SIZE <> 0)
        		order by SUBSTRING(a.URUTANTAMPIL, 1, 1) ASC ,
    		CAST(SUBSTRING(a.URUTANTAMPIL, 2) AS UNSIGNED) ASC";
        		
        $query = $this->db->query($sql);
        
        $data['rows'] = $query->result();

		return $data;
	}
	
	function simpan($id,$data,$edit,$datagambar = []){
		$this->db->trans_begin();
		
		if($edit){
			 //insert ulang gambar barang
				 
			//update di database utama 
			$this->db->where("IDBARANG",$id)
					 ->where("IDPERUSAHAAN",$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
			$this->db->updateRaw('MBARANG',$data);
		}else{
			//insert baru di database utama
			$this->db->insertRaw('MBARANG',$data);
			$id = $this->db->insert_id();
			
			$sqlCustomer = "select * from mcustomer where status = 1 and idperusahaan = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']}"; 
    		$queryCustomer = $this->db->query($sqlCustomer)->result();
    		
    		$index = 0;
    		foreach($queryCustomer as $itemCustomer){
                if($index == 100)
                {   
                    // Sleep for 500 milliseconds (0.5 seconds)
                    usleep(2000000);
                    $index = 0;
                }
                
                $index++;
        		$this->db->insert('MHARGA',[
        		    "IDPERUSAHAAN"      => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
        		    "IDBARANG"          => $id,
        		    "IDCUSTOMER"        => $itemCustomer->IDCUSTOMER,
        		    "HARGACORET"        => $data['HARGAJUAL'],
        		    "HARGAKONSINYASI"   => $data['HARGAJUAL']
        		]);
    		}
		}
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 'Gagal menyimpan pada database';
		}
		
		//UPDATE IDBARANG TIAP MARKETPLACE
		
		$kategori = str_replace("%2F","%",str_replace("%7C","%",str_replace("%20"," ",$data['KATEGORI'])));
	    $arrKategori = explode("%",$kategori);
	    
		if(count($arrKategori) > 1)
		{ 
		     $whereKategori = " KATEGORI like '$kategori' ";
		}
		else
		{
	        $whereKategori = " KATEGORI = '$kategori' ";
		}
		
		$sql = "SELECT IDINDUKBARANGSHOPEE,IDINDUKBARANGTIKTOK,IDINDUKBARANGLAZADA,BOOSTSHOPEE 
		           FROM MBARANG  
		           WHERE 
		            $whereKategori
		            AND ((IDINDUKBARANGSHOPEE <> 0 || IDINDUKBARANGSHOPEE <> '')
		            OR (IDINDUKBARANGTIKTOK <> 0 || IDINDUKBARANGTIKTOK <> '')
		            OR (IDINDUKBARANGLAZADA <> 0 || IDINDUKBARANGLAZADA <> ''))
		          ";
		
		$induk = $this->db->queryRaw($sql)->row()??"";

		if($induk != "")
		{
    			$sql = "UPDATE MBARANG SET 
    			   BOOSTSHOPEE = '$induk->BOOSTSHOPEE',
    			   IDINDUKBARANGSHOPEE = '$induk->IDINDUKBARANGSHOPEE',
    			   IDINDUKBARANGTIKTOK = '$induk->IDINDUKBARANGTIKTOK',
    			   IDINDUKBARANGLAZADA = '$induk->IDINDUKBARANGLAZADA' 
    			   WHERE 
    			    $whereKategori
		            AND ((IDINDUKBARANGSHOPEE = 0 || IDINDUKBARANGSHOPEE = '')
		            OR (IDINDUKBARANGTIKTOK = 0 || IDINDUKBARANGTIKTOK = '')
		            OR (IDINDUKBARANGLAZADA = 0 || IDINDUKBARANGLAZADA = ''))
		          ";
		
		    $this->db->queryRaw($sql);
		}
		
		
		
// 		// Menyimpan JSON ke dalam file
//         $file = 'assets/barang/dataGambar_'.$id.'.json';
//         if (file_put_contents($file, $this->input->post('BASE64IMAGES')??"")) {
//         } else {
//             $this->db->trans_rollback();
// 			return 'Gagal menyimpan gambar';
//         }
			 
		$this->db->trans_commit();
		return '';
	}

	function hapus($id){
		$this->db->trans_begin();
		
		if(checkBarangPadaTransaksi($id) == 1)
		{
    		return 'Data Barang Tidak Dapat Dihapus, Data Sudah Digunakan Pada Transaksi'; 
		}
		
		$this->db->where("IDPERUSAHAAN",$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				 ->where('IDBARANG',$id)
				 ->delete('MBARANG');
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data Barang Tidak Dapat Dihapus'; 
		}
		
	
        // $file = 'assets/barang/dataGambar_'.$id.'.json';

        // if (file_exists($file)) {
        //     // Try to delete the file
        //     if (unlink($file)) {
        //     } else {
        //         return "There was an error deleting the file.";
        //     }
        // } else {
        //     return "The file '$file' does not exist.";
        // }
		
		$this->db->where("IDPERUSAHAAN",$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				 ->where('IDBARANG',$id)
				 ->delete('MHARGA');
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data Barang Tidak Dapat Dihapus'; 
		}
		
		$this->db->trans_commit();
		return '';
	}
	
	function hapusHeader($kategori){
		$this->db->trans_begin();
	    $arrKategori = explode("%",$kategori);
	    
		if(count($arrKategori) > 1)
		{ 
		     $whereKategori = " KATEGORI like '$kategori' ";
		}
		else
		{
	        $whereKategori = " KATEGORI = '$kategori' ";
		}
		$sql = "SELECT * FROM MBARANG  WHERE $whereKategori ";
		
		$dataBarang = $this->db->query($sql)->result();
		
		foreach($dataBarang as $item)
		{
    		if(checkBarangPadaTransaksi($item->IDBARANG) == 1)
    		{
        		return 'Data Barang Tidak Dapat Dihapus, Data Sudah Digunakan Pada Transaksi'; 
    		}
		}
		
		$sql = "DELETE FROM MBARANG  WHERE $whereKategori ";
		
		$this->db->query($sql);
		
	
        // $file = 'assets/barang/dataGambar_'.$id.'.json';

        // if (file_exists($file)) {
        //     // Try to delete the file
        //     if (unlink($file)) {
        //     } else {
        //         return "There was an error deleting the file.";
        //     }
        // } else {
        //     return "The file '$file' does not exist.";
        // }
		
		$this->db->where("IDPERUSAHAAN",$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
				 ->where('IDBARANG',$id)
				 ->delete('MHARGA');
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data Barang Tidak Dapat Dihapus'; 
		}
		
		$this->db->trans_commit();
		return '';
	}
	
	function cek_valid_kode($kode){
		$sql = "select KODEBARANG, NAMABARANG 
				from MBARANG
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					  and KODEBARANG = ?";
		$query = $this->db->query($sql, $kode)->row();
		if(isset($query)){
			return 'Kode Barang Sudah Digunakan Oleh Barang ('.$query->KODEBARANG.') '.$query->NAMABARANG.',<br>Kode Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function cek_valid_barcode($barcode){
		$sql = "select KODEBARANG, NAMABARANG 
				from MBARANG
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					  and BARCODE = ?";
		$query = $this->db->query($sql, $barcode)->row();
		if(isset($query)){
			return 'Barcode Sudah Digunakan Oleh Barang ('.$query->KODEBARANG.') '.$query->NAMABARANG.',<br>Barcode Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function cek_valid_sku_shopee($sku){
		$sql = "select KODEBARANG, NAMABARANG 
				from MBARANG
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					  and SKUSHOPEE = ?";
		$query = $this->db->query($sql, $sku)->row();
		if(isset($query)){
			return 'SKU Shopee Sudah Digunakan Oleh Barang ('.$query->KODEBARANG.') '.$query->NAMABARANG.',<br>SKU Shopee Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function cek_valid_sku_tiktok($sku){
		$sql = "select KODEBARANG, NAMABARANG 
				from MBARANG
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					  and SKUTIKTOK = ?";
		$query = $this->db->query($sql, $sku)->row();
		if(isset($query)){
			return 'SKU Tiktok Sudah Digunakan Oleh Barang ('.$query->KODEBARANG.') '.$query->NAMABARANG.',<br>SKU Tiktok Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function cek_valid_sku_lazada($sku){
		$sql = "select KODEBARANG, NAMABARANG 
				from MBARANG
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					  and SKULAZADA = ?";
		$query = $this->db->query($sql, $sku)->row();
		if(isset($query)){
			return 'SKU Lazada Sudah Digunakan Oleh Barang ('.$query->KODEBARANG.') '.$query->NAMABARANG.',<br>SKU Lazada Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function cek_valid_nama($nama,$kode=" "){
		$sql = "select KODEBARANG, NAMABARANG 
				from MBARANG a
				where IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} 
					  and KODEBARANG <> ? 
					  and NAMABARANG = ?";
		$query = $this->db->query($sql, array($kode,$nama))->row();
		if(isset($query)){
			return 'Nama Barang Sudah Digunakan Oleh Barang ('.$query->KODEBARANG.') '.$query->NAMABARANG.',<br>Nama Tidak Dapat Digunakan';
		}else{
			return '';
		}
	}
	
	function getGambarBarang($idbarang){
	    $file = 'assets/barang/dataGambar_'.$idbarang.'.json';
	    return json_decode(file_get_contents($file),true);
	}
	
	function getDataBarangBySKU($sku){
	   $sql = "select IDBARANG, NAMABARANG from MBARANG where SKUSHOPEE = '$sku' or SKUTIKTOK  = '$sku' or SKULAZADA  = '$sku'";
	   return $this->db->query($sql)->row();
	}
	
	function getUrutanTampil($kategori,$kategorionline,$mode,$kodebarang){
	    

       $ada = false;
       $tanda = "";
       
       if($kategorionline == "PEMBUNGKUS KADO DAN KEMASAN")
       {
           $tanda = " = ";
           $arrUrutanAbjad = ["V","W","X","Y","Z"];
       }
       else
       {
           $tanda = " != ";
           $arrUrutanAbjad = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U"];
       }
       
       $simpanAbjad = $arrUrutanAbjad[0];
       $simpanUrutan = 0;
       
       $sqlAwal = "select URUTANTAMPIL as URUTAN from MBARANG where KATEGORI = '$kategori' ORDER BY SUBSTRING(URUTANTAMPIL, 1, 1) DESC,
        CAST(SUBSTRING(URUTANTAMPIL, 2) AS UNSIGNED) DESC";
       
       //JIKA BARANG SEBELUMNYA ADA
       if($this->db->query($sqlAwal)->row()->URUTAN != null)
       {
           $abjadAwal = $this->db->query($sqlAwal)->row()->URUTAN[0];
       
            
           //JIKA ABJADNYA ADA AMBIL DARI YANG ADA
           for($x = 0 ; $x < count($arrUrutanAbjad) ; $x++)
       	   {
       	       if($arrUrutanAbjad[$x] == $abjadAwal)
       	       {
       	           $simpanAbjad = $arrUrutanAbjad[$x];
       	           $ada = true;
       	       }
       	   }
       }
          
       //JIKA BARANG BARU ATAU TIDAK ADA URUTAN AMBIL DARI ABJAD TERAKHIR + 1
       if(!$ada)
       {
           $sqlUrutan = "select URUTANTAMPIL as URUTAN from MBARANG where KATEGORIONLINE ".$tanda." 'PEMBUNGKUS KADO DAN KEMASAN' ORDER BY SUBSTRING(URUTANTAMPIL, 1, 1) DESC,
                        CAST(SUBSTRING(URUTANTAMPIL, 2) AS UNSIGNED) DESC";

           $abjadAwal = $this->db->query($sqlUrutan)->row()->URUTAN[0];

         
       	   for($x = 0 ; $x < count($arrUrutanAbjad) ; $x++)
       	   {
       	       if($arrUrutanAbjad[$x] == $abjadAwal)
       	       {
       	           $simpanAbjad = $arrUrutanAbjad[$x+1];
       	       }
       	   }
       }
       
       return [
            'ABJAD' => $simpanAbjad,
            'URUTAN' => $simpanUrutan
       ];
	}
	
// 		function getUrutanTampil($kategori,$kategorionline,$mode,$kodebarang){
	    

//       $ada = false;
//       $tanda = "";
       
//       if($kategorionline == "PEMBUNGKUS KADO DAN KEMASAN")
//       {
//           $tanda = " = ";
//           $arrUrutanAbjad = ["V","W","X","Y","Z"];
//       }
//       else
//       {
//           $tanda = " != ";
//           $arrUrutanAbjad = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U"];
//       }
       
//       $simpanAbjad = $arrUrutanAbjad[0];
//       $simpanUrutan = 1;
       
//       $sqlAwal;
//       $sqlKategori = "select URUTANTAMPIL as URUTAN from MBARANG where KATEGORI = '$kategori' ORDER BY SUBSTRING(URUTANTAMPIL, 1, 1) DESC,
//         CAST(SUBSTRING(URUTANTAMPIL, 2) AS UNSIGNED) DESC";
//       //JIKA TIDAK ADA DATA
// 	   if($mode == 'tambah')
// 	   {
//     	  $sqlAwal = $sqlKategori;
           
// 	   }
// 	   //JIKA ADA DATA
//       else if($mode == "ubah")
//       {
//           $sqlAwal = "select URUTANTAMPIL as URUTAN from MBARANG where KODEBARANG = '$kodebarang'";
//       }
       
//       //JIKA BARANG SEBELUMNYA ADA
//       if($this->db->query($sqlAwal)->row()->URUTAN != null)
//       {
//           $abjadAwal = $this->db->query($sqlAwal)->row()->URUTAN[0];
       
            
//           //JIKA ABJADNYA ADA AMBIL DARI YANG ADA
//           for($x = 0 ; $x < count($arrUrutanAbjad) ; $x++)
//       	   {
//       	       if($arrUrutanAbjad[$x] == $abjadAwal)
//       	       {
//       	           $simpanAbjad = $arrUrutanAbjad[$x];
//                   $simpanUrutan = intval(explode($simpanAbjad,$this->db->query($sqlAwal)->row()->URUTAN)[1]);
//                   if($mode == 'tambah')
//                   {
//                       $simpanUrutan++;
//                   }
//       	           $ada = true;
//       	       }
//       	   }
//       }
          
//       //JIKA BARANG BARU ATAU TIDAK ADA URUTAN AMBIL DARI ABJAD TERAKHIR + 1
//       if(!$ada)
//       {
//           $sqlUrutan = "select URUTANTAMPIL as URUTAN from MBARANG where KATEGORIONLINE ".$tanda." 'PEMBUNGKUS KADO DAN KEMASAN' ORDER BY SUBSTRING(URUTANTAMPIL, 1, 1) DESC,
//                         CAST(SUBSTRING(URUTANTAMPIL, 2) AS UNSIGNED) DESC";

//           $abjadAwal = $this->db->query($sqlUrutan)->row()->URUTAN[0];

         
//       	   for($x = 0 ; $x < count($arrUrutanAbjad) ; $x++)
//       	   {
//       	       if($arrUrutanAbjad[$x] == $abjadAwal)
//       	       {
//       	           $simpanAbjad = $arrUrutanAbjad[$x+1];
//       	       }
//       	   }
      
       	   
//             if($mode == 'ubah'){
//                 if($this->db->query($sqlKategori)->row()->URUTAN != null)
//                 {
//                   $abjadAwal = $this->db->query($sqlKategori)->row()->URUTAN[0];
//                     //JIKA ABJADNYA ADA AMBIL DARI YANG ADA
//                   for($x = 0 ; $x < count($arrUrutanAbjad) ; $x++)
//               	   {
//               	       if($arrUrutanAbjad[$x] == $abjadAwal)
//               	       {
//               	           $simpanAbjad = $arrUrutanAbjad[$x];
//                           $simpanUrutan = intval(explode($simpanAbjad,$this->db->query($sqlKategori)->row()->URUTAN)[1]);
//                           $simpanUrutan++;
//               	       }
//               	   }
//                 }
//             }
//       }
       
//       return [
//             'ABJAD' => $simpanAbjad,
//             'URUTAN' => $simpanUrutan
//       ];
// 	}
	
	function ubahUrutanTampil($dataKategori){
	   $index = 0;
       $indexNonBungkus = -1;
       $indexBungkus = -1;
       for($x = 0 ; $x < count($dataKategori);$x++)
       {
           
           $sql = "SELECT * FROM MBARANG WHERE KATEGORI = '".$dataKategori[$x]->KATEGORI."' AND KATEGORIONLINE = '".$dataKategori[$x]->KATEGORIONLINE."'";
           $query = $this->db->query($sql)->result();
           
           if($dataKategori[$x]->KATEGORIONLINE == "PEMBUNGKUS KADO DAN KEMASAN")
           {
               $tanda = " = ";
               $arrUrutanAbjad = ["V","W","X","Y","Z"];
               $indexNonBungkus++;
               $index = $indexNonBungkus;
           }
           else
           {
               $tanda = " != ";
               $arrUrutanAbjad = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U"];
               $indexBungkus++;
               $index = $indexBungkus;
           }
       
           $sqlUrutan = "UPDATE MBARANG SET URUTANTAMPIL = REPLACE(URUTANTAMPIL,substr(URUTANTAMPIL,1,1),'".$arrUrutanAbjad[$index]."') WHERE KATEGORI = '".$dataKategori[$x]->KATEGORI."'";
           $this->db->query($sqlUrutan);
       }
    return '';
	}
	
	function updateUrutanTampil($data){
	    foreach($data as $item)
	    {
	    	$this->db->where("KODEBARANG",$item['KODEBARANG'])
	    	         ->or_where("SKUSHOPEE",$item['SKU'])
					 ->where("IDPERUSAHAAN",$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
			$this->db->update('MBARANG',array(
			    'URUTANTAMPIL' => $item['URUTANTAMPIL']));
			    
        	if ($this->db->trans_status() === FALSE) { 
    			$this->db->trans_rollback();
    			return 'Ada Kesalahan Pada Perubahan Urutan'; 
		    }
	    }
	    return '';
	}

	function getStok($idBarang, $idLokasi, $tgl) {
		$tgl = $tgl ?? date('Y-m-d');
		$result   = get_saldo_stok_new($_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],$idBarang, $idLokasi, $tgl);
		return $result;
    }
}
?>