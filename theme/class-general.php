<?php
/**
 * The General specific functionality.
 *
 * @since   1.0.0
 * @package Fdd\Theme
 */

namespace Fdd\Theme;

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

function max_srcset_image_width($default_width, $size_array) {
  // See Media::add_custom_image_sizes
  return false;
}

function menu_special_tags($title, $item, $args, $depth) {
  if (preg_match('/SEARCH-FORM/', $title)) {
    // return get_template_part('template-parts/header/search-form');
    return get_search_form(false);
  }
  if (preg_match('/SEPARATOR/', $title)) {
    return '<div class="menu-items-separator"></div>';
  }
  return $title;
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

    add_filter('wp_get_attachment_metadata', 'Fdd\Theme\get_attachment_metadata_hook', 100, 2);
    add_filter('intermediate_image_sizes', 'Fdd\Theme\intermediate_image_sizes_hook', 100, 5);

    add_filter('max_srcset_image_width', 'Fdd\Theme\max_srcset_image_width', 100, 2);
    add_filter('wp_calculate_image_srcset', 'Fdd\Theme\Utils\Images::srcset_attribute_hook', 100, 5);
    add_filter('wp_calculate_image_sizes', 'Fdd\Theme\Utils\Images::sizes_attribute_hook', 100, 5);
    add_filter('wp_constrain_dimensions', 'Fdd\Theme\Utils\Images::constrain_dimensions_hook', 100, 5);

    add_filter('the_content', 'Fdd\Theme\PSWP::add_image_sizes_to_content');

    add_filter('jpeg_quality', function() { return 85; });

    add_filter('walker_nav_menu_start_el', 'FDD\Theme\menu_special_tags', 10, 4);

    update_option('image_default_link_type', 'media');

    add_filter('widget_title', function($title, $instance, $id_base) {
      return null;
    }, 100, 3);

    // WooCommerce
    add_filter('woocommerce_short_description', '__return_null', 100);
    
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs');
    add_action( 'woocommerce_after_single_product_summary', function() {
      echo '<div class="fdd-woocommerce_after_single_product_summary"></div>';
    }, 100);

    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
    add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 1);
    
    add_filter('woocommerce_related_products', function() {
      return array();
    }, 100);

    add_filter('woocommerce_account_downloads_columns', function($columns) {
      unset($columns['download-remaining']);
      unset($columns['download-expires']);
      return $columns;
    }, 100);

    add_filter('woocommerce_single_product_carousel_options', function($args) {
      // https://github.com/woocommerce/FlexSlider/
      // $args['itemWidth'] = 600; // causes the next image to interfere; this slider is pretty shitty.
      return $args;
    }, 100);

    remove_action("woocommerce_single_product_summary", "woocommerce_template_single_excerpt");
    add_action("woocommerce_single_product_summary", function() {
      echo '<div class="single_product__excerpt">';
      add_filter("get_the_excerpt", "Fdd\Theme\Utils\Excerpt::single_product_excerpt_tweaks", 100, 1);
      the_excerpt();
      remove_filter("get_the_excerpt", "Fdd\Theme\Utils\Excerpt::single_product_excerpt_tweaks");
      echo '</div>';
    }, 35);

    add_filter('single_product_archive_thumbnail_size', function($size) {
      return 'fdd-640';
    });

    add_filter('woocommerce_product_get_image', function($image, $prod, $size, $attr, $placeholder, $foo) {
      $image = preg_replace("/\s?width=\"\d+\"/", "", $image);
      $image = preg_replace("/\s?height=\"\d+\"/", "", $image);
      return $image;
    }, 100, 6);
  }

}
