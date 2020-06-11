<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}jfteam, {$wpdb->prefix}jfplayer, {$wpdb->prefix}jfmatch, {$wpdb->prefix}jfteammatch, {$wpdb->prefix}jfcoordinator");