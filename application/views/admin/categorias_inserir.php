<!DOCTYPE html>
<html lang="pt-br">
<head>
	
	<?php include APPPATH . "views/template/config.php"; ?>

	<title>Cadastrar Categoria - Unimed Volta Redonda</title>
	
</head>

<body>

	<!-- Header -->
	<?php include APPPATH . "views/template/header.php"; ?>

	
	<!-- Container -->
	<section  class="container">
	
		<header>
			<h1>Cadastrar Categoria</h1>
		</header>
		
		<article>
			
			<!-- Formulário -->
			<?php
			if(isset($sucesso)){
				echo '<div class="alert alert-success">Registro salvo com sucesso.</div>
					  <a href="'.base_url('admin/categorias').'" class="btn btn-primary">Ver categorias</a>';
			} elseif(isset($erro)){
				echo '<div class="alert alert-danger">Não foi possível realizar a operação. Atualize a página e tente novamente.</div>
					  <a href="'.base_url('admin/categorias/inserir').'" class="btn btn-primary">Voltar</a>';
			} else {
				echo validation_errors();
				
				echo form_open('admin/categorias/inserir', 'class="row"');
			?>
				
				<div class="col-md-4 col-xs-12">			
				
					<div class="form-group <?php echo set_obg('categoria')?>">
						<label>Categoria</label>
						<input type="text" name="categoria" maxlength="60" class="form-control" value="<?php echo set_value('categoria')?>">
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

				<div class="both"></div>
		
				<div class="col-md-12">			
				
					<hr />
					
					<input type="submit" name="submit" value="Confimar" class="btn btn-primary" />
					<a href="<?php echo base_url('admin/categorias')?>" class="btn btn-danger">Cancelar</a>
				
				</div>
				
			</form>
			
			<?php }?>
			
		</article>
	
	</section>
		

	<!-- Footer -->
	<?php include APPPATH . "views/template/footer.php"; ?>
	<script src="<?php echo base_url('assets/js/minify/password-validation.js')?>"></script>
	
</body>
</html>