<?php

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class listkapal extends WP_List_Table {
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( [
			'singular' => __( 'Kapal', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Kapals', 'sp' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );
    }

    function table_data()
    {
        global $wpdb;

        $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}kapals", ARRAY_A);

        return $result;
    }

    function get_table_detail($table) {
        global $wpdb;

        $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$table");

        return $result;
    }

    function column_namak($item){
        
        //Build row actions
        $actions = array(
            'edit'   => '<a href="?page=add-schedule&kapal='.$item['kapalid'].'">Edit</a>',
            'delete' => '<a href="#" onclick="deleteKapal('.$item['kapalid'].')">Delete</a>'
        );
        
        //Return the title contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['namak'],
            /*$2%s*/ $this->row_actions($actions)
        );
    }

    function column_default($item, $column_name){
        $listOrigin       = $this->get_table_detail('origins');
        $listDestinations = $this->get_table_detail('destinations');
        switch ( $column_name ) {
			case 'namak':
			case 'namaky':
            case 'oriId':
                foreach ($listOrigin as $orig) {
                    if ($item['oriId'] == $orig->oriId) {
                        $item['oriId'] = $orig->oriName;
                    }
                }
            case 'destId':
                foreach ($listDestinations as $desti) {
                    if ($item['destId'] == $desti->destId) {
                        $item['destId'] = $desti->destName;
                    }
                }
			case 'cfs':
			case 'etd':
			case 'eta':
            case 'duration':
                $item['duration'] = $item['duration']. ' Days';
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
    }

    // function column_teamName($item){
        
    //     //Build row actions
    //     $actions = array(
    //         'edit'      => sprintf('<a href="?page=%s&id=%s">Edit</a>','football-show-team',$item['teamId']),
    //         'delete'    => sprintf('<a href="?page=%s&del=%s">Delete</a>','football-team',$item['teamId']),
    //         'view'      => sprintf('<a href="%s%s" target="_blank">View</a>',''.site_url().'\team/',str_replace(' ', '-', strtolower($item['teamName']))),
    //     );
        
    //     //Return the title contents
    //     return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
    //         /*$1%s*/ $item['teamName'],
    //         /*$2%s*/ $item['teamId'],
    //         /*$3%s*/ $this->row_actions($actions)
    //     );
    // }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['teamId']                //The value of the checkbox should be the record's id
        );
    }

    function get_columns() {
		$columns = [
			'cb'       => '<input type = "checkbox" />',
			'namak'    => __( 'Vessel', 'sp' ),
			'namaky'    => __( 'Voyage', 'sp' ),
			'oriId'    => __( 'Port Of Loading', 'sp' ),
			'destId'   => __( 'Destination', 'sp' ),
			'cfs'      => __( 'CFS CUT OFF', 'sp' ),
			'etd'      => __( 'ETD', 'sp' ),
			'eta'      => __( 'ETA', 'sp' ),
			'duration' => __( 'Duration', 'sp' )
		];

		return $columns;
	}

    function get_sortable_columns() {
        $sortable_columns = array(
			'namak'  => array( 'namak', true ),
			'namaky'  => array( 'namaky', true ),
			'oriId'  => array( 'oriId', true ),
			'destId' => array( 'destId', true ),
			'cfs'    => array( 'cfs', true ),
			'etd'    => array( 'etd', true ),
			'eta'    => array( 'eta', false )
		);

		return $sortable_columns;
    }

    public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}


    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
        // In our file that handles the request, verify the nonce.
        $nonce = esc_attr( $_REQUEST['_wpnonce'] );
    
        if ( ! wp_verify_nonce( $nonce, 'jf_delete_team' ) ) {
          die( 'Go get a life script kiddies' );
        }
        else {
          self::jf_delete_team( absint( $_GET['team'] ) );
    
          wp_redirect( esc_url( add_query_arg() ) );
          exit;
        }
        }
        
    }


    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 5;
        
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        // $this->process_bulk_action();
        
        
        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
		$data = $this->table_data();
                
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'namak'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');
        
        
        /***********************************************************************
         * ---------------------------------------------------------------------
         * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
         * 
         * In a real-world situation, this is where you would place your query.
         *
         * For information on making queries in WordPress, see this Codex entry:
         * http://codex.wordpress.org/Class_Reference/wpdb
         * 
         * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         * ---------------------------------------------------------------------
         **********************************************************************/
        
                
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count($data);
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
        
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
}

class listTable {

	// customer WP_List_Table object
	static $instance;
	public $jlist;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
	}


	public static function set_screen( $status, $option, $value ) {
		return $value;
	}


	public function jw_list() {
        global $wpdb;

        // get all origin from db
        $listOrigin       = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}origins");
        // get all destination from db
        $listDestinations = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}destinations");

        $this->jlist = new listkapal();
        if (isset($_GET['delkapal'])) {
            $deleteKapal = $wpdb->delete($wpdb->prefix.'kapals', ['kapalid' => $_GET['delkapal']]);
            if ($deleteKapal) {
                echo "<script>location.replace('admin.php?page=schedule-act');</script>";
            }
        } elseif (isset($_GET['deldestination'])) {
            $deleteDestination = $wpdb->delete($wpdb->prefix.'destinations', ['destId' => $_GET['deldestination']]);
            if ($deleteDestination) {
                echo "<script>location.replace('admin.php?page=schedule-act');</script>";
            }
        } elseif (isset($_GET['delorigin'])) {
            $deleteOrigin = $wpdb->delete($wpdb->prefix.'origins', ['oriId' => $_GET['delorigin']]);
            if ($deleteOrigin) {
                echo "<script>location.replace('admin.php?page=schedule-act');</script>";
            }
        }
		?>
		<div class="wrap">
            <h1 class="wp-heading-inline">List Jadwal</h1>
            <button class="page-title-action" onclick="window.location.href='admin.php?page=add-schedule'">Add New</button>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->jlist->prepare_items();
								$this->jlist->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
        </div>
        <div class="wrap">
                <div class="col-6">
                    <h2>List Port of Loading</h2>
                    <table class="wp-list-table widefat fixed striped teams">
                        <tbody>
                            <tr>
                                <th>No. </th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                            <?php
                            $no = 1;
                            foreach ($listOrigin as $gin) { ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $gin->oriName ?></td>
                                <td>
                                    <a href="?page=add-schedule&origin=<?= $gin->oriId ?>" class="primary">Edit</a>
                                    <span> - </span>
                                    <button onclick="deleteOrigin(<?= $gin->oriId ?>)"> Delete</button>
                                </td>
                            </tr>
                            <?php 
                            $no++;
                            } ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <h2>List Destinations</h2>
                    <table class="wp-list-table widefat fixed striped teams">
                        <tbody>
                            <tr>
                                <th>No. </th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                            <?php
                            $no = 1;
                            foreach ($listDestinations as $tions) { ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $tions->destName ?></td>
                                <td>
                                    <a href="" class="primary">Edit</a>
                                    <span> - </span>
                                    <button onclick="deleteDestination(<?= $tions->destId ?>)"> Delete</button>
                                </td>
                            </tr>
                            <?php 
                            $no++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
	<?php
	}

	/**
	 * Screen options
	 */
	public function screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => 'Kapals',
			'default' => 5,
			'option'  => 'kapals_per_page'
		];

		add_screen_option( $option, $args );
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}