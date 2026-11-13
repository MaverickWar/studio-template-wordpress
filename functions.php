<?php
/**
 * Theme setup. The look comes from design/tokens.json (Universal App Studio writes it) through the
 * adapter below, so don't type colours or fonts into theme.json or the templates.
 *
 * @package studio-starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_theme_file_path( 'design/adapters/wordpress/studio-theme.php' );

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
	}
);
