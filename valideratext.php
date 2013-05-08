<?php
/*
Plugin Name: Valideratext
Plugin URI: http://wordpress.org/extend/plugins/valideratext/
Description: Koppling till Valideratext.se för redaktionellt stöd (API v3). Valideratext.se hjälper dig att skriva texter som är enkla och lätta att förstå.
Version: 1.0
Author: Flowcom AB, Andreas Ek
Author URI: http://www.flowcom.se
License: GPL2
*/

/*
 * Adds custom buttons to the editor
 */
function valideratext_addbuttons() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
     add_filter('mce_buttons', 'register_valideratext_button');
     add_filter("mce_external_plugins", "register_valideratext_plugin");
   }
}
 
/*
 * Register the button
 */
function register_valideratext_button($buttons) {
   array_push($buttons, "separator", "valideratext");
   return $buttons;
}

/*
 * Register the editor plugin js-file
 */
function register_valideratext_plugin($plugin_array){
    $url = trim(get_bloginfo('url'), "/");
    $url.= "/wp-content/plugins/valideratext/js/editor_plugin.js";     
    $plugin_array["valideratext"] = $url;
    return $plugin_array;
}
 
// init process for button control
add_action('init', 'valideratext_addbuttons');
?>