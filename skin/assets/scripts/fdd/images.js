import * as PhotoSwipe from 'photoswipe'
import * as PhotoSwipeUI_Default from 'photoswipe/dist/photoswipe-ui-default'

function getImages(selector_outer, selector_inner) {
  const images = $(`${selector_outer} ${selector_inner} img`).get().map(i => $(i));
  for (const index in images) {
    images[index].data('index', index);
  };

  return images;
}

export class FDD_PhotoSwipe {
  constructor(selector_outer, selector_inner) {
    if (!selector_outer) {
      // Will be setup and called externally
      return;
    }

    $(selector_outer).on("click", selector_inner, (e) => {
      const target = $(e.target);
      if (this.click(target)) {
        e.stopPropagation();
        e.preventDefault();
      }
    });

    this.assignGallery(getImages(selector_outer, selector_inner));
  }

  assignGallery(images) {
    this.images = images.map(image => {
      const anch = image.parent();

      const w = parseInt(anch.attr('data-width'), 10);
      const h = parseInt(anch.attr('data-height'), 10);
      const src = anch.attr('href');
      const msrc = src.replace(/(\.\w+$)/, `-${anch.attr('thumb')}$1`);

      return { src, msrc, w, h, };
    });
  }

  click(image) {
    const options = {
      index: parseInt(image.data('index'), 10),
      timeToIdle: 1500,
      loop: false,
      closeOnScroll: false,
      closeOnVerticalDrag: false,
      history: false,
      shareEl: false,
      counterEl: false,
      captionEl: false,
      barsSize: {
        top: 0,
        bottom: 0,
      }
    };

    try {
      const pswp = document.querySelectorAll('.pswp')[0];
      this.gallery = new PhotoSwipe(pswp, PhotoSwipeUI_Default, this.images, options);
      this.gallery.init();
    } catch (e) {
      console.error(e);
    }

    return true;
  }
}

export class FDD_Carousel {
  constructor(selector_outer, selector_inner, media_query, forward = null) {
    $(selector_outer).on("click", selector_inner, (e) => {
      const target = $(e.target);
      if (this.click(target) || (forward && forward.click(target))) {
        e.stopPropagation();
        e.preventDefault();
      }
    });

    this.media_query = media_query;

    this.images = getImages(selector_outer, selector_inner);
    if (forward) {
      forward.assignGallery(this.images);
    }

    if (this.images.length < 2) {
      return;
    }

    this.first_image_sizes = this.images[0].prop('sizes');
    this.thumb_image_sizes = this.images[1].prop('sizes');

    this.active_image_index = 0;
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
