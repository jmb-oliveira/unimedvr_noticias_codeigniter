<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_categorias extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function countCategorias()
	{		
		return $this->db->where('removed_on IS NULL')->count_all_results('unmd_categorias');		
	}

	function getCategorias($inicio, $offset)
	{		
		return $this->db->where('removed_on IS NULL')
						->limit($offset, $inicio)
						->get('unmd_categorias')						
						->result();
	}

	function tryInsertCategoria()
	{
		$this->db->trans_begin();
		$this->db->query('INSERT INTO unmd_categorias(dcategoria,visivel_desktop,visivel_mobile,id_autor,created_on) VALUES (?,?,?,?,UNIX_TIMESTAMP())',
						array($this->input->post('categoria'), $this->input->post('visivel_desktop'), $this->input->post('visivel_mobile'), $this->session->userdata('useradmid')));
		
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}

	function getCategoria($id_categoria)
	{
		return $this->db->where('removed_on IS NULL')
						->get_where('unmd_categorias', array('id_categoria' => $id_categoria))
						->row(0);
	}

	function tryUpdateCategoria($id_categoria)
	{
		$this->db->trans_begin();
		
			$this->db->query('UPDATE unmd_categorias SET dcategoria=?, visivel_desktop=?, visivel_mobile=?, updated_on=UNIX_TIMESTAMP() WHERE id_categoria=?',
							array($this->input->post('categoria'), $this->input->post('visivel_desktop'), $this->input->post('visivel_mobile'), $id_categoria));		
		
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}

	function tryDeleteCategoria($id_categoria)
	{
		$this->db->trans_begin();

		$this->db->query('UPDATE unmd_categorias SET removed_on=UNIX_TIMESTAMP() WHERE id_categoria=?', $id_categoria);

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}
}