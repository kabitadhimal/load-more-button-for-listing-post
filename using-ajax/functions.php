<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 */
add_action( 'wp_ajax_display_post_contents', 'display_post_contents' );
add_action( 'wp_ajax_nopriv_display_post_contents', 'display_post_contents' );

function display_post_contents() {
	global $wpdb; // this is how you get access to the database
	/* Get the current page content */
	$currentPage= $_POST['current_page'] + 1;
    $args = [ 
              'post_type' => 'post', 
              'paged' => $currentPage, 
              'post_status'=> 'publish',
              'posts_per_page' => 3
            ];
    $query = new WP_Query( $args );
        if ( $query->have_posts() ) : ?>
          <?php while ( $query->have_posts() ) : ?>
        <?php $query->the_post(); ?>
        <?php echo get_template_part( 'template-parts/content/content', 'excerpt' ) ; ?>
      <?php endwhile;  
	  endif;
	wp_die(); // this is required to terminate immediately and return a proper response
}
