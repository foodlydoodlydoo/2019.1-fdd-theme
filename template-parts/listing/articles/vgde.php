<?php
/**
 * VGDE Single article
 *
 * @package Fdd\Template_Parts\Listing\Articles
 */

use Fdd\Theme\Utils\Excerpt;
use Fdd\Theme\Utils\Images;

$image = Images::get_post_image($post_order_in_category == 0
  ? 'fdd:listing:first-article'
  : 'fdd:listing:oldish-article');

$excerpt = Excerpt::get_excerpt(get_the_excerpt(), 22);

?>

<article class="article-grid">
<a class="article-permalink" href="<?php the_permalink();?>">
<div class="article-grid-wrapper">

<div class="article-grid__figure">
<img src="<?php echo esc_attr($image['image']); ?>"
     srcset="<?php echo esc_attr($image['srcset']); ?>"
     sizes="<?php echo esc_attr($image['sizes']); ?>">
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
