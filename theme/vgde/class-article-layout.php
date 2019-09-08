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
  private $category;

  function __construct() {
    $this->order = 0;
  }

  private function is_front_post() {
    return $this->order == 0;
  }

  public function before_grid($category) {
    echo "<div class=\"fdd-category-name\">" . esc_html($category->name) . "</div>";
    $this->category = $category;
  }

  public function before_article($post_order_var_name, $front_post_var_name) {
    if ($this->order == 1) {
      echo '<div class="oldish">';
      echo '<div class="oldish__inner">';
    }
    // Makes visible in the template
    set_query_var($post_order_var_name, $this->order);
    set_query_var($front_post_var_name, $this->is_front_post());
  }

  public function after_article() {
    ++$this->order;
  }

  public function at_limit($limit) {
    return $limit > 0 && $this->order >= $limit;
  }

  public function after_grid($more_posts) {
    if ($this->order > 1) {
      //echo '<div class="after-last-article"></div>';
      echo '</div>'; // oldish__inner
    }
    if ($more_posts) {
      set_query_var("category", $this->category);
      get_template_part('template-parts/listing/home-more-articles');
    }
    if ($this->order > 1) {
      echo '</div>'; // oldish
    }
    echo '<div class="fdd-heel"></div>';
  }

  public function tail($more_posts) {
  }
}

class ArticleWrappingCategory {
  private $order;
  private $oldish_open;
  const BUCKET_SIZE = 7;

  function __construct() {
    $this->order = 0;
    $this->oldish_open = false;
    $this->bucket_open = false;
  }

  private function is_front_post() {
    return $this->order % self::BUCKET_SIZE == 0;
  }

  public function before_grid($category) {
    echo "<h1 class=\"fdd-category-name\">" . esc_html($category->name) . "</h1>";
  }

  public function before_article($post_order_var_name, $front_post_var_name) {
    set_query_var($post_order_var_name, $this->order);
    set_query_var($front_post_var_name, $this->is_front_post());

    if ($this->order % self::BUCKET_SIZE == 1) {
      echo '<div class="oldish">';
      echo '<div class="oldish__inner">';
      $this->oldish_open = true;
    }
    if ($this->is_front_post()) {
      $this->close_bucket();
      if ($this->order) {
        echo '</div><div class="fdd-category-grid infscroll-item-selector">';
      }
      $this->bucket_open = true;
      $this->order = 0;
    }
    // Makes visible in the template
  }

  public function after_article() {
    ++$this->order;
    if ($this->is_front_post()) {
      $this->close_oldish();
    }
  }

  public function at_limit($limit) {
    return $limit > 0 && $this->order >= $limit;
  }

  public function after_grid($more_posts) {
    $this->close_oldish();
    $this->close_bucket();
  }

  private function close_oldish() {
    if ($this->oldish_open) {
      echo '</div>'; // inner
      echo '</div>';
      $this->oldish_open = false;
    }
  }

  private function close_bucket() {
    if ($this->bucket_open) {
      echo '<div class="fdd-heel"></div>';
      $this->bucket_open = false;
    }
  }

  public function tail($have_posts) {
  }
}
