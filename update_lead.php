<?php
// Include necessary files
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get the Click ID and new status from the POST data
$click_id = $_GET['click_id'];
$new_status = $_GET['status'];

// Update the lead status in the database using the new function
update_lead_by_click_id($click_id, ['status' => $new_status]);

// If the new status is "confirmed", send the event to Facebook
if ($new_status === 'confirmed') {
     // Fetch lead data by lead ID
     $lead_data = get_lead_data_by_click_id($click_id);

     // Set access token and pixel ID from database or configuration
     $access_token = $lead_data['api_token'];
     $pixel_id = $lead_data['pixel_id'];

     // Include the send_event_to_facebook.php script
     require_once 'send_event_to_facebook.php';
}
