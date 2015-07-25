<!DOCTYPE html>
<html lang="pt-br">
<head>
	
	<?php include APPPATH . "views/template/config.php"; ?>

	<title>Cadastrar Conta de Usuário - Unimed Volta Redonda</title>
	
</head>

<body>

	<!-- Header -->
	<?php include APPPATH . "views/template/header.php"; ?>

	
	<!-- Container -->
	<section  class="container">
	
		<header>
			<h1>Cadastrar Conta de Usuário</h1>
		</header>
		
		<article>
			
			<!-- Formulário -->
			<?php
			if(isset($sucesso)){
				echo '<div class="alert alert-success">Registro salvo com sucesso.</div>
					  <a href="'.base_url('admin/contas').'" class="btn btn-primary">Ver contas</a>';
			} elseif(isset($erro)){
				echo '<div class="alert alert-danger">Não foi possível realizar a operação. Atualize a página e tente novamente.</div>
					  <a href="'.base_url('admin/contas/inserir').'" class="btn btn-primary">Voltar</a>';
			} else {
				echo validation_errors();
				
				echo form_open('admin/contas/inserir', 'class="row"');
			?>
				
				<div class="col-md-4 col-xs-12">			
				
					<div class="form-group <?php echo set_obg('nome')?>">
						<label>Nome</label>
						<input type="text" name="nome" maxlength="60" class="form-control" value="<?php echo set_value('nome')?>">
					</div>
					
				</div>
				
				<div class="both"></div>
		
				<div class="col-md-4 col-xs-12">			
				
					<div class="form-group <?php echo set_obg('email')?>">
						<label>E-mail</label>
						<input type="text" name="email" maxlength="255" class="form-control" value="<?php echo set_value('email')?>">
					</div>
					
				</div>
				
				<div class="both"></div>
		
				<div class="col-md-2 col-xs-6">			
				
					<div class="form-group <?php echo set_obg('usuario')?>">
						<label>Usuário</label>
						<input type="text" name="usuario" maxlength="60" class="form-control" value="<?php echo set_value('usuario')?>">
					</div>
					
				</div>

				<div class="col-md-2 col-xs-6">			
				
					<div class="form-group <?php echo set_obg('acesso')?>">
						<label>Nível de Acesso</label>
						<?php echo form_dropdown('acesso', array('' => '', '1' => 'Autor', '2' => 'Administrador'), set_value('acesso'), 'class="form-control"')?>
					</div>
					
				</div>					
				
				<div class="both"></div>
				
				<div class="col-md-2 col-xs-6">			
				
					<div class="form-group <?php echo set_obg('senha')?>">
						<label>Senha</label>
						<input type="password" name="senha" maxlength="15" id="password1" class="form-control">
					</div>
					
				</div>
				
				<div class="col-md-2 col-xs-6">			
				
					<div class="form-group <?php echo set_obg('confirmar_senha')?>">
						<label>Confirmar senha</label>
						<input type="password" name="confirmar_senha" maxlength="15" id="password2" class="form-control">
					</div>
				</div>
				
				<div class="both"></div>
				
				<div class="col-md-4">			
					<div id="pass-info"></div>
				</div>

				<div class="both"></div>

				<div class="col-md-2 col-xs-6">			
				
					<div class="form-group <?php echo set_obg('habilitada')?>">
						<label>Conta Habilitada</label>
						<?php echo form_dropdown('habilitada', array('' => '', '1' => 'Sim', '0' => 'Não'), set_value('habilitada'), 'class="form-control"')?>
					</div>
					
				</div>

				<div class="both"></div>
		
				<div class="col-md-12">			
				
					<hr />
					
					<input type="submit" name="submit" value="Confimar" class="btn btn-primary" />
					<a href="<?php echo base_url('admin/contas')?>" class="btn btn-danger">Cancelar</a>
				
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