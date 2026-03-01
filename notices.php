<?php
include 'includes/authentication.php';
include 'supporter/permissions.php';
if (!is_super_admin()) {
    include 'supporter/access_denied.php';
    exit();
}
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'config/dbcon.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <h4>Notifications List</h4>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <?php include 'message.php'; ?>
          <div class="card">
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>SN</th>
                    <th>शीर्षक</th>
                    <th>सन्देश</th>
                    <th>प्रकार</th>
                    <th>फाइल</th>
                    <th>सिर्जना मिति</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $count = 0;
                  $query = "SELECT id, title, message, type, file_path, created_at FROM notifications ORDER BY created_at DESC";
                  $query_run = mysqli_query($con, $query);

                  if(mysqli_num_rows($query_run) > 0) {
                      while($row = mysqli_fetch_assoc($query_run)) {
                  ?>
                  <tr>
                    <td><?= ++$count ?></td>
                    <td class="word-wrap">
                        <?= htmlspecialchars(strip_tags($row['title']), ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td class="word-wrap">
                        <?= htmlspecialchars(strip_tags($row['message']), ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td><?= htmlspecialchars($row['type'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <?php
                        if(!empty($row['file_path'])) {
                            echo '<a href="'.htmlspecialchars($row['file_path'],ENT_QUOTES).'" target="_blank" style="text-decoration:none; color:blue;">View</a>';
                        } else {
                            echo 'None';
                        }
                        ?>
                    </td>

                    <td>
                      <?php 
                        $date = new DateTime($row['created_at']);
                        echo htmlspecialchars($date->format('Y-m-d H:i'), ENT_QUOTES,'UTF-8');
                      ?>
                    </td>
                    <td>
                      <button type="button" 
                              class="btn btn-primary btn-sm edit-btn" 
                              data-id="<?= $row['id'] ?>" 
                              data-title="<?= htmlspecialchars(strip_tags($row['title']), ENT_QUOTES) ?>" 
                              data-message="<?= htmlspecialchars(strip_tags($row['message']), ENT_QUOTES) ?>" 
                              data-type="<?= htmlspecialchars($row['type'], ENT_QUOTES) ?>" 
                              data-file="<?= htmlspecialchars($row['file_path'], ENT_QUOTES) ?>" 
                              data-bs-toggle="modal" data-bs-target="#editNotificationModal">
                          Edit
                      </button>
                    </td>
                     <td>
                      <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('Are you sure?')) window.location.href='notifications_code.php?delete_id=<?= $row['id'] ?>'">Delete</button>
                    </td>
                  </tr>
                  <?php
                      }
                  } else {
                      echo '<tr><td colspan="6" class="text-center">No Notifications Found</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Edit Notification Modal -->
<div class="modal fade" id="editNotificationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="editNotificationForm" method="POST" enctype="multipart/form-data" action="notifications_code.php">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Notification</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="edit_id" id="edit-id">

          <div class="mb-3">
            <label>शीर्षक</label>
            <input type="text" name="title" id="edit-title" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Message</label>
            <div id="edit-editor" style="height: 200px;"></div>
            <input type="hidden" name="message" id="edit-message" required>
          </div>

          <div class="mb-3">
            <label>Type</label>
            <select name="type" id="edit-type" class="form-control" required>
              <option value="info">Info</option>
              <option value="success">Success</option>
              <option value="warning">Warning</option>
              <option value="danger">Danger</option>
            </select>
          </div>

          <div class="mb-3">
            <label>Banner / Image / Video (optional)</label>
            <input type="file" name="banner" class="form-control">
            <div id="current-file" class="mt-2"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Notification</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>

<!-- Quill Editor -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var editQuill = new Quill('#edit-editor', { theme: 'snow' });

    document.querySelectorAll('.edit-btn').forEach(function(btn){
        btn.addEventListener('click', function(){
            document.getElementById('edit-id').value = this.dataset.id;
            document.getElementById('edit-title').value = this.dataset.title;
            document.getElementById('edit-type').value = this.dataset.type;
            editQuill.root.innerHTML = this.dataset.message;
            
            if(this.dataset.file){
                document.getElementById('current-file').innerHTML = 'Current file: <a href="'+this.dataset.file+'" target="_blank"  style="text-decoration:none; color:blue;">View</a>';
            } else {
                document.getElementById('current-file').innerHTML = '';
            }
        });
    });

    // Copy Quill content to hidden input on form submit
    document.getElementById('editNotificationForm').addEventListener('submit', function(){
        document.getElementById('edit-message').value = editQuill.root.innerHTML;
    });
});
</script>
