<?php
/**
 * Display regular index/home page
 *
 * @package Fdd
 */

Fdd\Theme\Utils\Images::set_image_sizes_mode('home');

get_header();

$categories = [
  'recipes',
  'food-art',
  'behind-the-scenes',
];

foreach ($categories as $category_slug) {
  $category = get_category_by_slug($category_slug);
  query_posts("cat=$category->term_id");

  if (have_posts()) {
    set_query_var("category", $category);
    set_query_var("wrapper_class", "ArticleWrappingHomepage");
    set_query_var("article_limit", 7);

    get_template_part('template-parts/listing/categories/vgde');
  }
}

get_footer();
