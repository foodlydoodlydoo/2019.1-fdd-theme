<?php
/**
 * Display regular search page with
 * title and regular listing sorted by date
 *
 * @package Fdd
 */

use Fdd\Theme\Pagination;

Fdd\Theme\Utils\Images::set_image_sizes_mode('search');

$search_term_patterns = array_filter(explode(" ", esc_html(get_search_query())), function($term) {
  return preg_match("/[\w\d]+/", $term);
});
$search_term_patterns = array_map(function($term) {
  return "/(" . strtolower(trim($term)) . ")/i";
}, $search_term_patterns);

function highlight_search_term($input) {
  global $search_term_patterns;
  foreach ($search_term_patterns as $term) {
    $input = preg_replace($term, "<span class=\"search__highlight\">$1</span>", $input);
  }
  return $input;
}

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
