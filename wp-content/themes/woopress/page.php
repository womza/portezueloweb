<?php 
	get_header();
?>

<?php 
	extract(etheme_get_page_sidebar());
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
	<div class="page-heading-slider">
		<?php echo do_shortcode('[rev_slider_vc alias="'.$page_slider.'"]'); ?>
	</div>
<?php endif; ?>

	<div class="container content-page">
		<div class="sidebar-position-<?php echo esc_attr($position); ?> responsive-sidebar-<?php echo esc_attr($responsive); ?>">
			<div class="row">
				<?php if($position == 'left' || ($responsive == 'top' && $position == 'right')): ?>
					<div class="<?php echo esc_attr( $sidebar_span ); ?> sidebar sidebar-left">
						<?php etheme_get_sidebar($sidebarname); ?>
					</div>
				<?php endif; ?>

				<div class="content <?php echo esc_attr($content_span); ?>">
					<?php if(have_posts()): while(have_posts()) : the_post(); ?>
						
						<?php the_content(); ?>

						<div class="post-navigation">
							<?php wp_link_pages(); ?>
						</div>
						
						<?php if ($post->ID != 0 && current_user_can('edit_post', $post->ID)): ?>
							<?php edit_post_link( __('Edit this', ETHEME_DOMAIN), '<p class="edit-link">', '</p>' ); ?>
						<?php endif ?>

					<?php endwhile; else: ?>

						<h3><?php _e('Ninguna página encontrada!', ETHEME_DOMAIN) ?></h3>

					<?php endif; ?>

				</div>

				<?php if($position == 'right' || ($responsive == 'bottom' && $position == 'left')): ?>
					<div class="<?php echo esc_attr($sidebar_span); ?> sidebar sidebar-right">
						<?php etheme_get_sidebar($sidebarname); ?>
					</div>
				<?php endif; ?>
			</div><!-- end row-fluid -->

		</div>
	</div><!-- end container -->

<?php
	get_footer();
?>
