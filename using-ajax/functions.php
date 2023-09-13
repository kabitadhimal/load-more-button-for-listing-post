<?php

add_action( 'wp_ajax_display_post_contents', 'display_post_contents' );
add_action( 'wp_ajax_nopriv_display_post_contents', 'display_post_contents' );

function display_post_contents() {
  //$currentPage = (int) $_GET['current_page'];
  $offset = (int) $_GET['offset'];
  $limit = (int) $_GET['limit'];
  //$limit = 9;
  $args = [
    'post_type' => 'post',
    'post_status'=> 'publish',
     //'offset' => 1 + (($currentPage - $offset) * $limit),  //offset
     'offset' => $offset,  //offset
    'posts_per_page' => $limit   //limit
  ];
  $query = new WP_Query( $args );

  ob_start();
  if ( $query->have_posts() ) : ?>
    <?php while ( $query->have_posts() ) : ?>
      <?php $query->the_post();
      echo get_template_part('template-parts/content', 'excerpt');
     endwhile;
  endif;
  $contents = ob_get_contents();
  ob_end_clean();

  $response = [
    'total' => $query->found_posts,
    'content' => $contents,
  ];

  wp_send_json($response);
}
