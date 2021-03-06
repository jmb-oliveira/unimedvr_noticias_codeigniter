<!DOCTYPE html>
<html lang="pt-br">
<head> 
	
	<?php include APPPATH . "views/template/config.php"; ?>

	<title>Categorias - Unimed Volta Redonda</title>
	
</head>

<body>

	<!-- Header -->
	<?php include APPPATH . "views/template/header.php"; ?>

	
	<!-- Categoriainer -->
	<section  class="container">
		
		<div id="titulo_principal"> 
			<h1>Categorias</h1>
			<div class="badge" data-toggle="tooltip" data-placement="right" title="Total de registros"><?php echo $total_rows?></div>
		</div>	
			
		<div class="both"></div>
		
		<article>
			
			<!-- Sidebar -->
			<div class="row sidebar">
			
				<!-- Botões -->
				<div class="col-md-7 col-xs-6 pull-left">
					<a href="<?php echo base_url('admin/categorias/inserir')?>" class="btn btn-primary">Inserir</a>
				</div>						
			
			</div>
			
			<div class="both"></div>
			
			<?php
			if($this->session->flashdata('remove-ok') != ''){
				echo '<div class="alert alert-success">Registro removido com sucesso.</div>';
			}

			if($this->session->flashdata('remove-error') != ''){
				echo '<div class="alert alert-danger">Não foi possível remover o registro. Atualize a página e tente novamente.</div>';
			}
			?>

			<?php
			if($lista == ''){
				echo '<div class="alert alert-info">Não há registros cadastrados no momento.</diV>';
			} else {
			?>
				<!-- Grid -->
				<table class="table table-bordered">
					<thead>
						<tr class="table-title">
							<th>Categoria</th>
							<th class="col-md-2 text-center">Visível em Desktop</th>
							<th class="col-md-2 text-center">Visível em Mobile</th>						
							<th class="col-md-1 col-xs-2 text-center">::</th>
						</tr>
					</thead>
					<tbody>
						<?php echo $lista?>
					</tbody>
				</table>			
				
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

	<script type="text/javascript">
		$(function(){
			
			$('.remover').click(function(){
				var id = $(this).parent().attr('data-id-registro');

				bootbox.confirm('Você está prestes a remover um registro.<br/><br/><b>Todas as notícias dessa categoria ficarão invisíveis para os leitores.</b><br/><br/>Confirma operação?', function(r){
					if(r){
						location.href = "<?php echo base_url('admin/categorias/remover')?>/" + id;
					}
				});
			});

		});
	</script>
	
</body>
</html>