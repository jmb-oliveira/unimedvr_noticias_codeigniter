<!DOCTYPE html>
<html lang="pt-br">
<head>
	
	<!-- Navegador obsoleto para o IE < 9 -->
	<!--[IF lt IE 9]> <script type="text/javascript">location="http://browsehappy.com/"</script> <![endif]-->
		
	<!-- Meta
	================================================== -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Link
	================================================== -->
	<link href="<?php echo base_url('assets/img/favicon.png')?>" rel="icon" sizes="64x64">
	<link href="<?php echo base_url('assets/img/favicon.ico')?>" rel="shortcut icon" type="image/x-icon" />
	<link href="<?php echo base_url('assets/css/minify/bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/source/login.css')?>" rel="stylesheet">

	<title>Login - Unimed Volta Redonda</title>
	
</head>

<body>

	<section class="container">
	
		<!-- Logotipo -->
		<div id="logotipo">
			<img src="<?php echo base_url('assets/img/logotipoUnimed.png')?>" alt="Unimed Volta Redonda" />
		</div>
		
		<?php
		if(isset($erro))
			echo '<div class="alert alert-danger"> <strong>Ops!</strong> Usuário ou senha inválidos. </div>';
		?>
		
		<!-- Formulário -->	
		<?php echo form_open('admin/login', 'class="form-signin"')?>
			<h2 class="form-signin-heading">Login</h2>			
			<p>Acesso de autores e administradores.</p>						
			
			<div class="form-group">
			  <input type="text" name="login" class="form-control" placeholder="Usuário" autofocus value="admin">
			</div>			
			
			<div class="form-group">
				<input type="password" name="senha" class="form-control" placeholder="Senha" value="admin">		
			</div>	
			
			<input type="submit" name="submit" value="Entrar" class="btn btn-lg btn-success btn-block"/>
			
		</form>

	</section>

</body>
</html>