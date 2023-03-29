<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isset($_GET['lead_id'])) {
    $lead_id = $_GET['lead_id'];

    // Fetch the lead data from the database
    $lead = fetch_lead_by_id($lead_id);

    if ($lead) {
        ?>
        <form id="lead-details-form">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($lead['id']); ?>">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($lead['name']); ?>">
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" name="phone" id="phone" value="<?php echo htmlspecialchars($lead['phone']); ?>">
    </div>
    <div class="mb-3">
        <label for="click_id" class="form-label">Click ID</label>
        <input type="text" class="form-control" name="click_id" id="click_id" value="<?php echo htmlspecialchars($lead['click_id']); ?>">
    </div>
    <div class="mb-3">
        <label for="currency" class="form-label">Currency</label>
        <input type="text" class="form-control" name="currency" id="currency" value="<?php echo htmlspecialchars($lead['currency']); ?>">
    </div>
    <div class="mb-3">
        <label for="value" class="form-label">Value</label>
        <input type="text" class="form-control" name="value" id="value" value="<?php echo htmlspecialchars($lead['value']); ?>">
    </div>
    <div class="mb-3">
        <label for="event_time" class="form-label">Event Time</label>
        <input type="text" class="form-control" name="event_time" id="event_time" value="<?php echo htmlspecialchars($lead['event_time']); ?>">
    </div>
    <div class="mb-3">
        <label for="event_name" class="form-label">Event Name</label>
        <input type="text" class="form-control" name="event_name" id="event_name" value="<?php echo htmlspecialchars($lead['event_name']); ?>">
    </div>
    <div class="mb-3">
        <label for="event_source_url" class="form-label">Event Source URL</label>
        <input type="text" class="form-control" name="event_source_url" id="event_source_url" value="<?php echo htmlspecialchars($lead['event_source_url']); ?>">
    </div>
    <div class="mb-3">
        <label for="action_source" class="form-label">Action Source</label>
        <input type="text" class="form-control" name="action_source" id="action_source" value="<?php echo htmlspecialchars($lead['action_source']); ?>">
    </div>
    <div class="mb-3">
        <label for="event_id" class="form-label">Event ID</label>
        <input type="text" class="form-control" name="event_id" id="event_id" value="<?php echo htmlspecialchars($lead['event_id']); ?>">
    </div>
    <div class="mb-3">
        <label for="client_user_agent" class="form-label">Client User Agent</label>
        <input type="text" class="form-control" name="client_user_agent" id="client_user_agent" value="<?php echo htmlspecialchars($lead['client_user_agent']); ?>">
    </div>
    <div class="mb-3">
        <label for="fbc_cookie" class="form-label">FBC Cookie</label>
        <input type="text" class="form-control" name="fbc_cookie" id="fbc_cookie" value="<?php echo htmlspecialchars($lead['fbc_cookie']); ?>">
    </div>
    <div class="mb-3">
        <label for="external_id" class="form-label">External ID</label>
        <input type="text" class="form-control" name="external_id" id="external_id" value="<?php echo htmlspecialchars($lead['external_id']); ?>">
    </div>
    <div class="mb-3">
        <label for="client_ip" class="form-label">Client IP</label>
        <input type="text" class="form-control" name="client_ip" id="client_ip" value="<?php echo htmlspecialchars($lead['client_ip']); ?>">
    </div>
    <div class="mb-3">
        <label for="city" class="form-label">City</label>
        <input type="text" class="form-control" name="city" id="city" value="<?php echo htmlspecialchars($lead['city']); ?>">
    </div>
    <div class="mb-3">
        <label for="country" class="form-label">Country</label>
        <input type="text" class="form-control" name="country" id="country" value="<?php echo htmlspecialchars($lead['country']); ?>">
    </div>
    <div class="mb-3">
        <label for="api_token" class="form-label">API Token</label>
        <input type="text" class="form-control" name="api_token" id="api_token" value="<?php echo htmlspecialchars($lead['api_token']); ?>">
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <input type="text" class="form-control" name="status" id="status" value="<?php echo htmlspecialchars($lead['status']); ?>">
    </div>
    <div class="mb-3">
        <label for="created_at" class="form-label">Created At</label>
        <input type="text" class="form-control" name="created_at" id="created_at" value="<?php echo htmlspecialchars($lead['created_at']); ?>">
    </div>
    <div class="mb-3">
        <label for="updated_at" class="form-label">Updated At</label>
        <input type="text" class="form-control" name="updated_at" id="updated_at" value="<?php echo htmlspecialchars($lead['updated_at']); ?>">
    </div>
    <div class="mb-3">
        <label for="offer" class="form-label">Offer</label>
        <input type="text" class="form-control" name="offer" id="offer" value="<?php echo htmlspecialchars($lead['offer']); ?>">
    </div>
    <div class="mb-3">
        <label for="payout" class="form-label">Payout</label>
        <input type="text" class="form-control" name="payout" id="payout" value="<?php echo htmlspecialchars($lead['payout']); ?>">
    </div>
    <div class="mb-3">
<label for="pixel_id" class="form-label">Pixel ID</label>
<input type="text" class="form-control" name="pixel_id" id="pixel_id" value="<?php echo htmlspecialchars($lead['pixel_id']); ?>">
</div>
<div class="mb-3">
<button type="submit" class="btn btn-primary">Save Changes</button>
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>

</form>
        <?php
    } else {
        echo 'Error: Lead not found';
    }
} else {
    echo 'Error: Lead ID not provided';
}
