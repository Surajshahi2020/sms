<?php
// Start output buffering at the very beginning
ob_start();

include 'includes/authentication.php';
include 'supporter/permissions.php';
if (!(is_super_admin())) {
    include 'supporter/access_denied.php';
    exit();
}

include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'config/dbcon.php'; 
include_once 'supporter/permissions.php';

$current_user_id = $_SESSION['auth_user']['user_id'] ?? null;

// Handle remarks form submission
if (isset($_POST['save_remark'])) {
    $report_id = mysqli_real_escape_string($con, $_POST['report_id']);
    $remark_text = mysqli_real_escape_string($con, $_POST['remark_text']);
    
    // Update the remarks field in incident_reports table
    $query = "UPDATE incident_reports 
              SET remarks = '$remark_text' 
              WHERE id = '$report_id'";
    
    if (mysqli_query($con, $query)) {
        $_SESSION['message'] = "Remark saved successfully!";
    } else {
        $_SESSION['message'] = "Error saving remark: " . mysqli_error($con);
    }
    
    // Clear output buffer before redirect
    ob_end_clean();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch remarks for display
$remarks = [];
$result = null;

if ($current_user_id) {
    if (is_super_admin()) {
        $query = "
            SELECT ir.*, i.title AS incident_title
            FROM incident_reports ir
            INNER JOIN incidents i ON ir.incident_id = i.id
            ORDER BY ir.created_at DESC
        ";
    } else {
        $current_user_id = mysqli_real_escape_string($con, $current_user_id);
        $query = "
            SELECT ir.*, i.title AS incident_title
            FROM incident_reports ir
            INNER JOIN incidents i ON ir.incident_id = i.id
            WHERE ir.officer_id = '$current_user_id'
            ORDER BY ir.created_at DESC
        ";
    }

    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $remarks[$row['id']] = $row['remarks'];
        }
        // Reset result pointer for main display
        mysqli_data_seek($result, 0);
    }
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2"></div>
        </div>
    </div>

    <section class="content">
        <div class="container">
            <?php include 'message.php'; ?>

            <!-- Remarks Modal -->
            <div class="modal fade" id="remarksModal" tabindex="-1" role="dialog" aria-labelledby="remarksModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="remarksModalLabel">Add/Edit Remarks</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="">
                            <div class="modal-body">
                                <input type="hidden" name="report_id" id="report_id">
                                <div class="form-group">
                                    <label for="remark_text">Remarks:</label>
                                    <textarea class="form-control" id="remark_text" name="remark_text" rows="5" placeholder="Enter your remarks here..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="save_remark" class="btn btn-primary">Save Remarks</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                               <th>क्र.सं.</th>
                               <th>घटना</th>
                               <th>प्रतिवेदन</th>
                               <th>फाइल</th>
                               <th>पेश गरिएको मिति</th>
                               <th>स्थिति</th>
                               <th>पठाउने</th>
                               <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($current_user_id && $result && mysqli_num_rows($result) > 0) {
                                $i = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $report_id = $row['id'];
                                    $current_remark = $row['remarks'] ?? '';
                                    
                                    echo "<tr>
                                            <td>{$i}</td>
                                            <td>" . htmlspecialchars($row['incident_title'] ?? '') . "</td>
                                            <td>" . htmlspecialchars($row['report_text'] ?? '') . "</td>
                                            <td>";
                                    if (!empty($row['report_file'])) {
                                        echo "<a href='reports/" . htmlspecialchars($row['report_file']) . "' target='_blank' style='color: green; font-weight: bold; text-decoration: none;'>View</a>";
                                    } else {
                                        echo "No File";
                                    }
                                    echo "</td>
                                            <td>" . htmlspecialchars($row['created_at']) . "</td>
                                            <td>";

                                    if (is_super_admin()) {
                                        switch ($row['status']) {
                                            case 'accepted':
                                                echo "<a href='report_code.php?id={$row['id']}&action=reject' class='btn btn-danger btn-sm'>Reject</a>";
                                                break;
                                            case 'rejected':
                                                echo "<a href='report_code.php?id={$row['id']}&action=accept' class='btn btn-success btn-sm'>Accept</a>";
                                                break;
                                            default:
                                                echo "<a href='report_code.php?id={$row['id']}&action=accept' class='btn btn-success btn-sm'>Accept</a>";
                                        }
                                    } else {
                                        $status = $row['status'] ?? 'pending';
                                        $status_label = ucfirst($status);
                                        $badge_class = $status === 'accepted' ? 'bg-success' : ($status === 'rejected' ? 'bg-danger' : 'bg-warning');
                                        echo "<span class='badge {$badge_class}'>$status_label</span>";
                                    }

                                    echo "</td>
                                            <td>" . htmlspecialchars($row['created_by']) . "</td>
                                            <td>
                                                <button type='button' class='btn btn-info btn-sm remarks-btn' 
                                                        data-report-id='{$report_id}' 
                                                        data-remark-text='" . htmlspecialchars($current_remark) . "'>
                                                    " . (!empty($current_remark) ? 'View/Edit Remarks' : 'Add Remarks') . "
                                                </button>
                                            </td>
                                          </tr>";

                                    $i++;
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'>No reports found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>

<?php
include 'includes/footer.php';
include 'includes/script.php';
?>

<script>
$(document).ready(function() {
    // Handle remarks button click
    $('.remarks-btn').click(function() {
        var reportId = $(this).data('report-id');
        var remarkText = $(this).data('remark-text');
        
        $('#report_id').val(reportId);
        $('#remark_text').val(remarkText);
        
        $('#remarksModal').modal('show');
    });
});
</script>