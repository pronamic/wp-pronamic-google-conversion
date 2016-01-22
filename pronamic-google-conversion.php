<?php
/*
Plugin Name: Pronamic Google Conversion
Plugin URI: http://www.pronamic.eu/plugins/pronamic-google-conversion/
Description: This plugin adds an shortcode to easily integrate an Google Conversion code.

Version: 1.0.0
Requires at least: 3.0

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: pronamic_google_conversion
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-google-conversion
*/

/**
 * Initialize
 */
function pronamic_google_conversion_init() {
	global $pronamic_google_conversion_codes;

	$pronamic_google_conversion_codes = array();
}

add_action( 'init', 'pronamic_google_conversion_init' );

/**
 * Footer
 */
function pronamic_google_conversion_footer() {
	global $pronamic_google_conversion_codes;

	echo implode( "\r\n", $pronamic_google_conversion_codes ); // WPCS: XSS ok.
}

add_action( 'wp_footer', 'pronamic_google_conversion_footer' );

/**
 * Google Conversion Code
 *
 * There is an issue with CDATA in the content, so we buffer the codes and
 * output in the footer.
 *
 * @see http://wordpress.stackexchange.com/a/68103
 * @see http://core.trac.wordpress.org/ticket/3670
 */
function pronamic_google_conversion_shortcode( $atts ) {
	global $pronamic_google_conversion_codes;

	extract( shortcode_atts( array(
		'id'               => null,
		'language'         => 'en',
		'format'           => 3,
		'color'            => '666666',
		'label'            => '',
		'value'            => 0,
		'remarketing_only' => false,
	), $atts ) );

	$crlf = "\r\n";

	if ( ! isset( $pronamic_google_conversion_codes[ $id ] ) ) {
		$no_script_image_url = sprintf( 'http://www.googleadservices.com/pagead/conversion/%d/', $id );
		$no_script_image_url = add_query_arg( array(
			'label'  => $label,
			'guid'   => 'ON',
			'script' => 0,
		), $no_script_image_url );

		$code = '';

		$code .= sprintf( '<!-- Google Code for %s -->', $id ) . $crlf;
		$code .= '<script type="text/javascript">' . $crlf;
		$code .= '/* <![CDATA[ */' . $crlf;
		$code .= sprintf( 'var google_conversion_id = %d;', $id ) . $crlf;
		$code .= sprintf( 'var google_conversion_language = "%s";', $language ) . $crlf;
		$code .= sprintf( 'var google_conversion_format = "%s";', $format ) . $crlf;
		$code .= sprintf( 'var google_conversion_color = "%s";', $color ) . $crlf;
		$code .= sprintf( 'var google_conversion_label = "%s";', $label ) . $crlf;
		$code .= sprintf( 'var google_conversion_value = %d;', $value ) . $crlf;
		$code .= sprintf( 'var google_remarketing_only = %s;', filter_var( $remarketing_only, FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false' ) . $crlf;
		$code .= '/* ]]> */' . $crlf;
		$code .= '</script>' . $crlf;

		$code .= '<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">' . $crlf;
		$code .= '</script>' . $crlf;

		$code .= '<noscript>' . $crlf;
		$code .= '<div style="display:inline;">' . $crlf;
		$code .= sprintf( '<img height="1" width="1" style="border-style:none;" alt="" src="%s"/>', esc_attr( $no_script_image_url ) ) . $crlf;
		$code .= '</div>' . $crlf;
		$code .= '</noscript>' . $crlf;

		$pronamic_google_conversion_codes[] = $code;
	}

	$output = sprintf( '<!-- Google Code %s moved to footer -->', $id );

	return $output;
}

add_shortcode( 'google_conversion_code', 'pronamic_google_conversion_shortcode' );
