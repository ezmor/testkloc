<?php


require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

define('SDK_DIR', __DIR__); // Path to the SDK directory
$loader = include SDK_DIR . '/vendor/autoload.php';

use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;

function objectToJsonString($object) {
    return json_encode((array) $object, JSON_PRETTY_PRINT);
}


function send_event_to_facebook($click_id) {
$lead_data = get_lead_data_by_click_id($click_id);
write_log("Lead Data: " . json_encode($lead_data));

// Fetch lead data from the database using the passed click ID
$lead_data = get_lead_data_by_click_id($click_id);

// Configuration.


$access_token = $lead_data['api_token'];
$pixel_id = $lead_data['pixel_id'];

// Initialize
Api::init(null, null, $access_token);
$api = Api::instance();
$api->setLogger(new CurlLogger());

$events = array();

$user_data_0 = (new UserData())
  ->setPhones([hash('sha256', $lead_data['phone'])])
  ->setFirstNames([hash('sha256', $lead_data['name'])])
  ->setCountryCodes([hash('sha256', $lead_data['country'])])
  ->setExternalIds([$lead_data['external_id']])
  ->setClientIpAddress($lead_data['client_ip'])
  ->setFbc($lead_data['fbc_cookie']);
write_log("Phone: " . $lead_data['phone'] . " => Hashed Phone: " . hash('sha256', $lead_data['phone']));
write_log("Name: " . $lead_data['name'] . " => Hashed Name: " . hash('sha256', $lead_data['name']));
write_log("Country: " . $lead_data['country'] . " => Hashed Country: " . hash('sha256', $lead_data['country']));


$custom_data_0 = (new CustomData())
  ->setValue($lead_data['value'])
  ->setCurrency($lead_data['currency'])
  ->setStatus($lead_data['status'])
  ->setCustomProperties(array("test_event_code" => "TEST37387"));

$event_0 = (new Event())
  ->setEventName($lead_data['event_name'])
  ->setEventTime(strtotime($lead_data['event_time']))
  ->setUserData($user_data_0)
  ->setCustomData($custom_data_0)
  ->setActionSource($lead_data['action_source']);
array_push($events, $event_0);

$request = (new EventRequest($pixel_id))
  ->setEvents($events);
write_log("Sending event to Facebook for click_id: $click_id");
try {
    // Add these updated lines right before executing the request
write_log("Event Data: " . objectToJsonString($event_0));
write_log("User Data: " . objectToJsonString($user_data_0));
write_log("Custom Data: " . objectToJsonString($custom_data_0));



    $response = $request->execute();
    write_log("Event sent successfully for click_id: $click_id. Response: " . json_encode($response));
} catch (Exception $e) {
     write_log("Error sending event for click_id: $click_id. Error: " . $e->getMessage());
    write_log("Full error trace: " . $e->getTraceAsString());
}

write_log("Pixel ID: " . $pixel_id);
write_log("Api Token: " . $access_token);
write_log("Events Array: " . json_encode($events));
// Remove this duplicate line since the request is already executed inside the try block
// $request->execute();

}
