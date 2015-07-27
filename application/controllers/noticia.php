<?php
// Bloqueia o acesso direto ao script
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Noticia extends CI_Controller {

	protected $_usuario = array();	
	
	function __construct()
	{
		parent::__construct();
			
		$this->load->Model('admin/Model_login');			
		
		if($this->session->userdata('useradmid') != ''){
			$this->_usuario = $this->Model_login->getLogin();
		}
		
		$this->load->Model('Model_noticias');
	}
	
	function index()
	{
		show_404();
	}

	function detalhes($id_noticia = NULL)
	{		
		if(!$noticia = $this->Model_noticias->getNoticia($id_noticia)){
			show_404();
		}

		$dados['noticia'] = $noticia;

		// Galeria de Imagens
		$dados['imagens'] = '';
		foreach($this->Model_noticias->getNoticiaImagens($id_noticia) as $img){
			if(file_exists('uploads/noticias/'.$img->imagem)){
				$dados['imagens'] .= '<a class="fancybox gallery-item" rel="gallery1" href="'.base_url('uploads/noticias/'.$img->imagem).'">
										<img src="'.base_url('uploads/noticias/thumb/'.$img->imagem).'"/>
									  </a>';
			}									 
		}

		// Embed Video
		$dados['embed_video'] = '';
		if($noticia->video_url != ''){

			$cod_video = (stripos($noticia->video_url, '?v=') !== false) ? explode('?v=', $noticia->video_url) : explode('/v/', $noticia->video_url);
			$cod_video = end($cod_video);

			$embed_url = 'https://www.youtube.com/embed/'. $cod_video;

			$dados['embed_video'] = '<iframe width="560" height="315" src="'. $embed_url .'" frameborder="0" allowfullscreen></iframe>';
		}			
	
		$dados['usuario'] = $this->_usuario;		
		$this->load->view('noticia_detalhar', $dados);
	}
}