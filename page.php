<?php
/**
 * Display regular page
 *
 * @package Fdd
 */

get_header();

if (have_posts()) {
  while (have_posts()) {
    the_post();
    get_template_part('template-parts/single/page');
  }
}

get_footer();

if (true) {
  $str = file_get_contents('test-vid.txt');

  fdd_reg_shortcodes();
  $str = do_shortcode($str);
  print_r($str);

  $post = array('post_content' => $str, 'post_type' => 'post', 'terms' => array(
    array('domain' => 'category', 'slug' => 'recipes'),
  ));
  $post = fdd_filter_wp_import_post_data_raw($post);

  file_put_contents('test.out', $post['post_content']);

  echo "\nSuccess!\n";
}
