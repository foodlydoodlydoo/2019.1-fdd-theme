<?php
/**
 * Single Post
 *
 * @package Fdd\Template_Parts\Single
 */

/*
use Fdd\Theme\Utils\Images;

$image = Images::get_post_image('full_width');
 */

function extract_category_slugs($accumulator, $entry) {
  if ($entry->taxonomy == 'category') {
    $accumulator = array_merge($accumulator, array('category-' . $entry->slug));
  }
  return $accumulator;
}

$category_slugs = join(' ', array_reduce(get_the_category(), 'extract_category_slugs', array()));

?>

<!-- Single Content Section -->
<section class="single <?php echo $category_slugs; ?>" id="<?php echo esc_attr($post->ID); ?>">
  <!--div class="single__image" data-normal="<?php echo esc_url($image['image']); ?>"></div-->
  <header>
    <h1 class="single__title">
      <?php the_title();?>
    </h1>
  </header>
  <div class="single__content content-style content-media-style">
    <?php the_content();?>
  </div>
  <?php require locate_template('template-parts/parts/google-rich-snippets.php');?>
</section>
