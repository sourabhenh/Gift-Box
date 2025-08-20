<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php
/**
 * Plugin Name: Mastery Box Direct
 * Plugin URI: https://your-website.com
 * Description: Direct play interactive game where users can instantly play and win prizes without filling forms - pick a box and see if you've won!
 * Version: 1.0.0
 * Author: Sourabh Usta
 * License: GPL v2 or later
 * Text Domain: mastery-box-direct
 * Domain Path: /languages
 */

if (!ob_get_level()) {
    ob_start();
}

if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'MASTERY_BOX_DIRECT_VERSION', '1.0.0' );
define( 'MASTERY_BOX_DIRECT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MASTERY_BOX_DIRECT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

function activate_mastery_box_direct() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-mastery-box-direct-activator.php';
    Mastery_Box_Direct_Activator::activate();
}

function deactivate_mastery_box_direct() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-mastery-box-direct-deactivator.php';
    Mastery_Box_Direct_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mastery_box_direct' );
register_deactivation_hook( __FILE__, 'deactivate_mastery_box_direct' );

/**
 * Enqueue media scripts for admin
 */
add_action( 'admin_enqueue_scripts', function() {
    wp_enqueue_media();
} );

/**
 * Save handler for settings (runs before any output)
 */
add_action( 'admin_post_masterybox_direct_save_settings', 'mastery_box_direct_handle_settings_save' );

function mastery_box_direct_handle_settings_save() {
    if ( ! isset( $_POST['mastery_box_direct_nonce'] ) || ! wp_verify_nonce( $_POST['mastery_box_direct_nonce'], 'mastery_box_direct_settings_action' ) ) {
        wp_die( __( 'Security check failed', 'mastery-box-direct' ) );
    }

    // Save settings
    update_option( 'mastery_box_direct_number_of_boxes', intval( $_POST['number_of_boxes'] ?? 3 ) );
    update_option( 'mastery_box_direct_win_message', sanitize_textarea_field( $_POST['win_message'] ?? '' ) );
    update_option( 'mastery_box_direct_lose_message', sanitize_textarea_field( $_POST['lose_message'] ?? '' ) );

    // Save box images
    update_option( 'mastery_box_direct_default_box_image', esc_url_raw( $_POST['default_box_image'] ?? '' ) );

    $box_images = array();
    if ( !empty( $_POST['box_images'] ) && is_array( $_POST['box_images'] ) ) {
        foreach ( $_POST['box_images'] as $idx => $url ) {
            $idx = intval( $idx );
            $url = esc_url_raw( $url );
            if ( $idx > 0 && !empty( $url ) ) {
                $box_images[$idx] = $url;
            }
        }
    }
    update_option( 'mastery_box_direct_box_images', $box_images );

    wp_redirect( admin_url( 'admin.php?page=mastery-box-direct-settings&message=updated' ) );
    exit;
}

add_action( 'admin_post_mastery_box_direct_export_entries', 'mastery_box_direct_export_entries_to_csv' );

function mastery_box_direct_export_entries_to_csv() {
    if ( !current_user_can( 'manage_options' ) ) {
        wp_die( __( 'Unauthorized', 'mastery-box-direct' ) );
    }
    if ( !isset( $_POST['mastery_box_direct_export_nonce'] ) || !wp_verify_nonce( $_POST['mastery_box_direct_export_nonce'], 'mastery_box_direct_export_entries' ) ) {
        wp_die( __( 'Security check failed', 'mastery-box-direct' ) );
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'masterybox_direct_entries';
    $gifts_table = $wpdb->prefix . 'masterybox_direct_gifts';

    // Fetch all with gift name
    $entries = $wpdb->get_results( "
        SELECT e.*, g.name as gift_name
        FROM $table_name e
        LEFT JOIN $gifts_table g ON e.gift_won = g.id
        ORDER BY e.id ASC
    ", ARRAY_A );

    if ( empty( $entries ) ) {
        wp_die( __( 'No entries found to export.', 'mastery-box-direct' ) );
    }

    // Send csv headers
    header( 'Content-Type: text/csv; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=mastery-box-direct-entries-' . date( 'Y-m-d' ) . '.csv' );
    $output = fopen( 'php://output', 'w' );

    // Output column header row
    $csv_headers = ['Box', 'Result', 'Gift Won', 'Date', 'IP Address'];
    fputcsv( $output, $csv_headers );

    foreach ( $entries as $entry ) {
        $row = [];
        $row[] = $entry['chosen_box'];
        $row[] = $entry['is_winner'] ? 'WIN' : 'LOSE';
        $row[] = $entry['gift_name'] ? $entry['gift_name'] : 'No prize';
        $row[] = $entry['created_at'];
        $row[] = $entry['ip_address'];
        fputcsv( $output, $row );
    }
    fclose( $output );
    exit;
}

require plugin_dir_path( __FILE__ ) . 'includes/class-mastery-box-direct.php';

function run_mastery_box_direct() {
    $plugin = new Mastery_Box_Direct();
    $plugin->run();
}
run_mastery_box_direct();
