<?php
$product_item = isset($product_item) ? $product_item : 'inner';
$columns = isset($columns) ? $columns : 4;
$rows_count = isset($rows) ? $rows : 1;

$screen_desktop          =      isset($screen_desktop) ? $screen_desktop : 4;
$screen_desktopsmall     =      isset($screen_desktopsmall) ? $screen_desktopsmall : 3;
$screen_tablet           =      isset($screen_tablet) ? $screen_tablet : 3;
$screen_mobile           =      isset($screen_mobile) ? $screen_mobile : 1;

$loop_type          	 =      isset($loop_type) ? $loop_type : '';
$auto_type          	 =      isset($auto_type) ? $auto_type : '';
$autospeed_type          =      isset($autospeed_type) ? $autospeed_type : '';
$disable_mobile          =      isset($disable_mobile) ? $disable_mobile : '';

$nav_type 		= ($nav_type == 'yes') ? 'true' : 'false';
$pagi_type 		= ($pagi_type == 'yes') ? 'true' : 'false';
$loop_type 		= ($loop_type == 'yes') ? 'true' : 'false';
$auto_type 		= ($auto_type == 'yes') ? 'true' : 'false';
$disable_mobile = ($disable_mobile == 'yes') ? 'true' : 'false';
?>
<div class="owl-carousel products" data-navleft="<?php echo greenmart_get_icon('icon_owl_left'); ?>" data-navright="<?php echo greenmart_get_icon('icon_owl_right'); ?>" data-items="<?php echo esc_attr($columns); ?>" data-large="<?php echo esc_attr($screen_desktop);?>" data-medium="<?php echo esc_attr($screen_desktopsmall); ?>" data-smallmedium="<?php echo esc_attr($screen_tablet); ?>" data-extrasmall="<?php echo esc_attr($screen_mobile); ?>" data-carousel="owl" data-pagination="<?php echo esc_attr( $pagi_type ); ?>" data-nav="<?php echo esc_attr( $nav_type ); ?>"  data-loop="<?php echo esc_attr( $loop_type ); ?>" data-auto="<?php echo esc_attr( $auto_type ); ?>" data-autospeed="<?php echo esc_attr( $autospeed_type )?>"  data-uncarouselmobile="<?php echo esc_attr( $disable_mobile ); ?>">
    <?php $count = 0; while ( $loop->have_posts() ): $loop->the_post(); global $product;
		
		// Extra post classes
		$classes = array('product-block', 'grid', 'vertical');

		if($count%$rows_count == 0){ ?>
		<div class="item">
		<?php } ?>
	
        
            <div <?php wc_product_class( $classes, $product ); ?>>
				<div class="product-content">
						<div class="block-inner">
							<figure class="image">
								
								<a title="<?php the_title_attribute(); ?>" href="<?php echo the_permalink(); ?>" class="product-image">
									<?php
										/**
										* woocommerce_before_shop_loop_item_title hook
										*
										* @hooked woocommerce_show_product_loop_sale_flash - 10
										* @hooked woocommerce_template_loop_product_thumbnail - 10
										*/
										remove_action('woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash', 10);
										do_action( 'woocommerce_before_shop_loop_item_title' );
									?>
								</a>
							</figure>
							<div class="groups-button clearfix">
								<?php if (class_exists('YITH_WCQV_Frontend')) { ?>
									<div class="yith-wcqv-button">
									<a href="#" title="<?php esc_attr_e('Quick view', 'greenmart'); ?>"  data-product_id="<?php echo esc_attr($product->get_id()); ?>">
										<i class="<?php echo greenmart_get_icon('icon_quick_view'); ?>"> </i>
									</a>
								</div>
								<?php } ?>	
								
								<?php if( class_exists( 'YITH_Woocompare' ) ) { ?>
									<?php
										$action_add = 'yith-woocompare-add-product';
										$url_args = array(
											'action' => $action_add,
											'id' => $product->get_id()
										);
									?>
									<div class="yith-compare">
										<a href="<?php echo wp_nonce_url( add_query_arg( $url_args ), $action_add ); ?>" title="<?php esc_attr_e('Compare', 'greenmart'); ?>" class="compare" data-product_id="<?php echo esc_attr($product->get_id()); ?>">
											<i class="<?php echo greenmart_get_icon('icon_compare'); ?>"></i>
										</a>
									</div>
								<?php } ?> 
								<div class="button-wishlist">
									<?php
										if( class_exists( 'YITH_WCWL' ) ) {
											echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
										}
									?>  
								</div>
							</div>
						</div>
						<div class="caption">
							<h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<?php
								/**
								* woocommerce_after_shop_loop_item_title hook
								*
								* @hooked woocommerce_template_loop_rating - 5
								* @hooked woocommerce_template_loop_price - 10
								*/
								remove_action( 'woocommerce_after_shop_loop_item_title', 'greenmart_woo_get_subtitle', 15 );
								do_action( 'woocommerce_after_shop_loop_item_title');
								add_action( 'woocommerce_after_shop_loop_item_title', 'greenmart_woo_get_subtitle', 15 );

							?>
							<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
						</div>
				    </div>
            </div>
		
		<?php if($count%$rows_count == $rows_count-1 || $count==$loop->post_count -1){ ?>
		</div>
		<?php }
		$count++; ?>
		
    <?php endwhile; ?>
</div> 
<?php wp_reset_postdata(); ?>