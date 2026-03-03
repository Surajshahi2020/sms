<?php
// step8-data-security.php
include 'includes/authentication.php';
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
                <div class="col-sm-6">
                    <h2 class="m-0">
                        <i class="fas fa-info-circle mr-2" style="color: #4361ee;"></i>
                        3. Data Security
                    </h2>
                    <p class="text-muted mt-1">Data protection and access control configuration</p>
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
                    <form method="POST" action="save-step8.php" id="dataSecurityForm">
                        <!-- Data Security Section -->
                        <div class="security-block p-4 border rounded">
                            
                            <!-- A. Authorization of Data -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    A. Authorization of Data:
                                </label>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" name="auth_data" id="authData" value="yes">
                                        <label class="custom-control-label" for="authData">Data authorization implemented</label>
                                    </div>
                                    <textarea class="form-control mt-2" name="auth_data_details" rows="2" 
                                              placeholder="Describe data authorization process and policies"></textarea>
                                </div>
                            </div>

                            <!-- B. Authentication of user -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    B. Authentication of user:
                                </label>
                                <div class="col-sm-9">
                                    <select class="form-control mb-2" name="auth_method">
                                        <option value="">Select Authentication Method</option>
                                        <option value="Password">Password Based</option>
                                        <option value="2FA">Two Factor Authentication (2FA)</option>
                                        <option value="Biometric">Biometric</option>
                                        <option value="Certificate">Certificate Based</option>
                                        <option value="LDAP">LDAP/Active Directory</option>
                                        <option value="SSO">Single Sign-On (SSO)</option>
                                        <option value="Multiple">Multiple Methods</option>
                                    </select>
                                    <textarea class="form-control" name="auth_details" rows="2" 
                                              placeholder="Additional authentication details and policies"></textarea>
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
                                                <input type="checkbox" class="custom-control-input" name="access_rbac" id="accessRBAC" value="yes">
                                                <label class="custom-control-label" for="accessRBAC">Role-Based Access (RBAC)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="access_mac" id="accessMAC" value="yes">
                                                <label class="custom-control-label" for="accessMAC">Mandatory Access (MAC)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="access_dac" id="accessDAC" value="yes">
                                                <label class="custom-control-label" for="accessDAC">Discretionary Access (DAC)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="access_rule" id="accessRule" value="yes">
                                                <label class="custom-control-label" for="accessRule">Rule-Based Access</label>
                                            </div>
                                        </div>
                                    </div>
                                    <textarea class="form-control mt-2" name="access_details" rows="2" 
                                              placeholder="Describe access control implementation"></textarea>
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
                                            <input type="radio" class="custom-control-input" name="privilege_based" id="privYes" value="yes">
                                            <label class="custom-control-label" for="privYes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="privilege_based" id="privNo" value="no">
                                            <label class="custom-control-label" for="privNo">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="privilege_based" id="privPartial" value="partial">
                                            <label class="custom-control-label" for="privPartial">Partially</label>
                                        </div>
                                    </div>
                                    
                                    <!-- I. Explain (SOP) -->
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <label class="font-weight-bold">I. Explain (SOP):</label>
                                        <textarea class="form-control" name="privilege_sop" rows="3" 
                                                  placeholder="Explain Standard Operating Procedure (SOP) for privilege-based user creation&#10;&#10;Example:&#10;- User privilege matrix documented&#10;- Approval process for privilege assignment&#10;- Regular privilege review cycle&#10;- Principle of least privilege followed"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- E. Encryption -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    E. Encryption:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="font-weight-bold">Data at Rest:</label>
                                            <select class="form-control mb-3" name="encryption_rest">
                                                <option value="">Select Encryption Type</option>
                                                <option value="AES-256">AES-256</option>
                                                <option value="AES-128">AES-128</option>
                                                <option value="DES">DES/3DES</option>
                                                <option value="None">No Encryption</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="font-weight-bold">Data in Transit:</label>
                                            <select class="form-control mb-3" name="encryption_transit">
                                                <option value="">Select Protocol</option>
                                                <option value="TLS 1.3">TLS 1.3</option>
                                                <option value="TLS 1.2">TLS 1.2</option>
                                                <option value="SSH">SSH</option>
                                                <option value="IPSec">IPSec</option>
                                                <option value="None">No Encryption</option>
                                            </select>
                                        </div>
                                    </div>
                                    <textarea class="form-control" name="encryption_details" rows="2" 
                                              placeholder="Additional encryption details and key management"></textarea>
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
                                        <div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="offhour_duty" id="dutyYes" value="yes">
                                                <label class="custom-control-label" for="dutyYes">Duty Roster Exists</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="offhour_duty" id="dutyNo" value="no">
                                                <label class="custom-control-label" for="dutyNo">No Duty Roster</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="offhour_duty" id="dutyOnCall" value="oncall">
                                                <label class="custom-control-label" for="dutyOnCall">On-Call Basis</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- II. Duty Sop -->
                                    <div class="mb-3">
                                        <label class="font-weight-bold">II. Duty SOP:</label>
                                        <textarea class="form-control" name="duty_sop" rows="3" 
                                                  placeholder="Describe Duty Standard Operating Procedure&#10;&#10;Example:&#10;- Escalation matrix&#10;- Response time requirements&#10;- Communication protocol&#10;- Handover procedure"></textarea>
                                    </div>

                                    <!-- III. Off hour server administration -->
                                    <div class="mb-2">
                                        <label class="font-weight-bold">III. Off hour server administration:</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="offhour_maintenance" id="offMaint" value="yes">
                                                    <label class="custom-control-label" for="offMaint">Scheduled Maintenance</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="offhour_emergency" id="offEmerg" value="yes">
                                                    <label class="custom-control-label" for="offEmerg">Emergency Access</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="offhour_logging" id="offLog" value="yes">
                                                    <label class="custom-control-label" for="offLog">Activity Logging</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="offhour_approval" id="offApp" value="yes">
                                                    <label class="custom-control-label" for="offApp">Prior Approval Required</label>
                                                </div>
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="offhour_details" rows="2" 
                                                  placeholder="Additional off-hour administration details"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label font-weight-bold text-primary">Remarks:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="remarks" rows="2" 
                                              placeholder="Any additional remarks or notes for data security"></textarea>
                                </div>
                            </div>

                            <!-- Reference -->
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <small class="text-primary">
                                        <i class="fas fa-book-open mr-1"></i>
                                        Reference: साइबर सुरक्षा नीति, पेज नं. ९, २८ को (ख) को ३
                                    </small>
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
                                        <button type="submit" name="save_step6" class="btn btn-primary px-5">
                                            <i class="fas fa-save mr-2"></i> Save Data Security
                                        </button>
                                        <button type="button" class="btn btn-info px-4 ml-2" onclick="window.location.href='step4.php'">
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