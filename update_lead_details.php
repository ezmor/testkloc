<?php
// Include necessary files
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect lead data from the request
    $leadData = array(
        'id' => isset($_POST['id']) ? intval($_POST['id']) : null,
        'name' => isset($_POST['name']) ? $_POST['name'] : null,
        'phone' => isset($_POST['phone']) ? $_POST['phone'] : null,
        'click_id' => isset($_POST['click_id']) ? $_POST['click_id'] : null,
        'currency' => isset($_POST['currency']) ? $_POST['currency'] : null,
        'value' => isset($_POST['value']) ? floatval($_POST['value']) : null,
        'event_time' => isset($_POST['event_time']) ? $_POST['event_time'] : null,
        'event_name' => isset($_POST['event_name']) ? $_POST['event_name'] : null,
        'event_source_url' => isset($_POST['event_source_url']) ? $_POST['event_source_url'] : null,
        'action_source' => isset($_POST['action_source']) ? $_POST['action_source'] : null,
        'event_id' => isset($_POST['event_id']) ? $_POST['event_id'] : null,
        'client_user_agent' => isset($_POST['client_user_agent']) ? $_POST['client_user_agent'] : null,
        'fbc_cookie' => isset($_POST['fbc_cookie']) ? $_POST['fbc_cookie'] : null,
        'external_id' => isset($_POST['external_id']) ? $_POST['external_id'] : null,
        'client_ip' => isset($_POST['client_ip']) ? $_POST['client_ip'] : null,
        'city' => isset($_POST['city']) ? $_POST['city'] : null,
        'country' => isset($_POST['country']) ? $_POST['country'] : null,
        'api_token' => isset($_POST['api_token']) ? $_POST['api_token'] : null,
        'status' => isset($_POST['status']) ? $_POST['status'] : null,
        'created_at' => isset($_POST['created_at']) ? $_POST['created_at'] : null,
        'updated_at' => isset($_POST['updated_at']) ? $_POST['updated_at'] : null,
        'offer' => isset($_POST['offer']) ? $_POST['offer'] : null,
        'payout' => isset($_POST['payout']) ? floatval($_POST['payout']) : null,
        'pixel_id' => isset($_POST['pixel_id']) ? $_POST['pixel_id'] : null
    );

    // Update the lead in the database using the collected data
    $result = update_lead_by_id($leadData['id'], $leadData);

    // Check if the update was successful and output the result
    if ($result) {
        echo "Lead updated successfully";
    } else {
        echo "Error updating lead";
    }
} else {
    echo "Invalid request method";
}
