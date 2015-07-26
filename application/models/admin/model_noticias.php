<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_noticias extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function countNoticias()
	{		
		return $this->db->where('removed_on IS NULL')->count_all_results('unmd_noticias');		
	}

	function getNoticias($inicio, $offset)
	{		
		return $this->db->select(array('t1.id_noticia', 't1.titulo', 't1.texto', 't1.visivel_desktop', 't1.visivel_mobile', 't1.publicada_em', 't2.dcategoria', 't2.removed_on as categoria_removed_on'))
						->from('unmd_noticias t1')
						->join('unmd_categorias t2', 't1.id_categoria = t2.id_categoria', 'inner')
						->where('t1.removed_on IS NULL')
						->order_by('t1.publicada_em', 'DESC')
						->limit($offset, $inicio)
						->get()						
						->result();
	}

	function getCategoriasOpc()
	{
		$opcoes = array('' => '');
		foreach($this->db->get_where('unmd_categorias', array('removed_on IS NULL' => NULL))->result() as $row){
			$opcoes[$row->id_categoria] = $row->dcategoria;
		}

		return $opcoes;
	}

	function tryInsertNoticia()
	{
		$this->db->trans_begin();
		$this->db->query('INSERT INTO unmd_noticias(titulo,texto,id_categoria,visivel_desktop,visivel_mobile,video_url,publicada_em,id_autor) VALUES (?,?,?,?,?,?,UNIX_TIMESTAMP(),?)',
						array($this->input->post('titulo'), $this->input->post('texto'), $this->input->post('categoria'), $this->input->post('visivel_desktop'), $this->input->post('visivel_mobile'), $this->input->post('video'), $this->session->userdata('useradmid')));
		
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}

	function getNoticia($id_noticia)
	{
		return $this->db->where('removed_on IS NULL')
						->get_where('unmd_noticias', array('id_noticia' => $id_noticia))
						->row(0);
	}

	function tryUpdateNoticia($id_noticia)
	{
		$this->db->trans_begin();
		
			$this->db->query('UPDATE unmd_noticias SET titulo=?, visivel_desktop=?, visivel_mobile=?, updated_on=UNIX_TIMESTAMP() WHERE id_noticia=?',
							array($this->input->post('noticia'), $this->input->post('visivel_desktop'), $this->input->post('visivel_mobile'), $id_noticia));		
		
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}

	function tryDeleteNoticia($id_noticia)
	{
		$this->db->trans_begin();

		$this->db->query('UPDATE unmd_noticias SET removed_on=UNIX_TIMESTAMP() WHERE id_noticia=?', $id_noticia);

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}
}