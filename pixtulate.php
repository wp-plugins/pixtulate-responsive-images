<?php
/**
 * Plugin Name: Pixtulate
 * Plugin URI: http://www.pixtulate.com/wordpress-plugin
 * Description: A brief description of the Plugin.
 * Version: 1.00
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

define('PIXTULATE_VERSION', '1.00');
define('PIXTULATE_PLUGIN_URL', plugin_dir_url( __FILE__ ));

load_plugin_textdomain( 'pixtulate', false, basename( dirname( __FILE__ ) ) . '/languages' );  //for localization

//let's create the admin page

add_action('admin_init', 'pixtulate_init');
add_action('admin_menu', 'pixtulate_add_options_page');

function pixtulate_init() {
	wp_register_style( 'pixtulateCSS', plugins_url('css/pixtulate_style.css', __FILE__)); 
	wp_register_script( 'jQueryPixtulate', plugins_url('js/jquery.min.js', __FILE__)); 
	wp_register_script( 'pixtulateJS', plugins_url('js/pixtulate_admin.js', __FILE__)); 
}

function pixtulate_add_options_page() {
	add_options_page("Pixtulate", "<img src='". PIXTULATE_PLUGIN_URL ."/images/pixtulate_sb.jpg' alt='' style='margin-bottom:-4px; padding-right:2px;' />Pixtulate", "manage_options", __FILE__, "pixtulate_settings");
}

function pixtulate_settings() {		
	
	wp_enqueue_style( 'pixtulateCSS' );
	wp_enqueue_script( 'jQueryPixtulate' );
	wp_enqueue_script( 'pixtulateJS' );
	
	if( isset( $_POST['pixtulate_update_settings'] ) ) {
		
		if ( !wp_verify_nonce( $_POST['pixtulate_admin_nonce'], plugin_basename(__FILE__) ) || ! current_user_can('administrator') ) {
			echo '<div id="message" class="updated fade"><p><strong>You were not verified.</strong></div>';
	    	return false; //someone either hacked around or is not an admin
	  	}
		
		$pixtulate_domain 				= sanitize_text_field( $_POST['pixtulate_domain'] );
		$pixtulate_constrain 			= sanitize_text_field( $_POST['pixtulate_constrain'] );
		$pixtulate_https 				= sanitize_text_field( $_POST['pixtulate_https'] );
		$pixtulate_rendering 			= sanitize_text_field( $_POST['pixtulate_rendering'] );
		$pixtulate_deactivate_for_admin = sanitize_text_field( $_POST['pixtulate_deactivate_for_admin'] );
		$pixtulate_connector 			= sanitize_text_field( $_POST['pixtulate_connector'] );
		
		update_option ( 'pixtulate_domain',    				$pixtulate_domain 			    );
		update_option ( 'pixtulate_constrain',    			$pixtulate_constrain 			);
		update_option ( 'pixtulate_https',    				$pixtulate_https 			    );
		update_option ( 'pixtulate_rendering', 				$pixtulate_rendering 		    );
		update_option ( 'pixtulate_deactivate_for_admin', 	$pixtulate_deactivate_for_admin );
		update_option ( 'pixtulate_connector',    			$pixtulate_connector 			);

		echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></div>';
	} else {
		
		$pixtulate_domain 				= get_option ( 'pixtulate_domain' );
		$pixtulate_constrain 			= get_option ( 'pixtulate_constrain' );
		$pixtulate_https 				= get_option ( 'pixtulate_https' );
		$pixtulate_rendering 			= get_option ( 'pixtulate_rendering' );
		$pixtulate_deactivate_for_admin = get_option ( 'pixtulate_deactivate_for_admin' );
		$pixtulate_connector 			= get_option ( 'pixtulate_connector' );
		
	}                 

	?>
	
	
		
	<div class="wrap">
		<form method="post" action="">
		<div class="row01">
			<div class="col01">		
				<a href="https://www.pixtulate.com" target="_blank" /><img src="http://demo.api.pixtulate.com/pixtulate-blue.png?w=300" alt="Pixtulate" title="Pixtulate" /></a>
			</div>
			<div class="col02">
				<strong>Responsive Images on Demand</strong> <br />
				<em>Crop, Scale & Optimize Images in the Cloud</em>
			</div>
		</div>
		<div class="row02">
			<h2>Account & Connector Settings</h2>
			<div class="col01">
				<p class="error">There was a problem updating your connector's configuration. Please make sure to sign up for Pixtulate before configuring the plugin</p>
				<p id="pixtulate_domain_input">
				  <label for="pixtulate_domain"><?php _e('Domain', 'pixtulate'); ?> <span class="req">*</span> </label><br />
				  <input type="text" name="pixtulate_domain" id="pixtulate_domain" value="<?php echo $pixtulate_domain; ?>" />
				</p>
				<p id="pixtulate_connector">
				  <label for="pixtulate_connect"><?php _e('HTTP Connector Path', 'pixtulate'); ?> <span class="req">*</span> </label> <br />
				  <select name="pixtulate_connector" id="pixtulate_connector">
					<option value="<?php echo site_url(); ?>"><?php echo site_url(); ?></option>
					<option value="<?php echo site_url(); ?>/wp-content/uploads/"><?php echo site_url(); ?>/wp-content/uploads</option>
				  </select> <a href="https://github.com/pixtulate/pixtulate.js" target="_blank"><img src="<?php echo PIXTULATE_PLUGIN_URL; ?>/images/qlinks.jpg" alt="Pixtulate" title="Pixtulate" /></a>
				</p>
				<input type="button" name="pixtulate_connector_submit" id="connector_test" value="<?php _e('Update Connector', 'pixtulate'); ?>" class="button-primary" />
			</div>
			<div class="col02">
				<a href="http://www.pixtulate.com/signup" target="_blank">Signup with Pixtulate</a> and make all <br />your images responsive in less than <br /> 60 seconds.
			</div>
		</div>
		<div class="row03">
			<h2>Plugin Settings</h2>
			<div class="col01">				 
				<p id="pixtulate_constrain_input">
				  <input type="checkbox" name="pixtulate_constrain" checked value="true" <?php echo ( $pixtulate_constrain == 'true' ? 'checked' : '' ); ?>/> <?php _e('Make maximum image width equal to visitor\'s screen width', 'pixtulate'); ?> <a href="https://github.com/pixtulate/pixtulate.js" target="_blank"><img src="<?php echo PIXTULATE_PLUGIN_URL; ?>/images/qlinks.jpg" alt="Pixtulate" title="Pixtulate" /></a> 
				</p>
				<p id="pixtulate_https_input">
				  <input type="checkbox" name="pixtulate_https" value="true" <?php echo ( $pixtulate_https == 'true' ? 'checked' : '' ); ?>/> <?php _e('Force SSL encryption of images (https)', 'pixtulate'); ?> <a href="https://github.com/pixtulate/pixtulate.js" target="_blank"><img src="<?php echo PIXTULATE_PLUGIN_URL; ?>/images/qlinks.jpg" alt="Pixtulate" title="Pixtulate" /></a>
				</p>
				<br />
				<p id="pixtulate_rendering_input">
				  <strong>Which images should be processed by Pixtulate?</strong><br />
				  <input type="radio" name="pixtulate_rendering" value="pages" <?php echo ( $pixtulate_rendering == 'page' ? 'checked' : '' ); ?>/> <label for="pixtulate_rendering_pages"><?php _e('Images on Pages', 'pixtulate'); ?> </label> <br/>
				  <input type="radio" name="pixtulate_rendering" value="posts" <?php echo ( $pixtulate_rendering == 'posts' ? 'checked' : '' ); ?>/> <label for="pixtulate_rendering_posts"><?php _e('Images on Posts', 'pixtulate'); ?> </label> <br/>
				  <input type="radio" name="pixtulate_rendering" value="sitewide" <?php echo ( $pixtulate_rendering == 'sitewide' ? 'checked' : '' ); ?>/> <label for="pixtulate_rendering_sitewide"><?php _e('All images on my site', 'pixtulate'); ?> </label> <br/>
				</p>			
				
				<p id="pixtulate_deactivate_for_admin_input">
  				  <strong>Deactivate Pixtulate for users that are logged in?</strong><br />
				  <input type="radio" name="pixtulate_deactivate_for_admin" value="1" <?php echo ( $pixtulate_deactivate_for_admin == '1' ? 'checked' : '' ); ?>/> <label for="pixtulate_deactivate_for_admin_1"><?php _e('Yes', 'pixtulate'); ?> </label> <br/>
				  <input type="radio" name="pixtulate_deactivate_for_admin" value="0" <?php echo ( $pixtulate_deactivate_for_admin == '0' ? 'checked' : '' ); ?>/> <label for="pixtulate_deactivate_for_admin_0"><?php _e('No', 'pixtulate'); ?> </label> <br/>
				</p>

				<p id="pixtulate_settings_submit">
					<input type="submit" name="pixtulate_settings_submit" value="<?php _e('Save Settings', 'pixtulate'); ?>" class="button-primary" />
					<input type="hidden" name="pixtulate_update_settings" value="1" />
					<?php wp_nonce_field( plugin_basename(__FILE__), 'pixtulate_admin_nonce' ); ?>
					
				</p>
				
			</div>
			<div class="col02">
				<img src="<?php echo PIXTULATE_PLUGIN_URL; ?>/images/resolutions.png" alt="Pixtulate" title="Pixtulate" /> 
				<p>
				Your images will be processed on demand by Pixtulate's servers to match your visitor's exact needs and cached worldwide by our CDN.
				</p>
			</div>
		</div>
		<div class="row04">
			&copy; Pixtulate, 2015. All Rights Reserved. Plugin Version 1.00 <span><a href="http://www.pixtulate.com" target="_blank">Pixtulate</a> | <a href="http://pixtulate.com/docs/index.htm" target="_blank">Docs</a> | <a href="https://pixtulate.desk.com/">Support</a> | <a href="https://wordpress.org/plugins/pixtulate-responsive-images/" target="_blank">WP Plugin</a> | <a href="https://twitter.com/pixtulate" target="_blank">Twitter</a></span>
		</div>	
		</form>
											
			
		</form>
	</div>
	
	<?php
	
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
	$pixtulate_modifyurl = "true";
	$pixtulate_constrain = get_option ( 'pixtulate_constrain' );
	$pixtulate_https = get_option ( 'pixtulate_https' ); 
	
	if($pixtulate_constrain == '') 
		$pixtulate_constrain = "false"; 
		
	if($pixtulate_https == '') 
		$pixtulate_https = "false"; 
	
	echo '<script> pixtulate("'. $pixtulate_domain .'",'. $pixtulate_modifyurl .','. $pixtulate_constrain .','. $pixtulate_https .'); </script>';
}

add_action('wp_head', 'pixtulate_buffer_start');
add_action('wp_footer', 'pixtulate_buffer_end');
	
function pixtulate_callback($buffer) {

  // modify buffer here, and then return the updated code
  return pixtulate_filter ( $buffer ); //replace src with data-src in entire page

}
 
function pixtulate_buffer_start() { 
	
	$pixtulate_domain = get_option ( 'pixtulate_domain' );
	$pixtulate_deactivate_for_admin = get_option ( 'pixtulate_deactivate_for_admin' );
	
	if ( ( is_user_logged_in() && $pixtulate_deactivate_for_admin == 1 ) || ! $pixtulate_domain ) 
		return;
		
	$pixtulate_rendering = get_option ( 'pixtulate_rendering' );
	
	if ( $pixtulate_rendering == 'sitewide' )
		ob_start("pixtulate_callback"); 
		
} 
function pixtulate_buffer_end() { 
	
	$pixtulate_domain = get_option ( 'pixtulate_domain' );
	$pixtulate_deactivate_for_admin = get_option ( 'pixtulate_deactivate_for_admin' );
	
	if ( ( is_user_logged_in() && $pixtulate_deactivate_for_admin == 1 ) || ! $pixtulate_domain ) 
		return;
		
	$pixtulate_rendering = get_option ( 'pixtulate_rendering' );

	if ( $pixtulate_rendering == 'sitewide' )
		ob_end_flush(); 
		
}

add_filter( 'wp_head', 'pixtulate_modify_content' );

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
	
	$pixtulate_connector = get_option ( 'pixtulate_connector' );
	$base_url = get_site_url();
			
	$base_url_arr = parse_url($base_url);
	$base_url_mod = $base_url_arr[scheme]. '\:\/\/' .$base_url_arr[host]. '\\' .$base_url_arr[path];
	
	if($pixtulate_connector == $base_url) 
		return preg_replace("/(src=\")(.*)([^>]*)(".$base_url_mod."\/)/", "data-$1$3", $content);
	else 
		return preg_replace("/(src=\")(.*)([0-9]{4})/", "data-$1$3", $content);
}


?>