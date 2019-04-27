import { FDD_Carousel } from './carousel';
import { FDD_NavMenu } from './navmenu';

$(function () {
  new FDD_Carousel('body', '.fdd-recipe--media a');
  const sandwitch = new FDD_NavMenu('sandwitch', target => target.find(".main-navigation__sub-menu"), (target, visible) => {
    const search_field = target.find(".search-field");
    if (visible) {
      search_field.focus().keydown((e) => {
        if (e.which == 27) {
          sandwitch.toggle();
        }
      });
      target.parent().addClass("fdd-sub-menu-active");
    } else {
      search_field.off('keydown');
      target.parent().removeClass("fdd-sub-menu-active");
    }
  });
});
