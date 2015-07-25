<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_noticias extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function countNoticias($busca)
	{
		if($busca == 'sem_busca')
			return $this->db->where('removed_on IS NULL')
							->count_all_results('unmd_noticias');
		else
			return $this->db->where('removed_on IS NULL')
							->like('titulo', $busca)
							->count_all_results('unmd_noticias');
	}
	function getNoticias($busca, $inicio, $offset)
	{
		$query = $this->db->select(array('id_noticia', 'titulo', 'texto', 'publicada_em'))
				 		  ->where('removed_on IS NULL');						 
			
		if($busca != 'sem_busca')
			$query->like('titulo', $busca);

		$result = $query->limit($offset, $inicio)
			  ->order_by('publicada_em', 'DESC')
			  ->get('unmd_noticias')
			  ->result();

		return $result;
	}
}