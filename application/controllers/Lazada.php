<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lazada extends MY_Controller {
	public function index()
	{
        
	    echo "
	         <b>Tutorial Cara Menggunakan Lazada API</b>
	         <br><br>
	         1. Masuk ke Lazada Console, masukkan data-data ini ke database :<br>
	         - App Key <br>
	         - App Secret
	         <br><br>
	         2. Buat link auth yang akan diinput, ketika klik Authorize (Console -> App List)<br>
	            Caranya : akses ".$this->config->item('base_url')."/Lazada/getAuth?
	         <br><br>
	         3. Lanjutkan Proses hingga di url browser, muncul Code dan Shop ID masukkan data-data ini ke database
	         <br><br>
	         4. Dapatkan akses token untuk Akses API<br>
	            Caranya : akses ".$this->config->item('base_url')."/Lazada/getToken?
	        <br><br> 
	         5. Masukkan akses token kedalam database
	         <br><br>
	         6. ketika menggunakan API, gunakan controller Lazada/getAPI atau Lazada/postAPI, dan masukkan pathnya kesana.
	         ";
	}
	
	public function getAuth()
	{
	    $appkey = $this->model_master_config->getConfigMarketplace('LAZADA','APP_KEY');
        $host = "https://auth.lazada.com";
        $path = "/oauth/authorize";
        $redirectUrl =  "https://".$_SERVER['SERVER_NAME'] .str_replace('getAuth?','setCode?',$_SERVER['REQUEST_URI']);

        $url = sprintf("%s%s?response_type=code&force_auth=true&redirect_uri=%s&client_id=%s", $host, $path, urlencode($redirectUrl),$appkey);
        echo "Authorize Link : ".$url."&country=id";
	}
	
	public function setCode(){
	    $this->model_master_config->setConfigMarketplace('LAZADA','CODE',$this->input->get("code"));
        echo "Code berhasil disimpan didatabase<br><br>akses https://".$_SERVER['SERVER_NAME'] . str_replace("setCode?=&code=".$this->input->get("code"),"getToken?",$_SERVER['REQUEST_URI']);
	}
	
	public function getToken()
	{
	    $code = $this->model_master_config->getConfigMarketplace('LAZADA','CODE');
	    $appKey = $this->model_master_config->getConfigMarketplace('LAZADA','APP_KEY');
	    $accessToken = $this->model_master_config->getConfigMarketplace('LAZADA','ACCESS_TOKEN');
	    $appSecret = $this->model_master_config->getConfigMarketplace('LAZADA','APP_SECRET');
        
        $host = 'https://auth.lazada.com/rest';
        $path = "/auth/token/create";
        
        //STEP 1
        // Current UTC time
        $datetime = new DateTime('now', new DateTimeZone('UTC'));
        
        // Get Unix timestamp in seconds
        $timestampSeconds = $datetime->getTimestamp();
        
        // Convert to milliseconds
        $timest = $timestampSeconds * 1000;

        $signMethod = 'sha256';
        $body = array("code" => $code,  "app_key" => $appKey, "timestamp" => $timest, "sign_method" => $signMethod); //"access_token" => $accessToken, "uuid" => ""
        uksort($body, function($a, $b) {
            return strcmp($a, $b);
        });
        
        //STEP 2
        $paramString = http_build_query($body, '', '&', PHP_QUERY_RFC3986);

        //STEP 3
        $baseString = str_replace("&","",str_replace("=","",sprintf("%s%s", $path, $paramString)));
        
        //STEP 4 & 5
        $sign = strtoupper(hash_hmac($signMethod, $baseString, $appSecret));
        
        $body['sign'] = $sign;
        
        $url = $host.$path;
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($c);
        $ret = json_decode($resp, true);

        if($ret['code'] != 0)
        {
          echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $accessToken = $ret["access_token"];
            $newRefreshToken = $ret["refresh_token"];
            
            if($accessToken != null && $newRefreshToken != null )
            {
                $this->model_master_config->setConfigMarketplace('LAZADA','ACCESS_TOKEN',$accessToken);
                $this->model_master_config->setConfigMarketplace('LAZADA','REFRESH_TOKEN',$newRefreshToken);
            }
            
            echo "Access Token : ".($accessToken)."<br>Refresh Token : ".($newRefreshToken)."<br><br>Berhasil disimpan di database";
        }
        
	}
	
	public function getRefreshToken(){
	    
	    $refreshToken = $this->model_master_config->getConfigMarketplace('LAZADA','REFRESH_TOKEN');
	    $appKey = $this->model_master_config->getConfigMarketplace('LAZADA','APP_KEY');
	    $accessToken = $this->model_master_config->getConfigMarketplace('LAZADA','ACCESS_TOKEN');
	    $appSecret = $this->model_master_config->getConfigMarketplace('LAZADA','APP_SECRET');
        
        $host = 'https://auth.lazada.com/rest';
        $path = "/auth/token/refresh";
        
        //STEP 1
        // Current UTC time
        $datetime = new DateTime('now', new DateTimeZone('UTC'));
        
        // Get Unix timestamp in seconds
        $timestampSeconds = $datetime->getTimestamp();
        
        // Convert to milliseconds
        $timest = $timestampSeconds * 1000;

        $signMethod = 'sha256';
        $body = array("refresh_token" => $refreshToken,  "app_key" => $appKey, "timestamp" => $timest, "sign_method" => $signMethod); //"access_token" => $accessToken, "uuid" => ""
        uksort($body, function($a, $b) {
            return strcmp($a, $b);
        });
        
        //STEP 2
        $paramString = http_build_query($body, '', '&', PHP_QUERY_RFC3986);

        //STEP 3
        $baseString = str_replace("&","",str_replace("=","",sprintf("%s%s", $path, $paramString)));
        
        //STEP 4 & 5
        $sign = strtoupper(hash_hmac($signMethod, $baseString, $appSecret));
        
        $body['sign'] = $sign;
        
        $url = $host.$path;
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($c);
        $ret = json_decode($resp, true);

        if($ret['code'] != 0)
        {
          echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $accessToken = $ret["access_token"];
            $newRefreshToken = $ret["refresh_token"];
            
            if($accessToken != null && $newRefreshToken != null )
            {
                $this->model_master_config->setConfigMarketplace('LAZADA','ACCESS_TOKEN',$accessToken);
                $this->model_master_config->setConfigMarketplace('LAZADA','REFRESH_TOKEN',$newRefreshToken);
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
    	    $debug     = $this->input->post('debug');
    	    $parameter = $this->input->post("parameter");
    	    
    	    $this->load->library('Lazop', [
                'appKey'    => $this->model_master_config->getConfigMarketplace('LAZADA','APP_KEY'),
                'appSecret' => $this->model_master_config->getConfigMarketplace('LAZADA','APP_SECRET')
            ]);
    
            $client = $this->lazop->getClient();
            // Example request: get user info
            $request = new LazopRequest($endpoint, 'GET');
            
          
            $arrParam = explode("&",$parameter);
            // Loop and add to LazopRequest
            for ($x = 0 ; $x < count($arrParam) ; $x++) {
                if($x != 0)
                {
                    $arrData = explode("=",$arrParam[$x]);
                    $request->addApiParam($arrData[0], $arrData[1]);
                }
            }
            
            if($debug == 1)
            {
               $this->output->set_output(json_encode([
                "code" => 100,
                   "message" =>  json_encode($arrData)
               ]));
            }
                
            $response = $client->execute($request, $this->model_master_config->getConfigMarketplace('LAZADA','ACCESS_TOKEN'));
            
            if($debug == 0)
            {
                echo $response; 
            }
	    }
	    else
	    {
	       $this->output->set_output(json_encode([
	            "code" => 100,
                "message" => "failed refresh token"
            ]));
	    }
	}
	
	public function postAPI()
	{
	    $this->output->set_content_type('application/json');
	    
	    $response =  $this->getRefreshToken();
	    if($response)
	    {
	        
    	    $endpoint = $this->input->post("endpoint");
    	    $debug = $this->input->post("debug")??0;
    	    $parameter = json_decode($this->input->post("parameter"),true);
    
    	    $this->load->library('Lazop', [
                'appKey'    => $this->model_master_config->getConfigMarketplace('LAZADA','APP_KEY'),
                'appSecret' => $this->model_master_config->getConfigMarketplace('LAZADA','APP_SECRET')
            ]);
    
            $client = $this->lazop->getClient();
            // Example request: get user info
            $request = new LazopRequest($endpoint);
           
            // Loop and add to LazopRequest
            for ($x = 0 ; $x < count($parameter) ; $x++) {
                if($parameter[$x]['xml'] == 1)
                {
                    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root/>');
                    $this->arrayToXml($parameter[$x]['parameter'], $xml);
                    $parameter[$x]['parameter'] = str_replace('<?xml version="1.0" encoding="UTF-8"?>','',$xml->Request->asXML());
                }
                $request->addApiParam($parameter[$x]['parameterKey'], $parameter[$x]['parameter']);
                if($debug == 1)
                {
                    $this->output->set_output(json_encode([
        	            "code" => 100,
                        "message" =>  $parameter[$x]['parameter']
                    ]));
                }
            }
            
            $response = $client->execute($request, $this->model_master_config->getConfigMarketplace('LAZADA','ACCESS_TOKEN'));
            if($debug == 0)
            {
                echo $response; 
            }
         

	    }
        else
	    {
	       // echo "Token gagal diperbaharui";
	        $this->output->set_output(json_encode([
	            "code" => 100,
                "message" => "failed refresh token"
            ]));

	    }
        
	}
	
	public function arrayToXml($data, &$xmlData, $parentKey = null) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    // kalau array numeric, pakai parentKey (misalnya URL)
                    $this->arrayToXml($value, $xmlData, $parentKey);
                } else {
                    $subnode = $xmlData->addChild($key);
                    $this->arrayToXml($value, $subnode, $key);
                }
            } else {
                $value = preg_replace("/\n+/", "<br>", $value);
                if (is_numeric($key) && $parentKey) {
                    // numeric key tapi value string → pakai parentKey
                    $xmlData->addChild($parentKey, htmlspecialchars($value));
                } else {
                    $xmlData->addChild("$key", htmlspecialchars($value));
                }
            }
        }
    }

	
	public function connectBarang(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');;
		$status = ["NORMAL","BANNED","UNLIST","REVIEWING","SELLER_DELETE","LAZADA_DELETE"];
		$statusok = true;
		$statusParam = "";
		$data["msg"] = "TIDAK ADA IDBARANGLAZADA YANG DIUPDATE";
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
              CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
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
            if($ret['code'] != 0)
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
                      CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
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
                    if($ret['code'] != 0)
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
                            
                            $sql = "UPDATE MBARANG SET IDBARANGLAZADA = ".$dataModel[$m]['model_id'].", IDINDUKBARANGLAZADA = ".$idbarang[$x]['item_id']." WHERE SKULAZADA = '".strtoupper($sku)."'";
                            $CI->db->query($sql);
                            echo $sql." ;";
                            echo "\n";
                           $data["total"]++;
                           $data["msg"] = "IDBARANGLAZADA BERHASIL DIUPDATE";
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
		
	
    	$sql = "SELECT IDCUSTOMER,KONSINYASI FROM MCUSTOMER WHERE KODECUSTOMER = 'XLAZADA' ";
    	$IDCUSTOMER = $CI->db->query($sql)->row()->IDCUSTOMER;
    	$KONSINYASI = $CI->db->query($sql)->row()->KONSINYASI??0;
		
		
		if($varian == "true")
		{
    		$sql = "SELECT a.IDBARANG,a.KATEGORI,
    		        a.IDBARANGLAZADA, a.IDINDUKBARANGLAZADA,a.SKULAZADA
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
    		                if($itemBarang->IDBARANGLAZADA != 0 && $itemBarang->IDINDUKBARANGLAZADA != 0)
    		                {
    		                    array_push($dataBarangHarga,
        		                    array(
        		                         'IDBARANGLAZADA'           => $itemBarang->IDBARANGLAZADA,
        		                         'IDINDUKBARANGLAZADA'      => $itemBarang->IDINDUKBARANGLAZADA,
        		                         'SKULAZADA'                => $itemBarang->SKULAZADA,
        		                         'HARGA'                    => $item->hargajualnew,
        		                         'HARGADISKON'              => $KONSINYASI == 1 ? $item->hargakonsinyasinew:$item->harganew,
        		                    )); 
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
    		           if($itemBarang->IDBARANGLAZADA != 0 && $itemBarang->IDINDUKBARANGLAZADA != 0)
    		           {
    		               array_push($dataBarangHarga,
        		                    array(
        		                         'IDBARANGLAZADA'           => $itemBarang->IDBARANGLAZADA,
        		                         'IDINDUKBARANGLAZADA'      => $itemBarang->IDINDUKBARANGLAZADA,
        		                         'SKULAZADA'                => $itemBarang->SKULAZADA,
        		                         'HARGA'                    => $item->hargajualnew,
        		                         'HARGADISKON'              => $KONSINYASI == 1 ? $item->hargakonsinyasinew:$item->harganew,
        		                    )); 
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
        	   $sql = "SELECT a.IDBARANG, a.IDBARANGLAZADA, a.IDINDUKBARANGLAZADA,a.SKULAZADA FROM MBARANG a WHERE a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and kategori in (select x.kategori from mbarang x where x.idbarang = {$item->idbarang})";
        	   
        	   $allBarang = $CI->db->query($sql)->result();
        	   
        	   foreach($allBarang as $itemBarang)
        	   {
                   if($itemBarang->IDBARANGLAZADA != 0 && $itemBarang->IDINDUKBARANGLAZADA != 0)
        	       {
        	            array_push($dataBarangHarga,
        		                    array(
        		                         'IDBARANGLAZADA'           => $itemBarang->IDBARANGLAZADA,
        		                         'IDINDUKBARANGLAZADA'      => $itemBarang->IDINDUKBARANGLAZADA,
        		                         'SKULAZADA'                => $itemBarang->SKULAZADA,
        		                         'HARGA'                    => $item->hargajualnew,
        		                         'HARGADISKON'              => $KONSINYASI == 1 ? $item->hargakonsinyasinew:$item->harganew,
        		                    )); 
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
        		    $sql = "SELECT a.IDBARANG, a.IDBARANGLAZADA, a.IDINDUKBARANGLAZADA,a.SKULAZADA FROM MBARANG a WHERE a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and kategori in (select x.kategori from mbarang x where x.idbarang = {$item->idbarang})";
        		    
        		    $allBarang = $CI->db->query($sql)->result();
        		    
        		    foreach($allBarang as $itemBarang)
        		    {
            		    if($itemBarang->IDBARANGLAZADA != 0 && $itemBarang->IDINDUKBARANGLAZADA != 0)
        		        {
        		            array_push($dataBarangHarga,
        		                    array(
        		                         'IDBARANGLAZADA'           => $itemBarang->IDBARANGLAZADA,
        		                         'IDINDUKBARANGLAZADA'      => $itemBarang->IDINDUKBARANGLAZADA,
        		                         'SKULAZADA'                => $itemBarang->SKULAZADA,
        		                         'HARGA'                    => $item->hargajualnew,
        		                         'HARGADISKON'              => $KONSINYASI == 1 ? $item->hargakonsinyasinew:$item->harganew,
        		                    )); 
        		        }
        		    }
    		    }
    		}
    		
        }
        
        $detailParameter = [];
        
		for($x = 0 ; $x < count($dataBarangHarga); $x++)
		{
    		
             array_push($detailParameter,
                   ['Sku' => [
                        'ItemId' => $dataBarangHarga[$x]['IDINDUKBARANGLAZADA'],
                        'SkuId' => $dataBarangHarga[$x]['IDBARANGLAZADA'],
            	        'SellerSku' => $dataBarangHarga[$x]['SKULAZADA'],
            	        'Price' => (int)$dataBarangHarga[$x]['HARGA'],
            	        'SalePrice'  => (int)$dataBarangHarga[$x]['HARGADISKON'],
            	    ]]);
            	    
        	if(($x % 19 == 0 && $x != 0) || $x == count($dataBarangHarga)-1)
            {
                $parameter = [[
            	    'xml' => 1,
            	    'parameterKey' => 'payload',
            	    'parameter' => [
                           'Request' => [
                               'Product' => [
                                    'Skus' => $detailParameter
                               ]
                           ]
                    ]
                ]];
                
                $curl = curl_init();
                        
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  array(
                  'endpoint' => '/product/price_quantity/update',
                  'parameter' => json_encode($parameter),
                //   'debug' => 1
                  ),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                
                if($ret['code'] != 0)
                { 
                    $ret['success'] = false;
                    $ret['msg'] = $ret['message'];
                    die(json_encode($ret));
                }
                else
                {
                   $parameter = [];
                   $detailParameter = [];
                }
            }
		}
		
		
        $data['success'] = true;
        $data['msg'] =  'Update Harga Lazada Berhasil';
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
		
        $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASILAZADA = 1 AND GROUPLOKASI like '%MARKETPLACE%'";
        $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
        
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
            $count = 0;
            $sql = "select IDPERUSAHAAN, IDBARANGLAZADA, IDINDUKBARANGLAZADA, IDBARANG,SKULAZADA,HARGAJUAL
        				from MBARANG
        				where (1=1) $whereBarang and (IDBARANGLAZADA is not null and IDBARANGLAZADA <> 0)
        				order by IDINDUKBARANGLAZADA
        				";	
        		
        	$dataHeader = $this->db->query($sql)->result();
        	
        	$parameter = [];
            $detailParameter = [];
            
        	for($x = 0; $x < count($dataHeader) ; $x++)
        	{
        	    
            	$result   = get_saldo_stok_new($dataHeader[$x]->IDPERUSAHAAN,$dataHeader[$x]->IDBARANG, $idlokasiset, date('Y-m-d'));
                $saldoQty = $result->QTY??0;
                if($saldoQty < 0)
                {
                    $saldoQty = 0;
                }
                                              
            	 array_push($detailParameter,
            	       ['Sku' => [
            	            'ItemId' => $dataHeader[$x]->IDINDUKBARANGLAZADA,
            	            'SkuId' => $dataHeader[$x]->IDBARANGLAZADA,
            		        'SellerSku' => $dataHeader[$x]->SKULAZADA,
            		        'Quantity' => (int)$saldoQty
            		      //  'Price' => (int)$dataHeader[$x]->HARGAJUAL
            		    ]]);
            		    
        	    if(($x % 19 == 0 && $x != 0) || $x == count($dataHeader)-1)
                {
                    
                    $parameter = [[
            		    'xml' => 1,
            		    'parameterKey' => 'payload',
            		    'parameter' => [
                               'Request' => [
                                   'Product' => [
                                        'Skus' => $detailParameter
                                   ]
                               ]
                        ]
                    ]];
                    
                    $curl = curl_init();
                            
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>  array(
                      'endpoint' => '/product/price_quantity/update',
                      'parameter' => json_encode($parameter),
                    //   'debug' => 1
                      ),
                      CURLOPT_HTTPHEADER => array(
                        'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                      ),
                    ));
                      
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $ret =  json_decode($response,true);
                    
                    if($ret['code'] != 0)
                    { 
                        $ret['success'] = false;
                        $ret['msg'] = $ret['message'];
                        die(json_encode($ret));
                    }
                    else
                    {
                       $parameter = [];
                       $detailParameter = [];
                    }
                }
        	}
        		    
        	
        	
        	if(count($dataHeader) == 0)
            {
                $data['success'] = true;
                $data['msg'] =  "";
                die(json_encode($data));
            }
        	
        }
        
        if($lokasi == $idlokasiset)
        {
            $data['success'] = true;
            $data['msg'] = "Stok Lazada Berhasil Diupdate";
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
		$ada = false;
        $curl = curl_init();
        
        $parameter = "&language_code=id_ID";
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/category/tree/get','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['code'] != 0)
        {
            $ret['success'] = false;
            $ret['msg'] = $ret['message'];
            die(json_encode($ret));
        }
        else
        {
            $dataKategori = $ret['data'];
            $responseKategori = [];
            
            foreach($dataKategori as $itemKategori)
            {
                if(!isset($itemKategori['children']))
                {
                    array_push($responseKategori,array(
                        'VALUE' =>  $itemKategori['category_id'],
                        'TEXT' => $itemKategori['name']
                    ));
                }
                else
                {
                    foreach($itemKategori['children'] as $itemSubKategori)
                    {
                    
                           if(!isset($itemSubKategori['children']))
                           {
                               array_push($responseKategori,array(
                                   'VALUE' =>  $itemSubKategori['category_id'],
                                   'TEXT' => $itemKategori['name']." / ".$itemSubKategori['name']
                               ));
                           }
                           else
                           {
                               foreach($itemSubKategori['children'] as $itemSubKategori2)
                               {
                                   if(!isset($itemSubKategori2['children']))
                                    {
                                        array_push($responseKategori,array(
                                            'VALUE' =>  $itemSubKategori2['category_id'],
                                            'TEXT' => $itemKategori['name']." / ".$itemSubKategori['name']." / ".$itemSubKategori2['name']
                                        ));
                                    }
                                    else
                                    {
                                        foreach($itemSubKategori2['children'] as $itemSubKategori3)
                                        {
                                            
                                            if(!isset($itemSubKategori3['children']))
                                            {
                                                array_push($responseKategori,array(
                                                    'VALUE' =>  $itemSubKategori3['category_id'],
                                                    'TEXT' => $itemKategori['name']." / ".$itemSubKategori['name']." / ".$itemSubKategori2['name']." / ".$itemSubKategori3['name']
                                                ));
                                                
                                            }
                                            else
                                            {
                                                foreach($itemSubKategori3['children']  as $itemSubKategori4)
                                                {
                                                    
                                                    if(!isset($itemSubKategori4['children']))
                                                    {
                                                        
                                                    
                                                       array_push($responseKategori,array(
                                                            'VALUE' =>  $itemSubKategori4['category_id'],
                                                            'TEXT' => $itemKategori['name']." / ".$itemSubKategori['name']." / ".$itemSubKategori2['name']." / ".$itemSubKategori3['name']." / ".$itemSubKategori4['name']
                                                        ));
                                                        
                                                    }
                                                    else
                                                    {
                                                        foreach($itemSubKategori4['children']  as $itemSubKategori5)
                                                        {
                                                            
                                                           array_push($responseKategori,array(
                                                                'VALUE' =>  $itemSubKategori5['category_id'],
                                                                'TEXT' => $itemKategori['name']." / ".$itemSubKategori['name']." / ".$itemSubKategori2['name']." / ".$itemSubKategori3['name']." / ".$itemSubKategori4['name']." / ".$itemSubKategori5['name']
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
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
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
        if($ret['code'] != 0)
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
              CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
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
            if($ret['code'] != 0)
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
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
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
        if($ret['code'] != 0)
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
                  CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
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
                if($ret['code'] != 0)
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
                          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
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
                        if($ret['code'] != 0)
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
		$status = $this->input->post('status');
		$arrStatus = explode(",",$status);
		$data['rows'] = [];
		$data["total"] = 0;
		
		for($x = 0 ; $x < count($arrStatus) ; $x++)
		{
    		$parameter = "&filter=$arrStatus[$x]";
    
    		$curl = curl_init();
    		
    		 curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => '/products/get','parameter' => $parameter),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            if($ret['code'] != 0)
            {
                echo $ret['code']." : ".$ret['message'];
            }
            else
            {
                 $dataBarang = $ret['data']['products'];
                 
                 $sqlBarangMaster = "select IDBARANG, KATEGORI, IDINDUKBARANGLAZADA from MBARANG where IDINDUKBARANGLAZADA != 0 and IDINDUKBARANGLAZADA != '' and IDINDUKBARANGLAZADA is not null";
                 $dataBarangMaster = $CI->db->query($sqlBarangMaster)->result();
                 
                 foreach($dataBarang as $itemBarang)
                 {
                   
                   $itemBarang['MASTERCONNECTED'] = "TIDAK";
                   $itemBarang['IDMASTERBARANG'] = 0;
                   $itemBarang['KATEGORIMASTERBARANG'] = '';
                   foreach($dataBarangMaster as $itemBarangMaster)
                   {
                       if($itemBarangMaster->IDINDUKBARANGLAZADA == $itemBarang['item_id'])
                       {
                          $itemBarang['MASTERCONNECTED'] ="YA";  
                          $itemBarang['IDMASTERBARANG'] = $itemBarangMaster->IDBARANG;
                          $itemBarang['KATEGORIMASTERBARANG'] = $itemBarangMaster->KATEGORI;
                       }
                   }
                   
                   $itemBarang['NAMABARANG'] = $itemBarang['attributes']['name'];    
                   $itemBarang['VARIAN'] = count($itemBarang['skus'][0]['saleProp']) != 0 ? "YA" : "TIDAK";     
                   $itemBarang['TGLENTRY'] = date("Y-m-d H:i:s", (float)($itemBarang['updated_time']??$itemBarang['created_time'])/1000);    
                   $itemBarang['STATUS'] = $itemBarang['status'];    
                   
                   array_push($data['rows'],$itemBarang);
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
	
	function getDataBarangdanVarian(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$itemid = $this->input->post('idindukbaranglazada');
		
		$data = [];
        
        $parameter = '';
        //GET MODEL
        $parameter = "&item_id=".(int)$itemid;
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/product/item/get','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);

        if($ret['code'] != 0)
        {
            $ret['success'] = false;
            $ret['msg'] = $ret['message'];
            die(json_encode($ret));
        }
        else
        {
            $data['skus'] = $ret['data']['skus'];
            $data['status'] = $ret['data']['status'];
        }
        echo(json_encode($data));
	}
	
	function getDataBarang(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$itemid = $this->input->post('idindukbaranglazada');
	    $parameter = '';
        //GET MODEL
        $parameter = "&item_id=".(int)$itemid;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/product/item/get','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);

        if($ret['code'] != 0)
        {
            $ret['success'] = false;
            $ret['msg'] = $ret['message'];
            die(json_encode($ret));
        }
        else
        {
            $dataUrutan = $ret['data']['variation']['Variation1']['options']; //URUTKAN DARI WARNA
            $dataModel = $ret['data']['skus'];
            $data['dataVarian'] = [];
            $data['dataGambarVarian'] = [];
            
            if(isset($dataModel[0]['saleProp']))
            {
                for($u = 0 ; $u < count($dataUrutan); $u++)
                {
                    $imageurl = "";
                    for($m = 0 ; $m < count($dataModel);$m++)
                    {
                        if($dataUrutan[$u] == $dataModel[$m]['saleProp']['color_family'])
                        {
                            array_push($data['dataVarian'], array(
                                'ID'    => $dataModel[$m]['SkuId'],
                                'NAMA'  => strtoupper($dataModel[$m]['saleProp']['color_family']." | SIZE ".$dataModel[$m]['saleProp']['size']),
                                'WARNA' => strtoupper($dataModel[$m]['saleProp']['color_family']),
                                'SIZE'  => strtoupper($dataModel[$m]['saleProp']['size']),
                                'SKU'   => $dataModel[$m]['SellerSku'],
                                "HARGA" => $dataModel[$m]['price']
                            ));
                            $imageurl = $dataModel[$m]['Images'][0];
                        }
                    }
                    // echo  explode(".",explode("/",$imageurl)[count(explode("/",$imageurl))-1])[0];
                    array_push($data['dataGambarVarian'], array(
                        'WARNA'     => strtoupper($dataUrutan[$u]),
                        'IMAGEID'   => explode(".",explode("/",$imageurl)[count(explode("/",$imageurl))-1])[0],
                        "IMAGEURL"  => $imageurl,
                    ));
                }
            }
        }
        
        echo(json_encode($data));
	}
	
	function setBarang(){
	   
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
		$idBarang           = $this->input->post("IDBARANG",0);
		$dataVarian         = json_decode($this->input->post("VARIAN"));
		$dataWarna          = json_decode($this->input->post("WARNA"));
		$dataUkuran         = json_decode($this->input->post("UKURAN"));
		$dataGambarProduk   = json_decode($this->input->post("GAMBARPRODUK"));
		$dataGambarVarian   = json_decode($this->input->post("GAMBARVARIAN"));
	    $sizeChart          = $this->input->post("SIZECHART");
		$sizeChartID        = $this->input->post("SIZECHARTID");
		$sizeChartTipe      = $this->input->post("SIZECHARTTIPE");
		$kategoriBarang     = $this->input->post("KATEGORI");
		$hargaInduk         = $this->input->post("HARGA");
		$skuInduk           = $this->input->post("SKU");
		
		if($dataUkuran[0] == 0)
		{
		    $dataUkuran = [];
		}

        $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASILAZADA = 1 AND GROUPLOKASI like '%MARKETPLACE%'";
        $idlokasiset = $CI->db->query($sql)->row()->IDLOKASI;
        
        $sql = "SELECT IDCUSTOMER,KONSINYASI FROM MCUSTOMER WHERE KODECUSTOMER like '%LAZADA%'";
        $dataCustomer = $CI->db->query($sql)->row();
        
		if(count($dataVarian) > 0)
		{
		    $indexVarian = 0;
		    $detailParameter = [];
		    foreach($dataVarian as $itemVarian)
		    {
		       if($itemVarian->MODE != 'HAPUS')
    		   {
    		        $indexWarna = 0;
        		    for($w = 0 ; $w < count($dataWarna) ; $w++){
        		        if($itemVarian->WARNA == $dataWarna[$w])
        		        {
        		            $indexWarna = $w;
        		        }
        		    }
        		    
        		    $dataSaleProp = [];
    		        if(count($dataWarna) > 0)
                    {
                        array_push($dataSaleProp,array(
                            'color_family' => $itemVarian->WARNA
                        ));
                    } 
                    
                    if(count($dataUkuran) > 0)
                    {
                        array_push($dataSaleProp,array(
                            'size' => $itemVarian->SIZE,
                        ));
                    } 
        		   
        		    $sql = "SELECT IDPERUSAHAAN, IDBARANG FROM MBARANG WHERE IDBARANGLAZADA = ".$itemVarian->IDBARANG. " or IDBARANG = ".$itemVarian->IDBARANG ;
                
                    $itemHeader = $CI->db->query($sql)->row();
                    $result   = get_saldo_stok_new($itemHeader->IDPERUSAHAAN,$itemHeader->IDBARANG, $idlokasiset, date('Y-m-d'));
                    $saldoQty = $result->QTY??0;
                    if($saldoQty < 0)
                    {
                        $saldoQty = 0;
                    }
                     
    		       array_push($detailParameter,
    		       ['Sku' => [
    		            'Status' => 'active',
        		        'SellerSku' => $itemVarian->SKULAZADA,
        		        'quantity' => (int)$saldoQty,
        		        'price' => (int)$itemVarian->HARGAJUAL,
        		        'saleProp' => $dataSaleProp,
        		      //  'special_price' => (int)$hargaPromo,
        		      //  'special_from_date' => "2025-06-20 17:18:31",
        		      //  'special_to_date' => "2026-06-20 17:18:31",
        		        'package_height' => (float)$this->input->post("TINGGI"),
        		        'package_length' => (float)$this->input->post("PANJANG"),
        		        'package_width' => (float)$this->input->post("LEBAR"),
        		        'package_weight' => ((float)$this->input->post("BERAT")/1000),
        		        'Images' => [
                          'Image' => $dataGambarVarian[$indexWarna]
                        ]
        		    ]]);
        		    
        		    $sql = "SELECT IF($dataCustomer->KONSINYASI = 1,HARGAKONSINYASI,HARGACORET) as HARGAPROMO FROM MHARGA WHERE IDBARANG = $itemHeader->IDBARANG AND IDCUSTOMER = $dataCustomer->IDCUSTOMER";
         
                    $hargaPromo = $CI->db->query($sql)->row()->HARGAPROMO;  
                    if($hargaPromo > 0)
                    {
                        $detailParameter[count($detailParameter)-1]['Sku']['special_price'] =  (int)$hargaPromo;
                    }
    		   }
		    }
		}
		else
		{
		    $sql = "SELECT IDPERUSAHAAN, IDBARANG FROM MBARANG WHERE KATEGORI like '".str_replace("%2F","%",str_replace("%7C","%",str_replace("%20","%",$this->input->post("NAMA"))))."'";
            
            $itemHeader = $CI->db->query($sql)->row();
            $result   = get_saldo_stok_new($itemHeader->IDPERUSAHAAN,$itemHeader->IDBARANG, $idlokasiset, date('Y-m-d'));
            $saldoQty = $result->QTY??0;
            if($saldoQty < 0)
            {
                $saldoQty = 0;
            }
            
		    $detailParameter = ['Sku' => [[
		        'Status' => 'active',
		        'SellerSku' => $skuInduk,
		        'quantity' => (int)$saldoQty,
		        'price' => (int)$hargaInduk,
		      //  'special_price' => (int)$hargaPromo,
		      //  'special_from_date' => "2025-06-20 17:18:31",
		      //  'special_to_date' => "2026-06-20 17:18:31",
		        'package_height' => (float)$this->input->post("TINGGI"),
		        'package_length' => (float)$this->input->post("PANJANG"),
		        'package_width' => (float)$this->input->post("LEBAR"),
		        'package_weight' => ((float)$this->input->post("BERAT")/1000),
		    ]]];
		    
            
            $sql = "SELECT IF($dataCustomer->KONSINYASI = 1,HARGAKONSINYASI,HARGACORET) as HARGAPROMO FROM MHARGA WHERE IDBARANG = $itemHeader->IDBARANG AND IDCUSTOMER = $dataCustomer->IDCUSTOMER";
         
            $hargaPromo = $CI->db->query($sql)->row()->HARGAPROMO;  
            if($hargaPromo > 0)
            {
                $detailParameter['Sku'][0]['special_price'] =  (int)$hargaPromo;
            }
		}
		
	    $arrayImageProduk = [];
	    foreach($dataGambarProduk as $itemGambarProduk)
	    {
	        array_push($arrayImageProduk,array(
	            'Image' => $itemGambarProduk
	        ));
	    }
	    
	    

		$parameter = [[
		    'xml' => 1,
		    'parameterKey' => 'payload',
		    'parameter' => [
                   'Request' => [
                       'Product' => [
                           'PrimaryCategory' => (int)$kategoriBarang,
                           'Images' => $arrayImageProduk,
                            'Attributes' => [
                                'name' => $this->input->post("NAMA"),
                                'description' => trim($this->input->post("DESKRIPSI")),
                                'brand' => "No Brand",
                            ],
                            'Skus' => $detailParameter
                       ]
                   ]
            ]
        ]];
        
        //KHUSUS SEPATU BAYI
        if($kategoriBarang == 14010){
            $parameter[0]['parameter']['Request']['Product']['Attributes']['material_filter'] = "PU Leather";
        }
        
        if(count($dataVarian) > 0)
        {
            $arrWarnaOption = [];
            for($iv = 0 ; $iv < count($dataWarna); $iv++)
            {
                array_push($arrWarnaOption,[
                    'option' =>   $dataWarna[$iv]  
                ]);
            }
            
            $arrSizeOption = [];
            for($iv = 0 ; $iv < count($dataUkuran); $iv++)
            {
                array_push($arrSizeOption,[
                    'option' =>   $dataUkuran[$iv]  
                ]);
            }
            
            if(count($dataWarna) > 0)
            {
                $parameter[0]['parameter']['Request']['Product']['variation']['variation1']['name']= 'color_family';
                $parameter[0]['parameter']['Request']['Product']['variation']['variation1']['label']= 'WARNA';
                $parameter[0]['parameter']['Request']['Product']['variation']['variation1']['hasImage']= 'true';
                $parameter[0]['parameter']['Request']['Product']['variation']['variation1']['customize']= 'true';
                $parameter[0]['parameter']['Request']['Product']['variation']['variation1']['options']= $arrWarnaOption;
            }
            
            if(count($dataUkuran) > 0)
            {
                $parameter[0]['parameter']['Request']['Product']['variation']['variation2']['name']= 'size';
                $parameter[0]['parameter']['Request']['Product']['variation']['variation2']['label']= 'SIZE';
                $parameter[0]['parameter']['Request']['Product']['variation']['variation2']['hasImage']= 'false';//false
                $parameter[0]['parameter']['Request']['Product']['variation']['variation2']['customize']= 'false';
                $parameter[0]['parameter']['Request']['Product']['variation']['variation2']['options']= $arrSizeOption;
            }
        }
		
		$endPoint = "/product/create";
		$skuRemove = [];
		if($idBarang != 0)
		{
		    $parameter[0]['parameter']['Request']['Product']['ItemId'] = (int)$idBarang;
		    
		    if(count($dataVarian) > 0)
		    {
		       for($s = 0 ; $s < count($dataVarian);$s++)
    		   {
    		       $parameter[0]['parameter']['Request']['Product']['AssociatedSku'] =  $parameter[0]['parameter']['Request']['Product']['Skus'][0]['Sku']['SkuId'];
    		       if($dataVarian[$s]->MODE != 'HAPUS')
    		       {
        		       //BERARTI ADA VARIAN BARU
        		       if($dataVarian[$s]->IDBARANGLAZADA != "")
        		       {
        		            $parameter[0]['parameter']['Request']['Product']['Skus'][$s]['Sku']['SkuId'] = $dataVarian[$s]->IDBARANGLAZADA;
        		       }
    		       }
    		       else if($dataVarian[$s]->MODE == 'HAPUS')
    		       {
    		           array_push($skuRemove,[
    		              'Sku' => [
    		                    'SkuId' =>  $dataVarian[$s]->IDBARANG
    		                ] 
    		           ]);
    		       }
    		   }
    		   
    		   if(count($skuRemove) > 0)
    		   {
    		        $varationSpec = [];
    		        if(count($dataWarna) > 0)
                    {
                        array_push($varationSpec,array(
                            'variation1' => array(
                                'name' => 'color_family'
                            )
                        ));
                    } 
                    
                    if(count($dataUkuran) > 0)
                    {
                        array_push($varationSpec,array(
                            'variation2' => array(
                                'name' => 'size'
                            )
                        ));
                    } 
        		   //JIKA ADA VARIAN DIHAPUS 
        		   
        		   $parameterRemove = [[
            		    'xml' => 1,
            		    'parameterKey' => 'payload',
            		    'parameter' => [
                               'Request' => [
                                   'Product' => [
                                        'ItemId' => (int)$idBarang,
                                        'Skus' => $skuRemove
                                   ]
                               ]
                        ],
                        'variation' => $varationSpec
                    ]];
                    $curl = curl_init();
                    
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>  array(
                      'endpoint' => '/product/sku/remove',
                      'parameter' => json_encode($parameterRemove),
                    //   'debug' => 1
                      ),
                      CURLOPT_HTTPHEADER => array(
                        'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                      ),
                    ));
                      
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $ret =  json_decode($response,true);
                    //ANEH ERROR TERUS, TAPI KEHAPUS
                    // if($ret['code'] != 0)
                    // { 
                    //     $ret['success'] = false;
                    //     $ret['msg'] = $ret['message'];
                    //     die(json_encode($ret));
                    // }
    		   }
            
		    }
		    else
		    {
    		    $sql = "SELECT IDBARANGLAZADA FROM MBARANG WHERE IDINDUKBARANGLAZADA = $idBarang";
    		    $parameter[0]['parameter']['Request']['Product']['Skus']['Sku'][0]['SkuId'] = explode("_",$CI->db->queryRaw($sql)->row()->IDBARANGLAZADA)[1];
    		        
		    }
		    $endPoint = "/product/update";
		}
		
		$itemid = 0;
		//TAMBAH BARANG
        $curl = curl_init();
        // print_r($parameter);
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => $endPoint,
          'parameter' => json_encode($parameter),
        //   'debug' => 1
          ),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
    
        if($ret['code'] != 0)
        { 
            $ret['success'] = false;
            $ret['msg'] = $ret['message'];
            die(json_encode($ret));
        }
        else
        {
            if($idBarang == 0)
		    {
                $itemid = $ret['data']['item_id'];
                $dataList = $ret['data']['sku_list'];
                if(count($dataVarian) > 0)
                {
                    foreach($dataList as $itemVarian)
    		        {
    		            $sql = "UPDATE MBARANG SET 
                               IDBARANGLAZADA = '".$itemVarian['sku_id']."', 
                               IDINDUKBARANGLAZADA = '".$itemid."' 
                               WHERE SKULAZADA = '".strtoupper($itemVarian['seller_sku'])."'";
                        $CI->db->queryRaw($sql);
    		        }
                }
                else
                {
                    foreach($dataList as $itemVarian)
    		        {
                        $sql = "UPDATE MBARANG SET 
                               IDBARANGLAZADA = '".$itemid."_".$itemVarian['sku_id']."', 
                               IDINDUKBARANGLAZADA = '".$itemid."' 
                               WHERE SKULAZADA = '".strtoupper($itemVarian['seller_sku'])."'";
                        $CI->db->queryRaw($sql);
    		        }
                }
		    }
		    else
		    {
		        $itemid = $idBarang;
        
                if(count($dataVarian) > 0)
                {
                    
        		    //GET PRODUCT FOR UPDATE ID
        		
                    $parameter = "&item_id=".(int)$itemid;
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS => array('endpoint' => '/product/item/get','parameter' => $parameter),
                      CURLOPT_HTTPHEADER => array(
                        'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                      ),
                    ));
                    
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $ret =  json_decode($response,true);
                
                    if($ret['code'] != 0)
                    {
                        $ret['success'] = false;
                        $ret['msg'] = $ret['message'];
                        die(json_encode($ret));
                    }
                    else
                    {
                        $dataList = $ret['data']['skus'];
                    
                        foreach($dataList as $itemVarian)
                	    {
                	        $sql = "UPDATE MBARANG SET 
                                   IDBARANGLAZADA = '".$itemVarian['SkuId']."', 
                                   IDINDUKBARANGLAZADA = '".$itemid."' 
                                   WHERE SKULAZADA = '".strtoupper($itemVarian['SellerSku'])."'";
                            $CI->db->queryRaw($sql);
                	    }
                    }
                }
		    }
            
            sleep(5);
            
            if($this->input->post("AKTIF") == 0)
            {
                $endPoint = "/product/deactivate";
                
                $parameterDeactivated = [[
        		    'xml' => 1,
        		    'parameterKey' => 'apiRequestBody',
        		    'parameter' => [
                           'Request' => [
                               'Product' => [
                                   'ItemId' => (int)$itemid
                               ]
                           ]
                    ]
                ]];
        		//DEACTIVATED
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  array(
                  'endpoint' => $endPoint,
                  'parameter' => json_encode($parameterDeactivated),
                //   'debug' => 1,
                  ),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                // print_r($ret);
                if($ret['code'] != 0)
                { 
                    $ret['success'] = false;
                    die(json_encode($ret));
                }
            }
             
            $data['success'] = true;
            $data['msg'] = "Barang berhasil tersimpan di Lazada";
            echo(json_encode($data));
        }
	   
	}
	
	function removeBarang(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$idBarang = $this->input->post("idindukbaranglazada")??0;
		$idSKU = $this->input->post("skulistlazada")??[];
		$arrIDSKU = json_decode($idSKU,true);
        
        $arrIDBarangSKU = [];
        
        foreach($arrIDSKU as $itemIDSKU)
        {
            array_push($arrIDBarangSKU,'SkuId_'.$idBarang.'_'.$itemIDSKU['SkuId']);
        }
        
		$parameter = [[
		    'xml' => 0,
		    'parameterKey' => 'sku_id_list',
		    'parameter' => json_encode($arrIDBarangSKU)
        ]];
		
	    $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => '/product/remove',
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);

        if($ret['code'] != 0 && $ret['code'] != 100)
        { 
            $ret['success'] = false;
            $ret['msg'] = $ret['message'];
            die(json_encode($ret));
        }
        else
        {
            $sql = "UPDATE MBARANG SET IDBARANGLAZADA = '0' , IDINDUKBARANGLAZADA = '0' WHERE IDINDUKBARANGLAZADA = '".$idBarang."'";
            $CI->db->query($sql);
            
            sleep(3);
            
            $data['success'] = true;
            $data['msg'] = "Barang Berhasil Dihapus dari Lazada";
            echo(json_encode($data));
        }
	}
	
	public function getCustomer(){
	    $this->output->set_content_type('application/json');
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
		$sql = "SELECT 
            ROW_NUMBER() OVER (ORDER BY USERNAME ASC) AS NO,
            CONCAT(NAME,' (',USERNAME,') ') AS NAMA,
            TELP,
            ALAMAT,
            KOTA,
            SUM(TOTALBARANG) AS TOTALBARANG,
            SUM(TOTALBAYAR) AS TOTALBAYAR,
            COUNT(IDPENJUALANMARKETPLACE) AS TOTALPESANAN,
            SUM(CASE WHEN KODEPENGEMBALIANMARKETPLACE = ''  THEN 1 ELSE 0 END) AS TOTALPESANANSUKSES,
            SUM(CASE WHEN KODEPENGEMBALIANMARKETPLACE != '' THEN 1 ELSE 0 END) AS TOTALPESANANRETUR,
            USERNAME
        FROM TPENJUALANMARKETPLACE
        WHERE STATUSMARKETPLACE != 'CANCELLED' AND MARKETPLACE = 'LAZADA'
        GROUP BY USERNAME, NAME, TELP, ALAMAT, KOTA
        ORDER BY NO ASC";
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
		$data['rows'] = [];
        $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI, IFNULL(NAMALOKASI,'') as NAMALOKASI, IDLOKASILAZADA, if(IDLOKASILAZADA = 1,'PICKUP_ADDRESS ,   RETURN_ADDRESS','') as LABEL   FROM MLOKASI WHERE GROUPLOKASI like '%MARKETPLACE%'";
        $dataAddress = $CI->db->query($sql)->result();
        
        for($x = 0 ; $x < count($dataAddress);$x++)
        {
            array_push($data['rows'],array(
                'NO' => ($x+1),
                'IDADDRESSAPI' => $dataAddress[$x]->IDLOKASI,
                'ADDRESSAPI' => $dataAddress[$x]->NAMALOKASI." <br><i>".$dataAddress[$x]->LABEL."</i>",
                'ADDRESSAPIRAW' => $dataAddress[$x]->NAMALOKASI,
                'LABELDEFAULT' => ($dataAddress[$x]->IDLOKASILAZADA == 1 ? true : false),
                'ADDRESS' => $dataAddress[$x]->IDLOKASI,
                'LABELADDRESS' => $dataAddress[$x]->NAMALOKASI,
            ));
        }
        echo(json_encode($data));
	}
	
	public function setStokLokasi(){
	    $this->output->set_content_type('application/json');
        $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
        $id = $this->input->post('id')??"0";
        $value = $this->input->post('value')??"false";
   
        //RESET SEMUA
        $CI->db->where("IDLOKASILAZADA",1)
                    ->set("IDLOKASILAZADA",0)
                    ->updateRaw("MLOKASI");

        if($value == "true")
        {
            //UPDATE TERBARU     
            $CI->db->where("IDLOKASI",$id)
                    ->set("IDLOKASILAZADA","1")
                    ->updateRaw("MLOKASI");
        }
                    
        $data['success'] = true;            
        echo json_encode($data); 
	}
	
	public function cekStokLokasi(){
	    $this->output->set_content_type('application/json');
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
	    //CEK LOKASI SUDAH DISET
		$curl = curl_init();
        
        $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASILAZADA = 1 AND GROUPLOKASI like '%MARKETPLACE%'";
        $idlokasiSet = $CI->db->query($sql)->row()->IDLOKASI??0;
        
        if($idlokasiSet == 0)
        {
            $data['success'] = false;
            $data['msg'] = "Terdapat Lokasi Marketplace dengan Master Lokasi yang belum tersambung";
            die(json_encode($data));
        }
        
        $data['success'] = true;
        echo json_encode($data);
	}
	

	
	function dataGridPromo(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
	    $sql = "SELECT KATEGORI, IDBARANGLAZADA,IDINDUKBARANGLAZADA,NAMABARANG FROM MBARANG WHERE IDBARANGLAZADA != '' and IDBARANGLAZADA != '0' and IDBARANGLAZADA is not null group by kategori";
	    
	    $dataBarang = $CI->db->query($sql)->result();
	    $arrBarang = [];
	    foreach($dataBarang as $itemBarang)
	    {
    	    $parameter = '';
            //GET MODEL
            $parameter = "&item_id=".(int)$itemBarang->IDINDUKBARANGLAZADA;
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => '/product/item/get','parameter' => $parameter),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
    
            if($ret['code'] != 0)
            {
                $ret['success'] = false;
                $ret['msg'] = $ret['message'];
                die(json_encode($ret));
            }
            else
            {
                 $dataLazada = $ret['data']['skus'];
                 $sqlDetail = "SELECT IDBARANGLAZADA,IDINDUKBARANGLAZADA,NAMABARANG,SKULAZADA FROM MBARANG WHERE IDBARANGLAZADA != '' and IDBARANGLAZADA != '0' and IDBARANGLAZADA is not null and kategori = '".$itemBarang->KATEGORI."'";
	    
	             $dataDetaiBarang = $CI->db->query($sqlDetail)->result();
	             
	            for($x = 0 ; $x < count($dataLazada); $x++)
	            {
    	            foreach($dataDetaiBarang as $itemdetailbarang)
    	            {
    	                if($dataLazada[$x]['SkuId'] == $itemdetailbarang->IDBARANGLAZADA)
    	                {
        	              array_push($arrBarang,array(
        	                  'IDBARANGLAZADA' => $itemdetailbarang->IDBARANGLAZADA,
        	                  'IDINDUKBARANGLAZADA' => $itemdetailbarang->IDINDUKBARANGLAZADA,
        	                  'NAMABARANG' =>  $itemdetailbarang->NAMABARANG,
        	                  'SKULAZADA' => $itemdetailbarang->SKULAZADA,
        	                  'HARGA' =>    $dataLazada[$x]['price'],
        	                  'HARGAPROMO' =>  $dataLazada[$x]['special_price']??0,
        	                  'PROMOMULAI' => $dataLazada[$x]['special_from_date']??"",
        	                  'PROMOBERAKHIR' => $dataLazada[$x]['special_to_date']??"",
        	              ));  
    	                }
    	            }
	            }
            }
	    }
	    
	     //URUTKAN BERDASARKAN NAMA BARANG
        usort($arrBarang, function($a, $b) {
            return strcmp($a['NAMABARANG'], $b['NAMABARANG']);
        });
        
	    $data['rows'] = $arrBarang;
	    $data['total'] = count($data['rows']);
	    
	     echo(json_encode($data));
	}
	
	function setPromo(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$dataBarangHarga   = json_decode($this->input->post("databarang",[]),true);
		
		$parameter = [];
		$detailParameter = [];
        
		for($x = 0 ; $x < count($dataBarangHarga); $x++)
		{
             array_push($detailParameter,
                   ['Sku' => [
                        'ItemId' => $dataBarangHarga[$x]['IDINDUKBARANGLAZADA'],
                        'SkuId' => $dataBarangHarga[$x]['IDBARANGLAZADA'],
            	        'SellerSku' => $dataBarangHarga[$x]['SKULAZADA'],
            	        'Price' => (int)$dataBarangHarga[$x]['HARGA'],
            	        'SalePrice'  => (int)$dataBarangHarga[$x]['HARGAPROMO'],
            	        'SaleStartDate' => $dataBarangHarga[$x]['PROMOMULAI'],
            	        'SaleEndDate' =>$dataBarangHarga[$x]['PROMOBERAKHIR'],
            	    ]]);
            	    
        	if(($x % 19 == 0 && $x != 0) || $x == count($dataBarangHarga)-1)
            {
                $parameter = [[
            	    'xml' => 1,
            	    'parameterKey' => 'payload',
            	    'parameter' => [
                           'Request' => [
                               'Product' => [
                                    'Skus' => $detailParameter
                               ]
                           ]
                    ]
                ]];
                
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  array(
                  'endpoint' => '/product/price_quantity/update',
                  'parameter' => json_encode($parameter),
                //   'debug' => 1
                  ),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                
                if($ret['code'] != 0)
                { 
                    $ret['success'] = false;
                    $ret['msg'] = $ret['message'];
                    die(json_encode($ret));
                }
                else
                {
                   $parameter = [];
                   $detailParameter = [];
                }
            }
		}
        
        $data['success'] = true;
        $data['msg'] = "Promo Produk pada Lazada Berhasil Disimpan";
        
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
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
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
        if($ret['code'] != 0)
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $data['success'] = true;
            $data['msg'] = "Promo Produk pada LAZADA Berhasil Dihapus";
            echo(json_encode($data));
        }
	}
	
	function setBoost(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$dataBarangPermanent = json_decode($this->input->post("databarangpermanent"),true);
		$dataBarangAll = json_decode($this->input->post("databarangall"),true);
		
		$CI->db->set('BOOSTLAZADA',0)
    	     ->update('MBARANG');
    	     
        foreach($dataBarangAll as $itemBarangAll)
		{
             $CI->db->where("IDINDUKBARANGLAZADA",$itemBarangAll)
    	     ->set('BOOSTLAZADA',1)
    	     ->update('MBARANG');
		}
		
		foreach($dataBarangPermanent as $itemBarangPermanent)
		{
             $CI->db->where("IDINDUKBARANGLAZADA",$itemBarangPermanent)
    	     ->set('BOOSTLAZADA',2)
    	     ->update('MBARANG');
		}
	     
        $data['success'] = true;
        $data['msg'] = "Jadwal Naikkan Produk pada LAZADA Berhasil Disimpan";
        echo(json_encode($data));
	}
	
	function getBoost(){
	   //$CI =& get_instance();	
    //   $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
	   //$this->output->set_content_type('application/json');
	   
	   //$data['rows'] = [];
	   
	   //$curl = curl_init();
	    
	   //curl_setopt_array($curl, array(
    //      CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
    //      CURLOPT_RETURNTRANSFER => true,
    //      CURLOPT_ENCODING => '',
    //      CURLOPT_MAXREDIRS => 10,
    //      CURLOPT_TIMEOUT => 30,
    //      CURLOPT_FOLLOWLOCATION => true,
    //      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //      CURLOPT_CUSTOMREQUEST => 'POST',
    //      CURLOPT_POSTFIELDS => array('endpoint' => 'product/get_boosted_list','parameter' => $parameter),
    //      CURLOPT_HTTPHEADER => array(
    //       'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
    //      ),
    //   ));
       
    //   $response = curl_exec($curl);
    //   curl_close($curl);
    //   $ret =  json_decode($response,true);
    //   if($ret['code'] != 0)
    //   {
    //       echo $ret['error']." : ".$ret['message'];
    //   }
    //   else
    //   {
    //       $itemList = $ret['response']['item_list'];
    //       $sql = "SELECT * FROM MBARANG WHERE BOOSTLAZADA != 0 group by KATEGORI";
    //       $dataBarang = $CI->db->query($sql)->result();
           
    //         foreach($dataBarang as $itemBarang)
    //         {
    //           if($itemBarang->IDINDUKBARANGLAZADA != 0)
    //           {
    //               $waktu = "-";
    //               for($i = 0 ; $i < count($itemList) ; $i++)
    //               {
    //                   if($itemBarang->IDINDUKBARANGLAZADA == $itemList[$i]['item_id'])
    //                   {
    //                       $waktu = $itemList[$i]['cool_down_second'];
    //                   }
    //               }
                       
    //                 array_push($data['rows'],array(
    //                     'PERMANENT' => $itemBarang->BOOSTLAZADA == 2 ? true : false,
    //                     'ID'        => $itemBarang->IDINDUKBARANGLAZADA,
    //                     'NAMA'      => $itemBarang->KATEGORI,
    //                     'WAKTU'     => $waktu
    //                 ));
    //           }
    //         }
    //   }
       
    //   usort($data['rows'], function($a, $b) {
    //       return strcmp($a['NAMA'], $b['NAMA']);
    //   });
       
    //   	echo json_encode($data); 
    echo json_encode([]);
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
            $whereStatus = "and b.STATUS = $state";
            $statusVar = "b.STATUSMARKETPLACE";
            
            if($state == 4)
            {
                $statusVar = "CONCAT(b.STATUSMARKETPLACE,'|',b.STATUSPENGEMBALIANMARKETPLACE)";
            }
        }
        else
        {
            $statusKhusus = explode("|",$status[0]);
            $statusGabungan = [];
            
            for($s = 1 ; $s < count($statusKhusus) ; $s++)
            {
                array_push($statusGabungan,$statusKhusus[$s]);
            }
            //KHUSUS RETUR
            if(count($statusKhusus) > 2)
            {
                $whereStatus = "b.STATUSPENGEMBALIANMARKETPLACE = '" . implode("' OR b.STATUSPENGEMBALIANMARKETPLACE = '", $statusGabungan) . "'";
                $whereStatus = "and b.STATUSMARKETPLACE = '".$statusKhusus[0]."' and (".$whereStatus.")";
                $statusVar = "CONCAT(b.STATUSMARKETPLACE,'|',b.STATUSPENGEMBALIANMARKETPLACE)";
            }
            else
            {
                $whereStatus = "and b.STATUSMARKETPLACE = '$status[0]'";
                $statusVar = "b.STATUSMARKETPLACE";
            }
        }
        
        if($state != 4)
        {
            $sql = "SELECT IDPENJUALANDARIMARKETPLACE as IDPESANAN, KODEPENJUALANMARKETPLACE as KODEPESANAN, TGLTRANS as TGLPESANAN, if(MINTGLKIRIM = '0000-00-00 00:00:00','-',MINTGLKIRIM) as MINTGLKIRIM, $statusVar AS STATUS,KODEPENGAMBILAN,
                            SKUPRODUK, '' as BARANG, TOTALBARANG, TOTALHARGA, TOTALBAYAR,  '' as ALAMAT,SKUPRODUKOLD,USERNAME,
                            NAME as BUYERNAME, TELP as BUYERPHONE, ALAMAT as BUYERALAMAT, KOTA,
                            METODEBAYAR, KURIR, RESI, CATATANPEMBELI as CATATANBELI, CATATANPENJUAL AS CATATANJUAL, CATATANPENGEMBALIAN,KODEPACKAGING,
                            KODEPENGEMBALIANMARKETPLACE as KODEPENGEMBALIAN, TGLPENGEMBALIAN, MINTGLPENGEMBALIAN, RESIPENGEMBALIAN, TOTALBARANGPENGEMBALIAN,MINTGLKIRIMPENGEMBALIAN,
                            TOTALPENGEMBALIANDANA, SKUPRODUKPENGEMBALIAN, '' as BARANGPENGEMBALIAN, TIPEPENGEMBALIAN, SELLERMENUNGGUBARANGDATANG,BARANGSAMPAI,BARANGSAMPAIMANUAL,STATUSPENGEMBALIANMARKETPLACE as STATUSPENGEMBALIAN
                            FROM TPENJUALANMARKETPLACE b WHERE MARKETPLACE = 'LAZADA' and TGLTRANS BETWEEN '".$tgl_aw."' and '".$tgl_ak."' $whereStatus 
                            order by TGLTRANS DESC";
        }
        else
        {
            $sql = "SELECT  a.IDPENJUALANDARIMARKETPLACE as IDPESANAN, b.KODEPENJUALANMARKETPLACE as KODEPESANAN, a.TGLTRANS as TGLPESANAN, if(a.MINTGLKIRIM = '0000-00-00 00:00:00','-',a.MINTGLKIRIM) as MINTGLKIRIM, $statusVar AS STATUS,a.KODEPENGAMBILAN,
                            a.SKUPRODUK, '' as BARANG, a.TOTALBARANG, a.TOTALHARGA, a.TOTALBAYAR,  '' as ALAMAT,a.SKUPRODUKOLD,a.USERNAME,
                            a.NAME as BUYERNAME, a.TELP as BUYERPHONE, a.ALAMAT as BUYERALAMAT, a.KOTA,
                            a.METODEBAYAR, a.KURIR, a.RESI, a.CATATANPEMBELI as CATATANBELI, a.CATATANPENJUAL AS CATATANJUAL, b.CATATANPENGEMBALIAN,KODEPACKAGING,
                            b.KODEPENGEMBALIANMARKETPLACE as KODEPENGEMBALIAN, b.TGLPENGEMBALIAN, b.MINTGLPENGEMBALIAN, b.RESIPENGEMBALIAN, 0 as TOTALBARANGPENGEMBALIAN,b.MINTGLKIRIMPENGEMBALIAN,
                            SUM(b.TOTALPENGEMBALIANDANA) as TOTALPENGEMBALIANDANA, group_concat(b.SKUPRODUKPENGEMBALIAN SEPARATOR '|') as SKUPRODUKPENGEMBALIAN, '' as BARANGPENGEMBALIAN, b.TIPEPENGEMBALIAN, a.SELLERMENUNGGUBARANGDATANG,SUM(IF(b.BARANGSAMPAI = 1,1,0)) as BARANGSAMPAI,SUM(IF(b.BARANGSAMPAIMANUAL = 1,1,0)) as BARANGSAMPAIMANUAL,b.STATUSPENGEMBALIANMARKETPLACE as STATUSPENGEMBALIAN
                            FROM TPENJUALANMARKETPLACEDTL b
                            INNER JOIN TPENJUALANMARKETPLACE a ON b.IDPENJUALANMARKETPLACE = a.IDPENJUALANMARKETPLACE
                            WHERE a.MARKETPLACE = 'LAZADA' and b.TGLPENGEMBALIAN BETWEEN '".$tgl_aw."' and '".$tgl_ak."' $whereStatus 
                            group by b.KODEPENGEMBALIANMARKETPLACE
                            order by a.TGLTRANS DESC";
        }
        
        $result = $CI->db->query($sql)->result();
        
        foreach($result as $item)
        {
            
            if($state == 4)
            {
                 $skukembalidata = explode("|",$item->SKUPRODUKPENGEMBALIAN);

                  for($j = 0 ; $j < count($skukembalidata); $j++)
                  {
                     $item->TOTALBARANGPENGEMBALIAN += (int)(explode("*",$skukembalidata[$j])[0]);
                  }
            }
            // else
            // {
            //     $sqlDetail = "SELECT GROUP_CONCAT(KODEPENGEMBALIANMARKETPLACE SEPARATOR ', ') as KODEPENGEMBALIANMARKETPLACE FROM TPENJUALANMARKETPLACEDTL WHERE KODEPENJUALANMARKETPLACE = '$item->KODEPESANAN' and (KODEPENGEMBALIANMARKETPLACE != '' AND KODEPENGEMBALIANMARKETPLACE IS NOT NULL)";
            //     $item->KODEPENGEMBALIAN = $CI->db->query($sqlDetail)->row()->KODEPENGEMBALIANMARKETPLACE;
                
            // }
          
            $produk = explode("|",$item->SKUPRODUK);
            $produkOld = explode("|",$item->SKUPRODUKOLD);

            $item->STATUS = $this->getStatus([($item->STATUS == "RETURNED"?$item->STATUS."|".$item->STATUSPENGEMBALIAN:$item->STATUS)])['status'];
            $item->TGLPESANAN = explode(" ",$item->TGLPESANAN)[0]."<br>".explode(" ",$item->TGLPESANAN)[1];
            $item->TGLPENGEMBALIAN = explode(" ",$item->TGLPENGEMBALIAN)[0]."<br>".explode(" ",$item->TGLPENGEMBALIAN)[1];
            
            //GET NAMA BARANG
            $sql = "SELECT NAMABARANG, WARNA, SIZE
                        FROM MBARANG WHERE SKULAZADA = '".explode("*",$produk[0])[1]."'";
            $dataBarang = $CI->db->query($sql)->row();
            $item->BARANG  = explode(" | ",$dataBarang->NAMABARANG)[0];
            
            $sqlOld = "SELECT WARNA, SIZE
                        FROM MBARANG WHERE SKULAZADA = '".explode("*",$produkOld[0])[1]."'";
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
                            FROM MBARANG WHERE SKULAZADA = '".explode("*",$produk[$indexPengganti])[1]."'";
                $dataBarang = $CI->db->query($sql)->row();
             
                $item->BARANGPENGEMBALIAN  = explode(" | ",$dataBarang->NAMABARANG)[0];
                
                $sqlOld = "SELECT WARNA, SIZE
                        FROM MBARANG WHERE SKULAZADA = '".explode("*",$produkOld[$indexPengganti])[1]."'";
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
            
            $item->BUYERALAMAT = "<div style='width:250px; white-space: pre-wrap;      /* CSS3 */   
                                                white-space: -moz-pre-wrap; /* Firefox */    
                                                white-space: -pre-wrap;     /* Opera <7 */   
                                                white-space: -o-pre-wrap;   /* Opera 7 */    
                                                word-wrap: break-word;      /* IE */'>". $item->BUYERALAMAT."<br>".$item->KOTA."</span></div>";
        
            $item->ALAMAT = "<div style='width:250px; white-space: pre-wrap;      /* CSS3 */   
                                                white-space: -moz-pre-wrap; /* Firefox */    
                                                white-space: -pre-wrap;     /* Opera <7 */   
                                                white-space: -o-pre-wrap;   /* Opera 7 */    
                                                word-wrap: break-word;      /* IE */'>".$item->BUYERNAME." (".$item->USERNAME.")<br>".$item->BUYERPHONE."<br>".$item->BUYERALAMAT."</span></div>";
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
            $item->CATATANJUAL = "<i class='fa fa-edit' id='editNoteLazada' style='cursor:pointer;'></i>
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
		$metodeBayar = $this->input->post('metodebayar')??"";
		
		//PAYMENT BUYER DETAIL
	    $parameter = "&order_id=".$nopesanan;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/order/items/get','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['code'] != 0)
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataPaymentBuyer = $ret['data'];
            // print_r($dataPaymentBuyer);
            
            $result;
            $result['BIAYALAINBELI']        = 0;
            $result['DISKONBELI']           = 0;
            $result['SUBTOTALBELI']         = 0;
            $result['BIAYAKIRIMBELI']       = 0;
            $result['PEMBAYARANBELI']       = 0;
            //PAYMENT SELLER DETAIL
            $result['PENERIMAANJUAL']       = 0;
            $result['DISKONJUAL']           = 0;
            $result['SUBTOTALJUAL']         = 0;
            $result['BIAYAKIRIMJUAL']       = 0;
            $result['REFUNDJUAL']           = 0;
            $result['BIAYALAYANANJUAL']     = 0;
            $result['PENYELESAIANPENJUAL']  = 0;
            
            $biayaLayanan = 1000;
            $biayaPenanganan = 0;
            if (strpos(str_replace("_"," ",$metodeBayar), 'VA') !== false) {
                $biayaPenanganan = 1000;
            }
            else if (strpos(str_replace("_"," ",$metodeBayar), 'INDOMARET') !== false || strpos(str_replace("_"," ",$metodeBayar), 'ALFA') !== false) {
                $biayaPenanganan = 2000;
            }
           
            foreach($dataPaymentBuyer as $itemPaymentBuyer)
            {
                if($itemPaymentBuyer['status'] != 'canceled')
                {
        		    $result['BIAYALAINBELI']        += $itemPaymentBuyer['tax_amount']+(int)(($biayaLayanan+$biayaPenanganan) / count($dataPaymentBuyer)); 
        		    $result['DISKONBELI']           += -($itemPaymentBuyer['voucher_seller']+$itemPaymentBuyer['shipping_fee_discount_seller']+$itemPaymentBuyer['voucher_platform']); 
        		    $result['SUBTOTALBELI']         += $itemPaymentBuyer['item_price']; 
        		    $result['BIAYAKIRIMBELI']       += $itemPaymentBuyer['shipping_amount']; 
                }
            }
            $result['BIAYALAINBELI']        = $this->pembulatanSatuan($result['BIAYALAINBELI']);
            $result['PEMBAYARANBELI']       += $result['SUBTOTALBELI']+$result['BIAYALAINBELI']+$result['BIAYAKIRIMBELI']+$result['DISKONBELI'];
        }
    		    
		$createDate = new DateTime($dataPaymentBuyer[0]['created_at']);
		$createDate = $createDate->format('Y-m-d');

    	$parameter = "&trade_order_id=".$nopesanan."&start_time=".$createDate."&end_time=".date('Y-m-d');
        
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/finance/transaction/details/get','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
 
        if($ret['code'] != 0)
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataPaymentSeller = $ret['data'];
            // print_r($dataPaymentSeller);
            
            $transactiontype = [];
            foreach($dataPaymentSeller as $itemPaymentSeller)
            {
                $itemPaymentSeller['amount'] = str_replace(",","",$itemPaymentSeller['amount']);
                $adaPS = false;
                for($ps = 0 ; $ps < count($transactiontype);$ps++)
                {
                    if($transactiontype[$ps]['name'] == $itemPaymentSeller['fee_name'])
                    {
                        $adaPS = true;
                        $transactiontype[$ps]['amount'] += ($itemPaymentSeller['amount']);
                    }
                }
                
                if(!$adaPS)
                {
                    array_push($transactiontype,array(
                        'name' => $itemPaymentSeller['fee_name'],
                        'amount' => $itemPaymentSeller['amount'],
                    ));
                }
            }
            
            for($pay = 0 ; $pay < count($transactiontype);$pay++)
            {
                
        		  //  $result['PENERIMAANJUAL']       = 0;
            //         $result['DISKONJUAL']           = 0;
            //         $result['SUBTOTALJUAL']         = 0;
            //         $result['BIAYAKIRIMJUAL']       = 0;
            //         $result['REFUNDJUAL']           = 0;
            //         $result['BIAYALAYANANJUAL']     = 0;
            //         $result['PENYELESAIANPENJUAL']  = 0;
            
        		    //PAYMENT SELLER DETAIL
            		$result['DISKONJUAL']           += 0; 
            		if($transactiontype[$pay]['name'] == 'Item Price Credit')
            		{
            		    $result['SUBTOTALJUAL']         += $transactiontype[$pay]['amount']; 
            		}
            		else if($transactiontype[$pay]['name'] == 'Reversal Item Price')
            		{
            		    $result['REFUNDJUAL']         += $transactiontype[$pay]['amount']; 
            		}
            		else
            		{
            		    $result['BIAYALAYANANJUAL']   += $transactiontype[$pay]['amount']; 
            		}
            }
            
            $result['BIAYAKIRIMJUAL']       = $itemPaymentBuyer['shipping_fee_discount_seller']+$itemPaymentBuyer['voucher_seller']; 
            // print_r($transactiontype);
        }
		$result['DETAILBARANG'] = [];
		
		$sql = "SELECT SKUPRODUK, ifnull(SKUPRODUKOLD,'') as SKUPRODUKOLD, SKUPRODUKPENGEMBALIAN, KODEPENGEMBALIANMARKETPLACE
                    FROM TPENJUALANMARKETPLACE 
                    WHERE MARKETPLACE = 'LAZADA' and KODEPENJUALANMARKETPLACE = '$nopesanan' ";
                    
        $resultPesanan = $CI->db->query($sql)->row();
        
        $produkData = explode("|",$resultPesanan->SKUPRODUK);
        $produkDataOld = explode("|",$resultPesanan->SKUPRODUKOLD);
        $dataProduk = [];
        $dataProdukKembali = [];
        $indexProduk = 0;
        foreach($produkData as $item)
        {
            //GET NAMA BARANG
            $sql = "SELECT NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKULAZADA as SKU,IDBARANGLAZADA,IDINDUKBARANGLAZADA,HARGAJUAL
                        FROM MBARANG WHERE SKULAZADA = '".explode("*",$item)[1]."'";
            $dataBarang = $CI->db->query($sql)->row();
            $dataProduk[$indexProduk]->BARANG  = explode(" | ",$dataBarang->NAMABARANG)[0];
            if(count(explode(" | ",$dataBarang->NAMABARANG)) > 1)
            {
                $dataProduk[$indexProduk]->BARANG .= "<br><i>".$dataBarang->WARNA.", ".$dataBarang->SIZE."</i>";
            }
            $dataProduk[$indexProduk]->JML = explode("*",$item)[0];
            $dataProduk[$indexProduk]->ID = $dataBarang->IDINDUKBARANGLAZADA;
            if(count(explode("_",$dataBarang->IDBARANGLAZADA)) > 1)
            {
                $dataProduk[$indexProduk]->MODELID =  explode("_",$dataBarang->IDBARANGLAZADA)[1];
            }
            else
            {
                $dataProduk[$indexProduk]->MODELID = $dataBarang->IDBARANGLAZADA;
            }
            $dataProduk[$indexProduk]->SATUAN = $dataBarang->SATUAN;
            $dataProduk[$indexProduk]->HARGA = $dataBarang->HARGAJUAL;
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
                $sql = "SELECT NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKULAZADA as SKU
                            FROM MBARANG WHERE SKULAZADA = '".explode("*",$item)[1]."'";
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
        
        $countFindRetur = 0;
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
                
                if($countFindRetur < count($produkDataKembali) )
                for($t = 0 ; $t < count($produkDataKembali);$t++)
                {
                    if(explode("*",$produkDataKembali[$t])[1] == $dataProduk[$s]->SKUOLD)
                    {
                         //JIKA ADA YANG BEDA UPDATE LAGI
                        $sql = "SELECT NAMABARANG, WARNA, SIZE,SKULAZADA as SKU
                                    FROM MBARANG WHERE SKULAZADA = '".explode("*",$produkDataKembali[$t])[1]."'";
                        $dataBarangKembali = $CI->db->query($sql)->row();
                    
                        $dataProduk[$s]->BARANGKEMBALI  = explode(" | ",$dataBarangKembali->NAMABARANG)[0];
                        if(count(explode(" | ",$dataBarangKembali->NAMABARANG)) > 1)
                        {
                            $dataProduk[$s]->BARANGKEMBALI .= "<br><i>".$dataBarangKembali->WARNA.", ".$dataBarangKembali->SIZE."</i>";
                        }
                        
                            
                        $dataProduk[$s]->JMLKEMBALI = explode("*", $produkDataKembali[$t])[0];
                        $dataProduk[$s]->WARNAKEMBALI = $dataBarangKembali->WARNA;
                        $dataProduk[$s]->SIZEKEMBALI = $dataBarangKembali->SIZE;
                        $dataProduk[$s]->SKUKEMBALI = $dataBarangKembali->SKU;
                        $countFindRetur++;
                    }
                }
            }
        }
        
        $parameter ="&order_id=".$nopesanan;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/order/items/get','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);

        if($ret['code'] != 0)
        {
            $ret['success'] = false;
            $ret['msg'] = $ret['message'];
            die(json_encode($ret));
        }
        else
        { 
            //CEK BARANG RETUR MANUAL
    		$sql = "SELECT SKU FROM TPENJUALANMARKETPLACEDTL WHERE KODEPENJUALANMARKETPLACE = '".$nopesanan."' AND BARANGSAMPAIMANUAL = 1 AND KODEPENGEMBALIANMARKETPLACE != ''";
    		$resultQtyBarang = $CI->db->query($sql)->result();
    		
    	    for($x = 0; $x < count($dataProduk) ; $x++)
    	    {
    	        $resultDetail;
    	        $resultDetail['KATEGORI'] = $dataProduk[$x]->KATEGORI;
    	        $resultDetail['ITEMID'] = $dataProduk[$x]->ID;
    	        $resultDetail['MODELID'] = $dataProduk[$x]->MODELID;
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
    	        $resultDetail['JUMLAH'] = (int)($dataProduk[$x]->JML??"0");
    	        $resultDetail['JUMLAHKEMBALI'] = (int)($dataProduk[$x]->JMLKEMBALI??"0");
    	        
    	        if($resultDetail['JUMLAHKEMBALI'] == 0)
		        {
		            foreach ($resultQtyBarang as $key => $itemQtyBarang)
                    {
                        if ($itemQtyBarang->SKU == $dataProduk[$x]->SKU)
                        {
                            $resultDetail['JUMLAHKEMBALI'] = 1;
                            unset($resultQtyBarang[$key]);
                        }
                    }
		        }
		        
    	        $resultDetail['SATUAN'] = $dataProduk[$x]->SATUAN;
    	        $resultDetail['HARGATAMPIL'] = $dataProduk[$x]->HARGA;
    	        $resultDetail['HARGACORET'] = 0;
    	        
    	        for($y = 0; $y < count($ret['data']) ; $y++)
    	        {
    	            if($resultDetail['MODELID'] == $ret['data'][$y]['sku_id'])
    	            {
        		        $resultDetail['HARGACORET'] =  $ret['data'][$y]['item_price'];
    	            }
    	        }
    	        
    	        $resultDetail['SUBTOTAL'] =  $resultDetail['JUMLAH'] * $resultDetail['HARGACORET'];
    	        array_push($result['DETAILBARANG'],$resultDetail);
    	    }
    	    
    	   
    	   //$dataDetailRetur = [];
        //   $parameter = "&reverse_order_id=".$resultPesanan->KODEPENGEMBALIANMARKETPLACE;
           
        //   $curl = curl_init();
           
        //   curl_setopt_array($curl, array(
        //      CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
        //      CURLOPT_RETURNTRANSFER => true,
        //      CURLOPT_ENCODING => '',
        //      CURLOPT_MAXREDIRS => 10,
        //      CURLOPT_TIMEOUT => 30,
        //      CURLOPT_FOLLOWLOCATION => true,
        //      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //      CURLOPT_CUSTOMREQUEST => 'POST',
        //      CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/return/detail/list','parameter' => $parameter),
        //      CURLOPT_HTTPHEADER => array(
        //       'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
        //      ),
        //   ));
           
        //   $response = curl_exec($curl);
        //   curl_close($curl);
        //   $retDetail =  json_decode($response,true);
        //   if($retDetail['code'] != 0)
        //   {
        //       echo $retDetail['error']." : ".$retDetail['message'];
        //   }
        //   else
        //   {
        //       $dataDetailRetur = $retDetail['data']['reverseOrderLineDTOList'];
        //   }
           
    	   //$result['DETAILBARANG'] = [];
    	   //for($y = 0; $y < count($ret['data']) ; $y++)
    	   //{
    	   // $resultDetail;
        //     $resultDetail['KATEGORI'] = "-";
        //     $resultDetail['ITEMID'] = 0;
        //     $resultDetail['MODELID'] = $ret['data'][$y]['sku_id'];
        //     $resultDetail['NAMA'] =  $ret['data'][$y]['name']. " <br><i>Tidak terhubung dengan master barang</i>";
        //     $resultDetail['WARNA'] = "";
        //     $resultDetail['SIZE'] = "";
        //     $resultDetail['SKU'] =  $ret['data'][$y]['sku'];
        //     $resultDetail['NAMAOLD'] = $ret['data'][$y]['name'];
        //     $resultDetail['WARNAOLD'] = "";
        //     $resultDetail['SIZEOLD'] = "";
        //     $resultDetail['SKUOLD'] = $ret['data'][$y]['sku'];
        //     $checkReverse = false;
            
        //     for($z = 0 ; $z < count($dataDetailRetur); $z++)
        //     {
        //         if($dataDetailRetur[$z]['reverse_status'] == 'REQUEST_CANCEL')
        //         {
        //             $resultDetail['NAMAKEMBALI'] = $ret['data'][$y]['name'];
        //       	    $resultDetail['WARNAKEMBALI'] = "";
        //       	    $resultDetail['SIZEKEMBALI'] =  "";
        //       	    $resultDetail['SKUKEMBALI'] = $ret['data'][$y]['sku'];
        //       	    $resultDetail['JUMLAHKEMBALI'] = 0;
        //             $checkReverse = true;
        //         }
        //         else if($dataDetailRetur[$z]['trade_order_line_id'] == $ret['data'][$y]['order_item_id'])
        //         {
        //   	        $resultDetail['NAMAKEMBALI'] = $ret['data'][$y]['name'];
        //   	        $resultDetail['WARNAKEMBALI'] = "";
        //   	        $resultDetail['SIZEKEMBALI'] =  "";
        //   	        $resultDetail['SKUKEMBALI'] = $ret['data'][$y]['sku'];
        //   	        $resultDetail['JUMLAHKEMBALI'] = 1;
        //   	        $checkReverse = true;
        //         }
        //     }
            
        //     if(!$checkReverse)
        //     {
        //         $resultDetail['NAMAKEMBALI'] = $ret['data'][$y]['name'];
        //   	    $resultDetail['WARNAKEMBALI'] = "";
        //   	    $resultDetail['SIZEKEMBALI'] =  "";
        //   	    $resultDetail['SKUKEMBALI'] = $ret['data'][$y]['sku'];
        //   	    $resultDetail['JUMLAHKEMBALI'] = 0;
        //     }
            
        //     $resultDetail['JUMLAH'] = 1;
        //     $resultDetail['SATUAN'] = "-";
        //     $resultDetail['HARGATAMPIL'] =  $ret['data'][$y]['item_price'];
        //     $resultDetail['HARGACORET'] =  $ret['data'][$y]['item_price'];
            
        //     $resultDetail['SUBTOTAL'] =  $resultDetail['JUMLAH'] * $resultDetail['HARGACORET'];
        //     array_push($result['DETAILBARANG'],$resultDetail);
    	   //}
           	
           $result['PENERIMAANJUAL']       = ($result['SUBTOTALJUAL'] + $result['BIAYAKIRIMJUAL'] + $result['DISKONJUAL']  + $result['REFUNDJUAL'] + $result['BIAYALAYANANJUAL'] ); 
           $result['PENYELESAIANPENJUAL']  = 	$result['PENERIMAANJUAL'];
    	   
        }
        
		echo(json_encode($result));

	}
	
	public function loadDetailPengembalian(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kode')??"";
		
		//PAYMENT DETAIL
	    $parameter = "&reverse_order_id=".$nopengembalian;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/return/detail/list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['code'] != 0)
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $reason = [];
            $refundAmount = 0;
            $dataDetail = $ret['data']['reverseOrderLineDTOList'];
            $result['REFUNDTYPE'] =  $ret['data']['request_type'];
            
            $result['GAMBAR'] = [];
            $result['VIDEO'] = [];
            
            for($d = 0 ; $d < count($dataDetail);$d++)
            {
                $statusRetur = $dataDetail[$d]['reverse_status'];
            
                if($statusRetur != "REQUEST_CANCEL")
                {
                    $refundAmount += (float)($dataDetail[$d]['refund_amount'] / 100); 
                }
                $adaReason = false;
                for($r = 0 ; $r < count($reason); $r++)
                {
                    if($dataDetail[$d]['reason_text'] == $reason[$r])
                    {
                        $adaReason = true;
                    }
                }
                if(!$adaReason)
                {
                    if(count($reason) > 0)
                    {
                        $reasonText .= ", ";
                    }
                    array_push($reason,$dataDetail[$d]['reason_text']);
                   $reasonText .= $dataDetail[$d]['reason_text'];
                }
                
                //LOAD GAMBAR
        	    $parameter = "&reverse_order_line_id=".$dataDetail[$d]['reverse_order_line_id'];
                
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/return/history/list','parameter' => $parameter),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                if($ret['code'] != 0)
                {
                    echo $ret['error']." : ".$ret['message'];
                }
                else
                {
                    for($g = 0 ; $g < count($ret['data']['list']) ;$g++)
                    {
                        $dataGambar = $ret['data']['list'][$g];
                        if(count($dataGambar['picture']) > 0)
                        {
                            for($p = 0 ; $p < count($dataGambar['picture']); $p++)
                            {
                                array_push($result['GAMBAR'],$dataGambar['picture'][$p]);
                            }
                        }
                    }
                }
                //LOAD GAMBAR
            }
            
            $result['TOTALREFUND'] = $refundAmount;
        
            $result['ALASANPILIHPENGEMBALIAN'] = $reasonText;
            
		    $result['DETAILBARANG'] = [];
		    
		    $sql = "SELECT GROUP_CONCAT(b.SKUPRODUKPENGEMBALIAN SEPARATOR '|') as SKUPRODUKPENGEMBALIAN, a.SKUPRODUK, ifnull(a.SKUPRODUKOLD,'') as SKUPRODUKOLD
                        FROM TPENJUALANMARKETPLACE a
                        INNER JOIN  TPENJUALANMARKETPLACEDTL b  on a.IDPENJUALANMARKETPLACE = b.IDPENJUALANMARKETPLACE
                        WHERE b.MARKETPLACE = 'LAZADA' and b.KODEPENGEMBALIANMARKETPLACE = '$nopengembalian' ";
                        
            $resultPesanan = $CI->db->query($sql)->row();
            $produkDataPengembalian = explode("|",$resultPesanan->SKUPRODUKPENGEMBALIAN);
            $produkData = explode("|",$resultPesanan->SKUPRODUK);
            $produkDataOld = explode("|",$resultPesanan->SKUPRODUKOLD);
            $dataProduk = [];
            $indexProduk = 0;
            
            $itemTidakAda = true;
            foreach($produkData as $item)
            {
                //GET NAMA BARANG
                $sql = "SELECT IDINDUKBARANGLAZADA, NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKULAZADA as SKU
                            FROM MBARANG WHERE SKULAZADA = '".explode("*",$item)[1]."'";
                $dataBarang = $CI->db->query($sql)->row();
                $dataProduk[$indexProduk]->BARANG  = explode(" | ",$dataBarang->NAMABARANG)[0];
                if(count(explode(" | ",$dataBarang->NAMABARANG)) > 1)
                {
                    $dataProduk[$indexProduk]->BARANG .= "<br><i>".$dataBarang->WARNA.", ".$dataBarang->SIZE."</i>";
                }
                $dataProduk[$indexProduk]->IDINDUKBARANGLAZADA = $dataBarang->IDINDUKBARANGLAZADA;
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
                if($dataBarang->NAMABARANG != "")
                {
                    $itemTidakAda = false;
                }
            }
            
            if($resultPesanan->SKUPRODUKOLD != "")
            {
                
                $indexProduk = 0;
                foreach($produkDataOld as $item)
                {
                    //GET NAMA BARANG
                    $sql = "SELECT NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKULAZADA as SKU
                                FROM MBARANG WHERE SKULAZADA = '".explode("*",$item)[1]."'";
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
                            $sql = "SELECT NAMABARANG, WARNA, SIZE, SKULAZADA as SKU
                                        FROM MBARANG WHERE SKULAZADA = '".explode("*",$produkData[$indexPengganti])[1]."'";
                            $dataBarang = $CI->db->query($sql)->row();
                            
                            $sqlOld = "SELECT WARNA, SIZE
                                    FROM MBARANG WHERE SKULAZADA = '".explode("*",$produkDataOld[$indexPengganti])[1]."'";
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
		    
		    for($x = 0; $x < count($dataDetail) ; $x++)
		    {
                if(!$itemTidakAda)
                {
    		        $resultDetail;
    		        $resultDetail['KATEGORI'] = $dataProduk[$x]->KATEGORI;
    		        $resultDetail['ITEMID'] =  $dataDetail[$x]['productDTO']['product_id'];
    		        $resultDetail['MODELID'] = $dataDetail[$x]['productDTO']['sku'];
    		        $resultDetail['NAMA'] = $dataProduk[$x]->BARANG;
    		        $resultDetail['WARNA'] = $dataProduk[$x]->WARNA;
    		        $resultDetail['SIZE'] = $dataProduk[$x]->SIZE;
    		        $resultDetail['SKU'] = $dataProduk[$x]->SKU;
    		        $resultDetail['NAMAOLD'] = $dataProduk[$x]->BARANGOLD;
    		        $resultDetail['WARNAOLD'] = $dataProduk[$x]->WARNAOLD;
    		        $resultDetail['SIZEOLD'] = $dataProduk[$x]->SIZEOLD;
    		        $resultDetail['SKUOLD'] = $dataProduk[$x]->SKUOLD;
    		        $resultDetail['JUMLAH'] = 1;
    		        $resultDetail['SATUAN'] = $dataProduk[$x]->SATUAN;
    		        $resultDetail['HARGA'] =  (float)($dataDetail[$x]['refund_amount'] / 100) ;
    		        $resultDetail['SUBTOTAL'] = ($resultDetail['JUMLAH'] * $resultDetail['HARGA']);
    		        array_push($result['DETAILBARANG'],$resultDetail);
                }
                else
                {
                    $resultDetail;
    		        $resultDetail['KATEGORI'] = "-";
    		        $resultDetail['ITEMID'] =  $dataDetail[$x]['productDTO']['product_id'];
    		        $resultDetail['MODELID'] = $dataDetail[$x]['productDTO']['sku'];
    		        $resultDetail['NAMA'] = "-<br>Barang Tidak terhubung dengan master barang"."<br>".($dataDetail[$x]['reverse_status'] != "REFUND_SUCCESS" && $dataDetail[$x]['reverse_status'] != "REQUEST_CANCEL"?"<i style='color:grey';>Masih Proses</i>":"");
    		        $resultDetail['WARNA'] = "";
    		        $resultDetail['SIZE'] = "";
    		        $resultDetail['SKU'] =  "";
    		        $resultDetail['NAMAOLD'] = "-<br>Barang Tidak terhubung dengan master barang";
    		        $resultDetail['WARNAOLD'] =  "";
    		        $resultDetail['SIZEOLD'] =  "";
    		        $resultDetail['SKUOLD'] =  "";
    		        
    		        $resultDetail['JUMLAH'] = 1;
    		        $resultDetail['SATUAN'] = "";
    		        $resultDetail['HARGA'] =  (float)($dataDetail[$x]['refund_amount'] / 100);
    		        $resultDetail['SUBTOTAL'] =  ($resultDetail['JUMLAH'] * $resultDetail['HARGA']);
    		        array_push($result['DETAILBARANG'],$resultDetail);
                }
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
		$idbaranglist = [];
		$parameter = '';
		$requestType = '';
		
	    $parameter = "&reverse_order_id=".$nopengembalian;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/return/detail/list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['code'] != 0)
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataDetail = $ret['data']['reverseOrderLineDTOList'];
            $requestType = $ret['data']['request_type'];
            
            for($d = 0 ; $d < count($dataDetail);$d++)
            {
                $statusRetur = $dataDetail[$d]['reverse_status'];
                $statusOFC = $dataDetail[$d]['ofc_status'];
          
                if($statusRetur == "REQUEST_INITIATE" || $statusOFC == "RETURN_DELIVERED")
                {
                    array_push($idbaranglist,(int)$dataDetail[$d]['reverse_order_line_id']);
                }
            }
        }
        
        if($requestType == "ONLY_REFUND")
        {
            $parameter = '';
    		$parameter = '&action=agreeRefund&reverse_order_id='.(int)$nopengembalian.'&reverse_order_item_ids='.json_encode($idbaranglist);
    		//REFUND
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                 CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => '',
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => 'POST',
                 CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/onlyrefund/seller/decide','parameter' => $parameter),
                 CURLOPT_HTTPHEADER => array(
                   'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                 ),
            ));
              
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
         
            if($ret['code'] != 0)
            {
                $data['success'] = false;
                $data['msg'] =  $ret['error']." : ".$ret['message'];
                die(json_encode($data));
            }
            else
            {
                sleep(5);
                $this->init(date('Y-m-d'),date('Y-m-d'),'update',false);
            }  
        }
        else if($requestType == "RETURN")
        {
            $action = "instantRefund";
            if($statusOFC == "RETURN_DELIVERED"){
                $action = "agreeRefund";
            }
            $parameter = '';
    		$parameter = '&action='.$action.'&reverse_order_id='.(int)$nopengembalian.'&reverse_order_item_ids='.json_encode($idbaranglist);
    		//REFUND
    // 		echo $parameter;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                 CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => '',
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => 'POST',
                 CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/return/update','parameter' => $parameter),
                 CURLOPT_HTTPHEADER => array(
                   'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                 ),
            ));
              
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
         
            if($ret['code'] != 0)
            {
                $data['success'] = false;
                $data['msg'] =  $ret['error']." : ".$ret['message'];
                die(json_encode($data));
            }
            else
            {
                sleep(5);
                $this->init(date('Y-m-d'),date('Y-m-d'),'update',false);
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
		$nopesanan = $this->input->post('kodepesanan')??"";
		$idbaranglist = [];
		$parameter = '';
		$requestType = '';
		
	    $parameter = "&reverse_order_id=".$nopengembalian;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/return/detail/list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['code'] != 0)
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataDetail = $ret['data']['reverseOrderLineDTOList'];
            $requestType = $ret['data']['request_type'];
            
            for($d = 0 ; $d < count($dataDetail);$d++)
            {
                $statusRetur = $dataDetail[$d]['reverse_status'];
            
                if($statusRetur == "REQUEST_INITIATE")
                {
                    array_push($idbaranglist,(int)$dataDetail[$d]['reverse_order_line_id']);
                }
            }
        }
        
        $parameter = '';
    	$parameter = '&action=agreeReturn&reverse_order_id='.(int)$nopengembalian.'&reverse_order_item_ids='.json_encode($idbaranglist);
    	//REFUND
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
             CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 30,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'POST',
             CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/return/update','parameter' => $parameter),
             CURLOPT_HTTPHEADER => array(
               'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
             ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        
        if($ret['code'] != 0)
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            sleep(5);
            $this->init(date('Y-m-d'),date('Y-m-d'),'update',false);
        }  
     
        if($ret['code'] != 0)
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $data['success'] = true;
            $data['msg'] = "Pengembalian Barang dan Dana #".$nopengembalian." Berhasil Dilakukan";
            echo(json_encode($data));
        }

	}
	
	public function getDispute(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kodepengembalian')??"";
		
		$idbaranglist = [];
		$parameter = '';
		
	    $parameter = "&reverse_order_id=".$nopengembalian;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/return/detail/list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['code'] != 0)
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataDetail = $ret['data']['reverseOrderLineDTOList'];
            $requestType = $ret['data']['request_type'];
            for($d = 0 ; $d < count($dataDetail);$d++)
            {
                $statusRetur = $dataDetail[$d]['reverse_status'];
                $statusOFC = $dataDetail[$d]['ofc_status'];
                
                if($statusOFC == "RETURN_DELIVERED")
                {
                    array_push($idbaranglist,(int)$dataDetail[$d]['reverse_order_line_id']);
                }
            }
        }
        
		
	    $parameter = '';
    	$parameter = '&reverse_order_line_id='.$idbaranglist[0];
    	//REFUND
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
             CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 30,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'POST',
             CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/reason/list','parameter' => $parameter),
             CURLOPT_HTTPHEADER => array(
               'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
             ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        
        if($ret['code'] != 0)
        {
            $data['success'] = false;
            $data['msg'] =  $ret['error']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            echo(json_encode($ret['data']));
        }  

	}
	
	public function dispute(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kodepengembalian')??"";
		$nopesanan = $this->input->post('kodepesanan')??"";
		$alasandispute = $this->input->post('alasandispute')??"";
		$pilihandispute = $this->input->post('pilihandispute')??"";
		$disputeproof = json_decode($this->input->post('disputeproof')??[],true);
		$requestType = '';
		$idbaranglist = [];
		$parameter = '';
		
	    $parameter = "&reverse_order_id=".$nopengembalian;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/return/detail/list','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['code'] != 0)
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataDetail = $ret['data']['reverseOrderLineDTOList'];
            $requestType = $ret['data']['request_type'];
            for($d = 0 ; $d < count($dataDetail);$d++)
            {
                $statusRetur = $dataDetail[$d]['reverse_status'];
                $statusOFC = $dataDetail[$d]['ofc_status'];
                
                if($statusRetur == "REQUEST_INITIATE" || $statusOFC == "RETURN_DELIVERED")
                {
                    array_push($idbaranglist,(int)$dataDetail[$d]['reverse_order_line_id']);
                }
            }
        }
        
        $imageProof = [];
        for($x = 0 ; $x < count($disputeproof); $x++)
        {
            array_push($imageProof,array(
               'name' => $disputeproof[$x]['id'],
               'url' => $disputeproof[$x]['url-baru']
            ));
            
           $folder = FCPATH . '/assets/proof/LAZADA/'; // Use FCPATH . 'assets/proof/' in CodeIgniter
           
           if (!is_dir($folder)) {
               die('Folder does not exist.');
           }
            
           $files = glob($folder . '*'); // Get all files in the folder
            
           foreach ($files as $file) {
               if (is_file($file) && strpos(basename($file), $kode . "_" . $index) !== false) {
                   unlink($file); // Delete file if name contains "a"
               }
           }
                                          
        }
        
        if($requestType == "ONLY_REFUND")
        {
            $parameter = '';
    		$parameter = '&action=startDispute&reverse_order_id='.(int)$nopengembalian.'&reverse_order_item_ids='.json_encode($idbaranglist).'&comment='.$alasandispute.'&image_info_list='.json_encode($imageProof);
    		//REFUND
            $curl = curl_init();
            curl_setopt_array($curl, array(
                 CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => '',
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => 'POST',
                 CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/onlyrefund/seller/decide','parameter' => $parameter),
                 CURLOPT_HTTPHEADER => array(
                   'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                 ),
            ));
              
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
         
            if($ret['code'] != 0)
            {
                $data['success'] = false;
                $data['msg'] =  $ret['error']." : ".$ret['message'];
                die(json_encode($data));
            }
            else
            {
                sleep(5);
                $this->init(date('Y-m-d'),date('Y-m-d'),'update',false);
            }  
        }
        else if($requestType == "RETURN")
        {
            $action = "refuseReturn";
            if($statusOFC == "RETURN_DELIVERED"){
                $action = "refuseRefund";
            }
            
            $parameter = '';
    		$parameter = '&action='.$action.'&reason_id='.(int)$pilihandispute.'&reverse_order_id='.(int)$nopengembalian.'&reverse_order_item_ids='.json_encode($idbaranglist).'&comment='.$alasandispute.'&image_info='.json_encode($imageProof);

    		//REFUND
            $curl = curl_init();
            curl_setopt_array($curl, array(
                 CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => '',
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => 'POST',
                 CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/return/update','parameter' => $parameter),
                 CURLOPT_HTTPHEADER => array(
                  'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                 ),
            ));
              
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
         
            if($ret['code'] != 0)
            {
                $data['success'] = false;
                $data['msg'] =  $ret['error']." : ".$ret['message'];
                die(json_encode($data));
            }
            else
            {
                sleep(5);
                $this->init(date('Y-m-d'),date('Y-m-d'),'update',false);
            }  
        }
     
        
        $data['success'] = true;
        $data['msg'] = "Pengembalian Dana #".$nopengembalian." Berhasil Dilakukan";
        echo(json_encode($data));

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
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
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
     
        if($ret['code'] != 0)
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
		$index = $this->input->post('index')??"0";
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
                $data['success'] = true;
                $data['url'] = $this->config->item('base_url')."/assets/".$this->input->post('reason')."/". $kode . "_" . $index.$type."?t=".date('Ymdhms');
                $data['thumbnail'] =  $data['url'];
                $data['id'] = $index;
                $data['urlLocal'] =  $data['url'];
            	echo(json_encode($data));
           }
        }
	    
	}
	
	public function changeLocalUrl(){

	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$url = $this->input->post('url')??[];
		$dataUrl = json_decode($url,true);
		$response =  $this->getRefreshToken();
        $error = 0;
        if($response)
        {
             $appKey = $this->model_master_config->getConfigMarketplace('LAZADA','APP_KEY');
             $accessToken = $this->model_master_config->getConfigMarketplace('LAZADA','ACCESS_TOKEN');
             $appSecret = $this->model_master_config->getConfigMarketplace('LAZADA','APP_SECRET');
         
             $this->load->library('Lazop', [
                 'appKey'    => $appKey,
                 'appSecret' => $appSecret
             ]);
             
             
             $linkUrl = [];
             $counterUrl = 0;
             
             for($f = 0 ; $f < count($dataUrl) ; $f++)
             {
                 array_push($linkUrl,array('Url' => $dataUrl[$f]['url']));
                 
                 if(($f % 7 == 0 && $f != 0) || $f == count($dataUrl)-1)
                 {
                     $array = [];
                     // Prepare POST data
                     $array = [
                        'Request' => [
                            'Images' => $linkUrl
                        ]
                     ];
                     
                     $endpoint = "/images/migrate";
                     $client = $this->lazop->getClient();
                     $request = new LazopRequest($endpoint);
                   
                     $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root/>');
                     $this->arrayToXml($array, $xml);
                    
                     $payload = str_replace('<?xml version="1.0" encoding="UTF-8"?>','',$xml->Request->asXML());
            
                     $request->addApiParam('payload',$payload);
                     $response = $client->execute($request, $accessToken);
                     $ret = json_decode($response,true);
           
                     if($ret['code'] == 0)
                     {
                         $batchID = $ret['batch_id'];
                         
                         $endpoint = "/image/response/get";
                        
                         $this->load->library('Lazop', [
                             'appKey'    => $appKey,
                             'appSecret' => $appSecret
                         ]);
                         
                         $client = $this->lazop->getClient();
                         $request = new LazopRequest($endpoint,'GET');
                         $request->addApiParam('batch_id',$batchID);
                         $response = $client->execute($request, $accessToken);
                         $retResp = json_decode($response,true);
              
                         if($retResp['code'] == 0)
                         {
                             $indexGambarAdd = 0;
                             for($x = (7*$counterUrl) ; $x <= $f ; $x++)
                             {
                                 if($dataUrl[$x]['url-baru'] == "")
                                 {
                                     $dataUrl[$x]['url-baru'] = $retResp['data']['images'][$indexGambarAdd]['url'];
                                     $dataUrl[$x]['id-baru'] = $retResp['data']['images'][$indexGambarAdd]['hash_code'];
                                     $indexGambarAdd++;
                                 }
                             }
                            $linkUrl = [];
                            $counterUrl++;
                         }
                         else
                         {
                             $error = 1;
                             echo json_encode(array(
                                 "success" => false,
                                 "message" => "RESPONSE : ".$retResp['code']." : ".$retResp['message'],
                                 "msg" => "Terjadi kesalahan di respon, mohon lakukan simpan ulang"
                             ));
                             break;
                         }
                     }
                     else
                     {
                         $error = 1;
                         echo json_encode(array(
                             "success" => false,
                             "message" => "MIGRATES : ".$ret['code']." : ".$ret['message'],
                             "msg" => "Terjadi kesalahan di migrasi url, mohon lakukan simpan ulang"
                         ));
                         break;
                     }
                 }
            }
             
            if($error == 0)
            {
               //JIKA BERHASIL, KIRIM BALIK SEMUA       
               echo json_encode(array(
                   "success" => true,
                   "data" => $dataUrl
               ));
            }
    
        }
         else
        {
            $error = 1;
           // echo "Token gagal diperbaharui";
            echo json_encode(array(
                "success" => false,
                "message" => "failed refresh token"
            ));
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
        $urlLocal = $this->config->item('base_url'). '/assets/'.$this->input->post('reason').'/'. $kode . "_" . $index.$type."?t=".date('ymdhis');

        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
            
            if($tipe == "GAMBAR")
            {
                $response =  $this->getRefreshToken();
        	    if($response)
        	    {
            	    $appKey = $this->model_master_config->getConfigMarketplace('LAZADA','APP_KEY');
            	    $accessToken = $this->model_master_config->getConfigMarketplace('LAZADA','ACCESS_TOKEN');
            	    $appSecret = $this->model_master_config->getConfigMarketplace('LAZADA','APP_SECRET');
            	    $endpoint = "/image/migrate";
                
                    $this->load->library('Lazop', [
                        'appKey'    => $appKey,
                        'appSecret' => $appSecret
                    ]);
    
                    $client = $this->lazop->getClient();
                    $request = new LazopRequest($endpoint);
  
                    // Prepare POST data
                    $array = [
                    'Request' => [
                           'Image' => [
                               'Url' => $urlLocal
                           ]
                       ]
                    ];
                  
                    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root/>');
                    $this->arrayToXml($array, $xml);
                    $payload = str_replace('<?xml version="1.0" encoding="UTF-8"?>','',$xml->Request->asXML());
                    $request->addApiParam('payload',$payload);
                    $response = $client->execute($request, $accessToken);
                    $ret = json_decode($response,true);
                    if($ret['message'] == "")
                    {
                        echo json_encode(array(
            	            "success" => true,
            	            "url"   => $ret['data']['image']['url'],
            	            "id"   => explode(".",explode("/",$ret['data']['image']['url'])[count(explode("/",$ret['data']['image']['url']))-1])[0],
            	            "urlLocal" => $urlLocal
            	        )); 
                    }
                    else
                    {
                        echo json_encode(array(
            	            "success" => false,
            	            "message" => $ret['code']." : ".$ret['message']
            	        ));
                    }
    
        	    }
                else
        	    {
        	       // echo "Token gagal diperbaharui";
        	        echo json_encode(array(
        	            "success" => false,
        	            "message" => "failed refresh token"
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
                 CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
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
               
              if($ret['code'] != 0)
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
                	    
                	    $code = $this->model_master_config->getConfigMarketplace('LAZADA','CODE');
                	    $shopId = $this->model_master_config->getConfigMarketplace('LAZADA','SHOP_ID');
                	    $partnerId = $this->model_master_config->getConfigMarketplace('LAZADA','PARTNER_ID');
                	    $partnerKey = $this->model_master_config->getConfigMarketplace('LAZADA','PARTNER_KEY');
                	    $accessToken = $this->model_master_config->getConfigMarketplace('LAZADA','ACCESS_TOKEN');
                        $path = "/api/v2/";
                        
                        $timest = time();
                        $sign = hash_hmac('sha256', $partnerId.$path.$endpoint.$timest,$partnerKey);
                        
                        $host = 'https://partner.LAZADAmobile.com'.$path.$endpoint;
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
                        
                        if($ret['code'] != 0)
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
                             CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
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
                           
                          if($ret['code'] != 0)
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
                                      CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
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
                                    if($ret['code'] != 0)
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
            	            "message" => "failed refresh token"
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
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
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
        
        if($ret['code'] != 0)
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
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
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
        if($ret['code'] != 0)
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
           $response = $ret['response'];
		   echo(json_encode($response));
        }

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
            
            $sqlBarang = "select idbarang from mbarang where SKULAZADA = '$sku'";
            $queryBarang = $CI->db->query($sqlBarang)->row();
                               
            //UPDATE DETAIL BARANG
            $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
		            ->where('MARKETPLACE','LAZADA')
		            ->where('URUTAN',$urutan)
		            ->set("SKU", $sku)
		            ->set("IDBARANG", $queryBarang->IDBARANG)
		            ->updateRaw("TPENJUALANMARKETPLACEDTL");
		}
		 $allskuold = substr($allskuold, 0, -1);
		 $allsku = substr($allsku, 0, -1);
		 
		
		 $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
		        ->where('MARKETPLACE','LAZADA')
		            ->set("SKUPRODUKOLD", $allskuold)
		            ->updateRaw("TPENJUALANMARKETPLACE");
		            
		 $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
		        ->where('MARKETPLACE','LAZADA')
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
		
		$CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
                ->where("MARKETPLACE","LAZADA")
		        ->set("CATATANPENJUAL", $note)
		        ->updateRaw("TPENJUALANMARKETPLACE");
		        
        $data['success'] = true;
        $data['msg'] = "Catatan #".$nopesanan." Berhasil Disimpan";
        echo(json_encode($data));

	}
	
	public function kirim(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
	    $history = json_decode($this->input->post('dataAll'),true)??"";

	    
	    $arrayKirim = [];
	    for($x = 0  ; $x < count($history); $x++)
        {
            array_push($arrayKirim,array(
             'package_id' => $history[$x]['package_id']
            ));
            
            if(($x % 19 == 0 && $x != 0) || $x == count($history)-1)
            {
                $parameter = [];
        	    $parameter = [[
                	'xml' => 0,
                	'parameterKey' => 'readyToShipReq',
                	'parameter' => json_encode([
                	    'packages' => $arrayKirim,
                ])]];
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  array(
                  'endpoint' => '/order/package/rts',
                  'parameter' => json_encode($parameter),
                //   'debug' => 1
                  ),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
           
                if($ret['code'] != 0)
                { 
                    $ret['success'] = false;
                    $ret['msg'] = $ret['message'];
                    die(json_encode($ret));
                }
                else
                {
                    $arrayKirim = [];
                    if(count($history) == 1)
                    {
                     $nopesanan = "#".$history[0]['order_number'];
                    }
                    
                   //MASUK KE PROCCESSED
                   $data['success'] = true;
                   $data['msg'] = "Pesanan ".$nopesanan." Berhasil Dikirim";
                }
            }
        }
                     
        sleep(5);
        
        $this->init(date('Y-m-d'),date('Y-m-d'),'update',false);
        
        echo(json_encode($data));
        
	}
	
	public function setLacak(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
	    $nopesanan = $this->input->post('kode')??"";
		
	    //GET RESI
        $parameter = "&order_id=".$nopesanan."&locale=ID";
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/logistic/order/trace','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['code'] != 0)
        {
            echo $ret['error']." : ".$ret['message'];
            $statusok = false;
        }
        else
        {
            $data = $ret['result']['module'][0]['package_detail_info_list'][0]['logistic_detail_info_list'];
             
            for($x = 0 ; $x < count($data) ; $x++)
            {
                $data[$x]['event_time'] = date("Y-m-d H:i:s", (float)($data[$x]['event_time'])/1000);
            }
            
            echo(json_encode($data));
        }
	}

	public function print(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
	    $CI->load->library('Pdf_merger'); 
		$this->output->set_content_type('application/json');
	    $history = json_decode($this->input->post('dataNoPesanan'),true)??"";
	    $printPDF = [];
	    $arrayPrint = [];
	    for($x = 0  ; $x < count($history); $x++)
        {
            array_push($arrayPrint,array(
             'package_id' => $history[$x]['package_id']
            ));
            
            if(($x % 19 == 0 && $x != 0) || $x == count($history)-1)
            {
                
               $parameterPrint = [];
               
               $parameterPrint = [[
               	'xml' => 0,
               	'parameterKey' => 'getDocumentReq',
               	'parameter' => json_encode([
               	    'doc_type'               => 'PDF',
                       'packages'               => $arrayPrint
               ])]];
               
               //TAMBAH BARANG
               $curl = curl_init();
               curl_setopt_array($curl, array(
                 CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => '',
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => 'POST',
                 CURLOPT_POSTFIELDS =>  array(
                 'endpoint' => '/order/package/document/get',
                 'parameter' => json_encode($parameterPrint),
               //   'debug' => 1
                 ),
                 CURLOPT_HTTPHEADER => array(
                   'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                 ),
               ));
                 
               $response = curl_exec($curl);
               curl_close($curl);
               $ret =  json_decode($response,true);
               
               if($ret['code'] != 0)
               { 
                   $ret['success'] = false;
                   $ret['msg'] = $ret['message'];
                   die(json_encode($ret));
               }
               else
               {
                   $arrayPrint = [];
                   array_push($printPDF, $ret['result']['data']['pdf_url']);
               }
            }
        }
        
        foreach ($printPDF as $url) {
        
            // Create a temp file
            $file = tempnam(sys_get_temp_dir(), 'pdf_');
        
            // Download PDF content from Lazada signed URL
            $pdfData = file_get_contents($url);
        
            if ($pdfData === false || strlen($pdfData) === 0) {
                log_message('error', 'Failed to download PDF from: ' . $url);
                continue;
            }
        
            // Save it locally
            file_put_contents($file, $pdfData);
            $tempFiles[] = $file; // ✅ FIXED (not $localFile)
        
            // Import pages
            try {
                $pageCount = $this->pdf_merger->setSourceFile($file);
        
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $tpl = $this->pdf_merger->importPage($pageNo);
                    $size = $this->pdf_merger->getTemplateSize($tpl);
                    $this->pdf_merger->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $this->pdf_merger->useTemplate($tpl);
                }
            } catch (Exception $e) {
                log_message('error', 'FPDI error: ' . $e->getMessage());
            }
        }
        
        // Output merged file
        $output_file = "assets/label/lazada/waybill_merge.pdf";
        $this->pdf_merger->Output('F', $output_file);
        
        // Clean up
        foreach ($tempFiles as $file) {
            @unlink($file);
        }
        
        // Kembalikan URL hasil merge sebagai JSON
        $data['merge_url'] = base_url($output_file);
        $data['success'] = true;
        
        if(count($history) == 1)
        {
            $data['msg'] = "Pesanan #".$history[0]['order_number']." Berhasil Dicetak"; 
        }
        else
        { 
            $data['msg'] = "Pesanan Berhasil Dicetak";
        }
    
        
        // $data['merge_url'] = $this->config->item('base_url')."/assets/label/merged.pdf";
        echo(json_encode($data));

	}
	
	public function printPDF(){
	   
	   $CI =& get_instance();	
	   $CI->load->library('Pdf_merger'); 
	   
	    $input = FCPATH . 'assets/label/lazada/waybill.pdf';
        $output = FCPATH . 'assets/label/lazada/waybill_compressed.pdf';
        
        $cmd = "gs -sDEVICE=pdfwrite \
               -dDEVICEWIDTHPOINTS=283 \
               -dDEVICEHEIGHTPOINTS=425 \
               -dPDFFitPage \
               -dNOPAUSE -dQUIET -dBATCH \ -sOutputFile='$output' '$input'";
        
        exec($cmd, $outputLines, $status);
        
        if ($status === 0) {
    	   $files = [
                'assets/label/lazada/waybill_compressed.pdf',
                'assets/label/lazada/waybill_compressed.pdf',
            ];
    
            foreach ($files as $file) {
                $pageCount = $this->pdf_merger->setSourceFile($file);
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $tpl = $this->pdf_merger->importPage($pageNo);
                    $this->pdf_merger->AddPage('P',[100, 100]);
                    $this->pdf_merger->useTemplate($tpl);
                }
            }
    
            $output_file = "assets/label/lazada/waybill_merge.pdf";
            $this->pdf_merger->Output('F', $output_file); // Simpan ke file
        
            // Kembalikan URL hasil merge sebagai JSON
            echo json_encode(['pdf_url' => base_url('assets/label/lazada/waybill_merge.pdf')]);
        } else {
            echo "Gagal convert PDF. Cek Ghostscript terinstall atau tidak.";
        }
       
	}
	
	public function hapus(){
	    $CI =& get_instance();
		$this->output->set_content_type('application/json');
		$nopesanan = $this->input->post('kode')??"";
		$alasan = $this->input->post('alasan')??"";
	
	    $parameter = "&order_id=".$nopesanan;
        $idbarang = [];
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/order/items/get','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['code'] != 0)
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataBarang = $ret['data'];
            foreach($dataBarang as $itemBarang)
            {
                if($itemBarang['status'] != 'canceled')
                {
                    array_push($idbarang,$itemBarang['order_item_id']);
                }
                $idpesanan = $itemBarang['order_id'];
            }
        }
        $curl = curl_init();
        
        $parameter = "&order_id=".$idpesanan."&reason_id=".$alasan."&order_item_id_list=".json_encode($idbarang);

        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/cancel/create','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['code'] != 0)
        {
            $ret['success'] = false;
            $ret['msg'] = $ret['message'];
            die(json_encode($ret));
        }
        else
        {
            sleep(5);
            
            $this->init(date('Y-m-d'),date('Y-m-d'),'update',false);
        
        
            $data['success'] = true;
            $data['msg'] = "Pesanan #".$nopesanan." Berhasil Dibatalkan";
            echo(json_encode($data));
            
        }

	}
	
	public function getStatus($arrOrderStatus){
	    
	    $urutanStatus = ['UNPAID','PENDING','TOPACK','PACKED','TOSHIP','READY_TO_SHIP','SHIPPED','FAILED','DELIVERED','CONFIRMED','COMPLETED','CANCELED','CANCELLED','RETURNED|REQUEST_INITIATE','RETURNED|RETURN_PICKUP_PENDING','RETURNED|BUYER_RETURN_ITEM', 'RETURNED|REFUND_PENDING', 'RETURNED|DISPUTE'];
	    $urutanTertinggi = -1;
	    //ARRAY ORDER STATUS
	    for($o = 0 ; $o < count($arrOrderStatus); $o++)
	    {
	        for($u = 0 ; $u < count($urutanStatus);$u++)
	        {
	            if(strtoupper($arrOrderStatus[$o]) == $urutanStatus[$u])
	            {
	                if($u > $urutanTertinggi)
	                {
	                    $urutanTertinggi = $u;
	                }
	            }
	        }
	    }
	    
	    $orderStatus = strtoupper($urutanStatus[$urutanTertinggi]);
	    
	    if($orderStatus == "UNPAID")
	    {
	        return [
	            "status" => "Belum Bayar",
	            "state"  => 1,
	            "statusLabel" => $orderStatus
	         ];
	    }
	    else if($orderStatus == "PENDING")
	    {
	         return [
	            "status" => "Siap Dikemas",
	            "state"  => 1,
	            "statusLabel" => $orderStatus
	         ];
	    }
	    else if($orderStatus == "READY_TO_SHIP")
	    {
	         return [
	            "status" => "Siap Dikirim",
	            "state"  => 1,
	            "statusLabel" => $orderStatus
	         ];
	    }
	    else if($orderStatus == "TOPACK" || $orderStatus == "PACKED")
	    {
	         return [
	            "status" => "Dikemas",
	            "state"  => 1,
	            "statusLabel" => $orderStatus
	         ];
	    }
	    else if($orderStatus == "TOSHIP")
	    {
	         return [
	            "status" => "Proses Kirim",
	            "state"  => 1,
	            "statusLabel" => $orderStatus
	         ];
	    }
	    else if($orderStatus == "SHIPPED")
	    {
	        return [
	            "status" => "Dalam Pengiriman",
	            "state"  => 2,
	            "statusLabel" => $orderStatus
	         ];
	    }
	    else if($orderStatus == "FAILED")
	    {
	         return [
	            "status" => "Gagal Kirim",
	            "state"  => 2,
	            "statusLabel" => $orderStatus
	         ];
	    }
	    else if($orderStatus == "DELIVERED")
	    {
	        return [
	            "status" => "Telah Dikirim",
	            "state"  => 2,
	            "statusLabel" => $orderStatus
	         ];
	    }
	    else if($orderStatus == "CONFIRMED" || $orderStatus == "COMPLETED")
	    {
	        return [
	            "status" => "Selesai",
	            "state"  => 3,
	            "statusLabel" => "COMPLETED"
	         ];
	    }
	    else if($orderStatus == "CANCELED" || $orderStatus == "CANCELLED")
	    {
	        return [
	            "status" => "Pembatalan",
	            "state"  => 3,
	            "statusLabel" => "CANCELLED"
	         ];
	    }
	    else if($orderStatus == "RETURNED")
	    {
	        return [
	            "status" => "Pengembalian",
	            "state"  => 4,
	            "statusLabel" => $orderStatus
	         ];
	    }
	    else if($orderStatus == "RETURNED|REQUEST_INITIATE")
	    {
	        return [
	            "status" => "Pengembalian<br>Diajukan",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|RETURN_PICKUP_PENDING" || $orderStatus == "RETURNED|BUYER_RETURN_ITEM" || $orderStatus == "RETURNED|REFUND_PENDING")
	    {
	        return [
	            "status" => "Pengembalian<br>Diproses",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|DISPUTE")
	    {
	        return [
	            "status" => "Pengembalian<br>Dalam Sengketa",
	            "state"  => 4,
	            "statusLabel" => $orderStatus
	         ];
	    }
	    
	    return $orderStatus;
	}
	
// 	public function getAlasanKembali($alasan){
// 	    if($alasan == "WRONG_ITEM")
// 	    {
// 	        $alasan = "Barang yang dikirim salah (salah ukuran, variasi, dll)";
// 	    }
// 	    else if($alasan == "CHANGE_MIND")
// 	    {
// 	        $alasan = "Ingin kembalikan barang sesuai kondisi awal";
// 	    }
// 	    else if($alasan == "NONE")
// 	    {
// 	       // $alasan = "Ingin kembalikan barang sesuai kondisi awal";
// 	    }
// 	    else if($alasan == "NOT_RECEIPT")
// 	    {
// 	        $alasan = "Semua barang tidak sampai";
// 	    }
// 	    else if($alasan == "ITEM_DAMAGED")
// 	    {
// 	       // $alasan = "Barang Rusak";
// 	    }
// 	    else if($alasan == "DIFFERENT_DESCRIPTION")
// 	    {
// 	        $alasan = "Barang berbeda dengan deskripsi/foto";
// 	    }
// 	    else if($alasan == "MUTUAL_AGREE")
// 	    {
// 	       // $alasan = "Ingin kembalikan barang sesuai kondisi awal";
// 	    }
// 	    else if($alasan == "OTHER")
// 	    {
// 	       // $alasan = "Ingin kembalikan barang sesuai kondisi awal";
// 	    }
// 	    else if($alasan == "ITEM_MISSING")
// 	    {
// 	        $alasan = "Barang sampai namun tidak lengkap";
// 	    }
// 	    else if($alasan == "EXPECTATION_FAILED")
// 	    {
// 	       // $alasan = "Ingin kembalikan barang sesuai kondisi awal";
// 	    }
// 	    else if($alasan == "ITEM_FAKE")
// 	    {
// 	        $alasan = "Barang palsu";
// 	    }
// 	    else if($alasan == "PHYSICAL_DMG")
// 	    {
// 	        $alasan = "Barang rusak";
// 	    }
// 	    else if($alasan == "FUNCTIONAL_DMG")
// 	    {
// 	        $alasan = "Barang tidak berfungsi/tidak bisa dipakai";
// 	    }
// 	    else if($alasan == "SLIGHT_SCRATCH_DENTS")
// 	    {
// 	        $alasan = "Barang tidak sempurna (Cacat)";
// 	    }
	    
// 	    return $alasan;
// 	}
	
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
            $sqlPesanan = "SELECT * FROM TPENJUALANMARKETPLACE WHERE MARKETPLACE = 'LAZADA' and KODEPENJUALANMARKETPLACE = '".$kodetrans."'"; 
            $resultPesanan = $CI->db->query($sqlPesanan)->row();
   
            $produkData = explode("|",$resultPesanan->SKUPRODUK);
            
            foreach($produkData as $item)
            {
                //GET ID BARANG
                $sql = "SELECT MBARANG.IDPERUSAHAAN, MBARANG.IDBARANG, ifnull(MHARGA.HARGAKONSINYASI,0) as HARGA
                            FROM MBARANG 
                            INNER JOIN MHARGA on MBARANG.IDBARANG = MHARGA.IDBARANG
                            INNER JOIN MCUSTOMER on MCUSTOMER.IDCUSTOMER = MHARGA.IDCUSTOMER
                            WHERE MBARANG.SKULAZADA = '".explode("*",$item)[1]."' and MCUSTOMER.NAMACUSTOMER = 'LAZADA'";
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
                	"JENISTRANS"    => 'JUAL LAZADA',
                	"KETERANGAN"    => 'PENJUALAN LAZADA KE '.$resultPesanan->USERNAME,
                	"MK"            => 'K',
                	"JML"           => explode("*",$item)[0],
                	"TOTALHARGA"    => (explode("*",$item)[0] * ($dataBarang->HARGA??"0")),
                	"STATUS"        => '1',
                );
                $exe = $CI->db->insert($labelKartuStok,$param);
                
            }
            
            //CEK TAKUTNYA ADA BARANG YANG SAMA TAPI 1-1
            
            $sqlGroup = 'SELECT IDPERUSAHAAN, IDLOKASI, MODUL, IDTRANS, KODETRANS, IDBARANG, TGLTRANS, JENISTRANS, KETERANGAN, MK, SUM(JML) as JML, SUM(TOTALHARGA) as TOTALHARGA 
                        FROM '.$labelKartuStok.'
                        WHERE KODETRANS = "'.$resultPesanan->KODEPENJUALANMARKETPLACE.'"
                        AND JENISTRANS = "JUAL LAZADA"
                        GROUP BY IDBARANG
                        ORDER BY URUTAN';
           
            $queryGroup = $CI->db->query($sqlGroup)->result();
            
            $CI->db->where('KODETRANS',$resultPesanan->KODEPENJUALANMARKETPLACE)->where('JENISTRANS','JUAL LAZADA')->delete($labelKartuStok);
            
            foreach($queryGroup as $itemGroup)
            {
                $param = array(
                	"IDPERUSAHAAN"  => $itemGroup->IDPERUSAHAAN,
                	"IDLOKASI"      => $itemGroup->IDLOKASI,
                	"MODUL"         => $itemGroup->MODUL,
                	"IDTRANS"       => $itemGroup->IDTRANS,
                	"KODETRANS"     => $itemGroup->KODETRANS,
                	"IDBARANG"      => $itemGroup->IDBARANG,
                	"KONVERSI1"     => 1,
                	"KONVERSI2"     => 1,
                	"TGLTRANS"      => $itemGroup->TGLTRANS,
                	"JENISTRANS"    => $itemGroup->JENISTRANS,
                	"KETERANGAN"    => $itemGroup->KETERANGAN,
                	"MK"            => $itemGroup->MK,
                	"JML"           => $itemGroup->JML,
                	"TOTALHARGA"    => $itemGroup->TOTALHARGA,
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
            $sqlPesanan = "SELECT b.SKUPRODUKPENGEMBALIAN, a.SKUPRODUK,a.SKUPRODUKOLD,b.KODEPENGEMBALIANMARKETPLACE,b.TGLPENGEMBALIAN,b.KODEBARANGPENGEMBALIANMARKETPLACE AS IDTRANS,a.USERNAME FROM TPENJUALANMARKETPLACE  a
                            INNER JOIN TPENJUALANMARKETPLACEDTL b ON a.KODEPENJUALANMARKETPLACE = b.KODEPENJUALANMARKETPLACE
                            WHERE a.MARKETPLACE = 'LAZADA' and b.KODEPENGEMBALIANMARKETPLACE = '".$kodetrans."'";
            $resultPesanan = $CI->db->query($sqlPesanan)->row();
            
            $produkDataPengembalian = explode("|",$resultPesanan->SKUPRODUKPENGEMBALIAN);
            
            if($resultPesanan->SKUPRODUKPENGEMBALIAN != "")
            {
                $indexPengganti;
                $produkDataKembali = explode("|",$resultPesanan->SKUPRODUKPENGEMBALIAN);

                for($t = 0 ; $t < count($produkDataKembali);$t++)
                {
                      //GET ID BARANG
                        $sql = "SELECT MBARANG.IDPERUSAHAAN, MBARANG.IDBARANG, ifnull(MHARGA.HARGAKONSINYASI,0) as HARGA
                                   FROM MBARANG 
                                   INNER JOIN MHARGA on MBARANG.IDBARANG = MHARGA.IDBARANG
                                   INNER JOIN MCUSTOMER on MCUSTOMER.IDCUSTOMER = MHARGA.IDCUSTOMER
                                   WHERE MBARANG.SKULAZADA = '".explode("*",$produkDataKembali[$t])[1]."' and MCUSTOMER.NAMACUSTOMER = 'LAZADA'";
                
                       $dataBarangKembali = $CI->db->query($sql)->row();
                       
                       $param = array(
                       	"IDPERUSAHAAN"  => $dataBarangKembali->IDPERUSAHAAN??"2",
                       	"IDLOKASI"      => $lokasi,
                       	"MODUL"         => 'RETUR JUAL',
                       	"IDTRANS"       => 0,
                       	"KODETRANS"     => $resultPesanan->KODEPENGEMBALIANMARKETPLACE,
                       	"IDBARANG"      => $dataBarangKembali->IDBARANG??"0",
                       	"KONVERSI1"     => 1,
                       	"KONVERSI2"     => 1,
                       	"TGLTRANS"      => $resultPesanan->TGLPENGEMBALIAN,
                       	"JENISTRANS"    => 'RETUR JUAL LAZADA',
                       	"KETERANGAN"    => 'RETUR LAZADA KE '.$resultPesanan->USERNAME,
                       	"MK"            => 'M',
                       	"JML"           => explode("*",$produkDataKembali[$t])[0],
                       	"TOTALHARGA"    => (explode("*",$produkDataKembali[$t])[0] * ($dataBarangKembali->HARGA??"0")),
                       	"STATUS"        => '1',
                       );
                       $exe = $CI->db->insert($labelKartuStok,$param);
                       $idBarang = $dataBarangKembali->IDBARANG??"0";
                }
                
                
               
              //CEK TAKUTNYA ADA BARANG YANG SAMA TAPI 1-1
               
              $sqlGroup = 'SELECT IDPERUSAHAAN, IDLOKASI, MODUL, IDTRANS, KODETRANS, IDBARANG, TGLTRANS, JENISTRANS, KETERANGAN, MK, SUM(JML) as JML, SUM(TOTALHARGA) as TOTALHARGA 
                          FROM '.$labelKartuStok.'
                          WHERE KODETRANS = "'.$resultPesanan->KODEPENGEMBALIANMARKETPLACE.'"
                          AND JENISTRANS = "RETUR JUAL LAZADA"
                          GROUP BY IDBARANG
                          ORDER BY URUTAN';
               
              $queryGroup = $CI->db->query($sqlGroup)->result();
               
              $CI->db->where('KODETRANS',$resultPesanan->KODEPENGEMBALIANMARKETPLACE)->where('JENISTRANS','RETUR JUAL LAZADA')->delete($labelKartuStok);
               
              foreach($queryGroup as $itemGroup)
              {
                  $param = array(
                  	"IDPERUSAHAAN"  => $itemGroup->IDPERUSAHAAN,
                  	"IDLOKASI"      => $itemGroup->IDLOKASI,
                  	"MODUL"         => $itemGroup->MODUL,
                  	"IDTRANS"       => $itemGroup->IDTRANS,
                  	"KODETRANS"     => $itemGroup->KODETRANS,
                  	"IDBARANG"      => $itemGroup->IDBARANG,
                  	"KONVERSI1"     => 1,
                  	"KONVERSI2"     => 1,
                  	"TGLTRANS"      => $itemGroup->TGLTRANS,
                  	"JENISTRANS"    => $itemGroup->JENISTRANS,
                  	"KETERANGAN"    => $itemGroup->KETERANGAN,
                  	"MK"            => $itemGroup->MK,
                  	"JML"           => $itemGroup->JML,
                  	"TOTALHARGA"    => $itemGroup->TOTALHARGA,
                  	"STATUS"        => '1',
                  );
                  $exe = $CI->db->insert($labelKartuStok,$param);
                   
              }
               
              if($labelKartuStok == "KARTUSTOK")
              {
                   	
                  $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASILAZADA = 1 AND GROUPLOKASI like '%MARKETPLACE%'";
                  $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
                   
                  $modeList = [];
                  $countBarang = 0;
                  $whereBarang = " and IDBARANG in (";

                  foreach($queryGroup as $itemBarang)
                  {
                  	$whereBarang .= $itemBarang->IDBARANG;
                  	if($countBarang < count($queryGroup)-1)
                  	{
                  	    $whereBarang .= ",";
                  	}
                  	$countBarang++;
                  }
                   
                  $whereBarang .= ")";	
                  $count = 0;
                  $sql = "select IDPERUSAHAAN, IDBARANGLAZADA, IDINDUKBARANGLAZADA, IDBARANG,SKULAZADA,HARGAJUAL
                  			from MBARANG
                  			where (1=1) $whereBarang and (IDBARANGLAZADA is not null and IDBARANGLAZADA <> 0)
                  			order by IDINDUKBARANGLAZADA
                  			";	
                   	
                  $dataHeader = $this->db->query($sql)->result();
                   
                  $parameter = [];
                  $detailParameter = [];
                   
                  for($x = 0; $x < count($dataHeader) ; $x++)
                  {
                       
                  	  $result   = get_saldo_stok_new($dataHeader[$x]->IDPERUSAHAAN,$dataHeader[$x]->IDBARANG, $lokasi, date('Y-m-d'));
                      $saldoQty = $result->QTY??0;
                      if($saldoQty < 0)
                      {
                          $saldoQty = 0;
                      }                         
                      
                  	 array_push($detailParameter,
                  	       ['Sku' => [
                  	            'ItemId' => $dataHeader[$x]->IDINDUKBARANGLAZADA,
                  	            'SkuId' => $dataHeader[$x]->IDBARANGLAZADA,
                  		        'SellerSku' => $dataHeader[$x]->SKULAZADA,
                  		        'Quantity' => (int)$saldoQty
                  		      //  'Price' => (int)$dataHeader[$x]->HARGAJUAL
                  		    ]]);
                   		    
                      if(($x % 19 == 0 && $x != 0) || $x == count($dataHeader)-1)
                      {
                           
                          $parameter = [[
                  		    'xml' => 1,
                  		    'parameterKey' => 'payload',
                  		    'parameter' => [
                                      'Request' => [
                                          'Product' => [
                                              'Skus' => $detailParameter
                                          ]
                                      ]
                              ]
                          ]];
                           
                          $curl = curl_init();
                                   
                          curl_setopt_array($curl, array(
                             CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
                             CURLOPT_RETURNTRANSFER => true,
                             CURLOPT_ENCODING => '',
                             CURLOPT_MAXREDIRS => 10,
                             CURLOPT_TIMEOUT => 30,
                             CURLOPT_FOLLOWLOCATION => true,
                             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                             CURLOPT_CUSTOMREQUEST => 'POST',
                             CURLOPT_POSTFIELDS =>  array(
                             'endpoint' => '/product/price_quantity/update',
                             'parameter' => json_encode($parameter),
                          //   'debug' => 1
                             ),
                             CURLOPT_HTTPHEADER => array(
                              'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                             ),
                          ));
                             
                          $response = curl_exec($curl);
                          curl_close($curl);
                          $ret =  json_decode($response,true);
                           
                          if($ret['code'] != 0)
                          { 
                              $ret['success'] = false;
                              $ret['msg'] = $ret['message'];
                              echo $ret['code']." : ".$ret['msg'];
                          }
                          else
                          {
                              $parameter = [];
                              $detailParameter = [];
                          }
                      }
                  }
              }
            }
        }
        
	}
	
	public function getAlasanPembatalan(){

        $curl = curl_init();
        
        $nopesanan = $this->input->post('kode')??"";
        $idbarang = [];
        $idpesanan = 0;
        	//PAYMENT BUYER DETAIL
	    $parameter = "&order_id=".$nopesanan;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/order/items/get','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['code'] != 0)
        {
            echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $dataBarang = $ret['data'];
            foreach($dataBarang as $itemBarang)
            {
                array_push($idbarang,$itemBarang['order_item_id']);
                $idpesanan = $itemBarang['order_id'];
            }
        }
        
        $curl = curl_init();
        
        $parameter = "&order_id=".$idpesanan."&order_item_id_list=".json_encode($idbarang);

        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/order/reverse/cancel/validate','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        if($ret['code'] != 0)
        {
            $ret['success'] = false;
            $ret['msg'] = $ret['message'];
            die(json_encode($ret));
        }
        else
        {
            $ret['success'] = true;
            $ret['data'] = $ret['data']['reason_options'];
            echo json_encode($ret); 
        }
	}
	
    public function pembulatanSatuan($angka) {
        $satuan = $angka % 10;
        if ($satuan == 0) {
            return $angka; // sudah bulat
        } else {
            return $angka + (10 - $satuan); // naikkan ke puluhan terdekat
        }
    }
    
    function extractDateTime($text) {
        // Match date and time pattern: YYYY-MM-DD HH:MM:SS
        if (preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $text, $matches)) {
            return $matches[0]; // return the matched date-time string
        }
        return null;
    }
    
    public function setReturBarang(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kodepengembalian')??"";
		$nopesanan = $this->input->post('kodepesanan')??"";
	    
	    $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASILAZADA = 1 AND GROUPLOKASI like '%MARKETPLACE%'";
        $idlokasiset = $CI->db->query($sql)->row()->IDLOKASI;

        $tglStokMulai = $this->model_master_config->getConfigMarketplace('LAZADA','TGLSTOKMULAI');
        $sqlRetur = "SELECT KODEPENGEMBALIANMARKETPLACE, TGLPENGEMBALIAN, BARANGSAMPAIMANUAL FROM TPENJUALANMARKETPLACEDTL WHERE MARKETPLACE = 'LAZADA' and BARANGSAMPAIMANUAL = 0 and KODEPENGEMBALIANMARKETPLACE != '' and KODEPENJUALANMARKETPLACE = '".$nopesanan."'  ORDER BY KODEPENJUALANMARKETPLACE";
        $dataRetur = $CI->db->query($sqlRetur)->result();
		   
        foreach($dataRetur as $itemRetur)
        {
            $CI->db->where("KODEPENGEMBALIANMARKETPLACE", $itemRetur->KODEPENGEMBALIANMARKETPLACE)
              ->set('BARANGSAMPAIMANUAL',1)
    	     ->update('TPENJUALANMARKETPLACEDTL');
    	     
            $this->insertKartuStokRetur($itemRetur->KODEPENGEMBALIANMARKETPLACE,$itemRetur->TGLPENGEMBALIAN,$tglStokMulai,$idlokasiset);
        }
     
        
        $data['success'] = true;
        $data['msg'] = "Pengembalian Barang Manual Atas Pesanan #".$nopesanan." Berhasil Dilakukan";
        echo(json_encode($data));

	}
	
	public function init($tgl_aw,$tgl_ak,$jenis = 'create',$showResponse = true) {
	    
	    
        $dateAw = new DateTime($tgl_aw." 00:00:00",new DateTimeZone('+08:00'));
        $dateAk = new DateTime($tgl_ak." 23:59:59",new DateTimeZone('+08:00'));
        $paramgrid = "";
	    if($jenis == "update")
	    {
	        $paramgrid = "&update_after=".$dateAw->format('Y-m-d\TH:i:sP');
	    }
	    else
	    {
	        $paramgrid = "&created_after=".$dateAw->format('Y-m-d\TH:i:sP')."&created_before=".$dateAk->format('Y-m-d\TH:i:sP');
	    }
		$this->output->set_content_type('application/json');
        $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);

        $finalResult = [];
        $history = [];
        $parameterhistory = [];
        $parameterreturnhistory = [];
        
        $count = 0;
        $countTotal = 1;
        $newOrder = 0;
        while($count < $countTotal)
        {
            $curl = curl_init();
        
            $parameter = $paramgrid."&limit=100&offset=".$count."&sort_by=created_at&sort_direction=ASC";
            array_push($parameterhistory,$parameter);
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => '/orders/get','parameter' => $parameter),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            if($ret['code'] != 0)
            {
                $finalResult['errorMsg'] =  "1 : ".$ret['code']." : ".$ret['message'];
                $countTotal = 0;
            }
            else
            {
                $count += $ret['data']['count'];
                $countTotal = $ret['data']['countTotal'];
                for($x = 0 ; $x < count($ret['data']['orders']); $x++)
                {
                 array_push($history,$ret['data']['orders'][$x]);
                }
            }
        }
        
        $parameter = "";
        $indexFirst = 0;
        //INDEX DIBUAT 1 KARENA, 0 DISIKAT DLU
        for($x = 0  ; $x < count($history); $x++)
        {
            if($parameter == "")
            {
                $indexFirst = $x;
                $parameter = "&order_ids=[".$history[$x]['order_number'];
            }
            else
            {
                $parameter .= (",".$history[$x]['order_number']);
            }
            
            if(($x % 49 == 0 && $x != 0) || $x == count($history)-1)
            {
                $parameter.= "]";
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('endpoint' => '/orders/items/get','parameter' => $parameter),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);

                if($ret['code'] != 0)
                {
                   $finalResult['errorMsg'] =  "2 : ".$ret['code']." : ".$ret['message'];
                }
                else
                {
                    for($z = $indexFirst  ; $z < ($x+1) ; $z++)
                    {
                        for($y = 0 ; $y < count($ret['data']); $y++)
                        {
                            $detail = $ret['data'][$y];
                            if($history[$z]['order_number'] == $detail['order_number'])
                            {
                                $history[$z]['order_items'] = $detail['order_items'];
                            }
                        }
                    }
                    $parameter = "";
                }
            }
        }
        
        $tempDataPack = [];
        $tempDataShipping = [];
        $pesananUpdate = "";
        
        for($x = 0  ; $x < count($history); $x++)
        {
            $pesananUpdate .= "'".$history[$x]['order_number']."',";
        }
        
        $pesananUpdate = substr($pesananUpdate, 0, -1);
        
        if($pesananUpdate != "")
        {
            $sql = "SELECT 1 as ADA , KODEPENJUALANMARKETPLACE, ifnull(KODEPENGEMBALIANMARKETPLACE,'') as KODEPENGEMBALIANMARKETPLACE,CATATANPENJUAL,IDPENJUALANMARKETPLACE as IDTRANS,if(MINTGLKIRIM = '0000-00-00 00:00:00','-',MINTGLKIRIM) as MINTGLKIRIM  FROM TPENJUALANMARKETPLACE 
                                    WHERE MARKETPLACE = 'LAZADA' 
                                    and KODEPENJUALANMARKETPLACE in (".$pesananUpdate.")";
                                
            $queryPesananDB = $CI->db->query($sql)->result();
        }
        
        for($x = 0  ; $x < count($history); $x++)
        {
            
            $ada = 0;
            $kodepengembalian = "";
            $idtrans =  0;
            $catatanJual = "";
            $tglkirim = "-";
            
            foreach($queryPesananDB as $dataPesananDB)
            {
                if($dataPesananDB->KODEPENJUALANMARKETPLACE == $history[$x]['order_number'])
                {
                    $ada = $dataPesananDB->ADA;
                    $kodepengembalian = $dataPesananDB->KODEPENGEMBALIANMARKETPLACE;
                    $idtrans =  $dataPesananDB->IDTRANS;
                    $catatanJual = $dataPesananDB->CATATANPENJUAL;
                    $tglkirim = $dataPesananDB->MINTGLKIRIM;
                }
            }
            
            $dataDetail = $history[$x]['order_items'];
            $allsku = "";
            $totalBarangAktif = 0;
            $totalBayarAktif = 0;
            $totalHargaAktif = 0;
            
            $biayaLayanan = 1000;
            $biayaPenanganan = 0;
            
            if (strpos(str_replace("_"," ",$history[$x]['payment_method']), 'VA') !== false) {
                $biayaPenanganan = 1000;
            }
            else if (strpos(str_replace("_"," ",$history[$x]['payment_method']), 'INDOMARET') !== false || strpos(str_replace("_"," ",$history[$x]['payment_method']), 'ALFA') !== false) {
                $biayaPenanganan = 2000;
            }
            
            for($d = 0 ; $d < count($dataDetail);$d++)
            {
                //KHUSUS SKU YANG KOSONG
                $sku = $dataDetail[$d]['sku'];
                
                if(strpos(strtoupper($dataDetail[$d]['name']),"BIRTHDAY CARD") !== false){
                    $sku = "LTWS";
                }
                else if(strpos(strtoupper($dataDetail[$d]['name']),"NEWBORN CARD") !== false){
                    $sku = "CARD-BOX-OTHER";
                }
                
                if($dataDetail[$d]['status'] == 'canceled')
                {
                     $allsku .= ("0*".$sku."|");
                }
                else
                {
                     $allsku .= ("1*".$sku."|");
                     $totalBarangAktif++;
                     $totalBayarAktif += ($dataDetail[$d]['paid_price'] + $dataDetail[$d]['shipping_amount'] + (int)(($biayaLayanan + $biayaPenanganan) / count($dataDetail)));
                     $totalHargaAktif += $dataDetail[$d]['item_price'];
                }
               
            }
            
            $totalBayarAktif        = $this->pembulatanSatuan($totalBayarAktif); 
            
            $allsku = substr($allsku, 0, -1);
            
            $data;
            $createDate = new DateTime($history[$x]['created_at']);
            //DAPATKAN RESI
            $data['ALLBARANG']                      = $dataDetail;
            $data['IDPENJUALAN']                    = 0;
            $data['MARKETPLACE']                    = "LAZADA";
            $data['IDPENJUALANDARIMARKETPLACE']     = $history[$x]['order_id'];
            $data['KODEPENJUALANMARKETPLACE']       = $history[$x]['order_number'];
            $data['TGLTRANS']                       = $createDate->format('Y-m-d H:i:s');
            $data['USERNAME']                       = $history[$x]['customer_first_name']." ".$history[$x]['customer_last_name'];
            $data['NAME']                           = $history[$x]['address_shipping']['first_name']." ".$history[$x]['address_shipping']['last_name'];
            $data['TELP']                           = $history[$x]['address_shipping']['phone']??"-";
            $data['ALAMAT']                         = $history[$x]['address_shipping']['address1'].($history[$x]['address_shipping']['address2'] != ""?("<br>".$history[$x]['address_shipping']['address2']):"").($history[$x]['address_shipping']['address3'] != ""?("<br>".$history[$x]['address_shipping']['address3']):"").($history[$x]['address_shipping']['address4'] != ""?("<br>".$history[$x]['address_shipping']['address4']):"");
            $data['KOTA']                           = str_replace(" CITY","",str_replace("KAB. ","",str_replace("KOTA ","",strtoupper($history[$x]['address_shipping']['city']))));
            $data['SKUPRODUK']                      = $allsku;
            $data['SKUPRODUKOLD']                   = $allsku;
            if($this->extractDateTime($data['ALLBARANG'][0]['fulfillment_sla']) != null && $tglkirim == "-")
            {
                $data['MINTGLKIRIM']                    = $this->extractDateTime($data['ALLBARANG'][0]['fulfillment_sla']);
            }
            $data['METODEBAYAR']                    = str_replace("_"," ",$history[$x]['payment_method']);
            $data['TOTALBARANG']                    = $totalBarangAktif;
            $data['TOTALBAYAR']                     = $totalBayarAktif;
            $data['TOTALHARGA']                     = $totalHargaAktif; 
     
            $data['STATUSMARKETPLACE']              = $this->getStatus($history[$x]['statuses'])['statusLabel'];
            $data['STATUS']                         = $this->getStatus($history[$x]['statuses'])['state'];
            $data['CATATANPEMBELI']                 = $history[$x]['buyer_note'];
            $data['CATATANPENJUAL']                 = $catatanJual;
            $data["LASTUPDATED"]                    =  date("Y-m-d H:i:s");
            
            if($ada)
            {
                $detailBarang = $data['ALLBARANG'];
                
                unset($data['ALLBARANG']);
                
                $CI->db->where("KODEPENJUALANMARKETPLACE",$data['KODEPENJUALANMARKETPLACE'])
                ->where('MARKETPLACE',"LAZADA")
                ->updateRaw("TPENJUALANMARKETPLACE", array(
                    'USERNAME'                          => $data['USERNAME'],
                    'NAME'                              => $data['NAME'],
                    'TELP'                              => $data['TELP'],
                    'ALAMAT'                            => $data['ALAMAT'],
                    'KOTA'                              => $data['KOTA'],
                    'SKUPRODUK'                         => $data['SKUPRODUK'],   
                    'SKUPRODUKOLD'                      => $data['SKUPRODUKOLD'],
                    'KODEPENGAMBILAN'                   => $data['KODEPENGAMBILAN'],
                    'MINTGLKIRIM'                       => $data['MINTGLKIRIM'],
                    'METODEBAYAR'                       => $data['METODEBAYAR'],
                    'TOTALBARANG'                       => $data['TOTALBARANG'],
                    'TOTALBAYAR'                        => $data['TOTALBAYAR'],
                    'TOTALHARGA'                        => $data['TOTALHARGA'],
                    'STATUSMARKETPLACE'                 => $data['STATUSMARKETPLACE'],
                    'STATUSPENGEMBALIANMARKETPLACE'     => "",
                    'STATUS'                            => $data['STATUS'],
                    'CATATANPEMBELI'                    => $data['CATATANPEMBELI'],
                    'CATATANPENJUAL'                    => $data['CATATANPENJUAL'],
                    "LASTUPDATED"                       =>  date("Y-m-d H:i:s")
                ));
                
                $CI->db->where('KODEPENJUALANMARKETPLACE',$data['KODEPENJUALANMARKETPLACE'])->delete("TPENJUALANMARKETPLACEDTL");
                $urutan = 0;
                foreach($detailBarang as $itemBarang){
                    $urutan++;
                    
                    //KHUSUS SKU YANG KOSONG
                    $sku = $itemBarang['sku'];
                
                    if(strpos(strtoupper($itemBarang['name']),"BIRTHDAY CARD") !== false){
                        $sku = "LTWS";
                    }
                    else if(strpos(strtoupper($itemBarang['name']),"NEWBORN CARD") !== false){
                        $sku = "CARD-BOX-OTHER";
                    }
                
                    $sqlBarang = "select idbarang from mbarang where SKULAZADA = '$sku'";
                    $queryBarang = $CI->db->query($sqlBarang)->row();
        
                    $CI->db->insertRaw("TPENJUALANMARKETPLACEDTL",
                    array(
                        'IDPENJUALANMARKETPLACE'    => $idtrans,
                        'KODEPENJUALANMARKETPLACE'  => $data['KODEPENJUALANMARKETPLACE'],
                        'IDBARANG'                  => $queryBarang->IDBARANG??0,
                        'MARKETPLACE'               => 'LAZADA',
                        'SKU'                       => $sku,
                        'URUTAN'                    => $urutan,
                        'JML'                       => ($itemBarang['status'] == "canceled"? 0 : 1),
                        'HARGA'                     => $itemBarang['item_price'],
                        'TOTAL'                     => (($itemBarang['status'] == "canceled"? 0 : 1) * $itemBarang['item_price']),
                        'STATUS'                    => $this->getStatus([$itemBarang['status']])['state'],
                        'STATUSMARKETPLACE'         => $this->getStatus([$itemBarang['status']])['statusLabel'],
                    ));
                }
            }
            else
            {
                $detailBarang = $data['ALLBARANG'];
                
                unset($data['ALLBARANG']);
                
                $newOrder++;
                $CI->db->insertRaw("TPENJUALANMARKETPLACE",$data);
                
                $idtrans = $CI->db->insert_id();
                $urutan = 0;
                foreach($detailBarang as $itemBarang){
                    $urutan++;
                    
                    //KHUSUS SKU YANG KOSONG
                    $sku = $itemBarang['sku'];
                
                    if(strpos(strtoupper($itemBarang['name']),"BIRTHDAY CARD") !== false){
                        $sku = "LTWS";
                    }
                    else if(strpos(strtoupper($itemBarang['name']),"NEWBORN CARD") !== false){
                        $sku = "CARD-BOX-OTHER";
                    }
                
                    $sqlBarang = "select idbarang from mbarang where SKULAZADA = '$sku'";
                    $queryBarang = $CI->db->query($sqlBarang)->row();
                    
                    $CI->db->insertRaw("TPENJUALANMARKETPLACEDTL",
                    array(
                        'IDPENJUALANMARKETPLACE'    => $idtrans,
                        'KODEPENJUALANMARKETPLACE'  => $data['KODEPENJUALANMARKETPLACE'],
                        'IDBARANG'                  => $queryBarang->IDBARANG??0,
                        'MARKETPLACE'               => 'LAZADA',
                        'SKU'                       => $sku,
                        'URUTAN'                    => $urutan,
                        'JML'                       => ($itemBarang['status'] == "canceled"? 0 : 1),
                        'HARGA'                     => $itemBarang['item_price'],
                        'TOTAL'                     => (($itemBarang['status'] == "canceled"? 0 : 1) * $itemBarang['item_price']),
                        'STATUS'                    => $this->getStatus([$itemBarang['status']])['state'],
                        'STATUSMARKETPLACE'         => $this->getStatus([$itemBarang['status']])['statusLabel'],
                    ));
                }
            } 
            
            $detailItem = [];
            foreach($detailBarang as $itemBarang){
                array_push($detailItem,$itemBarang['order_item_id']);
            }
            
            array_push($tempDataPack, array(
                'order_id' => $history[$x]['order_number'],
                'order_item_list' =>  $detailItem
            ));
            
            array_push($tempDataShipping, array(
                'order_id' => $history[$x]['order_number'],
                'order_item_ids' => $detailItem
            ));
            
            if(($x % 19 == 0 && $x != 0) || $x == count($history)-1)
            {
                $shipping_alocation = "";
                
                $parameterShipping = "&getShipmentProvidersReq=".json_encode(array(
                    'orders' => $tempDataShipping
                ));
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>   array('endpoint' => '/order/shipment/providers/get','parameter' => $parameterShipping),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
   
                if($ret['code'] != 0)
                { 
                   $finalResult['errorMsg'] =  "3 : ".$ret['code']." : ".$ret['message'];
                }
                else
                {
                    $tempDataShipping = [];
                    $shipping_alocation = $ret['result']['data']['shipping_allocate_type'];
                }
                
                $parameterPack = [[
                	'xml' => 0,
                	'parameterKey' => 'packReq',
                	'parameter' => json_encode([
                	    'pack_order_list' => $tempDataPack,
                	    'delivery_type' => 'dropship',
                        'shipping_allocate_type' => $shipping_alocation
                ])]];
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  array(
                  'endpoint' => '/order/fulfill/pack',
                  'parameter' => json_encode($parameterPack),
                //   'debug' => 1
                  ),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
   
                if($ret['code'] != 0)
                { 
                    $finalResult['errorMsg'] =  "4 : ".$ret['code']." : ".$ret['message'];
                }
                else
                {
                    $tempDataPack = [];
                    $dataPackage = $ret['result']['data']['pack_order_list'];
                    //DAPAT KURIR
                    foreach($dataPackage as $itemPackage)
                    {
                        $kodepackaging = "";
                        $kurir = "";
                        $resi = "";
                        foreach($itemPackage['order_item_list'] as $itemPackageDetail)
                        {
                           $kodepackaging =  $itemPackageDetail['package_id'];
                           $kurir = $itemPackageDetail["shipment_provider"];
                            foreach($history as $itemHistory)
                            {
                                if($itemHistory['order_number'] == $itemPackage['order_id'])
                                {
                                    $kurir .= (" - ".$itemHistory['warehouse_code']);
                                }
                            }
                           $resi = $itemPackageDetail['tracking_number'];
                        }
                        
                        $CI->db->where("KODEPENJUALANMARKETPLACE", $itemPackage['order_id'])
                        ->where('MARKETPLACE',"LAZADA")
                        ->updateRaw("TPENJUALANMARKETPLACE", array(
                            'KURIR'                 => $kurir,
                            'RESI'                  => $resi,
                            'KODEPACKAGING'         => $kodepackaging,
                            "LASTUPDATED"           =>  date("Y-m-d H:i:s")
                        ));
                        
                        //UPDATE STATUS
                         $CI->db->where("KODEPENJUALANMARKETPLACE", $itemPackage['order_id'])
                        ->where('MARKETPLACE',"LAZADA")
                        ->where('STATUSMARKETPLACE',"PENDING")
                        ->updateRaw("TPENJUALANMARKETPLACE", array(
                            'STATUSMARKETPLACE'     => 'PACKED',
                            "LASTUPDATED"           =>  date("Y-m-d H:i:s")
                        ));
                    }
                }
                
            }
        }
        
        $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASILAZADA = 1 AND GROUPLOKASI like '%MARKETPLACE%'";
        $idlokasiset = $CI->db->query($sql)->row()->IDLOKASI;
        
        //INSERT KARTUSTOK
        $tglStokMulai = $this->model_master_config->getConfigMarketplace('LAZADA','TGLSTOKMULAI');
        for($f = 0 ; $f < count($history) ; $f++)
        {
            if($this->getStatus($history[$f]['statuses'])['state'] != 1 && $this->getStatus($history[$f]['statuses'])['statusLabel'] != "CANCELLED" )
            {
                $createDate = new DateTime($history[$f]['created_at']);
                $this->insertKartuStokPesanan($history[$f]['order_number'],$createDate->format('Y-m-d'),$tglStokMulai,$idlokasiset);
            }
        }
        //INSERT KARTUSTOK
        
        //RETUR
        $count = 1;
        $countTotal = 2;
        
        if($jenis == "update")
	    {
	        //DIKURANGI 15 HARI
	        $date = new DateTime($tgl_aw);
            $date->sub(new DateInterval("P15D")); 
            $tgl_aw = $date->format("Y-m-d");
            $start = $tgl_aw . " 00:00:00";
            $end   = $tgl_ak . " 23:59:59";
            
            $startMs = strtotime($start) * 1000;
            $endMs   = strtotime($end) * 1000;
	        
	        $paramgrid = "&ReverseOrderLineModifiedTimeRangeStart=".$startMs."&ReverseOrderLineModifiedTimeRangeEnd=".$endMs;
	    }
	    else
	    {
	        
            $start = $tgl_aw . " 00:00:00";
            $end   = $tgl_ak . " 23:59:59";
            
            $startMs = strtotime($start) * 1000;
            $endMs   = strtotime($end) * 1000;
        
	        $paramgrid = "&ReverseOrderLineTimeRangeStart=".$startMs."&ReverseOrderLineTimeRangeEnd=".$endMs;
	    }
	    

        $paramgrid .= "&request_type_list=['RETURN','ONLY_REFUND']";
        while($count < $countTotal)
        {
            $curl = curl_init();
        
            $parameter = $paramgrid."&page_size=100&page_no=".$count;

            array_push($parameterreturnhistory,$parameter);
           
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => '/reverse/getreverseordersforseller','parameter' => $parameter),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            if($ret['code'] != 0)
            {
                $finalResult['errorMsg'] =  "5 : ".$ret['code']." : ".$ret['message'];
                $countTotal = 0;
            }
            else
            {
                $count += $ret['result']['page_size'];
                $countTotal = $ret['result']['total'];
               
                for($x = 0 ; $x < count($ret['result']['items']); $x++)
                {
                     $return = $ret['result']['items'];
                     $statusRetur = "";
                     $reasonText = "";
                     $refundAmount = 0;
                     $statusOfc = "";
                     $trackingNumber = "";
                     $tglPengembalian = "";
                     $minTglPengembalian = "";
                     $minTglKirimPengembalian = "";
                     //JIKA BARANG SAMPAI, HARUS RETUR
            
                     $dataDetail = $return[$x]['reverse_order_lines'];
                     
                     $allsku = "";
                     $jml = 0;
                     $reason = [];
                     $reverseComplete = true;
                     $batalRetur = true;
                     $returBarang = false;
                     for($d = 0 ; $d < count($dataDetail);$d++)
                     {
                         $statusRetur = $dataDetail[$d]['reverse_status'];
                         if($dataDetail[$d]['is_dispute'])
                         {
                             $statusRetur = "DISPUTE";
                         }
                         
                         if($statusRetur != "REQUEST_CANCEL")
                         {
                             $batalRetur = false;
                             if($statusRetur != "REFUND_SUCCESS" && $reverseComplete)
                             {
                                 $reverseComplete = false;
                             }
                             
                             $adaReason = false;
                             for($r = 0 ; $r < count($reason); $r++)
                             {
                                 if($dataDetail[$d]['reason_text'] == $reason[$r])
                                 {
                                     $adaReason = true;
                                 }
                             }
                             if(!$adaReason)
                             {
                                 if(count($reason) > 0)
                                 {
                                     $reasonText .= ", ";
                                 }
                                 array_push($reason,$dataDetail[$d]['reason_text']);
                                $reasonText .= $dataDetail[$d]['reason_text'];
                             }
                                
                             if($statusOfc == 'RETURN_DELIVERED' || $statusOfc == 'RETURN_RTM_DELIVERED' || $statusOfc == 'RETURN_LOGISTIC_CLOSURE_return_to_warehouse')
                             {
                                $returBarang = true;
                                $statusOfc = $dataDetail[$d]['ofc_status'];
                             }
                             //JIKA ADA YANG SUDAH KEMBALI DIANGGEP RETUR
                             if(!$returBarang)
                             {
                                 $statusOfc = $dataDetail[$d]['ofc_status'];
                             }
                             
                             if($dataDetail[$d]['tracking_number'] != "")
                             {
                                $trackingNumber = $dataDetail[$d]['tracking_number'];
                             }
                             $tglPengembalian = $dataDetail[$d]['return_order_line_gmt_create'];
                             
                             $timestamp_ms = $dataDetail[$d]['sla'];
                             $minTglPengembalian = $timestamp_ms / 1000;
                             $sku = $dataDetail[$d]['seller_sku_id'];
                         }
                         else if($reverseComplete)
                         {
                             $reverseComplete = false;
                         }
                         
                         //CARI URUTAN
                         $sqlUrutan = "SELECT KODEBARANGPENGEMBALIANMARKETPLACE,URUTAN,BARANGSAMPAI FROM TPENJUALANMARKETPLACEDTL 
                                        WHERE KODEPENJUALANMARKETPLACE = '".$return[$x]['trade_order_id']."' 
                                        AND MARKETPLACE = 'LAZADA' 
                                        AND SKU = '".$sku."' 
                                        ";
                         $queryUrutan = $CI->db->query($sqlUrutan)->result();
                         $urutan = 0;
                         $barangSampai = 0;
                         
                         //CEK SUDAH PERNAH ADA ATAU BELUM
                         foreach($queryUrutan as $itemUrutan)
                         {
                             if($itemUrutan->KODEBARANGPENGEMBALIANMARKETPLACE == $dataDetail[$x]['reverse_order_line_id'])
                             {
                                 $urutan = $itemUrutan->URUTAN;
                                 $barangSampai =  $itemUrutan->BARANGSAMPAI;
                             }
                             else if($itemUrutan->KODEBARANGPENGEMBALIANMARKETPLACE == "" && $urutan == 0)
                             {
                                 $urutan = $itemUrutan->URUTAN;
                                 $barangSampai =  $itemUrutan->BARANGSAMPAI;
                             }
                         }
                        
                        if($urutan != null)
                        {
                           $CI->db->where("KODEPENJUALANMARKETPLACE",$return[$x]['trade_order_id'])
                           ->where('SKU',$sku)
                           ->where('URUTAN',$urutan)
    		              ->where('MARKETPLACE','LAZADA')
    		              ->updateRaw("TPENJUALANMARKETPLACEDTL", array(
    		                  'KODEPENGEMBALIANMARKETPLACE'   =>  $statusRetur == "REQUEST_CANCEL"?null:$return[$x]['reverse_order_id'],
    		                  'KODEBARANGPENGEMBALIANMARKETPLACE'=> $statusRetur == "REQUEST_CANCEL"?null:$dataDetail[$d]['reverse_order_line_id'],
    		                  'SKUPRODUKPENGEMBALIAN'         =>  $statusRetur == "REQUEST_CANCEL"?null:("1*".$sku),
    		                  'STATUSPENGEMBALIANMARKETPLACE' =>  $statusRetur == "REQUEST_CANCEL"?null:$statusRetur,
    		                  'CATATANPENGEMBALIAN'           =>  $statusRetur == "REQUEST_CANCEL"?null:$dataDetail[$d]['reason_text'],
    		                  'TOTALPENGEMBALIANDANA'         =>  $statusRetur == "REQUEST_CANCEL"?null:(float)($dataDetail[$d]['refund_amount'] / 100),
    		                  'TIPEPENGEMBALIAN'              =>  $statusRetur == "REQUEST_CANCEL"?null:$statusOfc,
    		                  'TGLPENGEMBALIAN'               =>  $statusRetur == "REQUEST_CANCEL"?null:date("Y-m-d H:i:s", $tglPengembalian),
    		                  'MINTGLPENGEMBALIAN'            =>  $statusRetur == "REQUEST_CANCEL"?null:($minTglPengembalian == "" ?"0000-00-00 00:00:00":date("Y-m-d H:i:s", $minTglPengembalian)),
    		                  'STATUS'                        =>  ($statusRetur == "REQUEST_CANCEL" || $statusRetur == "REFUND_SUCCESS" ?'3':'4'),
    		                  'STATUSMARKETPLACE'             =>  ($statusRetur == "REQUEST_CANCEL" || $statusRetur == "REFUND_SUCCESS" ?'COMPLETED':'RETURNED'),
    		                  'RESIPENGEMBALIAN'              =>  $statusRetur == "REQUEST_CANCEL"?null:$trackingNumber,
    		                  'BARANGSAMPAI'                  =>  ($barangSampai == 1 || $statusOfc == 'RETURN_DELIVERED' || $statusOfc == 'RETURN_RTM_DELIVERED' || $statusOfc == 'RETURN_LOGISTIC_CLOSURE_return_to_warehouse' ? 1 : 0)
    		                ));
                        }
                     }
                     
                     //JIKA ADA KURANG RETUR NYA, INPUT STOK LAGI
            		 if(!$reverseComplete || !$returBarang){
            		     $CI->db->where('KODETRANS',$return[$x]['reverse_order_id'])
            		          ->where('JENISTRANS','RETUR JUAL LAZADA')
            		          ->delete('KARTUSTOK');
            		 }
                }
            }
        }
        
        
	    
	    if(count($history) > 0)
	    {
    	    $wherePesanan = "AND KODEPENJUALANMARKETPLACE in (";
            for($f = 0 ; $f < count($history) ; $f++)
            {
                $wherePesanan .= "'".$history[$f]['order_number']."'";
                
                if($f != count($history)-1)
                {
                     $wherePesanan .= ",";
                }
            }
            $wherePesanan .= ")";
	    }
	    
	    
        
        $sqlRetur = "SELECT KODEPENGEMBALIANMARKETPLACE, TGLPENGEMBALIAN FROM TPENJUALANMARKETPLACEDTL WHERE MARKETPLACE = 'LAZADA' and (BARANGSAMPAI = 1 OR BARANGSAMPAIMANUAL = 1) and KODEPENGEMBALIANMARKETPLACE != ''  $wherePesanan  ORDER BY KODEPENJUALANMARKETPLACE";
        $dataRetur = $CI->db->query($sqlRetur)->result();
		   
        foreach($dataRetur as $itemRetur)
        {
            $this->insertKartuStokRetur($itemRetur->KODEPENGEMBALIANMARKETPLACE,$itemRetur->TGLPENGEMBALIAN,$tglStokMulai,$idlokasiset);
        }
        
        //DELETE KARTUSTOK YANG STATUSNYA CANCELLED
        $sqlDeleteStok = "
            DELETE ks
            FROM KARTUSTOK ks
            JOIN TPENJUALANMARKETPLACE tp
              ON tp.KODEPENJUALANMARKETPLACE = ks.KODETRANS
            WHERE tp.MARKETPLACE = 'LAZADA'
              AND tp.STATUSMARKETPLACE = 'CANCELLED';
        ";
        
        $CI->db->query($sqlDeleteStok);
        
        
        //TOTAL RETUR HEADER
        $sqlReturHeader = "SELECT KODEPENJUALANMARKETPLACE,group_concat(KODEPENGEMBALIANMARKETPLACE SEPARATOR ', ') as KODEPENGEMBALIANMARKETPLACE, group_concat(IF(BARANGSAMPAI = 1,SKUPRODUKPENGEMBALIAN,null) SEPARATOR '|') as SKUPRODUKPENGEMBALIAN, 
                                sum(TOTALPENGEMBALIANDANA) as TOTALPENGEMBALIANDANA, SUM(IF(STATUS = 4,1,0)) as STATUS,  SUM(IF(BARANGSAMPAI = 1,1,0)) as BARANGSAMPAI,  SUM(IF(BARANGSAMPAIMANUAL = 1,1,0)) as BARANGSAMPAIMANUAL
                                FROM TPENJUALANMARKETPLACEDTL 
                                WHERE MARKETPLACE = 'LAZADA' and KODEPENGEMBALIANMARKETPLACE != ''  $wherePesanan  
                                GROUP BY KODEPENJUALANMARKETPLACE 
                                ORDER BY KODEPENJUALANMARKETPLACE";
        $dataReturHeader = $CI->db->query($sqlReturHeader)->result();
		   
        foreach($dataReturHeader as $itemReturHeader)
        {
          
          $CI->db->where("KODEPENJUALANMARKETPLACE",$itemReturHeader->KODEPENJUALANMARKETPLACE)
		 ->where('MARKETPLACE','LAZADA')
		 ->updateRaw("TPENJUALANMARKETPLACE", array(
		     'KODEPENGEMBALIANMARKETPLACE'   =>  $itemReturHeader->KODEPENGEMBALIANMARKETPLACE,
		     'SKUPRODUKPENGEMBALIAN'         =>  $itemReturHeader->SKUPRODUKPENGEMBALIAN, 
		     'TOTALBARANGPENGEMBALIAN'       =>  $itemReturHeader->BARANGSAMPAI, 
		     'TOTALPENGEMBALIANDANA'         =>  $itemReturHeader->TOTALPENGEMBALIANDANA, 
		     'STATUSMARKETPLACE'             =>  ($itemReturHeader->STATUS > 0 ? 'RETURNED' : 'COMPLETED'),  
		     'STATUS'                        =>  ($itemReturHeader->STATUS > 0 ? '4' : '3'),
		     "LASTUPDATED"                   =>  date("Y-m-d H:i:s"),
		     'BARANGSAMPAI'                  =>  ($itemReturHeader->BARANGSAMPAI > 0 ? 1 : 0), 
		     'BARANGSAMPAIMANUAL'           =>  ($itemReturHeader->BARANGSAMPAIMANUAL > 0 ? 1 : 0), 
		   ));
        }
        
        //RETUR
        
        //SET STOK

        $sql = "select IDPERUSAHAAN, IDBARANGLAZADA, IDINDUKBARANGLAZADA, IDBARANG,SKULAZADA
        			from MBARANG
        			where (1=1) and (IDBARANGLAZADA is not null and IDBARANGLAZADA <> 0)
        			order by IDINDUKBARANGLAZADA
        			";	
        	
        $dataHeader = $this->db->query($sql)->result();
        
        $parameter = [];
        $detailParameter = [];
        
        for($x = 0; $x < count($dataHeader) ; $x++)
        {
            
        	$result   = get_saldo_stok_new($dataHeader[$x]->IDPERUSAHAAN,$dataHeader[$x]->IDBARANG, $idlokasiset, date('Y-m-d'));
            $saldoQty = $result->QTY??0;
            
             if($saldoQty < 0)
             {
                 $saldoQty = 0;
             }
  
        	 array_push($detailParameter,
        	       ['Sku' => [
        	            'ItemId' => $dataHeader[$x]->IDINDUKBARANGLAZADA,
        	            'SkuId' => $dataHeader[$x]->IDBARANGLAZADA,
        		        'SellerSku' => $dataHeader[$x]->SKULAZADA,
        		        'Quantity' => (int)$saldoQty
        		      //  'Price' => (int)$dataHeader[$x]->HARGAJUAL
        		    ]]);
        		    
            if(($x % 19 == 0 && $x != 0) || $x == count($dataHeader)-1)
            {
                
                $parameter = [[
        		    'xml' => 1,
        		    'parameterKey' => 'payload',
        		    'parameter' => [
                          'Request' => [
                              'Product' => [
                                    'Skus' => $detailParameter
                              ]
                          ]
                    ]
                ]];
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Lazada/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  array(
                  'endpoint' => '/product/price_quantity/update',
                  'parameter' => json_encode($parameter),
                //   'debug' => 1
                  ),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                curl_close($curl);
                $ret =  json_decode($response,true);
                
                if($ret['code'] != 0)
                { 
                    $finalResult['errorMsg'] =  "6 : ".$ret['code']." : ".$ret['message'];
                }
                else
                {
                  $parameter = [];
                  $detailParameter = [];
                }
            }
        }
        //SET STOK
        
        
        //UPDATE TOTAL PENJUALAN
        

        $date30DaysBefore = (new DateTime($tgl_aw))->modify('-30 day')->format('Y-m-d');
        
        $parameter = "&start_time=".$date30DaysBefore."&end_time=".date('Y-m-d');
        
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Lazada/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/finance/transaction/details/get','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
 
        if($ret['code'] != 0)
        {
           $finalResult['errorMsg'] =  "7 : ".$ret['code']." : ".$ret['message'];
        }
        else
        {    
            $dataPaymentSeller = $ret['data'];
            $transaction = [];
            foreach($dataPaymentSeller as $itemPaymentSeller)
            {
                $itemPaymentSeller['amount'] = str_replace(",","",$itemPaymentSeller['amount']);
                $adaNo = false;
                for($ps = 0 ; $ps < count($transaction);$ps++)
                {
                    if($transaction[$ps]['orderno'] == $itemPaymentSeller['order_no'])
                    {
                        $adaNo = true;
                        $transaction[$ps]['amount'] +=  $itemPaymentSeller['amount'];
                    }
                }
                if(!$adaNo)
                {
                    array_push($transaction,array(
                        'orderno' => $itemPaymentSeller['order_no'],
                        'amount' => $itemPaymentSeller['amount'],
                    ));
                }
            }
            
            foreach($transaction as $itemTrans)
            {
                $CI->db->where("KODEPENJUALANMARKETPLACE",$itemTrans['orderno'])
    		        ->where('MARKETPLACE','LAZADA')
    		        ->updateRaw("TPENJUALANMARKETPLACE", array(
    		            'TOTALPENDAPATANPENJUAL'   =>  $itemTrans['amount'],
    		            "LASTUPDATED"              =>  date("Y-m-d H:i:s")
    		          ));
            }
            
        }
        
        //UPDATE TOTAL PENJUALAN
        	
        
        $finalResult["history"] = $parameterhistory;
        $finalResult["return_history"] = $parameterreturnhistory;
		$finalResult["total"] = $newOrder;
		if($showResponse)
		{
		    echo json_encode($finalResult); 
		}
	}
	
}
