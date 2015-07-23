	<header class="navbar navbar-default navbar-fixed-top hidden-380" role="navigation">
		<div class="container">
		
			<!-- Logotipo -->
			<div id="logotipo" class="pull-left">
				<a href="<?php echo base_url('home')?>"><img src="<?php echo base_url('assets/img/logotipoUnimed.png')?>" alt="Unimed Volta Redonda" title="Unimed Volta Redonda"/></a>
			</div>
			
			<!-- Botão responsivo -->
			<div class="mainmenu">
			
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Menu</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				</div>
				
				<!-- Menu principal -->
				<nav class="collapse navbar-collapse">
					<ul class="nav navbar-nav" id="mainmenu">						
						
						<li><a href="<?php echo base_url('home')?>" class="btn btn-success">Notícias</a></li>
						<li><a href="<?php echo base_url('home')?>" class="btn-collapse">Notícias</a></li>											

						<?php
							if(!$usuario){
								echo '<li><a href="'. base_url('admin/login') .'">Login</a></li>';
							
							} else {

								echo '<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">Cadastros <b class="caret"></b></a>
										<ul class="dropdown-menu dropdown-menu-arrow">
											<li><a href="'. base_url('admin/noticias') .'">Notícias</a></li>
											<li><a href="'. base_url('admin/categorias') .'">Categorias</a></li>';

											if($usuario->acesso == 2){
												echo '<li><a href="'. base_url('admin/usuarios') .'">Usuários</a></li>';
											}

								echo 	'</ul>
									  </li>';

								if($usuario->acesso == 2){
									echo '<li><a href="'. base_url('admin/logs') .'">Logs</a></li>';
								}

								echo '<li><a href="'. base_url('home/sair') .'">Sair</a></li>';
							}
						?>

					</ul>
				</nav>
				
			</div>
		  
		</div>
	</header>