<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://localhost/WordPress/
 * @since             1.0.0
 * @package           Book_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       WP Book Plugin
 * Plugin URI:        http://localhost/WordPress/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Tejas Patle
 * Author URI:        http://localhost/WordPress/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       book-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BOOK_PLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-book-plugin-activator.php
 */
function activate_book_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-book-plugin-activator.php';
	Book_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-book-plugin-deactivator.php
 */
function deactivate_book_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-book-plugin-deactivator.php';
	Book_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_book_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_book_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-book-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_book_plugin() {

	$plugin = new Book_Plugin();
	$plugin->run();

}
run_book_plugin();

 
function Register_Custom_Post_Type_books()
{

    $labels= array(
        'name'=>'Books',
        'singular-name'=>'Book'
    );
    
    $supports= array('title','editor','thumbnail','comments','excerpts');
        
    $options = array(
        'labels' => $labels,
        'public'=> true,
        'rewrite'=>array('slug'=>'book'),
        'supports'=>$supports,
        'taxonomies'=> array('book-catagory','book-tag')
    );

    register_post_type( "Book", $options);
}

add_action('init', 'Register_Custom_Post_Type_books');
    


/**
 *  Created custom hierarchical taxonomy 
 * **/
function Register_Custom_Hierarchical_Taxonomy_Book_catagery() {

    $labels=array(
        'name'=>'Books Catagories',
        'singular-name'=>'Book Catagory'
    );
    
    $options = array(
        'labels' => $labels,
        'hierarchical'=> true,
        'rewrite'=> array('slug' => 'book-catagory'),
        'show_admin_column'=>true
    );
    
    register_taxonomy('book-catagory', array('book'), $options);
}
   
add_action('init', 'Register_Custom_Hierarchical_Taxonomy_Book_catagery', 0);




/**
 * Cretaing non hierarchical taxonomy 
 *
 * @return void
 */
function Register_Non_Hierarchical_Taxonomy_Book_tag() {

    $labels = array(
        'name' => 'Book Tags',
        'singular_name' =>  'Book Tag',
        'parent_item' => null,
        'parent_item_colon' => null,
        'public'=> true
    ); 

    $options=array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => array( 'slug' => 'book-tag' ),
    );

    register_taxonomy('book-tag', 'book', $options);
}   

add_action('init', 'Register_Non_Hierarchical_Taxonomy_Book_tag', 0);

