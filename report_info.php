<?php
include 'includes/authentication.php';
include 'supporter/permissions.php';
if (!(is_admin())) {
    include 'supporter/access_denied.php';
    exit();
}

include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'config/dbcon.php'; 
include_once 'supporter/permissions.php';

$current_user_id = $_SESSION['auth_user']['user_id'] ?? null;
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
                               <th>Remarks</th>
                            </tr>
                        </thead>


                       <tbody>
                            <?php
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
                                if (mysqli_num_rows($result) > 0) {
                                    $i = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
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
                                                <td>" . htmlspecialchars($row['remarks'] ?? 'No remarks') . "</td>
                                            </tr>";

                                        $i++;
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No reports found.</td></tr>";
                                }
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
include 'includes/script.php'; // ✅ scripts loaded from here
?>
