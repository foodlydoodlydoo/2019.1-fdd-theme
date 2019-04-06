<?php
/**
 * VGDE Single article
 *
 * @package Fdd\Template_Parts\Listing\Articles
 */

use Fdd\Theme\Utils\Images;

$image = Images::get_post_image($post_order_in_category == 0 ? 'full_width' : 'medium');

?>

<article class="article-grid">
<a class="article-permalink" href="<?php the_permalink();?>">
<div class="article-grid-wrapper">

<img src="<?php echo esc_url($image['image']); ?>" style="<?php
if ($post_order_in_category > 0) {
  $_height = random_int(250, 500);
  echo "min-height: " . $_height . "px;";
  echo "max-height: " . $_height . "px;";
  echo "height: " . $_height . "px;";
}
?>">

<div class="article-grid__content">
  <header>
    <h2 class="article-grid__heading">
      <?php esc_html(the_title());
print("[$post_order_in_category]");?>
    </h2>
    <div class="article-grid__excert">
      <?php the_excerpt();?>
    </div>
    <div class="article-grid__underline"></div>
  </header>
</div>

</div>
</a>
<?php require locate_template('template-parts/parts/google-rich-snippets.php');?>
</article>
