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
		// Multi Upload
		if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0)
		{
			$allowed = array('png', 'jpg');
	
			$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
	
			if(!in_array(strtolower($extension), $allowed)){
				echo '{"status":"error"}';
				exit;
			}
	
			$diretorio = 'uploads/noticias/temp/'.$this->input->post('time');
	
			if(!is_dir($diretorio))
				mkdir($diretorio);
	
			$img = $diretorio.'/'.uniqid().'.'.$extension;
			if(move_uploaded_file($_FILES['upl']['tmp_name'], $img)){
	
				$this->load->library('image_lib');
					
				// Redimensiona imagem original
				$configUpload['source_image']	= $img;
				$configUpload['quality'] = '100%';
				$configUpload['width'] = '960';
				$configUpload['height'] = '680';
				$configUpload['maintain_ratio'] = FALSE;
	
				$this->image_lib->initialize($configUpload);
				$this->image_lib->resize();
	
				// Cria thumb
				$nome_thumb = explode('.', $img);
				$nome_thumb = $nome_thumb[0].'_thumb.'.$nome_thumb[1];
				unset($configUpload);
				$configUpload['source_image'] = $img;
				$configUpload['quality'] = '100%';
				$configUpload['width'] = '170';
				$configUpload['height'] = '170';
				$configUpload['maintain_ratio'] = FALSE;
				$configUpload['create_thumb'] = TRUE;
				$configUpload['new_image'] = $nome_thumb;
					
				$this->image_lib->initialize($configUpload);
				$this->image_lib->resize();			
	
				echo '{"status":"success"}';
				exit;
			}
		}

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
			if($id_noticia = $this->Model_noticias->tryInsertNoticia())
			{
				// Mover imagens temporarias
				$count = 1;			
				foreach(glob('uploads/noticias/temp/'.$this->input->post('time').'/*.{jpg,png}',GLOB_BRACE) as $img){
					$novo_nome_img = uniqid().'.'.end(explode('.', $img));
					if(stripos($img, 'thumb') === FALSE){
						rename($img, 'uploads/noticias/'.$novo_nome_img);
							
						$this->Model_noticias->insertNoticiaImg($id_noticia, $count, $novo_nome_img);
	
						$img = explode('.', $img);
						$img = $img[0].'_thumb.'.$img[1];
						rename($img, 'uploads/noticias/thumb/'.$novo_nome_img);
	
						$count++;
					}
				}

				$this->Model_login->addLog('Publicou a Noticia: '. $this->input->post('titulo'));
				$dados['sucesso'] = TRUE;
			} else {
				$dados['erro'] = TRUE;
			}
		} else {
	
			// Verifica se usuario upou imagens antes de dar erro de validacao para reexibir na listagem do uploader
			$dados['imagens_upadas_temp'] = '';
	
			foreach(glob('uploads/noticias/temp/'.$this->input->post('time').'/*.{jpg,png}',GLOB_BRACE) as $img){
				if(stripos($img, 'thumb') === FALSE)
					$dados['imagens_upadas_temp'] .= '<li class=""><div style="display:inline;width:28px;height:28px;"><canvas width="28" height="28px"></canvas><input type="text" value="100" data-width="28" data-height="28" data-fgcolor="#22927A" data-readonly="1" data-bgcolor="#3DFED3" readonly="readonly" style="width: 18px; height: 9px; position: absolute; vertical-align: middle; margin-top: 9px; margin-left: -23px; border: 0px; font-weight: bold; font-style: normal; font-variant: normal; font-stretch: normal; font-size: 5px; line-height: normal; font-family: Arial; text-align: center; color: rgb(34, 146, 122); padding: 0px; -webkit-appearance: none; background: none;"></div><p>'.end(explode('/', $img)).'<i>Concluído</i></p><span></span></li>';
			}
		}
		
		$dados['usuario'] = $this->_usuario;
		$this->load->view('admin/noticias_inserir', $dados);
	}
	
	function editar($id_noticia = NULL)
	{
		if(!$dados['noticia'] = $this->Model_noticias->getNoticia((int)$id_noticia))
			show_404();
		
		// Multi Upload
		if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0)
		{
			$allowed = array('png', 'jpg');
	
			$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
	
			if(!in_array(strtolower($extension), $allowed)){
				echo '{"status":"error"}';
				exit;
			}
	
			$diretorio = 'uploads/noticias/temp/'.$this->input->post('time');
	
			if(!is_dir($diretorio))
				mkdir($diretorio);
	
			$img = $diretorio.'/'.uniqid().'.'.$extension;
			if(move_uploaded_file($_FILES['upl']['tmp_name'], $img)){
	
				$this->load->library('image_lib');
					
				// Redimensiona imagem original
				$configUpload['source_image']	= $img;
				$configUpload['quality'] = '100%';
				$configUpload['width'] = '960';
				$configUpload['height'] = '680';
				$configUpload['maintain_ratio'] = FALSE;
	
				$this->image_lib->initialize($configUpload);
				$this->image_lib->resize();
	
				// Cria thumb
				$nome_thumb = explode('.', $img);
				$nome_thumb = $nome_thumb[0].'_thumb.'.$nome_thumb[1];
				unset($configUpload);
				$configUpload['source_image'] = $img;
				$configUpload['quality'] = '100%';
				$configUpload['width'] = '170';
				$configUpload['height'] = '170';
				$configUpload['maintain_ratio'] = FALSE;
				$configUpload['create_thumb'] = TRUE;
				$configUpload['new_image'] = $nome_thumb;
					
				$this->image_lib->initialize($configUpload);
				$this->image_lib->resize();			
	
				echo '{"status":"success"}';
				exit;
			}
		}

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
			if($this->Model_noticias->tryUpdateNoticia($id_noticia)){

				// Mover imagens temporarias
				$count = count($this->Model_noticias->getImagens($id_noticia)) + 1;
				foreach(glob('uploads/noticias/temp/'.$this->input->post('time').'/*.{jpg,png}',GLOB_BRACE) as $img){
					$novo_nome_img = uniqid().'.'.end(explode('.', $img));
					if(stripos($img, 'thumb') === FALSE){
						rename($img, 'uploads/noticias/'.$novo_nome_img);
							
						$this->Model_noticias->insertNoticiaImg($id_noticia, $count, $novo_nome_img);
	
						$img = explode('.', $img);
						$img = $img[0].'_thumb.'.$img[1];
						rename($img, 'uploads/noticias/thumb/'.$novo_nome_img);
	
						$count++;
					}
				}

				$this->Model_login->addLog('Alterou a Noticia id '. $id_noticia);
				$dados['sucesso'] = TRUE;
				$dados['noticia'] = $this->Model_noticias->getNoticia($id_noticia); // recarregar dados
			} else {
				$dados['erro'] = TRUE;	
			}			
		} else {
	
			// Verifica se usuario upou imagens antes de dar erro de validacao para reexibir na listagem do uploader
			$dados['imagens_upadas_temp'] = '';
	
			foreach(glob('uploads/noticias/temp/'.$this->input->post('time').'/*.{jpg,png}',GLOB_BRACE) as $img){
				if(stripos($img, 'thumb') === FALSE)
					$dados['imagens_upadas_temp'] .= '<li class=""><div style="display:inline;width:28px;height:28px;"><canvas width="28" height="28px"></canvas><input type="text" value="100" data-width="28" data-height="28" data-fgcolor="#22927A" data-readonly="1" data-bgcolor="#3DFED3" readonly="readonly" style="width: 18px; height: 9px; position: absolute; vertical-align: middle; margin-top: 9px; margin-left: -23px; border: 0px; font-weight: bold; font-style: normal; font-variant: normal; font-stretch: normal; font-size: 5px; line-height: normal; font-family: Arial; text-align: center; color: rgb(34, 146, 122); padding: 0px; -webkit-appearance: none; background: none;"></div><p>'.end(explode('/', $img)).'<i>Concluído</i></p><span></span></li>';
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

	/** AJAX FUNCTIONS ------------------------------------------------- */	
	function ajaxGetGaleriaImages($id_noticia)
	{
		// Imagens da galeria
		$galeria = '';
		$i = 1;
		$imgs = $this->Model_noticias->getImagens($id_noticia);
		$total_imgs = count($imgs);
		
		foreach($imgs as $img){
			$galeria .= '<div class="col-sm-3 col-md-3">
								<div class="thumbnail">
									<img src="'.base_url('uploads/noticias/'.$img->imagem).'">
			
									<div class="caption">
			
										<p class="pull-right">';
					
			// Mostrar mover esquerda/direita somente nas imagens intermediarias
			if($i <= $total_imgs && $i > 1){
				$galeria .= '
												<a href="#" data-src="'.$img->imagem.'" class="btn btn-default btn-xs btMoveLeft" role="button" data-toggle="tooltip" data-placement="bottom" title="Alterar ordem de visualização"><span class="glyphicon glyphicon-chevron-left"></span></a>
												';
			}
			if($i >= 1 && $i < $total_imgs){
				$galeria .= '
												<a href="#" data-src="'.$img->imagem.'" class="btn btn-default btn-xs btMoveRight" role="button" data-toggle="tooltip" data-placement="bottom" title="Alterar ordem de visualização"><span class="glyphicon glyphicon-chevron-right"></span></a>
												';
			}
						
			$galeria .= '
												&nbsp;<a href="#" data-src="'.$img->imagem.'" class="btn btn-danger btn-xs btRemove" role="button" data-toggle="tooltip" data-placement="bottom" title="Remover"><span class="glyphicon glyphicon-remove"></span></a>
												
										</p>
	
										<div class="both"></div>
									</div>
			
								</div>
							</div>';
			$i++;
		}
	
		echo(json_encode($galeria));
	}
	
	function ajaxRemoveImage($id_noticia, $img)
	{
		$extensao = end(explode('.', $img));
		$img = str_replace(array('.', '/'), array('', ''), substr($img, 0, -3)) . '.' . $extensao;		
		$pasta = './uploads/noticias/';
		$pasta_thumb = './uploads/noticias/thumb/';
		
		unlink($pasta . $img);
		unlink($pasta_thumb . $img);
		$this->Model_noticias->deleteImagem($img);
	
		// Renomeia arquivos para nova sequencia
		$count = 1;
		foreach($this->Model_noticias->getImagens($id_noticia) as $img){
			$this->Model_noticias->updateOrdemImagem($img->id_imagem, $count);
			$count++;
		}
	
		echo(json_encode('sucesso'));
	}
	
	function ajaxMoveImage($id_noticia, $img, $direcao)
	{
		$img = $this->Model_noticias->getImagemInfoByNome($img);
	
		$this->Model_noticias->moveImagem($id_noticia, $img->id_imagem, $img->ordem, $direcao);
	
		echo(json_encode('sucesso'));
	}
}