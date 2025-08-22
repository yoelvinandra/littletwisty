<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Init extends MY_Controller {
	
	public function index()
	{
	    
	     $curl = curl_init();
        //GET INIT
        
	    curl_setopt_array($curl, array(
          CURLOPT_URL => $this->config->item('base_url')."/Shopee/init/".TGLAWALFILTERMARKETPLACE."/".date("Y-m-d"),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
         
        $response = curl_exec($curl);
        curl_close($curl);
        print_r($response);
	}
	
}
