<?php
/**
 * The abstract class that will be used to extend for all config files.
 *
 * @since   1.0.0
 * @package Fdd\Includes
 */

namespace Fdd\Includes;

/**
 * Abstract Class Config
 *
 * Abstract class that exposes constants that are used across multiple files.
 */
abstract class Config {

  /**
   * Theme Name Constant
   *
   * @var string
   *
   * @since 1.0.0
   */
  const THEME_NAME = 'fdd';

  /**
   * Theme Version Constant
   *
   * @var string
   *
   * @since 1.0.0
   */
  const THEME_VERSION = '1.0.0';

}
