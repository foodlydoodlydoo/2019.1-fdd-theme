export class FDD_Carousel {
  constructor(selector_outer, selector_inner, media_query) {
    $(selector_outer).on("click", selector_inner, (e) => {
      if (this.click($(e.target))) {
        e.stopPropagation();
        e.preventDefault();
      }
    });

    this.media_query = media_query;

    this.images = $(`${selector_outer} ${selector_inner} img`).get().map(i => $(i));
    if (this.images.length < 2) {
      return;
    }

    this.first_image_sizes = this.images[0].prop('sizes');
    this.thumb_image_sizes = this.images[1].prop('sizes');

    this.active_image_index = 0;

    for (const index in this.images) {
      this.images[index].data('index', index);
    };
  }

  click(image) {
    const query = window.matchMedia(this.media_query);
    if (!query.matches) {
      return false;
    }

    let target = image.parent().parent(); // 'figure a img'
    if (!target.prev().length) {
      return false;
    }

    this.active_image_index = image.data('index');
    image.prop('sizes', this.first_image_sizes);
    $(target).hide().prependTo(target.parent()).fadeIn();

    for (let image of Object.values(this.images).reverse()) {
      if (image.data('index') == this.active_image_index) {
        continue;
      }

      image.prop('sizes', this.thumb_image_sizes);
      $(image.parent().parent()).insertAfter(target);
    }

    return true;
  }
}
