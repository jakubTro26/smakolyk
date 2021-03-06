<?php
if ( $max_value && $min_value === $max_value ) {
	?>
	<div class="quantity hidden">
		<input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>" />
	</div>
	<?php
} else {
		/* translators: %s: Quantity. */
		$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'greenmart' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'greenmart' );
	?>
	<div class="box-quantity">
		<div class="quantity">
      		<?php do_action( 'woocommerce_before_quantity_input_field' ); ?>
      		<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $label ); ?></label>
			<button class="minus" type="button" value="-" tabindex="0"><i class="tb-icon tb-icon-zz-minus"></i></button>
			<input 
				type="number" 
				id="<?php echo esc_attr( $input_id ); ?>"
				class="input-text qty text" 
				step="<?php echo esc_attr( $step ); ?>" 
				min="<?php echo esc_attr( $min_value ); ?>" 
				max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>" 
				name="<?php echo esc_attr( $input_name ); ?>" 
				value="<?php echo esc_attr( $input_value ); ?>" 
				title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'greenmart' ) ?>" 
				size="4"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
				inputmode="<?php echo esc_attr( $inputmode ); ?>" />
			<button class="plus" type="button" value="+" tabindex="0"><i class="tb-icon tb-icon-zz-plus"></i></button>
      		<?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
		</div>
	</div>
	<?php
}
 