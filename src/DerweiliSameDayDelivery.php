<?php

namespace Derweili\SameDayDelivery;

defined( 'ABSPATH' ) or die();

// var_dump(\WC_Shipping_Local_Pickup);

class DerweiliSameDayDelivery extends \WC_Shipping_Method {

	public function __construct(  $instance_id = 0  ) {
		$this->id 									= 'derweili-same-day-delivery'; // die ID der Versandart
		$this->instance_id					= absint( $instance_id ); // die ID der Instanz, da die Versandart mehrfach verwendet werden kann
		$this->method_title					= __('Same Day', 'derweili-same-day-delivery'); // Name der Versandart
		$this->method_description 	= __('Same Day delivery', 'derweili-same-day-delivery'); // Beschreibung der Versandart

		$this->supports = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);

		$this->init();
	}

	/**
	 * Die Versandkosten müssen immer angelegt werden, selbst wenn bei 0 liegen.
	 */
	public function calculate_shipping( $package = array() ) {
		$cart_totals = \WC()->cart->cart_contents_total;
		$price_in_percent = $this->get_instance_option("priceInPercent");

		$shipping_cost = $cart_totals / 100 * $price_in_percent;

		$this->add_rate(
			array(
				'label'   => $this->title,
				'package' => $package,
				'cost'    => $shipping_cost,
			)
		);
	}

	public function init() {

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title      = $this->get_option( 'title' );
		$this->tax_status = $this->get_option( 'tax_status' );

		// Actions.
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	public function init_form_fields() {
		parent::init_form_fields();

		$this->instance_form_fields = array_merge(
				$this->instance_form_fields,
				array(
					'title'      => array(
						'title'       => __( 'Title', 'woocommerce' ),
						'type'        => 'text',
						'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
						'default'     => __( 'Same Day', 'woocommerce' ),
						'desc_tip'    => true,
					),
					'tax_status' => array(
						'title'   => __( 'Tax status', 'woocommerce' ),
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'default' => 'taxable',
						'options' => array(
							'taxable' => __( 'Taxable', 'woocommerce' ),
							'none'    => _x( 'None', 'Tax status', 'woocommerce' ),
						),
					),
					'latestTimeToOrder' => array(
							'title' => __('Latest Time to Order', 'same-day-delivery'),
							'type' => 'time',
							'description' => 'Latest time to order. Example: 17:00',
							'default' => '12:00'
					),
					'priceInPercent' => array(
							'title' => __('Price in Percent', 'same-day-delivery'),
							'type' => 'number',
							'description' => 'Price in Percent',
							'default' => 7
					),
				)
		);
	}

	public function is_available( $package ) {
		$latestTimeToOrder = $this->get_instance_option("latestTimeToOrder");
		
		$latest_time_timestamp = strtotime( $latestTimeToOrder . date( 'd-m-Y', current_time('timestamp') ) );
		$current_time = current_time('timestamp');
		
		if($latestTimeToOrder) {
			if ( $current_time > $latest_time_timestamp ) {
				return false;
			}
		}
		return true;
	}
}