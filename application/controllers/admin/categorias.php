<?php
// Bloqueia o acesso direto ao script
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categorias extends CI_Controller {

	protected $_usuario = array();
	
	function __construct()
	{
		parent::__construct();
	
		// Se o usuário não estiver logado, redireciona para o login.
		$this->load->Model('admin/Model_login');
		if(!$this->_usuario = $this->Model_login->getLogin()){
			redirect('admin/login', 'refresh');			
		}
				
		$this->load->Model('admin/Model_categorias');
	}
	
	function index()
	{		
		$this->listar();
	}
	
	function listar($de_paginacao = '0')
	{	
		// Nao permite página menor que 0 e permite somente inteiros
		if($de_paginacao < 0 OR !is_numeric($de_paginacao)){
			redirect('admin/categorias', 'redirect');			
		}
	
		// Biblioteca da paginação
		$this->load->library('pagination');
	
		// Paginação
		$config_paginacao['base_url'] 	= site_url('admin/categorias/listar');		
		$config_paginacao['total_rows'] = $this->Model_categorias->countCategorias();		
		$config_paginacao['per_page'] = 10;
		$config_paginacao['uri_segment'] = 4;
		$dados['total_rows'] = $config_paginacao['total_rows'];
	
		// Nao permite pagina que nao existe (maior do que existe)
		if($de_paginacao > $config_paginacao['total_rows']){
			redirect('admin/categorias', 'redirect');			
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
					'<tr>
						<td>'.$categoria->dcategoria.'</td>						
						<td class="text-center">'.(($categoria->visivel_desktop == 1) ? 'Sim' : 'Não').'</td>
						<td class="text-center">'.(($categoria->visivel_mobile == 1) ? 'Sim' : 'Não').'</td>
						<td class="text-center">
							<div class="btn-group">
							  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> <span class="hidden-510">Ações</span> <span class="caret"></span> </button>
							  <ul class="dropdown-menu pull-right text-left" role="menu">
							  	<li><a href="'.base_url('admin/categorias/editar/'.$categoria->id_categoria).'"><span class="glyphicon glyphicon-pencil"></span> Editar</a></li>			
								<li data-id-registro="'.$categoria->id_categoria.'"><a style="cursor:pointer" class="remover"><span class="glyphicon glyphicon-trash"></span> Remover</a></li>
							</ul>
							</div>			
						</td>
					</tr>';
		}
	
		$dados['usuario'] = $this->_usuario;
		$this->load->view('admin/adm_categorias_listar', $dados);
	}
	
	function inserir()
	{	
		// Form Validation Configs
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
		$rules = array(
				array('field' => 'categoria', 'rules' => 'required|sanitizeHTML|min_length[3]|max_length[60]'),
				array('field' => 'visivel_desktop', 'rules' => 'required|strtonumeric'),
				array('field' => 'visivel_mobile', 'rules' => 'required|strtonumeric')			
		);
		$this->form_validation->set_rules($rules);
		
		// Enviar (submit)
		if($this->form_validation->run())
		{
			if($this->Model_categorias->tryInsertCategoria()){
				$this->Model_login->addLog('Criou a Categoria: '. $this->input->post('categoria'));
				$dados['sucesso'] = TRUE;
			} else {
				$dados['erro'] = TRUE;
			}
		}
		
		$dados['usuario'] = $this->_usuario;
		$this->load->view('admin/adm_categorias_inserir', $dados);
	}
	
	function editar($id_categoria = NULL)
	{
		if(!$dados['categoria'] = $this->Model_categorias->getCategoria((int)$id_categoria))
			show_404();
		
		// Form Validation Configs
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
		$rules = array(
				array('field' => 'categoria', 'rules' => 'required|sanitizeHTML|min_length[3]|max_length[60]'),
				array('field' => 'visivel_desktop', 'rules' => 'required|strtonumeric'),
				array('field' => 'visivel_mobile', 'rules' => 'required|strtonumeric')
		);
		$this->form_validation->set_rules($rules);
		
		// Enviar (submit)
		if($this->form_validation->run())
		{
			if($this->Model_categorias->tryUpdateCategoria($id_categoria)){
				$this->Model_login->addLog('Alterou a Categoria id '. $id_categoria);
				$dados['sucesso'] = TRUE;
				$dados['categoria'] = $this->Model_categorias->getCategoria($id_categoria); // recarregar dados
			} else {
				$dados['erro'] = TRUE;	
			}			
		}
		
		$dados['usuario'] = $this->_usuario;
		$this->load->view('admin/adm_categorias_editar', $dados);
	}
	
	function remover($id_categoria = NULL)
	{
		if(!$categoria = $this->Model_categorias->getCategoria((int)$id_categoria))
			show_404();
		
		if($this->Model_categorias->tryDeleteCategoria($id_categoria)){
			$this->Model_login->addLog('Removeu a Categoria '. $categoria->dcategoria);
			$this->session->set_flashdata('removed-ok', TRUE);
		} else {			
			$this->session->set_flashdata('removed-error', TRUE);
		}

		redirect('admin/categorias', 'refresh');
	}
}