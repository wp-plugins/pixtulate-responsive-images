=== Responsive Images by Pixtulate ===
Contributors: kkarski, konisto, sodux
Tags: responsive images, dynamic images, fluid images, image optimization, crop image, scale image, focal point, cdn
Donate link:
Requires at least: 3
Tested up to: 4
Stable tag: trunk
License: MIT
License URI: http://opensource.org/licenses/MIT

Automatically optimizes responsive images for each visitor's screen using Pixtulate's servers and delivers them worldwide over CDN. 

== Description ==
The responsive images plugin connects wordpress sites to the on demand image services of Pixtulate. Our image optimization dramatically speeds up websites with image content. The service scales, crops and optimizes responsive images on demand using Pixtulate's servers.

Pixtulate provides further image enhancements via focal points which ensure images are always cropped and scaled to feature their main subject. All sites also automatically benefit from Pixtulate's global CDN for fast image delivery worldwide.

The plugin simplifies configuration and automates certain otherwise manual tasks on WP sites. Please visit our docs section for full documentation: http://www.pixtulate.com/docs/index.htm. Immediately start optimizing images by signing up at http://www.pixtulate.com.

== Installation ==

= Plugin =
1. Upload the entire Pixtulate folder to the /wp-content/plugins/ directory under your WordPress installation.
2. Activate the Pixtulate plugin from the 'Plugins' menu in WordPress

= Before First Use =
1. Signup for an account at http://www.pixtulate.com/signup
2. Complete the signup process and register your desired domain name
3. Come back to the plugin settings page in your WordPress installation and enter your domain name
4. Select your desired HTTP connector location from the dropdown menu and press 'Update'. All images paths will be relative from this location.
5. Select what kids of images you want Pixtulate to optimize and your SSL settings
6. When done, press 'Update'

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 1.00 =
* Added JSON update for connector configuration
* Added new layout format to admin page
* Updated data-src regex to handle a multitude of path types 
* Fixed issue with script if an undefined variable was passed as a parameter

= 0.02 =
* Added all Param options in plugin
* Added Pixtulate Logo
* Added Pixtulate CTA + Summary for sign-ups
* Updated pixtulate.js with latest version
* Fixed issue where data-src did not obtain the proper link structure from image
* Modified Enqueue filter to prioritize the plugin first in load queue
* Added Page Type to options in plugin
* Fixed Issue where Pixtulate function never initialized
