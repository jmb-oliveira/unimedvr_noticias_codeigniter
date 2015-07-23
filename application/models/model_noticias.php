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
			return $this->db->query('SELECT COUNT(id_noticia) as cont
									 FROM unmd_noticias
									 WHERE removed_on IS NULL')->row(0)->cont;
		else
			return $this->db->query('SELECT COUNT(id_noticia) as cont
									 FROM unmd_noticias
									 WHERE (titulo LIKE \'%'.$this->db->escape_like_str($busca).'%\')
									 AND removed_on IS NULL')->row(0)->cont;
	}
	function getNoticias($busca, $inicio, $limite)
	{
		$sql = 'SELECT id_noticia, titulo, texto, publicada_em
				FROM unmd_noticias
				WHERE removed_on IS NULL';
			
		if($busca != 'sem_busca')
			$sql .= ' AND (titulo LIKE \'%'.$this->db->escape_like_str($busca).'%\')';
			
		$sql .= ' ORDER BY publicada_em DESC LIMIT ?,?';

		return $this->db->query($sql, array($inicio, $limite))->result();
	}
}