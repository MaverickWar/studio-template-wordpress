<?php
/**
 * Made by Universal App Studio. Reads design/tokens.json and hands the colours, fonts and corners
 * to WordPress: the block editor's palette and fonts, the site's styles, and --studio-* CSS
 * variables for any custom CSS. Change the look in Studio (it rewrites design/tokens.json), never here.
 *
 * Setup (once): the repo is the theme folder, so design/ sits inside the theme. In functions.php add:
 *   require_once get_theme_file_path( 'design/adapters/wordpress/studio-theme.php' );
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** The tokens, read once per request. */
function studio_tokens() {
	static $tokens = null;
	if ( null === $tokens ) {
		$file   = get_theme_file_path( 'design/tokens.json' );
		$tokens = is_readable( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : array();
		if ( ! is_array( $tokens ) ) {
			$tokens = array();
		}
	}
	return $tokens;
}

/** The Google Fonts stylesheet for the two fonts. */
function studio_fonts_href() {
	$t = studio_tokens();
	if ( empty( $t['font'] ) ) {
		return '';
	}
	$families = array_unique( array_filter( array( $t['font']['display'] ?? '', $t['font']['body'] ?? '' ) ) );
	$parts    = array();
	foreach ( $families as $family ) {
		$parts[] = 'family=' . str_replace( '%20', '+', rawurlencode( $family ) ) . ':wght@400;600;700';
	}
	return $parts ? 'https://fonts.googleapis.com/css2?' . implode( '&', $parts ) . '&display=swap' : '';
}

/** The tokens as --studio-* CSS variables. */
function studio_css_variables() {
	$t    = studio_tokens();
	$vars = array();
	foreach ( (array) ( $t['color'] ?? array() ) as $key => $hex ) {
		$vars[] = '--studio-' . $key . ':' . $hex;
	}
	if ( ! empty( $t['font'] ) ) {
		$vars[] = "--studio-font-display:'" . ( $t['font']['display'] ?? '' ) . "', Georgia, serif";
		$vars[] = "--studio-font-body:'" . ( $t['font']['body'] ?? '' ) . "', system-ui, sans-serif";
	}
	foreach ( (array) ( $t['radius'] ?? array() ) as $key => $size ) {
		$vars[] = '--studio-radius-' . $key . ':' . $size;
	}
	return $vars ? ':root{' . implode( ';', $vars ) . '}' : '';
}

// The block editor's palette, fonts and site-wide styles come from the tokens.
add_filter(
	'wp_theme_json_data_theme',
	function ( $theme_json ) {
		$t = studio_tokens();
		if ( empty( $t['color'] ) ) {
			return $theme_json;
		}
		$names   = array(
			'bg'         => 'Background',
			'surface'    => 'Surface',
			'text'       => 'Text',
			'text-muted' => 'Secondary text',
			'primary'    => 'Primary',
			'on-primary' => 'On primary',
			'accent'     => 'Accent',
			'on-accent'  => 'On accent',
		);
		$palette = array();
		foreach ( $t['color'] as $slug => $hex ) {
			$palette[] = array(
				'slug'  => 'studio-' . $slug,
				'color' => $hex,
				'name'  => $names[ $slug ] ?? $slug,
			);
		}
		$fonts = array();
		foreach ( array( 'display', 'body' ) as $role ) {
			if ( ! empty( $t['font'][ $role ] ) ) {
				$fonts[] = array(
					'slug'       => 'studio-' . $role,
					'name'       => $t['font'][ $role ],
					'fontFamily' => "'" . $t['font'][ $role ] . "', sans-serif",
				);
			}
		}
		return $theme_json->update_with(
			array(
				'version'  => 2,
				'settings' => array(
					'color'      => array( 'palette' => $palette ),
					'typography' => array( 'fontFamilies' => $fonts ),
				),
				'styles'   => array(
					'color'      => array(
						'background' => 'var(--wp--preset--color--studio-bg)',
						'text'       => 'var(--wp--preset--color--studio-text)',
					),
					'typography' => array( 'fontFamily' => 'var(--wp--preset--font-family--studio-body)' ),
					'elements'   => array(
						'heading' => array( 'typography' => array( 'fontFamily' => 'var(--wp--preset--font-family--studio-display)' ) ),
						'link'    => array( 'color' => array( 'text' => 'var(--wp--preset--color--studio-primary)' ) ),
						'button'  => array(
							'color'  => array(
								'background' => 'var(--wp--preset--color--studio-primary)',
								'text'       => 'var(--wp--preset--color--studio-on-primary)',
							),
							'border' => array( 'radius' => $t['radius']['md'] ?? '8px' ),
						),
					),
				),
			)
		);
	}
);

// Fonts and variables on the site and in the editor.
function studio_enqueue_theme() {
	$href = studio_fonts_href();
	if ( $href ) {
		wp_enqueue_style( 'studio-fonts', $href, array(), null );
	}
	wp_register_style( 'studio-theme', false, array(), null );
	wp_enqueue_style( 'studio-theme' );
	wp_add_inline_style( 'studio-theme', studio_css_variables() );
}
add_action( 'wp_enqueue_scripts', 'studio_enqueue_theme' );
add_action( 'enqueue_block_editor_assets', 'studio_enqueue_theme' );
