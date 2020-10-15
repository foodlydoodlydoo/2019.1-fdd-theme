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
  public static function pagination_link_next_class() {
    return 'class="page-numbers next"';
  }

  /**
   * Posts prev attibutes
   *
   * @return string Return class for link
   *
   * @since 1.0.0
   */
  public static function pagination_link_prev_class() {
    return 'class="page-numbers prev"';
  }

  /**
   * Prints out our custom-made posts nav link
   */
  private static function posts_link($link, $attrs, $inner_markup) {
    if ($link) {
      echo '<a ' . $attrs . ' href="' . $link . '">';
      echo $inner_markup;
      echo '</a>' . "\n";
    }
  }

  private static function nav_link_markup($text, $class, $reverse) {
      // Copies inner part of template-parts\listing\home-more-articles.php
      $text = '<span class="more-articles__text">' . $text . '</span>';
      $button = '<span class="more-articles__button ' . $class . '"></span>';
      if ($reverse) {
        return $button . $text;
      } else {
        return $text . $button;
      }
  }

  /**
   * Adds the pagination according to our styling (replacement for \the_posts_pagination())
   */
  public static function put($name, $more, $back) {
    // Markup copied from the private function `_navigation_markup`.
    // Filtering is not set that well to modify the content inside the links easily,
    // so we have to build on our own...

    echo '<nav class="navigation posts-navigation" role="navigation">' . "\n";
    echo '<div class="nav-links">' . "\n";

    global $wp_query, $paged;
    $next = get_next_posts_page_link($wp_query->max_num_pages);
    $prev = $paged > 1 ? get_previous_posts_page_link() : false;

    if ($prev) {
      if ($next) {
        // Only a decent 'back' markup when we can go both ways
        Pagination::posts_link($prev, 
          Pagination::pagination_link_prev_class() . ' title="' . htmlspecialchars(wp_strip_all_tags("$back $name")) . '"',
          Pagination::nav_link_markup('', 'prev', true)
        );
      } else {
        // On the last page make the back button visible
        Pagination::posts_link($prev, 
          Pagination::pagination_link_prev_class(), 
          Pagination::nav_link_markup(esc_html($back) . " <span class=\"more-articles__text_name\">$name</span>", 'prev', true)
        );
      }
    }

    if ($next) {
      Pagination::posts_link($next, 
        Pagination::pagination_link_next_class(), 
        Pagination::nav_link_markup(esc_html($more) . " <span class=\"more-articles__text_name\">$name</span>", 'next', false)
      );
    }

    echo '</div></nav>';
  }
}
