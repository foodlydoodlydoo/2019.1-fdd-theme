<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

Fdd\Theme\Utils\Images::set_image_sizes_mode('shop-loop');

$queried = get_queried_object();
if ($queried->parent == 0) {
	?>
	<p class="shop-categories-title">Shop by Motive - all motives are availlable on T Shirts, bags and hoodies:<p>
	<div class="shop-categories-wrap">
	<?php
	$motive_cat = get_term_by('slug', 'motive', 'product_cat');
	$motives = get_terms([
		'taxonomy' => 'product_cat',
		'hide_empty' => true,
		'parent' => $motive_cat->term_id
	]);
	foreach ($motives as $motive) {
		$thumbnail_id = get_term_meta( $motive->term_id, 'thumbnail_id', true );
		$link = get_term_link($motive->term_id, 'product_cat');
		$image_meta = wp_get_attachment_metadata($thumbnail_id);
		$size = 'fdd-200';
		$image = wp_get_attachment_image_src($thumbnail_id, $size, $image_meta);
		$srcset = wp_get_attachment_image_srcset($thumbnail_id, $size, $image_meta);
		$sizes = wp_get_attachment_image_sizes($thumbnail_id, $size, $image_meta);
		?>
		<div class="shop-motive-image">
			<a href="<?php echo $link; ?>">
			<img src="<?php echo esc_attr($image); ?>"
					srcset="<?php echo esc_attr($srcset); ?>"
					sizes="<?php echo esc_attr($sizes); ?>"
					alt="">
			</a>
		</div>
		<?php
	}
}
?>

</div>

<div class="fdd products columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?>">
<?php if ($queried->parent == 0) { ?>
<p class="shop-products-title">Shop by Product:<p>
<?php } ?>
