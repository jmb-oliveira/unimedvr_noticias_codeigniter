<?php
// Bloqueia o acesso direto ao script
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

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
		if($this->input->post('busca'))
			redirect('home/noticias/'.custom_urlencode(str_replace(array('%', "'"), array('', ''), $this->input->post('busca'))), 'refresh');
		else
			$this->noticias();
	}

	function noticias($busca = 'sem_busca', $de_paginacao = '0')
	{		
		$this->load->helper('form');
		
		if($this->input->post('busca'))
			redirect('home/noticias/'.custom_urlencode(str_replace(array('%', "'"), array('', ''), $this->input->post('busca'))), 'refresh');		
		
		// Nao permite página menor que 0 e permite somente inteiros
		if($de_paginacao < 0 OR !is_numeric($de_paginacao)){
			redirect('home', 'redirect');			
		}
	
		// Biblioteca da paginação
		$this->load->library('pagination');		
	
		// Paginação
		$busca_tratado = removeAcentos(custom_urldecode($busca));
		$config_paginacao['base_url'] 	= site_url('home/noticias/'.$busca);
		$config_paginacao['total_rows'] = $this->Model_noticias->countNoticias($busca_tratado);
		$dados['total_rows'] = $config_paginacao['total_rows'];
		$config_paginacao['per_page'] = 5;
		$config_paginacao['uri_segment'] = 4;
	
		// Nao permite pagina que nao existe (maior do que existe)
		if($de_paginacao > $config_paginacao['total_rows']){
			redirect('home', 'redirect');			
		}
		
		// Estilo paginacao
		include_once './assets/pagination/estilo.php';
	
		$this->pagination->initialize($config_paginacao);
		$dados['html_paginacao'] = $this->pagination->create_links();

		if($busca != 'sem_busca'){
			$dados['busca'] = TRUE;
		}
	
		// Listagem de dados
		$dados['lista'] = '';
		foreach($this->Model_noticias->getNoticias($busca_tratado, (int)$de_paginacao, $config_paginacao['per_page']) as $noticia)
		{
			$dados['lista'] .=
						'<div class="news-box">
							<h2><a href="'. base_url('noticia/detalhes/' . $noticia->id_noticia) .'" title="Ver notícia completa">'. $noticia->titulo .'</a></h2>
							<p class="news-datetime">Em '. date('d/m/Y \à\s H:i', $noticia->publicada_em) .'</p>
							<p>'. limita_caracteres($noticia->texto, 600, FALSE) .'</p>
						 </div>';
		}
	
		$dados['usuario'] = $this->_usuario;		
		$this->load->view('noticias_listar', $dados);
	}

	function sair()
	{
		$this->session->unset_userdata('useradmid');
		$this->session->unset_userdata('useradmsenha');
		redirect('home', 'refresh');
	}
}