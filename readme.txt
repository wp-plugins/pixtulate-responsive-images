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
The responsive images plugin connects wordpress sites to the on demand image services of [Pixtulate](http://www.pixtulate.com). Our image optimization dramatically speeds up websites with image content. The service scales, crops and optimizes responsive images on demand using Pixtulate's servers.

= Features =

Pixtulate provides further image enhancements via focal points which ensure images are always cropped and scaled to feature their main subject. All sites also automatically benefit from Pixtulate's global CDN for fast image delivery worldwide.

= Docs & Support =

The plugin simplifies configuration and automates certain otherwise manual tasks on WP sites. Please visit our docs section for full [documentation](http://www.pixtulate.com/docs/index.htm). Immediately start optimizing images by [signing up](http://www.pixtulate.com/signup/#?source=wordpress).

== Installation ==

= Plugin =
1. Upload the entire Pixtulate folder to the /wp-content/plugins/ directory under your WordPress installation.
2. Activate the Pixtulate plugin from the 'Plugins' menu in WordPress

= Before First Use =
1. [Signup](http://www.pixtulate.com/signup/#?source=wordpress) for an account
2. Complete the signup process and register your desired domain name
3. Come back to the plugin settings page in your WordPress installation and enter your domain name
4. Select your desired HTTP connector location from the dropdown menu and press 'Update'. All images paths will be relative from this location.
5. Select what kids of images you want Pixtulate to optimize and your SSL settings
6. When done, press 'Update'

== Frequently Asked Questions ==
**What do I put in the 'Domain' field?**

Only enter the name of the domain your registered, not the full host. For example, only enter "mydomain"and not mydomain.api.pixtulate.com

**What does the option 'Ignore default image dimensions (Recommended)' actually do?**

Wordpress by default embeds the image's original height and width as attributes on the <img> element. Selecting this option removes those attributes so that the image is free t be scaled accorrding to the layout's dimensions by Pixtulate, thereby renddering your images responsive.

**How do I actually make my images responsive?**

Simply add a media item to your page or post from your media library. This items should be of the best quality and highest resolution possible. No need to pre-scale or thumbnail beforehand. That's it. The image will be scaled to fit the area where it's located.

== Screenshots ==

1. Plugin admin area. From here you can directly configure your connector settings and plugin options.

== Changelog ==
= 1.1.1 =
* Ehm...very emberrasing issue with post path of form on plugin config page
* Removed jquery dependency since WP already provides it by default

= 1.1 =
* Combined JSON update and Plugin Settings into one form submission
* Added Tooltips for key items
* Added ability to ignore default image attribute dimensions
* Fixed Misc. Bugs pertaining to the Javascript include
* Minor cosmetic changes

= 1.0 =
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
