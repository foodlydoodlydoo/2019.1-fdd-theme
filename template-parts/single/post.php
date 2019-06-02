<?php
/**
 * Single Post
 *
 * @package Fdd\Template_Parts\Single
 */

$category_slugs = array_reduce(get_the_category(), function ($accumulator, $entry) {
  if ($entry->taxonomy == 'category') {
    $accumulator = array_merge($accumulator, array($entry->slug));
  }
  return $accumulator;
}, array());

$image_mode = array_reduce($category_slugs, function ($accumulator, $cat) {
  if (in_array($cat, ['recipes', 'food-art', 'behind-the-scenes'])) {
    $accumulator = $cat;
  }
  return $accumulator;
});

Fdd\Theme\Utils\Images::set_image_sizes_mode($image_mode);

$category_slug_classes = join(' ', array_map(function ($cat) {
  return "category-$cat";
}, $category_slugs));
?>

<!-- Single Content Section -->
<section class="single <?php echo $category_slug_classes; ?>" id="<?php echo esc_attr($post->ID); ?>">
  <header>
    <div class="single__pre_title"></div>
    <h1 class="single__title">
      <?php the_title();?>
    </h1>
    <div class="single__title_excerpt_div"></div>
    <div class="single__excerpt">
      <?php the_excerpt();?>
    </div>
  </header>
  <div class="single__content content-style content-media-style">
    <?php the_content();?>
  </div>
  <?php require locate_template('template-parts/parts/google-rich-snippets.php');?>
</section>
