<!DOCTYPE html>
<html lang="pt-br">
	<head>
    
		<title>404 | Página não encontrada</title>
		
		<?php			
			include(APPPATH . "views/template/config.php");
		?>
		
	</head>

	<body>
		
		<div class="container-page-error" style="margin-left:25px;">
			
			<h1>404</h1>
			<p>A página que você procura não foi encontrada.</p>

			<a href="<?php echo base_url('home')?>" title="Voltar para o site" class="btn btn-success">Voltar para o site</a>
		
		</div>

	</body>
</html>