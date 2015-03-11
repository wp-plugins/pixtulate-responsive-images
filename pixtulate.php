<?php
/**
 * Plugin Name: Pixtulate
 * Plugin URI: http://www.pixtulate.com/wordpress-plugin
 * Description: The responsive images plugin connects wordpress sites to the on demand image services of Pixtulate. Our image optimization dramatically speeds up websites with image content. The service scales, crops and optimizes responsive images on demand using Pixtulate's servers.
 * Version: 1.2.2
 * Author: Pixtulate
 * Author URI: https://www.pixtulate.com
 * License: GPL2
 */

/*  Copyright 2015 Pixtulate  (email : support@pixtulate.com)

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

- include changes in ReadMe File

*/

defined('ABSPATH') or die("No script kiddies please!");  //don't let people call this script directly

define('PIXTULATE_VERSION', '1.2.2');
define('PIXTULATE_PLUGIN_URL', plugin_dir_url( __FILE__ ));

load_plugin_textdomain( 'pixtulate', false, basename( dirname( __FILE__ ) ) . '/languages' );  //for localization

//let's create the admin page

add_action('admin_init', 'pixtulate_init');
add_action('admin_menu', 'pixtulate_add_options_page');

function pixtulate_init() {
	wp_register_style( 'pixtulateCSS', plugins_url('css/pixtulate_style.css', __FILE__));
	wp_register_script( 'pixtulateJS', plugins_url('js/pixtulate_admin.js', __FILE__));
}

function pixtulate_add_options_page() {
	add_options_page("Pixtulate", "<img src='". PIXTULATE_PLUGIN_URL ."/images/pixtulate_sb.png' alt='' style='margin-bottom:-4px; padding-right:2px;' />Pixtulate", "manage_options", __FILE__, "pixtulate_settings");
}

function pixtulate_settings() {

	wp_enqueue_style( 'pixtulateCSS' );
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
		$pixtulate_modifyimage 			= sanitize_text_field( $_POST['pixtulate_modifyimage'] );

		update_option ( 'pixtulate_domain',    				$pixtulate_domain 			    );
		update_option ( 'pixtulate_constrain',    			$pixtulate_constrain 			);
		update_option ( 'pixtulate_https',    				$pixtulate_https 			    );
		update_option ( 'pixtulate_rendering', 				$pixtulate_rendering 		    );
		update_option ( 'pixtulate_deactivate_for_admin', 	$pixtulate_deactivate_for_admin );
		update_option ( 'pixtulate_connector',    			$pixtulate_connector 			);
		update_option ( 'pixtulate_modifyimage',    		$pixtulate_modifyimage 			);

		echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></div>';

	} else {

		$pixtulate_domain 				= get_option ( 'pixtulate_domain' );
		$pixtulate_constrain 			= get_option ( 'pixtulate_constrain' );
		$pixtulate_https 				= get_option ( 'pixtulate_https' );
		$pixtulate_rendering 			= get_option ( 'pixtulate_rendering' );
		$pixtulate_deactivate_for_admin = get_option ( 'pixtulate_deactivate_for_admin' );
		$pixtulate_connector 			= get_option ( 'pixtulate_connector' );
		$pixtulate_modifyimage			= get_option ( 'pixtulate_modifyimage' );

	}

	?>


	<div class="wrap">
		<form id="pixtForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?page=pixtulate-responsive-images/pixtulate.php" method="post">
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
				  <label for="pixtulate_domain"><?php _e('Pixtulate Account ID', 'pixtulate'); ?> <span class="req">*</span> </label> <a href="https://github.com/pixtulate/pixtulate.js" target="_blank"><img src="<?php echo PIXTULATE_PLUGIN_URL; ?>/images/qlinks.jpg" alt="Pixtulate" title="Pixtulate" /></a><br />
				  <span>http://</span><input type="text" name="pixtulate_domain" id="pixtulate_domain" value="<?php echo $pixtulate_domain; ?>" /><span>.api.pixtulate.com</span>
				</p>
				<p id="pixtulate_connector">
				  <label for="pixtulate_connect"><?php _e('HTTP Connector Path', 'pixtulate'); ?> <span class="req">*</span> </label> <br />
				  <select name="pixtulate_connector" id="pixt_connector">
					<?php $u = site_url();
						if($pixtulate_connector == $u || !$pixtulate_connector): ?>
						<option value="<?php echo site_url(); ?>" selected="true"><?php echo site_url(); ?></option>
						<option value="<?php echo site_url(); ?>/wp-content/uploads/"><?php echo site_url(); ?>/wp-content/uploads</option>
					<?php else: ?>
						<option value="<?php echo site_url(); ?>"><?php echo site_url(); ?></option>
						<option value="<?php echo site_url(); ?>/wp-content/uploads/" selected="true"><?php echo site_url(); ?>/wp-content/uploads</option>
					<?php endif; ?>
				  </select>
				</p>
			</div>
			<div class="col02">
				<a href="http://www.pixtulate.com/signup" target="_blank">Signup with Pixtulate</a> and make all <br />your images responsive in less than <br /> 60 seconds.
			</div>
		</div>
		<div class="row03">
			<h2>Plugin Settings</h2>
			<div class="col01">
				<p id="pixtulate_constrain_input">
					<?php if(!$pixtulate_domain): ?>
						<input type="checkbox" name="pixtulate_constrain" id="pixtulate_max_width" checked value="true" <?php echo ( $pixtulate_constrain == 'true' ? 'checked' : '' ); ?>/> <label for="pixtulate_max_width"><?php _e('Make maximum image width equal to visitor\'s screen width', 'pixtulate'); ?></label> <img src="<?php echo PIXTULATE_PLUGIN_URL; ?>/images/qlinks.jpg" alt="Pixtulate" title="Pixtulate" class="tooltip01" />
					<?php else: ?>
						<input type="checkbox" name="pixtulate_constrain" id="pixtulate_max_width" value="true" <?php echo ( $pixtulate_constrain == 'true' ? 'checked' : '' ); ?>/> <label for="pixtulate_max_width"><?php _e('Make maximum image width equal to visitor\'s screen width', 'pixtulate'); ?></label> <img src="<?php echo PIXTULATE_PLUGIN_URL; ?>/images/qlinks.jpg" alt="Pixtulate" title="Pixtulate" class="tooltip01" />
					<?php endif; ?>
				</p>
				<p id="pixtulate_modimage_input">
					<?php if(!$pixtulate_domain): ?>
						<input type="checkbox" name="pixtulate_modifyimage" id="pixtulate_image_mod" checked value="true" <?php echo ( $pixtulate_modifyimage == 'true' ? 'checked' : '' ); ?>/> <label for="pixtulate_image_mod"><?php _e('Ignore default image dimensions (Recommended)', 'pixtulate'); ?></label> <img src="<?php echo PIXTULATE_PLUGIN_URL; ?>/images/qlinks.jpg" alt="Pixtulate" title="Pixtulate" class="tooltip02" />
					<?php else: ?>
						<input type="checkbox" name="pixtulate_modifyimage" id="pixtulate_image_mod" value="true" <?php echo ( $pixtulate_modifyimage == 'true' ? 'checked' : '' ); ?>/> <label for="pixtulate_image_mod"><?php _e('Ignore default image dimensions (Recommended)', 'pixtulate'); ?></label> <img src="<?php echo PIXTULATE_PLUGIN_URL; ?>/images/qlinks.jpg" alt="Pixtulate" title="Pixtulate" class="tooltip02" />
					<?php endif; ?>
				</p>
				<p id="pixtulate_https_input">
				  <input type="checkbox" name="pixtulate_https" id="pixtulate_ssl" value="true" <?php echo ( $pixtulate_https == 'true' ? 'checked' : '' ); ?>/> <label for="pixtulate_ssl"><?php _e('Force SSL encryption of images (https)', 'pixtulate'); ?></label> <img src="<?php echo PIXTULATE_PLUGIN_URL; ?>/images/qlinks.jpg" alt="Pixtulate" title="Pixtulate" class="tooltip03" />
				</p>
				<br />
				<p id="pixtulate_rendering_input">
					<strong>Which images should be processed by Pixtulate?</strong> <br />
					<input type="radio" name="pixtulate_rendering" id="pixtulate_rendering_posts" value="posts" <?php echo ( $pixtulate_rendering == 'posts' ? 'checked' : '' ); ?>/> <label for="pixtulate_rendering_posts"><?php _e('Images on Pages and Posts', 'pixtulate'); ?> </label>
					<img src="<?php echo PIXTULATE_PLUGIN_URL; ?>/images/qlinks.jpg" alt="Pixtulate" title="Pixtulate" class="tooltip04" /> <br/>
					<?php if(!$pixtulate_domain): ?>
						<input type="radio" name="pixtulate_rendering" id="pixtulate_rendering_sitewide" checked value="sitewide" <?php echo ( $pixtulate_rendering == 'sitewide' ? 'checked' : '' ); ?>/> <label for="pixtulate_rendering_sitewide"><?php _e('All images on my site', 'pixtulate'); ?> </label>
					<?php else: ?>
						<input type="radio" name="pixtulate_rendering" id="pixtulate_rendering_sitewide" value="sitewide" <?php echo ( $pixtulate_rendering == 'sitewide' ? 'checked' : '' ); ?>/> <label for="pixtulate_rendering_sitewide"><?php _e('All images on my site', 'pixtulate'); ?> </label>
					<?php endif; ?>
					<img src="<?php echo PIXTULATE_PLUGIN_URL; ?>/images/qlinks.jpg" alt="Pixtulate" title="Pixtulate" class="tooltip05" /> <br/>
				</p>

				<p id="pixtulate_deactivate_for_admin_input">
					<strong>Deactivate Pixtulate for users that are logged in?</strong><br />
					<input type="radio" name="pixtulate_deactivate_for_admin" value="1" <?php echo ( $pixtulate_deactivate_for_admin == '1' ? 'checked' : '' ); ?>/> <label for="pixtulate_deactivate_for_admin_1"><?php _e('Yes', 'pixtulate'); ?> </label> <br/>
					<input type="radio" name="pixtulate_deactivate_for_admin" value="0" <?php echo ( $pixtulate_deactivate_for_admin == '0' ? 'checked' : '' ); ?>/> <label for="pixtulate_deactivate_for_admin_0"><?php _e('No', 'pixtulate'); ?> </label> <br/>
				</p>

				<p id="pixtulate_settings_submit">
					<input type="button" name="pixtulate_settings_submit" id="connector_btn" value="<?php _e('Save Plugin Settings', 'pixtulate'); ?>" class="button-primary" />
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
			&copy; Pixtulate, 2015. All Rights Reserved. Plugin Version 1.2.2 <span><a href="http://www.pixtulate.com" target="_blank">Pixtulate</a> | <a href="http://pixtulate.com/docs/index.htm" target="_blank">Docs</a> | <a href="https://pixtulate.desk.com/">Support</a> | <a href="https://wordpress.org/plugins/pixtulate-responsive-images/" target="_blank">WP Plugin</a> | <a href="https://twitter.com/pixtulate" target="_blank">Twitter</a></span>
		</div>
		</form>
		<div class="pixt_tooltip"></div>
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

	if ( ( is_user_logged_in() && $pixtulate_deactivate_for_admin == 1 ) || ! $pixtulate_domain ) //if no domain name is set yet, then we can't do anything for you
		return;
	
	$pixtulate_modifyurl = "true";
	$pixtulate_constrain = get_option ( 'pixtulate_constrain' );
	$pixtulate_https = get_option ( 'pixtulate_https' );
	$pixtulate_modifyimage = get_option ( 'pixtulate_modifyimage' );

	if($pixtulate_modifyimage == '')
		$pixtulate_modifyimage = "false";

	if($pixtulate_constrain == '')
		$pixtulate_constrain = "false";

	if($pixtulate_https == '')
		$pixtulate_https = "false";

	echo '<script> pixtulate("'. $pixtulate_domain .'",'. $pixtulate_modifyurl .','.  $pixtulate_modifyimage .','. $pixtulate_constrain .','. $pixtulate_https .'); </script>';
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
	else
		ob_start("pixtulate_modify_content");

}
function pixtulate_buffer_end() {

	$pixtulate_domain = get_option ( 'pixtulate_domain' );
	$pixtulate_deactivate_for_admin = get_option ( 'pixtulate_deactivate_for_admin' );

	if ( ( is_user_logged_in() && $pixtulate_deactivate_for_admin == 1 ) || ! $pixtulate_domain )
		return;

	$pixtulate_rendering = get_option ( 'pixtulate_rendering' );

	ob_end_flush();

}

function pixtulate_modify_content ( $buffer ) {
	return pixtulate_type_filter ( $buffer );
}

/**
 * Replaces the src with data-src in all image tags it can find sitewide
 */
function pixtulate_filter ( $content ) {
	$pixtulate_connector = get_option ( 'pixtulate_connector' );
	$base_url = get_site_url();

	$base_url_arr = parse_url($base_url);
	if ($base_url_arr[path]) {
		$base_url_mod = $base_url_arr[scheme]. '\:\/\/' .$base_url_arr[host]. '\\' .$base_url_arr[path];
	} else {
		$base_url_mod = $base_url_arr[scheme]. '\:\/\/' .$base_url_arr[host];
	}

	if($pixtulate_connector == $base_url)
		return preg_replace("/(src=\")(.*)([^>]*)(".$base_url_mod."\/)/", "data-$1$2$3", $content);
	else
		return preg_replace("/(src=\")(.*)([^>]*)(uploads\/)/", "data-$1$3", $content);
}

/**
 * Replaces the src with data-src in all image tags it can find sitewide
 */
function pixtulate_type_filter ( $content ) {
	$post_type = get_post_type();

	if($post_type == 'page' || $post_type == 'post') {
		$pixtulate_connector = get_option ( 'pixtulate_connector' );
		$base_url = get_site_url();

		$base_url_arr = parse_url($base_url);

		if ($base_url_arr[path]) {
			$base_url_mod = $base_url_arr[scheme]. '\:\/\/' .$base_url_arr[host]. '\\' .$base_url_arr[path];
		} else {
			$base_url_mod = $base_url_arr[scheme]. '\:\/\/' .$base_url_arr[host];
		}

		if($pixtulate_connector == $base_url)
			return preg_replace("/(src=\")(.*)([^>]*)(".$base_url_mod."\/)/", "data-$1$2$3", $content);
			// return preg_replace("/(src=\")(.*)([^>]*)/", "data-$1$2$3", $content);
		else
			return preg_replace("/(src=\")(.*)([^>]*)(uploads\/)/", "data-$1$3", $content);
	}
}
?>
