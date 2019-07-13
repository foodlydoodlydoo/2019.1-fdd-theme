<?php
/**
 * Only on homepage, shows "See more articles" thingy under last article in category
 *
 * @package Fdd
 */

?>

<a class="more-articles" href="<?php echo get_category_link($category); ?>">
<span class="more-articles__text">
More <?php echo $category->name; ?>
</span>
<br/>
<span class="more-articles__button">
&gt;
</span>
</a>
