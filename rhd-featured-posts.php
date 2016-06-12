<?php
/**
 * Plugin Name: RHD Featured Posts
 * Description: Simple meta area for setting posts as 'Featured.'
 * Author: Roundhouse Designs
 * Author URI: http://roundhouse-designs.com
 * Version: 0.1
**/


/**
 * rhd_featured_post function.
 * 
 * @access public
 * @return void
 */
function rhd_featured_post()
{
	add_meta_box( 'rhd_featured_post', __( 'Feature This Post', 'rhd' ), 'rhd_featured_post_callback', 'post', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'rhd_featured_post' );


/**
 * rhd_featured_post_callback function.
 * 
 * @access public
 * @param mixed $post
 * @return void
 */
function rhd_featured_post_callback( $post )
{
	wp_nonce_field( basename( __FILE__ ), 'rhd_feature_nonce' );
	$is_featured = get_post_meta( $post->ID, 'rhd-feature-checkbox', true );
	?>

	<p>
		<p style="font-size: 0.9em; font-style: italic;">Add this post to the front page Featured Posts slider.</p>
		<div class="rhd-row-content">
			<label for="rhd-feature-checkbox">
				<input type="checkbox" name="rhd-feature-checkbox" id="rhd-feature-checkbox" value="yes" <?php checked( $is_featured, 'yes' ); ?> />
				<?php _e( 'Feature Post', 'rhd' )?>
			</label>
		</div>
	</p>

<?php
}


/**
 * Saves the custom meta input
 */
function rhd_featured_post_save( $post_id )
{
	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'rhd_feature_nonce' ] ) && wp_verify_nonce( $_POST[ 'rhd_feature_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	
	// Exits script depending on save status
	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		return;
	}
	
	// Checks for input and saves
	if( isset( $_POST[ 'rhd-feature-checkbox' ] ) ) {
		update_post_meta( $post_id, 'rhd-feature-checkbox', 'yes' );
	} else {
		update_post_meta( $post_id, 'rhd-feature-checkbox', '' );
	}
}
add_action( 'save_post', 'rhd_featured_post_save' );