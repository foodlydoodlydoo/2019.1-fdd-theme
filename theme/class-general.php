<?php
/**
 * The General specific functionality.
 *
 * @since   1.0.0
 * @package Fdd\Theme
 */

namespace Fdd\Theme;

function print_r_pre($object) {
  echo "<pre>[\n";
  print_r($object);
  echo "\n]</pre>";
}

function get_attachment_metadata_hook($data, $postid) {
  // As the 'large' size is the default to be picked for new core/image block,
  // this will make the editor fall back to full-size!
  // See https://github.com/WordPress/gutenberg/issues/8663#issuecomment-469041359
  // Also removing all other sizes to have full control over how we handle
  // responsive images
  if (is_array($data) && array_key_exists('sizes', $data)) {
    unset($data["sizes"]['large']);
    unset($data["sizes"]['thumbnail']);
    unset($data["sizes"]['medium']);
    unset($data["sizes"]['medium_large']);
  }
  return $data;
}

function intermediate_image_sizes_hook($sizes) {
  return array_filter($sizes, function ($size) {
    return !in_array($size, ['large', 'thumbnail', 'medium', 'medium_large']);
  });
}

function sort_image_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
  ksort($sources);
  return $sources;
}

function image_sizes_attr_hook($sizes, $size, $image_src, $image_meta, $attachment_id) {
  return Utils\Images::sizes_attribute_hook($sizes, $size, $image_src, $image_meta, $attachment_id);
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
    add_theme_support('title-tag', 'html5', 'search-form', 'responsive-embeds');
    add_filter('wp_calculate_image_srcset', 'Fdd\Theme\sort_image_srcset', 100, 5);
    add_filter('wp_calculate_image_sizes', 'Fdd\Theme\image_sizes_attr_hook', 100, 5);
    add_filter('intermediate_image_sizes', 'Fdd\Theme\intermediate_image_sizes_hook', 100, 5);
    add_filter('wp_get_attachment_metadata', 'Fdd\Theme\get_attachment_metadata_hook', 100, 2);
    add_filter('jpeg_quality', create_function('', 'return 85;'));
  }

}
