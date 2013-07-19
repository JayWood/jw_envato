<?
/*
Plugin Name:		JW Envato
Plugin URI:			http://plugish.com/plugins/jw_envato
Description:		A plugin to link referrals from your website to the Envato Marketplace of your choice.
Author:				Jerry Wood
Author URI:			http://plugish.com
Version:			1.0
*/
include_once('class/envato.class.php');
include_once('class/widgets.class.php');
$envato = new ENVATO;
add_shortcode('envato_popular', 'show_popular');
function show_popular($atts){
	global $envato;
	extract(shortcode_atts(array(
		'site'	=>	'codecanyon'
	), $atts));
	
	$output = '<pre>';
	$output .= print_r($envato->query_popular($site), true);
	$output .= '</pre>';
	
	return $output;
	
}
add_action('init', 'jw_setup_data');
function jw_setup_data(){
	wp_register_style('envato', plugins_url('css/envato.css', __FILE__), '', '1.0');
}
add_action('wp_enqueue_scripts', 'jw_print_style');
function jw_print_style(){
	wp_enqueue_style('envato');
}
?>