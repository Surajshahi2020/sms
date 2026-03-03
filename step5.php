<?php
// step10-policy-sop.php
include 'includes/authentication.php';
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'config/dbcon.php';

// Fetch existing data if editing
$server_id = isset($_GET['server_id']) ? $_GET['server_id'] : 0;
$policy_data = [];
if ($server_id) {
    $query = "SELECT * FROM policy_sop WHERE server_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $server_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $policy_data = $result->fetch_assoc();
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
                        5. Policy, SOP and Practices
                    </h2>
                    <p class="text-muted mt-1">Organizational policies, procedures and practices</p>
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
                    <form method="POST" action="save-step10.php" id="policySOPForm">
                        <input type="hidden" name="server_id" value="<?php echo $server_id; ?>">
                        
                        <!-- Policy, SOP and Practices Section -->
                        <div class="security-block p-4 border rounded">
                            
                            <!-- A. Does the organization have own documented policies/SOP/Practices apart from NA cyber security policy 2075? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    A. Does the organization have own documented policies/SOP/Practices apart from NA cyber security policy 2075?
                                </label>
                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="has_own_policies" id="policyYes" value="Yes" <?php echo (isset($policy_data['has_own_policies']) && $policy_data['has_own_policies'] == 'Yes') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="policyYes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="has_own_policies" id="policyNo" value="No" <?php echo (isset($policy_data['has_own_policies']) && $policy_data['has_own_policies'] == 'No') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="policyNo">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="has_own_policies" id="policyPartial" value="Partial" <?php echo (isset($policy_data['has_own_policies']) && $policy_data['has_own_policies'] == 'Partial') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="policyPartial">Partially</label>
                                        </div>
                                    </div>
                                    
                                    <!-- Policy Details -->
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <label class="font-weight-bold">Policy Details:</label>
                                        <textarea class="form-control" name="policy_details" rows="3" 
                                                  placeholder="Describe organizational policies, SOPs and practices..."><?php echo htmlspecialchars($policy_data['policy_details'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- B. List out all internal Policy and SOP -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    B. List out all internal Policy and SOP<br>
                                    <small class="text-muted">(Eg. Server documentation, server operation sop, change management, Access log management)</small>
                                </label>
                                <div class="col-sm-9">
                                    
                                    <!-- I. Incidents Response plan -->
                                    <div class="mb-4 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">I. Incidents Response plan:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="incident_response_status" id="incidentYes" value="Yes" <?php echo (isset($policy_data['incident_response_status']) && $policy_data['incident_response_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="incidentYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="incident_response_status" id="incidentNo" value="No" <?php echo (isset($policy_data['incident_response_status']) && $policy_data['incident_response_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="incidentNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="incident_response_status" id="incidentPartial" value="Partial" <?php echo (isset($policy_data['incident_response_status']) && $policy_data['incident_response_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="incidentPartial">Partial</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="font-weight-bold">Reference:</label>
                                                <div class="text-primary">
                                                    <small>साइबर सुरक्षा नीति<br>पेज नं. ५ बुँदा २०</small>
                                                </div>
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="incident_response_detail" rows="2" 
                                                  placeholder="Incident Response Plan details..."><?php echo htmlspecialchars($policy_data['incident_response_detail'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- II. Server Documentation -->
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">II. Server Documentation:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="server_doc_status" id="serverDocYes" value="Yes" <?php echo (isset($policy_data['server_doc_status']) && $policy_data['server_doc_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="serverDocYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="server_doc_status" id="serverDocNo" value="No" <?php echo (isset($policy_data['server_doc_status']) && $policy_data['server_doc_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="serverDocNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="server_doc_status" id="serverDocPartial" value="Partial" <?php echo (isset($policy_data['server_doc_status']) && $policy_data['server_doc_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="serverDocPartial">Partial</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="server_doc_ref" value="<?php echo htmlspecialchars($policy_data['server_doc_ref'] ?? ''); ?>" placeholder="Reference">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="server_doc_detail" rows="2" 
                                                  placeholder="Server documentation details..."><?php echo htmlspecialchars($policy_data['server_doc_detail'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- III. Server Operation SOP -->
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">III. Server Operation SOP:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="server_op_status" id="serverOpYes" value="Yes" <?php echo (isset($policy_data['server_op_status']) && $policy_data['server_op_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="serverOpYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="server_op_status" id="serverOpNo" value="No" <?php echo (isset($policy_data['server_op_status']) && $policy_data['server_op_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="serverOpNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="server_op_status" id="serverOpPartial" value="Partial" <?php echo (isset($policy_data['server_op_status']) && $policy_data['server_op_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="serverOpPartial">Partial</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="server_op_ref" value="<?php echo htmlspecialchars($policy_data['server_op_ref'] ?? ''); ?>" placeholder="Reference">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="server_op_detail" rows="2" 
                                                  placeholder="Server operation SOP details..."><?php echo htmlspecialchars($policy_data['server_op_detail'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- IV. Change Management -->
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">IV. Change Management:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="change_mgmt_status" id="changeYes" value="Yes" <?php echo (isset($policy_data['change_mgmt_status']) && $policy_data['change_mgmt_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="changeYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="change_mgmt_status" id="changeNo" value="No" <?php echo (isset($policy_data['change_mgmt_status']) && $policy_data['change_mgmt_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="changeNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="change_mgmt_status" id="changePartial" value="Partial" <?php echo (isset($policy_data['change_mgmt_status']) && $policy_data['change_mgmt_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="changePartial">Partial</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="change_mgmt_ref" value="<?php echo htmlspecialchars($policy_data['change_mgmt_ref'] ?? ''); ?>" placeholder="Reference">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="change_mgmt_detail" rows="2" 
                                                  placeholder="Change management process details..."><?php echo htmlspecialchars($policy_data['change_mgmt_detail'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- V. Access Log Management -->
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">V. Access Log Management:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="access_log_status" id="accessLogYes" value="Yes" <?php echo (isset($policy_data['access_log_status']) && $policy_data['access_log_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="accessLogYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="access_log_status" id="accessLogNo" value="No" <?php echo (isset($policy_data['access_log_status']) && $policy_data['access_log_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="accessLogNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="access_log_status" id="accessLogPartial" value="Partial" <?php echo (isset($policy_data['access_log_status']) && $policy_data['access_log_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="accessLogPartial">Partial</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="access_log_ref" value="<?php echo htmlspecialchars($policy_data['access_log_ref'] ?? ''); ?>" placeholder="Reference">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="access_log_detail" rows="2" 
                                                  placeholder="Access log management details..."><?php echo htmlspecialchars($policy_data['access_log_detail'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- VI. Backup and Recovery Policy -->
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">VI. Backup and Recovery Policy:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="backup_policy_status" id="backupYes" value="Yes" <?php echo (isset($policy_data['backup_policy_status']) && $policy_data['backup_policy_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="backupYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="backup_policy_status" id="backupNo" value="No" <?php echo (isset($policy_data['backup_policy_status']) && $policy_data['backup_policy_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="backupNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="backup_policy_status" id="backupPartial" value="Partial" <?php echo (isset($policy_data['backup_policy_status']) && $policy_data['backup_policy_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="backupPartial">Partial</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="backup_policy_ref" value="<?php echo htmlspecialchars($policy_data['backup_policy_ref'] ?? ''); ?>" placeholder="Reference">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="backup_policy_detail" rows="2" 
                                                  placeholder="Backup and recovery policy details..."><?php echo htmlspecialchars($policy_data['backup_policy_detail'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- VII. Password Policy -->
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">VII. Password Policy:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="password_policy_status" id="passwordYes" value="Yes" <?php echo (isset($policy_data['password_policy_status']) && $policy_data['password_policy_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="passwordYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="password_policy_status" id="passwordNo" value="No" <?php echo (isset($policy_data['password_policy_status']) && $policy_data['password_policy_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="passwordNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="password_policy_status" id="passwordPartial" value="Partial" <?php echo (isset($policy_data['password_policy_status']) && $policy_data['password_policy_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="passwordPartial">Partial</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="password_policy_ref" value="<?php echo htmlspecialchars($policy_data['password_policy_ref'] ?? ''); ?>" placeholder="Reference">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="password_policy_detail" rows="2" 
                                                  placeholder="Password policy details..."><?php echo htmlspecialchars($policy_data['password_policy_detail'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- VIII. User Access Management Policy -->
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">VIII. User Access Management Policy:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="user_access_status" id="userAccessYes" value="Yes" <?php echo (isset($policy_data['user_access_status']) && $policy_data['user_access_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="userAccessYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="user_access_status" id="userAccessNo" value="No" <?php echo (isset($policy_data['user_access_status']) && $policy_data['user_access_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="userAccessNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="user_access_status" id="userAccessPartial" value="Partial" <?php echo (isset($policy_data['user_access_status']) && $policy_data['user_access_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="userAccessPartial">Partial</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="user_access_ref" value="<?php echo htmlspecialchars($policy_data['user_access_ref'] ?? ''); ?>" placeholder="Reference">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="user_access_detail" rows="2" 
                                                  placeholder="User access management details..."><?php echo htmlspecialchars($policy_data['user_access_detail'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- IX. Data Classification Policy -->
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">IX. Data Classification Policy:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="data_class_status" id="dataClassYes" value="Yes" <?php echo (isset($policy_data['data_class_status']) && $policy_data['data_class_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="dataClassYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="data_class_status" id="dataClassNo" value="No" <?php echo (isset($policy_data['data_class_status']) && $policy_data['data_class_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="dataClassNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="data_class_status" id="dataClassPartial" value="Partial" <?php echo (isset($policy_data['data_class_status']) && $policy_data['data_class_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="dataClassPartial">Partial</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="data_class_ref" value="<?php echo htmlspecialchars($policy_data['data_class_ref'] ?? ''); ?>" placeholder="Reference">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="data_class_detail" rows="2" 
                                                  placeholder="Data classification policy details..."><?php echo htmlspecialchars($policy_data['data_class_detail'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- X. Business Continuity Plan -->
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">X. Business Continuity Plan:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="bcp_status" id="bcpYes" value="Yes" <?php echo (isset($policy_data['bcp_status']) && $policy_data['bcp_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="bcpYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="bcp_status" id="bcpNo" value="No" <?php echo (isset($policy_data['bcp_status']) && $policy_data['bcp_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="bcpNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="bcp_status" id="bcpPartial" value="Partial" <?php echo (isset($policy_data['bcp_status']) && $policy_data['bcp_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="bcpPartial">Partial</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="bcp_ref" value="<?php echo htmlspecialchars($policy_data['bcp_ref'] ?? ''); ?>" placeholder="Reference">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="bcp_detail" rows="2" 
                                                  placeholder="Business continuity plan details..."><?php echo htmlspecialchars($policy_data['bcp_detail'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- XI. Other Policies (Add more as needed) -->
                                    <div class="mb-2 p-3 bg-light rounded">
                                        <label class="font-weight-bold">XI. Other Policies/SOPs:</label>
                                        <textarea class="form-control" name="other_policies" rows="3" 
                                                  placeholder="List any other policies, SOPs or practices not covered above..."><?php echo htmlspecialchars($policy_data['other_policies'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- C. Interval of policy review (If any) -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    C. Interval of policy review (If any):
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <select class="form-control" name="review_interval">
                                                <option value="">Select Review Interval</option>
                                                <option value="Monthly" <?php echo (isset($policy_data['review_interval']) && $policy_data['review_interval'] == 'Monthly') ? 'selected' : ''; ?>>Monthly</option>
                                                <option value="Quarterly" <?php echo (isset($policy_data['review_interval']) && $policy_data['review_interval'] == 'Quarterly') ? 'selected' : ''; ?>>Quarterly</option>
                                                <option value="Half-Yearly" <?php echo (isset($policy_data['review_interval']) && $policy_data['review_interval'] == 'Half-Yearly') ? 'selected' : ''; ?>>Half-Yearly</option>
                                                <option value="Annually" <?php echo (isset($policy_data['review_interval']) && $policy_data['review_interval'] == 'Annually') ? 'selected' : ''; ?>>Annually</option>
                                                <option value="Bi-Annually" <?php echo (isset($policy_data['review_interval']) && $policy_data['review_interval'] == 'Bi-Annually') ? 'selected' : ''; ?>>Bi-Annually</option>
                                                <option value="As Needed" <?php echo (isset($policy_data['review_interval']) && $policy_data['review_interval'] == 'As Needed') ? 'selected' : ''; ?>>As Needed</option>
                                                <option value="Not Defined" <?php echo (isset($policy_data['review_interval']) && $policy_data['review_interval'] == 'Not Defined') ? 'selected' : ''; ?>>Not Defined</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <input type="date" class="form-control" name="last_review_date" value="<?php echo htmlspecialchars($policy_data['last_review_date'] ?? ''); ?>" placeholder="Last Review Date">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <input type="date" class="form-control" name="next_review_date" value="<?php echo htmlspecialchars($policy_data['next_review_date'] ?? ''); ?>" placeholder="Next Review Date">
                                        </div>
                                    </div>
                                    <textarea class="form-control mt-2" name="review_notes" rows="2" 
                                              placeholder="Review notes and findings..."><?php echo htmlspecialchars($policy_data['review_notes'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- D. Vetting Clearance of all user / Admin -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    D. Vetting Clearance of all user / Admin:
                                </label>
                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="vetting_status" id="vettingYes" value="Yes" <?php echo (isset($policy_data['vetting_status']) && $policy_data['vetting_status'] == 'Yes') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="vettingYes">Yes - All users vetted</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="vetting_status" id="vettingPartial" value="Partial" <?php echo (isset($policy_data['vetting_status']) && $policy_data['vetting_status'] == 'Partial') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="vettingPartial">Partially</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="vetting_status" id="vettingNo" value="No" <?php echo (isset($policy_data['vetting_status']) && $policy_data['vetting_status'] == 'No') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="vettingNo">No</label>
                                        </div>
                                    </div>
                                    
                                    <!-- Reference and Details -->
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="p-3 bg-light rounded">
                                                <label class="font-weight-bold">Vetting Process Details:</label>
                                                <textarea class="form-control" name="vetting_details" rows="3" 
                                                          placeholder="Describe vetting/clearance process for users and administrators..."><?php echo htmlspecialchars($policy_data['vetting_details'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-3 bg-light rounded">
                                                <label class="font-weight-bold">Reference:</label>
                                                <div class="text-primary">
                                                    <small>साइबरस्पेस प्रयोग निर्देशिका<br>पेज नं. २३ बुंदा ३ को (क)</small>
                                                </div>
                                                <input type="text" class="form-control mt-2" name="vetting_ref" value="<?php echo htmlspecialchars($policy_data['vetting_ref'] ?? ''); ?>" placeholder="Additional reference">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Policy Compliance Summary -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    Policy Compliance Summary:
                                </label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="compliance_summary" rows="4" 
                                              placeholder="Overall policy compliance status and observations..."><?php echo htmlspecialchars($policy_data['compliance_summary'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label font-weight-bold text-primary">Remarks:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="remarks" rows="3" 
                                              placeholder="Any additional remarks or notes for policies and procedures"><?php echo htmlspecialchars($policy_data['remarks'] ?? ''); ?></textarea>
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
                                        <button type="button" class="btn btn-info px-4 mr-2" onclick="window.location.href='step4.php'">
                                            <i class="fas fa-arrow-left mr-1"></i> Previous Step
                                        </button>
                                        <button type="submit" name="save_step6" class="btn btn-primary px-5">
                                            <i class="fas fa-save mr-2"></i> Save Policies and SOP
                                        </button>
                                        <button type="button" class="btn btn-info px-4 ml-2" onclick="window.location.href='step6.php'">
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