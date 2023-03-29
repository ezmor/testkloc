<?php

require_once 'includes/functions.php';

// Check if the postback data is received via GET or POST request
if (!empty($_GET)) {
    $postback_data = $_GET;
} elseif (!empty($_POST)) {
    $postback_data = $_POST;
} else {
    // No postback data received
    die("No postback data received.");
}

// Check if the required parameters (click_id and status) are present
if (!isset($postback_data['click_id']) || !isset($postback_data['status'])) {
    die("Required parameters (click_id and status) are missing.");
}

// Remove click_id from the postback data, as it will be used separately
$click_id = $postback_data['click_id'];
unset($postback_data['click_id']);

// Call the update_lead_by_click_id function to update the lead information
$result = update_lead_by_click_id($click_id, $postback_data);

if ($result) {
    echo "Lead updated successfully.";
} else {
    echo "Failed to update lead.";
}

?>