export class FDD_FullscreenMenu {
  constructor(item, target, onRevealed, onHidden) {
    const menu = $(target);
    const close = () => {
      menu.css('height', '0');
      onHidden(menu);
    };

    $(`.header .main-navigation .main-navigation__item--${item} > a`).click(() => {
      menu.css('height', '100%');
      onRevealed(menu, close);
    });
    $(`${target}__close-button`).click(close);
  }
};
