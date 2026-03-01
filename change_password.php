<?php
include 'includes/authentication.php';
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'config/dbcon.php';
?>

<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <!-- Breadcrumbs or title can go here -->
      </div>
    </div>
  </div>

  <!-- Change Password Form -->
  <section class="content">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12">
          <?php include 'message.php'; ?>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-key me-2"></i> पासवर्ड परिवर्तन</h3>
              <a href="javascript:history.back()" class="btn btn-danger btn-sm float-end">
                 पछाडि
              </a>
            </div>
            <div class="card-body">
              <form action="change_password_code.php" method="POST">
                <!-- Old Password -->
                <div class="mb-3">
                  <label for="old_password" class="form-label">हालको पासवर्ड <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password"
                           name="old_password"
                           id="old_password"
                           class="form-control"
                           placeholder=""
                           required>
                  </div>
                </div>

                <!-- New Password -->
                <div class="mb-3">
                  <label for="new_password" class="form-label">नयाँ पासवर्ड <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password"
                           name="new_password"
                           id="new_password"
                           class="form-control"
                           placeholder=""
                           required
                           minlength="4">
                  </div>
                  <div class="form-text text-muted">कम्तिमा ६ अक्षरको हुनुपर्छ</div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                  <label for="confirm_password" class="form-label">पासवर्ड पुष्टि गर्नुहोस् <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password"
                           name="confirm_password"
                           id="confirm_password"
                           class="form-control"
                           placeholder=""
                           required>
                  </div>
                </div>

                <div class="modal-footer" style="margin-top: 20px; padding-top: 10px;">
                        <button type="submit" name="changePassword" class="btn btn-info">अपडेट</button>
                    </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>

