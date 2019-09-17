<?php

/**
 * WordPress settings class
 *
 */

namespace hyperdrive;

class WeDevs_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new \WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'Hyperdrive', 'Hyperdrive', 'edit_theme_options', 'settings_hyperdrive', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'wedevs_basics',
                'title' => __( 'Hyperdrive', 'hyperdrive' )
            ),
						/*
            array(
                'id'    => 'wedevs_advanced',
                'title' => __( 'Advanced Settings', 'hyperdrive' )
            )
						*/
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'wedevs_basics' => array(
								/*
                array(
                    'name'              => 'text_val',
                    'label'             => __( 'Text Input', 'hyperdrive' ),
                    'desc'              => __( 'Text input description', 'hyperdrive' ),
                    'placeholder'       => __( 'Text Input placeholder', 'hyperdrive' ),
                    'type'              => 'text',
                    'default'           => 'Title',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'number_input',
                    'label'             => __( 'Number Input', 'hyperdrive' ),
                    'desc'              => __( 'Number field with validation callback `floatval`', 'hyperdrive' ),
                    'placeholder'       => __( '1.99', 'hyperdrive' ),
                    'min'               => 0,
                    'max'               => 100,
                    'step'              => '0.01',
                    'type'              => 'number',
                    'default'           => 'Title',
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'        => 'textarea',
                    'label'       => __( 'Textarea Input', 'hyperdrive' ),
                    'desc'        => __( 'Textarea description', 'hyperdrive' ),
                    'placeholder' => __( 'Textarea placeholder', 'hyperdrive' ),
                    'type'        => 'textarea'
                ),
                array(
                    'name'        => 'html',
                    'desc'        => __( 'HTML area description. You can use any <strong>bold</strong> or other HTML elements.', 'hyperdrive' ),
                    'type'        => 'html'
                ),
								*/
                array(
                    'name'  => 'styles',
                    'label' => __( 'Fetch styles', 'hyperdrive' ),
                    'desc'  => __( 'On', 'hyperdrive' ),
                    'type'  => 'checkbox'
                ),
								/*
                array(
                    'name'    => 'radio',
                    'label'   => __( 'Radio Button', 'hyperdrive' ),
                    'desc'    => __( 'A radio button', 'hyperdrive' ),
                    'type'    => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'selectbox',
                    'label'   => __( 'A Dropdown', 'hyperdrive' ),
                    'desc'    => __( 'Dropdown description', 'hyperdrive' ),
                    'type'    => 'select',
                    'default' => 'no',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'hyperdrive' ),
                    'desc'    => __( 'Password description', 'hyperdrive' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'file',
                    'label'   => __( 'File', 'hyperdrive' ),
                    'desc'    => __( 'File description', 'hyperdrive' ),
                    'type'    => 'file',
                    'default' => '',
                    'options' => array(
                        'button_label' => 'Choose Image'
                    )
                )
								*/
            ),
						/*
            'wedevs_advanced' => array(
                array(
                    'name'    => 'color',
                    'label'   => __( 'Color', 'hyperdrive' ),
                    'desc'    => __( 'Color description', 'hyperdrive' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'hyperdrive' ),
                    'desc'    => __( 'Password description', 'hyperdrive' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'wysiwyg',
                    'label'   => __( 'Advanced Editor', 'hyperdrive' ),
                    'desc'    => __( 'WP_Editor description', 'hyperdrive' ),
                    'type'    => 'wysiwyg',
                    'default' => ''
                ),
                array(
                    'name'    => 'multicheck',
                    'label'   => __( 'Multile checkbox', 'hyperdrive' ),
                    'desc'    => __( 'Multi checkbox description', 'hyperdrive' ),
                    'type'    => 'multicheck',
                    'default' => array('one' => 'one', 'four' => 'four'),
                    'options' => array(
                        'one'   => 'One',
                        'two'   => 'Two',
                        'three' => 'Three',
                        'four'  => 'Four'
                    )
                ),
            )
						*/
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}