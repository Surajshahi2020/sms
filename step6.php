<?php
// step6-physical-security.php
include 'includes/authentication.php';
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'config/dbcon.php';

// Fetch existing data if editing
$server_id = isset($_GET['server_id']) ? $_GET['server_id'] : 0;
$physical_data = [];
if ($server_id) {
    $query = "SELECT * FROM physical_security WHERE server_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $server_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $physical_data = $result->fetch_assoc();
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
                        6. Physical and Environmental Security
                    </h2>
                    <p class="text-muted mt-1">Physical access controls, environmental protection, and monitoring systems</p>
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
                    <form method="POST" action="save-step6.php" id="physicalSecurityForm">
                        <input type="hidden" name="server_id" value="<?php echo $server_id; ?>">
                        
                        <!-- Physical and Environmental Security Section -->
                        <div class="security-block p-4 border rounded">
                            
                            <!-- A. Physical Access control systems -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    A. Physical Access control systems:
                                </label>
                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="access_control_status" id="accessYes" value="Yes" <?php echo (isset($physical_data['access_control_status']) && $physical_data['access_control_status'] == 'Yes') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="accessYes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="access_control_status" id="accessNo" value="No" <?php echo (isset($physical_data['access_control_status']) && $physical_data['access_control_status'] == 'No') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="accessNo">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="access_control_status" id="accessPartial" value="Partial" <?php echo (isset($physical_data['access_control_status']) && $physical_data['access_control_status'] == 'Partial') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="accessPartial">Partially</label>
                                        </div>
                                    </div>
                                    
                                    <!-- Reference Information -->
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <div class="p-3 bg-light rounded">
                                                <label class="font-weight-bold">Access Control Details:</label>
                                                <textarea class="form-control" name="access_control_details" rows="2" 
                                                          placeholder="Describe physical access control systems in place..."><?php echo htmlspecialchars($physical_data['access_control_details'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-3 bg-light rounded">
                                                <label class="font-weight-bold">Reference:</label>
                                                <div class="text-primary">
                                                    <small>साइबर सुरक्षा नीति,<br>Digitization प्रयोग निर्देशिका<br>पेज नं. १२ बुंदा ३२ को (ख),<br>पेज नं. ३३ बुंदा २</small>
                                                </div>
                                                <input type="text" class="form-control mt-2" name="access_control_ref" value="<?php echo htmlspecialchars($physical_data['access_control_ref'] ?? ''); ?>" placeholder="Additional reference">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- I. Physical Door lock -->
                                    <div class="mt-4 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">I. Physical Door lock:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="door_lock_status" id="doorLockYes" value="Yes" <?php echo (isset($physical_data['door_lock_status']) && $physical_data['door_lock_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="doorLockYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="door_lock_status" id="doorLockNo" value="No" <?php echo (isset($physical_data['door_lock_status']) && $physical_data['door_lock_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="doorLockNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="door_lock_status" id="doorLockNA" value="N/A" <?php echo (isset($physical_data['door_lock_status']) && $physical_data['door_lock_status'] == 'N/A') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="doorLockNA">N/A</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">Lock Type:</label>
                                                <select class="form-control" name="door_lock_type">
                                                    <option value="">Select Type</option>
                                                    <option value="Traditional" <?php echo (isset($physical_data['door_lock_type']) && $physical_data['door_lock_type'] == 'Traditional') ? 'selected' : ''; ?>>Traditional Key Lock</option>
                                                    <option value="Combination" <?php echo (isset($physical_data['door_lock_type']) && $physical_data['door_lock_type'] == 'Combination') ? 'selected' : ''; ?>>Combination Lock</option>
                                                    <option value="Electronic" <?php echo (isset($physical_data['door_lock_type']) && $physical_data['door_lock_type'] == 'Electronic') ? 'selected' : ''; ?>>Electronic Lock</option>
                                                    <option value="Multiple" <?php echo (isset($physical_data['door_lock_type']) && $physical_data['door_lock_type'] == 'Multiple') ? 'selected' : ''; ?>>Multiple Types</option>
                                                </select>
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="door_lock_details" rows="2" 
                                                  placeholder="Physical door lock details..."><?php echo htmlspecialchars($physical_data['door_lock_details'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- II. Biometric Door lock -->
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">II. Biometric Door lock:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="biometric_status" id="biometricYes" value="Yes" <?php echo (isset($physical_data['biometric_status']) && $physical_data['biometric_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="biometricYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="biometric_status" id="biometricNo" value="No" <?php echo (isset($physical_data['biometric_status']) && $physical_data['biometric_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="biometricNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="biometric_status" id="biometricNA" value="N/A" <?php echo (isset($physical_data['biometric_status']) && $physical_data['biometric_status'] == 'N/A') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="biometricNA">N/A</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">Biometric Type:</label>
                                                <select class="form-control" name="biometric_type">
                                                    <option value="">Select Type</option>
                                                    <option value="Fingerprint" <?php echo (isset($physical_data['biometric_type']) && $physical_data['biometric_type'] == 'Fingerprint') ? 'selected' : ''; ?>>Fingerprint Scanner</option>
                                                    <option value="Retina" <?php echo (isset($physical_data['biometric_type']) && $physical_data['biometric_type'] == 'Retina') ? 'selected' : ''; ?>>Retina Scanner</option>
                                                    <option value="Facial" <?php echo (isset($physical_data['biometric_type']) && $physical_data['biometric_type'] == 'Facial') ? 'selected' : ''; ?>>Facial Recognition</option>
                                                    <option value="Palm" <?php echo (isset($physical_data['biometric_type']) && $physical_data['biometric_type'] == 'Palm') ? 'selected' : ''; ?>>Palm Scanner</option>
                                                    <option value="Multi" <?php echo (isset($physical_data['biometric_type']) && $physical_data['biometric_type'] == 'Multi') ? 'selected' : ''; ?>>Multi-Modal</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="biometric_vendor" value="<?php echo htmlspecialchars($physical_data['biometric_vendor'] ?? ''); ?>" placeholder="Vendor/System Name">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="biometric_model" value="<?php echo htmlspecialchars($physical_data['biometric_model'] ?? ''); ?>" placeholder="Model/Version">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="biometric_details" rows="2" 
                                                  placeholder="Biometric system details..."><?php echo htmlspecialchars($physical_data['biometric_details'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- III. Digital Card Lock -->
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">III. Digital Card Lock:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="card_lock_status" id="cardYes" value="Yes" <?php echo (isset($physical_data['card_lock_status']) && $physical_data['card_lock_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="cardYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="card_lock_status" id="cardNo" value="No" <?php echo (isset($physical_data['card_lock_status']) && $physical_data['card_lock_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="cardNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="card_lock_status" id="cardNA" value="N/A" <?php echo (isset($physical_data['card_lock_status']) && $physical_data['card_lock_status'] == 'N/A') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="cardNA">N/A</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">Card Type:</label>
                                                <select class="form-control" name="card_lock_type">
                                                    <option value="">Select Type</option>
                                                    <option value="RFID" <?php echo (isset($physical_data['card_lock_type']) && $physical_data['card_lock_type'] == 'RFID') ? 'selected' : ''; ?>>RFID Card</option>
                                                    <option value="Smart Card" <?php echo (isset($physical_data['card_lock_type']) && $physical_data['card_lock_type'] == 'Smart Card') ? 'selected' : ''; ?>>Smart Card</option>
                                                    <option value="Proximity" <?php echo (isset($physical_data['card_lock_type']) && $physical_data['card_lock_type'] == 'Proximity') ? 'selected' : ''; ?>>Proximity Card</option>
                                                    <option value="Magnetic Stripe" <?php echo (isset($physical_data['card_lock_type']) && $physical_data['card_lock_type'] == 'Magnetic Stripe') ? 'selected' : ''; ?>>Magnetic Stripe</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="card_lock_system" value="<?php echo htmlspecialchars($physical_data['card_lock_system'] ?? ''); ?>" placeholder="System Name">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="card_lock_vendor" value="<?php echo htmlspecialchars($physical_data['card_lock_vendor'] ?? ''); ?>" placeholder="Vendor">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="card_lock_details" rows="2" 
                                                  placeholder="Digital card lock details..."><?php echo htmlspecialchars($physical_data['card_lock_details'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- IV. Visitors Log Maintained -->
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">IV. Visitors Log Maintained:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="visitors_log_status" id="visitorsYes" value="Yes" <?php echo (isset($physical_data['visitors_log_status']) && $physical_data['visitors_log_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="visitorsYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="visitors_log_status" id="visitorsNo" value="No" <?php echo (isset($physical_data['visitors_log_status']) && $physical_data['visitors_log_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="visitorsNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="visitors_log_status" id="visitorsPartial" value="Partial" <?php echo (isset($physical_data['visitors_log_status']) && $physical_data['visitors_log_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="visitorsPartial">Partial</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="font-weight-bold">Reference:</label>
                                                <div class="text-primary">
                                                    <small>साइबर सुरक्षा नीति<br>पेज नं. १३ बुंदा ३३ को (ख) को ९</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <select class="form-control" name="visitors_log_type">
                                                    <option value="">Log Type</option>
                                                    <option value="Digital" <?php echo (isset($physical_data['visitors_log_type']) && $physical_data['visitors_log_type'] == 'Digital') ? 'selected' : ''; ?>>Digital/Electronic Log</option>
                                                    <option value="Paper" <?php echo (isset($physical_data['visitors_log_type']) && $physical_data['visitors_log_type'] == 'Paper') ? 'selected' : ''; ?>>Paper-Based Register</option>
                                                    <option value="Both" <?php echo (isset($physical_data['visitors_log_type']) && $physical_data['visitors_log_type'] == 'Both') ? 'selected' : ''; ?>>Both Digital and Paper</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="visitors_log_retention" value="<?php echo htmlspecialchars($physical_data['visitors_log_retention'] ?? ''); ?>" placeholder="Log Retention Period (e.g., 1 year)">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="visitors_log_details" rows="2" 
                                                  placeholder="Visitor log management details..."><?php echo htmlspecialchars($physical_data['visitors_log_details'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- B. Fire Control System -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    B. Fire Control System:
                                </label>
                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="fire_control_status" id="fireYes" value="Yes" <?php echo (isset($physical_data['fire_control_status']) && $physical_data['fire_control_status'] == 'Yes') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="fireYes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="fire_control_status" id="fireNo" value="No" <?php echo (isset($physical_data['fire_control_status']) && $physical_data['fire_control_status'] == 'No') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="fireNo">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="fire_control_status" id="firePartial" value="Partial" <?php echo (isset($physical_data['fire_control_status']) && $physical_data['fire_control_status'] == 'Partial') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="firePartial">Partially</label>
                                        </div>
                                    </div>

                                    <!-- Reference Information -->
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <!-- I. Fire Alarm System -->
                                            <div class="p-3 bg-light rounded mb-3">
                                                <label class="font-weight-bold">I. Fire Alarm System:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="fire_alarm_status" id="fireAlarmYes" value="Yes" <?php echo (isset($physical_data['fire_alarm_status']) && $physical_data['fire_alarm_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="fireAlarmYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="fire_alarm_status" id="fireAlarmNo" value="No" <?php echo (isset($physical_data['fire_alarm_status']) && $physical_data['fire_alarm_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="fireAlarmNo">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="fire_alarm_status" id="fireAlarmNA" value="N/A" <?php echo (isset($physical_data['fire_alarm_status']) && $physical_data['fire_alarm_status'] == 'N/A') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="fireAlarmNA">N/A</label>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="fire_alarm_type" value="<?php echo htmlspecialchars($physical_data['fire_alarm_type'] ?? ''); ?>" placeholder="Alarm System Type/Model">
                                            </div>

                                            <!-- II. Type of Fire control System -->
                                            <div class="p-3 bg-light rounded mb-3">
                                                <label class="font-weight-bold">II. Type of Fire control System:</label>
                                                <select class="form-control mb-2" name="fire_control_type">
                                                    <option value="">Select Fire Control Type</option>
                                                    <option value="Gas Based" <?php echo (isset($physical_data['fire_control_type']) && $physical_data['fire_control_type'] == 'Gas Based') ? 'selected' : ''; ?>>Gas Based Suppression (FM200, Novec, CO2)</option>
                                                    <option value="Water Based" <?php echo (isset($physical_data['fire_control_type']) && $physical_data['fire_control_type'] == 'Water Based') ? 'selected' : ''; ?>>Water Based Sprinkler System</option>
                                                    <option value="Foam Based" <?php echo (isset($physical_data['fire_control_type']) && $physical_data['fire_control_type'] == 'Foam Based') ? 'selected' : ''; ?>>Foam Based System</option>
                                                    <option value="Powder Based" <?php echo (isset($physical_data['fire_control_type']) && $physical_data['fire_control_type'] == 'Powder Based') ? 'selected' : ''; ?>>Powder Based System</option>
                                                    <option value="Hybrid" <?php echo (isset($physical_data['fire_control_type']) && $physical_data['fire_control_type'] == 'Hybrid') ? 'selected' : ''; ?>>Hybrid System</option>
                                                </select>
                                                <textarea class="form-control" name="fire_control_details" rows="2" 
                                                          placeholder="Additional fire control system details..."><?php echo htmlspecialchars($physical_data['fire_control_details'] ?? ''); ?></textarea>
                                            </div>

                                            <!-- III. Date of installation & IV. Date of Expiry -->
                                            <div class="p-3 bg-light rounded">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-weight-bold">III. Date of installation:</label>
                                                        <input type="date" class="form-control" name="fire_installation_date" value="<?php echo htmlspecialchars($physical_data['fire_installation_date'] ?? ''); ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-weight-bold">IV. Date of Expiry:</label>
                                                        <input type="date" class="form-control" name="fire_expiry_date" value="<?php echo htmlspecialchars($physical_data['fire_expiry_date'] ?? ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-3 bg-light rounded">
                                                <label class="font-weight-bold">Reference:</label>
                                                <div class="text-primary">
                                                    <small>Digitization प्रयोग निर्देशिका<br>पेज नं. १२ बुंदा ३२ को (ख)</small>
                                                </div>
                                                <input type="text" class="form-control mt-2" name="fire_control_ref" value="<?php echo htmlspecialchars($physical_data['fire_control_ref'] ?? ''); ?>" placeholder="Additional reference">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- C. Server Maintained in Rack -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    C. Server Maintained in Rack:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="server_rack_status" id="rackYes" value="Yes" <?php echo (isset($physical_data['server_rack_status']) && $physical_data['server_rack_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="rackYes">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="server_rack_status" id="rackNo" value="No" <?php echo (isset($physical_data['server_rack_status']) && $physical_data['server_rack_status'] == 'No') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="rackNo">No</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="server_rack_status" id="rackPartial" value="Partial" <?php echo (isset($physical_data['server_rack_status']) && $physical_data['server_rack_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="rackPartial">Partially</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="rack_type" value="<?php echo htmlspecialchars($physical_data['rack_type'] ?? ''); ?>" placeholder="Rack Type/Size (e.g., 42U Standard Rack)">
                                        </div>
                                    </div>
                                    <textarea class="form-control mt-2" name="rack_details" rows="2" 
                                              placeholder="Rack organization and maintenance details..."><?php echo htmlspecialchars($physical_data['rack_details'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- D. Air-conditioning System -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    D. Air-conditioning System:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="ac_status" id="acYes" value="Yes" <?php echo (isset($physical_data['ac_status']) && $physical_data['ac_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="acYes">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="ac_status" id="acNo" value="No" <?php echo (isset($physical_data['ac_status']) && $physical_data['ac_status'] == 'No') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="acNo">No</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="ac_type" value="<?php echo htmlspecialchars($physical_data['ac_type'] ?? ''); ?>" placeholder="AC Type (e.g., Precision AC)">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="ac_capacity" value="<?php echo htmlspecialchars($physical_data['ac_capacity'] ?? ''); ?>" placeholder="Capacity (e.g., 5 Ton)">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="ac_temperature" value="<?php echo htmlspecialchars($physical_data['ac_temperature'] ?? ''); ?>" placeholder="Maintained Temperature (e.g., 22°C)">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="ac_backup" value="<?php echo htmlspecialchars($physical_data['ac_backup'] ?? ''); ?>" placeholder="Backup AC Available?">
                                        </div>
                                    </div>
                                    <textarea class="form-control mt-2" name="ac_details" rows="2" 
                                              placeholder="Additional AC system details..."><?php echo htmlspecialchars($physical_data['ac_details'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- E. Humidity Control Systems -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    E. Humidity Control Systems:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="humidity_status" id="humidityYes" value="Yes" <?php echo (isset($physical_data['humidity_status']) && $physical_data['humidity_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="humidityYes">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="humidity_status" id="humidityNo" value="No" <?php echo (isset($physical_data['humidity_status']) && $physical_data['humidity_status'] == 'No') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="humidityNo">No</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="humidity_range" value="<?php echo htmlspecialchars($physical_data['humidity_range'] ?? ''); ?>" placeholder="Humidity Range (e.g., 40-60%)">
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control" name="humidity_control_type">
                                                <option value="">Control Type</option>
                                                <option value="Integrated" <?php echo (isset($physical_data['humidity_control_type']) && $physical_data['humidity_control_type'] == 'Integrated') ? 'selected' : ''; ?>>Integrated with AC</option>
                                                <option value="Standalone" <?php echo (isset($physical_data['humidity_control_type']) && $physical_data['humidity_control_type'] == 'Standalone') ? 'selected' : ''; ?>>Standalone Humidifier/Dehumidifier</option>
                                                <option value="Both" <?php echo (isset($physical_data['humidity_control_type']) && $physical_data['humidity_control_type'] == 'Both') ? 'selected' : ''; ?>>Both</option>
                                            </select>
                                        </div>
                                    </div>
                                    <textarea class="form-control mt-2" name="humidity_details" rows="2" 
                                              placeholder="Humidity control details..."><?php echo htmlspecialchars($physical_data['humidity_details'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- F. Redundant Electrical Power Supply -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    F. Redundant Electrical Power Supply:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="redundant_power_status" id="powerYes" value="Yes" <?php echo (isset($physical_data['redundant_power_status']) && $physical_data['redundant_power_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="powerYes">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="redundant_power_status" id="powerNo" value="No" <?php echo (isset($physical_data['redundant_power_status']) && $physical_data['redundant_power_status'] == 'No') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="powerNo">No</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="redundant_power_status" id="powerPartial" value="Partial" <?php echo (isset($physical_data['redundant_power_status']) && $physical_data['redundant_power_status'] == 'Partial') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="powerPartial">Partial</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="power_ups" id="powerUPS" value="Yes" <?php echo (isset($physical_data['power_ups']) && $physical_data['power_ups'] == 'Yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="powerUPS">UPS System</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="power_generator" id="powerGenerator" value="Yes" <?php echo (isset($physical_data['power_generator']) && $physical_data['power_generator'] == 'Yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="powerGenerator">Generator</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="power_dual" id="powerDual" value="Yes" <?php echo (isset($physical_data['power_dual']) && $physical_data['power_dual'] == 'Yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="powerDual">Dual Power Feed</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="power_ups_capacity" value="<?php echo htmlspecialchars($physical_data['power_ups_capacity'] ?? ''); ?>" placeholder="UPS Capacity (e.g., 20kVA)">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="power_generator_capacity" value="<?php echo htmlspecialchars($physical_data['power_generator_capacity'] ?? ''); ?>" placeholder="Generator Capacity (e.g., 50kVA)">
                                        </div>
                                    </div>
                                    <textarea class="form-control mt-2" name="power_details" rows="2" 
                                              placeholder="Power redundancy details..."><?php echo htmlspecialchars($physical_data['power_details'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- G. CCTV -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    G. CCTV:
                                </label>
                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="cctv_status" id="cctvYes" value="Yes" <?php echo (isset($physical_data['cctv_status']) && $physical_data['cctv_status'] == 'Yes') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="cctvYes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="cctv_status" id="cctvNo" value="No" <?php echo (isset($physical_data['cctv_status']) && $physical_data['cctv_status'] == 'No') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="cctvNo">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="cctv_status" id="cctvPartial" value="Partial" <?php echo (isset($physical_data['cctv_status']) && $physical_data['cctv_status'] == 'Partial') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="cctvPartial">Partial</label>
                                        </div>
                                    </div>

                                    <!-- I. Inside the Server Room -->
                                    <div class="p-3 bg-light rounded mb-3">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">I. Inside the Server Room:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="cctv_inside_status" id="cctvInsideYes" value="Yes" <?php echo (isset($physical_data['cctv_inside_status']) && $physical_data['cctv_inside_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="cctvInsideYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="cctv_inside_status" id="cctvInsideNo" value="No" <?php echo (isset($physical_data['cctv_inside_status']) && $physical_data['cctv_inside_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="cctvInsideNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="cctv_inside_count" value="<?php echo htmlspecialchars($physical_data['cctv_inside_count'] ?? ''); ?>" placeholder="Number of cameras">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- II. Outside the server room -->
                                    <div class="p-3 bg-light rounded mb-3">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">II. Outside the server room:</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="cctv_outside_status" id="cctvOutsideYes" value="Yes" <?php echo (isset($physical_data['cctv_outside_status']) && $physical_data['cctv_outside_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="cctvOutsideYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="cctv_outside_status" id="cctvOutsideNo" value="No" <?php echo (isset($physical_data['cctv_outside_status']) && $physical_data['cctv_outside_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="cctvOutsideNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="cctv_outside_count" value="<?php echo htmlspecialchars($physical_data['cctv_outside_count'] ?? ''); ?>" placeholder="Number of cameras">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- III. NOC -->
                                    <div class="p-3 bg-light rounded mb-3">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="font-weight-bold">III. NOC (Network Operations Center):</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="cctv_noc_status" id="cctvNocYes" value="Yes" <?php echo (isset($physical_data['cctv_noc_status']) && $physical_data['cctv_noc_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="cctvNocYes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" name="cctv_noc_status" id="cctvNocNo" value="No" <?php echo (isset($physical_data['cctv_noc_status']) && $physical_data['cctv_noc_status'] == 'No') ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="cctvNocNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="cctv_noc_count" value="<?php echo htmlspecialchars($physical_data['cctv_noc_count'] ?? ''); ?>" placeholder="Number of cameras">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- IV. Access & Monitoring -->
                                    <div class="p-3 bg-light rounded mb-3">
                                        <label class="font-weight-bold">IV. Access & Monitoring:</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select class="form-control" name="cctv_monitoring_type">
                                                    <option value="">Monitoring Type</option>
                                                    <option value="24x7" <?php echo (isset($physical_data['cctv_monitoring_type']) && $physical_data['cctv_monitoring_type'] == '24x7') ? 'selected' : ''; ?>>24x7 Monitored</option>
                                                    <option value="Business Hours" <?php echo (isset($physical_data['cctv_monitoring_type']) && $physical_data['cctv_monitoring_type'] == 'Business Hours') ? 'selected' : ''; ?>>Business Hours Only</option>
                                                    <option value="Recording Only" <?php echo (isset($physical_data['cctv_monitoring_type']) && $physical_data['cctv_monitoring_type'] == 'Recording Only') ? 'selected' : ''; ?>>Recording Only, No Live Monitoring</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="cctv_retention" value="<?php echo htmlspecialchars($physical_data['cctv_retention'] ?? ''); ?>" placeholder="Footage Retention Period">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="cctv_access_control" value="<?php echo htmlspecialchars($physical_data['cctv_access_control'] ?? ''); ?>" placeholder="Who has access to CCTV?">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="cctv_storage" value="<?php echo htmlspecialchars($physical_data['cctv_storage'] ?? ''); ?>" placeholder="Storage Type/Capacity">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- V. CCTV Monitoring Locations -->
                                    <div class="p-3 bg-light rounded mb-3">
                                        <label class="font-weight-bold">V. CCTV Monitoring Locations:</label>
                                        <textarea class="form-control" name="cctv_locations" rows="3" 
                                                  placeholder="List all CCTV monitoring locations (e.g., Server Room Entrance, Rack Aisle, Data Center Floor, Building Perimeter, etc.)"><?php echo htmlspecialchars($physical_data['cctv_locations'] ?? ''); ?></textarea>
                                    </div>

                                    <!-- VI. If No explain -->
                                    <div class="p-3 bg-light rounded">
                                        <label class="font-weight-bold">VI. If No explain:</label>
                                        <textarea class="form-control" name="cctv_no_explain" rows="3" 
                                                  placeholder="If CCTV is not installed or partially installed, explain the reasons and any alternative measures..."><?php echo htmlspecialchars($physical_data['cctv_no_explain'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Physical Security Summary -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    Physical Security Summary:
                                </label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="physical_security_summary" rows="4" 
                                              placeholder="Overall physical and environmental security assessment..."><?php echo htmlspecialchars($physical_data['physical_security_summary'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label font-weight-bold text-primary">Remarks:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="remarks" rows="3" 
                                              placeholder="Any additional remarks or notes for physical and environmental security"><?php echo htmlspecialchars($physical_data['remarks'] ?? ''); ?></textarea>
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
                                        <button type="button" class="btn btn-info px-4 mr-2" onclick="window.location.href='step5.php'">
                                            <i class="fas fa-arrow-left mr-1"></i> Previous Step
                                        </button>
                                        <button type="submit" name="save_step6" class="btn btn-primary px-5">
                                            <i class="fas fa-save mr-2"></i> Save Physical Security
                                        </button>
                                        <button type="button" class="btn btn-info px-4 ml-2" onclick="window.location.href='step7.php'">
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