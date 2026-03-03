<?php
// step9-network-security.php
include 'includes/authentication.php';
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'config/dbcon.php';

// Fetch existing data if editing
$server_id = isset($_GET['server_id']) ? $_GET['server_id'] : 0;
$network_data = [];
if ($server_id) {
    $query = "SELECT * FROM network_security WHERE server_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $server_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $network_data = $result->fetch_assoc();
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
                        4. Network Security
                    </h2>
                    <p class="text-muted mt-1">Firewall and network security configuration</p>
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
                    <form method="POST" action="save-step9.php" id="networkSecurityForm">
                        <input type="hidden" name="server_id" value="<?php echo $server_id; ?>">
                        
                        <!-- Network Security Section -->
                        <div class="security-block p-4 border rounded">
                            
                            <!-- A. Firewall -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    A. Firewall:
                                </label>
                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="firewall_status" id="firewallYes" value="Yes" <?php echo (isset($network_data['firewall_status']) && $network_data['firewall_status'] == 'Yes') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="firewallYes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="firewall_status" id="firewallNo" value="No" <?php echo (isset($network_data['firewall_status']) && $network_data['firewall_status'] == 'No') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="firewallNo">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="firewall_status" id="firewallNA" value="N/A" <?php echo (isset($network_data['firewall_status']) && $network_data['firewall_status'] == 'N/A') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="firewallNA">N/A</label>
                                        </div>
                                    </div>
                                    
                                    <!-- Firewall Details -->
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <label class="font-weight-bold">Firewall Details:</label>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <input type="text" class="form-control" name="firewall_vendor" value="<?php echo htmlspecialchars($network_data['firewall_vendor'] ?? ''); ?>" placeholder="Vendor (e.g., Cisco, Fortinet, Palo Alto)">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <input type="text" class="form-control" name="firewall_model" value="<?php echo htmlspecialchars($network_data['firewall_model'] ?? ''); ?>" placeholder="Model/Version">
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="firewall_detail" rows="2" 
                                                  placeholder="Additional firewall configuration details..."><?php echo htmlspecialchars($network_data['firewall_detail'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- B. Firewall architecture -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    B. Firewall architecture:
                                </label>
                                <div class="col-sm-9">
                                    
                                    <!-- B1. Multivendor -->
                                    <div class="mb-4 p-3 bg-light rounded">
                                        <label class="font-weight-bold">B1. Multivendor:</label>
                                        <div class="mb-2">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="multivendor_status" id="multiYes" value="Yes" <?php echo (isset($network_data['multivendor_status']) && $network_data['multivendor_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="multiYes">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="multivendor_status" id="multiNo" value="No" <?php echo (isset($network_data['multivendor_status']) && $network_data['multivendor_status'] == 'No') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="multiNo">No</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="multivendor_status" id="multiNA" value="N/A" <?php echo (isset($network_data['multivendor_status']) && $network_data['multivendor_status'] == 'N/A') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="multiNA">N/A</label>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="multivendor_detail" value="<?php echo htmlspecialchars($network_data['multivendor_detail'] ?? ''); ?>" placeholder="Vendor names (if multivendor)">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- B2. Cascaded -->
                                    <div class="mb-2 p-3 bg-light rounded">
                                        <label class="font-weight-bold">B2. Cascaded:</label>
                                        <div class="mb-2">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="cascaded_status" id="cascYes" value="Yes" <?php echo (isset($network_data['cascaded_status']) && $network_data['cascaded_status'] == 'Yes') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="cascYes">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="cascaded_status" id="cascNo" value="No" <?php echo (isset($network_data['cascaded_status']) && $network_data['cascaded_status'] == 'No') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="cascNo">No</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="cascaded_status" id="cascNA" value="N/A" <?php echo (isset($network_data['cascaded_status']) && $network_data['cascaded_status'] == 'N/A') ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="cascNA">N/A</label>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select class="form-control" name="cascaded_type">
                                                    <option value="">Select Cascade Type</option>
                                                    <option value="Active-Passive" <?php echo (isset($network_data['cascaded_type']) && $network_data['cascaded_type'] == 'Active-Passive') ? 'selected' : ''; ?>>Active-Passive</option>
                                                    <option value="Active-Active" <?php echo (isset($network_data['cascaded_type']) && $network_data['cascaded_type'] == 'Active-Active') ? 'selected' : ''; ?>>Active-Active</option>
                                                    <option value="Load Balanced" <?php echo (isset($network_data['cascaded_type']) && $network_data['cascaded_type'] == 'Load Balanced') ? 'selected' : ''; ?>>Load Balanced</option>
                                                </select>
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="cascaded_detail" rows="2" 
                                                  placeholder="Cascaded configuration details..."><?php echo htmlspecialchars($network_data['cascaded_detail'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- C. Types of Firewall -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    C. Types of Firewall:
                                </label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="firewall_type_packet" id="typePacket" value="Packet Filtering" <?php echo (isset($network_data['firewall_types']) && strpos($network_data['firewall_types'], 'Packet Filtering') !== false) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="typePacket">Packet Filtering</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="firewall_type_stateful" id="typeStateful" value="Stateful Inspection" <?php echo (isset($network_data['firewall_types']) && strpos($network_data['firewall_types'], 'Stateful Inspection') !== false) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="typeStateful">Stateful Inspection</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="firewall_type_application" id="typeApp" value="Application Layer" <?php echo (isset($network_data['firewall_types']) && strpos($network_data['firewall_types'], 'Application Layer') !== false) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="typeApp">Application Layer</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="firewall_type_ngfw" id="typeNGFW" value="Next-Generation" <?php echo (isset($network_data['firewall_types']) && strpos($network_data['firewall_types'], 'Next-Generation') !== false) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="typeNGFW">Next-Generation (NGFW)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="firewall_type_waf" id="typeWAF" value="WAF" <?php echo (isset($network_data['firewall_types']) && strpos($network_data['firewall_types'], 'WAF') !== false) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="typeWAF">Web Application (WAF)</label>
                                            </div>
                                        </div>
                                    </div>
                                    <textarea class="form-control mt-2" name="firewall_types_detail" rows="2" 
                                              placeholder="Additional firewall type details..."><?php echo htmlspecialchars($network_data['firewall_types_detail'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- D. IPS -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    D. IPS (Intrusion Prevention System):
                                </label>
                                <div class="col-sm-9">
                                    <div class="mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="ips_status" id="ipsYes" value="Yes" <?php echo (isset($network_data['ips_status']) && $network_data['ips_status'] == 'Yes') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="ipsYes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="ips_status" id="ipsNo" value="No" <?php echo (isset($network_data['ips_status']) && $network_data['ips_status'] == 'No') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="ipsNo">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="ips_status" id="ipsNA" value="N/A" <?php echo (isset($network_data['ips_status']) && $network_data['ips_status'] == 'N/A') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="ipsNA">N/A</label>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <label class="font-weight-bold">IPS Configuration:</label>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <input type="text" class="form-control" name="ips_vendor" value="<?php echo htmlspecialchars($network_data['ips_vendor'] ?? ''); ?>" placeholder="IPS Vendor">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <select class="form-control" name="ips_mode">
                                                    <option value="">Select Mode</option>
                                                    <option value="Inline" <?php echo (isset($network_data['ips_mode']) && $network_data['ips_mode'] == 'Inline') ? 'selected' : ''; ?>>Inline Mode</option>
                                                    <option value="Passive" <?php echo (isset($network_data['ips_mode']) && $network_data['ips_mode'] == 'Passive') ? 'selected' : ''; ?>>Passive Mode</option>
                                                    <option value="Tap" <?php echo (isset($network_data['ips_mode']) && $network_data['ips_mode'] == 'Tap') ? 'selected' : ''; ?>>Tap Mode</option>
                                                </select>
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="ips_detail" rows="2" 
                                                  placeholder="IPS rules and configuration details..."><?php echo htmlspecialchars($network_data['ips_detail'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- E. IDS -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    E. IDS (Intrusion Detection System):
                                </label>
                                <div class="col-sm-9">
                                    <div class="mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="ids_status" id="idsYes" value="Yes" <?php echo (isset($network_data['ids_status']) && $network_data['ids_status'] == 'Yes') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="idsYes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="ids_status" id="idsNo" value="No" <?php echo (isset($network_data['ids_status']) && $network_data['ids_status'] == 'No') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="idsNo">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="ids_status" id="idsNA" value="N/A" <?php echo (isset($network_data['ids_status']) && $network_data['ids_status'] == 'N/A') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="idsNA">N/A</label>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <label class="font-weight-bold">IDS Configuration:</label>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <input type="text" class="form-control" name="ids_vendor" value="<?php echo htmlspecialchars($network_data['ids_vendor'] ?? ''); ?>" placeholder="IDS Vendor">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <select class="form-control" name="ids_type">
                                                    <option value="">Select IDS Type</option>
                                                    <option value="NIDS" <?php echo (isset($network_data['ids_type']) && $network_data['ids_type'] == 'NIDS') ? 'selected' : ''; ?>>Network-based (NIDS)</option>
                                                    <option value="HIDS" <?php echo (isset($network_data['ids_type']) && $network_data['ids_type'] == 'HIDS') ? 'selected' : ''; ?>>Host-based (HIDS)</option>
                                                    <option value="Both" <?php echo (isset($network_data['ids_type']) && $network_data['ids_type'] == 'Both') ? 'selected' : ''; ?>>Both NIDS and HIDS</option>
                                                </select>
                                            </div>
                                        </div>
                                        <textarea class="form-control mt-2" name="ids_detail" rows="2" 
                                                  placeholder="IDS rules and monitoring details..."><?php echo htmlspecialchars($network_data['ids_detail'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- F. IP Table -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    F. IP Table:
                                </label>
                                <div class="col-sm-9">
                                    <div class="mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="iptable_status" id="iptableYes" value="Yes" <?php echo (isset($network_data['iptable_status']) && $network_data['iptable_status'] == 'Yes') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="iptableYes">Configured</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="iptable_status" id="iptableNo" value="No" <?php echo (isset($network_data['iptable_status']) && $network_data['iptable_status'] == 'No') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="iptableNo">Not Configured</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="iptable_status" id="iptableNA" value="N/A" <?php echo (isset($network_data['iptable_status']) && $network_data['iptable_status'] == 'N/A') ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="iptableNA">N/A</label>
                                        </div>
                                    </div>
                                    
                                    <!-- IP Table Rules -->
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <label class="font-weight-bold">IP Table Rules:</label>
                                        
                                        <!-- Input Chain -->
                                        <div class="mb-3">
                                            <label class="font-weight-bold">INPUT Chain:</label>
                                            <textarea class="form-control mb-2" name="iptable_input" rows="2" 
                                                      placeholder="INPUT chain rules..."><?php echo htmlspecialchars($network_data['iptable_input'] ?? ''); ?></textarea>
                                        </div>
                                        
                                        <!-- Output Chain -->
                                        <div class="mb-3">
                                            <label class="font-weight-bold">OUTPUT Chain:</label>
                                            <textarea class="form-control mb-2" name="iptable_output" rows="2" 
                                                      placeholder="OUTPUT chain rules..."><?php echo htmlspecialchars($network_data['iptable_output'] ?? ''); ?></textarea>
                                        </div>
                                        
                                        <!-- Forward Chain -->
                                        <div class="mb-2">
                                            <label class="font-weight-bold">FORWARD Chain:</label>
                                            <textarea class="form-control" name="iptable_forward" rows="2" 
                                                      placeholder="FORWARD chain rules..."><?php echo htmlspecialchars($network_data['iptable_forward'] ?? ''); ?></textarea>
                                        </div>
                                        
                                        <textarea class="form-control mt-3" name="iptable_detail" rows="2" 
                                                  placeholder="Additional IP table configuration..."><?php echo htmlspecialchars($network_data['iptable_detail'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Network Security Summary -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    Network Security Summary:
                                </label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="network_security_summary" rows="4" 
                                              placeholder="Provide overall network security configuration summary..."><?php echo htmlspecialchars($network_data['network_security_summary'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- Reference & Remarks Section -->
                            <div class="form-group row mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold text-primary">
                                    Reference:
                                </label>
                                <div class="col-sm-9">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-primary d-block mb-2">
                                            <i class="fas fa-book-open mr-1"></i>
                                            <strong>साइबर सुरक्षा नीति, २०७८ (Cyber Security Policy, 2078)</strong>
                                        </small>
                                        <small class="text-muted d-block">
                                            परिच्छेद ६: सञ्जाल सुरक्षा (Chapter 6: Network Security) - पृष्ठ २८-३२<br>
                                            बुँदा ४५: फायरवाल प्रबन्ध (Firewall Management)<br>
                                            बुँदा ४६: प्रवेश नियन्त्रण प्रणाली (Intrusion Prevention/Detection Systems)
                                        </small>
                                        <input type="text" class="form-control mt-2" name="network_ref" value="<?php echo htmlspecialchars($network_data['network_ref'] ?? ''); ?>" placeholder="Additional reference if any">
                                    </div>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label font-weight-bold text-primary">Remarks:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="remarks" rows="3" 
                                              placeholder="Any additional remarks or notes for network security"><?php echo htmlspecialchars($network_data['remarks'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- Review Information -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Review Information:</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Next Review Date:</label>
                                            <input type="date" class="form-control" name="next_review_date" value="<?php echo htmlspecialchars($network_data['next_review_date'] ?? ''); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Reviewed By:</label>
                                            <input type="text" class="form-control" name="reviewer_name" value="<?php echo htmlspecialchars($network_data['reviewer_name'] ?? ''); ?>" placeholder="Reviewer name">
                                        </div>
                                    </div>
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
                                        <button type="button" class="btn btn-info px-4 mr-2" onclick="window.location.href='step3.php'">
                                            <i class="fas fa-arrow-left mr-1"></i> Previous Step
                                        </button>
                                        <button type="submit" name="save_step6" class="btn btn-primary px-5">
                                            <i class="fas fa-save mr-2"></i> Save Network Security
                                        </button>
                                        <button type="button" class="btn btn-info px-4 ml-2" onclick="window.location.href='step5.php'">
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