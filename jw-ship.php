<?php
	/*
	Plugin Name: Schedule For ACT
	Plugin URI: https://jasterweb.com
	Description: Plugin Jadwal Kapal Khusus untuk ACT Global
	Author: Jaster Coding Team
	Version: 1.0
	Author URI: https://jasterweb.com
 	License:           GPL v2 or later
	License URI:       https://www.gnu.org/licenses/gpl-2.0.html
	Requires at least: 5.2
	Requires PHP:      7.0
	*/

require_once('core/jw-core.php');
if (! defined('ABSPATH')) {
    die;
}


class jw_Mainship {
    public $jtag;

    public function __construct() {
        $this->jtag = new jw_mainplugin;
    }
}

if(class_exists('jw_Mainship')) {
    $mainJaster = new jw_Mainship();
}


// start activation hook
register_activation_hook( __FILE__, array($mainJaster->jtag, 'jw_register_hook') );
// start deactivation hook
register_deactivation_hook( __FILE__, array($mainJaster->jtag, 'jw_deactivate_hook') );
?>