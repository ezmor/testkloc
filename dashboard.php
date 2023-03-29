<?php
// Include necessary files
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Fetch the leads data from the database
$leads = fetch_leads();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Dashboard</title>
    <!-- Include CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- Include JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>

</head>
<body>
   <?php include 'templates/header.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4">Leads Dashboard</h1>
        <button class="btn btn-danger mb-3" id="delete-leads-btn">Delete Selected Leads</button>
        <table id="leads-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Click ID</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leads as $lead): ?>
                    <tr>
                        <td><input type="checkbox" class="lead-checkbox" data-lead-id="<?php echo htmlspecialchars($lead['id']); ?>"></td>
                        <td><?php echo htmlspecialchars($lead['id']); ?></td>
                        <td><?php echo htmlspecialchars($lead['name']); ?></td>
                        <td><?php echo htmlspecialchars($lead['phone']); ?></td>
                        <td><?php echo htmlspecialchars($lead['click_id']); ?></td>
                        <td><?php echo htmlspecialchars($lead['status']); ?></td>
                        <td><?php echo htmlspecialchars($lead['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($lead['updated_at']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary view-lead-btn" data-lead-id="<?php echo htmlspecialchars($lead['id']); ?>">View</button>
                        </td>
                    </tr>
                 <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include 'templates/footer.php'; ?>

    <!-- Lead Details Modal -->
    <div class="modal fade" id="lead-details-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lead-details-modal-label">Lead Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="lead-details-modal-body">
                <!-- Lead details will be loaded here via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-lead-changes-btn">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTables for the leads table
        const table = $('#leads-table').DataTable({
            order: [[6, 'desc']] // Sort by the created_at column (7th column, zero-indexed)
        });

        // Handle view lead button click
        $('.view-lead-btn').on('click', function() {
            const lead_id = $(this).data('lead-id');

            // Fetch lead details via AJAX and display in the modal
            $.ajax({
                url: 'get_lead_details.php',
                method: 'GET',
                data: {
                    lead_id: lead_id
                },
                success: function(response) {
                    $('#lead-details-modal-body').html(response);
                    $('#lead-details-modal').modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error fetching lead details: ' + errorThrown);
                }
            });
        });

        // Handle save lead changes button click
        $('#save-lead-changes-btn').on('click', function() {
            // Collect lead data from the modal
            const leadData = {
                id: $('#lead-details-modal input[name="id"]').val(),
                // Add other fields as needed
            };

            // Send an AJAX request to update the lead details
            $.ajax({
                url: 'update_lead_details.php',
                method: 'POST',
                data: leadData,
                success: function(response) {
                    // Refresh the page to show the updated details
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error updating lead details: ' + errorThrown);
                }
            });
        });

        // Handle delete leads button click
        $('#delete-leads-btn').on('click', function() {
            const leadIds = [];

            $('.lead-checkbox:checked').each(function() {
                leadIds.push($(this).data('lead-id'));
            });

            if (leadIds.length === 0) {
                alert('No leads selected');
                return;
            }

            if (confirm('Are you sure you want to delete ' + leadIds.length + ' leads?')) {
                // Send an AJAX request to delete the selected leads
                $.ajax({
                    url: 'delete_leads.php',
                    method: 'POST',
                    data: {
                        lead_ids: leadIds
                    },
                    success: function(response) {
                        // Refresh the page to show the updated list
                        location.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting leads: ' + errorThrown);
                    }
                });
            }
        });
    });
</script>
</body>
</html>
