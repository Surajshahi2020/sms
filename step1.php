<?php
// step1-basic.php
include 'includes/authentication.php';
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'config/dbcon.php';

// Fetch existing data if editing
$server_id = isset($_GET['server_id']) ? $_GET['server_id'] : 0;
$server_data = [];
if ($server_id) {
    $query = "SELECT * FROM servers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $server_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $server_data = $result->fetch_assoc();
}
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="m-0">
                        <i class="fas fa-info-circle mr-2" style="color: #4361ee;"></i>
                        1. Basic Info
                    </h2>
                    <p class="text-muted mt-1">Server basic information</p>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="server-checklist.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Steps
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="save-step1.php" id="serverForm">
                        <input type="hidden" name="server_id" value="<?php echo $server_id; ?>">
                        
                        <!-- Server Block -->
                        <div class="security-block p-4 border rounded">
                            
                            <!-- A. Server Name (FQDN) -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    A. Server Name (FQDN):
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="server_name" 
                                           value="<?php echo htmlspecialchars($server_data['server_name'] ?? ''); ?>"
                                           placeholder="e.g., server01.company.com.np" required>
                                    <div class="mt-2 text-primary">
                                        <small>
                                            <i class="fas fa-book-open mr-1"></i>
                                            Reference: साइबर सुरक्षा नीति, पेज नं. १४, बुँदा ३४ को (ख)
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- B. Server Platform (Physical/Logical) -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    B. Server Platform (Physical/Logical):
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="platform" id="platformPhysical" value="Physical" <?php echo (isset($server_data['platform']) && $server_data['platform'] == 'Physical') ? 'checked' : ''; ?> required>
                                                <label class="custom-control-label" for="platformPhysical">Physical</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="platform" id="platformLogical" value="Logical" <?php echo (isset($server_data['platform']) && $server_data['platform'] == 'Logical') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="platformLogical">Logical</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- C. Purpose -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    C. Purpose:
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="purpose" 
                                           value="<?php echo htmlspecialchars($server_data['purpose'] ?? ''); ?>"
                                           placeholder="e.g., Web Server, Database Server, Application Server">
                                </div>
                            </div>

                            <!-- D. Running Services -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    D. Running Services:
                                </label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="services" rows="3" 
                                              placeholder="List all running services (one per line)&#10;e.g.:&#10;Apache HTTP Server&#10;MySQL Database&#10;PHP-FPM&#10;OpenSSH"><?php echo htmlspecialchars($server_data['services'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- E. IP Address -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    E. IP Address:
                                </label>
                                <div class="col-sm-9">
                                    
                                    <!-- I. Public -->
                                    <div class="p-3 bg-light rounded mb-3">
                                        <label class="font-weight-bold">I. Public IP:</label>
                                        <input type="text" class="form-control" name="public_ip" 
                                               value="<?php echo htmlspecialchars($server_data['public_ip'] ?? ''); ?>"
                                               placeholder="e.g., 202.51.80.100">
                                    </div>

                                    <!-- II. Private -->
                                    <div class="p-3 bg-light rounded">
                                        <label class="font-weight-bold">II. Private IP:</label>
                                        <input type="text" class="form-control" name="private_ip" 
                                               value="<?php echo htmlspecialchars($server_data['private_ip'] ?? ''); ?>"
                                               placeholder="e.g., 10.0.0.5, 192.168.1.10">
                                    </div>
                                </div>
                            </div>

                            <!-- F. MAC Address -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    F. MAC Address:
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="mac_address" 
                                           value="<?php echo htmlspecialchars($server_data['mac_address'] ?? ''); ?>"
                                           placeholder="e.g., 00:1A:2B:3C:4D:5E">
                                </div>
                            </div>

                            <!-- G. Priority of Server -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    G. Priority of Server:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="priority" id="priorityCritical" value="Critical" <?php echo (isset($server_data['priority']) && $server_data['priority'] == 'Critical') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="priorityCritical">Critical</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="priority" id="priorityHigh" value="High" <?php echo (isset($server_data['priority']) && $server_data['priority'] == 'High') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="priorityHigh">High</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="priority" id="priorityMedium" value="Medium" <?php echo (isset($server_data['priority']) && $server_data['priority'] == 'Medium') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="priorityMedium">Medium</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="priority" id="priorityLow" value="Low" <?php echo (isset($server_data['priority']) && $server_data['priority'] == 'Low') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="priorityLow">Low</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label font-weight-bold text-primary">Remarks:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="remarks" rows="2" 
                                              placeholder="Any additional remarks or notes for this server"><?php echo htmlspecialchars($server_data['remarks'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <button type="reset" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-undo mr-1"></i> Reset Form
                                    </button>
                                    <div>
                                        <button type="button" class="btn btn-info px-4 mr-2" onclick="window.location.href='index.php'">
                                            <i class="fas fa-arrow-left mr-1"></i> Previous Step
                                        </button>
                                        <button type="submit" name="save_step1" class="btn btn-primary px-5">
                                            <i class="fas fa-save mr-2"></i> Save Basic Info
                                        </button>
                                        <button type="button" class="btn btn-info px-4 ml-2" onclick="window.location.href='step2.php<?php echo $server_id ? '?server_id='.$server_id : ''; ?>'">
                                            Next Step <i class="fas fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.security-block {
    background: #ffffff;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef !important;
}

.security-block:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    border-color: #4361ee !important;
}

.form-group.row {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.form-group.row:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.col-form-label {
    font-weight: 600;
    color: #1e293b;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.bg-light.rounded {
    border-left: 3px solid #4361ee;
}

.form-control, .custom-select {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.form-control:focus, .custom-select:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.custom-control-label {
    cursor: pointer;
}

.btn-primary {
    background: #4361ee;
    border-color: #4361ee;
}

.btn-primary:hover {
    background: #3b55d4;
    border-color: #3b55d4;
}

/* Responsive */
@media (max-width: 768px) {
    .form-group.row {
        margin-bottom: 2rem;
    }
    
    .col-sm-3, .col-sm-9 {
        margin-bottom: 0.5rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn {
        width: 100%;
        margin: 5px 0 !important;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>