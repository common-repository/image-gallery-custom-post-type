<?php
if ( !defined( 'ABSPATH' ) ) exit;
/*Plugin Name:Image Gallery Custom Post Type
  Plugin URI: https://wordpress.org/plugins/ 
  Description: This plugin shows the title, gallery image and description in image gallery by using custom  post type.
  Version: 2.0
  Author: Nilesh Ziniwal
*/

class WP_Gallery_list{
    function __construct() {

      add_action( 'init', array( $this, 'nzp_add_gallery_menu' ));
      add_shortcode( 'show_gallery_img', array( $this, 'nzp_show_gallery_func' ) );
      add_action ('wp_enqueue_scripts', array( $this, 'nzp_adding_scripts') ); 
    }
    function nzp_adding_scripts() {        
        wp_register_style('bootstrap_stylesheet', plugin_dir_url( __FILE__ ) .'css/bootstrap.min.css');
        wp_enqueue_style('bootstrap_stylesheet'); 

        wp_register_style('style-css', plugin_dir_url( __FILE__ ) .'css/style.css');  
        wp_enqueue_style('style-css');    

        wp_register_style('font-awesome', plugin_dir_url( __FILE__ ) .'css/font-awesome.css');
        wp_enqueue_style('font-awesome'); 
        wp_register_script('bootstrap_min_js',plugin_dir_url( __FILE__ ) .'js/bootstrap.min.js', array(), '', true);
        wp_enqueue_script('bootstrap_min_js'); 
    }

    /*
      * Actions perform for create the custom post type in backend 
    */

    function nzp_add_gallery_menu() {
      // set up GalleryList labels
      $labels = array(
          'name' => 'Gallery Lists',
          'singular_name' => 'Gallery List',
          'add_new' => 'Add New',
          'add_new_item' => 'Add New',
          'edit_item' => 'Edit item',
          'new_item' => 'New item',
          'all_items' => 'All item',
          'view_item' => 'View item',
          'search_items' => 'Search items',
          'not_found' =>  'No data Found',
          'not_found_in_trash' => 'No data found in Trash', 
          'parent_item_colon' => '',
          'menu_name' => 'Gallery Lists',
      );
      // register post type
      $args = array(
          'labels' => $labels,
          'public' => true,
          'has_archive' => true,
          'show_ui' => true,
          'capability_type' => 'post',
          'hierarchical' => false,
          'rewrite' => array('slug' => 'gallerylist'),
          'query_var' => true,
          'menu_icon' => 'dashicons-format-gallery',
          'supports' => array(
              'title',
              'editor',
              'excerpt',
              'trackbacks',
              'custom-fields',
              'comments',
              'revisions',
              'thumbnail',
              'author',
              'page-attributes'
          )
      );
      register_post_type( 'gallerylist', $args );
   }

    /*
     * Actions perform for shortcode of the plugin
     */

  function nzp_show_gallery_func($atts) { 
    $args = array(
        'post_type' => 'gallerylist',
        'post_status'=>'publish',
        'orderby' => 'ID',
        'order'   => 'DESC' 
                      
    );
    $the_query = new WP_Query( $args );
    // The Loop
    if ( $the_query->have_posts() ) {
      global $post; $i=1;
      echo '<div class="gallery_section">';
      while ( $the_query->have_posts() ) {
        $the_query->the_post(); ?>               
        <div class="gallery_block <?php if($i>=4 && $i<=6){ echo 'row_two'; } ?>">
          <div class="in_block">
            <div class="left"><img src="<?php echo get_the_post_thumbnail_url($post->ID,'full');  ?>" alt="<?php echo get_the_title(); ?>" width="100%" height="auto" /></div>
              <div class="right">
                  <h2><?php echo get_the_title(); ?></h2>
                  <p class="smalldesc"><?php echo $content = get_the_content(); 
                  //echo mb_strimwidth($content, 0, 190, '..');
                  ?></p>
                  <a href="#" class="more">...</a>
              </div>
            <div class="clear"></div>
          </div>
        </div>         
      <?php $i++; }
      echo '<div class="clear"></div></div>';
      wp_reset_postdata();
    } else { echo "No record found"; }
   }    
  } 
new WP_Gallery_list();