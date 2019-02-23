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
          'name'          => esc_html__( 'Blog', 'fdd' ),
          'id'            => 'blog',
          'description'   => esc_html__( 'Description', 'fdd' ),
          'before_widget' => '',
          'after_widget'  => '',
          'before_title'  => '',
          'after_title'   => '',
      )
    );
  }

}
