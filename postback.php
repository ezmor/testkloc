<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'send_event_to_facebook.php';

// Check if the necessary parameters are present in the request
if (isset($_GET['click_id']) && isset($_GET['status'])) {
    $click_id = $_GET['click_id'];

    $lead_data = array(
        'status' => $_GET['status'],
        'name' => $_GET['name'] ?? null,
        'phone' => $_GET['phone'] ?? null,
        'currency' => $_GET['currency'] ?? null,
        'value' => $_GET['value'] ?? null,
        'event_time' => $_GET['event_time'] ?? null,
        'event_name' => $_GET['event_name'] ?? null,
        'event_source_url' => $_GET['event_source_url'] ?? null,
        'action_source' => $_GET['action_source'] ?? null,
        'event_id' => $_GET['event_id'] ?? null,
        'client_user_agent' => $_GET['client_user_agent'] ?? null,
        'fbc_cookie' => $_GET['fbc_cookie'] ?? null,
        'external_id' => $_GET['external_id'] ?? null,
        'client_ip' => $_GET['client_ip'] ?? null,
        'city' => $_GET['city'] ?? null,
        'country' => $_GET['country'] ?? null,
        'api_token' => $_GET['api_token'] ?? null,
        'offer' => $_GET['offer'] ?? null,
        'payout' => $_GET['payout'] ?? null,
        'pixel_id' => $_GET['pixel_id'] ?? null
        // Add other fields here if you want to update them as well
        // For example: 'name' => $_GET['name'] ?? null
    );

    // Update lead data based on the click_id
    $updated = update_lead_by_click_id($click_id, $lead_data);

    if ($updated) {
        echo "Lead data updated successfully.";

        // Get the new status from the $_GET data
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

            // Call the send_event_to_facebook function with the click ID as a parameter
            send_event_to_facebook($click_id);

        } else {
        echo "Error updating lead data. Please check if the click_id is correct.";
    }
} else {
    echo "Invalid request. Please provide both click_id and status.";
}
}
?>
