<?php
// Bloqueia o acesso direto ao script
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->Model('admin/Model_login');
	}
	
	function index()
	{
		$this->load->helper('form');
		$dados[] = '';
		
		// Submit
		if($this->input->post('submit')){
	
			if($this->input->post('login')=='')
				$dados['erro'] = TRUE;
			
			if($this->input->post('senha')=='')
				$dados['erro'] = TRUE;
			
			if(!isset($dados['erro'])){
				// Checa se o usuário e senha são válidos
				if($id = $this->Model_login->checaContaLogin($this->input->post('login'), $this->input->post('senha'))){
					// Coloca na sessao o id do usuário e a senha
					$this->session->set_userdata('useradmid', $id->id_usuario);
					$this->session->set_userdata('useradmsenha', $this->input->post('senha'));
					redirect('home', 'refresh');
				} else
					$dados['erro'] = TRUE;				
			}				
		}
		
		$this->load->view('admin/login', $dados);		
	}
}