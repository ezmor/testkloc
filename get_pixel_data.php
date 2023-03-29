// get_pixel_data.php
<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$click_id = isset($_GET['click_id']) ? $_GET['click_id'] : '';

if ($click_id) {
    $lead = get_lead_by_click_id($click_id);
    if ($lead) {
        echo json_encode(array(
            'pixel_id' => $lead['pixel_id'],
            'lead_status' => $lead['status']
        ));
    } else {
        echo json_encode(array('error' => 'Lead not found.'));
    }
} else {
    echo json_encode(array('error' => 'Invalid click_id.'));
}
