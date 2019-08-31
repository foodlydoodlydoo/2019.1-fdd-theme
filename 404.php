<?php
/**
 * 404 error page
 *
 * @package Fdd
 */

use Fdd\Helpers\General_Helper;

get_header();

$term_from_path = trim(preg_replace("/[^\w\d]/", " ", $_SERVER['REQUEST_URI']));
$nothing_found_img = General_Helper::get_manifest_assets_data('nothning-found.png');

?>

<div class="error404__wrap">
  <?php
    $search = new WP_Query("s=$term_from_path&showposts=-1");
    if ($search->found_posts > 0) {
      ?>
      <h2>Page not found, but...</h2>
      <div class="something-found border">
      ...maybe the articles below is what you are looking for:
      </div>
      <?php
      ?>
      <div class="fdd-search-list infscroll-content-selector">
      <?php
      $limit = 1;
      $has_more = $search->have_posts();
      while ($has_more && $limit--) {
        $search->the_post();
        get_template_part('template-parts/listing/articles/list'); // needs to be written
        $has_more = $search->have_posts();
      }
      ?></div><?php
      if ($has_more) {
        ?><div class="something-found">
          ...and there is more:
        </div><?php
        set_query_var("s", $term_from_path);
        get_search_form();
      }
    } else {
      ?>
      <h2>Page not found :(</h2>
      <div class="nothing-found">
      <div class="not-found__imagewrap"><img src="<?php echo $nothing_found_img;?>" alt="Nothing found"/></div>
      Sorry, we can't find this page or anything resemblimg it on <span class="site"><?php echo get_bloginfo('name'); ?></span>.
      </div>
      <?php
    }
  ?>
</div>

<?php
get_footer();
