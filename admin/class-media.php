<?php
/**
 * The Media specific functionality.
 *
 * @since   1.0.0
 * @package Fdd\Admin
 */

namespace Fdd\Admin;

use Fdd\Helpers\Object_Helper;
use Fdd\Theme\Utils\Images;

/**
 * Class Media
 */
class Media {

  /**
   * Use trait inside class.
   */
  use Object_Helper;

  /**
   * Enable theme support
   * for full list check: https://developer.wordpress.org/reference/functions/add_theme_support/
   *
   * @since 1.0.0
   */
  public function add_theme_support() {
    add_theme_support('post-thumbnails');
  }

  /**
   * Create new image sizes
   *
   * @since 1.0.0
   */
  public function add_custom_image_sizes() {
    function gen_standard_size($size) {
      add_image_size("fdd-$size", $size, $size * 1.5, false);
    }
    gen_standard_size(260);
    gen_standard_size(400);
    gen_standard_size(640);
    gen_standard_size(1000);
    gen_standard_size(1400);
    gen_standard_size(2000); // for full HD screens and retina tablets

    // calculations (home/cat):
    // vgde wide layout applies dynamically as w=(40/31)%, h=(90/70)vh between 960-1400px screen width
    // w=(384-560/297-434)px, h-max=(972/756)px, ratio = ~1:1.75
    function gen_portrait_lead_article_size($size) {
      add_image_size("fdd-lead-article-$size", $size, $size * 1.75, true);
    }
    foreach (Images::$portrait_lead_article_sizes as $size) {
      gen_portrait_lead_article_size($size);
    }
  }

  /**
   * Enable SVG uplod in media
   *
   * @param array $mimes Load all mimes types.
   * @return array       Return original and updated.
   *
   * @since 1.0.0
   */
  public function enable_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['zip'] = 'application/zip';
    return $mimes;
  }

  /**
   * Enable SVG preview in Media Library
   *
   * @param array      $response   Array of prepared attachment data.
   * @param int|object $attachment Attachment ID or object.
   *
   * @since 3.0.0 Replacing file_get_content with file.
   * @since 2.0.2 Added checks if xml file is valid.
   * @since 1.0.0
   */
  public function enable_svg_library_preview($response, $attachment) {
    if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml' && class_exists('SimpleXMLElement')) {
      try {
        $path = get_attached_file($attachment->ID);

        if (file_exists($path)) {
          $svg_content = file($path);
          $svg_content = implode(' ', $svg_content);

          if (!$this->is_valid_xml($svg_content)) {
            new \WP_Error(sprintf(esc_html__('Error: File invalid: %s', 'fdd'), $path));
            return false;
          }

          $svg = new \SimpleXMLElement($svg_content);
          $src = $response['url'];
          $width = (int) $svg['width'];
          $height = (int) $svg['height'];

          // media gallery.
          $response['image'] = compact('src', 'width', 'height');
          $response['thumb'] = compact('src', 'width', 'height');

          // media single.
          $response['sizes']['full'] = array(
            'height' => $height,
            'width' => $width,
            'url' => $src,
            'orientation' => $height > $width ? 'portrait' : 'landscape',
          );
        }
      } catch (\Exception $e) {
        new \WP_Error(sprintf(esc_html__('Error: %s', 'fdd'), $e));
      }
    }

    return $response;
  }

  /**
   * Check if svg is valid on Add New Media Page.
   *
   * @param array $response Response array.
   * @return array
   *
   * @since 3.0.0 Replacing file_get_content with file.
   * @since 1.0.0
   */
  public function check_svg_on_media_upload($response) {
    if ($response['type'] === 'image/svg+xml' && class_exists('SimpleXMLElement')) {
      $path = $response['tmp_name'];

      $svg_content = file($path);
      $svg_content = implode(' ', $svg_content);

      if (file_exists($path)) {
        if (!$this->is_valid_xml($svg_content)) {
          return array(
            'size' => $response,
            'name' => $response['name'],
          );
        }
      }
    }
    return $response;
  }
}
