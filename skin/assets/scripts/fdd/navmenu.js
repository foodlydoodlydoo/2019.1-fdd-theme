export class FDD_NavMenu {
  constructor(item, selector, action) {
    this.item = item;
    this.selector = selector;
    this.action = action;

    this.target = $(`.header .main-navigation .main-navigation__item--${item} > a`).click((e) => {
      const target = this.selector($(e.currentTarget).parent());
      target.toggle();

      const visible = target.is(":visible");
      this.action(target, visible);
      if (visible) {
        $(e.currentTarget).addClass("fdd-sub-menu-active");
      } else {
        $(e.currentTarget).removeClass("fdd-sub-menu-active");
      }

      e.preventDefault();
    });
  }

  toggle() {
    this.target.click();
  }
};
