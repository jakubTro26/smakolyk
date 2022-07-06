<?php
/**

 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( class_exists( 'GeeShop_gees' ) ) :


	function GSh_gees() {
		return GeeShop_gees::instance();
	}

endif;
if (is_admin()){
	
	
// Display Fields
add_action( 'woocommerce_product_options_general_product_data', 'geeshop_add_custom_general_fields' );

// Save Fields
add_action( 'woocommerce_process_product_meta', 'geeshop_add_custom_general_fields_save' );


function geeshop_add_custom_general_fields() {
	global $woocommerce, $post;
	
	//EAN 	
	echo '<div class="options_group">';	
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_gees_ean', 
			'label'       => __( 'EAN', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter the EAN value here.', 'woocommerce' ) 
		)
	);
	echo '</div>';
	
	//EXTERNAL ID 	
	echo '<div class="options_group">';	
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_gees_external_prd_id', 
			'label'       => __( 'ERP ID', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter the ERP product ID.', 'woocommerce' ) 
		)
	);
	echo '</div>';
}




function geeshop_add_custom_general_fields_save( $post_id ){
	
	//FERM 
	$woocommerce_text_field = isset($_POST['_gees_ferm_type_id'])?$_POST['_gees_ferm_type_id']:"";
	if( isset( $woocommerce_text_field ) )
		update_post_meta( $post_id, '_gees_ferm_type_id', esc_attr( $woocommerce_text_field ) );
	//YouTube Video ID
	$woocommerce_text_field = $_POST['_gees_youtube_id'];
	if( !empty( $woocommerce_text_field ) )
		update_post_meta( $post_id, '_gees_youtube_id', esc_attr( $woocommerce_text_field ) );
			
	//EAN
	$woocommerce_text_field = $_POST['_gees_ean'];
	//if( !empty( $woocommerce_text_field ) )
		update_post_meta( $post_id, '_gees_ean', esc_attr( $woocommerce_text_field ) );
	//EXTERNAL ID 
	$woocommerce_text_field = $_POST['_gees_external_prd_id'];
	if( !empty( $woocommerce_text_field ) )
		update_post_meta( $post_id, '_gees_external_prd_id', esc_attr( $woocommerce_text_field ) );

}
	
add_filter( 'manage_edit-shop_order_columns', 'geesoft_cset_custom_column_order_columns');

function geesoft_cset_custom_column_order_columns($columns) {
	// global $woocommerce;
	$nieuwearray = array();
	foreach($columns as $key => $title) {
		if ($key=='order_total'){ // in front of the Billing column
			$nieuwearray[$key] = $title;
			$nieuwearray['order_service']  = __( 'Serwis', 'woocommerce' );
		}else{			
			$nieuwearray[$key] = $title;
		}
	}
//	print_t($nieuwearray ); 
    return $nieuwearray ;
}

add_action( 'manage_shop_order_posts_custom_column' , 'geesoft_custom_shop_order_column', 10, 2 );
function geesoft_custom_shop_order_column( $column ) {
 global $post;

    switch ( $column ) {

        case 'order_service' :
			echo get_post_meta($post->ID, 'order_service', true);
		/*      $terms = $the_order->get_items();

	      if ( is_array( $terms ) ) {
             	foreach($terms as $term)
			{
			echo $term['item_meta']['_qty'][0] .' x ' . $term['name'] .'<br />';
			}
		  } else {
              	_e( 'Unable get the producten', 'woocommerce' );
		}*/
            break;

    }
}
//       update_post_meta( $order_id, 'My Field', sanitize_text_field( $_POST['my_field_name'] ) );

/**
 * Add the field to the checkout
 */
add_action( 'woocommerce_after_order_notes', 'gees_serwis_checkout_field' );

function gees_serwis_checkout_field( $checkout ) {

    echo '<div id="geesoft_serwis_checkout_field"><h2>' . __('Serwis') . '</h2>';

    woocommerce_form_field( 'gees_serwis', array(
        'type'          => 'text',
        'class'         => array('my-field-class form-row-wide'),
        'label'         => __('Fill in this field'),
        'placeholder'   => __('Serwis '),
        ), $checkout->get_value( 'gees_serwis' ));

    echo '</div>';

}

}

//require_once( GEESHOP_PLUGIN_DIR . ( 'addin/gees/import/gees.product.class.php ' ) );