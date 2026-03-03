<?php
// step8-server-administration.php
include 'includes/authentication.php';
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'config/dbcon.php';

// Fetch existing data if editing
$server_id = isset($_GET['server_id']) ? $_GET['server_id'] : 0;
$admin_data = [];
if ($server_id) {
    $query = "SELECT * FROM server_administration WHERE server_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $server_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin_data = $result->fetch_assoc();
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
                        8. Server Administration and Management
                    </h2>
                    <p class="text-muted mt-1">Server access, monitoring, maintenance, and administrative controls</p>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="server-checklist.php" class="btn btn-danger">
                        Back
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
                    <form method="POST" action="save-step8.php" id="serverAdminForm">
                        <input type="hidden" name="server_id" value="<?php echo $server_id; ?>">
                        
                        <!-- Server Administration Section -->
                        <div class="security-block p-4 border rounded">
                            
                            <!-- A. Server Access Control -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    A. Server Access Control:
                                </label>
                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="access_control_status" id="accessCtrlYes" value="Yes" <?php echo (isset($admin_data['access_control_status']) && $admin_data['access_control_status'] == 'Yes') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="accessCtrlYes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="access_control_status" id="accessCtrlNo" value="No" <?php echo (isset($admin_data['access_control_status']) && $admin_data['access_control_status'] == 'No') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="accessCtrlNo">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="access_control_status" id="accessCtrlPartial" value="Partial" <?php echo (isset($admin_data['access_control_status']) && $admin_data['access_control_status'] == 'Partial') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="accessCtrlPartial">Partially</label>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3 bg-light rounded">
                                        <label class="font-weight-bold">Access Methods:</label>
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="access_ssh" id="accessSSH" value="SSH" <?php echo (isset($admin_data['access_methods']) && strpos($admin_data['access_methods'], 'SSH') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="accessSSH">SSH (Secure Shell)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="access_rdp" id="accessRDP" value="RDP" <?php echo (isset($admin_data['access_methods']) && strpos($admin_data['access_methods'], 'RDP') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="accessRDP">RDP (Remote Desktop)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="access_console" id="accessConsole" value="Console" <?php echo (isset($admin_data['access_methods']) && strpos($admin_data['access_methods'], 'Console') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="accessConsole">Local Console</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="access_web" id="accessWeb" value="Web Interface" <?php echo (isset($admin_data['access_methods']) && strpos($admin_data['access_methods'], 'Web Interface') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="accessWeb">Web-Based Management</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="access_api" id="accessAPI" value="API" <?php echo (isset($admin_data['access_methods']) && strpos($admin_data['access_methods'], 'API') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="accessAPI">API Access</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label>Access Restriction:</label>
                                                <select class="form-control" name="access_restriction">
                                                    <option value="">Select Restriction Type</option>
                                                    <option value="IP Based" <?php echo (isset($admin_data['access_restriction']) && $admin_data['access_restriction'] == 'IP Based') ? 'selected' : ''; ?>>IP-Based Restriction</option>
                                                    <option value="VPN Required" <?php echo (isset($admin_data['access_restriction']) && $admin_data['access_restriction'] == 'VPN Required') ? 'selected' : ''; ?>>VPN Required</option>
                                                    <option value="Jump Server" <?php echo (isset($admin_data['access_restriction']) && $admin_data['access_restriction'] == 'Jump Server') ? 'selected' : ''; ?>>Jump Server/Bastion Host</option>
                                                    <option value="Multi-Factor" <?php echo (isset($admin_data['access_restriction']) && $admin_data['access_restriction'] == 'Multi-Factor') ? 'selected' : ''; ?>>Multi-Factor Authentication</option>
                                                    <option value="None" <?php echo (isset($admin_data['access_restriction']) && $admin_data['access_restriction'] == 'None') ? 'selected' : ''; ?>>No Restriction</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Access Schedule:</label>
                                                <input type="text" class="form-control" name="access_schedule" value="<?php echo htmlspecialchars($admin_data['access_schedule'] ?? ''); ?>" placeholder="e.g., 24x7, Business Hours Only">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- B. User Account Management -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    B. User Account Management:
                                </label>
                                <div class="col-sm-9">
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Total Admin Accounts:</label>
                                                <input type="number" class="form-control" name="total_admin_accounts" value="<?php echo htmlspecialchars($admin_data['total_admin_accounts'] ?? ''); ?>" placeholder="Number of admin accounts">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Total Service Accounts:</label>
                                                <input type="number" class="form-control" name="total_service_accounts" value="<?php echo htmlspecialchars($admin_data['total_service_accounts'] ?? ''); ?>" placeholder="Number of service accounts">
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="account_password_policy" id="accountPasswordPolicy" value="Yes" <?php echo (isset($admin_data['account_password_policy']) && $admin_data['account_password_policy'] == 'Yes') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="accountPasswordPolicy">Password Policy Enforced</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="account_lockout" id="accountLockout" value="Yes" <?php echo (isset($admin_data['account_lockout']) && $admin_data['account_lockout'] == 'Yes') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="accountLockout">Account Lockout Policy</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="password_expiry" value="<?php echo htmlspecialchars($admin_data['password_expiry'] ?? ''); ?>" placeholder="Password expiry (e.g., 90 days)">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="inactive_account_cleanup" value="<?php echo htmlspecialchars($admin_data['inactive_account_cleanup'] ?? ''); ?>" placeholder="Inactive account cleanup">
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="account_approval_required">
                                                    <option value="">Account Approval</option>
                                                    <option value="Yes" <?php echo (isset($admin_data['account_approval_required']) && $admin_data['account_approval_required'] == 'Yes') ? 'selected' : ''; ?>>Approval Required</option>
                                                    <option value="No" <?php echo (isset($admin_data['account_approval_required']) && $admin_data['account_approval_required'] == 'No') ? 'selected' : ''; ?>>No Approval Needed</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- C. Privilege Management -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    C. Privilege Management:
                                </label>
                                <div class="col-sm-9">
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="privilege_model" id="privRBAC" value="RBAC" <?php echo (isset($admin_data['privilege_model']) && $admin_data['privilege_model'] == 'RBAC') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="privRBAC">Role-Based Access Control (RBAC)</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="privilege_model" id="privMAC" value="MAC" <?php echo (isset($admin_data['privilege_model']) && $admin_data['privilege_model'] == 'MAC') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="privMAC">Mandatory Access Control (MAC)</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="privilege_model" id="privDAC" value="DAC" <?php echo (isset($admin_data['privilege_model']) && $admin_data['privilege_model'] == 'DAC') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="privDAC">Discretionary Access Control (DAC)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="sudo_controlled" id="sudoControlled" value="Yes" <?php echo (isset($admin_data['sudo_controlled']) && $admin_data['sudo_controlled'] == 'Yes') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="sudoControlled">Sudo/Superuser Access Controlled</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="privilege_separation" id="privilegeSeparation" value="Yes" <?php echo (isset($admin_data['privilege_separation']) && $admin_data['privilege_separation'] == 'Yes') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="privilegeSeparation">Duty Separation Enforced</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="least_privilege" id="leastPrivilege" value="Yes" <?php echo (isset($admin_data['least_privilege']) && $admin_data['least_privilege'] == 'Yes') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="leastPrivilege">Principle of Least Privilege</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <textarea class="form-control" name="privilege_details" rows="2" 
                                                          placeholder="Privilege management details and procedures..."><?php echo htmlspecialchars($admin_data['privilege_details'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- D. Server Monitoring -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    D. Server Monitoring:
                                </label>
                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="monitoring_status" id="monitorYes" value="Yes" <?php echo (isset($admin_data['monitoring_status']) && $admin_data['monitoring_status'] == 'Yes') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="monitorYes">Yes - 24x7 Monitoring</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="monitoring_status" id="monitorBusiness" value="Business Hours" <?php echo (isset($admin_data['monitoring_status']) && $admin_data['monitoring_status'] == 'Business Hours') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="monitorBusiness">Business Hours Only</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="monitoring_status" id="monitorNo" value="No" <?php echo (isset($admin_data['monitoring_status']) && $admin_data['monitoring_status'] == 'No') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="monitorNo">No Monitoring</label>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Monitoring Tools:</label>
                                                <input type="text" class="form-control" name="monitoring_tools" value="<?php echo htmlspecialchars($admin_data['monitoring_tools'] ?? ''); ?>" placeholder="e.g., Nagios, Zabbix, Prometheus">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Alerting Method:</label>
                                                <select class="form-control" name="alerting_method">
                                                    <option value="">Select Alert Method</option>
                                                    <option value="Email" <?php echo (isset($admin_data['alerting_method']) && $admin_data['alerting_method'] == 'Email') ? 'selected' : ''; ?>>Email</option>
                                                    <option value="SMS" <?php echo (isset($admin_data['alerting_method']) && $admin_data['alerting_method'] == 'SMS') ? 'selected' : ''; ?>>SMS</option>
                                                    <option value="Slack/Teams" <?php echo (isset($admin_data['alerting_method']) && $admin_data['alerting_method'] == 'Slack/Teams') ? 'selected' : ''; ?>>Slack/Microsoft Teams</option>
                                                    <option value="Phone Call" <?php echo (isset($admin_data['alerting_method']) && $admin_data['alerting_method'] == 'Phone Call') ? 'selected' : ''; ?>>Phone Call</option>
                                                    <option value="Multiple" <?php echo (isset($admin_data['alerting_method']) && $admin_data['alerting_method'] == 'Multiple') ? 'selected' : ''; ?>>Multiple Methods</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="monitor_cpu" id="monitorCPU" value="CPU" <?php echo (isset($admin_data['monitoring_metrics']) && strpos($admin_data['monitoring_metrics'], 'CPU') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="monitorCPU">CPU Usage</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="monitor_memory" id="monitorMemory" value="Memory" <?php echo (isset($admin_data['monitoring_metrics']) && strpos($admin_data['monitoring_metrics'], 'Memory') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="monitorMemory">Memory Usage</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="monitor_disk" id="monitorDisk" value="Disk" <?php echo (isset($admin_data['monitoring_metrics']) && strpos($admin_data['monitoring_metrics'], 'Disk') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="monitorDisk">Disk Usage</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="monitor_network" id="monitorNetwork" value="Network" <?php echo (isset($admin_data['monitoring_metrics']) && strpos($admin_data['monitoring_metrics'], 'Network') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="monitorNetwork">Network Traffic</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="monitor_service" id="monitorService" value="Services" <?php echo (isset($admin_data['monitoring_metrics']) && strpos($admin_data['monitoring_metrics'], 'Services') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="monitorService">Service/Process Health</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="monitor_logs" id="monitorLogs" value="Logs" <?php echo (isset($admin_data['monitoring_metrics']) && strpos($admin_data['monitoring_metrics'], 'Logs') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="monitorLogs">Log Monitoring</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- E. Maintenance and Patching -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    E. Maintenance and Patching:
                                </label>
                                <div class="col-sm-9">
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>OS Patch Frequency:</label>
                                                <select class="form-control" name="os_patch_frequency">
                                                    <option value="">Select Frequency</option>
                                                    <option value="Daily" <?php echo (isset($admin_data['os_patch_frequency']) && $admin_data['os_patch_frequency'] == 'Daily') ? 'selected' : ''; ?>>Daily</option>
                                                    <option value="Weekly" <?php echo (isset($admin_data['os_patch_frequency']) && $admin_data['os_patch_frequency'] == 'Weekly') ? 'selected' : ''; ?>>Weekly</option>
                                                    <option value="Monthly" <?php echo (isset($admin_data['os_patch_frequency']) && $admin_data['os_patch_frequency'] == 'Monthly') ? 'selected' : ''; ?>>Monthly</option>
                                                    <option value="Quarterly" <?php echo (isset($admin_data['os_patch_frequency']) && $admin_data['os_patch_frequency'] == 'Quarterly') ? 'selected' : ''; ?>>Quarterly</option>
                                                    <option value="As Needed" <?php echo (isset($admin_data['os_patch_frequency']) && $admin_data['os_patch_frequency'] == 'As Needed') ? 'selected' : ''; ?>>As Needed</option>
                                                    <option value="Never" <?php echo (isset($admin_data['os_patch_frequency']) && $admin_data['os_patch_frequency'] == 'Never') ? 'selected' : ''; ?>>Never</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Application Patch Frequency:</label>
                                                <select class="form-control" name="app_patch_frequency">
                                                    <option value="">Select Frequency</option>
                                                    <option value="Daily" <?php echo (isset($admin_data['app_patch_frequency']) && $admin_data['app_patch_frequency'] == 'Daily') ? 'selected' : ''; ?>>Daily</option>
                                                    <option value="Weekly" <?php echo (isset($admin_data['app_patch_frequency']) && $admin_data['app_patch_frequency'] == 'Weekly') ? 'selected' : ''; ?>>Weekly</option>
                                                    <option value="Monthly" <?php echo (isset($admin_data['app_patch_frequency']) && $admin_data['app_patch_frequency'] == 'Monthly') ? 'selected' : ''; ?>>Monthly</option>
                                                    <option value="Quarterly" <?php echo (isset($admin_data['app_patch_frequency']) && $admin_data['app_patch_frequency'] == 'Quarterly') ? 'selected' : ''; ?>>Quarterly</option>
                                                    <option value="As Needed" <?php echo (isset($admin_data['app_patch_frequency']) && $admin_data['app_patch_frequency'] == 'As Needed') ? 'selected' : ''; ?>>As Needed</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Last Patch Date:</label>
                                                <input type="date" class="form-control" name="last_patch_date" value="<?php echo htmlspecialchars($admin_data['last_patch_date'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="patch_automated" id="patchAutomated" value="Yes" <?php echo (isset($admin_data['patch_automated']) && $admin_data['patch_automated'] == 'Yes') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="patchAutomated">Automated Patching</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="patch_testing" id="patchTesting" value="Yes" <?php echo (isset($admin_data['patch_testing']) && $admin_data['patch_testing'] == 'Yes') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="patchTesting">Patches Tested Before Deployment</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="maintenance_window" id="maintenanceWindow" value="Yes" <?php echo (isset($admin_data['maintenance_window']) && $admin_data['maintenance_window'] == 'Yes') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="maintenanceWindow">Scheduled Maintenance Window</label>
                                                </div>
                                                <input type="text" class="form-control mt-2" name="maintenance_window_details" value="<?php echo htmlspecialchars($admin_data['maintenance_window_details'] ?? ''); ?>" placeholder="e.g., Sunday 2 AM - 4 AM">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- F. Change Management -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    F. Change Management:
                                </label>
                                <div class="col-sm-9">
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="change_mgmt_process" id="changeFormal" value="Formal" <?php echo (isset($admin_data['change_mgmt_process']) && $admin_data['change_mgmt_process'] == 'Formal') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="changeFormal">Formal Change Management</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="change_mgmt_process" id="changeInformal" value="Informal" <?php echo (isset($admin_data['change_mgmt_process']) && $admin_data['change_mgmt_process'] == 'Informal') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="changeInformal">Informal/Ad-hoc</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="change_mgmt_process" id="changeNone" value="None" <?php echo (isset($admin_data['change_mgmt_process']) && $admin_data['change_mgmt_process'] == 'None') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="changeNone">No Process</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Change Approval:</label>
                                                <select class="form-control" name="change_approval">
                                                    <option value="">Select Level</option>
                                                    <option value="Manager" <?php echo (isset($admin_data['change_approval']) && $admin_data['change_approval'] == 'Manager') ? 'selected' : ''; ?>>Manager Approval</option>
                                                    <option value="CAB" <?php echo (isset($admin_data['change_approval']) && $admin_data['change_approval'] == 'CAB') ? 'selected' : ''; ?>>Change Advisory Board (CAB)</option>
                                                    <option value="Peer" <?php echo (isset($admin_data['change_approval']) && $admin_data['change_approval'] == 'Peer') ? 'selected' : ''; ?>>Peer Review</option>
                                                    <option value="None" <?php echo (isset($admin_data['change_approval']) && $admin_data['change_approval'] == 'None') ? 'selected' : ''; ?>>No Approval</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <label>Change Documentation:</label>
                                                <textarea class="form-control" name="change_documentation" rows="2" 
                                                          placeholder="How are changes documented? (e.g., Ticket system, Change log, Wiki)"><?php echo htmlspecialchars($admin_data['change_documentation'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- G. Audit Logging -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    G. Audit Logging:
                                </label>
                                <div class="col-sm-9">
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="audit_login" id="auditLogin" value="Login" <?php echo (isset($admin_data['audit_events']) && strpos($admin_data['audit_events'], 'Login') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="auditLogin">Login/Logout Events</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="audit_command" id="auditCommand" value="Commands" <?php echo (isset($admin_data['audit_events']) && strpos($admin_data['audit_events'], 'Commands') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="auditCommand">Command Execution</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="audit_config" id="auditConfig" value="Config" <?php echo (isset($admin_data['audit_events']) && strpos($admin_data['audit_events'], 'Config') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="auditConfig">Configuration Changes</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="audit_file" id="auditFile" value="File" <?php echo (isset($admin_data['audit_events']) && strpos($admin_data['audit_events'], 'File') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="auditFile">File Access/Modifications</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="audit_privilege" id="auditPrivilege" value="Privilege" <?php echo (isset($admin_data['audit_events']) && strpos($admin_data['audit_events'], 'Privilege') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="auditPrivilege">Privilege Escalation</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label>Log Retention Period:</label>
                                                <input type="text" class="form-control" name="audit_retention" value="<?php echo htmlspecialchars($admin_data['audit_retention'] ?? ''); ?>" placeholder="e.g., 1 year">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Log Review Frequency:</label>
                                                <select class="form-control" name="audit_review_frequency">
                                                    <option value="">Select Frequency</option>
                                                    <option value="Daily" <?php echo (isset($admin_data['audit_review_frequency']) && $admin_data['audit_review_frequency'] == 'Daily') ? 'selected' : ''; ?>>Daily</option>
                                                    <option value="Weekly" <?php echo (isset($admin_data['audit_review_frequency']) && $admin_data['audit_review_frequency'] == 'Weekly') ? 'selected' : ''; ?>>Weekly</option>
                                                    <option value="Monthly" <?php echo (isset($admin_data['audit_review_frequency']) && $admin_data['audit_review_frequency'] == 'Monthly') ? 'selected' : ''; ?>>Monthly</option>
                                                    <option value="Quarterly" <?php echo (isset($admin_data['audit_review_frequency']) && $admin_data['audit_review_frequency'] == 'Quarterly') ? 'selected' : ''; ?>>Quarterly</option>
                                                    <option value="Not Reviewed" <?php echo (isset($admin_data['audit_review_frequency']) && $admin_data['audit_review_frequency'] == 'Not Reviewed') ? 'selected' : ''; ?>>Not Regularly Reviewed</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- H. Session Management -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    H. Session Management:
                                </label>
                                <div class="col-sm-9">
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Session Timeout:</label>
                                                <input type="text" class="form-control" name="session_timeout" value="<?php echo htmlspecialchars($admin_data['session_timeout'] ?? ''); ?>" placeholder="e.g., 15 minutes">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Max Concurrent Sessions:</label>
                                                <input type="number" class="form-control" name="max_concurrent_sessions" value="<?php echo htmlspecialchars($admin_data['max_concurrent_sessions'] ?? ''); ?>" placeholder="e.g., 2">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox mt-4">
                                                    <input type="checkbox" class="custom-control-input" name="session_logging" id="sessionLogging" value="Yes" <?php echo (isset($admin_data['session_logging']) && $admin_data['session_logging'] == 'Yes') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="sessionLogging">Session Logging Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- I. Backup Administration -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    I. Backup Administration:
                                </label>
                                <div class="col-sm-9">
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Backup Schedule Managed By:</label>
                                                <input type="text" class="form-control" name="backup_admin" value="<?php echo htmlspecialchars($admin_data['backup_admin'] ?? ''); ?>" placeholder="e.g., System Admin, Backup Team">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Backup Monitoring:</label>
                                                <select class="form-control" name="backup_monitoring">
                                                    <option value="">Select Level</option>
                                                    <option value="Automated" <?php echo (isset($admin_data['backup_monitoring']) && $admin_data['backup_monitoring'] == 'Automated') ? 'selected' : ''; ?>>Automated Alerts</option>
                                                    <option value="Manual" <?php echo (isset($admin_data['backup_monitoring']) && $admin_data['backup_monitoring'] == 'Manual') ? 'selected' : ''; ?>>Manual Checks</option>
                                                    <option value="Both" <?php echo (isset($admin_data['backup_monitoring']) && $admin_data['backup_monitoring'] == 'Both') ? 'selected' : ''; ?>>Both Automated & Manual</option>
                                                    <option value="None" <?php echo (isset($admin_data['backup_monitoring']) && $admin_data['backup_monitoring'] == 'None') ? 'selected' : ''; ?>>No Monitoring</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- J. Server Documentation -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    J. Server Documentation:
                                </label>
                                <div class="col-sm-9">
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="doc_config" id="docConfig" value="Config" <?php echo (isset($admin_data['documentation']) && strpos($admin_data['documentation'], 'Config') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="docConfig">Configuration Documentation</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="doc_network" id="docNetwork" value="Network" <?php echo (isset($admin_data['documentation']) && strpos($admin_data['documentation'], 'Network') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="docNetwork">Network Diagram</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="doc_procedures" id="docProcedures" value="Procedures" <?php echo (isset($admin_data['documentation']) && strpos($admin_data['documentation'], 'Procedures') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="docProcedures">Operational Procedures</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="doc_contact" id="docContact" value="Contact" <?php echo (isset($admin_data['documentation']) && strpos($admin_data['documentation'], 'Contact') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="docContact">Contact/Escalation List</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="doc_location" value="<?php echo htmlspecialchars($admin_data['doc_location'] ?? ''); ?>" placeholder="Documentation location (e.g., Wiki, Sharepoint)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- K. Incident Response - Administrative -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    K. Incident Response (Admin):
                                </label>
                                <div class="col-sm-9">
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Escalation Contact:</label>
                                                <input type="text" class="form-control" name="incident_contact" value="<?php echo htmlspecialchars($admin_data['incident_contact'] ?? ''); ?>" placeholder="Primary contact for incidents">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Response Time:</label>
                                                <input type="text" class="form-control" name="incident_response_time" value="<?php echo htmlspecialchars($admin_data['incident_response_time'] ?? ''); ?>" placeholder="e.g., 15 minutes">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <textarea class="form-control" name="incident_procedure" rows="2" 
                                                          placeholder="Brief description of incident response procedure..."><?php echo htmlspecialchars($admin_data['incident_procedure'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Administration Summary -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    Administration Summary:
                                </label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="admin_summary" rows="4" 
                                              placeholder="Overall server administration assessment, strengths, and areas for improvement..."><?php echo htmlspecialchars($admin_data['admin_summary'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label font-weight-bold text-primary">Remarks:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="remarks" rows="3" 
                                              placeholder="Any additional remarks or notes for server administration"><?php echo htmlspecialchars($admin_data['remarks'] ?? ''); ?></textarea>
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
                                        <button type="button" class="btn btn-info px-4 mr-2" onclick="window.location.href='step7.php'">
                                            <i class="fas fa-arrow-left mr-1"></i> Previous Step
                                        </button>
                                        <button type="submit" name="save_step8" class="btn btn-primary px-5">
                                            <i class="fas fa-save mr-2"></i> Save Administration
                                        </button>
                                        <button type="button" class="btn btn-info px-4 ml-2" onclick="window.location.href='index.php'">
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
    padding-bottom: 1.5rem;
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