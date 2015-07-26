<!DOCTYPE html>
<html lang="pt-br">
<head>
	
	<?php include APPPATH . "views/template/config.php"; ?>

	<title>Cadastrar Notícia - Unimed Volta Redonda</title>
	<link href="<?php echo base_url('assets/plugins/uploader/css/style.css')?>" rel="stylesheet" />
	
</head>

<body>

	<!-- Header -->
	<?php include APPPATH . "views/template/header.php"; ?>

	
	<!-- Container -->
	<section  class="container">
	
		<header>
			<h1>Cadastrar Notícia</h1>
		</header>
		
		<article>
			
			<!-- Formulário -->
			<?php
			if(isset($sucesso)){
				echo '<div class="alert alert-success">Registro salvo com sucesso.</div>
					  <a href="'.base_url('admin/noticias').'" class="btn btn-primary">Ver noticias</a>';
			} elseif(isset($erro)){
				echo '<div class="alert alert-danger">Não foi possível realizar a operação. Atualize a página e tente novamente.</div>
					  <a href="'.base_url('admin/noticias/inserir').'" class="btn btn-primary">Voltar</a>';
			} else {
				echo validation_errors();
				
				echo form_open_multipart('admin/noticias/inserir', 'class="row"');

				$time = ($this->input->post('time') != '') ? $this->input->post('time') : time();
				echo form_hidden('time', $time);
			?>

			<div class="col-md-12">

				<!-- Abas -->
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab1" data-toggle="tab">Geral</a></li>                
					<li><a href="#tab2" data-toggle="tab">Enviar imagens</a></li>					
				</ul>
				
				<!-- Conteúdo das abas -->
				<div class="tab-content">

					<!-- Informações gerais -->
					<div class="tab-pane row active" id="tab1">
					
						<div class="col-md-8 col-xs-12">			
						
							<div class="form-group <?php echo set_obg('titulo')?>">
								<label>Titulo</label>
								<input type="text" name="titulo" maxlength="200" class="form-control" value="<?php echo set_value('titulo')?>">
							</div>
							
						</div>
						
						<div class="both"></div>

						<div class="col-md-8 col-xs-12">
						
							<div class="form-group <?php echo set_obg('texto')?>">
								<label>Texto</label>
								<textarea name="texto" class="form-control" rows="12"><?php echo set_value('texto')?></textarea>
							</div>
							
						</div>
						
						<div class="both"></div>

						<div class="col-md-8 col-xs-12">			
						
							<div class="form-group <?php echo set_obg('video')?>">
								<label>Video URL (Youtube)</label>
								<input type="text" name="video" maxlength="255" class="form-control" value="<?php echo set_value('video')?>">
							</div>
							
						</div>

						<div class="both"></div>

						<div class="col-md-4 col-xs-12">			
						
							<div class="form-group <?php echo set_obg('categoria')?>">
								<label>Categoria</label>
								<?php echo form_dropdown('categoria', $this->Model_noticias->getCategoriasOpc(), set_value('categoria'), 'class="form-control"')?>
							</div>
							
						</div>

						<div class="both"></div>					

						<div class="col-md-2 col-xs-6">			
						
							<div class="form-group <?php echo set_obg('visivel_desktop')?>">
								<label>Visível em Desktop</label>
								<?php echo form_dropdown('visivel_desktop', array('1' => 'Sim', '0' => 'Não'), set_value('visivel_desktop'), 'class="form-control"')?>
							</div>
							
						</div>

						<div class="col-md-2 col-xs-6">			
						
							<div class="form-group <?php echo set_obg('visivel_mobile')?>">
								<label>Visível em Mobile</label>
								<?php echo form_dropdown('visivel_mobile', array('1' => 'Sim', '0' => 'Não'), set_value('visivel_mobile'), 'class="form-control"')?>
							</div>
							
						</div>

					</div>

					<!-- Uploader -->
					<div class="tab-pane row" id="tab2">
					
						<div class="col-md-12" id="upload">	
						
							<div id="drop">
								Arraste e solte suas imagens ou clique no botão selecionar
								
								<div class="both"></div>
								
								<img src="<?php echo base_url('assets/plugins/uploader/img/icon-cloud.jpg')?>" alt="Enviar arquivo" />
								
								<a>Selecionar arquivos</a>
								<input type="file" name="upl" multiple />
							</div>
		
							<ul id="lista-imagens">
								<!-- The file uploads will be shown here -->
								<?php if(isset($imagens_upadas_temp)) echo $imagens_upadas_temp?>
							</ul>							
						
						</div>
											
					</div>

				</div>

				<div class="both"></div>
							
				
				<hr />
				
				<input type="submit" name="submit" value="Confimar" class="btn btn-primary" />
				<a href="<?php echo base_url('admin/noticias')?>" class="btn btn-danger">Cancelar</a>
				
				</div>
				
			</form>
			
			<?php }?>
			
		</article>
	
	</section>
		

	<!-- Footer -->
	<?php include APPPATH . "views/template/footer.php"; ?>
	<script type="text/javascript" src="<?php echo base_url('assets/plugins/uploader/js/jquery.knob.js')?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/plugins/uploader/js/jquery.ui.widget.js')?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/plugins/uploader/js/jquery.iframe-transport.js')?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/plugins/uploader/js/jquery.fileupload.js')?>"></script>	
	<script type="text/javascript" src="<?php echo base_url('assets/plugins/uploader/js/script.js')?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/source/cep-search.js')?>"></script>
	
</body>
</html>