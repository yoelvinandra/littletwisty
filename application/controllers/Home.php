<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {
	
	public function index($jenisLaporan = "transaksi")
	{
	    if (isset($_SESSION[NAMAPROGRAM]['user']) && $jenisLaporan == "dashboard") {
	        //$text['label'] = saveStokCLS(date("Y-m-d"));
	        $this->load->view('header',$text);
			$this->load->view('v_dashboard');
			$this->load->view('footer');
	    }
		else if (isset($_SESSION[NAMAPROGRAM]['user']) && $jenisLaporan == "transaksi") {
		    //$text['label'] = saveStokCLS(date("Y-m-d"));
		  
			$this->load->view('header',$text);
			$aMenu = isset($_SESSION[NAMAPROGRAM]['array_menu']) ? json_decode($_SESSION[NAMAPROGRAM]['array_menu']) : array();
		    if($aMenu[0]->kodemenu == "D0001")
		    { 
			    $this->load->view('v_dashboard');
		    }
		    else
		    {
 			    $this->load->view('v_menuawal');
		    }
			$this->load->view('footer');
		} 
		else if(isset($_SESSION[NAMAPROGRAM]['user']) && $jenisLaporan == "laporan"){
		    //$text['label'] = saveStokCLS(date("Y-m-d"));
			$this->load->view('header',$text);
			$this->load->view('v_laporan');
			$this->load->view('footer');
		}
		else if($jenisLaporan == "shopee")
		{
		    //SANDBOX
            $partnerKey = "424b775146574f704f5970506c496c5647767971594969556d745a756c634a49";
            $partnerId = "1279536";
            $host = "https://partner.test-stable.shopeemobile.com";
            $path = "/api/v2/shop/auth_partner";
            $redirectUrl = "https://littletwisty.id/";
        
            $timest = time();
            $baseString = sprintf("%s%s%s", $partnerId, $path, $timest);
            $sign = hash_hmac('sha256', $baseString, $partnerKey);
            $url = sprintf("%s%s?partner_id=%s&time[SAMBUNG]stamp=%s&sign=%s&redirect=%s", $host, $path, $partnerId, $timest, $sign, $redirectUrl);
            echo  "SANDBOX : ".($url);
            
            echo "<br><br>";
            
            //LIVE
            $partnerKey = "624f42674f736c46776859664a6a417652736f4c5273684971666e4677566c56";
            $partnerId = "2011194";
            $host = "https://partner.shopeemobile.com";
            $path = "/api/v2/shop/auth_partner";
            $redirectUrl = "https://littletwisty.id/";
        
            $timest = time();
            $baseString = sprintf("%s%s%s", $partnerId, $path, $timest);
            $sign = hash_hmac('sha256', $baseString, $partnerKey);
            $url = sprintf("%s%s?partner_id=%s&time[SAMBUNG]stamp=%s&sign=%s&redirect=%s", $host, $path, $partnerId, $timest, $sign, $redirectUrl);
            echo "LIVE : ".($url);
            
             echo "<br><br>";
    
        $code = "6a6765514a485979736570466c6c6466";
        $shop_id = "224166268";
        $partner_id = "2011194";
        $partner_key = "624f42674f736c46776859664a6a417652736f4c5273684971666e4677566c56";
        
        $host = 'https://partner.shopeemobile.com';
        $path = "/api/v2/auth/token/get";
        
        $timest = time();
        $body = array("code" => $code,  "shop_id" => (int)$shop_id, "partner_id" => (int)$partner_id);
        $baseString = sprintf("%s%s%s", $partner_id, $path, $timest);
        $sign = hash_hmac('sha256', $baseString, $partner_key);
        $url = sprintf("%s%s?partner_id=%s&timestamp=%s&sign=%s", $host, $path, $partner_id, $timest, $sign);
        
        echo $url."<br><br>";
    
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($c);
        echo "raw result: $resp";
    
        $ret = json_decode($resp, true);
        $accessToken = $ret["access_token"];
        $newRefreshToken = $ret["refresh_token"];
        echo "\naccess_token: $accessToken, refresh_token: $newRefreshToken raw: $ret"."\n";
     
        echo "<br><br>";
        
        $access_token = "7a477a535964664d5961724555474350";
        $api_path = "/api/v2/product/get_category";
        $timest = time();
        // $baseString = sprintf("%s%s%s%s%s%s", $partner_id, $path, $timest,$accesstoken,$shop_id);
        $sign = hash_hmac('sha256', $partner_id.$api_path.$timest.$access_token.$shop_id,$partner_key);
        echo "TIME : ".$timest."<br>";
        echo "SIGN : ".$sign;
        


		}
		else {
			redirect('auth/Login');
		}
	}
	
}
