<?php
// step2-os.php
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
                <div class="col-sm-8">
                    <h2 class="m-0">
                        <i class="fas fa-info-circle mr-2" style="color: #4361ee;"></i>
                        Step 2: Operating Systems and Servers
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
                        <!-- Server Block -->
                        <div class="server-block p-4 border rounded">
                            
                            <!-- A. Operating Systems -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">A. Operating Systems:</label>
                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <label class="font-weight-bold">I. Windows:</label>
                                        <input type="text" class="form-control mt-1" name="os_windows" 
                                               placeholder="e.g., Windows Server 2019, Windows Server 2022, Windows 11">
                                    </div>
                                    <div class="mb-3">
                                        <label class="font-weight-bold">II. Linux:</label>
                                        <input type="text" class="form-control mt-1" name="os_linux" 
                                               placeholder="e.g., Ubuntu 22.04, RHEL 8, CentOS 7, Debian 12">
                                    </div>
                                    <div>
                                        <label class="font-weight-bold">III. Other platforms if any:</label>
                                        <input type="text" class="form-control mt-1" name="os_other" 
                                               placeholder="e.g., FreeBSD, Solaris, AIX, macOS Server">
                                    </div>
                                </div>
                            </div>

                            <!-- B. Updated OS Patches -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">B. Updated OS Patches:</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="os_patches_status">
                                        <option value="">Select Status</option>
                                        <option value="Yes">Yes - Fully Updated</option>
                                        <option value="No">No - Updates Pending</option>
                                        <option value="Partial">Partially Updated</option>
                                        <option value="N/A">Not Applicable</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="os_patches_details" 
                                           placeholder="If No/Partial, explain why?">
                                </div>
                            </div>

                            <!-- C. Used Databases -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">C. Used Databases:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="databases" rows="2" 
                                              placeholder="List databases installed&#10;e.g.:&#10;MySQL 8.0&#10;PostgreSQL 14&#10;Oracle 19c"></textarea>
                                </div>
                            </div>

                            <!-- D. Database Admin -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">D. Database Admin:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="db_admin" 
                                           placeholder="e.g., root, postgres, admin, sa">
                                </div>
                            </div>

                            <!-- E. Are the database updated? (with sub-items) -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">E. Are the database updated?</label>
                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <label class="font-weight-bold">I. Oracle:</label>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <select class="form-control" name="db_oracle_status">
                                                    <option value="">Status</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                    <option value="Partial">Partial</option>
                                                    <option value="N/A">N/A</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="db_oracle_version" 
                                                       placeholder="Version (e.g., 19c)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="font-weight-bold">II. MySQL:</label>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <select class="form-control" name="db_mysql_status">
                                                    <option value="">Status</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                    <option value="Partial">Partial</option>
                                                    <option value="N/A">N/A</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="db_mysql_version" 
                                                       placeholder="Version (e.g., 8.0)">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="font-weight-bold">III. Others:</label>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <select class="form-control" name="db_others_status">
                                                    <option value="">Status</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                    <option value="Partial">Partial</option>
                                                    <option value="N/A">N/A</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="db_others_details" 
                                                       placeholder="Specify database and version">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- F. Services/Frameworks/Other Platforms used -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">F. Services/Frameworks/Other Platforms used:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="services_frameworks" rows="3" 
                                              placeholder="List all services, frameworks, and platforms&#10;e.g.:&#10;Apache 2.4&#10;PHP 8.1&#10;Node.js 18&#10;Docker 24.0&#10;Kubernetes 1.28"></textarea>
                                </div>
                            </div>

                            <!-- G. Designated Computer for Admin purpose -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">G. Designated Computer for Admin purpose:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="admin_computer" 
                                           placeholder="e.g., Admin-WS-01, 192.168.1.100">
                                </div>
                            </div>

                            <!-- H. No. of System Admin/Root Users -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">H. No. of System Admin/Root Users:</label>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" name="admin_count" min="0" value="1">
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="admin_count_reason" 
                                           placeholder="If >1, explain why?">
                                </div>
                                <div class="col-sm-12 mt-1">
                                    <small class="text-primary">Reference: साइबर सुरक्षा नीति, पेज १४, बुंदा ३४ को (ख)</small>
                                </div>
                            </div>

                            <!-- I. No. of Normal Users -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">I. No. of Normal Users:</label>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" name="normal_users_count" min="0" value="0">
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="normal_users_details" 
                                           placeholder="Details if any">
                                </div>
                            </div>

                            <!-- J. Are password policies being followed? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">J. Are password policies being followed?</label>
                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <label class="font-weight-bold">I. Length of Password:</label>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <select class="form-control" name="password_length_status">
                                                    <option value="">Status</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                    <option value="Partial">Partial</option>
                                                    <option value="N/A">N/A</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="password_length_details" 
                                                       placeholder="Current length requirement">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="font-weight-bold">II. Combination of Password:</label>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <select class="form-control" name="password_combo_status">
                                                    <option value="">Status</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                    <option value="Partial">Partial</option>
                                                    <option value="N/A">N/A</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="password_combo_details" 
                                                       placeholder="e.g., Upper, Lower, Numbers, Special">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-1">
                                        <small class="text-primary">Reference: साइबरस्पेस प्रयोग निर्देशिका, पेज नं. २६, को (ङ)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- K. Password Log Book -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">K. Password Log Book:</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="password_logbook_status">
                                        <option value="">Select Status</option>
                                        <option value="Yes">Yes - Maintained</option>
                                        <option value="No">No - Not Maintained</option>
                                        <option value="N/A">Not Applicable</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="password_logbook_details" 
                                           placeholder="User details, password & password change date">
                                </div>
                            </div>

                            <!-- L. Server type -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">L. Server type:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="server_type">
                                        <option value="">Select Server Type</option>
                                        <option value="Web Server">Web Server</option>
                                        <option value="Mail Server">Mail Server</option>
                                        <option value="File Server">File Server</option>
                                        <option value="Database Server">Database Server</option>
                                        <option value="DNS Server">DNS Server</option>
                                        <option value="Application Server">Application Server</option>
                                        <option value="Proxy Server">Proxy Server</option>
                                        <option value="Load Balancer">Load Balancer</option>
                                        <option value="Backup Server">Backup Server</option>
                                        <option value="Domain Controller">Domain Controller</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <input type="text" class="form-control mt-2" name="server_type_other" 
                                           placeholder="If Other, please specify">
                                </div>
                            </div>

                            <!-- M. Is digital certificate used? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">M. Is digital certificate used?</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="certificate_used">
                                        <option value="">Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="certificate_details" 
                                           placeholder="Certificate details if yes">
                                </div>
                            </div>

                            <!-- N. Digital certificate expiry date -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">N. Digital certificate expiry date:</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" name="certificate_expiry">
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-primary">Reference: साइबर सुरक्षा नीति, पेज नं. १३, बुंदा ३३ को (ख)</small>
                                </div>
                            </div>

                            <!-- O. Is Antivirus installed? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">O. Is Antivirus installed?</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="antivirus_installed">
                                        <option value="">Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="antivirus_details" 
                                           placeholder="Antivirus name if yes">
                                </div>
                                <div class="col-sm-12 mt-1">
                                    <small class="text-primary">Reference: साइबरस्पेस प्रयोग निर्देशिका, पेज नं. २७, बुंदा (च)</small>
                                </div>
                            </div>

                            <!-- P. Is Antivirus Updated? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">P. Is Antivirus Updated?</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="antivirus_updated">
                                        <option value="">Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                        <option value="Partial">Partial</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="antivirus_update_details" 
                                           placeholder="Last update date">
                                </div>
                            </div>

                            <!-- Q. Is SE Linux Enabled? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">Q. Is SE Linux Enabled? (In case of Linux server)</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="selinux_enabled">
                                        <option value="">Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                        <option value="N/A">N/A (Not Linux)</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="selinux_details" 
                                           placeholder="If enabled, mode (Enforcing/Permissive)">
                                </div>
                            </div>

                            <!-- R. Is Remote administration to server enabled? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">R. Is Remote administration to server enabled?</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="remote_admin_enabled">
                                        <option value="">Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="remote_admin_details" 
                                           placeholder="I. If Yes explain">
                                </div>
                                <div class="col-sm-12 mt-1">
                                    <small class="text-primary">Reference: साइबर सुरक्षा नीति, पेज नं. १३, ३३ को (ख) को ४ र ६</small>
                                </div>
                            </div>

                            <!-- S. Is the policy for remote access for server followed? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">S. Is the policy for remote access for server followed?</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="remote_policy_followed">
                                        <option value="">Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                        <option value="Partial">Partial</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="remote_policy_details" 
                                           placeholder="I. If No explain">
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
                                        <button type="submit" name="save_step2" class="btn btn-success px-5">
                                            <i class="fas fa-save mr-2"></i> Save Information
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