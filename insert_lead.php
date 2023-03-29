<?php
include_once 'includes/config.php';
include_once 'includes/db.php';
include_once 'includes/functions.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the lead data from the form
    $lead_data = [
        'name' => isset($_POST['name']) ? $_POST['name'] : '',
        'phone' => isset($_POST['phone']) ? $_POST['phone'] : '',
        'click_id' => isset($_POST['sub1']) ? $_POST['sub1'] : '',
        'currency' => isset($_POST['currency']) ? $_POST['currency'] : '',
        'value' => isset($_POST['value']) ? $_POST['value'] : '',
        'event_time' => isset($_POST['event_time']) ? $_POST['event_time'] : '',
        'event_name' => isset($_POST['event_name']) ? $_POST['event_name'] : '',
        'event_source_url' => isset($_POST['event_source_url']) ? $_POST['event_source_url'] : '',
        'action_source' => isset($_POST['action_source']) ? $_POST['action_source'] : '',
        'event_id' => isset($_POST['event_id']) ? $_POST['event_id'] : '',
        'client_user_agent' => isset($_POST['client_user_agent']) ? $_POST['client_user_agent'] : '',
        'fbc_cookie' => isset($_POST['fbc_cookie']) ? $_POST['fbc_cookie'] : '',
        'external_id' => isset($_POST['external_id']) ? $_POST['external_id'] : '',
        'client_ip' => isset($_POST['client_ip']) ? $_POST['client_ip'] : '',
        'city' => isset($_POST['city']) ? $_POST['city'] : '',
        'country' => isset($_POST['country']) ? $_POST['country'] : '',
        'api_token' => isset($_POST['api_token']) ? $_POST['api_token'] : '',
        'offer' => isset($_POST['offer']) ? $_POST['offer'] : '',
        'payout' => isset($_POST['payout']) ? $_POST['payout'] : '',
        'pixel_id' => isset($_POST['pixel_id']) ? $_POST['pixel_id'] : ''
    ];

 
   // Insert the lead into the database
$inserted = insert_lead($lead_data);

if ($inserted) {
    // If the lead was successfully inserted, send a success response
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    // Check if a lead with the same click_id already exists
    $check_sql = "SELECT id FROM leads WHERE click_id = ?";
    $check_stmt = $mysqli->prepare($check_sql);
    $check_stmt->bind_param('s', $lead_data['click_id']);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // If a lead with the same click_id already exists, send an error response
        header('Content-Type: application/json');
        http_response_code(409); // Conflict
        echo json_encode(['success' => false, 'error' => 'A lead with the same click_id already exists.']);
    } else {
        // If there was another error, send a generic error response
        header('Content-Type: application/json');
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'error' => 'There was an error capturing the lead. Please try again.']);
    }

    $check_stmt->close();
}

}
