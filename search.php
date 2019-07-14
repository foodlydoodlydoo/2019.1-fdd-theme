<?php
/**
 * Display regular search page with
 * title and regular listing sorted by date
 *
 * @package Fdd
 */

Fdd\Theme\Utils\Images::set_image_sizes_mode('search');

get_header();

if (have_posts()) {
  ?>

  <!-- Page Title -->
  <header><h1>
  <?php printf(esc_html__('Searching: %s', 'fdd'), '<span>' . get_search_query() . '</span>');?>
  </h1>
  </header>
<?php }?>

<!-- Listing Section -->

<div class="fdd-search-list infscroll-content-selector">

<?php
if (have_posts()) {
  while (have_posts()) {
    the_post();
    get_template_part('template-parts/listing/articles/list');
  }

  the_posts_pagination(
    array(
      'screen_reader_text' => ' ',
    )
  );
} else {
  get_template_part('template-parts/listing/articles/empty');
}

?>

</div>

<?php

get_footer();
