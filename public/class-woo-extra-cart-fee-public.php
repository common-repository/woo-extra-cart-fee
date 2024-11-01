<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Extra_Cart_Fee
 * @subpackage Woo_Extra_Cart_Fee/public
 * @author     WPCodelibrary <support@wpcodelibrary.com>
 */
class Woo_Extra_Cart_Fee_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-extra-cart-fee-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-extra-cart-fee-public.js', array('jquery'), $this->version, false);
    }

    public function wecf_add_cart_fee() {
        global $woocommerce, $post;

        if (is_admin() && !defined('DOING_AJAX'))
            return;

        foreach ($woocommerce->cart->cart_contents as $key => $values) {
            $check_wecf_enable = get_post_meta($values['product_id'], '_wecf_enable', true);

            if ((isset($check_wecf_enable) && !empty($check_wecf_enable))) {
                $wecf_extra_cart_fee = get_post_meta($values['product_id'], '_wecf_amount', true);
                $wecf_amount = $wecf_extra_cart_fee;
            }

            if (isset($check_wecf_enable) && $check_wecf_enable != '' && !empty($check_wecf_enable) && !empty($wecf_amount)) {
                $wecf_label = get_post_meta($values['product_id'], '_wecf_label', true);
                $woocommerce->cart->add_fee(apply_filters('woo_extra_cart_fee', $wecf_label . ': '), $wecf_amount, true, 'standard');
            }
        }
    }

    public function wecf_add_cart_fee_variable() {
        global $woocommerce, $post;
        if (is_admin() && !defined('DOING_AJAX'))
            return;
        foreach ($woocommerce->cart->cart_contents as $key => $values) {
            $wecf_variable_amount = get_post_meta($values['variation_id'], '_wecf_variation_amount', true);
            $wecf_amount_variable = $wecf_variable_amount;
            $wecf_variable_label = get_post_meta($values['variation_id'], '_wecf_variation_label', true);
            if (isset($wecf_amount_variable) && $wecf_amount_variable != '' && isset($wecf_variable_label) && !empty($wecf_variable_label)) {
                $woocommerce->cart->add_fee(apply_filters('woo_extra_cart_fee_variable', $wecf_variable_label . ': '), $wecf_amount_variable, true, 'standard');
            }
        }
    }


}