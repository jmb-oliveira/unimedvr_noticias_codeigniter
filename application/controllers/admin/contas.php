<?php
// Bloqueia o acesso direto ao script
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contas extends CI_Controller {

	protected $_usuario = array();
	
	function __construct()
	{
		parent::__construct();
	
		// Se o usuário não estiver logado, redireciona para o login.
		$this->load->Model('admin/Model_login');
		if(!$this->_usuario = $this->Model_login->getLogin()){
			redirect('admin/login', 'refresh');			
		}
				
		$this->load->Model('admin/Model_contas');
	}
	
	function index()
	{		
		$this->listar();
	}
	
	function listar($de_paginacao = '0')
	{	
		// Nao permite página menor que 0 e permite somente inteiros
		if($de_paginacao < 0 OR !is_numeric($de_paginacao)){
			redirect('admin/contas', 'redirect');			
		}
	
		// Biblioteca da paginação
		$this->load->library('pagination');
	
		// Paginação
		$config_paginacao['base_url'] 	= site_url('admin/contas/listar');		
		$config_paginacao['total_rows'] = $this->Model_contas->countContas();		
		$config_paginacao['per_page'] = 10;
		$config_paginacao['uri_segment'] = 4;
		$dados['total_rows'] = $config_paginacao['total_rows'];
	
		// Nao permite pagina que nao existe (maior do que existe)
		if($de_paginacao > $config_paginacao['total_rows']){
			redirect('admin/contas', 'redirect');			
		}
	
		// Estilo paginacao
		include_once './assets/pagination/estilo.php';
	
		$this->pagination->initialize($config_paginacao);
		$dados['html_paginacao'] = $this->pagination->create_links();
	
		// Listagem de dados
		$dados['lista'] = '';
		foreach($this->Model_contas->getContas((int)$de_paginacao, $config_paginacao['per_page']) as $conta)
		{
			$dados['lista'] .=
					'<tr>
						<td class="col-md-2">'.$conta->dusuario.'</td>
						<td class="col-md-2">'.$conta->login.'</td>
						<td class="col-md-2">'.(($conta->acesso == 1) ? 'Autor' : 'Administrador').'</td>
						<td class="col-md-2 text-center">'.(($conta->habilitado == '1') ? 'Sim' : 'Não').'</td>
						<td class="col-md-2 text-center">
							<div class="btn-group">
							  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> <span class="hidden-510">Ações</span> <span class="caret"></span> </button>
							  <ul class="dropdown-menu pull-right text-left" role="menu">
							  	<li><a href="'.base_url('admin/contas/editar/'.$conta->id_usuario).'"><span class="glyphicon glyphicon-pencil"></span> Editar</a></li>';

							if($conta->id_usuario != $this->_usuario->id_usuario){				
								$dados['lista'] .= '<li data-id-registro="'.$conta->id_usuario.'"><a style="cursor:pointer" class="remover"><span class="glyphicon glyphicon-trash"></span> Remover</a></li>';
							}

			$dados['lista'] .= '</ul>
							</div>			
						</td>
					</tr>';
		}
	
		$dados['usuario'] = $this->_usuario;
		$this->load->view('admin/contas_listar', $dados);
	}
	
	function inserir()
	{	
		// Form Validation Configs
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
		$rules = array(
				array('field' => 'nome', 'rules' => 'required|sanitizeHTML|min_length[3]|max_length[60]'),
				array('field' => 'email', 'rules' => 'required|valid_email'),
				array('field' => 'usuario', 'rules' => 'required|sanitizeHTML|min_length[3]|max_length[60]'),
				array('field' => 'acesso', 'label' => 'nível de acesso', 'rules' => 'required|strtonumeric'),
				array('field' => 'senha', 'rules' => 'required|min_length[6]|max_length[15]'),
				array('field' => 'confirmar_senha', 'label' => 'confirmar senha', 'rules' => 'required|matches[senha]'),
				array('field' => 'habilitada', 'label' => 'conta habilitada', 'rules' => 'required|strtonumeric')
		);
		$this->form_validation->set_rules($rules);
		
		// Enviar (submit)
		if($this->form_validation->run())
		{
			if($this->Model_contas->tryInsertConta()){
				$this->Model_login->addLog('Criou a Conta de usuário: '. $this->input->post('usuario'));
				$dados['sucesso'] = TRUE;
			} else {
				$dados['erro'] = TRUE;
			}
		}
		
		$dados['usuario'] = $this->_usuario;
		$this->load->view('admin/contas_inserir', $dados);
	}
	
	function editar($id_conta = NULL)
	{
		if(!$dados['conta'] = $this->Model_contas->getConta((int)$id_conta))
			show_404();
		
		// Form Validation Configs
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
		$rules = array(
				array('field' => 'nome', 'rules' => 'required|sanitizeHTML|min_length[3]|max_length[60]'),
				array('field' => 'email', 'rules' => 'required|valid_email'),
				array('field' => 'usuario', 'rules' => 'required|sanitizeHTML|min_length[3]|max_length[60]'),
				array('field' => 'acesso', 'label' => 'nível de acesso', 'rules' => 'required|strtonumeric'),
				array('field' => 'senha', 'rules' => 'required|min_length[6]|max_length[15]'),
				array('field' => 'confirmar_senha', 'label' => 'confirmar senha', 'rules' => 'required|matches[senha]'),
				array('field' => 'habilitada', 'label' => 'conta habilitada', 'rules' => 'required|strtonumeric')
		);
		$this->form_validation->set_rules($rules);
		
		// Enviar (submit)
		if($this->form_validation->run())
		{
			if($this->Model_contas->tryUpdateConta($id_conta)){
				$this->Model_login->addLog('Alterou a Conta de usuário id '. $id_conta);
				$dados['sucesso'] = TRUE;
				$dados['conta'] = $this->Model_contas->getConta($id_conta); // recarregar dados
			} else {
				$dados['erro'] = TRUE;	
			}			
		}
		
		$dados['usuario'] = $this->_usuario;
		$this->load->view('admin/contas_editar', $dados);
	}
	
	function remover($id_conta = NULL)
	{
		if(!$dados['conta'] = $this->Model_contas->getConta((int)$id_conta))
			show_404();

		// Bloquear remoção da própria conta
		if($id_conta == $this->_usuario->id_usuario){
			$this->session->set_flashdata('error-self-account', TRUE);
			redirect('admin/contas', 'refresh');
		}

		
		if($this->Model_contas->tryDeleteConta($id_conta)){
			$this->Model_login->addLog('Removeu a Conta do usuário '. $this->Model_contas->getLoginConta($id_conta));
			$this->session->set_flashdata('removed-ok', TRUE);
		} else {			
			$this->session->set_flashdata('removed-error', TRUE);
		}

		redirect('admin/contas', 'refresh');
	}
}