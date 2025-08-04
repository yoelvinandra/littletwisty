<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
	public function __construct(){
		parent::__construct();
	  $this->load->model(array("model_master_menu","model_master_perusahaan","model_master_user"));
	}
	
	public function index(){
		$kodeMenu = $this->input->get('kode');
		// panggil set page di MY_Controller
		$this->setPage($kodeMenu);
	}
	
	public function comboGrid() {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_user->comboGrid($this->setPaginationGrid());
		
		echo json_encode($response);
	}
	
	public function comboGridVerify($kodemenu='') {
		$this->output->set_content_type('application/json');
		$response = $this->model_master_user->comboGridVerify($this->setPaginationGrid(),$kodemenu);
		
		echo json_encode($response);
	}

	public function dataGrid() {
		$this->output->set_content_type('application/json');

		$response = $this->model_master_user->dataGrid($this->setPaginationGrid(), $this->setFilterGrid());
		
		echo json_encode($response);
	}

	public function treeGrid() {
		$this->output->set_content_type('application/json');

		$kode 			= $this->input->post('iduser');
		$jenismenu 		= $this->input->post('jenismenu');
		$akses 			= $this->input->post('akses'); // back : front

		$response = $this->model_master_user->treeGrid(['akses' => $akses, 'user' => $kode, 'jenismenu' => $jenismenu]);
		
		echo json_encode($response);
	}
	
	function getUser(){
		$name = $this->input->post('user','');
		$data['query'] = $this->model_master_user->getUser($name);
		if(!$data['query']) 
			die(json_encode(array('success' => false,'errorMsg' => 'User tidak ditemukan!')));
	
		$data['success'] = true;
		echo json_encode($data);
	}
	
	function getUserAuthentication(){
		$name = $this->input->post('user','');
		if($name == "vision"){
			$data['query']['IDUSER'] = "vision";
			$data['query']['AUTHENTICATION'] = 1;
			$data['query']['PRIORITY'] = 1;
			$data['success'] = true;
		}else{
			$query = $this->model_master_user->getUserID(strtoupper($name));
			if(!$query) 
				die(json_encode(array('success' => false,'errorMsg' => 'User tidak ditemukan!')));
			
			$data['query']['IDUSER'] = $query->IDUSER;
			$data['query']['AUTHENTICATION'] = $query->AUTHENTICATION;
			$data['query']['PRIORITY'] = $query->PRIORITY;
			$data['success'] = true;
		}
		echo json_encode($data);
	}
	
    //untuk mengecek hak akses diri sendiri
    function getUserAkses(){
        if ($_SESSION[NAMAPROGRAM]['USERID'] == 'VISION') {
			$data['TAMBAH'] = $data['UBAH'] = $data['HAPUS'] = 
			$data['OTORISASI'] = $data['TAMPILGRANDTOTAL'] = 
			$data['CETAK'] = $data['BATALCETAK'] = $data['BLOKIR'] = 1;
            echo json_encode((array('success' => true, 'data' => $data)));
			exit;
        }
        

		$r = array();
        $r = $this->model_master_user->getAkses($this->input->post('kodemenu'),$this->input->post('iduser'));
		if($r){
			$r->OTORISASI = $_SESSION[NAMAPROGRAM]['OTORISASI'];
			$r->TAMPILGRANDTOTAL = $_SESSION[NAMAPROGRAM]['TAMPILGRANDTOTAL'];
			$r->PRINTULANG = $_SESSION[NAMAPROGRAM]['PRINTULANG'];
		} else {
			$r = array('TAMBAH' => 0, 'UBAH' => 0, 'HAPUS' => 0, 'OTORISASI' => 0, 'TAMPILGRANDTOTAL' => 0, 'PRINTULANG' => 0, 'BLOKIR' => 0, 'a' => $_SESSION[NAMAPROGRAM]['USERID']);
		}
		$json['success'] = true;
		$json['data'] = $r;
        
        echo json_encode($json);
	}
	
	function getUserAksesAuth() {
		$kodemenu = $this->input->post('kodemenu');
		$user	  = $this->input->post('txt_user');
		$pass 	  = $this->input->post('txt_pass');

		if (strtolower($user) == 'vision') {
			echo json_encode((array('success' => true, 'data' => array('TAMBAH' => 1, 'UBAH' => 1, 'HAPUS' => 1, 'OTORISASI' => 1, 'TAMPILGRANDTOTAL' => 1, 'PRINTULANG' => 1, 'BLOKIR' => 1,))));
			exit;
		}

		$r = $this->model_master_user->getUserID(strtoupper($user));
		if ($r=='') {
            return die(json_encode(array('errorMsg' => 'Invalid Username!')));
		}
		
		$passwordAsli = $r->PASS;
		$userid       = $r->USERID;
		$iduser       = $r->IDUSER;

		$r = $this->model_master_user->cekPerusahaan($iduser,$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN']);
		if ($r == '') {
			return die(json_encode(array('errorMsg' => 'User Tidak ada Dalam Perusahaan ini !')));
		}
		
		if ($passwordAsli === strtoupper(encrypt_data($pass))) {
			// get menuakses
			$r = $this->model_master_user->getAkses($kodemenu, $iduser);
			if($r){
				$r->OTORISASI = $_SESSION[NAMAPROGRAM]['OTORISASI'];
				$r->TAMPILGRANDTOTAL = $_SESSION[NAMAPROGRAM]['TAMPILGRANDTOTAL'];
				$r->PRINTULANG = $_SESSION[NAMAPROGRAM]['PRINTULANG'];
			} else {
				$r = array('TAMBAH' => 0, 'UBAH' => 0, 'HAPUS' => 0, 'OTORISASI' => 0, 'TAMPILGRANDTOTAL' => 0, 'PRINTULANG' => 0, 'BLOKIR' => 0, 'a' => $_SESSION[NAMAPROGRAM]['USERID']);
			}
			$json['success'] = true;
			$json['data'] = $r;
			echo json_encode($json);
		} else {
			echo json_encode(array('errorMsg' => 'Invalid Password !'));
		}
	}

	function simpan_profile() {
		$id	 			 = $this->input->post('IDUSER_PROFILE');
		$idperusahaan	 = $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'];
		$gambar = $_POST['GAMBAR'];
		$gambar_lama = $this->input->post('GAMBARUSER_PROFILE');
	
		$data_values = array (
			'USERNAME'	=> $this->input->post('USERNAME_PROFILE'),
			'EMAIL'	=> $this->input->post('EMAIL_PROFILE'),
			'HP'	=> $this->input->post('NOHP_PROFILE'),
			'TGLENTRY'	=> date("Y-m-d"),
		);
		
		$_SESSION[NAMAPROGRAM]['USERNAME'] = $this->input->post('USERNAME_PROFILE');
		$_SESSION[NAMAPROGRAM]['EMAIL_USER'] = $this->input->post('EMAIL_PROFILE');
		$_SESSION[NAMAPROGRAM]['HP_USER']= $this->input->post('NOHP_PROFILE');
							
							
		$row = $this->model_master_user->getUserByID($id);

		if (($row->PASS == strtoupper(encrypt_data($this->input->post('OLD_PASS_PROFILE'))))) {
			
				// upload file
				//define('UPLOAD_DIR', base_url().'assets/foto-jaminan/');
				if ($_FILES["FILEGAMBAR_PROFILE"]['name'] != '') {
					// upload gambar
					$target_dir = "./assets/foto_user/";
					$uploadOk = 1;
					$imageFileType = pathinfo($_FILES['FILEGAMBAR_PROFILE']['name'], PATHINFO_EXTENSION);
					$target_file = $target_dir . str_replace('/', '.', $row->USERID) . '.' . $imageFileType;
					$gambar = str_replace('/', '.',  $row->USERID) . '.' . $imageFileType;
					// Check if image file is a actual image or fake image

					$check = getimagesize($_FILES["FILEGAMBAR_PROFILE"]["tmp_name"]);
					if ($check !== false) {
						$uploadOk = 1;
					} else {
						//cek max
						die(json_encode(array('errorMsg' => 'File Yang Diupload Tidak Valid')));
						$uploadOk = 0;
					}

					if ($_POST['GAMBAR_PROFILE'] != '' && $_POST['GAMBAR_PROFILE'] != 'no_image.png' ) {
						unlink($target_dir.$_POST['GAMBAR_PROFILE']);
					}
					else if(substr($gambar,0, -4) == substr($gambar_lama,0, -4)  )
					{
						unlink($target_dir.$gambar_lama);
					}

					// Check if file already exists
					if (file_exists($target_file)) {
						//$uploadOk = 0;
					}
					// Check file size
					if ($_FILES["FILEGAMBAR_PROFILE"]["size"] > 5000000) {
						die(json_encode(array('errorMsg' => 'Sorry, your file is too large.')));
						$uploadOk = 0;
					}
					// Allow certain file formats
					if ( ! in_array(strtolower($imageFileType), array('jpg', 'png', 'jpeg', 'gif')) ) {
						die(json_encode(array('errorMsg' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.')));
						$uploadOk = 0;
					}
					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0) {
						die(json_encode(array('errorMsg' => 'Sorry, your file was not uploaded.')));
					// if everything is ok, try to upload file
					} else {
						if (move_uploaded_file($_FILES["FILEGAMBAR_PROFILE"]["tmp_name"], $target_file)) {
							//echo "The file ". basename($_FILES["GAMBAR"]["name"]). " has been uploaded.";
							$data_values['GAMBAR'] = $gambar;

							$_SESSION[NAMAPROGRAM]['FOTO_USER']= $gambar;
							
							
						} else {
							die(json_encode(array('errorMsg' => 'Sorry, there was an error uploading your file.')));
						}
					}
				}
			
			if (($this->input->post('NEW_PASS_PROFILE') != $this->input->post('OLD_PASS_PROFILE')) && ($this->input->post('NEW_PASS_PROFILE') != "")) 
			{
				$data_values['PASS'] = encrypt_data($this->input->post('NEW_PASS_PROFILE'));
				$response = $this->model_master_user->simpan_profile($id,$idperusahaan,$data_values);
			}
			else
			{
				$data_values['PASS'] = encrypt_data($this->input->post('OLD_PASS_PROFILE'));
				$response = $this->model_master_user->simpan_profile($id,$idperusahaan,$data_values);
			}
		}
		else
		{
			die(json_encode(array('errorMsg' => "Password Salah")));
		}

		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
	function getPass(){
		$row = $this->model_master_user->getUserByID($this->input->post('id'));
		echo $row->PASS;
	}
	function simpan() {	

		$id          = $this->input->post('IDUSER','');
        $userid      = $this->input->post('USERID');
        $login       = $this->input->post('LOGIN') ?? 0;
        $status      = $this->input->post('STATUS') ?? 0;
        $gambar_lama = $this->input->post('GAMBAR');

        $mode = $this->input->post('mode');
        
		if($userid == "")
		{
			die(json_encode(array('errorMsg' => "User ID Harus Diisi")));
		}
		
		if($this->input->post('PASS') == "")
		{
			die(json_encode(array('errorMsg' => "Password Harus Diisi")));
		}
		if($this->input->post('PASS') != $this->input->post('RE_PASS')){
			die(json_encode(array('errorMsg' => "Pengulangan Password Salah")));
		}
			
        if ($mode=='tambah') {
			$response = $this->model_master_user->cek_valid_userid($userid);
			if($response != ''){
				die(json_encode(array('errorMsg' => $response)));
			}
			

			// cek upload gambar kosong
			if ($_FILES["FILEGAMBAR"]['name'] == ''){
				$gambar = strtoupper('no_image.png');
			}

			$edit = false;
		}else if ($mode=='ubah'){
			$gambar = $gambar_lama;

			$edit = true;
		}

		// upload file
		//define('UPLOAD_DIR', base_url().'assets/foto-jaminan/');
		if ($_FILES["FILEGAMBAR"]['name'] != '') {
			// upload gambar
			$target_dir = "./assets/foto_user/";
			$uploadOk = 1;
			$imageFileType = pathinfo($_FILES['FILEGAMBAR']['name'], PATHINFO_EXTENSION);
			$target_file = $target_dir . str_replace('/', '.', $userid) . '.' . $imageFileType;
			$gambar = str_replace('/', '.', $userid) . '.' . $imageFileType;
			// Check if image file is a actual image or fake image

			$check = getimagesize($_FILES["FILEGAMBAR"]["tmp_name"]);
			if ($check !== false) {
				$uploadOk = 1;
			} else {
				//cek max
				die(json_encode(array('errorMsg' => 'File Yang Diupload Tidak Valid')));
				$uploadOk = 0;
			}

			if ($_POST['GAMBAR'] != '' && strtoupper($_POST['GAMBAR']) != strtoupper('no_image.png')) {
				unlink($target_dir.$_POST['GAMBAR']);
			}
			else if(substr($gambar,0, -4) == substr($gambar_lama,0, -4)  ){
				unlink($target_dir.$gambar_lama);
			}

			// Check if file already exists
			if (file_exists($target_file)) {
				$uploadOk = 0;
			}
			// Check file size
			if ($_FILES["FILEGAMBAR"]["size"] > 5000000) {
				die(json_encode(array('errorMsg' => 'Sorry, your file is too large.')));
				$uploadOk = 0;
			}
			// Allow certain file formats
			if ( ! in_array(strtolower($imageFileType), array('jpg', 'png', 'jpeg', 'gif')) ) {
				die(json_encode(array('errorMsg' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.')));
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				die(json_encode(array('errorMsg' => 'Sorry, your file was not uploaded.')));
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["FILEGAMBAR"]["tmp_name"], $target_file)) {
					//echo "The file ". basename($_FILES["GAMBAR"]["name"]). " has been uploaded.";
				} else {
					die(json_encode(array('errorMsg' => 'Sorry, there was an error uploading your file.')));
				}
			}
		}

		$data_values = array (
			'IDPERUSAHAAN'   => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
			'USERID'         => $userid,
			'USERNAME'       => $this->input->post('USERNAME'),
			'FINGERPRINT1'   => $this->input->post('FINGERPRINT1'),
			'FINGERPRINT2'   => $this->input->post('FINGERPRINT2'),
			//'PASS'		   => encrypt_data($this->input->post('PASS')),
			'AUTHENTICATION' => $this->input->post('AUTHENTICATION'),
			'PRIORITY'       => $this->input->post('PRIORITY'),
			'HP' 	         => $this->input->post('HP'),
			'EMAIL'          => $this->input->post('EMAIL'),
			'GAMBAR'         => $gambar,
			'CATATAN'        => $this->input->post('CATATAN'),
			//'AKSESUTAMA'     => $this->input->post('AKSESUTAMA'),
			'USERENTRY'      => $_SESSION[NAMAPROGRAM]['USERID'],
			'TGLENTRY'       => date("Y-m-d"),
			'LOGIN'          => $login,
			'STATUS'         => $status,
		);

		if ($mode == 'ubah') {
			$row = $this->model_master_user->getUserByID($id);

			if ($row->PASS != $this->input->post('PASS')) {
				$data_values['PASS'] = encrypt_data($this->input->post('PASS'));
			}
		} else 
			$data_values['PASS'] = encrypt_data($this->input->post('PASS'));

		$response = $this->model_master_user->simpan($id,($mode=='tambah'?false:true),$data_values,$dataMaster,$dataTransaksi,$dataLaporan,$dataLokasi);
		if (!is_numeric($response)){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		
		// panggil fungsi untuk log history
		log_history(
			$this->input->post('USERID'),
			'MASTER USER',
			$mode,
			array(
				array(
					'nama'  => 'header',
					'tabel' => 'muser',
					'kode'  => 'userid'
				),
			),
			$_SESSION[NAMAPROGRAM]['USERID']
		);
		
		echo json_encode(array('success' => true,'iduser' => $response));
	}
	
	function simpanDashboard(){
	    $id = $this->input->post('iduser');
		$dataDashboard 	= json_decode($this->input->post('dataDashboard'));
		$response = $this->model_master_user->insertAkses($id, 0, $dataDashboard);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
	
	function simpanMaster(){
	    $id = $this->input->post('iduser');
		$dataMaster 	= json_decode($this->input->post('dataMaster'));
		$response = $this->model_master_user->insertAkses($id, 0, $dataMaster);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
	
	function simpanTransaksi(){
	    $id = $this->input->post('iduser');
		$dataTransaksi 	= json_decode($this->input->post('dataTransaksi'));
		$response = $this->model_master_user->insertAkses($id, 0, $dataTransaksi);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
	
	function simpanLaporan(){
	    $id = $this->input->post('iduser');
		$dataLaporan 	= json_decode($this->input->post('dataLaporan'));
		$response = $this->model_master_user->insertAkses($id, 0, $dataLaporan);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		echo json_encode(array('success' => true,'errorMsg' => ''));
	}
	
	function simpanLokasi(){
	    $id = $this->input->post('iduser');
		$dataLokasi 	= json_decode($this->input->post('dataLokasi'));
		$response = $this->model_master_user->insertLokasi($id,$dataLokasi);
		if ($response != ''){
			// generate an error... or use the log_message() function to log your error
			die(json_encode(array('errorMsg' => $response)));
		}
		echo json_encode(array('success' => true,'errorMsg' => ''));
	}

	public function hapus() {
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');

		$exe = $this->model_master_user->hapus($id);
		if ($exe != '') { die(json_encode(array('errorMsg'=>$exe))); }
		
		echo json_encode(array('success' => true));

		log_history(
			$kode,
			'MASTER USER',
			'HAPUS',
			[],
			$_SESSION[NAMAPROGRAM]['USERID']
		);
	}
}
