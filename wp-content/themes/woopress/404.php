<?php 
	get_header();
?>

<div class="container">
	<div class="page-content page-404">
		<div class="row">
			<div class="col-md-12">
				<h1 class="largest">404</h1>
				<h1><?php _e('Oops! Página no encontrada', ETHEME_DOMAIN) ?></h1>
				<hr class="horizontal-break">
				<p><?php _e('Disculpa, pero la página que buscas no fue encontrada. Asegúrate de que hayas escrito correctamente la URL.', ETHEME_DOMAIN) ?> </p>
				<a href="<?php echo home_url(); ?>" class="button medium"><?php _e('Ir a la página de Inicio', ETHEME_DOMAIN); ?></a>
			</div>
		</div>


	</div>
</div>

	
<?php
	get_footer();
?>