<?php
/**
 * Display a single category listing on home page
 *
 * @package Fdd
 */

$wrapper_class = "Fdd\\Theme\\vgde\\$wrapper_class";
$wrapper = new $wrapper_class;

?>

<div id="<?php echo esc_html($category->slug); ?>" class="fdd-category infscroll-content-selector">
<div class="fdd-category__wrap">

<?php

$wrapper->before_grid($category);

?><div class="fdd-category-grid infscroll-item-selector"><?php

$more_posts = have_posts();
$first_post = true;

while ($more_posts) {
  the_post();

  $more_posts = have_posts();
  set_query_var("is_first_post", $first_post);
  set_query_var("is_last_post", !$more_posts);

  for ($i = 0; $i < 1; ++$i) {
    $wrapper->before_article('post_order_in_category', 'is_front_post');
    get_template_part('template-parts/listing/articles/vgde');
    $wrapper->after_article();
    if ($wrapper->at_limit($article_limit)) {
      break 2;
    }
  }

  $first_post = false;
}

$wrapper->after_grid($more_posts || true);
$wrapper->tail($more_posts);

?>
</div>
</div>
</div>
