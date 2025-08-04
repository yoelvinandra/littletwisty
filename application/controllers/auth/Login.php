<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->model(array("model_master_menu","model_master_user"));
	}

	public function index()
	{
		$this->load->view('auth/login');
	}
	
    //untuk proses login
    function cekLogin(){
		$user 	= $this->input->post('username');
		$pass 	= encrypt_data($this->input->post('password'));
		
        if ($user=='') {
			return die(json_encode(array('message' => 'Username Tidak Boleh Kosong')));	
		}
        if ($this->input->post('password')=='') {
			return die(json_encode(array('message' => 'Password Tidak Boleh Kosong')));	
		}
				
		//untuk debugging
		if ($user == 'vision' && $pass === encrypt_data('corejava')) {
			// panggil function di helper
			login($user);
            return die(json_encode(array('success' => 'Selamat Datang, '.$_SESSION[NAMAPROGRAM]['USERNAME'])));
		}
		
		$r = $this->model_master_user->getUserID($user);
		if ($r=='') {
            return die(json_encode(array('message' => 'Username Salah!')));
        }
		
		$r = $this->model_master_user->getStatusAktif($user);
		if ($r=='') {
            return die(json_encode(array('message' => 'User sudah di Non Aktifkan')));
        }
		
		$passwordAsli = $r->PASS;
		$userid       = $r->USERID;
		$iduser       = $r->IDUSER;
		
		if ($passwordAsli === strtoupper($pass)) {
			// panggil function di helper
            login($iduser);
            return die(json_encode(array('success' => 'Selamat Datang, '.$_SESSION[NAMAPROGRAM]['USERNAME'])));
			// redirect('Home');	
		} else {
			return die(json_encode(array('message' => 'Password Salah !')));
			session_destroy();
		}
    }
	
    function logout(){
        session_start();
		$r = $this->model_master_user->updateLogin($_SESSION[NAMAPROGRAM]['IDUSER'],0);

		unset($_SESSION[NAMAPROGRAM]);
		redirect(base_url());
		//delete_cookie('c_u');
		//delete_cookie('c_p');
		//delete_cookie('c_l');
    }
}
