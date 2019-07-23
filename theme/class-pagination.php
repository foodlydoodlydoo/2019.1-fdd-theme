<?php
/**
 * The Pagination specific functionality.
 *
 * @since   1.0.0
 * @package Fdd\Theme
 */

namespace Fdd\Theme;

/**
 * Class Pagination
 */
class Pagination {

  /**
   * Posts next attibutes
   *
   * @return string Return class for link
   *
   * @since 1.0.0
   */
  public function pagination_link_next_class() {
    return 'class="page-numbers next screen-reader-text"';
  }

  /**
   * Posts prev attibutes
   *
   * @return string Return class for link
   *
   * @since 1.0.0
   */
  public function pagination_link_prev_class() {
    return 'class="page-numbers prev screen-reader-text"';
  }

  /**
   * Adds the pagination according to our styling (replacement for \the_posts_pagination())
   */
  public static function put($name, $more, $prev) {
    echo '<nav class="navigation posts-navigation" role="navigation">';
    echo '<div class="nav-links">';

    if (get_next_posts_link()) {
      previous_posts_link('<');
      next_posts_link("$more $name");
    } else {
      previous_posts_link("< $prev $name");
    }

    echo '</div></nav>';
  }
}
