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

add_action('init', 'Register_Non_Hierarchical_Taxonomy_Book_tag',0);



/**
 * Creating fields for Book 
 */
function Book_Meta_fields() 
{
    ?>
    <div>  
        
        <label for='author-first-name'>Author First Name :</label>
        <input id='author-first-name' type='text' value='<?php echo get_post_meta(get_the_ID(), 'Author First Name', true); ?>' name='author-first-name' ><br>
        <label for='author-lirst-name'>Author Last Name :</label>
        <input id='author-last-name' type='text' value='<?php echo get_post_meta(get_the_ID(), 'Author Last Name', true); ?>' name='author-last-name' ><br>
        <label for='book-price'>Price :</label>
        <input id='book-price' type='Integer' name='book-price' value='<?php echo get_post_meta(get_the_ID(), 'Book Price', true); ?>'><br>
        <label for='book-publisher'>Publisher :</label>
        <input id='book-publisher' type='text' name='book-publisher' value='<?php echo get_post_meta(get_the_ID(), 'Book Publisher', true); ?>'><br>
        <label for='published-year'>Year :</label>
        <input id='published-year' type='text' name='published-year' value='<?php echo get_post_meta(get_the_ID(), 'Published year', true); ?>'>  <br>
        <label for='edition'>Edition :</label>
        <input  id='edition' type='text' name='edition' value='<?php echo get_post_meta(get_the_ID(), 'Edition', true); ?>'><br>
        <label for='book-url'>URL :</label>
        <input  id='book-url' type='text' name='book-url' value='<?php echo get_post_meta(get_the_ID(), 'Book URL', true); ?>'><br>

    </div>
    <?php
}


/**
 * Creating  Meta Box For Books
 *
 * @return void
 */
function Add_Book_Meta_box() 
{
    add_meta_box("book-meta-box", 'Book Meta Box', 'Book_Meta_fields', 'book');
}

add_action('add_meta_boxes', 'Add_Book_Meta_box');




/**
 * Saving the data in post meta table
 * 
 * @return void
 */
function Save_Meta_Data_book($post_id) {

    //Save data for author first name
    $field_data = $_POST['author-first-name'];
    if(isset($_POST['author-first-name'])) {
        if(get_post_meta($post_id, 'Author First Name', true) != '') {
            update_post_meta($post_id, 'Author First Name', $field_data);
        }
        else {
            add_post_meta($post_id, 'Author First Name', $field_data);   
        }
    }

    //Save data for author last name
    $field_data = $_POST['author-last-name'];
    if(isset($_POST['author-last-name'])) {
        if(get_post_meta($post_id, 'Author Last Name', true) != '') {
            update_post_meta($post_id, 'Author Last Name', $field_data);
        }
        else {
            add_post_meta($post_id, 'Author Last Name', $field_data);   
        }
    }

    //save meta data for Book Price
    $field_data = $_POST['book-price'];
    if(isset($_POST['book-price'])) {
        if(get_post_meta($post_id, 'Book Price', true) != '') {
            update_post_meta($post_id, 'Book Price', $field_data);
        }
        else {
            add_post_meta($post_id, 'Book Price', $field_data);   
        }
    }

    //save meta data for book Publisher
    $field_data = $_POST['book-publisher'];
    if(isset($_POST['book-publisher'])) {
        if(get_post_meta($post_id, 'Book Publisher', true) != '') {
            update_post_meta($post_id, 'Book Publisher', $field_data);
        }
        else {
            add_post_meta($post_id, 'Book Publisher', $field_data);   
        }
    }

    //save meta data for Publisherd year
    $field_data = $_POST['published-year'];
    if(isset($_POST['published-year'])) {
        if(get_post_meta($post_id, 'Published year', true) != '') {
            update_post_meta($post_id, 'Published year', $field_data);
        }
        else {
            add_post_meta($post_id, 'Published year', $field_data);   
        }
    }

    //save meta data for Edition 
    $field_data = $_POST['edition'];
    if(isset($_POST['edition'])) {
        if(get_post_meta($post_id, 'Edition', true) != '') {
            update_post_meta($post_id, 'Edition', $field_data);
        }
        else {
            add_post_meta($post_id, 'Edition', $field_data);   
        }
    }

    //save meta data for book URl
    $field_data = $_POST['book-url'];
    if(isset($_POST['book-url'])) {
        if(get_post_meta($post_id, 'Book URL', true) != '') {
            update_post_meta($post_id, 'Book URL', $field_data);
        }
        else {
            add_post_meta($post_id, 'Book URL', $field_data);   
        }
    }
}

add_action('save_post', 'Save_Meta_Data_book');



/**
 * Creating settings page for book
 *
 * @return void
 */
function Book_Setting_Page_book()
{

    if(isset($_POST['currency']) && isset($_POST['no_of_post']))
    {
        $currency=$_POST['currency'];
        $no_of_post=$_POST['no_of_post'];

        update_option('book_currency', $currency);
        update_option('book_no_of_post', $no_of_post);
    }
    ?>

    <h2>
        Hii This is Book Setting Page
    </h2>
    <h4>Here you can set your currency no of post per page</h4>
    <form>
        <div id='currency-container'>
            <label for='currency'>Currency</label>
            <select id='currency' name='currency'> 
                <option value='₹'>₹</option>
                <option value='$'>$</option>
                <option value='€'>€</option>
            </select>
        </div><br><br>
        <div id='no_of_post'>
            <label>No of Post Per page</label>
            <input type='number' id='no_of_post'><br><br>
        </div><br>
        <input type="submit" class="button-primary" value='<?php _e('Save changes'); ?>' />
    <form>

    <?php   
    
}

/**
 * Adding a MEnu Page for Book
 *
 * @return void
 */
function Add_Menu_Page_book()
{
  //  add_menu_page('Books Setting', 'Books Setting', 'manage_options', 'book-setting-page', 'Book_Setting_Page_book');

    //add_submenu_page('books', 'Books Setting', 'Books Setting', 'manage_options', 'book-setting-page', 'Book_Setting_Page_book');
    add_submenu_page(
        'edit.php?post_type=book', //$parent_slug
        'Book Settings Page',  //$page_title
        'Book Settings',        //$menu_title
        'manage_options',           //$capability
        'book_Settings-page', //menu slug
        'Book_Setting_Page_book' //$function
    );

}

add_action('admin_menu', 'Add_Menu_Page_book');
