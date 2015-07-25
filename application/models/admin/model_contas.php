<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_contas extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function countContas()
	{		
		return $this->db->where('removed_on IS NULL')->count_all_results('unmd_usuarios');		
	}

	function getContas($inicio, $offset)
	{		
		return $this->db->where('removed_on IS NULL')
						->limit($offset, $inicio)
						->get('unmd_usuarios')						
						->result();
	}

	function tryInsertConta()
	{
		$this->db->trans_begin();
		$this->db->query('INSERT INTO unmd_usuarios(acesso,habilitado,dusuario,login,email,senha,created_on) VALUES (?,?,?,?,?,?,UNIX_TIMESTAMP())',
						array($this->input->post('acesso'), $this->input->post('habilitada'), $this->input->post('nome'), $this->input->post('usuario'), $this->input->post('email'), sha1($this->input->post('senha'))));
		
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}

	function getConta($id_conta)
	{
		return $this->db->where('removed_on IS NULL')
						->get_where('unmd_usuarios', array('id_usuario' => $id_conta))
						->row(0);
	}

	function tryUpdateConta($id_conta)
	{
		$this->db->trans_begin();

		// Se a senha for "(######)", não atualiza a senha, mantém a anterior
		if($this->input->post('senha') == '(######)')
			$this->db->query('UPDATE unmd_usuarios SET acesso=?, habilitado=?, dusuario=?, login=?, email=?, updated_on=UNIX_TIMESTAMP() WHERE id_usuario=?',
							array($this->input->post('acesso'), $this->input->post('habilitada'), $this->input->post('nome'), $this->input->post('usuario'), $this->input->post('email'), $id_conta));
		else
			$this->db->query('UPDATE unmd_usuarios SET acesso=?, habilitado=?, dusuario=?, login=?, email=?, senha=?, updated_on=UNIX_TIMESTAMP() WHERE id_usuario=?',
							array($this->input->post('acesso'), $this->input->post('habilitada'), $this->input->post('nome'), $this->input->post('usuario'), $this->input->post('email'), sha1($this->input->post('senha')), $id_conta));		
		
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}

	function tryDeleteConta($id_conta)
	{
		$this->db->trans_begin();

		$this->db->query('UPDATE unmd_usuarios SET habilitado="0", removed_on=UNIX_TIMESTAMP() WHERE id_usuario=?', $id_conta);

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}
}