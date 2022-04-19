<?php
/**
* Template Name: News Loading using Load More Button and Ajax
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
         <div class="post-content">
          <?php while ( $query->have_posts() ) : ?>
        <?php $query->the_post(); ?>
            
        <?php echo get_template_part( 'template-parts/content/content', 'excerpt' ) ; ?>
      <?php endwhile;  ?>
        </div>
       <?php     if ( ! $max_page ) {
                $max_page = $query->max_num_pages;
            }
        // $nextpage = (int) $paged + 1;

            $nexPost =  next_posts( $max_page, false );
            //$attr = apply_filters( 'next_posts_link_attributes', '' )
            ?>

    <?php if($paged!=$max_page): ?>
      <a id="load-more" href="<?=$nexPost?>" data-current-page="<?=$paged?>" data-maxPage="<?=$max_page?>">
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
      var nextPage = $(this).attr('href');
      var currentPage = $(this).attr('data-current-page');
      var maxPage = $(this).attr('data-maxPage');

    var ajaxUrl = "<?=admin_url( 'admin-ajax.php' )?>";
    var data = {
	  'action': 'display_post_contents',
      'next_page': nextPage,
      'current_page': currentPage,
      'max_page': maxPage
		};

    jQuery.ajax({
         type : "post",
         url : ajaxUrl,
         data : data,
         success: function(response) {
            $( ".post-content" ).append(response).slideDown();
         }
      });
      /* 
       *
       * Hide the loader button after all the posts are displayed
       */

    if(( parseInt(currentPage) + 1 ) != parseInt(maxPage)) {
              $("#load-more").attr("data-current-page", parseInt(currentPage) + 1);
        }else {
          $("#load-more").remove();
        }

    });
  });
</script>
<?php get_footer();