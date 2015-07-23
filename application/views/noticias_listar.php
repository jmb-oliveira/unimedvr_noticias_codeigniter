<!DOCTYPE html>
<html lang="pt-br">
<head>
	
	<?php include APPPATH . "views/template/config.php"; ?>

	<title>Notícias - Unimed Volta Redonda</title>

</head>

<body>

	<!-- Header -->
	<?php include APPPATH . "views/template/header.php"; ?>

	
	<!-- Container -->
	<section class="container">
		
		<header>
			<h1>Notícias</h1>
		</header>

		<article>
		
			<!-- Sidebar -->
			<div class="row sidebar">
								
				<!-- Campo de busca -->
				<div class="col-md-6 col-xs-7 col-lg-5">
					<?php echo form_open('home/noticias')?>
						<div class="input-group">
						  <span class="input-group-addon"> <span class="glyphicon glyphicon-search"></span> </span>
						  <input type="text" name="busca" class="form-control" placeholder="Digite o que você procura e tecle enter">
						</div>		
					</form>
				</div>
								
			</div>
			
			<div class="both"></div>			
		
			<?php
				// Grid
				if($lista == '' && isset($busca)) {
					echo '<div class="alert alert-info">Sua busca não retornou resultados.</div>';
				
				} elseif($lista == '') {
					echo '<div class="alert alert-info">Não há notícias cadastradas no momento. Por favor, volte mais tarde.</div>';
				
				} else {
				
					echo $lista;
				?>
									
					
					<!-- Paginação -->
					<div class="text-center col-md-12">
						<ul class="pagination">
							<?php echo $html_paginacao?>
						</ul>			
					</div>
			
			<?php }?>
			
		</article>
		
	</section>		

	<!-- Footer -->
	<?php include APPPATH . "views/template/footer.php"; ?>
	
</body>
</html>