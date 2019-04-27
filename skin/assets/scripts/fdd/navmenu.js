export class FDD_NavMenu {
  constructor(item, selector, action) {
    this.item = item;
    this.selector = selector;
    this.action = action;

    this.target = $(`.header .main-navigation .main-navigation__item--${item} > a`).click((e) => {
      const target = this.selector($(e.currentTarget).parent());
      target.toggle();

      this.action(target, target.is(":visible"));

      e.preventDefault();
    });
  }

  toggle() {
    this.target.click();
  }
};
