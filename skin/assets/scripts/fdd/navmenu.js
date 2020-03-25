export class FDD_FullscreenMenu {
  constructor(item, target, onRevealed, onHidden) {
    const menu = $(target);
    const close = () => {
      menu.removeClass('open');
      onHidden(menu);
    };

    $(`.header .main-navigation .main-navigation__item--${item} > a`).click(event => {
      menu.addClass('open');
      onRevealed(menu, close, $(event.target).parent());
    });
    $(`${target}__close-button`).click(close);
  }
};
