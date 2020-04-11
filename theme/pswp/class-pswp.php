<?php

namespace Fdd\Theme;

class PSWP {

  static private function single_image_handler($matches) {
    $url = $matches[2];
    $type = wp_check_filetype($url);
    $addition = '';

    if (in_array($type['ext'], array('jpg', 'jpeg', 'jpe', 'gif', 'png', 'bmp', 'tif', 'tiff', 'ico', 'webp'))) {
      $attachment_id = attachment_url_to_postid($url);
      $image_meta = wp_get_attachment_metadata($attachment_id);
      $image = wp_get_attachment_image_src($attachment_id, 'full_width', $image_meta);

      $width = $image[1];
      $height = $image[2];

      if (!is_array($image_meta) || !array_key_exists('sizes', $image_meta)) {
        if (!is_admin()) {
          print("Missing image at $url");
        }
        return join($matches);
      }

      $thumb = $image_meta['sizes']['fdd-400'];

      $addition .= ' data-width="' . $width . '" data-height="' . $height . '"';
      $addition .= ' data-thumb="' . $thumb['width'] . 'x' . $thumb['height'] . '"';
    }

    return $matches[1] . $matches[2] . $matches[3] . $matches[4] . $addition . $matches[5];
  }

  static public function add_image_sizes_to_content($content) {
    $content = preg_replace_callback(
      '/(<a.[^>]*href=["\'])(.[^"^\']*?)(["\'])([^>]*)(>)/sU',
      'Fdd\Theme\PSWP::single_image_handler',
      $content
    );
    return $content;
  }

}
