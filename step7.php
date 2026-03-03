<?php
// step7-backup-recovery.php
include 'includes/authentication.php';
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'config/dbcon.php';

// Fetch existing data if editing
$server_id = isset($_GET['server_id']) ? $_GET['server_id'] : 0;
$backup_data = [];
if ($server_id) {
    $query = "SELECT * FROM backup_recovery WHERE server_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $server_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $backup_data = $result->fetch_assoc();
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
                        7. Backup & Recovery Testing
                    </h2>
                    <p class="text-muted mt-1">Backup configuration, disaster recovery, and testing procedures</p>
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
                    <form method="POST" action="save-step7.php" id="backupRecoveryForm">
                        <input type="hidden" name="server_id" value="<?php echo $server_id; ?>">
                        
                        <!-- Backup & Recovery Section -->
                        <div class="security-block p-4 border rounded">
                            
                            <!-- A. Alternative site or location for the Server -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    A. Alternative site or location for the Server:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="alt_site_status" id="altSiteYes" value="Yes" <?php echo (isset($backup_data['alt_site_status']) && $backup_data['alt_site_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="altSiteYes">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="alt_site_status" id="altSiteNo" value="No" <?php echo (isset($backup_data['alt_site_status']) && $backup_data['alt_site_status'] == 'No') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="altSiteNo">No</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="alt_site_status" id="altSitePlanned" value="Planned" <?php echo (isset($backup_data['alt_site_status']) && $backup_data['alt_site_status'] == 'Planned') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="altSitePlanned">Planned</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">Site Type:</label>
                                                <select class="form-control" name="alt_site_type">
                                                    <option value="">Select Type</option>
                                                    <option value="Hot Site" <?php echo (isset($backup_data['alt_site_type']) && $backup_data['alt_site_type'] == 'Hot Site') ? 'selected' : ''; ?>>Hot Site (Fully equipped)</option>
                                                    <option value="Warm Site" <?php echo (isset($backup_data['alt_site_type']) && $backup_data['alt_site_type'] == 'Warm Site') ? 'selected' : ''; ?>>Warm Site (Partially equipped)</option>
                                                    <option value="Cold Site" <?php echo (isset($backup_data['alt_site_type']) && $backup_data['alt_site_type'] == 'Cold Site') ? 'selected' : ''; ?>>Cold Site (Basic infrastructure)</option>
                                                    <option value="Cloud" <?php echo (isset($backup_data['alt_site_type']) && $backup_data['alt_site_type'] == 'Cloud') ? 'selected' : ''; ?>>Cloud-Based</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">Location:</label>
                                                <input type="text" class="form-control" name="alt_site_location" value="<?php echo htmlspecialchars($backup_data['alt_site_location'] ?? ''); ?>" placeholder="e.g., Kathmandu, Pokhara, etc.">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="alt_site_details" rows="2" 
                                                  placeholder="Alternative site details and specifications..."><?php echo htmlspecialchars($backup_data['alt_site_details'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- B. High Availability -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    B. High Availability:
                                </label>
                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="ha_status" id="haYes" value="Yes" <?php echo (isset($backup_data['ha_status']) && $backup_data['ha_status'] == 'Yes') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="haYes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="ha_status" id="haNo" value="No" <?php echo (isset($backup_data['ha_status']) && $backup_data['ha_status'] == 'No') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="haNo">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="ha_status" id="haPartial" value="Partial" <?php echo (isset($backup_data['ha_status']) && $backup_data['ha_status'] == 'Partial') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="haPartial">Partial</label>
                                        </div>
                                    </div>

                                    <!-- I. Primary -->
                                    <div class="p-3 bg-light rounded mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="font-weight-bold">I. Primary Server:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="ha_primary" value="<?php echo htmlspecialchars($backup_data['ha_primary'] ?? ''); ?>" placeholder="Primary server details (hostname, IP, role)">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- II. Secondary -->
                                    <div class="p-3 bg-light rounded mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="font-weight-bold">II. Secondary Server:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="ha_secondary" value="<?php echo htmlspecialchars($backup_data['ha_secondary'] ?? ''); ?>" placeholder="Secondary server details (hostname, IP, role)">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- III. Sync Interval -->
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="font-weight-bold">III. Sync Interval:</label>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="ha_sync_interval">
                                                    <option value="">Select Interval</option>
                                                    <option value="Real-time" <?php echo (isset($backup_data['ha_sync_interval']) && $backup_data['ha_sync_interval'] == 'Real-time') ? 'selected' : ''; ?>>Real-time Synchronous</option>
                                                    <option value="5 Seconds" <?php echo (isset($backup_data['ha_sync_interval']) && $backup_data['ha_sync_interval'] == '5 Seconds') ? 'selected' : ''; ?>>Every 5 Seconds</option>
                                                    <option value="30 Seconds" <?php echo (isset($backup_data['ha_sync_interval']) && $backup_data['ha_sync_interval'] == '30 Seconds') ? 'selected' : ''; ?>>Every 30 Seconds</option>
                                                    <option value="1 Minute" <?php echo (isset($backup_data['ha_sync_interval']) && $backup_data['ha_sync_interval'] == '1 Minute') ? 'selected' : ''; ?>>Every 1 Minute</option>
                                                    <option value="5 Minutes" <?php echo (isset($backup_data['ha_sync_interval']) && $backup_data['ha_sync_interval'] == '5 Minutes') ? 'selected' : ''; ?>>Every 5 Minutes</option>
                                                    <option value="15 Minutes" <?php echo (isset($backup_data['ha_sync_interval']) && $backup_data['ha_sync_interval'] == '15 Minutes') ? 'selected' : ''; ?>>Every 15 Minutes</option>
                                                    <option value="Hourly" <?php echo (isset($backup_data['ha_sync_interval']) && $backup_data['ha_sync_interval'] == 'Hourly') ? 'selected' : ''; ?>>Hourly</option>
                                                    <option value="Custom" <?php echo (isset($backup_data['ha_sync_interval']) && $backup_data['ha_sync_interval'] == 'Custom') ? 'selected' : ''; ?>>Custom Interval</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="ha_sync_custom" value="<?php echo htmlspecialchars($backup_data['ha_sync_custom'] ?? ''); ?>" placeholder="If custom, specify">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- C. Offsite Backup -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    C. Offsite Backup:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="offsite_backup_status" id="offsiteYes" value="Yes" <?php echo (isset($backup_data['offsite_backup_status']) && $backup_data['offsite_backup_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="offsiteYes">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="offsite_backup_status" id="offsiteNo" value="No" <?php echo (isset($backup_data['offsite_backup_status']) && $backup_data['offsite_backup_status'] == 'No') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="offsiteNo">No</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="offsite_backup_status" id="offsitePlanned" value="Planned" <?php echo (isset($backup_data['offsite_backup_status']) && $backup_data['offsite_backup_status'] == 'Planned') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="offsitePlanned">Planned</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- D. Location of Offsite Backup -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    D. Location of Offsite Backup:
                                </label>
                                <div class="col-sm-9">
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="offsite_location" value="<?php echo htmlspecialchars($backup_data['offsite_location'] ?? ''); ?>" placeholder="Physical location (e.g., Bank Vault, Another City)">
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control" name="offsite_location_type">
                                                    <option value="">Location Type</option>
                                                    <option value="Same City" <?php echo (isset($backup_data['offsite_location_type']) && $backup_data['offsite_location_type'] == 'Same City') ? 'selected' : ''; ?>>Same City - Different Building</option>
                                                    <option value="Different City" <?php echo (isset($backup_data['offsite_location_type']) && $backup_data['offsite_location_type'] == 'Different City') ? 'selected' : ''; ?>>Different City</option>
                                                    <option value="Different Region" <?php echo (isset($backup_data['offsite_location_type']) && $backup_data['offsite_location_type'] == 'Different Region') ? 'selected' : ''; ?>>Different Region</option>
                                                    <option value="Cloud" <?php echo (isset($backup_data['offsite_location_type']) && $backup_data['offsite_location_type'] == 'Cloud') ? 'selected' : ''; ?>>Cloud Storage</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="offsite_distance" value="<?php echo htmlspecialchars($backup_data['offsite_distance'] ?? ''); ?>" placeholder="Distance from primary site">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="offsite_facility" value="<?php echo htmlspecialchars($backup_data['offsite_facility'] ?? ''); ?>" placeholder="Facility type (e.g., Data Center, Vault)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- E. Process of Offsite Backup -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    E. Process of Offsite Backup:
                                </label>
                                <div class="col-sm-9">
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <select class="form-control" name="offsite_process_type">
                                                    <option value="">Select Process</option>
                                                    <option value="Automatic" <?php echo (isset($backup_data['offsite_process_type']) && $backup_data['offsite_process_type'] == 'Automatic') ? 'selected' : ''; ?>>Automatic/Electronic Transfer</option>
                                                    <option value="Manual" <?php echo (isset($backup_data['offsite_process_type']) && $backup_data['offsite_process_type'] == 'Manual') ? 'selected' : ''; ?>>Manual/Physical Transport</option>
                                                    <option value="Hybrid" <?php echo (isset($backup_data['offsite_process_type']) && $backup_data['offsite_process_type'] == 'Hybrid') ? 'selected' : ''; ?>>Hybrid (Both)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="offsite_transfer_method" value="<?php echo htmlspecialchars($backup_data['offsite_transfer_method'] ?? ''); ?>" placeholder="Transfer method (e.g., VPN, Courier)">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="offsite_encryption" value="<?php echo htmlspecialchars($backup_data['offsite_encryption'] ?? ''); ?>" placeholder="Encryption method">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="offsite_process_details" rows="2" 
                                                  placeholder="Detailed description of offsite backup process..."><?php echo htmlspecialchars($backup_data['offsite_process_details'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- F. Interval of Offsite Backup -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    F. Interval of Offsite Backup:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control" name="offsite_interval">
                                                <option value="">Select Interval</option>
                                                <option value="Real-time" <?php echo (isset($backup_data['offsite_interval']) && $backup_data['offsite_interval'] == 'Real-time') ? 'selected' : ''; ?>>Real-time/Continuous</option>
                                                <option value="Hourly" <?php echo (isset($backup_data['offsite_interval']) && $backup_data['offsite_interval'] == 'Hourly') ? 'selected' : ''; ?>>Hourly</option>
                                                <option value="Daily" <?php echo (isset($backup_data['offsite_interval']) && $backup_data['offsite_interval'] == 'Daily') ? 'selected' : ''; ?>>Daily</option>
                                                <option value="Weekly" <?php echo (isset($backup_data['offsite_interval']) && $backup_data['offsite_interval'] == 'Weekly') ? 'selected' : ''; ?>>Weekly</option>
                                                <option value="Monthly" <?php echo (isset($backup_data['offsite_interval']) && $backup_data['offsite_interval'] == 'Monthly') ? 'selected' : ''; ?>>Monthly</option>
                                                <option value="Custom" <?php echo (isset($backup_data['offsite_interval']) && $backup_data['offsite_interval'] == 'Custom') ? 'selected' : ''; ?>>Custom</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="offsite_interval_custom" value="<?php echo htmlspecialchars($backup_data['offsite_interval_custom'] ?? ''); ?>" placeholder="If custom, specify">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" class="form-control" name="offsite_backup_time" value="<?php echo htmlspecialchars($backup_data['offsite_backup_time'] ?? ''); ?>" placeholder="Backup time">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- G. Types of Database backup -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    G. Types of Database backup:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="db_backup_full" id="dbFull" value="Full Backup" <?php echo (isset($backup_data['db_backup_types']) && strpos($backup_data['db_backup_types'], 'Full Backup') !== false) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="dbFull">Full Backup</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="db_backup_incremental" id="dbIncremental" value="Incremental Backup" <?php echo (isset($backup_data['db_backup_types']) && strpos($backup_data['db_backup_types'], 'Incremental Backup') !== false) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="dbIncremental">Incremental Backup</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="db_backup_differential" id="dbDifferential" value="Differential Backup" <?php echo (isset($backup_data['db_backup_types']) && strpos($backup_data['db_backup_types'], 'Differential Backup') !== false) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="dbDifferential">Differential Backup</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="db_backup_transaction" id="dbTransaction" value="Transaction Log Backup" <?php echo (isset($backup_data['db_backup_types']) && strpos($backup_data['db_backup_types'], 'Transaction Log Backup') !== false) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="dbTransaction">Transaction Log Backup</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="db_backup_continuous" id="dbContinuous" value="Continuous Backup" <?php echo (isset($backup_data['db_backup_types']) && strpos($backup_data['db_backup_types'], 'Continuous Backup') !== false) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="dbContinuous">Continuous/Real-time Backup</label>
                                            </div>
                                        </div>
                                    </div>
                                    <textarea class="form-control mt-2" name="db_backup_details" rows="2" 
                                              placeholder="Additional database backup details..."><?php echo htmlspecialchars($backup_data['db_backup_details'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- H. Interval of Database backup -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    H. Interval of Database backup:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <select class="form-control" name="db_backup_interval">
                                                <option value="">Select Interval</option>
                                                <option value="Real-time" <?php echo (isset($backup_data['db_backup_interval']) && $backup_data['db_backup_interval'] == 'Real-time') ? 'selected' : ''; ?>>Real-time</option>
                                                <option value="Hourly" <?php echo (isset($backup_data['db_backup_interval']) && $backup_data['db_backup_interval'] == 'Hourly') ? 'selected' : ''; ?>>Hourly</option>
                                                <option value="Every 6 Hours" <?php echo (isset($backup_data['db_backup_interval']) && $backup_data['db_backup_interval'] == 'Every 6 Hours') ? 'selected' : ''; ?>>Every 6 Hours</option>
                                                <option value="Every 12 Hours" <?php echo (isset($backup_data['db_backup_interval']) && $backup_data['db_backup_interval'] == 'Every 12 Hours') ? 'selected' : ''; ?>>Every 12 Hours</option>
                                                <option value="Daily" <?php echo (isset($backup_data['db_backup_interval']) && $backup_data['db_backup_interval'] == 'Daily') ? 'selected' : ''; ?>>Daily</option>
                                                <option value="Weekly" <?php echo (isset($backup_data['db_backup_interval']) && $backup_data['db_backup_interval'] == 'Weekly') ? 'selected' : ''; ?>>Weekly</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="time" class="form-control" name="db_backup_time" value="<?php echo htmlspecialchars($backup_data['db_backup_time'] ?? ''); ?>" placeholder="Backup time">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="db_retention_period" value="<?php echo htmlspecialchars($backup_data['db_retention_period'] ?? ''); ?>" placeholder="Retention period">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="db_backup_size" value="<?php echo htmlspecialchars($backup_data['db_backup_size'] ?? ''); ?>" placeholder="Avg. backup size">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- I. Is there any disaster recovery plan? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    I. Is there any disaster recovery plan?
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="dr_plan_status" id="drYes" value="Yes" <?php echo (isset($backup_data['dr_plan_status']) && $backup_data['dr_plan_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="drYes">Yes - Documented DR Plan</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="dr_plan_status" id="drNo" value="No" <?php echo (isset($backup_data['dr_plan_status']) && $backup_data['dr_plan_status'] == 'No') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="drNo">No DR Plan</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="dr_plan_status" id="drDevelopment" value="In Development" <?php echo (isset($backup_data['dr_plan_status']) && $backup_data['dr_plan_status'] == 'In Development') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="drDevelopment">In Development</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <label class="font-weight-bold">DR Plan Details:</label>
                                        <textarea class="form-control" name="dr_plan_details" rows="3" 
                                                  placeholder="Describe disaster recovery plan, scope, and coverage..."><?php echo htmlspecialchars($backup_data['dr_plan_details'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- J. Is the plan being tested? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    J. Is the plan being tested (Simulation drills/ Actual drills/ Walkthrough)?
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control" name="dr_test_type">
                                                <option value="">Select Test Type</option>
                                                <option value="Walkthrough" <?php echo (isset($backup_data['dr_test_type']) && $backup_data['dr_test_type'] == 'Walkthrough') ? 'selected' : ''; ?>>Tabletop Walkthrough</option>
                                                <option value="Simulation" <?php echo (isset($backup_data['dr_test_type']) && $backup_data['dr_test_type'] == 'Simulation') ? 'selected' : ''; ?>>Simulation Drill</option>
                                                <option value="Actual" <?php echo (isset($backup_data['dr_test_type']) && $backup_data['dr_test_type'] == 'Actual') ? 'selected' : ''; ?>>Actual Drill/Failover</option>
                                                <option value="Multiple" <?php echo (isset($backup_data['dr_test_type']) && $backup_data['dr_test_type'] == 'Multiple') ? 'selected' : ''; ?>>Multiple Types</option>
                                                <option value="None" <?php echo (isset($backup_data['dr_test_type']) && $backup_data['dr_test_type'] == 'None') ? 'selected' : ''; ?>>Not Tested</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="dr_test_frequency" value="<?php echo htmlspecialchars($backup_data['dr_test_frequency'] ?? ''); ?>" placeholder="Test frequency (e.g., Quarterly)">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" name="dr_last_test_date" value="<?php echo htmlspecialchars($backup_data['dr_last_test_date'] ?? ''); ?>" placeholder="Last test date">
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="dr_test_success_rate" value="<?php echo htmlspecialchars($backup_data['dr_test_success_rate'] ?? ''); ?>" placeholder="Success rate / Findings">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" name="dr_next_test_date" value="<?php echo htmlspecialchars($backup_data['dr_next_test_date'] ?? ''); ?>" placeholder="Next scheduled test">
                                        </div>
                                    </div>
                                    
                                    <textarea class="form-control mt-2" name="dr_test_details" rows="2" 
                                              placeholder="Test results and observations..."><?php echo htmlspecialchars($backup_data['dr_test_details'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- K. Backup & recovery testing procedures -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    K. Backup & recovery testing procedures:
                                </label>
                                <div class="col-sm-9">
                                    <div class="p-3 bg-light rounded">
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="test_procedure_restore" id="testRestore" value="Restore Testing" <?php echo (isset($backup_data['test_procedures']) && strpos($backup_data['test_procedures'], 'Restore Testing') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="testRestore">Periodic Restore Testing</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="test_procedure_validation" id="testValidation" value="Data Validation" <?php echo (isset($backup_data['test_procedures']) && strpos($backup_data['test_procedures'], 'Data Validation') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="testValidation">Data Validation/Integrity Check</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="test_procedure_automated" id="testAutomated" value="Automated Testing" <?php echo (isset($backup_data['test_procedures']) && strpos($backup_data['test_procedures'], 'Automated Testing') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="testAutomated">Automated Backup Testing</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="test_procedure_manual" id="testManual" value="Manual Testing" <?php echo (isset($backup_data['test_procedures']) && strpos($backup_data['test_procedures'], 'Manual Testing') !== false) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="testManual">Manual Testing/Verification</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="test_frequency" value="<?php echo htmlspecialchars($backup_data['test_frequency'] ?? ''); ?>" placeholder="Testing frequency">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="test_sample_size" value="<?php echo htmlspecialchars($backup_data['test_sample_size'] ?? ''); ?>" placeholder="Sample size / % tested">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="test_success_criteria" value="<?php echo htmlspecialchars($backup_data['test_success_criteria'] ?? ''); ?>" placeholder="Success criteria">
                                            </div>
                                        </div>
                                        
                                        <textarea class="form-control mt-2" name="test_procedure_details" rows="3" 
                                                  placeholder="Detailed backup and recovery testing procedures..."><?php echo htmlspecialchars($backup_data['test_procedure_details'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- L. Backup of Log Taken -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    L. Backup of Log Taken:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="log_backup_status" id="logBackupYes" value="Yes" <?php echo (isset($backup_data['log_backup_status']) && $backup_data['log_backup_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="logBackupYes">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="log_backup_status" id="logBackupNo" value="No" <?php echo (isset($backup_data['log_backup_status']) && $backup_data['log_backup_status'] == 'No') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="logBackupNo">No</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="log_backup_status" id="logBackupPartial" value="Partial" <?php echo (isset($backup_data['log_backup_status']) && $backup_data['log_backup_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="logBackupPartial">Partial</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">Types of logs backed up:</label>
                                                <select class="form-control" name="log_types">
                                                    <option value="">Select log types</option>
                                                    <option value="System Logs" <?php echo (isset($backup_data['log_types']) && $backup_data['log_types'] == 'System Logs') ? 'selected' : ''; ?>>System Logs</option>
                                                    <option value="Application Logs" <?php echo (isset($backup_data['log_types']) && $backup_data['log_types'] == 'Application Logs') ? 'selected' : ''; ?>>Application Logs</option>
                                                    <option value="Security Logs" <?php echo (isset($backup_data['log_types']) && $backup_data['log_types'] == 'Security Logs') ? 'selected' : ''; ?>>Security/Audit Logs</option>
                                                    <option value="Database Logs" <?php echo (isset($backup_data['log_types']) && $backup_data['log_types'] == 'Database Logs') ? 'selected' : ''; ?>>Database Logs</option>
                                                    <option value="All Logs" <?php echo (isset($backup_data['log_types']) && $backup_data['log_types'] == 'All Logs') ? 'selected' : ''; ?>>All Logs</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">Log backup method:</label>
                                                <input type="text" class="form-control" name="log_backup_method" value="<?php echo htmlspecialchars($backup_data['log_backup_method'] ?? ''); ?>" placeholder="e.g., Separate log server, SIEM, etc.">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- M. If Yes then for how much time it can store? -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    M. If Yes then for how much time it can store?
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="log_storage_duration" value="<?php echo htmlspecialchars($backup_data['log_storage_duration'] ?? ''); ?>" placeholder="e.g., 30 days, 1 year">
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" name="log_storage_unit">
                                                <option value="">Unit</option>
                                                <option value="Days" <?php echo (isset($backup_data['log_storage_unit']) && $backup_data['log_storage_unit'] == 'Days') ? 'selected' : ''; ?>>Days</option>
                                                <option value="Months" <?php echo (isset($backup_data['log_storage_unit']) && $backup_data['log_storage_unit'] == 'Months') ? 'selected' : ''; ?>>Months</option>
                                                <option value="Years" <?php echo (isset($backup_data['log_storage_unit']) && $backup_data['log_storage_unit'] == 'Years') ? 'selected' : ''; ?>>Years</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="log_storage_size" value="<?php echo htmlspecialchars($backup_data['log_storage_size'] ?? ''); ?>" placeholder="Storage size (e.g., 100GB)">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="log_archive_method" value="<?php echo htmlspecialchars($backup_data['log_archive_method'] ?? ''); ?>" placeholder="Archive method">
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="log_compliance" value="<?php echo htmlspecialchars($backup_data['log_compliance'] ?? ''); ?>" placeholder="Compliance requirements (if any)">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="log_retention_policy" value="<?php echo htmlspecialchars($backup_data['log_retention_policy'] ?? ''); ?>" placeholder="Retention policy reference">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Backup Summary -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    Backup & Recovery Summary:
                                </label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="backup_summary" rows="4" 
                                              placeholder="Overall backup and recovery assessment, strengths, and areas for improvement..."><?php echo htmlspecialchars($backup_data['backup_summary'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- RTO and RPO -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    RTO & RPO:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded">
                                                <label class="font-weight-bold">Recovery Time Objective (RTO):</label>
                                                <input type="text" class="form-control" name="rto" value="<?php echo htmlspecialchars($backup_data['rto'] ?? ''); ?>" placeholder="e.g., 4 hours">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded">
                                                <label class="font-weight-bold">Recovery Point Objective (RPO):</label>
                                                <input type="text" class="form-control" name="rpo" value="<?php echo htmlspecialchars($backup_data['rpo'] ?? ''); ?>" placeholder="e.g., 15 minutes">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label font-weight-bold text-primary">Remarks:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="remarks" rows="3" 
                                              placeholder="Any additional remarks or notes for backup and recovery"><?php echo htmlspecialchars($backup_data['remarks'] ?? ''); ?></textarea>
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
                                        <button type="button" class="btn btn-info px-4 mr-2" onclick="window.location.href='step6.php'">
                                            <i class="fas fa-arrow-left mr-1"></i> Previous Step
                                        </button>
                                        <button type="submit" name="save_step7" class="btn btn-primary px-5">
                                            <i class="fas fa-save mr-2"></i> Save Backup & Recovery
                                        </button>
                                        <button type="button" class="btn btn-info px-4 ml-2" onclick="window.location.href='step8.php'">
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