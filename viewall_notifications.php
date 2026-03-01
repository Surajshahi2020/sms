<?php
include 'includes/authentication.php';
include 'supporter/permissions.php';
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'includes/script.php';

// Logged-in user ID
$user_id = $_SESSION['auth_user']['user_id'] ?? null;

// Filter and search setup
$filter = $_GET['filter'] ?? 'all';
$search = trim($_GET['search'] ?? '');

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Base query
$base_query = "
    FROM notifications n
    LEFT JOIN notification_reads nr 
        ON n.id = nr.notification_id AND nr.user_id = ?
    WHERE 1=1
";

// Filter conditions
if ($filter === 'read') $base_query .= " AND nr.is_read = 1";
elseif ($filter === 'unread') $base_query .= " AND (nr.is_read = 0 OR nr.is_read IS NULL)";

// Search condition
if ($search !== '') {
    $base_query .= " AND (n.title LIKE ? OR n.message LIKE ?)";
    $search_param = "%" . $search . "%";
}

// Count total notifications
$count_query = "SELECT COUNT(*) AS total " . $base_query;
$stmt = $con->prepare($count_query);
if ($search !== '') $stmt->bind_param("iss", $user_id, $search_param, $search_param);
else $stmt->bind_param("i", $user_id);
$stmt->execute();
$total_result = $stmt->get_result()->fetch_assoc();
$total_notifications = $total_result['total'];
$total_pages = ceil($total_notifications / $limit);
$stmt->close();

// Fetch notifications
$query = "
    SELECT n.id, n.title, n.message, n.type, n.file_path, n.created_at,
           COALESCE(nr.is_read, 0) AS is_read
    " . $base_query . "
    ORDER BY n.created_at DESC
    LIMIT ? OFFSET ?
";

$stmt = $con->prepare($query);
if ($search !== '') $stmt->bind_param("issii", $user_id, $search_param, $search_param, $limit, $offset);
else $stmt->bind_param("iii", $user_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="content-wrapper">
    <div class="content-header">
  <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap gap-2">
      
      <!-- Filter dropdown on the left -->
      <form method="GET" class="d-flex align-items-center gap-2">
          <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All</option>
              <option value="read" <?= $filter === 'read' ? 'selected' : '' ?>>Read</option>
              <option value="unread" <?= $filter === 'unread' ? 'selected' : '' ?>>Unread</option>
          </select>
      </form>

       <div class="col-sm-6">
        <h1 class="m-0 text-dark fw-bold">📢सूचनाहरू</h1>
      </div>

      <!-- Search bar on the right -->
      <form method="GET" class="d-flex align-items-center gap-2">
          <!-- Keep filter value when searching -->
          <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
          <input type="text" name="search" class="form-control form-control-sm" 
                 placeholder="🔍 Search notifications..." 
                 value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                 style="min-width: 200px; border-radius: 6px;">
          <button type="submit" class="btn btn-primary btn-sm" style="border-radius:6px;">Search</button>
      </form>
  </div>
</div>


    <section class="content">
        <div class="container mt-4">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): 
                    $badgeColor = match(strtolower($row['type'])) {
                        'info' => 'info',
                        'alert' => 'warning',
                        'error' => 'danger',
                        'success' => 'success',
                        default => 'secondary'
                    };
                    $createdAt = date('d M Y, h:i A', strtotime($row['created_at']));
                    $unreadClass = ($row['is_read'] == 0) ? 'unread' : '';
                ?>
                    <div class="notification-card mb-4 <?= $unreadClass ?>" data-id="<?= $row['id'] ?>">
                        <div class="notification-header p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="notif-title">📢 <?= htmlspecialchars($row['title']) ?></strong>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="notif-time"><i class="far fa-clock"></i> <?= $createdAt ?></small>
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-<?= $badgeColor ?>"><?= ucfirst($row['type']) ?></span>
                                <span class="badge <?= $row['is_read'] ? 'bg-success' : 'bg-warning text-dark' ?>">
                                    <?= $row['is_read'] ? 'Read' : 'Unread' ?>
                                </span>
                            </div>
                        </div>

                        <div class="notification-body p-3 collapsible-content">
                            <p class="notif-message-all mb-2"><?= nl2br(strip_tags($row['message'])) ?></p>
                            <?php if(!empty($row['file_path'])): ?>
                                <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank" class="notif-file-link">📎 View Attachment</a>
                            <?php endif; ?>

                            <!-- Comments Section -->
                            <div class="comments mt-3">
                                <h6>Comments</h6>
                                <?php
                                $comment_query = $con->prepare("
                                    SELECT 
                                        c.comment, 
                                        u.full_name_en AS username, 
                                        un.name_nepali AS unit_name,
                                        c.created_at 
                                    FROM notification_comments c
                                    JOIN users u ON c.user_id = u.id
                                    LEFT JOIN units un ON u.unit_id = un.unit_id
                                    WHERE c.notification_id = ?
                                    ORDER BY c.created_at ASC
                                ");
                                $comment_query->bind_param("i", $row['id']);
                                $comment_query->execute();
                                $comments = $comment_query->get_result();
                                ?>

                                <div class="comment-list mb-2" style="max-height: 200px; overflow-y: auto; padding: 6px; background: #fff; border-radius: 10px;">
                                    <?php if ($comments->num_rows > 0): 
                                        $colors = ['#f9f9f9', '#f0f4ff', '#fef9f0', '#e8f5e9']; $i = 0;
                                        while ($c = $comments->fetch_assoc()):
                                    ?>
                                        <div class="comment mb-2 p-2"
                                            style="background: <?= $colors[$i % count($colors)] ?>; border-radius: 8px; font-size:0.82rem; line-height:1.2; box-shadow: 0 1px 2px rgba(0,0,0,0.08);">
                                            <strong>📢 <?= htmlspecialchars($c['username']) ?></strong>
                                            <?php if(!empty($c['unit_name'])): ?>
                                                <div class="text-muted" style="font-size:0.68rem;"><?= htmlspecialchars($c['unit_name']) ?></div>
                                            <?php endif; ?>
                                            <span><?= nl2br(htmlspecialchars($c['comment'])) ?></span>
                                            <div class="text-muted" style="font-size:0.68rem;"><?= date('d M Y, h:i A', strtotime($c['created_at'])) ?></div>
                                        </div>
                                    <?php $i++; endwhile; else: ?>
                                        <div class="text-muted">No comments yet.</div>
                                    <?php endif; ?>
                                </div>

                                <!-- Add Comment -->
                                <form class="add-comment-form">
                                    <input type="hidden" name="notification_id" value="<?= $row['id'] ?>">
                                    <div class="d-flex gap-2 mt-2">
                                        <input type="text" name="comment" class="form-control flex-grow-1" placeholder="Add a comment..." required>
                                        <button type="submit" class="btn btn-primary">Comment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class='text-center py-5 text-muted fs-5'>No notifications found.</div>
            <?php endif; ?>

            <!-- Pagination -->
            <?php if($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-4">
                        <?php if($page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?filter=<?= $filter ?>&search=<?= urlencode($search) ?>&page=<?= $page-1 ?>">Prev</a></li>
                        <?php endif; ?>
                        <?php for($p = 1; $p <= $total_pages; $p++): ?>
                            <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?filter=<?= $filter ?>&search=<?= urlencode($search) ?>&page=<?= $p ?>"><?= $p ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if($page < $total_pages): ?>
                            <li class="page-item"><a class="page-link" href="?filter=<?= $filter ?>&search=<?= urlencode($search) ?>&page=<?= $page+1 ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>

<style>
/* Notification Styles */
.notification-card {
    background: linear-gradient(145deg, #ffffff, #f0f4ff);
    border-left: 6px solid transparent;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    overflow: hidden;
    cursor: pointer;
    transition: all 0.35s ease;
}
.notification-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
.notification-card.active { border-left: 6px solid #6c63ff; }
.notification-card.unread { background-color: #fffbe6; }
.notification-header { background: rgba(245,245,255,0.95); border-bottom: 1px solid #e0e0e0; }
.notif-title { font-weight: 700; font-size: 1.1rem; color: #2d3e50; }
.notif-time { color: #888; font-weight: 500; font-size: 0.85rem; }
.notification-body { display: none; background: #fdfdfd; border-top: 1px solid #e0e0e0; padding-top: 10px; }
.notif-file-link { text-decoration: none; color: #6c63ff; font-weight: 600; }
.notif-file-link:hover { color: #4536e0; text-decoration: none; }
.toggle-icon { transition: transform 0.3s ease; }
.notification-card.active .toggle-icon { transform: rotate(180deg); }
</style>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function(){
    $('.notification-card').click(function(e){
        // Ignore clicks inside comment form
        if($(e.target).closest('.add-comment-form').length > 0) return;

        const card = $(this);
        const body = card.find('.collapsible-content');
        const icon = card.find('.toggle-icon');
        const notifId = card.data('id');

        // Close other notifications
        $('.collapsible-content').not(body).slideUp(400);
        $('.notification-card').not(card).removeClass('active');

        // Toggle this notification
        body.stop(true,true).slideToggle(400);
        card.toggleClass('active');
        icon.toggleClass('rotated');

        // --- Optimistic UI update: mark as read immediately ---
        if(card.hasClass('unread') && card.hasClass('active')){
            card.removeClass('unread');
            const badge = card.find('.badge.bg-warning');
            if(badge.length){
                badge.removeClass('bg-warning text-dark')
                     .addClass('bg-success')
                     .text('Read');
            }

            // Send AJAX to backend in the background
            $.post('mark_notification_read.php', { notification_id: notifId }, function(response){
                try {
                    const res = JSON.parse(response);
                    if(res.status !== 'success'){
                        console.error('Failed to mark read:', res.message);
                        // Optional: revert UI if needed
                        // card.addClass('unread');
                        // badge.removeClass('bg-success').addClass('bg-warning text-dark').text('Unread');
                    }
                } catch(e){
                    console.error('Invalid JSON response:', response);
                }
            });
        }
    });

    // --- Add comment handler ---
    $('.add-comment-form').submit(function(e){
        e.preventDefault();
        const form = $(this);
        const notifId = form.find('input[name="notification_id"]').val();
        const comment = form.find('input[name="comment"]').val().trim();
        if(comment === '') return;

        $.ajax({
            url: 'add_notification_comment.php',
            type: 'POST',
            data: { notification_id: notifId, user_id: <?= $user_id ?>, comment: comment },
            success: function(){
                const commentHtml = `
                    <div class="comment p-2 mb-1" style="background:#f9f9f9; border-radius:6px;">
                        <strong>You:</strong> ${comment}
                        <div class="text-muted" style="font-size:0.7rem;">Just now</div>
                    </div>`;
                form.closest('.comments').find('.comment-list').append(commentHtml);
                form.find('input[name="comment"]').val('');
            }
        });
    });
});
</script>

