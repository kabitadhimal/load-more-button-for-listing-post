<?php
/**
* Template Name: News Loading using Load More Button and Javascript
*
*/
get_header();
?>
<div class="posts-container" id="post-container">
    <?php 
    $term = get_queried_object();
    $inputCatSlug = "";
    if( isset( $term ) && !empty( $term )) {
        $inputCatSlug = $term->slug;
        $inputCatName = $term->name;
    }
    //build query
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    $args = [ 
              'post_type' => 'post', 
              'paged' => $paged, 
              'post_status'=> 'publish',
              'posts_per_page' => 3
            ];
    $query = new WP_Query( $args );
        if ( $query->have_posts() ) : ?>
          <?php while ( $query->have_posts() ) : ?>
        <?php $query->the_post(); ?>
            
        <?php echo get_template_part( 'template-parts/content/content', 'excerpt' ) ; ?>
      <?php endwhile; 
      
            if ( ! $max_page ) {
                $max_page = $query->max_num_pages;
            }
        // $nextpage = (int) $paged + 1;

            $nexPost =  next_posts( $max_page, false );
            //$attr = apply_filters( 'next_posts_link_attributes', '' )
            ?>

    <?php if($paged!=$max_page): ?>
      <a id="load-more" href="<?=$nexPost?>">
             Load More Button
          </a>
    <?php 
      endif;

      wp_reset_postdata(); 
endif; ?>
  
<script>
  jQuery(document).ready(function($){
        $('#post-container').on('click', '#load-more', function(e) {
    // prevent new page load
      e.preventDefault();
    // store next page number
      var next_page = $(this).attr('href');
    // remove older posts button from DOM
      $(this).remove();
    // ajax older posts below existing posts
      $('#post-container').append(
        $('<div />').load(next_page + ' #post-container')
      );
    });
  });
</script>
<?php get_footer();