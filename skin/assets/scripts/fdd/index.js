import { FDD_Carousel } from './carousel';
import { FDD_FullscreenMenu } from './navmenu';

$(function () {
  const isTouchCapable =
    'ontouchstart' in window ||
    window.DocumentTouch && document instanceof window.DocumentTouch ||
    navigator.maxTouchPoints > 0 ||
    window.navigator.msMaxTouchPoints > 0;


  // Instantiate the recipe images carousel
  new FDD_Carousel('body', '.fdd-recipe--media a');

  // Instantiate the fullscreen menu hooks
  new FDD_FullscreenMenu('sandwitch', '#fullscreen-menu',
    (menu, close) => {
      const search_field = menu.find(".search-field");
      if (!isTouchCapable) {
        search_field.focus();
      }
      search_field.keydown((e) => {
        if (e.which == 27) {
          close();
        }
      });
      $('body').addClass('noscroll');
    },
    (menu) => {
      const search_field = menu.find(".search-field");
      search_field.off('keydown');
      $('body').removeClass('noscroll');
    }
  );
});
