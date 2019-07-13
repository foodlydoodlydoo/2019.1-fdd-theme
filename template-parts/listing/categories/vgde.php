<?php
/**
 * Display a single category listing on home page
 *
 * @package Fdd
 */

$wrapper_class = "Fdd\\Theme\\vgde\\$wrapper_class";
$wrapper = new $wrapper_class;

?><div id="<?php echo esc_html($category->slug); ?>" class="fdd-category"><?php

$wrapper->before_grid($category);

?><div class="fdd-category-grid"><?php

while ($more_posts = have_posts()) {
  the_post();

  for ($i = 0; $i < 1; ++$i) {
    $wrapper->before_article('post_order_in_category');
    get_template_part('template-parts/listing/articles/vgde');
    $wrapper->after_article();
    if ($wrapper->at_limit($article_limit)) {
      break 2;
    }
  }
}

$wrapper->after_grid($more_posts);

?><div class="fdd-heel"></div><?php

$wrapper->tail($more_posts);

?>
</div>
</div>
