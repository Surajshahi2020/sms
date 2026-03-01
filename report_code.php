<?php
session_start();
include 'config/dbcon.php'; // Ensure $con is your mysqli connection

if (isset($_POST['submit_report'])) {
    $incident_id    = $_POST['incident_id'] ?? null;
    $incident_title = $_POST['incident_title'] ?? '';
    $officer_id     = $_SESSION['auth_user']['user_id'] ?? null;
    $user_name      = $_SESSION['auth_user']['user_name'] ?? 'Guest';
    $officer_display_name = ''; // This will hold "Name (Rank)"

    if ($officer_id) {
        $stmt = $con->prepare("
            SELECT 
                u.full_name_ne,
                r.name_nepali
            FROM users u
            LEFT JOIN ranks r ON u.rank_code = r.rank_code
            WHERE u.id = ?
        ");
        $stmt->bind_param("i", $officer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $name = htmlspecialchars($row['full_name_ne'] ?? '');
            $rank = htmlspecialchars($row['name_nepali'] ?? '');

            // Combine: "Name (Rank)" — only show rank if available
            if ($name && $rank) {
                $officer_display_name = "{$rank} {$name}";
            } else {
                $officer_display_name = $name ?: $rank ?: '';
            }
        }
        $stmt->close();
    }

    if (!$incident_id || !$officer_id) {
        $_SESSION['status'] = "Missing required information.";
        $_SESSION['msg_type'] = "danger";
        header("Location: report.php?id=" . ($incident_id ?? ''));
        exit();
    }

    $report_text = !empty($_POST['incident_text']) ? $_POST['incident_text'] : NULL;
    $report_file_name = NULL;

    if (empty($_FILES['incident_file']['name'])) {
        $_SESSION['status'] = "Please attach a file. File is mandatory!";
        $_SESSION['msg_type'] = "warning";
        header("Location: report.php?id=$incident_id");
        exit();
    } else {
        $file_name = $_FILES['incident_file']['name'];
        $file_tmp  = $_FILES['incident_file']['tmp_name'];
        $upload_dir = 'reports/'; // Ensure folder exists and writable
        $report_file_name = time() . '_' . basename($file_name);

        if (!move_uploaded_file($file_tmp, $upload_dir . $report_file_name)) {
            $_SESSION['status'] = "Failed to upload file.";
            $_SESSION['msg_type'] = "danger";
            header("Location: report.php?id=$incident_id");
            exit();
        }
    }

    // Insert into incident_reports table
    $stmt = $con->prepare("INSERT INTO incident_reports (incident_id, officer_id, report_text, report_file, created_at, created_by) 
                           VALUES (?, ?, ?, ?, NOW(), ?)");
    if (!$stmt) {
        $_SESSION['status'] = "Database error: " . $con->error;
        $_SESSION['msg_type'] = "danger";
        header("Location: report_info.php?id=$incident_id");
        exit();
    }
    $stmt->bind_param("iisss", $incident_id, $officer_id, $report_text, $report_file_name, $officer_display_name);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Report submitted successfully!";
        $_SESSION['msg_type'] = "success";

        // ----------------------------
        // Audit log insertion
        // ----------------------------
        $report_data = json_encode([
            'incident_id' => $incident_id,
            'officer_id'  => $officer_id,
            'report_text' => $report_text,
            'report_file' => $report_file_name
        ], JSON_UNESCAPED_UNICODE);

        $table_name  = 'incident_reports';
        $action_type = 'INSERT';
        $record_id   = $stmt->insert_id;

        $audit_stmt = $con->prepare("INSERT INTO audit_log (table_name, record_id, action_type, old_data, new_data, performed_by, user_name)
                                     VALUES (?, ?, ?, '[]', ?, ?, ?)");
        $audit_stmt->bind_param("sissis", $table_name, $record_id, $action_type, $report_data, $officer_id, $user_name);
        $audit_stmt->execute();

    } else {
        $_SESSION['status'] = "Execution failed: " . $stmt->error;
        $_SESSION['msg_type'] = "danger";
    }

    $stmt->close();
    header("Location: report.php?id=$incident_id");
    exit();
}

if (isset($_GET['id'], $_GET['action'])) {
    $report_id = intval($_GET['id']);
    $action = $_GET['action']; // accept or reject
    if (!in_array($action, ['accept', 'reject'])) {
        $_SESSION['status'] = "Invalid action.";
        $_SESSION['msg_type'] = "danger";
        header("Location: report_info.php");
        exit();
    }
    $stmtCheck = $con->prepare("SELECT status FROM incident_reports WHERE id = ?");
    $stmtCheck->bind_param("i", $report_id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $report = $result->fetch_assoc();
    if ($report) {
        $new_status = ($action === 'accept') ? 'accepted' : 'rejected';
        if ($report['status'] === $new_status) {
            $_SESSION['status'] = "Report is already " . ucfirst($new_status) . ".";
            $_SESSION['msg_type'] = ($new_status === 'accepted') ? "success" : "danger";
        } else {
            $stmtUpdate = $con->prepare("UPDATE incident_reports SET status = ? WHERE id = ?");
            $stmtUpdate->bind_param("si", $new_status, $report_id);
            if ($stmtUpdate->execute()) {
                $_SESSION['status'] = "Report has been " . ucfirst($new_status) . ".";
                $_SESSION['msg_type'] = ($new_status === 'accepted') ? "success" : "info";
            } else {
                $_SESSION['status'] = "Error updating report: " . $stmtUpdate->error;
                $_SESSION['msg_type'] = "danger";
            }
            $stmtUpdate->close();
        }
    } else {
        $_SESSION['status'] = "Report not found.";
        $_SESSION['msg_type'] = "warning";
    }
    $stmtCheck->close();
    header("Location: report_change.php");
    exit();
}


if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    // Toggle is_report: 1 → 0, 0 → 1
    $sql = "UPDATE users 
            SET is_report = IF(is_report = 1, 0, 1) 
            WHERE id = ?";
            
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status";
    }
}

?>
