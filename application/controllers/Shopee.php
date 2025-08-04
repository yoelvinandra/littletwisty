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
	            Caranya : akses https://".$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']."/getAuth?
	         <br><br>
	         3. Lanjutkan Proses hingga di url browser, muncul Code dan Shop ID masukkan data-data ini ke database
	         <br><br>
	         4. Dapatkan akses token untuk Akses API<br>
	            Caranya : akses https://".$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']."/getToken?
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
            
            $this->model_master_config->setConfigMarketplace('SHOPEE','ACCESS_TOKEN',$accessToken);
            $this->model_master_config->setConfigMarketplace('SHOPEE','REFRESH_TOKEN',$newRefreshToken);
            
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
           echo $ret['error']." : ".$ret['message'];
        }
        else
        {
            $accessToken = $ret["access_token"];
            $newRefreshToken = $ret["refresh_token"];
            
            $this->model_master_config->setConfigMarketplace('SHOPEE','ACCESS_TOKEN',$accessToken);
            $this->model_master_config->setConfigMarketplace('SHOPEE','REFRESH_TOKEN',$newRefreshToken);
            
            echo "Access Token : ".($accessToken)."<br>Refresh Token : ".($newRefreshToken)."<br><br>Berhasil disimpan di database";
        }

	}
	
	public function getAPI()
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
          CURLOPT_TIMEOUT => 0,
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
	
	public function postAPI()
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
          CURLOPT_POSTFIELDS => $parameter,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_HTTPHEADER => array(
            'Cookie: SPC_SEC_SI=v1-UHAwR1pMemVVcnVNWk0yOAmAUDORs28JVn6OsCvGiwjQgIgi70oQOziTBqSXWyDZViEAeIWx3hwddNsCKaZ6iPdS4KmbbymAcw3YAhkMr6I=; SPC_SI=P7sdaAAAAABzUmtoeEtWN8sBwggAAAAATEVRUWR2b0Y='
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response; 
     
	}
	
	public function dataGrid() {
		$filter = $this->setFilterGrid();
		$temp_tgl_aw = TGLAWALFILTER;
		$tgl_aw = $this->input->post('tglawal') =='' ? "and a.TGLTRANS>='$temp_tgl_aw'" : "and a.TGLTRANS>='".ubah_tgl_firebird($this->input->post('tglawal'))."'";
		$tgl_ak = $this->input->post('tglakhir')=='' ? "and a.TGLTRANS<='$temp_tgl_ak'" : "and a.TGLTRANS<='".ubah_tgl_firebird($this->input->post('tglakhir'))."'";
		$status = explode(",",$this->input->post('status'));
		$status = count($status)>0 ? "and (a.STATUS='".implode("' or a.STATUS='", $status)."')" : '';
		$filter['sql'] .= $tgl_aw;
		$filter['sql'] .= $tgl_ak;
		$filter['sql'] .= $status;
		
		$this->output->set_content_type('application/json');
        $state = $this->input->post('state');
        $response = [];
        $response[0]->STATUS       = "TEST".$state;
        $response[0]->KODEPESANAN  = "TEST".$state;
        $response[0]->TGLPESANAN   = "TEST".$state;
        $response[0]->BARANG       = "TEST".$state;
        $response[0]->TOTALBARANG  = "TEST".$state;
        $response[0]->GRANDTOTAL   = "TEST".$state;
        $response[0]->ALAMAT       = "TEST".$state;
        $response[0]->JEMPUT       = "TEST".$state;
        $response[0]->KURIR        = "TEST".$state;
        $response[0]->RESI         = "TEST".$state;
        $response[0]->CATATANBELI  = "TEST".$state;
        $response[0]->CATATANJUAL  = "TEST".$state;
        
        $data["rows"]  = $response;
		$data["total"] = count($response);
		
		echo json_encode($data); 
	}

	public function loadDetail(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id')??"";
		$idcustomer = $this->input->post('idcustomer')??"";
		$kode = $this->input->post('kode')??"";
		$mode = $this->input->post('mode');
		
	    $response;

		echo json_encode($response);
	}
	
}
