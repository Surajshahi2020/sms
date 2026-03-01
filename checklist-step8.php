<?php
include 'includes/authentication.php';
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'config/dbcon.php';

$step = 1;
$step_title = "Basic Information";
$step_icon = "fas fa-info-circle";
$step_color = "primary";
$step_colors = [
    1 => ['primary', '#4361ee'],
    2 => ['success', '#10b981'],
    3 => ['info', '#3b82f6'],
    4 => ['warning', '#f59e0b'],
    5 => ['danger', '#ef4444'],
    6 => ['secondary', '#6b7280'],
    7 => ['dark', '#1f2937'],
    8 => ['purple', '#8b5cf6']
];
?>

<style>
:root {
    --primary: #4361ee;
    --primary-light: #e0e7ff;
    --success: #10b981;
    --success-light: #d1fae5;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --danger: #ef4444;
    --danger-light: #fee2e2;
    --info: #3b82f6;
    --info-light: #dbeafe;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-600: #4b5563;
    --gray-800: #1f2937;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* Step Header */
.step-header-modern {
    background: white;
    border-radius: 20px;
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.step-header-left {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.step-badge-large {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    background: <?php echo $step_colors[$step-1][1]; ?>;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    box-shadow: 0 10px 20px -5px <?php echo $step_colors[$step-1][1]; ?>80;
}

.step-title-large h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0 0 0.25rem 0;
}

.step-title-large p {
    color: var(--gray-600);
    margin: 0;
    font-size: 0.95rem;
}

.step-progress-mini {
    background: var(--gray-100);
    padding: 0.75rem 1.5rem;
    border-radius: 40px;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.step-progress-mini .step-count {
    font-weight: 600;
    color: var(--gray-800);
}

.step-progress-mini .step-count span {
    color: <?php echo $step_colors[$step-1][1]; ?>;
    font-size: 1.2rem;
    margin-right: 0.25rem;
}

.step-progress-mini .progress {
    width: 100px;
    height: 6px;
    background: var(--gray-200);
    border-radius: 3px;
    overflow: hidden;
}

.step-progress-mini .progress-bar {
    height: 100%;
    background: <?php echo $step_colors[$step-1][1]; ?>;
    border-radius: 3px;
}

/* Modern Progress Tracker */
.progress-tracker-modern {
    background: white;
    border-radius: 60px;
    padding: 0.75rem;
    margin-bottom: 2rem;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.tracker-steps {
    display: flex;
    justify-content: space-between;
    position: relative;
}

.tracker-step {
    flex: 1;
    text-align: center;
    position: relative;
    z-index: 2;
}

.tracker-step:not(:last-child)::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 60%;
    width: 80%;
    height: 2px;
    background: var(--gray-200);
    z-index: 1;
}

.tracker-step.completed:not(:last-child)::before {
    background: <?php echo $step_colors[$step-1][1]; ?>;
}

.tracker-dot {
    width: 40px;
    height: 40px;
    background: var(--gray-100);
    border: 2px solid var(--gray-200);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-weight: 600;
    color: var(--gray-600);
    transition: all 0.3s;
    cursor: pointer;
}

.tracker-step.active .tracker-dot {
    background: <?php echo $step_colors[$step-1][1]; ?>;
    border-color: <?php echo $step_colors[$step-1][1]; ?>;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 0 0 4px <?php echo $step_colors[$step-1][1]; ?>20;
}

.tracker-step.completed .tracker-dot {
    background: <?php echo $step_colors[$step-1][1]; ?>;
    border-color: <?php echo $step_colors[$step-1][1]; ?>;
    color: white;
}

.tracker-label {
    font-size: 0.8rem;
    color: var(--gray-600);
    font-weight: 500;
}

.tracker-step.active .tracker-label {
    color: <?php echo $step_colors[$step-1][1]; ?>;
    font-weight: 600;
}

/* Modern Form Cards */
.form-card-modern {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-sm);
}

.form-card-header {
    padding: 1.25rem 1.5rem;
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.form-card-header i {
    font-size: 1.2rem;
    color: <?php echo $step_colors[$step-1][1]; ?>;
}

.form-card-header h5 {
    margin: 0;
    font-weight: 600;
    color: var(--gray-800);
}

.form-card-body {
    padding: 1.5rem;
}

/* Modern Form Elements */
.form-group-modern {
    margin-bottom: 1.5rem;
}

.form-group-modern label {
    display: block;
    font-weight: 500;
    color: var(--gray-800);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.input-group-modern {
    display: flex;
    align-items: center;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s;
}

.input-group-modern:focus-within {
    border-color: <?php echo $step_colors[$step-1][1]; ?>;
    box-shadow: 0 0 0 3px <?php echo $step_colors[$step-1][1]; ?>20;
}

.input-group-text-modern {
    padding: 0.75rem 1rem;
    background: var(--gray-50);
    color: var(--gray-600);
    border-right: 1px solid var(--gray-200);
}

.input-group-modern input,
.input-group-modern select,
.input-group-modern textarea {
    flex: 1;
    padding: 0.75rem 1rem;
    border: none;
    outline: none;
    font-size: 0.95rem;
}

/* Platform Options */
.platform-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}

.platform-card {
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
}

.platform-card:hover {
    border-color: <?php echo $step_colors[$step-1][1]; ?>;
    background: <?php echo $step_colors[$step-1][1]; ?>05;
}

.platform-card.active {
    border-color: <?php echo $step_colors[$step-1][1]; ?>;
    background: <?php echo $step_colors[$step-1][1]; ?>10;
}

.platform-card i {
    font-size: 1.5rem;
    color: <?php echo $step_colors[$step-1][1]; ?>;
    margin-bottom: 0.5rem;
}

.platform-card span {
    display: block;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--gray-800);
}

/* Service Tags */
.service-tags-modern {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.service-tag-modern {
    background: var(--gray-100);
    padding: 0.4rem 1rem;
    border-radius: 30px;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid var(--gray-200);
}

.service-tag-modern i {
    cursor: pointer;
    opacity: 0.6;
    transition: opacity 0.2s;
}

.service-tag-modern i:hover {
    opacity: 1;
    color: var(--danger);
}

/* Sidebar Cards */
.sidebar-card-modern {
    background: white;
    border-radius: 16px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.sidebar-card-header {
    padding: 1rem 1.25rem;
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sidebar-card-header i {
    color: <?php echo $step_colors[$step-1][1]; ?>;
}

.sidebar-card-header h6 {
    margin: 0;
    font-weight: 600;
    color: var(--gray-800);
}

.sidebar-card-body {
    padding: 1.25rem;
}

/* Reference Item */
.reference-item-modern {
    background: var(--gray-50);
    border-radius: 12px;
    padding: 1rem;
    border-left: 3px solid var(--info);
}

.reference-item-modern i {
    color: var(--info);
    font-size: 0.9rem;
    margin-right: 0.5rem;
}

.reference-item-modern p {
    margin: 0.5rem 0 0 0;
    font-size: 0.9rem;
    color: var(--gray-800);
}

.reference-item-modern small {
    color: var(--gray-600);
    font-size: 0.8rem;
}

/* Action Footer */
.action-footer-modern {
    background: white;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1.5rem;
}

.btn-modern {
    padding: 0.6rem 1.2rem;
    border-radius: 10px;
    font-weight: 500;
    border: 1px solid var(--gray-200);
    background: white;
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-modern:hover {
    border-color: <?php echo $step_colors[$step-1][1]; ?>;
    color: <?php echo $step_colors[$step-1][1]; ?>;
    background: <?php echo $step_colors[$step-1][1]; ?>05;
}

.btn-modern-primary {
    background: <?php echo $step_colors[$step-1][1]; ?>;
    border-color: <?php echo $step_colors[$step-1][1]; ?>;
    color: white;
}

.btn-modern-primary:hover {
    background: <?php echo $step_colors[$step-1][1]; ?>dd;
    border-color: <?php echo $step_colors[$step-1][1]; ?>dd;
    color: white;
}

/* Helper Text */
.helper-text {
    font-size: 0.8rem;
    color: var(--gray-600);
    margin-top: 0.25rem;
    display: block;
}

.required-star {
    color: var(--danger);
    margin-left: 0.25rem;
}

/* Notification */
.notification-modern {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 1rem 1.25rem;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    border-left: 4px solid;
    z-index: 9999;
    animation: slideIn 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 300px;
}

.notification-modern.success { border-left-color: var(--success); }
.notification-modern.info { border-left-color: <?php echo $step_colors[$step-1][1]; ?>; }
.notification-modern.warning { border-left-color: var(--warning); }

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .step-header-modern {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .step-header-left {
        width: 100%;
    }
    
    .tracker-label {
        font-size: 0.7rem;
    }
    
    .platform-grid {
        grid-template-columns: 1fr;
    }
    
    .action-footer-modern {
        flex-direction: column;
    }
    
    .action-footer-modern div {
        width: 100%;
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-modern {
        flex: 1;
        justify-content: center;
    }
}
</style>

<div class="content-wrapper" style="background: #f3f4f6; padding: 20px;">
    <!-- Modern Step Header -->
    <div class="step-header-modern">
        <div class="step-header-left">
            <div class="step-badge-large">
                <i class="<?php echo $step_icon; ?>"></i>
            </div>
            <div class="step-title-large">
                <h2>Step <?php echo $step; ?>: <?php echo $step_title; ?></h2>
                <p><?php echo $step === 1 ? 'Enter basic server details to begin the audit' : 'Configure step details'; ?></p>
            </div>
        </div>
        <div class="step-progress-mini">
            <span class="step-count"><span><?php echo $step; ?></span>/8</span>
            <div class="progress">
                <div class="progress-bar" style="width: <?php echo ($step/8)*100; ?>%"></div>
            </div>
        </div>
    </div>

    <!-- Modern Progress Tracker -->
    <div class="progress-tracker-modern">
        <div class="tracker-steps">
            <?php 
            $labels = ['Basic', 'OS', 'Data', 'Network', 'Policy', 'Physical', 'Backup', 'Admin'];
            for($i = 1; $i <= 8; $i++): 
                $is_active = $i == $step ? 'active' : '';
                $is_completed = $i < $step ? 'completed' : '';
            ?>
            <div class="tracker-step <?php echo $is_active; ?> <?php echo $is_completed; ?>" 
                 onclick="window.location.href='checklist-step<?php echo $i; ?>.php'">
                <div class="tracker-dot">
                    <?php if($i < $step): ?>
                        <i class="fas fa-check" style="font-size: 0.8rem;"></i>
                    <?php else: ?>
                        <?php echo $i; ?>
                    <?php endif; ?>
                </div>
                <span class="tracker-label"><?php echo $labels[$i-1]; ?></span>
            </div>
            <?php endfor; ?>
        </div>
    </div>

    <div class="row">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <!-- Server Information Card -->
            <div class="form-card-modern">
                <div class="form-card-header">
                    <i class="fas fa-server"></i>
                    <h5>Server Identification</h5>
                </div>
                <div class="form-card-body">
                    <!-- Server Name -->
                    <div class="form-group-modern">
                        <label>Server Name (FQDN) <span class="required-star">*</span></label>
                        <div class="input-group-modern">
                            <span class="input-group-text-modern">
                                <i class="fas fa-globe"></i>
                            </span>
                            <input type="text" id="serverName" name="server_name" 
                                   placeholder="e.g., server01.company.com.np">
                        </div>
                        <span class="helper-text">Fully Qualified Domain Name (e.g., server01.company.com.np)</span>
                    </div>

                    <!-- Server Platform -->
                    <div class="form-group-modern">
                        <label>Server Platform</label>
                        <div class="platform-grid">
                            <?php
                            $platforms = [
                                'physical' => ['Physical', 'fa-server'],
                                'virtual' => ['Virtual', 'fa-cloud'],
                                'cloud' => ['Cloud', 'fa-cloud-upload-alt']
                            ];
                            foreach($platforms as $key => $plat):
                            ?>
                            <div class="platform-card" data-platform="<?php echo $key; ?>">
                                <i class="fas <?php echo $plat[1]; ?>"></i>
                                <span><?php echo $plat[0]; ?></span>
                                <input type="radio" name="platform" value="<?php echo $key; ?>" class="d-none">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Server Purpose -->
                    <div class="form-group-modern">
                        <label>Server Purpose</label>
                        <div class="input-group-modern">
                            <span class="input-group-text-modern">
                                <i class="fas fa-bullseye"></i>
                            </span>
                            <select id="serverPurpose" name="purpose">
                                <option value="">Select purpose...</option>
                                <option value="web">Web Server</option>
                                <option value="database">Database Server</option>
                                <option value="file">File Server</option>
                                <option value="mail">Mail Server</option>
                                <option value="dns">DNS Server</option>
                                <option value="application">Application Server</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Custom Purpose (Hidden by default) -->
                    <div class="form-group-modern d-none" id="customPurposeContainer">
                        <label>Specify Purpose</label>
                        <div class="input-group-modern">
                            <span class="input-group-text-modern">
                                <i class="fas fa-pen"></i>
                            </span>
                            <input type="text" id="customPurpose" name="custom_purpose" 
                                   placeholder="Enter custom purpose">
                        </div>
                    </div>

                    <!-- Running Services -->
                    <div class="form-group-modern">
                        <label>Running Services</label>
                        <div class="service-tags-modern" id="serviceTags"></div>
                        <div class="input-group-modern">
                            <span class="input-group-text-modern">
                                <i class="fas fa-cogs"></i>
                            </span>
                            <input type="text" id="newService" placeholder="Add a service (e.g., Apache, MySQL)">
                            <button class="btn-modern" id="addService" style="border-radius: 0; border-left: 1px solid var(--gray-200);">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        <span class="helper-text">Press Add to include running services</span>
                    </div>
                </div>
            </div>

            <!-- Network Information Card -->
            <div class="form-card-modern">
                <div class="form-card-header">
                    <i class="fas fa-network-wired"></i>
                    <h5>Network Configuration</h5>
                </div>
                <div class="form-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label>Public IP Address</label>
                                <div class="input-group-modern">
                                    <span class="input-group-text-modern">
                                        <i class="fas fa-globe"></i>
                                    </span>
                                    <input type="text" id="publicIP" name="public_ip" 
                                           placeholder="e.g., 202.51.80.100">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label>Private IP Address</label>
                                <div class="input-group-modern">
                                    <span class="input-group-text-modern">
                                        <i class="fas fa-network-wired"></i>
                                    </span>
                                    <input type="text" id="privateIP" name="private_ip" 
                                           placeholder="e.g., 10.0.0.5">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label>MAC Address</label>
                                <div class="input-group-modern">
                                    <span class="input-group-text-modern">
                                        <i class="fas fa-ethernet"></i>
                                    </span>
                                    <input type="text" id="macAddress" name="mac_address" 
                                           placeholder="00:1A:2B:3C:4D:5E">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label>Server Priority</label>
                                <div class="input-group-modern">
                                    <span class="input-group-text-modern">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                    <select id="priority" name="priority">
                                        <option value="Critical">🔴 Critical</option>
                                        <option value="High">🟠 High</option>
                                        <option value="Medium" selected>🟡 Medium</option>
                                        <option value="Low">🟢 Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Policy Reference Card -->
            <div class="sidebar-card-modern">
                <div class="sidebar-card-header">
                    <i class="fas fa-book-open"></i>
                    <h6>Policy Reference</h6>
                </div>
                <div class="sidebar-card-body">
                    <div class="reference-item-modern">
                        <i class="fas fa-quote-right"></i>
                        <p>साइबर सुरक्षा नीति २०७५, पेज नं. १४, बुँदा ३४ को (ख)</p>
                        <small>Server identification and naming convention</small>
                    </div>
                    <div class="reference-item-modern mt-3">
                        <i class="fas fa-quote-right"></i>
                        <p>आईटी निर्देशिका २०८०, खण्ड ५.२</p>
                        <small>Network configuration standards</small>
                    </div>
                </div>
            </div>

            <!-- Progress Card -->
            <div class="sidebar-card-modern">
                <div class="sidebar-card-header">
                    <i class="fas fa-chart-pie"></i>
                    <h6>Step Progress</h6>
                </div>
                <div class="sidebar-card-body">
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                            <span style="font-size: 0.85rem;">Current Step</span>
                            <span style="font-weight: 600;"><?php echo $step; ?>/8</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" style="width: <?php echo ($step/8)*100; ?>%; background: <?php echo $step_colors[$step-1][1]; ?>"></div>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--gray-600);">
                        <span><i class="fas fa-check-circle text-success me-1"></i> Completed: <span id="completedCount">0</span></span>
                        <span><i class="fas fa-clock text-warning me-1"></i> Pending: <span id="pendingCount">8</span></span>
                    </div>
                </div>
            </div>

            <!-- Auto-Save Card -->
            <div class="sidebar-card-modern">
                <div class="sidebar-card-body">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: <?php echo $step_colors[$step-1][1]; ?>20; display: flex; align-items: center; justify-content: center; color: <?php echo $step_colors[$step-1][1]; ?>;">
                            <i class="fas fa-save"></i>
                        </div>
                        <div>
                            <h6 style="margin: 0; font-weight: 600;">Auto-Save Enabled</h6>
                            <small class="text-muted">Your progress saves automatically</small>
                        </div>
                    </div>
                    <button class="btn-modern btn-modern-primary w-100" onclick="saveStepProgress(<?php echo $step; ?>)">
                        <i class="fas fa-save me-2"></i>Save Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Footer -->
    <div class="action-footer-modern">
        <button class="btn-modern" onclick="window.location.href='server-checklist.php'">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </button>
        <div>
            <button class="btn-modern me-2" onclick="saveStepProgress(<?php echo $step; ?>)">
                <i class="fas fa-save me-2"></i>Save Progress
            </button>
            <button class="btn-modern btn-modern-primary" onclick="window.location.href='checklist-step<?php echo $step+1; ?>.php'">
                Next Step <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </div>
    </div>
</div>

<!-- Notification Container -->
<div id="notificationContainer"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadStepProgress(<?php echo $step; ?>);
    updateProgressStats();
    
    // Platform selection
    const platformCards = document.querySelectorAll('.platform-card');
    platformCards.forEach(card => {
        card.addEventListener('click', function() {
            platformCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
            autoSave();
        });
    });

    // Purpose dropdown
    const purposeSelect = document.getElementById('serverPurpose');
    const customContainer = document.getElementById('customPurposeContainer');
    
    purposeSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            customContainer.classList.remove('d-none');
        } else {
            customContainer.classList.add('d-none');
        }
        autoSave();
    });

    // Service tags
    const serviceTags = document.getElementById('serviceTags');
    const newService = document.getElementById('newService');
    const addService = document.getElementById('addService');

    addService.addEventListener('click', function() {
        const service = newService.value.trim();
        if (service) {
            addServiceTag(service);
            newService.value = '';
            autoSave();
        }
    });

    newService.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addService.click();
        }
    });

    // Auto-save on input change
    document.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('change', autoSave);
        field.addEventListener('keyup', debounce(autoSave, 1000));
    });
});

function addServiceTag(service) {
    const tag = document.createElement('span');
    tag.className = 'service-tag-modern';
    tag.innerHTML = service + ' <i class="fas fa-times"></i>';
    tag.querySelector('i').addEventListener('click', function() {
        tag.remove();
        autoSave();
    });
    document.getElementById('serviceTags').appendChild(tag);
}

function saveStepProgress(step) {
    const formData = {};
    
    document.querySelectorAll('input, select, textarea').forEach(field => {
        if (field.type === 'radio') {
            if (field.checked) {
                formData[field.name || field.id] = field.value;
            }
        } else if (field.type === 'checkbox') {
            formData[field.name || field.id] = field.checked;
        } else {
            if (field.value) {
                formData[field.name || field.id] = field.value;
            }
        }
    });
    
    const services = [];
    document.querySelectorAll('.service-tag-modern').forEach(tag => {
        services.push(tag.textContent.replace('×', '').trim());
    });
    formData['services'] = services;
    
    let progress = JSON.parse(localStorage.getItem('checklistProgress')) || {};
    progress[`step${step}`] = formData;
    localStorage.setItem('checklistProgress', JSON.stringify(progress));
    
    updateProgressStats();
    showNotification('Progress saved successfully!', 'success');
}

function loadStepProgress(step) {
    let progress = JSON.parse(localStorage.getItem('checklistProgress')) || {};
    const stepData = progress[`step${step}`];
    
    if (stepData) {
        for (let key in stepData) {
            if (key === 'services' && Array.isArray(stepData[key])) {
                stepData[key].forEach(service => {
                    if (service) addServiceTag(service);
                });
                continue;
            }
            
            const field = document.getElementById(key) || document.querySelector(`[name="${key}"]`);
            if (field) {
                if (field.type === 'radio') {
                    if (field.value === stepData[key]) {
                        field.checked = true;
                        const platformCard = field.closest('.platform-card');
                        if (platformCard) {
                            document.querySelectorAll('.platform-card').forEach(c => c.classList.remove('active'));
                            platformCard.classList.add('active');
                        }
                    }
                } else if (field.tagName === 'SELECT') {
                    field.value = stepData[key];
                    if (key === 'purpose' && stepData[key] === 'other') {
                        document.getElementById('customPurposeContainer').classList.remove('d-none');
                    }
                } else {
                    field.value = stepData[key];
                }
            }
        }
    }
}

function updateProgressStats() {
    let progress = JSON.parse(localStorage.getItem('checklistProgress')) || {};
    let completed = 0;
    
    for (let i = 1; i <= 8; i++) {
        if (progress[`step${i}`] && Object.keys(progress[`step${i}`]).length > 0) {
            completed++;
        }
    }
    
    document.getElementById('completedCount').textContent = completed;
    document.getElementById('pendingCount').textContent = 8 - completed;
}

function autoSave() {
    saveStepProgress(<?php echo $step; ?>);
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function showNotification(message, type) {
    const container = document.getElementById('notificationContainer');
    const notification = document.createElement('div');
    notification.className = `notification-modern ${type}`;
    notification.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'info' ? 'fa-info-circle' : 'fa-exclamation-circle'}"></i>
        <span>${message}</span>
        <i class="fas fa-times" style="margin-left: auto; cursor: pointer; opacity: 0.6;" onclick="this.parentElement.remove()"></i>
    `;
    
    container.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}
</script>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>