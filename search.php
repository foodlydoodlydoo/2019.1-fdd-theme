<?php
/**
 * Display regular search page with
 * title and regular listing sorted by date
 *
 * @package Fdd
 */

use Fdd\Theme\Pagination;

Fdd\Theme\Utils\Images::set_image_sizes_mode('search');

get_header();

if (have_posts()) {
  ?>

  <!-- Page Title -->
  <header><h1>
  <?php printf(esc_html__('Search: %s', 'fdd'), '<span>' . get_search_query() . '</span>');?>
  </h1></header>
<?php }?>

<!-- Listing Section -->

<div class="fdd-search-list infscroll-content-selector">

<?php
$had_posts = have_posts();
if ($had_posts) {
  while (have_posts()) {
    the_post();
    get_template_part('template-parts/listing/articles/list');
  }
} else {
  get_template_part('template-parts/listing/articles/empty');
}

?>

</div>

<?php

if ($had_posts) {
  Pagination::put('search results', 'More', 'Previous');
}

get_footer();
