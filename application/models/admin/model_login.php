<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_login extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function checaContaLogin()
	{
		return $this->db->query("SELECT id_usuario
								 FROM unmd_usuarios
								 WHERE (login = ?) AND (senha = ?)",
								 array($this->input->post('login'), sha1($this->input->post('senha'))))
						->row(0);
	}
	
	function getLogin()
	{
		return $this->db->query("SELECT id_usuario, dusuario, acesso
								FROM unmd_usuarios
								WHERE (id_usuario=?) AND (senha=?)",
								array($this->session->userdata('useradmid'), sha1($this->session->userdata('useradmsenha'))))
						->row(0);
	}

	function addLog($log_message)
	{
		$this->db->query('INSERT INTO unmd_logs VALUES (NULL,?,?,UNIX_TIMESTAMP())', array($log_message, $this->session->userdata('useradmid')));
	}
}