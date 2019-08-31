<?php
/**
 * Empty Article
 *
 * @package Fdd\Template_Parts\Listing\Articles
 */

?>

<article class="article-empty">
  <?php printf(esc_html__('We can\'t find anything for %s'), '<span>' . get_search_query() . '</span>');?>
</article>
