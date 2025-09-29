<?php
/**
 * Plugin Name: User Email OTP
 * Description: Email verification with OTP.
 * Version: 1.0
 * Author: MohammadTanzilurRahman
 */

// Hook to run when the plugin is activated
register_activation_hook( __FILE__, 'create_custom_table' );

function create_custom_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'user_otp'; // Table name with prefix
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        email VARCHAR(100) NOT NULL,
        otp VARCHAR(10) NOT NULL,
        status TINYINT(1) DEFAULT 0 NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql ); // Creates or updates the table
}
