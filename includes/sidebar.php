<?php
include('config/dbcon.php');
include('supporter/permissions.php');
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index.php" class="brand-link">
    <img src="assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
    <span class="brand-text">SMS</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
       <div class="image">
        <?php
          $user_id = $_SESSION['auth_user']['user_id'];
          $query = "SELECT image FROM users WHERE id = '$user_id' LIMIT 1";
          $result = mysqli_query($con, $query);
          if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $img_path = (!empty($user['image']) && file_exists('profile/' . $user['image'])) ? 'profile/' . $user['image'] : 'assets/dist/img/user2-160x160.jpg';
          } else {
            $img_path = 'assets/dist/img/user2-160x160.jpg';
          }
        ?>
        <img src="<?php echo htmlspecialchars($img_path); ?>" alt="User Image" class="img-circle elevation-2">
       </div>
       <div class="info">
          <a href="#" class="d-block">
            <?php echo isset($_SESSION['auth']) ? $_SESSION['auth_user']['user_name'] : "Not Logged In"; ?>
          </a>
       </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="index.php" class="nav-link">
            <i class="bi bi-server"></i>
            <p>Server Audit</p>
          </a>
        </li>

        <?php if (is_super_admin()): ?>
          <li class="nav-item">
            <a href="notices.php" class="nav-link">
              <i class="bi bi-file-earmark-text-fill"></i>
              <p>View</p>
            </a>
          </li>
        <?php endif; ?>

        <?php if (is_super_admin()): ?>
          <li class="nav-header">Settings</li>
          <li class="nav-item">
              <a href="user_registration.php" class="nav-link">
                  <i class="bi bi-people-fill"></i>
                  <p>User Management</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="incident_accept.php" class="nav-link">
                  <i class="bi bi-check-circle-fill"></i>
                  <p>Server Management</p>
              </a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</aside>

<!-- Sidebar CSS -->
<style>
/* Sidebar General */
.main-sidebar {
    background: #1b1b1b;
    font-family: 'Poppins', sans-serif;
    border-right: 1px solid rgba(255,255,255,0.05);
}

/* Brand Logo */
.brand-link {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #851f61;
    padding: 12.5px 15px;
    transition: background 0.3s ease;
    text-decoration: none !important;
}
.brand-link:hover {
    background: #851f61;
}
.brand-text {
    font-weight: 700;
    font-size: 1.3rem;
    color: #fff !important;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* User Panel */
.user-panel {
    background: rgba(255,255,255,0.05);
    border-radius: 10px;
    padding: 5px 10px;
    margin-bottom: 15px;
    transition: background 0.3s ease;
}
.user-panel:hover {
    background: rgba(87,155,73,0.15);
}
.user-panel .image img {
    border-radius: 50%;
    width: 42px;
    height: 42px;
    object-fit: cover;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.user-panel .info a {
    color: #fff;
    font-weight: 600;
    font-size: 1rem;
    transition: color 0.3s ease;
    text-decoration: none;
    
}
/* .user-panel .info a:hover {
    color: #579b49;
} */

/* Sidebar Menu */
.nav-sidebar .nav-item {
    margin-bottom: 5px;
}
.nav-sidebar .nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    background: transparent;
    color: #cfd8dc;
    border-radius: 10px;
    padding: 10px 15px;
    transition: all 0.3s ease;
    text-decoration: none !important;
}
.nav-sidebar .nav-link:hover {
    background: rgba(87,155,73,0.2);
    color: #fff;
}
.nav-sidebar .nav-link i {
    font-size: 1.2rem;
    color: #e1e9df;
}
.nav-sidebar .nav-header {
    color: #6d74aa;
    font-weight: 700;
    font-size: 0.9rem;
    margin-top: 15px;
    margin-bottom: 5px;
    letter-spacing: 1px;
}

/* Active Link */
.nav-sidebar .nav-link.active {
    background: #d4e1d1;
    color: #fff;
}
.nav-sidebar .nav-link.active i {
    color: #fff;
}

/* Treeview submenu */
.nav-treeview {
    margin-left: 15px;
}
.nav-treeview .nav-link {
    font-size: 0.95rem;
    padding-left: 25px;
    border-radius: 8px;
}

/* Scrollbar Styling */
.sidebar::-webkit-scrollbar {
    width: 6px;
}
.sidebar::-webkit-scrollbar-thumb {
    background-color: rgba(87,155,73,0.5);
    border-radius: 3px;
}
.sidebar::-webkit-scrollbar-track {
    background: transparent;
}

/* Responsive tweaks */
@media (max-width: 768px) {
    .brand-text {
        font-size: 1.1rem;
    }
    .nav-sidebar .nav-link i {
        font-size: 1rem;
    }
}
</style>
