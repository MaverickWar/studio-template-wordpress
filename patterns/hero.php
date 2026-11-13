<?php
/**
 * Title: Hero
 * Slug: studio-starter/hero
 * Categories: banner, featured
 * Description: A big heading, a line of text and a button, in the project's colours.
 *
 * @package studio-starter
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"5rem","bottom":"5rem"}}},"backgroundColor":"studio-surface","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-studio-surface-background-color has-background" style="padding-top:5rem;padding-bottom:5rem">
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"studio-text-muted","fontSize":"large"} -->
<p class="has-studio-text-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Say what you do in one sentence.', 'studio-starter' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Get in touch', 'studio-starter' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
