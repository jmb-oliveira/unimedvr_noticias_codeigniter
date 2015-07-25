<?php
// Bloqueia o acesso direto ao script
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categorias extends CI_Controller {

	protected $_usuario = array();
	
	function __construct()
	{
		parent::__construct();
	
		// Se o usuário não estiver categoriaado, redireciona para o login.
		$this->load->Model('admin/Model_login');
		$this->_usuario = $this->Model_login->getLogin();

		$this->load->Model('Model_categorias');
	}
	
	function index()
	{		
		$this->listar();
	}
	
	function listar($de_paginacao = '0')
	{	
		// Nao permite página menor que 0 e permite somente inteiros
		if($de_paginacao < 0 OR !is_numeric($de_paginacao)){
			redirect('categorias', 'redirect');			
		}
	
		// Biblioteca da paginação
		$this->load->library('pagination');
	
		// Paginação
		$config_paginacao['base_url'] 	= site_url('categorias/listar');		
		$config_paginacao['total_rows'] = $this->Model_categorias->countCategorias();		
		$config_paginacao['per_page'] = 10;
		$config_paginacao['uri_segment'] = 3;
		$dados['total_rows'] = $config_paginacao['total_rows'];
	
		// Nao permite pagina que nao existe (maior do que existe)
		if($de_paginacao > $config_paginacao['total_rows']){
			redirect('categorias', 'redirect');			
		}
	
		// Estilo paginacao
		include_once './assets/pagination/estilo.php';
	
		$this->pagination->initialize($config_paginacao);
		$dados['html_paginacao'] = $this->pagination->create_links();
	
		// Listagem de dados
		$dados['lista'] = '';
		foreach($this->Model_categorias->getCategorias((int)$de_paginacao, $config_paginacao['per_page']) as $categoria)
		{
			$dados['lista'] .=
					'<h3><a href="'. base_url('categorias/detalhar/'. $categoria->id_categoria) .'" title="Ver notícias desta categoria">&bull; '.$categoria->dcategoria.'</a></h3>';
		}
	
		$dados['usuario'] = $this->_usuario;
		$this->load->view('categorias_listar', $dados);
	}

	function detalhar($id_categoria = null, $busca = 'sem_busca', $de_paginacao = '0')
	{
		if(!$dados['categoria'] = $this->Model_categorias->getCategoria($id_categoria)){
			show_404();
		}

		$this->load->helper('form');
		
		if($this->input->post('busca'))
			redirect('categorias/detalhar/'. $id_categoria .'/'. custom_urlencode(str_replace(array('%', "'"), array('', ''), $this->input->post('busca'))), 'refresh');		
		
		// Nao permite página menor que 0 e permite somente inteiros
		if($de_paginacao < 0 OR !is_numeric($de_paginacao)){
			redirect('categorias', 'redirect');			
		}
	
		// Biblioteca da paginação
		$this->load->library('pagination');		
	
		// Paginação
		$busca_tratado = removeAcentos(custom_urldecode($busca));
		$config_paginacao['base_url'] 	= site_url('categorias/detalhar/'. $id_categoria .'/'. $busca);
		$config_paginacao['total_rows'] = $this->Model_categorias->countCategoriaNoticias($id_categoria, $busca_tratado);
		$dados['total_rows'] = $config_paginacao['total_rows'];
		$config_paginacao['per_page'] = 5;
		$config_paginacao['uri_segment'] = 5;
	
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
		foreach($this->Model_categorias->getCategoriaNoticias($id_categoria, $busca_tratado, (int)$de_paginacao, $config_paginacao['per_page']) as $noticia)
		{
			$dados['lista'] .=
						'<div class="news-box">
							<h2><a href="'. base_url('noticia/detalhes/' . $noticia->id_noticia) .'" title="Ver notícia completa">'. $noticia->titulo .'</a></h2>
							<p class="news-datetime">Em '. date('d/m/Y \à\s H:i', $noticia->publicada_em) .'</p>
							<p>'. limita_caracteres($noticia->texto, 600, FALSE) .'</p>
						 </div>';
		}
	
		$dados['usuario'] = $this->_usuario;		
		$this->load->view('categoria_noticias_listar', $dados);
	}
}