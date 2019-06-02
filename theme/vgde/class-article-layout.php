<?php
/**
 * Shared php functions for producing the vgde home page template
 *
 * @since   1.0.0
 * @package Fdd\Theme\vgde
 */

namespace Fdd\Theme\vgde;

class ArticleWrappingHomepage {
  private $order;

  function __construct() {
    $this->order = 0;
  }

  public function before_grid($category) {
    echo "<div class=\"fdd-category-name\">" . esc_html($category->name) . "</div>";
  }

  public function before_article($post_order_var_name) {
    if ($this->order == 1) {
      echo '<div class="oldish">';
    }
    // Makes visible in the template
    set_query_var($post_order_var_name, $this->order);
  }

  public function after_article() {
    ++$this->order;
  }

  public function at_limit($limit) {
    return $limit > 0 && $this->order >= $limit;
  }

  public function after_grid() {
    if ($this->order > 1) {
      echo '</div>';
    }
  }
}

class ArticleWrappingCategory {
  private $order;
  private $oldish_open;
  const BUCKET_SIZE = 5;

  function __construct() {
    $this->order = 0;
    $this->oldish_open = false;
    $this->bucket_open = false;
  }

  public function before_grid($category) {
    echo "<div class=\"fdd-category-name\">" . esc_html($category->name) . "</div>";
  }

  public function before_article($post_order_var_name) {
    if ($this->order % self::BUCKET_SIZE == 1) {
      echo '<div class="oldish">';
      $this->oldish_open = true;
    }
    if ($this->order % self::BUCKET_SIZE == 0) {
      $this->close_bucket();
      if ($this->order) {
        echo '<div class="fdd-category-grid">';
      }
      $this->bucket_open = true;
    }
    // Makes visible in the template
    set_query_var($post_order_var_name, $this->order);
  }

  public function after_article() {
    ++$this->order;
    if ($this->order % self::BUCKET_SIZE == 0) {
      $this->close_oldish();
    }
  }

  public function at_limit($limit) {
    return $limit > 0 && $this->order >= $limit;
  }

  public function after_grid() {
    $this->close_oldish();
    $this->close_bucket();
  }

  private function close_oldish() {
    if ($this->oldish_open) {
      echo '</div>';
      $this->oldish_open = false;
    }
  }

  private function close_bucket() {
    if ($this->bucket_open) {
      echo '<div class="fdd-heel"></div></div>';
      $this->bucket_open = false;
    }
  }
}
