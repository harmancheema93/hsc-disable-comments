<?php 

/**
	Plugin Name: Disable Comments 
	Plugin URI: https://wordpress.org/plugins/page-editor-full-width/
	Description: This WordPress plugin will disable the comments from the selected post type.
	Author: Harmandeep Singh
	Version: 1.0
	Author URI: https://profiles.wordpress.org/harmancheema/
 */

 include( plugin_dir_path( __FILE__ ) . 'includes/hscDisableComments.class.php');
 $hook = new hsc_DisableComments();
 function hsc_activation_functionality(){
 }
 register_activation_hook( __FILE__, 'hsc_activation_functionality' );