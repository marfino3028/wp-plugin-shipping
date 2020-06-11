<?php
    class jw_adddb {
        protected $wpdbase;
        protected $charset_collate;
        
        public function __construct() {
            // initiate wp database and set charset
            global $wpdb;
            $this->wpdbase = $wpdb;
            $this->charset_collate = $wpdb->get_charset_collate(); 
            $this->jw_insert_db_destinasi();
            $this->jw_insert_db_origin();
            $this->jw_insert_db_kapal();
        }

        // insert table destinasi
        function jw_insert_db_destinasi() {
            $sqldestinasi = "CREATE TABLE `{$this->wpdbase->prefix}destinations` (
                `destId` int(11) AUTO_INCREMENT,
                `destName` varchar(80) NOT NULL,
                PRIMARY KEY (destId)
            ) $this->charset_collate;";

            require_once(ABSPATH . "wp-admin/includes/upgrade.php");
            $this->wpdbase->query($sqldestinasi);
        }

        // insert table origin
        function jw_insert_db_origin() {
            $sqlorigin = "CREATE TABLE `{$this->wpdbase->prefix}origins` (
                `oriId` int(11) AUTO_INCREMENT,
                `oriName` varchar(80) NOT NULL,
                PRIMARY KEY (oriId)
            ) $this->charset_collate;";

            require_once(ABSPATH . "wp-admin/includes/upgrade.php");
            $this->wpdbase->query($sqlorigin);
        }

        // insert table kapal
        function jw_insert_db_kapal() {
            global $wpdb;
            $table_name = $this->wpdbase->prefix . 'kapals';
    
            $sqlkapal = "CREATE TABLE `$table_name` (
              `kapalid` int(11) AUTO_INCREMENT,
              `namak` varchar(80) NOT NULL,
              `namaky` varchar(80) NOT NULL,
              `oriId` int(11) DEFAULT NULL,
              `destId` int(11) DEFAULT NULL,
              `cfs` date NOT NULL,
              `etd` date NOT NULL,
              `eta` date NOT NULL,
              `duration` int(11),
              PRIMARY KEY  (kapalid),

              FOREIGN KEY (oriId) REFERENCES {$this->wpdbase->prefix}origins(oriId)
                    ON DELETE SET NULL ON UPDATE CASCADE,
              FOREIGN KEY (destId) REFERENCES {$this->wpdbase->prefix}destinations(destId)
                    ON DELETE SET NULL ON UPDATE CASCADE
            ) $this->charset_collate;";
            
            require_once(ABSPATH . "wp-admin/includes/upgrade.php");
            $this->wpdbase->query($sqlkapal);
        }

    }