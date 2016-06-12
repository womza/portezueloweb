<?php
/**
 * Template Name: Portfolio
 *
 */
 ?>
 
<?php 
	extract(etheme_get_page_sidebar());
?>

<?php 
	get_header();
?>

<?php if ($page_heading != 'disable' && ($page_slider == 'no_slider' || $page_slider == '')): ?>
	<div class="page-heading bc-type-<?php echo esc_attr( etheme_get_option('breadcrumb_type') ); ?>">
		<div class="container">
			<div class="row">
				<div class="col-md-12 a-center">
					<h1 class="title"><span><?php the_title(); ?></span></h1>
					<?php etheme_breadcrumbs(); ?>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>

<?php if($page_slider != 'no_slider' && $page_slider != ''): ?>
	
	<?php echo do_shortcode('[rev_slider_vc alias="'.$page_slider.'"]'); ?>

<?php endif; ?>

<div class="container">
	<div class="page-content sidebar-position-without">
		<div class="row">
			<div class="content col-md-12">
					
					<?php get_etheme_portfolio(); ?>
			</div>
		</div>

	</div>
</div>
	
<?php
	get_footer();
?>