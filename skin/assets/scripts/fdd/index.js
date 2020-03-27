import { FDD_Carousel, FDD_PhotoSwipe } from './images';
import { FDD_FullscreenMenu } from './navmenu';

$(function () {
  const isTouchCapable =
    'ontouchstart' in window ||
    window.DocumentTouch && document instanceof window.DocumentTouch ||
    navigator.maxTouchPoints > 0 ||
    window.navigator.msMaxTouchPoints > 0;

  const section = $('main section div.single__content');
  const is_page = $('body').hasClass('page');
  const is_recipe = section.find('.fdd-recipe--page').length > 0;
  const is_art = section.find('.fdd-art--page').length > 0;

  if (is_recipe) {
    // Instantiate the recipe images carousel
    // 960 = $fdd-wide-width-pixels value from _common.scss
    new FDD_Carousel('main section .single__content', '.fdd-recipe--media figure a',
      '.fdd-recipe--media #arrow_left', '.fdd-recipe--media #arrow_right', '(min-width: 960px)',
      new FDD_PhotoSwipe());
  }
  if (is_art) {
    new FDD_PhotoSwipe('main section .single__content', '.fdd-art--page figure a');
  }
  if (is_page) {
    new FDD_PhotoSwipe('main section .single__content', 'figure a');
  }

  // Instantiate the fullscreen menu hooks
  new FDD_FullscreenMenu('sandwitch', '#fullscreen-menu',
    (menu, close, item) => {
      const is_search_invoke =
        item.hasClass("main-navigation__item--search");
      const search_field = menu.find(".search-field");
      if (!isTouchCapable || is_search_invoke) {
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
    });
});
