<!DOCTYPE html>
<html lang="pt-br">
<head>
	
	<?php include APPPATH . "views/template/config.php"; ?>

	<title>Categorias - Unimed Volta Redonda</title>

</head>

<body>

	<!-- Header -->
	<?php include APPPATH . "views/template/header.php"; ?>

	
	<!-- Container -->
	<section class="container">
		
		<header>
			<h1>Categorias</h1>
		</header>

		<article>						
		
			<?php
				// Grid
				if($lista == '') {
					echo '<div class="alert alert-info">Não há categorias cadastradas no momento. Por favor, volte mais tarde.</div>';		
				
				} else {
				
					echo $lista;
				?>
									
					
					<!-- Paginação -->
					<hr/>
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