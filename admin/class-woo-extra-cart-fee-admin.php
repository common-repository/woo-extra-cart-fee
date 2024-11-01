<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Extra_Cart_Fee
 * @subpackage Woo_Extra_Cart_Fee/admin
 * @author     WPCodelibrary <support@wpcodelibrary.com>
 */
class Woo_Extra_Cart_Fee_Admin {

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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-extra-cart-fee-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-extra-cart-fee-admin.js', array('jquery'), $this->version, false);
    }

    public function wecf_product_tab() {
        add_action('woocommerce_product_write_panel_tabs', array($this, 'wecf_product_write_panel_tab'));
        add_action('woocommerce_product_write_panels', array($this, 'wecf_product_write_panel'));
        add_action('woocommerce_process_product_meta', array($this, 'wecf_product_save_data'), 10, 2);
    }

    public function wecf_product_write_panel_tab() {
        echo "<li class=\"activation_tab\"><a href=\"#activation_tab\">" . __('Woo Extra Cart Fee', 'woo-extra-cart-fee') . "</a></li>";
    }

    public function wecf_product_write_panel() {

        global $post;

        echo '<br><br><div id="activation_tab" class="panel wc-metaboxes-wrapper woocommerce_options_panel">';
        woocommerce_wp_checkbox(array('id' => '_wecf_enable', 'label' => __('Enable/Disable', 'woo-extra-cart-fee'), 'description' => __('Enable Woo Extra Cart Fee', 'woo-extra-cart-fee')));
        woocommerce_wp_text_input(array('id' => '_wecf_label', 'label' => __('Label', 'woo-extra-cart-fee'), 'description' => __('Woo Extra Cart Fee label ', 'woo-extra-cart-fee'), 'class' => 'short'));
        woocommerce_wp_text_input(array('id' => '_wecf_amount', 'label' => __('Amount', 'woo-extra-cart-fee'), 'description' => __('Woo Extra Cart Fee Amount', 'woo-extra-cart-fee'), 'class' => 'short wc_input_price'));

        echo '</div>';
    }

    public function wecf_product_save_data($post_id, $post) {

        if (isset($_POST['_wecf_enable']) && !empty($_POST['_wecf_enable'])) {
            update_post_meta($post_id, '_wecf_enable', $_POST['_wecf_enable']);
        } else {
            update_post_meta($post_id, '_wecf_enable', '');
        }

        if (isset($_POST['_wecf_label']) && !empty($_POST['_wecf_label'])) {
            update_post_meta($post_id, '_wecf_label', $_POST['_wecf_label']);
        }

        if (isset($_POST['_wecf_amount']) && !empty($_POST['_wecf_amount'])) {
            update_post_meta($post_id, '_wecf_amount', $_POST['_wecf_amount']);
        }
    }

    public function wecf_variation_settings_fields($loop, $variation_data, $variation) {
        // Text Field

        $this->woocommerce_wp_text_input_own(
                array(
                    'id' => '_wecf_variation_label[' . $variation->ID . ']',
                    'label' => __('Woo Extra Fee Label:', 'woocommerce'),
                    'placeholder' => '',
                    'desc_tip' => 'true',
                    'value' => get_post_meta($variation->ID, '_wecf_variation_label', true),
                    'class' => 'wecf_varition_text'
                )
        );

        $this->woocommerce_wp_text_input_own(
                array(
                    'id' => '_wecf_variation_amount[' . $variation->ID . ']',
                    'label' => __('Amount:', 'woocommerce'),
                    'desc_tip' => 'true',
                    'data_type' => 'price',
                    'value' => get_post_meta($variation->ID, '_wecf_variation_amount', true),
                    'custom_attributes' => array(
                        'step' => 'any',
                        'min' => '1',
                       
                    )
                )
        );
    }

    public function wecf_save_variation_settings_fields($post_id) {
        // Text Field
        $text_field = sanitize_text_field($_POST['_wecf_variation_label'][$post_id]);
        $number_field = sanitize_text_field($_POST['_wecf_variation_amount'][$post_id]);

        if (isset($text_field) && !empty($text_field)) {
            update_post_meta($post_id, '_wecf_variation_label', esc_attr($text_field));
        }
        if (isset($number_field) && !empty($number_field)) {
            if ($number_field > 0) {
                update_post_meta($post_id, '_wecf_variation_amount', esc_attr($number_field));
            }
        }
    }

    /**
     * Output a text input box.
     *
     * @param array $field
     */
    public function woocommerce_wp_text_input_own($field) {
        global $thepostid, $post;

        $thepostid = empty($thepostid) ? $post->ID : $thepostid;
        $field['placeholder'] = isset($field['placeholder']) ? $field['placeholder'] : '';
        $field['class'] = isset($field['class']) ? $field['class'] : 'short';
        $field['style'] = isset($field['style']) ? $field['style'] : '';
        $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
        $field['value'] = isset($field['value']) ? $field['value'] : get_post_meta($thepostid, $field['id'], true);
        $field['name'] = isset($field['name']) ? $field['name'] : $field['id'];
        $field['type'] = isset($field['type']) ? $field['type'] : 'text';
        $data_type = empty($field['data_type']) ? '' : $field['data_type'];

        switch ($data_type) {
            case 'price' :
                $field['class'] .= ' wc_input_price';
                $field['value'] = wc_format_localized_price($field['value']);
                break;
            case 'decimal' :
                $field['class'] .= ' wc_input_decimal';
                $field['value'] = wc_format_localized_decimal($field['value']);
                break;
            case 'stock' :
                $field['class'] .= ' wc_input_stock';
                $field['value'] = wc_stock_amount($field['value']);
                break;
            case 'url' :
                $field['class'] .= ' wc_input_url';
                $field['value'] = esc_url($field['value']);
                break;

            default :
                break;
        }

        // Custom attribute handling
        $custom_attributes = array();

        if (!empty($field['custom_attributes']) && is_array($field['custom_attributes'])) {

            foreach ($field['custom_attributes'] as $attribute => $value) {
                $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($value) . '"';
            }
        }

        echo '<p class="form-row-first form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '"><label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label><input type="' . esc_attr($field['type']) . '" class="' . esc_attr($field['class']) . '" style="' . esc_attr($field['style']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['value']) . '" placeholder="' . esc_attr($field['placeholder']) . '" ' . implode(' ', $custom_attributes) . ' /> ';

        if (!empty($field['description'])) {

            if (isset($field['desc_tip']) && false !== $field['desc_tip']) {
                echo wc_help_tip($field['description']);
            } else {
                echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
            }
        }
        echo '</p>';
    }

}