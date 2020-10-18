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

  private static $standard_sizes = [
    200,
    400,
    640,
    960,
    1440,
    1920
  ];

  // Empiric for wide (>960px) layout
  private static $portrait_lead_article_sizes = [
    480, // = cat max possible width on wide, and 720 / 1.5
    720, // = home max possible width on wide
    960, // = 480 * 2
    1080, // = 720 * 1.5
    1440 // = 720 * 2
  ];

  // vgde narrow layout: max-height is 480px when 640-960px (min ratio = 1.33), 420px < 640px (=1.5)
  // mostly copy the standard_sizes listing to reuse those when fit 1.5 ratio
  private static $landscape_lead_article_sizes = [
    400,
    640,
    960, // and =640 * 1.5
    1440, // =960 * 1.5, ~640 * 2
    1920 // =960 * 2
  ];

  public static function register_image_sizes() {
    function gen_standard_size($size) {
      add_image_size("fdd-$size", $size, $size * 1.5, false);
    }
    foreach (Images::$standard_sizes as $size) {
      gen_standard_size($size);
    }

    function gen_portrait_lead_article_size($size) {
      add_image_size("fdd-lead-article-p-$size", $size, $size * 1.75, true);
    }
    foreach (Images::$portrait_lead_article_sizes as $size) {
      gen_portrait_lead_article_size($size);
    }

    function gen_landscape_lead_article_size($size) {
      add_image_size("fdd-lead-article-ls-$size", $size, ceil($size / 1.5), true);
    }
    foreach (Images::$landscape_lead_article_sizes as $size) {
      gen_landscape_lead_article_size($size);
    }
  }

  // Possibly one of: "home", "category", "post", ...
  static $image_sizing_mode = '';
  public static function set_image_sizes_mode($mode) {
    Images::$image_sizing_mode = $mode;
  }

  /**
   * Private helper declarations.
   */

  // All functions calling hooks accepting tag will set and reset this global
  private static $current_tag = '';

  /**
   * FDD Custom methods for generating the 'sizes' image attribute based
   * on internal tags and/or what we are currently displaying.
   */
  private static function remap_size($tag) {
    switch ($tag) {
    case 'fdd:listing:first-article':return 'fdd-960';
    case 'fdd:listing:first-article-landscape':return 'fdd-1440';
    case 'fdd:listing:first-article-portrait':return 'fdd-1120';
    case 'fdd:listing:oldish-article':return 'fdd-640';
    }

    return $tag;
  }

  private static function get_sizes_with_prefix($image_meta, $prefix) {
    return array_filter($image_meta['sizes'], function($size_name) use ($prefix) {
      return preg_match("/^$prefix/", $size_name);
    }, ARRAY_FILTER_USE_KEY);
  }

  private static function find_largest_size($image_meta, $prefix) {
    // Setup default in case we don't find anything (not expected to happen)
    $size = "full_size";
    
    $sizes = Images::get_sizes_with_prefix($image_meta, $prefix);
    $max_found = 0;
    foreach ($sizes as $size_name => $size_meta) {
      $width = $size_meta['width'];
      if ($max_found < $width) {
        $max_found = $width;
        $size = $size_name;
      }
    }

    return $size;
  }

  // According the tag (kind of image) return the default full image size to base srcset and sizes from.
  private static function get_largest_size($image_meta) {
    switch (Images::$current_tag) {
      case 'fdd:listing:first-article-portrait':
        return Images::find_largest_size($image_meta, "fdd-lead-article-p-");

      case 'fdd:listing:first-article-landscape':
        return Images::find_largest_size($image_meta, "fdd-lead-article-ls-");
    }

    return "full_width";
  }

  /**
   * The following code is heavilly dependent on styling for particular templates,
   * sizes are better determined empirically, by direct examination of elements on the screen.
   */
  public static function constrain_dimensions_hook($size, $current_width, $current_height, $max_width, $max_height) {
    // *** Keeping only for reference reasons ***

    /*
    // *** We are now building the srcset attribute for this tag manually
    // Abosolute hack - we must persuade `wp_image_matches_ratio` that we want differect aspects too, sometimes...
    switch (Images::$current_tag) {
    case 'fdd:listing:first-article':
      // Allow also `fdd-lead-article-*` sizes
      if (in_array($size[0], Images::$portrait_lead_article_sizes)) {
        return array($size[0], min($size[0] * 1.75, $current_height));
      }
      break;
    }
    */

    return $size;
  }

  public static function srcset_attribute_hook($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    // *** Keeping only for reference reasons ***

    // Note: this is already filtered according the constrain_dimensions_hook
    return $sources;
  }

  private static function get_srcset_attribute($tag, $width, $height, $attachment_id, $image_meta, $large_size) {
    $sources = array();
    $add_source = function($size) use ($attachment_id, $image_meta, &$sources) {
      $image = wp_get_attachment_image_src($attachment_id, $size, $image_meta);
      array_push($sources, [$image[0], $image[1]]);
    };
    $add_sources = function($sizes) use ($add_source) {
      foreach ($sizes as $size_name => $_) {
        $add_source($size_name);
      }
    };
    $sources_to_srcset = function($sources) {
      $widths = [];
      $sources = array_filter($sources, function($source) use (&$widths) {
        $width = $source[1];
        if (in_array($width, $widths)) {
          // remove duplicate widths, first occurence has a priority
          return false;
        }
        array_push($widths, $width);
        return true;
      });
      $sources = array_map(function($source) {
        return $source[0] . " " . $source[1] . "w";
      }, $sources);
      return join(", ", $sources);
    };

    switch (Images::$current_tag) {
    case 'fdd:listing:first-article':
      $add_sources(Images::get_sizes_with_prefix($image_meta, "fdd-lead-article-p-"));
      $add_sources(Images::get_sizes_with_prefix($image_meta, "fdd-\d+"));
      $add_source("full_size");
      $srcset = $sources_to_srcset($sources);
      break;

    case 'fdd:listing:first-article-portrait':
      $add_sources(Images::get_sizes_with_prefix($image_meta, "fdd-lead-article-p-"));
      $add_source("full_size");
      $srcset = $sources_to_srcset($sources);
      break;

    case 'fdd:listing:first-article-landscape':
      $add_sources(Images::get_sizes_with_prefix($image_meta, "fdd-lead-article-ls-"));
      $add_source("full_size");
      $srcset = $sources_to_srcset($sources);
      break;

    case 'fdd:listing:search-article':
      $add_sources(Images::get_sizes_with_prefix($image_meta, "fdd-lead-article-ls-"));
      $add_source("full_size");
      $srcset = $sources_to_srcset($sources);
      break;

    default:
      $srcset = wp_get_attachment_image_srcset($attachment_id, $large_size, $image_meta);
      break;
    }

    return $srcset;
  }

  // Depending on the current mode, return the appropriate sizes filling
  // This is called for images added generically by calls to `the_content()` et al.
  private static $recipe_image_order = 0;
  private static $product_listing_image_order = 0;
  public static function sizes_attribute_hook($sizes, $size, $image_src, $image_meta, $attachment_id) {
    $image = wp_get_attachment_image_src($attachment_id);
    $width = $image[1];
    $height = $image[2];
    $landscape = $width > $height;
    $ratio = $width / $height;

    switch (Images::$image_sizing_mode) {

    case 'food-art':
    case 'behind-the-scenes':
      // TODO Think of using 'min(90vw, 85vh)' (CSS4)
      // landscape fit the whole screen minus padding, square and portrait has max-height
      return $landscape ? '90vw' : (floor($ratio * 85) . 'vh');

    case 'recipes':
      if (Images::$recipe_image_order++ == 0) {
        $max_height = 60; // == $recipe-first-image-max-height @ recipe block style, @shop-enable
      } else {
        $max_height = 10; // == $recipe-second-and-on-image-max-height @ recipe block style
      }

      return $landscape
        ? '(max-width: 440px) 400px, (max-width: 960px) 90vw, ' . floor($ratio * $max_height) . 'vh'
        : '(max-width: 440px) 400px, (max-width: 960px) ' . floor($ratio * 80) . 'vh, ' . floor($ratio * $max_height) . 'vh';

    case 'page':
      // There is no way to get classes or any post-specific settings for the inserted image.
      // Assume that we have images only in two columns layout breaking at 960vw.
      return '(max-width: 959px) 90vw, 44vw';

    case 'shop-loop':
      $_get = function($screen, $width) {
        return '(max-width: ' . $screen . 'px) ' . floor($width) . 'vw, ';
      };

      $current_listing_image = Images::$product_listing_image_order++;
      if ($current_listing_image == 0) {
        return $landscape ? $_get(640, 80) . ' 40vw' : floor(70 * $ratio) . 'vh';
      }
      if (false && $landscape && $current_listing_image % 9 == 0) {
        return $landscape ? '40vw' : floor(70 * $ratio) . 'vh';
      }

      $ratio = max($ratio, 1);
      return $_get(640, 80) . $_get(880, 30 * $ratio) . $_get(1080, 20 * $ratio) . 
             $_get(1440, 15 * $ratio) . floor(1440 / (100 / 15 * $ratio)) . 'px';

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
      case 'fdd:listing:first-article-portrait':
        return '(max-width: 1200px) 480px, 720px';

      case 'fdd:listing:first-article-landscape':
        return '(min-width: 960px) 72vw, 960px';

      case 'fdd:listing:first-article':
        return $ratio < 1
          ? '(max-width: 260px) 260px, (max-width: 400px) 400px, (max-width: 640px) 640px, (max-width: 959.9px) 960px, (max-width: 1134px) 440px, 560px'
          : '(max-width: 640px) 640px, (max-width: 959.9px) 960px, (max-width: 1134px) 440px, 560px';

      case 'fdd:listing:oldish-article':
        return '(max-width: 480px) ' . floor(max([$ratio, 1]) * 260) . 'px, (max-width: 640px) 400px, (max-width: 960px) ' . floor($ratio * 50) . 'vw, ' . floor(max([$ratio, 1]) * 328) . 'px';
      } // switch $tag

      break;

    case 'category':
      switch ($tag) {
      case 'fdd:listing:first-article-portrait':
        return '480px';

      case 'fdd:listing:first-article-landscape':
        return '(min-width: 960px) 72vw, 960px';

      case 'fdd:listing:first-article':
        return $ratio < 1
          ? '(max-width: 260px) 260px, (max-width: 400px) 400px, (max-width: 880px) 640px, (max-width: 959.9px) 960px, 440px'
          : '(max-width: 880px) 640px, (max-width: 959.9px) 960px, 440px';

      case 'fdd:listing:oldish-article':
        return '(max-width: 480px) ' . floor(max([$ratio, 1]) * 260) . 'px, (max-width: 960px) ' . floor($ratio * 50) . 'vw, ' . floor(max([$ratio, 1]) * 300) . 'px';
      } // switch $tag

      break;

    case 'search':
      switch ($tag) {
      case 'fdd:listing:search-article':
        return '(max-width: 400px) 400px, (max-width: 640px) 640px, 400px';
      } // switch $tag
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

    Images::$current_tag = $tag;

    if (has_post_thumbnail($post_id)) {
      $attachment_id = get_post_thumbnail_id($post_id);
      $image_meta = wp_get_attachment_metadata($attachment_id);

      // Deliberately passing 'full_width' or largest portrait to get full range of sizes,
      // we count on srcset and sizes to pick up the right one.
      $large_size = Images::get_largest_size($image_meta);
      $image = wp_get_attachment_image_src($attachment_id, $large_size, $image_meta);

      // Instead of using wp_get_attachment_image_sizes/srcset, generate manually or fallback
      $srcset = Images::get_srcset_attribute($tag, $image[1], $image[2], $attachment_id, $image_meta, $large_size);
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

    Images::$current_tag = '';

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
