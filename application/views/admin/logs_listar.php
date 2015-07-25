<!DOCTYPE html>
<html lang="pt-br">
<head> 
	
	<?php include APPPATH . "views/template/config.php"; ?>

	<title>Logs - Unimed Volta Redonda</title>
	
</head>

<body>

	<!-- Header -->
	<?php include APPPATH . "views/template/header.php"; ?>

	
	<!-- Categoriainer -->
	<section  class="container">
		
		<div id="titulo_principal"> 
			<h1>Logs</h1>
			<div class="badge" data-toggle="tooltip" data-placement="right" title="Total de registros"><?php echo $total_rows?></div>
		</div>	
			
		<div class="both"></div>
		
		<article>

			<!-- Grid -->
			<table class="table table-bordered">
				<thead>
					<tr class="table-title">
						<th class="col-md-2 col-xs-3 text-center">Data</th>
						<th class="col-md-2 col-xs-3 text-center">Usuário</th>
						<th>Ação</th>						
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
			
		</article>
	
	</section>
		

	<!-- Footer -->
	<?php include APPPATH . "views/template/footer.php"; ?>
	
</body>
</html>