<?php
include 'includes/authentication.php';
include 'config/dbcon.php';
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';

// Get logged in user ID from session
$user_id = isset($_SESSION['auth_user']['user_id']) ? intval($_SESSION['auth_user']['user_id']) : 0;

// Simple initialization
$server_id = isset($_GET['server_id']) ? intval($_GET['server_id']) : 0;
$server_data = [];

// Fetch all servers for this user (for dropdown)
$servers_list = [];
if ($user_id > 0) {
    $servers_query = "SELECT id, server_name FROM basic_info WHERE user_id = ? ORDER BY server_name";
    $servers_stmt = $con->prepare($servers_query);
    $servers_stmt->bind_param("i", $user_id);
    $servers_stmt->execute();
    $servers_result = $servers_stmt->get_result();
    while ($row = $servers_result->fetch_assoc()) {
        $servers_list[] = $row;
    }
    $servers_stmt->close();
}

// If a specific server is selected, fetch its details
if ($server_id > 0) {
    // Make sure the server belongs to the logged-in user
    $query = "SELECT * FROM basic_info WHERE id = ? AND user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $server_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $server_data = $result->fetch_assoc();
    $stmt->close();
    
    // If no data found, server doesn't belong to user
    if (empty($server_data)) {
        $_SESSION['error'] = "Server not found or you don't have permission to access it";
        header("Location: step1.php");
        exit();
    }
}

// If no server is selected and there are existing servers, maybe show the first one?
if ($server_id == 0 && !empty($servers_list)) {
    // Optionally redirect to the first server
    // header("Location: step1.php?server_id=" . $servers_list[0]['id']);
    // exit();
}
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <?php include 'message.php'; ?>
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
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> <?php echo $_SESSION['success']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $_SESSION['error']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="save-step1.php" id="serverForm">
                        <input type="hidden" name="server_id" value="<?php echo $server_id; ?>">
                        
                        <!-- Server Selection Dropdown (if there are existing servers) -->
                        <?php if (!empty($servers_list)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Select existing server or create new:</strong>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label font-weight-bold">
                                Select Server:
                            </label>
                            <div class="col-sm-9">
                                <select class="form-control" id="server_selector" onchange="loadServer(this.value)">
                                    <option value="">-- Create New Server --</option>
                                    <?php foreach ($servers_list as $server): ?>
                                        <option value="<?php echo $server['id']; ?>" <?php echo ($server_id == $server['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($server['server_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">
                                    Select an existing server to edit or leave as "Create New Server" to add a new one.
                                </small>
                            </div>
                        </div>
                        
                        <script>
                        function loadServer(serverId) {
                            if (serverId) {
                                window.location.href = 'step1.php?server_id=' + serverId;
                            } else {
                                window.location.href = 'step1.php';
                            }
                        }
                        </script>
                        <?php endif; ?>
                        
                        <!-- Server Block -->
                        <div class="security-block p-4 border rounded">
                            
                            <!-- A. Server Name (FQDN) - Now as Datalist or Select -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    A. Server Name (FQDN): <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <!-- Option 1: Using datalist (allows both selection and new input) -->
                                    <input type="text" class="form-control" name="server_name" 
                                           list="server_names"
                                           value="<?php echo htmlspecialchars($server_data['server_name'] ?? ''); ?>"
                                           placeholder="e.g., server01.company.com.np" 
                                           autocomplete="off"
                                           required>
                                    <datalist id="server_names">
                                        <?php foreach ($servers_list as $server): ?>
                                            <option value="<?php echo htmlspecialchars($server['server_name']); ?>">
                                        <?php endforeach; ?>
                                    </datalist>
                                    <small class="form-text text-muted">
                                        You can select an existing server name or type a new one
                                    </small>
                                    
                                    <!-- Option 2: If you prefer a strict dropdown (only select existing) -->
                                    <!--
                                    <select class="form-control" name="server_name" required>
                                        <option value="">-- Select or Create New --</option>
                                        <option value="__new__" disabled>────────── Create New ──────────</option>
                                        <option value="__new_input__" data-new="true">+ Create New Server...</option>
                                        <option value="" disabled>────────── Existing Servers ──────────</option>
                                        <?php foreach ($servers_list as $server): ?>
                                            <option value="<?php echo htmlspecialchars($server['server_name']); ?>" 
                                                <?php echo (isset($server_data['server_name']) && $server_data['server_name'] == $server['server_name']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($server['server_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div id="new_server_input" style="display: none; margin-top: 10px;">
                                        <input type="text" class="form-control" name="new_server_name" 
                                               placeholder="Enter new server name">
                                    </div>
                                    <script>
                                    document.querySelector('select[name="server_name"]').addEventListener('change', function() {
                                        var newInputDiv = document.getElementById('new_server_input');
                                        if (this.value === '__new_input__') {
                                            newInputDiv.style.display = 'block';
                                            this.name = 'server_name_select';
                                            document.querySelector('input[name="new_server_name"]').setAttribute('name', 'server_name');
                                        } else {
                                            newInputDiv.style.display = 'none';
                                        }
                                    });
                                    </script>
                                    -->
                                </div>
                            </div>

                            <!-- Rest of your form remains exactly the same -->
                            <!-- B. Server Platform (Physical/Logical) -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    B. Server Platform (Physical/Logical): <span class="text-danger">*</span>
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
                                        <button type="button" class="btn btn-info px-4 mr-2" onclick="window.location.href='dashboard.php'">
                                            <i class="fas fa-arrow-left mr-1"></i> Previous Step
                                        </button>
                                        <?php if ($server_id > 0): ?>
                                        <button type="button" class="btn btn-danger px-4 mr-2" id="deleteServerBtn">
                                            <i class="fas fa-trash-alt mr-1"></i> Delete Server
                                        </button>
                                        <?php endif; ?>
                                        <button type="submit" name="save" class="btn btn-primary px-5">
                                            <i class="fas fa-save mr-2"></i> Save Basic Info
                                        </button>
                                        <button type="submit" name="save_and_next" class="btn btn-success px-4 ml-2">
                                            Save & Next <i class="fas fa-arrow-right ml-1"></i>
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

<!-- Add this JavaScript to enhance the dropdown experience -->
<script>
// Optional: Add confirmation when creating a new server with existing name
document.getElementById('serverForm').addEventListener('submit', function(e) {
    var serverNameInput = document.querySelector('input[name="server_name"]');
    var serverName = serverNameInput.value.trim();
    
    // Check if this is a new server (no server_id)
    var serverId = document.querySelector('input[name="server_id"]').value;
    
    if (serverId === '0' || serverId === '') {
        // Check if server name already exists in the datalist
        var datalist = document.getElementById('server_names');
        var exists = false;
        
        if (datalist) {
            var options = datalist.options;
            for (var i = 0; i < options.length; i++) {
                if (options[i].value.toLowerCase() === serverName.toLowerCase()) {
                    exists = true;
                    break;
                }
            }
        }
        
        if (exists) {
            if (!confirm('A server with this name already exists. Do you want to update the existing server instead of creating a new one?')) {
                e.preventDefault();
                return false;
            }
        }
    }
});

// delete button handler
var deleteBtn = document.getElementById('deleteServerBtn');
if (deleteBtn) {
    deleteBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to delete this server? This action cannot be undone.')) {
            window.location.href = 'delete-server.php?server_id=' + <?php echo $server_id; ?>;
        }
    });
}
</script>

<style>
/* Add this style to make datalist look better */
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

.form-control, .custom-select {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.9rem;
}

.form-control:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.btn-primary {
    background: #4361ee;
    border-color: #4361ee;
}

.btn-primary:hover {
    background: #3b55d4;
    border-color: #3b55d4;
}

@media (max-width: 768px) {
    .btn {
        width: 100%;
        margin: 5px 0;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
    }
    
    .text-right {
        text-align: left !important;
        margin-top: 10px;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>