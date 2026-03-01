<?php
include __DIR__ . '/../config/dbcon.php';

$user_id = $_SESSION['auth_user']['user_id'] ?? null;
$role_as = 'प्रयोगकर्ता';
$username = '';

// Fetch user info
if ($user_id && isset($con)) {
    $stmt = $con->prepare("SELECT full_name_ne, role_as FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result_user = $stmt->get_result();
    if ($result_user && $result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $username = $user['full_name_ne'] ?? '';
        $role_as = match ((int)$user['role_as']) {
            1 => 'प्रशासन',
            2 => 'सुपर प्रशासन',
            default => 'प्रयोगकर्ता',
        };
    }
    $stmt->close();
}

// Fetch notifications
$stmt = $con->prepare("
    SELECT n.*, COALESCE(nr.is_read, 0) AS is_read
    FROM notifications n
    LEFT JOIN notification_reads nr 
        ON n.id = nr.notification_id AND nr.user_id = ?
    ORDER BY n.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notif_result = $stmt->get_result();
$unread_count = 0;
$notifications = [];

if ($notif_result->num_rows > 0) {
    while ($row = $notif_result->fetch_assoc()) {
        if ($row['is_read'] == 0) { // only unread
            $notifications[] = $row;
            $unread_count++;
        }
    }
}
$stmt->close();

// Play alert sound only on first login
$play_alert_sound = false;
if (!isset($_SESSION['alert_sound_played']) && $unread_count > 0) {
    $play_alert_sound = true;
    $_SESSION['alert_sound_played'] = true;
}
?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark shadow-sm navbar-custom">
  <ul class="navbar-nav align-items-center">
    <li class="nav-item"><a class="nav-link text-light" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
    <li class="nav-item ms-3"><span class="navbar-title">साइबर सुरक्षा निर्देशनालय</span></li>
  </ul>

  <ul class="navbar-nav ms-auto align-items-center">
    <!-- Notification Bell -->
    <li class="nav-item dropdown me-3 position-relative">
      <a class="nav-link position-relative" id="notificationBell" data-bs-toggle="dropdown" href="#">
        <i class="fas fa-bell fa-lg text-light"></i>
        <?php if($unread_count > 0): ?>
          <span class="badge bg-danger notification-badge" id="notifCount"><?= $unread_count ?></span>
        <?php endif; ?>
      </a>

      <!-- Notifications Dropdown -->
      <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
        <li class="dropdown-header text-center text-black fw-bold">🔔 नयाँ सूचना</li>
        <li><hr class="dropdown-divider"></li>
        <div class="notification-list px-2">
        <?php
        if (!empty($notifications)) {
            foreach ($notifications as $notif) {
                $title_text = strip_tags($notif['title']);
                $message_text = strip_tags($notif['message']);
                $type_text = htmlspecialchars($notif['type']);
                $time = date('d M Y, h:i A', strtotime($notif['created_at']));

                echo '<a class="dropdown-item notification-item unread" href="viewall_notifications.php" data-id="'.$notif['id'].'">
                        <div class="notif-header">
                          <strong class="notif-title">'.htmlspecialchars($title_text).'</strong>
                          <span class="notif-type">('.$type_text.')</span>
                        </div>
                        <div class="notif-message">'.htmlspecialchars($message_text).'</div>
                        <small class="text-muted notif-time">'.$time.'</small>
                    </a>';
            }
        } else {
            echo '<span class="dropdown-item text-center text-muted">नयाँ सूचना छैन</span>';
        }
        ?>
        </div>
        <li><hr class="dropdown-divider"></li>
        <li><a href="viewall_notifications.php" class="dropdown-item text-center text-warning fw-bold">👉 सबै हेर्नुहोस्</a></li>
      </ul>
    </li>

    <!-- User Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" data-bs-toggle="dropdown">
        <span class="username-text"><?= htmlspecialchars($role_as) ?></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-custom">
        <li><a class="dropdown-item" href="change_password.php"><i class="fas fa-key me-2"></i> पासवर्ड परिवर्तन गर्नुहोस्</a></li>
        <li><a class="dropdown-item" href="registration_code.php?logout=1"><i class="fas fa-sign-out-alt me-2"></i> लगआउट</a></li>
      </ul>
    </li>
  </ul>
</nav>

<!-- Styles -->
<style>
.navbar-custom { background-color: #851f61; position: sticky; top: 0; z-index: 1030; }
.navbar-title { font-size: 1.2rem; color: #fff; font-weight: 700; }
.notification-badge { position: absolute; top: 4px; right: 2px; font-size: 0.7rem; padding: 3px 6px; border-radius: 50%; }
.notification-dropdown { background-color: rgb(249, 249, 248); border-radius: 10px; min-width: 350px; max-height: 400px; overflow-y: auto; box-shadow: 0 6px 20px rgba(0,0,0,0.5); }
.notification-item { color: black; border-radius: 6px; margin-bottom: 4px; padding: 10px; transition: all 0.2s ease; word-wrap: break-word; white-space: normal; }
.notification-item.unread { background-color: rgba(255,255,255,0.1); }
.notification-item:hover { background-color: rgba(255,255,255,0.2); }
.notif-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; gap: 8px; }
.notif-title { font-weight: 600; color: black }
.notif-type { font-size: 0.85em; color: #ffd700!important; }
.notif-message { margin-bottom: 6px; color: black!important; }
.notif-time { display: block; font-size: 0.75em; color: #8bb43fff !important; font-weight: 500; }
.dropdown-menu.dropdown-custom { background-color: white; border: none; border-radius: 10px; }
.dropdown-menu .dropdown-item { color: black; font-weight: 500; transition: all 0.2s ease; }
.dropdown-menu .dropdown-item:hover { background-color: rgba(255,183,0,0.15); color: #FFB700; }
.username-text { color: #fff; font-weight: 600; }
</style>

<!-- Bootstrap & jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Alert sound and notifications -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const playSound = <?= $play_alert_sound ? 'true' : 'false' ?>;

    if (playSound) {
        const audio = new Audio('music/aa.wav'); // your alert sound
        audio.play().catch(err => console.log('Audio playback failed:', err));

        setTimeout(() => {
            audio.pause();
            audio.currentTime = 0; // reset
        }, 10000); // 10 seconds
    }

    // Mark notifications as read when clicked
    $('.notification-item').click(function(e){
        const notifId = $(this).data('id');
        const $item = $(this);

        $.ajax({
            url: 'mark_notification_read.php',
            type: 'POST',
            data: { notification_id: notifId, user_id: <?= $user_id ?> },
            success: function(response) {
                $item.removeClass('unread');
                const badge = document.getElementById('notifCount');
                if (badge && parseInt(badge.innerText) > 0) {
                    badge.innerText = parseInt(badge.innerText) - 1;
                    if (parseInt(badge.innerText) === 0) badge.remove();
                }
            },
            error: function() { console.log('Error marking notification as read'); }
        });
    });
});
</script>
