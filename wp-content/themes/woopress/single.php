<?php 
	get_header();
?>

<?php 

    extract(etheme_get_page_sidebar());
    $blog_slider = etheme_get_option('blog_slider');
    $postspage_id = get_option('page_for_posts');
    $post_format = get_post_format();
    
    $post_content = $post->post_content;
    preg_match('/\[gallery.*ids=.(.*).\]/', $post_content, $ids);
    if(!empty($ids)) {
	    $attach_ids = explode(",", $ids[1]);
	    $content =  str_replace($ids[0], "", $post_content);
	    $filtered_content = apply_filters( 'the_content', $content);
    }
    
    $slider_id = rand(100,10000);
?>


<?php if ($page_heading != 'disable' && ($page_slider == 'no_slider' || $page_slider == '')): ?>
	<div class="page-heading bc-type-<?php echo esc_attr( etheme_get_option('breadcrumb_type') ); ?>">
		<div class="container">
			<div class="row">
				<div class="col-md-12 a-center">
				<h1 class="title"><span><?php echo get_the_title($postspage_id); ?></span></h1>
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
				<?php if(have_posts()): while(have_posts()) : the_post(); ?>
				
					<article <?php post_class('blog-post post-single'); ?> id="post-<?php the_ID(); ?>" >
					
						<?php 
							$width = etheme_get_option('blog_page_image_width');
							$height = etheme_get_option('blog_page_image_height');
							$crop = etheme_get_option('blog_page_image_cropping');
						?>
						

						<?php if($post_format == 'quote' || $post_format == 'video'): ?>
					    
					            <?php the_content(); ?>
					        
						<?php elseif($post_format == 'gallery'): ?>
					            <?php if(count($attach_ids) > 0): ?>
					                <div class="post-gallery-slider slider_id-<?php echo $slider_id; ?>">
					                    <?php foreach($attach_ids as $attach_id): ?>
					                        <div>
					                            <?php echo wp_get_attachment_image($attach_id, 'large'); ?>
					                        </div>
					                    <?php endforeach; ?>
					                </div>
					    
					                <script type="text/javascript">
					                    jQuery('.slider_id-<?php echo $slider_id; ?>').owlCarousel({
					                        items:1,
					                        navigation: true,
					                        lazyLoad: false,
					                        rewindNav: false,
					                        addClassActive: true,
					                        singleItem : true,
					                        autoHeight : true,
					                        itemsCustom: [1600, 1]
					                    });
					                </script>
					            <?php endif; ?>
					    
						<?php elseif(has_post_thumbnail()): ?>
							<div class="wp-picture">
								<?php the_post_thumbnail('large'); ?>
								<div class="zoom">
									<div class="btn_group">
										<a href="<?php echo etheme_get_image(); ?>" class="btn btn-black xmedium-btn" rel="pphoto"><span><?php _e('View large', ETHEME_DOMAIN); ?></span></a>
									</div>
									<i class="bg"></i>
								</div>
							</div>
						<?php endif; ?>

                        <?php if($post_format != 'quote'): ?>
                            <h6 class="active"><?php the_category(',&nbsp;') ?></h6>

                            <h2><?php the_title(); ?></h2>

                        	<?php if(etheme_get_option('blog_byline')): ?>
                                <div class="meta-post">
                                        <?php _e('Posted on', ETHEME_DOMAIN) ?>
                                        <?php the_time(get_option('date_format')); ?> 
                                        <?php _e('at', ETHEME_DOMAIN) ?> 
                                        <?php the_time(get_option('time_format')); ?>
                                        <?php _e('by', ETHEME_DOMAIN);?> <?php the_author_posts_link(); ?>
                                        <?php // Display Comments 

                                                if(comments_open() && !post_password_required()) {
                                                        echo ' / ';
                                                        comments_popup_link('0', '1 Comment', '% Comments', 'post-comments-count');
                                                }

                                         ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if($post_format != 'quote' && $post_format != 'video' && $post_format != 'gallery'): ?>
                            <div class="content-article">
                                    <?php the_content(); ?>
                            </div>
                        <?php elseif($post_format == 'gallery'): ?>
                            <div class="content-article">
                                <?php echo $filtered_content; ?>
                            </div>
                        <?php endif; ?>
					
						<?php if(etheme_get_option('post_share')): ?>
							<div class="share-post">
								<?php echo do_shortcode('[share title="'.__('Compartir', ETHEME_DOMAIN).'"]'); ?>
							</div>
						<?php endif; ?>
						
						<?php if(etheme_get_option('posts_links')): ?>
							<?php etheme_project_links(array()); ?>
						<?php endif; ?>
						
						
						<?php if(etheme_get_option('about_author')): ?>
							<h4 class="title-alt"><span><?php _e('Autor', ETHEME_DOMAIN); ?></span></h4>
							
							<div class="author-info">
								<a class="pull-left" href="#">
									<?php echo get_avatar( get_the_author_meta('email') , 90 ); ?>
								</a>
								<div class="media-body">
									<h4 class="media-heading"><?php the_author_link(); ?></h4>
									<?php echo get_the_author_meta('description'); ?>
								</div>
							</div>
						<?php endif; ?>
						
						<?php if(etheme_get_option('post_related')): ?>
							<div class="related-posts">
								<?php et_get_related_posts(); ?>
							</div>
						<?php endif; ?>
					
					</article>


				<?php endwhile; else: ?>

					<h1><?php _e('No posts were found!', ETHEME_DOMAIN) ?></h1>

				<?php endif; ?>

				<?php comments_template('', true); ?>

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