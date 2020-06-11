<?php
function jw_add() {
    global $wpdb;
    // get all origin from db
    $listOrigin       = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}origins");
    // get all destination from db
    $listDestinations = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}destinations");
    // set variable editing if page is edit
    $editing;
    // if there get variable
    if (isset($_GET['kapal'])) {
        // set the id of something wanna edit
        $idkapal       = $_GET['kapal'];
        // set editing to true
        $editing = true;
        // then show data by the id where to edit
        $editKapal       = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}kapals WHERE kapalid = $idkapal");
        // then give all input condition (biar kalo masuk page edit value nya ada)
    } elseif (isset($_GET['origin'])) {
        // set the id of something wanna edit
        $idorigin      = $_GET['origin'];
        // set editing to true
        $editing = true;
        // then show data by the id where to edit
        $editOrigin      = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}origins WHERE oriId = $idorigin");
    } elseif (isset($_GET['destination'])) {
        // set the id of something wanna edit
        $iddestination = $_GET['destination'];
        // set editing to true
        $editing = true;
        // then show data by the id where to edit
        $editDestination = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}destinations WHERE destId = $iddestination");
    } else {
        $editing = false;
    }
    ?>
            <div class="wrap">
                <h1 class="wp-heading-inline">Add Schedule</h1>
                <button class="page-title-action" onclick="window.location.href='admin.php?page=add-schedule'">Add New</button>
                <br/>
                
                <div class="col-6">
                    <h3>Input Jadwal</h3>
                    <form action="" method="post" autocomplete="off">
                    <table class="form-table">
                        <tbody>
                        <input type="hidden" name="kapalId" id="kapalId">
                        <tr>
                            <th scope="row">Vessel</th>
                            <td><input class="regular-text" type="text" name="kapalName" id="kapalName" value="<?php if($editing) {echo $editKapal->namak;} ?>"></td>
                        </tr>
                        <tr>
                            <th scope="row">Voyage</th>
                            <td><input class="regular-text" type="text" name="kapalNamey" id="kapalNamey" value="<?php if($editing) {echo $editKapal->namaky;} ?>"></td>
                        </tr>
                        <tr>
                            <th scope="row">Port Of Loading</th>
                            <td>
                                <select class="regular-text" name="origin" id="origin">
                                    <option value="">- Choose Origin -</option>
                                    <?php foreach ($listOrigin as $or) { ?>
                                        <option value="<?= $or->oriId ?>" <?php if($editing && $editKapal->oriId) {echo 'selected';}  ?>><?= $or->oriName ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Destination</th>
                            <td>
                                <select class="regular-text" name="destination" id="destination">
                                    <option value="">- Choose Destination -</option>
                                    <?php foreach ($listDestinations as $dest) { ?>
                                        <option value="<?= $dest->destId ?>" <?php if($editing && $editKapal->destId) {echo 'selected';}  ?>><?= $dest->destName ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">CFS CUT OFF</th>
                            <td><input class="regular-text" type="text" name="cfs" id="cfs" value="<?php if ($editing) {echo $editKapal->cfs;} ?>"></td>
                        </tr>
                        <tr>
                            <th scope="row">ETD</th>
                            <td><input class="regular-text" type="text" name="etd" id="etd" value="<?php if ($editing) {echo $editKapal->etd;} ?>"></td>
                        </tr>
                        <tr>
                            <th scope="row">ETA</th>
                            <td><input class="regular-text" type="text" name="eta" id="eta" value="<?php if ($editing) {echo $editKapal->eta;} ?>"></td>
                        </tr>
                        </tbody>
                    </table>
                    <p class="submit">
                        <input type="submit" value="Tambah Jadwal" id="submit" name="<?php if ($editing) {echo 'editKapal';} else {echo 'addKapal';} ?>" class="button button-primary">
                    </p>
                    </form>
                    <?php
                    // if submit type is insert
                    if (isset($_POST['addKapal'])) {
                        $departure = new DateTime($_POST['etd']);
                        $arrive    = new DateTime($_POST['eta']);
                        $interval = $departure->diff($arrive);
                        $duration = $interval->format('%a');

                        // set data to array
                        $dataKapal = [
                            'namak'    => $_POST['kapalName'],
                            'namaky'    => $_POST['kapalNamey'],
                            'oriId'    => $_POST['origin'],
                            'destId'   => $_POST['destination'],
                            'cfs'      => $_POST['cfs'],
                            'etd'      => $departure->format('Y-m-d'),
                            'eta'      => $arrive->format('Y-m-d'),
                            'duration' => $duration,
                        ];
                        /* then insert data to database 
                        the function refer to  https://developer.wordpress.org/reference/classes/wpdb/#insert-row
                        click it for details */
                        $insertKapal = $wpdb->insert($wpdb->prefix.'kapals', $dataKapal, ['%s', '%s', '%d', '%d', '%s', '%s', '%s', '%d']);
                        // if the insert is success ...
                        if ($insertKapal) {
                            echo "<script>location.replace('admin.php?page=schedule-act');</script>";
                        }
                    // if submit type is edit
                    } elseif (isset($_POST['editKapal'])) {
                        $departure = new DateTime($_POST['etd']);
                        $arrive    = new DateTime($_POST['eta']);
                        $interval  = $departure->diff($arrive);
                        $duration  = $interval->format('%a');

                        // set data to array
                        $dataKapal = [
                            'namak'    => $_POST['kapalName'],
                            'namaky'    => $_POST['kapalNamey'],
                            'oriId'    => $_POST['origin'],
                            'destId'   => $_POST['destination'],
                            'cfs'      => $_POST['cfs'],
                            'etd'      => $departure->format('Y-m-d'),
                            'eta'      => $arrive->format('Y-m-d'),
                            'duration' => $duration,
                        ];
                        /* then update data to database 
                        the function refer to  https://developer.wordpress.org/reference/classes/wpdb/#update-row
                        click it for details */
                        $updateKapal = $wpdb->update($wpdb->prefix.'kapals', $dataKapal, ['kapalid' => $idkapal], [ '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%d']);
                        // if the update is success ...
                        if ($updateKapal) {
                            echo "<script>location.replace('admin.php?page=schedule-act');</script>";
                        }
                    }
                    ?>
                </div>
                
                <div class="col-6">
                    <h3>Input Port of Loading</h3>
                    
                    <form action="" method="post">
                    <table class="form-table">
                        <tbody>
                        <input type="hidden" name="oriId" id="oriId">
                        <tr>
                            <th scope="row">Location</th>
                            <td><input class="regular-text" type="text" name="oriname" id="oriname" value="<?php if($editing) {echo $editOrigin->oriName;} ?>"></td>
                        </tr>
                        </tbody>
                    </table>
                    <p class="submit">
                        <input type="submit" value="Submit" id="submit" name="<?php if($editing && isset($_GET['origin'])) {echo 'editOrigin';} else {echo 'addOrigin';} ?>" class="button button-primary">
                    </p>
                    </form>
                    <?php
                    // handling port of loading submit
                    if (isset($_POST['addOrigin'])) {
                        // set data to array
                        $dataOrigin = [
                            'oriName' => $_POST['oriname'],
                        ];
                        /* then insert data to database 
                        the function refer to  https://developer.wordpress.org/reference/classes/wpdb/#insert-row
                        click it for details */
                        $insertOrigin = $wpdb->insert($wpdb->prefix.'origins', $dataOrigin, ['%s']);
                        // if the insert is success ...
                        if ($insertOrigin) {
                            echo "<script>location.replace('admin.php?page=schedule-act');</script>";
                        }
                    } elseif (isset($_POST['editOrigin'])) {
                        // set data to array
                        $dataOrigin = [
                            'oriName' => $_POST['oriname'],
                        ];
                        /* then update data to database 
                        the function refer to  https://developer.wordpress.org/reference/classes/wpdb/#update-row
                        click it for details */
                        $updateOrigin = $wpdb->update($wpdb->prefix.'origins', $dataOrigin, ['oriId' => $idorigin], ['%s']);
                        // if the insert is success ...
                        if ($updateOrigin) {
                            echo "<script>location.replace('admin.php?page=schedule-act');</script>";
                        }
                    }
                    ?>
                    <hr/>
                    <hr/>   
                    <h3>Input Destination</h3>
                    
                    <form action="" method="post">
                    <table class="form-table">
                        <tbody>
                        <input type="hidden" name="destid" id="destid">
                        <tr>
                            <th scope="row">Location</th>
                            <td><input class="regular-text" type="text" name="destname" id="destname" value="<?php if($editing) {echo $editDestination->destName;} ?>"></td>
                        </tr>
                        </tbody>
                    </table>
                    <p class="submit">
                        <input type="submit" value="Submit" id="submit" name="<?php if($editing && isset($_GET['destination'])) {echo 'editDestination';} else {echo 'addDestination';} ?>" class="button button-primary">
                    </p>
                    </form>
                    <?php
                    // handling destination submit
                    if (isset($_POST['addDestination'])) {
                        // set data to array
                        $dataDestination = [
                            'destName' => $_POST['destname'],
                        ];
                        /* then insert data to database 
                        the function refer to  https://developer.wordpress.org/reference/classes/wpdb/#insert-row
                        click it for details */
                        $insertDestination = $wpdb->insert($wpdb->prefix.'destinations', $dataDestination, ['%s']);
                        // if the insert is success ...
                        if ($insertDestination) {
                            echo "<script>location.replace('admin.php?page=schedule-act');</script>";
                        }
                    } elseif (isset($_POST['editDestination'])) {
                        // set data to array
                        $dataDestination = [
                            'destName' => $_POST['destname'],
                        ];
                        /* then update data to database 
                        the function refer to  https://developer.wordpress.org/reference/classes/wpdb/#update-row
                        click it for details */
                        $updateDestination = $wpdb->update($wpdb->prefix.'destinations', $dataDestination, ['destId' => $iddestination], ['%s']);
                        // if the insert is success ...
                        if ($updateDestination) {
                            echo "<script>location.replace('admin.php?page=schedule-act');</script>";
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
    }
    ?>