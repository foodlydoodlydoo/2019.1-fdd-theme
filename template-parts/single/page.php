<?php
/**
 * Single Page
 *
 * @package Fdd\Template_Parts\Single
 */

Fdd\Theme\Utils\Images::set_image_sizes_mode('page');
?>

<!-- Single Content Section -->
<section class="single" id="<?php echo esc_attr($post->ID); ?>">
  <header>
    <h1 class="single__title">
      <?php the_title();?>
    </h1>
  </header>
  <div class="single__content content-style content-media-style">
    <?php the_content();?>
  </div>
  <?php require locate_template('template-parts/parts/google-rich-snippets.php');?>
</section>
