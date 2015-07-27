<?php
// Bloqueia o acesso direto ao script
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logs extends CI_Controller {

	protected $_usuario = array();
	
	function __construct()
	{
		parent::__construct();
	
		// Se o usuário não estiver logado, redireciona para o login.
		$this->load->Model('admin/Model_login');
		if(!$this->_usuario = $this->Model_login->getLogin()){
			redirect('admin/login', 'refresh');			
		}

		// Acesso somente aos admins
		if($this->_usuario->acesso < 2){
			show_404();
		}
				
		$this->load->Model('admin/Model_logs');
	}
	
	function index()
	{		
		$this->listar();
	}
	
	function listar($de_paginacao = '0')
	{	
		// Nao permite página menor que 0 e permite somente inteiros
		if($de_paginacao < 0 OR !is_numeric($de_paginacao)){
			redirect('admin/logs', 'redirect');			
		}
	
		// Biblioteca da paginação
		$this->load->library('pagination');
	
		// Paginação
		$config_paginacao['base_url'] 	= site_url('admin/logs/listar');		
		$config_paginacao['total_rows'] = $this->Model_logs->countLogs();		
		$config_paginacao['per_page'] = 10;
		$config_paginacao['uri_segment'] = 4;
		$dados['total_rows'] = $config_paginacao['total_rows'];
	
		// Nao permite pagina que nao existe (maior do que existe)
		if($de_paginacao > $config_paginacao['total_rows']){
			redirect('admin/logs', 'redirect');			
		}
	
		// Estilo paginacao
		include_once './assets/pagination/estilo.php';
	
		$this->pagination->initialize($config_paginacao);
		$dados['html_paginacao'] = $this->pagination->create_links();
	
		// Listagem de dados
		$dados['lista'] = '';
		foreach($this->Model_logs->getLogs((int)$de_paginacao, $config_paginacao['per_page']) as $log)
		{
			$dados['lista'] .=
					'<tr>
						<td class="text-center">'. date('d/m/Y \à\s H:i', $log->created_on) .'</td>
						<td class="text-center">'. $log->login .'</td>
						<td>'.$log->descricao.'</td>		
					</tr>';
		}
	
		$dados['usuario'] = $this->_usuario;
		$this->load->view('admin/logs_listar', $dados);
	}
}