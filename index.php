<?php
// === PHP Includes ===
include 'includes/authentication.php';
include 'config/dbcon.php';
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/topbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header - Simple & Clean -->
<div class="content-header" style="background: white; border-bottom: 2px solid #f1f5f9;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center py-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 45px; height: 45px; background: linear-gradient(135deg, #4361ee15, #8b5cf615);">
                        <i class="fas fa-clipboard-check" style="color: #4361ee; font-size: 1.3rem;"></i>
                    </div>
                    <div>
                        <h2 style="font-weight: 600; color: #1e293b; margin: 0; font-size: 1.6rem;">
                            Server Audit Steps
                        </h2>
                        <div class="d-flex align-items-center mt-1">
                            <span style="width: 6px; height: 6px; background: #4361ee; border-radius: 50%; display: inline-block; margin-right: 8px;"></span>
                            <p style="color: #64748b; margin: 0; font-size: 0.9rem;">
                                Click on any step to begin your audit
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php include 'message.php'; ?>

            <!-- Step Guide - Simple clickable steps -->
            <div class="card">
                <div class="card-body">
                    <div class="steps-container">
                        <?php
                        $steps = [
                            1 => ['Basic Information', 'Server details, IP, platform', 'fas fa-info-circle', '#4361ee', 'step1.php'],
                            2 => ['Operating System', 'OS type, patches, users', 'fab fa-windows', '#06d6a0', 'step2.php'],
                            3 => ['Data Security', 'Authorization, encryption', 'fas fa-database', '#7209b7', 'step3-data.php'],
                            4 => ['Network Security', 'Firewall, IPS, IDS', 'fas fa-network-wired', '#f72585', 'step4-network.php'],
                            5 => ['Policy & SOP', 'Documentation, response', 'fas fa-file-alt', '#fb8b24', 'step5-policy.php'],
                            6 => ['Physical Security', 'Access, CCTV, fire', 'fas fa-lock', '#9a8c98', 'step6-physical.php'],
                            7 => ['Backup & Recovery', 'Backup frequency, offsite', 'fas fa-cloud-upload-alt', '#e63946', 'step7-backup.php'],
                            8 => ['Administration', 'Admin policies, logs', 'fas fa-users-cog', '#2b9f8c', 'step8-admin.php']
                        ];
                        
                        foreach ($steps as $num => $step):
                        ?>
                        <a href="<?php echo $step[4]; ?>" class="step-card">
                            <div class="step-icon" style="background: <?php echo $step[3]; ?>">
                                <i class="<?php echo $step[2]; ?>"></i>
                            </div>
                            <div class="step-content">
                                <h4><?php echo $step[0]; ?></h4>
                                <p><?php echo $step[1]; ?></p>
                                <span class="step-number">Step <?php echo $num; ?>/8</span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.steps-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
}

.step-card {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
}

.step-card:hover {
    border-color: #4361ee;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
}

.step-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
}

.step-content h4 {
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
    color: #1e293b;
}

.step-content p {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0 0 0.25rem 0;
}

.step-number {
    font-size: 0.7rem;
    color: #4361ee;
    background: #e0e7ff;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    display: inline-block;
}

/* Responsive */
@media (max-width: 768px) {
    .steps-container {
        grid-template-columns: 1fr;
    }
    
    .step-card {
        padding: 0.75rem;
    }
    
    .step-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>