<div class="home-top-sticky-post">
<?php

use Fdd\Theme\Utils\Excerpt;
use Fdd\Theme\Utils\Images;

if (have_posts()) {
  the_post();

  $image = Images::get_post_image('fdd:listing:sticky-article');

  $grid_classes = ["home-top-sticky-grid"];
  $grid_classes = join(' ', $grid_classes);

  ?>

  <article class="<?php echo($grid_classes);?>">
  <a class="home-top-sticky-permalink" href="<?php echo get_category_link($category);?>">
  <div class="home-top-sticky-grid-wrapper">

  <div class="home-top-sticky-grid__figure">
  <picture>
    <img src="<?php echo esc_attr($image['image']); ?>"
        srcset="<?php echo esc_attr($image['srcset']); ?>"
        sizes="<?php echo esc_attr($image['sizes']); ?>"
        alt="<?php echo esc_attr(wp_strip_all_tags(get_the_title()));?>">
  </picture>
  </div>

  <div class="home-top-sticky-grid__content">
    <div class="home-top-sticky-name">
      <?php echo esc_html($category->name) ?>
    </div>
  </div>

  </div>
  </a>
  <?php require locate_template('template-parts/parts/google-rich-snippets.php');?>
  </article>

<?php } ?>
</div>
