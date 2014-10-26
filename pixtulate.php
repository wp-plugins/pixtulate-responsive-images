<?
/**
 * Plugin Name: Pixtulate
 * Plugin URI: http://www.pixtulate.com/wordpress-plugin
 * Description: A brief description of the Plugin.
 * Version: 0.02
 * Author: Pixtulate
 * Author URI: https://www.pixtulate.com
 * License: GPL2
 */

/*  Copyright 2014  Philipp Reichardt  (email : webmaster@pixtulate.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*

*1. Added all Param options in plugin
*2. Added Pixtulate Logo
*3. Added Pixtulate CTA + Summary for sign-ups
*4. Updated pixtulate.js with latest version
*5. Fixed issue where data-src did not obtain the proper link structure from image
*6. Modified Enqueue filter to prioritize the plugin first in load queue
*7. Added Page Type to options in plugin
*8. Added include to insert main Pixtulate function in footer of page

- test in WordPress 3.7 - 4.0
- include changes in ReadMe File

*/

defined('ABSPATH') or die("No script kiddies please!");  //don't let people call this script directly

define('PIXTULATE_VERSION', '0.02');
define('PIXTULATE_PLUGIN_URL', plugin_dir_url( __FILE__ ));

load_plugin_textdomain( 'pixtulate', false, basename( dirname( __FILE__ ) ) . '/languages' );  //for localization

//let's create the admin page

add_action('admin_menu', 'pixtulate_add_options_page');

function pixtulate_add_options_page() {
	add_options_page("Pixtulate", "Pixtulate Settings", "manage_options", __FILE__, "pixtulate_settings");
}

function pixtulate_settings() {		
	
	if( isset( $_POST['pixtulate_update_settings'] ) ) {
		
		if ( !wp_verify_nonce( $_POST['pixtulate_admin_nonce'], plugin_basename(__FILE__) ) || ! current_user_can('administrator') ) {
			echo '<div id="message" class="updated fade"><p><strong>You were not verified.</strong></div>';
	    	return false; //someone either hacked around or is not an admin
	  	}
		
		$pixtulate_domain 				= sanitize_text_field( $_POST['pixtulate_domain'] );
		$pixtulate_modifyurl 			= sanitize_text_field( $_POST['pixtulate_modifyurl'] );
		$pixtulate_constrain 			= sanitize_text_field( $_POST['pixtulate_constrain'] );
		$pixtulate_https 				= sanitize_text_field( $_POST['pixtulate_https'] );
		$pixtulate_rendering 			= sanitize_text_field( $_POST['pixtulate_rendering'] );
		$pixtulate_deactivate_for_admin = sanitize_text_field( $_POST['pixtulate_deactivate_for_admin'] );
		
		update_option ( 'pixtulate_domain',    				$pixtulate_domain 			    );
		update_option ( 'pixtulate_modifyurl',    			$pixtulate_modifyurl 			);
		update_option ( 'pixtulate_constrain',    			$pixtulate_constrain 			);
		update_option ( 'pixtulate_https',    				$pixtulate_https 			    );
		update_option ( 'pixtulate_rendering', 				$pixtulate_rendering 		    );
		update_option ( 'pixtulate_deactivate_for_admin', 	$pixtulate_deactivate_for_admin );

		echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></div>';
	} else {
		
		$pixtulate_domain 				= get_option ( 'pixtulate_domain' );
		$pixtulate_modifyurl 			= get_option ( 'pixtulate_modifyurl' );
		$pixtulate_constrain 			= get_option ( 'pixtulate_constrain' );
		$pixtulate_https 				= get_option ( 'pixtulate_https' );
		$pixtulate_rendering 			= get_option ( 'pixtulate_rendering' );
		$pixtulate_deactivate_for_admin = get_option ( 'pixtulate_deactivate_for_admin' );
		
	}                                                                                
	
	
	
	?>
	
	<div class="wrap">
		<a href="https://www.pixtulate.com" target="_blank" /><img src="http://demo.api.pixtulate.com/pixtulate-blue.png?w=300" alt="Pixtulate" title="Pixtulate" /></a>
		<div style="width: 600px; margin: 10px 0; padding: 15px; background: #e1e1e1"> 
		Sign up for an account at <a href="http://www.pixtulate.com/signup" target="_blank">Pixtulate</a> and make all your images responsive in less than 60 seconds. Your images will be processed on demand by Pixtulate's servers to match your visitors' exact needs and cached worldwide by our CDN.
		</div>
		<h2><?php _e('Pixtulate Settings', 'pixtulate') ?></h2>
		<form method="post" action="">
			<p><strong><?php _e( 'Please define your settings.', 'pixtulate' ); ?></strong></p>

			<div id="pixtulate_settings_div">
											
				<p id="pixtulate_domain_input">
				  <label for="pixtulate_domain"><?php _e('Type in your Pixtulate Domain:', 'pixtulate'); ?> </label><input type="text" name="pixtulate_domain" value="<?php echo $pixtulate_domain; ?>" />
				</p>
				<p id="pixtulate_modifyurl_input">
				  <?php _e('Modify inconsistent URL format structures?', 'pixtulate'); ?> <input type="radio" name="pixtulate_modifyurl" value="true" <?php echo ( $pixtulate_modifyurl == 'true' ? 'checked' : '' ); ?>/> <label for="pixtulate_modifyurl_true"><?php _e('Yes', 'pixtulate'); ?> </label>
				  <input type="radio" name="pixtulate_modifyurl" value="false" <?php echo ( $pixtulate_modifyurl == 'false' ? 'checked' : '' ); ?>/> <label for="pixtulate_modifyurl_false"><?php _e('No', 'pixtulate'); ?> </label>
				</p>
				<p id="pixtulate_constrain_input">
				  <?php _e('Constrain image dimensions for mobile devices?', 'pixtulate'); ?> <input type="radio" name="pixtulate_constrain" value="true" <?php echo ( $pixtulate_constrain == 'true' ? 'checked' : '' ); ?>/> <label for="pixtulate_constrain_true"><?php _e('Yes', 'pixtulate'); ?> </label>
				  <input type="radio" name="pixtulate_constrain" value="false" <?php echo ( $pixtulate_constrain == 'false' ? 'checked' : '' ); ?>/> <label for="pixtulate_constrain_false"><?php _e('No', 'pixtulate'); ?> </label>
				</p>
				<p id="pixtulate_https_input">
				  <?php _e('Enable HTTPS for all images?', 'pixtulate'); ?> <input type="radio" name="pixtulate_https" value="true" <?php echo ( $pixtulate_https == 'true' ? 'checked' : '' ); ?>/> <label for="pixtulate_https_true"><?php _e('Yes', 'pixtulate'); ?> </label>
				  <input type="radio" name="pixtulate_https" value="false" <?php echo ( $pixtulate_https == 'false' ? 'checked' : '' ); ?>/> <label for="pixtulate_https_false"><?php _e('No', 'pixtulate'); ?> </label>
				</p>
				<p id="pixtulate_rendering_input">
				  <?php _e('Which pictures should be rendered through Pixtulate?', 'pixtulate'); ?><br/>
				  <input type="radio" name="pixtulate_rendering" value="pages" <?php echo ( $pixtulate_rendering == 'page' ? 'checked' : '' ); ?>/> <label for="pixtulate_rendering_pages"><?php _e('Only Pictures included in Pages', 'pixtulate'); ?> </label> <br/>
				  <input type="radio" name="pixtulate_rendering" value="posts" <?php echo ( $pixtulate_rendering == 'posts' ? 'checked' : '' ); ?>/> <label for="pixtulate_rendering_posts"><?php _e('Only Pictures included in Posts', 'pixtulate'); ?> </label> <br/>
				  <input type="radio" name="pixtulate_rendering" value="sitewide" <?php echo ( $pixtulate_rendering == 'sitewide' ? 'checked' : '' ); ?>/> <label for="pixtulate_rendering_sitewide"><?php _e('Every picture on my site', 'pixtulate'); ?> </label> <br/>
				</p>
				<p id="pixtulate_deactivate_for_admin_input">
				  <?php _e('Deactivate Pixtulate for users that are logged in?', 'pixtulate'); ?><br/>
				  <input type="radio" name="pixtulate_deactivate_for_admin" value="1" <?php echo ( $pixtulate_deactivate_for_admin == '1' ? 'checked' : '' ); ?>/> <label for="pixtulate_deactivate_for_admin_1"><?php _e('Yes', 'pixtulate'); ?> </label> <br/>
				  <input type="radio" name="pixtulate_deactivate_for_admin" value="0" <?php echo ( $pixtulate_deactivate_for_admin == '0' ? 'checked' : '' ); ?>/> <label for="pixtulate_deactivate_for_admin_0"><?php _e('No', 'pixtulate'); ?> </label> <br/>
				</p>
				<p>&nbsp;</p>
			</div>

			<p id="pixtulate_settings_submit">
				<input type="submit" name="pixtulate_settings_submit" value="<?php _e('Save Settings', 'pixtulate'); ?>" class="button-primary" />
				<input type="hidden" name="pixtulate_update_settings" value="1" />
				<?php wp_nonce_field( plugin_basename(__FILE__), 'pixtulate_admin_nonce' ); ?>
				
			</p>
			
		</form>
	</div>
	
	<?
	
}

//end admin page

//include the javascript

add_filter( 'wp_print_scripts', 'pixtulate_javascript', 0);
function pixtulate_javascript() {
	
	$pixtulate_deactivate_for_admin = get_option ( 'pixtulate_deactivate_for_admin' );  //are we deactivating is user is logged in?

	$pixtulate_domain = get_option ( 'pixtulate_domain' );
	
	if ( ( is_user_logged_in() && $pixtulate_deactivate_for_admin == 1 ) || ! $pixtulate_domain ) //if no domain name is set yet, then we can't do anything for you
		return;

	wp_enqueue_script ( 'pixtulate',  plugins_url( 'js/pixtulate.js' , __FILE__ ), array('jquery') );

}

add_filter('wp_footer', 'pixtulate_func');
function pixtulate_func() {
	$pixtulate_domain = get_option ( 'pixtulate_domain' );
	$pixtulate_modifyurl = get_option ( 'pixtulate_modifyurl' );
	$pixtulate_constrain = get_option ( 'pixtulate_constrain' );
	$pixtulate_https = get_option ( 'pixtulate_https' );
	
	echo '<script> pixtulate("'. $pixtulate_domain .'",'. $pixtulate_modifyurl .','. $pixtulate_constrain .','. $pixtulate_https .'); </script><br />';
}

add_action('wp_head', 'pixtulate_buffer_start');
add_action('wp_footer', 'pixtulate_buffer_end');
	
function pixtulate_callback($buffer) {
  // modify buffer here, and then return the updated code
  
  return pixtulate_filter ( $buffer ); //replace src with data-src in entire page

}
 
function pixtulate_buffer_start() { 
	
	$pixtulate_domain = get_option ( 'pixtulate_domain' );
	
	if ( ( is_user_logged_in() && $pixtulate_deactivate_for_admin == 1 ) || ! $pixtulate_domain ) 
		return;
		
	$pixtulate_rendering = get_option ( 'pixtulate_rendering' );
	
	if ( $pixtulate_rendering == 'sitewide' )
		ob_start("pixtulate_callback"); 
		
} 
function pixtulate_buffer_end() { 
	
	$pixtulate_domain = get_option ( 'pixtulate_domain' );
	
	if ( ( is_user_logged_in() && $pixtulate_deactivate_for_admin == 1 ) || ! $pixtulate_domain ) 
		return;
		
	$pixtulate_rendering = get_option ( 'pixtulate_rendering' );

	if ( $pixtulate_rendering == 'sitewide' )
		ob_end_flush(); 
		
}

add_filter( 'the_content', 'pixtulate_modify_content' );

function pixtulate_modify_content ( $content ) {
		
	$pixtulate_domain = get_option ( 'pixtulate_domain' );
	
	if ( ( is_user_logged_in() && $pixtulate_deactivate_for_admin == 1 ) || ! $pixtulate_domain ) 
		return $content;  //do nothing
		
	$pixtulate_rendering = get_option ( 'pixtulate_rendering' );

	if ( $pixtulate_rendering == 'posts' ) {
		return pixtulate_filter ( $content );  //replace src with data-src in content
	}
	else if( $pixtulate_rendering == 'page' ) {
		return pixtulate_filter ( $content );
	}
	else {
		return $content;	 //do nothing
	}
}

/**
 * Replaces the src with data-src in all image tags it can find
 */
function pixtulate_filter ( $content ) {
	return preg_replace("/(src=\")(.*)([0-9]{4})/", "data-$1$3", $content);
}


?>