<?php
// jag Main-Plugin
require_once('inc/jw-adddb.php');
require_once('inc/jw-frontschedule.php');
require_once('pages/jw-list.php');
require_once('jw-functions.php');

class jw_mainplugin {
    public $shipMenu;
    public $frontschedule;

    public function __construct() {
        // Tampilkan Menu
        $this->shipMenu = new jw_functions;
        $this->$frontschedule = new jw_frontschedule;
        add_action( 'admin_menu', array($this->shipMenu, 'jw_add_menu'));
        add_action( 'admin_enqueue_scripts', array($this, 'jw_add_script') );
        add_action( 'wp_enqueue_scripts', array($this, 'jw_add_front') );

        
    }

    function jw_register_hook() {
        $create_table = new jw_adddb();
        $this->shipMenu->jw_add_menu();
        # code...
    }

    function jw_deactivate_hook() {
        # code...
    }
    function jw_uninstall_hook() {
        # code...
    }
    
    function activate() {
        set_transient( 'team_flush', 1, 60 );
    }
    

    function jw_add_script() {
        $dir = plugin_dir_url(__FILE__);
        wp_enqueue_media();

// 		wp_register_style('jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js');
  		
		wp_register_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css');
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-datepicker', array('jquery', 'jquery-ui'));
		
        wp_register_script('jw-admin-js', $dir. '../assets/js/jwadmin.js');
        wp_enqueue_script('jw-admin-js', '', array('jquery', 'jquery-ui-datepicker'), NULL, true);
        // custom css admin by plugin
        wp_register_style( 'jw-admin-css', $dir. '../assets/css/jwadmin.css');
        wp_enqueue_style( 'jw-admin-css');
    }

    function jw_add_front() {
        $dir = plugin_dir_url(__FILE__);
        wp_register_style('jw-front-css', $dir. '../assets/css/jwfrontend.css');
        wp_enqueue_style('jw-front-css');

        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_register_script('jw-front-js', $dir. '../assets/js/jwfrontend.js');
        wp_enqueue_script('jw-front-js', '', '', '', true);
    }
   
}
add_action( 'plugins_loaded', function () {
	listTable::get_instance();
} );