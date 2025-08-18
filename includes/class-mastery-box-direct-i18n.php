<?php
/**
 * Define the internationalization functionality
 */
class Mastery_Box_Direct_i18n {

    /**
     * Load the plugin text domain for translation.
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'mastery-box-direct',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}