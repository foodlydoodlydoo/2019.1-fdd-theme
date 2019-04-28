<?php
/**
 * Display footer content
 *
 * @package Fdd\Template_Parts\Footer
 */

use Fdd\Admin\Menu\Menu;

$footer_menu = new Menu();
?>

<footer class="footer">
  <div class="footer__container">
  © FoodlyDoodlyDoo 2019
  <?php echo esc_html($footer_menu->bem_menu('footer_main_nav', 'footer-navigation')); ?>
  </div>
</footer>
