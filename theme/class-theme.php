<?php
/**
 * The Theme specific functionality.
 *
 * @since   1.0.0
 * @package Fdd\Theme
 */

namespace Fdd\Theme;

use Fdd\Helpers\General_Helper;
use Fdd\Includes\Config;

/**
 * Class Theme
 */
class Theme extends Config {

  /**
   * Register the Stylesheets for the theme area.
   *
   * @since 1.0.0
   */
  public function enqueue_styles() {

    $main_style = General_Helper::get_manifest_assets_data('application.css');
    wp_register_style(static::THEME_NAME . '-style', $main_style, array(), static::THEME_VERSION);
    wp_enqueue_style(static::THEME_NAME . '-style');

  }

  /**
   * Register the JavaScript for the theme area.
   *
   * First jQuery that is loaded by default by WordPress will be deregistered and then
   * 'enqueued' with empty string. This is done to avoid multiple jQuery loading, since
   * one is bundled with webpack and exposed to the global window.
   *
   * @since 1.0.0
   */
  public function enqueue_scripts() {
    // jQuery.

    wp_deregister_script('jquery-migrate');
    wp_deregister_script('jquery');
    wp_register_script('jquery', General_Helper::get_manifest_assets_data('jquery.min.js'), array(), '3.3.1');
    wp_enqueue_script('jquery');

    // JS.
    wp_register_script(static::THEME_NAME . '-scripts-vendors', General_Helper::get_manifest_assets_data('vendors.js'), array(), static::THEME_VERSION, true);
    wp_enqueue_script(static::THEME_NAME . '-scripts-vendors');

    wp_register_script(static::THEME_NAME . '-scripts', General_Helper::get_manifest_assets_data('application.js'), array(static::THEME_NAME . '-scripts-vendors'), static::THEME_VERSION, true);
    wp_enqueue_script(static::THEME_NAME . '-scripts');

    // Glbal variables for ajax and translations.
    wp_localize_script(
      static::THEME_NAME . '-scripts',
      'themeLocalization',
      array(
        'ajaxurl' => admin_url('admin-ajax.php'),
      )
    );
  }

}
