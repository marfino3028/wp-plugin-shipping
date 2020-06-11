<?php

class jw_frontschedule {
    public function __construct() {
        add_shortcode('jw-filter-schedule', array($this, 'jw_filter_schedule_shortcode'));
    }

    function jw_filter_schedule_shortcode() {
        global $wpdb;

        $listOrigin      = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}origins");
        $listDestination = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}destinations");
        $listDuration = $wpdb->get_results("SELECT duration FROM {$wpdb->prefix}kapals ORDER BY duration ASC");

        if (isset($_GET['search'])) {
            $searchquery = "SELECT * FROM {$wpdb->prefix}kapals";
            $queryCond = '';
            
            if (!empty($_GET['origin'])) {
                $queryCond .= "oriId = {$_GET['origin']} ";
            }
            if (!empty($_GET['destination'])) {
                if (!empty($queryCond)) {
                    $queryCond .= "OR ";
                }

                $queryCond .= "destId = {$_GET['destination']} ";
            }
            if (!empty($_GET['departure'])) {
                if (!empty($queryCond)) {
                    $queryCond .= "OR ";
                }

                $queryCond .= "etd = '{$_GET['departure']}' ";
            }
            if (!empty($_GET['arrival'])) {
                if (!empty($queryCond)) {
                    $queryCond .= "OR ";
                }

                $queryCond .= "eta = '{$_GET['arrival']}' ";
            }
            if (!empty($_GET['duration'])) {
                if (!empty($queryCond)) {
                    $queryCond .= "OR ";
                }

                $queryCond .= "duration = {$_GET['duration']}";
            }
            
            $queryCond = " WHERE ".$queryCond;
            $listSearchResult = $wpdb->get_results($searchquery.$queryCond);
        }

        ob_start();
        ?>
<section id="jtsection" class="jsect jadwalkapal">
	<div class="container">
		<h3>
			WEEKLY IMPORT SCHEDULES
		</h3>
        <form action="" method="get">
			<div class="jfilter">
			<div class="jform">
				<label>Origin</label>
				<select name="origin">
					<option value="">Origin</option>
					<?php foreach ($listOrigin as $ori) { ?>
						<option value="<?= $ori->oriId; ?>"><?= $ori->oriName; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="jform">
				<label>Destination</label>
				<select name="destination">
					<option value="">Destination</option>
					<?php foreach ($listDestination as $dest) { ?>
					<option value="<?= $dest->destId; ?>"><?= $dest->destName; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="jform">
				<label>Departure On/After</label>
            <input type="date" name="departure" id="departure">
			</div>
			<div class="jform">
				<label>Arrival Before</label>
            <input type="date" name="arrival" id="arrival">
			</div>
			<div class="jform">
				<label>Duration</label>
            <select name="duration">
                <option value="">Duration</option>
                <?php foreach ($listDuration as $dur) { ?>
                    <option value="<?= $dur->duration; ?>"><?= $dur->duration; ?> Days</option>
                <?php } ?>
            </select>
			</div>
				
			</div>
			<div class="jsubmit">
            <button type="submit" name="search">Search</button>
			</div>
        </form>
        <table>
            <tr>
                <th>Port of Loading</th>
                <th>Port of Discharge</th>
                <th>Vessel</th>
                <th>Voyage</th>
                <th>CFS Cut Off</th>
                <th>ETD</th>
                <th>ETA</th>
            </tr>
            <?php 
                if (isset($_GET['search'])) {
                    foreach ($listSearchResult as $sr) { ?>
                        <tr>
                            <td><?= $ori->oriName; ?></td>
                            <td><?= $dest->destName; ?></td>
                            <td><?= $sr->namak; ?></td>
                            <td><?= $sr->namaky; ?></td>
                            <td><?= $sr->cfs; ?></td>
                            <td><?= $sr->etd; ?></td>
                            <td><?= $sr->eta; ?></td>
                        </tr>
                    <?php }
                }
            ?>
        </table>
		
	</div>
	</section>
        <?php

        return ob_get_clean();
    }
}