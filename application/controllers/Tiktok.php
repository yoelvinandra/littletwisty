<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tiktok extends MY_Controller {
	public function index()
	{
	    echo "
	         <b>Tutorial Cara Menggunakan Tiktok API</b>
	         <br><br>
	         1. Masuk ke Tiktok Console, masukkan data-data ini ke database :<br>
	         - App Secret <br>
	         - App Key <br>
	         - App ID dari partnernya <br>
	         - Dan pastikan redirect linknya ".$this->config->item('base_url')."/Tiktok/setCodeandShop?
	         <br><br>
	         2. Buat link auth yang akan diinput, ketika klik Authorize (Console -> App List)<br>
	            Caranya : akses ".$this->config->item('base_url')."/Tiktok/getAuth?
	         <br><br>
	         3. Lanjutkan Proses hingga di url browser, muncul Code dan Shop ID masukkan data-data ini ke database
	         <br><br>
	         4. Dapatkan akses token untuk Akses API<br>
	            Caranya : akses ".$this->config->item('base_url')."/Tiktok/getToken?
	        <br><br> 
	         5. Masukkan akses token kedalam database
	         <br><br>
	         6. ketika menggunakan API, gunakan controller Tiktok/getAPI atau Tiktok/postAPI, dan masukkan pathnya kesana.
	         ";
	}
	
	public function getAuth()
	{
        $path  = APPPATH . 'cache/';
        $files = glob($path . '*TIKTOK*');
        
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
                    
	    $app_id = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_ID');
        echo "Authorize Link : https://services.tiktokshop.com/open/authorize?service_id=".$app_id;
	}
	
	public function setCodeandShop(){
	    $this->model_master_config->setConfigMarketplace('TIKTOK','AUTH_CODE',$this->input->get("code"));
        echo "Code dan Shop ID berhasil disimpan didatabase<br><br>akses ".$this->config->item('base_url')."/Tiktok/getToken?";
	}
	
	public function getToken()
	{
	    $auth_code = $this->model_master_config->getConfigMarketplace('TIKTOK','AUTH_CODE');
	    $app_secret = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_SECRET');
	    $app_key = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_KEY');
	    $grant_type = "authorized_code";
        
        $host = 'https://auth.tiktok-shops.com';
        $path = "/api/v2/token/get";
        $params = "?&app_key=".$app_key."&app_secret=".$app_secret."&auth_code=".$auth_code."&grant_type=".$grant_type;
        
        $url = $host.$path.$params;
        
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_HTTPGET, true); 
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $resp = curl_exec($c);
        $ret = json_decode($resp, true);

        if($ret['code'] != 0)
        {
           echo $ret['code']." : ".$ret['message'];
        }
        else
        {
            $accessToken = $ret['data']["access_token"];
            $newRefreshToken = $ret['data']["refresh_token"];
            
            if($accessToken != null && $newRefreshToken != null )
            {
                $this->model_master_config->setConfigMarketplace('TIKTOK','ACCESS_TOKEN',$accessToken);
                $this->model_master_config->setConfigMarketplace('TIKTOK','REFRESH_TOKEN',$newRefreshToken);
		         
		        $curl = curl_init($url);
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('endpoint' => '/authorization/202309/shops','parameter' => $parameter),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                
                $response = curl_exec($curl);
                curl_close($curl);
                if($ret['code'] != 0)
                {
                   echo $ret['code']." : ".$ret['message'];
                }
                else
                {
                    $this->model_master_config->setConfigMarketplace('TIKTOK','SHOP',json_encode($ret['data']['shops'][0]));
                    $this->getCipher();
                }
            }
            
            echo "Access Token : ".($accessToken)."<br>Refresh Token : ".($newRefreshToken)."<br><br>Berhasil disimpan di database";
        }
        
	}
	
	public function getRefreshToken(){
	    
	    $refresh_token = $this->model_master_config->getConfigMarketplace('TIKTOK','REFRESH_TOKEN');
	    $app_secret = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_SECRET');
	    $app_key = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_KEY');
	    $grant_type = "refresh_token";
        
        $host = 'https://auth.tiktok-shops.com';
        $path = "/api/v2/token/refresh";
        $params = "?&app_key=".$app_key."&app_secret=".$app_secret."&refresh_token=".$refresh_token."&grant_type=".$grant_type;
        
        $url = $host.$path.$params;
        
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_HTTPGET, true); 
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $resp = curl_exec($c);
        $ret = json_decode($resp, true);

        if($ret['code'] != 0)
        {
           echo $ret['code']." : ".$ret['message'];
        }
        else
        {
            $accessToken = $ret["data"]["access_token"];
            $newRefreshToken = $ret["data"]["refresh_token"];
            
            if($accessToken != null && $newRefreshToken != null )
            {
                $this->model_master_config->setConfigMarketplace('TIKTOK','ACCESS_TOKEN',$accessToken);
                $this->model_master_config->setConfigMarketplace('TIKTOK','REFRESH_TOKEN',$newRefreshToken);
            }
            // echo "Access Token : ".($accessToken)."<br>Refresh Token : ".($newRefreshToken)."<br><br>Berhasil disimpan di database";
            return 'true';
        }
        

	}
	
	public function getCipher(){
	   $curl = curl_init();
       curl_setopt_array($curl, array(
         CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => array('endpoint' => '/authorization/202309/shops','parameter' => $parameter),
         CURLOPT_HTTPHEADER => array(
           'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
         ),
       ));
       
       $resp = curl_exec($curl);
       $reta = json_decode($resp, true);
       curl_close($curl);
       if($reta['code'] != 0)
       {
          echo $reta['code']." : ".$reta['message'];
       }
       else
       {
          $this->model_master_config->setConfigMarketplace('TIKTOK','SHOP',json_encode($reta['data']['shops'][0]));
          echo "SHOPS : ".(json_encode($reta['data']['shops'][0]))."<br><br>Berhasil disimpan di database";
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
    	    $accessToken = $this->model_master_config->getConfigMarketplace('TIKTOK','ACCESS_TOKEN');
    	    
    	    
    	    //BUAT SIGN
    	    // 1
            $host = 'https://open-api.tiktokglobalshop.com'.$endpoint;
    	    
    	    $app_key = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_KEY');
    	    $timest = strtotime(date("Y-m-d H:i:s", time()));
    	    
    	    $allparameter = "app_key=$app_key&timestamp=$timest".$parameter;
    	    
    	    $shopCipher = $this->input->post("shop_cipher")??"true";
    	    if($shopCipher == "true")
    	    {
    	        $shop_cipher = json_decode($this->model_master_config->getConfigMarketplace('TIKTOK','SHOP'))->cipher;
    	        $allparameter .= "&shop_cipher=".$shop_cipher;
    	    }
            
            $arrParam = explode("&",$allparameter);
            
            $paramMap = [];
            foreach ($arrParam as $itemParam) {
                list($key, $value) = explode("=", $itemParam, 2);
                $paramMap[$key] = $value;       // assign directly
            }
            
            ksort($paramMap);
            
            // 2
            $stringParam = "";
            
            foreach ($paramMap as $key => $value) {
               $stringParam .= $key.$value;
            }
            
            // 3
            $stringParam = $endpoint.$stringParam;
            
            // 4
            $app_secret = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_SECRET');
            $stringParam = $app_secret.$stringParam.$app_secret;
            
            // 5
            $sign = hash_hmac('sha256', $stringParam,$app_secret);
            
            //BUAT SIGN
            $allparameter = $allparameter."&sign=".$sign;
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $host."?".$allparameter,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'x-tts-access-token: '.$accessToken,
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
    	    $urlparameter = $this->input->post("urlparameter")??"";
    	    $parameter = json_decode($this->input->post("parameter"),true)??[];
    	    $accessToken = $this->model_master_config->getConfigMarketplace('TIKTOK','ACCESS_TOKEN');
    	    
    	    //BUAT SIGN
    	    // 1
            $host = 'https://open-api.tiktokglobalshop.com'.$endpoint;
    	    
    	    $app_key = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_KEY');
    	    $timest = strtotime(date("Y-m-d H:i:s", time()));

    	    $urlparameter = "app_key=$app_key&timestamp=".$timest.$urlparameter;
    	    
    	    $shopCipher = $this->input->post("shop_cipher")??"true";
    	 
    	    if($shopCipher == "true")
    	    {
    	        $shop_cipher = json_decode($this->model_master_config->getConfigMarketplace('TIKTOK','SHOP'))->cipher;
    	        $urlparameter .= "&shop_cipher=".$shop_cipher;
    	    }
    	    
    	    $allparameter = $urlparameter;
         
            $arrParam = explode("&",$allparameter);

            $paramMap = [];
            
            foreach ($arrParam as $itemParam) {
                list($key, $value) = explode("=", $itemParam, 2);
                $paramMap[$key] = $value;       // assign directly
            }
            ksort($paramMap);
            
            // 2
            $stringParam = "";
            
            foreach ($paramMap as $key => $value) {
               $stringParam .= $key.$value;
            }
     
            // 3
            $stringParam = $endpoint.$stringParam.json_encode($parameter);
            
            // 4
            $app_secret = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_SECRET');
            $stringParam = $app_secret.$stringParam.$app_secret;
            // 5
            $sign = hash_hmac('sha256', $stringParam,$app_secret);
            //BUAT SIGN
            
            $urlparameter = $urlparameter."&sign=".$sign;
   
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $host.'?'.$urlparameter,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_POSTFIELDS => json_encode($parameter),
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_HTTPHEADER => array(
                 'x-tts-access-token: '.$accessToken,
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
	
	public function putAPI()
	{
	    $this->output->set_content_type('application/json');
	    $response =  $this->getRefreshToken();
	    if($response)
	    {
    	    $endpoint = $this->input->post("endpoint");
    	    $urlparameter = $this->input->post("urlparameter")??"";
    	    $parameter = json_decode($this->input->post("parameter"),true)??[];
    	    $accessToken = $this->model_master_config->getConfigMarketplace('TIKTOK','ACCESS_TOKEN');
    	    
    	    //BUAT SIGN
    	    // 1
            $host = 'https://open-api.tiktokglobalshop.com'.$endpoint;
    	    
    	    $app_key = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_KEY');
    	    $timest = strtotime(date("Y-m-d H:i:s", time()));

    	    $urlparameter = "app_key=$app_key&timestamp=".$timest.$urlparameter;
    	    
    	    $shopCipher = $this->input->post("shop_cipher")??"true";
    	 
    	    if($shopCipher == "true")
    	    {
    	        $shop_cipher = json_decode($this->model_master_config->getConfigMarketplace('TIKTOK','SHOP'))->cipher;
    	        $urlparameter .= "&shop_cipher=".$shop_cipher;
    	    }
    	    
    	    $allparameter = $urlparameter;
         
            $arrParam = explode("&",$allparameter);

            $paramMap = [];
            
            foreach ($arrParam as $itemParam) {
                list($key, $value) = explode("=", $itemParam, 2);
                $paramMap[$key] = $value;       // assign directly
            }
            ksort($paramMap);
            
            // 2
            $stringParam = "";
            
            foreach ($paramMap as $key => $value) {
               $stringParam .= $key.$value;
            }
     
            // 3
            $stringParam = $endpoint.$stringParam.json_encode($parameter);
            
            // 4
            $app_secret = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_SECRET');
            $stringParam = $app_secret.$stringParam.$app_secret;
            // 5
            $sign = hash_hmac('sha256', $stringParam,$app_secret);
            //BUAT SIGN
            
            $urlparameter = $urlparameter."&sign=".$sign;
   
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $host.'?'.$urlparameter,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_POSTFIELDS => json_encode($parameter),
              CURLOPT_CUSTOMREQUEST => 'PUT',
              CURLOPT_HTTPHEADER => array(
                 'x-tts-access-token: '.$accessToken,
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
	
	public function deleteAPI()
	{
	    $this->output->set_content_type('application/json');
	    $response =  $this->getRefreshToken();
	    if($response)
	    {
    	    $endpoint = $this->input->post("endpoint");
    	    $urlparameter = $this->input->post("urlparameter")??"";
    	    $parameter = json_decode($this->input->post("parameter"),true)??[];
    	    $accessToken = $this->model_master_config->getConfigMarketplace('TIKTOK','ACCESS_TOKEN');
    	    
    	    //BUAT SIGN
    	    // 1
            $host = 'https://open-api.tiktokglobalshop.com'.$endpoint;
    	    
    	    $app_key = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_KEY');
    	    $timest = strtotime(date("Y-m-d H:i:s", time()));

    	    $urlparameter = "app_key=$app_key&timestamp=".$timest.$urlparameter;
    	    
    	    $shopCipher = $this->input->post("shop_cipher")??"true";
    	 
    	    if($shopCipher == "true")
    	    {
    	        $shop_cipher = json_decode($this->model_master_config->getConfigMarketplace('TIKTOK','SHOP'))->cipher;
    	        $urlparameter .= "&shop_cipher=".$shop_cipher;
    	    }
    	    
    	    $allparameter = $urlparameter;
         
            $arrParam = explode("&",$allparameter);

            $paramMap = [];
            
            foreach ($arrParam as $itemParam) {
                list($key, $value) = explode("=", $itemParam, 2);
                $paramMap[$key] = $value;       // assign directly
            }
            ksort($paramMap);
            
            // 2
            $stringParam = "";
            
            foreach ($paramMap as $key => $value) {
               $stringParam .= $key.$value;
            }
     
            // 3
            $stringParam = $endpoint.$stringParam.json_encode($parameter);
            
            // 4
            $app_secret = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_SECRET');
            $stringParam = $app_secret.$stringParam.$app_secret;
            // 5
            $sign = hash_hmac('sha256', $stringParam,$app_secret);
            //BUAT SIGN
            
            $urlparameter = $urlparameter."&sign=".$sign;
   
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $host.'?'.$urlparameter,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_POSTFIELDS => json_encode($parameter),
              CURLOPT_CUSTOMREQUEST => 'DELETE',
              CURLOPT_HTTPHEADER => array(
                 'x-tts-access-token: '.$accessToken,
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
		$this->output->set_content_type('application/json');
		$status = $this->input->post('status')??'ALL';
		
		$nextPageToken = "";
		$data['rows'] = [];
		$data["total"] = 999;
		$data["totalUpdate"] = 0;
		$pageSize = 100;
		
        $parameter = array(
            'status' =>    'ACTIVATE' 
        );
        
		//LOGISTIC
		$curl = curl_init();
		while(count($data['rows']) < $data["total"])
        {
		    $urlparameter = "&page_token=".$nextPageToken."&page_size=".$pageSize;
		    
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => '/product/202502/products/search','urlparameter' => $urlparameter,'parameter'=>json_encode($parameter)),
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
                $response = $ret['data'];
                $nextPageToken = $response['next_page_token'];
                $data["total"] = $response['total_count'];
                $dataBarang = $response['products'];
                foreach($dataBarang as $itemBarang)
                {
                    echo "\n\n".$itemBarang['id']." ".$itemBarang['title']."\n";
                    foreach($itemBarang['skus'] as $itemDetail)
                    {
                        // $sql = "SELECT If(count(*) = 1,'ADA','TIDAK') as ADA FROM MBARANG WHERE SKUTIKTOK = '".strtoupper($itemDetail['seller_sku'])."' LIMIT 1";
                        // $ada = $CI->db->query($sql)->row()->ADA;
                      
                        // echo $itemDetail['id']." ".$itemDetail['seller_sku']." ".$ada."\n";
                            
                      $sql = "UPDATE MBARANG SET IDBARANGTIKTOK = (IF(WARNA = '','".($itemBarang['id']."_".$itemDetail['id'])."','".$itemDetail['id']."')), idindukbarangtiktok = '".$itemBarang['id']."' WHERE SKUTIKTOK = '".strtoupper($itemDetail['seller_sku'])."'";
                      $CI->db->query($sql);
                      echo $sql." ;";
                      echo "\n";
                      $data["totalUpdate"]++;
                      $data["msg"] = "IDBARANGTIKTOK BERHASIL DIUPDATE";
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
		
	
        $sql = "SELECT IDCUSTOMER,KONSINYASI FROM MCUSTOMER WHERE KODECUSTOMER = 'XTIKTOK' ";
    	$IDCUSTOMER = $CI->db->query($sql)->row()->IDCUSTOMER;
    	$KONSINYASI = $CI->db->query($sql)->row()->KONSINYASI??0;
		
		
		if($varian == "true")
		{
    		$sql = "SELECT a.IDBARANG,a.KATEGORI,
    		        a.IDBARANGTIKTOK, a.IDINDUKBARANGTIKTOK,a.SKUTIKTOK
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
    		                if($itemBarang->IDBARANGTIKTOK != 0 && $itemBarang->IDINDUKBARANGTIKTOK != 0)
    		                {
    		                    array_push($dataBarangHarga,
        		                    array(
        		                         'IDBARANGTIKTOK'           => $itemBarang->IDBARANGTIKTOK,
        		                         'IDINDUKBARANGTIKTOK'      => $itemBarang->IDINDUKBARANGTIKTOK,
        		                         'SKUTIKTOK'                => $itemBarang->SKUTIKTOK,
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
    		           if($itemBarang->IDBARANGTIKTOK != 0 && $itemBarang->IDINDUKBARANGTIKTOK != 0)
    		           {
    		               array_push($dataBarangHarga,
        		                    array(
        		                         'IDBARANGTIKTOK'           => $itemBarang->IDBARANGTIKTOK,
        		                         'IDINDUKBARANGTIKTOK'      => $itemBarang->IDINDUKBARANGTIKTOK,
        		                         'SKUTIKTOK'                => $itemBarang->SKUTIKTOK,
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
        	   $sql = "SELECT a.IDBARANG, a.IDBARANGTIKTOK, a.IDINDUKBARANGTIKTOK,a.SKUTIKTOK FROM MBARANG a WHERE a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and kategori in (select x.kategori from mbarang x where x.idbarang = {$item->idbarang})";
        	   
        	   $allBarang = $CI->db->query($sql)->result();
        	   
        	   foreach($allBarang as $itemBarang)
        	   {
                   if($itemBarang->IDBARANGTIKTOK != 0 && $itemBarang->IDINDUKBARANGTIKTOK != 0)
        	       {
        	            array_push($dataBarangHarga,
        		                    array(
        		                         'IDBARANGTIKTOK'           => $itemBarang->IDBARANGTIKTOK,
        		                         'IDINDUKBARANGTIKTOK'      => $itemBarang->IDINDUKBARANGTIKTOK,
        		                         'SKUTIKTOK'                => $itemBarang->SKUTIKTOK,
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
        		    $sql = "SELECT a.IDBARANG, a.IDBARANGTIKTOK, a.IDINDUKBARANGTIKTOK,a.SKUTIKTOK FROM MBARANG a WHERE a.IDPERUSAHAAN = {$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']} and kategori in (select x.kategori from mbarang x where x.idbarang = {$item->idbarang})";
        		    
        		    $allBarang = $CI->db->query($sql)->result();
        		    
        		    foreach($allBarang as $itemBarang)
        		    {
            		    if($itemBarang->IDBARANGTIKTOK != 0 && $itemBarang->IDINDUKBARANGTIKTOK != 0)
        		        {
        		            array_push($dataBarangHarga,
        		                    array(
        		                         'IDBARANGTIKTOK'           => $itemBarang->IDBARANGTIKTOK,
        		                         'IDINDUKBARANGTIKTOK'      => $itemBarang->IDINDUKBARANGTIKTOK,
        		                         'SKUTIKTOK'                => $itemBarang->SKUTIKTOK,
        		                         'HARGA'                    => $item->hargajualnew,
        		                         'HARGADISKON'              => $KONSINYASI == 1 ? $item->hargakonsinyasinew:$item->harganew,
        		                    )); 
        		        }
        		    }
    		    }
    		}
    		
        }
        
        $idbarangheader = 0;
        $databaranginduk = [];
        
        //SUSUN HEADER BARANG
        for($x = 0 ; $x < count($dataBarangHarga); $x++)
		{
		    $ada = false;
		    for($y = 0; $y < count($databaranginduk); $y++)
		    {
		        if($databaranginduk[$y] == $dataBarangHarga[$x]['IDINDUKBARANGTIKTOK'])
		        {
		           $ada = true;
		        }
		    }
		    
		    if(!$ada)
		    {
		         array_push($databaranginduk,$dataBarangHarga[$x]['IDINDUKBARANGTIKTOK']);
		    }
		}
		
		for($y = 0; $y < count($databaranginduk); $y++)
		{
		    $parameter = [
		        'skus' => []
		    ];
		    
		    for($x = 0 ; $x < count($dataBarangHarga); $x++)
    		{
        	    if($databaranginduk[$y] == $dataBarangHarga[$x]['IDINDUKBARANGTIKTOK'])	
        	    {
        	        array_push(
        	            $parameter['skus'], array(
            	            'id' => explode('_',$dataBarangHarga[$x]['IDBARANGTIKTOK'])[0],
            	            'price' => array(
        		                'amount' => $dataBarangHarga[$x]['HARGA'],
        		                'currency' => 'IDR'
        		            )
    		            )
        	        );
        	    }
    		}
    		
            $curl = curl_init();
                    
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  array(
              'endpoint' => '/product/202309/products/'.$databaranginduk[$y].'/prices/update',
              'parameter' => json_encode($parameter),
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
            }
		}
        
		
		
		
// 		//HARGA PROMO SAAT INI
// 		$status = ['ongoing','upcoming'];
		
// 		$arrIDPromo = [];
		
// 		//PROMO
// 		for($x = 0 ; $x < count($status); $x++)
// 		{
		    
//     		$statusok = true;
//     		$pageno = 1;
//     		$pageSize = 100;
		    
//     		while($statusok)
//             {
                
// 		        $curl = curl_init();
//     		    $parameter = "&discount_status=".$status[$x]."&page_no=".$pageno."&page_size=".$pageSize;

//                 curl_setopt_array($curl, array(
//                   CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
//                   CURLOPT_RETURNTRANSFER => true,
//                   CURLOPT_ENCODING => '',
//                   CURLOPT_MAXREDIRS => 10,
//                   CURLOPT_TIMEOUT => 30,
//                   CURLOPT_FOLLOWLOCATION => true,
//                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                   CURLOPT_CUSTOMREQUEST => 'POST',
//                   CURLOPT_POSTFIELDS => array('endpoint' => 'discount/get_discount_list','parameter' => $parameter),
//                   CURLOPT_HTTPHEADER => array(
//                     'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
//                   ),
//                 ));
                
//                 $response = curl_exec($curl);
//                 curl_close($curl);
//                 $ret =  json_decode($response,true);
//                 if($ret['code'] != 0)
//                 {
//                     echo $ret['code']." : ".$ret['message'];
//                 }
//                 else
//                 {
//                     $response = $ret['response'];
//                     $statusok = $response['more'];
//                     if($statusok){
//                         $pageno++;
//                     }
                    
//                     for($p = 0 ; $p < count($response['discount_list']);$p++)
//                     {
//                         $dataPromo = $response['discount_list'][$p];
//                         array_push($arrIDPromo,
//                             array(
//                                 'IDPROMO' => $dataPromo['discount_id'],
//                                 'STATUS' => $status[$x]
//                             )
//                         );
//                     }
//                 }
//             }
// 		}
//         //JIKA ADA PROMO, TINGGAL TAMBAHKAN BARANGNYA 
//         for($x = 0 ;$x < count($arrIDPromo) ; $x++)
//         {
//             $idPromosi = $arrIDPromo[$x]['IDPROMO'];
    		
//     		$statusok = true;
//     		$pageno = 1;
//     		$pageSize = 100;
//     		$data['rows'] = [];
    		
//     		//LOGISTIC
//     		$curl = curl_init();
    		
//     		while($statusok)
//             {
                
//     		    $parameter = "&discount_id=".$idPromosi."&page_no=".$pageno."&page_size=".$pageSize;
    		    
//     		  //  echo $parameter;
    		    
//                 curl_setopt_array($curl, array(
//                   CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
//                   CURLOPT_RETURNTRANSFER => true,
//                   CURLOPT_ENCODING => '',
//                   CURLOPT_MAXREDIRS => 10,
//                   CURLOPT_TIMEOUT => 30,
//                   CURLOPT_FOLLOWLOCATION => true,
//                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                   CURLOPT_CUSTOMREQUEST => 'POST',
//                   CURLOPT_POSTFIELDS => array('endpoint' => 'discount/get_discount','parameter' => $parameter),
//                   CURLOPT_HTTPHEADER => array(
//                     'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
//                   ),
//                 ));
                
//                 $response = curl_exec($curl);
//                 curl_close($curl);
//                 $ret =  json_decode($response,true);
//                 if($ret['code'] != 0)
//                 {
//                     echo $ret['code']." : ".$ret['message'];
//                 }
//                 else
//                 {
//                     $response = $ret['response'];
//                     $statusok = $response['more'];
//                     if($statusok){
//                         $pageno++;
//                     }
                    
//                     $parameterTambah = [];
//                 	$parameterTambah['discount_id']   = (int)$idPromosi;
//                 	$parameterTambah['item_list']  = array();
    		
//             		$parameterUbah = [];
//                 	$parameterUbah['discount_id']   = (int)$idPromosi;
//                 	$parameterUbah['item_list']  = array();
                    
                    
//                     for($p = 0 ; $p < count($response['item_list']);$p++)
//                     {
//                         $dataItem = $response['item_list'][$p];
//                         //INDUK
//                         if(count($dataItem['model_list']) == 0)
//                         {
//                             foreach($dataHargaBarang as $key  => $itemHargaBarang)
//                             {
//                                 if($itemHargaBarang['item_id'] == $dataItem['item_id'])
//                                 {
//                                     array_push($parameterUbah['item_list'], array(
//                             	       'item_id'                => (int)$itemHargaBarang['item_id'],
//                             	       'item_promotion_price'   => (float)$itemHargaBarang['model'][0]['cross_price'],
//                             	       'purchase_limit'         => (int)0,
//                             	       'model_list' => []
//                             	   ));
                            	   
//                             	   unset($dataHargaBarang[$key]);
//                                 }
//                             }
//                         }
//                         else
//                         {
//                             foreach ($dataHargaBarang as $key => &$itemHargaBarang) {
//                                 if ($itemHargaBarang['item_id'] == $dataItem['item_id']) {
                            
//                                     $parameterUbah['item_list'][] = [
//                                         'item_id'              => (int)$itemHargaBarang['item_id'],
//                                         'item_promotion_price' => (float)$itemHargaBarang['model'][0]['cross_price'],
//                                         'purchase_limit'       => 0,
//                                         'model_list'           => []
//                                     ];
                            
//                                     $lastIndex = count($parameterUbah['item_list']) - 1;
                            
//                                     foreach ($dataItem['model_list'] as $itemModel) {
//                                         foreach ($itemHargaBarang['model'] as $modelKey => $itemHargaBarangModel) {
                            
//                                             if ($itemHargaBarangModel['model_id'] == $itemModel['model_id']) {
//                                                 $parameterUbah['item_list'][$lastIndex]['model_list'][] = [
//                                                     'model_id'              => (int)$itemHargaBarangModel['model_id'],
//                                                     'model_promotion_price' => (float)$itemHargaBarangModel['cross_price'],
//                                                 ];
                            
//                                                 // remove model from the reference array
//                                                 unset($itemHargaBarang['model'][$modelKey]);
//                                             }
//                                         }
//                                     }
                            
//                                     // remove the item if no models left
//                                     if (empty($itemHargaBarang['model'])) {
//                                         unset($dataHargaBarang[$key]);
//                                     }
//                                 }
//                             }
//                             unset($itemHargaBarang); // cleanup reference
                            
//                             // optional: reindex
//                             $dataHargaBarang = array_values($dataHargaBarang);
//                         }
//                     }
                    
//                     if(count($parameterUbah['item_list']) > 0)
//     	            {
                        
//                         $curl = curl_init();
                
//                         curl_setopt_array($curl, array(
//                           CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
//                           CURLOPT_RETURNTRANSFER => true,
//                           CURLOPT_ENCODING => '',
//                           CURLOPT_MAXREDIRS => 10,
//                           CURLOPT_TIMEOUT => 30,
//                           CURLOPT_FOLLOWLOCATION => true,
//                           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                           CURLOPT_CUSTOMREQUEST => 'POST',
//                           CURLOPT_POSTFIELDS =>  array(
//                           'endpoint' => 'discount/update_discount_item',
//                           'parameter' => json_encode($parameterUbah)),
//                           CURLOPT_HTTPHEADER => array(
//                             'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
//                           ),
//                         ));
                          
//                         $response = curl_exec($curl);
//                         curl_close($curl);
//                         $ret =  json_decode($response,true);
                     
//                         if($ret['code'] != 0)
//                         {
//                             $data['success'] = false;
//                             $data['msg'] =  $ret['error']."PROMO BARANG UBAH : ".$ret['message'];
//                             die(json_encode($data));
//                         }
//     	            }
//                 }
//             }
//         }
        
        
//         //SISA BELUM ADA
//         if(count($dataHargaBarang) > 0)
//         {
//     		$nama       = "DISKON SYSTEM";
//     		$startdate        = new DateTime();  
//             $startdate->modify('+1 hour 1 minutes');  
            
//             $enddate        = new DateTime();  
//             $enddate->modify('+180 days');  
            
//     		$tglMulai   = $startdate->format('Y-m-d H:i:s');
//     		$tglAkhir   = $enddate->format('Y-m-d H:i:s');
//     		$parameter = [];
//     		$parameter['discount_name'] = $nama." ".date('Y-m-d H:i:s');
//     		$parameter['start_time']    = strtotime($tglMulai);
//     		$parameter['end_time']      = strtotime($tglAkhir);
		
// 		    $endpoint = 'discount/add_discount';
    		
    		
//     	    $curl = curl_init();
            
//             curl_setopt_array($curl, array(
//               CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
//               CURLOPT_RETURNTRANSFER => true,
//               CURLOPT_ENCODING => '',
//               CURLOPT_MAXREDIRS => 10,
//               CURLOPT_TIMEOUT => 30,
//               CURLOPT_FOLLOWLOCATION => true,
//               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//               CURLOPT_CUSTOMREQUEST => 'POST',
//               CURLOPT_POSTFIELDS =>  array(
//               'endpoint' => $endpoint,
//               'parameter' => json_encode($parameter)),
//               CURLOPT_HTTPHEADER => array(
//                 'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
//               ),
//             ));
              
//             $response = curl_exec($curl);
//             curl_close($curl);
//             $ret =  json_decode($response,true);
         
//             if($ret['code'] != 0)
//             {
//                 $data['success'] = false;
//                 $data['msg'] =  $ret['error']." BUAT PROMO : ".$ret['message'];
//                 die(json_encode($data));
//             }
//             else
//             {
//                 $id = $ret['response']['discount_id'];
                
//                 $parameterTambah = [];
//         	    $parameterTambah['discount_id']   = (int)$id;
//         	    $parameterTambah['item_list']  = array();
		
// 		        foreach($dataHargaBarang as $itemHargaBarang)
//                 {
//                     if($itemHargaBarang['model'][0]['model_id'] == 0)
//                     {
//                       array_push($parameterTambah['item_list'], array(
//         	               'item_id'                => (int)$itemHargaBarang['item_id'],
//                           'item_promotion_price'   => (float)$itemHargaBarang['model'][0]['cross_price'],
//                           'purchase_limit'         => (int)0,
//         	               'model_list' => []
//         	           ));
//                     }
//                     else
//                     {
//                       array_push($parameterTambah['item_list'], array(
//         	               'item_id'                => (int)$itemHargaBarang['item_id'],
//                           'item_promotion_price'   => (float)$itemHargaBarang['model'][0]['cross_price'],
//                           'purchase_limit'         => (int)0,
//         	               'model_list' => []
//         	           ));
//                       foreach($itemHargaBarang['model'] as $itemHargaBarangModel)
//                       { 
//                           array_push($parameterTambah['item_list'][count($parameterTambah['item_list'])-1]['model_list'],
//             	           array(
//             	                'model_id'               => (int)$itemHargaBarangModel['model_id'],
//             	                'model_promotion_price'   => (float)$itemHargaBarangModel['cross_price'],
//             	           ));  
//                       }
//                     }
//                 }
                
//                 $curl = curl_init();
                
//                 curl_setopt_array($curl, array(
//                   CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
//                   CURLOPT_RETURNTRANSFER => true,
//                   CURLOPT_ENCODING => '',
//                   CURLOPT_MAXREDIRS => 10,
//                   CURLOPT_TIMEOUT => 30,
//                   CURLOPT_FOLLOWLOCATION => true,
//                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                   CURLOPT_CUSTOMREQUEST => 'POST',
//                   CURLOPT_POSTFIELDS =>  array(
//                   'endpoint' => 'discount/add_discount_item',
//                   'parameter' => json_encode($parameterTambah)),
//                   CURLOPT_HTTPHEADER => array(
//                     'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
//                   ),
//                 ));
                  
//                 $response = curl_exec($curl);
//                 curl_close($curl);
//                 $ret =  json_decode($response,true);
                
//                 if($ret['code'] != 0)
//                 {
//                     $data['success'] = false;
//                     $data['msg'] =  $ret['error']."PROMO BARANG TAMBAH : ".$ret['message'];
//                     die(json_encode($data));
//                 }
//             }
            
            
//         }
		
		
        $data['success'] = true;
        $data['msg'] =  'Update Harga Tiktok Berhasil';
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
          curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/logistics/202309/warehouses','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        $lokasi = "1";
        
        if($ret['code'] != 0)
        {
            echo $ret['code']." : ".$ret['message'];
        }
        else
        {
            $dataAddress = $ret['data']['warehouses'];
            $data['rows'] = [];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI, IFNULL(NAMALOKASI,'') as NAMALOKASI FROM MLOKASI WHERE (IDLOKASITIKTOKPICKUP = ".$dataAddress[$x]['id']." OR  IDLOKASITIKTOKRETUR = ".$dataAddress[$x]['id'].") AND GROUPLOKASI like '%MARKETPLACE%'";
                $pickup = false;
                
                if($dataAddress[$x]['type'] == "SALES_WAREHOUSE"  && $dataAddress[$x]['is_default'] == 1)
                {
                    $pickup = true;
                }
                
                if($pickup)
                {
                    $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
                    $lokasiPickupTiktok = $dataAddress[$x]['id'];
                }
            }
            
  
            if($lokasi == $idlokasiset)
            {
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
                
                $sql = "select IDPERUSAHAAN, IDBARANGTIKTOK, IDINDUKBARANGTIKTOK, IDBARANG
            				from MBARANG
            				where (1=1) $whereBarang and (IDBARANGTIKTOK is not null and IDBARANGTIKTOK <> 0)
            				order by idindukbarangtiktok
            				";	
            		
            	$dataHeader = $this->db->query($sql)->result();
      
                 $idHeader = 0;
                 $parameter = [];
            	 foreach($dataHeader as $itemHeader)
            	 {
            	     if($itemHeader->IDINDUKBARANGTIKTOK != $idHeader)
                     {
                         if(count($parameter) > 0)
                         {
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => '',
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 30,
                              CURLOPT_FOLLOWLOCATION => true,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => 'POST',
                              CURLOPT_POSTFIELDS =>  array(
                              'endpoint' => '/product/202309/products/'.$idHeader.'/inventory/update',
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
                                $data['msg'] =  $ret['error']." STOK 1 : ".$ret['message'];
                                die(json_encode($data));
                                // print_r($ret);
                            }
                         }
                         $idHeader = $itemHeader->IDINDUKBARANGTIKTOK;
                         
                         //UPDATE KE TIKTOKNYA
                        $parameter = [];
                     	$parameter['skus'] = [];
                     }
                	     
                     $result   = get_saldo_stok_new($itemHeader->IDPERUSAHAAN,$itemHeader->IDBARANG, $lokasi, date('Y-m-d'));
                     $saldoQty = $result->QTY??0;
                     if($saldoQty < 0)
                     {
                         $saldoQty = 0;
                     }
                     
                    $idskuvarian = $itemHeader->IDBARANGTIKTOK;
                    
                    if(explode("_",$itemHeader->IDBARANGTIKTOK)[0] == $itemHeader->IDINDUKBARANGTIKTOK)
                    {
                        $idskuvarian = explode("_",$itemHeader->IDBARANGTIKTOK)[1];
                    }
                    
                     array_push($parameter['skus'],array(
                        'id'      => $idskuvarian,
                        'inventory'  => array(
                             array(
                                 'warehouse_id' => $lokasiPickupTiktok,
                                 'quantity' => (int)$saldoQty
                            )
                        ))
                    );
                }
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>  array(
                  'endpoint' =>  '/product/202309/products/'.$idHeader.'/inventory/update',
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
                    $data['msg'] =  $ret['error']." STOK 2 : ".$ret['message'];
                    die(json_encode($data));
                }
            	else
            	{
            	    $data['success'] = true;
                    $data['msg'] =  "";
            	   // die(json_encode($data));
            	}
            }
        }
        
        if($lokasi == $idlokasiset)
        {
            $data['success'] = true;
            $data['msg'] = "Stok Tiktok Berhasil Diupdate";
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
        
        $parameter = "&locale=id-ID&category_version=v2";
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/product/202309/categories','parameter' => $parameter),
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
            $dataKategori = $ret['data']['categories'];
            $responseKategori = [];
            
            foreach($dataKategori as $itemKategori)
            {
                if($itemKategori['parent_id'] == 0)
                {
                    
                    if($itemKategori['is_leaf'] && $itemKategori['permission_statuses'][0] == 'AVAILABLE')
                    {
                        array_push($responseKategori,array(
                            'VALUE' =>  $itemKategori['id'],
                            'TEXT' => $itemKategori['local_name']
                        ));
                    }
                    else
                    {
                        foreach($dataKategori as $itemSubKategori)
                        {
                        
                          if($itemKategori['id'] == $itemSubKategori['parent_id'])
                          {
                                 
                              if($itemSubKategori['is_leaf'] && $itemSubKategori['permission_statuses'][0] == 'AVAILABLE')
                              {
                                  array_push($responseKategori,array(
                                      'VALUE' =>  $itemSubKategori['id'],
                                      'TEXT' => $itemKategori['local_name']." / ".$itemSubKategori['local_name']
                                  ));
                              }
                              else
                              {
                                  foreach($dataKategori as $itemSubKategori2)
                                  {
                                      if($itemSubKategori['id'] == $itemSubKategori2['parent_id'])
                                      {
                                          if($itemSubKategori2['is_leaf'] && $itemSubKategori2['permission_statuses'][0] == 'AVAILABLE')
                                          {
                                              array_push($responseKategori,array(
                                                  'VALUE' =>  $itemSubKategori2['id'],
                                                  'TEXT' => $itemKategori['local_name']." / ".$itemSubKategori['local_name']." / ".$itemSubKategori2['local_name']
                                              ));
                                          }
                                          else
                                          {
                                              foreach($dataKategori as $itemSubKategori3)
                                              {
                                                  if($itemSubKategori2['id'] == $itemSubKategori3['parent_id'])
                                                  {
                                                      if($itemSubKategori3['is_leaf'] && $itemSubKategori3['permission_statuses'][0] == 'AVAILABLE')
                                                      {
                                                          array_push($responseKategori,array(
                                                              'VALUE' =>  $itemSubKategori3['id'],
                                                              'TEXT' => $itemKategori['local_name']." / ".$itemSubKategori['local_name']." / ".$itemSubKategori2['local_name']." / ".$itemSubKategori3['local_name']
                                                          ));
                                                           
                                                      }
                                                      else
                                                      {
                                                          foreach($dataKategori as $itemSubKategori4)
                                                          {
                                                              if($itemSubKategori3['id'] == $itemSubKategori4['parent_id'])
                                                              {
                                                                  if($itemSubKategori4['is_leaf'] && $itemSubKategori4['permission_statuses'][0] == 'AVAILABLE')
                                                                  {
                                                                      array_push($responseKategori,array(
                                                                          'VALUE' =>  $itemSubKategori4['id'],
                                                                          'TEXT' => $itemKategori['local_name']." / ".$itemSubKategori['local_name']." / ".$itemSubKategori2['local_name']." / ".$itemSubKategori3['local_name']." / ".$itemSubKategori4['local_name']
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
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
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
            echo $ret['code']." : ".$ret['message'];
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
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
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
                echo $ret['code']." : ".$ret['message'];
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
        $urlparameter = "&locale=id-ID";
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/product/202309/categories/'.$kategori.'/rules','urlparameter' =>  $urlparameter, 'parameter' => $parameter),
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
            $data['available_image_size_chart'] = $ret['data']['size_chart']['is_required'];
            if($data['available_image_size_chart'])
            {
                
                $urlparameter = "&page_size=100&locale=['id-ID']";
                
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('endpoint' => '/product/202407/sizecharts/search','urlparameter' =>  $urlparameter, 'parameter' => $parameter, 'shop_cipher' => 'false'),
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
                    $dataSizeChart = $ret['data']['size_chart'];
                    
                    for($x = 0 ; $x < count($dataSizeChart); $x++)
                    {
                         array_push($data['rows'], array(
                                'SIZE_ID' => $dataSizeChart['data']['template_id'],
                                'SIZE_NAME' => $dataSizeChart['data']['template_name']
                        ));
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
		$status = $this->input->post('status')??'ALL';
		
		$nextPageToken = "";
		$data['rows'] = [];
		$data["total"] = 999;
		$pageSize = 100;
		
        $parameter = array(
            'status' =>    $status 
        );
        
		//LOGISTIC
		$curl = curl_init();
		
		while(count($data['rows']) < $data["total"])
        {
		    $urlparameter = "&page_token=".$nextPageToken."&page_size=".$pageSize;
		    
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => '/product/202502/products/search','urlparameter' => $urlparameter,'parameter'=>json_encode($parameter)),
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
                $response = $ret['data'];
                $nextPageToken = $response['next_page_token'];
                $data["total"] = $response['total_count'];
                $dataBarang = $response['products'];
                
                $sqlBarangMaster = "select IDBARANG, KATEGORI, IDINDUKBARANGTIKTOK from MBARANG where idindukbarangtiktok != 0 and idindukbarangtiktok != '' and idindukbarangtiktok is not null";
                $dataBarangMaster = $CI->db->query($sqlBarangMaster)->result();
                  
                foreach($dataBarang as $itemBarang)
                {
                    $itemBarang['MASTERCONNECTED'] = "TIDAK";
                    $itemBarang['IDMASTERBARANG'] = 0;
                    $itemBarang['KATEGORIMASTERBARANG'] = '';
                    foreach($dataBarangMaster as $itemBarangMaster)
                    {
                        if($itemBarangMaster->IDINDUKBARANGTIKTOK == $itemBarang['id'])
                        {
                          $itemBarang['MASTERCONNECTED'] ="YA";  
                          $itemBarang['IDMASTERBARANG'] = $itemBarangMaster->IDBARANG;
                          $itemBarang['KATEGORIMASTERBARANG'] = $itemBarangMaster->KATEGORI;
                        }
                    }
                    
                    $itemBarang['NAMABARANG'] = $itemBarang['title'];    
                    $itemBarang['VARIAN'] = count($itemBarang['skus']) > 0 ? "YA" : "TIDAK";     
                    $itemBarang['TGLENTRY'] = date("Y-m-d H:i:s", $itemBarang['update_time']??$itemBarang['create_time']);    
                    $itemBarang['STATUS'] = $itemBarang['status'];    
                    
                    array_push($data['rows'],$itemBarang);
                }
            }
        }
        
        //URUTKAN BERDASARKAN NAMA BARANG
        usort($data['rows'], function($a, $b) {
            return strcmp($a['NAMABARANG'], $b['NAMABARANG']);
        });
                        
        echo json_encode($data);
	}
	
	function getDataBarangdanVarian(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$itemid = $this->input->post('idindukbarangtiktok');
		
		$data = [];
        $data['dataVarian'] = [];
        $data['dataGambarInduk'];
        $data['dataGambarVarian'] = [];
	     //GET ORDER DETAIL
        $parameter = "";
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/product/202309/products/'.$itemid,'parameter' => $parameter),
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
            $statusok = false;
        }
        else
        {
            
        }
        
        $data['dataVarian'] = [];
        $data['dataGambarVarian'] = [];
        
        //VARIAN
        if(count($ret['data']['skus'][0]['sales_attributes']) > 0)
        {
            $dataModel = $ret['data']['skus'];
            for($m = 0 ; $m < count($dataModel);$m++)
            {
                array_push($data['dataVarian'], array(
                    'ID'    => $dataModel[$m]['id'],
                    'NAMA'  => strtoupper(($dataModel[$m]['sales_attributes'][0]['value_name']." / ".$dataModel[$m]['sales_attributes'][1]['value_name'])),
                    'WARNA'  => strtoupper($dataModel[$m]['sales_attributes'][0]['value_name']),
                    'SIZE'  =>strtoupper($dataModel[$m]['sales_attributes'][1]['value_name']),
                    'SKU'   => $dataModel[$m]['seller_sku'],
                    "HARGA" => $dataModel[$m]['price']['tax_exclusive_price']
                ));
                
                $ada = false;
                for($y = 0 ; $y < count($data['dataGambarVarian']); $y++)
                {
                    if($dataModel[$m]['sales_attributes'][0]['value_name'] == $data['dataGambarVarian'][$y]['WARNA']){
                        $ada = true;
                    }
                }
                
                if(!$ada)
                {
                    array_push($data['dataGambarVarian'], array(
                        'WARNA'     => strtoupper($dataModel[$m]['sales_attributes'][0]['value_name']),
                        'IMAGEID'   => $dataModel[$m]['sales_attributes'][0]['sku_img']['uri'],
                        "IMAGEURL"  => $dataModel[$m]['sales_attributes'][0]['sku_img']['urls'][0],
                    ));
                }
            }
        }
        else
        {
            //INDUK
            $data['dataInduk'] = $ret['data']['skus'][0];
        }
        $data['status'] = $ret['data']['product_status'];
        echo(json_encode($data));
	}
	
	function getDataBarang(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$itemid = $this->input->post('idindukbarangtiktok');
	    $parameter = '';
        //GET MODEL
        $parameter = "";
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/product/202309/products/'.$itemid,'parameter' => $parameter),
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
            $statusok = false;
        }
        else
        {
            $data['dataVarian'] = [];
            $data['dataGambarVarian'] = [];
            $data['data'] = $ret['data'];
            
            if(count($ret['data']['skus'][0]['sales_attributes']) > 0)
            {
                $dataModel = $ret['data']['skus'];
            
                for($m = 0 ; $m < count($dataModel);$m++)
                {
                    array_push($data['dataVarian'], array(
                        'ID'    => $dataModel[$m]['id'],
                        'NAMA'  => strtoupper(($dataModel[$m]['sales_attributes'][0]['value_name']." / ".$dataModel[$m]['sales_attributes'][1]['value_name'])),
                        'WARNA'  => strtoupper($dataModel[$m]['sales_attributes'][0]['value_name']),
                        'SIZE'  =>strtoupper($dataModel[$m]['sales_attributes'][1]['value_name']),
                        'SKU'   => $dataModel[$m]['seller_sku'],
                        "HARGA" => $dataModel[$m]['price']['tax_exclusive_price']
                    ));
                    
                    $ada = false;
                    for($y = 0 ; $y < count($data['dataGambarVarian']); $y++)
                    {
                        if($dataModel[$m]['sales_attributes'][0]['value_name'] == $data['dataGambarVarian'][$y]['WARNA']){
                            $ada = true;
                        }
                    }
                    
                    if(!$ada)
                    {
                        array_push($data['dataGambarVarian'], array(
                            'WARNA'     => strtoupper($dataModel[$m]['sales_attributes'][0]['value_name']),
                            'IMAGEID'   => $dataModel[$m]['sales_attributes'][0]['sku_img']['uri'],
                            "IMAGEURL"  => $dataModel[$m]['sales_attributes'][0]['sku_img']['urls'][0],
                        ));
                    }
                }
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
		$dataKetGambarVarian= json_decode($this->input->post("KETERANGANGAMBARVARIAN"));
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
        $idlokasiwarehouse = "";
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/logistics/202309/warehouses','parameter' => $parameter),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        $idlokasiset = "1";
        
        
        if($ret['code'] != 0)
        {
            $data['success'] = false;
            $data['msg'] =  $ret['code']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $dataAddress = $ret['data']['warehouses'];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE IDLOKASITIKTOKPICKUP = ".$dataAddress[$x]['id']." AND GROUPLOKASI like '%MARKETPLACE%'";
             
                if($dataAddress[$x]['type'] == "SALES_WAREHOUSE" && $dataAddress[$x]['is_default'] == 1)
                {
                    $lokasiPickup = $CI->db->query($sql)->row();
                    $idlokasiset = $lokasiPickup->IDLOKASI;
                    $idlokasiwarehouse = $dataAddress[$x]['id'];
                }
                 // else if($dataAddress[$x]['type'] == "RETURN_WAREHOUSE")
                 // {
                 //    $lokasiRetur = $CI->db->query($sql)->row();
                        // $idlokasiset = $lokasiRetur->IDLOKASI;
                        // $idlokasiwarehouse = dataAddress[$x]['id'];
                 // }
            }
        }
        
		
		$sql = "SELECT IDCUSTOMER,KONSINYASI FROM MCUSTOMER WHERE KODECUSTOMER like '%TIKTOK%'";
        $dataCustomer = $CI->db->query($sql)->row();
        
		$parameter = [];
		$parameter['save_mode'] = ($this->input->post("AKTIF") == 1 ? "LISTING" : "AS_DRAFT");
		$parameter['description'] = $this->input->post("DESKRIPSI");
		$parameter['category_id'] = $kategoriBarang;
		$parameter['main_images'] = [];
		foreach($dataGambarProduk as $itemGambarProduk)
		{
		    array_push($parameter['main_images'],array(
		        'uri' => $itemGambarProduk   
		    ));
		}
		$parameter['title'] = $this->input->post("NAMA");
		$parameter['package_dimensions']['length'] =  $this->input->post("PANJANG");
		$parameter['package_dimensions']['width'] = $this->input->post("LEBAR");
		$parameter['package_dimensions']['height'] = $this->input->post("TINGGI");
		$parameter['package_dimensions']['unit'] = "CENTIMETER";
		$parameter['package_weight']['value'] =  $this->input->post("BERAT");
        $parameter['package_weight']['unit'] = "GRAM";
        $parameter['category_version'] = "v2";
        
        if($sizeChartTipe == "COMBOBOX")
		{
		    $parameter['size_chart']['template']['id'] = $sizeChartID ;
		}
		else if($sizeChartTipe == "GAMBAR")
		{
		    $parameter['size_chart']['image']['uri'] = $sizeChartID ;
		}
		
		$parameter['skus'] = [];
		$indexAttr = 0;
		//VARIAN
		if(count($dataVarian) > 0)
		{
		    foreach($dataVarian as $itemVarian)
		    {
		        if($itemVarian->MODE != 'HAPUS')
		        {
    		        $sql = "SELECT IDPERUSAHAAN, IDBARANG, IDBARANGTIKTOK FROM MBARANG WHERE SKUTIKTOK = '".$itemVarian->SKUTIKTOK."'";
                    
                    $itemHeader = $CI->db->query($sql)->row();
                    $result   = get_saldo_stok_new($itemHeader->IDPERUSAHAAN,$itemHeader->IDBARANG, $idlokasiset, date('Y-m-d'));
                    $saldoQty = $result->QTY??0;
                     if($saldoQty < 0)
                     {
                         $saldoQty = 0;
                     }
                    
                    // $sql = "SELECT IF($dataCustomer->KONSINYASI = 1,HARGAKONSINYASI,HARGACORET) as HARGAPROMO FROM MHARGA WHERE IDBARANG = $itemHeader->IDBARANG AND IDCUSTOMER = $dataCustomer->IDCUSTOMER";
                    // $hargaPromo = $CI->db->query($sql)->row()->HARGAPROMO;  
                    $urlGambarVarian = "";
                    for($x = 0 ; $x < count($dataKetGambarVarian);$x++)
                    {
                        if($dataKetGambarVarian[$x] == $itemVarian->WARNA)
                        {
                            $urlGambarVarian = $dataGambarVarian[$x];
                        }
                    }
                    
                    //INDUK
        	    	$idbarangtiktok = $itemHeader->IDBARANGTIKTOK;
        	    	if($itemHeader->IDBARANGTIKTOK == "" || $itemHeader->IDBARANGTIKTOK == 0)
        	    	{
        	    	    $idbarangtiktok = "0";
        	    	}
    	    	
    		        array_push($parameter['skus'],array(
    		            'id' => (string)$idbarangtiktok,
    		            'sales_attributes' => array(
    		                array(
    		                    'id' => '100000',
    		                  //  'value_id' =>  (string)($indexAttr+100000),
    		                    'name' => 'Warna',
    		                    'sku_img' => array(
    		                        'uri' => $urlGambarVarian
    		                     ),
    		                    'value_name' => $itemVarian->WARNA
    		                )
    		                ,
    		                array(
    		                    'id' =>  '100007',
    		                  //  'value_id' =>  (string)($indexAttr+11000),
    		                    'name' => 'Ukuran',
    		                    'value_name' => $itemVarian->SIZE
    		                )
    		             ),
    		            'seller_sku' => $itemVarian->SKUTIKTOK,
    		            'price' => array(
    		                'amount' => $itemVarian->HARGAJUAL,
    		                'currency' => 'IDR'
    		              //  'sale_price' => $hargaPromo,
    		            ),
    		            'inventory' => array(array(
    		                'warehouse_id' => (string)$idlokasiwarehouse,
    		                'quantity' => (int)$saldoQty
    		            ))
    		        ));
    		        $indexAttr++;
		        }
		    }
		}
		else
		{
		    $sql = "SELECT IDPERUSAHAAN, IDBARANG, IDBARANGTIKTOK FROM MBARANG WHERE SKUTIKTOK = '".$skuInduk."'";
                
            $itemHeader = $CI->db->query($sql)->row();
            $result   = get_saldo_stok_new($itemHeader->IDPERUSAHAAN,$itemHeader->IDBARANG, $idlokasiset, date('Y-m-d'));
            $saldoQty = $result->QTY??0;
             if($saldoQty < 0)
            {
                $saldoQty = 0;
            }
            
            // $sql = "SELECT IF($dataCustomer->KONSINYASI = 1,HARGAKONSINYASI,HARGACORET) as HARGAPROMO FROM MHARGA WHERE IDBARANG = $itemHeader->IDBARANG AND IDCUSTOMER = $dataCustomer->IDCUSTOMER";
            // $hargaPromo = $CI->db->query($sql)->row()->HARGAPROMO;
                
	    	//INDUK
	    	$idbarangtiktok = $itemHeader->IDBARANGTIKTOK;
	    	if($itemHeader->IDBARANGTIKTOK == "" || $itemHeader->IDBARANGTIKTOK == 0)
	    	{
	    	    $idbarangtiktok = "0";
	    	}
	    	
		    $parameter['skus'] = array(array(
		            'id' => (string)explode("_",$idbarangtiktok)[1],
		            'seller_sku' => $skuInduk,
		            'price' => array(
		                'amount' => $hargaInduk,
		                'currency' => 'IDR'
		            ),
		            'inventory' => array(array(
		                'warehouse_id' => (string)$idlokasiwarehouse,
		                'quantity' => (int)$saldoQty
		            ))
		    ));
		}
		
		$url = $this->config->item('base_url')."/Tiktok/postAPI/";
		$endPoint = "/product/202309/products";
		if($idBarang != 0)
		{
		    $url = $this->config->item('base_url')."/Tiktok/putAPI/";
		    $endPoint = "/product/202509/products/".$idBarang;
		}
		
// 		echo $endPoint;
// 		print_r($parameter);

		$curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => $endPoint,
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
            $data['msg'] =  $ret['code']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $respBarang = $ret['data'];
            //VARIAN
            if(count($respBarang['skus'][0]['sales_attributes']) > 0)
            {
                foreach($respBarang['skus'] as $itemSKU)
                {
                    $sql = "UPDATE MBARANG SET IDBARANGTIKTOK = '".$itemSKU['id']."' , idindukbarangtiktok = '".$respBarang['product_id']."' WHERE SKUTIKTOK = '".$itemSKU['seller_sku']."'";
                    $CI->db->query($sql);
                }
            }
            else
            {
                foreach($respBarang['skus'] as $itemSKU)
                {
                    $sql = "UPDATE MBARANG SET IDBARANGTIKTOK = '".$respBarang['product_id'].'_'.$itemSKU['id']."' , idindukbarangtiktok = '".$respBarang['product_id']."' WHERE SKUTIKTOK = '".$itemSKU['seller_sku']."'";
                    $CI->db->query($sql);
                }
            }
            sleep(5);
            $data['success'] = true;
            $data['msg'] = "Barang berhasil tersimpan di Tiktok";
            echo(json_encode($data));
        }
	   
	}
	
	function removeBarang(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
		$idBarang = $this->input->post("idindukbarangtiktok",0);
		$parameter['product_ids'] = [$idBarang];
		
	    $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/deleteAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => '/product/202309/products',
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
            $data['msg'] =  $ret['code']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $sql = "UPDATE MBARANG SET idbarangtiktok = '0' , idindukbarangtiktok = '0' WHERE idindukbarangtiktok = '".$idBarang."'";
            $CI->db->query($sql);
            sleep(5);
            $data['success'] = true;
            $data['msg'] = "Barang Berhasil Dihapus dari Tiktok";
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
        WHERE STATUSMARKETPLACE != 'CANCELLED' AND MARKETPLACE = 'TIKTOK'
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
		
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/logistics/202309/warehouses','parameter' => $parameter),
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
            $dataAddress = $ret['data']['warehouses'];
            $data['rows'] = [];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI, IFNULL(NAMALOKASI,'') as NAMALOKASI FROM MLOKASI WHERE (IDLOKASITIKTOKPICKUP = ".$dataAddress[$x]['id']." OR  IDLOKASITIKTOKRETUR = ".$dataAddress[$x]['id'].") AND GROUPLOKASI like '%MARKETPLACE%'";
                $pickup = 0;
                $return = 0;
                
                if($dataAddress[$x]['type'] == "SALES_WAREHOUSE" && $dataAddress[$x]['is_default'] == 1)
                {
                    $pickup = 1;
                    $label = "<br><i>PICKUP_ADDRESS</i>";
                }
                else if($dataAddress[$x]['type'] == "RETURN_WAREHOUSE")
                {
                    $return = 1;
                    $label = "<br><i>RETURN_ADDRESS</i>";
                }
                
                array_push($data['rows'],array(
                    'NO' => ($x+1),
                    'IDADDRESSAPI' => $dataAddress[$x]['id'],
                    'ADDRESSAPI' => $dataAddress[$x]['address']['full_address']." ".$label,
                    'ADDRESSAPIRAW' => $dataAddress[$x]['address']['full_address'],
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
        $tipe = $this->input->post('tipe')??"0";
        
        if($tipe == 'PICKUP')
        {
            //RESET SEMUA
            $CI->db->where("IDLOKASITIKTOKPICKUP",$idAPI)
                        ->set("IDLOKASITIKTOKPICKUP",0)
                        ->updateRaw("MLOKASI"); 
            
            $CI->db->where("IDLOKASI",$id)
                    ->set("IDLOKASITIKTOKPICKUP",$idAPI)
                    ->updateRaw("MLOKASI");
        }
        else if($tipe == 'RETUR')
        {
            //RESET SEMUA
            $CI->db->where("IDLOKASITIKTOKRETUR",$idAPI)
                        ->set("IDLOKASITIKTOKRETUR",0)
                        ->updateRaw("MLOKASI");
            
              $CI->db->where("IDLOKASI",$id)
                    ->set("IDLOKASITIKTOKRETUR",$idAPI)
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
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
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
        if($ret['code'] != 0)
        {
            echo $ret['code']." : ".$ret['message'];
        }
        else
        {
            $dataAddress = $ret['response']['address_list'];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI FROM MLOKASI WHERE (IDLOKASITIKTOKPICKUP = ". $dataAddress[$x]['address_id']." OR IDLOKASITIKTOKRETUR = ". $dataAddress[$x]['address_id'].") AND GROUPLOKASI like '%MARKETPLACE%'";
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
		
	    $data['rows'] = [];
		$data["total"] = 0;
		$count = 0;
        $countTotal = 1;
        $nextPageToken = "";
        $newOrder = 0;
        
        while(count($data['rows']) < $countTotal)
        {
            $parameter = [];
            $parameter['page_size'] = 100;
            $parameter['page_token'] = $nextPageToken;
            $parameter['activity_type'] = "FIXED_PRICE";
            $parameter['status'] = $status;
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  array(
              'endpoint' => '/promotion/202309/activities/search',
              'urlparameter' => $urlparameter,
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
                echo $ret['code']." : ".$ret['message'];
                $countTotal = 0;
            }
            else
            {
                for($x = 0 ;$x < count($ret['data']['activities']); $x++)
                {
                    $dataPromo = $ret['data']['activities'][$x];
                    array_push($data['rows'],array(
                        'NAMAPROMOSI'   => $dataPromo['title'],
                        'TGLMULAI'      => date("Y-m-d H:i:s", $dataPromo['begin_time']),
                        'TGLAKHIR'      => date("Y-m-d H:i:s", $dataPromo['end_time']),
                        'STATUS'        => $dataPromo['status'],
                        'IDPROMOSI'     => $dataPromo['id'],
                    ));
                }
                $nextPageToken = $ret['data']['next_page_token'];
                $countTotal = $ret['data']['total_count'];
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
         
        $sql = "SELECT IDBARANG,IDBARANGTIKTOK FROM MBARANG";
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
                  CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
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
                if($ret['code'] != 0)
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
                                if((int)$itemBarangMaster->IDBARANGTIKTOK == (int)($dataPromo[$p]['promotion'][$pm]['model_id']??$dataPromo[$p]['item_id']))
                                {
                                    $ID = $itemBarangMaster->IDBARANG;
                                }
                            }
                            
                            array_push($data['rows'], array(
                                'ID'                => $ID,
                                'IDINDUKBARANGTIKTOK'=> $dataPromo[$p]['item_id'],
                                'IDBARANGTIKTOK'    => $dataPromo[$p]['promotion'][$pm]['model_id']??$dataPromo[$p]['item_id'],
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
		
		while($statusok)
        {
            
		    $parameter = "&discount_id=".$idPromosi."&page_no=".$pageno."&page_size=".$pageSize;
		    
		  //  echo $parameter;
		    
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
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
            if($ret['code'] != 0)
            {
                echo $ret['code']." : ".$ret['message'];
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
                            if($itemBarang->idindukbarangtiktok == $dataItem['item_id'] && $itemBarang->IDBARANGTIKTOK == $dataModel['model_id'])
                            {
                                $namamodel = $itemBarang->NAMABARANG;
                                $id        = $itemBarang->IDBARANG;
                            }
                        }
                        
                        array_push($data['rows'],array(
                            'ID'                 => $id,
                            'idindukbarangtiktok'=> $dataItem['item_id'],
                            'IDBARANGTIKTOK'     => $dataModel['model_id'],
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
                            if($itemBarang->idindukbarangtiktok == $dataItem['item_id'] && $itemBarang->idindukbarangtiktok == $itemBarang->IDBARANGTIKTOK)
                            {
                                $namainduk = $itemBarang->NAMABARANG;
                                $id        = $itemBarang->IDBARANG;
                            }
                        }
                        
                        array_push($data['rows'],array(
                            'ID'                 => $id,
                            'idindukbarangtiktok'=> $dataItem['item_id'],
                            'IDBARANGTIKTOK'     => $dataItem['item_id'],
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
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
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
         
            if($ret['code'] != 0)
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
    	   if($itemBarang['IDBARANGTIKTOK'] != 0 && $itemBarang['idindukbarangtiktok'] != 0)
    	   {
    	       if($itemBarang['MODE'] == "TAMBAH")
    	       {
        	       if($idbarangTambah != $itemBarang['idindukbarangtiktok'])
        	       {
        	           $idbarangTambah = $itemBarang['idindukbarangtiktok'];
        	           array_push($parameterTambah['item_list'], array(
        	               'item_id'                => (int)$itemBarang['idindukbarangtiktok'],
        	               'item_promotion_price'   => (float)$itemBarang['HARGACORET'],
        	               'purchase_limit'         => (int)$itemBarang['BATASPEMBELIAN']??0,
        	               'model_list' => []
        	           ));
        	       }
        	       
        	       if($itemBarang['IDBARANGTIKTOK'] != $itemBarang['idindukbarangtiktok'])
        	       {
        	           array_push($parameterTambah['item_list'][count($parameterTambah['item_list'])-1]['model_list'],
        	           array(
        	                'model_id'               => (int)$itemBarang['IDBARANGTIKTOK'],
        	                'model_promotion_price'   => (float)$itemBarang['HARGACORET'],
        	           ));
        	       }
    	       }
    	       else if($itemBarang['MODE'] == "UBAH")
    	       {
        	       if($idbarangUbah != $itemBarang['idindukbarangtiktok'])
        	       {
        	           $idbarangUbah = $itemBarang['idindukbarangtiktok'];
        	           array_push($parameterUbah['item_list'], array(
        	               'item_id'                => (int)$itemBarang['idindukbarangtiktok'],
        	               'item_promotion_price'   => (float)$itemBarang['HARGACORET'],
        	               'purchase_limit'         => (int)$itemBarang['BATASPEMBELIAN']??0,
        	               'model_list' => []
        	           ));
        	       }
        	       
        	       if($itemBarang['IDBARANGTIKTOK'] != $itemBarang['idindukbarangtiktok'])
        	       {
        	           array_push($parameterUbah['item_list'][count($parameterUbah['item_list'])-1]['model_list'],
        	           array(
        	                'model_id'               => (int)$itemBarang['IDBARANGTIKTOK'],
        	                'model_promotion_price'   => (float)$itemBarang['HARGACORET'],
        	           ));
        	       }
    	       }
    	       else if($itemBarang['MODE'] == "HAPUS")
    	       {
        	       if($idbarangHapus != $itemBarang['idindukbarangtiktok'])
        	       {
        	           $idbarangHapus = $itemBarang['idindukbarangtiktok'];
        	           array_push($parameterHapus['item_list'], array(
        	               'item_id'                => (int)$itemBarang['idindukbarangtiktok'],
        	               'item_promotion_price'   => (float)$itemBarang['HARGACORET'],
        	               'purchase_limit'         => (int)$itemBarang['BATASPEMBELIAN']??0,
        	               'model_list' => []
        	           ));
        	       }
        	       
        	       if($itemBarang['IDBARANGTIKTOK'] != $itemBarang['idindukbarangtiktok'])
        	       {
        	           array_push($parameterHapus['item_list'][count($parameterHapus['item_list'])-1]['model_list'],
        	           array(
        	                'model_id'               => (int)$itemBarang['IDBARANGTIKTOK'],
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
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
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
         
            if($ret['code'] != 0)
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
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
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
         
            if($ret['code'] != 0)
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
                      CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
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
                 
                    if($ret['code'] != 0)
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
                          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
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
                     
                        if($ret['code'] != 0)
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
        $data['msg'] = "Promo Produk pada TIKTOK Berhasil Disimpan";
        
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
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
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
            $data['msg'] =  $ret['code']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $data['success'] = true;
            $data['msg'] = "Promo Produk pada TIKTOK Berhasil Dihapus";
            echo(json_encode($data));
        }
	}
	
	function setBoost(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$dataBarangPermanent = json_decode($this->input->post("databarangpermanent"),true);
		$dataBarangAll = json_decode($this->input->post("databarangall"),true);
		
		$CI->db->set('BOOSTTIKTOK',0)
    	     ->update('MBARANG');
    	     
        foreach($dataBarangAll as $itemBarangAll)
		{
             $CI->db->where("idindukbarangtiktok",$itemBarangAll)
    	     ->set('BOOSTTIKTOK',1)
    	     ->update('MBARANG');
		}
		
		foreach($dataBarangPermanent as $itemBarangPermanent)
		{
             $CI->db->where("idindukbarangtiktok",$itemBarangPermanent)
    	     ->set('BOOSTTIKTOK',2)
    	     ->update('MBARANG');
		}
	     
        $data['success'] = true;
        $data['msg'] = "Jadwal Naikkan Produk pada TIKTOK Berhasil Disimpan";
        echo(json_encode($data));
	}
	
	function getBoost(){
	   $CI =& get_instance();	
       $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
	   $this->output->set_content_type('application/json');
	   
	   $data['rows'] = [];
	   
	   $curl = curl_init();
	    
	   curl_setopt_array($curl, array(
         CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
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
       if($ret['code'] != 0)
       {
           echo $ret['code']." : ".$ret['message'];
       }
       else
       {
           $itemList = $ret['response']['item_list'];
           $sql = "SELECT * FROM MBARANG WHERE BOOSTTIKTOK != 0 group by KATEGORI";
           $dataBarang = $CI->db->query($sql)->result();
           
            foreach($dataBarang as $itemBarang)
            {
               if($itemBarang->idindukbarangtiktok != 0)
               {
                   $waktu = "-";
                   for($i = 0 ; $i < count($itemList) ; $i++)
                   {
                       if($itemBarang->idindukbarangtiktok == $itemList[$i]['item_id'])
                       {
                           $waktu = $itemList[$i]['cool_down_second'];
                       }
                   }
                       
                    array_push($data['rows'],array(
                        'PERMANENT' => $itemBarang->BOOSTTIKTOK == 2 ? true : false,
                        'ID'        => $itemBarang->idindukbarangtiktok,
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
        
        $whereTgltrans = "and TGLTRANS BETWEEN '".$tgl_aw."' and '".$tgl_ak."' ".$whereStatus ;
        if($state == 4)
        {
           $whereTgltrans = "and TGLPENGEMBALIAN BETWEEN '".$tgl_aw."' and '".$tgl_ak."' ".$whereStatus ;
        }
    
        if($state != 4)
        {
            $sql = "SELECT KODEPENJUALANMARKETPLACE as KODEPESANAN, TGLTRANS as TGLPESANAN, MINTGLKIRIM, $statusVar AS STATUS,KODEPENGAMBILAN,
                            SKUPRODUK, '' as BARANG, TOTALBARANG, TOTALHARGA, TOTALBAYAR,  '' as ALAMAT,SKUPRODUKOLD,USERNAME,
                            NAME as BUYERNAME, TELP as BUYERPHONE, ALAMAT as BUYERALAMAT, KOTA,
                            METODEBAYAR, KURIR, RESI, CATATANPEMBELI as CATATANBELI, CATATANPENJUAL AS CATATANJUAL, CATATANPENGEMBALIAN,KODEPACKAGING,
                            KODEPENGEMBALIANMARKETPLACE as KODEPENGEMBALIAN, TGLPENGEMBALIAN, MINTGLPENGEMBALIAN, RESIPENGEMBALIAN, TOTALBARANGPENGEMBALIAN,MINTGLKIRIMPENGEMBALIAN,
                            TOTALPENGEMBALIANDANA, SKUPRODUKPENGEMBALIAN, '' as BARANGPENGEMBALIAN, TIPEPENGEMBALIAN, SELLERMENUNGGUBARANGDATANG,BARANGSAMPAI,BARANGSAMPAIMANUAL
                            FROM TPENJUALANMARKETPLACE b WHERE MARKETPLACE = 'TIKTOK' ".$whereTgltrans."
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
                            WHERE a.MARKETPLACE = 'TIKTOK' and b.TGLPENGEMBALIAN BETWEEN '".$tgl_aw."' and '".$tgl_ak."' $whereStatus 
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
            
            $produk = explode("|",$item->SKUPRODUK);
            $produkOld = explode("|",$item->SKUPRODUKOLD);
            $item->STATUS = $this->getStatus($item->STATUS)['status'];
            $item->TGLPESANAN = explode(" ",$item->TGLPESANAN)[0]."<br>".explode(" ",$item->TGLPESANAN)[1];
            $item->TGLPENGEMBALIAN = explode(" ",$item->TGLPENGEMBALIAN)[0]."<br>".explode(" ",$item->TGLPENGEMBALIAN)[1];
            
            //GET NAMA BARANG
            $sql = "SELECT NAMABARANG, WARNA, SIZE
                        FROM MBARANG WHERE SKUTIKTOK = '".explode("*",$produk[0])[1]."'";
            $dataBarang = $CI->db->query($sql)->row();
            $item->BARANG  = explode(" | ",$dataBarang->NAMABARANG)[0];
            
            $sqlOld = "SELECT WARNA, SIZE
                        FROM MBARANG WHERE SKUTIKTOK = '".explode("*",$produkOld[0])[1]."'";
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
                            FROM MBARANG WHERE SKUTIKTOK = '".explode("*",$produk[$indexPengganti])[1]."'";
                $dataBarang = $CI->db->query($sql)->row();
             
                $item->BARANGPENGEMBALIAN  = explode(" | ",$dataBarang->NAMABARANG)[0];
                
                $sqlOld = "SELECT WARNA, SIZE
                        FROM MBARANG WHERE SKUTIKTOK = '".explode("*",$produkOld[$indexPengganti])[1]."'";
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
            $item->CATATANJUAL = "<i class='fa fa-edit' id='editNoteTiktok' style='cursor:pointer;'></i>
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
	    $parameter = "&ids=".json_encode([$nopesanan]);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
             CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 30,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'POST',
             CURLOPT_POSTFIELDS => array('endpoint' => '/order/202507/orders','parameter' => $parameter),
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
            $sql = "SELECT SKUPRODUK, ifnull(SKUPRODUKOLD,'') as SKUPRODUKOLD, SKUPRODUKPENGEMBALIAN, STATUSMARKETPLACE
                        FROM TPENJUALANMARKETPLACE 
                        WHERE MARKETPLACE = 'TIKTOK' and KODEPENJUALANMARKETPLACE = '$nopesanan' ";
                        
            $resultPesanan = $CI->db->query($sql)->row();
            
            $dataPayment = $ret['data']['orders'][0]['payment'];
          
            $result;
		    $result['BIAYALAINBELI']        = (int)$dataPayment['buyer_service_fee'] + (int)$dataPayment['tax'] + (int)$dataPayment['small_order_fee'] + (int)$dataPayment['shipping_fee_tax'] + (int)$dataPayment['product_tax'] + (int)$dataPayment['retail_delivery_fee']  + (int)$dataPayment['handling_fee']  + (int)$dataPayment['shipping_insurance_fee']  + (int)$dataPayment['item_insurance_fee']  + (int)$dataPayment['item_insurance_tax']; 
		    $result['PEMBAYARANBELI']       = (int)$dataPayment['total_amount']; 
		    $result['DISKONBELI']           = -((int)$dataPayment['shipping_fee_cofunded_discount'] + (int)$dataPayment['shipping_fee_platform_discount'] + (int)$dataPayment['shipping_fee_seller_discount'] + (int)$dataPayment['payment_platform_discount'] ); //+ (int)$dataPayment['platform_discount'] + (int)$dataPayment['payment_discount_service_fee'] 
		    $result['SUBTOTALBELI']         = (int)$dataPayment['sub_total']; 
		    $result['BIAYAKIRIMBELI']       = (int)$dataPayment['original_shipping_fee']; 
		    
		    $result['BIAYALAYANANJUAL']     = 0;
            $result['PENERIMAANJUAL']       = 0;
            $result['DISKONJUAL']           = 0; 
            $result['SUBTOTALJUAL']         = 0;
            $result['BIAYAKIRIMJUAL']       = 0;
            $result['REFUNDJUAL']           = 0;
            $result['PENYELESAIANPENJUAL']  = 0;
		    
            $dataUnsettled = [];
            $adaUnsettled = false;
		    if($this->getStatus($resultPesanan->STATUSMARKETPLACE)['state'] != 3 || $resultPesanan->STATUSMARKETPLACE == 'COMPLETED')
            {
        		//UNSETTLED
                $curl = curl_init();
                $parameter = "&sort_field=order_create_time&page_size=100&search_time_ge=".$ret['data']['orders'][0]['create_time']; 
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('endpoint' => '/finance/202507/orders/unsettled','parameter' => $parameter),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                
                $response = curl_exec($curl);
                curl_close($curl);
                $retUnsettled =  json_decode($response,true);
                if($retUnsettled['code'] != 0)
                {
                    echo $retUnsettled['code']." : ".$retUnsettled['message'];
                }
                else
                {
                    $dataUnsettled = $retUnsettled['data']['transactions'];
                }
                
                for($u = 0; $u < count($dataUnsettled); $u++)
                {
                    if($dataUnsettled[$u]['order_id'] == $nopesanan)
                    {
                        $adaUnsettled = true;
            		    $result['BIAYALAYANANJUAL']     = (int)$dataUnsettled[$u]['est_fee_tax_amount']; 
            		    $result['PENERIMAANJUAL']       = (int)$dataUnsettled[$u]['est_settlement_amount']; 
            		    $result['DISKONJUAL']           = (int)$dataUnsettled[$u]['revenue_breakdown']['seller_discount_amount']+(int)$dataUnsettled[$u]['revenue_breakdown']['seller_discount_refund_amount']; 
            		    $result['SUBTOTALJUAL']         = (int)$result['SUBTOTALBELI']; 
            		    $result['BIAYAKIRIMJUAL']       = (int)$dataUnsettled[$u]['est_shipping_cost_amount'];
            		    $result['REFUNDJUAL']           = (int)$dataUnsettled[$u]['revenue_breakdown']['refund_subtotal_before_discount_amount'];
            		    $result['PENYELESAIANPENJUAL']  = (int)$dataUnsettled[$u]['est_settlement_amount']; 
                    }
                }
          }
		  if(!$adaUnsettled && $resultPesanan->STATUSMARKETPLACE == 'COMPLETED')
		  { 
		       $curl = curl_init();
              $parameter = "";
              curl_setopt_array($curl, array(
                 CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => '',
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => 'POST',
                 CURLOPT_POSTFIELDS => array('endpoint' => '/finance/202501/orders/'.$nopesanan.'/statement_transactions','parameter' => $parameter),
                 CURLOPT_HTTPHEADER => array(
                  'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                 ),
              ));
               
              $response = curl_exec($curl);
              curl_close($curl);
              $retSettled =  json_decode($response,true);
              if($retSettled['code'] != 0)
              {
                  echo $retSettled['code']." : ".$retSettled['message'];
              }
              else
              {
                  
                  $result['BIAYALAYANANJUAL']      = (int)$retSettled['data']['fee_and_tax_amount']; 
            	   $result['PENERIMAANJUAL']       = (int)$retSettled['data']['settlement_amount']; 
            	   $result['DISKONJUAL']           = (int)$retSettled['data']['revenue_breakdown']['seller_discount_amount']+(int)$retSettled['data']['revenue_breakdown']['seller_discount_refund_amount']; 
            	   $result['SUBTOTALJUAL']         = (int)$retSettled['data']['revenue_amount']; 
            	   $result['BIAYAKIRIMJUAL']       = (int)$retSettled['data']['shipping_cost_amount'];
            	   $result['REFUNDJUAL']           = (int)$retSettled['data']['revenue_breakdown']['refund_subtotal_before_discount_amount'];
            	   $result['PENYELESAIANPENJUAL']  = (int)$retSettled['data']['settlement_amount']; 
              }
		  }
		    $result['DETAILBARANG'] = [];
		    
		    
            
            $produkData = explode("|",$resultPesanan->SKUPRODUK);
            $produkDataOld = explode("|",$resultPesanan->SKUPRODUKOLD);
            $dataProduk = [];
            $dataProdukKembali = [];
            $indexProduk = 0;
            foreach($produkData as $item)
            {
                //GET NAMA BARANG
                $sql = "SELECT NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKUTIKTOK as SKU
                            FROM MBARANG WHERE SKUTIKTOK = '".explode("*",$item)[1]."'";
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
                    $sql = "SELECT NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKUTIKTOK as SKU
                                FROM MBARANG WHERE SKUTIKTOK = '".explode("*",$item)[1]."'";
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
                            $sql = "SELECT NAMABARANG, WARNA, SIZE,SKUTIKTOK as SKU
                                        FROM MBARANG WHERE SKUTIKTOK = '".explode("*",$produkDataKembali[$t])[1]."'";
                            $dataBarangKembali = $CI->db->query($sql)->row();
                        
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
            
            //CEK BARANG RETUR MANUAL
    		$sql = "SELECT SKU FROM TPENJUALANMARKETPLACEDTL WHERE KODEPENJUALANMARKETPLACE = '".$nopesanan."' AND BARANGSAMPAIMANUAL = 1 AND KODEPENGEMBALIANMARKETPLACE != ''";
    		$resultQtyBarang = $CI->db->query($sql)->result();
            
		    $dataDetail = $ret['data']['orders'][0]['line_items'];
		    for($x = 0; $x < count($dataDetail) ; $x++)
		    {
		        $resultDetail;
		        $resultDetail['KATEGORI'] = $dataProduk[$x]->KATEGORI;
		        $resultDetail['ITEMID'] = $dataDetail[$x]['id'];
		        $resultDetail['MODELID'] = $dataDetail[$x]['sku_id'];
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
		        $resultDetail['JUMLAH'] = 1;
		        $resultDetail['JUMLAHKEMBALI'] = $dataProduk[$x]->JMLKEMBALI??"0";
		        
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
		        $resultDetail['HARGATAMPIL'] = $dataDetail[$x]['original_price'];
		        $resultDetail['HARGACORET'] = $dataDetail[$x]['sale_price'];
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
	    $parameter = "";
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/return_refund/202309/returns/'.$nopengembalian.'/records','parameter' => $parameter),
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
            $dataRetur = $ret['data']['records'][0];
            $result;
            $result['GAMBAR'] = $dataRetur['images']??[];
            $result['VIDEO'] = $dataRetur['videos']??[];
            
            $result['ALASANPENGEMBALIAN'] = $dataRetur['note'];
            
            
            //PRODUCT RETURN
            $parameter = [];
            $parameter['return_ids'] = [$nopengembalian];
            $dataDetail = [];
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  array(
              'endpoint' => '/return_refund/202309/returns/search',
              'urlparameter' => $urlparameter,
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
                echo $ret['code']." : ".$ret['message'];
                $statusok = false;
                $countTotal = 0;
            }
            else
            {
                for($x = 0 ;$x < count($ret['data']['return_orders']); $x++)
                {
                    $dataDetail = $ret['data']['return_orders'][$x]['return_line_items'];
                    $result['TOTALREFUND'] =  $ret['data']['return_orders'][$x]['refund_amount']['refund_total'];
                };
            }
            
		    $result['DETAILBARANG'] = [];
		    
		     $sql = "SELECT GROUP_CONCAT(b.SKUPRODUKPENGEMBALIAN SEPARATOR '|') as SKUPRODUKPENGEMBALIAN, a.SKUPRODUK, ifnull(a.SKUPRODUKOLD,'') as SKUPRODUKOLD,b.SELLERMENUNGGUBARANGDATANG
                        FROM TPENJUALANMARKETPLACE a
                        INNER JOIN  TPENJUALANMARKETPLACEDTL b  on a.IDPENJUALANMARKETPLACE = b.IDPENJUALANMARKETPLACE
                        WHERE b.MARKETPLACE = 'TIKTOK' and b.KODEPENGEMBALIANMARKETPLACE = '$nopengembalian' ";
                        
            $resultPesanan = $CI->db->query($sql)->row();
            $produkDataPengembalian = explode("|",$resultPesanan->SKUPRODUKPENGEMBALIAN);
            $produkData = explode("|",$resultPesanan->SKUPRODUK);
            $produkDataOld = explode("|",$resultPesanan->SKUPRODUKOLD);
            $result['SELLERMENUNGGUBARANGDATANG'] = $resultPesanan->SELLERMENUNGGUBARANGDATANG;
            $dataProduk = [];
            $indexProduk = 0;
            
            $itemTidakAda = true;
            foreach($produkData as $item)
            {
                //GET NAMA BARANG
                $sql = "SELECT IDINDUKBARANGTIKTOK, NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKUTIKTOK as SKU
                            FROM MBARANG WHERE SKUTIKTOK = '".explode("*",$item)[1]."'";
                $dataBarang = $CI->db->query($sql)->row();
                $dataProduk[$indexProduk]->BARANG  = explode(" | ",$dataBarang->NAMABARANG)[0];
                if(count(explode(" | ",$dataBarang->NAMABARANG)) > 1)
                {
                    $dataProduk[$indexProduk]->BARANG .= "<br><i>".$dataBarang->WARNA.", ".$dataBarang->SIZE."</i>";
                }
                $dataProduk[$indexProduk]->IDINDUKBARANGTIKTOK = $dataBarang->IDINDUKBARANGTIKTOK;
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
                    $sql = "SELECT NAMABARANG, WARNA, SIZE, SATUAN, KATEGORI,SKUTIKTOK as SKU
                                FROM MBARANG WHERE SKUTIKTOK = '".explode("*",$item)[1]."'";
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
                            $sql = "SELECT NAMABARANG, WARNA, SIZE, SKUTIKTOK as SKU
                                        FROM MBARANG WHERE SKUTIKTOK = '".explode("*",$produkData[$indexPengganti])[1]."'";
                            $dataBarang = $CI->db->query($sql)->row();
                            
                            $sqlOld = "SELECT WARNA, SIZE
                                    FROM MBARANG WHERE SKUTIKTOK = '".explode("*",$produkDataOld[$indexPengganti])[1]."'";
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
    		        $resultDetail['ITEMID'] = $dataDetail[$x]['sku_id'];
    		        $resultDetail['MODELID'] = $dataDetail[$x]['sku_id'];
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
    		        $resultDetail['HARGA'] =  (float)($dataDetail[$x]['refund_amount']['refund_total']) ;
    		        $resultDetail['SUBTOTAL'] = ($resultDetail['JUMLAH'] * $resultDetail['HARGA']);
    		        array_push($result['DETAILBARANG'],$resultDetail);
                }
                else
                {
                    $resultDetail;
    		        $resultDetail['KATEGORI'] = "-";
    		        $resultDetail['ITEMID'] =  $dataDetail[$x]['sku_id'];
    		        $resultDetail['MODELID'] = $dataDetail[$x]['sku_id'];
    		        $resultDetail['NAMA'] = "-<br>Barang Tidak terhubung dengan master barang";
    		        $resultDetail['WARNA'] = "";
    		        $resultDetail['SIZE'] = "";
    		        $resultDetail['SKU'] =  "";
    		        $resultDetail['NAMAOLD'] = "-<br>Barang Tidak terhubung dengan master barang";
    		        $resultDetail['WARNAOLD'] =  "";
    		        $resultDetail['SIZEOLD'] =  "";
    		        $resultDetail['SKUOLD'] =  "";
    		        
    		        $resultDetail['JUMLAH'] = 1;
    		        $resultDetail['SATUAN'] = "";
    		        $resultDetail['HARGA'] =  (float)($dataDetail[$x]['refund_amount']['refund_total']);
    		        $resultDetail['SUBTOTAL'] =  ($resultDetail['JUMLAH'] * $resultDetail['HARGA']);
    		        array_push($result['DETAILBARANG'],$resultDetail);
                }
		    }
		    echo(json_encode($result));
        }

	}
	
	public function getAlasanReject(){
	    
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kode')??"";
		 
		$curl = curl_init();
		$parameter = "&return_or_cancel_id=".$nopengembalian."&locale=id-ID";
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/return_refund/202309/reject_reasons','parameter' => $parameter),
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
            $statusok = false;
        }
        else
        {
            $arrayResult = $ret['data']['reasons'];
        }
	
           
		echo(json_encode($arrayResult));

	}
	
	public function returnRefund(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kodepengembalian')??"";
		$type = $this->input->post('type')??"";
		
		
		if($type == "MENUNGGU")
		{
		     $CI->db->where("KODEPENGEMBALIANMARKETPLACE",$nopengembalian)
		        ->where('MARKETPLACE','TIKTOK')
		            ->set("SELLERMENUNGGUBARANGDATANG", 1)
		            ->updateRaw("TPENJUALANMARKETPLACEDTL");

           
            $data['success'] = true;
            $data['msg'] = "Proses Pengembalian #".$nopengembalian." Diserahkan Kepada Tiktok";
            echo(json_encode($data));
		}
		else
		{
        	$parameter = [];
        	$parameter['decision'] = $type;
        	
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  array(
              'endpoint' =>'/return_refund/202309/returns/'.$nopengembalian.'/approve',
              'parameter' => json_encode($parameter)),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
              ),
            ));
              
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            
            
            sleep(5); 
            
            $this->init(date('Y-m-d'),date('Y-m-d'),'update',false);
                
            if($ret['code'] != 0)
            {
                $data['success'] = false;
                $data['msg'] =  $ret['code']." : ".$ret['message'];
                die(json_encode($data));
            }
            else
            {
                $data['success'] = true;
                $data['msg'] = "Pengembalian #".$nopengembalian." Berhasil Disetujui";
                echo(json_encode($data));
            }
		}

	}
	
	public function rejectReturnRefund(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kodepengembalian')??"";
		$type = $this->input->post('type')??"";
		$reason = $this->input->post('reason')??"";
		$alasandetail = $this->input->post('alasandetail')??"";
		
		
		$imageid = $this->input->post('imageid')??"";
		$tipe = $this->input->post('tipe')??"";
		$panjang = $this->input->post('panjang')??"";
		$lebar = $this->input->post('lebar')??"";
	
    	//HAPUS PESANAN
    	
    	$parameter = [];
    	$parameter['decision'] = $type;
    	$parameter['reject_reason'] = $reason;
    	$parameter['comment'] = $alasandetail;
    	$parameter['images'] = array(array(
    	    'image_id' => $imageid,
    	    'mime_type' => $tipe,
    	    'height' => (int)$panjang,
    	    'width' => (int)$lebar
    	));
    	
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => '/return_refund/202309/returns/'.$nopengembalian.'/reject',
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        
        
        sleep(5); 
        
        $this->init(date('Y-m-d'),date('Y-m-d'),'update',false);
            
        if($ret['code'] != 0)
        {
            $data['success'] = false;
            $data['msg'] =  $ret['code']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
            $data['success'] = true;
            $data['msg'] = "Pengembalian / Penukaran #".$nopengembalian." Berhasil Dibatalkan / Ditolak";
            echo(json_encode($data));
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
		$reason =  $this->input->post('reason')??"";
	
		if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            die(json_encode([
                'success' => false,
                'msg' => 'File upload failed.'
            ]));
        }
        
        // Destination folder (make sure this folder exists and is writable)
        $uploadDir = FCPATH . 'assets/produk/'; 
        
        // Create filename and move the file
        $type = ".".explode(".",$_FILES['file']['name'])[count(explode(".",$_FILES['file']['name']))-1];
        $destination = $uploadDir . $kode . "_" . $index.$type;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
           
            if($tipe == "GAMBAR")
            {
                $response =  $this->getRefreshToken();
        	    if($response)
        	    {
                    // Path to the local file
                    $filePath = $destination;
                    
                    // Check if file exists
                    if (!file_exists($filePath)) {
                    die('File not found.');
                    }
                    
                    // Prepare the CURLFile object
                    $cfile = new CURLFile($filePath, mime_content_type($filePath), basename($filePath));
                 
                	$endpoint = "/product/202309/images/upload";
                	$urlparameter = $this->input->post("urlparameter")??"";
                	$parameter = array(
                	    'data' => $cfile,
                	    'use_case' => $reason
                	);
                	
                	$accessToken = $this->model_master_config->getConfigMarketplace('TIKTOK','ACCESS_TOKEN');
                	
                	//BUAT SIGN
                	// 1
                    $host = 'https://open-api.tiktokglobalshop.com'.$endpoint;
                	
                	$app_key = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_KEY');
                	$timest = strtotime(date("Y-m-d H:i:s", time()));
            
                	$urlparameter = "app_key=$app_key&timestamp=".$timest.$urlparameter;
                	
                	$allparameter = $urlparameter;
                    
                    $arrParam = explode("&",$allparameter);
            
                    $paramMap = [];
                    
                    foreach ($arrParam as $itemParam) {
                        list($key, $value) = explode("=", $itemParam, 2);
                        $paramMap[$key] = $value;       // assign directly
                    }
                    ksort($paramMap);
                    
                    // 2
                    $stringParam = "";
                    
                    foreach ($paramMap as $key => $value) {
                       $stringParam .= $key.$value;
                    }
                    
                    // 3
                    $stringParam = $endpoint.$stringParam;
                    
                    // 4
                    $app_secret = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_SECRET');
                    $stringParam = $app_secret.$stringParam.$app_secret;
                    // 5
                    $sign = hash_hmac('sha256', $stringParam,$app_secret);
                    //BUAT SIGN
                    
                    $urlparameter = $urlparameter."&sign=".$sign;
               
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $host.'?'.$urlparameter,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_POSTFIELDS => ($parameter),
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_HTTPHEADER => array(
                         'x-tts-access-token: '.$accessToken,
                        // 'Content-Type: multipart/form-data',
                        'Cookie: SPC_SEC_SI=v1-UHAwR1pMemVVcnVNWk0yOAmAUDORs28JVn6OsCvGiwjQgIgi70oQOziTBqSXWyDZViEAeIWx3hwddNsCKaZ6iPdS4KmbbymAcw3YAhkMr6I=; SPC_SI=P7sdaAAAAABzUmtoeEtWN8sBwggAAAAATEVRUWR2b0Y='
                      ),
                    ));
                    
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $ret =  json_decode($response,true);
                    
                    if($ret['code'] != 0)
                    {
                        $data['success'] = false;
                        $data['msg'] =  $ret['code']." : ".$ret['message'];
                        die(json_encode($data));
                    }
                    else
                    {
                        $data['success'] = true;
                        $data['url'] = $ret['data']['url'];
                        $data['id'] =  $ret['data']['uri'];
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
	
	public function changeAllLocalUrl(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
		$urlData = json_decode($this->input->post('url'))??[];
		$response =  $this->getRefreshToken();
		
		$dataConfig = [];
        $dataConfig['ACCESS_TOKEN'] = $this->model_master_config->getConfigMarketplace('TIKTOK','ACCESS_TOKEN');
        $dataConfig['APP_KEY'] = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_KEY');
        $dataConfig['APP_SECRET'] = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_SECRET');

		foreach($urlData as $itemUrl)
		{
		    $resp_url = $this->changeLocalUrl($itemUrl->url,$itemUrl->reason,false,$response,$dataConfig);

		    if($resp_url['success'])
		    {
    		    $itemUrl->{'id-baru'} = $resp_url['id'];
    		    $itemUrl->{'url-baru'} = $resp_url['url'];
		    }
		    else
		    {
                die(json_encode($resp_url));
		    }
		}
		
	   $data['success'] = true;
       $data['data'] = $urlData;
       echo(json_encode($data));
	}
	
	public function changeLocalUrl($url="",$reason="", $output=true,$response = false,$dataConfig = []){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		
		if($url == "")
		{
		    $url =  $this->input->post('url');
		}
		if($reason == "")
		{
		    $reason =  $this->input->post('reason');
		}
		
        $uploadDir = FCPATH . 'assets/produk/TIKTOK/image_'.$reason.'_'.date('Ymdhms').'.jpg'; 
        
        file_put_contents($uploadDir, file_get_contents($url));
        $destination = $uploadDir;
        if($output)
        {
            $response =  $this->getRefreshToken();
            $accessToken = $this->model_master_config->getConfigMarketplace('TIKTOK','ACCESS_TOKEN');
        	$app_key = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_KEY');
            $app_secret = $this->model_master_config->getConfigMarketplace('TIKTOK','APP_SECRET');
        }
        if(count($dataConfig) > 0)
        {
            $accessToken = $dataConfig['ACCESS_TOKEN'];
        	$app_key = $dataConfig['APP_KEY'];
            $app_secret = $dataConfig['APP_SECRET'];
        }
        if($response)
        {
            // Path to the local file
            $filePath = $destination;
            
            // Check if file exists
            if (!file_exists($filePath)) {
            die('File not found.');
            }
            
            // Prepare the CURLFile object
            $cfile = new CURLFile($filePath, mime_content_type($filePath), basename($filePath));
         
        	$endpoint = "/product/202309/images/upload";
        	$urlparameter = $this->input->post("urlparameter")??"";
        	$parameter = array(
        	    'data' => $cfile,
        	    'use_case' => $reason
        	);
        	
        	
        	
        	//BUAT SIGN
        	// 1
            $host = 'https://open-api.tiktokglobalshop.com'.$endpoint;
        	
        	$timest = strtotime(date("Y-m-d H:i:s", time()));
        
        	$urlparameter = "app_key=$app_key&timestamp=".$timest.$urlparameter;
        	
        	$allparameter = $urlparameter;
            
            $arrParam = explode("&",$allparameter);
        
            $paramMap = [];
            
            foreach ($arrParam as $itemParam) {
                list($key, $value) = explode("=", $itemParam, 2);
                $paramMap[$key] = $value;       // assign directly
            }
            ksort($paramMap);
            
            // 2
            $stringParam = "";
            
            foreach ($paramMap as $key => $value) {
               $stringParam .= $key.$value;
            }
            
            // 3
            $stringParam = $endpoint.$stringParam;
            
            // 4
            $stringParam = $app_secret.$stringParam.$app_secret;
            // 5
            $sign = hash_hmac('sha256', $stringParam,$app_secret);
            //BUAT SIGN
            
            $urlparameter = $urlparameter."&sign=".$sign;
        
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $host.'?'.$urlparameter,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_POSTFIELDS => ($parameter),
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_HTTPHEADER => array(
                 'x-tts-access-token: '.$accessToken,
                // 'Content-Type: multipart/form-data',
                'Cookie: SPC_SEC_SI=v1-UHAwR1pMemVVcnVNWk0yOAmAUDORs28JVn6OsCvGiwjQgIgi70oQOziTBqSXWyDZViEAeIWx3hwddNsCKaZ6iPdS4KmbbymAcw3YAhkMr6I=; SPC_SI=P7sdaAAAAABzUmtoeEtWN8sBwggAAAAATEVRUWR2b0Y='
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $ret =  json_decode($response,true);
            
            unlink($destination);
                      
            if($ret['code'] != 0)
            {
                $data['success'] = false;
                $data['msg'] =  $ret['code']." : ".$ret['message'];
                
                if($output)
                {
                    die(json_encode($data));
                }
                else
                {
                    return $data;
                }
            }
            else
            {
                $data['success'] = true;
                $data['url'] = $ret['data']['url'];
                $data['id'] =  $ret['data']['uri'];
                
                if($output)
                {
                    echo(json_encode($data));
                }
                else
                {
                    return $data;
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
        
        // print_r($parameter);
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
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
            $data['msg'] =  $ret['code']." : ".$ret['message'];
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
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
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
            echo $ret['code']." : ".$ret['message'];
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
            
            $sqlBarang = "select idbarang from mbarang where SKUTIKTOK = '$sku'";
            $queryBarang = $CI->db->query($sqlBarang)->row();
                               
            //UPDATE DETAIL BARANG
            $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
		            ->where('MARKETPLACE','TIKTOK')
		            ->where('URUTAN',$urutan)
		            ->set("SKU", $sku)
		            ->set("IDBARANG", $queryBarang->IDBARANG)
		            ->updateRaw("TPENJUALANMARKETPLACEDTL");
		}
		 $allskuold = substr($allskuold, 0, -1);
		 $allsku = substr($allsku, 0, -1);
		 
		
		 $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
		        ->where('MARKETPLACE','TIKTOK')
		            ->set("SKUPRODUKOLD", $allskuold)
		            ->updateRaw("TPENJUALANMARKETPLACE");
		            
		 $CI->db->where("KODEPENJUALANMARKETPLACE",$nopesanan)
		        ->where('MARKETPLACE','TIKTOK')
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
               ->where("MARKETPLACE","TIKTOK")
	           ->set("CATATANPENJUAL", $note)
	           ->updateRaw("TPENJUALANMARKETPLACE");
	        
       $data['success'] = true;
       $data['msg'] = "Catatan #".$parameter['order_sn']." Berhasil Disimpan";
       echo(json_encode($data));

	}
	
	public function setKirim(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$dataNoPackaging = json_decode($this->input->post('dataNoPackaging'),true);
		
	    //GET RESI
        $parameter = "";
        $data = [];
        $curl = curl_init();
        
        foreach($dataNoPackaging as $itemPackaging)
        {
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('endpoint' => '/fulfillment/202309/packages/'.$itemPackaging['KODEPACKAGING'].'/handover_time_slots','parameter' => $parameter),
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
                $statusok = false;
            }
            else
            {
                $res = $ret['data']['pickup_slots'];
                
                if(count($res) > 0)
                {
                    $data['index'] = $this->input->post('index')??0;
                    $data['time_pickup'] = [];
                    
                    for($x = 0 ; $x < count($res) ; $x++)
                    {
                      if($res[$x]['avaliable']){
                          $startDate = new DateTime('@' . (int)$res[$x]['start_time']);
                                        $startDate->modify('+7 hours');
                                        
                          $endDate = new DateTime('@' . (int)$res[$x]['end_time']);
                                        $endDate->modify('+7 hours');
                                        
                                        
                         array_push(
                            $data['time_pickup'], array(
                                'id_pickup' => $startDate->format('Y-m-d H:i:s')."|".$endDate->format('Y-m-d H:i:s'),
                                'start_pickup' => $startDate->format('Y-m-d H:i:s'),
                                'end_pickup' => $endDate->format('Y-m-d H:i:s')
                            )
                         );
                      }
                    }
                }
                
                // $minStartPickup = 0;
                // $minEndPickup =  0;
                
                // $maxStartPickup = 0;
                // $maxEndPickup =  0;
                
                // for($x = 0 ; $x < count($res) ; $x++)
                // {
                //   if($res[$x]['avaliable']){
                //       if($minStartPickup == 0)
                //       {
                //             $minStartPickup = $res[$x]['start_time'];
                //       }
                //         $maxStartPickup = $res[$x]['start_time'];
                         
                //       if($minEndPickup == 0)
                //       {
                //         $minEndPickup =  $res[$x]['end_time'];
                //       }
                //       $maxEndPickup =  $res[$x]['end_time'];
                //   }
                // }
                
                // $data['success'] = true; 
                // $dtStartMin = new DateTime('@' . (int)$minStartPickup);
                // $dtStartMin->modify('+7 hours');
                
                // $dtStartMax = new DateTime('@' . (int)$maxStartPickup);
                // $dtStartMax->modify('+7 hours');
                
                // $dtEndMin = new DateTime('@' . (int)$minEndPickup);
                // $dtEndMin->modify('+7 hours');
                
                // $dtEndMax = new DateTime('@' . (int)$maxEndPickup);
                // $dtEndMax->modify('+7 hours');

                // $data['minStartPickup'] = $dtStartMin->format('Y-m-d H:i:s');
                // $data['minEndPickup'] = $dtStartMax->format('Y-m-d H:i:s');
                // $data['maxStartPickup'] = $dtEndMin->format('Y-m-d H:i:s');
                // $data['maxEndPickup'] = $dtEndMax->format('Y-m-d H:i:s');
            }
        }
             
        echo(json_encode($data));
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
            if($item['METHOD'] == "PICKUP" && ($item['JAMAWAL'] == "" || $item['JAMAKHIR'] == ""))
            {
                $data['success'] = false;
                $data['msg'] = "Terdapat Waktu Pickup yang belum ditentukan";
                die(json_encode($data));
            }
            
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
        	$parameter = [];
        	$packaging = [];
            $indexPackaging = 0;
            
    		for($s = 0 ; $s < count($order);$s++){
    		    if($kurir[$a] == $order[$s]['KURIR'])
    		    {
    		        array_push($dataPesanan, $order[$s]);
    		    }
    		}
            
            for($k = 0 ; $k < count($dataPesanan);$k++){
            
                $indexPackaging = count($packaging);
                
                $packaging[$indexPackaging]['id'] = $dataPesanan[$k]['KODEPACKAGING'];
                $packaging[$indexPackaging]['handover_method'] = $dataPesanan[$k]['METHOD'];

                if($dataPesanan[$k]['METHOD'] == "PICKUP")
                {
                    $dateAw = new DateTime(
                        $dataPesanan[$k]['JAMAWAL'],      // contoh: '2026-01-03 12:20:00'
                        new DateTimeZone('Asia/Jakarta')  // WIB
                    );
                    
                    $dateAw->setTimezone(new DateTimeZone('UTC'));
                    
                    $dateAk = new DateTime(
                        $dataPesanan[$k]['JAMAKHIR'],      // contoh: '2026-01-03 12:20:00'
                        new DateTimeZone('Asia/Jakarta')  // WIB
                    );
                    
                    $dateAk->setTimezone(new DateTimeZone('UTC'));
                    
                    $packaging[$indexPackaging]['pickup_slot'] = array(
                        'start_time' => $dateAw->getTimestamp(), 
                        'end_time' => $dateAk->getTimestamp(),
                        // 'start_time_raw' => $dateAw, 
                        // 'end_time_raw' => $dateAk
                    );
                }
            }
            
            //GET RESI
            $parameter['packages'] =  $packaging;
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  array(
              'endpoint' => "/fulfillment/202309/packages/ship",
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
                echo $ret['code']." : ".$ret['message'];
                $statusok = false;
            }
            else
            {
                $nopesanan = "";
                if(count($dataPesanan) == 1 && count($kurir) == 1)
                {
                    $nopesanan = "#".$dataPesanan[0]['KODEPESANAN'];
                }
              //MASUK KE PROCCESSED
              $data['success'] = true;
              $data['msg'] = "Pesanan ".$nopesanan." Berhasil Dikirim";
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
		$nopackaging = $this->input->post('kodepackaging')??"";
		
	    //GET RESI
        $parameter = "";
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/fulfillment/202309/orders/'.$nopesanan.'/tracking','parameter' => $parameter),
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
            $statusok = false;
        }
        else
        {
            $data = $ret['data']['tracking'];
             
            for($x = 0 ; $x < count($data) ; $x++)
            {
                $millis = $data[$x]['update_time_millis'];
                $dt = (new DateTime('@' . (int) floor($millis / 1000)))
                        ->setTimezone(new DateTimeZone('Asia/Jakarta'));
                
                $dt->format('Y-m-d H:i:s');

                $data[$x]['update_time'] = $dt->format('Y-m-d H:i:s');
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
		    if (file_exists(FCPATH."assets/label/tiktok/waybill_".$item['KODEPESANAN']."_compressed.pdf")) {
               $files[$a] = FCPATH."assets/label/tiktok/waybill_".$item['KODEPESANAN']."_compressed.pdf";
               $data['code'] = "Done";
            } else {
               $dataRePrint = $this->reprint([$item]);
               if($dataRePrint['success'])
               {
                $files[$a] = FCPATH."assets/label/tiktok/waybill_".$dataRePrint['KODEPESANAN']."_compressed.pdf";
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
    
        $output_file = "assets/label/tiktok/waybill_merge.pdf";
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
    		$parameter = "&document_type=SHIPPING_LABEL";
        	for($f = 0 ; $f < count($invoiceKirim);$f++){	    
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('endpoint' => '/fulfillment/202309/packages/'.$invoiceKirim[$f]['package_number'].'/shipping_documents','parameter' => $parameter),
                  CURLOPT_HTTPHEADER => array(
                    'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
                  ),
                ));
                  
                $response = curl_exec($curl);
                $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $content_type = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
                
                curl_close($curl);
                $ret =  json_decode($response,true);
                if($ret['code'] != 0)
                {
                    $data['success'] = false;
                    $data['msg'] =   $ret['code']." : ".$ret['message'];
                    $data['ret'] = $ret;
                }
                else
                {
                    // Save the file if request is successful
                    if ($http_code == 200) {
                        
                        $pdfContent = file_get_contents($ret['data']['doc_url']);

                        if ($pdfContent === false) {
                            die('Gagal download PDF');
                        }

                        file_put_contents("assets/label/tiktok/waybill_".$invoiceKirim[$f]['order_sn'].".pdf", $pdfContent);
                        
                        $input = FCPATH . "assets/label/tiktok/waybill_".$invoiceKirim[$f]['order_sn'].".pdf";
                        $output = FCPATH . "assets/label/tiktok/waybill_".$invoiceKirim[$f]['order_sn']."_compressed.pdf";
                        
                        $cmd = "gs -sDEVICE=pdfwrite \
                              -dDEVICEWIDTHPOINTS=283 \
                              -dDEVICEHEIGHTPOINTS=425 \
                              -dPDFFitPage \
                              -dNOPAUSE -dQUIET -dBATCH \ -sOutputFile='$output' '$input'";
                        
                        exec($cmd, $outputLines, $status);
                        $data['success'] = true;
                        $data['KODEPESANAN'] = $invoiceKirim[$f]['order_sn'];
                    } else {
                        $data['success'] = false;
                        $data['msg'] =  "Failed to download file. HTTP Status: $http_code\n";;
                    }
                }
        	}
    	}
        
        return $data;

	}
	
	public function printPDF(){
	   
	   $CI =& get_instance();	
	   $CI->load->library('Pdf_merger'); 
	   
	    $input = FCPATH . 'assets/label/tiktok/waybill.pdf';
        $output = FCPATH . 'assets/label/tiktok/waybill_compressed.pdf';
        
        $cmd = "gs -sDEVICE=pdfwrite \
               -dDEVICEWIDTHPOINTS=283 \
               -dDEVICEHEIGHTPOINTS=425 \
               -dPDFFitPage \
               -dNOPAUSE -dQUIET -dBATCH \ -sOutputFile='$output' '$input'";
        
        exec($cmd, $outputLines, $status);
        
        if ($status === 0) {
    	   $files = [
                'assets/label/tiktok/waybill_compressed.pdf',
                'assets/label/tiktok/waybill_compressed.pdf',
            ];
    
            foreach ($files as $file) {
                $pageCount = $this->pdf_merger->setSourceFile($file);
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $tpl = $this->pdf_merger->importPage($pageNo);
                    $this->pdf_merger->AddPage('P',[100, 100]);
                    $this->pdf_merger->useTemplate($tpl);
                }
            }
    
            $output_file = "assets/label/tiktok/waybill_merge.pdf";
            $this->pdf_merger->Output('F', $output_file); // Simpan ke file
        
            // Kembalikan URL hasil merge sebagai JSON
            echo json_encode(['pdf_url' => base_url('assets/label/tiktok/waybill_merge.pdf')]);
        } else {
            echo "Gagal convert PDF. Cek Ghostscript terinstall atau tidak.";
        }
       
	}
	
	public function getAlasanPembatalan(){

        $curl = curl_init();
        
        $status = $this->input->post('status')??"";
        $alasan = [];
        if(strtoupper($status) == "BELUM BAYAR")
        {
            $alasan = [
                [
                     'reason_id' => 'seller_cancel_unpaid_reason_out_of_stock',
                     'reason_name' => 'Out of stock'
                ],
                [
                     'reason_id' => 'seller_cancel_unpaid_reason_wrong_price',
                     'reason_name' => 'Pricing error'
                ],
                [
                     'reason_id' => 'seller_cancel_unpaid_reason_buyer_hasnt_paid_within_time_allowed',
                     'reason_name' => 'Buyer did not pay on time'
                ],
                [
                     'reason_id' => 'seller_cancel_unpaid_reason_buyer_requested_cancellation',
                     'reason_name' => 'Buyer requested cancellation'
                ]
            ];
        }
        else if(strtoupper($status) == "MENUNGGU" || strtoupper($status) == "SIAP DIKIRIM")
        {
            $alasan = [
                [
                     'reason_id' => 'seller_cancel_reason_out_of_stock',
                     'reason_name' => 'Out of stock'
                ],
                [
                     'reason_id' => 'seller_cancel_reason_wrong_price',
                     'reason_name' => 'Pricing error'
                ],
                [
                     'reason_id' => 'seller_cancel_paid_reason_address_not_deliver',
                     'reason_name' => 'Unable to deliver to buyer address'
                ],
                [
                     'reason_id' => 'seller_cancel_paid_reason_buyer_requested_cancellation',
                     'reason_name' => 'Buyer requested cancellation'
                ]
            ];
        }
        
        $ret['success'] = true;
        $ret['data'] = $alasan;
        echo json_encode($ret); 
	}
	
	
	public function hapus(){
	    $CI =& get_instance();
		$this->output->set_content_type('application/json');
		$nopesanan = $this->input->post('kode')??"";
		$alasan = $this->input->post('alasan')??"";
		$dataitem = json_decode($this->input->post('dataItem'),true)??"";
		
		$parameter = [];
		$parameter['order_id'] = $nopesanan;
		$parameter['cancel_reason'] = $alasan;
// 		$parameter['item_list'] = [];
// 		foreach($dataitem as $item)
// 		{
// 		    $parameter['item_list'][count($parameter['item_list'])] = array(
// 		      "item_id"  => $item['ITEMID'],
// 		      "model_id"  => $item['MODELID']
// 		    );
// 		}
		//HAPUS PESANAN
		
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  array(
          'endpoint' => '/return_refund/202309/cancellations',
          'parameter' => json_encode($parameter)),
          CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=98dd861508777823e02f6276721dc2d2189d25b8'
          ),
        ));
          
        $response = curl_exec($curl);
        curl_close($curl);
        $ret =  json_decode($response,true);
        
        
        sleep(3); 
        
        $this->init(date('Y-m-d'),date('Y-m-d'),'update',false);
            
        if($ret['code'] != 0)
        {
            $data['success'] = false;
            $data['msg'] =  $ret['code']." : ".$ret['message'];
            die(json_encode($data));
        }
        else
        {
        
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
	    else if($orderStatus == "ON_HOLD")
	    {
	        return [
	            "status" => "Menunggu",
	            "state"  => 1,
	        ];
	    }
	    else if($orderStatus == "AWAITING_SHIPMENT")
	    {
	         return [
	            "status" => "Siap Dikirim",
	            "state"  => 1,
	         ];
	    }
	   // else if($orderStatus == "RETRY_SHIP")
	   // {
	   //     return [
	   //         "status" => "Dikirim Ulang",
	   //         "state"  => 2,
	   //      ];
	   // }
	    else if($orderStatus == "AWAITING_COLLECTION")
	    {
	        return [
	            "status" => "Diproses",
	            "state"  => 1,
	        ];
	    }
	    else if($orderStatus == "IN_TRANSIT")
	    {
	        return [
	            "status" => "Dalam Pengiriman",
	            "state"  => 2,
	         ];
	    }
	    else if($orderStatus == "DELIVERED")
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
	   // else if($orderStatus == "IN_CANCEL")
	   // {
	   //     return [
	   //         "status" => "Dibatalkan Penjual",
	   //         "state"  => 1,
	   //      ];
	   // }
	    else if($orderStatus == "CANCELLED")
	    {
	        return [
	            "status" => "Pembatalan",
	            "state"  => 3,
	         ];
	    }
	    else if($orderStatus == "RETURNED")
	    {
	        return [
	            "status" => "Pengembalian",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|RETURN_OR_REFUND_REQUEST_PENDING")
	    {
	        return [
	            "status" => "Permintaan<br>Pengembalian",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|AWAITING_BUYER_SHIP")
	    {
	        return [
	            "status" => "Barang<br>Akan Dikirim",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|REQUEST_REJECTED" || $orderStatus == "RETURNED|REFUND_OR_RETURN_REQUEST_REJECT")
	    {
	        return [
	            "status" => "Pengembalian<br>Ditolak",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|REQUEST_SUCCESS")
	    {
	        return [
	            "status" => "Pengembalian<br>Diterima",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|RECEIVE_REJECTED")
	    {
	        return [
	            "status" => "Penerimaan<br>Barang Ditolak",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|RETURN_OR_REFUND_CANCEL")
	    {
	        return [
	            "status" => "Pengembalian<br>Dibatalkan",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|BUYER_SHIPPED_ITEM")
	    {
	        return [
	            "status" => "Barang<br>Telah Dikirim",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|RETURN_OR_REFUND_REQUEST_SUCCESS")
	    {
	        return [
	            "status" => "Pengembalian<br>Berhasil",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|REPLACEMENT_REQUEST_PENDING")
	    {
	        return [
	            "status" => "Permintaan<br>Penukaran",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|REPLACEMENT_REQUEST_COMPLETE")
	    {
	        return [
	            "status" => "Penukaran<br>Selesai",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|REPLACEMENT_REQUEST_REFUND_SUCCESS")
	    {
	        return [
	            "status" => "Penukaran<br>dan Pengembalian Dana",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|REPLACEMENT_REQUEST_CANCEL")
	    {
	        return [
	            "status" => "Penukaran<br>Dibatalkan",
	            "state"  => 4,
	         ];
	    }
	    else if($orderStatus == "RETURNED|REPLACEMENT_REQUEST_REJECT")
	    {
	        return [
	            "status" => "Penukaran<br>Ditolak",
	            "state"  => 4,
	         ];
	    }
	    
	    
	    
	    return $orderStatus;
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
            $sqlPesanan = "SELECT * FROM TPENJUALANMARKETPLACE WHERE MARKETPLACE = 'TIKTOK' and KODEPENJUALANMARKETPLACE = '".$kodetrans."'";
            $resultPesanan = $CI->db->query($sqlPesanan)->row();
            
            $produkData = explode("|",$resultPesanan->SKUPRODUK);
            foreach($produkData as $item)
            {
                //GET ID BARANG
                $sql = "SELECT MBARANG.IDPERUSAHAAN, MBARANG.IDBARANG, ifnull(MHARGA.HARGAKONSINYASI,0) as HARGA
                            FROM MBARANG 
                            INNER JOIN MHARGA on MBARANG.IDBARANG = MHARGA.IDBARANG
                            INNER JOIN MCUSTOMER on MCUSTOMER.IDCUSTOMER = MHARGA.IDCUSTOMER
                            WHERE MBARANG.SKUTIKTOK = '".explode("*",$item)[1]."' and MCUSTOMER.NAMACUSTOMER = 'TIKTOK'";
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
                	"JENISTRANS"    => 'JUAL TIKTOK',
                	"KETERANGAN"    => 'PENJUALAN TIKTOK KE '.$resultPesanan->USERNAME,
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
            $sqlPesanan = "SELECT b.SKUPRODUKPENGEMBALIAN, a.SKUPRODUK,a.SKUPRODUKOLD,b.KODEPENGEMBALIANMARKETPLACE,b.TGLPENGEMBALIAN,b.KODEBARANGPENGEMBALIANMARKETPLACE AS IDTRANS,a.USERNAME FROM TPENJUALANMARKETPLACE  a
                            INNER JOIN TPENJUALANMARKETPLACEDTL b ON a.KODEPENJUALANMARKETPLACE = b.KODEPENJUALANMARKETPLACE
                            WHERE a.MARKETPLACE = 'TIKTOK' and b.KODEPENGEMBALIANMARKETPLACE = '".$kodetrans."'";
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
                                   WHERE MBARANG.SKUTIKTOK = '".explode("*",$produkDataKembali[$t])[1]."' and MCUSTOMER.NAMACUSTOMER = 'TIKTOK'";
                
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
                       	"JENISTRANS"    => 'RETUR JUAL TIKTOK',
                       	"KETERANGAN"    => 'RETUR TIKTOK KE '.$resultPesanan->USERNAME,
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
                          AND JENISTRANS = "RETUR JUAL TIKTOK"
                          GROUP BY IDBARANG
                          ORDER BY URUTAN';
               
              $queryGroup = $CI->db->query($sqlGroup)->result();
               
              $CI->db->where('KODETRANS',$resultPesanan->KODEPENGEMBALIANMARKETPLACE)->where('JENISTRANS','RETUR JUAL TIKTOK')->delete($labelKartuStok);
               
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
                  $parameter = "";
                  $idHeader = 0;
                  
                  $curl = curl_init();
        
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS => array('endpoint' => '/logistics/202309/warehouses','parameter' => $parameter),
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
                        $dataAddress = $ret['data']['warehouses'];
                        $data['rows'] = [];
                        for($x = 0 ; $x < count($dataAddress);$x++)
                        {
                            $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI, IFNULL(NAMALOKASI,'') as NAMALOKASI FROM MLOKASI WHERE (IDLOKASITIKTOKPICKUP = ".$dataAddress[$x]['id']." OR  IDLOKASITIKTOKRETUR = ".$dataAddress[$x]['id'].") AND GROUPLOKASI like '%MARKETPLACE%'";
                            $return = false;
                            
                            //RENCANA PAKAI YANG RETURN_WAREHOUSE, TAPI KETIKA COBA HIT API STOK NYA, MESTI ERROR, AKHIRNYA PAKE YANG INI
                            if($dataAddress[$x]['type'] == "SALES_WAREHOUSE" && $dataAddress[$x]['is_default'] == 1)
                            {
                                $return = true;
                            }
                            
                            if($return)
                            {
                                $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
                                $lokasiReturnTiktok = $dataAddress[$x]['id'];
                            }
                        }
                    }
                  
                  $parameter = [];
                  
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
                  $sql = "select IDPERUSAHAAN, IDBARANGTIKTOK, IDINDUKBARANGTIKTOK, IDBARANG,SKUTIKTOK,HARGAJUAL
                  			from MBARANG
                  			where (1=1) $whereBarang and (IDBARANGTIKTOK is not null and IDBARANGTIKTOK <> 0)
                  			order by IDINDUKBARANGTIKTOK
                  			";	
                   	
                  $dataHeader = $this->db->query($sql)->result();
                   
                  foreach($dataHeader as $itemHeader)
                  {
                       if($itemHeader->IDINDUKBARANGTIKTOK != $idHeader)
                       {
                           if(count($parameter) > 0)
                           {
                              $curl = curl_init();
                              curl_setopt_array($curl, array(
                                CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS =>  array(
                                'endpoint' => '/product/202309/products/'.$idHeader.'/inventory/update',
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
                                  $data['msg'] =  $ret['error']." STOK 1 : ".$ret['message'];
                                  
                                  echo $data['code']." : ".$data['msg'];
                                  // print_r($ret);
                              }
                           }
                           $idHeader = $itemHeader->IDINDUKBARANGTIKTOK;
                           
                           //UPDATE KE TIKTOKNYA
                          $parameter = [];
                       	$parameter['skus'] = [];
                       }
                  	     
                       $result   = get_saldo_stok_new($itemHeader->IDPERUSAHAAN,$itemHeader->IDBARANG, $lokasi, date('Y-m-d'));
                       $saldoQty = $result->QTY??0;
                       
                         if($saldoQty < 0)
                         {
                             $saldoQty = 0;
                         }
                      
                      $idskuvarian = $itemHeader->IDBARANGTIKTOK;
                      
                      if(explode("_",$itemHeader->IDBARANGTIKTOK)[0] == $itemHeader->IDINDUKBARANGTIKTOK)
                      {
                          $idskuvarian = explode("_",$itemHeader->IDBARANGTIKTOK)[1];
                      }
                      
                       array_push($parameter['skus'],array(
                          'id'      => $idskuvarian,
                          'inventory'  => array(
                               array(
                                   'warehouse_id' => $lokasiReturnTiktok,
                                   'quantity' => (int)$saldoQty
                              )
                          ))
                      );
                  }
                  
                  $curl = curl_init();
                  curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>  array(
                    'endpoint' =>  '/product/202309/products/'.$idHeader.'/inventory/update',
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
                      $ret['success'] = false;
                      $ret['msg'] = $ret['message'];
                      echo $ret['code']." : ".$ret['msg'];
                  }
                  else
                  {
                      $parameter = [];
                  }
              }
            }
        }
        
	}
	
	public function setReturBarang(){
	    $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
		$this->output->set_content_type('application/json');
		$nopengembalian = $this->input->post('kodepengembalian')??"";
		$nopesanan = $this->input->post('kodepesanan')??"";
	    
	    $idlokasiset = "1";
        $parameter="";
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/logistics/202309/warehouses','parameter' => $parameter),
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
            $dataAddress = $ret['data']['warehouses'];
            $data['rows'] = [];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI, IFNULL(NAMALOKASI,'') as NAMALOKASI FROM MLOKASI WHERE (IDLOKASITIKTOKPICKUP = ".$dataAddress[$x]['id']." OR  IDLOKASITIKTOKRETUR = ".$dataAddress[$x]['id'].") AND GROUPLOKASI like '%MARKETPLACE%'";
                $return = false;
                
                if($dataAddress[$x]['type'] == "RETURN_WAREHOUSE")
                {
                    $return = true;
                }
                
                if($return)
                {
                    $idlokasiset = $CI->db->query($sql)->row()->IDLOKASI;
                }
            }
        }

        $tglStokMulai = $this->model_master_config->getConfigMarketplace('TIKTOK','TGLSTOKMULAI');
        $sqlRetur = "SELECT KODEPENGEMBALIANMARKETPLACE, TGLPENGEMBALIAN, BARANGSAMPAIMANUAL FROM TPENJUALANMARKETPLACEDTL WHERE MARKETPLACE = 'TIKTOK' and BARANGSAMPAIMANUAL = 0 and KODEPENGEMBALIANMARKETPLACE != '' and KODEPENJUALANMARKETPLACE = '".$nopesanan."'  ORDER BY KODEPENJUALANMARKETPLACE";
        $dataRetur = $CI->db->query($sqlRetur)->result();
		   
        foreach($dataRetur as $itemRetur)
        {
            $CI->db->where("KODEPENGEMBALIANMARKETPLACE", $itemRetur->KODEPENGEMBALIANMARKETPLACE)
              ->set('BARANGSAMPAIMANUAL',1)
    	     ->update('TPENJUALANMARKETPLACEDTL');
    	     
            $this->insertKartuStokRetur($itemRetur->KODEPENGEMBALIANMARKETPLACE,$itemRetur->TGLPENGEMBALIAN,$tglStokMulai,$idlokasiset);
        }
        
        $CI->db->where("KODEPENJUALANMARKETPLACE", $nopesanan)
              ->set('BARANGSAMPAIMANUAL',1)
              ->set('TOTALBARANGPENGEMBALIAN',count($dataRetur))
    	     ->update('TPENJUALANMARKETPLACE');
     
        
        $data['success'] = true;
        $data['msg'] = "Pengembalian Barang Manual Atas Pesanan #".$nopesanan." Berhasil Dilakukan";
        echo(json_encode($data));

	}
	
	public function init($tgl_aw,$tgl_ak,$jenis = 'create_time',$showResponse = true) {
	  
	    $dateAw = new DateTime($tgl_aw." 00:00:00",new DateTimeZone('+00:00'));
        $dateAk = new DateTime($tgl_ak." 23:59:59",new DateTimeZone('+00:00'));
        $parameter = [];
	    if($jenis == "update")
	    {
	        $parameter['update_time_ge'] = $dateAw->getTimestamp();
	        $parameter['update_time_lt'] = $dateAk->getTimestamp();
	    }
	    else
	    {
	        $parameter['create_time_ge'] = $dateAw->getTimestamp();
	        $parameter['create_time_lt'] = $dateAk->getTimestamp();
	    }
	    
		$this->output->set_content_type('application/json');
        $CI =& get_instance();	
        $CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);

        $finalResult = [];
        $finalData = [];
        $history = [];
        $result = [];
        $returnhistory = [];
   
        $count = 0;
        $countTotal = 1;
        $nextPageToken = "";
        $newOrder = 0;
        
        while(count($result) < $countTotal)
        {
            $urlparameter = "&page_size=100&page_token=".$nextPageToken;
            array_push($history,($urlparameter.json_encode($parameter)));
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  array(
              'endpoint' => '/order/202309/orders/search',
              'urlparameter' => $urlparameter,
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
                $finalResult['errorMsg'] =  "1 : ".$ret['code']." : ".$ret['message'];
                $statusok = false;
                $countTotal = 0;
            }
            else
            {
                for($x = 0 ;$x < count($ret['data']['orders']); $x++)
                {
                    array_push($result, $ret['data']['orders'][$x]);
                }
                $nextPageToken = $ret['data']['next_page_token'];
                $countTotal = $ret['data']['total_count'];
            }
        }
        
        $pesananUpdate = "";
        
        for($x = 0  ; $x < count($result); $x++)
        {
            $pesananUpdate .= "'".$result[$x]['id']."',";
        }
        
        $pesananUpdate = substr($pesananUpdate, 0, -1);
        
        if($pesananUpdate != "")
        {
            $sql = "SELECT 1 as ADA , KODEPENJUALANMARKETPLACE, ifnull(KODEPENGEMBALIANMARKETPLACE,'') as KODEPENGEMBALIANMARKETPLACE,CATATANPENJUAL,IDPENJUALANMARKETPLACE as IDTRANS  FROM TPENJUALANMARKETPLACE 
                                    WHERE MARKETPLACE = 'TIKTOK' 
                                    and KODEPENJUALANMARKETPLACE in (".$pesananUpdate.")";
                                
            $queryPesananDB = $CI->db->query($sql)->result();
        }
        
        for($x = 0  ; $x < count($result); $x++)
        {
            
            $ada = 0;
            $kodepengembalian = "";
            $idtrans =  0;
            $catatanJual = "";
            $tglkirim = "-";
            
            foreach($queryPesananDB as $dataPesananDB)
            {
                if($dataPesananDB->KODEPENJUALANMARKETPLACE == $result[$x]['id'])
                {
                    $ada = $dataPesananDB->ADA;
                    $kodepengembalian = $dataPesananDB->KODEPENGEMBALIANMARKETPLACE;
                    $idtrans =  $dataPesananDB->IDTRANS;
                    $catatanJual = $dataPesananDB->CATATANPENJUAL;
                }
            }
            
            $dataDetail = $result[$x]['line_items'];
            $allsku = "";
            for($d = 0 ; $d < count($dataDetail);$d++)
            {
                //KHUSUS SKU YANG KOSONG
                $sku = $dataDetail[$d]['seller_sku'];
                
                if(strpos(strtoupper($dataDetail[$d]['product_name']),"BIRTHDAY CARD") !== false){
                    $sku = "LTWS";
                }
                else if(strpos(strtoupper($dataDetail[$d]['product_name']),"NEWBORN CARD") !== false){
                    $sku = "CARD-BOX-OTHER";
                }
                
                $allsku .= ("1*".$sku."|");
               
            }
            
             $allsku = substr($allsku, 0, -1);
            
            $data;
            
            //DAPATKAN RESI
            $data['ALLBARANG']                      = $dataDetail;
            $data['IDPENJUALAN']                    = 0;
            $data['MARKETPLACE']                    = "TIKTOK";
            $data['IDPENJUALANDARIMARKETPLACE']     = 0;
            $data['KODEPENJUALANMARKETPLACE']       = $result[$x]['id'];
            $data['TGLTRANS']                       = date('Y-m-d H:i:s', $result[$x]['create_time']);
;
            $data['USERNAME']                       = $result[$x]['recipient_address']['name'];
            $data['NAME']                           = $result[$x]['recipient_address']['first_name']." ".$result[$x]['recipient_address']['last_name'];
            $data['TELP']                           = $result[$x]['recipient_address']['phone_number']??"-";
            $data['ALAMAT']                         = $result[$x]['recipient_address']['full_address'];
            $data['KOTA']                           = "";
            //CARI KOTA
            for($c = 0 ; $c < count($result[$x]['recipient_address']['district_info']); $c++)
            {
                if($result[$x]['recipient_address']['district_info'][$c]['address_level_name'] == 'city')
                {
                    $data['KOTA'] = str_replace(" CITY","",str_replace("KAB. ","",str_replace("KOTA ","", strtoupper($result[$x]['recipient_address']['district_info'][$c]['address_name']))));
                }
            }
            $data['SKUPRODUK']                      = $allsku;
            $data['SKUPRODUKOLD']                   = $allsku;
            
            $data['MINTGLKIRIM']                    =  date('Y-m-d H:i:s', $result[$x]['shipping_due_time']);
            $data['KURIR']                          = $result[$x]['shipping_provider']??"";
            $data['KODEPACKAGING']                  = $dataDetail[0]['package_id']??"";
            $data['RESI']                           = $dataDetail[0]['tracking_number']??"";
            
            $data['METODEBAYAR']                    = str_replace("_"," ",$result[$x]['payment_method_name']);
            $data['TOTALBARANG']                    = count($dataDetail);
            $data['TOTALBAYAR']                     = $result[$x]['payment']['total_amount'];
            $data['TOTALHARGA']                     = $result[$x]['payment']['sub_total']; 
            
            
            $data['STATUSMARKETPLACE']              = $result[$x]['status'];
            $data['STATUS']                         = $this->getStatus($result[$x]['status'])['state'];
            $data['CATATANPEMBELI']                 = $result[$x]['buyer_message']??"";
            $data['CATATANPENJUAL']                 = $catatanJual;
            $data['CATATANPENGEMBALIAN']            = ($result[$x]['cancel_reason']??"");
            $data["LASTUPDATED"]                    =  date("Y-m-d H:i:s");
            
            
            if(strtoupper($this->getStatus($result[$x]['status'])['status']) == "DIPROSES")
            {
                $invoice = [];
                $invoice['KODEPESANAN'] = $data['KODEPENJUALANMARKETPLACE'];
                $invoice['KODEPACKAGING'] = $data["KODEPACKAGING"];
                $invoice['KURIR'] = $data["KURIR"];
                $invoice['RESI'] = $data["RESI"];
                    
                if (!file_exists(FCPATH."assets/label/tiktok/waybill_". $invoice['KODEPESANAN']."_compressed.pdf")) {
                  $dataRePrint = $this->reprint([$invoice]);
                }
            }
            
            array_push($finalData,$data);
            
            if($ada)
            {
                $detailBarang = $data['ALLBARANG'];
                
                unset($data['ALLBARANG']);
                
                $CI->db->where("KODEPENJUALANMARKETPLACE",$data['KODEPENJUALANMARKETPLACE'])
                ->where('MARKETPLACE',"TIKTOK")
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
                    'KODEPACKAGING'                     => $data['KODEPACKAGING'],
                    'KURIR'                             => $data['KURIR'],
                    'RESI'                              => $data['RESI'],
                    'METODEBAYAR'                       => $data['METODEBAYAR'],
                    'TOTALBARANG'                       => $data['TOTALBARANG'],
                    'TOTALBAYAR'                        => $data['TOTALBAYAR'],
                    'TOTALHARGA'                        => $data['TOTALHARGA'],
                    'STATUSMARKETPLACE'                 => $data['STATUSMARKETPLACE'],
                    'STATUSPENGEMBALIANMARKETPLACE'     => "",
                    'STATUS'                            => $data['STATUS'],
                    'CATATANPEMBELI'                    => $data['CATATANPEMBELI'],
                    'CATATANPENJUAL'                    => $data['CATATANPENJUAL'],
                    'CATATANPENGEMBALIAN'               => $data['CATATANPENGEMBALIAN'],
                    "LASTUPDATED"                       =>  date("Y-m-d H:i:s")
                ));
               
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
                    $sku = $itemBarang['seller_sku'];
                
                    if(strpos(strtoupper($itemBarang['product_name']),"BIRTHDAY CARD") !== false){
                        $sku = "LTWS";
                    }
                    else if(strpos(strtoupper($itemBarang['product_name']),"NEWBORN CARD") !== false){
                        $sku = "CARD-BOX-OTHER";
                    }
                
                    $sqlBarang = "select idbarang from mbarang where SKUTIKTOK = '$sku'";
                    $queryBarang = $CI->db->query($sqlBarang)->row();
        
                    $CI->db->insertRaw("TPENJUALANMARKETPLACEDTL",
                    array(
                        'IDPENJUALANMARKETPLACE'    => $idtrans,
                        'KODEPENJUALANMARKETPLACE'  => $data['KODEPENJUALANMARKETPLACE'],
                        'IDBARANG'                  => $queryBarang->IDBARANG??0,
                        'MARKETPLACE'               => 'TIKTOK',
                        'SKU'                       => $sku,
                        'URUTAN'                    => $urutan,
                        'JML'                       => 1,
                        'HARGA'                     => $itemBarang['sale_price'],
                        'TOTAL'                     => 1 * $itemBarang['sale_price'],
                    ));
                }
            } 
        }
        
        //CEK LOKASI PICKUP
        $lokasi = "1";
        $parameter="";
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/logistics/202309/warehouses','parameter' => $parameter),
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
            $dataAddress = $ret['data']['warehouses'];
            $data['rows'] = [];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI, IFNULL(NAMALOKASI,'') as NAMALOKASI FROM MLOKASI WHERE (IDLOKASITIKTOKPICKUP = ".$dataAddress[$x]['id']." OR  IDLOKASITIKTOKRETUR = ".$dataAddress[$x]['id'].") AND GROUPLOKASI like '%MARKETPLACE%'";
                $pickup = false;
                
                if($dataAddress[$x]['type'] == "SALES_WAREHOUSE" && $dataAddress[$x]['is_default'] == 1)
                {
                    $pickup = true;
                }
                
                if($pickup)
                {
                    $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
                }
            }
        }
        
	    $tglStokMulai = $this->model_master_config->getConfigMarketplace('TIKTOK','TGLSTOKMULAI');
	    
        for($f = 0 ; $f < count($finalData) ; $f++)
        {
            //INSERT KARTUSTOK
            if($this->getStatus($finalData[$f]['STATUSMARKETPLACE'])['state'] != 1 && $finalData[$f]['STATUSMARKETPLACE'] != "CANCELLED" )
            {
                $this->insertKartuStokPesanan($finalData[$f]['KODEPENJUALANMARKETPLACE'],$finalData[$f]['TGLTRANS'],$tglStokMulai,$lokasi);
            }
        }
        //CEK LOKASI PICKUP
        
        
        //PENDAPATAN PENJUAL
        //UNSETTLED
        $dataUnsettled = [];
        $curl = curl_init();
        $parameter = "&sort_field=order_create_time&page_size=100"; //&search_time_ge=".$dateAw->getTimestamp()."&search_time_lt=". $dateAk->getTimestamp()
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/finance/202507/orders/unsettled','parameter' => $parameter),
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
            $dataUnsettled = $ret['data']['transactions'];
        }
        
        for($x = 0 ; $x < count($finalData) ; $x++)
        {
            $totalPendapatan = 0;
            $ada = false;
            if($this->getStatus($finalData[$x]['STATUSMARKETPLACE'])['state'] != 3 || $finalData[$x]['STATUSMARKETPLACE'] == 'COMPLETED')
            {
                for($u = 0; $u < count($dataUnsettled); $u++)
                {
                    if($dataUnsettled[$u]['order_id'] == $finalData[$x]['KODEPENJUALANMARKETPLACE'])
                    {
                        $totalPendapatan = $dataUnsettled[$u]['est_settlement_amount'];
                        $ada = true;
                    }
                }
            }
            
            if(!$ada && $finalData[$x]['STATUSMARKETPLACE'] == 'COMPLETED')
            {
              $curl = curl_init();
              $parameter = "";
              curl_setopt_array($curl, array(
                 CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => '',
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => 'POST',
                 CURLOPT_POSTFIELDS => array('endpoint' => '/finance/202501/orders/'.$finalData[$x]['KODEPENJUALANMARKETPLACE'].'/statement_transactions','parameter' => $parameter),
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
                  $totalPendapatan = $ret['data']['settlement_amount'];
                  $ada = true;
              }
            }
            else if($finalData[$x]['STATUSMARKETPLACE'] == 'CANCELLED')
            {
                $ada = true;
            }
            
            if($ada)
            {
             $CI->db->where("KODEPENJUALANMARKETPLACE",$finalData[$x]['KODEPENJUALANMARKETPLACE'])
    		              ->where('MARKETPLACE','TIKTOK')
    		              ->updateRaw("TPENJUALANMARKETPLACE", array(
    		                  'TOTALPENDAPATANPENJUAL'   =>  $totalPendapatan,
    		                  "LASTUPDATED"              =>  date("Y-m-d H:i:s")
    		                ));
            }
        }
        
		//RETURN 
		
		
        $count = 0;
        $countTotal = 1;
        $nextPageToken = "";
        
        $parameter = [];
        $resultReturn = [];
	    if($jenis == "update")
	    {
	        $parameter['update_time_ge'] = $dateAw->getTimestamp();
	        $parameter['update_time_lt'] = $dateAk->getTimestamp();
	    }
	    else
	    {
	        $parameter['create_time_ge'] = $dateAw->getTimestamp();
	        $parameter['create_time_lt'] = $dateAk->getTimestamp();
	    }
        
        while(count($resultReturn) < $countTotal)
        {
            $urlparameter = "&page_size=50&page_token=".$nextPageToken;
            array_push($returnhistory,($urlparameter.json_encode($parameter)));
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  array(
              'endpoint' => '/return_refund/202309/returns/search',
              'urlparameter' => $urlparameter,
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
                $finalResult['errorMsg'] =  "5 : ".$ret['code']." : ".$ret['message'];
                $statusok = false;
                $countTotal = 0;
            }
            else
            {
                for($x = 0 ;$x < count($ret['data']['return_orders']); $x++)
                {
                    array_push($resultReturn, $ret['data']['return_orders'][$x]);
                }
                $nextPageToken = $ret['data']['next_page_token'];
                $countTotal = $ret['data']['total_count'];
            }
        }
        
        for($x = 0 ; $x < count($resultReturn); $x++)
        {
            for($d = 0 ; $d < count($resultReturn[$x]['return_line_items']); $d++)
            {
                
                $reverseComplete = true;
                if((($statusRetur != "RETURN_OR_REFUND_REQUEST_REJECT" || $statusRetur != "REFUND_OR_RETURN_REQUEST_REJECT") && $statusRetur == "RETURN_OR_REFUND_REQUEST_CANCEL") || ($statusRetur != "REPLACEMENT_REQUEST_REJECT" && $statusRetur == "REPLACEMENT_REQUEST_CANCEL"))
                {
                    if((($statusRetur != "RETURN_OR_REFUND_REQUEST_COMPLETE" && $reverseComplete) || ($statusRetur != "REPLACEMENT_REQUEST_COMPLETE" || $statusRetur != "REPLACEMENT_REQUEST_REFUND_SUCCESS")) && $reverseComplete)
                    {
                        $reverseComplete = false;
                    }
                }
                else if($reverseComplete)
                {
                    $reverseComplete = false;
                }
                         
                $sku = $resultReturn[$x]['return_line_items'][$d]['seller_sku'];
                //CARI URUTAN
                $sqlUrutan = "SELECT KODEBARANGPENGEMBALIANMARKETPLACE,URUTAN,BARANGSAMPAI FROM TPENJUALANMARKETPLACEDTL 
                               WHERE KODEPENJUALANMARKETPLACE = '".$resultReturn[$x]['order_id']."' 
                               AND MARKETPLACE = 'TIKTOK' 
                               AND SKU = '".$sku."' 
                               ";
                $queryUrutan = $CI->db->query($sqlUrutan)->result();
                $urutan = 0;
                $barangSampai = 0;
                
                //CEK SUDAH PERNAH ADA ATAU BELUM
                foreach($queryUrutan as $itemUrutan)
                {
                    if($itemUrutan->KODEBARANGPENGEMBALIANMARKETPLACE == $resultReturn[$x]['return_line_items'][$d]['return_line_item_id'])
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
                
                $statusRetur = $resultReturn[$x]['return_status'];
                
                //HANYA UNTUK YANG RETUR SAJA
                $CI->db->where("KODEPENJUALANMARKETPLACE",$resultReturn[$x]['order_id'])
                 ->where('SKU',$sku)
                 ->where('URUTAN',$urutan)
            	->where('MARKETPLACE','TIKTOK')
            	->updateRaw("TPENJUALANMARKETPLACEDTL", array(
            	    'KODEPENGEMBALIANMARKETPLACE'       =>  ($statusRetur == "REPLACEMENT_REQUEST_CANCEL" || $statusRetur == "REPLACEMENT_REQUEST_REJECT" || $statusRetur == "REFUND_OR_RETURN_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_CANCEL"?null:$resultReturn[$x]['return_id']),
            	    'KODEBARANGPENGEMBALIANMARKETPLACE' =>  ($statusRetur == "REPLACEMENT_REQUEST_CANCEL" || $statusRetur == "REPLACEMENT_REQUEST_REJECT" || $statusRetur == "REFUND_OR_RETURN_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_CANCEL"?null:$resultReturn[$x]['return_line_items'][$d]['return_line_item_id']),
            	    'SKUPRODUKPENGEMBALIAN'             =>  ($statusRetur == "REPLACEMENT_REQUEST_CANCEL" || $statusRetur == "REPLACEMENT_REQUEST_REJECT" || $statusRetur == "REFUND_OR_RETURN_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_CANCEL"?null:("1*".$sku)),
            	    'STATUSPENGEMBALIANMARKETPLACE'     =>  ($statusRetur == "REPLACEMENT_REQUEST_CANCEL" || $statusRetur == "REPLACEMENT_REQUEST_REJECT" || $statusRetur == "REFUND_OR_RETURN_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_CANCEL"?null:$resultReturn[$x]['return_status']),
            	    'CATATANPENGEMBALIAN'               =>  ($statusRetur == "REPLACEMENT_REQUEST_CANCEL" || $statusRetur == "REPLACEMENT_REQUEST_REJECT" || $statusRetur == "REFUND_OR_RETURN_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_CANCEL"?null:$resultReturn[$x]['return_reason_text']),
            	    'TOTALPENGEMBALIANDANA'             =>  ($statusRetur == "REPLACEMENT_REQUEST_CANCEL" || $statusRetur == "REPLACEMENT_REQUEST_REJECT" || $statusRetur == "REFUND_OR_RETURN_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_CANCEL"?null:$resultReturn[$x]['return_line_items'][$d]['refund_amount']['refund_total']),
            	    'TIPEPENGEMBALIAN'                  =>  ($statusRetur == "REPLACEMENT_REQUEST_CANCEL" || $statusRetur == "REPLACEMENT_REQUEST_REJECT" || $statusRetur == "REFUND_OR_RETURN_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_CANCEL"?null:$resultReturn[$x]['return_type']),
            	    'TGLPENGEMBALIAN'                   =>  ($statusRetur == "REPLACEMENT_REQUEST_CANCEL" || $statusRetur == "REPLACEMENT_REQUEST_REJECT" || $statusRetur == "REFUND_OR_RETURN_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_CANCEL"?null:date("Y-m-d H:i:s", $resultReturn[$x]['create_time'])),
            	    'MINTGLPENGEMBALIAN'                =>  ($statusRetur == "REPLACEMENT_REQUEST_CANCEL" || $statusRetur == "REPLACEMENT_REQUEST_REJECT" || $statusRetur == "REFUND_OR_RETURN_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_CANCEL"?null:date("Y-m-d H:i:s", $resultReturn[$x]['seller_next_action_response'][count($resultReturn[$x]['seller_next_action_response'])-1]['deadline'])),
            	    'STATUS'                            =>  ($statusRetur == "REPLACEMENT_REQUEST_CANCEL" || $statusRetur == "REPLACEMENT_REQUEST_REJECT" || $statusRetur == "REFUND_OR_RETURN_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_CANCEL" || $statusRetur == "RETURN_OR_REFUND_REQUEST_COMPLETE" ?'3':'4'),
            	    'STATUSMARKETPLACE'                 =>  ($statusRetur == "REPLACEMENT_REQUEST_CANCEL" || $statusRetur == "REPLACEMENT_REQUEST_REJECT" || $statusRetur == "REFUND_OR_RETURN_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_CANCEL" || $statusRetur == "RETURN_OR_REFUND_REQUEST_COMPLETE" ?'COMPLETED':'RETURNED'),
            	    'RESIPENGEMBALIAN'                  =>  ($statusRetur == "REPLACEMENT_REQUEST_CANCEL" || $statusRetur == "REPLACEMENT_REQUEST_REJECT" || $statusRetur == "REFUND_OR_RETURN_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_REJECT" || $statusRetur == "RETURN_OR_REFUND_REQUEST_CANCEL")?null: $resultReturn[$x]['return_tracking_number'],
            	    'BARANGSAMPAI'                      =>  (($barangSampai == 1 || (($statusRetur == "REPLACEMENT_REQUEST_COMPLETE" || $statusRetur == "REPLACEMENT_REQUEST_REFUND_SUCCESS") &&  $resultReturn[$x]['return_type'] == 'REPLACEMENT') || ($statusRetur == "RETURN_OR_REFUND_REQUEST_COMPLETE" &&  $resultReturn[$x]['return_type'] == 'RETURN_AND_REFUND'))? 1 : 0)
            	  ));
            
            
                //JIKA ADA KURANG RETUR NYA, INPUT STOK LAGI
                if(!$reverseComplete){
                    $CI->db->where('KODETRANS',$resultReturn[$x]['return_id'])
                         ->where('JENISTRANS','RETUR JUAL TIKTOK')
                         ->delete('KARTUSTOK');
                }
            }
        }
        
        
         //CEK LOKASI RETURN, YANG BARANG SMPAI = 1
        $lokasi = "1";
        $parameter="";
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/logistics/202309/warehouses','parameter' => $parameter),
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
            $dataAddress = $ret['data']['warehouses'];
            $data['rows'] = [];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI, IFNULL(NAMALOKASI,'') as NAMALOKASI FROM MLOKASI WHERE (IDLOKASITIKTOKPICKUP = ".$dataAddress[$x]['id']." OR  IDLOKASITIKTOKRETUR = ".$dataAddress[$x]['id'].") AND GROUPLOKASI like '%MARKETPLACE%'";
                $return = false;
                
                if($dataAddress[$x]['type'] == "RETURN_WAREHOUSE")
                {
                    $return = true;
                }
                
                if($return)
                {
                    $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
                }
            }
        }
        
        
	    $tglStokMulai = $this->model_master_config->getConfigMarketplace('TIKTOK','TGLSTOKMULAI');
	    
	    if(count($finalData) > 0)
	    {
    	    $wherePesanan = "AND a.KODEPENJUALANMARKETPLACE in (";
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
        
        $sqlRetur = "SELECT a.KODEPENGEMBALIANMARKETPLACE, a.TGLPENGEMBALIAN FROM TPENJUALANMARKETPLACEDTL a WHERE a.MARKETPLACE = 'TIKTOK' and (a.BARANGSAMPAI = 1 OR a.BARANGSAMPAIMANUAL = 1) and a.KODEPENGEMBALIANMARKETPLACE != ''  $wherePesanan  ORDER BY a.KODEPENJUALANMARKETPLACE";
        $dataRetur = $CI->db->query($sqlRetur)->result();

        foreach($dataRetur as $itemRetur)
        {
          $this->insertKartuStokRetur($itemRetur->KODEPENGEMBALIANMARKETPLACE,$itemRetur->TGLPENGEMBALIAN,$tglStokMulai,$lokasi);
        }
        
         //DELETE KARTUSTOK YANG STATUSNYA CANCELLED
        $sqlDeleteStok = "
            DELETE ks
            FROM KARTUSTOK ks
            JOIN TPENJUALANMARKETPLACE tp
              ON tp.KODEPENJUALANMARKETPLACE = ks.KODETRANS
            WHERE tp.MARKETPLACE = 'TIKTOK'
              AND tp.STATUSMARKETPLACE = 'CANCELLED';
        ";
        
        $CI->db->query($sqlDeleteStok);
        
        //TOTAL RETUR HEADER
        $sqlReturHeader = "SELECT a.KODEPENJUALANMARKETPLACE, 
                            IFNULL( GROUP_CONCAT( NULLIF(a.KODEPENGEMBALIANMARKETPLACE, '') SEPARATOR ', ' ), '' ) AS KODEPENGEMBALIANMARKETPLACE, 
                            GROUP_CONCAT( IF(a.BARANGSAMPAI = 1, a.SKUPRODUKPENGEMBALIAN, NULL) SEPARATOR '|' ) AS SKUPRODUKPENGEMBALIAN, 
                            SUM(a.TOTALPENGEMBALIANDANA) AS TOTALPENGEMBALIANDANA, SUM(IF(a.STATUS = 4,1,0)) AS STATUS, 
                            SUM(IF(a.BARANGSAMPAI = 1,1,0)) AS BARANGSAMPAI, SUM(IF(a.BARANGSAMPAIMANUAL = 1,1,0)) AS BARANGSAMPAIMANUAL,
                            b.STATUSMARKETPLACE AS STATUSMARKETPLACEHEADER ,b.STATUS AS STATUSHEADER
                            FROM TPENJUALANMARKETPLACEDTL a
                            INNER JOIN TPENJUALANMARKETPLACE b ON a.IDPENJUALANMARKETPLACE = b.IDPENJUALANMARKETPLACE
                                WHERE a.MARKETPLACE = 'TIKTOK'   $wherePesanan  
                                AND b.STATUSMARKETPLACE != 'CANCELLED'
                                AND b.STATUS > 2
                                GROUP BY a.KODEPENJUALANMARKETPLACE 
                                ORDER BY a.KODEPENJUALANMARKETPLACE";
        $dataReturHeader = $CI->db->query($sqlReturHeader)->result();
		   
        foreach($dataReturHeader as $itemReturHeader)
        {
          
          $CI->db->where("KODEPENJUALANMARKETPLACE",$itemReturHeader->KODEPENJUALANMARKETPLACE)
		 ->where('MARKETPLACE','TIKTOK')
		 ->updateRaw("TPENJUALANMARKETPLACE", array(
		     'KODEPENGEMBALIANMARKETPLACE'   =>  $itemReturHeader->KODEPENGEMBALIANMARKETPLACE,
		     'SKUPRODUKPENGEMBALIAN'         =>  $itemReturHeader->SKUPRODUKPENGEMBALIAN, 
		     'TOTALBARANGPENGEMBALIAN'       =>  ($itemReturHeader->BARANGSAMPAIMANUAL > $itemReturHeader->BARANGSAMPAI ? $itemReturHeader->BARANGSAMPAIMANUAL : $itemReturHeader->BARANGSAMPAI), 
		     'TOTALPENGEMBALIANDANA'         =>  $itemReturHeader->TOTALPENGEMBALIANDANA, 
		     'STATUSMARKETPLACE'             =>  ($itemReturHeader->STATUS > 0 ? 'RETURNED' : 'COMPLETED'),  
		     'STATUS'                        =>  ($itemReturHeader->STATUS > 0 ? '4' : '3'),
		     "LASTUPDATED"                   =>  date("Y-m-d H:i:s"),
		     'BARANGSAMPAI'                  =>  ($itemReturHeader->BARANGSAMPAI > 0 ? 1 : 0), 
		     'BARANGSAMPAIMANUAL'           =>  ($itemReturHeader->BARANGSAMPAIMANUAL > 0 ? 1 : 0), 
		   ));
        }
        //CEK LOKASI RETURN
        
        
        //SET STOK
        $dataBarang = [];
        $lokasi = "1";
        $lokasiPickupTiktok = "1";
        $parameter="";
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/getAPI/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('endpoint' => '/logistics/202309/warehouses','parameter' => $parameter),
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
            $dataAddress = $ret['data']['warehouses'];
            $data['rows'] = [];
            for($x = 0 ; $x < count($dataAddress);$x++)
            {
                $sql = "SELECT IFNULL(IDLOKASI,0) as IDLOKASI, IFNULL(NAMALOKASI,'') as NAMALOKASI FROM MLOKASI WHERE (IDLOKASITIKTOKPICKUP = ".$dataAddress[$x]['id']." OR  IDLOKASITIKTOKRETUR = ".$dataAddress[$x]['id'].") AND GROUPLOKASI like '%MARKETPLACE%'";
                $pickup = false;
                
                if($dataAddress[$x]['type'] == "SALES_WAREHOUSE" && $dataAddress[$x]['is_default'] == 1)
                {
                    $pickup = true;
                }
                
                if($pickup)
                {
                    $lokasi = $CI->db->query($sql)->row()->IDLOKASI;
                    $lokasiPickupTiktok = $dataAddress[$x]['id'];
                }
            }
                
            $sql = "select IDPERUSAHAAN, IDBARANGTIKTOK, IDINDUKBARANGTIKTOK, IDBARANG
            				from MBARANG
            				WHERE idindukbarangtiktok is not null AND
            				idindukbarangtiktok <> '' AND
            				idindukbarangtiktok <> 0
            				order by idindukbarangtiktok
            				";	
            		
            $dataHeader = $this->db->query($sql)->result();
            		
            $idHeader = 0;
            $parameter = [];
            foreach($dataHeader as $itemHeader)
            {
                 if($itemHeader->IDINDUKBARANGTIKTOK != $idHeader)
                 {
                     if(count($parameter) > 0)
                     {
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS =>  array(
                          'endpoint' => '/product/202309/products/'.$idHeader.'/inventory/update',
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
                            $finalResult['errorMsg'] =  "8 : ".$ret['code']." : ".$ret['message'];
                        }
                     }
                     $idHeader = $itemHeader->IDINDUKBARANGTIKTOK;
                     
                     //UPDATE KE TIKTOKNYA
                    $parameter = [];
                 	$parameter['skus'] = [];
                 }
            	     
                 $result   = get_saldo_stok_new($itemHeader->IDPERUSAHAAN,$itemHeader->IDBARANG, $lokasi, date('Y-m-d'));
                 $saldoQty = $result->QTY??0;
                   if($saldoQty < 0)
                     {
                         $saldoQty = 0;
                     }
                
                $idskuvarian = $itemHeader->IDBARANGTIKTOK;
                
                if(explode("_",$itemHeader->IDBARANGTIKTOK)[0] == $itemHeader->IDINDUKBARANGTIKTOK)
                {
                    $idskuvarian = explode("_",$itemHeader->IDBARANGTIKTOK)[1];
                }
                
                 array_push($parameter['skus'],array(
                    'id'      => $idskuvarian,
                    'inventory'  => array(
                         array(
                             'warehouse_id' => $lokasiPickupTiktok,
                             'quantity' => (int)$saldoQty
                        )
                    ))
                );
            }
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->config->item('base_url')."/Tiktok/postAPI/",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  array(
              'endpoint' =>  '/product/202309/products/'.$idHeader.'/inventory/update',
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
                $finalResult['errorMsg'] =  "9 : ".$ret['code']." : ".$ret['message'];
            }
        }
        //SET STOK
        	
        
        $finalResult["history"] = $history;
        $finalResult["return_history"] = $returnhistory;
		$finalResult["total"] = $newOrder;
		
		if($showResponse)
		{
		    echo json_encode($finalResult); 
		}
	}
	
}
