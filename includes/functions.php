<?php

require_once 'db.php'; // Include the db.php file for the database connection

// Function to fetch leads from the database
function fetch_leads($status = null) {
    global $pdo;

    $sql = "SELECT * FROM leads";

    if ($status) {
        $sql .= " WHERE status = :status";
    }

    $stmt = $pdo->prepare($sql);

    if ($status) {
        $stmt->bindParam(':status', $status);
    }

    $stmt->execute();

    $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $leads;
}


// Function to insert a lead into the database
/*function insert_lead($lead_data) {
    global $mysqli;

    // Prepare the SQL query with placeholders for each field that can be inserted
    $sql = "INSERT INTO leads (name, phone, click_id, currency, value, event_time, event_name, event_source_url, action_source, event_id, client_user_agent, fbc_cookie, external_id, client_ip, city, country, api_token, offer, payout, pixel_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $fields = array();
    $placeholders = array();
    $types = '';
    $values = array();

    // Loop through the lead_data array and add fields that have values to the query
    foreach ($lead_data as $field => $value) {
        if ($value !== null && $value !== '') {
            $fields[] = $field;
            $placeholders[] = '?';
            $types .= get_param_type($value); // Get the appropriate type for bind_param
            $values[] = $value;
        }
    }

    $sql .= implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";

    $stmt = $mysqli->prepare($sql);

    // Bind the values for each field
    $stmt->bind_param('ssssdssssssssssssdds', $lead_data['name'], $lead_data['phone'], $lead_data['click_id'], $lead_data['currency'], $lead_data['value'], $lead_data['event_time'], $lead_data['event_name'], $lead_data['event_source_url'], $lead_data['action_source'], $lead_data['event_id'], $lead_data['client_user_agent'], $lead_data['fbc_cookie'], $lead_data['external_id'], $lead_data['client_ip'], $lead_data['city'], $lead_data['country'], $lead_data['api_token'], $lead_data['offer'], $lead_data['payout'], $lead_data['pixel_id']);

    $stmt->execute();
    $affected_rows = $stmt->affected_rows;
    $stmt->close();

    return $affected_rows > 0;
}
*/
/*function insert_lead($lead_data) {
    global $mysqli;

    // Check if a lead with the same click_id already exists
    $check_sql = "SELECT id FROM leads WHERE click_id = ?";
    $check_stmt = $mysqli->prepare($check_sql);
    $check_stmt->bind_param('s', $lead_data['click_id']);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // A lead with the same click_id already exists, return false
        $check_stmt->close();
        return false;
    }

    $check_stmt->close();


    $fields = array();
    $placeholders = array();
    $types = '';
    $values = array();

    // Loop through the lead_data array and add fields that have values to the query
    foreach ($lead_data as $field => $value) {
        if ($value !== null && $value !== '') {
            $fields[] = $field;
            $placeholders[] = '?';
            $types .= get_param_type($value); // Get the appropriate type for bind_param
            $values[] = $value;
        }
    }

    $sql = "INSERT INTO leads (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";

    $stmt = $mysqli->prepare($sql);

    // Call bind_param dynamically using the values array
    $params = array_merge(array($types), $values);
    $tmp = array();
    foreach ($params as $key => $value) {
        $tmp[$key] = &$params[$key];
    }
    call_user_func_array(array($stmt, 'bind_param'), $tmp);

    $stmt->execute();
    $affected_rows = $stmt->affected_rows;
    $stmt->close();

    return $affected_rows > 0;
}*/
function insert_lead($lead_data) {
    global $pdo;

    // Check if a lead with the same click_id already exists
    $check_sql = "SELECT id FROM leads WHERE click_id = :click_id";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute(['click_id' => $lead_data['click_id']]);
    $existing_lead = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_lead) {
        // If a lead with the same click_id already exists, update the lead
        return update_lead_by_click_id($lead_data['click_id'], $lead_data);
    } else {
        // If no lead with the same click_id exists, insert a new lead
        $fields = array();
        $placeholders = array();
        $params = array();

        // Loop through the lead_data array and add fields that have values to the query
        foreach ($lead_data as $field => $value) {
            if ($value !== null && $value !== '') {
                $fields[] = $field;
                $placeholders[] = ':' . $field;
                $params[$field] = $value;
            }
        }

        $sql = "INSERT INTO leads (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";

        $stmt = $pdo->prepare($sql);

        $stmt->execute($params);
        $affected_rows = $stmt->rowCount();

        return $affected_rows > 0;
    }
}





// Function to update a lead's status in the database
function update_lead_status($lead_id, $status) {
    global $pdo;
    $sql = "UPDATE leads SET status = :status WHERE id = :lead_id";

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute(['status' => $status, 'lead_id' => $lead_id]);

    return $result;
}

// Function to fetch a lead by ID from the database
function get_lead_by_id($lead_id) {
    global $pdo;
    $sql = "SELECT * FROM leads WHERE id = :lead_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['lead_id' => $lead_id]);

    $lead = $stmt->fetch(PDO::FETCH_ASSOC);

    return $lead;
}

// Function to fetch leads by user ID from the database (optional, if leads are associated with users)
function get_leads_by_user_id($user_id) {
    global $pdo;

    $sql = "SELECT * FROM leads WHERE user_id = :user_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);

    $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $leads;
}

// Function to update a lead using `click_id` and other parameters received via postback
function update_lead_by_click_id($click_id, $params) {
    global $pdo;

    // Prepare the SQL query with placeholders for each field that can be updated
    $sql = "UPDATE leads SET ";

    $fields = array();
    $values = array();

    // Loop through the received parameters and add them to the query if they have values
    foreach ($params as $field => $value) {
        if ($value !== null && $value !== '') {
            $fields[] = "{$field} = :{$field}";
            $values[$field] = $value;
        }
    }

    // If there are no fields to update, return early
    if (count($fields) === 0) {
        return false;
    }

    $sql .= implode(', ', $fields) . " WHERE click_id = :click_id";

    $stmt = $pdo->prepare($sql);

    // Bind the values for each field and the click_id
    $values['click_id'] = $click_id;

    $result = $stmt->execute($values);

    return $result;
}


function get_param_type($value) {
    $type = 's'; // Default to string
    if (is_integer($value)) {
        $type = 'i';
    } elseif (is_float($value)) {
        $type = 'd';
    }

    return $type;
}
// Function to fetch lead data from the database using the click_id
function get_lead_data_by_click_id($click_id) {
    global $pdo;

    $sql = "SELECT * FROM leads WHERE click_id = :click_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['click_id' => $click_id]);

    $lead_data = $stmt->fetch(PDO::FETCH_ASSOC);

    return $lead_data;
}

// Function to authenticate a user using the provided username and password
function authenticate_user($username, $password) {
    // Declare the global variable $pdo
    global $pdo;

    // Prepare the SQL query to fetch user information
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");

    // Bind the username to the query and execute it
    $stmt->execute([$username]);

    // Fetch the result
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the provided password
    if ($user && password_verify($password, $user['password'])) {
        // Return the user ID if authentication is successful
        return $user['id'];
    }

    // Close the statement
    $stmt->closeCursor();

    // Return false if authentication failed
    return false;
}

function write_log($message) {
    $log_file = 'logs/facebook_events.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}
function fetch_lead_by_id($lead_id) {
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM leads WHERE id = :lead_id');
    $stmt->execute(['lead_id' => $lead_id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// Function to update a lead using its ID and an array of parameters
function update_lead_by_id($lead_id, $params) {
    global $pdo;

    // Prepare the SQL query with placeholders for each field that can be updated
    $sql = "UPDATE leads SET ";

    $fields = array();
    $values = array();

    // Loop through the received parameters and add them to the query if they have values
    foreach ($params as $field => $value) {
        if ($value !== null && $value !== '') {
            $fields[] = "{$field} = ?";
            $values[] = $value;
        }
    }

    // If there are no fields to update, return early
    if (count($fields) === 0) {
        return false;
    }

    $sql .= implode(', ', $fields) . " WHERE id = ?";

    $stmt = $pdo->prepare($sql);

    // Add the lead ID to the values array
    $values[] = $lead_id;

    // Bind the values for each field and the lead_id
    $stmt->execute($values);

    // Check if the update was successful and return the result
    $result = ($stmt->rowCount() > 0);
    $stmt->closeCursor();

    return $result;
}



?>
