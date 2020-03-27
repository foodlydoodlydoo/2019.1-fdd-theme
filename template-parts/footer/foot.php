<?php
/**
 * Footer
 *
 * @package Fdd\Template_Parts\Footer
 */

?>

<!--a href="#html, body" class="scroll-to-top js-scroll-to-top">
  <?php esc_html_e('To top', 'fdd');?>
</a-->
<?php wp_footer();?>

<?php if (is_single() || is_page()) {
  get_template_part('template-parts/parts/pswp');
}
?>

</body>
</html>
