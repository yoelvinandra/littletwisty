<?php
class Model_master_config extends CI_Model{
	public $maindb;
	public function __construct()
	{
		$this->maindb = $this->load->database('default',true);	
	}
	
	public function getConfigMarketplace($modul,$conf){
		return $this->maindb
					->where('MODUL',$modul)
					->where('CONFIG',$conf)
					->get('MCONFIG')->row()->VALUE;
	}
	
	public function setConfigMarketplace($modul,$param,$val){
		$this->maindb->set('VALUE', $val)
		->where('MODUL',$modul)
		->where('CONFIG',$param)
		->updateRaw('MCONFIG');
		
		if ($this->db->trans_status() === FALSE) { 
			$this->db->trans_rollback();
			return 'Data Barang Tidak Dapat Dihapus'; 
		}
	}
    
	public function getConfig($modul,$conf){
		return $this->maindb
					->where('MODUL',$modul)
					->where('CONFIG',$conf)
					->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					->get('MCONFIG')->row()->VALUE;
	}
	
	public function getConfigAll($modul,$conf){
		return $this->maindb
					->where('MODUL',$modul)
					->where('CONFIG',$conf)
					->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
					->get('MCONFIG')->row();
	}
	
	public function getPPN(){
	    $q = $this->maindb->where('CONFIG',"PPNPERSEN")
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('MCONFIG')->row_array();
		return $q["VALUE"];
	}
	
	public function setConfig($conf,$val){
	    $q = $this->maindb->where('CONFIG',$conf)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('MCONFIG');

	    if ( $q->num_rows() > 0 ){
		    $this->maindb->set('VALUE', $val)
						->where('CONFIG', $conf)
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->update('MCONFIG');
	    } else {
			$data = array(
					'IDPERUSAHAAN' => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
					'CONFIG'       => $conf,
					'VALUE'        => $val
			);
		    $this->maindb->insert('MCONFIG',$data);
	    }
		return true;
	}
	
	function getAkses($kodemenu){
		if ($_SESSION[NAMAPROGRAM]['USERID'] === 'VISION') {
            return array('TAMBAH' => 1, 'UBAH' => 1, 'HAPUS' => 1, 'OTORISASI' => 1, 'TAMPILGRANDTOTAL' => 1, 'PRINTULANG' => 1, 'BLOKIR' => 1,'INPUTHARGA' => 1,'LIHATHARGA' => 1);
        }
		$query = $this->maindb->from('MUSERAKSES a')
					->join('MMENU b','a.KODEMENU = b.KODEMENU','left')
					->where('b.KODEMENU', $kodemenu)
					->where('b.STATUS', 1)
					->where('a.IDUSER', $_SESSION[NAMAPROGRAM]['IDUSER'])
					->get()->row_array();
		return $query;
	}
	
	public function getPersentasePendaftaranFlamboyan(){
	    $q = $this->maindb->where('CONFIG',"PERSENTASEDAFTARFLAMBOYAN")
						->where('IDPERUSAHAAN',$_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'])
						->get('MCONFIG')->row_array();
		return $q["VALUE"];
	}
}