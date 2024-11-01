<?php
/*
Plugin Name:Wp Faqs Jquery Slide
Author:Renu Sharma.
Author URI:http://www.renuwp.wordpress.com/
Description:This plugin creates a faq slides using custom wordpress post
Version: 1.1
Author URI:http://www.renuwp.wordpress.com/
License: A "Slug" license name e.g. GPL2
*/

/*  Copyright 2014  Simple tabs - Renu Sharma (email : renusharma7@gmail.com)
 
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


 
 
function wp_custom_faqs() {
	$labels = array(
		'name'               => _x( 'Faqs', 'post type general name' ),
		'singular_name'      => _x( 'Faq', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'book' ),
		'add_new_item'       => __( 'Add New Faq' ),
		'edit_item'          => __( 'Edit Faq' ),
		'new_item'           => __( 'New Faq' ),
		'all_items'          => __( 'All Faqs' ),
		'view_item'          => __( 'View Faq' ),
		'search_items'       => __( 'Search Faqs' ),
		'not_found'          => __( 'No Faqs found' ),
		'not_found_in_trash' => __( 'No Faqs found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Faqs'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds our Faqs and Faq specific data',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
		'has_archive'   => true,
	);
	register_post_type( 'Faq', $args );	
}
add_action( 'init', 'wp_custom_faqs' );

function faq_updated_messages( $messages ) {
	global $post, $post_ID;
	$messages['Faq'] = array(
		0 => '', 
		1 => sprintf( __('Faq updated. <a href="%s">View Faq</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Faq updated.'),
		5 => isset($_GET['revision']) ? sprintf( __('Faq restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Faq published. <a href="%s">View Faq</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Faq saved.'),
		8 => sprintf( __('Faq submitted. <a target="_blank" href="%s">Preview Faq</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Faq scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Faq</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Faq draft updated. <a target="_blank" href="%s">Preview Faq</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'faq_updated_messages' );


//faq category
function faq_taxonomies_product() {
	$labels = array(
		'name'              => _x( 'Faq Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Faq Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Faq Categories' ),
		'all_items'         => __( 'All Faq Categories' ),
		'parent_item'       => __( 'Parent Faq Category' ),
		'parent_item_colon' => __( 'Parent Faq Category:' ),
		'edit_item'         => __( 'Edit Faq Category' ), 
		'update_item'       => __( 'Update Faq Category' ),
		'add_new_item'      => __( 'Add New Faq Category' ),
		'new_item_name'     => __( 'New Faq Category' ),
		'menu_name'         => __( 'Faq Categories' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
	);
	register_taxonomy( 'faq_category', 'faq', $args );
}
add_action( 'init', 'faq_taxonomies_product', 0 );

    wp_register_style('wptuts-jquery-ui-style', WP_PLUGIN_URL.'/wp-faqs-jquery-slide/css/style-faqs.css');
    wp_enqueue_style('wptuts-jquery-ui-style');
	    wp_register_script('wptuts-custom-js', WP_PLUGIN_URL.'/wp-faqs-jquery-slide/js/faq-effect.js', array('jquery-ui-accordion'), '', true);
    wp_enqueue_script('wptuts-custom-js');
function  reg_faqs(){
}
add_shortcode('Faqs', function(){
    $posts = get_posts(array(
        'numberposts' => 10,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'post_type' => 'Faq',
    ));
    $faq = '<div class="container">'; ///Open the container
    foreach ( $posts as $post ){

        $tabs .= sprintf(('<h2 class="acc_trigger"><a href="">%1$s</a></h3><div class="acc_container">%2$s</div>'), // Generate the markup for each Question
            $post->post_title,
            wpautop($post->post_content)
        );
    }
    $tabs .= '</div>'; //Close the Container
    return $tabs; //Return the HTML
});
//add_action( 'after_setup_theme', 'purple_bellies_theme_setup', 11 );
register_activation_hook( __FILE__, 'reg_faqs' );