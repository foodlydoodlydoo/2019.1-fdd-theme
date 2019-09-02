<?php
/**
 * VGDE Single article
 *
 * @package Fdd\Template_Parts\Listing\Articles
 */

use Fdd\Theme\Utils\Excerpt;
use Fdd\Theme\Utils\Images;

$image = Images::get_post_image($is_front_post
  ? 'fdd:listing:first-article'
  : 'fdd:listing:oldish-article');

$lead_picture_landscape = $is_front_post
  ? Images::get_post_image('fdd:listing:first-article-landscape')
  : false;

$lead_picture_portrait = $is_front_post
  ? Images::get_post_image('fdd:listing:first-article-portrait')
  : false;

$excerpt = Excerpt::get_excerpt(get_the_excerpt(), $is_front_post
  ? 22
  : 30);

$grid_classes = ["article-grid"];
if ($is_first_post) array_push($grid_classes, "first-article");
if ($is_last_post) array_push($grid_classes, "last-article");

$grid_classes = join(' ', $grid_classes);

$has_video = function_exists('get_field') && get_field('has_video');

?>

<article class="<?php echo($grid_classes);?>">
<a class="article-permalink" href="<?php the_permalink();?>">
<div class="article-grid-wrapper">

<div class="article-grid__figure">
<?php 
/* 
$lead_picture_landscape
$lead_picture_portrait

TODO - Use <picture> with fallback to the image below for the lead article portrail/landscape optimization 
https://developer.mozilla.org/en-US/docs/Web/HTML/Element/picture#The_media_attribute 
*/
?>
<img src="<?php echo esc_attr($image['image']); ?>"
     srcset="<?php echo esc_attr($image['srcset']); ?>"
     sizes="<?php echo esc_attr($image['sizes']); ?>">
<?php if ($has_video) { ?>
  <span class="article-grid__is-video"></span>
<?php } ?>
</div>

<div class="article-grid__content">
  <header>
    <h2 class="article-grid__heading">
      <?php esc_html(the_title());?>
    </h2>
    <div class="article-grid__excerpt">
      <?php echo $excerpt; ?>
    </div>
    <div class="article-grid__underline"></div>
  </header>
</div>

</div>
</a>
<?php require locate_template('template-parts/parts/google-rich-snippets.php');?>
</article>
