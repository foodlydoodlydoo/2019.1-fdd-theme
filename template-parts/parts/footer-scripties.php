<?php

$product_base = 'product';
if (function_exists('wc_get_permalink_structure')) {
  $permalink = wc_get_permalink_structure();
  $product_base = $permalink['product_rewrite_slug'];
}

$product_image_cta = \FDD\Core\get_custom_content('image_inline_product_cta');

?>
<script>
jQuery(document).ready(function($) {
  $("img").on("contextmenu", function(e) { e.preventDefault(); return false;});
  $("figure.wp-block-image[productslug]:not([productslug=\"\"])").each(function() {
    const figure = $(this);
    const link = figure.find('a');
    const productslug = figure.attr('productslug');
    const productlink = `/<?php echo $product_base; ?>/${productslug}`;
    const element = $("<a>")
      .prop("href", productlink)
      .addClass("fdd-product-link")
      .click(event => {
        window.location.href = productlink;
        event.preventDefault();
        return false;
      })
      .append($("<span>").text('<?php echo $product_image_cta; ?>'));
    link.append(element);
  });
});
</script>
