<?php
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => '192.168.100.10',
	'username' => 'root',
	'password' => '',
	'database' => 'dev_becik_joyo_back_end',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

function __construct() { 
	// Call the Model constructor parent::__construct(); 
	$this->load->library('session'); 
	$this->db = $this->load->database($this->session->DatabaseConfig, TRUE); 
}
?>