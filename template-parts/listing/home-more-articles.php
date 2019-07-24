<?php
/**
 * Only on homepage, shows "See more articles" thingy under last article in category
 *
 * @package Fdd
 */

 /**
  * IMPORTANT, Fdd\Theme\Pagination is using inner parts of this template!
  */
 
?>

<div class="more-articles__wrap">
<a class="more-articles" href="<?php echo get_category_link($category); ?>">
<span class="more-articles__text">
More <?php echo $category->name; ?>
</span>
<span class="more-articles__button">
&gt;
</span>
</a>
</div>
