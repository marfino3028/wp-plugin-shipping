<?php
// jag-MainMenu

require_once('pages/jw-add.php');
require_once('pages/jw-list.php');

class jw_functions {
    function jw_add_menu() {
        add_menu_page(
            'Schedule', // Nama Page Title
            'Schedule', // Nama di Menu
            'manage_options', // Capabillity
            'schedule-act', // Link / slug
            NULL,
            'dashicons-megaphone',
            60
        );
        add_submenu_page(
            'schedule-act',
            'Schedule',
            'List Jadwal',
            'manage_options',
            'schedule-act',
            array(new listTable, 'jw_list')
        );
        add_submenu_page(
        'schedule-act',
        'Tambah Data',
        'Tambah Data',
        'manage_options',
        'add-schedule',
        'jw_add'
        );
    }
}

?>