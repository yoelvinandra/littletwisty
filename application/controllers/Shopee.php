<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Shopee extends MY_Controller {
	public function index()
	{
	    echo "
	         <b>Tutorial Cara Menggunakan Shopee API</b>
	         <br><br>
	         1. Masuk ke Shopee Console, masukkan data-data ini ke database :<br>
	         - Live Partner ID <br>
	         - Live Partner Key (Detail App List)
	         <br><br>
	         2. Buat link auth yang akan diinput, ketika klik Authorize (Console -> App List)<br>
	            Caranya : akses ".$this->config->item('base_url')."/Shopee/getAuth?
	         <br><br>
	         3. Lanjutkan Proses hingga di url browser, muncul Code dan Shop ID masukkan data-data ini ke database
	         <br><br>
	         4. Dapatkan akses token untuk Akses API<br>
	            Caranya : akses ".$this->config->item('base_url')."/Shopee/getToken?
	        <br><br> 
	         5. Masukkan akses token kedalam database
	         <br><br>
	         6. ketika menggunakan API, gunakan controller Shopee/getAPI atau Shopee/postAPI, dan masukkan pathnya kesana.
	         ";
	}
	
	public function getAuth()
	{
	    $partnerId = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_ID');
	    $partnerKey = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_KEY');

        $host = "https://partner.shopeemobile.com";
        $path = "/api/v2/shop/auth_partner";
        $redirectUrl = "https://".$_SERVER['SERVER_NAME'] . str_replace('getAuth?','setCodeandShop?',$_SERVER['REQUEST_URI']);
     
        $timest = time();
        $baseString = sprintf("%s%s%s", $partnerId, $path, $timest);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);
        $url = sprintf("%s%s?partner_id=%s&time[SAMBUNG]stamp=%s&sign=%s&redirect=%s", $host, $path, $partnerId, $timest, $sign, $redirectUrl);
        echo "Authorize Link : ".($url)."<br><br>Hapus [SAMBUNG]";
	}
	
	public function setCodeandShop(){
	    $this->model_master_config->setConfigMarketplace('SHOPEE','CODE',$this->input->get("code"));
        $this->model_master_config->setConfigMarketplace('SHOPEE','SHOP_ID',$this->input->get("shop_id"));
        echo "Code dan Shop ID berhasil disimpan didatabase<br><br>akses https://".$_SERVER['SERVER_NAME'] . str_replace("setCodeandShop?code=".$this->input->get("code")."&shop_id=".$this->input->get("shop_id"),"getToken?",$_SERVER['REQUEST_URI']);
	}
	
	public function getToken()
	{
	    $code = $this->model_master_config->getConfigMarketplace('SHOPEE','CODE');
	    $shopId = $this->model_master_config->getConfigMarketplace('SHOPEE','SHOP_ID');
	    $partnerId = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_ID');
	    $partnerKey = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_KEY');
        
        $host = 'https://partner.shopeemobile.com';
        $path = "/api/v2/auth/token/get";
        
        $timest = time();
        $body = array("code" => $code,  "shop_id" => (int)$shopId, "partner_id" => (int)$partnerId);
        $baseString = sprintf("%s%s%s", $partnerId, $path, $timest);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);
        $url = sprintf("%s%s?partner_id=%s&timestamp=%s&sign=%s", $host, $path, $partnerId, $timest, $sign);
    
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($c);
        $ret = json_decode($resp, true);

        if($ret['error'] != "")
        {
           echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $accessToken = $ret["access_token"];
            $newRefreshToken = $ret["refresh_token"];
            
            if($accessToken != null && $newRefreshToken != null )
            {
                $this->model_master_config->setConfigMarketplace('SHOPEE','ACCESS_TOKEN',$accessToken);
                $this->model_master_config->setConfigMarketplace('SHOPEE','REFRESH_TOKEN',$newRefreshToken);
            }
            
            echo "Access Token : ".($accessToken)."<br>Refresh Token : ".($newRefreshToken)."<br><br>Berhasil disimpan di database";
        }
        
	}
	
	public function getRefreshToken(){
	    
	    $refreshToken = $this->model_master_config->getConfigMarketplace('SHOPEE','REFRESH_TOKEN');
	    $shopId = $this->model_master_config->getConfigMarketplace('SHOPEE','SHOP_ID');
	    $partnerId = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_ID');
	    $partnerKey = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_KEY');
        
	    
        $host = 'https://partner.shopeemobile.com';
        $path = "/api/v2/auth/access_token/get";
        
        $timest = time();
        $body = array("partner_id" => (int)$partnerId, "shop_id" => (int)$shopId, "refresh_token" => $refreshToken);
        $baseString = sprintf("%s%s%s", $partnerId, $path, $timest);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);
        $url = sprintf("%s%s?partner_id=%s&timestamp=%s&sign=%s", $host, $path, $partnerId, $timest, $sign);
    
    
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    
        $result = curl_exec($c);
    
        $ret = json_decode($result, true);
    
        if($ret['error'] != "")
        {
        //   echo $ret['error']." : ".$ret['message'];
           return 'false';
        }
        else
        {
            $accessToken = $ret["access_token"];
            $newRefreshToken = $ret["refresh_token"];
            
            if($accessToken != null && $newRefreshToken != null )
            {
                $this->model_master_config->setConfigMarketplace('SHOPEE','ACCESS_TOKEN',$accessToken);
                $this->model_master_config->setConfigMarketplace('SHOPEE','REFRESH_TOKEN',$newRefreshToken);
            }
            
            // echo "Access Token : ".($accessToken)."<br>Refresh Token : ".($newRefreshToken)."<br><br>Berhasil disimpan di database";
            return 'true';
        }
        

	}
	
	public function getAPI()
	{
	    $this->output->set_content_type('application/json');
	    $response =  $this->getRefreshToken();
	    if($response)
	    {
    	    $endpoint = $this->input->post("endpoint");
    	    $parameter = $this->input->post("parameter");
    	    
    	    $code = $this->model_master_config->getConfigMarketplace('SHOPEE','CODE');
    	    $shopId = $this->model_master_config->getConfigMarketplace('SHOPEE','SHOP_ID');
    	    $partnerId = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_ID');
    	    $partnerKey = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_KEY');
    	    $accessToken = $this->model_master_config->getConfigMarketplace('SHOPEE','ACCESS_TOKEN');
    	    
            $path = "/api/v2/";
            
            $timest = time();
            $sign = hash_hmac('sha256', $partnerId.$path.$endpoint.$timest.$accessToken.$shopId,$partnerKey);
            
            $host = 'https://partner.shopeemobile.com'.$path.$endpoint;
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $host.'?access_token='.$accessToken.'&timestamp='.$timest.'&sign='.$sign.'&shop_id='.$shopId.'&partner_id='.$partnerId.$parameter,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Cookie: SPC_SEC_SI=v1-UHAwR1pMemVVcnVNWk0yOAmAUDORs28JVn6OsCvGiwjQgIgi70oQOziTBqSXWyDZViEAeIWx3hwddNsCKaZ6iPdS4KmbbymAcw3YAhkMr6I=; SPC_SI=P7sdaAAAAABzUmtoeEtWN8sBwggAAAAATEVRUWR2b0Y='
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            echo $response;
	    }
	    else
	    {
	       // echo "Token gagal diperbaharui";
	        echo array(
	            "error" => "failed refresh token"
	        );
	    }
	}
	
	public function postAPI()
	{
	    $this->output->set_content_type('application/json');
	    $response =  $this->getRefreshToken();
	    if($response)
	    {
    	    $endpoint = $this->input->post("endpoint");
    	    $parameter = $this->input->post("parameter");
    	    
    	    $code = $this->model_master_config->getConfigMarketplace('SHOPEE','CODE');
    	    $shopId = $this->model_master_config->getConfigMarketplace('SHOPEE','SHOP_ID');
    	    $partnerId = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_ID');
    	    $partnerKey = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_KEY');
    	    $accessToken = $this->model_master_config->getConfigMarketplace('SHOPEE','ACCESS_TOKEN');
            $path = "/api/v2/";
            
            $timest = time();
            $sign = hash_hmac('sha256', $partnerId.$path.$endpoint.$timest.$accessToken.$shopId,$partnerKey);
            
            $host = 'https://partner.shopeemobile.com'.$path.$endpoint;
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $host.'?access_token='.$accessToken.'&timestamp='.$timest.'&sign='.$sign.'&shop_id='.$shopId.'&partner_id='.$partnerId,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_POSTFIELDS => ($parameter),
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: SPC_SEC_SI=v1-UHAwR1pMemVVcnVNWk0yOAmAUDORs28JVn6OsCvGiwjQgIgi70oQOziTBqSXWyDZViEAeIWx3hwddNsCKaZ6iPdS4KmbbymAcw3YAhkMr6I=; SPC_SI=P7sdaAAAAABzUmtoeEtWN8sBwggAAAAATEVRUWR2b0Y='
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            echo $response;
	    }
        else
	    {
	       // echo "Token gagal diperbaharui";
	        echo array(
	            "error" => "failed refresh token"
	        );
	    }
        
	}
	
	public function connectBarang(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');;
		$status = ["NORMAL","BANNED","UNLIST","REVIEWING","SELLER_DELETE","SHOPEE_DELETE"];
		$statusok = true;
		$statusParam = "";
		$data["msg"] = "TIDAK ADA IDBARANGSHOPEE YANG DIUPDATE";
		$data["total"] = 0;
		$offset = 0;
		$pageSize = 100;
		for($x = 0 ;$x < count($status); $x++)
		{
		    $statusParam .= "&item_status=".$status[$x];
		}
		
		//LOGISTIC
		$curl = curl_init();
		
		while(!$bigger && $statusok)
        {
            
		    $parameter = "&offset=".$offset."&page_size=".$pageSize.$statusParam;
		    
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_item_list','parameter' => $parameter),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            if($ret['error'] != "")
            {
                echo $ret['error']." : ".$ret['message'];
            }
            else
            {
                $response = $ret['response'];
                $statusok = $response['has_next_page'];
                $offset = $response['next_offset'];
                $idbarang = $response['item'];
                $paramId = "";
                for($x = 0 ; $x < count($idbarang);$x++)
                {
                
                    //GET MODEL
                    $parameter = "&item_id=".(int)$idbarang[$x]['item_id'];
                    $curl = curl_init();
                    
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_model_list','parameter' => $parameter),
                      CURLOPT_HTTPHEADER => array(
                        'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                      ),
                    ));
                    
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $ret =  json_decode($response,true);
                    if($ret['error'] != "")
                    {
                        echo $ret['error']." : ".$ret['message'];
                        $statusok = false;
                    }
                    else
                    {
                        $dataModel = $ret['response']['model'];
                        for($m = 0 ; $m < count($dataModel);$m++)
                        {
                            $sku = "";
                            if($dataModel[$m]['model_sku'] == "")
                            {
                                 $sku = $dataModel[$m]['item_sku'];
                            }
                            else
                            {
                                 $sku = $dataModel[$m]['model_sku'];
                            }
                            
                            $sql = "UPDATE MBARANG SET IDBARANGSHOPEE = ".$dataModel[$m]['model_id'].", IDINDUKBARANGSHOPEE = ".$idbarang[$x]['item_id']." WHERE SKUSHOPEE = '".strtoupper($sku)."'";
                            $CI->db->query($sql);
                            echo $sql." ;";
                            echo "\n";
                           $data["total"]++;
                           $data["msg"] = "IDBARANGSHOPEE BERHASIL DIUPDATE";
                        }
                    }
                }
            }
        }
        echo(json_encode($data));
	}
	
	public function setHargaBarang(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$data     = json_decode($this->input->post('data_detail'));
		$allcustomer = $this->input->post('allcustomer')??"false";
		$varian = $this->input->post('varian')??"false";
		$dataBarangHarga = [];
		
	
    	$sql = "SELECT IDCUSTOMER,KONSINYASI FROM MCUSTOMER WHERE KODECUSTOMER = 'XSHOPEE' ";
    	$IDCUSTOMER = $CI->db->query($sql)->row()->IDCUSTOMER;
    	$KONSINYASI = $CI->db->query($sql)->row()->KONSINYASI??0;
		
		
		if($varian == "true")
		{
    		$sql = "SELECT a.IDBARANG,a.KATEGORI,
    		        a.IDBARANGSHOPEE, a.IDINDUKBARANGSHOPEE
    		        FROM MBARANG a";
    		$dataBarang = $CI->db->query($sql)->result();  
		}
		
		//BUKAN SEMUA CUSTOMER, TAPI VARIAN
	    if($allcustomer == "false" && $varian == "true")
        {
    		$idbarang = 0;
    		foreach($data as $item)
    		{
    		    if($IDCUSTOMER == $item->idcustomer)
    		    {
    		        foreach($dataBarang as $itemBarang){
    		            if($itemBarang->IDBARANG == $item->idbarang)
    		            {
    		                if($itemBarang->IDBARANGSHOPEE != 0 && $itemBarang->IDINDUKBARANGSHOPEE != 0)
    		                {
    		                    if($idbarang != $itemBarang->IDINDUKBARANGSHOPEE)
    		                    {
    		                        $idbarang = $itemBarang->IDINDUKBARANGSHOPEE;
    		                        array_push($dataBarangHarga, array(
        		                        'item_id' => $itemBarang->IDINDUKBARANGSHOPEE,
        		                        'model' => []
    		                        ));
    		                    }
    		                    
    		                    if($itemBarang->IDBARANGSHOPEE == $itemBarang->IDINDUKBARANGSHOPEE)
    		                    {
    		                        array_push($dataBarangHarga[count($dataBarangHarga)-1]['model'],
        		                    array(
        		                         'model_id'         => 0,
        		                         'original_price'   => $item->hargajualnew,
        		                         'cross_price'      => $KONSINYASI == 1 ? $item->hargakonsinyasinew:$item->harganew,
        		                    )); 
    		                    }
    		                    else
    		                    {
        		                    array_push($dataBarangHarga[count($dataBarangHarga)-1]['model'],
        		                    array(
        		                         'model_id'         => $itemBarang->IDBARANGSHOPEE,
        		                         'original_price'   => $item->hargajualnew,
        		                         'cross_price'      => $KONSINYASI == 1 ? $item->hargakonsinyasinew:$item->harganew,
        		                    ));
    		                    }
    		                }
    		            }
    		        }
    		    }
    		}
        }
        else if($allcustomer == "true" && $varian == "true")  //SEMUA CUSTOMER, TAPI VARIAN
        {
    		$idbarang = 0;
    		foreach($data as $item)
    		{
    		   foreach($dataBarang as $itemBarang){
    		       if($itemBarang->IDBARANG == $item->idbarang)
    		       {
    		           if($itemBarang->IDBARANGSHOPEE != 0 && $itemBarang->IDINDUKBARANGSHOPEE != 0)
    		           {
    		               if($idbarang != $itemBarang->IDINDUKBARANGSHOPEE)
    		               {
    		                   $idbarang = $itemBarang->IDINDUKBARANGSHOPEE;
    		                   array_push($dataBarangHarga, array(
        	                    'item_id' => $itemBarang->IDINDUKBARANGSHOPEE,
        	                    'model' => []
    		                   ));
    		               }
    		               
    		               if($itemBarang->IDBARANGSHOPEE == $itemBarang->IDINDUKBARANGSHOPEE)
    		               {
    		                array_push($dataBarangHarga[count($dataBarangHarga)-1]['model'],
        	                array(
        	                     'model_id'         => 0,
        	                     'original_price'   => $item->hargajualnew,
        		                 'cross_price'      => $KONSINYASI == 1 ? $item->hargakonsinyasinew:$item->harganew,
        	                )); 
    		               }
    		               else
    		               {
        	                array_push($dataBarangHarga[count($dataBarangHarga)-1]['model'],
        	                array(
        	                     'model_id'         => $itemBarang->IDBARANGSHOPEE,
        	                     'original_price'   => $item->hargajualnew,
        		                 'cross_price'      => $KONSINYASI == 1 ? $item->hargakonsinyasinew:$item->harganew,
        	                ));
    		               }
    		           }
    		       }
    		   }
    		}
        }
        else if($allcustomer == "true" && $varian == "false")  //SEMUA CUSTOMER, TAPi BUKAN VARIAN
        {
            $idbarang = 0;
    		foreach($data as $item)
    		{	
        	   $sql = "SELECT a.IDBARANG, a.IDBARANGSHOPEE, a.IDINDUKBARANGSHOPEE FROM MBARANG a WHERE a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and kategori in (select x.kategori from mbarang x where x.idbarang = {$item->idbarang})";
        	   
        	   $allBarang = $CI->db->query($sql)->result();
        	   
        	   foreach($allBarang as $itemBarang)
        	   {
                   if($itemBarang->IDBARANGSHOPEE != 0 && $itemBarang->IDINDUKBARANGSHOPEE != 0)
        	       {
        	           if($idbarang != $itemBarang->IDINDUKBARANGSHOPEE)
        	           {
        	               $idbarang = $itemBarang->IDINDUKBARANGSHOPEE;
        	               array_push($dataBarangHarga, array(
                            'item_id' => $itemBarang->IDINDUKBARANGSHOPEE,
                            'model' => []
        	               ));
        	           }
        	           
        	           if($itemBarang->IDBARANGSHOPEE == $itemBarang->IDINDUKBARANGSHOPEE)
        	           {
        	            array_push($dataBarangHarga[count($dataBarangHarga)-1]['model'],
                        array(
                             'model_id'         => 0,
                             'original_price'   => $item->hargajualnew,
        		             'cross_price'      => $KONSINYASI == 1 ? $item->hargakonsinyasinew:$item->harganew,
                        )); 
        	           }
        	           else
        	           {
                        array_push($dataBarangHarga[count($dataBarangHarga)-1]['model'],
                        array(
                             'model_id'         => $itemBarang->IDBARANGSHOPEE,
                             'original_price'   => $item->hargajualnew,
        		             'cross_price'      => $KONSINYASI == 1 ? $item->hargakonsinyasinew:$item->harganew,
                        ));
        	           }
        	       }
        	   }
    		}
        }
        else if($allcustomer == "false" && $varian == "false")  //BUKAN SEMUA CUSTOMER, TAPi BUKAN VARIAN
        {
    		foreach($data as $item)
    		{	
		    
		        if($IDCUSTOMER == $item->idcustomer)
    		    {
        		    $sql = "SELECT a.IDBARANG, a.IDBARANGSHOPEE, a.IDINDUKBARANGSHOPEE FROM MBARANG a WHERE a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and kategori in (select x.kategori from mbarang x where x.idbarang = {$item->idbarang})";
        		    
        		    $allBarang = $CI->db->query($sql)->result();
        		    
        		    foreach($allBarang as $itemBarang)
        		    {
            		    if($itemBarang->IDBARANGSHOPEE != 0 && $itemBarang->IDINDUKBARANGSHOPEE != 0)
        		        {
        		            if($idbarang != $itemBarang->IDINDUKBARANGSHOPEE)
        		            {
        		                $idbarang = $itemBarang->IDINDUKBARANGSHOPEE;
        		                array_push($dataBarangHarga, array(
            	                 'item_id' => $itemBarang->IDINDUKBARANGSHOPEE,
            	                 'model' => []
        		                ));
        		            }
        		            
        		            if($itemBarang->IDBARANGSHOPEE == $itemBarang->IDINDUKBARANGSHOPEE)
        		            {
        		             array_push($dataBarangHarga[count($dataBarangHarga)-1]['model'],
            	             array(
            	                  'model_id'         => 0,
            	                  'original_price'   => $item->hargajualnew,
        		                  'cross_price'      => $KONSINYASI == 1 ? $item->hargakonsinyasinew:$item->harganew,
            	             )); 
        		            }
        		            else
        		            {
            	             array_push($dataBarangHarga[count($dataBarangHarga)-1]['model'],
            	             array(
            	                  'model_id'         => $itemBarang->IDBARANGSHOPEE,
            	                  'original_price'   => $item->hargajualnew,
        		                  'cross_price'      => $KONSINYASI == 1 ? $item->hargakonsinyasinew:$item->harganew,
            	             ));
        		            }
        		        }
        		    }
    		    }
    		}
    		
        }
        
		for($x = 0 ; $x < count($dataBarangHarga); $x++)
		{
        	$parameter = [];
            $parameter['item_id'] = (int)$dataBarangHarga[$x]['item_id'];
            $parameter['price_list'] = [];
            for($h = 0 ; $h < count($dataBarangHarga[$x]['model']); $h++)
            {
                 array_push($parameter['price_list'],array(
                     'model_id'       =>  (int)$dataBarangHarga[$x]['model'][$h]['model_id'],
                     'original_price' =>  (float)$dataBarangHarga[$x]['model'][$h]['original_price'],
                  ));
            }
            
            $curl = curl_init();
                  
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  array(
              'endpoint' => 'product/update_price',
              'parameter' => json_encode($parameter)),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
              
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            
            if($ret['error'] != "")
            {
                $data['success'] = false;
                $data['msg'] =  $ret['error']." UBAH HARGA SHOPEE : ".$ret['message'];
                die(json_encode($data));
            }
		}
		
		
        $data['success'] = true;
        $data['msg'] =  'Update Harga Shopee Berhasil';
        echo(json_encode($data));
	}
	
	public function setStokBarang(){
	    $this->output->set_content_type('application/json');
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
		$idtrans = $this->input->post("idtrans")??0;
		$jenistrans = $this->input->post("jenistrans")??'';
		$idlokasiset = $this->input->post("idlokasi");
		$dataBarang = json_decode($this->input->post("databarang"),true)??[];
		
		if($jenistrans != "")
		{
		    $dataBarang = [];
		    if($jenistrans == "SALDOSTOK")
		    {
		        $sql = "SELECT a.IDLOKASI,b.IDBARANG FROM ".$jenistrans."dtl b 
		            inner join ".$jenistrans." a on b.ID".$jenistrans." = a.ID".$jenistrans."
		            WHERE a.ID".$jenistrans." = $idtrans";
		    }
		    else if($jenistrans == "TRANSFER_ASAL")
		    {
		        $sql = "SELECT a.IDLOKASIASAL as IDLOKASI,b.IDBARANG FROM ttransferdtl b 
		            inner join ttransfer a on b.IDtransfer = a.IDtransfer
		            WHERE a.IDtransfer = $idtrans";
		    }
		    else if($jenistrans == "TRANSFER_TUJUAN")
		    {
		        $sql = "SELECT a.IDLOKASITUJUAN as IDLOKASI,b.IDBARANG FROM ttransferdtl b 
		            inner join ttransfer a on b.IDtransfer = a.IDtransfer
		            WHERE a.IDtransfer = $idtrans";
		    }
		    else
		    {
		        $sql = "SELECT a.IDLOKASI,b.IDBARANG FROM T".$jenistrans."dtl b 
		            inner join T".$jenistrans." a on b.ID".$jenistrans." = a.ID".$jenistrans."
		            WHERE a.ID".$jenistrans." = $idtrans";
		    }
		    $dataRow = $CI->db->query($sql)->result();
		    
		    foreach($dataRow as $itemRow)
		    {
		        array_push($dataBarang,$itemRow->IDBARANG);
		    }
		    
		    $idlokasiset = $itemRow->IDLOKASI;
		}
		
        $curl = curl_init();
        $parameter = "";
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'logistics/get_address_list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        $lokasi = 0;
        $countSuccess = 0 ;
        if($ret['error'] != "")
        {
            echo $ret['error']." LOKASI : ".$ret['message'];
        }
        else
        {
            $dataAddress = $ret['response']['address_list'];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASISHOPEE = ".$dataAddress[$x]['address_id']." AND GROUPLOKASI like '%MARKETPLACE%'";
                $pickup = false;
                for($y = 0 ; $y < count($dataAddress[$x]['address_type']);$y++)
                {
                    if($dataAddress[$x]['address_type'][$y] == "PICKUP_ADDRESS")
                    {
                        $pickup = true;
                    }
                    // else if($dataAddress[$x]['address_type'][$y] == "DEFAULT_ADDRESS")
                    // {
                    //     $default = true;
                    // }
                }
                
                if($pickup)
                {
                    $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
                }
            }
            
            if($lokasi == $idlokasiset)
            {
                $modeList = [];
                $countBarang = 0;
                $whereBarang = " and IDBARANG in (";
                foreach($dataBarang as $itemBarang)
                {
            		$whereBarang .= $itemBarang;
            		if($countBarang < count($dataBarang)-1)
            		{
            		    $whereBarang .= ",";
            		}
            		$countBarang++;
                }
                
                $whereBarang .= ")";	
                
                $sql = "select IDBARANGSHOPEE, IDINDUKBARANGSHOPEE, IDBARANG
            				from MBARANG
            				where (1=1) $whereBarang
            				order by IDINDUKBARANGSHOPEE
            				";	
            		
            	$dataHeader = $this->db->query($sql)->result();
            		
                 $idHeader = 0;
                 $parameter = [];
            	 foreach($dataHeader as $itemHeader)
            	 {
            	     if($itemHeader->IDINDUKBARANGSHOPEE != $idHeader)
            	     {
            	         if(count($parameter) > 0)
            	         {
            	        
            	            $curl = curl_init();
                            curl_setopt_array($curl, array(
                              CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => '',
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 30,
                              CURLOPT_FOLLOWLOCATION => true,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => 'POST',
                              CURLOPT_POSTFIELDS =>  array(
                              'endpoint' => 'product/update_stock',
                              'parameter' => json_encode($parameter)),
                              CURLOPT_HTTPHEADER => array(
                                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                              ),
                            ));
                              
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $ret =  json_decode($response,true);
                            
                            if($ret['error'] != "")
                            {
                                $data['success'] = false;
                                $data['msg'] =  $ret['error']." STOK : ".$ret['message'];
                                die(json_encode($data));
                                print_r($ret);
                            }
            	         }
            	         $idHeader = $itemHeader->IDINDUKBARANGSHOPEE;
            	         
            	         //UPDATE KE SHOPEENYA
                        $parameter = [];
                     	$parameter['item_id'] = (int)$itemHeader->IDINDUKBARANGSHOPEE;
                     	$parameter['stock_list'] = [];
            	     }
            	     
                     $result   = get_saldo_stok_new($_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],$itemHeader->IDBARANG, $idlokasiset, date('Y-m-d'));
                     $saldoQty = $result->QTY??0;
                    
                    $modelId = 0;
                    
                    if($itemHeader->IDBARANGSHOPEE != $itemHeader->IDINDUKBARANGSHOPEE)
                    {
                        $modelId = $itemHeader->IDBARANGSHOPEE;
                    }
                    
                     array_push($parameter['stock_list'],array(
                        'model_id'      => (int)$modelId,
                        'seller_stock'  => array(
                             array('stock' => (int)$saldoQty)
                        ))
                    );
            	}
            	
            	$curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  array(
                  'endpoint' => 'product/update_stock',
                  'parameter' => json_encode($parameter)),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                
                if($ret['error'] != "")
                {
                    $data['success'] = false;
                    $data['msg'] =  $ret['error']." STOK : ".$ret['message'];
                    die(json_encode($data));
                }
            }
        }
        
        if($lokasi == $idlokasiset)
        {
            $data['success'] = true;
            $data['msg'] = "Stok Shopee Berhasil Diupdate";
            echo(json_encode($data));
        }
        else
        {
           $data['success'] = true; 
           $data['msg'] = "";
           echo(json_encode($data));
        }
	}
	
	public function getKategori(){
	    $this->output->set_content_type('application/json');
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
        $curl = curl_init();
        
        $parameter = "&language=ID";
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_category','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataKategori = $ret['response']['category_list'];
            $responseKategori = [];
            
            foreach($dataKategori as $itemKategori)
            {
                if($itemKategori['parent_category_id'] == 0)
                {
                    
                    if(!$itemKategori['has_children'])
                    {
                        array_push($responseKategori,array(
                            'VALUE' =>  $itemKategori['category_id'],
                            'TEXT' => $itemKategori['display_category_name']
                        ));
                    }
                    else
                    {
                        foreach($dataKategori as $itemSubKategori)
                        {
                        
                           if($itemKategori['category_id'] == $itemSubKategori['parent_category_id'])
                           {
                                 
                               if(!$itemSubKategori['has_children'])
                               {
                                   array_push($responseKategori,array(
                                       'VALUE' =>  $itemSubKategori['category_id'],
                                       'TEXT' => $itemKategori['display_category_name']." / ".$itemSubKategori['display_category_name']
                                   ));
                               }
                               else
                               {
                                   foreach($dataKategori as $itemSubKategori2)
                                   {
                                       if($itemSubKategori['category_id'] == $itemSubKategori2['parent_category_id'])
                                       {
                                           if(!$itemSubKategori2['has_children'])
                                           {
                                               array_push($responseKategori,array(
                                                   'VALUE' =>  $itemSubKategori2['category_id'],
                                                   'TEXT' => $itemKategori['display_category_name']." / ".$itemSubKategori['display_category_name']." / ".$itemSubKategori2['display_category_name']
                                               ));
                                           }
                                           else
                                           {
                                               foreach($dataKategori as $itemSubKategori3)
                                               {
                                                   if($itemSubKategori2['category_id'] == $itemSubKategori3['parent_category_id'])
                                                   {
                                                       if(!$itemSubKategori3['has_children'])
                                                       {
                                                           array_push($responseKategori,array(
                                                               'VALUE' =>  $itemSubKategori3['category_id'],
                                                               'TEXT' => $itemKategori['display_category_name']." / ".$itemSubKategori['display_category_name']." / ".$itemSubKategori2['display_category_name']." / ".$itemSubKategori3['display_category_name']
                                                           ));
                                                           
                                                       }
                                                       else
                                                       {
                                                           foreach($dataKategori as $itemSubKategori4)
                                                           {
                                                               if($itemSubKategori3['category_id'] == $itemSubKategori4['parent_category_id'])
                                                               {
                                                                   if(!$itemSubKategori4['has_children'])
                                                                   {
                                                                       array_push($responseKategori,array(
                                                                           'VALUE' =>  $itemSubKategori4['category_id'],
                                                                           'TEXT' => $itemKategori['display_category_name']." / ".$itemSubKategori['display_category_name']." / ".$itemSubKategori2['display_category_name']." / ".$itemSubKategori3['display_category_name']." / ".$itemSubKategori4['display_category_name']
                                                                       ));
                                                                   }
                                                               }
                                                           }
                                                       }
                                                   }
                                               }
                                           }
                                           
                                       }
                                   }
                                }
                            }
                        }
                    }
                }
            }
            
            // Sort $responseKategori by the 'TEXT' key in ascending order
            usort($responseKategori, function($a, $b) {
                return strcmp($a['TEXT'], $b['TEXT']);
            });
            
            echo(json_encode($responseKategori));
        }
	}
	
	public function getPengiriman(){
	    $this->output->set_content_type('application/json');
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
        $curl = curl_init();
        
        $parameter = "&language=ID";
        $data['rows'] = [];
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'logistics/get_channel_list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $respLogistics = $ret['response']['logistics_channel_list'];
            foreach($respLogistics as $itemLogistics)
            {
                if($itemLogistics['enabled'] && $itemLogistics['mask_channel_id'] == 0)
                {
                    $pengiriman = [];
                    foreach($respLogistics as $itemDetailLogistics)
                    {
                        if($itemDetailLogistics['enabled'] && $itemDetailLogistics['mask_channel_id'] == $itemLogistics['logistics_channel_id'])
                        {
                            array_push($pengiriman,array(
                                'IDPENGIRIMAN' =>  $itemDetailLogistics['logistics_channel_id'],
                                'NAMAPENGIRIMAN' => $itemDetailLogistics['logistics_channel_name'],
                                'KETERANGANPENGIRIMAN' => $itemDetailLogistics['logistics_description']
                            ));
                        }
                    }
                    
                    
                    array_push($data['rows'], array(
                        "CHOOSEPENGIRIMAN" => 1,
                        'IDPENGIRIMAN' =>  $itemLogistics['logistics_channel_id'],
                        'NAMAPENGIRIMAN' => $itemLogistics['logistics_channel_name'],
                        'KETERANGANPENGIRIMAN' => $itemLogistics['logistics_description'],
                        'JENISPENGIRIMAN' => $pengiriman
                    ));
                }
            }
            
            // Sort $responseKategori by the 'TEXT' key in ascending order
            usort($data['rows'], function($a, $b) {
                return strcmp($a['NAMAPENGIRIMAN'], $b['NAMAPENGIRIMAN']);
            });
            
            $data["total"] = count($data['rows']);
            echo json_encode($data);
        }
	}
	
	public function getAttribut($idkategori = ""){
	    $this->output->set_content_type('application/json');
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
		$data['rows'] = [];
		if($idkategori != "")
		{
            $curl = curl_init();
            
            $parameter = "&category_id_list=$idkategori&language=ID";
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_attribute_tree','parameter' => $parameter),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            if($ret['error'] != "")
            {
                echo $ret['error']." : ".$ret['message'];
            }
            else
            {
                $responseAttribut = $ret['response']['list'][0]['attribute_tree'];
                foreach($responseAttribut as $itemAttribut)
                {
                    $dataDetail = [];
                    
                    foreach($itemAttribut['attribute_value_list'] as $itemDetail)
                    {
                        array_push($dataDetail,array(
                            'IDATTRIBUT' => $itemDetail['value_id'],
                            'NAMAATTRIBUT' => $itemDetail['multi_lang'][0]['value'],
                        ));
                    }
                    
                    //PILIH DATA
                    $component = "";
                    if($itemAttribut['attribute_info']['input_type'] == 1)
                    {
                        $component = "SINGLEDROPDOWN";
                    }
                    else if($itemAttribut['attribute_info']['input_type'] == 2)
                    {
                        $component = "SINGLECOMBOBOX";
                    }
                    else if($itemAttribut['attribute_info']['input_type'] == 3)
                    {
                        $component = "FREETEXTFILED";
                    }
                    else if($itemAttribut['attribute_info']['input_type'] == 4)
                    {
                        $component = "MULTIDROPDOWN";
                    }
                    else if($itemAttribut['attribute_info']['input_type'] == 5)
                    {
                        $component = "MULTICOMBOBOX";
                    }
                    
                    array_push($data["rows"],array(
                        'IDATTRIBUT'     => $itemAttribut['attribute_id'],
                        'NAMAATTRIBUT'   => $itemAttribut['multi_lang'][0]['value'],
                        'REQUIRED'       => $itemAttribut['mandatory'],
                        'SELECTED'       => '0',
                        'VALUEATTRIBUT'  => '',
                        'JENISATTRIBUT'  => $dataDetail,
                        "SYARATATTRIBUT" => array(
                            'COMPONENT'   => $component,
                            'FORMATVALUE' => $itemAttribut['attribute_info']['input_validation_type'],
                            'MAXVALUE'    => $itemAttribut['attribute_info']['max_value_count']??1,
                            'SELECTED'    => 0,
                        )
                    ));
                }
             
                
                // // Sort $responseKategori by the 'TEXT' key in ascending order
                // usort($responseKategori, function($a, $b) {
                //     return strcmp($a['TEXT'], $b['TEXT']);
                // });
            }
		}
        
        $data["total"] = count($data['rows']);
        echo(json_encode($data));
	}
	
	public function getSizeChart(){
	    $this->output->set_content_type('application/json');
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$kategori = $this->input->post('kategori');
		
        
        $data['rows'] = [];
        $parameter = "&category_id=".$kategori;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_item_limit','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $response = $ret['response'];
            $data['available_image_size_chart'] = $response['size_chart_limit']['support_image_size_chart'];
            $data['available_template_size_chart'] = $response['size_chart_limit']['support_template_size_chart'];
            if($response['size_chart_limit']['size_chart_mandatory'])
            {
                $parameter = "&category_id=".$kategori."&page_size=50";
                
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_size_chart_list','parameter' => $parameter),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                if($ret['error'] != "")
                {
                    echo $ret['error']." : ".$ret['message'];
                }
                else
                {
                    $dataSizeChart = $ret['response']['size_chart_list'];
                    
                    for($x = 0 ; $x < count($dataSizeChart); $x++)
                    {
                        $curl = curl_init();
                        $parameter = "&size_chart_id=".$dataSizeChart[$x]['size_chart_id'];
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_size_chart_detail','parameter' => $parameter),
                          CURLOPT_HTTPHEADER => array(
                            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                          ),
                        ));
                        
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $ret =  json_decode($response,true);
                        if($ret['error'] != "")
                        {
                            echo $ret['error']." : ".$ret['message'];
                        }
                        else
                        {
                            array_push($data['rows'], array(
                                'SIZE_ID' => $ret['response']['size_chart_id'],
                                'SIZE_NAME' => $ret['response']['size_chart_name']
                            ));
                        }
                    }
                }
            }
        }
        
        echo json_encode($data);
	}
	
	function dataGridBarang(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$status = explode(",",$this->input->post('status'));
		
		$statusok = true;
		$statusParam = "";
		$data['rows'] = [];
		$data["total"] = 0;
		$offset = 0;
		$pageSize = 100;
		for($x = 0 ;$x < count($status); $x++)
		{
		    $statusParam .= "&item_status=".$status[$x];
		}
		
		//LOGISTIC
		$curl = curl_init();
		
		while(!$bigger && $statusok)
        {
            
		    $parameter = "&offset=".$offset."&page_size=".$pageSize.$statusParam;
		    
		  //  echo $parameter;
		    
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_item_list','parameter' => $parameter),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            if($ret['error'] != "")
            {
                echo $ret['error']." : ".$ret['message'];
            }
            else
            {
                $response = $ret['response'];
                $statusok = $response['has_next_page'];
                $offset = $response['next_offset'];
                $idbarang = $response['item'];
                $paramId = "";
                for($x = 0 ; $x < count($idbarang);$x++)
                {
                    $paramId.= (int)$idbarang[$x]['item_id'];
                    
                    if(($x % 49 == 0 && $x != 0) || $x == count($idbarang)-1)
                    {
                        //GET ORDER DETAIL
                        $parameter = "&item_id_list=".$paramId;
                        $curl = curl_init();
                    
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_item_base_info','parameter' => $parameter),
                          CURLOPT_HTTPHEADER => array(
                            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                          ),
                        ));
                        
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $ret =  json_decode($response,true);
                        if($ret['error'] != "")
                        {
                            echo $ret['error']." : ".$ret['message'];
                            $statusok = false;
                        }
                        else
                        {
                            $dataBarang = $ret['response']['item_list'];
                            
                            $sqlBarangMaster = "select IDBARANG, KATEGORI, IDINDUKBARANGSHOPEE from MBARANG where IDINDUKBARANGSHOPEE != 0 and IDINDUKBARANGSHOPEE != '' and IDINDUKBARANGSHOPEE is not null";
                            $dataBarangMaster = $CI->db->query($sqlBarangMaster)->result();
                            
                            foreach($dataBarang as $itemBarang)
                            {
                                // $dataModel = [];
                                // if($itemBarang['has_model'] == 1)
                                // {
                                //     //GET MODEL
                                //     $parameter = "&item_id=".$itemBarang['item_id'];
                                //     $curl = curl_init();
                                
                                //     curl_setopt_array($curl, array(
                                //       CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                                //       CURLOPT_RETURNTRANSFER => true,
                                //       CURLOPT_ENCODING => '',
                                //       CURLOPT_MAXREDIRS => 10,
                                //       CURLOPT_TIMEOUT => 30,
                                //       CURLOPT_FOLLOWLOCATION => true,
                                //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                //       CURLOPT_CUSTOMREQUEST => 'POST',
                                //       CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_model_list','parameter' => $parameter),
                                //       CURLOPT_HTTPHEADER => array(
                                //         'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                                //       ),
                                //     ));
                                    
                                //     $response = curl_exec($curl);
                                //     curl_close($curl);
                                //     $ret =  json_decode($response,true);
                                //     if($ret['error'] != "")
                                //     {
                                //         echo $ret['error']." : ".$ret['message'];
                                //         $statusok = false;
                                //     }
                                //     else
                                //     {
                                //         $dataModel = $ret['response']['tier_variation'];
                                        
                                //     }
                                // }
                              $itemBarang['MASTERCONNECTED'] = "TIDAK";
                              $itemBarang['IDMASTERBARANG'] = 0;
                              $itemBarang['KATEGORIMASTERBARANG'] = '';
                              foreach($dataBarangMaster as $itemBarangMaster)
                              {
                                  if($itemBarangMaster->IDINDUKBARANGSHOPEE == $itemBarang['item_id'])
                                  {
                                     $itemBarang['MASTERCONNECTED'] ="YA";  
                                     $itemBarang['IDMASTERBARANG'] = $itemBarangMaster->IDBARANG;
                                     $itemBarang['KATEGORIMASTERBARANG'] = $itemBarangMaster->KATEGORI;
                                  }
                              }
                              
                              $itemBarang['NAMABARANG'] = $itemBarang['item_name'];    
                              $itemBarang['VARIAN'] = $itemBarang['has_model'] == 1 ? "YA" : "TIDAK";     
                              $itemBarang['TGLENTRY'] = date("Y-m-d H:i:s", $itemBarang['update_time']??$itemBarang['create_time']);    
                              $itemBarang['STATUS'] = $itemBarang['item_status'];    
                              
                              array_push($data['rows'],$itemBarang);
                          }
                      }
                        
                        $paramId = "";
                    }
                    else
                    {
                        $paramId .= ",";
                    }
                }
            }
        }
        
        //URUTKAN BERDASARKAN NAMA BARANG
        usort($data['rows'], function($a, $b) {
            return strcmp($a['NAMABARANG'], $b['NAMABARANG']);
        });
                        
        $data["total"] = count($data['rows']);
        echo json_encode($data);
	}
	
	function getDataBarang(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$itemid = $this->input->post('idindukbarangshopee');
	    $parameter = '';
        //GET MODEL
        $parameter = "&item_id=".(int)$itemid;
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_model_list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
            $statusok = false;
        }
        else
        {
            $dataUrutan = $ret['response']['tier_variation'][0]['option_list']; //URUTKAN DARI WARNA
            $dataModel = $ret['response']['model'];
            $dataGambarModel = $ret['response']['standardise_tier_variation'][0]['variation_option_list'];
            $data['dataVarian'] = [];
            $data['dataGambarVarian'] = [];
            for($u = 0 ; $u < count($dataUrutan); $u++)
            {
                for($m = 0 ; $m < count($dataModel);$m++)
                {
                    $model = explode(",",$dataModel[$m]['model_name']);
                    if($dataUrutan[$u]['option'] == $model[0])
                    {
                        array_push($data['dataVarian'], array(
                            'ID'    => $dataModel[$m]['model_id'],
                            'NAMA'  => strtoupper($dataModel[$m]['model_name']),
                            'WARNA'  => strtoupper($model[0]),
                            'SIZE'  => strtoupper($model[1]),
                            'SKU'   => $dataModel[$m]['model_sku'],
                            "HARGA" => $dataModel[$m]['price_info'][0]['original_price']
                        ));
                    }
                }
            }
            
            for($g = 0 ; $g < count($dataGambarModel); $g++)
            {
                array_push($data['dataGambarVarian'], array(
                    'WARNA'     => strtoupper($dataGambarModel[$g]['variation_option_name']),
                    'IMAGEID'   => $dataGambarModel[$g]['image_id'],
                    "IMAGEURL"  => $dataGambarModel[$g]['image_url'],
                ));
            }
        
            echo(json_encode($data));
        }
	}
	
	function setBarang(){
	   
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
		$idBarang           = $this->input->post("IDBARANG",0);
		$dataVarian         = json_decode($this->input->post("VARIAN"));
		$dataWarna          = json_decode($this->input->post("WARNA"));
		$dataUkuran         = json_decode($this->input->post("UKURAN"));
		$dataAttribut       = json_decode($this->input->post("ATTRIBUT"));
		$dataGambarProduk   = json_decode($this->input->post("GAMBARPRODUK"));
		$dataGambarVarian   = json_decode($this->input->post("GAMBARVARIAN"));
		$dataLogistik       = json_decode($this->input->post("LOGISTICS"));  
	    $sizeChart          = $this->input->post("SIZECHART");
		$sizeChartID        = $this->input->post("SIZECHARTID");
		$sizeChartTipe      = $this->input->post("SIZECHARTTIPE");
		$kategoriBarang     = $this->input->post("KATEGORI");
		$hargaInduk         = $this->input->post("HARGA");
		$skuInduk           = $this->input->post("SKU");
		
		//FIND PICKUP LOCATION
		$curl = curl_init();
        $parameter = "";
        $idlokasiset = "";
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'logistics/get_address_list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        $lokasi = 0;
        $countSuccess = 0 ;
        if($ret['error'] != "")
        {
            echo $ret['error']." LOKASI : ".$ret['message'];
        }
        else
        {
            $dataAddress = $ret['response']['address_list'];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASISHOPEE = ".$dataAddress[$x]['address_id']." AND GROUPLOKASI like '%MARKETPLACE%'";
                $pickup = false;
                for($y = 0 ; $y < count($dataAddress[$x]['address_type']);$y++)
                {
                    if($dataAddress[$x]['address_type'][$y] == "PICKUP_ADDRESS")
                    {
                        $pickup = true;
                    }
                    // else if($dataAddress[$x]['address_type'][$y] == "DEFAULT_ADDRESS")
                    // {
                    //     $default = true;
                    // }
                }
                
                if($pickup)
                {
                    $idlokasiset = $CI->db->query($sql)->row()->IDLOKASI;
                }
            }
        }
		
		
		$parameter = [];
		$parameter['original_price']    = count($dataVarian) > 0 ?(int)$dataVarian[0]->HARGAJUAL:(int)$hargaInduk;
		$parameter['description']       = $this->input->post("DESKRIPSI");
		$parameter['weight']            = (float)$this->input->post("BERAT");
		$parameter['item_name']         = $this->input->post("NAMA");
		$parameter['item_status']       = ($this->input->post("UNLISTED") == 1 ? "UNLIST" : "NORMAL");
		$parameter['dimension'] = array(
		    'package_height' => (float)$this->input->post("TINGGI"),
		    'package_length' => (float)$this->input->post("PANJANG"),
		    'package_width'  => (float)$this->input->post("LEBAR")
		);
		
		$parameter['logistic_info'] = [];
		foreach($dataLogistik as $itemLogistik)
		{
		    $logistikArray = (array)$itemLogistik; // convert object to array
            $logistikArray['enabled'] = filter_var($itemLogistik->enabled, FILTER_VALIDATE_BOOLEAN);
            $logistikArray['logistic_id'] = (int)$itemLogistik->logistic_id;
		    array_push($parameter['logistic_info'],$logistikArray);
		}
		
		$parameter['category_id'] = (int)$kategoriBarang;
		
		$parameter['image'] = array(
		    'image_id_list' => $dataGambarProduk
		);
		$parameter['item_sku'] =  count($dataVarian) > 0 ?$dataVarian[0]->SKUSHOPEE:$skuInduk;
		$parameter['condition'] = "NEW";
		
		$parameter['brand'] = array(
		    'brand_id' => 3873176,
		    'original_brand_name' => "Little Twisty",
		);
		
		$parameter['seller_stock'] = [];
		if($sizeChartTipe == "COMBOBOX")
		{
		    $parameter['size_chart_info'] = array(
    		    "size_chart"  => '',
    		    "size_chart_id" => (int)$sizeChartID
    		);
		}
		else if($sizeChartTipe == "GAMBAR")
		{
		    $parameter['size_chart_info'] = array(
    		    "size_chart"  => $sizeChartID,
    		    "size_chart_id" => 0
    		);
		}
		
		$parameter['attribute_list'] = $dataAttribut;
// 		print_r(json_encode($parameter,JSON_PRETTY_PRINT));
		
		$endPoint = "product/add_item";
		if($idBarang != 0)
		{
		    $parameter['item_id'] = (int)$idBarang;
		    $endPoint = "product/update_item";
		}
		$itemid = 0;
		//TAMBAH BARANG
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => $endPoint,
          'parameter' => json_encode($parameter,JSON_PRETTY_PRINT)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
     
        if($ret['error'] != "")
        { 
            $data['success'] = false;
            $data['msg'] =  $ret['error']." ITEM : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            
            $itemid = $ret['response']['item_id']??0;
            if(count($dataVarian) > 0)
            {
              	//TAMBAH VARIAN
                $parameter = [];
                $parameter['item_id'] = $itemid;
                
                $optionWarna = [];
                for($x = 0; $x < count($dataWarna) ; $x++)
                {
                    array_push($optionWarna,array(
                        'option' => $dataWarna[$x] ,
                        'image' => array(
                            'image_id' =>  $dataGambarVarian[$x],   
                        )
                    ));
                }
                
                $optionUkuran = [];
                for($x = 0; $x < count($dataUkuran) ; $x++)
                {
                    array_push($optionUkuran,array(
                        'option' => $dataUkuran[$x] ,
                    ));
                }
                
                $parameter['tier_variation'] = array(
                  array(
                    'name' => 'Warna',
                    'option_list' => $optionWarna
                  ),
                  array(
                    'name' => 'Ukuran',
                    'option_list' => $optionUkuran
                  )
                );
                
                $dataModel = [];
                $dataModelBaru = [];
                $dataModelUbahHarga = [];
                $dataModelUbahSKU = [];
                $dataModelHapus = [];
               
                for($w = 0 ; $w < count($optionWarna); $w++)
                {
                    for($u = 0 ; $u < count($optionUkuran); $u++)
                    {
                        for($x = 0 ; $x < count($dataVarian); $x++)
                        {
                            if($dataVarian[$x]->WARNA == $optionWarna[$w]['option'] && $dataVarian[$x]->SIZE == $optionUkuran[$u]['option'])
                            {
                                $sql = "SELECT IDPERUSAHAAN, IDBARANG FROM MBARANG WHERE IDBARANGSHOPEE = ".$dataVarian[$x]->IDBARANG. " or IDBARANG = ".$dataVarian[$x]->IDBARANG ;

                                $itemHeader = $CI->db->query($sql)->row();
                                $result   = get_saldo_stok_new($itemHeader->IDPERUSAHAAN,$itemHeader->IDBARANG, $idlokasiset, date('Y-m-d'));
                                $saldoQty = $result->QTY??0;
                                             
                                array_push($dataModel,array(
                                    'model_id'          => $dataVarian[$x]->IDBARANG,
                                    'tier_index'        => array((int)$w,(int)$u),
                                    'original_price'    => (float)$dataVarian[$x]->HARGAJUAL,
                                    'model_sku'         => $dataVarian[$x]->SKUSHOPEE,
                                    'model_status'      => $dataVarian[$x]->STATUS == 1 ? 'NORMAL' : 'UNAVAILABLE',
                                    'seller_stock'      => array(array('stock' => $saldoQty )),
                                    'weight'            => (float)$this->input->post("BERAT"),
                                    'dimension'         => array(
                                        'package_height' => (int)$this->input->post("TINGGI"),
                                        'package_width'  => (int)$this->input->post("LEBAR"),
                                        'package_length' => (int)$this->input->post("PANJANG"),
                                    ),
                                ));
                                if($dataVarian[$x]->MODE == "BARU")
                                {
                                    array_push($dataModelBaru,$dataModel[count($dataModel)-1]);
                                }
                                else if($dataVarian[$x]->MODE == "UBAH HARGA")
                                {
                                    array_push($dataModelUbahHarga,$dataModel[count($dataModel)-1]);
                                }
                                else if($dataVarian[$x]->MODE == "UBAH SKU")
                                {
                                     array_push($dataModelUbahSKU,$dataModel[count($dataModel)-1]);
                                }
                                else if($dataVarian[$x]->MODE == "HAPUS")
                                {
                                    array_push($dataModelHapus,$dataModel[count($dataModel)-1]);
                                }
                            }
                        }
                    }   
                }
            
                $parameter['model'] = $dataModel;
                
                $curl = curl_init();
                
                $endpointModel = "product/init_tier_variation";
                if($idBarang != 0)
        		{
        		    $endpointModel = "product/update_tier_variation";
        		}
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  array(
                  'endpoint' => $endpointModel,
                  'parameter' => json_encode($parameter)),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
             
                if($ret['error'] != "")
                {
                    $data['success'] = false;
                    $data['msg'] =  $ret['error']." MODEL : ".$ret['message'];
                    die(json_encode($data));
                }
                else
                {
                    
                  //CEK JIKA ADA VARIAN BARU, ADD MODEL
                  if(count($dataModelBaru) > 0)
                  {
                       $parameter = [];
                       $parameter['item_id'] = $itemid;
                       $parameter['model_list'] = $dataModelBaru;
                
                        $curl = curl_init();
                        
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS =>  array(
                          'endpoint' => 'product/add_model',
                          'parameter' => json_encode($parameter)),
                          CURLOPT_HTTPHEADER => array(
                            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                          ),
                        ));
                          
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $ret =  json_decode($response,true);
                     
                        if($ret['error'] != "")
                        {
                            $data['success'] = false;
                            $data['msg'] =  $ret['error']." MODEL BARU : ".$ret['message'];
                            die(json_encode($data));
                        }
                  }
                  
                  //CEK JIKA ADA VARIAN UBAH HARGA, UPDATE MODEL
                  if(count($dataModelUbahHarga) > 0)
                  {
                      $parameter = [];
                      $parameter['item_id'] = $itemid;
                      $parameter['price_list'] = [];
                      for($h = 0 ; $h < count($dataModelUbahHarga); $h++)
                      {
                           array_push($parameter['price_list'],array(
                               'model_id'       =>  (int)$dataModelUbahHarga[$h]['model_id'],
                               'original_price' =>  $dataModelUbahHarga[$h]['original_price'],
                            ));
                      }
                      
                      $curl = curl_init();
                            
                      curl_setopt_array($curl, array(
                        CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>  array(
                        'endpoint' => 'product/update_price',
                        'parameter' => json_encode($parameter)),
                        CURLOPT_HTTPHEADER => array(
                          'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                        ),
                      ));
                        
                      $response = curl_exec($curl);
                      curl_close($curl);
                      $ret =  json_decode($response,true);
                      
                      if($ret['error'] != "")
                      {
                          $data['success'] = false;
                          $data['msg'] =  $ret['error']." MODEL UBAH HARGA : ".$ret['message'];
                          print_r($ret);
                          die(json_encode($data));
                      }
                  }
                  
                  //CEK JIKA ADA VARIAN UBAH SKU, UPDATE MODEL
                  if(count($dataModelUbahSKU) > 0)
                  {
                      $parameter = [];
                      $parameter['item_id'] = $itemid;
                      $parameter['model'] = [];
                      for($h = 0 ; $h < count($dataModelUbahSKU); $h++)
                      {
                           array_push($parameter['model'],$dataModelUbahSKU[$h]);
                      }
                      
                      $curl = curl_init();
                            
                      curl_setopt_array($curl, array(
                        CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>  array(
                        'endpoint' => 'product/update_model',
                        'parameter' => json_encode($parameter)),
                        CURLOPT_HTTPHEADER => array(
                          'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                        ),
                      ));
                        
                      $response = curl_exec($curl);
                      curl_close($curl);
                      $ret =  json_decode($response,true);
                      
                      if($ret['error'] != "")
                      {
                          $data['success'] = false;
                          $data['msg'] =  $ret['error']." MODEL UBAH SKU : ".$ret['message'];
                          die(json_encode($data));
                      }
                  }
                  
                  //CEK JIKA ADA VARIAN DIHAPUS, DELETE MODEL
                  if(count($dataModelHapus) > 0)
                  {
                      for($h = 0 ; $h < count($dataModelHapus); $h++)
                      {
                           $parameter = [];
                           $parameter['item_id'] = $itemid;
                           $parameter['model_id'] = $dataModelHapus[$h]['model_id'];
                    
                            $curl = curl_init();
                            
                            curl_setopt_array($curl, array(
                              CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => '',
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 30,
                              CURLOPT_FOLLOWLOCATION => true,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => 'POST',
                              CURLOPT_POSTFIELDS =>  array(
                              'endpoint' => 'product/delete_model',
                              'parameter' => json_encode($parameter)),
                              CURLOPT_HTTPHEADER => array(
                                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                              ),
                            ));
                              
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $ret =  json_decode($response,true);
                         
                            if($ret['error'] != "")
                            {
                                $data['success'] = false;
                                $data['msg'] =  $ret['error']." MODEL HAPUS : ".$ret['message'];
                                die(json_encode($data));
                            }
                      }
                  }
                  
                  
                  $parameter = '';
                  //GET MODEL
                  $parameter = "&item_id=".(int)$itemid;
                  $curl = curl_init();
                  
                  curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_model_list','parameter' => $parameter),
                    CURLOPT_HTTPHEADER => array(
                      'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                    ),
                  ));
                  
                  $response = curl_exec($curl);
                  curl_close($curl);
                  $ret =  json_decode($response,true);
                  if($ret['error'] != "")
                  {
                      echo $ret['error']." : ".$ret['message'];
                      $statusok = false;
                  }
                  else
                  {
                      $dataModelResponse = $ret['response']['model'];
                      for($m = 0 ; $m < count($dataModelResponse);$m++)
                      {
                          $sku = "";
                          if($dataModelResponse[$m]['model_sku'] == "")
                          {
                               $sku = $dataModelResponse[$m]['item_sku'];
                          }
                          else
                          {
                               $sku = $dataModelResponse[$m]['model_sku'];
                          }
                          
                          $sql = "UPDATE MBARANG SET 
                                    IDBARANGSHOPEE = ".$dataModelResponse[$m]['model_id'].", 
                                    IDINDUKBARANGSHOPEE = ".$itemid." 
                                    WHERE SKUSHOPEE = '".strtoupper($sku)."'";
                          $CI->db->queryRaw($sql);
                      }
                      
                      $data['success'] = true;
                      $data['msg'] = "Barang berhasil tersimpan di Shopee";
                      echo(json_encode($data));
                      
                  }
                }
            }
            else
            {
                //UPDATEPRICE
                $parameter = [];
                $parameter['item_id'] = $itemid;
                $parameter['price_list'] = [];
                array_push($parameter['price_list'],array(
                   'model_id'       =>  0,
                   'original_price' =>  (float)$hargaInduk,
                ));
                $curl = curl_init();
                      
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  array(
                  'endpoint' => 'product/update_price',
                  'parameter' => json_encode($parameter)),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                
                if($ret['error'] != "")
                {
                    $data['success'] = false;
                    $data['msg'] =  $ret['error']." PRODUK UBAH HARGA : ".$ret['message'];
                    die(json_encode($data));
                }
                      
                $sql = "UPDATE MBARANG SET 
                                        IDBARANGSHOPEE = ".$itemid.", 
                                        IDINDUKBARANGSHOPEE = ".$itemid." 
                                        WHERE SKUSHOPEE = '".strtoupper($skuInduk)."'";
                              $CI->db->queryRaw($sql);
                sleep(3);              
                $data['success'] = true;
                          $data['msg'] = "Barang berhasil tersimpan di Shopee";
                          echo(json_encode($data));
            }
        }
	   
	}
	
	function removeBarang(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
		$idBarang = $this->input->post("idindukbarangshopee",0);
		$parameter['item_id'] = (int)$idBarang;
		
	    $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => 'product/delete_item',
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        sleep(3);
        if($ret['error'] != "")
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $sql = "UPDATE MBARANG SET IDBARANGSHOPEE = '' , IDINDUKBARANGSHOPEE = '' WHERE IDINDUKBARANGSHOPEE = '".$idBarang."'";
            $CI->db->query($sql);
            
            $data['success'] = true;
            $data['msg'] = "Barang Berhasil Dihapus dari Shopee";
            echo(json_encode($data));
        }
	}
	
	public function getCustomer(){
	    $this->output->set_content_type('application/json');
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
		$sql = "SELECT ROW_NUMBER() OVER (ORDER BY USERNAME ASC) AS NO, CONCAT(NAME,' (',USERNAME,') ') AS NAMA, TELP,ALAMAT,KOTA,
		        SUM(TOTALBARANG) as TOTALBARANG, SUM(TOTALBAYAR) as TOTALBAYAR, COUNT(IDPENJUALANMARKETPLACE) as TOTALPESANAN
		        FROM TPENJUALANMARKETPLACE
		        WHERE STATUSMARKETPLACE != 'CANCELLED' 
		        GROUP BY USERNAME
		        ORDER BY USERNAME ASC";
		$dataCustomer = $CI->db->query($sql)->result(); 
		
		foreach($dataCustomer as $itemCustomer)
		{
		    $itemCustomer->ALAMAT = "<div style='width:400px; white-space: pre-wrap;       
                                                white-space: -moz-pre-wrap;  
                                                white-space: -pre-wrap;      
                                                white-space: -o-pre-wrap;     
                                                word-wrap: break-word;'>".$itemCustomer->ALAMAT."</div>" ;
		}
		
		$data["rows"]  = $dataCustomer;
		$data["total"] = count($dataCustomer);
		
		echo(json_encode($data));
	}
	
	//MASTER
	public function getStokLokasi(){
	    $this->output->set_content_type('application/json');
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'logistics/get_address_list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataAddress = $ret['response']['address_list'];
            $data['rows'] = [];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI, IFNULL(NAMALOKASI,'') as NAMALOKASI FROM MLOKASI WHERE IDLOKASISHOPEE = ".$dataAddress[$x]['address_id']." AND GROUPLOKASI like '%MARKETPLACE%'";
                
                $label = "<br>&nbsp;&nbsp;<i class='fa fa-edit' style='margin-top:5px; cursor:pointer;' onclick='changeLabelShopee(".$x.")'></i>&nbsp;&nbsp;&nbsp;&nbsp;";
                $default = 0;
                $pickup = 0;
                $return = 0;
                for($y = 0 ; $y < count($dataAddress[$x]['address_type']);$y++)
                {
                    if($dataAddress[$x]['address_type'][$y] == "DEFAULT_ADDRESS")
                    {
                        $default = 1; 
                    }
                    else if($dataAddress[$x]['address_type'][$y] == "PICKUP_ADDRESS")
                    {
                        $pickup = 1;
                    }
                    else if($dataAddress[$x]['address_type'][$y] == "RETURN_ADDRESS")
                    {
                        $return = 1;
                    }
                    
                    if($y == 0)
                    {
                      $label.="<i>"; 
                    }
                    
                    $label .= $dataAddress[$x]['address_type'][$y];
                    if($y != count($dataAddress[$x]['address_type'])-1)
                    {
                      $label.="&nbsp;,&nbsp;&nbsp;&nbsp;"; 
                    }
                }
                
                if($label != "")
                {
                    $label .= "</i>";
                }
                
                array_push($data['rows'],array(
                    'NO' => ($x+1),
                    'IDADDRESSAPI' => $dataAddress[$x]['address_id'],
                    'ADDRESSAPI' => $dataAddress[$x]['address']." ".$label,
                    'ADDRESSAPIRAW' => $dataAddress[$x]['address'],
                    'LABELDEFAULT' => $default,
                    'LABELPICKUP' => $pickup,
                    'LABELRETURN' => $return,
                    'ADDRESS' => $CI->db->query($sql)->row()->IDLOKASI??0,
                    'LABELADDRESS' => $CI->db->query($sql)->row()->NAMALOKASI??''
                ));
            }
            echo(json_encode($data));
        }
	}
	
	public function setStokLokasi(){
	    $this->output->set_content_type('application/json');
        $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
        $id = $this->input->post('id')??"0";
        $idAPI = $this->input->post('idAPI')??"0";
        
        //RESET SEMUA
        $CI->db->where("IDLOKASISHOPEE",$idAPI)
                    ->set("IDLOKASISHOPEE",0)
                    ->updateRaw("MLOKASI");    
        //UPDATE TERBARU     
        $CI->db->where("IDLOKASI",$id)
                    ->set("IDLOKASISHOPEE",$idAPI)
                    ->updateRaw("MLOKASI");
                    
        $data['success'] = true;            
        echo json_encode($data); 
	}
	
	public function setLabelLokasi(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id')??"";
		$default = $this->input->post('default')??"false";
		$pickup = $this->input->post('pickup')??"false";
		$return = $this->input->post('return')??"false";
		
		$arrayLabel = [];
		if($default == 'true')
		{
		    array_push($arrayLabel,"DEFAULT_ADDRESS");
		}
		if($pickup == 'true')
		{
		    array_push($arrayLabel,"PICKUP_ADDRESS");
		}
		if($return == 'true')
		{
		    array_push($arrayLabel,"RETURN_ADDRESS");
		}
		
		$parameter = [];
		$parameter['address_type_config'] = array(
		    'address_id' => (int)$id,
		    'address_type' => $arrayLabel
		);
		//HAPUS PESANAN
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => 'logistics/set_address_config',
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
     
        if($ret['error'] != "")
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $data['success'] = true;
            $data['msg'] = "Label Berhasil Diubah";
            echo(json_encode($data));
        }
	}
	
	public function cekStokLokasi(){
	    $this->output->set_content_type('application/json');
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
	    //CEK LOKASI SUDAH DISET
		$curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'logistics/get_address_list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataAddress = $ret['response']['address_list'];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASISHOPEE = ". $dataAddress[$x]['address_id']." AND GROUPLOKASI like '%MARKETPLACE%'";
                $idlokasiSet = $CI->db->query($sql)->row()->IDLOKASI??0;
                
                if($idlokasiSet == 0)
                {
                    $data['success'] = false;
                    $data['msg'] = "Terdapat Lokasi Marketplace dengan Master Lokasi yang belum tersambung";
                    die(json_encode($data));
                }
            }
        }
        
        $data['success'] = true;
        echo json_encode($data);
	}
	
	function dataGridPromo(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$status = $this->input->post('status');
		
		$statusok = true;
		$statusParam = "";
		$data['rows'] = [];
		$data["total"] = 0;
		$pageno = 1;
		$pageSize = 100;
		
		//LOGISTIC
		$curl = curl_init();
		
		while(!$bigger && $statusok)
        {
            
		    $parameter = "&discount_status=".$status."&page_no=".$pageno."&page_size=".$pageSize.$statusParam;
		    
		  //  echo $parameter;
		    
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => 'discount/get_discount_list','parameter' => $parameter),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            if($ret['error'] != "")
            {
                echo $ret['error']." : ".$ret['message'];
            }
            else
            {
                $response = $ret['response'];
                $statusok = $response['more'];
                if($statusok){
                    $pageno++;
                }
                
                for($p = 0 ; $p < count($response['discount_list']);$p++)
                {
                    $dataPromo = $response['discount_list'][$p];
                    array_push($data['rows'],array(
                        'NAMAPROMOSI'   => $dataPromo['discount_name'],
                        'TGLMULAI'      => date("Y-m-d H:i:s", $dataPromo['start_time']),
                        'TGLAKHIR'      => date("Y-m-d H:i:s", $dataPromo['end_time']),
                        'STATUS'        => $dataPromo['status'],
                        'IDPROMOSI'     => $dataPromo['discount_id'],
                    ));
                }
            }
        }
        
        //URUTKAN BERDASARKAN NAMA BARANG
        // usort($data['rows'], function($a, $b) {
        //     return strcmp($a['NAMABARANG'], $b['NAMABARANG']);
        // });
                        
        $data["total"] = count($data['rows']);
        echo json_encode($data);
	}
	
	function getItemPromo(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$dataBarang = json_decode($this->input->post('databarang'),true);
		$tglAw = strtotime($this->input->post('tglmulai'));
		$tglAk = strtotime($this->input->post('tglakhir'));
         
        $sql = "SELECT IDBARANG,IDBARANGSHOPEE FROM MBARANG";
        $dataBarangMaster = $CI->db->query($sql)->result();
        $data['rows'] = [];
        $item_id_list = "";
        for($x = 0 ; $x < count($dataBarang);$x++)
        {
            $item_id_list .= $dataBarang[$x];
            if(($x % 49 == 0 && $x != 0) || $x == count($dataBarang)-1)
            {
                //GET ORDER DETAIL
                $parameter = "&item_id_list=".$item_id_list;
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_item_promotion','parameter' => $parameter),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                if($ret['error'] != "")
                {
                    echo $ret['error']." ITEM PROMO : ".$ret['message'];
                    $statusok = false;
                }
                else
                {
                    
                    $dataPromo = $ret['response']['success_list'];
                 
                    for($p = 0 ; $p < count($dataPromo) ; $p++)
                    {   
                        for($pm = 0 ; $pm < count($dataPromo[$p]['promotion']) ; $pm++)
                        {
                            $ID = "";
                            foreach($dataBarangMaster as $itemBarangMaster)
                            {
                                if((int)$itemBarangMaster->IDBARANGSHOPEE == (int)($dataPromo[$p]['promotion'][$pm]['model_id']??$dataPromo[$p]['item_id']))
                                {
                                    $ID = $itemBarangMaster->IDBARANG;
                                }
                            }
                            
                            array_push($data['rows'], array(
                                'ID'                => $ID,
                                'IDINDUKBARANGSHOPE'=> $dataPromo[$p]['item_id'],
                                'IDBARANGSHOPEE'    => $dataPromo[$p]['promotion'][$pm]['model_id']??$dataPromo[$p]['item_id'],
                                'STARTDATE'         => $dataPromo[$p]['promotion'][$pm]['start_time'],
                                'ENDDATE'           => $dataPromo[$p]['promotion'][$pm]['end_time'],
                                'STARTDATELASTPROMO'=> $tglAw,
                                'ENDDATELASTPROMO'  => $tglAk,
                                'DISABLED'          => $tglAw > $dataPromo[$p]['promotion'][$pm]['end_time'] ? false : true
                            ));
                        }
                    }
                }
                $item_id_list = "";
            }
            else
            {
                $item_id_list .= ",";
            }
            
        }
        
        usort($data['rows'], function($a, $b) {
           return strcmp($a['ID'], $b['ID']);
        });
        
        echo json_encode($data);
	}
	
	function getPromo(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$idPromosi = $this->input->post("idpromosi",0);
		
		$statusok = true;
		$pageno = 1;
		$pageSize = 100;
		$data['rows'] = [];
		
		//LOGISTIC
		$curl = curl_init();
		
		while(!$bigger && $statusok)
        {
            
		    $parameter = "&discount_id=".$idPromosi."&page_no=".$pageno."&page_size=".$pageSize;
		    
		  //  echo $parameter;
		    
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => 'discount/get_discount','parameter' => $parameter),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            if($ret['error'] != "")
            {
                echo $ret['error']." : ".$ret['message'];
            }
            else
            {
                $response = $ret['response'];
                $statusok = $response['more'];
                if($statusok){
                    $pageno++;
                }
                
                $sql = "SELECT * FROM MBARANG";
                $dataBarang = $CI->db->query($sql)->result();
                
                for($p = 0 ; $p < count($response['item_list']);$p++)
                {
                    $dataItem = $response['item_list'][$p];
                    for($m = 0 ; $m < count($dataItem['model_list']); $m++)
                    {
                        //MODEL
                        $dataModel = $dataItem['model_list'][$m];
                        $namamodel = $dataModel['model_name'];
                        $id = "";
                        foreach($dataBarang as $itemBarang)
                        {
                            if($itemBarang->IDINDUKBARANGSHOPEE == $dataItem['item_id'] && $itemBarang->IDBARANGSHOPEE == $dataModel['model_id'])
                            {
                                $namamodel = $itemBarang->NAMABARANG;
                                $id        = $itemBarang->IDBARANG;
                            }
                        }
                        
                        array_push($data['rows'],array(
                            'ID'                 => $id,
                            'IDINDUKBARANGSHOPEE'=> $dataItem['item_id'],
                            'IDBARANGSHOPEE'     => $dataModel['model_id'],
                            'NAMABARANG'         => $namamodel,
                            'HARGAJUALTAMPIL'    => $dataModel['model_original_price'],
                            'HARGACORET'         => $dataModel['model_promotion_price'],
                            'BATASPEMBELIAN'     => $dataItem['purchase_limit'],
                        ));
                    }
                    
                    //INDUK
                    if(count($dataItem['model_list']) == 0)
                    {
                        $namainduk = $dataItem['item_name'];
                        $id = "";
                        foreach($dataBarang as $itemBarang)
                        {
                            if($itemBarang->IDINDUKBARANGSHOPEE == $dataItem['item_id'] && $itemBarang->IDINDUKBARANGSHOPEE == $itemBarang->IDBARANGSHOPEE)
                            {
                                $namainduk = $itemBarang->NAMABARANG;
                                $id        = $itemBarang->IDBARANG;
                            }
                        }
                        
                        array_push($data['rows'],array(
                            'ID'                 => $id,
                            'IDINDUKBARANGSHOPEE'=> $dataItem['item_id'],
                            'IDBARANGSHOPEE'     => $dataItem['item_id'],
                            'NAMABARANG'         => $namainduk,
                            'HARGAJUALTAMPIL'    => $dataItem['item_original_price'],
                            'HARGACORET'         => $dataItem['item_promotion_price'],
                            'BATASPEMBELIAN'     => $dataItem['purchase_limit'],
                        ));
                    }
                }
            }
        }
        
        usort($data['rows'], function($a, $b) {
           return strcmp($a['NAMABARANG'], $b['NAMABARANG']);
        });
        
        echo json_encode($data);
	}
	
	function setPromo(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
		$mode       = $this->input->post("mode","");
		$id         = $this->input->post("idpromosi","");
		$nama       = $this->input->post("namapromosi","");
		$tglMulai   = $this->input->post("tglmulai","");
		$tglAkhir   = $this->input->post("tglakhir","");
		$status     = $this->input->post("status","");
		$dataBarang   = json_decode($this->input->post("databarang",[]),true);
		
		$parameter = [];
		$parameter['discount_name'] = $nama;
		$parameter['start_time']    = strtotime($tglMulai);
		$parameter['end_time']      = strtotime($tglAkhir);
		
		if($status == 'upcoming')
		{
    		$endpoint = "";
    		
    		if($mode == "UBAH")
    		{
    		    $parameter['discount_id'] = (int)$id;
    		    $endpoint = 'discount/update_discount';
    		}
    		else
    		{
    		    $endpoint = 'discount/add_discount';
    		}
    		
    	    $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  array(
              'endpoint' => $endpoint,
              'parameter' => json_encode($parameter)),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
              
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
         
            if($ret['error'] != "")
            {
                $data['success'] = false;
                $data['msg'] =  $ret['error']." BUAT PROMO : ".$ret['message'];
                die(json_encode($data));
            }
            else
            {
                $id = $ret['response']['discount_id'];
            }
		}
        
        $response = $ret['response'];
        $parameterTambah = [];
    	$parameterTambah['discount_id']   = (int)$id;
    	$parameterTambah['item_list']  = array();
    	
    	$parameterUbah = [];
    	$parameterUbah['discount_id']   = (int)$id;
    	$parameterUbah['item_list']  = array();
    	
    	$parameterHapus = [];
    	$parameterHapus['discount_id']   = (int)$id;
    	$parameterHapus['item_list']  = array();
    	
    	$idbarangTambah = "";
    	$idbarangUbah   = "";
    	$idbarangHapus = "";
    	
    	foreach($dataBarang as $itemBarang){
    	   if($itemBarang['IDBARANGSHOPEE'] != 0 && $itemBarang['IDINDUKBARANGSHOPEE'] != 0)
    	   {
    	       if($itemBarang['MODE'] == "TAMBAH")
    	       {
        	       if($idbarangTambah != $itemBarang['IDINDUKBARANGSHOPEE'])
        	       {
        	           $idbarangTambah = $itemBarang['IDINDUKBARANGSHOPEE'];
        	           array_push($parameterTambah['item_list'], array(
        	               'item_id'                => (int)$itemBarang['IDINDUKBARANGSHOPEE'],
        	               'item_promotion_price'   => (float)$itemBarang['HARGACORET'],
        	               'purchase_limit'         => (int)$itemBarang['BATASPEMBELIAN']??0,
        	               'model_list' => []
        	           ));
        	       }
        	       
        	       if($itemBarang['IDBARANGSHOPEE'] != $itemBarang['IDINDUKBARANGSHOPEE'])
        	       {
        	           array_push($parameterTambah['item_list'][count($parameterTambah['item_list'])-1]['model_list'],
        	           array(
        	                'model_id'               => (int)$itemBarang['IDBARANGSHOPEE'],
        	                'model_promotion_price'   => (float)$itemBarang['HARGACORET'],
        	           ));
        	       }
    	       }
    	       else if($itemBarang['MODE'] == "UBAH")
    	       {
        	       if($idbarangUbah != $itemBarang['IDINDUKBARANGSHOPEE'])
        	       {
        	           $idbarangUbah = $itemBarang['IDINDUKBARANGSHOPEE'];
        	           array_push($parameterUbah['item_list'], array(
        	               'item_id'                => (int)$itemBarang['IDINDUKBARANGSHOPEE'],
        	               'item_promotion_price'   => (float)$itemBarang['HARGACORET'],
        	               'purchase_limit'         => (int)$itemBarang['BATASPEMBELIAN']??0,
        	               'model_list' => []
        	           ));
        	       }
        	       
        	       if($itemBarang['IDBARANGSHOPEE'] != $itemBarang['IDINDUKBARANGSHOPEE'])
        	       {
        	           array_push($parameterUbah['item_list'][count($parameterUbah['item_list'])-1]['model_list'],
        	           array(
        	                'model_id'               => (int)$itemBarang['IDBARANGSHOPEE'],
        	                'model_promotion_price'   => (float)$itemBarang['HARGACORET'],
        	           ));
        	       }
    	       }
    	       else if($itemBarang['MODE'] == "HAPUS")
    	       {
        	       if($idbarangHapus != $itemBarang['IDINDUKBARANGSHOPEE'])
        	       {
        	           $idbarangHapus = $itemBarang['IDINDUKBARANGSHOPEE'];
        	           array_push($parameterHapus['item_list'], array(
        	               'item_id'                => (int)$itemBarang['IDINDUKBARANGSHOPEE'],
        	               'item_promotion_price'   => (float)$itemBarang['HARGACORET'],
        	               'purchase_limit'         => (int)$itemBarang['BATASPEMBELIAN']??0,
        	               'model_list' => []
        	           ));
        	       }
        	       
        	       if($itemBarang['IDBARANGSHOPEE'] != $itemBarang['IDINDUKBARANGSHOPEE'])
        	       {
        	           array_push($parameterHapus['item_list'][count($parameterHapus['item_list'])-1]['model_list'],
        	           array(
        	                'model_id'               => (int)$itemBarang['IDBARANGSHOPEE'],
        	                'model_promotion_price'   => (float)$itemBarang['HARGACORET'],
        	           ));
        	       }
    	       }
    	   }
    	}
    	
    	if(count($parameterTambah['item_list']) > 0)
    	{
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  array(
              'endpoint' => 'discount/add_discount_item',
              'parameter' => json_encode($parameterTambah)),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
              
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
         
            if($ret['error'] != "")
            {
                $data['success'] = false;
                $data['msg'] =  $ret['error']."PROMO BARANG TAMBAH : ".$ret['message'];
                die(json_encode($data));
            }
    	}
    	
    	if(count($parameterUbah['item_list']) > 0)
    	{
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  array(
              'endpoint' => 'discount/update_discount_item',
              'parameter' => json_encode($parameterUbah)),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
              
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
         
            if($ret['error'] != "")
            {
                $data['success'] = false;
                $data['msg'] =  $ret['error']."PROMO BARANG UBAH : ".$ret['message'];
                die(json_encode($data));
            }
    	}
    	
    	if(count($parameterHapus['item_list']) > 0)
    	{
    	    for($h = 0 ; $h < count($parameterHapus['item_list']) ; $h++)
    	    {
    	        if(count($parameterHapus['item_list'][$h]['model_list']) == 0)
    	        {
    	            $curl = curl_init();
            	    $parameter = [];
            	    $parameter['discount_id']   = (int)$parameterHapus['discount_id'];
            	    $parameter['item_id']       = (int)$parameterHapus['item_list'][$h]['item_id'];

                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>  array(
                      'endpoint' => 'discount/delete_discount_item',
                      'parameter' => json_encode($parameter)),
                      CURLOPT_HTTPHEADER => array(
                        'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                      ),
                    ));
                      
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $ret =  json_decode($response,true);
                 
                    if($ret['error'] != "")
                    {
                        $data['success'] = false;
                        $data['msg'] =  $ret['error']."PROMO BARANG HAPUS : ".$ret['message'];
                        die(json_encode($data));
                    }
    	        }
    	        else
    	        {
    	        
        	        for($v = 0 ; $v < count($parameterHapus['item_list'][$h]['model_list']) ; $v++)
        	        {
                	    $curl = curl_init();
                	    $parameter = [];
                	    $parameter['discount_id']   = (int)$parameterHapus['discount_id'];
                	    $parameter['item_id']       = (int)$parameterHapus['item_list'][$h]['item_id'];
                	    $parameter['model_id']      = (int)$parameterHapus['item_list'][$h]['model_list'][$v]['model_id'];

                        curl_setopt_array($curl, array(
                          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS =>  array(
                          'endpoint' => 'discount/delete_discount_item',
                          'parameter' => json_encode($parameter)),
                          CURLOPT_HTTPHEADER => array(
                            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                          ),
                        ));
                          
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $ret =  json_decode($response,true);
                     
                        if($ret['error'] != "")
                        {
                            $data['success'] = false;
                            $data['msg'] =  $ret['error']."PROMO BARANG HAPUS : ".$ret['message'];
                            die(json_encode($data));
                        }
        	        }
    	        }
    	    }
    	}
        
        $data['success'] = true;
        $data['msg'] = "Promo Produk pada Shopee Berhasil Disimpan";
        
        echo(json_encode($data));
    
	}
	
	function removePromo(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
		$idPromosi = $this->input->post("idpromosi",0);
		$statusPromosi = $this->input->post("statuspromosi");
		
		$endpoint = "discount/delete_discount";
		if($statusPromosi == "ongoing")
		{
		    $endpoint = "discount/end_discount";
		}
		$parameter = [];
		$parameter['discount_id'] = (int)$idPromosi;
		
	    $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => $endpoint,
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        sleep(3);
        if($ret['error'] != "")
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $data['success'] = true;
            $data['msg'] = "Promo Produk pada Shopee Berhasil Dihapus";
            echo(json_encode($data));
        }
	}
	
	function setBoost(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$dataBarangPermanent = json_decode($this->input->post("databarangpermanent"),true);
		$dataBarangAll = json_decode($this->input->post("databarangall"),true);
		
		$CI->db->set('BOOSTSHOPEE',0)
    	     ->update('MBARANG');
    	     
        foreach($dataBarangAll as $itemBarangAll)
		{
             $CI->db->where("IDINDUKBARANGSHOPEE",$itemBarangAll)
    	     ->set('BOOSTSHOPEE',1)
    	     ->update('MBARANG');
		}
		
		foreach($dataBarangPermanent as $itemBarangPermanent)
		{
             $CI->db->where("IDINDUKBARANGSHOPEE",$itemBarangPermanent)
    	     ->set('BOOSTSHOPEE',2)
    	     ->update('MBARANG');
		}
	     
        $data['success'] = true;
        $data['msg'] = "Jadwal Naikkan Produk pada Shopee Berhasil Disimpan";
        echo(json_encode($data));
	}
	
	function getBoost(){
	   $CI =& get_instance();	
       $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
	   $this->output->set_content_type('application/json');
	   
	   $data['rows'] = [];
	   
	   $curl = curl_init();
	    
	   curl_setopt_array($curl, array(
         CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_boosted_list','parameter' => $parameter),
         CURLOPT_HTTPHEADER => array(
           'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
         ),
       ));
       
       $response = curl_exec($curl);
       curl_close($curl);
       $ret =  json_decode($response,true);
       if($ret['error'] != "")
       {
           echo $ret['error']." : ".$ret['message'];
       }
       else
       {
           $itemList = $ret['response']['item_list'];
           $sql = "SELECT * FROM MBARANG WHERE BOOSTSHOPEE != 0 group by KATEGORI";
           $dataBarang = $CI->db->query($sql)->result();
           
            foreach($dataBarang as $itemBarang)
            {
               if($itemBarang->IDINDUKBARANGSHOPEE != 0)
               {
                   $waktu = "-";
                   for($i = 0 ; $i < count($itemList) ; $i++)
                   {
                       if($itemBarang->IDINDUKBARANGSHOPEE == $itemList[$i]['item_id'])
                       {
                           $waktu = $itemList[$i]['cool_down_second'];
                       }
                   }
                       
                    array_push($data['rows'],array(
                        'PERMANENT' => $itemBarang->BOOSTSHOPEE == 2 ? true : false,
                        'ID'        => $itemBarang->IDINDUKBARANGSHOPEE,
                        'NAMA'      => $itemBarang->KATEGORI,
                        'WAKTU'     => $waktu
                    ));
               }
            }
       }
       
       usort($data['rows'], function($a, $b) {
           return strcmp($a['NAMA'], $b['NAMA']);
       });
       
       	echo json_encode($data); 
	}
	
	//TRANSACTION
	public function dataGrid() {
		$this->output->set_content_type('application/json');
        $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
        $state = $this->input->post('state');
		$filter = $this->setFilterGrid();
		$temp_tgl_aw = TGLAWALFILTERMARKETPLACE;
		$tgl_aw = $this->input->post('tglawal') =='' ? $temp_tgl_aw : ubah_tgl_firebird($this->input->post('tglawal'));
		$tgl_ak = $this->input->post('tglakhir')=='' ? $temp_tgl_ak : ubah_tgl_firebird($this->input->post('tglakhir'));
		$status = explode(",",$this->input->post('status'));
        
        $tgl_aw = $tgl_aw." 00:00:00"; 
        $tgl_ak = $tgl_ak." 23:59:59";
	    
        $result;
        $statusVar = "";
        if(count($status)>1)
        {
            $whereStatus = "and STATUS = $state";
            $statusVar = "STATUSMARKETPLACE";
            
            if($state == 4)
            {
                $statusVar = "CONCAT(STATUSMARKETPLACE,'|',STATUSPENGEMBALIANMARKETPLACE)";
            }
        }
        else
        {
            $statusKhusus = explode("|",$status[0]);
            if(count($statusKhusus) == 2)
            {
                $statusGanda = explode("-",$statusKhusus[1]);
                if(count($statusGanda) == 2)
                {
                    $whereStatus = "and STATUSMARKETPLACE = '".$statusKhusus[0]."' and (STATUSPENGEMBALIANMARKETPLACE = '".$statusGanda[0]."' OR STATUSPENGEMBALIANMARKETPLACE = '".$statusGanda[1]."')";
                }
                else
                {
                    $whereStatus = "and STATUSMARKETPLACE = '".$statusKhusus[0]."' and STATUSPENGEMBALIANMARKETPLACE = '".$statusKhusus[1]."'";
                }
                $statusVar = "CONCAT(STATUSMARKETPLACE,'|',STATUSPENGEMBALIANMARKETPLACE)";
            }
            else
            {
                $whereStatus = "and STATUSMARKETPLACE = '$status[0]'";
                $statusVar = "STATUSMARKETPLACE";
            }
        }
        
        $sql = "SELECT KODEPENJUALANMARKETPLACE as KODEPESANAN, TGLTRANS as TGLPESANAN, MINTGLKIRIM, $statusVar AS STATUS,KODEPENGAMBILAN,
                        SKUPRODUK, '' as BARANG, TOTALBARANG, TOTALHARGA, TOTALBAYAR,  '' as ALAMAT,SKUPRODUKOLD,USERNAME,
                        NAME as BUYERNAME, TELP as BUYERPHONE, ALAMAT as BUYERALAMAT, KOTA,
                        METODEBAYAR, KURIR, RESI, CATATANPEMBELI as CATATANBELI, CATATANPENJUAL AS CATATANJUAL, CATATANPENGEMBALIAN,KODEPACKAGING,
                        KODEPENGEMBALIANMARKETPLACE as KODEPENGEMBALIAN, TGLPENGEMBALIAN, MINTGLPENGEMBALIAN, RESIPENGEMBALIAN, TOTALBARANGPENGEMBALIAN,MINTGLKIRIMPENGEMBALIAN,
                        TOTALPENGEMBALIANDANA, SKUPRODUKPENGEMBALIAN, '' as BARANGPENGEMBALIAN, TIPEPENGEMBALIAN, SELLERMENUNGGUBARANGDATANG,BARANGSAMPAI
                        FROM TPENJUALANMARKETPLACE WHERE MARKETPLACE = 'SHOPEE' and TGLTRANS BETWEEN '".$tgl_aw."' and '".$tgl_ak."' $whereStatus 
                        order by TGLTRANS DESC";
        $result = $CI->db->query($sql)->result();
        
        foreach($result as $item)
        {
            $produk = explode("|",$item->SKUPRODUK);
            $produkOld = explode("|",$item->SKUPRODUKOLD);
            $item->STATUS = $this->getStatus($item->STATUS)['status'];
            $item->TGLPESANAN = explode(" ",$item->TGLPESANAN)[0]."<br>".explode(" ",$item->TGLPESANAN)[1];
            $item->TGLPENGEMBALIAN = explode(" ",$item->TGLPENGEMBALIAN)[0]."<br>".explode(" ",$item->TGLPENGEMBALIAN)[1];
            
            //GET NAMA BARANG
            $sql = "SELECT NAMABARANG, WARNA, SIZE
                        FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$produk[0])[1]."'";
            $dataBarang = $CI->db->query($sql)->row();
            $item->BARANG  = explode(" | ",$dataBarang->NAMABARANG)[0];
            
            $sqlOld = "SELECT WARNA, SIZE
                        FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$produkOld[0])[1]."'";
            $dataBarangOld = $CI->db->query($sqlOld)->row();
            
            if(count(explode(" | ",$dataBarang->NAMABARANG)) > 1)
            {
                $item->BARANG .= "<br><i>".$dataBarang->WARNA.", ".$dataBarang->SIZE."</i>";
                
                if($dataBarang->SIZE != $dataBarangOld->SIZE || $dataBarang->WARNA != $dataBarangOld->WARNA)
                {
                    $item->BARANG .= "&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : ".$dataBarangOld->WARNA." / ".$dataBarangOld->SIZE."</span>";
                }
            }
            
            if(count($produk) > 1)
            {
                $item->BARANG .= "<br><i style='color:blue;'>Lihat produk lain, buka Detail Pesanan</i>";
            }
            
            if($item->SKUPRODUKPENGEMBALIAN != "")
            {
                $indexPengganti;
                $findChange = false;
                $produkKembali = explode("|",$item->SKUPRODUKPENGEMBALIAN);
               
                for($t = 0 ; $t < count($produkKembali);$t++)
                {
                    for($s = 0 ; $s < count($produkOld);$s++)
                    {
                        if(explode("*",$produkKembali[$t])[1] == explode("*",$produkOld[$s])[1] && !$findChange)
                        {
                           $indexPengganti = $s;
                           $findChange = true;
                        }
                    }
                }
                
                //GET NAMA BARANG
                $sql = "SELECT NAMABARANG, WARNA, SIZE
                            FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$produk[$indexPengganti])[1]."'";
                $dataBarang = $CI->db->query($sql)->row();
             
                $item->BARANGPENGEMBALIAN  = explode(" | ",$dataBarang->NAMABARANG)[0];
                
                $sqlOld = "SELECT WARNA, SIZE
                        FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$produkOld[$indexPengganti])[1]."'";
                $dataBarangOld = $CI->db->query($sqlOld)->row();
        
                if(count(explode(" | ",$dataBarang->NAMABARANG)) > 1)
                {
                    $item->BARANGPENGEMBALIAN .= "<br><i>".$dataBarang->WARNA.", ".$dataBarang->SIZE."</i>";
                    
                    if($dataBarang->SIZE != $dataBarangOld->SIZE || $dataBarang->WARNA != $dataBarangOld->WARNA)
                    {
                        $item->BARANGPENGEMBALIAN .= "&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : ".$dataBarangOld->WARNA." / ".$dataBarangOld->SIZE."</span>";
                    }
                }
                
                if(count($produkKembali) > 1)
                {
                    $item->BARANGPENGEMBALIAN .= "<br><i style='color:blue;'>Lihat produk lain, buka Detail Pengembalian</i>";
                }
                
                
                
                
            }
            else
            {
                $item->BARANGPENGEMBALIAN = $item->BARANG;
            }
        
            $item->ALAMAT = "<div style='width:250px; white-space: pre-wrap;      /* CSS3 */   
                                                white-space: -moz-pre-wrap; /* Firefox */    
                                                white-space: -pre-wrap;     /* Opera <7 */   
                                                white-space: -o-pre-wrap;   /* Opera 7 */    
                                                word-wrap: break-word;      /* IE */'>".$item->BUYERNAME." (".$item->USERNAME.")<br>".$item->BUYERPHONE."<br>".$item->BUYERALAMAT."</div>";
            $item->CATATANPENGEMBALIAN = "<div style='width:250px; white-space: pre-wrap;      /* CSS3 */   
                                                white-space: -moz-pre-wrap; /* Firefox */    
                                                white-space: -pre-wrap;     /* Opera <7 */   
                                                white-space: -o-pre-wrap;   /* Opera 7 */    
                                                word-wrap: break-word;      /* IE */'>".$item->CATATANPENGEMBALIAN??''."</div>";                                    
            $item->CATATANBELI = "<div style='width:250px; white-space: pre-wrap;      /* CSS3 */   
                                                white-space: -moz-pre-wrap; /* Firefox */    
                                                white-space: -pre-wrap;     /* Opera <7 */   
                                                white-space: -o-pre-wrap;   /* Opera 7 */    
                                                word-wrap: break-word;      /* IE */'>".$item->CATATANBELI??''."</div>";
            $item->CATATANJUALRAW = $item->CATATANJUAL;                                    
            $item->CATATANJUAL = "<i class='fa fa-edit' id='editNoteShopee' style='cursor:pointer;'></i>
                                  <div style='width:250px; white-space: pre-wrap;      /* CSS3 */   
                                                white-space: -moz-pre-wrap; /* Firefox */    
                                                white-space: -pre-wrap;     /* Opera <7 */   
                                                white-space: -o-pre-wrap;   /* Opera 7 */    
                                                word-wrap: break-word;      /* IE */'>".$item->CATATANJUAL??''."</div>";
        }
        
        $data["rows"]  = $result;
		$data["total"] = count($result);
		
		echo json_encode($data); 
	}

	public function loadDetail(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopesanan = $this->input->post('kode')??"";
		
		//PAYMENT DETAIL
	    $parameter = "&order_sn=".$nopesanan;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'payment/get_escrow_detail','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $result;
		    $result['BIAYALAINBELI']        = $ret['response']['buyer_payment_info']['buyer_service_fee']; 
		    $result['PEMBAYARANBELI']       = $ret['response']['buyer_payment_info']['buyer_total_amount']; 
		    $result['DISKONBELI']           = ($ret['response']['buyer_payment_info']['shopee_voucher']+$ret['response']['buyer_payment_info']['seller_voucher']+$ret['response']['buyer_payment_info']['shopee_coins_redeemed']); 
		    $result['SUBTOTALBELI']         = $ret['response']['buyer_payment_info']['merchant_subtotal']; 
		    $result['BIAYAKIRIMBELI']       = $ret['response']['buyer_payment_info']['shipping_fee']; 
		    
		    $result['BIAYALAYANANJUAL']     = ($ret['response']['order_income']['service_fee']+$ret['response']['order_income']['commission_fee']+$ret['response']['order_income']['order_ams_commission_fee']) * -1; 
		    $result['PENERIMAANJUAL']       = $ret['response']['order_income']['escrow_amount']; 
		    $result['DISKONJUAL']           = $ret['response']['buyer_payment_info']['seller_voucher']; 
		    $result['SUBTOTALJUAL']         = $ret['response']['order_income']['merchant_subtotal']; 
		    $result['BIAYAKIRIMJUAL']       = ($ret['response']['order_income']['reverse_shipping_fee']*-1) + $ret['response']['order_income']['final_shipping_fee']  + $ret['response']['order_income']['buyer_paid_shipping_fee']; 
		    $result['REFUNDJUAL']           = $ret['response']['order_income']['seller_return_refund'];
		    $result['PENYELESAIANPENJUAL']  = $ret['response']['order_income']['escrow_amount'];
		    $result['DETAILBARANG'] = [];
		    
		    $sql = "SELECT SKUPRODUK, ifnull(SKUPRODUKOLD,'') as SKUPRODUKOLD, SKUPRODUKPENGEMBALIAN
                        FROM TPENJUALANMARKETPLACE 
                        WHERE MARKETPLACE = 'SHOPEE' and KODEPENJUALANMARKETPLACE = '$nopesanan' ";
                        
            $resultPesanan = $CI->db->query($sql)->row();
            
            $produkData = explode("|",$resultPesanan->SKUPRODUK);
            $produkDataOld = explode("|",$resultPesanan->SKUPRODUKOLD);
            $dataProduk = [];
            $dataProdukKembali = [];
            $indexProduk = 0;
            foreach($produkData as $item)
            {
                //GET NAMA BARANG
                $sql = "SELECT NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKUSHOPEE as SKU
                            FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$item)[1]."'";
                $dataBarang = $CI->db->query($sql)->row();
                $dataProduk[$indexProduk]->BARANG  = explode(" | ",$dataBarang->NAMABARANG)[0];
                if(count(explode(" | ",$dataBarang->NAMABARANG)) > 1)
                {
                    $dataProduk[$indexProduk]->BARANG .= "<br><i>".$dataBarang->WARNA.", ".$dataBarang->SIZE."</i>";
                }
                $dataProduk[$indexProduk]->SATUAN = $dataBarang->SATUAN;
                $dataProduk[$indexProduk]->KATEGORI = $dataBarang->KATEGORI;
                $dataProduk[$indexProduk]->WARNA = $dataBarang->WARNA;
                $dataProduk[$indexProduk]->SIZE = $dataBarang->SIZE;
                $dataProduk[$indexProduk]->SKU = $dataBarang->SKU;
                $dataProduk[$indexProduk]->BARANGOLD = $dataProduk[$indexProduk]->BARANG;
                $dataProduk[$indexProduk]->WARNAOLD = $dataProduk[$indexProduk]->WARNA;
                $dataProduk[$indexProduk]->SIZEOLD = $dataProduk[$indexProduk]->SIZE;
                $dataProduk[$indexProduk]->SKUOLD = $dataProduk[$indexProduk]->SKU;
                $dataProduk[$indexProduk]->BARANGKEMBALI = "";
                $dataProduk[$indexProduk]->WARNAKEMBALI = "";
                $dataProduk[$indexProduk]->SIZEKEMBALI = "";
                $dataProduk[$indexProduk]->SKUKEMBALI = "";
                $indexProduk++;
            }
            
            if($resultPesanan->SKUPRODUKOLD != "")
            {
                
                $indexProduk = 0;
                foreach($produkDataOld as $item)
                {
                    //GET NAMA BARANG
                    $sql = "SELECT NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKUSHOPEE as SKU
                                FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$item)[1]."'";
                    $dataBarang = $CI->db->query($sql)->row();
                    $dataProduk[$indexProduk]->BARANGOLD  = explode(" | ",$dataBarang->NAMABARANG)[0];
                    if(count(explode(" | ",$dataBarang->NAMABARANG)) > 1)
                    {
                        $dataProduk[$indexProduk]->BARANGOLD .= "<br><i>".$dataBarang->WARNA.", ".$dataBarang->SIZE."</i>";
                    }
                    
                    $dataProduk[$indexProduk]->WARNAOLD = $dataBarang->WARNA;
                    $dataProduk[$indexProduk]->SIZEOLD = $dataBarang->SIZE;
                    $dataProduk[$indexProduk]->SKUOLD = $dataBarang->SKU;
                    $indexProduk++;
                }
            }
            
            
            if($resultPesanan->SKUPRODUKPENGEMBALIAN != "")
            {
                $indexPengganti;
                $produkDataKembali = explode("|",$resultPesanan->SKUPRODUKPENGEMBALIAN);
                for($s = 0 ; $s < count($dataProduk);$s++)
                {
                    $dataProduk[$s]->JMLKEMBALI = 0;
                    $dataProduk[$s]->BARANGKEMBALI =  "";
                    $dataProduk[$s]->WARNAKEMBALI =  "";
                    $dataProduk[$s]->SIZEKEMBALI =  "";
                    $dataProduk[$s]->SKUKEMBALI =  "";
                
                    for($t = 0 ; $t < count($produkDataKembali);$t++)
                    {
                        if(explode("*",$produkDataKembali[$t])[1] == $dataProduk[$s]->SKUOLD)
                        {
                           
                             //JIKA ADA YANG BEDA UPDATE LAGI
                            $sql = "SELECT NAMABARANG, WARNA, SIZE,SKUSHOPEE as SKU
                                        FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$produkDataKembali[$t])[1]."'";
                            $dataBarangKembali = $CI->db->query($sql)->row();
                        
                            if(count(explode(" | ",$dataBarangKembali->NAMABARANG)) > 1)
                            {
                                
                                $dataProduk[$s]->BARANGKEMBALI  = explode(" | ",$dataBarangKembali->NAMABARANG)[0];
                                if(count(explode(" | ",$dataBarangKembali->NAMABARANG)) > 1)
                                {
                                    $dataProduk[$s]->BARANGKEMBALI .= "<br><i>".$dataBarangKembali->WARNA.", ".$dataBarangKembali->SIZE."</i>";
                                }
                                
                                
                                $dataProduk[$s]->JMLKEMBALI = explode("*",$produkDataKembali[$t])[0];
                                $dataProduk[$s]->WARNAKEMBALI = $dataBarangKembali->WARNA;
                                $dataProduk[$s]->SIZEKEMBALI = $dataBarangKembali->SIZE;
                                $dataProduk[$s]->SKUKEMBALI = $dataBarangKembali->SKU;
                                
                            }
                        }
                    }
                }
            }
            
		    
		    for($x = 0; $x < count($ret['response']['order_income']['items']) ; $x++)
		    {
            
		        $resultDetail;
		        $resultDetail['KATEGORI'] = $dataProduk[$x]->KATEGORI;
		        $resultDetail['ITEMID'] = $ret['response']['order_income']['items'][$x]['item_id'];
		        $resultDetail['MODELID'] = $ret['response']['order_income']['items'][$x]['model_id'];
		        $resultDetail['NAMA'] = $dataProduk[$x]->BARANG;
		        $resultDetail['WARNA'] = $dataProduk[$x]->WARNA;
		        $resultDetail['SIZE'] = $dataProduk[$x]->SIZE;
		        $resultDetail['SKU'] = $dataProduk[$x]->SKU;
		        $resultDetail['NAMAOLD'] = $dataProduk[$x]->BARANGOLD;
		        $resultDetail['WARNAOLD'] = $dataProduk[$x]->WARNAOLD;
		        $resultDetail['SIZEOLD'] = $dataProduk[$x]->SIZEOLD;
		        $resultDetail['SKUOLD'] = $dataProduk[$x]->SKUOLD;
		        $resultDetail['NAMAKEMBALI'] = $dataProduk[$x]->BARANGKEMBALI;
		        $resultDetail['WARNAKEMBALI'] = $dataProduk[$x]->WARNAKEMBALI;
		        $resultDetail['SIZEKEMBALI'] = $dataProduk[$x]->SIZEKEMBALI;
		        $resultDetail['SKUKEMBALI'] = $dataProduk[$x]->SKUKEMBALI;
		        $resultDetail['JUMLAH'] = $ret['response']['order_income']['items'][$x]['quantity_purchased'];
		        $resultDetail['JUMLAHKEMBALI'] = $dataProduk[$x]->JMLKEMBALI??"0";
		        $resultDetail['SATUAN'] = $dataProduk[$x]->SATUAN;
		        $resultDetail['HARGATAMPIL'] = $ret['response']['order_income']['items'][$x]['original_price'] / $resultDetail['JUMLAH'];
		        $resultDetail['HARGACORET'] =  $ret['response']['order_income']['items'][$x]['selling_price'] / $resultDetail['JUMLAH'];
		        $resultDetail['SUBTOTAL'] =  $resultDetail['JUMLAH'] * $resultDetail['HARGACORET'];
		        array_push($result['DETAILBARANG'],$resultDetail);
		    }
		    echo(json_encode($result));
        }

	}
	
	public function loadDetailPengembalian(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kode')??"";
		
		//PAYMENT DETAIL
	    $parameter = "&return_sn=".$nopengembalian;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'returns/get_return_detail','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $result;
            $result['TOTALREFUND'] = $ret['response']['refund_amount'];
            $result['GAMBAR'] = $ret['response']['image'];
            $result['VIDEO'] = $ret['response']['buyer_videos'];
            $result['MINTGLCEKBARANG'] =  date("Y-m-d H:i:s", $ret['response']['return_seller_due_date']);
            
            $result['NEGOTIATIONSTATUS'] = $ret['response']['negotiation']['negotiation_status'];
            $result['NEGOTIATIONREFUND'] = $ret['response']['negotiation']['latest_offer_amount'];
            $result['NEGOTIATIONCOUNTER'] = $ret['response']['negotiation']['counter_limit']??0;
            $result['NEGOTIATIONSOLUTION'] = $ret['response']['negotiation']['latest_solution'];
            $result['NEGOTIATIONDATE'] = date("Y-m-d H:i:s", $ret['response']['negotiation']['offer_due_date']);
            $result['LOGISTICSTATUS'] = $ret['response']['logistics_status'];
            $result['REFUNDTYPE'] = $ret['response']['return_refund_type'];
            
            $result['ALASANPILIHPENGEMBALIAN'] = $this->getAlasanKembali($ret['response']['reason']);
            
		    $result['DETAILBARANG'] = [];
		    
		    $sql = "SELECT SKUPRODUKPENGEMBALIAN, SKUPRODUK, ifnull(SKUPRODUKOLD,'') as SKUPRODUKOLD
                        FROM TPENJUALANMARKETPLACE 
                        WHERE MARKETPLACE = 'SHOPEE' and KODEPENGEMBALIANMARKETPLACE = '$nopengembalian' ";
                        
            $resultPesanan = $CI->db->query($sql)->row();
            $produkDataPengembalian = explode("|",$resultPesanan->SKUPRODUKPENGEMBALIAN);
            $produkData = explode("|",$resultPesanan->SKUPRODUK);
            $produkDataOld = explode("|",$resultPesanan->SKUPRODUKOLD);
            $dataProduk = [];
            $indexProduk = 0;
            foreach($produkData as $item)
            {
                //GET NAMA BARANG
                $sql = "SELECT NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKUSHOPEE as SKU
                            FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$item)[1]."'";
                $dataBarang = $CI->db->query($sql)->row();
                $dataProduk[$indexProduk]->BARANG  = explode(" | ",$dataBarang->NAMABARANG)[0];
                if(count(explode(" | ",$dataBarang->NAMABARANG)) > 1)
                {
                    $dataProduk[$indexProduk]->BARANG .= "<br><i>".$dataBarang->WARNA.", ".$dataBarang->SIZE."</i>";
                }
                $dataProduk[$indexProduk]->SATUAN = $dataBarang->SATUAN;
                $dataProduk[$indexProduk]->KATEGORI = $dataBarang->KATEGORI;
                $dataProduk[$indexProduk]->WARNA = $dataBarang->WARNA;
                $dataProduk[$indexProduk]->SIZE = $dataBarang->SIZE;
                $dataProduk[$indexProduk]->SKU = $dataBarang->SKU;
                $dataProduk[$indexProduk]->BARANGOLD = $dataProduk[$indexProduk]->BARANG;
                $dataProduk[$indexProduk]->WARNAOLD = $dataProduk[$indexProduk]->WARNA;
                $dataProduk[$indexProduk]->SIZEOLD = $dataProduk[$indexProduk]->SIZE;
                $dataProduk[$indexProduk]->SKUOLD = $dataProduk[$indexProduk]->SKU;
                $indexProduk++;
            }
            
            if($resultPesanan->SKUPRODUKOLD != "")
            {
                
                $indexProduk = 0;
                foreach($produkDataOld as $item)
                {
                    //GET NAMA BARANG
                    $sql = "SELECT NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKUSHOPEE as SKU
                                FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$item)[1]."'";
                    $dataBarang = $CI->db->query($sql)->row();
                    $dataProduk[$indexProduk]->BARANGOLD  = explode(" | ",$dataBarang->NAMABARANG)[0];
                    if(count(explode(" | ",$dataBarang->NAMABARANG)) > 1)
                    {
                        $dataProduk[$indexProduk]->BARANGOLD .= "<br><i>".$dataBarang->WARNA.", ".$dataBarang->SIZE."</i>";
                    }
                    
                    $dataProduk[$indexProduk]->WARNAOLD = $dataBarang->WARNA;
                    $dataProduk[$indexProduk]->SIZEOLD = $dataBarang->SIZE;
                    $dataProduk[$indexProduk]->SKUOLD = $dataBarang->SKU;
                    $indexProduk++;
                }
            }
            
            if($resultPesanan->SKUPRODUKPENGEMBALIAN != "")
            {
                $indexPengganti;
                $produkDataKembali = explode("|",$resultPesanan->SKUPRODUKPENGEMBALIAN);
               
                for($t = 0 ; $t < count($produkDataKembali);$t++)
                {
                    for($s = 0 ; $s < count($produkDataOld);$s++)
                    {
                        if(explode("*",$produkDataKembali[$t])[1] == explode("*",$produkDataOld[$s])[1])
                        {
                           $indexPengganti = $s;
                           
                             //GET NAMA BARANG
                            $sql = "SELECT NAMABARANG, WARNA, SIZE, SKUSHOPEE as SKU
                                        FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$produkData[$indexPengganti])[1]."'";
                            $dataBarang = $CI->db->query($sql)->row();
                            
                            $sqlOld = "SELECT WARNA, SIZE
                                    FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$produkDataOld[$indexPengganti])[1]."'";
                            $dataBarangOld = $CI->db->query($sqlOld)->row();
                        
                            if(count(explode(" | ",$dataBarang->NAMABARANG)) > 1)
                            {
                                
                                if($dataBarang->SIZE != $dataBarangOld->SIZE || $dataBarang->WARNA != $dataBarangOld->WARNA)
                                {
                                    $dataProduk[$indexPengganti]->WARNA = $dataBarang->WARNA;
                                    $dataProduk[$indexPengganti]->SIZE = $dataBarang->SIZE;
                                    $dataProduk[$indexPengganti]->SKU = $dataBarang->SKU;
                                }
                            }
                        }
                    }
                }
            }
		    
		    for($x = 0; $x < count($ret['response']['item']) ; $x++)
		    {
            
		        $resultDetail;
		        $resultDetail['KATEGORI'] = $dataProduk[$x]->KATEGORI;
		        $resultDetail['ITEMID'] = $ret['response']['item'][$x]['item_id'];
		        $resultDetail['MODELID'] = $ret['response']['item'][$x]['model_id'];
		        $resultDetail['NAMA'] = $dataProduk[$x]->BARANG;
		        $resultDetail['WARNA'] = $dataProduk[$x]->WARNA;
		        $resultDetail['SIZE'] = $dataProduk[$x]->SIZE;
		        $resultDetail['SKU'] = $dataProduk[$x]->SKU;
		        $resultDetail['NAMAOLD'] = $dataProduk[$x]->BARANGOLD;
		        $resultDetail['WARNAOLD'] = $dataProduk[$x]->WARNAOLD;
		        $resultDetail['SIZEOLD'] = $dataProduk[$x]->SIZEOLD;
		        $resultDetail['SKUOLD'] = $dataProduk[$x]->SKUOLD;
		        $resultDetail['JUMLAH'] = $ret['response']['item'][$x]['amount'];
		        $resultDetail['SATUAN'] = $dataProduk[$x]->SATUAN;
		        $resultDetail['HARGA'] = $ret['response']['item'][$x]['item_price'];
		        $resultDetail['SUBTOTAL'] =  $ret['response']['item'][$x]['refund_amount'];
		        array_push($result['DETAILBARANG'],$resultDetail);
		    }
		    echo(json_encode($result));
        }

	}
	
	public function getSolution(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kode')??"";
		$detailRetur = json_decode($this->input->post('detailRetur')??"",true);
        
		$arrayResult = [
            [
                "eligibility" => false,
                "keterangan" => "KEMBALI DANA PENUH",
                "max_refund_amount" => $detailRetur['TOTALREFUND'],
                "refund_amount_adjustable" => false
            ],
            [
                "eligibility" => false,
                "keterangan" => "NEGOSIASI",
                "max_refund_amount" => $detailRetur['TOTALREFUND'],
                "refund_amount_adjustable" => false
            ],
            [
                "eligibility" => false,
                "keterangan" => "MENUNGGU PEMBAYARAN",
                "max_refund_amount" => $detailRetur['TOTALREFUND'],
                "refund_amount_adjustable" => false
            ],
            [
                "eligibility" => false,
                "keterangan" => "DISPUTE",
                "max_refund_amount" => $detailRetur['TOTALREFUND'],
                "refund_amount_adjustable" => false
            ]
        ];
        
		if($detailRetur['REFUNDTYPE'] == "RRBOC")
		{
		    //KALAU 2, BELUM PERNAH NEGO
		    if($detailRetur['NEGOTIATIONCOUNTER'] == 2 && $detailRetur['NEGOTIATIONSTATUS'] != "")
		    {  
    		    $arrayResult[0]['eligibility'] = true;
                $arrayResult[1]['eligibility'] = true;
            	$arrayResult[2]['eligibility'] = true;
		    }
		    else if($detailRetur['LOGISTICSTATUS'] == "LOGISTICS_DELIVERY_DONE")
		    {
		        $arrayResult[0]['eligibility'] = true;
		        $arrayResult[3]['eligibility'] = true;
		    }
		    else if($detailRetur['NEGOTIATIONSTATUS'] == "")
		    {  
    		    $arrayResult[0]['eligibility'] = true;
		    }
		    else
		    {
		        $arrayResult[1]['eligibility'] = true;
		        $arrayResult[1]['max_refund_amount'] = $detailRetur['NEGOTIATIONREFUND'];
		    }
		}
		else
		{
            $arrayResult[3]['eligibility'] = true;
		}
		
           
		echo(json_encode($arrayResult));

	}
	
	public function refund(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kodepengembalian')??"";
		$nopesanan = $this->input->post('kodepesanan')??"";
		
		$parameter = [];
		$parameter['return_sn'] = $nopengembalian;
		//HAPUS PESANAN
		
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => 'returns/confirm',
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
     
        if($ret['error'] != "")
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
          //GET ORDER DETAIL
          $parameter = "&order_sn_list=".$nopesanan."&response_optional_fields=total_amount,item_list,buyer_username,recipient_address,shipping_carrier,payment_method,note,package_list,buyer_cancel_reason";
          // echo $tglTemp." - ".$tgl_ak." 23:59:59"."<br>";
          // echo "&order_status=COMPLETED&time_range_field=create_time&time_from=".$tglAw."&time_to=".$tglAk."&page_size=10"."<br>";
           
          $curl = curl_init();
           
          curl_setopt_array($curl, array(
             CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 30,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'POST',
             CURLOPT_POSTFIELDS => array('endpoint' => 'order/get_order_detail','parameter' => $parameter),
             CURLOPT_HTTPHEADER => array(
              'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
             ),
          ));
           
          $response = curl_exec($curl);
          curl_close($curl);
          $ret =  json_decode($response,true);
          if($ret['error'] != "")
          {
              echo $ret['error']." : ".$ret['message'];
              $statusok = false;
          }
          else
          {
                for($y = 0 ; $y < count($ret['response']['order_list']); $y++)
                {
                  $dataDetail = $ret['response']['order_list'][$y];
                  $data;
                  //DAPATKAN RESI
                  $data['KODEPENJUALANMARKETPLACE']   = $dataDetail['order_sn'];
                  $data['TOTALBAYAR']                 = $dataDetail['total_amount'];
                  $data['STATUSMARKETPLACE']          = $dataDetail['order_status'];
                  $data['NAME']                       = $dataDetail['recipient_address']['name'];
                  $data['TELP']                       = $dataDetail['recipient_address']['phone'];
                  $data['ALAMAT']                     = $dataDetail['recipient_address']['full_address'];
                  $data['KOTA']                       = $dataDetail['recipient_address']['city'];
                  $data['STATUS']                     = $this->getStatus($dataDetail['order_status'])['state'];
                  $data['CATATANPENGEMBALIAN']        = $dataDetail['buyer_cancel_reason'];
             
                  $CI->db->where("KODEPENJUALANMARKETPLACE",$data['KODEPENJUALANMARKETPLACE'])
                  ->where('MARKETPLACE',"SHOPEE")
                    ->updateRaw("TPENJUALANMARKETPLACE", array(
                      'NAME'                       => $data['NAME'],  
                      'TELP'                       => $data['TELP'],  
                      'ALAMAT'                     => $data['ALAMAT'],
                      'KOTA'                       => $data['KOTA'],  
                      'TOTALBAYAR'                 => $data['TOTALBAYAR'],
                      'STATUSMARKETPLACE'          => $data['STATUSMARKETPLACE'],
                      'STATUS'                     => $data['STATUS'],
                      'CATATANPENGEMBALIAN'        => $data['CATATANPENGEMBALIAN'],
                    ));    
                }
          }
           
            //UPDATE TOTAL PENDAPATAN DANA
            $parameter = "";
    	    $parameter = "&order_sn=".$nopesanan;
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => 'payment/get_escrow_detail','parameter' => $parameter),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            if($ret['error'] != "")
            {
                echo $ret['error']." : ".$ret['message'];
            }
            else
            {
    		   $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
    		   ->where('MARKETPLACE','SHOPEE')
    		   ->updateRaw("TPENJUALANMARKETPLACE", array(
    		      'TOTALPENDAPATANPENJUAL'   =>  $ret['response']['order_income']['escrow_amount_after_adjustment'],
    		    ));
            }
            
           //UPDATE BARANG SAMPAI
           $parameter = "&return_sn=".$nopengembalian;
           
           $curl = curl_init();
           $logisticStatus = "";
           curl_setopt_array($curl, array(
             CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 30,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'POST',
             CURLOPT_POSTFIELDS => array('endpoint' => 'returns/get_return_detail','parameter' => $parameter),
             CURLOPT_HTTPHEADER => array(
               'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
             ),
           ));
           
           $response = curl_exec($curl);
           curl_close($curl);
           $ret =  json_decode($response,true);
           if($ret['error'] != "")
           {
               echo $ret['error']." : ".$ret['message'];
           }
           else
           {
               $logisticStatus = $ret['response']['logistics_status'];
               
                $CI->db->where("KODEPENGEMBALIANMARKETPLACE",$nopengembalian)
		          ->where('MARKETPLACE','SHOPEE')
		          ->updateRaw("TPENJUALANMARKETPLACE", array(
		              'BARANGSAMPAI'                  =>  ($logisticStatus == "LOGISTICS_DELIVERY_DONE"? 1 : 0)
		            ));
		            
		        if($logisticStatus == "LOGISTICS_DELIVERY_DONE")
		        {
    		        //CEK LOKASI RETURN, YANG BARANG SMPAI = 1
                    $lokasi = "0";
                    $parameter="";
                    $curl = curl_init();
                    
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS => array('endpoint' => 'logistics/get_address_list','parameter' => $parameter),
                      CURLOPT_HTTPHEADER => array(
                        'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                      ),
                    ));
                    
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $ret =  json_decode($response,true);
                    if($ret['error'] != "")
                    {
                        echo $ret['error']." : ".$ret['message'];
                    }
                    else
                    {
                        $dataAddress = $ret['response']['address_list'];
                        $data['rows'] = [];
                        for($x = 0 ; $x < count($dataAddress);$x++)
                        {
                            $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASISHOPEE = ".$dataAddress[$x]['address_id']." AND GROUPLOKASI like '%MARKETPLACE%'";
                            
                            for($y = 0 ; $y < count($dataAddress[$x]['address_type']);$y++)
                            {
                                if($dataAddress[$x]['address_type'][$y] == "RETURN_ADDRESS")
                                {
                                    $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
                                }
                            }
                        }
                    }
                    
            	    $tglStokMulai = $this->model_master_config->getConfigMarketplace('SHOPEE','TGLSTOKMULAI');
            	    
            	    $wherePesanan = "AND KODEPENJUALANMARKETPLACE in (";
                    for($f = 0 ; $f < count($finalData) ; $f++)
                    {
                        $wherePesanan .= "'".$finalData[$f]['KODEPENJUALANMARKETPLACE']."'";
                        
                        if($f != count($finalData)-1)
                        {
                             $wherePesanan .= ",";
                        }
                    }
                    $wherePesanan .= ")";
                    
                    $sqlRetur = "SELECT KODEPENGEMBALIANMARKETPLACE, TGLPENGEMBALIAN FROM TPENJUALANMARKETPLACE WHERE MARKETPLACE = 'SHOPEE' and KODEPENGEMBALIANMARKETPLACE != '' and BARANGSAMPAI = 1 $wherePesanan ";
                    $dataRetur = $CI->db->query($sqlRetur)->result();
            
                    foreach($dataRetur as $itemRetur)
                    {
                       $this->insertKartuStokRetur($itemRetur->KODEPENGEMBALIANMARKETPLACE,$itemRetur->TGLPENGEMBALIAN,$tglStokMulai,$lokasi);
                    }
                    //CEK LOKASI RETURN
		        }
           }
            
        }
        
        $data['success'] = true;
        $data['msg'] = "Pengembalian Dana #".$nopengembalian." Berhasil Dilakukan";
        echo(json_encode($data));

	}
	
	public function returnRefund(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kodepengembalian')??"";
		$offeramount = $this->input->post('offeramount')??"";
		$solution = $this->input->post('solution')??"";
		
		$parameter = [];
		$parameter['return_sn'] = $nopengembalian;
		$parameter['proposed_solution'] = $solution;
		$parameter['proposed_adjusted_refund_amount'] = (float)$offeramount;
		//HAPUS PESANAN
		
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => 'returns/offer',
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
     
        if($ret['error'] != "")
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $data['success'] = true;
            $data['msg'] = "Negosiasi Pengembalian #".$parameter['return_sn']." Berhasil Dilakukan";
            echo(json_encode($data));
        }

	}
	
	public function finalReturnRefund(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kodepengembalian')??"";
		$nopesanan = $this->input->post('kodepesanan')??"";
		
		$parameter = [];
		$parameter['return_sn'] = $nopengembalian;
		//HAPUS PESANAN
		
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => 'returns/accept_offer',
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
     
        if($ret['error'] != "")
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            //GET ORDER DETAIL
           $parameter = "&order_sn_list=".$nopesanan."&response_optional_fields=total_amount,item_list,buyer_username,recipient_address,shipping_carrier,payment_method,note,package_list,buyer_cancel_reason";
           // echo $tglTemp." - ".$tgl_ak." 23:59:59"."<br>";
           // echo "&order_status=COMPLETED&time_range_field=create_time&time_from=".$tglAw."&time_to=".$tglAk."&page_size=10"."<br>";
           
           $curl = curl_init();
           
           curl_setopt_array($curl, array(
             CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 30,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'POST',
             CURLOPT_POSTFIELDS => array('endpoint' => 'order/get_order_detail','parameter' => $parameter),
             CURLOPT_HTTPHEADER => array(
               'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
             ),
           ));
           
           $response = curl_exec($curl);
           curl_close($curl);
           $ret =  json_decode($response,true);
           if($ret['error'] != "")
           {
               echo $ret['error']." : ".$ret['message'];
               $statusok = false;
           }
           else
           {
                for($y = 0 ; $y < count($ret['response']['order_list']); $y++)
                {
                   $dataDetail = $ret['response']['order_list'][$y];
                   $data;
                   //DAPATKAN RESI
                   $data['KODEPENJUALANMARKETPLACE']   = $dataDetail['order_sn'];
                   $data['TOTALBAYAR']                 = $dataDetail['total_amount'];
                   $data['STATUSMARKETPLACE']          = $dataDetail['order_status'];
                   $data['NAME']                       = $dataDetail['recipient_address']['name'];
                   $data['TELP']                       = $dataDetail['recipient_address']['phone'];
                   $data['ALAMAT']                     = $dataDetail['recipient_address']['full_address'];
                   $data['KOTA']                       = $dataDetail['recipient_address']['city'];
                   $data['STATUS']                     = $this->getStatus($dataDetail['order_status'])['state'];
                   $data['CATATANPENGEMBALIAN']        = $dataDetail['buyer_cancel_reason'];
             
                   $CI->db->where("KODEPENJUALANMARKETPLACE",$data['KODEPENJUALANMARKETPLACE'])
                   ->where('MARKETPLACE',"SHOPEE")
                    ->updateRaw("TPENJUALANMARKETPLACE", array(
                       'NAME'                       => $data['NAME'],  
                       'TELP'                       => $data['TELP'],  
                       'ALAMAT'                     => $data['ALAMAT'],
                       'KOTA'                       => $data['KOTA'],  
                       'TOTALBAYAR'                 => $data['TOTALBAYAR'],
                       'STATUSMARKETPLACE'          => $data['STATUSMARKETPLACE'],
                       'STATUS'                     => $data['STATUS'],
                       'CATATANPENGEMBALIAN'        => $data['CATATANPENGEMBALIAN'],
                    ));    
                }
           }
           
           
            //UPDATE TOTAL PENDAPATAN DANA
            $parameter = "";
    	    $parameter = "&order_sn=".$nopesanan;
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => 'payment/get_escrow_detail','parameter' => $parameter),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            if($ret['error'] != "")
            {
                echo $ret['error']." : ".$ret['message'];
            }
            else
            {
    		   $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
    		   ->where('MARKETPLACE','SHOPEE')
    		   ->updateRaw("TPENJUALANMARKETPLACE", array(
    		      'TOTALPENDAPATANPENJUAL'   =>  $ret['response']['order_income']['escrow_amount_after_adjustment'],
    		    ));
            }
            
           
            $data['success'] = true;
            $data['msg'] = "Pengembalian Dana #".$nopengembalian." Berhasil Dilakukan";
            echo(json_encode($data));
        }

	}
	
	public function dispute(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kodepengembalian')??"";
		$nopesanan = $this->input->post('kodepesanan')??"";
		$email = $this->input->post('email')??"";
		$disputeid = $this->input->post('id')??"";
		$disputetext = $this->input->post('description')??"";
		$imageData = json_decode($this->input->post('data')??"",true);
		
		$imageDataSave = [];
		
		for($x = 0 ; $x < count($imageData); $x++)
		{
		    array_push($imageDataSave,array(
		        'module_index' => (int)$imageData[$x]['index'],
		        'requirement' => $imageData[$x]['requirement'],
		        'image_url' => $imageData[$x]['url']
		    ));
		}
		
		$parameter = [];
		$parameter['return_sn'] = $nopengembalian;
		$parameter['email'] = $email;
		$parameter['dispute_reason_id'] = (int)$disputeid;
		$parameter['dispute_text_reason'] = $disputetext;
		$parameter['image_list'] = $imageDataSave;
		//HAPUS PESANAN
		
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => 'returns/dispute',
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
     
        if($ret['error'] != "")
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
             //GET ORDER DETAIL
           $parameter = "&order_sn_list=".$nopesanan."&response_optional_fields=total_amount,item_list,buyer_username,recipient_address,shipping_carrier,payment_method,note,package_list,buyer_cancel_reason";
           // echo $tglTemp." - ".$tgl_ak." 23:59:59"."<br>";
           // echo "&order_status=COMPLETED&time_range_field=create_time&time_from=".$tglAw."&time_to=".$tglAk."&page_size=10"."<br>";
           
           $curl = curl_init();
           
           curl_setopt_array($curl, array(
             CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 30,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'POST',
             CURLOPT_POSTFIELDS => array('endpoint' => 'order/get_order_detail','parameter' => $parameter),
             CURLOPT_HTTPHEADER => array(
               'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
             ),
           ));
           
           $response = curl_exec($curl);
           curl_close($curl);
           $ret =  json_decode($response,true);
           if($ret['error'] != "")
           {
               echo $ret['error']." : ".$ret['message'];
               $statusok = false;
           }
           else
           {
                for($y = 0 ; $y < count($ret['response']['order_list']); $y++)
                {
                   $dataDetail = $ret['response']['order_list'][$y];
                   $data;
                   //DAPATKAN RESI
                   $data['KODEPENJUALANMARKETPLACE']   = $dataDetail['order_sn'];
                   $data['TOTALBAYAR']                 = $dataDetail['total_amount'];
                   $data['STATUSMARKETPLACE']          = $dataDetail['order_status'];
                   $data['NAME']                       = $dataDetail['recipient_address']['name'];
                   $data['TELP']                       = $dataDetail['recipient_address']['phone'];
                   $data['ALAMAT']                     = $dataDetail['recipient_address']['full_address'];
                   $data['KOTA']                       = $dataDetail['recipient_address']['city'];
                   $data['STATUS']                     = $this->getStatus($dataDetail['order_status'])['state'];
                   $data['CATATANPENGEMBALIAN']        = $dataDetail['buyer_cancel_reason'];
             
                   $CI->db->where("KODEPENJUALANMARKETPLACE",$data['KODEPENJUALANMARKETPLACE'])
                   ->where('MARKETPLACE',"SHOPEE")
                    ->updateRaw("TPENJUALANMARKETPLACE", array(
                       'NAME'                       => $data['NAME'],  
                       'TELP'                       => $data['TELP'],  
                       'ALAMAT'                     => $data['ALAMAT'],
                       'KOTA'                       => $data['KOTA'],  
                       'TOTALBAYAR'                 => $data['TOTALBAYAR'],
                       'STATUSMARKETPLACE'          => $data['STATUSMARKETPLACE'],
                       'STATUS'                     => $data['STATUS'],
                       'CATATANPENGEMBALIAN'        => $data['CATATANPENGEMBALIAN'],
                    ));    
                }
           }
           
           
            //UPDATE TOTAL PENDAPATAN DANA
            $parameter = "";
    	    $parameter = "&order_sn=".$nopesanan;
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => 'payment/get_escrow_detail','parameter' => $parameter),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            if($ret['error'] != "")
            {
                echo $ret['error']." : ".$ret['message'];
            }
            else
            {
    		   $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
    		   ->where('MARKETPLACE','SHOPEE')
    		   ->updateRaw("TPENJUALANMARKETPLACE", array(
    		      'TOTALPENDAPATANPENJUAL'   =>  $ret['response']['order_income']['escrow_amount_after_adjustment'],
    		    ));
            }
            
            //UPDATE BARANG SAMPAI
            $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
    		   ->where('MARKETPLACE','SHOPEE')
    		   ->updateRaw("TPENJUALANMARKETPLACE", array(
    		      'BARANGSAMPAI'   =>  1,
    		 ));
        
            //CEK LOKASI RETURN
            $lokasi = "0";
            $parameter="";
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => 'logistics/get_address_list','parameter' => $parameter),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            if($ret['error'] != "")
            {
                echo $ret['error']." : ".$ret['message'];
            }
            else
            {
                $dataAddress = $ret['response']['address_list'];
                $data['rows'] = [];
                for($x = 0 ; $x < count($dataAddress);$x++)
                {
                    $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASISHOPEE = ".$dataAddress[$x]['address_id']." AND GROUPLOKASI like '%MARKETPLACE%'";
                    
                    for($y = 0 ; $y < count($dataAddress[$x]['address_type']);$y++)
                    {
                        if($dataAddress[$x]['address_type'][$y] == "RETURN_ADDRESS")
                        {
                            $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
                        }
                    }
                }
            }
            
    	    $tglStokMulai = $this->model_master_config->getConfigMarketplace('SHOPEE','TGLSTOKMULAI');
            
            $sqlRetur = "SELECT KODEPENGEMBALIANMARKETPLACE, TGLPENGEMBALIAN FROM TPENJUALANMARKETPLACE WHERE MARKETPLACE = 'SHOPEE' and KODEPENGEMBALIANMARKETPLACE = '".$nopengembalian."'";
            $dataRetur = $CI->db->query($sqlRetur)->result();
            
            foreach($dataRetur as $itemRetur)
            {
               $this->insertKartuStokRetur($itemRetur->KODEPENGEMBALIANMARKETPLACE,$itemRetur->TGLPENGEMBALIAN,$tglStokMulai,$lokasi);
            }
            //CEK LOKASI RETURN
            
            $data['success'] = true;
            $data['msg'] = "Proses Sengketa #".$nopengembalian." Berhasil Dilakukan";
            echo(json_encode($data));
        }

	}
	
	public function getDispute(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kode')??"";
		
		//PAYMENT DETAIL
	    $parameter = "&return_sn=".$nopengembalian;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'returns/get_return_dispute_reason','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
           $response = $ret['response']['dispute_reason_list'];
           $index = 0 ;
           foreach($response as $item)
           {
               $response[$index]['dispute_text'] = $this->getAlasanDispute($response[$index]['dispute_reason']);
               $index++;
           }
		   echo(json_encode($response));
        }

	}
	
	public function cancelDispute(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kodepengembalian')??"";
		$nopesanan = $this->input->post('kodepesanan')??"";
		$email = $this->input->post('email')??"";
		
		$parameter = [];
		$parameter['return_sn'] = $nopengembalian; // when the return_status is ACCEPTED and the compensation_status is COMPENSATION_REQUESTED.
		$parameter['email'] = $email;
		//HAPUS PESANAN
		
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => 'returns/cancel_dispute',
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
     
        if($ret['error'] != "")
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $data['success'] = true;
            $data['msg'] = "Proses Sengketa #".$parameter['return_sn']." Berhasil Dibatalkan";
            echo(json_encode($data));
        }

	}
	
	public function uploadLocalUrlProof(){
	      $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$kode = $this->input->post('kode')??"";
		$index = $this->input->post('index')??0;
		$tipe = $this->input->post('tipe')??0;
		$size = $this->input->post('size')??0;
		
		if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            die(json_encode([
                'success' => false,
                'msg' => 'File upload failed.'
            ]));
        }
        
        // Destination folder (make sure this folder exists and is writable)
        $uploadDir = FCPATH . 'assets/'.$this->input->post('reason').'/'; 
        
        // Create filename and move the file
        $type = ".".explode(".",$_FILES['file']['name'])[count(explode(".",$_FILES['file']['name']))-1];
        $destination = $uploadDir . $kode . "_" . $index.$type;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
            
            if($tipe == "GAMBAR")
            {
                $response =  $this->getRefreshToken();
        	    if($response)
        	    {
        	        $endpoint = "returns/convert_image";
            	    
            	    $code = $this->model_master_config->getConfigMarketplace('SHOPEE','CODE');
            	    $shopId = $this->model_master_config->getConfigMarketplace('SHOPEE','SHOP_ID');
            	    $partnerId = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_ID');
            	    $partnerKey = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_KEY');
            	    $accessToken = $this->model_master_config->getConfigMarketplace('SHOPEE','ACCESS_TOKEN');
                    $path = "/api/v2/";
                    
                    $timest = time();
                    $sign = hash_hmac('sha256', $partnerId.$path.$endpoint.$timest.$accessToken.$shopId,$partnerKey);
                    
                    $host = 'https://partner.shopeemobile.com'.$path.$endpoint;
                    // Path to the local file
                    $filePath = $destination;
                    
                    // Check if file exists
                    if (!file_exists($filePath)) {
                    die('File not found.');
                    }
                    
                    // Prepare the CURLFile object
                    $cfile = new CURLFile($filePath, mime_content_type($filePath), basename($filePath));
                    
                    // Prepare POST data
                    $postData = [
                    'return_sn' => $kode,
                    'upload_image' => $cfile
                    ];
                    
                    // Initialize cURL
                    $ch = curl_init();
                    // Set cURL options
                    curl_setopt($ch, CURLOPT_URL,  $host.'?timestamp='.$timest.'&sign='.$sign.'&partner_id='.$partnerId.'&shop_id='.$shopId."&access_token=".$accessToken); // destination URL
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: multipart/form-data'
                    ]);
                    
                    // Execute the request
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $ret =  json_decode($response,true);
                    if($ret['error'] != "")
                    {
                        $data['success'] = false;
                        print_r($ret);
                        echo json_encode($postData).$ret['error']." : ".$ret['message'];
                    }
                    else
                    {
                      $data['success'] = true;
                      $data['url'] = $ret['response']['url'];
                      $data['thumbnail'] = $ret['response']['thumbnail'];
                      $data['id'] = 0;
                       
                      //HAPUS DLU
                      $folder = FCPATH . '/assets/'.$this->input->post('reason').'/'; // Use FCPATH . 'assets/proof/' in CodeIgniter
                
                      if (!is_dir($folder)) {
                          die('Folder does not exist.');
                      }
                       
                      $files = glob($folder . '*'); // Get all files in the folder
                       
                      foreach ($files as $file) {
                          if (is_file($file) && strpos(basename($file), $kode . "_" . $index) !== false) {
                              unlink($file); // Delete file if name contains "a"
                          }
                      }
        
                      $data['urlLocal'] = $this->config->item('base_url')."/assets/".$this->input->post('reason')."/". $kode . "_" . $index.$type;
            		   echo(json_encode($data));
                    }
    
        	    }
                else
        	    {
        	       // echo "Token gagal diperbaharui";
        	        echo json_encode(array(
        	            "success" => false,
        	            "error" => "failed refresh token"
        	        ));
        	    }
           }
        }
	    
	}
	
	public function uploadLocalUrl(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$kode = $this->input->post('kode')??"";
		$index = $this->input->post('index')??0;
		$tipe = $this->input->post('tipe')??0;
		$size = $this->input->post('size')??0;
		
		if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            die(json_encode([
                'success' => false,
                'msg' => 'File upload failed.'
            ]));
        }
        
        // Destination folder (make sure this folder exists and is writable)
        $uploadDir = FCPATH . 'assets/'.$this->input->post('reason').'/'; 
        
        // Create filename and move the file
        $type = ".".explode(".",$_FILES['file']['name'])[count(explode(".",$_FILES['file']['name']))-1];
        $destination = $uploadDir . $kode . "_" . $index.$type;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
            
            if($tipe == "GAMBAR")
            {
                $response =  $this->getRefreshToken();
        	    if($response)
        	    {
                
            	    $endpoint = "media_space/upload_image";
            	    
            	    $code = $this->model_master_config->getConfigMarketplace('SHOPEE','CODE');
            	    $shopId = $this->model_master_config->getConfigMarketplace('SHOPEE','SHOP_ID');
            	    $partnerId = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_ID');
            	    $partnerKey = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_KEY');
            	    $accessToken = $this->model_master_config->getConfigMarketplace('SHOPEE','ACCESS_TOKEN');
                    $path = "/api/v2/";
                    
                    $timest = time();
                    $sign = hash_hmac('sha256', $partnerId.$path.$endpoint.$timest,$partnerKey);
                    
                    $host = 'https://partner.shopeemobile.com'.$path.$endpoint;
                    // Path to the local file
                    $filePath = $destination;
                    
                    // Check if file exists
                    if (!file_exists($filePath)) {
                    die('File not found.');
                    }
                    
                    // Prepare the CURLFile object
                    $cfile = new CURLFile($filePath, mime_content_type($filePath), basename($filePath));
                    
                    // Prepare POST data
                    $postData = [
                    'image' => $cfile
                    ];
                    
                    // Initialize cURL
                    $ch = curl_init();
                    // Set cURL options
                    curl_setopt($ch, CURLOPT_URL,  $host.'?timestamp='.$timest.'&sign='.$sign.'&partner_id='.$partnerId); // destination URL
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: multipart/form-data'
                    ]);
                    
                    // Execute the request
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $ret =  json_decode($response,true);
                    
                    if($ret['error'] != "")
                    {
                        $data['success'] = false;
                        echo $ret['error']." : ".$ret['message'];
                    }
                    else
                    {
                      $data['success'] = true;
                      $dataUrl = $ret['response']['image_info']['image_url_list'];
                      //GET URL
                      for($u = 0 ; $u < count($dataUrl);$u++)
                      {
                          if($dataUrl[$u]['image_url_region'] == "ID")
                          {
                             $data['url'] = $dataUrl[$u]['image_url'];
                          }
                      }
                      $data['id'] = $ret['response']['image_info']['image_id'];
                       
                      //HAPUS DLU
                      $folder = FCPATH . '/assets/'.$this->input->post('reason').'/'; // Use FCPATH . 'assets/proof/' in CodeIgniter
                
                      if (!is_dir($folder)) {
                          die('Folder does not exist.');
                      }
                       
                      $files = glob($folder . '*'); // Get all files in the folder
                       
                      foreach ($files as $file) {
                          if (is_file($file) && strpos(basename($file), $kode . "_" . $index) !== false) {
                              unlink($file); // Delete file if name contains "a"
                          }
                      }
        
                      $data['urlLocal'] = $this->config->item('base_url')."/assets/".$this->input->post('reason')."/". $kode . "_" . $index.$type;
            		   echo(json_encode($data));
                    }
    
        	    }
                else
        	    {
        	       // echo "Token gagal diperbaharui";
        	        echo json_encode(array(
        	            "success" => false,
        	            "error" => "failed refresh token"
        	        ));
        	    }
           }
           else if($tipe == "VIDEO")
           {
               // Path to the local file
               $filePath = $destination;
               
               // Check if file exists
               if (!file_exists($filePath)) {
                   die('File not found.');
               }
               
                // $input = $filePath;
                // $output = $uploadDir . $kode . "_" . $index."_NEW".$type;
                
                // echo "Input path: " . $input . "\n";
                // echo "Output path: " . $output . "\n";

                // // Escape filenames to avoid shell injection
                // $inputEscaped = escapeshellarg($input);
                // $outputEscaped = escapeshellarg($output);
                
                // $command = "avconv -i $input -c:v libx264 -c:a mp3 -b:a 128k -r 24 -vf \"scale=512:-1,crop=512:288:0:0\" $output";
                
                // // Execute the command
                // $output = shell_exec($command);
                // print_r($command);
                // // Optional: check if output file exists
                // if (file_exists($output)) {
                //     echo "Compression successful.";
                // } else {
                //     echo "Compression failed.";
                // }
               
              // Prepare POST data
              $parameter = [
                  'file_md5' => md5_file($filePath),
                  'file_size' => (int)$size,
              ];
               
              // Initialize cURL
              $curl = curl_init();
              curl_setopt_array($curl, array(
                 CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => '',
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => 'POST',
                 CURLOPT_POSTFIELDS =>  array(
                 'endpoint' => 'media_space/init_video_upload',
                 'parameter' => json_encode($parameter)),
                 CURLOPT_HTTPHEADER => array(
                  'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                 ),
              ));
                 
              $response = curl_exec($curl);
              curl_close($curl);
              $ret =  json_decode($response,true);
               
              if($ret['error'] != "")
              {
                  $data['success'] = false;
                  $data['msg'] =  $ret['error']." init : ".$ret['message'];
                  die(json_encode($data));
              }
              else
              {
                  $parameter = [];
                   
                  $uploadID = $ret['response']['video_upload_id'];
                   
                    $response =  $this->getRefreshToken();
            	    if($response)
            	    {
                	    $endpoint = "media_space/upload_video_part";
                	    
                	    $code = $this->model_master_config->getConfigMarketplace('SHOPEE','CODE');
                	    $shopId = $this->model_master_config->getConfigMarketplace('SHOPEE','SHOP_ID');
                	    $partnerId = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_ID');
                	    $partnerKey = $this->model_master_config->getConfigMarketplace('SHOPEE','PARTNER_KEY');
                	    $accessToken = $this->model_master_config->getConfigMarketplace('SHOPEE','ACCESS_TOKEN');
                        $path = "/api/v2/";
                        
                        $timest = time();
                        $sign = hash_hmac('sha256', $partnerId.$path.$endpoint.$timest,$partnerKey);
                        
                        $host = 'https://partner.shopeemobile.com'.$path.$endpoint;
                        // Path to the local file
                        $filePath = $destination;
                        
                        // Check if file exists
                        if (!file_exists($filePath)) {
                        die('File not found.');
                        }
                        
                        // Prepare the CURLFile object
                        $cfile = new CURLFile($filePath, mime_content_type($filePath), basename($filePath));
                        
                        // Prepare POST data
                        $postData = [
                          'video_upload_id'=> $uploadID,
                          'part_seq'       => 0,
                          'content_md5'    => md5_file($filePath),
                          'part_content'   => $cfile
                        ];
                        
                        $starTimeUpload = new DateTime();
                        // Initialize cURL
                        $ch = curl_init();
                        // Set cURL options
                        curl_setopt($ch, CURLOPT_URL,  $host.'?timestamp='.$timest.'&sign='.$sign.'&partner_id='.$partnerId); // destination URL
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'Content-Type: multipart/form-data'
                        ]);
                        
                        // Execute the request
                        $response = curl_exec($ch);
                        curl_close($ch);
                        $ret =  json_decode($response,true);
                        
                        if($ret['error'] != "")
                        {
                            $data['success'] = false;
                            echo $ret['error']." part : ".$ret['message'];
                        }
                        else
                        { 
                            $endTimeUpload = new DateTime();
                            $startMs = (float)$starTimeUpload->format('U.u') * 1000;
                            $endMs = (float)$endTimeUpload->format('U.u') * 1000;
                            
                            $diffMs = $endMs - $startMs;
    
                            $parameter = [];
                            $partPath = $filePath;
                            //UPLOAD PART VIDEO
                            $parameter = [
                              'video_upload_id'=> $uploadID,
                              'part_seq_list'  => [0],
                              'report_data'    => array(
                                    'upload_cost' => (int)$diffMs
                              )
                            ];
                        
                          // Initialize cURL
                          $curl = curl_init();
                          curl_setopt_array($curl, array(
                             CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                             CURLOPT_RETURNTRANSFER => true,
                             CURLOPT_ENCODING => '',
                             CURLOPT_MAXREDIRS => 10,
                             CURLOPT_TIMEOUT => 30,
                             CURLOPT_FOLLOWLOCATION => true,
                             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                             CURLOPT_CUSTOMREQUEST => 'POST',
                             CURLOPT_POSTFIELDS =>  array(
                             'endpoint' => 'media_space/complete_video_upload',
                             'parameter' => json_encode($parameter)),
                             CURLOPT_HTTPHEADER => array(
                              'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                             ),
                          ));
                             
                          $response = curl_exec($curl);
                          curl_close($curl);
                          $ret =  json_decode($response,true);
                           
                          if($ret['error'] != "")
                          {
                              $data['success'] = false;
                              $data['msg'] =  $ret['error']." complete : ".$ret['message'];
                              die(json_encode($data));
                          }
                          else
                          {
                              $success = false;
                               while(!$success)
                               {
                                  	//COMPLETE VIDEO
                            	    $parameter = "&video_upload_id=".$uploadID;
    
                                    $curl = curl_init();
                                    
                                    curl_setopt_array($curl, array(
                                      CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                                      CURLOPT_RETURNTRANSFER => true,
                                      CURLOPT_ENCODING => '',
                                      CURLOPT_MAXREDIRS => 10,
                                      CURLOPT_TIMEOUT => 30,
                                      CURLOPT_FOLLOWLOCATION => true,
                                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                      CURLOPT_CUSTOMREQUEST => 'POST',
                                      CURLOPT_POSTFIELDS => array('endpoint' => 'media_space/get_video_upload_result','parameter' => $parameter),
                                      CURLOPT_HTTPHEADER => array(
                                        'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                                      ),
                                    ));
                                    
                                    $response = curl_exec($curl);
                                    curl_close($curl);
                                    $ret =  json_decode($response,true);
                                    if($ret['error'] != "")
                                    {
                                        echo $ret['error']." : ".$ret['message'];
                                    }
                                    else
                                    {
                                        if($ret['response']['status'] == "SUCCEEDED")
                                        { 
                                          $success = true;
                                          $data['success'] = true;
                                          $dataUrl = $ret['response']['video_info']['video_url_list'];
                                          //GET URL
                                          for($u = 0 ; $u < count($dataUrl);$u++)
                                          {
                                              if($dataUrl[$u]['video_url_region'] == "ID")
                                              {
                                                 $data['url'] = $dataUrl[$u]['video_url'];
                                              }
                                          }
                                          $data['id'] = $uploadID;
                                           
                                          //HAPUS DLU
                                          $folder = FCPATH . '/assets/'.$this->input->post('reason').'/'; // Use FCPATH . 'assets/proof/' in CodeIgniter
                                    
                                          if (!is_dir($folder)) {
                                              die('Folder does not exist.');
                                          }
                                           
                                          $files = glob($folder . '*'); // Get all files in the folder
                                           
                                          foreach ($files as $file) {
                                              if (is_file($file) && strpos(basename($file), $kode . "_" . $index) !== false) {
                                                  unlink($file); // Delete file if name contains "a"
                                              }
                                          }
                            
                                          $data['urlLocal'] = $this->config->item('base_url')."/assets/".$this->input->post('reason')."/". $kode . "_" . $index.$type;
                                		   echo(json_encode($data));
                                        }
                                    }
                               }
                          }
                        }
        
            	    }
                    else
            	    {
            	       // echo "Token gagal diperbaharui";
            	        echo json_encode(array(
            	            "success" => false,
            	            "error" => "failed refresh token"
            	        ));
            	    }
              }
           }
            
        } else {
            $data['success'] = false;
            $data['msg'] = "Failed to save the uploaded file";
            echo(json_encode($data));
        }
	}
	
	public function uploadProof(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kodepengembalian')??"";
		$nopesanan = $this->input->post('kodepesanan')??"";
		$dataProof = json_decode($this->input->post('dataProof'),true)??[];
		$dataDisputeProof = json_decode($this->input->post('dataDisputeProof'),true)??[];
		$description = $this->input->post('description')??"";
        
        //UPLOAD
        $parameter = [];
    	$parameter['return_sn'] = $nopengembalian;
    	$parameter['photo'] = $dataProof;
    	$parameter['description'] = $description;
        $curl = curl_init();
        
        print_r($parameter);
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => 'returns/upload_proof',
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        
        if($ret['error'] != "")
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $data['success'] = true;
            $data['msg'] = "Bukti Sengketa #".$nopengembalian." Berhasil Disimpan";
            echo(json_encode($data));
        }

	}
	
	public function getProof(){
	     $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kode')??"";
		
		//PAYMENT DETAIL
	    $parameter = "&return_sn=".$nopengembalian;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'returns/query_proof','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
           $response = $ret['response'];
		   echo(json_encode($response));
        }

	}
	
	public function menungguBarangDatang(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kodepengembalian')??"";
		$nopesanan = $this->input->post('kodepesanan')??"";
		
		
	    $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
		        ->where('MARKETPLACE','SHOPEE')
		            ->set("SELLERMENUNGGUBARANGDATANG", 1)
		            ->updateRaw("TPENJUALANMARKETPLACE");

           
        $data['success'] = true;
        $data['msg'] = "Proses Pengembalian #".$nopengembalian." Ditunda Hingga Barang Tiba";
        echo(json_encode($data));
	}
	
	public function ubah(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopesanan = $this->input->post('kode')??"";
		$dataitem = json_decode($this->input->post('dataItem'),true)??"";
		
		$allskuold = "";
		$allsku = "";
		$urutan = 0;
		foreach($dataitem as $item)
		{
		   $urutan++;
		   
            $sku = $item['SKU'];
            $jml = $item['JUMLAH'];
            $allsku .= ($jml."*".$sku."|");
            
            
            $skuold = $item['SKUOLD'];
            $allskuold .= ($jml."*".$skuold."|");
            
            $sqlBarang = "select idbarang from mbarang where SKUSHOPEE = '$sku'";
            $queryBarang = $CI->db->query($sqlBarang)->row();
                               
            //UPDATE DETAIL BARANG
            $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
		            ->where('MARKETPLACE','SHOPEE')
		            ->where('URUTAN',$urutan)
		            ->set("SKU", $sku)
		            ->set("IDBARANG", $queryBarang->IDBARANG)
		            ->updateRaw("TPENJUALANMARKETPLACEDTL");
		}
		 $allskuold = substr($allskuold, 0, -1);
		 $allsku = substr($allsku, 0, -1);
		 
		
		 $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
		        ->where('MARKETPLACE','SHOPEE')
		            ->set("SKUPRODUKOLD", $allskuold)
		            ->updateRaw("TPENJUALANMARKETPLACE");
		            
		 $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
		        ->where('MARKETPLACE','SHOPEE')
		            ->set("SKUPRODUK", $allsku)
		            ->updateRaw("TPENJUALANMARKETPLACE");
		
		$data['success'] = true;
		$data['msg'] = "Produk Berhasil Diubah";
        echo(json_encode($data));

	}
	
	public function catatanPenjual(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopesanan = $this->input->post('kode')??"";
		$note = $this->input->post('note')??"";
		
		$parameter = [];
		$parameter['order_sn'] = $nopesanan;
		$parameter['note'] = $note;
		//HAPUS PESANAN
		
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => 'order/set_note',
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
                    ->where("MARKETPLACE","SHOPEE")
		            ->set("CATATANPENJUAL", $note)
		            ->updateRaw("TPENJUALANMARKETPLACE");
		            
            $data['success'] = true;
            $data['msg'] = "Catatan #".$parameter['order_sn']." Berhasil Disimpan";
            echo(json_encode($data));
        }

	}
	
	public function setKirim(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
	    $dataNoPackaging = json_decode($this->input->post('dataNoPackaging'),true)??"";
        
		$parameter = [];
	    $packaging = [];
        $indexPackaging = 0;
        for($f = 0 ; $f < count($dataNoPackaging);$f++){
            $packaging[count($packaging)]['package_number'] = $dataNoPackaging[$f]['KODEPACKAGING'];
            if(($f % 49 == 0 && $f != 0) || $f == count($dataNoPackaging)-1)
            {
                //GET RESI
                $parameter['package_list'] =  $packaging;
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  array(
                  'endpoint' => 'logistics/get_mass_shipping_parameter',
                  'parameter' => json_encode($parameter)),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                if($ret['error'] != "")
                {
                    echo $ret['error']." : ".$ret['message'];
                    $statusok = false;
                }
                else
                {
                    $data['pickup'] = $ret['response']['pickup']['address_list'];
                    
                    for($x = 0 ; $x < count($data['pickup']) ; $x++)
                    {
                        
                        $sql = "SELECT IFNULL(NAMALOKASI,'') as NAMALOKASI FROM MLOKASI WHERE IDLOKASISHOPEE = ". $data['pickup'][$x]['address_id']." AND GROUPLOKASI like '%MARKETPLACE%'";
                        $namalokasiset = $CI->db->query($sql)->row()->NAMALOKASI??'';
                        
                        if($namalokasiset != "")
                        {
                            $data['pickup'][$x]['address'] .= ("&nbsp;&nbsp;|&nbsp;&nbsp;".$namalokasiset);
                        }
                
                        for($y = 0 ; $y < count($data['pickup'][$x]['time_slot_list']);$y++)
                        {
                            $data['pickup'][$x]['time_slot_list'][$y]['date'] =  date("Y-m-d",$data['pickup'][$x]['time_slot_list'][$y]['date']);
                        }
                        
                    }
                    
                    $data['info'] =$ret['response']['info_needed'];
                    $data['dropoff'] =$ret['response']['dropoff'];
                    $data['index'] = $this->input->post('index')??0;
                    echo(json_encode($data));
                }
                
                $packaging = [];
            }
        }
	}
	
	public function kirim(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
	    $dataAll = json_decode($this->input->post('dataAll'),true)??"";
	    
	    $dataPesanan = [];
	    $order = [];
		$kurir = [];
		foreach($dataAll as $item)
		{
		    
		    $ada = false;
		    for($x = 0 ; $x < count($kurir) ; $x++)
		    {
		        if($kurir[$x] == $item["KURIR"])
		        {
		            $ada = true;
		        }
		    }
		    
		    if(!$ada)
		    {
		       array_push($kurir,$item["KURIR"]) ;
		    }
		    $index = count($order);
            $order[$index] = $item;
		}
		
		for($a = 0 ; $a < count($kurir); $a++)
		{
		    $dataPesanan = [];
    		for($s = 0 ; $s < count($order);$s++){
    		    if($kurir[$a] == $order[$s]['KURIR'])
    		    {
    		        array_push($dataPesanan, $order[$s]);
    		    }
    		}
            
            for($k = 0 ; $k < count($dataPesanan);$k++){
                
        		$parameter = [];
        	    $packaging = [];
                $indexPackaging = 0;
            
                $indexPackaging = count($packaging);
                
                $packaging[$indexPackaging]['package_number'] = $dataPesanan[$k]['KODEPACKAGING'];
                $packaging[$indexPackaging]['order_sn'] = $dataPesanan[$k]['KODEPESANAN'];

                //GET RESI
                $parameter['package_list'] =  $packaging;
                if($dataPesanan[$k]['METHOD'] == "PICK_UP")
                {
                    $parameter['pickup'] =  array(
                        'address_id'     => (int) $dataPesanan[$k]['ADDRESS'],
                        'pickup_time_id' =>  $dataPesanan[$k]['PICKUP']
                    );
                }
                else
                {
                     $parameter['dropoff'] =  array(
                        'branch_id'     => "",
                        'sender_real_name' => "",
                        'tracking_number' =>  ""
                    );
                }
                
                // print_r($parameter);
                
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  array(
                  'endpoint' => 'logistics/mass_ship_order',
                  'parameter' => json_encode($parameter)),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                
                if($ret['error'] != "")
                {
                    echo $ret['error']." : ".$ret['message'];
                    $statusok = false;
                }
                else
                {
                    $parameter = [];
                    $packaging = [];
                    $indexPackaging2 = 0;
                    for($f = 0 ; $f < count($dataPesanan);$f++){
                        $packaging[count($packaging)]['package_number'] = $dataPesanan[$f]['KODEPACKAGING'];
                        if(($f % 49 == 0 && $f != 0) || $f == count($dataPesanan)-1)
                        {
                            //GET RESI
                            $parameter['package_list'] =  $packaging;
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                              CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => '',
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 30,
                              CURLOPT_FOLLOWLOCATION => true,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => 'POST',
                              CURLOPT_POSTFIELDS =>  array(
                              'endpoint' => 'logistics/get_mass_tracking_number',
                              'parameter' => json_encode($parameter)),
                              CURLOPT_HTTPHEADER => array(
                                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                              ),
                            ));
                              
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $ret =  json_decode($response,true);
                            if($ret['error'] != "")
                            {
                                echo $ret['error']." : ".$ret['message'];
                                $statusok = false;
                            }
                            else
                            {
                                for($x = 0 ;$x < count($ret['response']['success_list']); $x++)
                                {
                                    
                                    $dataPesanan[$indexPackaging2]['RESI'] = $ret['response']['success_list'][$x]['tracking_number'];
                                    $dataPesanan[$indexPackaging2]['KODEPENGAMBILAN'] = $ret['response']['success_list'][$x]['pickup_code'];
                                    
                                    $CI->db->where("KODEPENJUALANMARKETPLACE",$dataPesanan[$indexPackaging2]['KODEPESANAN'])
                                        ->where('MARKETPLACE',"SHOPEE")
                    		            ->updateRaw("TPENJUALANMARKETPLACE", array(
                                            'RESI'                       => $dataPesanan[$indexPackaging2]['RESI'],
                                            'KODEPENGAMBILAN'            => $dataPesanan[$indexPackaging2]['KODEPENGAMBILAN'],
                    		        ));
                    		            
                                    $indexPackaging2++;
                                }
                            }
                            
                            $packaging = [];
                        }
                    }
            
            
                    $nopesanan = "";
                    if(count($dataPesanan) == 1)
                    {
                        $nopesanan = "#".$dataPesanan[0]['KODEPESANAN'];
                    }
                  //MASUK KE PROCCESSED
                  $data['success'] = true;
                  $data['msg'] = "Pesanan ".$nopesanan." Berhasil Dikirim";
                }
            
            }
		}
		sleep(3); 
		$nopesanan = "";
        for($x = 0 ; $x < count($dataAll);$x++)
        {
            $nopesanan .= $dataAll[$x]['KODEPESANAN'];
            if(($x % 49 == 0 && $x != 0) || $x == count($dataAll)-1)
            {
                //GET ORDER DETAIL
                $parameter = "&order_sn_list=".$nopesanan."&response_optional_fields=total_amount,item_list,buyer_username,recipient_address,shipping_carrier,payment_method,note,package_list,buyer_cancel_reason";
                // echo $tglTemp." - ".$tgl_ak." 23:59:59"."<br>";
                // echo "&order_status=COMPLETED&time_range_field=create_time&time_from=".$tglAw."&time_to=".$tglAk."&page_size=10"."<br>";
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('endpoint' => 'order/get_order_detail','parameter' => $parameter),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                if($ret['error'] != "")
                {
                    echo $ret['error']." : ".$ret['message'];
                    $statusok = false;
                }
                else
                {
                     for($y = 0 ; $y < count($ret['response']['order_list']); $y++)
                     {
                        $dataDetail = $ret['response']['order_list'][$y];

                        $CI->db->where("KODEPENJUALANMARKETPLACE",$dataDetail['order_sn'])
                        ->where('MARKETPLACE',"SHOPEE")
                         ->updateRaw("TPENJUALANMARKETPLACE", array(
                            'STATUSMARKETPLACE'          => $dataDetail['order_status'],
                            'STATUS'                     => $this->getStatus($dataDetail['order_status'])['state'],
                        ));   
                        
                        //JAGA2x
                        if($dataDetail['order_status'] == "READY_TO_SHIP")
                        {
                           $CI->db->where("KODEPENJUALANMARKETPLACE",$dataDetail['order_sn'])
                            ->where('MARKETPLACE',"SHOPEE")
                             ->updateRaw("TPENJUALANMARKETPLACE", array(
                                'STATUSMARKETPLACE'          => 'PROCESSED',
                                'STATUS'                     => $this->getStatus('PROCESSED')['state'],
                            ));   
                        }
                        else
                        {
                            $CI->db->where("KODEPENJUALANMARKETPLACE",$dataDetail['order_sn'])
                            ->where('MARKETPLACE',"SHOPEE")
                             ->updateRaw("TPENJUALANMARKETPLACE", array(
                                'STATUSMARKETPLACE'          => $dataDetail['order_status'],
                                'STATUS'                     => $this->getStatus($dataDetail['order_status'])['state'],
                            ));   
                        }
                     }
                }
                $nopesanan = "";
            }
            else
            {
                $nopesanan .= "%2C";
            }
            
        }
        
        //CEK LOKASI PICKUP
        $lokasi = "0";
        $parameter="";
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'logistics/get_address_list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataAddress = $ret['response']['address_list'];
        }
        
	    $tglStokMulai = $this->model_master_config->getConfigMarketplace('SHOPEE','TGLSTOKMULAI');
	    
        for($f = 0 ; $f < count($dataAll) ; $f++)
        {
            //DROP OFF
            if($dataAll[$f]['ADDRESS'] == "")
            {
                for($x = 0 ; $x < count($dataAddress);$x++)
                {
                    
                    $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASISHOPEE = ".$dataAddress[$x]['address_id']." AND GROUPLOKASI like '%MARKETPLACE%'";
                    $pickup = false;
                    // $default = false;
                    
                    for($y = 0 ; $y < count($dataAddress[$x]['address_type']);$y++)
                    {
                        if($dataAddress[$x]['address_type'][$y] == "PICKUP_ADDRESS")
                        {
                            $pickup = true;
                        }
                        // else if($dataAddress[$x]['address_type'][$y] == "DEFAULT_ADDRESS")
                        // {
                        //     $default = true;
                        // }
                    }
                    
                    
                
                    if($pickup)
                    {
                        $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
                    }
                }
            }
            //PICKUP
            else
            {
                 $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASISHOPEE = ".$dataAll[$f]['ADDRESS']." AND GROUPLOKASI like '%MARKETPLACE%'";
                 $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
            }
            
            //INSERT KARTUSTOK
            if($this->getStatus($dataAll[$f]['STATUSMARKETPLACE'])['state'] != 1)
            {
                $this->insertKartuStokPesanan($dataAll[$f]['KODEPESANAN'],$dataAll[$f]['TGLTRANS'],$tglStokMulai,$lokasi);
            }
        }
        //CEK LOKASI PICKUP
        
        echo(json_encode($data));
        
	}
	
	public function setLacak(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
	    $nopesanan = $this->input->post('kode')??"";
		$nopackaging = $this->input->post('kodepackaging')??"";
		
	    //GET RESI
        $parameter = "&order_sn=".$nopesanan."&package_number=".$nopackaging;
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'logistics/get_tracking_info','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
            $statusok = false;
        }
        else
        {
            $data = $ret['response']['tracking_info'];
             
            for($x = 0 ; $x < count($data) ; $x++)
            {
                $data[$x]['update_time'] = date("Y-m-d H:i:s", $data[$x]['update_time']);
            }
            
            echo(json_encode($data));
        }
	}

	public function print(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
	    $dataPesanan = json_decode($this->input->post('dataNoPesanan'),true)??"";
	  
	    $CI->load->library('Pdf_merger'); 
        
        $a++;
		foreach($dataPesanan as $item)
		{
		    if (file_exists(FCPATH."assets/label/waybill_".$item['KODEPESANAN']."_compressed.pdf")) {
               $files[$a] = FCPATH."assets/label/waybill_".$item['KODEPESANAN']."_compressed.pdf";
               $data['code'] = "Done";
            } else {
               $dataRePrint = $this->reprint([$item]);
               if($dataRePrint['success'])
               {
                $files[$a] = FCPATH."assets/label/waybill_".$dataRePrint['KODEPESANAN']."_compressed.pdf";
                 $data['code'] = "Reprint";
               }
               else
               {
                   $data['success'] = false;
                   $data['code'] = "Failed Reprint";
                   $data['msg'] = "Lakukan Cetak Ulang";
                   die(json_encode($data));
               }
            }
            $a++;
		}
		
          
        foreach ($files as $file) {
            $pageCount = $this->pdf_merger->setSourceFile($file);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $tpl = $this->pdf_merger->importPage($pageNo);
                $this->pdf_merger->AddPage('P',[100, 150]);
                $this->pdf_merger->useTemplate($tpl);
            }
        }
    
        $output_file = "assets/label/waybill_merge.pdf";
        $this->pdf_merger->Output('F', $output_file); // Simpan ke file
        
        // Kembalikan URL hasil merge sebagai JSON
        $data['merge_url'] = base_url($output_file);
        $data['success'] = true;
        
        if(count($dataPesanan) == 1)
        {
            $data['msg'] = "Pesanan #".$dataPesanan[0]['KODEPESANAN']." Berhasil Dicetak"; 
        }
        else
        { 
            $data['msg'] = "Pesanan Berhasil Dicetak";
        }
    
        
        // $data['merge_url'] = $this->config->item('base_url')."/assets/label/merged.pdf";
        echo(json_encode($data));

	}

	public function reprint($dataPesanan){
        
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
	   // $dataPesanan = json_decode($this->input->post('dataNoPesanan'),true)??"";
	  
	    $CI->load->library('Pdf_merger'); 

		$invoice = [];
		$kurir = [];
		$files = [];
		foreach($dataPesanan as $item)
		{
		    $ada = false;
		    for($x = 0 ; $x < count($kurir) ; $x++)
		    {
		        if($kurir[$x] == $item["KURIR"])
		        {
		            $ada = true;
		        }
		    }
		    
		    if(!$ada)
		    {
		       array_push($kurir,$item["KURIR"]) ;
		    }
		    $index = count($invoice);
            $invoice[$index]['order_sn'] = $item["KODEPESANAN"];
            $invoice[$index]['package_number'] = $item["KODEPACKAGING"];
            $invoice[$index]['kurir'] = $item["KURIR"];
            $invoice[$index]['tracking_number'] = $item["RESI"];
            $invoice[$index]['shipping_document_type'] = "";
		}
		
		
		$invoiceKirim = [];
		for($a = 0 ; $a < count($kurir); $a++)
		{
		    $invoiceKirim = [];
    		for($f = 0 ; $f < count($invoice);$f++){
    		    if($kurir[$a] == $invoice[$f]['kurir'])
    		    {
    		        array_push($invoiceKirim, $invoice[$f]);
    		    }
    		}
    		
        	for($f = 0 ; $f < count($invoiceKirim);$f++){	    
                if(($f % 49 == 0 && $f != 0) || $f == count($invoiceKirim)-1)
                {
            		$parameter = [];
            		$parameter['order_list'] = $invoiceKirim;
            		
            		//GET RESI
                    $curl = curl_init();
                    
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>  array(
                      'endpoint' => 'logistics/get_shipping_document_parameter',
                      'parameter' => json_encode($parameter)),
                      CURLOPT_HTTPHEADER => array(
                        'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                      ),
                    ));
                      
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $ret =  json_decode($response,true);
    		        $data = [];
                    if($ret['error'] != "")
                    {
                        $data['success'] = false;
                        $data['msg'] =   "1 ".$ret['error']." : ".$ret['message'];
                        $data['ret'] = $ret;
                    }
                    else
                    {
                        
                        $dataDocumentType = $ret['response']['result_list'];
                    
                        $indexCetak = 0;
                        foreach($parameter['order_list'] as $item)
                    	{
                            foreach($dataDocumentType as $itemDocumentType)
                            {
                        	    if($item['order_sn'] == $itemDocumentType['order_sn'])
                        	    {
                        	        $parameter['order_list'][$indexCetak]['shipping_document_type'] =  $itemDocumentType['suggest_shipping_document_type']??"NORMAL_AIR_WAYBILL";
                        	        $indexCetak++;
                        	    }
                            }
                    	}
                    	
                    	//BUAT LABEL PESANAN
                    	
                        $curl = curl_init();
                        
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS =>  array(
                          'endpoint' => 'logistics/create_shipping_document',
                          'parameter' => json_encode($parameter)),
                          CURLOPT_HTTPHEADER => array(
                            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                          ),
                        ));
                          
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $ret =  json_decode($response,true);
    		            $data = [];
                        if($ret['error'] != "")
                        {
                            $data['success'] = false;
                            $data['msg'] =  "2 ".$ret['error']." : ".$ret['message'];
                            $data['ret'] = $ret;
                        }
                        else
                        {
                
                            //DAPATKAN HASIL LABEL PESANAN
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                              CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => '',
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 30,
                              CURLOPT_FOLLOWLOCATION => true,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => 'POST',
                              CURLOPT_POSTFIELDS =>  array(
                              'endpoint' => 'logistics/get_shipping_document_result',
                              'parameter' => json_encode($parameter)),
                              CURLOPT_HTTPHEADER => array(
                                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                              ),
                            ));
                                  
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $ret =  json_decode($response,true);
                                
                            if($ret['error'] != "")
                            {
                                $data['success'] = false;
                                $data['msg'] =   "3 ".$ret['error']." : ".$ret['message'];
                                $data['ret'] = $ret;
                            }
                            else
                            {
                                $dataDocumentDownload = $ret['response']['result_list'];
                                
                                $invoiceKirim = [];
                                foreach($dataDocumentDownload as $itemDocumentDownload)
                                {
                                    $index = count($invoiceKirim);
                                		$invoiceKirim[$index]['order_sn'] = $itemDocumentDownload['order_sn'];
                                		$invoiceKirim[$index]['package_number'] = $itemDocumentDownload['package_number'];
                                }
                                $parameter = [];
                                $parameter['order_list'] = $invoiceKirim;
                                	//DAPATKAN HASIL LABEL PESANAN
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                  CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                                  CURLOPT_RETURNTRANSFER => true,
                                  CURLOPT_ENCODING => '',
                                  CURLOPT_MAXREDIRS => 10,
                                  CURLOPT_TIMEOUT => 30,
                                  CURLOPT_FOLLOWLOCATION => true,
                                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                  CURLOPT_CUSTOMREQUEST => 'POST',
                                  CURLOPT_POSTFIELDS =>  array(
                                  'endpoint' => 'logistics/download_shipping_document',
                                  'parameter' => json_encode($parameter)),
                                  CURLOPT_HTTPHEADER => array(
                                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                                  ),
                                ));
                                  
                                $response = curl_exec($curl);
                                $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                $content_type = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
                                
                                curl_close($curl);
                                $ret =  json_decode($response,true);
                                if($ret['error'] != "")
                                {
                                    $data['success'] = false;
                                    $data['msg'] =   "4 ".$ret['error']." : ".$ret['message'];
                                    $data['ret'] = $ret;
                                }
                                else
                                {
                                    // Save the file if request is successful
                                    if ($http_code == 200) {
                                        file_put_contents("assets/label/waybill_".$invoice[0]['order_sn'].".pdf", $response);
                                        
                                        $input = FCPATH . "assets/label/waybill_".$invoice[0]['order_sn'].".pdf";
                                        $output = FCPATH . "assets/label/waybill_".$invoice[0]['order_sn']."_compressed.pdf";
                                        
                                        $cmd = "gs -sDEVICE=pdfwrite \
                                              -dDEVICEWIDTHPOINTS=283 \
                                              -dDEVICEHEIGHTPOINTS=425 \
                                              -dPDFFitPage \
                                              -dNOPAUSE -dQUIET -dBATCH \ -sOutputFile='$output' '$input'";
                                        
                                        exec($cmd, $outputLines, $status);
                                        $data['success'] = true;
                                        $data['KODEPESANAN'] = $invoice[0]['order_sn'];
                                    } else {
                                        $data['success'] = false;
                                        $data['msg'] =  "Failed to download file. HTTP Status: $http_code\n";;
                                    }
                                }
                            }
                        }
                    
                    }
                          
                    $invoiceKirim = [];
                }
        	}
    	}
        
        return $data;

	}
	
	public function printPDF(){
	   
	   $CI =& get_instance();	
	   $CI->load->library('Pdf_merger'); 
	   
	    $input = FCPATH . 'assets/label/waybill.pdf';
        $output = FCPATH . 'assets/label/waybill_compressed.pdf';
        
        $cmd = "gs -sDEVICE=pdfwrite \
               -dDEVICEWIDTHPOINTS=283 \
               -dDEVICEHEIGHTPOINTS=425 \
               -dPDFFitPage \
               -dNOPAUSE -dQUIET -dBATCH \ -sOutputFile='$output' '$input'";
        
        exec($cmd, $outputLines, $status);
        
        if ($status === 0) {
    	   $files = [
                'assets/label/waybill_compressed.pdf',
                'assets/label/waybill_compressed.pdf',
            ];
    
            foreach ($files as $file) {
                $pageCount = $this->pdf_merger->setSourceFile($file);
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $tpl = $this->pdf_merger->importPage($pageNo);
                    $this->pdf_merger->AddPage('P',[100, 100]);
                    $this->pdf_merger->useTemplate($tpl);
                }
            }
    
            $output_file = "assets/label/waybill_merge.pdf";
            $this->pdf_merger->Output('F', $output_file); // Simpan ke file
        
            // Kembalikan URL hasil merge sebagai JSON
            echo json_encode(['pdf_url' => base_url('assets/label/waybill_merge.pdf')]);
        } else {
            echo "Gagal convert PDF. Cek Ghostscript terinstall atau tidak.";
        }
       
	}
	
	public function hapus(){
	    $CI =& get_instance();
		$this->output->set_content_type('application/json');
		$nopesanan = $this->input->post('kode')??"";
		$alasan = $this->input->post('alasan')??"";
		$dataitem = json_decode($this->input->post('dataItem'),true)??"";
		
		$parameter = [];
		$parameter['order_sn'] = $nopesanan;
		$parameter['cancel_reason'] = $alasan;
		$parameter['item_list'] = [];
		foreach($dataitem as $item)
		{
		    $parameter['item_list'][count($parameter['item_list'])] = array(
		      "item_id"  => $item['ITEMID'],
		      "model_id"  => $item['MODELID']
		    );
		}
		//HAPUS PESANAN
		
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => 'order/cancel_order',
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
           //GET ORDER DETAIL
           $parameter = "&order_sn_list=".$nopesanan."&response_optional_fields=total_amount,item_list,buyer_username,recipient_address,shipping_carrier,payment_method,note,package_list,buyer_cancel_reason";
           // echo $tglTemp." - ".$tgl_ak." 23:59:59"."<br>";
           // echo "&order_status=COMPLETED&time_range_field=create_time&time_from=".$tglAw."&time_to=".$tglAk."&page_size=10"."<br>";
           
           $curl = curl_init();
           
           curl_setopt_array($curl, array(
             CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 30,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'POST',
             CURLOPT_POSTFIELDS => array('endpoint' => 'order/get_order_detail','parameter' => $parameter),
             CURLOPT_HTTPHEADER => array(
               'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
             ),
           ));
           
           $response = curl_exec($curl);
           curl_close($curl);
           $ret =  json_decode($response,true);
           if($ret['error'] != "")
           {
               echo $ret['error']." : ".$ret['message'];
               $statusok = false;
           }
           else
           {
                for($y = 0 ; $y < count($ret['response']['order_list']); $y++)
                {
                   $dataDetail = $ret['response']['order_list'][$y];
                   $data;
                   //DAPATKAN RESI
                   $data['KODEPENJUALANMARKETPLACE']   = $dataDetail['order_sn'];
                   $data['TOTALBAYAR']                 = $dataDetail['total_amount'];
                   $data['STATUSMARKETPLACE']          = $dataDetail['order_status'];
                   $data['NAME']                       = $dataDetail['recipient_address']['name'];
                   $data['TELP']                       = $dataDetail['recipient_address']['phone'];
                   $data['ALAMAT']                     = $dataDetail['recipient_address']['full_address'];
                   $data['KOTA']                       = $dataDetail['recipient_address']['city'];
                   $data['STATUS']                     = $this->getStatus($dataDetail['order_status'])['state'];
                   $data['CATATANPENGEMBALIAN']        = $dataDetail['buyer_cancel_reason'];
             
                   $CI->db->where("KODEPENJUALANMARKETPLACE",$data['KODEPENJUALANMARKETPLACE'])
                   ->where('MARKETPLACE',"SHOPEE")
                    ->updateRaw("TPENJUALANMARKETPLACE", array(
                       'NAME'                       => $data['NAME'],  
                       'TELP'                       => $data['TELP'],  
                       'ALAMAT'                     => $data['ALAMAT'],
                       'KOTA'                       => $data['KOTA'],  
                       'TOTALBAYAR'                 => $data['TOTALBAYAR'],
                       'STATUSMARKETPLACE'          => $data['STATUSMARKETPLACE'],
                       'STATUS'                     => $data['STATUS'],
                       'CATATANPENGEMBALIAN'        => $data['CATATANPENGEMBALIAN'],
                ));    
                }
           }
        
            $data['success'] = true;
            $data['msg'] = "Pesanan #".$nopesanan." Berhasil Dibatalkan";
            echo(json_encode($data));
            
        }

	}
	
	public function getStatus($orderStatus){
	    if($orderStatus == "UNPAID")
	    {
	        return [
	            "status" => "Belum Bayar",
	            "state"  => 1,
	         ];
	    }
	    else if($orderStatus == "READY_TO_SHIP")
	    {
	         return [
	            "status" => "Siap Dikirim",
	            "state"  => 1,
	         ];
	    }
	    else if($orderStatus == "RETRY_SHIP")
	    {
	        return [
	            "status" => "Dikirim Ulang",
	            "state"  => 2,
	         ];
	    }
	    else if($orderStatus == "PROCESSED")
	    {
	        return [
	            "status" => "Diproses",
	            "state"  => 1,
	        ];
	    }
	    else if($orderStatus == "SHIPPED")
	    {
	        return [
	            "status" => "Dalam Pengiriman",
	            "state"  => 2,
	         ];
	    }
	    else if($orderStatus == "TO_CONFIRM_RECEIVE")
	    {
	         return [
	            "status" => "Telah Dikirim",
	            "state"  => 2,
	         ];
	    }
	    else if($orderStatus == "COMPLETED")
	    {
	        return [
	            "status" => "Selesai",
	            "state"  => 3,
	         ];
	    }
	    else if($orderStatus == "IN_CANCEL")
	    {
	        return [
	            "status" => "Dibatalkan Penjual",
	            "state"  => 1,
	         ];
	    }
	    else if($orderStatus == "CANCELLED")
	    {
	        return [
	            "status" => "Pembatalan",
	            "state"  => 3,
	         ];
	    }
	    else if($orderStatus == "TO_RETURN")
	    {
	        return [
	            "status" => "Pengembalian",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "TO_RETURN|REQUESTED")
	    {
	        return [
	            "status" => "Pengembalian<br>Pending",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "TO_RETURN|PROCESSING")
	    {
	        return [
	            "status" => "Pengembalian<br>Diproses",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "TO_RETURN|JUDGING")
	    {
	        return [
	            "status" => "Pengembalian<br>Dalam Sengketa",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "TO_RETURN|SELLER_DISPUTE")
	    {
	        return [
	            "status" => "Pengembalian<br>Dalam Sengketa",
	            "state"  => 4,
	         ];
	    }
	    
	    return $orderStatus;
	}
	
	public function getAlasanKembali($alasan){
	    if($alasan == "WRONG_ITEM")
	    {
	        $alasan = "Barang yang dikirim salah (salah ukuran, variasi, dll)";
	    }
	    else if($alasan == "CHANGE_MIND")
	    {
	        $alasan = "Ingin kembalikan barang sesuai kondisi awal";
	    }
	    else if($alasan == "NONE")
	    {
	       // $alasan = "Ingin kembalikan barang sesuai kondisi awal";
	    }
	    else if($alasan == "NOT_RECEIPT")
	    {
	        $alasan = "Semua barang tidak sampai";
	    }
	    else if($alasan == "ITEM_DAMAGED")
	    {
	       // $alasan = "Barang Rusak";
	    }
	    else if($alasan == "DIFFERENT_DESCRIPTION")
	    {
	        $alasan = "Barang berbeda dengan deskripsi/foto";
	    }
	    else if($alasan == "MUTUAL_AGREE")
	    {
	       // $alasan = "Ingin kembalikan barang sesuai kondisi awal";
	    }
	    else if($alasan == "OTHER")
	    {
	       // $alasan = "Ingin kembalikan barang sesuai kondisi awal";
	    }
	    else if($alasan == "ITEM_MISSING")
	    {
	        $alasan = "Barang sampai namun tidak lengkap";
	    }
	    else if($alasan == "EXPECTATION_FAILED")
	    {
	       // $alasan = "Ingin kembalikan barang sesuai kondisi awal";
	    }
	    else if($alasan == "ITEM_FAKE")
	    {
	        $alasan = "Barang palsu";
	    }
	    else if($alasan == "PHYSICAL_DMG")
	    {
	        $alasan = "Barang rusak";
	    }
	    else if($alasan == "FUNCTIONAL_DMG")
	    {
	        $alasan = "Barang tidak berfungsi/tidak bisa dipakai";
	    }
	    else if($alasan == "SLIGHT_SCRATCH_DENTS")
	    {
	        $alasan = "Barang tidak sempurna (Cacat)";
	    }
	    
	    return $alasan;
	}
	
	public function getAlasanDispute($id){
	    
        if($id == 1){
            $message = "I would like to reject the non-receipt claim";
    	}
        if($id == 2){
            $message = "I would like to reject the return request";
    	}
        if($id == 3){
            $message = "I agree with the return request, but I did not receive the product(s) which was/were supposed to be";
    	}
        if($id == 4){
            $message = "未收到退貨";
    	}
        if($id == 5){
            $message = "商品毀損/瑕疵";
    	}
        if($id == 6){
            $message = "商品缺件/不符";
    	}
        if($id == 7){
            $message = "非鑑賞期商品";
    	}
        if($id == 8){
            $message = "其他";
    	}
        if($id == 9){
            $message = "I have shipped the item(s) and have proof of shipment";
    	}
        if($id == 10){
            $message = "I shipped the correct item(s) as buyer ordered";
    	}
        if($id == 11){
            $message = "I shipped the item(s) in good working condition";
    	}
        if($id == 12){
            $message = "I agreed with the return request, but I have not received the item(s) that was/were supposed to be returned";
    	}
        if($id == 13){
            $message = "I agreed with the return request, but I received wrong/damaged item(s) from buyer";
    	}
        if($id == 41){
            $message = "I have shipped the item(s) and have proof of shipment";
    	}
        if($id == 42){
            $message = "I shipped the correct item(s) as buyer ordered";
    	}
        if($id == 43){
            $message = "I shipped the item(s) in good working condition";
    	}
        if($id == 44){
            $message = "Unable to come to an agreement with buyer";
    	}
        if($id == 45){
            $message = "Products are not in the appreciation period";
    	}
        if($id == 46){
            $message = "Tidak menerima pengembalian barang";
    	}
        if($id == 47){
            $message = "Menerima pengembalian barang dengan kerusakan fisik";
    	}
        if($id == 48){
            $message = "Menerima pengembalian barang yang tidak lengkap (jumlah / aksesoris hilang)";
    	}
        if($id == 49){
            $message = "Menerima pengembalian barang yang salah";
    	}
        if($id == 50){
            $message = "Menerima pengembalian barang, klaim pembeli salah";
    	}
        if($id == 51){
            $message = "Unable to come to an agreement with seller";
    	}
        if($id == 53){
            $message = "Buyer’s claim is incorrect";
    	}
        if($id == 54){
            $message = "Buyer has been refunded wrong amount";
    	}
        if($id == 55){
            $message = "Buyer claim is correct, but I have other concerns";
    	}
        if($id == 56){
            $message = "Pengembalian barang telah diterima, tetapi barang telah digunakan";
    	}
        if($id == 81){
            $message = "Tidak menerima pengembalian barang";
    	}
        if($id == 82){
            $message = "Menerima pengembalian barang dengan kerusakan fisik";
    	}
        if($id == 83){
            $message = "Menerima pengembalian barang yang tidak lengkap (jumlah / aksesoris hilang)";
    	}
        if($id == 84){
            $message = "Menerima pengembalian barang yang salah";
    	}
        if($id == 85){
            $message = "Products are not in the appreciation period";
    	}
        if($id == 86){
            $message = "Menerima pengembalian barang, klaim pembeli salah";
    	}
        if($id == 87){
            $message = "The product(s) returned is excluded from buyer's statutory right of withdrawal";
    	}
        if($id == 88){
            $message = "Buyer was unresponsive in Seller Arrange Return";
    	}
        if($id == 89){
            $message = "Pengembalian barang telah diterima, tetapi barang telah digunakan";
    	}
	    
	    return $message;
	}
	
	public function insertKartuStokPesanan($kodetrans,$tgltrans,$tglStokMulai,$lokasi){
	    
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
        
	    $tgltransDate = new DateTime($tgltrans);
        $tglStokMulaiDate = new DateTime($tglStokMulai);
       
        $labelKartuStok = "KARTUSTOKTEMP";
	    if($tgltransDate >= $tglStokMulaiDate)
	    {
	        $labelKartuStok = "KARTUSTOK";
	    }
	    
        $sqlStok = "SELECT count(IDTRANS) as ADA FROM $labelKartuStok WHERE KODETRANS = '".$kodetrans."'";
        $ada = $CI->db->query($sqlStok)->row()->ADA;
        if($ada == 0)
        {
            $sqlPesanan = "SELECT * FROM TPENJUALANMARKETPLACE WHERE MARKETPLACE = 'SHOPEE' and KODEPENJUALANMARKETPLACE = '".$kodetrans."'";
            $resultPesanan = $CI->db->query($sqlPesanan)->row();
            
            $produkData = explode("|",$resultPesanan->SKUPRODUK);
            foreach($produkData as $item)
            {
                //GET ID BARANG
                $sql = "SELECT MBARANG.IDPERUSAHAAN, MBARANG.IDBARANG, ifnull(MHARGA.HARGAKONSINYASI,0) as HARGA
                            FROM MBARANG 
                            INNER JOIN MHARGA on MBARANG.IDBARANG = MHARGA.IDBARANG
                            INNER JOIN MCUSTOMER on MCUSTOMER.IDCUSTOMER = MHARGA.IDCUSTOMER
                            WHERE MBARANG.SKUSHOPEE = '".explode("*",$item)[1]."' and MCUSTOMER.NAMACUSTOMER = 'SHOPEE'";
                $dataBarang = $CI->db->query($sql)->row();
                
                
                $param = array(
                	"IDPERUSAHAAN"  => $dataBarang->IDPERUSAHAAN??"2",
                	"IDLOKASI"      => $lokasi,
                	"MODUL"         => 'PENJUALAN',
                	"IDTRANS"       => $resultPesanan->IDPENJUALANMARKETPLACE,
                	"KODETRANS"     => $resultPesanan->KODEPENJUALANMARKETPLACE,
                	"IDBARANG"      => $dataBarang->IDBARANG??"0",
                	"KONVERSI1"     => 1,
                	"KONVERSI2"     => 1,
                	"TGLTRANS"      => $resultPesanan->TGLTRANS,
                	"JENISTRANS"    => 'JUAL SHOPEE',
                	"KETERANGAN"    => 'PENJUALAN SHOPEE KE '.$resultPesanan->USERNAME,
                	"MK"            => 'K',
                	"JML"           => explode("*",$item)[0],
                	"TOTALHARGA"    => (explode("*",$item)[0] * ($dataBarang->HARGA??"0")),
                	"STATUS"        => '1',
                );
                $exe = $CI->db->insert($labelKartuStok,$param);
                
            }
        }
        
	}
	
	public function insertKartuStokRetur($kodetrans,$tgltrans,$tglStokMulai,$lokasi){
	    
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
        
	    $tgltransDate = new DateTime($tgltrans);
        $tglStokMulaiDate = new DateTime($tglStokMulai);
	    
	    $labelKartuStok = "KARTUSTOKTEMP";
	    if($tgltransDate >= $tglStokMulaiDate)
	    {
	        $labelKartuStok = "KARTUSTOK";
	    }
	    
        $sqlStok = "SELECT count(IDTRANS) as ADA FROM $labelKartuStok WHERE KODETRANS = '".$kodetrans."'";
        $ada = $CI->db->query($sqlStok)->row()->ADA;
        if($ada == 0)
        {
            $sqlPesanan = "SELECT * FROM TPENJUALANMARKETPLACE WHERE MARKETPLACE = 'SHOPEE' and KODEPENGEMBALIANMARKETPLACE = '".$kodetrans."'";
            $resultPesanan = $CI->db->query($sqlPesanan)->row();
            
            $produkDataPengembalian = explode("|",$resultPesanan->SKUPRODUKPENGEMBALIAN);
            $produkData = explode("|",$resultPesanan->SKUPRODUK);
            $produkDataOld = explode("|",$resultPesanan->SKUPRODUKOLD);
            $dataProduk = [];
            $indexProduk = 0;
            foreach($produkData as $item)
            {
                //GET NAMA BARANG
                $sql = "SELECT NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKUSHOPEE as SKU
                            FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$item)[1]."'";
                $dataBarang = $CI->db->query($sql)->row();
                $dataProduk[$indexProduk]->BARANG  = explode(" | ",$dataBarang->NAMABARANG)[0];
                if(count(explode(" | ",$dataBarang->NAMABARANG)) > 1)
                {
                    $dataProduk[$indexProduk]->BARANG .= "<br><i>".$dataBarang->WARNA.", ".$dataBarang->SIZE."</i>";
                }
                $dataProduk[$indexProduk]->SATUAN = $dataBarang->SATUAN;
                $dataProduk[$indexProduk]->KATEGORI = $dataBarang->KATEGORI;
                $dataProduk[$indexProduk]->WARNA = $dataBarang->WARNA;
                $dataProduk[$indexProduk]->SIZE = $dataBarang->SIZE;
                $dataProduk[$indexProduk]->SKU = $dataBarang->SKU;
                $dataProduk[$indexProduk]->BARANGOLD = $dataProduk[$indexProduk]->BARANG;
                $dataProduk[$indexProduk]->WARNAOLD = $dataProduk[$indexProduk]->WARNA;
                $dataProduk[$indexProduk]->SIZEOLD = $dataProduk[$indexProduk]->SIZE;
                $dataProduk[$indexProduk]->SKUOLD = $dataProduk[$indexProduk]->SKU;
                $indexProduk++;
            }
            
            if($resultPesanan->SKUPRODUKOLD != "")
            {
                
                $indexProduk = 0;
                foreach($produkDataOld as $item)
                {
                    //GET NAMA BARANG
                    $sql = "SELECT NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKUSHOPEE as SKU
                                FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$item)[1]."'";
                    $dataBarang = $CI->db->query($sql)->row();
                    $dataProduk[$indexProduk]->BARANGOLD  = explode(" | ",$dataBarang->NAMABARANG)[0];
                    if(count(explode(" | ",$dataBarang->NAMABARANG)) > 1)
                    {
                        $dataProduk[$indexProduk]->BARANGOLD .= "<br><i>".$dataBarang->WARNA.", ".$dataBarang->SIZE."</i>";
                    }
                    
                    $dataProduk[$indexProduk]->WARNAOLD = $dataBarang->WARNA;
                    $dataProduk[$indexProduk]->SIZEOLD = $dataBarang->SIZE;
                    $dataProduk[$indexProduk]->SKUOLD = $dataBarang->SKU;
                    $indexProduk++;
                }
            }
            
            if($resultPesanan->SKUPRODUKPENGEMBALIAN != "")
            {
                $indexPengganti;
                $produkDataKembali = explode("|",$resultPesanan->SKUPRODUKPENGEMBALIAN);

                for($t = 0 ; $t < count($produkDataKembali);$t++)
                {
                    for($s = 0 ; $s < count($produkDataOld);$s++)
                    {
                        if(explode("*",$produkDataKembali[$t])[1] == explode("*",$produkDataOld[$s])[1])
                        {
                           $indexPengganti = $s;
                           
                             //GET NAMA BARANG
                            $sql = "SELECT NAMABARANG, WARNA, SIZE, SKUSHOPEE as SKU
                                        FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$produkData[$indexPengganti])[1]."'";
                            $dataBarang = $CI->db->query($sql)->row();
                            
                            $sqlOld = "SELECT NAMABARANG, WARNA, SIZE, SKUSHOPEE as SKU
                                    FROM MBARANG WHERE SKUSHOPEE = '".explode("*",$produkDataOld[$indexPengganti])[1]."'";
                            $dataBarangOld = $CI->db->query($sqlOld)->row();
                            
                            $idBarang = "0";
                            if(count(explode(" | ",$dataBarang->NAMABARANG)) > 1)
                            {
                                
                                if($dataBarang->SIZE != $dataBarangOld->SIZE || $dataBarang->WARNA != $dataBarangOld->WARNA)
                                {
                                    
                                    //GET ID BARANG
                                    $sql = "SELECT MBARANG.IDPERUSAHAAN, MBARANG.IDBARANG, ifnull(MHARGA.HARGAKONSINYASI,0) as HARGA
                                                FROM MBARANG 
                                                INNER JOIN MHARGA on MBARANG.IDBARANG = MHARGA.IDBARANG
                                                INNER JOIN MCUSTOMER on MCUSTOMER.IDCUSTOMER = MHARGA.IDCUSTOMER
                                                WHERE MBARANG.SKUSHOPEE = '".$dataBarang->SKU."' and MCUSTOMER.NAMACUSTOMER = 'SHOPEE'";
                
                                    $dataBarangKembali = $CI->db->query($sql)->row();
                                    
                                    
                                    $param = array(
                                    	"IDPERUSAHAAN"  => $dataBarangKembali->IDPERUSAHAAN??"2",
                                    	"IDLOKASI"      => $lokasi,
                                    	"MODUL"         => 'RETUR JUAL',
                                    	"IDTRANS"       => $resultPesanan->IDPENJUALANMARKETPLACE,
                                    	"KODETRANS"     => $resultPesanan->KODEPENGEMBALIANMARKETPLACE,
                                    	"IDBARANG"      => $dataBarangKembali->IDBARANG??"0",
                                    	"KONVERSI1"     => 1,
                                    	"KONVERSI2"     => 1,
                                    	"TGLTRANS"      => $resultPesanan->TGLPENGEMBALIAN,
                                    	"JENISTRANS"    => 'RETUR JUAL SHOPEE',
                                    	"KETERANGAN"    => 'RETUR SHOPEE KE '.$resultPesanan->USERNAME,
                                    	"MK"            => 'M',
                                    	"JML"           => explode("*",$produkDataKembali[$t])[0],
                                    	"TOTALHARGA"    => (explode("*",$produkDataKembali[$t])[0] * ($dataBarangKembali->HARGA??"0")),
                                    	"STATUS"        => '1',
                                    );
                                    $exe = $CI->db->insert($labelKartuStok,$param);
                                    $idBarang = $dataBarangKembali->IDBARANG??"0";
                                }
                                else
                                {
                                    //GET ID BARANG
                                    $sql = "SELECT MBARANG.IDPERUSAHAAN, MBARANG.IDBARANG, ifnull(MHARGA.HARGAKONSINYASI,0) as HARGA
                                                FROM MBARANG 
                                                INNER JOIN MHARGA on MBARANG.IDBARANG = MHARGA.IDBARANG
                                                INNER JOIN MCUSTOMER on MCUSTOMER.IDCUSTOMER = MHARGA.IDCUSTOMER
                                                WHERE MBARANG.SKUSHOPEE = '".$dataBarangOld->SKU."' and MCUSTOMER.NAMACUSTOMER = 'SHOPEE'";
                
                                    $dataBarangKembali = $CI->db->query($sql)->row();
                                    
                                    
                                    $param = array(
                                    	"IDPERUSAHAAN"  => $dataBarangKembali->IDPERUSAHAAN,
                                    	"IDLOKASI"      => $lokasi,
                                    	"MODUL"         => 'RETUR JUAL',
                                    	"IDTRANS"       => $resultPesanan->IDPENJUALANMARKETPLACE,
                                    	"KODETRANS"     => $resultPesanan->KODEPENGEMBALIANMARKETPLACE,
                                    	"IDBARANG"      => $dataBarangKembali->IDBARANG,
                                    	"KONVERSI1"     => 1,
                                    	"KONVERSI2"     => 1,
                                    	"TGLTRANS"      => $resultPesanan->TGLPENGEMBALIAN,
                                    	"JENISTRANS"    => 'RETUR JUAL SHOPEE',
                                    	"KETERANGAN"    => 'RETUR SHOPEE KE '.$resultPesanan->USERNAME,
                                    	"MK"            => 'M',
                                    	"JML"           => explode("*",$produkDataKembali[$t])[0],
                                    	"TOTALHARGA"    => (explode("*",$produkDataKembali[$t])[0] * $dataBarangKembali->HARGA),
                                    	"STATUS"        => '1',
                                    );
                                    $exe = $CI->db->insert($labelKartuStok,$param);
                                    $idBarang = $dataBarangKembali->IDBARANG??"0";
                                }
                            }
                            
                            if($labelKartuStok == "KARTUSTOK")
                            {
                                $dataBarang = [];
                                $idlokasiset = $lokasi;
                                //SET STOK
                                $curl = curl_init();
                                $parameter = "";
                                
                                curl_setopt_array($curl, array(
                                  CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                                  CURLOPT_RETURNTRANSFER => true,
                                  CURLOPT_ENCODING => '',
                                  CURLOPT_MAXREDIRS => 10,
                                  CURLOPT_TIMEOUT => 30,
                                  CURLOPT_FOLLOWLOCATION => true,
                                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                  CURLOPT_CUSTOMREQUEST => 'POST',
                                  CURLOPT_POSTFIELDS => array('endpoint' => 'logistics/get_address_list','parameter' => $parameter),
                                  CURLOPT_HTTPHEADER => array(
                                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                                  ),
                                ));
                                
                                $response = curl_exec($curl);
                                curl_close($curl);
                                $ret =  json_decode($response,true);
                                $lokasi = 0;
                                $countSuccess = 0 ;
                                if($ret['error'] != "")
                                {
                                    echo $ret['error']." LOKASI : ".$ret['message'];
                                }
                                else
                                {
                                    $dataAddress = $ret['response']['address_list'];
                                    for($x = 0 ; $x < count($dataAddress);$x++)
                                    {
                                        $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASISHOPEE = ".$dataAddress[$x]['address_id']." AND GROUPLOKASI like '%MARKETPLACE%'";
                                        $pickup = false;
                                        for($y = 0 ; $y < count($dataAddress[$x]['address_type']);$y++)
                                        {
                                            if($dataAddress[$x]['address_type'][$y] == "PICKUP_ADDRESS")
                                            {
                                                $pickup = true;
                                            }
                                            // else if($dataAddress[$x]['address_type'][$y] == "DEFAULT_ADDRESS")
                                            // {
                                            //     $default = true;
                                            // }
                                        }
                                        
                                        if($pickup)
                                        {
                                            $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
                                        }
                                    }
                                    
                                    if($lokasi == $idlokasiset)
                                    {
                                        $modeList = [];
                                        $countBarang = 0;
                                        $whereBarang = " and IDBARANG in (";
                                        foreach($dataBarang as $itemBarang)
                                        {
                                    		$whereBarang .= $itemBarang;
                                    		if($countBarang < count($dataBarang)-1)
                                    		{
                                    		    $whereBarang .= ",";
                                    		}
                                    		$countBarang++;
                                        }
                                        
                                        $whereBarang .= ")";	
                                        
                                        $sql = "select IDBARANGSHOPEE, IDINDUKBARANGSHOPEE, IDBARANG
                                    				from MBARANG
                                    				where (1=1) $whereBarang
                                    				order by IDINDUKBARANGSHOPEE
                                    				";	
                                    		
                                    	$dataHeader = $this->db->query($sql)->result();
                                    		
                                         $idHeader = 0;
                                         $parameter = [];
                                    	 foreach($dataHeader as $itemHeader)
                                    	 {
                                    	     if($itemHeader->IDINDUKBARANGSHOPEE != $idHeader)
                                    	     {
                                    	         if(count($parameter) > 0)
                                    	         {
                                    	            $curl = curl_init();
                                                    curl_setopt_array($curl, array(
                                                      CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                                                      CURLOPT_RETURNTRANSFER => true,
                                                      CURLOPT_ENCODING => '',
                                                      CURLOPT_MAXREDIRS => 10,
                                                      CURLOPT_TIMEOUT => 30,
                                                      CURLOPT_FOLLOWLOCATION => true,
                                                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                      CURLOPT_CUSTOMREQUEST => 'POST',
                                                      CURLOPT_POSTFIELDS =>  array(
                                                      'endpoint' => 'product/update_stock',
                                                      'parameter' => json_encode($parameter)),
                                                      CURLOPT_HTTPHEADER => array(
                                                        'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                                                      ),
                                                    ));
                                                      
                                                    $response = curl_exec($curl);
                                                    curl_close($curl);
                                                    $ret =  json_decode($response,true);
                                                    
                                                    if($ret['error'] != "")
                                                    {
                                                        $data['success'] = false;
                                                        $data['msg'] =  $ret['error']." STOK : ".$ret['message'];
                                                        die(json_encode($data));
                                                        print_r($ret);
                                                    }
                                    	         }
                                    	         $idHeader = $itemHeader->IDINDUKBARANGSHOPEE;
                                    	         
                                    	         //UPDATE KE SHOPEENYA
                                                $parameter = [];
                                             	$parameter['item_id'] = (int)$itemHeader->IDINDUKBARANGSHOPEE;
                                             	$parameter['stock_list'] = [];
                                    	     }
                                    	     
                                             $result   = get_saldo_stok_new($_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],$itemHeader->IDBARANG, $idlokasiset, date('Y-m-d'));
                                             $saldoQty = $result->QTY??0;
                                            
                                            $modelId = 0;
                                            
                                            if($itemHeader->IDBARANGSHOPEE != $itemHeader->IDINDUKBARANGSHOPEE)
                                            {
                                                $modelId = $itemHeader->IDBARANGSHOPEE;
                                            }
                                            
                                             array_push($parameter['stock_list'],array(
                                                'model_id'      => (int)$modelId,
                                                'seller_stock'  => array(
                                                     array('stock' => (int)$saldoQty)
                                                ))
                                            );
                                    	}
                                    	
                                    	  
                                    	$curl = curl_init();
                                        curl_setopt_array($curl, array(
                                          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                                          CURLOPT_RETURNTRANSFER => true,
                                          CURLOPT_ENCODING => '',
                                          CURLOPT_MAXREDIRS => 10,
                                          CURLOPT_TIMEOUT => 30,
                                          CURLOPT_FOLLOWLOCATION => true,
                                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                          CURLOPT_CUSTOMREQUEST => 'POST',
                                          CURLOPT_POSTFIELDS =>  array(
                                          'endpoint' => 'product/update_stock',
                                          'parameter' => json_encode($parameter)),
                                          CURLOPT_HTTPHEADER => array(
                                            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                                          ),
                                        ));
                                          
                                        $response = curl_exec($curl);
                                        curl_close($curl);
                                        $ret =  json_decode($response,true);
                                        
                                        if($ret['error'] != "")
                                        {
                                            $data['success'] = false;
                                            $data['msg'] =  $ret['error']." STOK : ".$ret['message'];
                                            die(json_encode($data));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
	}
	
	public function init($tgl_aw,$tgl_ak,$jenis = 'create_time') {
	    
	    if($jenis == "update")
	    {
	        $jenis = "update_time";
	    }
	    
		$this->output->set_content_type('application/json');
        $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		
// 		$tgl_aw = "2025-06-01";
// 		$tgl_ak = "2025-06-30";
        
        $result = [];
        $resultTemp2 = [];
        $history = [];
        $finalData = [];
        $index=0;
        $indexTemp2 = 0;
        $newOrder = 0;
       
        $bigger = false;
        $cursor = "0";
        $statusok = true; 
        $tglTemp = $tgl_aw." 00:00:00"; 
        $dateAk = new DateTime($tgl_ak." 23:59:59");
        $tglcobaAW;
        $tglcobaAK;
        while(!$bigger && $statusok)
        {
            $dateAw = new DateTime($tglTemp);
            $dateTemp = new DateTime($tglTemp);
            $tglTempAdd = $dateTemp->modify('+14 days')->format('Y-m-d')." 23:59:59"; // Add 15 days
            $dateTemp = new DateTime($tglTempAdd);    
            if($dateTemp >= $dateAk)
            {
                $tglcobaAW = $tglTemp;
                $tglcobaAK = $tgl_ak." 23:59:59";
                
                $bigger = true;
                $tglAw = $dateAw->getTimestamp();
                $tglAk = $dateAk->getTimestamp();
            }
            else
            {
                $tglcobaAW = $tglTemp;
                $tglcobaAK = $tglTempAdd;
                
                $tglAw = $dateAw->getTimestamp();
                $tglAk = $dateTemp->getTimestamp();
            }
                 
            while($cursor != ""  && $statusok)
            {
                 $parameter = "&time_range_field=".$jenis."&time_from=".$tglAw."&time_to=".$tglAk."&page_size=100&cursor=".$cursor;
                //  echo $parameter."\n";
                 array_push($history,$parameter." ".$tglcobaAW." | ".$tglcobaAK);
                 $curl = curl_init();
                //GET ORDER LIST
                 curl_setopt_array($curl, array(
                   CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_ENCODING => '',
                   CURLOPT_MAXREDIRS => 10,
                   CURLOPT_TIMEOUT => 30,
                   CURLOPT_FOLLOWLOCATION => true,
                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                   CURLOPT_CUSTOMREQUEST => 'POST',
                   CURLOPT_POSTFIELDS => array('endpoint' => 'order/get_order_list','parameter' => $parameter),
                   CURLOPT_HTTPHEADER => array(
                     'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                   ),
                 ));
                 
                 $response = curl_exec($curl);
                 curl_close($curl);
                 $ret =  json_decode($response,true);
                 if($ret['error'] != "")
                 {
                     echo $ret['error']." : ".$ret['message'];
                     $statusok = false;
                 }
                 else
                 {
                     for($x = 0  ; $x < count($ret['response']['order_list']); $x++)
                     {
                         $resultTemp2[$indexTemp2]['KODEPESANAN'] = $ret['response']['order_list'][$x]['order_sn'];
                         $indexTemp2++;
                     }
                     $cursor = $ret['response']['next_cursor'];
                 }
                 
                 if($cursor == "")
                 {
                      for($x = count($resultTemp2)-1 ; $x >= 0; $x--)
                      {
                          $result[$index]['KODEPESANAN'] = $resultTemp2[$x]['KODEPESANAN'];
                          $index++;
                      }
                     $resultTemp2 = [];
                     $indexTemp2 = 0;
                 }
            }
            $cursor = "0";
            $statusok = true;
            
            $dateAddOne = new DateTime($tglTempAdd);
            $tglTempAddOne = $dateAddOne->modify('+1 days')->format('Y-m-d')." 00:00:00"; // Add 1 days
            $tglTemp = $tglTempAddOne;
        }
        
        $nopesanan = "";
        for($x = 0 ; $x < count($result);$x++)
        {
            $nopesanan .= $result[$x]['KODEPESANAN'];
            if(($x % 49 == 0 && $x != 0) || $x == count($result)-1)
            {
                //GET ORDER DETAIL
                $parameter = "&order_sn_list=".$nopesanan."&response_optional_fields=total_amount,item_list,buyer_username,recipient_address,shipping_carrier,payment_method,note,package_list,buyer_cancel_reason";
                // echo $tglTemp." - ".$tgl_ak." 23:59:59"."<br>";
                // echo "&order_status=COMPLETED&time_range_field=create_time&time_from=".$tglAw."&time_to=".$tglAk."&page_size=10"."<br>";
                
                $curl = curl_init();
            
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('endpoint' => 'order/get_order_detail','parameter' => $parameter),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                if($ret['error'] != "")
                {
                    echo $ret['error']." : ".$ret['message'];
                    $statusok = false;
                }
                else
                {
                     for($y = 0 ; $y < count($ret['response']['order_list']); $y++)
                     {
                        $dataDetail = $ret['response']['order_list'][$y];
                        $allsku = "";
                        $jml = 0;
                        
                        //URUTKAN BERDASARKAN NAMA BARANG
                        usort($dataDetail['item_list'], function($a, $b) {
                            return strcmp($a['item_name'], $b['item_name']);
                        });
                        
                        for($d = 0 ; $d < count($dataDetail['item_list']);$d++)
                        {
                            //KHUSUS SKU YANG KOSONG
                            if($dataDetail['item_list'][$d]['model_sku'] == "")
                            {
                                $sku = $dataDetail['item_list'][$d]['item_sku'];
                            }
                            else
                            {  
                                $sku = $dataDetail['item_list'][$d]['model_sku'];
                            }
                            
                            if(strpos(strtoupper($dataDetail['item_list'][$d]['item_name']),"BIRTHDAY CARD") !== false){
                                $sku = "LTWS";
                            }
                            else if(strpos(strtoupper($dataDetail['item_list'][$d]['item_name']),"NEWBORN CARD") !== false){
                                $sku = "CARD-BOX-OTHER";
                            }
                            $jml += $dataDetail['item_list'][$d]['model_quantity_purchased'];
                            $allsku .= $dataDetail['item_list'][$d]['model_quantity_purchased']."*".$sku."|";
                        }
                        
                        $allsku = substr($allsku, 0, -1);
                        
                        $catatanBeli = "<div style='width:250px; white-space: pre-wrap;      /* CSS3 */   
                                                                white-space: -moz-pre-wrap; /* Firefox */    
                                                                white-space: -pre-wrap;     /* Opera <7 */   
                                                                white-space: -o-pre-wrap;   /* Opera 7 */    
                                                                word-wrap: break-word;      /* IE */'>".$dataDetail['message_to_seller']."</div>";
                                                                
                        $catatanJual = "<div style='width:250px; white-space: pre-wrap;      /* CSS3 */   
                                                                white-space: -moz-pre-wrap; /* Firefox */    
                                                                white-space: -pre-wrap;     /* Opera <7 */   
                                                                white-space: -o-pre-wrap;   /* Opera 7 */    
                                                                word-wrap: break-word;      /* IE */'>".$dataDetail['message_to_seller']."</div>";
                        
                        $data;
                        //DAPATKAN RESI
                        $data['ALLBARANG']                      = $dataDetail['item_list'];
                        $data['IDPENJUALAN']                    = 0;
                        $data['MARKETPLACE']                    = "SHOPEE";
                        $data['KODEPENJUALANMARKETPLACE']       = $dataDetail['order_sn'];
                        $data['TGLTRANS']                       = date("Y-m-d H:i:s", $dataDetail['create_time']);
                        $data['USERNAME']                       = $dataDetail['buyer_username']??"-";
                        $data['NAME']                           = $dataDetail['recipient_address']['name'];
                        $data['TELP']                           = $dataDetail['recipient_address']['phone'];
                        $data['ALAMAT']                         = $dataDetail['recipient_address']['full_address'];
                        $data['KOTA']                           = $dataDetail['recipient_address']['city'];
                        $data['KURIR']                          = $dataDetail['shipping_carrier'];
                        $data['KODEPACKAGING']                  = $dataDetail['package_list'][0]['package_number'];
                        $data['RESI']                           = "";
                        $data['SKUPRODUK']                      = $allsku;
                        $data['SKUPRODUKOLD']                   = $allsku;
                        $data['MINTGLKIRIM']                    = date("Y-m-d H:i:s", $dataDetail['ship_by_date']);
                        $data['METODEBAYAR']                    = $dataDetail['payment_method'];
                        $data['TOTALBARANG']                    = $jml;
                        $data['TOTALBAYAR']                     = $dataDetail['total_amount'];
                        $data['STATUSMARKETPLACE']              = $dataDetail['order_status'];
                        $data['STATUS']                         = $this->getStatus($dataDetail['order_status'])['state'];
                        $data['CATATANPEMBELI']                 = $dataDetail['message_to_seller'];
                        $data['CATATANPENJUAL']                 = $dataDetail['note'];
                        $data['CATATANPENGEMBALIAN']            = $dataDetail['buyer_cancel_reason'];
                  
                        array_push($finalData,$data);     
                     }
                }
                $nopesanan = "";
            }
            else
            {
                $nopesanan .= "%2C";
            }
            
        }
        
        $parameter = [];
        $packaging = [];
        $indexPackaging = 0;
        
        for($f = 0 ; $f < count($finalData);$f++){
            $packaging[count($packaging)]['package_number'] = $finalData[$f]['KODEPACKAGING'];
            if(($f % 49 == 0 && $f != 0) || $f == count($finalData)-1)
            {
                //GET RESI
                $parameter['package_list'] =  $packaging;
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  array(
                  'endpoint' => 'logistics/get_mass_tracking_number',
                  'parameter' => json_encode($parameter)),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                if($ret['error'] != "")
                {
                    echo $ret['error']." : ".$ret['message'];
                    $statusok = false;
                }
                else
                {
                    for($x = 0 ;$x < count($ret['response']['success_list']); $x++)
                    {
                       $sql = "SELECT count(KODEPENJUALANMARKETPLACE) as ADA,ifnull(KODEPENGEMBALIANMARKETPLACE,'') as KODEPENGEMBALIANMARKETPLACE FROM TPENJUALANMARKETPLACE 
                                    WHERE MARKETPLACE = 'SHOPEE' 
                                    and KODEPENJUALANMARKETPLACE = '".$finalData[$indexPackaging]['KODEPENJUALANMARKETPLACE']."'";
                                
                        $dataPesananDB = $CI->db->query($sql)->row();
                        
                        $ada = $dataPesananDB->ADA;
                        $kodepengembalian = $dataPesananDB->KODEPENGEMBALIANMARKETPLACE;
                        
                        $finalData[$indexPackaging]['RESI'] = $ret['response']['success_list'][$x]['tracking_number'];
                        $finalData[$indexPackaging]['KODEPENGAMBILAN'] = $ret['response']['success_list'][$x]['pickup_code'];
                        
                        if($ada)
                        {
                            $CI->db->where("KODEPENJUALANMARKETPLACE",$finalData[$indexPackaging]['KODEPENJUALANMARKETPLACE'])
                            ->where('MARKETPLACE',"SHOPEE")
        		            ->updateRaw("TPENJUALANMARKETPLACE", array(
                                'USERNAME'                   => $finalData[$indexPackaging]['USERNAME']??"-",
                                'NAME'                       => $finalData[$indexPackaging]['NAME'],
                                'TELP'                       => $finalData[$indexPackaging]['TELP'],
                                'ALAMAT'                     => $finalData[$indexPackaging]['ALAMAT'],
                                'KOTA'                       => $finalData[$indexPackaging]['KOTA'],
                                'KURIR'                      => $finalData[$indexPackaging]['KURIR'],
                                'KODEPENGAMBILAN'            => $finalData[$indexPackaging]['KODEPENGAMBILAN'],
                                'KODEPACKAGING'              => $finalData[$indexPackaging]['KODEPACKAGING'],
                                'RESI'                       => $finalData[$indexPackaging]['RESI'],
                                'MINTGLKIRIM'                => $finalData[$indexPackaging]['MINTGLKIRIM'],
                                'METODEBAYAR'                => $finalData[$indexPackaging]['METODEBAYAR'],
                                'TOTALBARANG'                => $finalData[$indexPackaging]['TOTALBARANG'],
                                'TOTALBAYAR'                 => $finalData[$indexPackaging]['TOTALBAYAR'],
                                'STATUSMARKETPLACE'          => $finalData[$indexPackaging]['STATUSMARKETPLACE'],
                                'STATUS'                     => $finalData[$indexPackaging]['STATUS'],
                                'CATATANPEMBELI'             => $finalData[$indexPackaging]['CATATANPEMBELI'],
                                'CATATANPENJUAL'             => $finalData[$indexPackaging]['CATATANPENJUAL'],
                                'CATATANPENGEMBALIAN'        => $finalData[$indexPackaging]['CATATANPENGEMBALIAN'],
        		            ));
                        }
                        else
                        {
                            $detailBarang = $finalData[$indexPackaging]['ALLBARANG'];
                            
                            unset($finalData[$indexPackaging]['ALLBARANG']);
                            
                            $newOrder++;
                            $CI->db->insertRaw("TPENJUALANMARKETPLACE",$finalData[$indexPackaging]);
                            
                            $idtrans = $CI->db->insert_id();
                            $urutan = 0;
                            foreach($detailBarang as $itemBarang){
                                $urutan++;
                                
                               //KHUSUS SKU YANG KOSONG
                                if($itemBarang['model_sku'] == "")
                                {
                                    $sku = $itemBarang['item_sku'];
                                }
                                else
                                {  
                                    $sku = $itemBarang['model_sku'];
                                }
                            
                                if(strpos(strtoupper($itemBarang['item_name']),"BIRTHDAY CARD") !== false){
                                    $sku = "LTWS";
                                }
                                else if(strpos(strtoupper($itemBarang['item_name']),"NEWBORN CARD") !== false){
                                    $sku = "CARD-BOX-OTHER";
                                }
                            
                                $sqlBarang = "select idbarang from mbarang where SKUSHOPEE = '$sku'";
                                $queryBarang = $CI->db->query($sqlBarang)->row();
                                
                                $CI->db->insertRaw("TPENJUALANMARKETPLACEDTL",
                                array(
                                    'IDPENJUALANMARKETPLACE'    => $idtrans,
                                    'KODEPENJUALANMARKETPLACE'  => $finalData[$indexPackaging]['KODEPENJUALANMARKETPLACE'],
                                    'IDBARANG'                  => $queryBarang->IDBARANG??0,
                                    'MARKETPLACE'               => 'SHOPEE',
                                    'SKU'                       => $sku,
                                    'URUTAN'                    => $urutan,
                                    'JML'                       => $itemBarang['model_quantity_purchased'],
                                    'HARGA'                     => $itemBarang['model_discounted_price'],
                                    'TOTAL'                     => ($itemBarang['model_quantity_purchased'] * $itemBarang['model_discounted_price']),
            		            ));
                            }
                        }
                        
                        $indexPackaging++;
                    }
                }
                
                $packaging = [];
            }
        }
        
        //CEK LOKASI PICKUP
        $lokasi = "0";
        $parameter="";
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'logistics/get_address_list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataAddress = $ret['response']['address_list'];
            $data['rows'] = [];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASISHOPEE = ".$dataAddress[$x]['address_id']." AND GROUPLOKASI like '%MARKETPLACE%'";
                $pickup = false;
                for($y = 0 ; $y < count($dataAddress[$x]['address_type']);$y++)
                {
                    if($dataAddress[$x]['address_type'][$y] == "PICKUP_ADDRESS")
                    {
                        $pickup = true;
                    }
                    // else if($dataAddress[$x]['address_type'][$y] == "DEFAULT_ADDRESS")
                    // {
                    //     $default = true;
                    // }
                }
                
                if($pickup)
                {
                    $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
                }
            }
        }
        
	    $tglStokMulai = $this->model_master_config->getConfigMarketplace('SHOPEE','TGLSTOKMULAI');
	    
        for($f = 0 ; $f < count($finalData) ; $f++)
        {
            //INSERT KARTUSTOK
            if($this->getStatus($finalData[$f]['STATUSMARKETPLACE'])['state'] != 1)
            {
                $this->insertKartuStokPesanan($finalData[$f]['KODEPENJUALANMARKETPLACE'],$finalData[$f]['TGLTRANS'],$tglStokMulai,$lokasi);
            }
        }
        //CEK LOKASI PICKUP
        
        //PRINT
        $invoice = [];
		$kurir = [];
		$files = [];
		$parameter = [];
		
		foreach($finalData as $item)
		{
		   
        	$file = FCPATH."assets/label/waybill_".$item['KODEPENJUALANMARKETPLACE'].".pdf";
        	$fileCompressed = FCPATH."assets/label/waybill_".$item['KODEPENJUALANMARKETPLACE']."_compressed.pdf";
        	//JIKA DIA DIPROSES dan SIAP DIKIRIM, CEK DULU ADA APA NDAK BARANGNYA, KALAU NDAK ADA BARU BUAT
    		if(strtoupper($this->getStatus($item['STATUSMARKETPLACE'])['status']) == "DIPROSES" || (strtoupper($this->getStatus($item['STATUSMARKETPLACE'])['status']) == "SIAP DIKIRIM" && $item['KODEPENGAMBILAN'] != ""))
        	{
        		    
        		 if (!file_exists($fileCompressed)) {
            		    $ada = false;
            		    for($x = 0 ; $x < count($kurir) ; $x++)
            		    {
            		        if($kurir[$x] == $item["KURIR"])
            		        {
            		            $ada = true;
            		        }
            		    }
            		    
            		    if(!$ada)
            		    {
            		       array_push($kurir,$item["KURIR"]) ;
            		    }
            		    $index = count($invoice);
                        $invoice[$index]['order_sn'] = $item["KODEPENJUALANMARKETPLACE"];
                        $invoice[$index]['package_number'] = $item["KODEPACKAGING"];
                        $invoice[$index]['kurir'] = $item["KURIR"];
                        $invoice[$index]['tracking_number'] = $item["RESI"];
                        $invoice[$index]['shipping_document_type'] = "";
        		}
		    }
    		else
    		{
    		    //KALAU DALAM PENGIRIMAN, HAPUS AJA PDF SISA
    		   if(strtoupper($this->getStatus($item['STATUSMARKETPLACE'])['status']) == "DALAM PENGIRIMAN")
               {
                 unlink($file);
                 unlink($fileCompressed);
               }
    		}
		}
		
		
		for($a = 0 ; $a < count($kurir); $a++)
		{
		    $invoiceKirim = [];
    		for($f = 0 ; $f < count($invoice);$f++){
    		    if($kurir[$a] == $invoice[$f]['kurir'])
    		    {
    		        array_push($invoiceKirim, $invoice[$f]);
    		    }
    		}
    		
        	$params=[];
        	for($f = 0 ; $f < count($invoiceKirim);$f++){
        	    
        	    array_push($params,$invoiceKirim[$f]);
                if(($f % 49 == 0 && $f != 0) || $f == count($invoiceKirim)-1)
                {
            		$parameter = [];
            		$parameter['order_list'] = $params;
            		
            		  //GET RESI
                    $curl = curl_init();
                    
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>  array(
                      'endpoint' => 'logistics/get_shipping_document_parameter',
                      'parameter' => json_encode($parameter)),
                      CURLOPT_HTTPHEADER => array(
                        'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                      ),
                    ));
                      
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $ret =  json_decode($response,true);
		            $data = [];
                    if($ret['error'] != "")
                    {
                        $data['success'] = false;
                        $data['msg'] =   "1 ".$ret['error']." : ".$ret['message'];
                        $data['ret'] = $ret;
                        die(json_encode($data));
                    }
                    else
                    {
                        
                        $dataDocumentType = $ret['response']['result_list'];
                    
                        $indexCetak = 0;
                        foreach($parameter['order_list'] as $item)
                    	{
                            foreach($dataDocumentType as $itemDocumentType)
                            {
                        	    if($item['order_sn'] == $itemDocumentType['order_sn'])
                        	    {
                        	        $parameter['order_list'][$indexCetak]['shipping_document_type'] =  $itemDocumentType['suggest_shipping_document_type']??"NORMAL_AIR_WAYBILL";
                        	        $indexCetak++;
                        	    }
                            }
                    	}
                    	
                    	//BUAT LABEL PESANAN
                    	
                        $curl = curl_init();
                        
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS =>  array(
                          'endpoint' => 'logistics/create_shipping_document',
                          'parameter' => json_encode($parameter)),
                          CURLOPT_HTTPHEADER => array(
                            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                          ),
                        ));
                          
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $ret =  json_decode($response,true);
		                $data = [];
                        if($ret['error'] != "")
                        {
                            $data['success'] = false;
                            $data['msg'] =  "2 ".$ret['error']." : ".$ret['message'];
                            $data['ret'] = $ret;
                            die(json_encode($data));
                        }
                        else
                        {
                            // DAPATKAN HASIL LABEL PESANAN
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                              CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => '',
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 30,
                              CURLOPT_FOLLOWLOCATION => true,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => 'POST',
                              CURLOPT_POSTFIELDS =>  array(
                              'endpoint' => 'logistics/get_shipping_document_result',
                              'parameter' => json_encode($parameter)),
                              CURLOPT_HTTPHEADER => array(
                                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                              ),
                            ));
                                  
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $ret =  json_decode($response,true);
                                
                            if($ret['error'] != "")
                            {
                                $data['success'] = false;
                                $data['msg'] =   "3 ".$ret['error']." : ".$ret['message'];
                                $data['ret'] = $ret;
                                die(json_encode($data));
                            }
                            else
                            {
                                $dataDocumentDownload = $ret['response']['result_list'];
                                
                                
                                foreach($dataDocumentDownload as $itemDocumentDownload)
                                {
                                    $invoiceKirim = [];
                                    $index = count($invoiceKirim);
                                	$invoiceKirim[$index]['order_sn'] = $itemDocumentDownload['order_sn'];
                                	$invoiceKirim[$index]['package_number'] = $itemDocumentDownload['package_number'];
                                	$parameter = [];
                                    $parameter['order_list'] = $invoiceKirim;
                                    	//DAPATKAN HASIL LABEL PESANAN
                                    $curl = curl_init();
                                    curl_setopt_array($curl, array(
                                      CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                                      CURLOPT_RETURNTRANSFER => true,
                                      CURLOPT_ENCODING => '',
                                      CURLOPT_MAXREDIRS => 10,
                                      CURLOPT_TIMEOUT => 30,
                                      CURLOPT_FOLLOWLOCATION => true,
                                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                      CURLOPT_CUSTOMREQUEST => 'POST',
                                      CURLOPT_POSTFIELDS =>  array(
                                      'endpoint' => 'logistics/download_shipping_document',
                                      'parameter' => json_encode($parameter)),
                                      CURLOPT_HTTPHEADER => array(
                                        'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                                      ),
                                    ));
                                      
                                    $response = curl_exec($curl);
                                    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                    $content_type = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
                                    
                                    curl_close($curl);
                                    $ret =  json_decode($response,true);
                                    if($ret['error'] != "")
                                    {
                                        $data['success'] = false;
                                        $data['msg'] =   "4 ".$ret['error']." : ".$ret['message'];
                                        $data['ret'] = $ret;
                                         die(json_encode($data));
                                    }
                                    else
                                    {
                                        // Save the file if request is successful
                                        if ($http_code == 200) {
                                            file_put_contents("assets/label/waybill_".$invoiceKirim[0]['order_sn'].".pdf", $response);
                                            
                                            $input = FCPATH . "assets/label/waybill_".$invoiceKirim[0]['order_sn'].".pdf";
                                            $output = FCPATH . "assets/label/waybill_".$invoiceKirim[0]['order_sn']."_compressed.pdf";
                                            
                                            $cmd = "gs -sDEVICE=pdfwrite \
                                                  -dDEVICEWIDTHPOINTS=283 \
                                                  -dDEVICEHEIGHTPOINTS=425 \
                                                  -dPDFFitPage \
                                                  -dNOPAUSE -dQUIET -dBATCH \ -sOutputFile='$output' '$input'";
                                            
                                            exec($cmd, $outputLines, $status);
                                            $data['success'] = true;
                                            $data['order_sn'] = $invoiceKirim[0]['order_sn'];
                                        } else {
                                            $data['success'] = false;
                                            $data['msg'] =  "Failed to download file. HTTP Status: $http_code\n";
                                            die(json_encode($data));
                                        }
                                    }
                                }
                            }
                        }
                
                    }
                    
        	        $params=[];
                }
        	}
		}
		
		//TOTALPENDAPATANPENJUAL
		$params=[];
        for($f = 0 ; $f < count($finalData);$f++){
            
            array_push($params,$finalData[$f]['KODEPENJUALANMARKETPLACE']);
              if(($f % 49 == 0 && $f != 0) || $f == count($finalData)-1)
              {
          		$parameter = [];
          		$parameter['order_sn_list'] = $params;
          		
          		  //GET RESI
                  $curl = curl_init();
                  
                  curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>  array(
                    'endpoint' => 'payment/get_escrow_detail_batch',
                    'parameter' => json_encode($parameter)),
                    CURLOPT_HTTPHEADER => array(
                      'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                    ),
                  ));
                    
                  $response = curl_exec($curl);
                  curl_close($curl);
                  $ret =  json_decode($response,true);
		          $data = [];
                  if($ret['error'] != "")
                  {
                      $data['success'] = false;
                      $data['msg'] =   "1 ".$ret['error']." : ".$ret['message'];
                      $data['ret'] = $ret;
                      die(json_encode($data));
                  }
                  else
                  {
                      
                      $dataPendapatan = $ret['response'];
                      
                      foreach($dataPendapatan as $itemPendapatan)
                      {
                          $CI->db->where("KODEPENJUALANMARKETPLACE",$itemPendapatan['escrow_detail']['order_sn'])
    		              ->where('MARKETPLACE','SHOPEE')
    		              ->updateRaw("TPENJUALANMARKETPLACE", array(
    		                  'TOTALHARGA'               =>  $itemPendapatan['escrow_detail']['buyer_payment_info']['merchant_subtotal'], 
    		                  'TOTALPENDAPATANPENJUAL'   =>  $itemPendapatan['escrow_detail']['order_income']['escrow_amount'],
    		                ));
                      }
              
                  }
                  
                $params=[];
              }
        }
		
		
		//RETURN 
        $bigger = false;
        $cursor = 0;
        $more = true;
        $statusok = true; 
        $tglTemp = $tgl_aw." 00:00:00"; 
        $dateAk = new DateTime($tgl_ak." 23:59:59");
        $tglcobaAW;
        $tglcobaAK;
        $returnhistory = [];
        while(!$bigger && $statusok)
        {
            $dateAw = new DateTime($tglTemp);
            $dateTemp = new DateTime($tglTemp);
            $tglTempAdd = $dateTemp->modify('+14 days')->format('Y-m-d')." 23:59:59"; // Add 15 days
            $dateTemp = new DateTime($tglTempAdd);    
            if($dateTemp >= $dateAk)
            {
                $tglcobaAW = $tglTemp;
                $tglcobaAK = $tgl_ak." 23:59:59";
                
                $bigger = true;
                $tglAw = $dateAw->getTimestamp();
                $tglAk = $dateAk->getTimestamp();
            }
            else
            {
                $tglcobaAW = $tglTemp;
                $tglcobaAK = $tglTempAdd;
                
                $tglAw = $dateAw->getTimestamp();
                $tglAk = $dateTemp->getTimestamp();
            }
                 
            while($more  && $statusok)
            {
                 $parameter = "&create_time_from=".$tglAw."&create_time_to=".$tglAk."&page_size=100&page_no=".$cursor;
                 array_push($returnhistory, $parameter);
                 $curl = curl_init();
                //GET ORDER LIST
                 curl_setopt_array($curl, array(
                   CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_ENCODING => '',
                   CURLOPT_MAXREDIRS => 10,
                   CURLOPT_TIMEOUT => 30,
                   CURLOPT_FOLLOWLOCATION => true,
                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                   CURLOPT_CUSTOMREQUEST => 'POST',
                   CURLOPT_POSTFIELDS => array('endpoint' => 'returns/get_return_list','parameter' => $parameter),
                   CURLOPT_HTTPHEADER => array(
                     'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                   ),
                 ));
                 
                 $response = curl_exec($curl);
                 curl_close($curl);
                 $ret =  json_decode($response,true);
                 if($ret['error'] != "")
                 {
                     echo $ret['error']." : ".$ret['message'];
                     $statusok = false;
                 }
                 else
                 {
                     $return = $ret['response']['return'];
                     for($x = 0  ; $x < count($return); $x++)
                     {
                		//JIKA BARANG SAMPAI, HARUS RETUR STOK BARANG
                	    $parameter = "&return_sn=".$return[$x]['return_sn'];
                        
                        $curl = curl_init();
                        $logisticStatus = "";
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS => array('endpoint' => 'returns/get_return_detail','parameter' => $parameter),
                          CURLOPT_HTTPHEADER => array(
                            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                          ),
                        ));
                        
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $ret =  json_decode($response,true);
                        if($ret['error'] != "")
                        {
                            echo $ret['error']." : ".$ret['message'];
                        }
                        else
                        {
                            $logisticStatus = $ret['response']['logistics_status'];
                        }
                        //JIKA BARANG SAMPAI, HARUS RETUR
            
                        $dataDetail = $return[$x]['item'];
                        
                        //URUTKAN BERDASARKAN NAMA BARANG
                        usort($dataDetail, function($a, $b) {
                            return strcmp($a['name'], $b['name']);
                        });
                        
                        $allsku = "";
                        $jml = 0;
                        for($d = 0 ; $d < count($dataDetail);$d++)
                        {
                            //KHUSUS SKU YANG KOSONG
                            $sku = $dataDetail[$d]['variation_sku'];
                            if(strpos(strtoupper($dataDetail[$d]['name']),"BIRTHDAY CARD") !== false){
                                $sku = "LTWS";
                            }
                            else if(strpos(strtoupper($dataDetail[$d]['name']),"NEWBORN CARD") !== false){
                                $sku = "CARD-BOX-OTHER";
                            }
                            $jml += $dataDetail[$d]['amount'];
                            $allsku .= $dataDetail[$d]['amount']."*".$sku."|";
                        }
                        
                        $allsku = substr($allsku, 0, -1);
                        
                         $CI->db->where("KODEPENJUALANMARKETPLACE",$return[$x]['order_sn'])
		                    ->where('MARKETPLACE','SHOPEE')
		                    ->updateRaw("TPENJUALANMARKETPLACE", array(
		                        'KODEPENGEMBALIANMARKETPLACE'   =>  $return[$x]['return_sn'],
		                        'SKUPRODUKPENGEMBALIAN'         =>  $allsku,
		                        'TOTALBARANGPENGEMBALIAN'       =>  $jml,
		                        'STATUSPENGEMBALIANMARKETPLACE' =>  $return[$x]['status'],
		                        'CATATANPENGEMBALIAN'           =>  $return[$x]['text_reason'],
		                        'TOTALPENGEMBALIANDANA'         =>  $return[$x]['refund_amount'],
		                        'TIPEPENGEMBALIAN'              =>  $return[$x]['return_refund_type'],
		                        'TGLPENGEMBALIAN'               =>  date("Y-m-d H:i:s", $return[$x]['create_time']),
		                        'MINTGLPENGEMBALIAN'            =>  date("Y-m-d H:i:s", $return[$x]['due_date']),
		                        'MINTGLKIRIMPENGEMBALIAN'       =>  date("Y-m-d H:i:s", $return[$x]['return_ship_due_date']),
		                        'RESIPENGEMBALIAN'              =>  $return[$x]['tracking_number'],
		                        'BARANGSAMPAI'                  =>  ($logisticStatus == "LOGISTICS_DELIVERY_DONE"? 1 : 0)
		                      ));
                     }
                     $cursor++;
                     $more = $ret['response']['more'];
                 }
            }
            $cursor = 0;
            $more = false;
            $statusok = true;
            
            $dateAddOne = new DateTime($tglTempAdd);
            $tglTempAddOne = $dateAddOne->modify('+1 days')->format('Y-m-d')." 00:00:00"; // Add 1 days
            $tglTemp = $tglTempAddOne;
        }
        
        
        //CEK LOKASI RETURN, YANG BARANG SMPAI = 1
        $lokasi = "0";
        $parameter="";
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'logistics/get_address_list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataAddress = $ret['response']['address_list'];
            $data['rows'] = [];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASISHOPEE = ".$dataAddress[$x]['address_id']." AND GROUPLOKASI like '%MARKETPLACE%'";
                
                for($y = 0 ; $y < count($dataAddress[$x]['address_type']);$y++)
                {
                    if($dataAddress[$x]['address_type'][$y] == "RETURN_ADDRESS")
                    {
                        $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
                    }
                }
            }
        }
        
	    $tglStokMulai = $this->model_master_config->getConfigMarketplace('SHOPEE','TGLSTOKMULAI');
	    
	    if(count($finalData) > 0)
	    {
    	    $wherePesanan = "AND KODEPENJUALANMARKETPLACE in (";
            for($f = 0 ; $f < count($finalData) ; $f++)
            {
                $wherePesanan .= "'".$finalData[$f]['KODEPENJUALANMARKETPLACE']."'";
                
                if($f != count($finalData)-1)
                {
                     $wherePesanan .= ",";
                }
            }
            $wherePesanan .= ")";
	    }
        
        $sqlRetur = "SELECT KODEPENGEMBALIANMARKETPLACE, TGLPENGEMBALIAN FROM TPENJUALANMARKETPLACE WHERE MARKETPLACE = 'SHOPEE' and KODEPENGEMBALIANMARKETPLACE != '' and BARANGSAMPAI = 1 $wherePesanan ";
        $dataRetur = $CI->db->query($sqlRetur)->result();

        foreach($dataRetur as $itemRetur)
        {
           $this->insertKartuStokRetur($itemRetur->KODEPENGEMBALIANMARKETPLACE,$itemRetur->TGLPENGEMBALIAN,$tglStokMulai,$lokasi);
        }
        //CEK LOKASI RETURN
        
        
        //SET BOOST
        
        //CEK COOLDOWN HABIS APA TIDAK
        $waktu = 0;
        $curl = curl_init();
	    
	    curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/shopee/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_boosted_list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['error'] != "")
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $itemList = $ret['response']['item_list'];
            for($i = 0 ; $i < count($itemList) ; $i++)
            {
              $waktu = $itemList[$i]['cool_down_second'];
            }
        }
        
        if($waktu == 0)
        {
            
    		$parameter = [];
    		$parameter['item_id_list'] = [];
    		
            $sql = "SELECT IDINDUKBARANGSHOPEE FROM MBARANG WHERE BOOSTSHOPEE = 2 GROUP BY KATEGORI LIMIT 5";
            $dataBarangPermanent = $CI->db->query($sql)->result();
            
            foreach($dataBarangPermanent as $itemBarangPermanent)
    		{
    		    array_push($parameter['item_id_list'],(int)$itemBarangPermanent->IDINDUKBARANGSHOPEE);
    		}
            
            $sql = "SELECT IDINDUKBARANGSHOPEE FROM MBARANG WHERE BOOSTSHOPEE = 1 GROUP BY KATEGORI ORDER BY RAND() LIMIT ".(5-count($dataBarangPermanent));
            $dataBarang = $CI->db->query($sql)->result();
    		
    		foreach($dataBarang as $itemBarang)
    		{
    		    array_push($parameter['item_id_list'],(int)$itemBarang->IDINDUKBARANGSHOPEE);
    		}
    		
    		print_r($parameter);
    	    $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/shopee/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  array(
              'endpoint' => 'product/boost_item',
              'parameter' => json_encode($parameter)),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
              
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
         
            if($ret['error'] != "")
            {
                echo $ret['error']." BOOST : ".$ret['message'];
            }
        }
        
        //SET BOOST
        	
        
        $finalResult["history"] = $history;
        $finalResult["return_history"] = $returnhistory;
		$finalResult["total"] = $newOrder;
		
		echo json_encode($finalResult); 
	}
	
}
