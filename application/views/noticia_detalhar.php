<!DOCTYPE html>
<html lang="pt-br">
<head>
	
	<?php include APPPATH . "views/template/config.php"; ?>

	<title><?php echo $noticia->titulo?> - Unimed Volta Redonda</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/fancybox/jquery.fancybox.css?v=2.1.5')?>" type="text/css" media="screen" />

</head>

<body>

	<!-- Header -->
	<?php include APPPATH . "views/template/header.php"; ?>

	
	<!-- Container -->
	<section class="container">
		
		<header>
			<h2><?php echo $noticia->titulo?></h2>
			<p class="news-datetime">Publicada em <?php echo date('d/m/Y \à\s H:i', $noticia->publicada_em)?></p>
		</header>

		<article>		
		
			<p><?php echo nl2br($noticia->texto)?></p>

			<?php
				// Galeria de imagens
				if($imagens != ''){
					echo '<hr/><div class="gallery"><h3>Galeria de Imagens</h3>'. $imagens .'</div><div class="both"></div>';
				}

				// Video
				if($embed_video != ''){
					echo '<hr/><h3>Video</h3>'. $embed_video;
				}
			?>

		</article>
		
	</section>		

	<!-- Footer -->
	<?php include APPPATH . "views/template/footer.php"; ?>
	<script type="text/javascript" src="<?php echo base_url('assets/plugins/fancybox/jquery.fancybox.pack.js?v=2.1.5')?>"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".fancybox").fancybox({
				openEffect	: 'none',
				closeEffect	: 'none'
			});
		});		
	</script>

</body>
</html>