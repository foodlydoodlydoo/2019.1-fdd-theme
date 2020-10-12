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
          'name'          => esc_html__( 'Shop ad on the Homepage', 'fdd' ),
          'id'            => 'shop-home-call-to-action',
          'description'   => esc_html__( 'This is what we show on the home page betwean Recipe and Food art sections', 'fdd' ),
          'before_widget' => '<div class="home-call-to-action--wrap"><div class="home-call-to-action shop">',
          'after_widget'  => '</div></div>',
          'before_title'  => '',
          'after_title'   => '',
      )
    );
    register_sidebar(
      array(
          'name'          => esc_html__( 'Shop ad in Recipes, Food Art pages', 'fdd' ),
          'id'            => 'shop-pages-call-to-action',
          'description'   => esc_html__( 'This is what we show in individual recipes/art pages', 'fdd' ),
          'before_widget' => '<div class="single-call-to-action--wrap"><div class="single-call-to-action shop">',
          'after_widget'  => '</div></div>',
          'before_title'  => '',
          'after_title'   => '',
      )
    );
  }

}
