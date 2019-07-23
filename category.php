<?php
/**
 * Display regular index/home page
 *
 * @package Fdd
 */

use Fdd\Theme\Pagination;

Fdd\Theme\Utils\Images::set_image_sizes_mode('category');

get_header();

if (have_posts()) {
  $category = get_queried_object();
  set_query_var("category", $category);
  set_query_var("wrapper_class", "ArticleWrappingCategory");

  get_template_part('template-parts/listing/categories/vgde');

  Pagination::put($category->name, 'More', 'Newer');
}

get_footer();
