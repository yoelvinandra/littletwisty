<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fingerprint extends MY_controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->model(array("model_fingerprint","model_master_perusahaan","model_master_user","model_master_menu"));
		$time_limit_reg = "15";
		$time_limit_ver = "10";
    }
	
	function cekLogin(){
		$user 	= $this->input->post('txt_user');
		$iduser = $this->input->post('txt_iduser');
		$perusahaan = $this->input->post('txt_kodeperusahaan');
        if ($user=='') die(json_encode(array('success'=>false,'errorMsg' => 'Anda Belum Mengisi User !')));
		
		$r = $this->model_master_user->getUser($user);
		if ($r=='') {
            return die(json_encode(array('errorMsg' => 'Invalid Username!')));
        }
		
		//pengecekan apakah perusahaan tersebut valid
        $r = $this->model_master_perusahaan->getPerusahaan($perusahaan);

        if ($r=='') {
            die(json_encode(array('success'=>false,'errorMsg' => 'Anda Belum Mengisi Lokasi !')));
        }
		//pengecekan terdaftar pada perusahaan
		$r = $this->model_master_user->cekPerusahaan($iduser,$perusahaan);
		if ($r == '') {
			return die(json_encode(array('errorMsg' => 'Tidak terdaftar pada perusahaan !')));
		}
		
		$url_verification = base64_encode(base_url()."Fingerprint/verify?user_id=".$iduser."&id=".$perusahaan);
		$json['link'] = "finspot:FingerspotVer;$url_verification";
		$json['success'] = true;
		echo json_encode($json);
	}
	
	function getAC(){
		if (isset($_GET['vc']) && !empty($_GET['vc'])) {
			$data = $this->model_fingerprint->getDeviceAcSn($_GET['vc']);
			echo $data[0]['AC'].$data[0]['SN'];
		}
	}
	
	function verify(){	
		if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
			$user_id 	= $_GET['user_id'];
			$id 		= $_GET['id'];
			$finger		= $this->model_master_user->getUserFinger($user_id);
			
			echo "$user_id;".$finger.";SecurityKey;".$time_limit_ver.";".base_url()."Fingerprint/processVerification;".base_url()."Fingerprint/getAC".";".$id;
		}	
	}
		
	function processVerification(){
		if (isset($_POST['VerPas']) && !empty($_POST['VerPas'])) {
			$data 		= explode(";",$_POST['VerPas']);
			$user_id	= $data[0];
			$vStamp 	= $data[1];
			$time 		= $data[2];
			$sn 		= $data[3];
			$id			= $data[4];
			
			$fingerData = $this->model_master_user->getUserFinger($user_id);
			$device 	= $this->model_fingerprint->getDeviceBySn($sn);
			$salt = md5($sn.$fingerData.$device[0]['VC'].$time.$user_id.$device[0]['VKEY']);
			
			if (strtoupper($vStamp) == strtoupper($salt)) {
				//sukses
				//$this->login($user_id, $id, $this->session->userdata($id));=
				
				echo base_url()."fingerprint/success?userid=".$user_id."&id=".$id;
			} else {
				$msg = $_POST['VerPas'];
				echo base_url()."messages.php?msg=$msg";
			}
		}
	}	
	
	function success(){
		$user = $this->input->get('userid');
		//dapatkan session,jika login id=idperusahaan
		$id = $this->input->get('id');
		$data = $_SESSION[$id];
		$data['user'] = $_SESSION[NAMAPROGRAM]['user'];
		$data['config'] = $_SESSION[NAMAPROGRAM]['CONFIG'];
		if($data['mode']=="tambah" || $data['mode']=="approve"){
			$response = json_decode(postCURL(base_url().$data['LINK'],$data));
			if($response->errorMsg != ''){
				$msg = $id.";".$response->errorMsg;
			}
			else{
				$msg = $id.";".$data['act'];
			}
			$this->load->view('FingerprintSuccess',array("msg"=>$msg));
		}
		else{
			login($user,$perusahaan);
			redirect("home");
		}
	}
	
	function verifySimpan(){		
		//mengambil setiap data form
		$data = $this->input->post(NULL, TRUE);
		$id = 'verify'.date("YmdHis").rand(1000,9999);
		$_SESSION[$id] = $data;
		$iduser = $this->input->post('VERIFYUSER');
		$url_verification = base64_encode(base_url()."Fingerprint/verify?user_id=".$iduser."&id=".$id);
		$json['link'] = "finspot:FingerspotVer;$url_verification";
		$json['success'] = true;
		$json['idTrans'] = $id;
		
		echo json_encode($json);
	}
	
}
