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

if (false) {

// IMPORT TEST - REMOVE FROM PRODUCTION!
  $cats = ['recipes', 'recipes', 'recipes', 'recipes', 'food-art'];
  foreach (array('prep-in', 'prep-sec', 'vid', 'imgs', 'art1') as $test) {
    $str = file_get_contents("test-$test.txt");

    fdd_reg_shortcodes();
    $str = do_shortcode($str);
    print_r($str);

    $post = array('post_content' => $str, 'post_type' => 'post', 'terms' => array(
      array('domain' => 'category', 'slug' => array_shift($cats)),
    ));
    $post = fdd_filter_wp_import_post_data_raw($post);

    file_put_contents("test-$test.out", $post['post_content']);

    echo "\nSuccess for $test!\n";
  }
// IMPORT TEST - REMOVE FROM PRODUCTION!

}
