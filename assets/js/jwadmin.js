jQuery(document).ready(function() {
	jQuery('#cfs, #etd, #eta').datepicker({ dateFormat: 'yy-mm-dd' });	
})

function deleteKapal(id) {
    var deleteconfirm = confirm("Anda yakin ingin menghapus ini ?");
    if (deleteconfirm == true) {
        location.replace('admin.php?page=schedule-act&delkapal='+ id);
    }
}

function deleteDestination(id) {
    var deleteconfirm = confirm("Anda yakin ingin menghapus ini ?");
    if (deleteconfirm == true) {
        location.replace('admin.php?page=schedule-act&deldestination='+ id);
    }
}

function deleteOrigin(id) {
    var deleteconfirm = confirm("Anda yakin ingin menghapus ini ?");
    if (deleteconfirm == true) {
        location.replace('admin.php?page=schedule-act&delorigin='+ id);
    }
}