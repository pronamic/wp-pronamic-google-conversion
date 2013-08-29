<?php
/*
Plugin Name: Google Conversion
Plugin URI: http://pronamic.eu/wordpress/events/
Description: This plugin add some basic Event functionality to WordPress

Version: 1.0.0
Requires at least: 3.0

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: google_conversion
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-google-conversion
*/

/**
 * Google Conversion Code
 */
function shortcode_google_conversion_code( $atts ) {
	extract( shortcode_atts( array(
		'id'       => null,
		'language' => 'en',
		'format'   => 3,
		'color'    => '666666',
		'label'    => '',
		'value'    => 0,
	), $atts ) );

	$crlf = "\r\n";

	$no_script_image_url = sprintf( 'http://www.googleadservices.com/pagead/conversion/%d/', $id );
	$no_script_image_url = add_query_arg( array(
		'label'  => $label,
		'guid'   => 'ON',
		'script' => 0
	), $no_script_image_url );

	$output = '';

	$output .= '<!-- Google Code for Bezoekers Pagina Douchegoten Remarketing List -->' . $crlf;
	$output .= '<script type="text/javascript">' . $crlf;
	$output .= '/* <![CDATA[ */' . $crlf;
	$output .= sprintf( 'var google_conversion_id = %d;', $id ) . $crlf;
	$output .= sprintf( 'var google_conversion_language = "%s";', $language ) . $crlf;
	$output .= sprintf( 'var google_conversion_format = "%s";', $format ) . $crlf;
	$output .= sprintf( 'var google_conversion_color = "%s";', $color ) . $crlf;
	$output .= sprintf( 'var google_conversion_label = "%s";', $label ) . $crlf;
	$output .= sprintf( 'var google_conversion_value = %d;', $value ) . $crlf;
	$output .= '/* ]]> */' . $crlf;
	$output .= '</script>' . $crlf;

	$output .= '<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">' . $crlf;
	$output .= '</script>' . $crlf;

	$output .= '<noscript>' . $crlf;
	$output .= '<div style="display:inline;">' . $crlf;
	$output .= sprintf( '<img height="1" width="1" style="border-style:none;" alt="" src="%s"/>', esc_attr( $noScriptImageUrl ) ) . $crlf;
	$output .= '</div>' . $crlf;
	$output .= '</noscript>' . $crlf;

	return $output;
}

add_shortcode( 'google_conversion_code', 'shortcode_google_conversion_code' );
