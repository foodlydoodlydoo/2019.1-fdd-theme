<?php
/**
 * Display regular index/home page
 *
 * @package Fdd
 */

get_header();

if (have_posts()) {
  $category = get_the_category();
  set_query_var("category", $category[0]);
  set_query_var("wrapper_class", "ArticleWrappingCategory");

  get_template_part('template-parts/listing/categories/vgde');
  the_posts_pagination();
}

get_footer();
