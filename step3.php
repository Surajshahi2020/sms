<?php
// step3-data-security.php
include 'includes/authentication.php';
include 'config/dbcon.php';

// Get logged in user ID from session
$user_id = isset($_SESSION['auth_user']['user_id']) ? intval($_SESSION['auth_user']['user_id']) : 0;

// Simple initialization
$server_id = isset($_GET['server_id']) ? intval($_GET['server_id']) : 0;
$server_data = [];
$data_security = [];

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

// If a specific server is selected, fetch its details (including data security info)
if ($server_id > 0) {
    // Verify server belongs to user
    $check_query = "SELECT id FROM basic_info WHERE id = ? AND user_id = ?";
    $check_stmt = $con->prepare($check_query);
    $check_stmt->bind_param("ii", $server_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    if ($check_result->num_rows == 0) {
        $_SESSION['error'] = "Server not found or you don't have permission to access it";
        header("Location: step3.php");
        exit();
    }
    $check_stmt->close();
    
    // Fetch data security info
    $query = "SELECT * FROM data_security WHERE server_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $server_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $data_security = $row;
    }
    $stmt->close();
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
            <?php include 'message.php'; ?>
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h2 class="m-0">
                        <i class="fas fa-info-circle mr-2" style="color: #4361ee;"></i>
                        3. Data Security
                    </h2>
                    <p class="text-muted mt-1">Data protection and access control configuration</p>
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
                    <form method="POST" action="save-step3.php" id="dataSecurityForm">
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
                                    <option value="">-- Select Server --</option>
                                    <?php foreach ($servers_list as $server): ?>
                                        <option value="<?php echo $server['id']; ?>" <?php echo ($server_id == $server['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($server['server_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($server_id > 0): ?>
                        <!-- Data Security Section -->
                        <div class="security-block p-4 border rounded">
                            
                            <!-- A. Authorization of Data -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    A. Authorization of Data:
                                </label>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" name="auth_data" id="authData" value="yes" <?php echo (isset($data_security['auth_data']) && $data_security['auth_data'] == 'yes') ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="authData">Data authorization implemented</label>
                                    </div>
                                    <textarea class="form-control mt-2" name="auth_data_details" rows="2" 
                                              placeholder="Describe data authorization process and policies"><?php echo isset($data_security['auth_data_details']) ? htmlspecialchars($data_security['auth_data_details']) : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- B. Authentication of user -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    B. Authentication of user:
                                </label>
                                <div class="col-sm-9">
                                    <select class="form-control mb-2" name="auth_method" id="authMethod" onchange="toggleAuthOther()">
                                        <option value="">Select Authentication Method</option>
                                        <option value="Password" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'Password') ? 'selected' : ''; ?>>Password Based</option>
                                        <option value="2FA" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == '2FA') ? 'selected' : ''; ?>>Two Factor Authentication (2FA)</option>
                                        <option value="Biometric" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'Biometric') ? 'selected' : ''; ?>>Biometric</option>
                                        <option value="Certificate" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'Certificate') ? 'selected' : ''; ?>>Certificate Based</option>
                                        <option value="LDAP" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'LDAP') ? 'selected' : ''; ?>>LDAP/Active Directory</option>
                                        <option value="SSO" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'SSO') ? 'selected' : ''; ?>>Single Sign-On (SSO)</option>
                                        <option value="OAuth" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'OAuth') ? 'selected' : ''; ?>>OAuth/OpenID Connect</option>
                                        <option value="MagicLink" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'MagicLink') ? 'selected' : ''; ?>>Magic Link</option>
                                        <option value="APIKey" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'APIKey') ? 'selected' : ''; ?>>API Key</option>
                                        <option value="Token" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'Token') ? 'selected' : ''; ?>>Token-based (JWT)</option>
                                        <option value="SmartCard" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'SmartCard') ? 'selected' : ''; ?>>Smart Card</option>
                                        <option value="HardwareToken" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'HardwareToken') ? 'selected' : ''; ?>>Hardware Token (YubiKey/U2F)</option>
                                        <option value="OTP" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'OTP') ? 'selected' : ''; ?>>One-Time Password (OTP/SMS)</option>
                                        <option value="EmailVerify" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'EmailVerify') ? 'selected' : ''; ?>>Email Verification</option>
                                        <option value="Captcha" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'Captcha') ? 'selected' : ''; ?>>CAPTCHA/Recaptcha</option>
                                        <option value="Multiple" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'Multiple') ? 'selected' : ''; ?>>Multiple Methods</option>
                                        <option value="Other" <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'Other') ? 'selected' : ''; ?>>Other (Please Specify)</option>
                                    </select>
                                    <input type="text" class="form-control mb-2" name="auth_method_other" id="authMethodOther"
                                           placeholder="Specify other authentication method"
                                           value="<?php echo isset($data_security['auth_method_other']) ? htmlspecialchars($data_security['auth_method_other']) : ''; ?>"
                                           style="display: <?php echo (isset($data_security['auth_method']) && $data_security['auth_method'] == 'Other') ? 'block' : 'none'; ?>;">
                                    <textarea class="form-control" name="auth_details" rows="2" 
                                              placeholder="Additional authentication details and policies"><?php echo isset($data_security['auth_details']) ? htmlspecialchars($data_security['auth_details']) : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- C. Data Access control for user -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    C. Data Access control for user:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="access_rbac" id="accessRBAC" value="yes" <?php echo (isset($data_security['access_rbac']) && $data_security['access_rbac'] == 'yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="accessRBAC">Role-Based Access (RBAC)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="access_mac" id="accessMAC" value="yes" <?php echo (isset($data_security['access_mac']) && $data_security['access_mac'] == 'yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="accessMAC">Mandatory Access (MAC)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="access_dac" id="accessDAC" value="yes" <?php echo (isset($data_security['access_dac']) && $data_security['access_dac'] == 'yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="accessDAC">Discretionary Access (DAC)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="access_rule" id="accessRule" value="yes" <?php echo (isset($data_security['access_rule']) && $data_security['access_rule'] == 'yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="accessRule">Rule-Based Access</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="access_other_check" id="accessOtherCheck" value="yes" <?php echo (isset($data_security['access_other_check']) && $data_security['access_other_check'] == 'yes') ? 'checked' : ''; ?> onchange="toggleAccessOther()">
                                                <label class="custom-control-label" for="accessOtherCheck">Other</label>
                                            </div>
                                            <input type="text" class="form-control mt-1" name="access_other" id="accessOther"
                                                   placeholder="Specify other access control method"
                                                   value="<?php echo isset($data_security['access_other']) ? htmlspecialchars($data_security['access_other']) : ''; ?>"
                                                   style="display: <?php echo (isset($data_security['access_other_check']) && $data_security['access_other_check'] == 'yes') ? 'block' : 'none'; ?>;">
                                        </div>
                                    </div>
                                    <textarea class="form-control mt-2" name="access_details" rows="2" 
                                              placeholder="Describe access control implementation"><?php echo isset($data_security['access_details']) ? htmlspecialchars($data_security['access_details']) : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- D. Are user created on the basis of privilege and being followed? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    D. Are user created on the basis of privilege and being followed?
                                </label>
                                <div class="col-sm-9">
                                    <div class="mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="privilege_based" id="privYes" value="yes" <?php echo (isset($data_security['privilege_based']) && $data_security['privilege_based'] == 'yes') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="privYes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="privilege_based" id="privNo" value="no" <?php echo (isset($data_security['privilege_based']) && $data_security['privilege_based'] == 'no') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="privNo">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="privilege_based" id="privPartial" value="partial" <?php echo (isset($data_security['privilege_based']) && $data_security['privilege_based'] == 'partial') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="privPartial">Partially</label>
                                        </div>
                                    </div>
                                    
                                    <!-- I. Explain (SOP) -->
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <label class="font-weight-bold">I. Explain (SOP):</label>
                                        <textarea class="form-control" name="privilege_sop" rows="3" 
                                                  placeholder="Explain Standard Operating Procedure (SOP) for privilege-based user creation"><?php echo isset($data_security['privilege_sop']) ? htmlspecialchars($data_security['privilege_sop']) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- E. Encryption -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    E. Encryption:
                                </label>
                                <div class="col-sm-9">
                                    <select class="form-control mb-2" name="encryption_method" id="encryptionMethod" onchange="toggleEncryptionOther()">
                                        <option value="">Select Encryption Method</option>
                                        <option value="AES-256" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'AES-256') ? 'selected' : ''; ?>>AES-256</option>
                                        <option value="AES-128" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'AES-128') ? 'selected' : ''; ?>>AES-128</option>
                                        <option value="RSA" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'RSA') ? 'selected' : ''; ?>>RSA</option>
                                        <option value="DES" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'DES') ? 'selected' : ''; ?>>DES</option>
                                        <option value="TLS" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'TLS') ? 'selected' : ''; ?>>TLS/SSL</option>
                                        <option value="FullDisk" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'FullDisk') ? 'selected' : ''; ?>>Full Disk Encryption</option>
                                        <option value="Database" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'Database') ? 'selected' : ''; ?>>Database Encryption</option>
                                        <option value="FileLevel" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'FileLevel') ? 'selected' : ''; ?>>File-Level Encryption</option>
                                        <option value="HTTPS" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'HTTPS') ? 'selected' : ''; ?>>HTTPS</option>
                                        <option value="SSH" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'SSH') ? 'selected' : ''; ?>>SSH</option>
                                        <option value="SFTP" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'SFTP') ? 'selected' : ''; ?>>SFTP</option>
                                        <option value="VPN" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'VPN') ? 'selected' : ''; ?>>VPN</option>
                                        <option value="IPSec" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'IPSec') ? 'selected' : ''; ?>>IPSec</option>
                                        <option value="Multiple" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'Multiple') ? 'selected' : ''; ?>>Multiple Encryption Methods</option>
                                        <option value="Other" <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'Other') ? 'selected' : ''; ?>>Other (Please Specify)</option>
                                    </select>
                                    <input type="text" class="form-control mb-2" name="encryption_method_other" id="encryptionMethodOther"
                                           placeholder="Specify other encryption method"
                                           value="<?php echo isset($data_security['encryption_method_other']) ? htmlspecialchars($data_security['encryption_method_other']) : ''; ?>"
                                           style="display: <?php echo (isset($data_security['encryption_method']) && $data_security['encryption_method'] == 'Other') ? 'block' : 'none'; ?>;">
                                    <textarea class="form-control" name="encryption_details" rows="2" 
                                              placeholder="Additional encryption details, key management, and coverage (at rest/in transit)"><?php echo isset($data_security['encryption_details']) ? htmlspecialchars($data_security['encryption_details']) : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- F. Off hour administration and management -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    F. Off hour administration and management:
                                </label>
                                <div class="col-sm-9">
                                    
                                    <!-- I. Duty or Not -->
                                    <div class="mb-3">
                                        <label class="font-weight-bold">I. Duty or Not:</label>
                                        <select class="form-control mb-2" name="offhour_duty" id="offhourDuty" onchange="toggleOffhourOther()">
                                            <option value="">Select Duty Type</option>
                                            <option value="DutyRoster" <?php echo (isset($data_security['offhour_duty']) && $data_security['offhour_duty'] == 'DutyRoster') ? 'selected' : ''; ?>>Duty Roster Exists</option>
                                            <option value="NoDutyRoster" <?php echo (isset($data_security['offhour_duty']) && $data_security['offhour_duty'] == 'NoDutyRoster') ? 'selected' : ''; ?>>No Duty Roster</option>
                                            <option value="OnCall" <?php echo (isset($data_security['offhour_duty']) && $data_security['offhour_duty'] == 'OnCall') ? 'selected' : ''; ?>>On-Call Basis</option>
                                            <option value="Rotational" <?php echo (isset($data_security['offhour_duty']) && $data_security['offhour_duty'] == 'Rotational') ? 'selected' : ''; ?>>Rotational Shift</option>
                                            <option value="Hybrid" <?php echo (isset($data_security['offhour_duty']) && $data_security['offhour_duty'] == 'Hybrid') ? 'selected' : ''; ?>>Hybrid (Duty + On-Call)</option>
                                            <option value="Other" <?php echo (isset($data_security['offhour_duty']) && $data_security['offhour_duty'] == 'Other') ? 'selected' : ''; ?>>Other (Please Specify)</option>
                                        </select>
                                        <input type="text" class="form-control mb-2" name="offhour_duty_other" id="offhourDutyOther"
                                               placeholder="Specify other duty type"
                                               value="<?php echo isset($data_security['offhour_duty_other']) ? htmlspecialchars($data_security['offhour_duty_other']) : ''; ?>"
                                               style="display: <?php echo (isset($data_security['offhour_duty']) && $data_security['offhour_duty'] == 'Other') ? 'block' : 'none'; ?>;">
                                    </div>

                                    <!-- II. Duty Sop -->
                                    <div class="mb-3">
                                        <label class="font-weight-bold">II. Duty SOP:</label>
                                        <textarea class="form-control" name="duty_sop" rows="3" 
                                                  placeholder="Describe Duty Standard Operating Procedure"><?php echo isset($data_security['duty_sop']) ? htmlspecialchars($data_security['duty_sop']) : ''; ?></textarea>
                                    </div>

                                    <!-- III. Off hour server administration -->
                                    <div class="mb-2">
                                        <label class="font-weight-bold">III. Off hour server administration:</label>
                                        <select class="form-control mb-2" name="offhour_admin" id="offhourAdmin" onchange="toggleOffhourAdminOther()">
                                            <option value="">Select Administration Type</option>
                                            <option value="ScheduledMaintenance" <?php echo (isset($data_security['offhour_admin']) && $data_security['offhour_admin'] == 'ScheduledMaintenance') ? 'selected' : ''; ?>>Scheduled Maintenance</option>
                                            <option value="EmergencyAccess" <?php echo (isset($data_security['offhour_admin']) && $data_security['offhour_admin'] == 'EmergencyAccess') ? 'selected' : ''; ?>>Emergency Access</option>
                                            <option value="ActivityLogging" <?php echo (isset($data_security['offhour_admin']) && $data_security['offhour_admin'] == 'ActivityLogging') ? 'selected' : ''; ?>>Activity Logging</option>
                                            <option value="PriorApproval" <?php echo (isset($data_security['offhour_admin']) && $data_security['offhour_admin'] == 'PriorApproval') ? 'selected' : ''; ?>>Prior Approval Required</option>
                                            <option value="All" <?php echo (isset($data_security['offhour_admin']) && $data_security['offhour_admin'] == 'All') ? 'selected' : ''; ?>>All of the above</option>
                                            <option value="Multiple" <?php echo (isset($data_security['offhour_admin']) && $data_security['offhour_admin'] == 'Multiple') ? 'selected' : ''; ?>>Multiple Methods</option>
                                            <option value="Other" <?php echo (isset($data_security['offhour_admin']) && $data_security['offhour_admin'] == 'Other') ? 'selected' : ''; ?>>Other (Please Specify)</option>
                                        </select>
                                        <input type="text" class="form-control mb-2" name="offhour_admin_other" id="offhourAdminOther"
                                               placeholder="Specify other administration method"
                                               value="<?php echo isset($data_security['offhour_admin_other']) ? htmlspecialchars($data_security['offhour_admin_other']) : ''; ?>"
                                               style="display: <?php echo (isset($data_security['offhour_admin']) && $data_security['offhour_admin'] == 'Other') ? 'block' : 'none'; ?>;">
                                        <textarea class="form-control mt-2" name="offhour_details" rows="2" 
                                                  placeholder="Additional off-hour administration details"><?php echo isset($data_security['offhour_details']) ? htmlspecialchars($data_security['offhour_details']) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label font-weight-bold text-primary">Remarks:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="remarks" rows="2" 
                                              placeholder="Any additional remarks or notes for data security"><?php echo isset($data_security['remarks']) ? htmlspecialchars($data_security['remarks']) : ''; ?></textarea>
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
                                        <button type="button" class="btn btn-info px-4 mr-2" onclick="window.location.href='step2.php'">
                                            <i class="fas fa-arrow-left mr-1"></i> Previous Step
                                        </button>
                                        <button type="submit" name="save_step3" class="btn btn-primary px-5">
                                            <i class="fas fa-save mr-2"></i> Save Data Security
                                        </button>
                                        <button type="submit" name="save_and_next" class="btn btn-success px-4 ml-2">
                                            Save & Next <i class="fas fa-arrow-right ml-1"></i>
                                        </button>
                                        <button type="button" class="btn btn-info px-4 ml-2" onclick="window.location.href='step4.php'">
                                            Next Step <i class="fas fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function loadServer(serverId) {
    if (serverId) {
        window.location.href = 'step3.php?server_id=' + serverId;
    } else {
        window.location.href = 'step3.php';
    }
}

function toggleEncryptionOther() {
    var sel = document.getElementById('encryptionMethod');
    var other = document.getElementById('encryptionMethodOther');
    if (sel && other) {
        other.style.display = (sel.value === 'Other') ? 'block' : 'none';
        if (sel.value !== 'Other') other.value = '';
    }
}
function toggleAuthOther() {
    var sel = document.getElementById('authMethod');
    var other = document.getElementById('authMethodOther');
    if (sel && other) {
        other.style.display = (sel.value === 'Other') ? 'block' : 'none';
        if (sel.value !== 'Other') other.value = '';
    }
}
function toggleAccessOther() {
    var check = document.getElementById('accessOtherCheck');
    var other = document.getElementById('accessOther');
    if (check && other) {
        other.style.display = check.checked ? 'block' : 'none';
        if (!check.checked) other.value = '';
    }
}
function toggleOffhourOther() {
    var sel = document.getElementById('offhourDuty');
    var other = document.getElementById('offhourDutyOther');
    if (sel && other) {
        other.style.display = (sel.value === 'Other') ? 'block' : 'none';
        if (sel.value !== 'Other') other.value = '';
    }
}
function toggleOffhourAdminOther() {
    var sel = document.getElementById('offhourAdmin');
    var other = document.getElementById('offhourAdminOther');
    if (sel && other) {
        other.style.display = (sel.value === 'Other') ? 'block' : 'none';
        if (sel.value !== 'Other') other.value = '';
    }
}
</script>

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
