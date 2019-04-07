<?php
/**
 * The General specific functionality.
 *
 * @since   1.0.0
 * @package Fdd\Theme
 */

namespace Fdd\Theme;

function get_attachment_metadata_remove_large_size($data, $postid) {
  // As the 'large' size is the default to be picked for new core/image block,
  // this will make the editor fall back to full-size!
  // See https://github.com/WordPress/gutenberg/issues/8663#issuecomment-469041359
  if (array_key_exists('sizes', $data)) {
    unset($data["sizes"]['large']);
  }

  /*
  $msg = print_r($data, true);
  error_log($msg);
   */
  return $data;
}

function sort_image_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
  ksort($sources);
  return $sources;
}

/**
 * Class General
 */
class General {

  /**
   * Enable theme support
   *
   * @since 1.0.0
   */
  public function add_theme_support() {
    add_theme_support('title-tag', 'html5', 'responsive-embeds');
    add_filter('wp_calculate_image_srcset', 'Fdd\Theme\sort_image_srcset', 100, 5);
    add_filter('wp_get_attachment_metadata', 'Fdd\Theme\get_attachment_metadata_remove_large_size', 100, 2);
  }

}
