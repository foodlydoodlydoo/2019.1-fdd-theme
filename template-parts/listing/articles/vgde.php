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
  <img src="<?php echo esc_url($image['image']); ?>">
  <div class="article-grid__content">
    <header>
      <h2 class="article-grid__heading">
        <?php esc_html(the_title());?>
      </h2>
    </header>
  </div>
</a>

<?php require locate_template('template-parts/parts/google-rich-snippets.php');?>
</article>
