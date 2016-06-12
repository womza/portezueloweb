<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

extract(etheme_get_single_product_sidebar());

?>

<?php
	
	$class = 'tabs-'.etheme_get_option('tabs_location');
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class($class); ?>>


    
    <div class="row">
            
		<?php if($single_product_sidebar && ($position == 'left')): ?>
			<div class="col-lg-3 col-sm-12 sidebar sidebar-left single-product-sidebar">
				<?php et_product_brand_image(); ?>
				<?php if(etheme_get_option('upsell_location') == 'sidebar') woocommerce_upsell_display(); ?>
				<?php dynamic_sidebar('single-sidebar'); ?>
			</div>
		<?php endif; ?>
		
        <div class="col-md-<?php echo ($position == 'without') ? 12: 9; ?> col-sm-12 product-content sidebar-position-<?php echo esc_attr($position); ?> responsive-sidebar-<?php echo esc_attr($responsive); ?>">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 product-images">
                	<?php
                		/**
                		 * woocommerce_before_single_product_summary hook
                		 *
                		 * @hooked woocommerce_show_product_sale_flash - 10
                		 * @hooked woocommerce_show_product_images - 20
                		 */
                		do_action( 'woocommerce_before_single_product_summary' );
                	?>
                </div><!-- Product images/ END -->
                
                <div class="col-lg-6 col-md-6 col-sm-12 product-information <?php if(etheme_get_option('ajax_addtocart')): ?>ajax-enabled<?php endif; ?>">
                    <div class="product-navigation clearfix">
						<h4 class="meta-title"><span><?php _e('Información básica del Producto', ETHEME_DOMAIN); ?></span></h4>
						<div class="product-arrows pull-right">
                            <?php previous_post_link_product(); ?>
                            <?php next_post_link_product(); ?>
						</div>
					</div>
                    
                    
            		<?php
            			/**
            			 * woocommerce_single_product_summary hook
            			 *
            			 * @hooked woocommerce_template_single_title - 5 
            			 * @hooked woocommerce_template_single_rating - 10
            			 * @hooked woocommerce_template_single_price - 10
            			 * @hooked woocommerce_template_single_excerpt - 20
            			 * @hooked woocommerce_template_single_add_to_cart - 30
            			 * @hooked woocommerce_template_single_meta - 40
            			 * @hooked woocommerce_template_single_sharing - 50
            			 */
            			do_action( 'woocommerce_single_product_summary' );
            		?>
        	
            		<?php if(etheme_get_option('share_icons')) echo do_shortcode('[share text="'.get_the_title().'"]'); ?>
                    
                </div><!-- Product information/ END -->
            </div>
            
        	<?php
        		/**
        		 * woocommerce_after_single_product_summary hook
        		 *
        		 * @hooked woocommerce_output_product_data_tabs - 10
        		 * @hooked woocommerce_output_related_products - 20 [REMOVED in woo.php]
        		 */
        		 if(etheme_get_option('tabs_location') == 'after_content') {
	        		 do_action( 'woocommerce_after_single_product_summary' );
        		 }
        	?>
            
        </div> <!-- CONTENT/ END -->
        

		<?php if($single_product_sidebar && ($position == 'right')): ?>
			<div class="col-md-3 col-sm-12 sidebar sidebar-right single-product-sidebar">
				<?php et_product_brand_image(); ?>
				<?php if(etheme_get_option('upsell_location') == 'sidebar') woocommerce_upsell_display(); ?>
				<?php dynamic_sidebar('single-sidebar'); ?>
			</div>
		<?php endif; ?>
    </div>
    
    <?php if(etheme_get_option('upsell_location') == 'after_content') woocommerce_upsell_display(); ?>
    <?php
		if(etheme_get_custom_field('additional_block') != '') {
			echo '<div class="product-extra-content">';
				et_show_block(etheme_get_custom_field('additional_block'));
			echo '</div>';
		}     
    ?>
    <?php if(etheme_get_option('show_related')) woocommerce_output_related_products(); ?>


	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
