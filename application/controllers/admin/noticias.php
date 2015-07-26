<?php
// Bloqueia o acesso direto ao script
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Noticias extends CI_Controller {

	protected $_usuario = array();
	
	function __construct()
	{
		parent::__construct();
	
		// Se o usuário não estiver logado, redireciona para o login.
		$this->load->Model('admin/Model_login');
		if(!$this->_usuario = $this->Model_login->getLogin()){
			redirect('admin/login', 'refresh');			
		}
				
		$this->load->Model('admin/Model_noticias');
	}
	
	function index()
	{		
		$this->listar();
	}
	
	function listar($de_paginacao = '0')
	{	
		// Nao permite página menor que 0 e permite somente inteiros
		if($de_paginacao < 0 OR !is_numeric($de_paginacao)){
			redirect('admin/noticias', 'redirect');			
		}
	
		// Biblioteca da paginação
		$this->load->library('pagination');
	
		// Paginação
		$config_paginacao['base_url'] 	= site_url('admin/noticias/listar');		
		$config_paginacao['total_rows'] = $this->Model_noticias->countNoticias();		
		$config_paginacao['per_page'] = 10;
		$config_paginacao['uri_segment'] = 4;
		$dados['total_rows'] = $config_paginacao['total_rows'];
	
		// Nao permite pagina que nao existe (maior do que existe)
		if($de_paginacao > $config_paginacao['total_rows']){
			redirect('admin/noticias', 'redirect');			
		}
	
		// Estilo paginacao
		include_once './assets/pagination/estilo.php';
	
		$this->pagination->initialize($config_paginacao);
		$dados['html_paginacao'] = $this->pagination->create_links();
	
		// Listagem de dados
		$dados['lista'] = '';
		foreach($this->Model_noticias->getNoticias((int)$de_paginacao, $config_paginacao['per_page']) as $noticia)
		{
			$alerta_categoria = (!is_null($noticia->categoria_removed_on)) ? '<span class="glyphicon glyphicon-exclamation-sign text-danger" data-toggle="tooltip" data-placement="right" title="Notícia invisível. A categoria foi removida."></span>' : '';
			$dados['lista'] .=
					'<tr>
						<td><strong>'.$noticia->titulo.'</strong>
							<p class="news-datetime">Em '. date('d/m/Y \à\s H:i', $noticia->publicada_em) .' - Categoria: '. $noticia->dcategoria .' '. $alerta_categoria .'</p>
							<p>'. limita_caracteres($noticia->texto, 600, FALSE) .'</p>
						</td>						
						<td class="text-center">'.(($noticia->visivel_desktop == 1) ? 'Sim' : 'Não').'</td>
						<td class="text-center">'.(($noticia->visivel_mobile == 1) ? 'Sim' : 'Não').'</td>
						<td class="text-center">
							<div class="btn-group">
							  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> <span class="hidden-510">Ações</span> <span class="caret"></span> </button>
							  <ul class="dropdown-menu pull-right text-left" role="menu">
							  	<li><a href="'.base_url('admin/noticias/editar/'.$noticia->id_noticia).'"><span class="glyphicon glyphicon-pencil"></span> Editar</a></li>			
								<li data-id-registro="'.$noticia->id_noticia.'"><a style="cursor:pointer" class="remover"><span class="glyphicon glyphicon-trash"></span> Remover</a></li>
							</ul>
							</div>			
						</td>
					</tr>';
		}
	
		$dados['usuario'] = $this->_usuario;
		$this->load->view('admin/noticias_listar', $dados);
	}
	
	function inserir()
	{	
		// Form Validation Configs
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
		$rules = array(
				array('field' => 'titulo', 'rules' => 'required|sanitizeHTML|min_length[3]|max_length[200]'),
				array('field' => 'texto', 'rules' => 'required|sanitizeHTML|min_length[3]'),
				array('field' => 'video', 'rules' => 'valid_url'),
				array('field' => 'categoria', 'rules' => 'required|strtonumeric'),
				array('field' => 'visivel_desktop', 'rules' => 'required|strtonumeric'),
				array('field' => 'visivel_mobile', 'rules' => 'required|strtonumeric')			
		);
		$this->form_validation->set_rules($rules);
		
		// Enviar (submit)
		if($this->form_validation->run())
		{
			if($this->Model_noticias->tryInsertNoticia()){
				$this->Model_login->addLog('Publicou a Noticia: '. $this->input->post('titulo'));
				$dados['sucesso'] = TRUE;
			} else {
				$dados['erro'] = TRUE;
			}
		}
		
		$dados['usuario'] = $this->_usuario;
		$this->load->view('admin/noticias_inserir', $dados);
	}
	
	function editar($id_noticia = NULL)
	{
		if(!$dados['noticia'] = $this->Model_noticias->getNoticia((int)$id_noticia))
			show_404();
		
		// Form Validation Configs
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
		$rules = array(
				array('field' => 'noticia', 'rules' => 'required|sanitizeHTML|min_length[3]|max_length[60]'),
				array('field' => 'visivel_desktop', 'rules' => 'required|strtonumeric'),
				array('field' => 'visivel_mobile', 'rules' => 'required|strtonumeric')
		);
		$this->form_validation->set_rules($rules);
		
		// Enviar (submit)
		if($this->form_validation->run())
		{
			if($this->Model_noticias->tryUpdateNoticia($id_noticia)){
				$this->Model_login->addLog('Alterou a Noticia id '. $id_noticia);
				$dados['sucesso'] = TRUE;
				$dados['noticia'] = $this->Model_noticias->getNoticia($id_noticia); // recarregar dados
			} else {
				$dados['erro'] = TRUE;	
			}			
		}
		
		$dados['usuario'] = $this->_usuario;
		$this->load->view('admin/noticias_editar', $dados);
	}
	
	function remover($id_noticia = NULL)
	{
		if(!$noticia = $this->Model_noticias->getNoticia((int)$id_noticia))
			show_404();
		
		if($this->Model_noticias->tryDeleteNoticia($id_noticia)){
			$this->Model_login->addLog('Removeu a Noticia '. $noticia->titulo);
			$this->session->set_flashdata('removed-ok', TRUE);
		} else {			
			$this->session->set_flashdata('removed-error', TRUE);
		}

		redirect('admin/noticias', 'refresh');
	}
}