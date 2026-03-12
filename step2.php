<?php
// step2-os.php
include 'includes/authentication.php';
include 'config/dbcon.php';

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

// If a specific server is selected, fetch its details (including OS info)
if ($server_id > 0) {
    $query = "SELECT bi.*, so.*
              FROM basic_info bi
              LEFT JOIN server_os_info so ON so.basic_info_id = bi.id
              WHERE bi.id = ? AND bi.user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $server_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $server_data = $result->fetch_assoc();
    $stmt->close();
    if (empty($server_data)) {
        $_SESSION['error'] = "Server not found or you don't have permission to access it";
        header("Location: step2.php");
        exit();
    }
}

// include layout after logic
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'config/dbcon.php';
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h2 class="m-0">
                        <i class="fas fa-info-circle mr-2" style="color: #4361ee;"></i>
                        2. Operating Systems and Servers
                    </h2>
                    <p class="text-muted mt-1">Enter operating system and server details</p>
                </div>
                <div class="col-sm-4 text-right">
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
                    <form method="POST" action="save-step2.php" id="osForm">
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
                                window.location.href = 'step2.php?server_id=' + serverId;
                            } else {
                                window.location.href = 'step2.php';
                            }
                        }
                        </script>

                        <script>
                        // toggle visibility of text inputs based on checkbox state
                        function toggleInput(chkId, inputId) {
                            var chk = document.getElementById(chkId);
                            var inp = document.getElementById(inputId);
                            if (chk && inp) {
                                inp.style.display = chk.checked ? 'block' : 'none';
                                if (!chk.checked) inp.value = '';
                            }
                        }
                        document.addEventListener('DOMContentLoaded', function() {
                            ['Windows','Linux','Other'].forEach(function(name) {
                                var chk = document.getElementById('os' + name + 'Chk');
                                var inp = document.getElementById('os' + name + 'Input');
                                if (chk) {
                                    chk.addEventListener('change', function(){ toggleInput('os' + name + 'Chk','os' + name + 'Input'); });
                                }
                            });
                            // show description textbox when a status is selected for each db type
                            ['oracle','mysql','others'].forEach(function(type){
                                var sel = document.querySelector('select[name="db_' + type + '_status"]');
                                var upd = document.getElementById('db' + type.charAt(0).toUpperCase() + type.slice(1) + 'Update');
                                if(sel && upd){
                                    sel.addEventListener('change', function(){
                                        if(this.value !== ''){
                                            upd.style.display = 'block';
                                        } else {
                                            upd.style.display = 'none';
                                            upd.value = '';
                                        }
                                    });
                                    // initialize visibility
                                    if(sel.value !== '') upd.style.display = 'block';
                                }
                            });
                            // password policy toggle: show details block when any status selected
                            var policySel = document.getElementById('passwordPolicyStatus');
                            var policyDetails = document.getElementById('passwordPolicyDetails');
                            if(policySel && policyDetails){
                                policySel.addEventListener('change', function(){
                                    if(this.value !== ''){
                                        policyDetails.style.display = 'block';
                                    } else {
                                        policyDetails.style.display = 'none';
                                        // clear any entered details
                                        policyDetails.querySelectorAll('input,textarea').forEach(function(i){ i.value = ''; });
                                    }
                                });
                                // initialize visibility
                                if(policySel.value !== '') policyDetails.style.display = 'block';
                                else policyDetails.style.display = 'none';
                            }
                        });
                        </script>
                        
                        <?php if ($server_id > 0): ?>
                        <script>
                        // delete server button (reused from step1)
                        var deleteBtn = document.getElementById('deleteServerBtn');
                        if (deleteBtn) {
                            deleteBtn.addEventListener('click', function() {
                                if (confirm('Are you sure you want to delete this server? This action cannot be undone.')) {
                                    window.location.href = 'delete-server.php?server_id=' + <?php echo $server_id; ?>;
                                }
                            });
                        }
                        </script>
                        <?php endif; ?>
                        <?php endif; ?>
                        <!-- Server Block -->
                        <div class="server-block p-4 border rounded">
                            
                            <!-- A. Operating Systems -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">A. Operating Systems:</label>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="osWindowsChk" name="os_windows_chk" <?php echo (!empty($server_data['os_windows'])) ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="osWindowsChk">Windows</label>
                                        <input type="text" class="form-control mt-1" id="osWindowsInput" name="os_windows" 
                                               value="<?php echo htmlspecialchars($server_data['os_windows'] ?? ''); ?>"
                                               placeholder="e.g., Windows Server 2019, Windows 11" <?php echo empty($server_data['os_windows']) ? 'style="display:none;"' : ''; ?>>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="osLinuxChk" name="os_linux_chk" <?php echo (!empty($server_data['os_linux'])) ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="osLinuxChk">Linux</label>
                                        <input type="text" class="form-control mt-1" id="osLinuxInput" name="os_linux" 
                                               value="<?php echo htmlspecialchars($server_data['os_linux'] ?? ''); ?>"
                                               placeholder="e.g., Ubuntu 22.04, RHEL 8" <?php echo empty($server_data['os_linux']) ? 'style="display:none;"' : ''; ?>>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="osOtherChk" name="os_other_chk" <?php echo (!empty($server_data['os_other'])) ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="osOtherChk">Other platform</label>
                                        <input type="text" class="form-control mt-1" id="osOtherInput" name="os_other" 
                                               value="<?php echo htmlspecialchars($server_data['os_other'] ?? ''); ?>"
                                               placeholder="e.g., FreeBSD, AIX" <?php echo empty($server_data['os_other']) ? 'style="display:none;"' : ''; ?>>
                                    </div>
                                </div>
                            </div>

                            <!-- B. Updated OS Patches -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">B. Updated OS Patches:</label>
                                <div class="col-sm-3">
                                    <select class="form-control" id="osPatchesStatus" name="os_patches_status">
                                        <option value="" <?php echo empty($server_data['os_patches_status']) ? 'selected' : ''; ?>>Select Status</option>
                                        <option value="Yes" <?php echo (isset($server_data['os_patches_status']) && $server_data['os_patches_status']==='Yes') ? 'selected' : ''; ?>>Yes - Fully Updated</option>
                                        <option value="No" <?php echo (isset($server_data['os_patches_status']) && $server_data['os_patches_status']==='No') ? 'selected' : ''; ?>>No - Updates Pending</option>
                                        <option value="Partial" <?php echo (isset($server_data['os_patches_status']) && $server_data['os_patches_status']==='Partial') ? 'selected' : ''; ?>>Partially Updated</option>
                                        <option value="N/A" <?php echo (isset($server_data['os_patches_status']) && $server_data['os_patches_status']==='N/A') ? 'selected' : ''; ?>>Not Applicable</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="osPatchesDetails" name="os_patches_details" 
                                           value="<?php echo htmlspecialchars($server_data['os_patches_details'] ?? ''); ?>"
                                           placeholder="Enter patch details or comments">
                                </div>
                            </div>

                            <!-- C. Used Databases -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">C. Used Databases:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="databases" rows="2" 
                                              placeholder="List databases installed&#10;e.g.:&#10;MySQL 8.0&#10;PostgreSQL 14&#10;Oracle 19c"><?php echo htmlspecialchars($server_data['databases'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- D. Database Admin -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">D. Database Admin:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="db_admin" 
                                           value="<?php echo htmlspecialchars($server_data['db_admin'] ?? ''); ?>"
                                           placeholder="e.g., root, postgres, admin, sa">
                                </div>
                            </div>

                            <!-- E. Are the database updated? (with sub-items) -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">E. Are the database updated?</label>
                                <div class="col-sm-3">
                                    <select class="form-control mb-2" name="db_updated_overall" id="dbUpdatedOverall">
                                        <option value="" <?php echo empty($server_data['db_updated_overall']) ? 'selected' : ''; ?>>-- Select --</option>
                                        <option value="Yes" <?php echo (isset($server_data['db_updated_overall']) && $server_data['db_updated_overall']==='Yes') ? 'selected' : ''; ?>>Yes</option>
                                        <option value="No" <?php echo (isset($server_data['db_updated_overall']) && $server_data['db_updated_overall']==='No') ? 'selected' : ''; ?>>No</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="dbUpdatedDetails" name="db_updated_details"
                                           placeholder="Enter update details or reason"
                                           value="<?php echo htmlspecialchars($server_data['db_updated_details'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- F. Services/Frameworks/Other Platforms used -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">F. Services/Frameworks/Other Platforms used:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="services_frameworks" rows="3" 
                                              placeholder="List all services, frameworks, and platforms&#10;e.g.:&#10;Apache 2.4&#10;PHP 8.1&#10;Node.js 18&#10;Docker 24.0&#10;Kubernetes 1.28"><?php echo htmlspecialchars($server_data['services_frameworks'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- G. Designated Computer for Admin purpose -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">G. Designated Computer for Admin purpose:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="admin_computer" 
                                           placeholder="e.g., Admin-WS-01, 192.168.1.100" value="<?php echo htmlspecialchars($server_data['admin_computer'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- H. No. of System Admin/Root Users -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">H. No. of System Admin/Root Users:</label>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" name="admin_count" min="0" value="<?php echo htmlspecialchars($server_data['admin_count'] ?? '1'); ?>">
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="admin_count_reason" 
                                           placeholder="If >1, explain why?" value="<?php echo htmlspecialchars($server_data['admin_count_reason'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- I. No. of Normal Users -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">I. No. of Normal Users:</label>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" name="normal_users_count" min="0" value="<?php echo htmlspecialchars($server_data['normal_users_count'] ?? '0'); ?>">
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="normal_users_details" 
                                           placeholder="Details if any" value="<?php echo htmlspecialchars($server_data['normal_users_details'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- J. Are password policies being followed? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">J. Are password policies being followed?</label>
                                <div class="col-sm-9">
                                    <select class="form-control mb-2" name="password_policy_status" id="passwordPolicyStatus">
                                        <option value="" <?php echo empty($server_data['password_policy_status']) ? 'selected' : ''; ?>>-- Select --</option>
                                        <option value="Yes" <?php echo (isset($server_data['password_policy_status']) && $server_data['password_policy_status']==='Yes') ? 'selected' : ''; ?>>Yes</option>
                                        <option value="No" <?php echo (isset($server_data['password_policy_status']) && $server_data['password_policy_status']==='No') ? 'selected' : ''; ?>>No</option>
                                    </select>
                                    <div id="passwordPolicyDetails" style="display:<?php echo (isset($server_data['password_policy_status']) && $server_data['password_policy_status']!=='') ? 'block' : 'none'; ?>;">
                                        <label class="font-weight-bold">I. Length of Password:</label>
                                        <input type="text" class="form-control mb-2" name="password_length_details" 
                                               placeholder="Current length requirement"
                                               value="<?php echo htmlspecialchars($server_data['password_length_details'] ?? ''); ?>">
                                        <label class="font-weight-bold">II. Combination of Password:</label>
                                        <input type="text" class="form-control mb-2" name="password_combo_details" 
                                               placeholder="e.g., Upper, Lower, Numbers, Special"
                                               value="<?php echo htmlspecialchars($server_data['password_combo_details'] ?? ''); ?>">
                                        <label class="font-weight-bold">Reason:</label>
                                        <textarea class="form-control" name="password_policy_reason" rows="2" placeholder="Explain yes or no"><?php echo htmlspecialchars($server_data['password_policy_reason'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- K. Password Log Book -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">K. Password Log Book:</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="password_logbook_status">
                                        <option value="" <?php echo empty($server_data['password_logbook_status']) ? 'selected' : ''; ?>>Select Status</option>
                                        <option value="Yes" <?php echo (isset($server_data['password_logbook_status']) && $server_data['password_logbook_status']==='Yes') ? 'selected' : ''; ?>>Yes - Maintained</option>
                                        <option value="No" <?php echo (isset($server_data['password_logbook_status']) && $server_data['password_logbook_status']==='No') ? 'selected' : ''; ?>>No - Not Maintained</option>
                                        <option value="N/A" <?php echo (isset($server_data['password_logbook_status']) && $server_data['password_logbook_status']==='N/A') ? 'selected' : ''; ?>>Not Applicable</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="password_logbook_details" 
                                           placeholder="User details, password & password change date" value="<?php echo htmlspecialchars($server_data['password_logbook_details'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- L. Server type -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">L. Server type:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="server_type">
                                        <option value="" <?php echo empty($server_data['server_type']) ? 'selected' : ''; ?>>Select Server Type</option>
                                        <option value="Web Server" <?php echo (isset($server_data['server_type']) && $server_data['server_type']==='Web Server') ? 'selected' : ''; ?>>Web Server</option>
                                        <option value="Mail Server" <?php echo (isset($server_data['server_type']) && $server_data['server_type']==='Mail Server') ? 'selected' : ''; ?>>Mail Server</option>
                                        <option value="File Server" <?php echo (isset($server_data['server_type']) && $server_data['server_type']==='File Server') ? 'selected' : ''; ?>>File Server</option>
                                        <option value="Database Server" <?php echo (isset($server_data['server_type']) && $server_data['server_type']==='Database Server') ? 'selected' : ''; ?>>Database Server</option>
                                        <option value="DNS Server" <?php echo (isset($server_data['server_type']) && $server_data['server_type']==='DNS Server') ? 'selected' : ''; ?>>DNS Server</option>
                                        <option value="Application Server" <?php echo (isset($server_data['server_type']) && $server_data['server_type']==='Application Server') ? 'selected' : ''; ?>>Application Server</option>
                                        <option value="Proxy Server" <?php echo (isset($server_data['server_type']) && $server_data['server_type']==='Proxy Server') ? 'selected' : ''; ?>>Proxy Server</option>
                                        <option value="Load Balancer" <?php echo (isset($server_data['server_type']) && $server_data['server_type']==='Load Balancer') ? 'selected' : ''; ?>>Load Balancer</option>
                                        <option value="Backup Server" <?php echo (isset($server_data['server_type']) && $server_data['server_type']==='Backup Server') ? 'selected' : ''; ?>>Backup Server</option>
                                        <option value="Domain Controller" <?php echo (isset($server_data['server_type']) && $server_data['server_type']==='Domain Controller') ? 'selected' : ''; ?>>Domain Controller</option>
                                        <option value="Other" <?php echo (isset($server_data['server_type']) && $server_data['server_type']==='Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                    <input type="text" class="form-control mt-2" name="server_type_other" 
                                           placeholder="If Other, please specify" value="<?php echo htmlspecialchars($server_data['server_type_other'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- M. Is digital certificate used? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">M. Is digital certificate used?</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="certificate_used">
                                        <option value="" <?php echo empty($server_data['certificate_used']) ? 'selected' : ''; ?>>Select</option>
                                        <option value="Yes" <?php echo (isset($server_data['certificate_used']) && $server_data['certificate_used']==='Yes') ? 'selected' : ''; ?>>Yes</option>
                                        <option value="No" <?php echo (isset($server_data['certificate_used']) && $server_data['certificate_used']==='No') ? 'selected' : ''; ?>>No</option>
                                        <option value="N/A" <?php echo (isset($server_data['certificate_used']) && $server_data['certificate_used']==='N/A') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="certificate_details" 
                                           placeholder="Certificate details if yes" value="<?php echo htmlspecialchars($server_data['certificate_details'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- N. Digital certificate expiry date -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">N. Digital certificate expiry date:</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" name="certificate_expiry" value="<?php echo htmlspecialchars($server_data['certificate_expiry'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- O. Is Antivirus installed? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">O. Is Antivirus installed?</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="antivirus_installed">
                                        <option value="" <?php echo empty($server_data['antivirus_installed']) ? 'selected' : ''; ?>>Select</option>
                                        <option value="Yes" <?php echo (isset($server_data['antivirus_installed']) && $server_data['antivirus_installed']==='Yes') ? 'selected' : ''; ?>>Yes</option>
                                        <option value="No" <?php echo (isset($server_data['antivirus_installed']) && $server_data['antivirus_installed']==='No') ? 'selected' : ''; ?>>No</option>
                                        <option value="N/A" <?php echo (isset($server_data['antivirus_installed']) && $server_data['antivirus_installed']==='N/A') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="antivirus_details" 
                                           placeholder="Antivirus name if yes" value="<?php echo htmlspecialchars($server_data['antivirus_details'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- P. Is Antivirus Updated? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">P. Is Antivirus Updated?</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="antivirus_updated">
                                        <option value="" <?php echo empty($server_data['antivirus_updated']) ? 'selected' : ''; ?>>Select</option>
                                        <option value="Yes" <?php echo (isset($server_data['antivirus_updated']) && $server_data['antivirus_updated']==='Yes') ? 'selected' : ''; ?>>Yes</option>
                                        <option value="No" <?php echo (isset($server_data['antivirus_updated']) && $server_data['antivirus_updated']==='No') ? 'selected' : ''; ?>>No</option>
                                        <option value="Partial" <?php echo (isset($server_data['antivirus_updated']) && $server_data['antivirus_updated']==='Partial') ? 'selected' : ''; ?>>Partial</option>
                                        <option value="N/A" <?php echo (isset($server_data['antivirus_updated']) && $server_data['antivirus_updated']==='N/A') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="antivirus_update_details" 
                                           placeholder="Last update date" value="<?php echo htmlspecialchars($server_data['antivirus_update_details'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- Q. Is SE Linux Enabled? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">Q. Is SE Linux Enabled? (In case of Linux server)</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="selinux_enabled">
                                        <option value="" <?php echo empty($server_data['selinux_enabled']) ? 'selected' : ''; ?>>Select</option>
                                        <option value="Yes" <?php echo (isset($server_data['selinux_enabled']) && $server_data['selinux_enabled']==='Yes') ? 'selected' : ''; ?>>Yes</option>
                                        <option value="No" <?php echo (isset($server_data['selinux_enabled']) && $server_data['selinux_enabled']==='No') ? 'selected' : ''; ?>>No</option>
                                        <option value="N/A" <?php echo (isset($server_data['selinux_enabled']) && $server_data['selinux_enabled']==='N/A') ? 'selected' : ''; ?>>N/A (Not Linux)</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="selinux_details" 
                                           placeholder="If enabled, mode (Enforcing/Permissive)" value="<?php echo htmlspecialchars($server_data['selinux_details'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- R. Is Remote administration to server enabled? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">R. Is Remote administration to server enabled?</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="remote_admin_enabled">
                                        <option value="" <?php echo empty($server_data['remote_admin_enabled']) ? 'selected' : ''; ?>>Select</option>
                                        <option value="Yes" <?php echo (isset($server_data['remote_admin_enabled']) && $server_data['remote_admin_enabled']==='Yes') ? 'selected' : ''; ?>>Yes</option>
                                        <option value="No" <?php echo (isset($server_data['remote_admin_enabled']) && $server_data['remote_admin_enabled']==='No') ? 'selected' : ''; ?>>No</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="remote_admin_details" 
                                           placeholder="I. If Yes explain" value="<?php echo htmlspecialchars($server_data['remote_admin_details'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- S. Is the policy for remote access for server followed? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">S. Is the policy for remote access for server followed?</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="remote_policy_followed">
                                        <option value="" <?php echo empty($server_data['remote_policy_followed']) ? 'selected' : ''; ?>>Select</option>
                                        <option value="Yes" <?php echo (isset($server_data['remote_policy_followed']) && $server_data['remote_policy_followed']==='Yes') ? 'selected' : ''; ?>>Yes</option>
                                        <option value="No" <?php echo (isset($server_data['remote_policy_followed']) && $server_data['remote_policy_followed']==='No') ? 'selected' : ''; ?>>No</option>
                                        <option value="Partial" <?php echo (isset($server_data['remote_policy_followed']) && $server_data['remote_policy_followed']==='Partial') ? 'selected' : ''; ?>>Partial</option>
                                        <option value="N/A" <?php echo (isset($server_data['remote_policy_followed']) && $server_data['remote_policy_followed']==='N/A') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="remote_policy_details" 
                                           placeholder="I. If No explain" value="<?php echo htmlspecialchars($server_data['remote_policy_details'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- Reference (already included in each section) -->
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <button type="reset" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-undo mr-1"></i> Reset Form
                                    </button>
                                    <div>
                                        <button type="button" class="btn btn-info px-4 mr-2" onclick="window.location.href='step1.php'">
                                            <i class="fas fa-arrow-left mr-1"></i> Previous Step
                                        </button>
                                        <?php if ($server_id > 0): ?>
                                        <button type="button" class="btn btn-danger px-4 mr-2" id="deleteServerBtn">
                                            <i class="fas fa-trash-alt mr-1"></i> Delete Server
                                        </button>
                                        <?php endif; ?>
                                        <button type="submit" name="save_step6" class="btn btn-primary px-5">
                                            <i class="fas fa-save mr-2"></i> Save Operating System
                                        </button>
                                        <button type="button" class="btn btn-info px-4 ml-2" onclick="window.location.href='step3.php'">
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
.row {
    margin-left: 0;
    margin-right: 0;
}

.form-group.row {
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px dashed #e9ecef;
}

.form-group.row:last-child {
    border-bottom: none;
}

.col-form-label {
    font-weight: 600;
    color: #1e293b;
}

.server-block {
    background: #ffffff;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef !important;
}

.server-block:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    border-color: #06d6a0 !important;
}

.form-control, .form-select {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.form-control:focus, .form-select:focus {
    border-color: #06d6a0;
    box-shadow: 0 0 0 3px rgba(6, 214, 160, 0.1);
}

textarea.form-control {
    resize: vertical;
}

.btn-success {
    background: #06d6a0;
    border-color: #06d6a0;
}

.btn-success:hover {
    background: #05b586;
    border-color: #05b586;
}

/* Sub-labels styling */
.font-weight-bold {
    font-weight: 600;
    color: #4b5563;
    font-size: 0.9rem;
}

.mb-3 {
    margin-bottom: 1rem;
}

/* Reference text */
.text-primary small {
    font-size: 0.8rem;
}

/* Responsive */
@media (max-width: 768px) {
    .form-group.row {
        margin-bottom: 2rem;
    }
    
    .col-sm-3, .col-sm-9, .col-sm-4, .col-sm-6, .col-sm-7, .col-sm-2 {
        margin-bottom: 0.5rem;
    }
    
    .row > [class*="col-"] {
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