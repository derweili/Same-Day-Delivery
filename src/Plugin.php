<?php

namespace Derweili\SameDayDelivery;

defined( 'ABSPATH' ) or die();

class Plugin {
	function __construct() {
		$this->register_hooks();
	}

	function register_hooks() {
		add_filter( 'woocommerce_shipping_methods', array( $this, 'register_shipping_method' ), 10, 4 );
	}

	public static function register_shipping_method( $methods ) {
		// $methods['same-day-delivery'] = __NAMESPACE__ . '\SameDayShippingMethod';
		$methods['derweili-same-day-delivery'] = __NAMESPACE__ . '\DerweiliSameDayDelivery';

		return $methods;	
	}
}