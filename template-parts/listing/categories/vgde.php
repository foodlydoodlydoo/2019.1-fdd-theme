<?php
/**
 * Display a single category listing on home page
 *
 * @package Fdd
 */

global $category;

use Fdd\Theme\vgde\ArticleWrapping;

?>
<div class="fdd-category">

<div class="fdd-category-name">
  <?php echo esc_html($category->name); ?>
</div>

<div class="fdd-category-grid">
<?php

$wrapper = new ArticleWrapping;

while (have_posts()) {
  the_post();

  for ($i = 0; $i < 5; ++$i) {
    $wrapper->before_article('post_order_in_category');
    get_template_part('template-parts/listing/articles/vgde');
    $wrapper->after_article();
  }
}

$wrapper->after_grid();

?>
<div class="fdd-heel"></div>
</div>

</div>
