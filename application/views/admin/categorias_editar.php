<!DOCTYPE html>
<html lang="pt-br">
<head>
	
	<?php include APPPATH . "views/template/config.php"; ?>

	<title>Editar Categoria - Unimed Volta Redonda</title>
	
</head>

<body>

	<!-- Header -->
	<?php include APPPATH . "views/template/header.php"; ?>

	
	<!-- Container -->
	<section  class="container">
	
		<header>
			<h1>Editar Categoria</h1>
		</header>
		
		<article>
			
			<!-- Formulário -->
			<?php
			if(isset($erro)){
				echo '<div class="alert alert-danger">Não foi possível realizar a operação. Atualize a página e tente novamente.</div>';
			}

			if(isset($sucesso)){
				echo '<div class="alert alert-success">Registro salvo com sucesso.</div>';
			}

			echo validation_errors();

			echo form_open('admin/categorias/editar/'.$categoria->id_categoria, 'class="row"');
			?>
			
				<div class="col-md-4 col-xs-12">			
				
					<div class="form-group <?php echo set_obg('categoria')?>">
						<label>Categoria</label>
						<input type="text" name="categoria" maxlength="60" class="form-control" value="<?php echo set_value('categoria', $categoria->dcategoria)?>">
					</div>
					
				</div>
				
				<div class="both"></div>

				<div class="col-md-2 col-xs-6">			
				
					<div class="form-group <?php echo set_obg('visivel_desktop')?>">
						<label>Visível em Desktop</label>
						<?php echo form_dropdown('visivel_desktop', array('1' => 'Sim', '0' => 'Não'), set_value('visivel_desktop', $categoria->visivel_desktop), 'class="form-control"')?>
					</div>
					
				</div>

				<div class="col-md-2 col-xs-6">			
				
					<div class="form-group <?php echo set_obg('visivel_mobile')?>">
						<label>Visível em Mobile</label>
						<?php echo form_dropdown('visivel_mobile', array('1' => 'Sim', '0' => 'Não'), set_value('visivel_mobile', $categoria->visivel_mobile), 'class="form-control"')?>
					</div>
					
				</div>

				<div class="both"></div>
		
				<div class="col-md-12">			
				
					<hr />
					
					<input type="submit" name="submit" value="Confimar" class="btn btn-primary" />
					<a href="<?php echo base_url('admin/categorias')?>" class="btn btn-danger">Cancelar</a>
				
				</div>
				
			</form>
			
		</article>
	
	</section>
		

	<!-- Footer -->
	<?php include APPPATH . "views/template/footer.php"; ?>	
	
</body>
</html>