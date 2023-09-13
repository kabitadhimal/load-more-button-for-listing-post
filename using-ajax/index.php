<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package newtheme
 */

get_header(); ?>
  <div class="container article-blog">
    <?php
    $recentPost = new WP_Query([
      'post_type' => 'post',
      'posts_per_page' => 1,
      'post__not_in' => get_option( 'sticky_posts' ),
      'post_status' => "publish"
    ]);

    ?>
    <article class="row article-sticky">
      <?php while ($recentPost->have_posts()) : $recentPost->the_post();
        $image = wp_get_attachment_url(get_post_thumbnail_id());
        $title = get_the_title();
        ?>
        <div class="col-7">
          <figure class="article-image">
            <img class="avatar" src="<?=$image?>" alt="<?=$title?>">
          </figure>
        </div>
        <div class="col-5 theme-info--lighter">
          <div class="article-sticky--content">
            <span class="article-published text-primary mb-1"><?= get_the_date("F | Y") ?></span>
            <h4 class="article-title"><?=$title?></h4>
            <?php the_excerpt(); ?>
            <a class="admin-hidden article-more text-primary" href="<?php the_permalink(); ?>">READ MORE</a>
          </div>
        </div>
      <?php endwhile;
      wp_reset_postdata(); ?>
    </article>
    <div class="row js-post-content">
      <?php
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
      $args = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'offset' => 1,
        'posts_per_page' => 9
      ];

      $query = new WP_Query($args);
      while ($query->have_posts()) : $query->the_post();
        echo get_template_part('template-parts/content', 'excerpt');
        ?>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    </div>

    <div style="text-align: center;">
      <div class="js-spinner d-none">
        <img height="100" width="100" src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg">
      </div>
      <button class="btn btn-primary-light js-load-more" data-current-page="1">
        Load More
      </button>
    </div>
  </div>
<style>
  .d-none{
    display: none !important;
  }
</style>
  <script>
    (function () {
      const loader = document.querySelector('.js-spinner');
      const resultDiv = document.querySelector(".js-post-content");
      const loadMoreBtn = document.querySelector(".js-load-more");

      let limit = 9;
      let offset = 1;

      loadMoreBtn.addEventListener('click', async function() {

        offset += limit ;
        const data = `action=display_post_contents&limit=${limit}&offset=${offset}`;

        loader.classList.remove('d-none');
        loadMoreBtn.classList.add('d-none');

        try{
          const response = await fetch(`${ajax_object.ajaxURL}?${data}`);
          if(!response.ok){
            const errorData = await response.json();
            throw new Error("Error while loading more content", {cause: errorData})
          }
          const responseData = await response.json();
         // console.log(responseData);
          resultDiv.innerHTML += responseData.content;

          if((offset + limit) < parseInt(responseData.total)){
            loadMoreBtn.classList.remove('d-none');
          }
        }catch (e) {
          loadMoreBtn.classList.remove('d-none');
          console.log("Error", e.message);
        }finally {
          loader.classList.add('d-none');
        }
      });
    })()
  </script>

<?php
get_footer();
