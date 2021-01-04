<?php
/**
 * Plugin Name:     Same Day Delivery
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     same-day-delivery
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Same_Day_Delivery
 */

// Your code starts here.
namespace Derweili\SameDayDelivery;

defined( 'ABSPATH' ) or die();

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ){
	require __DIR__ . '/vendor/autoload.php';
}

new Plugin();
