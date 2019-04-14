<?php
/**
 * Display a single category listing on home page
 *
 * @package Fdd
 */

?>
<div id="<?php echo esc_html($category->slug); ?>" class="fdd-category">

<div class="fdd-category-name">
  <?php echo esc_html($category->name); ?>
</div>

<div class="fdd-category-grid">
<?php

$wrapper_class = "Fdd\\Theme\\vgde\\$wrapper_class";
$wrapper = new $wrapper_class;

while (have_posts()) {
  the_post();

  for ($i = 0; $i < 3; ++$i) {
    $wrapper->before_article('post_order_in_category');
    get_template_part('template-parts/listing/articles/vgde');
    $wrapper->after_article();
    if ($wrapper->at_limit($article_limit)) {
      break 2;
    }
  }
}

$wrapper->after_grid();

?>
<div class="fdd-heel"></div>
</div>

</div>
