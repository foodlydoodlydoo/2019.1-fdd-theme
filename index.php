<?php
/**
 * Display regular index/home page
 *
 * @package Fdd
 */

Fdd\Theme\Utils\Images::set_image_sizes_mode('home');

function print_sticky_post($category_slug) {
  $category = get_category_by_slug($category_slug);
  query_posts(array(
    "cat" => $category->term_id,
    "post__in" => get_option("sticky_posts")
  ));
  
  if (have_posts()) {
    set_query_var("category", $category);

    get_template_part('template-parts/parts/home-top-sticky');
  }
}

function print_category($category_slug) {
  $category = get_category_by_slug($category_slug);
  query_posts("cat=$category->term_id");

  if (have_posts()) {
    set_query_var("category", $category);
    set_query_var("wrapper_class", "ArticleWrappingHomepage");
    set_query_var("article_limit", 7);

    get_template_part('template-parts/listing/categories/vgde');
  }
}

get_header();

?>

<div class="home-top-sticky-posts__wrap">

<?php

print_sticky_post('recipes');
print_sticky_post('behind-the-scenes');
print_sticky_post('food-art');

?>

</div>

<?php

echo FDD\Core\get_custom_content("shop_cta_on_homepage");

print_category('recipes');

echo FDD\Core\get_custom_content("work_with_us_cta_on_homepage");

print_category('food-art');

echo do_shortcode('[fdd_aweber_form name="subscribe-inline-1" title="' . FDD\Core\get_custom_content("subscribe_title_homepage") . '"]');

print_category('behind-the-scenes');

get_footer();
