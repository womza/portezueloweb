<?php 
/**
 * The main template file.
 *
 */
	get_header();
?>
<?php 
	extract(etheme_get_blog_sidebar());
	$postspage_id = get_option('page_for_posts');
	$content_layout = $blog_layout;
	if($content_layout == 'mosaic') {
		$content_layout = 'grid';
	}
?>

<?php if ($page_heading != 'disable' && ($page_slider == 'no_slider' || $page_slider == '')): ?>
	<div class="page-heading bc-type-<?php echo esc_attr( etheme_get_option('breadcrumb_type') ); ?>">
		<div class="container">
			<div class="row">
				<div class="col-md-12 a-center">
					<h1 class="title"><span><?php echo (!empty($postspage_id)) ? get_the_title($postspage_id) : esc_html_e('Blog', ETHEME_DOMAIN); ?></span></h1>
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
	<div class="page-content sidebar-position-<?php echo esc_attr($position); ?> responsive-sidebar-<?php echo esc_attr($responsive); ?>">
		<div class="row">
			<?php if($position == 'left' || ($responsive == 'top' && $position == 'right')): ?>
				<div class="<?php echo esc_attr( $sidebar_span ); ?> sidebar sidebar-left">
					<?php etheme_get_sidebar($sidebarname); ?>
				</div>
			<?php endif; ?>

			<div class="content <?php echo esc_attr($content_span); ?>">
			
					<div class="<?php if ($content_layout == 'grid'): ?>blog-masonry row<?php endif ?>">
					
						<?php if(have_posts()): while(have_posts()) : the_post(); ?>
	
								<?php get_template_part('content', $content_layout); ?>

						<?php endwhile; ?>

					</div>

				<?php else: ?>

					<h1><?php _e('No posts were found!', ETHEME_DOMAIN) ?></h1>

				<?php endif; ?>
				
				<?php if ($blog_layout == 'grid' && etheme_get_option('ajax_posts_loading')): ?>
				
					<?php add_filter( 'next_posts_link_attributes', 'et_show_more_posts' ); ?>
				
					<div class="articles-nav load-more-posts">
						<div><?php next_posts_link(__('Load More Posts', ETHEME_DOMAIN)); ?></div>
						<div class="clear"></div>
					</div>
					
				<?php else: ?>
					<div class="articles-nav">
						<div class="left"><?php next_posts_link(__('&larr; Older Posts', ETHEME_DOMAIN)); ?></div>
						<div class="right"><?php previous_posts_link(__('Newer Posts &rarr;', ETHEME_DOMAIN)); ?></div>
						<div class="clear"></div>
					</div>
				<?php endif ?>

			</div>

			<?php if($position == 'right' || ($responsive == 'bottom' && $position == 'left')): ?>
				<div class="<?php echo esc_attr($sidebar_span); ?> sidebar sidebar-right">
					<?php etheme_get_sidebar($sidebarname); ?>
				</div>
			<?php endif; ?>
		</div>


	</div>
</div>

	
<?php
	get_footer();
?>