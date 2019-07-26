<?php
/**
 * The Utils-Images specific functionality.
 *
 * @since   1.0.0
 * @package Fdd\Theme\Utils
 */

namespace Fdd\Theme\Utils;

use Fdd\Helpers\General_Helper;

/**
 * Class Images
 */
class Images {

  /**
   * FDD Custom methods for generating the 'sizes' image attribute based
   * on internal tags and/or what we are currently displaying.
   */

  private static function remap_size($tag) {
    switch ($tag) {
    case 'fdd:listing:first-article':return 'fdd-1000';
    case 'fdd:listing:oldish-article':return 'fdd-640';
    }

    return $tag;
  }

  // Possibly one of: "home", "category", "post", ...
  static $image_sizing_mode = '';
  public static function set_image_sizes_mode($mode) {
    Images::$image_sizing_mode = $mode;
  }

  /**
   * The following code is heavilly dependent on styling for particular templates,
   * sizes are better determined empirically, by direct examination of elements on the screen.
   */

  // Depending on the current mode, return the appropriate sizes filling
  // This is called for images added generically by calls to `the_content()` et al.
  private static $recipe_image_order = 0;
  public static function sizes_attribute_hook($sizes, $size, $image_src, $image_meta, $attachment_id) {
    $image = wp_get_attachment_image_src($attachment_id);
    $width = $image[1];
    $height = $image[2];
    $landscape = $width > $height;
    $ratio = $width / $height;

    switch (Images::$image_sizing_mode) {

    case 'food-art':
    case 'behind-the-scenes':
      // TODO Think of using 'min(90vw, 85vh)'
      // landscape fit the whole screen minus padding, square and portrait has max-height
      return $landscape ? '90vw' : (floor($ratio * 85) . 'vh');

    case 'recipes':
      if (Images::$recipe_image_order++ == 0) {
        $max_height = 65;
      } else {
        $max_height = 10;
      }

      return $landscape
      ? '(max-width: 440px) 400px, (max-width: 960px) 90vw, ' . floor($ratio * $max_height) . 'vh'
      : '(max-width: 440px) 400px, (max-width: 960px) ' . floor($ratio * 80) . 'vh, ' . floor($ratio * $max_height) . 'vh';

    case 'page':
      // There is no way to get classes or any post-specific settings for the inserted image.
      // Assume that we have images only in two columns layout breaking at 960vw.
      return '(max-width: 959px) 90vw, 44vw';

    } // switch $mode

    return $sizes;
  }

  // As above, depending on the mode returns the sizes attr, but this is called manually
  // from `Images::get_post_image` method.
  private static function get_sizes_attribute($tag, $width, $height, $attachment_id) {
    $ratio = $width / $height;

    switch (Images::$image_sizing_mode) {

    case 'home':
      switch ($tag) {
      case 'fdd:listing:first-article':
        // 420 = first-article-max-height-category-narrow
        return '(max-width: 640px) ' . floor(max([$ratio, 1]) * 420) . 'px, (max-width: 960px) 100vw, ' . floor($ratio * 90) . 'vh';

      case 'fdd:listing:oldish-article':
        return '(max-width: 480px) 260px, (max-width: 640px) 400px, (max-width: 960px) ' . floor($ratio * 50) . 'vw, ' . floor(max([$ratio, 1]) * 22) . 'vw';
      } // switch $tag

      break;

    case 'category':
      switch ($tag) {
      case 'fdd:listing:first-article':
        // 420 = first-article-max-height-category-narrow
        return '(max-width: 640px) ' . floor(max([$ratio, 1]) * 420) . 'px, (max-width: 960px) 1000px, ' . floor($ratio * 70) . 'vh';

      case 'fdd:listing:oldish-article':
        return '(max-width: 480px) 260px, (max-width: 960px) ' . floor($ratio * 50) . 'vw, ' . floor(max([$ratio, 1]) * 21) . 'vw';
      } // switch $tag

      break;

    case 'search':
      switch ($tag) {
      case 'fdd:listing:search-article':
        // so far a copy from home/oldish
        return '(max-width: 400px) 400px, (max-width: 640px) 640px, 400px';
      }
      break;

    } // switch $mode

    return wp_get_attachment_image_sizes($attachment_id, Images::remap_size($tag));
  }

  /**
   * Get featured image for specific post/page ID.
   *
   * If found return it, if not return no image placeholder.
   *
   * @param  string  $size     Image size. Accepts any valid image size.
   * @param  integer $post_id  Post ID.
   * @param  integer $no_image Link to no image thumbnail.
   * @return array             Array with image settings.
   *
   * @since 1.0.0
   */
  public static function get_post_image($tag, $post_id = null, $no_image = null) {
    global $post;

    if (!$post_id) {
      $post_id = $post->ID;
    }

    if (has_post_thumbnail($post_id)) {
      $attachment_id = get_post_thumbnail_id($post_id);
      $image_meta = wp_get_attachment_metadata($attachment_id);

      // Deliberately passing 'full_width' to get full range of sizes,
      // we count on srcset and sizes to pick up the right one.
      $image = wp_get_attachment_image_src($attachment_id, 'full_width', $image_meta);
      $srcset = wp_get_attachment_image_srcset($attachment_id, 'full_width', $image_meta);

      // Instead of using wp_get_attachment_image_sizes, generate manually
      $sizes = Images::get_sizes_attribute($tag, $image[1], $image[2], $attachment_id);

      $image_array = [
        'image' => $image[0],
        'width' => $image[1],
        'height' => $image[2],
        'srcset' => $srcset,
        'sizes' => $sizes,
      ];
    } else {
      $no_img = General_Helper::get_manifest_assets_data('images/no-image-' . $size . '.jpg');
      $image_array = [
        'image' => $no_img,
        'width' => '',
        'height' => '',
        'srcset' => '',
        'sizes' => '',
      ];

      if (!empty($no_image)) {
        $image_array['image'] = $no_image;
      }
    }

    return $image_array;
  }

  /**
   * Get image from image array
   * If found return it, if not return no image placeholder.
   *
   * @param string $size Image size from Image object.
   * @param array  $image_array WP image array.
   *
   * @since 1.0.0
   */
  public static function get_image_from_array($size, $image_array) {
    if (!empty($image_array)) {
      $img = $image_array['sizes'];
      $src = $img[$size];
    } else {
      $src = General_Helper::get_manifest_assets_data('images/no-image-' . $size . '.jpg');
    }

    return [
      'image' => $src,
    ];
  }

}
