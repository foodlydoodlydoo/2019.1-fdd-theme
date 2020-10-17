<?php
/**
 * The Widgets specific functionality.
 *
 * @since   1.0.0
 * @package Fdd\Admin
 */

namespace Fdd\Admin;

/**
 * Class Widgets
 */
class Widgets {

  /**
   * Set up widget areas
   *
   * @since 1.0.0
   */
  public function register_widget_position() {
    register_sidebar(
      array(
          'name'          => esc_html__( 'Dummy sidebar', 'fdd' ),
          'id'            => 'fdd-dummy-sidebar',
          'description'   => esc_html__( 'Just for reference, not used', 'fdd' ),
          'before_widget' => '',
          'after_widget'  => '',
          'before_title'  => '',
          'after_title'   => '',
      )
    );
  }

}
