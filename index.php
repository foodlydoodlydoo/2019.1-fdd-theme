<?php
/**
 * Display regular index/home page
 *
 * @package Fdd
 */

get_header();

$categories = [
  'recipes',
  'food-art',
];

foreach ($categories as $category_slug) {
  $category = get_category_by_slug($category_slug);
  query_posts("cat=$category->term_id");

  if (have_posts()) {
    get_template_part('template-parts/listing/categories/vgde');
    the_posts_pagination();
  } else {
    get_template_part('template-parts/listing/articles/empty');
  }
}

get_footer();
