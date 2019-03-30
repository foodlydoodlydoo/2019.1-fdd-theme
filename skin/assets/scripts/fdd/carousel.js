export class FDD_Carousel {
  constructor(selector_outer, selector_inner) {
    $(selector_outer).on("click", selector_inner, (e) => {
      if (this.click($(e.target))) {
        e.stopPropagation();
        e.preventDefault();
      }
    });
  }

  click(image) {
    let target = image.parent().parent(); // 'figure a img'
    if (!target.prev().length) {
      return false;
    }

    $(target).hide().prependTo(target.parent()).fadeIn();
    return true;
  }
}
