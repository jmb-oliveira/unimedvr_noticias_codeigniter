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
		return $this->db->select(array('id_categoria', 'dcategoria'))
						->from('unmd_categorias')
						->where('removed_on IS NULL')
						->order_by('dcategoria', 'ASC')
						->limit($offset, $inicio)
						->get()						
						->result();
	}

	function getCategoria($id_categoria)
	{
		return $this->db->where('removed_on IS NULL')
						->get_where('unmd_categorias', array('id_categoria' => $id_categoria))
						->row(0);
	}

	function countCategoriaNoticias($id_categoria, $busca)
	{
		if($busca == 'sem_busca')
			return $this->db->where(array('removed_on IS NULL' => NULL, 'id_categoria' => $id_categoria))
							->count_all_results('unmd_noticias');
		else
			return $this->db->where(array('removed_on IS NULL' => NULL, 'id_categoria' => $id_categoria))
							->like('titulo', $busca)
							->count_all_results('unmd_noticias');
	}
	function getCategoriaNoticias($id_categoria, $busca, $inicio, $offset)
	{
		$query = $this->db->select(array('id_noticia', 'titulo', 'texto', 'publicada_em'))
				 		  ->where('removed_on IS NULL')
				 		  ->where('id_categoria', $id_categoria);						 
			
		if($busca != 'sem_busca')
			$query->like('titulo', $busca);

		$result = $query->limit($offset, $inicio)
			  ->order_by('publicada_em', 'DESC')
			  ->get('unmd_noticias')
			  ->result();

		return $result;
	}
}