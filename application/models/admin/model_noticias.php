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
		
		$new_id = $this->db->insert_id();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return $new_id;
		}
	}

	function insertNoticiaImg($id_noticia, $ordem, $nome_img)
	{
		$this->db->query('INSERT INTO unmd_noticias_imagens(ordem, imagem, id_noticia) VALUES (?,?,?)',
				array($ordem, $nome_img, $id_noticia));
	}

	function getNoticia($id_noticia)
	{
		return $this->db->where('removed_on IS NULL')
						->get_where('unmd_noticias', array('id_noticia' => $id_noticia))
						->row(0);
	}

	function getImagens($id_noticia)
	{
		return $this->db->order_by('ordem')->get_where('unmd_noticias_imagens', array('id_noticia' => $id_noticia))->result();
	}

	function tryUpdateNoticia($id_noticia)
	{
		$this->db->trans_begin();
		
			$this->db->query('UPDATE unmd_noticias SET titulo=?, texto=?, id_categoria=?, visivel_desktop=?, visivel_mobile=?, video_url=?, updated_on=UNIX_TIMESTAMP() WHERE id_noticia=?',
							array($this->input->post('titulo'), $this->input->post('texto'), $this->input->post('categoria'), $this->input->post('visivel_desktop'), $this->input->post('visivel_mobile'), $this->input->post('video'), $id_noticia));		
		
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

	function getImagemInfoByNome($nome_img)
	{
		return $this->db->get_where('unmd_noticias_imagens', array('imagem' => $nome_img))->row(0);
	}

	function moveImagem($id_noticia, $id_imagem, $ordem, $direcao)
	{
		if($direcao == 'right'){
			$this->db->query('UPDATE unmd_noticias_imagens SET ordem=ordem-1 WHERE ordem=? AND id_noticia=?', array($ordem + 1, $id_noticia));
			$this->db->query('UPDATE unmd_noticias_imagens SET ordem=ordem+1 WHERE id_imagem=?', array($id_imagem));
		} else {
			$this->db->query('UPDATE unmd_noticias_imagens SET ordem=ordem+1 WHERE ordem=? AND id_noticia=?', array($ordem - 1, $id_noticia));
			$this->db->query('UPDATE unmd_noticias_imagens SET ordem=ordem-1 WHERE id_imagem=?', array($id_imagem));
		}
	}

	function updateOrdemImagem($id_imagem, $ordem)
	{
		$this->db->query('UPDATE unmd_noticias_imagens SET ordem=? WHERE id_imagem=?', array($ordem, $id_imagem));
	}

	function deleteImagem($nome_img)
	{
		$this->db->query('DELETE FROM unmd_noticias_imagens WHERE imagem=?', $nome_img);
	}
}