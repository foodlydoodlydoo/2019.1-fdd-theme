<?php
/**
 * List Simple Article
 *
 * @package Fdd\Template_Parts\Listing\Articles
 */

use Fdd\Theme\Utils\Excerpt;
use Fdd\Theme\Utils\Images;

$image = Images::get_post_image('fdd:listing:search-article');

$excerpt = Excerpt::get_excerpt(get_the_excerpt(), 9999);

?>
<div class="infscroll-item-selector">
  <article class="article-list">
    <a class="article-permalink" href="<?php the_permalink();?>">
      <div class="article-list__container">
        <div class="article-list__image">
          <img src="<?php echo esc_attr($image['image']); ?>"
              srcset="<?php echo esc_attr($image['srcset']); ?>"
              sizes="<?php echo esc_attr($image['sizes']); ?>"
              alt="<?php echo esc_attr(wp_strip_all_tags(get_the_title()));?>">
        </div>
        <div class="article-list__content">
          <header>
            <h2 class="article-list__heading">
              <?php echo highlight_search_term(wp_strip_all_tags(get_the_title())); ?>
            </h2>
          </header>
          <div class="article-list__excerpt">
            <?php echo highlight_search_term($excerpt);?>
          </div>
          <div class="article-grid__underline"></div>
        </div>
      </div>
      <?php require locate_template('template-parts/parts/google-rich-snippets.php');?>
    </a>
  </article>
</div>
