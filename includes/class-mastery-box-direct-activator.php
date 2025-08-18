<?php
/**
 * Fired during plugin activation
 */
class Mastery_Box_Direct_Activator {

    /**
     * Short Description.
     */
    public static function activate() {
        require_once plugin_dir_path(__FILE__) . 'class-mastery-box-direct-database.php';
        Mastery_Box_Direct_Database::create_tables();
    }
}