<?php
/**
 * Display footer content
 *
 * @package Fdd\Template_Parts\Footer
 */

use Fdd\Admin\Menu\Menu;
use Fdd\Helpers\General_Helper;

$footer_menu = new Menu();
$logo_img = General_Helper::get_manifest_assets_data('foodly-doodly-doo-logo-mcr.png');
?>

<footer class="footer">
  <div class="footer__container">
    <span>© FoodlyDoodlyDoo 2019-2020</span>
    <?php echo esc_html($footer_menu->bem_menu('footer_main_nav', 'footer-navigation')); ?>
  </div>
  <!--div class="footer__logo_container">
    <img class="footer__logo" src="<?php echo esc_url($logo_img);?>"/>
  </div-->
</footer>
