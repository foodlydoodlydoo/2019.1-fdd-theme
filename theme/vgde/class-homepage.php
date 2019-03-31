<?php
/**
 * Shared php functions for producing the vgde home page template
 *
 * @since   1.0.0
 * @package Fdd\Theme\vgde
 */

namespace Fdd\Theme\vgde;

class ArticleWrapping {

  private $order = 0;

  public function before_article($post_order_var_name) {
    if ($this->$order == 1) {
      echo '<div class="oldish">';
    }
    // Makes visible in the template
    set_query_var($post_order_var_name, $post_order);
  }

  public function after_article() {
    ++$this->$order;
  }

  public function at_limit($limit) {
    return $this->$order >= $limit;
  }

  public function after_grid() {
    if ($this->$order > 1) {
      echo '</div>';
    }
  }

}
