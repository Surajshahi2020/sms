<?php
// step1-basic.php
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
                        Step 1: Basic Information
                    </h2>
                    <p class="text-muted mt-1">Enter server basic information</p>
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
                    <form method="POST" action="save-step1.php" id="serverForm">
                        <!-- Server Block -->
                        <div class="server-block p-4 border rounded">
                            <!-- Server Name -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">A. Server Name (FQDN):</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="server_name" 
                                           placeholder="e.g., server01.company.com.np" required>
                                </div>
                            </div>

                            <!-- Server Platform -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">B. Server Platform:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="platform">
                                        <option value="">Select Platform</option>
                                        <option value="Physical">Physical</option>
                                        <option value="Virtual">Virtual</option>
                                        <option value="Logical">Logical</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Purpose -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">C. Purpose:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="purpose" 
                                           placeholder="e.g., Web Server, Database Server">
                                </div>
                            </div>

                            <!-- Running Services -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">D. Running Services:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="services" rows="3" 
                                              placeholder="List all running services (one per line)&#10;e.g.:&#10;Apache&#10;MySQL&#10;PHP&#10;FTP"></textarea>
                                </div>
                            </div>

                            <!-- Public IP -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">E1. Public IP:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="public_ip" 
                                           placeholder="e.g., 202.51.80.100">
                                </div>
                            </div>

                            <!-- Private IP -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">E2. Private IP:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="private_ip" 
                                           placeholder="e.g., 10.0.0.5">
                                </div>
                            </div>

                            <!-- MAC Address -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">F. MAC Address:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="mac_address" 
                                           placeholder="e.g., 00:1A:2B:3C:4D:5E">
                                </div>
                            </div>

                            <!-- Priority -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">G. Server Priority:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="priority">
                                        <option value="">Select Priority</option>
                                        <option value="Critical">Critical</option>
                                        <option value="High">High</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label font-weight-bold text-primary">Remarks:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="remarks" rows="2" 
                                              placeholder="Any remarks or notes for this server"></textarea>
                                </div>
                            </div>

                            <!-- Reference -->
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <small class="text-primary">
                                        <i class="fas fa-book-open mr-1"></i>
                                        Reference: साइबर सुरक्षा नीति, पेज १४, बुँदा ३४(ख)
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
                                        <button type="submit" name="save_step1" class="btn btn-primary px-5">
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
    margin-bottom: 1rem;
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
    border-color: #4361ee !important;
}

.form-control, .form-select {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.form-control:focus, .form-select:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

textarea.form-control {
    resize: vertical;
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
        margin-bottom: 1.5rem;
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